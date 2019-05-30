<?php
/**
 * [OECMS] (C)2012-2099 OEdev,Inc.
 * <E-Mail：phpcoo@qq.com>
 * Url www.oedev.net, www.phpcoo.com
 * Update 2013.02.05
*/
if(!defined('IN_OECMS')) {
	exit('Access Denied');
}
class control extends adminbase {
	private $comeurl = NUll;

	
	public function action_run() {
		TPL::display($this->cptpl.'login.tpl');
	}

	
	public function action_login() {
		$info = $this->_validAdminInfo();
        
		$model = parent::model('login', 'am');
		$flag = $model->doLogin($info['username'], $info['password']);
        $model->loginLog();
        unset($model);
        if ($flag == 0) {
            $this->saveLoginLog($info['username'], '登录失败', 0);
		    XHandle::halt('未知错误！', '', 1);
		}
		elseif ($flag == 1) {
            $this->saveLoginLog($info['username'], '登录失败，帐号被锁定', 0);
		    XHandle::halt('帐号被锁定!', '', 1);
		}
		elseif ($flag == 2) {
            $this->saveLoginLog($info['username'], '登录失败，密码错误', 0);
		    XHandle::halt('登录失败!', '', 1);
		}
		elseif ($flag == 3) {
            $this->saveLoginLog($info['username'], '登录成功', 1);
			XHandle::halt('登录成功!', $this->cpfile.'?c=frame', 0);
		}
	}
    
    
    public function action_logout() {
        if (!empty(parent::$wrap_admin['username'])) {
            $this->saveLoginLog(parent::$wrap_admin['username'], '退出登录', 1);
        }
        $model = parent::model('login', 'am');
        $model->logout();
        unset($model);
        XHandle::halt('退出成功', $this->cpfile.'?c=login', 0);
    }

    
    private function _validAdminInfo() {
        $args = XRequest::getGpc(
            array('username', 'password', 'checkcode')
        );
        if (false === XValid::isUserArgs($args['username'])) {
            XHandle::halt('管理员帐号格式不正确', '', 1);
        }
        if (false === XValid::isLength($args['username'], 4, 16)) {
            XHandle::halt('管理员帐号不正确', '', 1);
        }
        if (false === XValid::isLength($args['password'], 4, 16)) {
            XHandle::halt('管理员密码格式不正确', '', 1);
        }
        parent::loadUtil('session');
        if ($args['checkcode'] != XSession::get('verifycode')) {
            XHandle::halt('验证码不正确', '', 1);
        }
        unset($args['checkcode']);
        return $args;
    }
}
?>
