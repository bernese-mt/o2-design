<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<!--{$page_charset}-->" />
<title>栏目设置</title>
<!--{include file="<!--{$cptplpath}-->headerjs.tpl"}-->
<!--{assign var='pluginevent' value=XHook::doAction('adm_head')}-->
</head>
<body>
<!--{assign var='pluginevent' value=XHook::doAction('adm_main_top')}-->
<!--{if $a eq "run"}-->
<div class="main-wrap">
  <div class="path"><p>当前位置：模块管理<span>&gt;&gt;</span>栏目管理<span>&gt;&gt;</span><a href="<!--{$cpfile}-->?c=category">栏目设置</a></p></div>
  <div class="main-cont">
    <h3 class="title">
	<a href="<!--{$cpfile}-->?c=category&a=add_nodeoutside" class="btn-general"><span>添加一级外部栏目</span></a>
	<a href="<!--{$cpfile}-->?c=category&a=add_nodeabout" class="btn-general"><span>添加一级单页栏目</span></a>
	<a href="<!--{$cpfile}-->?c=category&a=add_nodelist" class="btn-general"><span>添加一级列表栏目</span></a>
	
	栏目设置
	</h3>
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table" align="center">
	  <thead class="tb-tit-bg">
	  <tr>
	    <th width="6%"><div class="th-gap">ID</div></th>
		<th width="6%"><div class="th-gap">排序</div></th>
		<th width="8%"><div class="th-gap">模型</div></th>
		<th width="12%"><div class="th-gap">栏目标识</div></th>
		<th><div class="th-gap">栏目名称</div></th>
		<th width="6%"><div class="th-gap">状态</div></th>
		<th width="8%"><div class="th-gap">主导航</div></th>
		<th width="8%"><div class="th-gap">副导航</div></th>
		<th width="20%"><div class="th-gap">操作</div></th>
	  </tr>
	  </thead>
	  <tfoot class="tb-foot-bg"></tfoot>
	  <!--{foreach $category as $volist}-->
	  <tr onMouseOver="overColor(this)" onMouseOut="outColor(this)">
	    <td align="center"><!--{$volist.catid}--></td>
		<td align="center"><!--{$volist.orders}--></td>
		<td align="center"><font color="<!--{$volist.modcolor}-->"><!--{$volist.modname}--></font></td>
		<td>
		<!--{if !empty($volist.dirname)}-->
		<!--{if $volist.linktype==1}-->
		<a href="<!--{$volist.url}-->" target="_blank"><!--{$volist.dirname}--></a>
		<!--{else}-->
		<a href="<!--{$volist.outurl}-->" target="_blank"><!--{$volist.dirname}--></a>
		<!--{/if}-->
		<!--{/if}-->
		</td>
		<td>
		<!--{if $volist.depth==0}-->
		<b><!--{$volist.tree_node}--></b>
		<!--{else}-->
		<!--{$volist.tree_node}-->
		<!--{/if}-->
		&nbsp;&nbsp;
		</td>
		<td align="center">
		<!--{if $volist.flag==0}-->
			<input type="hidden" id="attr_flag<!--{$volist.catid}-->" value="flagopen" />
			<img id="flag<!--{$volist.catid}-->" src="<!--{$urlpath}-->tpl/static/images/no.gif" onClick="javascript:fetch_ajax('flag','<!--{$volist.catid}-->');" style="cursor:pointer;">
		<!--{else}-->
			<input type="hidden" id="attr_flag<!--{$volist.catid}-->" value="flagclose" />
			<img id="flag<!--{$volist.catid}-->" src="<!--{$urlpath}-->tpl/static/images/yes.gif" onClick="javascript:fetch_ajax('flag','<!--{$volist.catid}-->');" style="cursor:pointer;">	
		<!--{/if}-->
		</td>
		<td align="center">
		<!--{if $volist.ismenu==0}-->
			<input type="hidden" id="attr_ismenu<!--{$volist.catid}-->" value="ismenuopen" />
			<img id="ismenu<!--{$volist.catid}-->" src="<!--{$urlpath}-->tpl/static/images/no.gif" onClick="javascript:fetch_ajax('ismenu','<!--{$volist.catid}-->');" style="cursor:pointer;">
		<!--{else}-->
			<input type="hidden" id="attr_ismenu<!--{$volist.catid}-->" value="ismenuclose" />
			<img id="ismenu<!--{$volist.catid}-->" src="<!--{$urlpath}-->tpl/static/images/yes.gif" onClick="javascript:fetch_ajax('ismenu','<!--{$volist.catid}-->');" style="cursor:pointer;">	
		<!--{/if}-->
		</td>
		<td align="center">
		<!--{if $volist.isaccessory==0}-->
			<input type="hidden" id="attr_isaccessory<!--{$volist.catid}-->" value="isaccessoryopen" />
			<img id="isaccessory<!--{$volist.catid}-->" src="<!--{$urlpath}-->tpl/static/images/no.gif" onClick="javascript:fetch_ajax('isaccessory','<!--{$volist.catid}-->');" style="cursor:pointer;">
		<!--{else}-->
			<input type="hidden" id="attr_isaccessory<!--{$volist.catid}-->" value="isaccessoryclose" />
			<img id="isaccessory<!--{$volist.catid}-->" src="<!--{$urlpath}-->tpl/static/images/yes.gif" onClick="javascript:fetch_ajax('isaccessory','<!--{$volist.catid}-->');" style="cursor:pointer;">	
		<!--{/if}-->
		</td>
		
		<td align="center">
		<!-- 添加子栏目 -->
		<!--{if $volist.modalias=='about'}-->
			<!--{if $volist.depth==0}-->
			<a href="<!--{$cpfile}-->?c=category&a=add_about&rootid=<!--{$volist.catid}-->" class="icon-show">添加二级栏目</a>
			<!--{/if}-->
		<!--{elseif $volist.modalias=='outside'}-->
		<!--{else}-->
			<!--{if $volist.depth<4}-->
			<a href="<!--{$cpfile}-->?c=category&a=add_list&rootid=<!--{$volist.catid}-->" class="icon-add">添加<!--{$volist.depth+2}-->级栏目</a>
			<!--{/if}-->
		<!--{/if}-->
        &nbsp;
		<!-- 修改 -->
		<!--{if $volist.rootid=='0'}-->
			<!--{if $volist.modalias=='about'}-->
			<a href="<!--{$cpfile}-->?c=category&a=edit_nodeabout&id=<!--{$volist.catid}-->" class="icon-set">设置</a>
			<!--{elseif $volist.modalias=='outside'}-->
			<a href="<!--{$cpfile}-->?c=category&a=edit_nodeoutside&id=<!--{$volist.catid}-->" class="icon-edit">设置</a>
			<!--{else}-->
			<a href="<!--{$cpfile}-->?c=category&a=edit_nodelist&id=<!--{$volist.catid}-->" class="icon-set">设置</a>
			<!--{/if}-->
		<!--{else}-->
			<!--{if $volist.modalias=='about'}-->
			<a href="<!--{$cpfile}-->?c=category&a=edit_about&id=<!--{$volist.catid}-->" class="icon-set">设置</a>
			<!--{elseif $volist.modalias=='outside'}-->
			<!--{else}-->
			<a href="<!--{$cpfile}-->?c=category&a=edit_list&id=<!--{$volist.catid}-->" class="icon-edit">修改</a>
			<!--{/if}-->
		<!--{/if}-->
		&nbsp;
		<!--{if $volist.system==0}-->
		<a href="<!--{$cpfile}-->?c=category&a=del&id=<!--{$volist.catid}-->" onClick="{if(confirm('确定要删除吗？一旦删除无法恢复！')){return true;} return false;}" class="icon-del">删除</a>
		<!--{/if}-->
		</td>
	  </tr>
	  <!--{foreachelse}-->
      <tr>
	    <td colspan="9" align="center">暂无信息</td>
	  </tr>
	  <!--{/foreach}-->
	</table>
	<!--{if $total>1}-->
	<table width='95%' border='0' cellspacing='0' cellpadding='0' align='center' style="margin-top:10px;">
	  <tr>
		<td align='left'>共有栏目：<!--{$total}--> 个</td>
	  </tr>
	</table>
	<!--{/if}-->
  </div>
