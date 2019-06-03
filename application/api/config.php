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

// 載入系統語言包
/*\think\Lang::load([
    APP_PATH . 'admin' . DS . 'lang' . DS . request()->langset() . EXT,
]);*/

$api_config = array(

    // +----------------------------------------------------------------------
    // | 異常及錯誤設定
    // +----------------------------------------------------------------------

    // 異常頁面的模板檔案 
    //'exception_tmpl'         => ROOT_PATH.'public/static/errpage/404.html',
    // errorpage 錯誤頁面
    //'error_tmpl'         => ROOT_PATH.'public/static/errpage/404.html',

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
return array_merge($api_config, $html_config);
?>