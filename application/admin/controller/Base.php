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

namespace app\admin\controller;
use app\admin\logic\UpgradeLogic;
use think\Controller;
use think\Db;
use think\response\Json;
use think\Session;
class Base extends Controller {

    public $session_id;

    /**
     * 解構函式
     */
    function __construct() 
    {
        if (!session_id()) {
            Session::start();
        }
        header("Cache-control: private");  // history.back返回后輸入框值丟失問題
        parent::__construct();

        $this->global_assign();

        /*---------*/
        $is_eyou_authortoken = session('web_is_authortoken');
        $is_eyou_authortoken = !empty($is_eyou_authortoken) ? $is_eyou_authortoken : 0;
        $this->assign('is_eyou_authortoken', $is_eyou_authortoken);
        /*--end*/
    }
    
    /*
     * 初始化操作
     */
    public function _initialize() 
    {
        $this->session_id = session_id(); // 目前的 session_id
        !defined('SESSION_ID') && define('SESSION_ID', $this->session_id); //將目前的session_id儲存為常量，供其它方法呼叫

        parent::_initialize();

        //過濾不需要登陸的行為
        $ctl_act = CONTROLLER_NAME.'@'.ACTION_NAME;
        $ctl_all = CONTROLLER_NAME.'@*';
        $filter_login_action = config('filter_login_action');
        if (in_array($ctl_act, $filter_login_action) || in_array($ctl_all, $filter_login_action)) {
            //return;
        }else{
            $admin_login_expire = session('admin_login_expire'); // 登錄有效期
            if (getTime() - intval($admin_login_expire) < config('login_expire')) {
                session('admin_login_expire', getTime()); // 登錄有效期
                $this->check_priv();//檢查管理員菜單操作許可權
            }else{
                /*自動退出*/
                adminLog('自動退出');
                session_unset();
                session::clear();
                cookie('admin-treeClicked', null); // 清除並恢復欄目列表的展開方式
                /*--end*/
                $url = request()->baseFile().'?s=Admin/login';
                $this->redirect($url);
            }
        }
    }
    
    public function check_priv()
    {
        $ctl = CONTROLLER_NAME;
        $act = ACTION_NAME;
        $ctl_act = $ctl.'@'.$act;
        $ctl_all = $ctl.'@*';
        //無需驗證的操作
        $uneed_check_action = config('uneed_check_action');
        if (0 >= intval(session('admin_info.role_id'))) {
            //超級管理員無需驗證
            return true;
        } else {
            $bool = false;

            /*檢測是否有該許可權*/
            if (is_check_access($ctl_act)) {
                $bool = true;
            }
            /*--end*/

            /*在列表中的操作不需要驗證許可權*/
            if (IS_AJAX || strpos($act,'ajax') !== false || in_array($ctl_act, $uneed_check_action) || in_array($ctl_all, $uneed_check_action)) {
                $bool = true;
            }
            /*--end*/

            //檢查是否擁有此操作許可權
            if (!$bool) {
                $this->error('您沒有操作許可權，請聯繫超級管理員分配許可權');
            }
        }
    }  

    /**
     * 儲存系統設定 
     */
    public function global_assign()
    {
        $this->assign('version', getCmsVersion());
        $this->assign('global', tpCache('global'));
    } 
    
    /**
     * 多語言功能操作許可權
     */
    public function language_access()
    {
        if (is_language() && $this->main_lang != $this->admin_lang) {
            $lang_title = model('Language')->where('mark',$this->main_lang)->value('title');
            $this->error('目前語言沒有此功能，請切換到【'.$lang_title.'】語言');
        }
    }
}