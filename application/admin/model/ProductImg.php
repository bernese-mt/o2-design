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
 * 產品圖片
 */
class ProductImg extends Model
{
    //初始化
    protected function initialize()
    {
        // 需要呼叫`Model`的`initialize`方法
        parent::initialize();
    }

    /**
     * 獲取單條產品的所有圖片
     * @author 小虎哥 by 2018-4-3
     */
    public function getProImg($aid, $field = '*')
    {
        $result = db('ProductImg')->field($field)
            ->where('aid', $aid)
            ->order('sort_order asc')
            ->select();

        return $result;
    }

    /**
     * 刪除單條產品的所有圖片
     * @author 小虎哥 by 2018-4-3
     */
    public function delProImg($aid = array())
    {
        if (!is_array($aid)) {
            $aid = array($aid);
        }
        $result = db('ProductImg')->where(array('aid'=>array('IN', $aid)))->delete();

        return $result;
    }



    /**
     * 儲存產品圖片
     * @author 小虎哥 by 2018-4-3
     */
    public function saveimg($aid, $post = array())
    {
        $proimg = isset($post['proimg']) ? $post['proimg'] : array();
        if (!empty($proimg) && count($proimg) > 1) {
            array_pop($proimg); // 彈出最後一個

            // 刪除產品圖片
            $this->delProImg($aid);

             // 新增圖片
            $data = array();
            $sort_order = 0;
            foreach($proimg as $key => $val)
            {
                if($val == null || empty($val))  continue;
                
                $img_info = array();
                $filesize = 0;
                if (is_http_url($val)) {
                    $imgurl = $val;
                } else {
                    $imgurl = ROOT_PATH.ltrim($val, '/');
                    $filesize = @filesize('.'.$val);
                }
                $img_info = @getimagesize($imgurl);
                $width = isset($img_info[0]) ? $img_info[0] : 0;
                $height = isset($img_info[1]) ? $img_info[1] : 0;
                $type = isset($img_info[2]) ? $img_info[2] : 0;
                $attr = isset($img_info[3]) ? $img_info[3] : '';
                $mime = isset($img_info['mime']) ? $img_info['mime'] : '';
                $title = !empty($post['title']) ? $post['title'] : '';
                ++$sort_order;
                $data[] = array(
                    'aid' => $aid,
                    'title' => $title,
                    'image_url'   => $val,
                    'width' => $width,
                    'height' => $height,
                    'filesize'  => $filesize,
                    'mime'  => $mime,
                    'sort_order'    => $sort_order,
                    'add_time' => getTime(),
                );
            }
            if (!empty($data)) {
                M('ProductImg')->insertAll($data);

                // 沒有封面圖時，取第一張圖作為封面圖
                $litpic = isset($post['litpic']) ? $post['litpic'] : '';
                if (empty($litpic)) {
                    $litpic = $data[0]['image_url'];
                    M('archives')->where(array('aid'=>$aid))->update(array('litpic'=>$litpic, 'update_time'=>getTime()));
                }
            }
            delFile(UPLOAD_PATH."product/thumb/$aid"); // 刪除縮圖
        }
    }
}