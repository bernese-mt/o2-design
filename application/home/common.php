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

// 模板錯誤提示
switch_exception();

if (!function_exists('set_home_url_mode')) 
{
    // 設定前臺URL模式
    function set_home_url_mode() {
        $uiset = I('param.uiset/s', 'off');
        $uiset = trim($uiset, '/');
        $seo_pseudo = tpCache('seo.seo_pseudo');
        if ($seo_pseudo == 1 || $uiset == 'on') {
            config('url_common_param', true);
            config('url_route_on', false);
        } elseif ($seo_pseudo == 2 && $uiset != 'on') {
            config('url_common_param', false);
            config('url_route_on', true);
        } elseif ($seo_pseudo == 3 && $uiset != 'on') {
            config('url_common_param', false);
            config('url_route_on', true);
        }
    }
}

if (!function_exists('set_arcseotitle')) 
{
    /**
     * 設定內容標題
     */
    function set_arcseotitle($title = '', $seo_title = '', $typename = '')
    {
        /*針對沒有自定義SEO標題的文件*/
        if (empty($seo_title)) {
            $web_name = tpCache('web.web_name');
            $seo_viewtitle_format = tpCache('seo.seo_viewtitle_format');
            switch ($seo_viewtitle_format) {
                case '1':
                    $seo_title = $title;
                    break;
                
                case '3':
                    $seo_title = $title.'_'.$typename.'_'.$web_name;
                    break;
                
                case '2':
                default:
                    $seo_title = $title.'_'.$web_name;
                    break;
            }
        }
        /*--end*/

        return $seo_title;
    }
}

if (!function_exists('set_typeseotitle')) 
{
    /**
     * 設定欄目標題
     */
    function set_typeseotitle($typename = '', $seo_title = '')
    {
        /*針對沒有自定義SEO標題的列表*/
        if (empty($seo_title)) {
            $web_name = tpCache('web.web_name');
            $seo_liststitle_format = tpCache('seo.seo_liststitle_format');
            switch ($seo_liststitle_format) {
                case '1':
                    $seo_title = $typename.'_'.$web_name;
                    break;
                
                case '2':
                default:
                    $page = I('param.page/d', 1);
                    if ($page > 1) {
                        $typename .= "_第{$page}頁";
                    }
                    $seo_title = $typename.'_'.$web_name;
                    break;
            }
        }

        return $seo_title;
    }
}
