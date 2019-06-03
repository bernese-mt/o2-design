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
use think\Db;
use think\Backup;

class Tools extends Base {

    public function _initialize() {
        parent::_initialize();
        $this->language_access(); // 多語言功能操作許可權
    }
    
    /**
     * 數據表列表
     */
    public function index()
    {
        $dbtables = Db::query('SHOW TABLE STATUS');
        $total = 0;
        $list = array();
        foreach ($dbtables as $k => $v) {
            if (preg_match('/^'.PREFIX.'/i', $v['Name'])) {
                $v['size'] = format_bytes($v['Data_length'] + $v['Index_length']);
                $list[$k] = $v;
                $total += $v['Data_length'] + $v['Index_length'];
            }
        }
        $path = tpCache('global.web_sqldatapath');
        $path = !empty($path) ? $path : config('DATA_BACKUP_PATH');
        @unlink(realpath(trim($path, '/')) . DS . 'backup.lock');
        // if (session('?backup_config.path')) {
            //備份完成，清空快取
            session('backup_tables', null);
            session('backup_file', null);
            session('backup_config', null);
        // }
        $this->assign('list', $list);
        $this->assign('total', format_bytes($total));
        $this->assign('tableNum', count($list));
        return $this->fetch();
    }

    /**
     * 數據備份
     */
    public function export($tables = null, $id = null, $start = null,$optstep = 0)
    {
        //防止備份數據過程超時
        function_exists('set_time_limit') && set_time_limit(0);

        /*升級完自動備份所有數據表*/
        if ('all' == $tables) {
            $dbtables = Db::query('SHOW TABLE STATUS');
            $list = array();
            foreach ($dbtables as $k => $v) {
                if (preg_match('/^'.PREFIX.'/i', $v['Name'])) {
                    $list[] = $v['Name'];
                }
            }
            $tables = $list;
            unlink(session('backup_config.path') . 'backup.lock');
        }
        /*--end*/

        if(IS_POST && !empty($tables) && is_array($tables) && empty($optstep)){ //初始化
            $path = tpCache('global.web_sqldatapath');
            $path = !empty($path) ? $path : config('DATA_BACKUP_PATH');
            $path = trim($path, '/');
            if(!empty($path) && !is_dir($path)){
                mkdir($path, 0755, true);
            }

            //讀取備份配置
            $config = array(
                'path'     => realpath($path) . DS,
                'part'     => config('DATA_BACKUP_PART_SIZE'),
                'compress' => config('DATA_BACKUP_COMPRESS'),
                'level'    => config('DATA_BACKUP_COMPRESS_LEVEL'),
            );
            //檢查是否有正在執行的任務
            $lock = "{$config['path']}backup.lock";
            if(is_file($lock)){
                return json(array('info'=>'檢測到有一個備份任務正在執行，請稍後再試！', 'status'=>0, 'url'=>''));
            } else {
                //建立鎖檔案
                file_put_contents($lock, $_SERVER['REQUEST_TIME']);
            }

            //檢查備份目錄是否可寫
            if(!is_writeable($config['path'])){
                return json(array('info'=>'備份目錄不存在或不可寫，請檢查後重試！', 'status'=>0, 'url'=>''));
            }
            session('backup_config', $config);

            //產生備份檔案資訊
            $file = array(
                'name' => date('Ymd-His', $_SERVER['REQUEST_TIME']),
                'part' => 1,
                'version' => getCmsVersion(),
            );
            session('backup_file', $file);
            //快取要備份的表
            session('backup_tables', $tables);
            //建立備份檔案
            $Database = new Backup($file, $config);
            if(false !== $Database->create()){
                $speed = (floor((1/count($tables))*10000)/10000*100);
                $speed = sprintf("%.2f", $speed);
                $tab = array('id' => 0, 'start' => 0, 'speed'=>$speed, 'table'=>$tables[0], 'optstep'=>1);
                return json(array('tables' => $tables, 'tab' => $tab, 'info'=>'初始化成功！', 'status'=>1, 'url'=>''));
            } else {
                return json(array('info'=>'初始化失敗，備份檔案建立失敗！', 'status'=>0, 'url'=>''));
            }
        } elseif (IS_POST && is_numeric($id) && is_numeric($start) && 1 == intval($optstep)) { //備份數據
            $tables = session('backup_tables');
            //備份指定表
            $Database = new Backup(session('backup_file'), session('backup_config'));
            $start  = $Database->backup($tables[$id], $start);
            if(false === $start){ //出錯
                return json(array('info'=>'備份出錯！', 'status'=>0, 'url'=>''));
            } elseif (0 === $start) { //下一表
                if(isset($tables[++$id])){
                    $speed = (floor((($id+1)/count($tables))*10000)/10000*100);
                    $speed = sprintf("%.2f", $speed);
                    $tab = array('id' => $id, 'start' => 0, 'speed' => $speed, 'table'=>$tables[$id], 'optstep'=>1);
                    return json(array('tab' => $tab, 'info'=>'備份完成！', 'status'=>1, 'url'=>''));
                } else { //備份完成，清空快取
                    /*自動覆蓋安裝目錄下的eyoucms.sql*/
                    $install_time = DEFAULT_INSTALL_DATE;
                    $constsant_path = APP_PATH.MODULE_NAME.'/conf/constant.php';
                    if (file_exists($constsant_path)) {
                        require_once($constsant_path);
                        defined('INSTALL_DATE') && $install_time = INSTALL_DATE;
                    }
                    $install_path = ROOT_PATH.'install';
                    if (!is_dir($install_path) || !file_exists($install_path)) {
                        $install_path = ROOT_PATH.'install_'.$install_time;
                    }
                    if (is_dir($install_path) && file_exists($install_path)) {
                        $srcfile = session('backup_config.path').session('backup_file.name').'-'.session('backup_file.part').'-'.session('backup_file.version').'.sql';
                        $dstfile = $install_path.'/eyoucms.sql';
                        if(@copy($srcfile, $dstfile)){
                            /*替換所有表的字首為官方預設ey_，並重寫安裝數據包里*/
                            $eyouDbStr = file_get_contents($dstfile);
                            $dbtables = Db::query('SHOW TABLE STATUS');
                            foreach ($dbtables as $k => $v) {
                                $tableName = $v['Name'];
                                if (preg_match('/^'.PREFIX.'/i', $tableName)) {
                                    $eyTableName = preg_replace('/^'.PREFIX.'/i', 'ey_', $tableName);
                                    $eyouDbStr = str_replace('`'.$tableName.'`', '`'.$eyTableName.'`', $eyouDbStr);
                                }
                            }
                            @file_put_contents($dstfile, $eyouDbStr);
                            /*--end*/
                        } else {
                            @unlink($dstfile); // 複製失敗就刪掉，避免安裝錯誤的數據包
                        }
                    }
                    /*--end*/
                    unlink(session('backup_config.path') . 'backup.lock');
                    session('backup_tables', null);
                    session('backup_file', null);
                    session('backup_config', null);
                    return json(array('info'=>'備份完成！', 'status'=>1, 'url'=>''));
                }
            } else {
                $rate = floor(100 * ($start[0] / $start[1]));
                $speed = floor((($id+1)/count($tables))*10000)/10000*100 + ($rate/100);
                $speed = sprintf("%.2f", $speed);
                $tab  = array('id' => $id, 'start' => $start[0], 'speed' => $speed, 'table'=>$tables[$id], 'optstep'=>1);
                return json(array('tab' => $tab, 'info'=>"正在備份...({$rate}%)", 'status'=>1, 'url'=>''));
            }

        } else {//出錯
            return json(array('info'=>'參數有誤', 'status'=>0, 'url'=>''));
        }
    }
        
