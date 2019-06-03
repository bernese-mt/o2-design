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

namespace app\home\logic;

use think\Model;
use think\Db;
/**
 * 欄位邏輯定義
 * Class CatsLogic
 * @package home\Logic
 */
class FieldLogic extends Model
{
    /**
     * 查詢解析模型數據用以頁面展示
     * @param array $data 表數據
     * @param intval $channel_id 模型ID
     * @param array $batch 是否批量列表
     * @author 小虎哥 by 2018-7-25
     */
    public function getChannelFieldList($data, $channel_id = '', $batch = false)
    {
        if (!empty($data) && !empty($channel_id)) {
            /*獲取模型對應的附加表字段資訊*/
            $map = array(
                'channel_id'    => $channel_id,
            );
            $fieldInfo = model('Channelfield')->getListByWhere($map, '*', 'name');
            /*--end*/
            $data = $this->handleAddonFieldList($data, $fieldInfo, $batch);
        } else {
            $data = array();
        }
        
        return $data;
    }

    /**
     * 查詢解析單個數據表的數據用以頁面展示
     * @param array $data 表數據
     * @param intval $channel_id 模型ID
     * @param array $batch 是否批量列表
     * @author 小虎哥 by 2018-7-25
     */
    public function getTableFieldList($data, $channel_id = '', $batch = false)
    {
        if (!empty($data) && !empty($channel_id)) {
            /*獲取自定義表字段資訊*/
            $map = array(
                'channel_id'    => $channel_id,
            );
            $fieldInfo = model('Channelfield')->getListByWhere($map, '*', 'name');
            /*--end*/
            $data = $this->handleAddonFieldList($data, $fieldInfo, $batch);
        } else {
            $data = array();
        }

        return $data;
    }

    /**
     * 處理自定義欄位的值
     * @param array $data 表數據
     * @param array $fieldInfo 自定義欄位集合
     * @param array $batch 是否批量列表
     * @author 小虎哥 by 2018-7-25
     */
    public function handleAddonFieldList($data, $fieldInfo, $batch = false)
    {
        if (false !== $batch) {
            return $this->handleBatchAddonFieldList($data, $fieldInfo);
        }

        if (!empty($data) && !empty($fieldInfo)) {
            foreach ($data as $key => $val) {
                $dtype = !empty($fieldInfo[$key]) ? $fieldInfo[$key]['dtype'] : '';
                $dfvalue_unit = !empty($fieldInfo[$key]) ? $fieldInfo[$key]['dfvalue_unit'] : '';
                switch ($dtype) {
                    case 'int':
                    case 'float':
                    case 'decimal':
                    case 'text':
                    {
                        $data[$key.'_unit'] = $dfvalue_unit;
                        break;
                    }

                    case 'checkbox':
                    case 'imgs':
                    case 'files':
                    {
                        if (!is_array($val)) {
                            $val = !empty($val) ? explode(',', $val) : array();
                        }
                        /*支援子目錄*/
                        foreach ($val as $k1 => $v1) {
                            $val[$k1] = handle_subdir_pic($v1);
                        }
                        /*--end*/
                        break;
                    }

                    case 'htmltext':
                    {
                        $val = htmlspecialchars_decode($val);

                        /*追加指定內嵌樣式到編輯器內容的img標籤，相容圖片自動適應頁面*/
                        $titleNew = !empty($data['title']) ? $data['title'] : '';
                        $val = img_style_wh($val, $titleNew);
                        /*--end*/

                        /*支援子目錄*/
                        $val = handle_subdir_pic($val, 'html');
                        /*--end*/
                        break;
                    }

                    case 'decimal':
                    {
                        $val = number_format($val,'2','.',',');
                        break;
                    }
                    
                    default:
                    {
                        /*支援子目錄*/
                        if (is_string($val)) {
                            $val = handle_subdir_pic($val, 'html');
                            $val = handle_subdir_pic($val);
                        }
                        /*--end*/
                        break;
                    }
                }
                $data[$key] = $val;
            }
        }
        return $data;
    }

    /**
     * 列表批量處理自定義欄位的值
     * @param array $data 表數據
     * @param array $fieldInfo 自定義欄位集合
     * @author 小虎哥 by 2018-7-25
     */
    public function handleBatchAddonFieldList($data, $fieldInfo)
    {
        if (!empty($data) && !empty($fieldInfo)) {
            foreach ($data as $key => $subdata) {
                $data[$key] = $this->handleAddonFieldList($subdata, $fieldInfo);
            }
        }
        return $data;
    }
}
