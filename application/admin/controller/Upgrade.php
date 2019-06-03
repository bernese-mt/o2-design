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

namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\response\Json;
use app\admin\model;
class Upgrade extends Controller {

    /**
     * 解構函式
     */
    function __construct() {
        parent::__construct();
        @ini_set('memory_limit', '1024M'); // 設定記憶體大小
        @ini_set("max_execution_time", "0"); // 請求超時時間 0 為不限時
        @ini_set('default_socket_timeout', 3600); // 設定 file_get_contents 請求超時時間 官方的說明，似乎沒有不限時間的選項，也就是不能填0，你如果填0，那麼socket就會立即返回失敗，
        $this->assign('version', getCmsVersion());
    }

    public function welcome()
    {
        $sys_info['os']             = PHP_OS;
        $sys_info['zlib']           = function_exists('gzclose') ? 'YES' : 'NO';//zlib
        $sys_info['safe_mode']      = (boolean) ini_get('safe_mode') ? 'YES' : 'NO';//safe_mode = Off       
        $sys_info['timezone']       = function_exists("date_default_timezone_get") ? date_default_timezone_get() : "no_timezone";
        $sys_info['curl']           = function_exists('curl_init') ? 'YES' : 'NO';  
        $sys_info['web_server']     = $_SERVER['SERVER_SOFTWARE'];
        $sys_info['phpv']           = phpversion();
        $sys_info['ip']             = GetHostByName($_SERVER['SERVER_NAME']);
        $sys_info['fileupload']     = @ini_get('file_uploads') ? ini_get('upload_max_filesize') :'unknown';
        $sys_info['max_ex_time']    = @ini_get("max_execution_time").'s'; //指令碼最大執行時間
        $sys_info['set_time_limit'] = function_exists("set_time_limit") ? true : false;
        $sys_info['domain']         = $_SERVER['HTTP_HOST'];
        $sys_info['memory_limit']   = ini_get('memory_limit');                                  
        $sys_info['version']        = file_get_contents(DATA_PATH.'conf/version.txt');
        $mysqlinfo = Db::query("SELECT VERSION() as version");
        $sys_info['mysql_version']  = $mysqlinfo[0]['version'];
        if(function_exists("gd_info")){
            $gd = gd_info();
            $sys_info['gdinfo']     = $gd['GD Version'];
        }else {
            $sys_info['gdinfo']     = "未知";
        }

        $globalTpCache = tpCache('global');

        $upgradeLogic = new \app\admin\logic\UpgradeLogic();
        $sys_info['curent_version'] = $upgradeLogic->curent_version; //目前程式版本
        $sys_info['web_name'] = !empty($globalTpCache['web_name']) ? $globalTpCache['web_name'] : '';
        $this->assign('sys_info', $sys_info);

        $upgradeMsg = $upgradeLogic->checkVersion(); //升級包訊息     
        $this->assign('upgradeMsg',$upgradeMsg);

        $is_eyou_authortoken = session('web_is_authortoken');
        $is_eyou_authortoken = !empty($is_eyou_authortoken) ? $is_eyou_authortoken : 0;
        $this->assign('is_eyou_authortoken', $is_eyou_authortoken);

        $this->assign('web_show_popup_upgrade', $globalTpCache['web.web_show_popup_upgrade']);

        $this->assign('global', $globalTpCache);

        return $this->fetch();
    }

    /**
    * 一鍵升級
    */
    public function OneKeyUpgrade(){
        header('Content-Type:application/json; charset=utf-8');
        function_exists('set_time_limit') && set_time_limit(0);

        /*許可權控制 by 小虎哥*/
        $auth_role_info = session('admin_info.auth_role_info');
        if(0 < intval(session('admin_info.role_id')) && ! empty($auth_role_info) && intval($auth_role_info['online_update']) <= 0){
            $this->error('您沒有操作許可權，請聯繫超級管理員分配許可權');
        }
        /*--end*/

        $upgradeLogic = new \app\admin\logic\UpgradeLogic();
        $data = $upgradeLogic->OneKeyUpgrade(); //升級包訊息
        if (1 <= intval($data['code'])) {
            $this->success($data['msg'], null, ['code'=>$data['code']]);
        } else {
            $msg = '升級異常，請第一時間聯繫技術支援，排查問題！';
            if (is_array($data)) {
                $msg = $data['msg'];
            }
            $this->error($msg);
        }
    }

