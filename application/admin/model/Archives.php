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
 * 文件主表
 */
class Archives extends Model
{
    //初始化
    protected function initialize()
    {
        // 需要呼叫`Model`的`initialize`方法
        parent::initialize();
    }

    /**
     * 統計每個欄目文件數
     * @param int $aid 產品id
     */
    public function afterSave($aid, $post)
    {
        if (isset($post['aid']) && intval($post['aid']) > 0) {
            $opt = 'edit';
            M('article_content')->where('aid', $aid)->update($post);
        } else {
            $opt = 'add';
            $post['aid'] = $aid;
            M('article_content')->insert($post);
        }
        // 自動推送鏈接給蜘蛛
        push_zzbaidu($opt, $aid);

        // --處理TAG標籤
        model('Taglist')->savetags($aid, $post['typeid'], $post['tags']);
    }

    /**
     * 獲取單條記錄
     * @author wengxianhu by 2017-7-26
     */
    public function getInfo($aid, $field = '', $isshowbody = true)
    {
        $result = array();
        if ($isshowbody) {
            $field = !empty($field) ? $field : 'b.*, a.*, a.aid as aid';
            $result = db('archives')->field($field)
                ->alias('a')
                ->join('__ARTICLE_CONTENT__ b', 'b.aid = a.aid', 'LEFT')
                ->find($aid);
        } else {
            $field = !empty($field) ? $field : 'a.*';
            $result = db('archives')->field($field)
                ->alias('a')
                ->find($aid);
        }

        // 文章TAG標籤
        if (!empty($result)) {
            $typeid = isset($result['typeid']) ? $result['typeid'] : 0;
            $tags = model('Taglist')->getListByAid($aid, $typeid);
            $result['tags'] = $tags;
        }

        return $result;
    }

    /**
     * 偽刪除欄目下所有文件
     */
    public function pseudo_del($typeidArr)
    {
        // 偽刪除文件
        M('archives')->where([
                'typeid'    => ['IN', $typeidArr],
                'is_del'    => 0,
            ])
            ->update([
                'is_del'    => 1,
                'del_method'    => 2,
                'update_time'   => getTime(),
            ]);

        return true;
    }

    /**
     * 刪除欄目下所有文件
     */
    public function del($typeidArr)
    {
        /*獲取欄目下所有文件，並取得每個模型下含有的文件ID集合*/
        $channelAidList = array(); // 模型下的文件ID列表
        $arcrow = M('archives')->where(array('typeid'=>array('IN', $typeidArr)))
            ->order('channel asc')
            ->select();
        foreach ($arcrow as $key => $val) {
            if (!isset($channelAidList[$val['channel']])) {
                $channelAidList[$val['channel']] = array();
            }
            array_push($channelAidList[$val['channel']], $val['aid']);
        }
        /*--end*/

        /*在相關模型下刪除文件殘餘的關聯記錄*/
        $sta = M('archives')->where(array('typeid'=>array('IN', $typeidArr)))->delete(); // 刪除文件
        if ($sta) {
            foreach ($channelAidList as $key => $val) {
                $aidArr = $val;
                /*刪除其餘相關聯的表記錄*/
                switch ($key) {
                    case '1': // 文章模型
                        model('Article')->afterDel($aidArr);
                        break;
                    
                    case '2': // 產品模型
                        model('Product')->afterDel($aidArr);
                        M('product_attribute')->where(array('typeid'=>array('IN', $typeidArr)))->delete();
                        break;
                    
                    case '3': // 圖集模型
                        model('Images')->afterDel($aidArr);
                        break;
                    
                    case '4': // 下載模型
                        model('Download')->afterDel($aidArr);
                        break;
                    
                    case '6': // 單頁模型
                        model('Single')->afterDel($typeidArr);
                        break;

                    default:
                        # code...
                        break;
                }
                /*--end*/
            }
        }
        /*--end*/

        /*刪除留言模型下的關聯內容*/
        $guestbookList = M('guestbook')->where(array('typeid'=>array('IN', $typeidArr)))->select();
        if (!empty($guestbookList)) {
            $aidArr = get_arr_column($guestbookList, 'aid');
            $typeidArr = get_arr_column($guestbookList, 'typeid');
            if ($aidArr && $typeidArr) {
                $sta = M('guestbook')->where(array('typeid'=>array('IN', $typeidArr)))->delete();
                if ($sta) {
                    M('guestbook_attribute')->where(array('typeid'=>array('IN', $typeidArr)))->delete();
                    model('Guestbook')->afterDel($aidArr);
                }
            }
        }
        /*--end*/

        return true;
    }
}