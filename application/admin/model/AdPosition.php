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
 * 廣告分類
 */
class AdPosition extends Model
{
    //初始化
    protected function initialize()
    {
        // 需要呼叫`Model`的`initialize`方法
        parent::initialize();
    }

    /**
     * 獲取單條記錄
     * @author wengxianhu by 2017-7-26
     */
    public function getInfo($id, $field = '*')
    {
        $result = db('AdPosition')->field($field)->find($id);

        return $result;
    }

    /**
     * 獲取多條記錄
     * @author wengxianhu by 2017-7-26
     */
    public function getListByIds($ids, $field = '*')
    {
        $map = array(
            'id'   => array('IN', $ids),
            'lang'  => get_admin_lang(),
        );
        $result = db('AdPosition')->field($field)
            ->where($map)
            ->select();

        return $result;
    }

    /**
     * 預設獲取廣告分類，包括有效、無效等分類
     * @author wengxianhu by 2017-7-26
     */
    public function getAll($field = '*', $index_key = '')
    {
        $result = db('AdPosition')->field($field)
            ->where([
                'lang'  => get_admin_lang(),
            ])->select();

        if (!empty($index_key)) {
            $result = convert_arr_key($result, $index_key);
        }

        return $result;
    }
}