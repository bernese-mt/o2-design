<?php
/**
 * eyoucms
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
 
class UpgradeLogic extends Model
{
    public $root_path;
    public $data_path;
    public $version_txt_path;
    public $curent_version;    
    public $service_url;
    public $upgrade_url;
    public $service_ey;
    
    /**
     * 解構函式
     */
    function  __construct() {
         
        $this->service_ey = config('service_ey');
        $this->root_path = ROOT_PATH; // 
        $this->data_path = DATA_PATH; // 
        $this->version_txt_path = $this->data_path.'conf'.DS.'version.txt'; // 版本檔案路徑
        $this->curent_version = getCmsVersion();
        // api_Service_checkVersion
        $tmp_str = 'L2luZGV4LnBocD9tPWFwaSZjPVNlcnZpY2UmYT1jaGVja1ZlcnNpb24=';
        $this->service_url = base64_decode($this->service_ey).base64_decode($tmp_str);
        $this->upgrade_url = $this->service_url . '&domain='.request()->host(true).'&v=' . $this->curent_version;
    }

    /**
     * 檢查是否有更新包
     * @return type 提示語
     */
    public  function checkVersion() {
        //error_reporting(0);//關閉所有錯誤報告     
        $allow_url_fopen = ini_get('allow_url_fopen');
        if (!$allow_url_fopen) {
            return ['code' => 1, 'msg' => "<font color='red'>請聯繫空間商（設定 php.ini 中參數 allow_url_fopen = 1）</font>"];
        }

        $url = $this->upgrade_url; 
        $context = stream_context_set_default(array('http' => array('timeout' => 5,'method'=>'GET')));
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
            tpCache('system', ['system_upgrade_filelist'=>base64_encode($lastupgrade['upgrade'])]);
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
     * 一鍵更新
     */
    public function OneKeyUpgrade(){
        error_reporting(0);//關閉所有錯誤報告
        $allow_url_fopen = ini_get('allow_url_fopen');
        if (!$allow_url_fopen) {
            return ['code' => 0, 'msg' => "請聯繫空間商，設定 php.ini 中參數 allow_url_fopen = 1"];
        }     
               
        if (!extension_loaded('zip')) {
            return ['code' => 0, 'msg' => "請聯繫空間商，開啟 php.ini 中的php-zip擴充套件"];
        }

        $serviceVersionList = file_get_contents($this->upgrade_url);
        $serviceVersionList = json_decode($serviceVersionList,true);
        if (empty($serviceVersionList)) {
            return ['code' => 0, 'msg' => "沒找到升級資訊"];
        }
        
        clearstatcache(); // 清除資料夾許可權快取
        /*$quanxuan = substr(base_convert(@fileperms($this->data_path),10,8),-4);
        if(!in_array($quanxuan,array('0777','0755','0666','0662','0622','0222')))
            return "網站根目錄不可寫，無法升級.";*/
        if (!is_writeable($this->version_txt_path)) {
            return ['code' => 0, 'msg' => '檔案'.$this->version_txt_path.' 不可寫，不能升級!!!'];
        }
        /*最新更新版本資訊*/
        $lastServiceVersion = $serviceVersionList[count($serviceVersionList) - 1];
        /*--end*/
        /*批量下載更新包*/
        $upgradeArr = array(); // 更新的檔案列表
        $sqlfileArr = array(); // 更新SQL檔案列表
        $folderName = $lastServiceVersion['key_num'];
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
                $folderName = str_replace(".zip", "", $lastDownFileName);  // 資料夾
                /*--end*/

                /*解壓之前，刪除已重複的資料夾*/
                delFile($this->data_path.'backup'.DS.$folderName);
                /*--end*/
            }
            /*--end*/

            $downFileName = explode('/', $val['down_url']);    
            $downFileName = end($downFileName);

            /*解壓檔案*/
            $zip = new \ZipArchive();//新建一個ZipArchive的對象
            if ($zip->open($this->data_path.'backup'.DS.$downFileName) != true) {
                return ['code' => 0, 'msg' => "升級包讀取失敗!"];
            }
            $zip->extractTo($this->data_path.'backup'.DS.$folderName.DS);//假設解壓縮到在目前路徑下backup資料夾內
            $zip->close();//關閉處理的zip檔案
            /*--end*/
            
            if (!file_exists($this->data_path.'backup'.DS.$folderName.DS.'www'.DS.'data'.DS.'conf'.DS.'version.txt')) {
                return ['code' => 0, 'msg' => "缺少version.txt檔案，請聯繫客服"];
            }

            if (file_exists($this->data_path.'backup'.DS.$folderName.DS.'www'.DS.'application'.DS.'database.php')) {
                return ['code' => 0, 'msg' => "不得修改數據庫配置檔案，請聯繫客服"];
            }

            /*更新的檔案列表*/
            $upgrade = !empty($val['upgrade']) ? $val['upgrade'] : array();
            $upgradeArr = array_merge($upgradeArr, $upgrade);
            /*--end*/

            /*更新的SQL檔案列表*/
            $sql_file = !empty($val['sql_file']) ? $val['sql_file'] : array();
            $sqlfileArr = array_merge($sqlfileArr, $val['sql_file']);
            /*--end*/
        }
        /*--end*/

        /*將多個更新包重新組建一個新的完全更新包*/
        $upgradeArr = array_unique($upgradeArr); // 移除檔案列表里重複的檔案
        $sqlfileArr = array_unique($sqlfileArr); // 移除檔案列表里重複的檔案
        $serviceVersion = $lastServiceVersion;
        $serviceVersion['upgrade'] = $upgradeArr;
        $serviceVersion['sql_file'] = $sqlfileArr;
        /*--end*/

        /*升級之前，備份涉及的原始檔*/
        $upgrade = $serviceVersion['upgrade'];
        if (!empty($upgrade) && is_array($upgrade)) {
            foreach ($upgrade as $key => $val) {
                $source_file = $this->root_path.$val;
                if (file_exists($source_file)) {
                    $destination_file = $this->data_path.'backup'.DS.$this->curent_version.'_www'.DS.$val;
                    tp_mkdir(dirname($destination_file));
                    $copy_bool = @copy($source_file, $destination_file);
                    if (false == $copy_bool) {
                        return ['code' => 0, 'msg' => "更新前備份檔案失敗，請檢查所有目錄是否有讀寫許可權"];
                    }
                }
            }
        }
        /*--end*/

        /*升級的 sql檔案*/
        if(!empty($serviceVersion['sql_file']))
        {
            foreach($serviceVersion['sql_file'] as $key => $val)
            {
                //讀取數據檔案
                $sqlpath = $this->data_path.'backup'.DS.$folderName.DS.'sql'.DS.trim($val);
                $execute_sql = file_get_contents($sqlpath);
                $sqlFormat = $this->sql_split($execute_sql, PREFIX);
                /**
                 * 執行SQL語句
                 */
                try {
                    $counts = count($sqlFormat);

                    for ($i = 0; $i < $counts; $i++) {
                        $sql = trim($sqlFormat[$i]);

                        if (stristr($sql, 'CREATE TABLE')) {
                            Db::execute($sql);
                        } else {
                            if(trim($sql) == '')
                               continue;
                            Db::execute($sql);
                        }
                    }
                } catch (\Exception $e) {
                    return ['code' => 0, 'msg' => "數據庫執行中途失敗，請第一時間請求技術支援，否則將影響後續的版本升級！"];
                }
            }
        }
        /*--end*/

        // 遞迴複製資料夾
        $copy_data = $this->recurse_copy($this->data_path.'backup'.DS.$folderName.DS.'www', rtrim($this->root_path, DS), $folderName);

        /*覆蓋自定義後臺入口檔案*/
        $login_php = 'login.php';
        $rootLoginFile = $this->data_path.'backup'.DS.$folderName.DS.'www'.DS.$login_php;
        if (file_exists($rootLoginFile)) {
            $adminbasefile = preg_replace('/^(.*)\/([^\/]+)$/i', '$2', request()->baseFile());
            if ($login_php != $adminbasefile && is_writable($this->root_path.$adminbasefile)) {
                if (!@copy($rootLoginFile, $this->root_path.$adminbasefile)) {
                    return ['code' => 0, 'msg' => "更新入口檔案失敗，請第一時間請求技術支援，否則將影響部分功能的使用！"];
                }
                @unlink($this->root_path.$login_php);
            } 
        }
        /*--end*/

        /*多語言*/
        if (is_language()) {
            $langRow = \think\Db::name('language')->order('id asc')
                ->select();
            foreach ($langRow as $key => $val) {
                tpCache('system',['system_version'=>$serviceVersion['key_num']], $val['mark']); // 記錄版本號
            }
        } else { // 單語言
            tpCache('system',['system_version'=>$serviceVersion['key_num']]); // 記錄版本號
        }
        /*--end*/

        // 清空快取
        delFile(rtrim(RUNTIME_PATH, '/'));
        tpCache('global');

        /*刪除下載的升級包*/
        $ziplist = glob($this->data_path.'backup'.DS.'*.zip');
        @array_map('unlink', $ziplist);
        /*--end*/
        
        // 推送回伺服器  記錄升級成功
        $this->UpgradeLog($serviceVersion['key_num']);
        
        return ['code' => $copy_data['code'], 'msg' => "升級成功{$copy_data['msg']}"];
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
        tp_mkdir($dst);
        while (false !== $file = readdir($dir)) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    $this->recurse_copy($src . '/' . $file, $dst . '/' . $file, $folderName);
                }
                else {
                    if (file_exists($src . DIRECTORY_SEPARATOR . $file)) {
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
            $msg = "，其中失敗 <font color='red'>{$badcp}</font> 個檔案，<br />請從升級包目錄[<font color='red'>data/backup/{$folderName}/www</font>]中的取出全部檔案覆蓋到根目錄，完成手工升級。";
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

    private function sql_split($sql, $tablepre) {

        if ($tablepre != "ey_")
            $sql = str_replace("`ey_", '`'.$tablepre, $sql);
              
        $sql = preg_replace("/TYPE=(InnoDB|MyISAM|MEMORY)( DEFAULT CHARSET=[^; ]+)?/", "ENGINE=\\1 DEFAULT CHARSET=utf8", $sql);
        
        $sql = str_replace("\r", "\n", $sql);
        $ret = array();
        $num = 0;
        $queriesarray = explode(";\n", trim($sql));
        unset($sql);
        foreach ($queriesarray as $query) {
            $ret[$num] = '';
            $queries = explode("\n", trim($query));
            $queries = array_filter($queries);
            foreach ($queries as $query) {
                $str1 = substr($query, 0, 1);
                if ($str1 != '#' && $str1 != '-')
                    $ret[$num] .= $query;
            }
            $num++;
        }
        return $ret;
    }
 
    /**     
     * @param type $fileUrl 下載檔案地址
     * @param type $md5File 檔案MD5 加密值 用於對比下載是否完整
     * @return string 錯誤或成功提示
     */
    private function downloadFile($fileUrl,$md5File)
    {                    
        $downFileName = explode('/', $fileUrl);    
        $downFileName = end($downFileName);
        $saveDir = $this->data_path.'backup'.DS.$downFileName; // 儲存目錄
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
            'domain'=>$_SERVER['HTTP_HOST'], //使用者域名                
            'key_num'=>$this->curent_version, // 使用者版本號
            'to_key_num'=>$to_key_num, // 使用者要升級的版本號                
            'add_time'=>time(), // 升級時間
            'serial_number'=>$serial_number,
            'ip'    => GetHostByName($_SERVER['SERVER_NAME']),
            'phpv'  => phpversion(),
            'mysql_version' => $mysql_version,
            'web_server'    => $_SERVER['SERVER_SOFTWARE'],
        );
        // api_Service_upgradeLog
        $tmp_str = 'L2luZGV4LnBocD9tPWFwaSZjPVNlcnZpY2UmYT11cGdyYWRlTG9nJg==';
        $url = base64_decode($this->service_ey).base64_decode($tmp_str).http_build_query($vaules);
        @file_get_contents($url);
    }
} 
?>