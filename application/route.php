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

$home_rewrite = array();
$route = array(
    '__pattern__' => array(
        'tid' => '\w+',
        'aid' => '\d+',
    ),
    '__alias__' => array(),
    '__domain__' => array(),
);

$globalConfig = tpCache('global');
// mysql的sql-mode模式參數
$system_sql_mode = !empty($globalConfig['system_sql_mode']) ? $globalConfig['system_sql_mode'] : config('ey_config.system_sql_mode');
config('ey_config.system_sql_mode', $system_sql_mode);
// 多語言數量
$system_langnum = !empty($globalConfig['system_langnum']) ? intval($globalConfig['system_langnum']) : config('ey_config.system_langnum');
config('ey_config.system_langnum', $system_langnum);
// 前臺預設語言
$system_home_default_lang = !empty($globalConfig['system_home_default_lang']) ? $globalConfig['system_home_default_lang'] : config('ey_config.system_home_default_lang');
config('ey_config.system_home_default_lang', $system_home_default_lang);
// URL模式
$seo_pseudo = !empty($globalConfig['seo_pseudo']) ? intval($globalConfig['seo_pseudo']) : config('ey_config.seo_pseudo');
// 是否https鏈接
$is_https = !empty($globalConfig['web_is_https']) ? true : config('is_https');
config('is_https', $is_https);