    /**
     * 優化
     */
    public function optimize()
    {
        $batchFlag = input('get.batchFlag', 0, 'intval');
        //批量刪除
        if ($batchFlag) {
            $table = input('key', array());
        }else {
            $table[] = input('tablename' , '');
        }
    
        if (empty($table)) {
            $this->error('請選擇數據表');
        }

        $strTable = implode(',', $table);
        if (!DB::query("OPTIMIZE TABLE {$strTable} ")) {
            $strTable = '';
        }
        $this->success("操作成功" . $strTable, url('Tools/index'));
    
    }
    
    /**
     * 修復
     */
    public function repair()
    {
        $batchFlag = input('get.batchFlag', 0, 'intval');
        //批量刪除
        if ($batchFlag) {
            $table = input('key', array());
        }else {
            $table[] = input('tablename' , '');
        }
    
        if (empty($table)) {
            $this->error('請選擇數據表');
        }
    
        $strTable = implode(',', $table);
        if (!DB::query("REPAIR TABLE {$strTable} ")) {
            $strTable = '';
        }
    
        $this->success("操作成功" . $strTable, url('Tools/index'));
  
    }

    /**
     * 數據還原
     */
    public function restore()
    {
        $path = tpCache('global.web_sqldatapath');
        $path = !empty($path) ? $path : config('DATA_BACKUP_PATH');
        $path = trim($path, '/');
        if(!empty($path) && !is_dir($path)){
            mkdir($path, 0755, true);
        }
        $path = realpath($path);
        $flag = \FilesystemIterator::KEY_AS_FILENAME;
        $glob = new \FilesystemIterator($path,  $flag);
        $list = array();
        $filenum = $total = 0;
        foreach ($glob as $name => $file) {
            if(preg_match('/^\d{8,8}-\d{6,6}-\d+-v\d+\.\d+\.\d+\.sql(?:\.gz)?$/', $name)){
                $name = sscanf($name, '%4s%2s%2s-%2s%2s%2s-%d-%s');
                $date = "{$name[0]}-{$name[1]}-{$name[2]}";
                $time = "{$name[3]}:{$name[4]}:{$name[5]}";
                $part = $name[6];
                $version = preg_replace('#\.sql(.*)#i', '', $name[7]);
                $info = pathinfo($file);
                if(isset($list["{$date} {$time}"])){
                    $info = $list["{$date} {$time}"];
                    $info['part'] = max($info['part'], $part);
                    $info['size'] = $info['size'] + $file->getSize();
                } else {
                    $info['part'] = $part;
                    $info['size'] = $file->getSize();
                }
                $info['compress'] = ($info['extension'] === 'sql') ? '-' : $info['extension'];
                $info['time']  = strtotime("{$date} {$time}");
                $info['version']  = $version;
                $filenum++;
                $total += $info['size'];
                $list["{$date} {$time}"] = $info;
            }
        }
        array_multisort($list, SORT_DESC);
        $this->assign('list', $list);
        $this->assign('filenum',$filenum);
        $this->assign('total',$total);
        return $this->fetch();
    }

