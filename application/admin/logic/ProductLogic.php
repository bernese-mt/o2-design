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

namespace app\admin\logic;

use think\Model;
use think\db;
/**
 * 產品邏輯定義
 * Class CatsLogic
 * @package admin\Logic
 */
class ProductLogic extends Model
{
    /**
     * 動態獲取產品參數輸入框 根據不同的數據返回不同的輸入框型別
     * @param int $aid 產品id
     * @param int $typeid 產品欄目id
     */
    public function getAttrInput($aid, $typeid)
    {
        header("Content-type: text/html; charset=utf-8");
        $productAttribute = model('ProductAttribute');
        $attributeList = $productAttribute->where(['typeid'=>$typeid, 'is_del'=>0])->order('sort_order asc, attr_id asc')->select();
        $str = '';
        foreach($attributeList as $key => $val)
        {
            $attr_id = $val['attr_id'];
            $curAttrVal = $this->getProductAttrVal(NULL,$aid, $attr_id);
             //促使他 循環
            if(empty($curAttrVal))
                $curAttrVal[] = array('product_attr_id' =>'','aid' => '','attr_id' => '','attr_value' => '');
            foreach($curAttrVal as $k =>$v)
            {
                $str .= "<dl class='row attr_{$attr_id}'>";
                $addDelAttr = ''; // 加減符號
                $str .= "<dt class='tit'><label for='attr_{$attr_id}'>$addDelAttr {$val['attr_name']}</label></dt>";
                        
                // 單行文字框
                if($val['attr_input_type'] == 0)
                {
                    $str .= "<dd class='opt'><input type='text' size='40' value='".($aid ? $v['attr_value'] : $val['attr_values'])."' name='attr_{$attr_id}[]' /><span class='err' tyle='color:#F00; display:none;'></span><p class='notic'></p></dd>";
                }
                // 下拉選單框（一行代表一個可選值）
                if($val['attr_input_type'] == 1)
                {
                    $str .= "<dd class='opt'><select name='attr_{$attr_id}[]'><option value='0'>無</option>";
                    $tmp_option_val = explode(PHP_EOL, $val['attr_values']);
                    foreach($tmp_option_val as $k2=>$v2)
                    {
                        // 編輯的時候 有選中值
                        $v2 = preg_replace("/\s/","",$v2);
                        if($v['attr_value'] == $v2)
                            $str .= "<option selected='selected' value='{$v2}'>{$v2}</option>";
                        else
                            $str .= "<option value='{$v2}'>{$v2}</option>";
                    }
                    $str .= "</select><span class='err' tyle='color:#F00; display:none;'></span><p class='notic'></p></dd>";                
                }
                // 多行文字框
                if($val['attr_input_type'] == 2)
                {
                    $str .= "<dd class='opt'><textarea cols='40' rows='3' name='attr_{$attr_id}[]'>".($aid ? $v['attr_value'] : $val['attr_values'])."</textarea><span class='err' tyle='color:#F00; display:none;'></span><p class='notic'></p></dd>";
                }
                // 富文字編輯器
                if($val['attr_input_type'] == 3)
                {
                    $str .= "<dd class='opt'><textarea class='span12 ckeditor' id='attr_{$attr_id}' name='attr_{$attr_id}[]'>".($aid ? $v['attr_value'] : $val['attr_values'])."</textarea><span class='err' tyle='color:#F00; display:none;'></span><p class='notic'></p></dd>";
                    $url = url('Ueditor/index', array('savepath'=>'product'));
                    $str .= <<<EOF
<script type="text/javascript">
    UE.getEditor("attr_{$attr_id}",{
        serverUrl :"{$url}",
        zIndex: 999,
        initialFrameWidth: "100%", //初化寬度
        initialFrameHeight: 300, //初化高度            
        focus: false, //初始化時，是否讓編輯器獲得焦點true或false
        maximumWords: 99999,
        removeFormatAttributes: 'class,style,lang,width,height,align,hspace,valign',//允許的最大字元數 'fullscreen',
        pasteplain:false, //是否預設為純文字貼上。false為不使用純文字貼上，true為使用純文字貼上
        autoHeightEnabled: false,
        toolbars: ueditor_toolbars
    });
</script>
EOF;
                }
                $str .=  "</dl>";
            }                        

        }        
        return  $str;
    }
    
    /**
     * 獲取 product_attr 表中指定 aid  指定 attr_id  或者 指定 product_attr_id 的值 可是字串 可是陣列
     * @param int $product_attr_id product_attr表id
     * @param int $aid 產品id
     * @param int $attr_id 產品參數id
     * @return array 返回陣列
     */
    public function getProductAttrVal($product_attr_id = 0 ,$aid = 0, $attr_id = 0)
    {
        $productAttr = M('ProductAttr');        
        if($product_attr_id > 0)
            return $productAttr->where("product_attr_id = $product_attr_id")->select(); 
        if($aid > 0 && $attr_id > 0)
            return $productAttr->where("aid = $aid and attr_id = $attr_id")->select();        
    }

    /**
     *  給指定產品新增屬性 或修改屬性 更新到 product_attr
     * @param int $aid  產品id
     * @param int $typeid  產品欄目id
     */
    public function saveProductAttr($aid, $typeid)
    {  
        $productAttr = M('ProductAttr');
                
        // 屬性型別被更改了 就先刪除以前的屬性型別 或者沒有屬性 則刪除        
        if($typeid == 0)  
        {
            $productAttr->where('aid = '.$aid)->delete(); 
            return;
        }
    
        $productAttrList = $productAttr->where('aid = '.$aid)->select();
        
        $old_product_attr = array(); // 數據庫中的的屬性  以 attr_id _ 和值的 組合爲鍵名
        foreach($productAttrList as $k => $v)
        {                
            $old_product_attr[$v['attr_id'].'_'.$v['attr_value']] = $v;
        }            
                          
        // post 提交的屬性  以 attr_id _ 和值的 組合爲鍵名    
        $post = input("post.");
        foreach($post as $k => $v)
        {
            $attr_id = str_replace('attr_','',$k);
            if(!strstr($k, 'attr_'))
                continue;                                 
            foreach ($v as $k2 => $v2)
            {                      
                //$v2 = str_replace('_', '', $v2); // 替換特殊字元
                //$v2 = str_replace('@', '', $v2); // 替換特殊字元
                $v2 = trim($v2);

                if(empty($v2))
                    continue;

                $tmp_key = $attr_id."_".$v2;
                if(!array_key_exists($tmp_key , $old_product_attr)) // 數據庫中不存在 說明要做刪除操作
                {
                    $adddata = array(
                        'aid'   => $aid,
                        'attr_id'   => $attr_id,
                        'attr_value'   => $v2,
                        'add_time'   => getTime(),
                        'update_time'   => getTime(),
                    );
                    $productAttr->add($adddata);                       
                }
                unset($old_product_attr[$tmp_key]);
           }
            
        }     
        // 沒有被 unset($old_product_attr[$tmp_key]); 掉是 說明 數據庫中存在 表單中沒有提交過來則要刪除操作
        foreach($old_product_attr as $k => $v)
        {                
            $productAttr->where('product_attr_id = '.$v['product_attr_id'])->delete(); // 
        }                       
    }
}