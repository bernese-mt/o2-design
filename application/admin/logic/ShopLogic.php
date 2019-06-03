<?php
/**
 * 易優CMS
 * ============================================================================
 * 版權所有 2016-2028 海南贊贊網路科技有限公司，並保留所有權利。
 * 網站地址: http://www.eyoucms.com
 * ----------------------------------------------------------------------------
 * 如果商業用途務必到官方購買正版授權, 以免引起不必要的法律糾紛.
 * ============================================================================
 * Author: 小虎哥 <1105415366@qq.com>
 * Date: 2018-4-3
 */

namespace app\admin\logic;

use think\Model;
use think\Db;

/**
 * 邏輯定義
 * Class CatsLogic
 * @package admin\Logic
 */
class ShopLogic extends Model
{
    private $request = null;
    private $data_path;
    private $version_txt_path;
    private $version;    
    private $service_url;
    private $upgrade_url;
    private $service_ey;
    private $planPath_pc;
    private $planPath_m;

    /**
     * 解構函式
     */
    function  __construct() {
        $this->request = request();
        $this->service_ey = config('service_ey');
        $this->data_path = DATA_PATH; // 
        $this->version_txt_path = $this->data_path.'conf'.DS.'version_themeshop.txt'; // 版本檔案路徑
        $this->version = getVersion('version_themeshop');
        // api_Service_checkVersion
        $tmp_str = 'L2luZGV4LnBocD9tPWFwaSZjPVVwZ3JhZGUmYT1jaGVja1RoZW1lVmVyc2lvbg==';
        $this->service_url = base64_decode($this->service_ey).base64_decode($tmp_str);
        $this->upgrade_url = $this->service_url . '&domain='.request()->host(true).'&v=' . $this->version.'&type=theme_shop';
        $this->planPath_pc = 'template/pc/';
        $this->planPath_m = 'template/mobile/';
    }

    /**
     * 檢測並第一次從官方同步訂單中心的前臺模板
     */
    public function syn_theme_shop()
    {
        error_reporting(0);//關閉所有錯誤報告
        if ('v1.0.1' > $this->version) {
            return $this->OneKeyUpgrade();
        } else {
            return true;
        }
    }

    /**
     * 檢測目錄許可權
     */
    public function checkAuthority($filelist = '')
    {
        /*------------------檢測目錄讀寫許可權----------------------*/
        $filelist = htmlspecialchars_decode($filelist);
        $filelist = explode('<br>', $filelist);

        $dirs = array();
        $i = -1;
        foreach($filelist as $filename)
        {
            if (stristr($filename, $this->planPath_pc) && !file_exists($this->planPath_pc)) {
                continue;
            } else if (stristr($filename, $this->planPath_m) && !file_exists($this->planPath_m)) {
                continue;
            }

            $tfilename = $filename;
            $curdir = $this->GetDirName($tfilename);
            if (empty($curdir)) {
                continue;
            }
            if( !isset($dirs[$curdir]) ) 
            {
                $dirs[$curdir] = $this->TestIsFileDir($curdir);
            }
            if($dirs[$curdir]['isdir'] == FALSE) 
            {
                continue;
            }
            else {
                $dirs[$curdir] = $this->TestIsFileDir($curdir);
            }
            $i++;
        }

        $is_pass = true;
        $msg = '檢測通過';
        if($i > -1)
        {
            $n = 0;
            $dirinfos = '';
            foreach($dirs as $curdir)
            {
                $dirinfos .= $curdir['name']."&nbsp;&nbsp;狀態：";
                if ($curdir['writeable']) {
                    $dirinfos .= "[√正常]";
                } else {
                    $is_pass = false;
                    $n++;
                    $dirinfos .= "<font color='red'>[×不可寫]</font>";
                }
                $dirinfos .= "<br />";
            }
            $title = "本次升級需要在下面資料夾寫入更新檔案，已檢測站點有 <font color='red'>{$n}</font> 處沒有寫入許可權：<br />";
            $title .= "<font color='red'>問題分析（如有問題，請諮詢技術支援）：<br />";
            $title .= "1、檢查站點目錄的使用者組與所有者，禁止是 root ;<br />";
            $title .= "2、檢查站點目錄的讀寫許可權，一般許可權值是 0755 ;<br />";
            $title .= "</font>涉及更新目錄列表如下：<br />";
            $msg = $title . $dirinfos;
        }
        /*------------------end----------------------*/

        if (true === $is_pass) {
            return ['code'=>1, 'msg'=>$msg];
        } else {
            return ['code'=>0, 'msg'=>$msg, 'data'=>['code'=>1]];
        }
    }

