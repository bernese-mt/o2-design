<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<!--{$page_charset}-->" />
<title>模块字段</title>
<!--{include file="<!--{$cptplpath}-->headerjs.tpl"}-->
<!--{assign var='pluginevent' value=XHook::doAction('adm_head')}-->
</head>
<body>
<!--{assign var='pluginevent' value=XHook::doAction('adm_main_top')}-->
<!--{if $a eq "run"}-->
<div class="main-wrap">
  <div class="path"><p>当前位置：模块管理<span>&gt;&gt;</span><!--{$smodule}-->模型<span>&gt;&gt;</span>模块字段</p></div>
  <div class="main-cont">
    <h3 class="title"><a href="<!--{$cpfile}-->?c=modattr&a=add&smodule=<!--{$smodule}-->&streeid=<!--{$streeid}-->" class="btn-general"><span>添加模块字段</span></a>模块字段</h3>
	<div class="search-area ">
	  <form method="post" id="search_form" action="<!--{$cpfile}-->?c=modattr&smodule=<!--{$smodule}-->" />
	  <div class="item">
	    <label>所属栏目：</label><!--{$root_select}-->&nbsp;&nbsp;
		<input type="submit" class="button_s" value="搜 索" />
	  </div>
	  </form>
	</div>
	<form action="<!--{$cpfile}-->?c=modattr&a=del&<!--{$urlitem}-->" method="post" name="myform" id="myform" style="margin:0">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table" align="center">
	  <thead class="tb-tit-bg">
	  <tr>
	    <th width="9%"><div class="th-gap">选择</div></th>
		<th width="8%"><div class="th-gap">排序</div></th>
		<th width="15%"><div class="th-gap">字段简述</div></th>
		<th width="15%"><div class="th-gap">字段名称</div></th>
		<th width="12%"><div class="th-gap">字段类型</div></th>
		<th width="10%"><div class="th-gap">所属栏目</div></th>
		<th width="8%"><div class="th-gap">必填</div></th>
		<th width="8%"><div class="th-gap">状态</div></th>
		<th><div class="th-gap">操作</div></th>
	  </tr>
	  </thead>
	  <tfoot class="tb-foot-bg"></tfoot>
	  <!--{foreach $modattr as $volist}-->
	  <tr onMouseOver="overColor(this)" onMouseOut="outColor(this)">
	    <td align="center">
		<!--{if $volist.issystem=='1'}-->
		<!--{else}-->
		<input name="id[]" type="checkbox" value="<!--{$volist.aid}-->" onClick="checkItem(this, 'chkAll')">
		<!--{/if}-->
		</td>
		<td align="center"><!--{$volist.orders}--></td>
		<td align="left"><!--{$volist.typename}--></td>
		<td align="center"><!--{$volist.attrname}--></td>
		<td align="center">
		<!--{if $volist.inputtype == 'text'}-->
		单行文本
		<!--{elseif $volist.inputtype == 'textarea'}-->
		多行文本
		<!--{elseif $volist.inputtype == 'checkbox'}-->
		多选框
		<!--{elseif $volist.inputtype == 'radio'}-->
		单选框
		<!--{elseif $volist.inputtype == 'select'}-->
		下拉选框
		<!--{elseif $volist.inputtype == 'img'}-->
		图片附件
		<!--{elseif $volist.inputtype == 'attchment'}-->
		文件附件
		<!--{/if}-->
		</td>
		<td align="center">
		<!--{if $volist.treeid==0}-->
		所有栏目
		<!--{else}-->
		<!--{$volist.catname}-->
		<!--{/if}-->
		</td>

		<td align="center">
		<!--{if $volist.isvalid==0}-->
			<input type="hidden" id="attr_isvalid<!--{$volist.aid}-->" value="isvalidopen" />
			<img id="isvalid<!--{$volist.aid}-->" src="<!--{$urlpath}-->tpl/static/images/no.gif" onClick="javascript:fetch_ajax('isvalid','<!--{$volist.aid}-->');" style="cursor:pointer;">
		<!--{else}-->
			<input type="hidden" id="attr_isvalid<!--{$volist.aid}-->" value="isvalidclose" />
			<img id="isvalid<!--{$volist.aid}-->" src="<!--{$urlpath}-->tpl/static/images/yes.gif" onClick="javascript:fetch_ajax('isvalid','<!--{$volist.aid}-->');" style="cursor:pointer;">	
		<!--{/if}-->
        </td>

		<td align="center">
		<!--{if $volist.flag==0}-->
			<input type="hidden" id="attr_flag<!--{$volist.aid}-->" value="flagopen" />
			<img id="flag<!--{$volist.aid}-->" src="<!--{$urlpath}-->tpl/static/images/no.gif" onClick="javascript:fetch_ajax('flag','<!--{$volist.aid}-->');" style="cursor:pointer;">
		<!--{else}-->
			<input type="hidden" id="attr_flag<!--{$volist.aid}-->" value="flagclose" />
			<img id="flag<!--{$volist.aid}-->" src="<!--{$urlpath}-->tpl/static/images/yes.gif" onClick="javascript:fetch_ajax('flag','<!--{$volist.aid}-->');" style="cursor:pointer;">	
		<!--{/if}-->
        </td>

		<td align="center"><a href="<!--{$cpfile}-->?c=modattr&a=edit&id=<!--{$volist.aid}-->&page=<!--{$page}-->&<!--{$urlitem}-->" class="icon-edit">编辑</a>&nbsp;&nbsp;<a href="<!--{$cpfile}-->?c=modattr&a=del&id[]=<!--{$volist.aid}-->&<!--{$urlitem}-->" onClick="{if(confirm('确定要删除吗？')){return true;} return false;}" class="icon-del">删除</a></td>
	  </tr>
	  <!--{foreachelse}-->
      <tr>
	    <td colspan="9" align="center">暂无信息</td>
	  </tr>
	  <!--{/foreach}-->
	  <!--{if $total>0}-->
	  <tr>
		<td align="center"><input name="chkAll" type="checkbox" id="chkAll" onClick="checkAll(this, 'id[]')" value="checkbox"></td>
		<td class="hback" colspan="8"><input class="button" name="btn_del" type="button" value="删 除" onClick="{if(confirm('确定要删除吗？')){$('#myform').submit();return true;}return false;}" class="button">&nbsp;&nbsp;<!--{$smodule}-->模型 共[ <b><!--{$total}--></b> ] 个字段</td>
	  </tr>
	  <!--{/if}-->
	</table>
	</form>
	<!--{if $pagecount>1}-->
	<table width='95%' border='0' cellspacing='0' cellpadding='0' align='center' style="margin-top:10px;">
	  <tr>
		<td align='center'><!--{$showpage}--></td>
	  </tr>
	</table>
	<!--{/if}-->
  </div>