    /**
     * 上傳sql檔案
     */
    public function restoreUpload()
    {
        $file = request()->file('sqlfile');
        if(empty($file)){
            $this->error('請上傳sql檔案');
        }
        // 移動到框架應用根目錄/data/sqldata/ 目錄下
        $path = tpCache('global.web_sqldatapath');
        $path = !empty($path) ? $path : config('DATA_BACKUP_PATH');
        $path = trim($path, '/');
        $image_upload_limit_size = intval(tpCache('basic.file_size') * 1024 * 1024);
        $info = $file->validate(['size'=>$image_upload_limit_size,'ext'=>'sql,gz'])->move($path, $_FILES['sqlfile']['name']);
        if ($info) {
            //上傳成功 獲取上傳檔案資訊
            $file_path_full = $info->getPathName();
            if (file_exists($file_path_full)) {
                $sqls = Backup::parseSql($file_path_full);
                if(Backup::install($sqls)){
//                    array_map("unlink", glob($path));
                    /*清除快取*/
                    delFile(RUNTIME_PATH);
                    /*--end*/
                    $this->success("執行sql成功", url('Tools/restore'));
                }else{
                    $this->error('執行sql失敗');
                }
            } else {
                $this->error('sql檔案上傳失敗');
            }
        } else {
            //上傳錯誤提示錯誤資訊
            $this->error($file->getError());
        }
    }

