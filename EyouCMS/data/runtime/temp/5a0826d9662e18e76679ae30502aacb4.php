<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:30:"./template/pc/lists_single.htm";i:1557461013;s:57:"/home/o2design/public_html/EyouCMS/template/pc/header.htm";i:1557303003;s:57:"/home/o2design/public_html/EyouCMS/template/pc/footer.htm";i:1557302387;}*/ ?>
<!DOCTYPE HTML>
<html debug="true" lang="zh_tw">
<head>
<meta charset="utf-8">
<title><?php echo $eyou['field']['seo_title']; ?></title>
<meta property="og:title" content="<?php echo $eyou['field']['seo_title']; ?>">
<meta property="og:site_name" content="<?php echo $eyou['field']['seo_title']; ?>">
<meta property="og:url" content="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_basehost"); echo $__VALUE__; ?>">
<meta property="og:type" content="website">
<meta name="description" content="<?php echo $eyou['field']['seo_description']; ?>">
<meta name="keywords" content="<?php echo $eyou['field']['seo_keywords']; ?>">

<!--CSS-->
<link rel="stylesheet" type="text/css" href="template/pc/css/master.css">
<!--main nav-->
<link rel="stylesheet" type="text/css" href="template/pc/function/bootsnav/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="template/pc/function/bootsnav/css/bootsnav.css">

<link rel="stylesheet" type="text/css" href="template/pc/css/article.css">

<!--手機解析度-->
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!--favor icon-->
<link rel="shortcut icon" href="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_cmspath"); echo $__VALUE__; ?>/favicon.ico" />

<!--google_font-->
<link href="https://fonts.googleapis.com/css?family=Lalezar" rel="stylesheet">  

<script type="text/javascript" src="template/pc/Scripts/jquery/jquery-2.1.1.min.js"></script>
<!--main nav-->
<script src="template/pc/function/bootsnav/bootsnav.js"></script>

