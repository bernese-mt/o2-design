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
 * 區域分類
 */
class Region extends Model
{
    //初始化
    protected function initialize()
    {
        // 需要呼叫`Model`的`initialize`方法
        parent::initialize();
    }

    /**
     * 獲取單條地區
     * @author wengxianhu by 2017-7-26
     */
    public function getInfo($id, $field = '*')
    {
        $result = db('Region')->field($field)->find($id);

        return $result;
    }

    /**
     * 獲取多個地區
     * @author wengxianhu by 2017-7-26
     */
    public function getListByIds($ids = array(), $field = '*', $index_key = '')
    {
        $map = array(
            'id'   => array('IN', $ids),
        );
        $result = db('Region')->field($field)
            ->where($map)
            ->select();

        if (!empty($index_key)) {
            $result = convert_arr_key($result, $index_key);
        }

        return $result;
    }

    /**
     * 獲取子地區
     * @author wengxianhu by 2017-7-26
     */
    public function getList($parent_id = 0, $field = '*', $index_key = '')
    {
        $result = $this->getAll($parent_id, $field, $index_key);

        return $result;
    }

    /**
     * 獲取全部地區
     * @author wengxianhu by 2017-7-26
     */
    public function getAll($parent_id = false, $field = '*', $index_key = '')
    {
        $map = array();
        if (false !== $parent_id) {
            $map['parent_id'] = $parent_id;
        }

        $result = db('Region')->field($field)
            ->where($map)
            ->select();

        if (!empty($index_key)) {
            $result = convert_arr_key($result, $index_key);
        }

        return $result;
    }

    /**
     * 獲取級別的地區
     * @author wengxianhu by 2017-7-26
     */
    public function getListByLevel($level = 1, $field = '*', $index_key = '')
    {
        $map = array(
            'level' => $level,
        );

        $result = db('Region')->field($field)
            ->where($map)
            ->select();

        if (!empty($index_key)) {
            $result = convert_arr_key($result, $index_key);
        }

        return $result;
    }
}