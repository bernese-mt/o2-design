<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:23:"./template/pc/index.htm";i:1557416050;s:57:"/home/o2design/public_html/EyouCMS/template/pc/header.htm";i:1557303003;s:57:"/home/o2design/public_html/EyouCMS/template/pc/footer.htm";i:1557302387;}*/ ?>
<!DOCTYPE HTML>
<html debug="true" lang="zh_tw">
<head>
<meta charset="utf-8">
<title><?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_title"); echo $__VALUE__; ?></title>
<meta property="og:title" content="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_title"); echo $__VALUE__; ?>">
<meta property="og:site_name" content="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_title"); echo $__VALUE__; ?>">
<meta property="og:url" content="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_basehost"); echo $__VALUE__; ?>">
<meta property="og:type" content="website">
<meta name="description" content="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_description"); echo $__VALUE__; ?>">
<meta name="keywords" content="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_keywords"); echo $__VALUE__; ?>">

<!--CSS-->
<link rel="stylesheet" type="text/css" href="template/pc/css/master.css">
<!--main nav-->
<link rel="stylesheet" type="text/css" href="template/pc/function/bootsnav/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="template/pc/function/bootsnav/css/bootsnav.css">

<link rel="stylesheet" type="text/css" href="template/pc/css/home.css">

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
		<div class="banner">
			<?php  $tagAdv = new \think\template\taglib\eyou\TagAdv; $_result = $tagAdv->getAdv(1, "", ""); if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $e = 1;$__LIST__ = is_array($_result) ? array_slice($_result,0, 10, true) : $_result->slice(0, 10, true); if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$field):  if ($i == 0) : $field["currentstyle"] = "active"; else:  $field["currentstyle"] = ""; endif;$mod = ($e % 2 );$i= intval($key) + 1;?><a href="<?php echo $field['links']; ?>" title="<?php echo $field['title']; ?>" <?php echo $field['target']; ?>><img src="<?php echo $field['litpic']; ?>" /></a>
			<?php ++$e; endforeach; endif; else: echo htmlspecialchars_decode("");endif; ?>
		</div>
		<div class="main_wrap">
			<section class="news_wrap">
				<div class="news_container">
					<!--news-->
					<div class="newsBox">
						<div class="titleBox" data-num="01">
							<span>Association</span>
							<b>Announcement.</b>
							<h2>協會公告</h2>
						</div>
						<ul class="newsList">
							 <?php  $tagUiarclist = new \think\template\taglib\eyou\TagUiarclist; $__LIST__ = $tagUiarclist->getUiarclist("2","203", "index"); if((is_array($__LIST__)) && (!empty($__LIST__["info"]) || (($__LIST__["info"] instanceof \think\Collection || $__LIST__["info"] instanceof \think\Paginator ) && $__LIST__["info"]->isEmpty()))): $field = $__LIST__;  $ui_typeid = !empty($field["info"]["typeid"]) ? $field["info"]["typeid"] : ""; if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  if(isset($ui_row) && !empty($ui_row)) : $row = $ui_row; else: $row = 12; endif; $channeltype = ""; $param = array(      "typeid"=> $typeid,      "notypeid"=> "",      "flag"=> "",      "noflag"=> "",      "channel"=> $channeltype, ); $tag = array (
  'row' => '12',
  'titlelen' => '30',
  'infolen' => '160',
); $tagArclist = new \think\template\taglib\eyou\TagArclist; $_result = $tagArclist->getArclist($param, $row, "", "","desc","",$tag,"0");if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $e = 1;$__LIST__ = is_array($_result) ? array_slice($_result,0, $row, true) : $_result->slice(0, $row, true); if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$field): $aid = $field["aid"];$field["title"] = text_msubstr($field["title"], 0, 30, false);$field["seo_description"] = text_msubstr($field["seo_description"], 0, 160, true);$mod = ($e % 2 );$i= intval($key) + 1;?>
           					<li>
           						<div class="dateBox"><?php echo MyDate('Y-m-d',$field['add_time']); ?></div>
           						<div class="newsSort"><?php echo $field['typename']; ?></div>
           						<div class="newsTitle"><a href="<?php echo $field['arcurl']; ?>" title="<?php echo $field['title']; ?>" target="_self"> <?php echo $field['title']; ?> </a></div>
            				</li>
            				<?php ++$e; $aid = 0; endforeach; endif; else: echo htmlspecialchars_decode("");endif; $ui_typeid = $ui_row = ""; endif; ?>
						</ul>
					</div>
					<!--news end-->
					<!--Newsletter-->
					<div class="newsletterBox">
						<?php  $tagAdv = new \think\template\taglib\eyou\TagAdv; $_result = $tagAdv->getAdv(3, "", ""); if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $e = 1;$__LIST__ = is_array($_result) ? array_slice($_result,0, 10, true) : $_result->slice(0, 10, true); if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$field):  if ($i == 0) : $field["currentstyle"] = "active"; else:  $field["currentstyle"] = ""; endif;$mod = ($e % 2 );$i= intval($key) + 1;?>
						<a href="<?php echo $field['links']; ?>" title="<?php echo $field['title']; ?>" <?php echo $field['target']; ?>>
							<img src='<?php echo $field['litpic']; ?>' alt='<?php echo $field['title']; ?>' />
						</a>
						<?php ++$e; endforeach; endif; else: echo htmlspecialchars_decode("");endif; ?>
					</div>
				</div>
			</section>
			<section class="course_wrap">
				<div class="course_container">
					<div class="titleBox" data-num="02">
						<span>Information about</span>
						<b>Course.</b>
						<h2>課程資訊</h2>
					</div>
					<ul class="courseList">
						 <?php  $tagUiarclist = new \think\template\taglib\eyou\TagUiarclist; $__LIST__ = $tagUiarclist->getUiarclist("3","203", "index"); if((is_array($__LIST__)) && (!empty($__LIST__["info"]) || (($__LIST__["info"] instanceof \think\Collection || $__LIST__["info"] instanceof \think\Paginator ) && $__LIST__["info"]->isEmpty()))): $field = $__LIST__;  $ui_typeid = !empty($field["info"]["typeid"]) ? $field["info"]["typeid"] : ""; if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  if(isset($ui_row) && !empty($ui_row)) : $row = $ui_row; else: $row = 4; endif; $channeltype = ""; $param = array(      "typeid"=> $typeid,      "notypeid"=> "",      "flag"=> "",      "noflag"=> "",      "channel"=> $channeltype, ); $tag = array (
  'row' => '4',
  'titlelen' => '40',
  'infolen' => '40',
); $tagArclist = new \think\template\taglib\eyou\TagArclist; $_result = $tagArclist->getArclist($param, $row, "", "","desc","",$tag,"0");if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $e = 1;$__LIST__ = is_array($_result) ? array_slice($_result,0, $row, true) : $_result->slice(0, $row, true); if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$field): $aid = $field["aid"];$field["title"] = text_msubstr($field["title"], 0, 40, false);$field["seo_description"] = text_msubstr($field["seo_description"], 0, 40, true);$mod = ($e % 2 );$i= intval($key) + 1;?>
           				<li>
           					<div class="courseSort"><?php echo $field['typename']; ?></div>
         					<div class="item">
           						<div class="Class_date"><i class="far fa-calendar-alt"></i> <?php  $tagField = new \think\template\taglib\eyou\TagField; $__VALUE__ = $tagField->getField("Class_date", $field['aid']); echo $__VALUE__; ?></div>
								<h3><a href="<?php echo $field['arcurl']; ?>" title="<?php echo $field['title']; ?>" target="_self"><?php echo $field['title']; ?></a></h3>
           						<p class="ellipsis"><?php  $tagField = new \think\template\taglib\eyou\TagField; $__VALUE__ = $tagField->getField("description", $field['aid']); echo $__VALUE__; ?></p>
								<div class="moreBox"><a href="<?php echo $field['arcurl']; ?>" title="<?php echo $field['title']; ?>" target="_self">See more</a></div>
   							</div>
           				</li>
           				<?php ++$e; $aid = 0; endforeach; endif; else: echo htmlspecialchars_decode("");endif; $ui_typeid = $ui_row = ""; endif; ?>
					</ul>
				</div>
				<img class="parallaxImg" src="template/pc/images/linePoint.png" />
			</section>
			<section class="facebook_wrap">
				<div class="facebook_container">
					<div class="titleBox" data-num="03">
						<h2>Follow Us</h2>
						<p>Taiwan Property <br>Management Association</p>
					</div>
					<ul class="facebookBox">
						<li>
							<div class="fb-page" data-href="https://www.facebook.com/%E8%82%B2%E6%88%90%E7%89%A9%E6%A5%AD%E7%AE%A1%E7%90%86%E7%A4%BE%E5%9C%98%E6%B3%95%E4%BA%BA%E5%8F%B0%E7%81%A3%E7%89%A9%E6%A5%AD%E7%AE%A1%E7%90%86%E7%94%A2%E6%A5%AD%E5%8D%94%E6%9C%83%E7%8E%8B%E6%96%87%E6%A5%B7%E5%BB%BA%E7%AF%89%E5%B8%AB%E4%BA%8B%E5%8B%99%E6%89%80-1927886114126522/?ref=bookmarks" data-tabs="timeline" data-width="" data-height="" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/%E8%82%B2%E6%88%90%E7%89%A9%E6%A5%AD%E7%AE%A1%E7%90%86%E7%A4%BE%E5%9C%98%E6%B3%95%E4%BA%BA%E5%8F%B0%E7%81%A3%E7%89%A9%E6%A5%AD%E7%AE%A1%E7%90%86%E7%94%A2%E6%A5%AD%E5%8D%94%E6%9C%83%E7%8E%8B%E6%96%87%E6%A5%B7%E5%BB%BA%E7%AF%89%E5%B8%AB%E4%BA%8B%E5%8B%99%E6%89%80-1927886114126522/?ref=bookmarks" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/%E8%82%B2%E6%88%90%E7%89%A9%E6%A5%AD%E7%AE%A1%E7%90%86%E7%A4%BE%E5%9C%98%E6%B3%95%E4%BA%BA%E5%8F%B0%E7%81%A3%E7%89%A9%E6%A5%AD%E7%AE%A1%E7%90%86%E7%94%A2%E6%A5%AD%E5%8D%94%E6%9C%83%E7%8E%8B%E6%96%87%E6%A5%B7%E5%BB%BA%E7%AF%89%E5%B8%AB%E4%BA%8B%E5%8B%99%E6%89%80-1927886114126522/?ref=bookmarks">育成物業管理/社團法人台灣物業管理產業協會/王文楷建築師事務所</a></blockquote></div>
						</li>
						<li>
							<div class="fb-page" data-href="https://www.facebook.com/%E7%8E%8B%E6%96%87%E6%A5%B7%E5%BB%BA%E7%AF%89%E5%B8%AB%E4%BA%8B%E5%8B%99%E6%89%80-335267630440946/" data-tabs="timeline" data-width="" data-height="" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/%E7%8E%8B%E6%96%87%E6%A5%B7%E5%BB%BA%E7%AF%89%E5%B8%AB%E4%BA%8B%E5%8B%99%E6%89%80-335267630440946/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/%E7%8E%8B%E6%96%87%E6%A5%B7%E5%BB%BA%E7%AF%89%E5%B8%AB%E4%BA%8B%E5%8B%99%E6%89%80-335267630440946/">王文楷建築師事務所</a></blockquote></div>
						</li>
						<li>
							<div class="fb-page" data-href="https://www.facebook.com/%E8%82%B2%E6%88%90%E7%89%A9%E6%A5%AD%E7%AE%A1%E7%90%86%E9%A1%A7%E5%95%8F%E6%9C%89%E9%99%90%E5%85%AC%E5%8F%B8-2285294741741914/" data-tabs="timeline" data-width="" data-height="" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/%E8%82%B2%E6%88%90%E7%89%A9%E6%A5%AD%E7%AE%A1%E7%90%86%E9%A1%A7%E5%95%8F%E6%9C%89%E9%99%90%E5%85%AC%E5%8F%B8-2285294741741914/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/%E8%82%B2%E6%88%90%E7%89%A9%E6%A5%AD%E7%AE%A1%E7%90%86%E9%A1%A7%E5%95%8F%E6%9C%89%E9%99%90%E5%85%AC%E5%8F%B8-2285294741741914/">育成物業管理顧問有限公司</a></blockquote></div>
						</li>
					</ul>
				</div>
			</section>
		</div>
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
<link rel="stylesheet" type="text/css" href="template/pc/Scripts/slick/slick.css">
<link rel="stylesheet" type="text/css" href="template/pc/Scripts/slick/slick-theme.css">
<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="template/pc/Scripts/slick/slick.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		jQuery(".banner").slick({
			arrows: true,
			dots: true,             //顯示輪播圖片會顯示圓圈
			infinite: true,         //重覆輪播
			slidesToShow:1,         //輪播顯示個數
			slidesToScroll: 1,      //輪播捲動個數
			autoplay: true          //autoplay : 自動播放 
		});
		jQuery(".newsList").slick({
			arrows: false,
			dots: true,             //顯示輪播圖片會顯示圓圈
			infinite: true,         //重覆輪播
			slidesToShow:4,         //輪播顯示個數
			slidesToScroll: 4,      //輪播捲動個數
			autoplay: true,         //autoplay : 自動播放 
            vertical: true
		});
		jQuery(".newsletterBox").slick({
			arrows: false,
			dots: false,            //顯示輪播圖片會顯示圓圈
			infinite: true,         //重覆輪播
			slidesToShow:1,         //輪播顯示個數
			slidesToScroll: 1,      //輪播捲動個數
			autoplay: true          //autoplay : 自動播放 
		});
		jQuery(".courseList").slick({
			arrows: false,
			dots: true,             //顯示輪播圖片會顯示圓圈
			infinite: true,         //重覆輪播
			slidesToShow:3,         //輪播顯示個數
			slidesToScroll: 1,      //輪播捲動個數
			autoplay: true,         //autoplay : 自動播放
			vertical: true
		});
	});
</script>

<script type="text/javascript" src="template/pc/Scripts/jquery.dotdotdot-1.5.3.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('.ellipsis').dotdotdot({
			 wrap: 'letter',
			 remove: [ ' ', ',', ';', '.', '!', '?' ]
		});
	});
</script>

<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/zh_TW/sdk.js#xfbml=1&version=v3.3"></script>
</body>
</html>