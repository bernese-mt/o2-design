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

namespace app\admin\controller;

use think\Page;
use think\Db;
use app\admin\logic\WeappLogic;

/**
 * 外掛控制器
 */
class Weapp extends Base
{
    public $weappM;
    public $weappLogic;
    public $plugins = array();

    /*
     * 前置操作
     */
    protected $beforeActionList = array(
        'init'
    );


    /*
     * 初始化操作
     */
    public function _initialize() {
        parent::_initialize();
        $this->weappM = model('Weapp');
        $this->weappLogic = new WeappLogic();
        //  更新外掛
        $this->weappLogic->insertWeapp();
    }

    public function init(){
        /*許可權控制 by 小虎哥*/
        if (0 < intval(session('admin_info.role_id'))) {
            $auth_role_info = session('admin_info.auth_role_info');
            if(! empty($auth_role_info)){
                if(! empty($auth_role_info['permission']['plugins'])){
                    foreach ($auth_role_info['permission']['plugins'] as $plugins){
                        if(isset($plugins['code'])){
                            $this->plugins[] = $plugins['code'];
                        }
                    }
                }
            }
        }
        /*--end*/
    }

    /*
     * 外掛列表
     */
    public function index()
    {
        $root_dir = ROOT_DIR;
        if (!empty($root_dir)) {
            $this->error('子目錄暫時不支援外掛，待完善中……');
        }
        
        $assign_data = array();
        $condition = array();
        // 獲取到所有GET參數
        $get = input('get.');

        // 應用搜索條件
        foreach (['keywords'] as $key) {
            if (isset($get[$key]) && $get[$key] !== '') {
                if ($key == 'keywords') {
                    $condition['a.name|code'] = array('LIKE', "%{$get[$key]}%");
                } else {
                    $condition['a.'.$key] = array('eq', $get[$key]);
                }
            }
        }

        /*許可權控制 by 小虎哥*/
        if(! empty($this->plugins)){
            $condition['a.code'] = array('in', $this->plugins);
        }
        /*--end*/

        $weappArr = array(); // 外掛標識陣列

        /**
         * 數據查詢，搜索出主鍵ID的值
         */
        $count = DB::name('weapp')->alias('a')->where($condition)->count();// 查詢滿足要求的總記錄數
        $Page = new Page($count, config('paginate.list_rows'));// 實例化分頁類 傳入總記錄數和每頁顯示的記錄數
        $list = DB::name('weapp')
            ->field('a.*')
            ->alias('a')
            ->where($condition)
            ->order('a.sort_order asc, a.id desc')
            ->limit($Page->firstRow.','.$Page->listRows)
            ->getAllWithIndex('id');
        foreach ($list as $key => $val) {
            $config = include WEAPP_PATH.$val['code'].DS.'config.php';
            $config['description'] = filter_line_return($config['description'], '<br/>');
            $val['config'] = $config;
            $val['version'] = getWeappVersion($val['code']);

            switch ($val['status']) {
                case '-1':
                    $status_text = '禁用';
                    break;

                case '1':
                    $status_text = '啟用';
                    break;

                default:
                    $status_text = '未安裝';
                    break;
            }
            $val['status_text'] = $status_text;

            $list[$key] = $val;

            /*外掛標識陣列*/
            $weappArr[$val['code']] = array(
                'code'  => $val['code'],
                'version'  => $val['version'],
            );
            /*--end*/
        }
        $show = $Page->show(); // 分頁顯示輸出
        $assign_data['page'] = $show; // 賦值分頁輸出
        $assign_data['list'] = $list; // 賦值數據集
        $assign_data['pager'] = $Page; // 賦值分頁對像

        /*檢測更新*/
        $weapp_upgrade = array();
        if (!empty($weappArr)) {
            // 標識
            $codeArr = get_arr_column($weappArr, 'code');
            $codeStr = implode(',', $codeArr);
            // 版本號
            $versionArr = get_arr_column($weappArr, 'version');
            $versionStr = implode(',', $versionArr);
            // URL參數
            $vaules = array(
                'domain'    => request()->host(true),
                'code'      => $codeStr,
                'v'         => $versionStr,
            );
            $tmp_str = 'L2luZGV4LnBocD9tPWFwaSZjPVdlYXBwJmE9Y2hlY2tCYXRjaFZlcnNpb24m';
            $service_url = base64_decode(config('service_ey')).base64_decode($tmp_str);
            $url = $service_url.http_build_query($vaules);
            $context = stream_context_set_default(array('http' => array('timeout' => 3,'method'=>'GET')));
            $response = @file_get_contents($url,false,$context);
            $batch_upgrade = json_decode($response,true);

            if (is_array($batch_upgrade) && !empty($batch_upgrade)) {
                $weapp_upgrade = $this->weappLogic->checkBatchVersion($batch_upgrade); //升級包訊息 
            }
        }
        $assign_data['weapp_upgrade'] = $weapp_upgrade;
        /*--end*/

        $this->assign($assign_data);
        return $this->fetch();
    }

