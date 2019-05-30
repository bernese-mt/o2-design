<?php /* Smarty version Smarty-3.0.5, created on 2019-05-07 15:40:24
         compiled from "/home/o2design/public_html/OECMS/tpl/admincp/frm_top.tpl" */ ?>
<?php /*%%SmartyHeaderCode:97283315cd13668cd3985-29772446%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '02c3976fe0de16d7c489a8702f178b3c1ec26cc3' => 
    array (
      0 => '/home/o2design/public_html/OECMS/tpl/admincp/frm_top.tpl',
      1 => 1557211178,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '97283315cd13668cd3985-29772446',
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
<title>top</title>
<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->getVariable('cppath')->value;?>
css/top.css" />
<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('urlpath')->value;?>
tpl/static/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('cppath')->value;?>
js/top.js"></script>
<!--[if lte IE 6]>
<script type='text/javascript' src='<?php echo $_smarty_tpl->getVariable('urlpath')->value;?>
tpl/staticjs/DD_belatedPNG-min.js'></script>
<script language="javascript">DD_belatedPNG.fix('.logo');</script>
<![endif]-->
</head>
<body>
<div id="top">
  <div class="logo"></div>
  <div id="navs">
	<ul>
	  <li><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=frame&a=left&mod=setting">系统设置</a></li>
	  <li><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=frame&a=left&mod=content">模块管理</a></li>
	  <li><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=frame&a=left&mod=skin">界面模板</a></li>
	  <li><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=frame&a=left&mod=app">插件&应用</a></li>
	</ul>  
  </div>
  <div id="right">
    <p>
	欢迎回来：<?php echo $_smarty_tpl->getVariable('adminname')->value;?>
&nbsp;&nbsp;<font color="#999999">|</font>&nbsp;&nbsp;
	<a href="index.php" target="_blank">网站首页</a>&nbsp;&nbsp;<font color="#999999">|</font>&nbsp;&nbsp;
	<a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=login&a=logout" target='_top'>退出登录</a>&nbsp;&nbsp;<font color="#999999">|</font>&nbsp;&nbsp;
	<a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=setting&a=clearcache" target='main'>清除页面缓存</a>&nbsp;&nbsp;<font color="#999999">|</font>&nbsp;&nbsp;
	<a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=setting&a=updatecache" target='main'>更新数据缓存</a>

	</p>
  </div>
  <div style="clear:both;"></div>
</div>
</body>
</html>