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
 
class WeappLogic extends Model
{
    public $root_path;
    public $weapp_path;
    public $data_path;
    // public $config_path;
    // public $curent_version;    
    public $service_url;
    // public $upgrade_url;
    public $service_ey;
    // public $code;
    
    /**
     * 解構函式
     */
    function  __construct() {
        // $this->code = input('param.code/s', '');
        // $this->curent_version = getWeappVersion($this->code);
        $this->service_ey = config('service_ey');
        $this->root_path = ROOT_PATH; // 
        $this->weapp_path = WEAPP_DIR_NAME.DS; // 
        $this->data_path = DATA_PATH; // 
        // $this->config_path = $this->weapp_path.$this->code.DS.'config.php'; // 版本配置檔案路徑
        // api_Weapp_checkVersion
        $tmp_str = 'L2luZGV4LnBocD9tPWFwaSZjPVdlYXBwJmE9Y2hlY2tWZXJzaW9u';
        $this->service_url = base64_decode($this->service_ey).base64_decode($tmp_str).'&domain='.request()->host(true);
        // $this->upgrade_url = $this->service_url.'&code='.$this->code.'&v='.$this->curent_version;
    }

    /**
     * 更新外掛到數據庫
     * @param $weapp_list array 本地外掛陣列
     */
    public function insertWeapp(){
        $row = M('weapp')->field('id,code,config')->getAllWithIndex('code'); // 數據庫
        $new_arr = array(); // 本地
        $addData = array(); // 數據儲存變數
        $updateData = array(); // 數據儲存變數
        $weapp_list = $this->scanWeapp();
        //  本地對比數據庫
        foreach($weapp_list as $k=>$v){
            $code = isset($v['code']) ? $v['code'] : 'error_'.date('Ymd');
            /*初步過濾不規範外掛*/
            if ($k != $code) {
                continue;
            }
            /*--end*/
            $new_arr[] = $code;
            // 對比數據庫 本地有 數據庫沒有
            $data = array(
                'code'          => $code,
                'name'          => isset($v['name']) ? $v['name'] : '配置資訊不完善',
                'config'        => empty($v) ? '' : json_encode($v),
                'position'      => isset($v['position']) ? $v['position'] : 'default',
                'sort_order'    => 100,
            );
            if(empty($row[$code])){ // 新增外掛
                $data['add_time'] = getTime();
                $addData[] = $data;
            } else { // 更新外掛
                if ($row[$code]['config'] != json_encode($v)) {
                    $data['id'] = $row[$code]['id'];
                    $data['update_time'] = getTime();
                    $updateData[] = $data;
                }
            }
        }
        if (!empty($addData)) {
            model('weapp')->saveAll($addData);
        }
        if (!empty($updateData)) {
            model('weapp')->saveAll($updateData);
            // \think\Cache::clear('hook');
        }
        //數據庫有 本地沒有
        foreach($row as $k => $v){
            if (!in_array($v['code'], $new_arr)) {
                M('weapp')->where($v)->delete();
            }
        }
    }

    /**
     * 外掛目錄掃瞄
     * @return array 返回目錄陣列
     */
    private function scanWeapp(){
        $dir = WEAPP_DIR_NAME;
        $weapp_list = $this->dirscan($dir);
        foreach($weapp_list as $k=>$v){
            if (!is_dir(WEAPP_DIR_NAME.DS.$v) || !file_exists(WEAPP_DIR_NAME.DS.$v.'/config.php')) {
                unset($weapp_list[$k]);
            }
            else
            {
                $weapp_list[$v] = include(WEAPP_DIR_NAME.DS.$v.'/config.php');
                unset($weapp_list[$k]);                    
            }
        }
        return $weapp_list;
    }