    /**
     * 檢查是否有更新包
     * @return type 提示語
     */
    public function checkVersion() {
        //error_reporting(0);//關閉所有錯誤報告     
        $allow_url_fopen = ini_get('allow_url_fopen');
        if (!$allow_url_fopen) {
            return ['code' => 1, 'msg' => "<font color='red'>請聯繫空間商（設定 php.ini 中參數 allow_url_fopen = 1）</font>"];
        }

        $url = $this->upgrade_url; 
        $context = stream_context_set_default(array('http' => array('timeout' => 3,'method'=>'GET')));
        $serviceVersionList = @file_get_contents($url,false,$context);    
        $serviceVersionList = json_decode($serviceVersionList,true);
        if(!empty($serviceVersionList))
        {
            $upgradeArr = array();
            $introStr = '';
            $upgradeStr = '';
            foreach ($serviceVersionList as $key => $val) {
                $upgrade = !empty($val['upgrade']) ? $val['upgrade'] : array();
                $upgradeArr = array_merge($upgradeArr, $upgrade);
                $introStr .= '<br>'.filter_line_return($val['intro'], '<br>');
            }
            $upgradeArr = array_unique($upgradeArr);
            foreach ($upgradeArr as $key => $val) {
                if (stristr($val, $this->planPath_pc) && !file_exists($this->planPath_pc)) {
                    unset($upgradeArr[$key]);
                } else if (stristr($val, $this->planPath_m) && !file_exists($this->planPath_m)) {
                    unset($upgradeArr[$key]);
                }
            }
            $upgradeStr = implode('<br>', $upgradeArr); // 升級提示需要覆蓋哪些檔案

            $introArr = explode('<br>', $introStr);
            $introStr = '更新日誌：';
            foreach ($introArr as $key => $val) {
                if (empty($val)) {
                    continue;
                }
                $introStr .= "<br>{$key}、".$val;
            }

            $lastupgrade = $serviceVersionList[count($serviceVersionList) - 1];
            if (!empty($lastupgrade['upgrade_title'])) {
                $introStr .= '<br>'.$lastupgrade['upgrade_title'];
            }
            $lastupgrade['intro'] = htmlspecialchars_decode($introStr);
            $lastupgrade['upgrade'] = htmlspecialchars_decode($upgradeStr); // 升級提示需要覆蓋哪些檔案
            /*升級公告*/
            if (!empty($lastupgrade['notice'])) {
                $lastupgrade['notice'] = htmlspecialchars_decode($lastupgrade['notice']) . '<br>';
            }
            /*--end*/

            return ['code' => 2, 'msg' => $lastupgrade];
        }
        return ['code' => 1, 'msg' => '已是最新版'];
    }

