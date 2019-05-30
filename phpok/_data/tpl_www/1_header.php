<?php if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");} ?><!DOCTYPE HTML>
<html debug="true" lang="zh_tw">
<head>
<meta charset="utf-8">
<title><?php if($title){ ?><?php echo $title;?>_<?php } ?><?php if($seo['title']){ ?><?php echo $seo['title'];?>_<?php } ?><?php echo $config['title'];?></title>
<meta property="og:title" content="<?php if($title){ ?><?php echo $title;?>_<?php } ?><?php if($seo['title']){ ?><?php echo $seo['title'];?>_<?php } ?><?php echo $config['title'];?>">
<meta property="og:site_name" content="<?php if($title){ ?><?php echo $title;?>_<?php } ?><?php if($seo['title']){ ?><?php echo $seo['title'];?>_<?php } ?><?php echo $config['title'];?>">
<meta property="og:url" content="<?php echo $sys['url'];?>">
<meta property="og:type" content="website">
<?php if($seo['description']){ ?><meta name="description" content="<?php echo $seo['description'];?>"><?php } ?>
<?php if($seo['keywords']){ ?><meta name="keywords" content="<?php echo $seo['keywords'];?>"><?php } ?>

<!--CSS-->
<link rel="stylesheet" type="text/css" href="tpl/www/css/master.css">
<!--main nav-->
<link rel="stylesheet" type="text/css" href="tpl/www/function/bootsnav/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="tpl/www/function/bootsnav/bootsnav.css">

<!--手機解析度-->
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php if($config['favicon']){ ?><!--favor icon-->
<link rel="shortcut icon" href="<?php echo $config['favicon'];?>" />
<?php } ?>
<!--google_font-->
<link href="https://fonts.googleapis.com/css?family=Lalezar" rel="stylesheet">  

<script type="text/javascript" src="Scripts/jquery/jquery-2.1.1.min.js"></script>
<!--main nav-->
<script src="function/bootsnav/bootsnav.js"></script>

<!-- IE Fix for HTML5 Tags -->
<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<?php echo $app->plugin_html_ap("phpokhead");?></head>
<body>
	<div class="page_Wrap">