    /**
     * 執行還原數據庫操作
     * @param int $time
     * @param null $part
     * @param null $start
     */
    public function import($time = 0, $part = null, $start = null)
    {
        function_exists('set_time_limit') && set_time_limit(0);

        if(is_numeric($time) && is_null($part) && is_null($start)){ //初始化
            //獲取備份檔案資訊
            $name  = date('Ymd-His', $time) . '-*.sql*';
            $path = tpCache('global.web_sqldatapath');
            $path = !empty($path) ? $path : config('DATA_BACKUP_PATH');
            $path = trim($path, '/');
            $path  = realpath($path) . DS . $name;
            $files = glob($path);
            $list  = array();
            foreach($files as $name){
                $basename = basename($name);
                $match    = sscanf($basename, '%4s%2s%2s-%2s%2s%2s-%d');
                $gz       = preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql.gz$/', $basename);
                $list[$match[6]] = array($match[6], $name, $gz);
            }
            ksort($list);

            //檢測檔案正確性
            $last = end($list);
            if(count($list) === $last[0]){
                session('backup_list', $list); //快取備份列表
                $part = 1;
                $start = 0;
                $data = array('part' => $part, 'start' => $start);
                // $this->success('初始化完成！', null, array('part' => $part, 'start' => $start));
                respose(array('code'=>1, 'msg'=>"初始化完成！準備還原#{$part}...", 'rate'=>'', 'data'=>$data));
            } else {
                // $this->error('備份檔案可能已經損壞，請檢查！');
                respose(array('code'=>0, 'msg'=>"備份檔案可能已經損壞，請檢查！"));
            }
        } elseif(is_numeric($part) && is_numeric($start)) {
            $list  = session('backup_list');
            $path = tpCache('global.web_sqldatapath');
            $path = !empty($path) ? $path : config('DATA_BACKUP_PATH');
            $path = trim($path, '/');
            $db = new Backup($list[$part], array(
                    'path'     => realpath($path) . DS,
                    'compress' => $list[$part][2]));
            $start = $db->import($start);
            if(false === $start){
                // $this->error('還原數據出錯！');
                respose(array('code'=>0, 'msg'=>"還原數據出錯！", 'rate'=>'0%'));
            } elseif(0 === $start) { //下一卷
                if(isset($list[++$part])){
                    $data = array('part' => $part, 'start' => 0);
                    // $this->success("正在還g原...#{$part}", null, $data);
                    $rate = (floor((($start+1)/count($list))*10000)/10000*100).'%';
                    respose(array('code'=>1, 'msg'=>"正在還原#{$part}...", 'rate'=>$rate, 'data'=>$data));
                } else {
                    session('backup_list', null);
                    delFile(RUNTIME_PATH);
                    respose(array('code'=>1, 'msg'=>"還原完成...", 'rate'=>'100%'));
                    // $this->success('還原完成！');
                }
            } else {
                $data = array('part' => $part, 'start' => $start[0]);
                if($start[1]){
                    $rate = floor(100 * ($start[0] / $start[1])).'%';
                    respose(array('code'=>1, 'msg'=>"正在還原#{$part}...", 'rate'=>$rate, 'data'=>$data));
                    // $this->success("正在還d原...#{$part} ({$rate}%)", null, $data);
                } else {
                    $data['gz'] = 1;
                    respose(array('code'=>1, 'msg'=>"正在還原#{$part}...", 'data'=>$data, 'start'=>$start));
                    // $this->success("正在還s原...#{$part}", null, $data);
                }
            }
        } else {
            // $this->error('參數錯誤！');
            respose(array('code'=>0, 'msg'=>"參數有誤", 'rate'=>'0%'));
        }
    }

