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

return array(
    // 數據庫型別
    'type'            => 'mysql',
    // 伺服器地址
    'hostname'        => '127.0.0.1',
    // 數據庫名
    'database'        => 'o2design_o2EyouCMS',
    // 使用者名稱
    'username'        => 'root',
    // 密碼
    'password'        => 'ada*.914',
    // 埠
    'hostport'        => '3306',
    // 連線dsn
    'dsn'             => '',
    // 數據庫連線參數
    'params'          => array(),
    // 數據庫編碼預設採用utf8
    'charset'         => 'utf8',
    // 數據庫表字首
    'prefix'          => 'o2_',
    // 數據庫除錯模式
    'debug'           => false,
    // 數據庫部署方式:0 集中式(單一伺服器),1 分佈式(主從伺服器)
    'deploy'          => 0,
    // 數據庫讀寫是否分離 主從式有效
    'rw_separate'     => false,
    // 讀寫分離后 主伺服器數量
    'master_num'      => 1,
    // 指定從伺服器序號
    'slave_no'        => '',
    // 是否嚴格檢查欄位是否存在
    'fields_strict'   => true,
    // 數據集返回型別
    'resultset_type'  => 'array',
    // 自動寫入時間戳欄位
    'auto_timestamp'  => false,
    // 時間欄位取出后的預設時間格式
    'datetime_format' => 'Y-m-d H:i:s',
    // 是否需要進行SQL效能分析
    'sql_explain'     => false,
    // Builder類
    'builder'         => '',
    // Query類
    'query'           => '\\think\\db\\Query',
    // 是否需要斷線重連
    'break_reconnect' => true,
);
