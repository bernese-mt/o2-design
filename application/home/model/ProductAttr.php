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

namespace app\home\model;

use think\Model;

/**
 * 產品參數
 */
class ProductAttr extends Model
{
    //初始化
    protected function initialize()
    {
        // 需要呼叫`Model`的`initialize`方法
        parent::initialize();
    }

    /**
     * 獲取單條產品的所有參數
     * @author 小虎哥 by 2018-4-3
     */
    public function getProAttr($aid, $field = 'b.*, a.*')
    {
        $result = db('ProductAttribute')->field($field)
            ->alias('a')
            ->join('__PRODUCT_ATTR__ b', 'b.attr_id = a.attr_id', 'LEFT')
            ->where([
                'b.aid' => $aid,
                'a.is_del' => 0,
            ])
            ->order('a.sort_order asc, a.attr_id asc')
            ->select();

        return $result;
    }
}