    /**
     *  執行外掛控制器
     *  控制模組  參數m
     *  控制器名  參數c來確定
     *  控制器里-操作名  參數a
     *  http://網站域名/login.php/weapp/execute?m=login&c=Qq&a=callback
     */
    public function execute($sm = '', $sc = '', $sa = '')
    {
        if (!IS_AJAX) {
            $msg = $this->weappLogic->checkInstall();
            if ($msg !== true) {
                $this->error($msg, url('Weapp/index'));
            }
        }
        $sm = request()->param('sm');
        $sc = request()->param('sc');
        $sa = request()->param('sa');

        /*外掛轉為內建*/
        if ('Smtpmail' == $sm) {
            $this->success('該外掛已遷移，前往中…', url('System/smtp'));
        }
        /*--end*/

        $controllerName = !empty($sc) ? $sc : $sm;
        $actionName = !empty($sa) ? $sa : "index";
        $class_path = "\\".WEAPP_DIR_NAME."\\".$sm."\\controller\\".$controllerName;
        $controller = new $class_path();
        $result = $controller->$actionName();
        return $result;
    }

    /**
     * 安裝外掛
     */
    public function install($id){
        $row      =   M('Weapp')->field('name,code,thorough,config')->find($id);
        $row['config'] = json_decode($row['config'], true);
        $class    =   get_weapp_class($row['code']);
        if (!class_exists($class)) {
            $this->error('外掛不存在！');
        }
        $weapp  =   new $class;
        if(!$weapp->checkConfig()) {//檢測資訊的正確性
            $this->error('外掛config配置參數不全！');
        }
        $cms_version = getCmsVersion();
        $min_version = $row['config']['min_version'];
        if ($cms_version < $min_version) {
            $this->error('目前CMS版本太低，該外掛要求CMS版本 >= '.$min_version.'，請升級系統！');
        }
        /*外掛安裝的前置操作（可無）*/
        $this->beforeInstall($weapp);
        /*--end*/

        if (true) {
            /*外掛sql檔案*/
            $sqlfile = WEAPP_DIR_NAME.DS.$row['code'].DS.'data'.DS.'install.sql';
            if (empty($row['thorough']) && file_exists($sqlfile)) {
                $execute_sql = file_get_contents($sqlfile);
                $sqlFormat = $this->sql_split($execute_sql, PREFIX);
                /**
                 * 執行SQL語句
                 */
                $counts = count($sqlFormat);

                for ($i = 0; $i < $counts; $i++) {
                    $sql = trim($sqlFormat[$i]);

                    if (strstr($sql, 'CREATE TABLE')) {
                        Db::execute($sql);
                    } else {
                        if(trim($sql) == '')
                           continue;
                        Db::execute($sql);
                    }
                }
            }
            /*--end*/
            $r = M('weapp')->where('id',$id)->update(array('thorough'=>1,'status'=>1,'add_time'=>getTime()));
            if ($r) {
                cache('hooks', null);
                cache("hookexec_".$row['code'], null);
                \think\Cache::clear('hooks');
                /*外掛安裝的後置操作（可無）*/
                $this->afterInstall($weapp);
                /*--end*/
                adminLog('安裝外掛：'.$row['name']);
                $this->success('安裝成功', url('Weapp/index'));
                exit;
            }
        }

        $this->error('安裝失敗');
    }

