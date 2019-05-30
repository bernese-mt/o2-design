<?php /* Smarty version Smarty-3.0.5, created on 2019-05-07 15:40:25
         compiled from "/home/o2design/public_html/OECMS/tpl/admincp/frm_footer.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1260130115cd1366914b0e9-25325967%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ed1dc24fb4f128ac5665671b8fddd3e317c113a6' => 
    array (
      0 => '/home/o2design/public_html/OECMS/tpl/admincp/frm_footer.tpl',
      1 => 1557211189,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1260130115cd1366914b0e9-25325967',
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
<title>footer</title>
<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->getVariable('cppath')->value;?>
css/footer.css" />
<?php $_template = new Smarty_Internal_Template(($_smarty_tpl->getVariable('cptplpath')->value)."headerjs.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>
</head>
<body>
<div class="footer">
<table width="98%" border="0" cellpadding="0" cellspacing="0" align="left">
  <tr>
	<td>快捷操作：
	<a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category" target="main">栏目设置</a>&nbsp;|&nbsp;
	<a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=module" target="main">模型设置</a>&nbsp;|&nbsp;
	<a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=admin&a=editpassword" target="main">修改密码</a>&nbsp;|&nbsp;
    <a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=login&a=logout" target='_top'>退出登录</a>&nbsp;|&nbsp;
	<a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=setting&a=clearcache" target='main'>清除页面缓存</a>&nbsp;|&nbsp;
	<a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=setting&a=updatecache" target='main'>更新数据缓存</a>
	</td>
	<td align="right" style="padding-right:10px;"><?php echo $_smarty_tpl->getVariable('copyright_poweredby')->value;?>
 Version <?php echo $_smarty_tpl->getVariable('copyright_version')->value;?>
<?php echo $_smarty_tpl->getVariable('copyright_release')->value;?>
</td>
  </tr>
</table>
</div>	
</body>
</html>