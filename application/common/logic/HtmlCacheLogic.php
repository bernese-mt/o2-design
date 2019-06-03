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

namespace app\common\logic;

use think\Db;

/**
 *
 * 頁面快取
 */
class HtmlCacheLogic 
{
    public function __construct() 
    {

    }

    /**
     * 清除首頁的頁面快取檔案
     */
    public function clear_index()
    {
        $web_cmsmode = tpCache('web.web_cmsmode');
        $html_cache_arr = config('HTML_CACHE_ARR');
        if (1 == intval($web_cmsmode)) { // 頁面html靜態永久快取
            $fileList = glob(HTML_ROOT.'http*/'.$html_cache_arr['home_Index_index']['filename'].'/*_html/index*.html');
            if (!empty($fileList)) {
                foreach ($fileList as $k2 => $file) {
                    if (file_exists($file) && preg_match('/index(_\d+)?\.html$/i', $file)) {
                        @unlink($file);
                    }
                }
            }
        } else { // 頁面cache自動過期快取
            $fileList = glob(HTML_ROOT.'http*/'.$html_cache_arr['home_Index_index']['filename'].'/*_cache/index/*');
            if (!empty($fileList)) {
                foreach ($fileList as $k2 => $dir) {
                    if (file_exists($dir) && is_dir($dir)) {
                        delFile($dir, true);
                    }
                }
            }
        }
    }

    /**
     * 清除指定欄目的頁面快取檔案
     * @param array $typeids 欄目ID陣列
     */
    public function clear_arctype($typeids = [])
    {
        $web_cmsmode = tpCache('web.web_cmsmode');
        $html_cache_arr = config('HTML_CACHE_ARR');
        if (!empty($typeids)) {
            foreach ($typeids as $key => $tid) {
                if (1 == intval($web_cmsmode)) { // 頁面html靜態永久快取
                    $fileList = glob(HTML_ROOT.'http*/'.$html_cache_arr['home_Lists_index']['filename'].'/*_html/'.$tid.'*.html');
                    if (!empty($fileList)) {
                        foreach ($fileList as $k2 => $file) {
                            if (file_exists($file) && preg_match('/'.$tid.'(_\d+)?\.html$/i', $file)) {
                                @unlink($file);
                            }
                        }
                    }
                } else { // 頁面cache自動過期快取
                    $fileList = glob(HTML_ROOT.'http*/'.$html_cache_arr['home_Lists_index']['filename'].'/*_cache/'.$tid.'/*');
                    if (!empty($fileList)) {
                        foreach ($fileList as $k2 => $dir) {
                            if (file_exists($dir) && is_dir($dir)) {
                                delFile($dir, true);
                            }
                        }
                    }
                }
            }
        } else { // 清除全部的欄目頁面快取
            $fileList = glob(HTML_ROOT.'http*/'.$html_cache_arr['home_Lists_index']['filename'].'/*');
            if (!empty($fileList)) {
                foreach ($fileList as $k2 => $dir) {
                    if (file_exists($dir) && is_dir($dir)) {
                        delFile($dir, true);
                    }
                }
            }
        }

        $this->clear_index(); // 清除首頁快取
    }

    /**
     * 清除指定文件的頁面快取檔案
     * @param array $aids 文件ID陣列
     */
    public function clear_archives($aids = [])
    {
        $web_cmsmode = tpCache('web.web_cmsmode');
        $html_cache_arr = config('HTML_CACHE_ARR');
        if (!empty($aids)) {
            $row = Db::name('archives')->field('aid,typeid')
                ->where([
                    'aid'   => ['IN', $aids],
                ])->select();
            foreach ($row as $key => $val) {
                $aid = $val['aid'];
                $typeid = $val['typeid'];
                if (1 == intval($web_cmsmode)) { // 頁面html靜態永久快取
                    $fileList = glob(HTML_ROOT.'http*/'.$html_cache_arr['home_View_index']['filename'].'/*_html/'.$aid.'*.html');
                    if (!empty($fileList)) {
                        foreach ($fileList as $k2 => $file) {
                            if (preg_match('/'.$aid.'(_\d+)?\.html$/i', $file)) {
                                @unlink($file);
                            }
                        }
                    }
                } else { // 頁面cache自動過期快取
                    $fileList = glob(HTML_ROOT.'http*/'.$html_cache_arr['home_View_index']['filename'].'/*_cache/'.$aid.'/*');
                    if (!empty($fileList)) {
                        foreach ($fileList as $k2 => $dir) {
                            if (file_exists($dir) && is_dir($dir)) {
                                delFile($dir, true);
                            }
                        }
                    }
                }
            }
        } else { // 清除所有的文件頁面快取
            $fileList = glob(HTML_ROOT.'http*/'.$html_cache_arr['home_View_index']['filename'].'*');
            if (!empty($fileList)) {
                foreach ($fileList as $k2 => $dir) {
                    if (file_exists($dir) && is_dir($dir)) {
                        delFile($dir, true);
                    }
                }
            }
        }

        $this->clear_arctype(); // 清除所有的欄目
    }
}
