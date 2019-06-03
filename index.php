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
header("Content-type:text/html;charset=utf-8");
// [ 應用入口檔案 ]
if (extension_loaded('zlib')){
    try{
        ob_end_clean();
    } catch(Exception $e) {

    }
    ob_start('ob_gzhandler');
}
// 檢測PHP環境
if(version_compare(PHP_VERSION,'5.4.0','<'))  die('本系統要求PHP版本 >= 5.4.0，目前PHP版本為：'.PHP_VERSION . '，請到虛擬主機控制面板里切換PHP版本，或聯繫空間商協助切換。<a href="http://www.eyoucms.com/help/" target="_blank">點選檢視易優安裝教程</a>');
// error_reporting(E_ALL ^ E_NOTICE);//顯示除去 E_NOTICE 之外的所有錯誤資訊
error_reporting(E_ERROR | E_WARNING | E_PARSE);//報告執行時錯誤

// 檢測是否已安裝EyouCMS系統
if(file_exists("./install/") && !file_exists("./install/install.lock")){
    header('Location:./install/index.php');
    exit(); 
}

// 快取時間
define('EYOUCMS_CACHE_TIME', 86400);
// 數據絕對路徑
define('DATA_PATH', __DIR__ . '/data/');
// 執行快取
define('RUNTIME_PATH', DATA_PATH . 'runtime/');
// 安裝程式定義
define('DEFAULT_INSTALL_DATE',1525756440);
// 序列號
define('DEFAULT_SERIALNUMBER','20180508131400oCWIoa');
// 定義應用目錄
define('APP_PATH', __DIR__ . '/application/');
// 載入框架引導檔案
require __DIR__ . '/core/start.php';
