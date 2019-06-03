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

namespace app\home\controller;

class Index extends Base
{
    public function _initialize() {
        parent::_initialize();
    }

    public function index()
    {
        if (config('is_https')) {
            $filename = 'indexs.html';
        } else {
            $filename = 'index.html';
        }

        if (file_exists($filename)) {
            @unlink($filename);
        }

        //自動產生HTML版
        if(isset($_GET['clear']) || !file_exists($filename))
        {
            /*獲取目前頁面URL*/
            $result['pageurl'] = request()->url(true);
            /*--end*/
            $eyou = array(
                'field' => $result,
            );
            $this->eyou = array_merge($this->eyou, $eyou);
            $this->assign('eyou', $this->eyou);
            
            /*模板檔案*/
            $viewfile = 'index';
            /*--end*/

            /*多語言內建模板檔名*/
            if (!empty($this->home_lang)) {
                $viewfilepath = TEMPLATE_PATH.$this->theme_style.DS.$viewfile."_{$this->home_lang}.".$this->view_suffix;
                if (file_exists($viewfilepath)) {
                    $viewfile .= "_{$this->home_lang}";
                }
            }
            /*--end*/

            $html = $this->fetch(":{$viewfile}");
            // @file_put_contents($filename, $html);
            return $html;
        }
        else
        {
            // header('HTTP/1.1 301 Moved Permanently');
            // header('Location:'.$filename);
        }
    }
}