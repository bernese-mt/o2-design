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
 * 模型
 */
class Channeltype extends Model
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
    public function getInfo($id)
    {
        $result = M('channeltype')->field('*')->cache(true,EYOUCMS_CACHE_TIME,"channeltype")->find($id);

        return $result;
    }

    /**
     * 獲取單條記錄
     * @author 小虎哥 by 2018-4-16
     */
    public function getInfoByWhere($where, $field = '*')
    {
        $result = M('channeltype')->field($field)->where($where)->find();

        return $result;
    }

    /**
     * 獲取多條記錄
     * @author 小虎哥 by 2018-4-16
     */
    public function getListByIds($ids, $field = '*')
    {
        $map = array(
            'id'   => array('IN', $ids),
        );
        $result = db('Channeltype')->field($field)
            ->where($map)
            ->order('sort_order asc')
            ->select();

        return $result;
    }

    /**
     * 預設獲取全部
     * @author 小虎哥 by 2018-4-16
     */
    public function getAll($field = '*', $map = array(), $index_key = '')
    {
        $cacheKey = array(
            'common',
            'model',
            'Channeltype',
            'getAll',
            $field,
            $map,
            $index_key
        );
        $cacheKey = json_encode($cacheKey);
        $result = cache($cacheKey);
        if (empty($result)) {
            $result = db('channeltype')->field($field)
                ->where($map)
                ->order('sort_order asc, id asc')
                ->select();

            if (!empty($index_key)) {
                $result = convert_arr_key($result, $index_key);
            }

            cache($cacheKey, $result, null, 'channeltype');
        }

        return $result;
    }

    /**
     * 獲取有欄目的模型列表
     * @param string $type yes表示存在欄目的模型列表，no表示不存在欄目的模型列表
     * @author 小虎哥 by 2018-4-16
     */
    public function getArctypeChannel($type = 'yes')
    {
        if ($type == 'yes') {
            $map = array(
                'b.status'    => 1,
            );
            $result = M('Channeltype')->field('b.*, a.*, b.id as typeid')
                ->alias('a')
                ->join('__ARCTYPE__ b', 'b.current_channel = a.id', 'LEFT')
                ->where($map)
                ->group('a.id')
                ->cache(true,EYOUCMS_CACHE_TIME,"arctype")
                ->getAllWithIndex('nid');

        } else {
            $result = M('Channeltype')->field('b.*, a.*, b.id as typeid')
                ->alias('a')
                ->join('__ARCTYPE__ b', 'b.current_channel = a.id', 'LEFT')
                ->group('a.id')
                ->cache(true,EYOUCMS_CACHE_TIME,"arctype")
                ->getAllWithIndex('nid');

            if ($result) {
                foreach ($result as $key => $val) {
                    if (intval($val['channeltype']) > 0) {
                        unset($result[$key]);
                    }
                }
            }
        }

        return $result;
    }

    /**
     * 根據文件ID獲取模型資訊
     * @author 小虎哥 by 2018-4-16
     */
    public function getInfoByAid($aid)
    {
        $result = array();
        $res1 = M('archives')->where(array('aid'=>$aid))->find();
        $res2 = M('Channeltype')->where(array('id'=>$res1['channel']))->find();

        if (is_array($res1) && is_array($res2)) {
            $result = array_merge($res1, $res2);
        }

        return $result;
    }

    /**
     * 根據前端模板自動開啟系統模型
     */
    public function setChanneltypeStatus()
    {
        $planPath = 'template/pc';
        $planPath = realpath($planPath);
        if (!file_exists($planPath)) {
            return true;
        }
        $ctl_name_arr = array();
        $dirRes   = opendir($planPath);
        $view_suffix = config('template.view_suffix');
        while($filename = readdir($dirRes))
        {
            if(preg_match('/^(lists|view)?_/i', $filename) == 1)
            {
                $tplname = preg_replace('/([^_]+)?_([^\.]+)\.'.$view_suffix.'$/i', '${2}', $filename);
                $ctl_name_arr[] = ucwords($tplname);
            } elseif (preg_match('/\.'.$view_suffix.'$/i', $filename) == 1) {
                $tplname = preg_replace('/\.'.$view_suffix.'$/i', '', $filename);
                $ctl_name_arr[] = ucwords($tplname);
            }
        }
        $ctl_name_arr = array_unique($ctl_name_arr);

        if (!empty($ctl_name_arr)) {
            \think\Db::name('Channeltype')->where('id > 0')->cache(true,null,"channeltype")->update(array('status'=>0, 'update_time'=>getTime()));
            $map = array(
                'ctl_name'  => array('IN', $ctl_name_arr),
            );
            \think\Db::name('Channeltype')->where($map)->cache(true,null,"channeltype")->update(array('status'=>1, 'update_time'=>getTime()));
        } 
    }
}