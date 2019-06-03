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

namespace app\admin\controller;

use think\Page;

class Region extends Base
{

    /**
    * 獲取子類列表
    */  
    public function ajax_get_region($pid = 0, $text = ''){
        $data = model('Region')->getList($pid);
        $html = "<option value=''>--".urldecode($text)."--</option>";
        foreach($data as $key=>$val){
            $html.="<option value='".$val['id']."'>".$val['name']."</option>";
        }

        return $html;
    }
}