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

namespace app\admin\validate;
use think\Validate;

class GuestbookAttribute extends Validate
{
    // 驗證規則
    protected $rule = array(
        array('typeid','checkTypeid'),
        array('attr_name','require','表單名稱不能為空'),
        array('attr_input_type', 'require', '請選擇表單型別'),
        array('attr_values','checkAttrValues'),
    );
      
    /**
     *  自定義函式 判斷 使用者選擇 從下面的列表中選擇 可選值列表：不能為空
     * @param type $attr_values
     * @return boolean
     */
    protected function checkTypeid($typeid, $rule)
    {
        if(empty($typeid) || I('param.typeid/d') == 0)         
            return '請選擇欄目……';
        else
            return true;
    }  
      
    /**
     *  自定義函式 判斷 使用者選擇 從下面的列表中選擇 可選值列表：不能為空
     * @param type $attr_values
     * @return boolean
     */
    protected function checkAttrValues($attr_values,$rule)
    {
        if(empty($attr_values) && I('param.attr_input_type/d') == '1')        
            return '可選值列表不能為空';
        else
            return true;
    }    
}