</div>
<!--{/if}-->

<!--{if $a eq "add"}-->
<div class="main-wrap">
  <div class="path"><p>当前位置：模块管理<span>&gt;&gt;</span><!--{$smodule}-->模型<span>&gt;&gt;</span>添加字段</p></div>
  <div class="main-cont">
	<h3 class="title"><a href="<!--{$cpfile}-->?c=modattr&smodule=<!--{$smodule}-->&streeid=<!--{$streeid}-->" class="btn-general"><span>返回列表</span></a>添加字段</h3>
    <form name="myform" id="myform" method="post" action="<!--{$cpfile}-->?c=modattr&smodule=<!--{$smodule}-->&streeid=<!--{$streeid}-->" onsubmit='return checkform();' />
	<input type='hidden' name='a' id='a' value='saveadd' />
	<table cellpadding='1' cellspacing='1' class='tab'>
	  <tr>
		<td class='hback_1' width="15%">所属栏目 <span class='f_red'></span></td>
		<td class='hback' width="85%"><!--{$root_select}--> <span id="dtreeid" class='f_red'></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>简述文字 <span class="f_red">*</span> </td>
		<td class='hback'><input type="text" name="typename" id="typename" class="input-150" /> <span id='dtypename' class='f_red'></span> <br />(发布内容时显示的字段提示，请输入长度不能大于50的任意字符段)</td>
	  </tr>
	  <tr>
		<td class='hback_1'>字段类型 <span class="f_red">*</span> </td>
		<td class='hback'>
		<select name='inputtype' id='inputtype'>
		<option value="">请选择</option>
		<option value="text">单行文本</option>
		<option value="textarea">多行文本</option>
		<option value="checkbox">多选框</option>
		<option value="radio">单选框</option>
		<option value="select">下拉框</option>
		<option value="img">图片附件</option>
		<option value="attchment">文件附件</option>
		</select>
		<span id='dinputtype' class='f_red'></span> （注意一旦选择不能修改）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>字段名称 <span class="f_red">*</span> </td>
		<td class='hback'><input type="text" name="attrname" id="attrname" class="input-100" /> <span id='dattrname' class='f_red'></span> (同一模型名称必须唯一)<br />（请输入长度不能小于2和大于40的英文字符，注意一旦填写不能修改）</td>
	  </tr>

	  <tr>
		<td class='hback_1'>提示文字 <span class="f_red"></span> </td>
		<td class='hback'><input type="text" name="typeremark" id="typeremark" class="input-txt" /> <span id='dtyperemark' class='f_red'></span> <br />（发布内容时显示的备注内容，请输入长度不能大于200的任意字符段）</td>
	  </tr>

	  <tr>
		<td class='hback_1'>默认值 <span class="f_red"></span></td>
		<td class='hback'><textarea name='attrvalue' id='attrvalue' style='width:50%;height:80px;overflow:auto;'></textarea>  <span id='dattrvalue' class='f_red'></span> <br />（如果字段为下拉、复合选项关联输入框、单选或多选，每个默认值回车输入）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>输入框长度  <span class="f_red"></span></td>
		<td class='hback'><input type="text" name="attrwidth" id="attrwidth" class="input-s" value="100" />像素/百分比  <span id='dattrwidth' class='f_red'></span> <br />(单行文本，请填写数字 60,80,100,150,200,250，多行文本填写1-100百分比)</td>
	  </tr>
	  <tr>
		<td class='hback_1'>本文框高度 <span class="f_red"></span></td>
		<td class='hback'><input type="text" name="attrheight" id="attrheight" class="input-s" value="60" />像素  <span id='dattrheight' class='f_red'></span> (本属性只对多行文本框有效)</td>
	  </tr>
	  <tr>
		<td class='hback_1'>字段排序 <span class="f_red"></span></td>
		<td class='hback'><input type="text" name="orders" id="orders" class="input-s" value="0" />  <span id='dorders' class='f_red'></span> (数字越小越考前)</td>
	  </tr>
	  <tr>
		<td class='hback_1'>必填字段 <span class="f_red"></span> </td>
		<td class='hback'><input type="radio" name="isvalid" id="isvalid" value="1" />启用，<input type="radio" name="isvalid" id="isvalid" value="0" checked />关闭</td>
	  </tr>

	  <tr>
		<td class='hback_1'>显示状态 <span class="f_red"></span> </td>
		<td class='hback'><input type="radio" name="flag" id="flag" value="1" checked />启用，<input type="radio" name="flag" id="flag" value="0" />关闭</td>
	  </tr>

	  <tr>
		<td class='hback_none'></td>
		<td class='hback_none'><input type="submit" name="btn_save" class="button" value="添加保存" /></td>
	  </tr>
	</table>
	</form>
  </div>
  <div style="clear:both;"></div>
