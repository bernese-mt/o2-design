<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<!--{$page_charset}-->" />
<title>单页</title>
<!--{include file="<!--{$cptplpath}-->headerjs.tpl"}-->
<!--{assign var='pluginevent' value=XHook::doAction('adm_head')}-->
</head>
<body>
<!--{assign var='pluginevent' value=XHook::doAction('adm_main_top')}-->

<!--{if $a eq "edit"}-->
<div class="main-wrap">
  <div class="path"><p>当前位置：模块管理<span>&gt;&gt;</span>about单页模型<span>&gt;&gt;</span><a href="<!--{$cpfile}-->?c=about&a=edit&catid=<!--{$catid}-->&id=<!--{$id}-->">编辑[<!--{$about.catname}-->]</a></p></div>
  <div class="main-cont">
	<h3 class="title">编辑[<!--{$about.catname}-->]</h3>
    <form name="myform" id="myform" method="post" action="<!--{$cpfile}-->?c=about&catid=<!--{$catid}-->" />
    <input type="hidden" name="a" value="saveedit" />
	<input type="hidden" name="id" value="<!--{$id}-->" />
	<table cellpadding='1' cellspacing='1' class='tab'>
	  <tr>
		<td class='hback_1' width='15%'>单页标题 <span class="f_red">*</span> </td>
		<td class='hback' width='85%'><input type="text" name="title" id="title" value="<!--{$about.title}-->" class="input-txt" /> <span id='dtitle' class='f_red'></span> (标题长度不能大于255个任意字符 )</td>
	  </tr>
	  <tr>
		<td class='hback_1'>单页简介 <span class="f_red"></span> </td>
		<td class='hback'><textarea name="intro" id="intro" style="width:50%;height:60px;overflow:auto;"><!--{$about.intro}--></textarea>  <span id='dintro' class='f_red'></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>单页图片 <span class="f_red"></span> </td>
		<td class='hback'>
		  <table border="0" cellspacing="0" cellpadding="0">
		    <tr>
			  <td><input type="text" name="uploadfiles" id="uploadfiles" class="input-txt" value="<!--{$about.uploadfiles}-->"  /> <span id='duploadfiles' class='f_red'></span></td>
			  <td>
			  <iframe id='iframe_t' border='0' frameborder='0' scrolling='no' style="width:260px;height:25px;padding:0px;margin:0px;" src='<!--{$cpfile}-->?c=upload&formname=myform&module=article&uploadinput=uploadfiles&multipart=sf_upload'></iframe>
			  </td>
			</tr>
		  </table>	
		</td>
	  </tr>
	  <tr>
		<td class='hback_1'>单页内容</td>
		<td class='hback'>
		  <textarea name="content" id="content" style="width:98%;height:250px;display:none;"><!--{$about.content}--></textarea>
		  <script>var editor;KindEditor.ready(function(K) {editor = K.create('#content'); });</script>
		</td>
	  </tr>
	  <tr>
		<td class='hback_yellow' colspan="2">SEO优化相关设置 </td>
	  </tr>
	  <tr>
		<td class='hback_1'>TAG标签 <span class="f_red"></span> </td>
		<td class='hback'><input type="text" name="tags" id="tags" value="<!--{$about.tags}-->" class="input-txt" /> 
		<span id='dtags' class='f_red'></span>
		(多个标签请用","隔开，会自动关联链接)</td>
	  </tr>
	  <tr>
		<td class='hback_1'>Meta关键字 <span class="f_red"></span> </td>
		<td class='hback'><input type="text" name="metakeyword" id="metakeyword" value="<!--{$about.metakeyword}-->" class="input-txt" /> <span id='dmetakeyword' class='f_red'></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>Meta描述 <span class="f_red"></span> </td>
		<td class='hback'><textarea name="metadescription" id="metadescription" style="width:50%;height:60px;overflow:auto;"><!--{$about.metadescription}--></textarea>  <span id='dmetadescription' class='f_red'></span> </td>
	  </tr>
	  <tr>
		<td class='hback_yellow' colspan="2">个性化设置 </td>
	  </tr>
	  <tr>
		<td class='hback_1'>指定模板文件 <span class="f_red"></span> </td>
		<td class='hback'><input type="text" name="tplname" id="tplname" value="<!--{$about.tplname}-->" class="input-150" />.tpl 
		<span id='dtplname' class='f_red'></span>  &nbsp;&nbsp;<a href="javascript:void(0);" onclick="tbox_templet('选择模板文件', 'tplname');">选择模板</a> <br />(模板文件无扩展名，并确保模板文件夹存在，不填写默认使用栏目和模型的模板)</td>
	  </tr>
	  <tr>
		<td class='hback_none'></td>
		<td class='hback_none'><input type="submit" name="btn_save" class="button" value="更新保存" /></td>
	  </tr>
	</table>
	</form>
  </div>
  <div style="clear:both;"></div>
</div>
<!--{/if}-->
<!--{assign var='pluginevent' value=XHook::doAction('adm_footer')}-->
</body>
</html>
<script type="text/javascript">
function checkform() {
	var t = "";
	var v = "";

	t = "name";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("标签名称不能为空", t);
		return false;
	}
	t = "description";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("标签描述不能为空", t);
		return false;
	}
	return true;
}
</script>
