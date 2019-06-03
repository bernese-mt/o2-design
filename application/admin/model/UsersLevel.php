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
namespace app\admin\model;

use think\Model;

/**
 * 會員級別
 */
class UsersLevel extends Model
{
    //初始化
    protected function initialize()
    {
        // 需要呼叫`Model`的`initialize`方法
        parent::initialize();
    }

    /**
     * 校驗唯一性
     * @author wengxianhu by 2017-7-26
     */
    public function isRequired($id_name='',$id_value='',$field='',$value='')
    {
        $return = true;
        $value = trim($value);
        if (!empty($value)) {
            $field == 'level_value' && $value = intval($value);

            $count = $this->where([
                    $field      => $value,
                    $id_name    => ['NEQ', $id_value],
                ])->count();
            if (!empty($count)) {
                $return = [
                    'msg'   => '數據不可重複',
                ];
            }
        }

        return $return;
    }
}