    /**
     * 檢查是否有更新包
     * @return type 提示語
     */
    public function OneKeyUpgrade() {
        $allow_url_fopen = ini_get('allow_url_fopen');
        if (!$allow_url_fopen) {
            return ['code' => 0, 'msg' => "請聯繫空間商，設定 php.ini 中參數 allow_url_fopen = 1"];
        }     
               
        if (!extension_loaded('zip')) {
            return ['code' => 0, 'msg' => "請聯繫空間商，開啟 php.ini 中的php-zip擴充套件"];
        }

        $serviceVersionList = @file_get_contents($this->upgrade_url);
        $serviceVersionList = json_decode($serviceVersionList,true);
        if (empty($serviceVersionList)) {
            if ('v1.0.1' > $this->version) {
                return ['code' => 0, 'msg' => "請求伺服器失敗，請檢查是否網路故障！"];
            } else {
                return ['code' => 0, 'msg' => "沒找到升級資訊"];
            }
        }
        
        clearstatcache(); // 清除資料夾許可權快取
        if (!is_writeable($this->version_txt_path)) {
            return ['code' => 0, 'msg' => '檔案'.$this->version_txt_path.' 不可寫，不能升級!!!'];
        }
        /*最新更新版本資訊*/
        $lastServiceVersion = $serviceVersionList[count($serviceVersionList) - 1];
        /*--end*/
        /*批量下載更新包*/
        $upgradeArr = array(); // 更新的檔案列表
        $folderName = 'shop-'.$lastServiceVersion['key_num'];
        foreach ($serviceVersionList as $key => $val) {
            // 下載更新包
            $result = $this->downloadFile($val['down_url'], $val['file_md5']);
            if (!isset($result['code']) || $result['code'] != 1) {
                return $result;
            }

            /*第一個循環執行的業務*/
            if ($key == 0) {
                /*解壓到最後一個更新包的資料夾*/
                $lastDownFileName = explode('/', $lastServiceVersion['down_url']);    
                $lastDownFileName = end($lastDownFileName);
                $folderName = 'shop-'.str_replace(".zip", "", $lastDownFileName);  // 資料夾
                /*--end*/

                /*解壓之前，刪除已重複的資料夾*/
                delFile($this->data_path.'backup'.DS.'theme'.DS.$folderName);
                /*--end*/
            }
            /*--end*/

            $downFileName = explode('/', $val['down_url']);    
            $downFileName = 'shop-'.end($downFileName);

            /*解壓檔案*/
            $zip = new \ZipArchive();//新建一個ZipArchive的對象
            if ($zip->open($this->data_path.'backup'.DS.'theme'.DS.$downFileName) != true) {
                return ['code' => 0, 'msg' => "升級包讀取失敗!"];
            }
            $zip->extractTo($this->data_path.'backup'.DS.'theme'.DS.$folderName.DS);//假設解壓縮到在目前路徑下backup資料夾內
            $zip->close();//關閉處理的zip檔案
            /*--end*/
            
            if (!file_exists($this->data_path.'backup'.DS.'theme'.DS.$folderName.DS.'data'.DS.'conf'.DS.'version_themeshop.txt')) {
                return ['code' => 0, 'msg' => "缺少version_themeshop.txt檔案，請聯繫客服"];
            }

            /*更新的檔案列表*/
            $upgrade = !empty($val['upgrade']) ? $val['upgrade'] : array();
            $upgradeArr = array_merge($upgradeArr, $upgrade);
            /*--end*/
        }
        /*--end*/

        /*將多個更新包重新組建一個新的完全更新包*/
        $upgradeArr = array_unique($upgradeArr); // 移除檔案列表里重複的檔案
        $serviceVersion = $lastServiceVersion;
        $serviceVersion['upgrade'] = $upgradeArr;
        /*--end*/

        /*升級之前，備份涉及的原始檔*/
        $upgrade = $serviceVersion['upgrade'];
        if (!empty($upgrade) && is_array($upgrade)) {
            foreach ($upgrade as $key => $val) {
                $source_file = ROOT_PATH.$val;
                if (file_exists($source_file)) {
                    $destination_file = $this->data_path.'backup'.DS.'theme'.DS.$folderName.'_www'.DS.$val;
                    tp_mkdir(dirname($destination_file));
                    $copy_bool = @copy($source_file, $destination_file);
                    if (false == $copy_bool) {
                        return ['code' => 0, 'msg' => "更新前備份檔案失敗，請檢查所有目錄是否有讀寫許可權"];
                    }
                }
            }
        }
        /*--end*/

        // 遞迴複製資料夾
        $copy_data = $this->recurse_copy($this->data_path.'backup'.DS.'theme'.DS.$folderName, rtrim(ROOT_PATH, DS), $folderName);

        /*刪除下載的升級包*/
        $ziplist = glob($this->data_path.'backup'.DS.'theme'.DS.'shop-*.zip');
        @array_map('unlink', $ziplist);
        /*--end*/
        
        // 推送回伺服器  記錄升級成功
        $this->UpgradeLog($serviceVersion['key_num']);
        
        return ['code' => $copy_data['code'], 'msg' => "升級模板成功{$copy_data['msg']}"];
    }