    /**
     * 解除安裝外掛
     */
    public function uninstall(){
        $id       =   input('param.id/d', 0);
        $thorough = input('param.thorough/d', 0);
        $row      =   M('Weapp')->field('name,code')->find($id);
        $class    =   get_weapp_class($row['code']);
        if (!class_exists($class)) {
            $this->error('外掛不存在！');
        }
        $weapp  =   new $class;

        // 外掛解除安裝的前置操作（可無）
        $this->beforeUninstall($weapp);
        /*--end*/

        if (true) {
            $is_uninstall = false;
            if (1 == $thorough) {
                $r = M('weapp')->where('id',$id)->update(array('thorough'=>$thorough,'status'=>0,'add_time'=>getTime()));
            } else if (0 == $thorough) {
                $r = M('weapp')->where('id',$id)->update(array('thorough'=>$thorough,'status'=>0,'update_time'=>getTime()));
                $r && $is_uninstall = true;
            }
            if (false !== $r) {
               /*外掛sql檔案，不執行刪除外掛數據表*/
                $sqlfile = WEAPP_DIR_NAME.DS.$row['code'].DS.'data'.DS.'uninstall.sql';
                if (empty($thorough) && file_exists($sqlfile)) {
                    $execute_sql = file_get_contents($sqlfile);
                    $sqlFormat = $this->sql_split($execute_sql, PREFIX);
                    /**
                     * 執行SQL語句
                     */
                    $counts = count($sqlFormat);

                    for ($i = 0; $i < $counts; $i++) {
                        $sql = trim($sqlFormat[$i]);

                        if (strstr($sql, 'CREATE TABLE')) {
                            Db::execute($sql);
                        } else {
                            if(trim($sql) == '')
                               continue;
                            Db::execute($sql);
                        }
                    }
                }
                /*--end*/

                cache('hooks', null);
                cache("hookexec_".$row['code'], null);
                \think\Cache::clear('hooks');
                /*外掛解除安裝的後置操作（可無）*/
                $this->afterUninstall($weapp);
                /*--end*/

                // 刪除外掛相關檔案
                if ($is_uninstall) {
                    $rdel = M('weapp')->where('id',$id)->delete();
                    $this->unlinkcode($row['code']);
                }

                adminLog('解除安裝外掛：'.$row['name']);
                $this->success('解除安裝成功', url('Weapp/index'));
                exit;
            }
        }

        $this->error('解除安裝失敗');
    }

    /**
     * 啟用外掛
     */
    public function enable()
    {
        $id       =   input('param.id/d', 0);
        if (0 < $id) {
            $row = M('weapp')->field('code')->find($id);
            $class    =   get_weapp_class($row['code']);
            if (!class_exists($class)) {
                $this->error('外掛不存在！');
            }
            $weapp  =   new $class;
            /*外掛啟用的前置操作（可無）*/
            $this->beforeEnable($weapp);
            /*--end*/
            $r = M('weapp')->where('id',$id)->update(array('status'=>1,'update_time'=>getTime()));
            if ($r) {
                /*外掛啟用的後置操作（可無）*/
                $this->afterEnable($weapp);
                /*--end*/
                cache("hookexec_".$row['code'], null);
                cache('hooks', null);
                \think\Cache::clear('hooks');
                $this->success('操作成功！', url('Weapp/index'));
                exit;
            }
        }
        $this->error('操作失敗！');
        exit;
    }

    /**
     * 禁用外掛
     */
    public function disable()
    {
        $id       =   input('param.id/d', 0);
        if (0 < $id) {
            $row = M('weapp')->field('code')->find($id);
            $class    =   get_weapp_class($row['code']);
            if (!class_exists($class)) {
                $this->error('外掛不存在！');
            }
            $weapp  =   new $class;
            /*外掛禁用的前置操作（可無）*/
            $this->beforeDisable($weapp);
            /*--end*/
            $r = M('weapp')->where('id',$id)->update(array('status'=>-1,'update_time'=>getTime()));
            if ($r) {
                /*外掛禁用的後置操作（可無）*/
                $this->afterDisable($weapp);
                /*--end*/
                cache("hookexec_".$row['code'], null);
                cache('hooks', null);
                \think\Cache::clear('hooks');
                $this->success('操作成功！', url('Weapp/index'));
                exit;
            }
        }
        $this->error('操作失敗！');
        exit;
    }
    
