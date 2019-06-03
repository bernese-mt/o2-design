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
 * Date: 2019-2-20
 */

namespace app\user\controller;

use think\Config;
use app\user\logic\SmtpmailLogic;

// 用於郵箱驗證
class Smtpmail extends Base
{
    public $smtpmailLogic;

    /**
     * 構造方法
     */
    public function __construct(){
        parent::__construct();
        $this->smtpmailLogic = new SmtpmailLogic;
    }

    /**
     * 發送郵件
     */
    public function send_email($email = '', $title = '', $type = 'reg', $scene = 2)
    {
        // 超時後，斷掉郵件發送
        function_exists('set_time_limit') && set_time_limit(5);
        
        $data = $this->smtpmailLogic->send_email($email, $title, $type, $scene);
        if (1 == $data['code']) {
            $this->success($data['msg']);
        } else {
            $this->error($data['msg']);
        }
    }
}