$uiset = I('param.uiset/s', 'off');
if ('on' == trim($uiset, '/')) { // 視覺化頁面必須是相容模式的URL
    config('ey_config.seo_inlet', 0);
    config('ey_config.seo_pseudo', 1);
    config('ey_config.seo_dynamic_format', 1);
} else {
    // URL模式
    config('ey_config.seo_pseudo', $seo_pseudo);
    // 動態URL格式
    $seo_dynamic_format = !empty($globalConfig['seo_dynamic_format']) ? intval($globalConfig['seo_dynamic_format']) : config('ey_config.seo_dynamic_format');
    config('ey_config.seo_dynamic_format', $seo_dynamic_format);
    // 偽靜態格式
    $seo_rewrite_format = !empty($globalConfig['seo_rewrite_format']) ? intval($globalConfig['seo_rewrite_format']) : config('ey_config.seo_rewrite_format');
    config('ey_config.seo_rewrite_format', $seo_rewrite_format); 
    // 是否隱藏入口檔案
    $seo_inlet = !empty($globalConfig['seo_inlet']) ? $globalConfig['seo_inlet'] : config('ey_config.seo_inlet');
    config('ey_config.seo_inlet', $seo_inlet);

    if (3 == $seo_pseudo) {
        $lang_rewrite = [];
        $lang_rewrite_str = '';
        /*多語言*/
        $lang = input('param.lang/s');
        if (is_language()) {
            if (!stristr($request->baseFile(), 'index.php')) {
                if (!empty($lang) && $lang != $system_home_default_lang) {
                    $lang_rewrite_str = '<lang>/';
                    $lang_rewrite = [
                        // 首頁
                        $lang_rewrite_str.'$' => array('home/Index/index',array('method' => 'get', 'ext' => ''), 'cache'=>1),
                    ];
                }
            } else {
                if (get_current_lang() != get_default_lang()) {
                    $lang_rewrite_str = '<lang>/';
                    $lang_rewrite = [
                        // 首頁
                        $lang_rewrite_str.'$' => array('home/Index/index',array('method' => 'get', 'ext' => ''), 'cache'=>1),
                    ];
                }
            }
        }
        /*--end*/
        if (1 == $seo_rewrite_format) { // 精簡偽靜態
            $home_rewrite = array(
                // 會員中心
                $lang_rewrite_str.'user$' => array('user/Users/login',array('ext' => ''), 'cache'=>1),
                $lang_rewrite_str.'reg$' => array('user/Users/reg',array('ext' => ''), 'cache'=>1),
                $lang_rewrite_str.'centre$' => array('user/Users/centre',array('ext' => ''), 'cache'=>1),
                $lang_rewrite_str.'user/index$' => array('user/Users/index',array('ext' => ''), 'cache'=>1),
                // 留言提交
                $lang_rewrite_str.'guestbook/submit$' => array('home/Lists/gbook_submit',array('method' => 'post', 'ext' => 'html'), 'cache'=>1),
                // 下載檔案
                $lang_rewrite_str.'downfile/<id>/<uhash>$' => array('home/View/downfile',array('method' => 'get', 'ext' => 'html'),'cache'=>1),
                // 標籤偽靜態
                $lang_rewrite_str.'tags$' => array('home/Tags/index',array('method' => 'get', 'ext' => ''), 'cache'=>1),
                $lang_rewrite_str.'tags/<tagid>$' => array('home/Tags/lists',array('method' => 'get', 'ext' => 'html'), 'cache'=>1),
                // 搜索偽靜態
                $lang_rewrite_str.'search$' => array('home/Search/lists',array('method' => 'get', 'ext' => 'html'), 'cache'=>1),
                // 列表頁
                $lang_rewrite_str.'<tid>$' => array('home/Lists/index',array('method' => 'get', 'ext' => ''), 'cache'=>1),
                // 內容頁
                $lang_rewrite_str.'<dirname>/<aid>$' => array('home/View/index',array('method' => 'get', 'ext' => 'html'),'cache'=>1),
            );
        } else {
            $home_rewrite = array(
                // 會員中心
                $lang_rewrite_str.'Users/login$' => array('user/Users/login',array('ext' => 'html'), 'cache'=>1),
                $lang_rewrite_str.'Users/reg$' => array('user/Users/reg',array('ext' => 'html'), 'cache'=>1),
                $lang_rewrite_str.'Users/centre$' => array('user/Users/centre',array('ext' => 'html'), 'cache'=>1),
                $lang_rewrite_str.'Users/index$' => array('user/Users/index',array('ext' => 'html'), 'cache'=>1),
                // 文章模型偽靜態
                $lang_rewrite_str.'article$' => array('home/Article/index',array('method' => 'get', 'ext' => 'html'), 'cache'=>1),
                $lang_rewrite_str.'article/<tid>$' => array('home/Article/lists',array('method' => 'get', 'ext' => 'html'), 'cache'=>1),
                $lang_rewrite_str.'article/<dirname>/<aid>$' => array('home/Article/view',array('method' => 'get', 'ext' => 'html'),'cache'=>1),
                // 產品模型偽靜態
                $lang_rewrite_str.'product$' => array('home/Product/index',array('method' => 'get', 'ext' => 'html'), 'cache'=>1),
                $lang_rewrite_str.'product/<tid>$' => array('home/Product/lists',array('method' => 'get', 'ext' => 'html'), 'cache'=>1),
                $lang_rewrite_str.'product/<dirname>/<aid>$' => array('home/Product/view',array('method' => 'get', 'ext' => 'html'),'cache'=>1),
                // 圖集模型偽靜態
                $lang_rewrite_str.'images$' => array('home/Images/index',array('method' => 'get', 'ext' => 'html'), 'cache'=>1),
                $lang_rewrite_str.'images/<tid>$' => array('home/Images/lists',array('method' => 'get', 'ext' => 'html'), 'cache'=>1),
                $lang_rewrite_str.'images/<dirname>/<aid>$' => array('home/Images/view',array('method' => 'get', 'ext' => 'html'),'cache'=>1),
                // 下載模型偽靜態
                $lang_rewrite_str.'download$' => array('home/Download/index',array('method' => 'get', 'ext' => 'html'), 'cache'=>1),
                $lang_rewrite_str.'download/<tid>$' => array('home/Download/lists',array('method' => 'get', 'ext' => 'html'), 'cache'=>1),
                $lang_rewrite_str.'download/<dirname>/<aid>$' => array('home/Download/view',array('method' => 'get', 'ext' => 'html'),'cache'=>1),
                $lang_rewrite_str.'downfile/<id>/<uhash>$' => array('home/View/downfile',array('method' => 'get', 'ext' => 'html'),'cache'=>1),
                // 單頁模型偽靜態
                $lang_rewrite_str.'single$' => array('home/Single/index',array('method' => 'get', 'ext' => 'html'), 'cache'=>1),
                $lang_rewrite_str.'single/<tid>$' => array('home/Single/lists',array('method' => 'get', 'ext' => 'html'), 'cache'=>1),
                // 標籤偽靜態
                $lang_rewrite_str.'tags$' => array('home/Tags/index',array('method' => 'get', 'ext' => ''), 'cache'=>1),
                $lang_rewrite_str.'tags/<tagid>$' => array('home/Tags/lists',array('method' => 'get', 'ext' => 'html'), 'cache'=>1),
                // 搜索偽靜態
                $lang_rewrite_str.'search$' => array('home/Search/lists',array('method' => 'get', 'ext' => 'html'), 'cache'=>1),
                // 留言模型
                $lang_rewrite_str.'guestbook$' => array('home/Guestbook/index',array('method' => 'get', 'ext' => 'html'), 'cache'=>1),
                $lang_rewrite_str.'guestbook/submit$' => array('home/Lists/gbook_submit',array('method' => 'post', 'ext' => 'html'), 'cache'=>1),
                $lang_rewrite_str.'guestbook/<tid>$' => array('home/Guestbook/lists',array('method' => 'get', 'ext' => 'html'), 'cache'=>1),
            );

            /*自定義模型*/
            $cacheKey = "application_route_channeltype";
            $channeltype_row = \think\Cache::get($cacheKey);
            if (empty($channeltype_row)) {
                $channeltype_row = \think\Db::name('channeltype')->field('nid,ctl_name')
                    ->where([
                        'ifsystem' => 0,
                    ])
                    ->select();
                \think\Cache::set($cacheKey, $channeltype_row, EYOUCMS_CACHE_TIME, "channeltype");
            }
            foreach ($channeltype_row as $value) {
                $home_rewrite += array(
                    $lang_rewrite_str.$value['nid'].'$' => array('home/'.$value['ctl_name'].'/index',array('method' => 'get', 'ext' => 'html'), 'cache'=>1),
                    $lang_rewrite_str.$value['nid'].'/<tid>$' => array('home/'.$value['ctl_name'].'/lists',array('method' => 'get', 'ext' => 'html'), 'cache'=>1),
                    $lang_rewrite_str.$value['nid'].'/<dirname>/<aid>$' => array('home/'.$value['ctl_name'].'/view',array('method' => 'get', 'ext' => 'html'),'cache'=>1),
                );
            }
            /*--end*/
        }
        $home_rewrite = array_merge($lang_rewrite, $home_rewrite);
    }

    /*外掛模組路由*/
    $weapp_route_file = 'weapp/route.php';
    if (file_exists(APP_PATH.$weapp_route_file)) {
        $weapp_route = include_once $weapp_route_file;
        $route = array_merge($weapp_route, $route);
    }
    /*--end*/
}

$route = array_merge($route, $home_rewrite);

return $route;