    /**
     * 獲取外掛目錄列表
     * @param $dir
     * @return array
     */
    private function dirscan($dir){
        $dirArray = array();
        if (false != ($handle = opendir($dir))) {
            $i = 0;
            while ( false !== ($file = readdir ($handle)) ) {
                //去掉"「.」、「..」以及帶「.xxx」後綴的檔案
                if ($file != "." && $file != ".." && !strpos($file,".")) {
                    $dirArray[$i] = $file;
                    $i++;
                }
            }
            //關閉控制代碼
            closedir($handle);
        }
        return $dirArray;
    }

    /**
     * 外掛基類構造方法
     * sm：module        外掛模組
     * sc：controller    外掛控制器
     * sa：action        外掛操作
     */
    public function checkInstall()
    {
        $msg = true;
        if(!array_key_exists("sm", request()->param())){
            $msg = '無效外掛URL！';
        } else {
            $module = request()->param('sm');
            $module = $module ?: request()->param('sc');
            $row = M('Weapp')->field('code, name, status')
                ->where(array('code'=>$module))
                ->find();
            if (empty($row)) {
                $msg = "外掛【{$row['name']}】不存在";
            } else {
                if ($row['status'] == -1) {
                    $msg = "請先啟用外掛【{$row['name']}】";
                } else if (intval($row['status']) == 0) {
                    $msg = "請先安裝外掛【{$row['name']}】";
                }
            }
        }

        return $msg;
    }