    /**
    * 設定彈窗更新-不再提醒
    */
    public function setPopupUpgrade()
    {
        $show_popup_upgrade = input('param.show_popup_upgrade/s', '1');
        $inc_type = 'web';
        tpCache($inc_type, array($inc_type.'_show_popup_upgrade'=>$show_popup_upgrade));
        respose(1);
    }

    /**
    * 檢測目錄許可權、目前版本的數據庫是否與官方一致
    */
    public function check_authority()
    {
        /*------------------檢測目錄讀寫許可權----------------------*/
        $filelist = tpCache('system.system_upgrade_filelist');
        $filelist = base64_decode($filelist);
        $filelist = htmlspecialchars_decode($filelist);
        $filelist = explode('<br>', $filelist);
        $dirs = array();
        $i = -1;
        foreach($filelist as $filename)
        {
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
                @tp_mkdir($curdir, 0777);
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

        if (true == $is_pass) {
            /*------------------檢測目錄讀寫許可權----------------------*/
            $tmp_str = 'L2luZGV4LnBocD9tPWFwaSZjPVNlcnZpY2UmYT1nZXRfZGF0YWJhc2VfdHh0';
            $service_url = base64_decode(config('service_ey')).base64_decode($tmp_str);
            $url = $service_url . '&version=' . getCmsVersion();
            $context = stream_context_set_default(array('http' => array('timeout' => 3,'method'=>'GET')));
            $response = @file_get_contents($url,false,$context);
            $params = json_decode($response,true);
            if (false == $params) {
                $this->error('連線升級伺服器超時，請重新整理重試，或者聯繫技術支援！', null, ['code'=>2]);
            }

            if (is_array($params)) {
                if (1 == intval($params['code'])) {
                    /*------------------組合本地數據庫資訊----------------------*/
                    $dbtables = Db::query('SHOW TABLE STATUS');
                    $local_database = array();
                    foreach ($dbtables as $k => $v) {
                        $table = $v['Name'];
                        if (preg_match('/^'.PREFIX.'/i', $table)) {
                            $local_database[$table] = [
                                'name'  => $table,
                                'field' => [],
                            ];
                        }
                    }
                    /*------------------end----------------------*/

                    /*------------------組合官方遠端數據庫資訊----------------------*/
                    $info = $params['info'];
                    $info = preg_replace("#[\r\n]{1,}#", "\n", $info);
                    $infos = explode("\n", $info);
                    $infolists = [];
                    foreach ($infos as $key => $val) {
                        if (!empty($val)) {
                            $arr = explode('|', $val);
                            $infolists[$arr[0]] = $val;
                        }
                    }
                    /*------------------end----------------------*/

                    /*------------------校驗數據庫是否合格----------------------*/
                    foreach ([1] as $testk => $testv) {
                        // 對比數據表數量
                        if (count($local_database) < count($infolists)) {
                            $is_pass = false;
                            break;
                        }

                        // 對比數據表字段數量
                        foreach ($infolists as $k1 => $v1) {
                            $arr1 = explode('|', $v1);
                            
                            if (1 >= count($arr1)) {
                                continue; // 忽略不對比的數據表
                            }

                            $fieldArr = explode(',', $arr1[1]);
                            $table = preg_replace('/^ey_/i', PREFIX, $arr1[0]);
                            $local_fields = Db::getFields($table); // 本地數據表字段列表
                            $local_database[$table]['field'] = $local_fields;
                            if (count($local_fields) < count($fieldArr)) {
                                $is_pass = false;
                                break;
                            }
                        }
                        if (false == $is_pass) break;
                    }
                    /*------------------end----------------------*/
                } else if (2 == intval($params['code'])) {
                    $this->error('官方缺少版本號'.getCmsVersion().'的數據庫比較檔案，請第一時間聯繫技術支援！', null, ['code'=>2]);
                }
            }

            if (true == $is_pass) {
                $this->success($msg);
            } else {
                $this->error('目前版本數據庫結構與官方不一致，請第一時間聯繫技術支援！', null, ['code'=>2]);
            }
            /*------------------end----------------------*/
        } else {
            $this->error($msg, null, ['code'=>1]);
        }
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