    /**
     * 刪除外掛以及檔案
     */
    public function del()
    {
        if (IS_POST) {
            $id_arr = input('del_id/a');
            $id_arr = eyIntval($id_arr);
            if(!empty($id_arr)){
                $result = M('weapp')->field('id,name,code')
                    ->where([
                        'id'    => ['IN', $id_arr],
                    ])->select();
                $name_list = get_arr_column($result, 'name');

                $r = M('weapp')->where([
                        'id'    => ['IN', $id_arr],
                    ])
                    ->delete();
                if($r){
                    /*清理外掛相關檔案*/
                    foreach ($result as $key => $val) {
                        $unbool = $this->unlinkcode($val['code']);
                        if (true == $unbool) {
                            continue;
                        }
                    }
                    /*--end*/

                    adminLog('刪除外掛：'.implode(',', $name_list));
                    $this->success('刪除成功');
                }else{
                    $this->error('刪除失敗');
                }
            } else {
                $this->error('參數有誤');
            }
        }
        $this->error('非法訪問');
    }

    /**
     * 清理外掛相關檔案
     */
    private function unlinkcode($code)
    {
        $filelist_path = WEAPP_DIR_NAME.DS.$code.DS.'filelist.txt';
        if (file_exists($filelist_path)) {
            $filelistStr = file_get_contents($filelist_path);
            $filelist = explode("\n\r", $filelistStr);
            if (empty($filelist)) {
                return true;
            }
            delFile(WEAPP_DIR_NAME.DS.$code, true);
            foreach ($filelist as $k2 => $v2) {
                if (!empty($v2) && !preg_match('/^'.WEAPP_DIR_NAME.'\/'.$code.'/i', $v2)) {
                    if (file_exists($v2) && is_file($v2)) {
                        @unlink($v2);
                    }
                }
            }
            delFile(WEAPP_DIR_NAME.DS.$code, true);
        }

        return true;
    }

