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
 * Date: 2019-2-25
 */

namespace app\user\controller;

use think\Db;
// use think\Session;
use think\Config;

class LoginApi extends Base
{
    public $oauth;

    public function _initialize() {
        parent::_initialize();
        session('?users_id');
        $this->oauth = input('param.oauth/s');
        if (!$this->oauth) {
            $this->error('非法操作', url('user/Users/login'));
        }
    }

    public function login(){
        $this->error('該功能尚未開放', url('user/Users/login'));
    }

    public function callback()
    {

    }
}