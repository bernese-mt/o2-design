<?php /* Smarty version Smarty-3.0.5, created on 2019-05-07 15:42:58
         compiled from "/home/o2design/public_html/OECMS/tpl/admincp/zone.tpl" */ ?>
<?php /*%%SmartyHeaderCode:11335135605cd137027a48c4-26371711%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '109927580941820816cf478cc42b6033ef95cb61' => 
    array (
      0 => '/home/o2design/public_html/OECMS/tpl/admincp/zone.tpl',
      1 => 1557211190,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11335135605cd137027a48c4-26371711',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_modifier_stripslashes')) include '/home/o2design/public_html/OECMS/source/core/smarty/plugins/modifier.stripslashes.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $_smarty_tpl->getVariable('page_charset')->value;?>
" />
<title>设置广告版位</title>
<?php $_template = new Smarty_Internal_Template(($_smarty_tpl->getVariable('cptplpath')->value)."headerjs.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>
<?php $_smarty_tpl->tpl_vars['pluginevent'] = new Smarty_variable(XHook::doAction('adm_head'), null, null);?>
</head>
<body>
<?php $_smarty_tpl->tpl_vars['pluginevent'] = new Smarty_variable(XHook::doAction('adm_main_top'), null, null);?>
<?php if ($_smarty_tpl->getVariable('a')->value=="run"){?>
<div class="main-wrap">
  <div class="path"><p>当前位置：系统设置<span>&gt;&gt;</span>其他设置<span>&gt;&gt;</span><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=zone">广告版位</a></p></div>
  <div class="main-cont">
    <h3 class="title"><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=zone&a=add" class="btn-general"><span>添加版位</span></a>广告版位</h3>
	<form action="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=zone&a=del" method="post" name="myform" id="myform" style="margin:0">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table" align="center">
	  <thead class="tb-tit-bg">
	  <tr>
	    <th width="8%"><div class="th-gap">选择</div></th>
		<th width="15%"><div class="th-gap">版位名称</div></th>
		<th width="20%"><div class="th-gap">版位标识</div></th>
		<th width="10%"><div class="th-gap">类型</div></th>
		<th width="15%"><div class="th-gap">大小</div></th>
		<th width="10%"><div class="th-gap">广告数</div></th>
		<th width="8%"><div class="th-gap">状态</div></th>
		<th><div class="th-gap">操作</div></th>
	  </tr>
	  </thead>
	  <tfoot class="tb-foot-bg"></tfoot>
	  <?php  $_smarty_tpl->tpl_vars['volist'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('zone')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['volist']->key => $_smarty_tpl->tpl_vars['volist']->value){
?>
	  <tr onMouseOver="overColor(this)" onMouseOut="outColor(this)">
	    <td align="center"><input name="id[]" type="checkbox" value="<?php echo $_smarty_tpl->tpl_vars['volist']->value['zoneid'];?>
" onClick="checkItem(this, 'chkAll')"></td>
		<td><?php echo $_smarty_tpl->tpl_vars['volist']->value['zonename'];?>
</td>
		<td align="center"><?php echo $_smarty_tpl->tpl_vars['volist']->value['idmark'];?>
</td>
		<td align="center"><?php echo $_smarty_tpl->tpl_vars['volist']->value['sort'];?>
</td>
		<td align="center"><?php echo $_smarty_tpl->tpl_vars['volist']->value['zonewidth'];?>
x<?php echo $_smarty_tpl->tpl_vars['volist']->value['zoneheight'];?>
(像素)</td>
		<td align="center"><?php echo $_smarty_tpl->tpl_vars['volist']->value['adscount'];?>
</td>
		<td align="center">
		<?php if ($_smarty_tpl->tpl_vars['volist']->value['flag']==0){?>
			<input type="hidden" id="attr_flag<?php echo $_smarty_tpl->tpl_vars['volist']->value['zoneid'];?>
" value="flagopen" />
			<img id="flag<?php echo $_smarty_tpl->tpl_vars['volist']->value['zoneid'];?>
" src="<?php echo $_smarty_tpl->getVariable('urlpath')->value;?>
tpl/static/images/no.gif" onClick="javascript:fetch_ajax('flag','<?php echo $_smarty_tpl->tpl_vars['volist']->value['zoneid'];?>
');" style="cursor:pointer;">
		<?php }else{ ?>
			<input type="hidden" id="attr_flag<?php echo $_smarty_tpl->tpl_vars['volist']->value['zoneid'];?>
" value="flagclose" />
			<img id="flag<?php echo $_smarty_tpl->tpl_vars['volist']->value['zoneid'];?>
" src="<?php echo $_smarty_tpl->getVariable('urlpath')->value;?>
tpl/static/images/yes.gif" onClick="javascript:fetch_ajax('flag','<?php echo $_smarty_tpl->tpl_vars['volist']->value['zoneid'];?>
');" style="cursor:pointer;">	
		<?php }?>
		</td>
		<td align="center">
		<a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=zone&a=edit&id=<?php echo $_smarty_tpl->tpl_vars['volist']->value['zoneid'];?>
&page=<?php echo $_smarty_tpl->getVariable('page')->value;?>
" class="icon-set">设置</a>&nbsp;&nbsp;
		<a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=zone&a=del&id[]=<?php echo $_smarty_tpl->tpl_vars['volist']->value['zoneid'];?>
" onClick="{if(confirm('确定要删除该信息？')){return true;} return false;}" class="icon-del">删除</a></td>
	  </tr>
	  <?php }} else { ?>
      <tr>
	    <td colspan="8" align="center">暂无信息</td>
	  </tr>
	  <?php } ?>
	  <?php if ($_smarty_tpl->getVariable('total')->value>0){?>
	  <tr>
		<td align="center"><input name="chkAll" type="checkbox" id="chkAll" onClick="checkAll(this, 'id[]')" value="checkbox"></td>
		<td class="hback" colspan="7"><input class="button" name="btn_del" type="button" value="删 除" onClick="{if(confirm('确定要删除选定信息？')){$('#myform').submit();return true;}return false;}" class="button">&nbsp;&nbsp;共[ <b><?php echo $_smarty_tpl->getVariable('total')->value;?>
</b> ]条记录</td>
	  </tr>
	  <?php }?>
	</table>
	</form>
	<?php if ($_smarty_tpl->getVariable('pagecount')->value>1){?>
	<table width='95%' border='0' cellspacing='0' cellpadding='0' align='center' style="margin-top:10px;">
	  <tr>
		<td align='center'><?php echo $_smarty_tpl->getVariable('showpage')->value;?>
</td>
	  </tr>
	</table>
	<?php }?>
  </div>
</div>
<?php }?>

<?php if ($_smarty_tpl->getVariable('a')->value=="add"){?>
<div class="main-wrap">
  <div class="path"><p>当前位置：系统设置<span>&gt;&gt;</span>其他设置<span>&gt;&gt;</span><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=zone&a=add">添加广告版位</a></p></div>
  <div class="main-cont">
	<h3 class="title"><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=zone" class="btn-general"><span>返回列表</span></a>添加广告版位</h3>
    <form name="myform" id="myform" method="post" action="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=zone" onsubmit='return checkform();' />
    <input type="hidden" name="a" value="saveadd" />
	<table cellpadding='1' cellspacing='2' class='tab'>
	  <tr>
		<td class='hback_1' width="15%">版位名称：<span class='f_red'>*</span></td>
		<td class='hback' width="85%"><input type="text" name="zonename" id="zonename" class="input-txt" /> <span class='f_red' id="dzonename"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>版位标识：<span class='f_red'>*</span></td>
		<td class='hback'><input type="text" name="idmark" id="idmark" class="input-txt" /> <span class='f_red' id="didmark"></span> （只能字母，数字，下横线，中横线组成）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>版位类型：<span class='f_red'>*</span></td>
		<td class='hback'>
		<select name="sort" id="sort">
		<option value=''>请选择</option>
		<option value='slide'>幻灯片</option>
		<option value='flash'>Flash</option>
		<option value='picture'>单张图片</option>
		</select>
		</td>
	  </tr>
	  <tr>
		<td class='hback_1'>广告位宽：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="zonewidth" id="zonewidth" class="input-s" /> 像素(px) <span class='f_red' id="dzonewidth"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>广告位高：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="zoneheight" id="zoneheight" class="input-s" /> 像素(px) <span class='f_red' id="dzoneheight"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>使用状态：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="flag" id="flag" value="1" checked /> 正常，<input type="radio" name="flag" id="flag" value="0" /> 锁定</td>
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

<?php if ($_smarty_tpl->getVariable('a')->value=="edit"){?>
<div class="main-wrap">
  <div class="path"><p>当前位置：系统设置<span>&gt;&gt;</span>其他设置<span>&gt;&gt;</span>编辑广告版位</p></div>
  <div class="main-cont">
	<h3 class="title"><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=zone&page=<?php echo $_smarty_tpl->getVariable('page')->value;?>
" class="btn-general"><span>返回列表</span></a>编辑广告版位</h3>
    <form name="myform" id="myform" method="post" action="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=zone&page=<?php echo $_smarty_tpl->getVariable('page')->value;?>
" onsubmit='return checkedit();' />
    <input type="hidden" name="a" value="saveedit" />
	<input type="hidden" name="id" value="<?php echo $_smarty_tpl->getVariable('id')->value;?>
" />
	<table cellpadding='1' cellspacing='2' class='tab'>
	  <tr>
		<td class='hback_1'>版位标识：<span class='f_red'></span></td>
		<td class='hback'><?php echo $_smarty_tpl->getVariable('zone')->value['idmark'];?>
 （不能修改）</td>
	  </tr>
	  <tr>
		<td class='hback_1' width="15%">版位名称：<span class='f_red'>*</span></td>
		<td class='hback' width="85%"><input type="text" name="zonename" id="zonename" class="input-txt" value="<?php echo smarty_modifier_stripslashes($_smarty_tpl->getVariable('zone')->value['zonename']);?>
" /> <span class='f_red' id="dzonename"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>版位类型：<span class='f_red'>*</span></td>
		<td class='hback'>
		<select name="sort" id="sort">
		<option value=''>请选择</option>
		<option value='slide'<?php if ($_smarty_tpl->getVariable('zone')->value['sort']=='slide'){?> selected<?php }?>>幻灯片</option>
		<option value='flash'<?php if ($_smarty_tpl->getVariable('zone')->value['sort']=='flash'){?> selected<?php }?>>Flash</option>
		<option value='picture'<?php if ($_smarty_tpl->getVariable('zone')->value['sort']=='picture'){?> selected<?php }?>>单张图片</option>
		</select>
		</td>
	  </tr>
	  <tr>
		<td class='hback_1'>广告位宽：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="zonewidth" id="zonewidth" class="input-s" value="<?php echo $_smarty_tpl->getVariable('zone')->value['zonewidth'];?>
" /> 像素(px) <span class='f_red' id="dzonewidth"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>广告位高：<span class='f_red'></span></td>
		<td class='hback'><input type="text" name="zoneheight" id="zoneheight" class="input-s" value="<?php echo $_smarty_tpl->getVariable('zone')->value['zoneheight'];?>
" /> 像素(px) <span class='f_red' id="dzoneheight"></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>使用状态：<span class='f_red'></span></td>
		<td class='hback'><input type="radio" name="flag" id="flag" value="1"<?php if ($_smarty_tpl->getVariable('zone')->value['flag']=='1'){?> checked<?php }?> /> 正常，<input type="radio" name="flag" id="flag" value="0"<?php if ($_smarty_tpl->getVariable('zone')->value['flag']=='0'){?> checked<?php }?> /> 锁定</td>
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
<?php }?>
<?php $_smarty_tpl->tpl_vars['pluginevent'] = new Smarty_variable(XHook::doAction('adm_footer'), null, null);?>
</body>
</html>
<script type="text/javascript">
function checkform() {
	var t = "";
	var v = "";

	t = "zonename";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("版位名称不能为空", t);
		return false;
	}

	t = "idmark";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("版位标识不能为空", t);
		return false;
	}

	t = "sort";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("版位类型不能为空", t);
		return false;
	}

	return true;
}

function checkedit() {
	var t = "";
	var v = "";

	t = "zonename";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("版位名称不能为空", t);
		return false;
	}

	t = "sort";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("版位类型不能为空", t);
		return false;
	}

	return true;
}
</script>