</div>
<!--{/if}-->

<!--{if $a eq "add_nodelist"}-->
<div class="main-wrap">
  <div class="path"><p>当前位置：模块管理<span>&gt;&gt;</span>栏目管理<span>&gt;&gt;</span><a href="<!--{$cpfile}-->?c=category&a=add_nodelist">添加一级列表栏目</a></p></div>
  <div class="main-cont">
	<h3 class="title"><a href="<!--{$cpfile}-->?c=category" class="btn-general"><span>返回列表</span></a>添加一级列表栏目</h3>
    <form name="myform" id="myform" method="post" action="<!--{$cpfile}-->?c=category" onsubmit='return check_add_nodelist();' />
    <input type="hidden" name="a" value="saveadd_nodelist" />
	<table cellpadding='1' cellspacing='2' class='tab'>
	  <tr>
		<td class='hback_1' width="15%">模块类型：<span class='f_red'>*</span></td>
		<td class='hback' width="85%"><!--{$module_select}--> <span class='f_red' id="dmodalias"></span> （一旦选择不能修改）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目名称：<span class='f_red'>*</span></td>
		<td class='hback'><input type="text" name="catname" id="catname" class="input-txt" /> <span class='f_red' id="dcatname"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目子名称：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="asname" id="asname" class="input-txt" /> <span class='f_red' id="dasname"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>目录标识：<span class='f_red'>*</span></td>
		<td class='hback'><input type="text" name="dirname" id="dirname" class="input-100" onblur="ajax_checkcatalog('dirname', 'tips_dirname');" /> <span class='f_red' id="ddirname"></span>
		<span id="tips_dirname"></span>
		<br /> 只能由字母，数字，下横线，中横线组成；<br />不能跟其他栏目的标识相同，一旦填写不能修改。</td>
	  </tr>
	  <tr>
		<td class='hback_1'>同级排序：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="orders" id="orders" class="input-100"  /> <span class='f_red' id="dorders"></span> (数字越小，越靠前)</td>
	  </tr>
	  <tr>
		<td class='hback_1'>状态设置：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="flag" value="1" checked />正常，<input type="radio" name="flag" value="0" />锁定</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目图片：<span class='f_red'></span></td>
		<td class='hback'>
		  <table border="0" cellspacing="0" cellpadding="0">
		    <tr>
			  <td><input type="text" name="catpic" id="catpic" class="input-txt"  /> <span id='dcatpic' class='f_red'></span></td>
			  <td>
			  <iframe id='iframe_t' border='0' frameborder='0' scrolling='no' style="width:260px;height:25px;padding:0px;margin:0px;" src='<!--{$cpfile}-->?c=upload&formname=myform&module=article&uploadinput=catpic&multipart=sf_upload'></iframe>
			  </td>
			</tr>
		  </table>
	    </td>
	  </tr>
	  <tr>
		<td class='hback_yellow' colspan="2">SEO相关设置</td>
	  </tr>
	  <tr>
		<td class='hback_1'>Meta标题：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="metatitle" id="metatitle" class="input-txt" /> <span class='f_red' id="dmetatitle"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>Meta关键字：<span class='f_red'></span></td>
		<td class='hback'><textarea name="metakeyword" id="metakeyword" style="width:40%;height:45px;overflow:auto;"></textarea> <span class='f_red' id="dmetakeyword"></span> （多个用英文逗号隔开）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>Meta描述：<span class='f_red'></span></td>
		<td class='hback'><textarea name="metadescription" id="metadescription" style="width:40%;height:45px;overflow:auto;"></textarea> <span class='f_red' id="dmetadescription"></span></td>
	  </tr>
	  <tr>
		<td class='hback_yellow' colspan="2">显示及其他设置</td>
	  </tr>
	  <tr>
		<td class='hback_1'>主导航显示：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="ismenu" value="1" checked />显示，<input type="radio" name="ismenu" value="0" />关闭</td>
	  </tr>
	  <tr>
		<td class='hback_1'>附加导航显示：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="isaccessory" value="1" />显示，<input type="radio" name="isaccessory" value="0" checked />关闭</td>
	  </tr>
	  <tr>
		<td class='hback_1'>链接类型：<span class='f_red'></span></td>
		<td class='hback'>
		<input type="radio" name="linktype" value="1" checked />内部链接，<input type="radio" name="linktype" id="linktype" value="2" />外部链接，外部链接URL：<input type="text" name="outurl" id="outurl" class="input-txt" />
		<br />
		（内部链接，请继续填写以下设置）
		</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目首页模板：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="tplindex" id="tplindex" class="input-150" /> <a href="javascript:void(0);" onclick="tbox_templet('选择模板文件', 'tplindex');">选择模板</a> <span class='f_red' id="dtplindex"></span> （不需要填写风格路径和模板后缀，不填写则启用模块类型的默认模板）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目列表模板：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="tpllist" id="tpllist" class="input-150" /> <a href="javascript:void(0);" onclick="tbox_templet('选择模板文件', 'tpllist');">选择模板</a> <span class='f_red' id="dtpllist"></span> （不需要填写风格路径和模板后缀，不填写则启用模块类型的默认模板）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目内容模板：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="tpldetail" id="tpldetail" class="input-150" /> <a href="javascript:void(0);" onclick="tbox_templet('选择模板文件', 'tpldetail');">选择模板</a> <span class='f_red' id="dtpldetail"></span> （不需要填写风格路径和模板后缀，不填写则启用模块类型的默认模板）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>每页显示数量：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="pagemax" id="pagemax" class="input-s" /> <span class='f_red' id="dpagemax"></span> （不填写或者0，表示使用默认。）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>列表排序方式：<span class='f_red'></span></td>
		<td class='hback'>
		<input type="radio" name="orderby" value="0" checked />默认，
		<input type="radio" name="orderby" value="1" />更新时间，
		<input type="radio" name="orderby" value="2" />发布时间，
		<input type="radio" name="orderby" value="3" />点击次数，
		<input type="radio" name="orderby" value="4" />ID倒序，
		<input type="radio" name="orderby" value="5" />ID顺序
		</td>
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

