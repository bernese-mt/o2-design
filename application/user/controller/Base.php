<?php
/**
 * 易優CMS
 * ============================================================================
 * 版權所有 2016-2028 海南贊贊網路科技有限公司，並保留所有權利。
 * 網站地址: http://www.eyoucms.com
 * ----------------------------------------------------------------------------
 * 如果商業用途務必到官方購買正版授權, 以免引起不必要的法律糾紛.
 * ============================================================================
 * Author: 陳風任 <491085389@qq.com>
 * Date: 2019-1-25
 */

namespace app\user\controller;
use think\Controller;
use app\common\controller\Common;
use think\Db;

class Base extends Common {

    public $usersConfig = [];

    /**
     * 初始化操作
     */
    public function _initialize() {
        parent::_initialize();

        if(session('?users_id'))
        {
            $users_id = session('users_id');
            $users = M('users')->field('a.*,b.level_name')
                ->alias('a')
                ->join('__USERS_LEVEL__ b', 'a.level = b.level_id', 'LEFT')
                ->where([
                    'a.users_id'        => $users_id,
                    'a.lang'            => $this->home_lang,
                    'a.is_activation'   => 1,
                ])->find();
            session('users',$users);  //覆蓋session 中的 users
            $this->users = $users;
            $this->users_id = $users['users_id'];

            $this->assign('users',$users); //儲存使用者資訊
            $this->assign('users_id',$this->users_id);
        } else {
            //過濾不需要登陸的行為
            $ctl_act = CONTROLLER_NAME.'@'.ACTION_NAME;
            $ctl_all = CONTROLLER_NAME.'@*';
            $filter_login_action = config('filter_login_action');
            if (!in_array($ctl_act, $filter_login_action) && !in_array($ctl_all, $filter_login_action)) {
                if (IS_AJAX) {
                    $this->error('請先登錄！');
                } else {
                    $this->redirect('user/Users/login');
                    exit;
                }
            }
        }

        // 訂單超過 get_shop_order_validity 設定的時間，則修改訂單為已取消狀態，無需返回數據
        model('Shop')->UpdateShopOrderData($this->users_id);

        // 會員功能是否開啟
        $logut_redirect_url = '';
        $this->usersConfig = getUsersConfigData('all');
        $web_users_switch = tpCache('web.web_users_switch');
        if (empty($web_users_switch) || isset($this->usersConfig['users_open_register']) && $this->usersConfig['users_open_register'] == 1) { 
            // 前臺會員中心已關閉
            $logut_redirect_url = ROOT_DIR.'/';
        } else if (session('?users_id') && empty($this->users)) { 
            // 登錄的會員被後臺刪除，立馬退出會員中心
            $logut_redirect_url = url('user/Users/centre');
        }
        if (!empty($logut_redirect_url)) {
            // 清理session並回到首頁
            session('users_id', null);
            session('users', null);
            $this->redirect($logut_redirect_url);
            exit;
        }
        // --end
        
        $this->assign('usersConfig', $this->usersConfig);
        
        $this->usersConfig['theme_color'] = $theme_color = !empty($this->usersConfig['theme_color']) ? $this->usersConfig['theme_color'] : '#ff6565'; // 預設主題顏色
        $this->assign('theme_color', $theme_color);

        $is_mobile = '2';// PC端
        if (isMobile()) {
            $is_mobile = '1'; //手機端
        }
        $this->assign('is_mobile',$is_mobile);
    }
}