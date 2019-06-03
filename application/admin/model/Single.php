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
 * 單頁
 */
class Single extends Model
{
    //初始化
    protected function initialize()
    {
        // 需要呼叫`Model`的`initialize`方法
        parent::initialize();
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
        model('Field')->dealChannelPostData(6, $post, $addonFieldExt);
    }

    /**
     * 刪除的後置操作方法
     * 自定義的一個函式 用於數據刪除后做的相應處理操作, 使用時手動呼叫
     * @param int $aid
     */
    public function afterDel($typeidArr = array())
    {
        if (is_string($typeidArr)) {
            $typeidArr = explode(',', $typeidArr);
        }

        // 同時刪除單頁文件表
        M('archives')->where(
                array(
                    'typeid'=>array('IN', $typeidArr)
                )
            )
            ->delete();
        // 同時刪除內容
        M('single_content')->where(
                array(
                    'typeid'=>array('IN', $typeidArr)
                )
            )
            ->delete();
        // 清除快取
        \think\Cache::clear("arctype");
        extra_cache('admin_all_menu', NULL);
        \think\Cache::clear('admin_archives_release');
    }
}