    /**
     * 分解SQL檔案的語句
     */
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
            if ((!stristr($ret[$num], 'SET FOREIGN_KEY_CHECKS') && !stristr($ret[$num], 'SET NAMES')) && false === stripos($ret[$num], $tablepre.'weapp_')) {
                $this->error('請刪除不相干的SQL語句，或者數據表字首是否符合外掛規範（#@__weapp_）');
            }
            $num++;
        }
        return $ret;
    }

    /**
     * 外掛安裝的前置操作（可無）
     */
    public function beforeInstall($weappClass){
        if (method_exists($weappClass, 'beforeInstall')) {
            $weappClass->beforeInstall();
        }
    }

    /**
     * 外掛安裝的後置操作（可無）
     */
    public function afterInstall($weappClass){
        if (method_exists($weappClass, 'afterInstall')) {
            $weappClass->afterInstall();
        }
    }

    /**
     * 外掛解除安裝的前置操作（可無）
     */
    public function beforeUninstall($weappClass){
        if (method_exists($weappClass, 'beforeUninstall')) {
            $weappClass->beforeUninstall();
        }
    }

    /**
     * 外掛解除安裝的後置操作（可無）
     */
    public function afterUninstall($weappClass){
        if (method_exists($weappClass, 'afterUninstall')) {
            $weappClass->afterUninstall();
        }
    }

    /**
     * 外掛啟用的前置操作（可無）
     */
    public function beforeEnable($weappClass){
        if (method_exists($weappClass, 'beforeEnable')) {
            $weappClass->beforeEnable();
        }
    }

    /**
     * 外掛啟用的後置操作（可無）
     */
    public function afterEnable($weappClass){
        if (method_exists($weappClass, 'afterEnable')) {
            $weappClass->afterEnable();
        }
    }

    /**
     * 外掛禁用的前置操作（可無）
     */
    public function beforeDisable($weappClass){
        if (method_exists($weappClass, 'beforeDisable')) {
            $weappClass->beforeDisable();
        }
    }

    /**
     * 外掛禁用的後置操作（可無）
     */
    public function afterDisable($weappClass){
        if (method_exists($weappClass, 'afterDisable')) {
            $weappClass->afterDisable();
        }
    }

    /**
     * 上傳外掛並解壓
     */
    public function upload() 
    {
        //防止php超時
        function_exists('set_time_limit') && set_time_limit(0);
        
        if (IS_POST) {
            $fileExt = 'zip';
            $savePath = UPLOAD_PATH.'tmp'.DS;
            $image_upload_limit_size = intval(tpCache('basic.file_size') * 1024 * 1024);
            $file = request()->file('weappfile');
            if(empty($file)){
                $this->error('請先上傳zip檔案');
            }
            $error = $file->getError();
            if(!empty($error)){
                $this->error($error);
            }
            $result = $this->validate(
                ['file' => $file], 
                ['file'=>'fileSize:'.$image_upload_limit_size.'|fileExt:'.$fileExt],
                ['file.fileSize' => '上傳檔案過大','file.fileExt'=>'上傳檔案後綴名必須為'.$fileExt]
            );
            if (true !== $result || empty($file)) {
                $this->error($result);
            }
            // 移動到框架應用根目錄/public/upload/tmp/ 目錄下
            $fileName = md5(getTime().uniqid(mt_rand(), TRUE)).'.'.$fileExt; // 上傳之後的檔案全名
            $folderName = str_replace(".zip", "", $fileName);  // 檔名，不帶副檔名
            /*使用自定義的檔案儲存規則*/
            $info = $file->rule(function ($file) {
                return  $folderName;
            })->move($savePath, $folderName);
            /*--end*/
            if ($info) {
                $filepath = $savePath.$fileName;
                if (file_exists($filepath)) {
                    /*解壓之前，刪除存在的資料夾*/
                    delFile($savePath.$folderName);
                    /*--end*/

                    /*解壓檔案*/
                    $zip = new \ZipArchive();//新建一個ZipArchive的對象
                    if ($zip->open($savePath.$fileName) != true) {
                        $this->error("外掛壓縮包讀取失敗!");
                    }
                    $zip->extractTo($savePath.$folderName.DS);//假設解壓縮到在目前路徑下外掛名稱資料夾內
                    $zip->close();//關閉處理的zip檔案
                    /*--end*/
                    
                    /*獲取外掛目錄名稱*/
                    $dirList = glob($savePath.$folderName.DS.WEAPP_DIR_NAME.DS.'*');
                    $weappPath = !empty($dirList) ? $dirList[0] : '';
                    if (empty($weappPath)) {
                        $this->error('外掛壓縮包缺少目錄檔案');
                    }
                    
                    $weappPath = str_replace("\\", DS, $weappPath);
                    $weappPathArr = explode(DS, $weappPath);
                    $weappName = $weappPathArr[count($weappPathArr) - 1];
                    // if (is_dir(ROOT_PATH.WEAPP_DIR_NAME.DS.$weappName)) {
                    //     $this->error("已存在同名外掛{$weappName}，請手工移除".WEAPP_DIR_NAME.DS.$weappName."目錄");
                    // }
                    /*--end*/

                    // 遞迴複製資料夾            
                    $copy_bool = recurse_copy($savePath.$folderName, rtrim(ROOT_PATH, DS));
                    if (true !== $copy_bool) {
                        $this->error($copy_bool);
                    }

                    /*刪除上傳的外掛包*/
                    @unlink(realpath($savePath.$fileName));
                    @delFile($savePath.$folderName, true);
                    /*--end*/

                    /*安裝外掛*/
                    $configfile = WEAPP_DIR_NAME.DS.$weappName.'/config.php';
                    if (file_exists($configfile)) {
                        $configdata = include($configfile);
                        $code = isset($configdata['code']) ? $configdata['code'] : 'error_'.date('Ymd');
                        $addData = [
                            'code'          => $code,
                            'name'          => isset($configdata['name']) ? $configdata['name'] : '配置資訊不完善',
                            'config'        => empty($configdata) ? '' : json_encode($configdata),
                            'add_time'      => getTime(),
                        ];
                        $weapp_id = Db::name('weapp')->insertGetId($addData);
                        if (!empty($weapp_id)) {
                            $this->install($weapp_id);
                        }
                    }
                    /*--end*/
                }
            }else{
                //上傳錯誤提示錯誤資訊
                $this->error($info->getError());
            }
        }
    }

    /**
     * 一鍵更新外掛
     */
    public function OneKeyUpgrade()
    {
        header('Content-Type:application/json; charset=utf-8');
        $code = input('param.code/s', '');
        $upgradeMsg = $this->weappLogic->OneKeyUpgrade($code); //一鍵更新外掛
        respose($upgradeMsg);
    }

    /**
     * 檢查外掛是否有更新包
     * @return type 提示語
     */
    public function checkVersion()
    { 
        // error_reporting(0);//關閉所有錯誤報告
        $upgradeMsg = $this->weappLogic->checkVersion(); //升級包訊息   
        respose($upgradeMsg);
    }

    /**
     * 建立初始外掛結構
     */
    public function create()
    {
        $sample = 'Sample';
        $srcPath = DATA_NAME.DS.WEAPP_DIR_NAME.DS.$sample;

        if (IS_POST) {
            $post = input('post.');
            $code = trim($post['code']);
            if (!preg_match('/^[A-Z]([a-zA-Z0-9]*)$/', $code)) {
                $this->error('外掛標識格式不正確！');
            }
            if ('Sample' == $code) {
                $this->error('外掛標識已被佔用！');
            }
            if (!preg_match('/^v([0-9]+)\.([0-9]+)\.([0-9]+)$/', $post['version'])) {
                $this->error('外掛版本號格式不正確！');
            }
            if (empty($post['min_version'])) {
                $post['min_version'] = getCmsVersion();
            }
            if (empty($post['version'])) {
                $post['version'] = 'v1.0.0';
            }

            /*複製樣本結構到外掛目錄下*/
            $srcFiles = getDirFile($srcPath);
            $filetxt = '';
            foreach ($srcFiles as $key => $srcfile) {
                $dstfile = str_replace($sample, $code, $srcfile);
                $dstfile = str_replace(strtolower($sample), strtolower($code), $dstfile);
                if (!preg_match('/^'.WEAPP_DIR_NAME.'\/'.$code.'/i', $dstfile)) {
                    $filetxt .= $dstfile."\n\r";
                }
                if(tp_mkdir(dirname($dstfile))) {
                    $fileContent = file_get_contents($srcPath . DS . $srcfile);
                    if (preg_match('/\.sql$/i', $dstfile)) {
                        $fileContent = str_replace(strtolower($sample), uncamelize($code), $fileContent);
                    } else {
                        $fileContent = str_replace($sample, $code, $fileContent);
                        $fileContent = str_replace(strtolower($sample), strtolower($code), $fileContent);
                    }
                    $puts = @file_put_contents($dstfile, $fileContent); //初始化外掛檔案列表   
                    if (!$puts) {
                        $this->error('寫入檔案內容 ' . $dstfile . ' 失敗');
                        exit;
                    }
                }
            }
            $filetxt .= WEAPP_DIR_NAME.'/'.$code;
            @file_put_contents(WEAPP_DIR_NAME.DS.$code.DS.'filelist.txt', $filetxt); //初始化外掛檔案列表  
            /*--end*/

            /*讀取配置檔案，並替換外掛資訊*/
            $configPath = WEAPP_DIR_NAME.DS.$code.DS.'config.php';
            if (!eyPreventShell($configPath) || !file_exists($configPath)) {
                $this->error('建立外掛結構不完整，請重新建立！');
            }
            $strConfig = file_get_contents(WEAPP_DIR_NAME.DS.$code.DS.'config.php');
            $strConfig = str_replace('#CODE#', $code, $strConfig);
            $strConfig = str_replace('#NAME#', $post['name'], $strConfig);
            $strConfig = str_replace('#VERSION#', $post['version'], $strConfig);
            $strConfig = str_replace('#MIN_VERSION#', $post['min_version'], $strConfig);
            $strConfig = str_replace('#AUTHOR#', $post['author'], $strConfig);
            $strConfig = str_replace('#DESCRIPTION#', $post['description'], $strConfig);
            $strConfig = str_replace('#SCENE#', $post['scene'], $strConfig);
            @chmod(WEAPP_DIR_NAME.DS.$code.DS.'config.php'); //配置檔案的地址
            $puts = @file_put_contents(WEAPP_DIR_NAME.DS.$code.DS.'config.php', $strConfig); //配置檔案的地址    
            if (!$puts) {
                $this->error('替換外掛資訊失敗，請設定目錄許可權為 755！');
            }
            /*--end*/

            $this->success('初始化外掛成功，請在該外掛基礎上進行二次開發！', url('Weapp/index'), [], 3);
        }

        /*刪除多餘目錄以及檔案，相容v1.1.7之後的版本*/
        if (file_exists($srcPath.DS.'application'.DS.'weapp')) {
            delFile($srcPath.DS.'application'.DS.'weapp', true);
        }
        if (file_exists($srcPath.DS.'template'.DS.'weapp')) {
            delFile($srcPath.DS.'template'.DS.'weapp', true);
        }
        if (file_exists($srcPath.DS.'weapp'.DS.$sample.DS.'behavior'.DS.'weapp')) {
            delFile($srcPath.DS.'weapp'.DS.$sample.DS.'behavior'.DS.'weapp', true);
        }
        if (file_exists($srcPath.DS.'weapp'.DS.$sample.DS.'template'.DS.'skin'.DS.'font')) {
            delFile($srcPath.DS.'weapp'.DS.$sample.DS.'template'.DS.'skin'.DS.'font', true);
        }
        if (file_exists($srcPath.DS.'weapp'.DS.$sample.DS.'common.php')) {
            @unlink($srcPath.DS.'weapp'.DS.$sample.DS.'common.php');
        }
        /*--end*/

        $assign_data = array();
        $assign_data['min_version'] = getCmsVersion();

        $this->assign($assign_data);
        return $this->fetch();
    }

    /**
     * 打包外掛
     */
    public function pack()
    {
        if (IS_POST) {
            $packfiles = array(); // 打包的全部檔案列表

            $post = input('post.');
            $code = $post['code'];
            $additional_file = $post['additional_file'];

            if (!preg_match('/^[A-Z]([a-zA-Z0-9]*)$/', $code)) {
                $this->error('外掛標識格式不正確！');
            } else if (!file_exists(WEAPP_DIR_NAME.DS.$code)) {
                $this->error('該外掛不存在！');
            }
            if (empty($additional_file)) {
                $this->error('打包檔案不能為空！');
            }

            /*額外打包檔案*/
            if (!empty($additional_file)) {
                $file_arr = explode(PHP_EOL, $additional_file);
                foreach ($file_arr as $key => $val) {
                    if (empty($val)) {
                        continue;
                    }
                    if (eyPreventShell($val) && is_file($val) && file_exists($val)) {
                        $packfiles[$val] = $val;
                    } else if (eyPreventShell($val) && is_dir($val) && file_exists($val)) {
                        $dirfiles = getDirFile($val, $val);
                        foreach ($dirfiles as $k2 => $v2) {
                            $packfiles[$v2] = $v2;
                        }
                    }
                }
            }
            /*--end*/

            /*壓縮外掛目錄*/
            $zip = new \ZipArchive();//新建一個ZipArchive的對象
            $filepath = DATA_PATH.WEAPP_DIR_NAME;
            tp_mkdir($filepath);
            $zipName = $filepath.DS.$code.'.zip';//定義打包后的包名
            if ($zip->open($zipName, \ZIPARCHIVE::OVERWRITE | \ZIPARCHIVE::CREATE) !== TRUE)
                $this->error('外掛壓縮包打開失敗！');

            /*打包外掛標準結構涉及的檔案與目錄，並且打包zip*/
            $filetxt = '';
            foreach ($packfiles as $key => $srcfile) {
                $filetxt .= $srcfile."\n\r";
                // $dstfile = DATA_NAME.DS.WEAPP_DIR_NAME.DS.$code.DS.$srcfile;
                // if(true == tp_mkdir(dirname($dstfile))) {
                    if(file_exists($srcfile)) {
                        // $copyrt = copy($srcfile, $dstfile); //複製檔案
                        // if (!$copyrt) {
                        //     $this->error('copy ' . $dstfile . ' 失敗');
                        //     exit;
                        // }
                        //addFile函式首個參數如果帶有路徑，則壓縮的檔案里包含的是帶有路徑的檔案壓縮
                        //若不希望帶有路徑，則需要該函式的第二個參數
                        $zip->addFile($srcfile);//第二個參數是放在壓縮包中的檔名稱，如果檔案可能會有重複，就需要注意一下
                    }
                // }
            }
            // $dst_filelist = DATA_NAME.DS.WEAPP_DIR_NAME.DS.$code.DS.WEAPP_DIR_NAME.DS.$code.DS.'filelist.txt';
            $src_filelist = WEAPP_DIR_NAME.DS.$code.DS.'filelist.txt';
            @file_put_contents($src_filelist, $filetxt); //初始化外掛檔案列表  
            // copy($src_filelist, $dst_filelist);
            /*--end*/
            $zip->addFile($src_filelist);
            $zip->close(); 

            /*壓縮外掛目錄*/
            if (!file_exists($zipName)) {
                $this->error('打包zip檔案包失敗！');
            }
            
            $this->success('打包成功', url('Weapp/pack'));

        }

        return $this->fetch();
    }

    /**
     * 壓縮檔案
     */
    private function zip($files = array(), $zipName){
        $zip = new \ZipArchive; //使用本類，linux需開啟zlib，windows需取消php_zip.dll前的註釋
        /*
         * 通過ZipArchive的對象處理zip檔案
         * $zip->open這個方法如果對zip檔案對像操作成功，$zip->open這個方法會返回TRUE
         * $zip->open這個方法第一個參數列示處理的zip檔名。
         * 這裡重點說下第二個參數，它表示處理模式
         * ZipArchive::OVERWRITE 總是以一個新的壓縮包開始，此模式下如果已經存在則會被覆蓋。
         * ZIPARCHIVE::CREATE 如果不存在則建立一個zip壓縮包，若存在系統就會往原來的zip檔案里新增內容。
         *
         * 這裡不得不說一個大坑。
         * 我的應用場景是需要每次都是建立一個新的壓縮包，如果之前存在，則直接覆蓋，不要追加
         * so，根據官方文件和參考其他程式碼，$zip->open的第二個參數我應該用 ZipArchive::OVERWRITE
         * 問題來了，當這個壓縮包不存在的時候，會報錯：ZipArchive::addFile(): Invalid or uninitialized Zip object
         * 也就是說，通過我的測試發現，ZipArchive::OVERWRITE 不會新建，只有目前存在這個壓縮包的時候，它才有效
         * 所以我的解決方案是 $zip->open($zipName, \ZIPARCHIVE::OVERWRITE | \ZIPARCHIVE::CREATE)
         *
         * 以上總結基於我目前的執行環境來說
         * */
        if ($zip->open($zipName, \ZIPARCHIVE::OVERWRITE | \ZIPARCHIVE::CREATE) !== TRUE) {
            return '無法打開檔案，或者檔案建立失敗';
        }
        foreach($files as $val){
            //$attachfile = $attachmentDir . $val['filepath']; //獲取原始檔案路徑
            if(file_exists($val)){
                //addFile函式首個參數如果帶有路徑，則壓縮的檔案里包含的是帶有路徑的檔案壓縮
                //若不希望帶有路徑，則需要該函式的第二個參數
                $zip->addFile($val, basename($val));//第二個參數是放在壓縮包中的檔名稱，如果檔案可能會有重複，就需要注意一下
            }
        }
        $zip->close();//關閉
 
        if(!file_exists($zipName)){
            return "無法找到檔案"; //即使建立，仍有可能失敗
        }
 
        //如果不要下載，下面這段刪掉即可，如需返回壓縮包下載鏈接，只需 return $zipName;
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header('Content-disposition: attachment; filename='.basename($zipName)); //檔名
        header("Content-Type: application/zip"); //zip格式的
        header("Content-Transfer-Encoding: binary"); //告訴瀏覽器，這是二進制檔案
        header('Content-Length: '. filesize($zipName)); //告訴瀏覽器，檔案大小
        @readfile($zipName);
    }

    /**
     * 驗證外掛標識是否同名
     */
    public function ajax_check_code($code)
    {
        $service_ey = base64_decode(config('service_ey'));
        $url = "{$service_ey}/index.php?m=api&c=Weapp&a=checkIsCode&code={$code}";
        $response = httpRequest($url, "GET");
        if (1 == intval($response)) {
            $this->success('外掛標識可使用！', url('Weapp/create'));
        } else if (-1 == intval($response)) {
            $this->error('外掛標識已被佔用！');
        }
        $this->error('遠端驗證外掛標識失敗！');
    }
}