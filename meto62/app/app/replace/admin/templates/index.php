<!--<?php 
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 

defined('IN_MET') or exit('No permission');//保持入口文件，每个应用模板都要添加
//PHP代码
require_once $this->template('ui/head');//引用头部UI文件
echo <<<EOT
-->


{$listjs}


<style type="text/css">
dd{color:#403b3b;}
.danxuan{width:120px; float:left;}
.schedule{color:blue;}
</style>
 
<!--新表单开始-->

<form method="POST" name="myform" action="{$_M[url][site_admin]}/index.php?n=replace&c=replace&a=domodify&anyid={$anyid}&lang={$lang}&cs={$cs}" target="_self">
	
	<div class="v52fmbx_tbmax">
        <div class="v52fmbx_tbbox">
            <div class="v52fmbx">
	
	
	
	<input name="action" type="hidden" value="modify" />
	<input id="idlist" type="hidden" value="" />
	<input id="table" type="hidden" value="" />
	<div class="v52fmbx_dlbox">
	   <dl>
		<dd class="ftype_description">
         一般网站搬家或换域名后图片还是原来的路径，导致图片无法显示，该功能可以很好的解决这一问题
		</dd>
	    </dl>
	
   
	</div>
	<div class="v52fmbx_dlbox">
    <dl> 
		<dt class="text">我要替换：</dt>
		<dd style="color:#403b3b;">
		<select name="class1" id="class1select" class="noselect" >		
		<option value="-1">一级栏目</option>
		<option value="-2">替换全部</option>
		<!--
EOT;
foreach($clist1now as $key=>$val1){
$k=$val1['id'];
echo <<<EOT
-->			
<option  value="$val1[id]" $class1[$k]>$val1[name]</option>
<!--
EOT;
}
echo <<<EOT
-->
				</select>
		<select name="class2" id="class2select" > 
		<option value=0>二级栏目</option>
		</select>
		<select name="class3" id="class3select" onChange=""> 
		<option value=0>三级栏目</option>
		</select>
		&nbsp;&nbsp;<span class="tips">指定某个栏目替换或选择一键替换全部</span>
		</dd>
	</dl>
    </div>
	<div class="v52fmbx_dlbox">
	<dl class="noborder">
	    <dt>替换类型：</dt>
			<dd class="ftype_radio">
				<div class="fbox">
					<label class="danxuan" ><input name="typex" type="radio" value="1" checked >图片src路径</label>
					<label class="danxuan" ><input name="typex" type="radio" value="3">文本内容</label>
					<label class="danxuan" ><input name="typex" type="radio" value="2">超链接</label>
				</div>
				<span class="tips">只限于编辑器中相关内容的替换</span>
			</dd>
    </dl>
	</div>
	<div class="v52fmbx_dlbox">
	<dl>
			<dt>被替换的文字：</dt>
			<dd class="ftype_input">
				<div class="fbox">
				
					<input name="retexted" id="retexted" class="text" value=""/>
				</div>
				
			</dd>
	</dl>
	</div>
	<div class="v52fmbx_dlbox">
	<dl>
			<dt>替换成:</dt>
			<dd class="ftype_input">
				<div class="fbox">
					<input name="retext" id="retext" class="text" value=""/>
				</div>
				
			</dd>
	</dl>
	</div>
	<div class="v52fmbx_dlbox">
	<dl>
			<dt>进度：</dt>
			<dd><span class='schedule' id='schedule'>未开始</span></dd>
	</dl>
	</div>
	<div class="v52fmbx_dlbox">
	<dl>
			<dt>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</dt>
			<dd style= "color:red;" >替换过程不可逆，请做好备份，以防误操作造成丢失数据</dd>
	</dl>
	</div>
	
    <dl>
			<dt></dt>
			<dd>
				<input type="submit" name="submit" id="submit" value="替换" class="submit" >
			</dd>
	</dl>
	
	 
	  $foot
	   </div>
      </div>
   </div>
	
</form>	
	<!--新表单结束----->
	

<!--
EOT;
require_once $this->template('ui/foot');//引用底部UI文件
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>