    /**
     * 檢查是否有更新包
     * @return type 提示語
     */
    public function checkVersion($code, $serviceVersionList = false) {
        error_reporting(0);//關閉所有錯誤報告
        $lastupgrade = array();
        if (false === $serviceVersionList) {
            $curent_version = getWeappVersion($code);
            $url = $this->service_url.'&code='.$code.'&v='.$curent_version;
            $context = stream_context_set_default(array('http' => array('timeout' => 3,'method'=>'GET')));
            $serviceVersionList = @file_get_contents($url,false,$context);    
            $serviceVersionList = json_decode($serviceVersionList,true);
        }
        if(!empty($serviceVersionList))
        {
            $upgradeArr = array();
            $introStr = '';
            $upgradeStr = '';
            foreach ($serviceVersionList as $key => $val) {
                $upgrade = !empty($val['upgrade']) ? $val['upgrade'] : array();
                $upgradeArr = array_merge($upgradeArr, $upgrade);
                $introStr .= '<br/>'.filter_line_return($val['intro'], '<br/>');
            }
            $upgradeArr = array_unique($upgradeArr);
            $upgradeStr = implode('<br/>', $upgradeArr); // 升級提示需要覆蓋哪些檔案

            $introArr = explode('<br/>', $introStr);
            $introStr = '更新日誌：';
            foreach ($introArr as $key => $val) {
                if (empty($val)) {
                    continue;
                }
                $introStr .= "<br/>{$key}、".$val;
            }

            $lastupgrade = $serviceVersionList[count($serviceVersionList) - 1];
            if (!empty($lastupgrade['upgrade_title'])) {
                $introStr .= '<br/>'.$lastupgrade['upgrade_title'];
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
     * 批量檢查是否有更新包
     * @return type 提示語
     */
    public function checkBatchVersion($upgradeArr) 
    {
        $result = array();
        if (is_array($upgradeArr) && !empty($upgradeArr)) {
            foreach ($upgradeArr as $key => $upgrade) {
                $result[$key] = $this->checkVersion($key, $upgrade);
            }
        }
        return $result;
    }

    /**
     * 一鍵更新
     */
    public function OneKeyUpgrade($code){
        error_reporting(0);//關閉所有錯誤報告
        if (empty($code)) {
            return ['code' => 0, 'msg' => "URL傳參錯誤，缺少外掛標識參數值！"];
        }

        $allow_url_fopen = ini_get('allow_url_fopen');
        if (!$allow_url_fopen) {
            return ['code' => 0, 'msg' => "請聯繫空間商，設定 php.ini 中參數 allow_url_fopen = 1"];
        }     
               
        if (!extension_loaded('zip')) {
            return ['code' => 0, 'msg' => "請聯繫空間商，開啟 php.ini 中的php-zip擴充套件"];
        }

        $curent_version = getWeappVersion($code);
        $upgrade_url = $this->service_url.'&code='.$code.'&v='.$curent_version;
        $serviceVersionList = file_get_contents($upgrade_url);
        $serviceVersionList = json_decode($serviceVersionList,true);
        if (empty($serviceVersionList)) {
            return ['code' => 0, 'msg' => "沒找到升級包"];
        }
        
        clearstatcache(); // 清除資料夾許可權快取
        $config_path = $this->weapp_path.$code.DS.'config.php'; // 版本配置檔案路徑
        if(!is_writeable($config_path)) {
            return ['code' => 0, 'msg' => '檔案'.$config_path.' 不可寫，不能升級!!!'];
        }
        /*最新更新版本資訊*/
        $lastServiceVersion = $serviceVersionList[count($serviceVersionList) - 1];
        /*--end*/
        /*批量下載更新包*/
        $upgradeArr = array(); // 更新的檔案列表
        $sqlfileArr = array(); // 更新SQL檔案列表
        $folderName = $code.'-'.$lastServiceVersion['key_num'];
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
                $folderName = $code.'-'.str_replace(".zip", "", $lastDownFileName);  // 資料夾
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
            if($zip->open($this->data_path.'backup'.DS.$downFileName) != true) {
                return ['code' => 0, 'msg' => "升級包讀取失敗!"];
            }
            $zip->extractTo($this->data_path.'backup'.DS.$folderName.DS);//假設解壓縮到在目前路徑下backup資料夾內
            $zip->close();//關閉處理的zip檔案
            /*--end*/

            if(!file_exists($this->data_path.'backup'.DS.$folderName.DS.'www'.DS.'weapp'.DS.$code.DS.'config.php')) {
                return ['code' => 0, 'msg' => $code."外掛目錄缺少config.php檔案,請聯繫客服"];
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
                    $destination_file = $this->data_path.'backup'.DS.$code.'-'.$curent_version.'_www'.DS.$val;
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
                    return ['code' => 0, 'msg' => "數據庫執行中途失敗，請第一時間請求技術支援，否則將影響該外掛後續的版本升級！"];
                }
            }
        }
        /*--end*/

        // 遞迴複製資料夾
        $copy_data = $this->recurse_copy($this->data_path.'backup'.DS.$folderName.DS.'www', rtrim($this->root_path, DS), $folderName);

        // 清空快取
        delFile(rtrim(RUNTIME_PATH, '/'));
        tpCache('global');

        /*刪除下載的升級包*/
        $ziplist = glob($this->data_path.'backup'.DS.'*.zip');
        @array_map('unlink', $ziplist);
        /*--end*/

        // 推送回伺服器  記錄升級成功
        $this->UpgradeLog($code, $serviceVersion['key_num']);
        
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

    public function sql_split($sql, $tablepre) {

        $sql = str_replace("`#@__", '`'.$tablepre, $sql);
              
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
            return ['code' => 0, 'msg' => '官方外掛升級包不存在']; // 檔案存在直接退出
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
    private  function UpgradeLog($code, $to_key_num){
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
            'code'  => $code, // 外掛標識
            'key_num'=>getWeappVersion($code), // 使用者版本號
            'to_key_num'=>$to_key_num, // 使用者要升級的版本號                
            'add_time'=>time(), // 升級時間
            'serial_number'=>$serial_number,
            'ip'    => GetHostByName($_SERVER['SERVER_NAME']),
            'phpv'  => phpversion(),
            'mysql_version' => $mysql_version,
            'web_server'    => $_SERVER['SERVER_SOFTWARE'],
        );
        // api_Weapp_upgradeLog
        $tmp_str = 'L2luZGV4LnBocD9tPWFwaSZjPVdlYXBwJmE9dXBncmFkZUxvZyY=';
        $url = base64_decode($this->service_ey).base64_decode($tmp_str).http_build_query($vaules);
        file_get_contents($url);
    }
} 
?>