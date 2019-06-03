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

$home_config = array(
    //分頁配置
    'paginate'      => array(
        'type'      => 'eyou',
        'var_page'  => 'page',
        'list_rows' => 15,
    ),

    // +----------------------------------------------------------------------
    // | 異常及錯誤設定
    // +----------------------------------------------------------------------

    // 異常頁面的模板檔案 
    //'exception_tmpl'         => ROOT_PATH.'public/static/errpage/404.html',
    // errorpage 錯誤頁面
    //'error_tmpl'         => ROOT_PATH.'public/static/errpage/404.html',
    
    /**假設這個訪問地址是 www.xxxxx.dev/index/goods/goodsInfo/id/1.html 
     *就儲存名字為 index_goods_goodsinfo_1.html     
     *配置成這樣, 指定 模組 控制器 方法名 參數名
     */
    'HTML_CACHE_ARR'=> array(),
);

$html_config = include_once 'html.php';
return array_merge($home_config, $html_config);
?>