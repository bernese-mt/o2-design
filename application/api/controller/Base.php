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

namespace app\api\controller;
use app\common\controller\Common;
use think\Db;
use think\response\Json;
class Base extends Common {

    public $uipath = '';
    public $theme_style = '';

    /**
     * 解構函式
     */
    function __construct() 
    {
        parent::__construct();
        $this->theme_style = THEME_STYLE;
        $this->uipath = RUNTIME_PATH.'ui/'.$this->theme_style.'/';
    }
    
    /*
     * 初始化操作
     */
    public function _initialize() 
    {
        parent::_initialize();
        
        $this->set_global_variable();
    }

    /**
     * 設定全域性模板變數 
     */
    public function set_global_variable()
    {
        // 設定全域性模板變數
        if (!defined('MODULE_NAME')) {
            $request = think\Request::instance();
            define('MODULE_NAME', $request->module());
        }
        $global_variable = array();
        $view_replace_str = config('view_replace_str');
        foreach ($view_replace_str as $key => $val) {
            $view_replace_str[$key] = preg_replace('/(.*?)(\/'.MODULE_NAME.'\/)(\w+)(.*?)/i', '${1}${2}'.$this->theme_style.'${4}', $val);
        }
        config('view_replace_str', $view_replace_str);
        $global_variable = array_merge($global_variable, config('view_replace_str'));
        $this->assign($global_variable);
    }
}