<!--{if $a eq "edit_nodelist"}-->
<div class="main-wrap">
  <div class="path"><p>当前位置：模块管理<span>&gt;&gt;</span>栏目管理<span>&gt;&gt;</span>编辑一级列表栏目</p></div>
  <div class="main-cont">
	<h3 class="title"><a href="<!--{$cpfile}-->?c=category" class="btn-general"><span>返回列表</span></a>编辑一级列表栏目</h3>
    <form name="myform" id="myform" method="post" action="<!--{$cpfile}-->?c=category" onsubmit='return check_edit_nodelist();' />
    <input type="hidden" name="a" value="saveedit_nodelist" />
	<input type="hidden" name="id" value="<!--{$id}-->" />
	<table cellpadding='1' cellspacing='2' class='tab'>
	  <tr>
		<td class='hback_1' width="15%">模块类型：<span class='f_red'></span></td>
		<td class='hback' width="85%"><!--{$category.modname}--> （不可更改）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目名称：<span class='f_red'>*</span></td>
		<td class='hback'><input type="text" name="catname" id="catname" class="input-txt" value="<!--{$category.catname}-->" /> <span class='f_red' id="dcatname"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目子名称：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="asname" id="asname" class="input-txt" value="<!--{$category.asname}-->" /> <span class='f_red' id="dasname"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>目录标识：<span class='f_red'></span></td>
		<td class='hback'><!--{$category.dirname}--> （不可更改）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>同级排序：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="orders" id="orders" class="input-100" value="<!--{$category.orders}-->"  /> <span class='f_red' id="dorders"></span> (数字越小，越靠前)</td>
	  </tr>
	  <tr>
		<td class='hback_1'>状态设置：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="flag" value="1"<!--{if $category.flag=='1'}--> checked<!--{/if}--> />正常，<input type="radio" name="flag" value="0"<!--{if $category.flag=='0'}--> checked<!--{/if}--> />锁定</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目图片：<span class='f_red'></span></td>
		<td class='hback'>
		  <table border="0" cellspacing="0" cellpadding="0">
		    <tr>
			  <td><input type="text" name="catpic" id="catpic" class="input-txt" value="<!--{$category.catpic}-->"  /> <span id='dcatpic' class='f_red'></span></td>
			  <td>
			  <iframe id='iframe_t' border='0' frameborder='0' scrolling='no' style="width:260px;height:25px;padding:0px;margin:0px;" src='<!--{$cpfile}-->?c=upload&formname=myform&module=article&uploadinput=catpic&multipart=sf_upload'></iframe>
			  </td>
			</tr>
		  </table>
	    </td>
	  </tr>
	  <tr>
		<td class='hback_yellow' colspan="2">SEO相关设置</td>
	  </tr>
	  <tr>
		<td class='hback_1'>Meta标题：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="metatitle" id="metatitle" class="input-txt" value="<!--{$category.metatitle}-->" /> <span class='f_red' id="dmetatitle"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>Meta关键字：<span class='f_red'></span></td>
		<td class='hback'><textarea name="metakeyword" id="metakeyword" style="width:40%;height:45px;overflow:auto;"><!--{$category.metakeyword}--></textarea> <span class='f_red' id="dmetakeyword"></span> （多个用英文逗号隔开）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>Meta描述：<span class='f_red'></span></td>
		<td class='hback'><textarea name="metadescription" id="metadescription" style="width:40%;height:45px;overflow:auto;"><!--{$category.metadescription}--></textarea> <span class='f_red' id="dmetadescription"></span></td>
	  </tr>
	  <tr>
		<td class='hback_yellow' colspan="2">显示及其他设置</td>
	  </tr>
	  <tr>
		<td class='hback_1'>主导航显示：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="ismenu" value="1"<!--{if $category.ismenu=='1'}--> checked<!--{/if}--> />显示，<input type="radio" name="ismenu" value="0"<!--{if $category.ismenu=='0'}--> checked<!--{/if}--> />关闭</td>
	  </tr>
	  <tr>
		<td class='hback_1'>附加导航显示：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="isaccessory" value="1"<!--{if $category.isaccessory=='1'}--> checked<!--{/if}--> />显示，<input type="radio" name="isaccessory" value="0"<!--{if $category.isaccessory=='0'}--> checked<!--{/if}--> />关闭</td>
	  </tr>
	  <tr>
		<td class='hback_1'>链接类型：<span class='f_red'></span></td>
		<td class='hback'>
		<input type="radio" name="linktype" value="1"<!--{if $category.linktype=='1'}--> checked<!--{/if}--> />内部链接，<input type="radio" name="linktype"  value="2"<!--{if $category.linktype=='2'}--> checked<!--{/if}--> />外部链接，外部链接URL：<input type="text" name="outurl" id="outurl" class="input-txt" value="<!--{$category.outurl}-->" />
		<br />
		（内部链接，请继续填写以下设置）
		</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目首页模板：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="tplindex" id="tplindex" class="input-150" value="<!--{$category.tplindex}-->" /> <a href="javascript:void(0);" onclick="tbox_templet('选择模板文件', 'tplindex');">选择模板</a> <span class='f_red' id="dtplindex"></span> （不需要填写风格路径和模板后缀，不填写则启用模块类型的默认模板）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目列表模板：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="tpllist" id="tpllist" class="input-150" value="<!--{$category.tpllist}-->" /> <a href="javascript:void(0);" onclick="tbox_templet('选择模板文件', 'tpllist');">选择模板</a> <span class='f_red' id="dtpllist"></span> （不需要填写风格路径和模板后缀，不填写则启用模块类型的默认模板）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目内容模板：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="tpldetail" id="tpldetail" class="input-150" value="<!--{$category.tpldetail}-->" /> <a href="javascript:void(0);" onclick="tbox_templet('选择模板文件', 'tpldetail');">选择模板</a> <span class='f_red' id="dtpldetail"></span> （不需要填写风格路径和模板后缀，不填写则启用模块类型的默认模板）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>每页显示数量：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="pagemax" id="pagemax" class="input-s" value="<!--{$category.pagemax}-->" /> <span class='f_red' id="dpagemax"></span> （不填写或者0，表示使用默认。）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>列表排序方式：<span class='f_red'></span></td>
		<td class='hback'>
		<input type="radio" name="orderby" value="0"<!--{if $category.orderby=='0'}--> checked<!--{/if}--> />默认，
		<input type="radio" name="orderby" value="1"<!--{if $category.orderby=='1'}--> checked<!--{/if}--> />更新时间，
		<input type="radio" name="orderby" value="2"<!--{if $category.orderby=='2'}--> checked<!--{/if}--> />发布时间，
		<input type="radio" name="orderby" value="3"<!--{if $category.orderby=='3'}--> checked<!--{/if}--> />点击次数，
		<input type="radio" name="orderby" value="4"<!--{if $category.orderby=='4'}--> checked<!--{/if}--> />ID倒序，
		<input type="radio" name="orderby" value="5"<!--{if $category.orderby=='5'}--> checked<!--{/if}--> />ID顺序
		</td>
	  </tr>
	  <tr>
		<td class='hback_none'></td>
		<td class='hback_none'><input type="submit" name="btn_save" class="button" value="编辑保存" /></td>
	  </tr>
	</table>
	</form>
  </div>
  <div style="clear:both;"></div>
</div>
<!--{/if}-->


