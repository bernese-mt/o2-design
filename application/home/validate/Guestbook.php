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

namespace app\home\validate;
use think\Validate;

class Guestbook extends Validate
{
    // 驗證規則
    protected $rule = array(
        'typeid'    => 'require|token',
    );

    protected $message = array(
        'typeid.require' => '表單缺少標籤屬性{$field.hidden}',
    );
}