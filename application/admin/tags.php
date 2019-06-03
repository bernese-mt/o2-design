<?php

// 應用行為擴充套件定義檔案
return array(
    // 模組初始化
    'module_init'  => array(
        'app\\admin\\behavior\\ModuleInitBehavior',
    ),
    // 操作開始執行
    'action_begin' => array(
        'app\\admin\\behavior\\AuthRoleBehavior',
        'app\\admin\\behavior\\ActionBeginBehavior',
    ),
    // 檢視內容過濾
    'view_filter'  => array(),
    // 日誌寫入
    'log_write'    => array(),
    // 應用結束
    'app_end'      => array(
        'app\\admin\\behavior\\AppEndBehavior',
    ),
);