<!-- IE Fix for HTML5 Tags -->
<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
</head> 
 <body>
 	<div class="page_Wrap">
 		<!--header-s-->
 		<header>
	<div class="top_link">
 		<div class="content">
 	   		<h2><?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_recordnum"); echo $__VALUE__; ?></h2>
 	   	    <ul>
 	   	    	<li><a href="#" target="_blank"><i class="fab fa-facebook-f"></i> 協會粉絲團</a></li>
 	   	        <li><a href="https://enews.url.com.tw/TPMA/69724" target="_blank"><i class="far fa-newspaper"></i> 物業網報</a></li>
 	   	    </ul>
 	   	</div>
  	</div>
  	<div class="logo_wrap">
  		<div class="cis">
	   		<a href="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_cmsurl"); echo $__VALUE__; ?>/" title="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_name"); echo $__VALUE__; ?>"><img src="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_logo"); echo $__VALUE__; ?>" alt="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_name"); echo $__VALUE__; ?>" /></a> 
 	   	</div>
  	   	<div class="info_wrap">
  	   		<ul>
  	   	    	<li>
  	   	        	<i class="fas fa-map-marker-alt"></i>
  	   	            <div class="info_item">
  	   	            	<strong>協會位址.</strong>
  	   	                <p><?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_attr_1"); echo $__VALUE__; ?></p>
   	   	            </div>
  	   	        </li>
  	   	        <li>
  	   	        	<i class="fas fa-globe-asia"></i>
  	   	            <div class="info_item">
  	   	            	<strong><?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_attr_2"); echo $__VALUE__; ?></strong>
  	   	                <p><a href="mailto:<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_attr_3"); echo $__VALUE__; ?>"><?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_attr_3"); echo $__VALUE__; ?></a></p>
  	   	            </div>
  	   	        </li>
  	   	        <li>
  	   	        	<i class="far fa-clock"></i>
  	   	            <div class="info_item">
  	   	            	<strong>服務時間.</strong>
  	   	                <p><?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_attr_9"); echo $__VALUE__; ?></p>
  	   	            </div>
  	   	    	</li>
  	   		</ul>
  		</div>
  	</div>
  	<!--menuBox-->
	<nav class="manu_box navbar navbar-default navbar-mobile bootsnav">
    	<div class="navbar-header">
        	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
            	<i class="fa fa-bars"></i>
            </button>
        </div>
        <div class="collapse navbar-collapse" id="navbar-menu">
        	<ul class="nav navbar-nav" data-in="fadeInDown" data-out="fadeOutUp">
        		<?php  if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  if(isset($ui_row) && !empty($ui_row)) : $row = $ui_row; else: $row = 60; endif; $tagChannel = new \think\template\taglib\eyou\TagChannel; $_result = $tagChannel->getChannel($typeid, "top", "active"); if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $e = 1;$__LIST__ = is_array($_result) ? array_slice($_result,0, $row, true) : $_result->slice(0, $row, true); if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$field): $field["typename"] = text_msubstr($field["typename"], 0, 100, false); $__LIST__[$key] = $_result[$key] = $field;$mod = ($e % 2 );$i= intval($key) + 1;?>
        		<li class="dropdown">
        			<a href="<?php echo $field['typeurl']; ?>"> <?php echo $field['typename']; if(!(empty($field['children']) || (($field['children'] instanceof \think\Collection || $field['children'] instanceof \think\Paginator ) && $field['children']->isEmpty()))): endif; ?></a>
        			<?php if(!(empty($field['children']) || (($field['children'] instanceof \think\Collection || $field['children'] instanceof \think\Paginator ) && $field['children']->isEmpty()))): ?>
        			<ul class="dropdown-menu">
        				<?php  if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  if(isset($ui_row) && !empty($ui_row)) : $row = $ui_row; else: $row = 100; endif;if(is_array($field['children']) || $field['children'] instanceof \think\Collection || $field['children'] instanceof \think\Paginator): $i = 0; $e = 1;$__LIST__ = is_array($field['children']) ? array_slice($field['children'],0,100, true) : $field['children']->slice(0,100, true); if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$field2): $field2["typename"] = text_msubstr($field2["typename"], 0, 100, false); $__LIST__[$key] = $_result[$key] = $field2;$mod = ($e % 2 );$i= intval($key) + 1;?>
        				<li>
        					<a href="<?php echo $field2['typeurl']; ?>"><?php echo $field2['typename']; ?></a>
        					<?php if(!(empty($field2['children']) || (($field2['children'] instanceof \think\Collection || $field2['children'] instanceof \think\Paginator ) && $field2['children']->isEmpty()))): ?>
        					<ul class="dropdown-menu">
        						<?php  if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  if(isset($ui_row) && !empty($ui_row)) : $row = $ui_row; else: $row = 100; endif;if(is_array($field2['children']) || $field2['children'] instanceof \think\Collection || $field2['children'] instanceof \think\Paginator): $i = 0; $e = 1;$__LIST__ = is_array($field2['children']) ? array_slice($field2['children'],0,100, true) : $field2['children']->slice(0,100, true); if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$field3): $field3["typename"] = text_msubstr($field3["typename"], 0, 100, false); $__LIST__[$key] = $_result[$key] = $field3;$mod = ($e % 2 );$i= intval($key) + 1;?>
        						<li><a href="<?php echo $field3['typeurl']; ?>"><?php echo $field3['typename']; ?></a></li>
        						<?php ++$e; endforeach; endif; else: echo htmlspecialchars_decode("");endif; ?>
							</ul>
         					<?php endif; ?>
						</li>
         				<?php ++$e; endforeach; endif; else: echo htmlspecialchars_decode("");endif; ?>
					</ul>
        			<?php endif; ?>
        		</li>
        		<?php ++$e; endforeach; endif; else: echo htmlspecialchars_decode("");endif; ?>
        	</ul>
		</div>
  	</nav>
