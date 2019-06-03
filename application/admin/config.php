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

$admin_ey_config = [
    'seo_pseudo'    => 1, // 預設純動態URL模式，相容不支援pathinfo環境
    'seo_dynamic_format'    => 1, // 1=相容模式的URL，2=偽動態
    'seo_rewrite_format'    => config('ey_config.seo_rewrite_format'),
    'system_sql_mode'   => config('ey_config.system_sql_mode'), // 數據庫模式
    'seo_inlet' => config('ey_config.seo_inlet'), // 0=保留入口檔案，1=隱藏入口檔案
];
$ey_config = array_merge(config('ey_config'), $admin_ey_config);

$admin_config = array(
    'ey_config' => $ey_config,
    //分頁配置
    'paginate'      => array(
        'list_rows' => 15,
    ),
    // 預設全域性過濾方法 用逗號分隔多個
    'default_filter'         => 'htmlspecialchars', // htmlspecialchars
    // 登錄有效期
    'login_expire' => 3600,
    // +----------------------------------------------------------------------
    // | 模板設定
    // +----------------------------------------------------------------------
    // 預設成功跳轉對應的模板檔案
    'dispatch_success_tmpl'  => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',
    // 預設錯誤跳轉對應的模板檔案
    'dispatch_error_tmpl'    => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',

    // +----------------------------------------------------------------------
    // | 異常及錯誤設定
    // +----------------------------------------------------------------------

    // 異常頁面的模板檔案 
    //'exception_tmpl'         => ROOT_PATH.'public/static/errpage/404.html',
    // errorpage 錯誤頁面
    //'error_tmpl'         => ROOT_PATH.'public/static/errpage/404.html',

    /**假設這個訪問地址是 www.xxxxx.dev/home/goods/goodsInfo/id/1.html 
     *就儲存名字為 home_goods_goodsinfo_1.html     
     *配置成這樣, 指定 模組 控制器 方法名 參數名
     */
    'HTML_CACHE_STATUS' => false,
    
    // 控制器與操作名之間的連線符
    'POWER_OPERATOR' => '@',

    // 數據管理
    'DATA_BACKUP_PATH' => '/data/sqldata', //數據庫備份根路徑
    'DATA_BACKUP_PART_SIZE' => 52428800, //數據庫備份卷大小 50M
    'DATA_BACKUP_COMPRESS' => 0, //數據庫備份檔案是否啟用壓縮
    'DATA_BACKUP_COMPRESS_LEVEL' => 9, //數據庫備份檔案壓縮級別

    // 過濾不需要登錄的操作
    'filter_login_action' => array(
        'Admin@login', // 登錄
        'Admin@logout', // 退出
        'Admin@vertify', // 驗證碼
    ),
    
    // 無需驗證許可權的操作
    'uneed_check_action' => array(
        'Base@*', // 基類
        'Index@*', // 後臺首頁
        'Ajax@*', // 所有ajax操作
        'Ueditor@*', // 編輯器上傳
        'Uploadify@*', // 圖片上傳
    ),
);

$html_config = include_once 'html.php';
return array_merge($admin_config, $html_config);
?>