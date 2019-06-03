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
 * Date: 2019-3-11
 */
namespace app\admin\model;

use think\Model;

/**
 * 會員屬性
 */
class UsersParameter extends Model
{
    private $admin_lang = 'cn';
    //初始化
    protected function initialize()
    {
        // 需要呼叫`Model`的`initialize`方法
        parent::initialize();
        $this->admin_lang = get_admin_lang();
    }

    /**
     * 校驗是否允許非必填
     */
    public function isRequired($id_name='',$id_value='',$field='',$value='')
    {
        $return = true;

        $value = trim($value);
        if (($value == '0' && $field == 'is_required') || ($value == '1' && $field == 'is_hidden')) {
            $where = [
                $id_name => $id_value,
                'lang'   => $this->admin_lang,
            ];
            $paraData = M('users_parameter')->where($where)->field('dtype')->find();
            if ($paraData['dtype'] == 'email') {
                $usersData = getUsersConfigData('users.users_verification');
                if ($usersData == '2') {
                    if ($value == '0') {
                        $return = [
                            'msg'   => '您已選擇：會員功能設定-註冊驗證-郵件驗證，因此郵箱地址必須為必填！',
                        ];
                    }
                    if ($value == '1') {
                        $return = [
                            'msg'   => '您已選擇：會員功能設定-註冊驗證-郵件驗證，因此郵箱地址不可隱藏！',
                        ];
                    }
                    
                }
            }
        }

        return $return;
    }
}