<!--{if $a eq "add_nodeabout"}-->
<div class="main-wrap">
  <div class="path"><p>当前位置：模块管理<span>&gt;&gt;</span>栏目管理<span>&gt;&gt;</span><a href="<!--{$cpfile}-->?c=category&a=add_nodeabout">添加一级单页栏目</a></p></div>
  <div class="main-cont">
	<h3 class="title"><a href="<!--{$cpfile}-->?c=category" class="btn-general"><span>返回列表</span></a>添加一级单页栏目</h3>
    <form name="myform" id="myform" method="post" action="<!--{$cpfile}-->?c=category" onsubmit='return check_add_nodeabout();' />
    <input type="hidden" name="a" value="saveadd_nodeabout" />
	<table cellpadding='1' cellspacing='2' class='tab'>
	  <tr>
		<td class='hback_1' width="15%">模块类型：<span class='f_red'></span></td>
		<td class='hback' width="85%">单页模型</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目名称：<span class='f_red'>*</span></td>
		<td class='hback'><input type="text" name="catname" id="catname" class="input-txt" /> <span class='f_red' id="dcatname"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目子名称：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="asname" id="asname" class="input-txt" /> <span class='f_red' id="dasname"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>目录标识：<span class='f_red'>*</span></td>
		<td class='hback'><input type="text" name="dirname" id="dirname" class="input-100" onblur="ajax_checkcatalog('dirname', 'tips_dirname');" /> <span class='f_red' id="ddirname"></span>
		<span id="tips_dirname"></span>
		
		<br /> 只能由字母，数字，下横线，中横线组成；<br />不能跟其他栏目的标识相同，一旦填写不能修改。</td>
	  </tr>
	  <tr>
		<td class='hback_1'>同级排序：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="orders" id="orders" class="input-100"  /> <span class='f_red' id="dorders"></span> (数字越小，越靠前)</td>
	  </tr>
	  <tr>
		<td class='hback_1'>状态设置：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="flag" value="1" checked />正常，<input type="radio" name="flag" value="0" />锁定</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目图片：<span class='f_red'></span></td>
		<td class='hback'>
		  <table border="0" cellspacing="0" cellpadding="0">
		    <tr>
			  <td><input type="text" name="catpic" id="catpic" class="input-txt"  /> <span id='dcatpic' class='f_red'></span></td>
			  <td>
			  <iframe id='iframe_t' border='0' frameborder='0' scrolling='no' style="width:260px;height:25px;padding:0px;margin:0px;" src='<!--{$cpfile}-->?c=upload&formname=myform&module=article&uploadinput=catpic&multipart=sf_upload'></iframe>
			  </td>
			</tr>
		  </table>
	    </td>
	  </tr>
	  <tr>
		<td class='hback_yellow' colspan="2">显示及其他设置</td>
	  </tr>
	  <tr>
		<td class='hback_1'>主导航显示：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="ismenu" value="1" checked />显示，<input type="radio" name="ismenu" value="0" />关闭</td>
	  </tr>
	  <tr>
		<td class='hback_1'>附加导航显示：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="isaccessory" value="1" />显示，<input type="radio" name="isaccessory" value="0" checked />关闭</td>
	  </tr>
	  <tr>
		<td class='hback_1'>链接类型：<span class='f_red'></span></td>
		<td class='hback'>
		<input type="radio" name="linktype" value="1" checked />内部链接，<input type="radio" name="linktype" id="linktype" value="2" />外部链接，外部链接URL：<input type="text" name="outurl" id="outurl" class="input-txt" />
		<br />
		（内部链接，请继续填写以下设置）
		</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目内容模板：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="tpldetail" id="tpldetail" class="input-150" /> <a href="javascript:void(0);" onclick="tbox_templet('选择模板文件', 'tpldetail');">选择模板</a> <span class='f_red' id="dtpldetail"></span> （不需要填写风格路径和模板后缀，不填写则启用模块类型的默认模板）</td>
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

<!--{if $a eq "edit_nodeabout"}-->
<div class="main-wrap">
  <div class="path"><p>当前位置：模块管理<span>&gt;&gt;</span>栏目管理<span>&gt;&gt;</span>编辑一级单页栏目</p></div>
  <div class="main-cont">
	<h3 class="title"><a href="<!--{$cpfile}-->?c=category" class="btn-general"><span>返回列表</span></a>编辑一级单页栏目</h3>
    <form name="myform" id="myform" method="post" action="<!--{$cpfile}-->?c=category" onsubmit='return check_edit_nodeabout();' />
    <input type="hidden" name="a" value="saveedit_nodeabout" />
	<input type="hidden" name="id" value="<!--{$id}-->" />
	<table cellpadding='1' cellspacing='2' class='tab'>
	  <tr>
		<td class='hback_1' width="15%">模块类型：<span class='f_red'></span></td>
		<td class='hback' width="85%"><!--{$category.modname}--> （不可更改）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目名称：<span class='f_red'>*</span></td>
		<td class='hback'><input type="text" name="catname" id="catname" class="input-txt" value="<!--{$category.catname}-->" /> <span class='f_red' id="dcatname"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目子名称：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="asname" id="asname" class="input-txt" value="<!--{$category.asname}-->" /> <span class='f_red' id="dasname"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>目录标识：<span class='f_red'></span></td>
		<td class='hback'><!--{$category.dirname}--> （不可更改）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>同级排序：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="orders" id="orders" class="input-100" value="<!--{$category.orders}-->"  /> <span class='f_red' id="dorders"></span> (数字越小，越靠前)</td>
	  </tr>
	  <tr>
		<td class='hback_1'>状态设置：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="flag" value="1"<!--{if $category.flag=='1'}--> checked<!--{/if}--> />正常，<input type="radio" name="flag" value="0"<!--{if $category.flag=='0'}--> checked<!--{/if}--> />锁定</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目图片：<span class='f_red'></span></td>
		<td class='hback'>
		  <table border="0" cellspacing="0" cellpadding="0">
		    <tr>
			  <td><input type="text" name="catpic" id="catpic" class="input-txt" value="<!--{$category.catpic}-->"  /> <span id='dcatpic' class='f_red'></span></td>
			  <td>
			  <iframe id='iframe_t' border='0' frameborder='0' scrolling='no' style="width:260px;height:25px;padding:0px;margin:0px;" src='<!--{$cpfile}-->?c=upload&formname=myform&module=article&uploadinput=catpic&multipart=sf_upload'></iframe>
			  </td>
			</tr>
		  </table>
	    </td>
	  </tr>
	  <tr>
		<td class='hback_yellow' colspan="2">显示及其他设置</td>
	  </tr>
	  <tr>
		<td class='hback_1'>主导航显示：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="ismenu" value="1"<!--{if $category.ismenu=='1'}--> checked<!--{/if}--> />显示，<input type="radio" name="ismenu" value="0"<!--{if $category.ismenu=='0'}--> checked<!--{/if}--> />关闭</td>
	  </tr>
	  <tr>
		<td class='hback_1'>附加导航显示：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="isaccessory" value="1"<!--{if $category.isaccessory=='1'}--> checked<!--{/if}--> />显示，<input type="radio" name="isaccessory" value="0"<!--{if $category.isaccessory=='0'}--> checked<!--{/if}--> />关闭</td>
	  </tr>
	  <tr>
		<td class='hback_1'>链接类型：<span class='f_red'></span></td>
		<td class='hback'>
		<input type="radio" name="linktype" value="1"<!--{if $category.linktype=='1'}--> checked<!--{/if}--> />内部链接，<input type="radio" name="linktype"  value="2"<!--{if $category.linktype=='2'}--> checked<!--{/if}--> />外部链接，外部链接URL：<input type="text" name="outurl" id="outurl" class="input-txt" value="<!--{$category.outurl}-->" />
		<br />
		（内部链接，请继续填写以下设置）
		</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目内容模板：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="tpldetail" id="tpldetail" class="input-150" value="<!--{$category.tpldetail}-->" /> <a href="javascript:void(0);" onclick="tbox_templet('选择模板文件', 'tpldetail');">选择模板</a> <span class='f_red' id="dtpldetail"></span> （不需要填写风格路径和模板后缀，不填写则启用模块类型的默认模板）</td>
	  </tr>
	  <tr>
		<td class='hback_none'></td>
		<td class='hback_none'><input type="submit" name="btn_save" class="button" value="编辑保存" /></td>
	  </tr>
	</table>
	</form>
  </div>
  <div style="clear:both;"></div>
</div>
<!--{/if}-->


