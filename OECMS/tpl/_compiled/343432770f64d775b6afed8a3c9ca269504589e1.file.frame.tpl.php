<?php /* Smarty version Smarty-3.0.5, created on 2019-05-07 15:40:24
         compiled from "/home/o2design/public_html/OECMS/tpl/admincp/frame.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4758616735cd136683c60a7-40448299%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '343432770f64d775b6afed8a3c9ca269504589e1' => 
    array (
      0 => '/home/o2design/public_html/OECMS/tpl/admincp/frame.tpl',
      1 => 1557211191,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4758616735cd136683c60a7-40448299',
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
<title>管理中心-[<?php echo $_smarty_tpl->getVariable('config')->value['sitename'];?>
]</title>
<frameset frameborder=10 framespacing=0 border=0 rows="70, *,32">
<frame src="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=frame&a=top" name="top" frameborder=0 NORESIZE SCROLLING='no' marginwidth=0 marginheight=0>
<frameset frameborder=0  framespacing=0 border=0 cols="170,7, *" id="frame-body">
<frame src="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=frame&a=left" frameborder=0 id="menu-frame" name="menu" scrolling="auto" marginwidth="0">
<frame src="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=frame&a=drag" id="drag-frame" name="drag-frame" frameborder="no" scrolling="no">
<frame src="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=frame&a=main" frameborder=0 id="main-frame" name="main">
</frameset>
<frame src="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=frame&a=footer" name="footer" frameborder=0 NORESIZE SCROLLING='no' marginwidth=0 marginheight=0>
</frameset><noframes></noframes>
</html>