    /**
     * (新)執行還原數據庫操作
     * @param int $time
     */
    public function new_import($time = 0)
    {
        function_exists('set_time_limit') && set_time_limit(0);

        if(is_numeric($time) && intval($time) > 0){
            //獲取備份檔案資訊
            $name  = date('Ymd-His', $time) . '-*.sql*';
            $path = tpCache('global.web_sqldatapath');
            $path = !empty($path) ? $path : config('DATA_BACKUP_PATH');
            $path = trim($path, '/');
            $path  = realpath($path) . DS . $name;
            $files = glob($path);
            $list  = array();
            foreach($files as $name){
                $basename = basename($name);
                $match    = sscanf($basename, '%4s%2s%2s-%2s%2s%2s-%d-%s');
                $gz       = preg_match('/^\d{8,8}-\d{6,6}-\d+-v\d+\.\d+\.\d+\.sql.gz$/', $basename);
                $list[$match[6]] = array($match[6], $name, $gz);
            }
            ksort($list);

            //檢測檔案正確性
            $last = end($list);
            $file_path_full = !empty($last[1]) ? $last[1] : '';
            if (file_exists($file_path_full)) {
                /*校驗sql檔案是否屬於目前CMS版本*/
                preg_match('/(\d{8,8})-(\d{6,6})-(\d+)-(v\d+\.\d+\.\d+)\.sql/i', $file_path_full, $matches);
                $version = getCmsVersion();
                if ($matches[4] != $version) {
                    $this->error('sql不相容目前版本：'.$version, url('Tools/restore'));
                }
                /*--end*/
                $sqls = Backup::parseSql($file_path_full);
                if(Backup::install($sqls)){
                    /*清除快取*/
                    delFile(RUNTIME_PATH);
                    /*--end*/
                    $this->success('操作成功', request()->baseFile(), '', 1, [], '_parent');
                }else{
                    $this->error('操作失敗！', url('Tools/restore'));
                }
            }
        }
        else 
        {
            $this->error("參數有誤", url('Tools/restore'));
        }
        exit;
    }

    /**
     * 下載
     * @param int $time
     */
    public function downFile($time = 0)
    {
        $name  = date('Ymd-His', $time) . '-*.sql*';
        $path = tpCache('global.web_sqldatapath');
        $path = !empty($path) ? $path : config('DATA_BACKUP_PATH');
        $path = trim($path, '/');
        $path  = realpath($path) . DS . $name;
        $files = glob($path);
        if(is_array($files)){
            foreach ($files as $filePath){
                if (!file_exists($filePath)) {
                    $this->error("該檔案不存在，可能是被刪除");
                }else{
                    $filename = basename($filePath);
                    header("Content-type: application/octet-stream");
                    header('Content-Disposition: attachment; filename="' . $filename . '"');
                    header("Content-Length: " . filesize($filePath));
                    readfile($filePath);
                }
            }
        }
    }

    /**
     * 刪除備份檔案
     * @param  Integer $time 備份時間
     */
    public function del()
    {
        $time_arr = input('del_id/a');
        $time_arr = eyIntval($time_arr);
        if(is_array($time_arr) && !empty($time_arr)){
            foreach ($time_arr as $key => $val) {
                $name  = date('Ymd-His', $val) . '-*.sql*';
                $path = tpCache('global.web_sqldatapath');
                $path = !empty($path) ? $path : config('DATA_BACKUP_PATH');
                $path = trim($path, '/');
                $path  = realpath($path) . DS . $name;
                array_map("unlink", glob($path));
                if(count(glob($path))){
                    $this->error('備份檔案刪除失敗，請檢查目錄許可權！');
                }
            }
            $this->success('刪除成功！');
        } else {
            $this->error('參數有誤');
        }
    }
}