<!--{if $a eq "add_nodeoutside"}-->
<div class="main-wrap">
  <div class="path"><p>当前位置：模块管理<span>&gt;&gt;</span>栏目管理<span>&gt;&gt;</span><a href="<!--{$cpfile}-->?c=category&a=add_nodeoutside">添加一级外部栏目</a></p></div>
  <div class="main-cont">
	<h3 class="title"><a href="<!--{$cpfile}-->?c=category" class="btn-general"><span>返回列表</span></a>添加一级外部栏目</h3>
    <form name="myform" id="myform" method="post" action="<!--{$cpfile}-->?c=category" onsubmit='return check_add_nodeoutside();' />
    <input type="hidden" name="a" value="saveadd_nodeoutside" />
	<table cellpadding='1' cellspacing='2' class='tab'>
	  <tr>
		<td class='hback_1' width="15%">模块类型：<span class='f_red'></span></td>
		<td class='hback' width="85%">外部模型</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目名称：<span class='f_red'>*</span></td>
		<td class='hback'><input type="text" name="catname" id="catname" class="input-txt" /> <span class='f_red' id="dcatname"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>外部链接URL：<span class='f_red'>*</span></td>
		<td class='hback'><input type="text" name="outurl" id="outurl" class="input-txt" /> <span class='f_red' id="douturl"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>同级排序：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="orders" id="orders" class="input-100"  /> <span class='f_red' id="dorders"></span> (数字越小，越靠前)</td>
	  </tr>
	  <tr>
		<td class='hback_1'>状态设置：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="flag" value="1" checked />正常，<input type="radio" name="flag" value="0" />锁定</td>
	  </tr>
	  <tr>
		<td class='hback_1'>主导航显示：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="ismenu" value="1" checked />显示，<input type="radio" name="ismenu" value="0" />关闭</td>
	  </tr>
	  <tr>
		<td class='hback_1'>附加导航显示：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="isaccessory" value="1" />显示，<input type="radio" name="isaccessory" value="0" checked />关闭</td>
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
<!--{if $a eq "edit_nodeoutside"}-->
<div class="main-wrap">
  <div class="path"><p>当前位置：模块管理<span>&gt;&gt;</span>栏目管理<span>&gt;&gt;</span>编辑一级外部栏目</p></div>
  <div class="main-cont">
	<h3 class="title"><a href="<!--{$cpfile}-->?c=category" class="btn-general"><span>返回列表</span></a>编辑一级外部栏目</h3>
    <form name="myform" id="myform" method="post" action="<!--{$cpfile}-->?c=category" onsubmit='return check_edit_nodeoutside();' />
    <input type="hidden" name="a" value="saveedit_nodeoutside" />
	<input type="hidden" name="id" value="<!--{$id}-->" />
	<table cellpadding='1' cellspacing='2' class='tab'>
	  <tr>
		<td class='hback_1' width="15%">模块类型：<span class='f_red'></span></td>
		<td class='hback' width="85%"><!--{$category.modname}--> （不可更改）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目名称：<span class='f_red'>*</span></td>
		<td class='hback'><input type="text" name="catname" id="catname" class="input-txt" value="<!--{$category.catname}-->" /> <span class='f_red' id="dcatname"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>外部链接URL：<span class='f_red'>*</span></td>
		<td class='hback'><input type="text" name="outurl" id="outurl" class="input-txt" value="<!--{$category.outurl}-->" /> <span class='f_red' id="douturl"></span>
		</td>
	  </tr>
	  <tr>
		<td class='hback_1'>同级排序：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="orders" id="orders" class="input-100" value="<!--{$category.orders}-->"  /> <span class='f_red' id="dorders"></span> (数字越小，越靠前)</td>
	  </tr>
	  <tr>
		<td class='hback_1'>状态设置：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="flag" value="1"<!--{if $category.flag=='1'}--> checked<!--{/if}--> />正常，<input type="radio" name="flag" value="0"<!--{if $category.flag=='0'}--> checked<!--{/if}--> />锁定</td>
	  </tr>
	  <tr>
		<td class='hback_1'>主导航显示：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="ismenu" value="1"<!--{if $category.ismenu=='1'}--> checked<!--{/if}--> />显示，<input type="radio" name="ismenu" value="0"<!--{if $category.ismenu=='0'}--> checked<!--{/if}--> />关闭</td>
	  </tr>
	  <tr>
		<td class='hback_1'>附加导航显示：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="isaccessory" value="1"<!--{if $category.isaccessory=='1'}--> checked<!--{/if}--> />显示，<input type="radio" name="isaccessory" value="0"<!--{if $category.isaccessory=='0'}--> checked<!--{/if}--> />关闭</td>
	  </tr>

	  <tr>
		<td class='hback_none'></td>
		<td class='hback_none'><input type="submit" name="btn_save" class="button" value="编辑保存" /></td>
	  </tr>
	</table>
	</form>
  </div>
  <div style="clear:both;"></div>
</div>
<!--{/if}-->


<!--           以下为子栏目操作页面          -->

