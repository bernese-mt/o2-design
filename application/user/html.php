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

// 全域性變數陣列
$global = tpCache('global');
// 系統模式
$web_cmsmode = isset($global['web_cmsmode']) ? $global['web_cmsmode'] : 2;
/*頁面快取有效期*/
$app_debug = true;
$web_htmlcache_expires_in = -1;
if (1 == $web_cmsmode) { // 運營模式
    $app_debug = false;
}
/*--end*/

return array(
    // 應用除錯模式
    'app_debug' => $app_debug,
    // 模板設定
    'template' => array(
        // 模板路徑
        'view_path' => './template/',
        // 模板後綴
        'view_suffix' => 'htm',
        // 模板引擎禁用函式
        'tpl_deny_func_list' => 'eval,echo,exit',
        // 預設模板引擎是否禁用PHP原生程式碼 苦惱啊！ 鑑於百度統計使用原生php，這裡暫時無法開啟
        'tpl_deny_php'       => false,
    ),
    // 檢視輸出字串內容替換
    'view_replace_str' => array(
        '__EVAL__'  => '', // 過濾模板里的eval函式，防止被注入
    ),
);
?>