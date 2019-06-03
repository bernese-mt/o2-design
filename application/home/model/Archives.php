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
use think\Page;
use think\Db;
use app\home\logic\FieldLogic;

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
        $this->fieldLogic = new FieldLogic();
    }

    /**
     * 獲取單條文件記錄
     * @author wengxianhu by 2017-7-26
     */
    public function getViewInfo($aid, $litpic_remote = false)
    {
        $result = array();
        $row = db('archives')->field('*')->find($aid);
        if (!empty($row)) {
            /*封面圖*/
            if (empty($row['litpic'])) {
                $row['is_litpic'] = 0; // 無封面圖
            } else {
                $row['is_litpic'] = 1; // 有封面圖
            }
            $row['litpic'] = get_default_pic($row['litpic'], $litpic_remote); // 預設封面圖

            /*文件基本資訊*/
            if (1 == $row['channel']) { // 文章模型
                $articleModel = new \app\home\model\Article();
                $rowExt = $articleModel->getInfo($aid);
            } else if (2 == $row['channel']) { // 產品模型
                $productModel = new \app\home\model\Product();
                $rowExt = $productModel->getInfo($aid);
                /*產品參數*/
                $productAttrModel = new \app\home\model\ProductAttr();
                $attr_list = $productAttrModel->getProAttr($aid);
                $row['attr_list'] = $attr_list;
                // 產品相簿
                $productImgModel = new \app\home\model\ProductImg();
                $image_list = $productImgModel->getProImg($aid);
                foreach ($image_list as $key => $val) {
                    $val['image_url'] = get_default_pic($val['image_url'], $litpic_remote);
                    $image_list[$key] = $val;
                }
                $row['image_list'] = $image_list;
            } else if (3 == $row['channel']) { // 圖集模型
                $imagesModel = new \app\home\model\Images();
                $rowExt = $imagesModel->getInfo($aid);
                // 圖集相簿
                $imagesUploadModel = new \app\home\model\ImagesUpload();
                $image_list = $imagesUploadModel->getImgUpload($aid);
                foreach ($image_list as $key => $val) {
                    $val['image_url'] = get_default_pic($val['image_url'], $litpic_remote);
                    $image_list[$key] = $val;
                }
                $row['image_list'] = $image_list;
            } else if (4 == $row['channel']) { // 下載模型
                $downloadModel = new \app\home\model\Download();
                $rowExt = $downloadModel->getInfo($aid);
            }
            $rowExt = $this->fieldLogic->getChannelFieldList($rowExt, $row['channel']); // 自定義欄位的數據格式處理
            /*--end*/

            $result = array_merge($rowExt, $row);
        }

        return $result;
    }

    /**
     * 獲取單頁欄目記錄
     * @author wengxianhu by 2017-7-26
     */
    public function getSingleInfo($typeid, $litpic_remote = false)
    {
        $result = array();
        /*文件基本資訊*/
        $singleModel = new \app\home\model\Single();
        $row = $singleModel->getInfoByTypeid($typeid);
        /*--end*/
        if (!empty($row)) {
            /*封面圖*/
            if (empty($row['litpic'])) {
                $row['is_litpic'] = 0; // 無封面圖
            } else {
                $row['is_litpic'] = 1; // 有封面圖
            }
            $row['litpic'] = get_default_pic($row['litpic'], $litpic_remote); // 預設封面圖
            /*--end*/

            $row = $this->fieldLogic->getTableFieldList($row, config('global.arctype_channel_id')); // 自定義欄位的數據格式處理
            /*--end*/
            $row = $this->fieldLogic->getChannelFieldList($row, $row['channel']); // 自定義欄位的數據格式處理

            $result = $row;
        }

        return $result;
    }
}