<!--{if $a eq "add_list"}-->
<div class="main-wrap">
  <div class="path"><p>当前位置：模块管理<span>&gt;&gt;</span>栏目管理<span>&gt;&gt;</span><a href="<!--{$cpfile}-->?c=category&a=add_list&rootid=<!--{$rootid}-->">添加列表子栏目</a></p></div>
  <div class="main-cont">
	<h3 class="title"><a href="<!--{$cpfile}-->?c=category" class="btn-general"><span>返回列表</span></a>添加列表子栏目</h3>
    <form name="myform" id="myform" method="post" action="<!--{$cpfile}-->?c=category" onsubmit='return check_add_list();' />
    <input type="hidden" name="a" value="saveadd_list" />
	<table cellpadding='1' cellspacing='2' class='tab'>
	  <tr>
		<td class='hback_1' width="15%">模块类型：<span class='f_red'></span></td>
		<td class='hback' width="85%"><!--{$modname}--> </td>
	  </tr>
	  <tr>
		<td class='hback_1'>上级栏目：<span class='f_red'>*</span></td>
		<td class='hback'><!--{$root_select}--> <span class='f_red' id="drootid"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目名称：<span class='f_red'>*</span></td>
		<td class='hback'><input type="text" name="catname" id="catname" class="input-txt" /> <span class='f_red' id="dcatname"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目子名称：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="asname" id="asname" class="input-txt" /> <span class='f_red' id="dasname"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>目录标识：<span class='f_red'>*</span></td>
		<td class='hback'><input type="text" name="dirname" id="dirname" class="input-100" onblur="ajax_checkcatalog('dirname', 'tips_dirname');" /> <span class='f_red' id="ddirname"></span>
		<span id="tips_dirname"></span>
		<br /> 只能由字母，数字，下横线，中横线组成；<br />不能跟其他栏目的标识相同，一旦填写不能修改。</td>
	  </tr>
	  <tr>
		<td class='hback_1'>同级排序：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="orders" id="orders" class="input-100"  /> <span class='f_red' id="dorders"></span> (数字越小，越靠前)</td>
	  </tr>
	  <tr>
		<td class='hback_1'>状态设置：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="flag" value="1" checked />正常，<input type="radio" name="flag" value="0" />锁定</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目图片：<span class='f_red'></span></td>
		<td class='hback'>
		  <table border="0" cellspacing="0" cellpadding="0">
		    <tr>
			  <td><input type="text" name="catpic" id="catpic" class="input-txt" /> <span id='dcatpic' class='f_red'></span></td>
			  <td>
			  <iframe id='iframe_t' border='0' frameborder='0' scrolling='no' style="width:260px;height:25px;padding:0px;margin:0px;" src='<!--{$cpfile}-->?c=upload&formname=myform&module=article&uploadinput=catpic&multipart=sf_upload'></iframe>
			  </td>
			</tr>
		  </table>
	    </td>
	  </tr>
	  <tr>
		<td class='hback_yellow' colspan="2">SEO相关设置</td>
	  </tr>
	  <tr>
		<td class='hback_1'>Meta标题：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="metatitle" id="metatitle" class="input-txt" /> <span class='f_red' id="dmetatitle"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>Meta关键字：<span class='f_red'></span></td>
		<td class='hback'><textarea name="metakeyword" id="metakeyword" style="width:40%;height:45px;overflow:auto;"></textarea> <span class='f_red' id="dmetakeyword"></span> （多个用英文逗号隔开）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>Meta描述：<span class='f_red'></span></td>
		<td class='hback'><textarea name="metadescription" id="metadescription" style="width:40%;height:45px;overflow:auto;"></textarea> <span class='f_red' id="dmetadescription"></span></td>
	  </tr>
	  <tr>
		<td class='hback_yellow' colspan="2">显示及其他设置</td>
	  </tr>
	  <tr>
		<td class='hback_1'>主导航显示：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="ismenu" value="1" checked />显示，<input type="radio" name="ismenu" value="0" />关闭</td>
	  </tr>
	  <tr>
		<td class='hback_1'>附加导航显示：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="isaccessory" value="1" />显示，<input type="radio" name="isaccessory" value="0" checked />关闭</td>
	  </tr>
	  <tr>
		<td class='hback_1'>链接类型：<span class='f_red'></span></td>
		<td class='hback'>
		<input type="radio" name="linktype" value="1" checked />内部链接，<input type="radio" name="linktype" id="linktype" value="2" />外部链接，外部链接URL：<input type="text" name="outurl" id="outurl" class="input-txt" />
		<br />
		（内部链接，请继续填写以下设置）
		</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目首页模板：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="tplindex" id="tplindex" class="input-150" /> <a href="javascript:void(0);" onclick="tbox_templet('选择模板文件', 'tplindex');">选择模板</a> <span class='f_red' id="dtplindex"></span> （不需要填写风格路径和模板后缀，不填写则启用模块类型的默认模板）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目列表模板：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="tpllist" id="tpllist" class="input-150" /> <a href="javascript:void(0);" onclick="tbox_templet('选择模板文件', 'tpllist');">选择模板</a> <span class='f_red' id="dtpllist"></span> （不需要填写风格路径和模板后缀，不填写则启用模块类型的默认模板）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目内容模板：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="tpldetail" id="tpldetail" class="input-150" /> <a href="javascript:void(0);" onclick="tbox_templet('选择模板文件', 'tpldetail');">选择模板</a> <span class='f_red' id="dtpldetail"></span> （不需要填写风格路径和模板后缀，不填写则启用模块类型的默认模板）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>每页显示数量：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="pagemax" id="pagemax" class="input-s" /> <span class='f_red' id="dpagemax"></span> （不填写或者0，表示使用默认。）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>列表排序方式：<span class='f_red'></span></td>
		<td class='hback'>
		<input type="radio" name="orderby" value="0" checked />默认，
		<input type="radio" name="orderby" value="1" />更新时间，
		<input type="radio" name="orderby" value="2" />发布时间，
		<input type="radio" name="orderby" value="3" />点击次数，
		<input type="radio" name="orderby" value="4" />ID倒序，
		<input type="radio" name="orderby" value="5" />ID顺序
		</td>
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

<!--{if $a eq "edit_list"}-->
<div class="main-wrap">
  <div class="path"><p>当前位置：模块管理<span>&gt;&gt;</span>栏目管理<span>&gt;&gt;</span>修改列表子栏目</p></div>
  <div class="main-cont">
	<h3 class="title"><a href="<!--{$cpfile}-->?c=category" class="btn-general"><span>返回列表</span></a>修改列表子栏目</h3>
    <form name="myform" id="myform" method="post" action="<!--{$cpfile}-->?c=category" onsubmit='return check_edit_list();' />
    <input type="hidden" name="a" value="saveedit_list" />
	<input type="hidden" name="id" value="<!--{$id}-->" />
	<table cellpadding='1' cellspacing='2' class='tab'>
	  <tr>
		<td class='hback_1' width="15%">模块类型：<span class='f_red'></span></td>
		<td class='hback' width="85%"><!--{$category.modname}--> </td>
	  </tr>
	  <tr>
		<td class='hback_1'>上级栏目：<span class='f_red'>*</span></td>
		<td class='hback'><!--{$root_select}--> <span class='f_red' id="drootid"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目名称：<span class='f_red'>*</span></td>
		<td class='hback'><input type="text" name="catname" id="catname" class="input-txt" value="<!--{$category.catname}-->" /> <span class='f_red' id="dcatname"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目子名称：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="asname" id="asname" class="input-txt" value="<!--{$category.asname}-->" /> <span class='f_red' id="dasname"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>目录标识：<span class='f_red'></span></td>
		<td class='hback'><!--{$category.dirname}--> （不可更改）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>同级排序：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="orders" id="orders" class="input-100" value="<!--{$category.orders}-->"  /> <span class='f_red' id="dorders"></span> (数字越小，越靠前)</td>
	  </tr>
	  <tr>
		<td class='hback_1'>状态设置：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="flag" value="1"<!--{if $category.flag=='1'}--> checked<!--{/if}--> />正常，<input type="radio" name="flag" value="0"<!--{if $category.flag=='0'}--> checked<!--{/if}--> />锁定</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目图片：<span class='f_red'></span></td>
		<td class='hback'>
		  <table border="0" cellspacing="0" cellpadding="0">
		    <tr>
			  <td><input type="text" name="catpic" id="catpic" class="input-txt" value="<!--{$category.catpic}-->"  /> <span id='dcatpic' class='f_red'></span></td>
			  <td>
			  <iframe id='iframe_t' border='0' frameborder='0' scrolling='no' style="width:260px;height:25px;padding:0px;margin:0px;" src='<!--{$cpfile}-->?c=upload&formname=myform&module=article&uploadinput=catpic&multipart=sf_upload'></iframe>
			  </td>
			</tr>
		  </table>
	    </td>
	  </tr>
	  <tr>
		<td class='hback_yellow' colspan="2">SEO相关设置</td>
	  </tr>
	  <tr>
		<td class='hback_1'>Meta标题：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="metatitle" id="metatitle" class="input-txt" value="<!--{$category.metatitle}-->" /> <span class='f_red' id="dmetatitle"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>Meta关键字：<span class='f_red'></span></td>
		<td class='hback'><textarea name="metakeyword" id="metakeyword" style="width:40%;height:45px;overflow:auto;"><!--{$category.metakeyword}--></textarea> <span class='f_red' id="dmetakeyword"></span> （多个用英文逗号隔开）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>Meta描述：<span class='f_red'></span></td>
		<td class='hback'><textarea name="metadescription" id="metadescription" style="width:40%;height:45px;overflow:auto;"><!--{$category.metadescription}--></textarea> <span class='f_red' id="dmetadescription"></span></td>
	  </tr>
	  <tr>
		<td class='hback_yellow' colspan="2">显示及其他设置</td>
	  </tr>
	  <tr>
		<td class='hback_1'>主导航显示：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="ismenu" value="1"<!--{if $category.ismenu=='1'}--> checked<!--{/if}--> />显示，<input type="radio" name="ismenu" value="0"<!--{if $category.ismenu=='0'}--> checked<!--{/if}--> />关闭</td>
	  </tr>
	  <tr>
		<td class='hback_1'>附加导航显示：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="isaccessory" value="1"<!--{if $category.isaccessory=='1'}--> checked<!--{/if}--> />显示，<input type="radio" name="isaccessory" value="0"<!--{if $category.isaccessory=='0'}--> checked<!--{/if}--> />关闭</td>
	  </tr>
	  <tr>
		<td class='hback_1'>链接类型：<span class='f_red'></span></td>
		<td class='hback'>
		<input type="radio" name="linktype" value="1"<!--{if $category.linktype=='1'}--> checked<!--{/if}--> />内部链接，<input type="radio" name="linktype"  value="2"<!--{if $category.linktype=='2'}--> checked<!--{/if}--> />外部链接，外部链接URL：<input type="text" name="outurl" id="outurl" class="input-txt" value="<!--{$category.outurl}-->" />
		<br />
		（内部链接，请继续填写以下设置）
		</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目首页模板：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="tplindex" id="tplindex" class="input-150" value="<!--{$category.tplindex}-->" /> <a href="javascript:void(0);" onclick="tbox_templet('选择模板文件', 'tplindex');">选择模板</a> <span class='f_red' id="dtplindex"></span> （不需要填写风格路径和模板后缀，不填写则启用模块类型的默认模板）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目列表模板：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="tpllist" id="tpllist" class="input-150" value="<!--{$category.tpllist}-->" /> <a href="javascript:void(0);" onclick="tbox_templet('选择模板文件', 'tpllist');">选择模板</a> <span class='f_red' id="dtpllist"></span> （不需要填写风格路径和模板后缀，不填写则启用模块类型的默认模板）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目内容模板：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="tpldetail" id="tpldetail" class="input-150" value="<!--{$category.tpldetail}-->" /> <a href="javascript:void(0);" onclick="tbox_templet('选择模板文件', 'tpldetail');">选择模板</a> <span class='f_red' id="dtpldetail"></span> （不需要填写风格路径和模板后缀，不填写则启用模块类型的默认模板）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>每页显示数量：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="pagemax" id="pagemax" class="input-s" value="<!--{$category.pagemax}-->" /> <span class='f_red' id="dpagemax"></span> （不填写或者0，表示使用默认。）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>列表排序方式：<span class='f_red'></span></td>
		<td class='hback'>
		<input type="radio" name="orderby" value="0"<!--{if $category.orderby=='0'}--> checked<!--{/if}--> />默认，
		<input type="radio" name="orderby" value="1"<!--{if $category.orderby=='1'}--> checked<!--{/if}--> />更新时间，
		<input type="radio" name="orderby" value="2"<!--{if $category.orderby=='2'}--> checked<!--{/if}--> />发布时间，
		<input type="radio" name="orderby" value="3"<!--{if $category.orderby=='3'}--> checked<!--{/if}--> />点击次数，
		<input type="radio" name="orderby" value="4"<!--{if $category.orderby=='4'}--> checked<!--{/if}--> />ID倒序，
		<input type="radio" name="orderby" value="5"<!--{if $category.orderby=='5'}--> checked<!--{/if}--> />ID顺序
		</td>
	  </tr>
	  <tr>
		<td class='hback_none'></td>
		<td class='hback_none'><input type="submit" name="btn_save" class="button" value="编辑保存" /></td>
	  </tr>
	</table>
	</form>
  </div>
  <div style="clear:both;"></div>