    /**
     * 自定義函式遞迴的複製帶有多級子目錄的目錄
     * 遞迴複製資料夾
     *
     * @param string $src 原目錄
     * @param string $dst 複製到的目錄
     * @param string $folderName 存放升級包目錄名稱
     * @return string
     */                        
    //參數說明：            
    //自定義函式遞迴的複製帶有多級子目錄的目錄
    private function recurse_copy($src, $dst, $folderName)
    {
        static $badcp = 0; // 累計覆蓋失敗的檔案總數
        static $n = 0; // 累計執行覆蓋的檔案總數
        static $total = 0; // 累計更新的檔案總數

        $dir = opendir($src);

        /*pc和mobile目錄存在的情況下，才拷貝會員模板到相應的pc或mobile里*/
        $dst_tmp = str_replace('\\', '/', $dst);
        $dst_tmp = rtrim($dst_tmp, '/').'/';
        if (stristr($dst_tmp, $this->planPath_pc) && file_exists($this->planPath_pc)) {
            tp_mkdir($dst);
        } else if (stristr($dst_tmp, $this->planPath_m) && file_exists($this->planPath_m)) {
            tp_mkdir($dst);
        }
        /*--end*/

        while (false !== $file = readdir($dir)) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    $this->recurse_copy($src . '/' . $file, $dst . '/' . $file, $folderName);
                }
                else {
                    if (file_exists($src . DIRECTORY_SEPARATOR . $file)) {
                        /*pc和mobile目錄存在的情況下，才拷貝會員模板到相應的pc或mobile里*/
                        $rs = true;
                        $src_tmp = str_replace('\\', '/', $src . DIRECTORY_SEPARATOR . $file);
                        if (stristr($src_tmp, $this->planPath_pc) && !file_exists($this->planPath_pc)) {
                            continue;
                        } else if (stristr($src_tmp, $this->planPath_m) && !file_exists($this->planPath_m)) {
                            continue;
                        }
                        /*--end*/
                        $rs = @copy($src . DIRECTORY_SEPARATOR . $file, $dst . DIRECTORY_SEPARATOR . $file);
                        if($rs) {
                            $n++;
                            @unlink($src . DIRECTORY_SEPARATOR . $file);
                        } else {
                            $n++;
                            $badcp++;
                        }
                    } else {
                        $n++;
                    }
                    $total++;
                }
            }
        }
        closedir($dir);

        $code = 1;
        $msg = '！';
        if($badcp > 0)
        {
            $code = 2;
            $msg = "，其中失敗 <font color='red'>{$badcp}</font> 個檔案，<br />請從升級包目錄[<font color='red'>data/backup/theme/{$folderName}</font>]中的取出全部檔案覆蓋到根目錄，完成手工升級。";
        }

        $this->copy_speed($n, $total);

        return ['code'=>$code, 'msg'=>$msg];
    }

    /**
     * 複製檔案進度
     */
    private function copy_speed($n, $total)
    {
        $data = false;

        if ($n < $total) {
            $this->copy_speed($n, $total);
        } else {
            $data = true;
        }
        
        return $data;
    }
 
    /**     
     * @param type $fileUrl 下載檔案地址
     * @param type $md5File 檔案MD5 加密值 用於對比下載是否完整
     * @return string 錯誤或成功提示
     */
    private function downloadFile($fileUrl,$md5File)
    {                    
        $downFileName = explode('/', $fileUrl);    
        $downFileName = 'shop-'.end($downFileName);
        $saveDir = $this->data_path.'backup'.DS.'theme'.DS.$downFileName; // 儲存目錄
        tp_mkdir(dirname($saveDir));
        if(!file_get_contents($fileUrl, 0, null, 0, 1)){
            return ['code' => 0, 'msg' => '官方升級包不存在']; // 檔案存在直接退出
        }
        $ch = curl_init($fileUrl);            
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
        $file = curl_exec ($ch);
        curl_close ($ch);
        $fp = fopen($saveDir,'w');
        fwrite($fp, $file);
        fclose($fp);
        if(!eyPreventShell($saveDir) || !file_exists($saveDir) || $md5File != md5_file($saveDir))
        {
            return ['code' => 0, 'msg' => '下載儲存升級包失敗，請檢查所有目錄的許可權以及使用者組不能為root'];
        }
        return ['code' => 1, 'msg' => '下載成功'];
    }                
    
    // 升級記錄 log 日誌
    private  function UpgradeLog($to_key_num){
        $serial_number = DEFAULT_SERIALNUMBER;

        $constsant_path = APP_PATH.MODULE_NAME.'/conf/constant.php';
        if (file_exists($constsant_path)) {
            require_once($constsant_path);
            defined('SERIALNUMBER') && $serial_number = SERIALNUMBER;
        }
        $mysqlinfo = \think\Db::query("SELECT VERSION() as version");
        $mysql_version  = $mysqlinfo[0]['version'];
        $vaules = array(
            'type'  => 'theme_shop',
            'domain'=>$_SERVER['HTTP_HOST'], //使用者域名                
            'key_num'=>$this->version, // 使用者版本號
            'to_key_num'=>$to_key_num, // 使用者要升級的版本號                
            'add_time'=>time(), // 升級時間
            'serial_number'=>$serial_number,
            'ip'    => GetHostByName($_SERVER['SERVER_NAME']),
            'phpv'  => phpversion(),
            'mysql_version' => $mysql_version,
            'web_server'    => $_SERVER['SERVER_SOFTWARE'],
        );
        // api_Service_upgradeLog
        $tmp_str = 'L2luZGV4LnBocD9tPWFwaSZjPVVwZ3JhZGUmYT11cGdyYWRlTG9nJg==';
        $url = base64_decode($this->service_ey).base64_decode($tmp_str).http_build_query($vaules);
        @file_get_contents($url);
    }

    /**
     * 獲取檔案的目錄路徑
     * @param string $filename 檔案路徑+檔名
     * @return string
     */
    private function GetDirName($filename)
    {
        $dirname = preg_replace("#[\\\\\/]{1,}#", '/', $filename);
        $dirname = preg_replace("#([^\/]*)$#", '', $dirname);
        return $dirname;
    }

    /**
     * 測試目錄路徑是否有讀寫許可權
     * @param string $dirname 檔案目錄路徑
     * @return array
     */
    private function TestIsFileDir($dirname)
    {
        $dirs = array('name'=>'', 'isdir'=>FALSE, 'writeable'=>FALSE);
        $dirs['name'] =  $dirname;
        tp_mkdir($dirname);
        if(is_dir($dirname))
        {
            $dirs['isdir'] = TRUE;
            $dirs['writeable'] = $this->TestWriteAble($dirname);
        }
        return $dirs;
    }

    /**
     * 測試目錄路徑是否有寫入許可權
     * @param string $d 目錄路勁
     * @return boolean
     */
    private function TestWriteAble($d)
    {
        $tfile = '_eyout.txt';
        $fp = @fopen($d.$tfile,'w');
        if(!$fp) {
            return false;
        }
        else {
            fclose($fp);
            $rs = @unlink($d.$tfile);
            return true;
        }
    }
}