</header>
 		<!--header-e-->
 		<!--banner-->
 		<div class="banner">
 			<div class="banner_wrap">
 				<div class="inPageTitle">
					<p>About</p>
					<h2>協會介紹</h2>
				</div>
			</div>
 		</div>
		<!--banner end-->
		<!--mainBox-->
		<div class="main_wrap">
			<!--bread-->
			<div class="bread"> <?php  $typeid = ""; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  $tagPosition = new \think\template\taglib\eyou\TagPosition; $__VALUE__ = $tagPosition->getPosition($typeid, "", "crumb"); echo $__VALUE__; ?></div>
			<!--bread end-->
			<div class="side_menu">
				<ul>
					<?php  if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  if(isset($ui_row) && !empty($ui_row)) : $row = $ui_row; else: $row = 10; endif; $tagChannel = new \think\template\taglib\eyou\TagChannel; $_result = $tagChannel->getChannel($typeid, "sonself", "active"); if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $e = 1;$__LIST__ = is_array($_result) ? array_slice($_result,0, $row, true) : $_result->slice(0, $row, true); if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$field): $field["typename"] = text_msubstr($field["typename"], 0, 100, false); $__LIST__[$key] = $_result[$key] = $field;$mod = ($e % 2 );$i= intval($key) + 1;?>
					<li><a href="<?php echo $field['typeurl']; ?>" title="<?php echo $field['typename']; ?>" class="<?php echo $field['currentstyle']; ?>"><?php echo $field['typename']; ?></a></li>
					<?php ++$e; endforeach; endif; else: echo htmlspecialchars_decode("");endif; ?>
				</ul>
			</div>
			<h1 class="articleTitle"><?php echo $eyou['field']['typename']; ?></h1>
			<div class="share">
				<b>Share：</b>
				<a href="javascript: void(window.open('http://www.facebook.com/share.php?u='.concat(encodeURIComponent(location.href)) ));"><i class="fab fa-facebook-square"></i></a>
				<a href="javascript: void(window.open('http://twitter.com/home/?status='.concat(encodeURIComponent(document.title)) .concat(' ') .concat(encodeURIComponent(location.href))));"><i class="fab fa-twitter"></i></a>
				<a class="gPlus" href="javascript: void(window.open('https://plus.google.com/share?url='.concat(encodeURIComponent(location.href))));" target="_blank"><i class="fab fa-google-plus-g"></i></a>
				<a href="javascript:(function(){window.open('http://v.t.sina.com.cn/share/share.php?title='+encodeURIComponent(document.title)+'&amp;url='+encodeURIComponent(location.href)+'&amp;source=bookmark','_blank','');})()"><i class="fab fa-weibo"></i></a>
				<a class="line" href="javascript: void(window.open('http://line.naver.jp/R/msg/text/?'.concat(encodeURIComponent(location.href)) ));" title="Line"><i class="fab fa-line"></i></a>
			</div>
			<div class="main_article">
				<?php echo $eyou['field']['content']; ?>
			</div>
		</div>
		<!--mainBox end-->
		<!--footer-s-->
		<footer>
	<section class="footer_wrap">
		<div class="footerInfo">
			<div class="logo">
				<a href="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_cmsurl"); echo $__VALUE__; ?>/" title="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_name"); echo $__VALUE__; ?>"><img src="template/pc/images/footer-logo.png" /></a>
			</div>
			<div class="infoBox">
				<p><span>地&nbsp;&nbsp;&nbsp;址：</span><?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_attr_1"); echo $__VALUE__; ?></p>
				<p><span>電&nbsp;&nbsp;&nbsp;話：</span><?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_attr_2"); echo $__VALUE__; ?></p>
				<p><span>傳&nbsp;&nbsp;&nbsp;真：</span><?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_attr_11"); echo $__VALUE__; ?></p>
				<p><span>E-mail：</span><?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_attr_3"); echo $__VALUE__; ?></p>
			</div>
		</div>
		<div class="copyright">
			 <?php  $tagUitext = new \think\template\taglib\eyou\TagUitext; $__LIST__ = $tagUitext->getUitext("web_copyright", "footer"); if((is_array($__LIST__)) && (!empty($__LIST__["value"]) || (($__LIST__["value"] instanceof \think\Collection || $__LIST__["value"] instanceof \think\Paginator ) && $__LIST__["value"]->isEmpty()))): $field = $__LIST__; ?>
         	<?php echo $field['value']; else:  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_copyright"); echo $__VALUE__; endif; ?>
		</div>
	</section>
</footer>
		<!--footer-e-->
	</div>
 </body>
</html>