</div>
<!--{/if}-->

<!--{if $a eq "add_about"}-->
<div class="main-wrap">
  <div class="path"><p>当前位置：模块管理<span>&gt;&gt;</span>栏目管理<span>&gt;&gt;</span><a href="<!--{$cpfile}-->?c=category&a=add_about">添加单页子栏目</a></p></div>
  <div class="main-cont">
	<h3 class="title"><a href="<!--{$cpfile}-->?c=category" class="btn-general"><span>返回列表</span></a>添加单页子栏目</h3>
    <form name="myform" id="myform" method="post" action="<!--{$cpfile}-->?c=category" onsubmit='return check_add_about();' />
    <input type="hidden" name="a" value="saveadd_about" />
	<table cellpadding='1' cellspacing='2' class='tab'>
	  <tr>
		<td class='hback_1' width="15%">模块类型：<span class='f_red'></span></td>
		<td class='hback' width="85%">单页模型</td>
	  </tr>
	  <tr>
		<td class='hback_1'>上级栏目：<span class='f_red'>*</span></td>
		<td class='hback'><!--{$root_select}--> <span class='f_red' id="drootid"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目名称：<span class='f_red'>*</span></td>
		<td class='hback'><input type="text" name="catname" id="catname" class="input-txt" /> <span class='f_red' id="dcatname"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目子名称：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="asname" id="asname" class="input-txt" /> <span class='f_red' id="dasname"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>目录标识：<span class='f_red'>*</span></td>
		<td class='hback'><input type="text" name="dirname" id="dirname" class="input-100" onblur="ajax_checkcatalog('dirname', 'tips_dirname');" /> <span class='f_red' id="ddirname"></span>
		<span id="tips_dirname"></span>

		<br /> 只能由字母，数字，下横线，中横线组成；<br />不能跟其他栏目的标识相同，一旦填写不能修改。</td>
	  </tr>
	  <tr>
		<td class='hback_1'>同级排序：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="orders" id="orders" class="input-100"  /> <span class='f_red' id="dorders"></span> (数字越小，越靠前)</td>
	  </tr>
	  <tr>
		<td class='hback_1'>状态设置：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="flag" value="1" checked />正常，<input type="radio" name="flag" value="0" />锁定</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目图片：<span class='f_red'></span></td>
		<td class='hback'>
		  <table border="0" cellspacing="0" cellpadding="0">
		    <tr>
			  <td><input type="text" name="catpic" id="catpic" class="input-txt" /> <span id='dcatpic' class='f_red'></span></td>
			  <td>
			  <iframe id='iframe_t' border='0' frameborder='0' scrolling='no' style="width:260px;height:25px;padding:0px;margin:0px;" src='<!--{$cpfile}-->?c=upload&formname=myform&module=article&uploadinput=catpic&multipart=sf_upload'></iframe>
			  </td>
			</tr>
		  </table>
	    </td>
	  </tr>
	  <tr>
		<td class='hback_yellow' colspan="2">显示及其他设置</td>
	  </tr>
	  <tr>
		<td class='hback_1'>主导航显示：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="ismenu" value="1" checked />显示，<input type="radio" name="ismenu" value="0" />关闭</td>
	  </tr>
	  <tr>
		<td class='hback_1'>附加导航显示：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="isaccessory" value="1" />显示，<input type="radio" name="isaccessory" value="0" checked />关闭</td>
	  </tr>
	  <tr>
		<td class='hback_1'>链接类型：<span class='f_red'></span></td>
		<td class='hback'>
		<input type="radio" name="linktype" value="1" checked />内部链接，<input type="radio" name="linktype" id="linktype" value="2" />外部链接，外部链接URL：<input type="text" name="outurl" id="outurl" class="input-txt" />
		<br />
		（内部链接，请继续填写以下设置）
		</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目内容模板：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="tpldetail" id="tpldetail" class="input-150" /> <a href="javascript:void(0);" onclick="tbox_templet('选择模板文件', 'tpldetail');">选择模板</a> <span class='f_red' id="dtpldetail"></span> （不需要填写风格路径和模板后缀，不填写则启用模块类型的默认模板）</td>
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