</div>
<!--{/if}-->

<!--{if $a eq "edit"}-->
<div class="main-wrap">
  <div class="path"><p>当前位置：模块管理<span>&gt;&gt;</span><!--{$smodule}-->模型<span>&gt;&gt;</span>编辑字段</p></div>
  <div class="main-cont">
	<h3 class="title"><a href="<!--{$cpfile}-->?c=modattr&<!--{$comeurl}-->" class="btn-general"><span>返回列表</span></a>编辑字段</h3>
    <form name="myform" id="myform" method="post" action="<!--{$cpfile}-->?c=modattr&<!--{$comeurl}-->" onsubmit='return checkedit();' />
    <input type="hidden" name="a" value="saveedit" />
	<input type="hidden" name="id" value="<!--{$id}-->" />
	<table cellpadding='1' cellspacing='1' class='tab'>
	  <tr>
		<td class='hback_1' width="15%">所属栏目 <span class='f_red'></span></td>
		<td class='hback' width="85%"><!--{$root_select}--> <span id="dtreeid" class='f_red'></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>简述文字 <span class="f_red">*</span> </td>
		<td class='hback'><input type="text" name="typename" id="typename" class="input-150" value="<!--{$modattr.typename}-->" /> <span id='dtypename' class='f_red'></span> <br />(发布内容时显示的字段提示，请输入长度不能大于50的任意字符段)</td>
	  </tr>
	  <tr>
		<td class='hback_1'>字段类型 <span class="f_red"></span> </td>
		<td class='hback'>
		<select name='inputtype' id='inputtype' disabled>
		<option value="">请选择</option>
		<option value="text"<!--{if $modattr.inputtype=='text'}--> selected<!--{/if}-->>单行文本</option>
		<option value="textarea"<!--{if $modattr.inputtype=='textarea'}--> selected<!--{/if}-->>多行文本</option>
		<option value="checkbox"<!--{if $modattr.inputtype=='checkbox'}--> selected<!--{/if}-->>多选框</option>
		<option value="radio"<!--{if $modattr.inputtype=='radio'}--> selected<!--{/if}-->>单选框</option>
		<option value="select"<!--{if $modattr.inputtype=='select'}--> selected<!--{/if}-->>下拉框</option>
		<option value="img"<!--{if $modattr.inputtype=='img'}--> selected<!--{/if}-->>图片附件</option>
		<option value="attchment"<!--{if $modattr.inputtype=='attchment'}--> selected<!--{/if}-->>文件附件</option>
		</select>
		<span id='dinputtype' class='f_red'></span> (不能更改)</td>
	  </tr>
	  <tr>
		<td class='hback_1'>字段名称 <span class="f_red"></span> </td>
		<td class='hback'><!--{$modattr.attrname}--> <span id='dattrname' class='f_red'></span> （不能更改）</td>
	  </tr>

	  <tr>
		<td class='hback_1'>提示文字 <span class="f_red"></span> </td>
		<td class='hback'><input type="text" name="typeremark" id="typeremark" class="input-txt" value="<!--{$modattr.typeremark}-->" /> <span id='dtyperemark' class='f_red'></span> <br />（发布内容时显示的备注内容，请输入长度不能大于200的任意字符段）</td>
	  </tr>

	  <tr>
		<td class='hback_1'>默认值 <span class="f_red"></span></td>
		<td class='hback'><textarea name='attrvalue' id='attrvalue' style='width:50%;height:80px;overflow:auto;'><!--{$modattr.attrvalue}--></textarea>  <span id='dattrvalue' class='f_red'></span> <br />（如果字段为下拉、复合选项关联输入框、单选或多选，每个默认值回车输入）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>输入框长度  <span class="f_red"></span></td>
		<td class='hback'><input type="text" name="attrwidth" id="attrwidth" class="input-s" value="<!--{$modattr.attrwidth}-->" />像素/百分比  <span id='dattrwidth' class='f_red'></span><br />(单行文本，请填写数字 60,80,100,150,200,250，多行文本填写1-100百分比)</td>
	  </tr>
	  <tr>
		<td class='hback_1'>本文框高度 <span class="f_red"></span></td>
		<td class='hback'><input type="text" name="attrheight" id="attrheight" class="input-s" value="<!--{$modattr.attrheight}-->" />像素  <span id='dattrheight' class='f_red'></span> (本属性只对多行文本框有效)</td>
	  </tr>
	  <tr>
		<td class='hback_1'>字段排序 <span class="f_red"></span></td>
		<td class='hback'><input type="text" name="orders" id="orders" class="input-s" value="<!--{$modattr.orders}-->" />  <span id='dorders' class='f_red'></span> (数字越小越考前)</td>
	  </tr>
	  <tr>
		<td class='hback_1'>必填字段 <span class="f_red"></span> </td>
		<td class='hback'><input type="radio" name="isvalid" id="isvalid" value="1"<!--{if $modattr.isvalid=='1'}--> checked<!--{/if}--> />启用，<input type="radio" name="isvalid" id="isvalid" value="0"<!--{if $modattr.isvalid=='0'}--> checked<!--{/if}--> />关闭</td>
	  </tr>

	  <tr>
		<td class='hback_1'>显示状态 <span class="f_red"></span> </td>
		<td class='hback'><input type="radio" name="flag" id="flag" value="1"<!--{if $modattr.flag=='1'}--> checked<!--{/if}--> />启用，<input type="radio" name="flag" id="flag" value="0"<!--{if $modattr.flag=='0'}--> checked<!--{/if}--> />关闭</td>
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

	t = "typename";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("简述文字不能为空", t);
		$('#'+t).focus();
		return false;
	}
	t = "inputtype";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("字段类型不能为空", t);
		$('#'+t).focus();
		return false;
	}

	t = "attrname";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("字段名称不能为空", t);
		$('#'+t).focus();
		return false;
	}
	else {
		if (v.length<2 || v.length>40) {
			dmsg("字段长度不正确", t);
			$('#'+t).focus();
			return false;
		}
	}
	return true;
}

function checkedit() {
	var t = "";
	var v = "";

	t = "typename";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("简述文字不能为空", t);
		$('#'+t).focus();
		return false;
	}

	return true;
}
</script>