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
 * 圖集
 */
class Images extends Model
{
    // 模型標識
    public $nid = 'images';
    // 模型ID
    public $channeltype = '';

    //初始化
    protected function initialize()
    {
        // 需要呼叫`Model`的`initialize`方法
        parent::initialize();
        $channeltype_list = config('global.channeltype_list');
        $this->channeltype = $channeltype_list[$this->nid];
    }

    /**
     * 後置操作方法
     * 自定義的一個函式 用於數據儲存后做的相應處理操作, 使用時手動呼叫
     * @param int $aid 產品id
     * @param array $post post數據
     * @param string $opt 操作
     */
    public function afterSave($aid, $post, $opt)
    {
        $post['aid'] = $aid;
        $addonFieldExt = !empty($post['addonFieldExt']) ? $post['addonFieldExt'] : array();
        model('Field')->dealChannelPostData($post['channel'], $post, $addonFieldExt);
        // 自動推送鏈接給蜘蛛
        push_zzbaidu($opt, $aid);

        // ---------多圖
        model('ImagesUpload')->saveimg($aid, $post);
        // ---------end

        // --處理TAG標籤
        model('Taglist')->savetags($aid, $post['typeid'], $post['tags']);

        /*清除頁面快取*/
        // $htmlCacheLogic = new \app\common\logic\HtmlCacheLogic;
        // $htmlCacheLogic->clear_archives([$aid]);
        /*--end*/
    }

    /**
     * 獲取單條記錄
     * @author wengxianhu by 2017-7-26
     */
    public function getInfo($aid, $field = '', $isshowbody = true)
    {
        $result = array();
        $field = !empty($field) ? $field : '*';
        $result = db('archives')->field($field)
            ->where([
                'aid'   => $aid,
                'lang'  => get_admin_lang(),
            ])
            ->find();
        if ($isshowbody) {
            $tableName = M('channeltype')->where('id','eq',$result['channel'])->getField('table');
            $result['addonFieldExt'] = db($tableName.'_content')->where('aid',$aid)->find();
        }

        // 圖集TAG標籤
        if (!empty($result)) {
            $typeid = isset($result['typeid']) ? $result['typeid'] : 0;
            $tags = model('Taglist')->getListByAid($aid, $typeid);
            $result['tags'] = $tags;
        }

        return $result;
    }
    

    /**
     * 獲取多條記錄
     * @author lindaoyun by 2017-9-18
     */
    public function getListByLimit($map = array(),  $limit = 15, $field = '*', $order = 'a.aid desc')
    {
        $data = array();
        $field_arr = explode(',', $field);
        foreach ($field_arr as $key => $val) {
            array_push($data, 'a.'.trim($val));
        }
        $field = implode(',', $data);
        $field = 'b.*, '.$field;

        if (!empty($map) && is_array($map)) {
            foreach ($map as $key => $val) {
                if (preg_match("/^(a\.)/i", $val) == 0) {
                    $map['a.'.$key] = $val;
                    unset($map[$key]);
                }
            }
        }
        $map['a.channel'] = $this->channeltype;

        $result = db('archives')
            ->field($field)
            ->alias('a')
            ->join('__ARCTYPE__ b', 'b.id = a.typeid', 'LEFT')
            ->where($map)
            ->order($order)
            ->limit($limit)
            ->select();

        return $result;
    }   

    /**
     * 獲取多條記錄
     * @author wengxianhu by 2017-7-26
     */
    public function getListByClick($limit = 10, $map = array(), $field = '*')
    {
        $map['channel'] = $this->channeltype;
        $map['status'] = 1;
        $result = db('archives')
            ->field($field)
            ->where($map)
            // ->cache(true,EYOUCMS_CACHE_TIME)
            ->order('click desc')
            ->limit($limit)
            ->select();

        return $result;
    }   

    /**
     * 獲取目前圖集的所有上下級分類
     */
    public function getAllCateByTypeid($typeid)
    {
        $result = M('arctype')->field('id,parent_id,typename')
            ->where(array('id|parent_id'=>$typeid, 'status'=>1))
            ->order('parent_id asc, sort_order asc')
            ->getAllWithIndex('id');   

        return $result;     
    }

    /**
     * 刪除的後置操作方法
     * 自定義的一個函式 用於數據刪除后做的相應處理操作, 使用時手動呼叫
     * @param int $aid
     */
    public function afterDel($aidArr = array())
    {
        if (is_string($aidArr)) {
            $aidArr = explode(',', $aidArr);
        }
        // 同時刪除內容
        M('images_content')->where(
                array(
                    'aid'=>array('IN', $aidArr)
                )
            )
            ->delete();
        // 同時刪除圖片
        $result = M('images_upload')->field('image_url')
            ->where(
                array(
                    'aid'=>array('IN', $aidArr)
                )
            )
            ->select();
        if (!empty($result)) {
            foreach ($result as $key => $val) {
                if (!is_http_url($val['image_url'])) {
                    @unlink(ROOT_PATH.trim($val['image_url'], '/'));
                }
            }
            M('images_upload')->where(
                array(
                    'aid'=>array('IN', $aidArr)
                )
            )
            ->delete();
        }
        // 同時刪除TAG標籤
        model('Taglist')->delByAids($aidArr);
    }
}