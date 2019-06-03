<?php

// 應用行為擴充套件定義檔案

/*引入全部外掛的app_init行為*/
$app_init = [
    'app\\common\\behavior\\AppInitBehavior',
];
$files = glob(WEAPP_DIR_NAME.DS.'*'.DS.'behavior'.DS.'AppInitBehavior.php');
if (!empty($files)) {
    foreach ($files as $key => $file) {
        if (is_file($file) && file_exists($file)) {
            $fileStr = str_replace('/', '\\', $file);
            $fileStr = str_replace('.php', '', $fileStr);
            array_push($app_init, $fileStr);
        }
    }
}
/*--end*/

/*引入全部外掛的app_begin行為*/
$app_begin = [];
$files = glob(WEAPP_DIR_NAME.DS.'*'.DS.'behavior'.DS.'AppBeginBehavior.php');
if (!empty($files)) {
    foreach ($files as $key => $file) {
        if (is_file($file) && file_exists($file)) {
            $fileStr = str_replace('/', '\\', $file);
            $fileStr = str_replace('.php', '', $fileStr);
            array_push($app_begin, $fileStr);
        }
    }
}
/*--end*/

/*引入全部外掛的app_begin行為*/
$module_init = [];
$files = glob(WEAPP_DIR_NAME.DS.'*'.DS.'behavior'.DS.'ModuleInitBehavior.php');
if (!empty($files)) {
    foreach ($files as $key => $file) {
        if (is_file($file) && file_exists($file)) {
            $fileStr = str_replace('/', '\\', $file);
            $fileStr = str_replace('.php', '', $fileStr);
            array_push($module_init, $fileStr);
        }
    }
}
/*--end*/

/*引入全部外掛的action_begin行為*/
$action_begin = [];
$files = glob(WEAPP_DIR_NAME.DS.'*'.DS.'behavior'.DS.'ActionBeginBehavior.php');
if (!empty($files)) {
    foreach ($files as $key => $file) {
        if (is_file($file) && file_exists($file)) {
            $fileStr = str_replace('/', '\\', $file);
            $fileStr = str_replace('.php', '', $fileStr);
            array_push($action_begin, $fileStr);
        }
    }
}
/*--end*/

/*引入全部外掛的view_filter行為*/
$view_filter = [];
$files = glob(WEAPP_DIR_NAME.DS.'*'.DS.'behavior'.DS.'ViewFilterBehavior.php');
if (!empty($files)) {
    foreach ($files as $key => $file) {
        if (is_file($file) && file_exists($file)) {
            $fileStr = str_replace('/', '\\', $file);
            $fileStr = str_replace('.php', '', $fileStr);
            array_push($view_filter, $fileStr);
        }
    }
}
/*--end*/

/*引入全部外掛的log_write行為*/
$log_write = [];
$files = glob(WEAPP_DIR_NAME.DS.'*'.DS.'behavior'.DS.'LogWriteBehavior.php');
if (!empty($files)) {
    foreach ($files as $key => $file) {
        if (is_file($file) && file_exists($file)) {
            $fileStr = str_replace('/', '\\', $file);
            $fileStr = str_replace('.php', '', $fileStr);
            array_push($log_write, $fileStr);
        }
    }
}
/*--end*/

/*引入全部外掛的app_end行為*/
$app_end = [];
$files = glob(WEAPP_DIR_NAME.DS.'*'.DS.'behavior'.DS.'AppEndBehavior.php');
if (!empty($files)) {
    foreach ($files as $key => $file) {
        if (is_file($file) && file_exists($file)) {
            $fileStr = str_replace('/', '\\', $file);
            $fileStr = str_replace('.php', '', $fileStr);
            array_push($app_end, $fileStr);
        }
    }
}
/*--end*/

return array(
    // 應用初始化
    'app_init'     => $app_init,
    // 應用開始
    'app_begin'    => $app_begin,
    // 模組初始化
    'module_init'  => $module_init,
    // 操作開始執行
    'action_begin' => $action_begin,
    // 檢視內容過濾
    'view_filter'  => $view_filter,
    // 日誌寫入
    'log_write'    => $log_write,
    // 應用結束
    'app_end'      => $app_end,
);