<!--{if $a eq "edit_about"}-->
<div class="main-wrap">
  <div class="path"><p>当前位置：模块管理<span>&gt;&gt;</span>栏目管理<span>&gt;&gt;</span>编辑单页子栏目</p></div>
  <div class="main-cont">
	<h3 class="title"><a href="<!--{$cpfile}-->?c=category" class="btn-general"><span>返回列表</span></a>编辑单页子栏目</h3>
    <form name="myform" id="myform" method="post" action="<!--{$cpfile}-->?c=category" onsubmit='return check_edit_about();' />
    <input type="hidden" name="a" value="saveedit_about" />
	<input type="hidden" name="id" value="<!--{$id}-->" />
	<table cellpadding='1' cellspacing='2' class='tab'>
	  <tr>
		<td class='hback_1' width="15%">模块类型：<span class='f_red'></span></td>
		<td class='hback' width="85%"><!--{$category.modname}--> （不可更改）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>上级栏目：<span class='f_red'>*</span></td>
		<td class='hback'><!--{$root_select}--> <span class='f_red' id="drootid"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目名称：<span class='f_red'>*</span></td>
		<td class='hback'><input type="text" name="catname" id="catname" class="input-txt" value="<!--{$category.catname}-->" /> <span class='f_red' id="dcatname"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目子名称：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="asname" id="asname" class="input-txt" value="<!--{$category.asname}-->" /> <span class='f_red' id="dasname"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>目录标识：<span class='f_red'></span></td>
		<td class='hback'><!--{$category.dirname}--> （不可更改）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>同级排序：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="orders" id="orders" class="input-100" value="<!--{$category.orders}-->"  /> <span class='f_red' id="dorders"></span> (数字越小，越靠前)</td>
	  </tr>
	  <tr>
		<td class='hback_1'>状态设置：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="flag" value="1"<!--{if $category.flag=='1'}--> checked<!--{/if}--> />正常，<input type="radio" name="flag" value="0"<!--{if $category.flag=='0'}--> checked<!--{/if}--> />锁定</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目图片：<span class='f_red'></span></td>
		<td class='hback'>
		  <table border="0" cellspacing="0" cellpadding="0">
		    <tr>
			  <td><input type="text" name="catpic" id="catpic" class="input-txt" value="<!--{$category.catpic}-->"  /> <span id='dcatpic' class='f_red'></span></td>
			  <td>
			  <iframe id='iframe_t' border='0' frameborder='0' scrolling='no' style="width:260px;height:25px;padding:0px;margin:0px;" src='<!--{$cpfile}-->?c=upload&formname=myform&module=article&uploadinput=catpic&multipart=sf_upload'></iframe>
			  </td>
			</tr>
		  </table>
	    </td>
	  </tr>
	  <tr>
		<td class='hback_yellow' colspan="2">显示及其他设置</td>
	  </tr>
	  <tr>
		<td class='hback_1'>主导航显示：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="ismenu" value="1"<!--{if $category.ismenu=='1'}--> checked<!--{/if}--> />显示，<input type="radio" name="ismenu" value="0"<!--{if $category.ismenu=='0'}--> checked<!--{/if}--> />关闭</td>
	  </tr>
	  <tr>
		<td class='hback_1'>附加导航显示：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="isaccessory" value="1"<!--{if $category.isaccessory=='1'}--> checked<!--{/if}--> />显示，<input type="radio" name="isaccessory" value="0"<!--{if $category.isaccessory=='0'}--> checked<!--{/if}--> />关闭</td>
	  </tr>
	  <tr>
		<td class='hback_1'>链接类型：<span class='f_red'></span></td>
		<td class='hback'>
		<input type="radio" name="linktype" value="1"<!--{if $category.linktype=='1'}--> checked<!--{/if}--> />内部链接，<input type="radio" name="linktype"  value="2"<!--{if $category.linktype=='2'}--> checked<!--{/if}--> />外部链接，外部链接URL：<input type="text" name="outurl" id="outurl" class="input-txt" value="<!--{$category.outurl}-->" />
		<br />
		（内部链接，请继续填写以下设置）
		</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目内容模板：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="tpldetail" id="tpldetail" class="input-150" value="<!--{$category.tpldetail}-->" /> <a href="javascript:void(0);" onclick="tbox_templet('选择模板文件', 'tpldetail');">选择模板</a> <span class='f_red' id="dtpldetail"></span> （不需要填写风格路径和模板后缀，不填写则启用模块类型的默认模板）</td>
	  </tr>
	  <tr>
		<td class='hback_none'></td>
		<td class='hback_none'><input type="submit" name="btn_save" class="button" value="编辑保存" /></td>
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
//一级列表栏目
function check_add_nodelist() {
	var t = "";
	var v = "";

	t = "modalias";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("请选择模块类型", t);
		$("#"+t).focus();
		return false;
	}

	t = "catname";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("栏目名称不能为空", t);
		$("#"+t).focus();
		return false;
	}

	t = "dirname";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("目录标识不能为空", t);
		$("#"+t).focus();
		return false;
	}

	return true;
}
function check_edit_nodelist() {
	var t = "";
	var v = "";

	t = "catname";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("栏目名称不能为空", t);
		$("#"+t).focus();
		return false;
	}
	return true;
}

//一级单页栏目
function check_add_nodeabout() {
	var t = "";
	var v = "";

	t = "catname";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("栏目名称不能为空", t);
		$("#"+t).focus();
		return false;
	}

	t = "dirname";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("目录标识不能为空", t);
		$("#"+t).focus();
		return false;
	}

	return true;
}
function check_edit_nodeabout() {
	var t = "";
	var v = "";

	t = "catname";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("栏目名称不能为空", t);
		$("#"+t).focus();
		return false;
	}

	return true;
}

//一级外部栏目
function check_add_nodeoutside() {
	var t = "";
	var v = "";

	t = "catname";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("栏目名称不能为空", t);
		$("#"+t).focus();
		return false;
	}

	t = "outurl";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("外部链接URL不能为空", t);
		$("#"+t).focus();
		return false;
	}

	return true;
}
function check_edit_nodeoutside() {
	var t = "";
	var v = "";

	t = "catname";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("栏目名称不能为空", t);
		$("#"+t).focus();
		return false;
	}

	t = "outurl";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("外部链接URL不能为空", t);
		$("#"+t).focus();
		return false;
	}

	return true;
}

//列表子栏目
function check_add_list() {
	var t = "";
	var v = "";

	t = "rootid";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("请选择上级栏目", t);
		$("#"+t).focus();
		return false;
	}

	t = "catname";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("栏目名称不能为空", t);
		$("#"+t).focus();
		return false;
	}

	t = "dirname";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("目录标识不能为空", t);
		$("#"+t).focus();
		return false;
	}

	return true;
}
function check_edit_list() {
	var t = "";
	var v = "";

	t = "rootid";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("请选择上级栏目", t);
		$("#"+t).focus();
		return false;
	}

	t = "catname";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("栏目名称不能为空", t);
		$("#"+t).focus();
		return false;
	}

	return true;
}

//单页子栏目
function check_add_about() {
	var t = "";
	var v = "";

	t = "rootid";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("请选择上级栏目", t);
		$("#"+t).focus();
		return false;
	}

	t = "catname";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("栏目名称不能为空", t);
		$("#"+t).focus();
		return false;
	}

	t = "dirname";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("目录标识不能为空", t);
		$("#"+t).focus();
		return false;
	}

	return true;
}
function check_edit_about() {
	var t = "";
	var v = "";

	t = "rootid";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("请选择上级栏目", t);
		$("#"+t).focus();
		return false;
	}

	t = "catname";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("栏目名称不能为空", t);
		$("#"+t).focus();
		return false;
	}

	return true;
}
</script>
