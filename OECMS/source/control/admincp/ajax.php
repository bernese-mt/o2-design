<?php
/**
 * [OECMS] (C)2012-2099 OEdev,Inc.
 * <E-Mail：phpcoo@qq.com>
 * Url www.oedev.net, www.phpcoo.com
 * Update 2013.02.05
*/
if(!defined('IN_OECMS')) {
	exit('Access Denied');
}
class control extends adminbase {   

	
    public function action_run() {}
    
    
    public function action_add_album() {
		$imglist = '';
        
        $sort = XRequest::getInt('sort');
		
        $orders = ($sort+1);
		
		$module = XRequest::getArgs('module');
		if (empty($module)) {
			$module = 'product';
		}
    	$imglist .= "<tr class='imglist'>\n".
                    "  <td class='hback_1'><a href='###' onclick='album_countnums();album_del($(this), {$orders});'>删除</a> 图片{$orders}</td>\n".
                    "  <td class='hback'>\n".

					"    <table border='0' cellspacing='0' cellpadding='0'>\n".
					"	   <tr>\n".
					"	     <td colspan='2'>\n".
                    "			排序：<input name='imgorders_{$orders}' id='imgorders_{$orders}' type='text' class='input-s' value='{$orders}' />&nbsp;".
                    "			图片描述：<input name='imgname_{$orders}' id='imgname_{$orders}' type='text' class='input-150' />&nbsp;".
					"		  </td>\n".
					"	   </tr>\n".
					"	   <tr>\n".
					"	     <td>\n".
    	            "		    图片地址：<input name='imgurl_{$orders}' id='imgurl_{$orders}' type='text' class='input-200' /><input type='hidden' name='imgthumb_{$orders}' id='imgthumb_{$orders}' value='' />".
					"		 </td>\n".
					"		 <td>\n".
					"			<iframe id='iframe_t_{$orders}' border='0' frameborder='0' scrolling='no' style='width:260px;height:25px;padding:0px;margin:0px;' src='".$this->cpfile."?c=upload&module=".$module."&formname=myform&uploadinput=imgurl_{$orders}&thumbinput=imgthumb_{$orders}&multipart=sf_upload_{$orders}&previewid=imgpreview_{$orders}'></iframe>".
					"		 </td>\n".
					"	   </tr>\n".
					"	 </table>\n".
					"		<span id='imgpreview_{$orders}'></span>".
                    
    	           "  </td>\n".
    	           "</tr>\n";
        echo json_encode(array('list'=>$imglist));
    }
    
    
    public function action_checkcatalog() {
        $response = false;
        $error = '';
        $name = XRequest::getArgs('name');
        if (false === XValid::isSpChar($name)) {
            $error = '栏目标识格式错误';
        }
        else {
            $model = parent::model('category', 'am');
            if (true === $model->isForbidCatname($name)) {
                $error = '栏目标识系统禁止使用';
            }
            else {
                if (true === $model->doCheckDirName($name)) {
                    $error = '栏目标识已存在';
                }
                else {
                    $response = true;
                    $error = '栏目标识可用';
                }
            }
            unset($model);
        }
        echo json_encode(array('response'=>$response,'msg'=>$error));
    }
    
    
}
?>
