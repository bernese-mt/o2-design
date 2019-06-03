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

namespace app\common\model;

use think\Model;

/**
 * 模型自定義欄位
 */
class Channelfield extends Model
{
    //初始化
    protected function initialize()
    {
        // 需要呼叫`Model`的`initialize`方法
        parent::initialize();
    }

    /**
     * 獲取單條記錄
     * @author 小虎哥 by 2018-4-16
     */
    public function getInfo($id, $field = '*')
    {
        $result = db('Channelfield')->field($field)->find($id);

        return $result;
    }

    /**
     * 獲取單條記錄
     * @author 小虎哥 by 2018-4-16
     */
    public function getInfoByWhere($where, $field = '*')
    {
        $result = db('Channelfield')->field($field)->where($where)->cache(true,EYOUCMS_CACHE_TIME,"channelfield")->find();

        return $result;
    }

    /**
     * 預設模型欄位
     * @author 小虎哥 by 2018-4-16
     */
    public function getListByWhere($map = array(), $field = '*', $index_key = '')
    {
        $result = db('Channelfield')->field($field)
            ->where($map)
            ->order('sort_order asc, channel_id desc, id desc')
            ->select();

        if (!empty($index_key)) {
            $result = convert_arr_key($result, $index_key);
        }
        
        return $result;
    }
}