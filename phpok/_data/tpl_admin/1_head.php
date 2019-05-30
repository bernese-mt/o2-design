<?php if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");} ?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="author" content="phpok.com" />
	<meta http-equiv="Expires" content="wed, 26 feb 1997 08:21:57 GMT" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Cache-Control" content="no-cache,no-store,must-revalidate" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title><?php if($title){ ?><?php echo $title;?> - <?php } ?><?php echo $site_info['title'] ? $site_info['title'] : $config['title'];?>_<?php echo P_Lang("后台管理");?></title>
	<?php if($config['favicon']){ ?>
	<link rel="shortcut icon" href="<?php echo $config['favicon'];?>" />
	<?php } ?>
	<link rel="stylesheet" type="text/css" href="css/css.php?type=admin&version=<?php echo VERSION;?>" />
	<?php echo phpok_head_css();?>
	<?php $js_ext = 'admin.'.$sys['ctrl'].'.js,admin.'.$sys['ctrl'].'-'.$sys['func'].'.js';?>
	<script type="text/javascript" src="<?php echo phpok_url(array('ctrl'=>'js','ext'=>$js_ext));?>"></script>
	<script type="text/javascript" src="js/laydate/laydate.js"></script>
	<?php echo phpok_head_js();?>
	<?php echo $app->plugin_html_ap("head");?>
<?php echo $app->plugin_html_ap("phpokhead");?></head>
<body ondragstart="return false;">
<div class="main">
