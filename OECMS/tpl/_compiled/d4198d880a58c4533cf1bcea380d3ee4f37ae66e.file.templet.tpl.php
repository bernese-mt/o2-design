<?php /* Smarty version Smarty-3.0.5, created on 2019-05-07 15:40:59
         compiled from "/home/o2design/public_html/OECMS/tpl/admincp/templet.tpl" */ ?>
<?php /*%%SmartyHeaderCode:16082097105cd1368bb5f8c2-20001054%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd4198d880a58c4533cf1bcea380d3ee4f37ae66e' => 
    array (
      0 => '/home/o2design/public_html/OECMS/tpl/admincp/templet.tpl',
      1 => 1557211177,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16082097105cd1368bb5f8c2-20001054',
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
<title>模板文件</title>
<?php $_template = new Smarty_Internal_Template(($_smarty_tpl->getVariable('cptplpath')->value)."headerjs.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>
<?php if ($_smarty_tpl->getVariable('fromtype')->value!='jdbox'){?>
<?php $_smarty_tpl->tpl_vars['pluginevent'] = new Smarty_variable(XHook::doAction('adm_head'), null, null);?>
<?php }?>
</head>
<body>
<?php if ($_smarty_tpl->getVariable('fromtype')->value!='jdbox'){?>
<?php $_smarty_tpl->tpl_vars['pluginevent'] = new Smarty_variable(XHook::doAction('adm_main_top'), null, null);?>
<?php }?>
<?php if ($_smarty_tpl->getVariable('a')->value=="run"){?>
<div class="main-wrap">
  <div class="path"><p>当前位置：界面模板<span>&gt;&gt;</span>模板文件</p></div>
  <div class="main-cont">
    <h3 class="title"><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=skin&a=style" class="btn-general"><span>配置界面风格</span></a>正在使用的主题模板</h3>

    <table cellspacing="10" cellpadding="0" width="80%" border="0">
      <tr>
        <td width="27%">
		<img src="<?php echo $_smarty_tpl->getVariable('usingskin')->value['preview'];?>
" width="240" height="180"  border="1" />	  
		</td>
		<td width="73%" style="line-height:25px;">
	    <?php echo $_smarty_tpl->getVariable('usingskin')->value['tplname'];?>
 <em><?php echo $_smarty_tpl->getVariable('usingskin')->value['version'];?>
</em><br>
	    <?php echo $_smarty_tpl->getVariable('usingskin')->value['author'];?>
<br>
	    <?php echo $_smarty_tpl->getVariable('usingskin')->value['forversion'];?>
<br>
		<?php echo $_smarty_tpl->getVariable('usingskin')->value['lastupdate'];?>
<br />
	    <p><?php echo $_smarty_tpl->getVariable('usingskin')->value['desc'];?>
</p>
	    </td>
      </tr>
    </table>


    <h3 class="title"><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=templet" class="btn-general"><span>返回模板根目录</span></a><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=templet&dir=<?php echo urlencode($_smarty_tpl->getVariable('dir')->value);?>
" class="btn-general"><span>当前目录：<?php echo $_smarty_tpl->getVariable('dir')->value;?>
</span></a>正在使用的主题模板文件</h3>
	<div class="search-area ">
	  <div class="item">
	  1、编辑，删除模板文件，必须确保目录“tpl/templets/<?php echo $_smarty_tpl->getVariable('usingskin')->value['tpldir'];?>
”有写入，删除权限，否则无法使用；<br />
	  2、文件必须为“<?php echo $_smarty_tpl->getVariable('page_charset')->value;?>
”格式，修改和删除文件时请检查文件的使用情况，以免影响网站正常运行。
	  </div>
	</div>
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table" align="center">
	  <thead class="tb-tit-bg">
	  <tr>
	    <th width="10%"><div class="th-gap">编号</div></th>
		<th width="10%"><div class="th-gap">类型</div></th>
		<th width="30%"><div class="th-gap">文件名</div></th>
		<th width="15%"><div class="th-gap">大小</div></th>
		<th width="18%"><div class="th-gap">最后修改时间</div></th>
		<th><div class="th-gap">操作</div></th>
	  </tr>
	  </thead>
	  <tfoot class="tb-foot-bg"></tfoot>
	  <?php  $_smarty_tpl->tpl_vars['volist'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('templets')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['volist']->key => $_smarty_tpl->tpl_vars['volist']->value){
?>
	  <tr onMouseOver="overColor(this)" onMouseOut="outColor(this)">
	    <td align="center"><?php echo $_smarty_tpl->tpl_vars['volist']->value['i'];?>
</td>
		<td align="center"><?php if ($_smarty_tpl->tpl_vars['volist']->value['type']==1){?><font color="green"><b>目录</b></font><?php }else{ ?><font color="blue"><b>文件</b></font><?php }?></td>
		<td align="left"><?php echo $_smarty_tpl->tpl_vars['volist']->value['filename'];?>
</td>
		<td align="center"><?php echo $_smarty_tpl->tpl_vars['volist']->value['size'];?>
 Bytes</td>
		<td align="center"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['volist']->value['timeline'],"%Y-%m-%d %H:%H:%S");?>
</td>
		<td align="center">
		<?php if ($_smarty_tpl->tpl_vars['volist']->value['type']==1){?>
		<a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=templet&dir=<?php echo urlencode($_smarty_tpl->tpl_vars['volist']->value['dir']);?>
" class="icon-show">打开</a>&nbsp;&nbsp;
		<a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=templet&a=delfolder&id=<?php echo urlencode($_smarty_tpl->tpl_vars['volist']->value['dir']);?>
" onClick="{if(confirm('确定要删除该文件夹吗？一旦删除无法恢复！')){return true;} return false;}" class="icon-del">删除</a>
		<?php }else{ ?>
		<a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=templet&a=edit&id=<?php echo urlencode($_smarty_tpl->tpl_vars['volist']->value['filepath']);?>
" class="icon-edit">修改</a>&nbsp;&nbsp;
		<a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=templet&a=delfile&id=<?php echo urlencode($_smarty_tpl->tpl_vars['volist']->value['filepath']);?>
" onClick="{if(confirm('确定要删除该文件吗？一旦删除无法恢复！')){return true;} return false;}" class="icon-del">删除</a>
		<?php }?>
		
		</td>
	  </tr>
	  <?php }} else { ?>
      <tr>
	    <td colspan="6" align="center">对不起，该目录没有文件！</td>
	  </tr>
	  <?php } ?>
	</table>
  </div>
</div>
<?php }?>

<?php if ($_smarty_tpl->getVariable('a')->value=="edit"){?>
<div class="main-wrap">
  <div class="path"><p>当前位置：界面模板<span>&gt;&gt;</span>模板文件<span>&gt;&gt;</span>编辑文件</p></div>
  <div class="main-cont">

    <h3 class="title"><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=skin&a=style" class="btn-general"><span>配置界面风格</span></a>正在使用的主题模板</h3>

    <table cellspacing="10" cellpadding="0" width="80%" border="0">
      <tr>
        <td width="27%">
		<img src="<?php echo $_smarty_tpl->getVariable('usingskin')->value['preview'];?>
" width="240" height="180"  border="1" />	  
		</td>
		<td width="73%" style="line-height:25px;">
	    <?php echo $_smarty_tpl->getVariable('usingskin')->value['tplname'];?>
 <em><?php echo $_smarty_tpl->getVariable('usingskin')->value['version'];?>
</em><br>
	    <?php echo $_smarty_tpl->getVariable('usingskin')->value['author'];?>
<br>
	    <?php echo $_smarty_tpl->getVariable('usingskin')->value['forversion'];?>
<br>
		<?php echo $_smarty_tpl->getVariable('usingskin')->value['lastupdate'];?>
<br />
	    <p><?php echo $_smarty_tpl->getVariable('usingskin')->value['desc'];?>
</p>
	    </td>
      </tr>
    </table>

    <h3 class="title"><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=templet" class="btn-general"><span>返回模板根目录</span></a><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=templet&dir=<?php echo urlencode($_smarty_tpl->getVariable('dir')->value);?>
" class="btn-general"><span>当前目录：<?php echo $_smarty_tpl->getVariable('dir')->value;?>
</span></a>编辑模板文件</h3>
	<div class="search-area ">
	  <div class="item">
	  1、编辑，删除模板文件，必须确保目录“tpl/templets/<?php echo $_smarty_tpl->getVariable('usingskin')->value['tpldir'];?>
”有写入，删除权限，否则无法使用；<br />
	  2、文件必须为“<?php echo $_smarty_tpl->getVariable('page_charset')->value;?>
”格式，修改和删除文件时请检查文件的使用情况，以免影响网站正常运行。
	  </div>
	</div>
    <form name="myform" id="myform" method="post" action="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=templet&a=saveedit" onsubmit='return checkform();' />
	<input type="hidden" name="id" value="<?php echo $_smarty_tpl->getVariable('id')->value;?>
" />
	<input type="hidden" name="dir" value="<?php echo $_smarty_tpl->getVariable('dir')->value;?>
" />
	<table cellpadding='3' cellspacing='3' class='tab'>
	  <tr>
		<td class='hback_1' width="10%">文件名 </td>
		<td class='hback' width="80%"><b><font color="blue">tpl/templets/<?php echo $_smarty_tpl->getVariable('usingskin')->value['tpldir'];?>
<?php echo $_smarty_tpl->getVariable('id')->value;?>
</font></b></td>
	  </tr>
	  <tr>
		<td class='hback_1'>文件内容 <span class='f_red'>*</span></td>
		<td class='hback'><textarea name="content" id="content" style="width:98%;height:300px;display:;overflow:auto;"><?php echo $_smarty_tpl->getVariable('content')->value;?>
</textarea>  <br /><span id='dcontent' class='f_red'></span></td>
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

<?php if ($_smarty_tpl->getVariable('a')->value=="select"){?>
<div class="main-wrap">
  <div class="main-cont">
    <h3 class="title">正在使用的主题 [<?php echo $_smarty_tpl->getVariable('usingskin')->value['tplname'];?>
] 模板目录：tpl/templets/<?php echo $_smarty_tpl->getVariable('usingskin')->value['tpldir'];?>
</h3>
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table" align="center">
	  <thead class="tb-tit-bg">
	  <tr>
	    <th width="10%"><div class="th-gap">序号</div></th>
		<th width="35%"><div class="th-gap">模板文件名</div></th>
		<th width="15%"><div class="th-gap">大小</div></th>
		<th width="25%"><div class="th-gap">最后修改时间</div></th>
		<th><div class="th-gap">选择</div></th>
	  </tr>
	  </thead>
	  <tfoot class="tb-foot-bg"></tfoot>
	  <?php  $_smarty_tpl->tpl_vars['volist'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('templets')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['volist']->key => $_smarty_tpl->tpl_vars['volist']->value){
?>
	  <tr onMouseOver="overColor(this)" onMouseOut="outColor(this)">
	    <td align="center"><?php echo $_smarty_tpl->tpl_vars['volist']->value['i'];?>
</td>
		<td align="left"><?php echo $_smarty_tpl->tpl_vars['volist']->value['filename'];?>
</td>
		<td align="center"><?php echo $_smarty_tpl->tpl_vars['volist']->value['size'];?>
 Bytes</td>
		<td align="center"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['volist']->value['timeline'],"%Y-%m-%d %H:%H:%S");?>
</td>
		<td align="center"><a href="javascript:void(0);" onclick="selecttpl('<?php echo $_smarty_tpl->getVariable('inputid')->value;?>
', '<?php echo $_smarty_tpl->tpl_vars['volist']->value['tplname'];?>
')">选择</a></td>
	  </tr>
	  <?php }} else { ?>
      <tr>
	    <td colspan="5" align="center">对不起，该主题下没有tpl模板，请检查。</td>
	  </tr>
	  <?php } ?>
	</table>
  </div>
</div>
<?php }?>


<?php if ($_smarty_tpl->getVariable('fromtype')->value!='jdbox'){?>
<?php $_smarty_tpl->tpl_vars['pluginevent'] = new Smarty_variable(XHook::doAction('adm_footer'), null, null);?>
<?php }?>
</body>
</html>
<script type="text/javascript">
//编辑验证
function checkform() {
	var t = "";
	var v = "";

	t = "content";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("模板文件内容不能为空", t);
		return false;
	}
	return true;
}
//选择模板文件
function selecttpl(inputid, tplname) {
	window.parent.$('#'+inputid).val(tplname);
	window.parent.tb_remove();
}
</script>
