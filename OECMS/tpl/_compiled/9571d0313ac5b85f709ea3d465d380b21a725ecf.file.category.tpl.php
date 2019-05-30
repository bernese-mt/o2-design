<?php /* Smarty version Smarty-3.0.5, created on 2019-05-07 15:57:41
         compiled from "/home/o2design/public_html/OECMS/tpl/admincp/category.tpl" */ ?>
<?php /*%%SmartyHeaderCode:18202260305cd13a75c42945-81887038%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9571d0313ac5b85f709ea3d465d380b21a725ecf' => 
    array (
      0 => '/home/o2design/public_html/OECMS/tpl/admincp/category.tpl',
      1 => 1557211180,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18202260305cd13a75c42945-81887038',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $_smarty_tpl->getVariable('page_charset')->value;?>
" />
<title>栏目设置</title>
<?php $_template = new Smarty_Internal_Template(($_smarty_tpl->getVariable('cptplpath')->value)."headerjs.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>
<?php $_smarty_tpl->tpl_vars['pluginevent'] = new Smarty_variable(XHook::doAction('adm_head'), null, null);?>
</head>
<body>
<?php $_smarty_tpl->tpl_vars['pluginevent'] = new Smarty_variable(XHook::doAction('adm_main_top'), null, null);?>
<?php if ($_smarty_tpl->getVariable('a')->value=="run"){?>
<div class="main-wrap">
  <div class="path"><p>当前位置：模块管理<span>&gt;&gt;</span>栏目管理<span>&gt;&gt;</span><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category">栏目设置</a></p></div>
  <div class="main-cont">
    <h3 class="title">
	<a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category&a=add_nodeoutside" class="btn-general"><span>添加一级外部栏目</span></a>
	<a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category&a=add_nodeabout" class="btn-general"><span>添加一级单页栏目</span></a>
	<a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category&a=add_nodelist" class="btn-general"><span>添加一级列表栏目</span></a>
	
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
	  <?php  $_smarty_tpl->tpl_vars['volist'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('category')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['volist']->key => $_smarty_tpl->tpl_vars['volist']->value){
?>
	  <tr onMouseOver="overColor(this)" onMouseOut="outColor(this)">
	    <td align="center"><?php echo $_smarty_tpl->tpl_vars['volist']->value['catid'];?>
</td>
		<td align="center"><?php echo $_smarty_tpl->tpl_vars['volist']->value['orders'];?>
</td>
		<td align="center"><font color="<?php echo $_smarty_tpl->tpl_vars['volist']->value['modcolor'];?>
"><?php echo $_smarty_tpl->tpl_vars['volist']->value['modname'];?>
</font></td>
		<td>
		<?php if (!empty($_smarty_tpl->tpl_vars['volist']->value['dirname'])){?>
		<?php if ($_smarty_tpl->tpl_vars['volist']->value['linktype']==1){?>
		<a href="<?php echo $_smarty_tpl->tpl_vars['volist']->value['url'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['volist']->value['dirname'];?>
</a>
		<?php }else{ ?>
		<a href="<?php echo $_smarty_tpl->tpl_vars['volist']->value['outurl'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['volist']->value['dirname'];?>
</a>
		<?php }?>
		<?php }?>
		</td>
		<td>
		<?php if ($_smarty_tpl->tpl_vars['volist']->value['depth']==0){?>
		<b><?php echo $_smarty_tpl->tpl_vars['volist']->value['tree_node'];?>
</b>
		<?php }else{ ?>
		<?php echo $_smarty_tpl->tpl_vars['volist']->value['tree_node'];?>

		<?php }?>
		&nbsp;&nbsp;
		</td>
		<td align="center">
		<?php if ($_smarty_tpl->tpl_vars['volist']->value['flag']==0){?>
			<input type="hidden" id="attr_flag<?php echo $_smarty_tpl->tpl_vars['volist']->value['catid'];?>
" value="flagopen" />
			<img id="flag<?php echo $_smarty_tpl->tpl_vars['volist']->value['catid'];?>
" src="<?php echo $_smarty_tpl->getVariable('urlpath')->value;?>
tpl/static/images/no.gif" onClick="javascript:fetch_ajax('flag','<?php echo $_smarty_tpl->tpl_vars['volist']->value['catid'];?>
');" style="cursor:pointer;">
		<?php }else{ ?>
			<input type="hidden" id="attr_flag<?php echo $_smarty_tpl->tpl_vars['volist']->value['catid'];?>
" value="flagclose" />
			<img id="flag<?php echo $_smarty_tpl->tpl_vars['volist']->value['catid'];?>
" src="<?php echo $_smarty_tpl->getVariable('urlpath')->value;?>
tpl/static/images/yes.gif" onClick="javascript:fetch_ajax('flag','<?php echo $_smarty_tpl->tpl_vars['volist']->value['catid'];?>
');" style="cursor:pointer;">	
		<?php }?>
		</td>
		<td align="center">
		<?php if ($_smarty_tpl->tpl_vars['volist']->value['ismenu']==0){?>
			<input type="hidden" id="attr_ismenu<?php echo $_smarty_tpl->tpl_vars['volist']->value['catid'];?>
" value="ismenuopen" />
			<img id="ismenu<?php echo $_smarty_tpl->tpl_vars['volist']->value['catid'];?>
" src="<?php echo $_smarty_tpl->getVariable('urlpath')->value;?>
tpl/static/images/no.gif" onClick="javascript:fetch_ajax('ismenu','<?php echo $_smarty_tpl->tpl_vars['volist']->value['catid'];?>
');" style="cursor:pointer;">
		<?php }else{ ?>
			<input type="hidden" id="attr_ismenu<?php echo $_smarty_tpl->tpl_vars['volist']->value['catid'];?>
" value="ismenuclose" />
			<img id="ismenu<?php echo $_smarty_tpl->tpl_vars['volist']->value['catid'];?>
" src="<?php echo $_smarty_tpl->getVariable('urlpath')->value;?>
tpl/static/images/yes.gif" onClick="javascript:fetch_ajax('ismenu','<?php echo $_smarty_tpl->tpl_vars['volist']->value['catid'];?>
');" style="cursor:pointer;">	
		<?php }?>
		</td>
		<td align="center">
		<?php if ($_smarty_tpl->tpl_vars['volist']->value['isaccessory']==0){?>
			<input type="hidden" id="attr_isaccessory<?php echo $_smarty_tpl->tpl_vars['volist']->value['catid'];?>
" value="isaccessoryopen" />
			<img id="isaccessory<?php echo $_smarty_tpl->tpl_vars['volist']->value['catid'];?>
" src="<?php echo $_smarty_tpl->getVariable('urlpath')->value;?>
tpl/static/images/no.gif" onClick="javascript:fetch_ajax('isaccessory','<?php echo $_smarty_tpl->tpl_vars['volist']->value['catid'];?>
');" style="cursor:pointer;">
		<?php }else{ ?>
			<input type="hidden" id="attr_isaccessory<?php echo $_smarty_tpl->tpl_vars['volist']->value['catid'];?>
" value="isaccessoryclose" />
			<img id="isaccessory<?php echo $_smarty_tpl->tpl_vars['volist']->value['catid'];?>
" src="<?php echo $_smarty_tpl->getVariable('urlpath')->value;?>
tpl/static/images/yes.gif" onClick="javascript:fetch_ajax('isaccessory','<?php echo $_smarty_tpl->tpl_vars['volist']->value['catid'];?>
');" style="cursor:pointer;">	
		<?php }?>
		</td>
		
		<td align="center">
		<!-- 添加子栏目 -->
		<?php if ($_smarty_tpl->tpl_vars['volist']->value['modalias']=='about'){?>
			<?php if ($_smarty_tpl->tpl_vars['volist']->value['depth']==0){?>
			<a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category&a=add_about&rootid=<?php echo $_smarty_tpl->tpl_vars['volist']->value['catid'];?>
" class="icon-show">添加二级栏目</a>
			<?php }?>
		<?php }elseif($_smarty_tpl->tpl_vars['volist']->value['modalias']=='outside'){?>
		<?php }else{ ?>
			<?php if ($_smarty_tpl->tpl_vars['volist']->value['depth']<4){?>
			<a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category&a=add_list&rootid=<?php echo $_smarty_tpl->tpl_vars['volist']->value['catid'];?>
" class="icon-add">添加<?php echo $_smarty_tpl->tpl_vars['volist']->value['depth']+2;?>
级栏目</a>
			<?php }?>
		<?php }?>
        &nbsp;
		<!-- 修改 -->
		<?php if ($_smarty_tpl->tpl_vars['volist']->value['rootid']=='0'){?>
			<?php if ($_smarty_tpl->tpl_vars['volist']->value['modalias']=='about'){?>
			<a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category&a=edit_nodeabout&id=<?php echo $_smarty_tpl->tpl_vars['volist']->value['catid'];?>
" class="icon-set">设置</a>
			<?php }elseif($_smarty_tpl->tpl_vars['volist']->value['modalias']=='outside'){?>
			<a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category&a=edit_nodeoutside&id=<?php echo $_smarty_tpl->tpl_vars['volist']->value['catid'];?>
" class="icon-edit">设置</a>
			<?php }else{ ?>
			<a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category&a=edit_nodelist&id=<?php echo $_smarty_tpl->tpl_vars['volist']->value['catid'];?>
" class="icon-set">设置</a>
			<?php }?>
		<?php }else{ ?>
			<?php if ($_smarty_tpl->tpl_vars['volist']->value['modalias']=='about'){?>
			<a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category&a=edit_about&id=<?php echo $_smarty_tpl->tpl_vars['volist']->value['catid'];?>
" class="icon-set">设置</a>
			<?php }elseif($_smarty_tpl->tpl_vars['volist']->value['modalias']=='outside'){?>
			<?php }else{ ?>
			<a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category&a=edit_list&id=<?php echo $_smarty_tpl->tpl_vars['volist']->value['catid'];?>
" class="icon-edit">修改</a>
			<?php }?>
		<?php }?>
		&nbsp;
		<?php if ($_smarty_tpl->tpl_vars['volist']->value['system']==0){?>
		<a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category&a=del&id=<?php echo $_smarty_tpl->tpl_vars['volist']->value['catid'];?>
" onClick="{if(confirm('确定要删除吗？一旦删除无法恢复！')){return true;} return false;}" class="icon-del">删除</a>
		<?php }?>
		</td>
	  </tr>
	  <?php }} else { ?>
      <tr>
	    <td colspan="9" align="center">暂无信息</td>
	  </tr>
	  <?php } ?>
	</table>
	<?php if ($_smarty_tpl->getVariable('total')->value>1){?>
	<table width='95%' border='0' cellspacing='0' cellpadding='0' align='center' style="margin-top:10px;">
	  <tr>
		<td align='left'>共有栏目：<?php echo $_smarty_tpl->getVariable('total')->value;?>
 个</td>
	  </tr>
	</table>
	<?php }?>
  </div>
</div>
<?php }?>

<?php if ($_smarty_tpl->getVariable('a')->value=="add_nodelist"){?>
<div class="main-wrap">
  <div class="path"><p>当前位置：模块管理<span>&gt;&gt;</span>栏目管理<span>&gt;&gt;</span><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category&a=add_nodelist">添加一级列表栏目</a></p></div>
  <div class="main-cont">
	<h3 class="title"><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category" class="btn-general"><span>返回列表</span></a>添加一级列表栏目</h3>
    <form name="myform" id="myform" method="post" action="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category" onsubmit='return check_add_nodelist();' />
    <input type="hidden" name="a" value="saveadd_nodelist" />
	<table cellpadding='1' cellspacing='2' class='tab'>
	  <tr>
		<td class='hback_1' width="15%">模块类型：<span class='f_red'>*</span></td>
		<td class='hback' width="85%"><?php echo $_smarty_tpl->getVariable('module_select')->value;?>
 <span class='f_red' id="dmodalias"></span> （一旦选择不能修改）</td>
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
			  <iframe id='iframe_t' border='0' frameborder='0' scrolling='no' style="width:260px;height:25px;padding:0px;margin:0px;" src='<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=upload&formname=myform&module=article&uploadinput=catpic&multipart=sf_upload'></iframe>
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
<?php }?>

<?php if ($_smarty_tpl->getVariable('a')->value=="edit_nodelist"){?>
<div class="main-wrap">
  <div class="path"><p>当前位置：模块管理<span>&gt;&gt;</span>栏目管理<span>&gt;&gt;</span>编辑一级列表栏目</p></div>
  <div class="main-cont">
	<h3 class="title"><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category" class="btn-general"><span>返回列表</span></a>编辑一级列表栏目</h3>
    <form name="myform" id="myform" method="post" action="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category" onsubmit='return check_edit_nodelist();' />
    <input type="hidden" name="a" value="saveedit_nodelist" />
	<input type="hidden" name="id" value="<?php echo $_smarty_tpl->getVariable('id')->value;?>
" />
	<table cellpadding='1' cellspacing='2' class='tab'>
	  <tr>
		<td class='hback_1' width="15%">模块类型：<span class='f_red'></span></td>
		<td class='hback' width="85%"><?php echo $_smarty_tpl->getVariable('category')->value['modname'];?>
 （不可更改）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目名称：<span class='f_red'>*</span></td>
		<td class='hback'><input type="text" name="catname" id="catname" class="input-txt" value="<?php echo $_smarty_tpl->getVariable('category')->value['catname'];?>
" /> <span class='f_red' id="dcatname"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目子名称：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="asname" id="asname" class="input-txt" value="<?php echo $_smarty_tpl->getVariable('category')->value['asname'];?>
" /> <span class='f_red' id="dasname"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>目录标识：<span class='f_red'></span></td>
		<td class='hback'><?php echo $_smarty_tpl->getVariable('category')->value['dirname'];?>
 （不可更改）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>同级排序：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="orders" id="orders" class="input-100" value="<?php echo $_smarty_tpl->getVariable('category')->value['orders'];?>
"  /> <span class='f_red' id="dorders"></span> (数字越小，越靠前)</td>
	  </tr>
	  <tr>
		<td class='hback_1'>状态设置：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="flag" value="1"<?php if ($_smarty_tpl->getVariable('category')->value['flag']=='1'){?> checked<?php }?> />正常，<input type="radio" name="flag" value="0"<?php if ($_smarty_tpl->getVariable('category')->value['flag']=='0'){?> checked<?php }?> />锁定</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目图片：<span class='f_red'></span></td>
		<td class='hback'>
		  <table border="0" cellspacing="0" cellpadding="0">
		    <tr>
			  <td><input type="text" name="catpic" id="catpic" class="input-txt" value="<?php echo $_smarty_tpl->getVariable('category')->value['catpic'];?>
"  /> <span id='dcatpic' class='f_red'></span></td>
			  <td>
			  <iframe id='iframe_t' border='0' frameborder='0' scrolling='no' style="width:260px;height:25px;padding:0px;margin:0px;" src='<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=upload&formname=myform&module=article&uploadinput=catpic&multipart=sf_upload'></iframe>
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
		<td class='hback'><input type="text" name="metatitle" id="metatitle" class="input-txt" value="<?php echo $_smarty_tpl->getVariable('category')->value['metatitle'];?>
" /> <span class='f_red' id="dmetatitle"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>Meta关键字：<span class='f_red'></span></td>
		<td class='hback'><textarea name="metakeyword" id="metakeyword" style="width:40%;height:45px;overflow:auto;"><?php echo $_smarty_tpl->getVariable('category')->value['metakeyword'];?>
</textarea> <span class='f_red' id="dmetakeyword"></span> （多个用英文逗号隔开）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>Meta描述：<span class='f_red'></span></td>
		<td class='hback'><textarea name="metadescription" id="metadescription" style="width:40%;height:45px;overflow:auto;"><?php echo $_smarty_tpl->getVariable('category')->value['metadescription'];?>
</textarea> <span class='f_red' id="dmetadescription"></span></td>
	  </tr>
	  <tr>
		<td class='hback_yellow' colspan="2">显示及其他设置</td>
	  </tr>
	  <tr>
		<td class='hback_1'>主导航显示：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="ismenu" value="1"<?php if ($_smarty_tpl->getVariable('category')->value['ismenu']=='1'){?> checked<?php }?> />显示，<input type="radio" name="ismenu" value="0"<?php if ($_smarty_tpl->getVariable('category')->value['ismenu']=='0'){?> checked<?php }?> />关闭</td>
	  </tr>
	  <tr>
		<td class='hback_1'>附加导航显示：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="isaccessory" value="1"<?php if ($_smarty_tpl->getVariable('category')->value['isaccessory']=='1'){?> checked<?php }?> />显示，<input type="radio" name="isaccessory" value="0"<?php if ($_smarty_tpl->getVariable('category')->value['isaccessory']=='0'){?> checked<?php }?> />关闭</td>
	  </tr>
	  <tr>
		<td class='hback_1'>链接类型：<span class='f_red'></span></td>
		<td class='hback'>
		<input type="radio" name="linktype" value="1"<?php if ($_smarty_tpl->getVariable('category')->value['linktype']=='1'){?> checked<?php }?> />内部链接，<input type="radio" name="linktype"  value="2"<?php if ($_smarty_tpl->getVariable('category')->value['linktype']=='2'){?> checked<?php }?> />外部链接，外部链接URL：<input type="text" name="outurl" id="outurl" class="input-txt" value="<?php echo $_smarty_tpl->getVariable('category')->value['outurl'];?>
" />
		<br />
		（内部链接，请继续填写以下设置）
		</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目首页模板：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="tplindex" id="tplindex" class="input-150" value="<?php echo $_smarty_tpl->getVariable('category')->value['tplindex'];?>
" /> <a href="javascript:void(0);" onclick="tbox_templet('选择模板文件', 'tplindex');">选择模板</a> <span class='f_red' id="dtplindex"></span> （不需要填写风格路径和模板后缀，不填写则启用模块类型的默认模板）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目列表模板：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="tpllist" id="tpllist" class="input-150" value="<?php echo $_smarty_tpl->getVariable('category')->value['tpllist'];?>
" /> <a href="javascript:void(0);" onclick="tbox_templet('选择模板文件', 'tpllist');">选择模板</a> <span class='f_red' id="dtpllist"></span> （不需要填写风格路径和模板后缀，不填写则启用模块类型的默认模板）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目内容模板：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="tpldetail" id="tpldetail" class="input-150" value="<?php echo $_smarty_tpl->getVariable('category')->value['tpldetail'];?>
" /> <a href="javascript:void(0);" onclick="tbox_templet('选择模板文件', 'tpldetail');">选择模板</a> <span class='f_red' id="dtpldetail"></span> （不需要填写风格路径和模板后缀，不填写则启用模块类型的默认模板）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>每页显示数量：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="pagemax" id="pagemax" class="input-s" value="<?php echo $_smarty_tpl->getVariable('category')->value['pagemax'];?>
" /> <span class='f_red' id="dpagemax"></span> （不填写或者0，表示使用默认。）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>列表排序方式：<span class='f_red'></span></td>
		<td class='hback'>
		<input type="radio" name="orderby" value="0"<?php if ($_smarty_tpl->getVariable('category')->value['orderby']=='0'){?> checked<?php }?> />默认，
		<input type="radio" name="orderby" value="1"<?php if ($_smarty_tpl->getVariable('category')->value['orderby']=='1'){?> checked<?php }?> />更新时间，
		<input type="radio" name="orderby" value="2"<?php if ($_smarty_tpl->getVariable('category')->value['orderby']=='2'){?> checked<?php }?> />发布时间，
		<input type="radio" name="orderby" value="3"<?php if ($_smarty_tpl->getVariable('category')->value['orderby']=='3'){?> checked<?php }?> />点击次数，
		<input type="radio" name="orderby" value="4"<?php if ($_smarty_tpl->getVariable('category')->value['orderby']=='4'){?> checked<?php }?> />ID倒序，
		<input type="radio" name="orderby" value="5"<?php if ($_smarty_tpl->getVariable('category')->value['orderby']=='5'){?> checked<?php }?> />ID顺序
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
<?php }?>


<?php if ($_smarty_tpl->getVariable('a')->value=="add_nodeabout"){?>
<div class="main-wrap">
  <div class="path"><p>当前位置：模块管理<span>&gt;&gt;</span>栏目管理<span>&gt;&gt;</span><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category&a=add_nodeabout">添加一级单页栏目</a></p></div>
  <div class="main-cont">
	<h3 class="title"><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category" class="btn-general"><span>返回列表</span></a>添加一级单页栏目</h3>
    <form name="myform" id="myform" method="post" action="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category" onsubmit='return check_add_nodeabout();' />
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
			  <iframe id='iframe_t' border='0' frameborder='0' scrolling='no' style="width:260px;height:25px;padding:0px;margin:0px;" src='<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=upload&formname=myform&module=article&uploadinput=catpic&multipart=sf_upload'></iframe>
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
<?php }?>

<?php if ($_smarty_tpl->getVariable('a')->value=="edit_nodeabout"){?>
<div class="main-wrap">
  <div class="path"><p>当前位置：模块管理<span>&gt;&gt;</span>栏目管理<span>&gt;&gt;</span>编辑一级单页栏目</p></div>
  <div class="main-cont">
	<h3 class="title"><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category" class="btn-general"><span>返回列表</span></a>编辑一级单页栏目</h3>
    <form name="myform" id="myform" method="post" action="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category" onsubmit='return check_edit_nodeabout();' />
    <input type="hidden" name="a" value="saveedit_nodeabout" />
	<input type="hidden" name="id" value="<?php echo $_smarty_tpl->getVariable('id')->value;?>
" />
	<table cellpadding='1' cellspacing='2' class='tab'>
	  <tr>
		<td class='hback_1' width="15%">模块类型：<span class='f_red'></span></td>
		<td class='hback' width="85%"><?php echo $_smarty_tpl->getVariable('category')->value['modname'];?>
 （不可更改）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目名称：<span class='f_red'>*</span></td>
		<td class='hback'><input type="text" name="catname" id="catname" class="input-txt" value="<?php echo $_smarty_tpl->getVariable('category')->value['catname'];?>
" /> <span class='f_red' id="dcatname"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目子名称：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="asname" id="asname" class="input-txt" value="<?php echo $_smarty_tpl->getVariable('category')->value['asname'];?>
" /> <span class='f_red' id="dasname"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>目录标识：<span class='f_red'></span></td>
		<td class='hback'><?php echo $_smarty_tpl->getVariable('category')->value['dirname'];?>
 （不可更改）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>同级排序：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="orders" id="orders" class="input-100" value="<?php echo $_smarty_tpl->getVariable('category')->value['orders'];?>
"  /> <span class='f_red' id="dorders"></span> (数字越小，越靠前)</td>
	  </tr>
	  <tr>
		<td class='hback_1'>状态设置：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="flag" value="1"<?php if ($_smarty_tpl->getVariable('category')->value['flag']=='1'){?> checked<?php }?> />正常，<input type="radio" name="flag" value="0"<?php if ($_smarty_tpl->getVariable('category')->value['flag']=='0'){?> checked<?php }?> />锁定</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目图片：<span class='f_red'></span></td>
		<td class='hback'>
		  <table border="0" cellspacing="0" cellpadding="0">
		    <tr>
			  <td><input type="text" name="catpic" id="catpic" class="input-txt" value="<?php echo $_smarty_tpl->getVariable('category')->value['catpic'];?>
"  /> <span id='dcatpic' class='f_red'></span></td>
			  <td>
			  <iframe id='iframe_t' border='0' frameborder='0' scrolling='no' style="width:260px;height:25px;padding:0px;margin:0px;" src='<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=upload&formname=myform&module=article&uploadinput=catpic&multipart=sf_upload'></iframe>
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
		<td class='hback'><input type="radio" name="ismenu" value="1"<?php if ($_smarty_tpl->getVariable('category')->value['ismenu']=='1'){?> checked<?php }?> />显示，<input type="radio" name="ismenu" value="0"<?php if ($_smarty_tpl->getVariable('category')->value['ismenu']=='0'){?> checked<?php }?> />关闭</td>
	  </tr>
	  <tr>
		<td class='hback_1'>附加导航显示：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="isaccessory" value="1"<?php if ($_smarty_tpl->getVariable('category')->value['isaccessory']=='1'){?> checked<?php }?> />显示，<input type="radio" name="isaccessory" value="0"<?php if ($_smarty_tpl->getVariable('category')->value['isaccessory']=='0'){?> checked<?php }?> />关闭</td>
	  </tr>
	  <tr>
		<td class='hback_1'>链接类型：<span class='f_red'></span></td>
		<td class='hback'>
		<input type="radio" name="linktype" value="1"<?php if ($_smarty_tpl->getVariable('category')->value['linktype']=='1'){?> checked<?php }?> />内部链接，<input type="radio" name="linktype"  value="2"<?php if ($_smarty_tpl->getVariable('category')->value['linktype']=='2'){?> checked<?php }?> />外部链接，外部链接URL：<input type="text" name="outurl" id="outurl" class="input-txt" value="<?php echo $_smarty_tpl->getVariable('category')->value['outurl'];?>
" />
		<br />
		（内部链接，请继续填写以下设置）
		</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目内容模板：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="tpldetail" id="tpldetail" class="input-150" value="<?php echo $_smarty_tpl->getVariable('category')->value['tpldetail'];?>
" /> <a href="javascript:void(0);" onclick="tbox_templet('选择模板文件', 'tpldetail');">选择模板</a> <span class='f_red' id="dtpldetail"></span> （不需要填写风格路径和模板后缀，不填写则启用模块类型的默认模板）</td>
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
<?php }?>


<?php if ($_smarty_tpl->getVariable('a')->value=="add_nodeoutside"){?>
<div class="main-wrap">
  <div class="path"><p>当前位置：模块管理<span>&gt;&gt;</span>栏目管理<span>&gt;&gt;</span><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category&a=add_nodeoutside">添加一级外部栏目</a></p></div>
  <div class="main-cont">
	<h3 class="title"><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category" class="btn-general"><span>返回列表</span></a>添加一级外部栏目</h3>
    <form name="myform" id="myform" method="post" action="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category" onsubmit='return check_add_nodeoutside();' />
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
<?php }?>
<?php if ($_smarty_tpl->getVariable('a')->value=="edit_nodeoutside"){?>
<div class="main-wrap">
  <div class="path"><p>当前位置：模块管理<span>&gt;&gt;</span>栏目管理<span>&gt;&gt;</span>编辑一级外部栏目</p></div>
  <div class="main-cont">
	<h3 class="title"><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category" class="btn-general"><span>返回列表</span></a>编辑一级外部栏目</h3>
    <form name="myform" id="myform" method="post" action="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category" onsubmit='return check_edit_nodeoutside();' />
    <input type="hidden" name="a" value="saveedit_nodeoutside" />
	<input type="hidden" name="id" value="<?php echo $_smarty_tpl->getVariable('id')->value;?>
" />
	<table cellpadding='1' cellspacing='2' class='tab'>
	  <tr>
		<td class='hback_1' width="15%">模块类型：<span class='f_red'></span></td>
		<td class='hback' width="85%"><?php echo $_smarty_tpl->getVariable('category')->value['modname'];?>
 （不可更改）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目名称：<span class='f_red'>*</span></td>
		<td class='hback'><input type="text" name="catname" id="catname" class="input-txt" value="<?php echo $_smarty_tpl->getVariable('category')->value['catname'];?>
" /> <span class='f_red' id="dcatname"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>外部链接URL：<span class='f_red'>*</span></td>
		<td class='hback'><input type="text" name="outurl" id="outurl" class="input-txt" value="<?php echo $_smarty_tpl->getVariable('category')->value['outurl'];?>
" /> <span class='f_red' id="douturl"></span>
		</td>
	  </tr>
	  <tr>
		<td class='hback_1'>同级排序：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="orders" id="orders" class="input-100" value="<?php echo $_smarty_tpl->getVariable('category')->value['orders'];?>
"  /> <span class='f_red' id="dorders"></span> (数字越小，越靠前)</td>
	  </tr>
	  <tr>
		<td class='hback_1'>状态设置：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="flag" value="1"<?php if ($_smarty_tpl->getVariable('category')->value['flag']=='1'){?> checked<?php }?> />正常，<input type="radio" name="flag" value="0"<?php if ($_smarty_tpl->getVariable('category')->value['flag']=='0'){?> checked<?php }?> />锁定</td>
	  </tr>
	  <tr>
		<td class='hback_1'>主导航显示：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="ismenu" value="1"<?php if ($_smarty_tpl->getVariable('category')->value['ismenu']=='1'){?> checked<?php }?> />显示，<input type="radio" name="ismenu" value="0"<?php if ($_smarty_tpl->getVariable('category')->value['ismenu']=='0'){?> checked<?php }?> />关闭</td>
	  </tr>
	  <tr>
		<td class='hback_1'>附加导航显示：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="isaccessory" value="1"<?php if ($_smarty_tpl->getVariable('category')->value['isaccessory']=='1'){?> checked<?php }?> />显示，<input type="radio" name="isaccessory" value="0"<?php if ($_smarty_tpl->getVariable('category')->value['isaccessory']=='0'){?> checked<?php }?> />关闭</td>
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
<?php }?>


<!--           以下为子栏目操作页面          -->

<?php if ($_smarty_tpl->getVariable('a')->value=="add_list"){?>
<div class="main-wrap">
  <div class="path"><p>当前位置：模块管理<span>&gt;&gt;</span>栏目管理<span>&gt;&gt;</span><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category&a=add_list&rootid=<?php echo $_smarty_tpl->getVariable('rootid')->value;?>
">添加列表子栏目</a></p></div>
  <div class="main-cont">
	<h3 class="title"><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category" class="btn-general"><span>返回列表</span></a>添加列表子栏目</h3>
    <form name="myform" id="myform" method="post" action="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category" onsubmit='return check_add_list();' />
    <input type="hidden" name="a" value="saveadd_list" />
	<table cellpadding='1' cellspacing='2' class='tab'>
	  <tr>
		<td class='hback_1' width="15%">模块类型：<span class='f_red'></span></td>
		<td class='hback' width="85%"><?php echo $_smarty_tpl->getVariable('modname')->value;?>
 </td>
	  </tr>
	  <tr>
		<td class='hback_1'>上级栏目：<span class='f_red'>*</span></td>
		<td class='hback'><?php echo $_smarty_tpl->getVariable('root_select')->value;?>
 <span class='f_red' id="drootid"></span></td>
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
			  <iframe id='iframe_t' border='0' frameborder='0' scrolling='no' style="width:260px;height:25px;padding:0px;margin:0px;" src='<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=upload&formname=myform&module=article&uploadinput=catpic&multipart=sf_upload'></iframe>
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
<?php }?>

<?php if ($_smarty_tpl->getVariable('a')->value=="edit_list"){?>
<div class="main-wrap">
  <div class="path"><p>当前位置：模块管理<span>&gt;&gt;</span>栏目管理<span>&gt;&gt;</span>修改列表子栏目</p></div>
  <div class="main-cont">
	<h3 class="title"><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category" class="btn-general"><span>返回列表</span></a>修改列表子栏目</h3>
    <form name="myform" id="myform" method="post" action="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category" onsubmit='return check_edit_list();' />
    <input type="hidden" name="a" value="saveedit_list" />
	<input type="hidden" name="id" value="<?php echo $_smarty_tpl->getVariable('id')->value;?>
" />
	<table cellpadding='1' cellspacing='2' class='tab'>
	  <tr>
		<td class='hback_1' width="15%">模块类型：<span class='f_red'></span></td>
		<td class='hback' width="85%"><?php echo $_smarty_tpl->getVariable('category')->value['modname'];?>
 </td>
	  </tr>
	  <tr>
		<td class='hback_1'>上级栏目：<span class='f_red'>*</span></td>
		<td class='hback'><?php echo $_smarty_tpl->getVariable('root_select')->value;?>
 <span class='f_red' id="drootid"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目名称：<span class='f_red'>*</span></td>
		<td class='hback'><input type="text" name="catname" id="catname" class="input-txt" value="<?php echo $_smarty_tpl->getVariable('category')->value['catname'];?>
" /> <span class='f_red' id="dcatname"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目子名称：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="asname" id="asname" class="input-txt" value="<?php echo $_smarty_tpl->getVariable('category')->value['asname'];?>
" /> <span class='f_red' id="dasname"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>目录标识：<span class='f_red'></span></td>
		<td class='hback'><?php echo $_smarty_tpl->getVariable('category')->value['dirname'];?>
 （不可更改）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>同级排序：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="orders" id="orders" class="input-100" value="<?php echo $_smarty_tpl->getVariable('category')->value['orders'];?>
"  /> <span class='f_red' id="dorders"></span> (数字越小，越靠前)</td>
	  </tr>
	  <tr>
		<td class='hback_1'>状态设置：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="flag" value="1"<?php if ($_smarty_tpl->getVariable('category')->value['flag']=='1'){?> checked<?php }?> />正常，<input type="radio" name="flag" value="0"<?php if ($_smarty_tpl->getVariable('category')->value['flag']=='0'){?> checked<?php }?> />锁定</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目图片：<span class='f_red'></span></td>
		<td class='hback'>
		  <table border="0" cellspacing="0" cellpadding="0">
		    <tr>
			  <td><input type="text" name="catpic" id="catpic" class="input-txt" value="<?php echo $_smarty_tpl->getVariable('category')->value['catpic'];?>
"  /> <span id='dcatpic' class='f_red'></span></td>
			  <td>
			  <iframe id='iframe_t' border='0' frameborder='0' scrolling='no' style="width:260px;height:25px;padding:0px;margin:0px;" src='<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=upload&formname=myform&module=article&uploadinput=catpic&multipart=sf_upload'></iframe>
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
		<td class='hback'><input type="text" name="metatitle" id="metatitle" class="input-txt" value="<?php echo $_smarty_tpl->getVariable('category')->value['metatitle'];?>
" /> <span class='f_red' id="dmetatitle"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>Meta关键字：<span class='f_red'></span></td>
		<td class='hback'><textarea name="metakeyword" id="metakeyword" style="width:40%;height:45px;overflow:auto;"><?php echo $_smarty_tpl->getVariable('category')->value['metakeyword'];?>
</textarea> <span class='f_red' id="dmetakeyword"></span> （多个用英文逗号隔开）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>Meta描述：<span class='f_red'></span></td>
		<td class='hback'><textarea name="metadescription" id="metadescription" style="width:40%;height:45px;overflow:auto;"><?php echo $_smarty_tpl->getVariable('category')->value['metadescription'];?>
</textarea> <span class='f_red' id="dmetadescription"></span></td>
	  </tr>
	  <tr>
		<td class='hback_yellow' colspan="2">显示及其他设置</td>
	  </tr>
	  <tr>
		<td class='hback_1'>主导航显示：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="ismenu" value="1"<?php if ($_smarty_tpl->getVariable('category')->value['ismenu']=='1'){?> checked<?php }?> />显示，<input type="radio" name="ismenu" value="0"<?php if ($_smarty_tpl->getVariable('category')->value['ismenu']=='0'){?> checked<?php }?> />关闭</td>
	  </tr>
	  <tr>
		<td class='hback_1'>附加导航显示：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="isaccessory" value="1"<?php if ($_smarty_tpl->getVariable('category')->value['isaccessory']=='1'){?> checked<?php }?> />显示，<input type="radio" name="isaccessory" value="0"<?php if ($_smarty_tpl->getVariable('category')->value['isaccessory']=='0'){?> checked<?php }?> />关闭</td>
	  </tr>
	  <tr>
		<td class='hback_1'>链接类型：<span class='f_red'></span></td>
		<td class='hback'>
		<input type="radio" name="linktype" value="1"<?php if ($_smarty_tpl->getVariable('category')->value['linktype']=='1'){?> checked<?php }?> />内部链接，<input type="radio" name="linktype"  value="2"<?php if ($_smarty_tpl->getVariable('category')->value['linktype']=='2'){?> checked<?php }?> />外部链接，外部链接URL：<input type="text" name="outurl" id="outurl" class="input-txt" value="<?php echo $_smarty_tpl->getVariable('category')->value['outurl'];?>
" />
		<br />
		（内部链接，请继续填写以下设置）
		</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目首页模板：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="tplindex" id="tplindex" class="input-150" value="<?php echo $_smarty_tpl->getVariable('category')->value['tplindex'];?>
" /> <a href="javascript:void(0);" onclick="tbox_templet('选择模板文件', 'tplindex');">选择模板</a> <span class='f_red' id="dtplindex"></span> （不需要填写风格路径和模板后缀，不填写则启用模块类型的默认模板）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目列表模板：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="tpllist" id="tpllist" class="input-150" value="<?php echo $_smarty_tpl->getVariable('category')->value['tpllist'];?>
" /> <a href="javascript:void(0);" onclick="tbox_templet('选择模板文件', 'tpllist');">选择模板</a> <span class='f_red' id="dtpllist"></span> （不需要填写风格路径和模板后缀，不填写则启用模块类型的默认模板）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目内容模板：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="tpldetail" id="tpldetail" class="input-150" value="<?php echo $_smarty_tpl->getVariable('category')->value['tpldetail'];?>
" /> <a href="javascript:void(0);" onclick="tbox_templet('选择模板文件', 'tpldetail');">选择模板</a> <span class='f_red' id="dtpldetail"></span> （不需要填写风格路径和模板后缀，不填写则启用模块类型的默认模板）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>每页显示数量：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="pagemax" id="pagemax" class="input-s" value="<?php echo $_smarty_tpl->getVariable('category')->value['pagemax'];?>
" /> <span class='f_red' id="dpagemax"></span> （不填写或者0，表示使用默认。）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>列表排序方式：<span class='f_red'></span></td>
		<td class='hback'>
		<input type="radio" name="orderby" value="0"<?php if ($_smarty_tpl->getVariable('category')->value['orderby']=='0'){?> checked<?php }?> />默认，
		<input type="radio" name="orderby" value="1"<?php if ($_smarty_tpl->getVariable('category')->value['orderby']=='1'){?> checked<?php }?> />更新时间，
		<input type="radio" name="orderby" value="2"<?php if ($_smarty_tpl->getVariable('category')->value['orderby']=='2'){?> checked<?php }?> />发布时间，
		<input type="radio" name="orderby" value="3"<?php if ($_smarty_tpl->getVariable('category')->value['orderby']=='3'){?> checked<?php }?> />点击次数，
		<input type="radio" name="orderby" value="4"<?php if ($_smarty_tpl->getVariable('category')->value['orderby']=='4'){?> checked<?php }?> />ID倒序，
		<input type="radio" name="orderby" value="5"<?php if ($_smarty_tpl->getVariable('category')->value['orderby']=='5'){?> checked<?php }?> />ID顺序
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
<?php }?>

<?php if ($_smarty_tpl->getVariable('a')->value=="add_about"){?>
<div class="main-wrap">
  <div class="path"><p>当前位置：模块管理<span>&gt;&gt;</span>栏目管理<span>&gt;&gt;</span><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category&a=add_about">添加单页子栏目</a></p></div>
  <div class="main-cont">
	<h3 class="title"><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category" class="btn-general"><span>返回列表</span></a>添加单页子栏目</h3>
    <form name="myform" id="myform" method="post" action="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category" onsubmit='return check_add_about();' />
    <input type="hidden" name="a" value="saveadd_about" />
	<table cellpadding='1' cellspacing='2' class='tab'>
	  <tr>
		<td class='hback_1' width="15%">模块类型：<span class='f_red'></span></td>
		<td class='hback' width="85%">单页模型</td>
	  </tr>
	  <tr>
		<td class='hback_1'>上级栏目：<span class='f_red'>*</span></td>
		<td class='hback'><?php echo $_smarty_tpl->getVariable('root_select')->value;?>
 <span class='f_red' id="drootid"></span></td>
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
			  <iframe id='iframe_t' border='0' frameborder='0' scrolling='no' style="width:260px;height:25px;padding:0px;margin:0px;" src='<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=upload&formname=myform&module=article&uploadinput=catpic&multipart=sf_upload'></iframe>
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
<?php }?>

<?php if ($_smarty_tpl->getVariable('a')->value=="edit_about"){?>
<div class="main-wrap">
  <div class="path"><p>当前位置：模块管理<span>&gt;&gt;</span>栏目管理<span>&gt;&gt;</span>编辑单页子栏目</p></div>
  <div class="main-cont">
	<h3 class="title"><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category" class="btn-general"><span>返回列表</span></a>编辑单页子栏目</h3>
    <form name="myform" id="myform" method="post" action="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category" onsubmit='return check_edit_about();' />
    <input type="hidden" name="a" value="saveedit_about" />
	<input type="hidden" name="id" value="<?php echo $_smarty_tpl->getVariable('id')->value;?>
" />
	<table cellpadding='1' cellspacing='2' class='tab'>
	  <tr>
		<td class='hback_1' width="15%">模块类型：<span class='f_red'></span></td>
		<td class='hback' width="85%"><?php echo $_smarty_tpl->getVariable('category')->value['modname'];?>
 （不可更改）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>上级栏目：<span class='f_red'>*</span></td>
		<td class='hback'><?php echo $_smarty_tpl->getVariable('root_select')->value;?>
 <span class='f_red' id="drootid"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目名称：<span class='f_red'>*</span></td>
		<td class='hback'><input type="text" name="catname" id="catname" class="input-txt" value="<?php echo $_smarty_tpl->getVariable('category')->value['catname'];?>
" /> <span class='f_red' id="dcatname"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目子名称：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="asname" id="asname" class="input-txt" value="<?php echo $_smarty_tpl->getVariable('category')->value['asname'];?>
" /> <span class='f_red' id="dasname"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>目录标识：<span class='f_red'></span></td>
		<td class='hback'><?php echo $_smarty_tpl->getVariable('category')->value['dirname'];?>
 （不可更改）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>同级排序：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="orders" id="orders" class="input-100" value="<?php echo $_smarty_tpl->getVariable('category')->value['orders'];?>
"  /> <span class='f_red' id="dorders"></span> (数字越小，越靠前)</td>
	  </tr>
	  <tr>
		<td class='hback_1'>状态设置：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="flag" value="1"<?php if ($_smarty_tpl->getVariable('category')->value['flag']=='1'){?> checked<?php }?> />正常，<input type="radio" name="flag" value="0"<?php if ($_smarty_tpl->getVariable('category')->value['flag']=='0'){?> checked<?php }?> />锁定</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目图片：<span class='f_red'></span></td>
		<td class='hback'>
		  <table border="0" cellspacing="0" cellpadding="0">
		    <tr>
			  <td><input type="text" name="catpic" id="catpic" class="input-txt" value="<?php echo $_smarty_tpl->getVariable('category')->value['catpic'];?>
"  /> <span id='dcatpic' class='f_red'></span></td>
			  <td>
			  <iframe id='iframe_t' border='0' frameborder='0' scrolling='no' style="width:260px;height:25px;padding:0px;margin:0px;" src='<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=upload&formname=myform&module=article&uploadinput=catpic&multipart=sf_upload'></iframe>
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
		<td class='hback'><input type="radio" name="ismenu" value="1"<?php if ($_smarty_tpl->getVariable('category')->value['ismenu']=='1'){?> checked<?php }?> />显示，<input type="radio" name="ismenu" value="0"<?php if ($_smarty_tpl->getVariable('category')->value['ismenu']=='0'){?> checked<?php }?> />关闭</td>
	  </tr>
	  <tr>
		<td class='hback_1'>附加导航显示：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="isaccessory" value="1"<?php if ($_smarty_tpl->getVariable('category')->value['isaccessory']=='1'){?> checked<?php }?> />显示，<input type="radio" name="isaccessory" value="0"<?php if ($_smarty_tpl->getVariable('category')->value['isaccessory']=='0'){?> checked<?php }?> />关闭</td>
	  </tr>
	  <tr>
		<td class='hback_1'>链接类型：<span class='f_red'></span></td>
		<td class='hback'>
		<input type="radio" name="linktype" value="1"<?php if ($_smarty_tpl->getVariable('category')->value['linktype']=='1'){?> checked<?php }?> />内部链接，<input type="radio" name="linktype"  value="2"<?php if ($_smarty_tpl->getVariable('category')->value['linktype']=='2'){?> checked<?php }?> />外部链接，外部链接URL：<input type="text" name="outurl" id="outurl" class="input-txt" value="<?php echo $_smarty_tpl->getVariable('category')->value['outurl'];?>
" />
		<br />
		（内部链接，请继续填写以下设置）
		</td>
	  </tr>
	  <tr>
		<td class='hback_1'>栏目内容模板：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="tpldetail" id="tpldetail" class="input-150" value="<?php echo $_smarty_tpl->getVariable('category')->value['tpldetail'];?>
" /> <a href="javascript:void(0);" onclick="tbox_templet('选择模板文件', 'tpldetail');">选择模板</a> <span class='f_red' id="dtpldetail"></span> （不需要填写风格路径和模板后缀，不填写则启用模块类型的默认模板）</td>
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
<?php }?>

<?php $_smarty_tpl->tpl_vars['pluginevent'] = new Smarty_variable(XHook::doAction('adm_footer'), null, null);?>
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
