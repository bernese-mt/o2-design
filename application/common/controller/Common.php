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

namespace app\common\controller;
use think\Controller;
use think\Session;
use think\Db;
class Common extends Controller {

    public $session_id;
    public $theme_style = '';
    public $view_suffix = 'html';
    public $eyou = array();

    public $users_id = 0;
    public $users = array();

    /**
     * 解構函式
     */
    function __construct() 
    {
        /*是否隱藏或顯示應用入口index.php*/
        if (tpCache('seo.seo_inlet') == 0) {
            \think\Url::root('/index.php');
        } else {
            // \think\Url::root('/');
        }
        /*--end*/
        parent::__construct();
    }    
    
    /*
     * 初始化操作
     */
    public function _initialize() 
    {
        session('admin_info'); // 傳後臺資訊到前臺，此處視覺化用到
        if (!session_id()) {
            Session::start();
        }
        header("Cache-control: private");  // history.back返回后輸入框值丟失問題 
        $this->session_id = session_id(); // 目前的 session_id
        !defined('SESSION_ID') && define('SESSION_ID', $this->session_id); //將目前的session_id儲存為常量，供其它方法呼叫

        /*關閉網站*/
        if (tpCache('web.web_status') == 1) {
            die("<div style='text-align:center; font-size:20px; font-weight:bold; margin:50px 0px;'>網站暫時關閉，維護中……</div>");
        }
        /*--end*/

        $this->global_assign(); // 獲取網站全域性變數值
        $this->view_suffix = config('template.view_suffix'); // 模板後綴htm
        $this->theme_style = THEME_STYLE; // 模板目錄
        //全域性變數
        $global = tpCache('global'); 
        $this->eyou['global'] = $global;
        // 多語言變數
        $langArr = include_once APP_PATH."lang/{$this->home_lang}.php";
        $this->eyou['lang'] = !empty($langArr) ? $langArr : [];
        /*電腦版與手機版的切換*/
        $v = I('param.v/s', 'pc');
        $v = trim($v, '/');
        $this->assign('v', $v);
        /*--end*/

        // 判斷是否開啟註冊入口
        $users_open_register = getUsersConfigData('users.users_open_register');
        $this->assign('users_open_register', $users_open_register);
    }

    /**
     * 獲取系統內建變數 
     */
    public function global_assign()
    {
        $globalParams = tpCache('global');
        $this->assign('global', $globalParams);
    }
}