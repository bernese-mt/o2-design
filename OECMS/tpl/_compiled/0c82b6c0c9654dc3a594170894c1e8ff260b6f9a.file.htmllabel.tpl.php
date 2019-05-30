<?php /* Smarty version Smarty-3.0.5, created on 2019-05-07 15:42:11
         compiled from "/home/o2design/public_html/OECMS/tpl/admincp/htmllabel.tpl" */ ?>
<?php /*%%SmartyHeaderCode:8283593075cd136d333e0d2-52834379%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0c82b6c0c9654dc3a594170894c1e8ff260b6f9a' => 
    array (
      0 => '/home/o2design/public_html/OECMS/tpl/admincp/htmllabel.tpl',
      1 => 1557211188,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8283593075cd136d333e0d2-52834379',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_modifier_date_format')) include '/home/o2design/public_html/OECMS/source/core/smarty/plugins/modifier.date_format.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $_smarty_tpl->getVariable('page_charset')->value;?>
" />
<title>自定义HTML标签</title>
<?php $_template = new Smarty_Internal_Template(($_smarty_tpl->getVariable('cptplpath')->value)."headerjs.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>
<?php $_smarty_tpl->tpl_vars['pluginevent'] = new Smarty_variable(XHook::doAction('adm_head'), null, null);?>
</head>
<body>
<?php $_smarty_tpl->tpl_vars['pluginevent'] = new Smarty_variable(XHook::doAction('adm_main_top'), null, null);?>
<?php if ($_smarty_tpl->getVariable('a')->value=="run"){?>
<div class="main-wrap">
  <div class="path"><p>当前位置：系统设置<span>&gt;&gt;</span>其他设置<span>&gt;&gt;</span><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=htmllabel">自定义HTML标签</a></p></div>
  <div class="main-cont">
    <h3 class="title"><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=htmllabel&a=add" class="btn-general"><span>添加标签</span></a>自定义HTML标签</h3>
	<form action="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=htmllabel&a=del" method="post" name="myform" id="myform" style="margin:0">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table" align="center">
	  <thead class="tb-tit-bg">
	  <tr>
	    <th width="10%"><div class="th-gap">选择</div></th>
		<th width="12%"><div class="th-gap">标签描述</div></th>
		<th width="12%"><div class="th-gap">标签名</div></th>
		<th width="25%"><div class="th-gap">调用标签</div></th>
		<th width="8%"><div class="th-gap">状态</div></th>
		<th width="18%"><div class="th-gap">录入时间</div></th>
		<th><div class="th-gap">操作</div></th>
	  </tr>
	  </thead>
	  <tfoot class="tb-foot-bg"></tfoot>
	  <?php  $_smarty_tpl->tpl_vars['volist'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('htmllabel')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['volist']->key => $_smarty_tpl->tpl_vars['volist']->value){
?>
	  <tr onMouseOver="overColor(this)" onMouseOut="outColor(this)">
	    <td align="center">
		<?php if ($_smarty_tpl->tpl_vars['volist']->value['issystem']=='0'){?>
		<input name="id[]" type="checkbox" value="<?php echo $_smarty_tpl->tpl_vars['volist']->value['labelid'];?>
" onClick="checkItem(this, 'chkAll')">
		<?php }else{ ?>
		<input type='checkbox' disabled />
		<?php }?>
		</td>
		<td align="left"><?php echo $_smarty_tpl->tpl_vars['volist']->value['description'];?>
</td>
		<td align="center"><?php echo $_smarty_tpl->tpl_vars['volist']->value['labelname'];?>
</td>
		<td align="left">&lt;!--{label name='<?php echo $_smarty_tpl->tpl_vars['volist']->value['labelname'];?>
'}--&gt;</td>
		
		<td align="center">
		<?php if ($_smarty_tpl->tpl_vars['volist']->value['flag']==0){?>
			<input type="hidden" id="attr_flag<?php echo $_smarty_tpl->tpl_vars['volist']->value['labelid'];?>
" value="flagopen" />
			<img id="flag<?php echo $_smarty_tpl->tpl_vars['volist']->value['labelid'];?>
" src="<?php echo $_smarty_tpl->getVariable('urlpath')->value;?>
tpl/static/images/no.gif" onClick="javascript:fetch_ajax('flag','<?php echo $_smarty_tpl->tpl_vars['volist']->value['labelid'];?>
');" style="cursor:pointer;">
		<?php }else{ ?>
			<input type="hidden" id="attr_flag<?php echo $_smarty_tpl->tpl_vars['volist']->value['labelid'];?>
" value="flagclose" />
			<img id="flag<?php echo $_smarty_tpl->tpl_vars['volist']->value['labelid'];?>
" src="<?php echo $_smarty_tpl->getVariable('urlpath')->value;?>
tpl/static/images/yes.gif" onClick="javascript:fetch_ajax('flag','<?php echo $_smarty_tpl->tpl_vars['volist']->value['labelid'];?>
');" style="cursor:pointer;">	
		<?php }?>
        </td>
		<td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['volist']->value['timeline'],"%Y/%m/%d %H:%M:%S");?>
</td>
		<td align="center">
		<a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=htmllabel&a=edit&id=<?php echo $_smarty_tpl->tpl_vars['volist']->value['labelid'];?>
&page=<?php echo $_smarty_tpl->getVariable('page')->value;?>
" class="icon-edit">编辑</a>&nbsp;&nbsp;
		<?php if ($_smarty_tpl->tpl_vars['volist']->value['issystem']=='0'){?>
		<a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=htmllabel&a=del&id[]=<?php echo $_smarty_tpl->tpl_vars['volist']->value['labelid'];?>
" onClick="{if(confirm('确定要删除吗？')){return true;} return false;}" class="icon-del">删除</a>
		<?php }?>
		</td>
	  </tr>
	  <?php }} else { ?>
      <tr>
	    <td colspan="7" align="center">暂无信息</td>
	  </tr>
	  <?php } ?>
	  <?php if ($_smarty_tpl->getVariable('total')->value>0){?>
	  <tr>
		<td align="center"><input name="chkAll" type="checkbox" id="chkAll" onClick="checkAll(this, 'id[]')" value="checkbox"></td>
		<td class="hback" colspan="6"><input class="button" name="btn_del" type="button" value="删 除" onClick="{if(confirm('确定删除选定信息吗!?')){$('#myform').submit();return true;}return false;}" class="button">&nbsp;&nbsp;共[ <b><?php echo $_smarty_tpl->getVariable('total')->value;?>
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
?c=htmllabel&a=add">添加HTML标签</a></p></div>
  <div class="main-cont">
	<h3 class="title"><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=htmllabel" class="btn-general"><span>返回列表</span></a>添加HTML标签</h3>
    <form name="myform" id="myform" method="post" action="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=htmllabel" onsubmit='return checkform();' />
    <input type="hidden" name="a" value="saveadd" />
	<table cellpadding='1' cellspacing='1' class='tab'>
	  <tr>
		<td class='hback_1' width='15%'>标签名称 <span class="f_red">*</span> </td>
		<td class='hback' width='85%'><input type="text" name="labelname" id="labelname" class="input-txt" /> <span id='dlabelname' class='f_red'></span> (标签名只能字母、下横线、中横线、数字组成)</td>
	  </tr>
	  <tr>
		<td class='hback_1'>标签描述 <span class="f_red">*</span> </td>
		<td class='hback'><input type="text" name="description" id="description" class="input-txt" />  <span id='ddescription' class='f_red'></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>状态设置 </td>
		<td class='hback'><input type="radio" name="flag" id="flag" value="1" checked />正常，<input type="radio" name="flag" id="flag" value="0" />锁定</td>
	  </tr>
	  <tr>
		<td class='hback_1'>标签内容</td>
		<td class='hback'>
		  <textarea name="content" id="content" style="width:98%;height:250px;display:none;"></textarea>
		  <script>var editor;KindEditor.ready(function(K) {editor = K.create('#content'); });</script>
		  <br />1、支持config标签，调用config标签时不需要使用起始符“&lt!--”和结束符“--&gt;”<br />
		           如：调用网站名称只需在内容里填写  {$config.sitetitle}<br />
		        2、支持{$skinpath}、{$urlpath}标签。
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

<?php if ($_smarty_tpl->getVariable('a')->value=="edit"){?>
<div class="main-wrap">
  <div class="path"><p>当前位置：系统设置<span>&gt;&gt;</span>其他设置<span>&gt;&gt;</span>编辑HTML标签</p></div>
  <div class="main-cont">
	<h3 class="title"><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=htmllabel&page=<?php echo $_smarty_tpl->getVariable('page')->value;?>
" class="btn-general"><span>返回列表</span></a>编辑HTML标签</h3>
    <form name="myform" id="myform" method="post" action="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=htmllabel&page=<?php echo $_smarty_tpl->getVariable('page')->value;?>
" />
    <input type="hidden" name="a" value="saveedit" />
	<input type="hidden" name="id" value="<?php echo $_smarty_tpl->getVariable('id')->value;?>
" />
	<table cellpadding='1' cellspacing='1' class='tab'>
	  <tr>
		<td class='hback_1' width='15%'>标签名称 <span class="f_red"></span> </td>
		<td class='hback' width='85%'><?php echo $_smarty_tpl->getVariable('htmllabel')->value['labelname'];?>
 (不可更改)</td>
	  </tr>
	  <tr>
		<td class='hback_1'>标签描述 <span class="f_red">*</span> </td>
		<td class='hback'><input type="text" name="description" id="description" class="input-txt" value="<?php echo $_smarty_tpl->getVariable('htmllabel')->value['description'];?>
" />  <span id='ddescription' class='f_red'></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>状态设置 </td>
		<td class='hback'><input type="radio" name="flag" id="flag" value="1"<?php if ($_smarty_tpl->getVariable('htmllabel')->value['flag']=='1'){?> checked<?php }?> />正常，<input type="radio" name="flag" id="flag" value="0"<?php if ($_smarty_tpl->getVariable('htmllabel')->value['flag']=='0'){?> checked<?php }?> />锁定</td>
	  </tr>
	  <tr>
		<td class='hback_1'>标签内容</td>
		<td class='hback'>
		  <textarea name="content" id="content" style="width:98%;height:250px;display:none;"><?php echo $_smarty_tpl->getVariable('htmllabel')->value['content'];?>
</textarea>
		  <script>var editor;KindEditor.ready(function(K) {editor = K.create('#content'); });</script>
		  <br />1、支持config标签，调用config标签时不需要使用起始符“&lt!--”和结束符“--&gt;”<br />
		           如：调用网站名称只需在内容里填写  {$config.sitetitle}<br />
		        2、支持{$skinpath}、{$urlpath}标签。
		</td>
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
