<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<!--{$page_charset}-->" />
<title>模块类型</title>
<!--{include file="<!--{$cptplpath}-->headerjs.tpl"}-->
<!--{assign var='pluginevent' value=XHook::doAction('adm_head')}-->
</head>
<body>
<!--{assign var='pluginevent' value=XHook::doAction('adm_main_top')}-->
<!--{if $a eq "run"}-->
<div class="main-wrap">
  <div class="path"><p>当前位置：模块管理<span>&gt;&gt;</span>栏目管理<span>&gt;&gt;</span><a href="<!--{$cpfile}-->?c=module">模块类型</a></p></div>
  <div class="main-cont">
    <h3 class="title">模块类型</h3>
	<form action="<!--{$cpfile}-->?c=module&a=saveupdate" method="post" name="myform" id="myform" style="margin:0">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table" align="center">
	  <thead class="tb-tit-bg">
	  <tr>
	    <th width="8%"><div class="th-gap">ID</div></th>
		<th width="12%"><div class="th-gap">别名</div></th>
		<th width="15%"><div class="th-gap">名称</div></th>
		<th width="10%"><div class="th-gap">颜色</div></th>
		<th width="15%"><div class="th-gap">默认首页模板</div></th>
		<th width="15%"><div class="th-gap">默认列表模板</div></th>
		<th width="15%"><div class="th-gap">默认内容模板</div></th>
		<th><div class="th-gap">状态</div></th>
	  </tr>
	  </thead>
	  <tfoot class="tb-foot-bg"></tfoot>
	  <!--{foreach $module as $volist}-->
	  <tr onMouseOver="overColor(this)" onMouseOut="outColor(this)">
	    <td align="center"><input type="hidden" name="id[]" value="<!--{$volist.modid}-->" /><!--{$volist.modid}--></td>
		<td><!--{$volist.alias}--></td>
		<td align="left"><input type="text" name="modname_<!--{$volist.modid}-->" value="<!--{$volist.modname}-->" class="input-100" /></td>
		<td align="left"><input type="text" name="color_<!--{$volist.modid}-->" value="<!--{$volist.color}-->" class="input-s" /></td>
		<td align="left"><input type="text" name="tplindex_<!--{$volist.modid}-->" value="<!--{$volist.tplindex}-->" class="input-100" /></td>
		<td align="left"><input type="text" name="tpllist_<!--{$volist.modid}-->" value="<!--{$volist.tpllist}-->" class="input-100" /></td>
		<td align="left"><input type="text" name="tpldetail_<!--{$volist.modid}-->" value="<!--{$volist.tpldetail}-->" class="input-100" /></td>
		<td align="center">
		<select name="enabled_<!--{$volist.modid}-->">
		<option value="1"<!--{if $volist.enabled=='1'}--> selected<!--{/if}-->>开启</option>
		<option value="0"<!--{if $volist.enabled=='0'}--> selected<!--{/if}-->>关闭</option>
		</select>
		</td>
	  </tr>
	  <!--{foreachelse}-->
      <tr>
	    <td colspan="8" align="center">暂无信息</td>
	  </tr>
	  <!--{/foreach}-->
	  <tr>
		<td class="hback" colspan="8" align="right">默认模板，不需要填写当前风格路径；&nbsp;&nbsp;<input class="button" name="btn_update" type="submit" value="保存更新" class="button">&nbsp;&nbsp;共[ <b><!--{$total}--></b> ]个模块类型&nbsp;&nbsp;&nbsp;&nbsp;</td>
	  </tr>
	</table>
	</form>
  </div>
</div>
<!--{/if}-->

<!--{assign var='pluginevent' value=XHook::doAction('adm_footer')}-->
</body>
</html>
