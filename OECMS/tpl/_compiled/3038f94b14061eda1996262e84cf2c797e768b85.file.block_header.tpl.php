<?php /* Smarty version Smarty-3.0.5, created on 2019-05-07 15:41:19
         compiled from "/home/o2design/public_html/OECMS/tpl/templets/default/block_header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:5969842055cd1369f8111c9-44448315%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3038f94b14061eda1996262e84cf2c797e768b85' => 
    array (
      0 => '/home/o2design/public_html/OECMS/tpl/templets/default/block_header.tpl',
      1 => 1557211258,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5969842055cd1369f8111c9-44448315',
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
<title><?php echo $_smarty_tpl->getVariable('page_title')->value;?>
</title>
<meta name="description" content="<?php echo $_smarty_tpl->getVariable('page_description')->value;?>
" />
<meta name="keywords" content="<?php echo $_smarty_tpl->getVariable('page_keyword')->value;?>
" />
<meta name="author" content="OEdev" />
<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->getVariable('skinpath')->value;?>
style/main.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->getVariable('skinpath')->value;?>
style/css.css" />
<script type='text/javascript' src='<?php echo $_smarty_tpl->getVariable('urlpath')->value;?>
tpl/static/js/jquery.min.js'></script>
<script type='text/javascript' src='<?php echo $_smarty_tpl->getVariable('urlpath')->value;?>
tpl/static/js/public.js'></script>
<script src="<?php echo $_smarty_tpl->getVariable('skinpath')->value;?>
js/downnav.js" language="javascript" type="text/javascript"></script>
<!--[if IE 6]>
<script src="<?php echo $_smarty_tpl->getVariable('urlpath')->value;?>
tpl/static/js/DD_belatedPNG-min.js" language="javascript" type="text/javascript"></script>
<script type="text/javascript">
DD_belatedPNG.fix('.bg,img'); 
</script>
<![endif]-->
<?php $_smarty_tpl->tpl_vars['pluginevent'] = new Smarty_variable(XHook::doAction('index_head'), null, null);?>
</head>
<body>
