<?php
/**
 * 易优CMS
 * ============================================================================
 * 版权所有 2016-2028 海南赞赞网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.eyoucms.com
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ============================================================================
 * Author: 陈风任 <491085389@qq.com>
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
            session('users',$users);  //覆盖session 中的 users
            $this->users = $users;
            $this->users_id = $users['users_id'];

            $this->assign('users',$users); //存储用户信息
            $this->assign('users_id',$this->users_id);
        } else {
            //过滤不需要登陆的行为
            $ctl_act = CONTROLLER_NAME.'@'.ACTION_NAME;
            $ctl_all = CONTROLLER_NAME.'@*';
            $filter_login_action = config('filter_login_action');
            if (!in_array($ctl_act, $filter_login_action) && !in_array($ctl_all, $filter_login_action)) {
                if (IS_AJAX) {
                    $this->error('请先登录！');
                } else {
                    $this->redirect('user/Users/login');
                    exit;
                }
            }
        }

        $logut_redirect_url = '';
        $this->usersConfig = getUsersConfigData('all');
        if (isset($this->usersConfig['users_open_register']) && $this->usersConfig['users_open_register'] == 1) { // 前台会员中心已关闭
            $logut_redirect_url = ROOT_DIR.'/';
        } else if (session('?users_id') && empty($this->users)) { // 登录的会员被后台删除，立马退出会员中心
            $logut_redirect_url = url('user/Users/centre');
        }
        if (!empty($logut_redirect_url)) {
            // 清理session并回到首页
            session('users_id', null);
            session('users', null);
            $this->redirect($logut_redirect_url);
        }
        $this->assign('usersConfig', $this->usersConfig);
        
        $this->usersConfig['theme_color'] = $theme_color = !empty($this->usersConfig['theme_color']) ? $this->usersConfig['theme_color'] : '#ff6565'; // 默认主题颜色
        $this->assign('theme_color', $theme_color);

        $is_mobile = '2';// PC端
        if (isMobile()) {
            $is_mobile = '1'; //手机端
        }
        $this->assign('is_mobile',$is_mobile);
    }
}