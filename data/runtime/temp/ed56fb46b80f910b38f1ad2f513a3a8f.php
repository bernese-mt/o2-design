<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:23:"./template/pc/index.htm";i:1559183111;s:49:"/home/o2design/public_html/template/pc/header.htm";i:1559112671;s:49:"/home/o2design/public_html/template/pc/footer.htm";i:1559113908;}*/ ?>
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

<!--fakeLoader-->
<link rel="stylesheet" type="text/css" href="template/pc/Scripts/fakeLoader/css/fakeLoader.min.css">

<!--CSS-->
<link rel="stylesheet" type="text/css" href="template/pc/css/master.css">

<link rel="stylesheet" type="text/css" href="template/pc/css/home.css">

<!--手機解析度-->
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!--favor icon-->
<link rel="shortcut icon" href="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_cmspath"); echo $__VALUE__; ?>/favicon.ico" />

<!--google_font-->
<link href="https://fonts.googleapis.com/css?family=Lalezar" rel="stylesheet">  

<script type="text/javascript" src="template/pc/Scripts/jquery/jquery-1.11.0.min.js"></script>

<!--main nav-->
<link rel="stylesheet" type="text/css" href="template/pc/Scripts/bootsnav/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="template/pc/Scripts/bootsnav/css/bootsnav.css">
<script src="template/pc/Scripts/bootsnav/bootstrap.min.js"></script>
<script src="template/pc/Scripts/bootsnav/bootsnav.js"></script>

<!-- IE Fix for HTML5 Tags -->
<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
</head>

<body>
	<div class="page_wrap">
		<!--header-s-->
		<header>
	<section class="container">
		<div class="logo">
			<h1><a title="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_name"); echo $__VALUE__; ?>" href="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_cmsurl"); echo $__VALUE__; ?>/"><img src="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_logo"); echo $__VALUE__; ?>" alt="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_name"); echo $__VALUE__; ?>" /></a></h1>
		</div>
		<!--menuBox-->
		<nav class="navbar navbar-default navbar-mobile bootsnav">
        	<div class="navbar-header">
            	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
	            	<i class="fa fa-bars"></i>
	            </button>
	        </div>
	        <div class="collapse navbar-collapse" id="navbar-menu">
	        	<ul class="nav navbar-nav" data-in="fadeInDown" data-out="fadeOutUp">
            		<?php  if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  if(isset($ui_row) && !empty($ui_row)) : $row = $ui_row; else: $row = 60; endif; $tagChannel = new \think\template\taglib\eyou\TagChannel; $_result = $tagChannel->getChannel($typeid, "top", "active"); if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $e = 1;$__LIST__ = is_array($_result) ? array_slice($_result,0, $row, true) : $_result->slice(0, $row, true); if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$field): $field["typename"] = text_msubstr($field["typename"], 0, 100, false); $__LIST__[$key] = $_result[$key] = $field;$mod = ($e % 2 );$i= intval($key) + 1;?>
            		<li class="dropdown">
            			<a href="<?php echo $field['typeurl']; ?>" class="dropdown-toggle <?php echo $field['currentstyle']; ?>" <?php if(!(empty($field['children']) || (($field['children'] instanceof \think\Collection || $field['children'] instanceof \think\Paginator ) && $field['children']->isEmpty()))): ?>data-toggle="dropdown"<?php endif; ?>><span data-title="<?php echo $field['typename']; ?>"><?php echo $field['typename']; ?></span><?php if(!(empty($field['children']) || (($field['children'] instanceof \think\Collection || $field['children'] instanceof \think\Paginator ) && $field['children']->isEmpty()))): ?><i class="fas fa-angle-right"></i><?php endif; ?></a> 
            			<?php if(!(empty($field['children']) || (($field['children'] instanceof \think\Collection || $field['children'] instanceof \think\Paginator ) && $field['children']->isEmpty()))): ?>
            			<ul class="dropdown-menu">
            				<?php  if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  if(isset($ui_row) && !empty($ui_row)) : $row = $ui_row; else: $row = 100; endif;if(is_array($field['children']) || $field['children'] instanceof \think\Collection || $field['children'] instanceof \think\Paginator): $i = 0; $e = 1;$__LIST__ = is_array($field['children']) ? array_slice($field['children'],0,100, true) : $field['children']->slice(0,100, true); if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$field2): $field2["typename"] = text_msubstr($field2["typename"], 0, 100, false); $__LIST__[$key] = $_result[$key] = $field2;$mod = ($e % 2 );$i= intval($key) + 1;?>
            				<li class="dropdown">
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
		<!--menuBox end-->
		<div class="call">
			<a href="tel:<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_attr_2"); echo $__VALUE__; ?>" title="網站架設諮詢專線"><i class="fas fa-phone-volume"></i> <?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_attr_2"); echo $__VALUE__; ?></a>
		</div>
	</section>
</header>
		<!--header-e-->
		<!--banner-->
		<div class="banner_wrap">
			<div class="flexslider">
				<ul class="slides">
					<?php  $tagAdv = new \think\template\taglib\eyou\TagAdv; $_result = $tagAdv->getAdv(1, "", ""); if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $e = 1;$__LIST__ = is_array($_result) ? array_slice($_result,0, 10, true) : $_result->slice(0, 10, true); if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$field):  if ($i == 0) : $field["currentstyle"] = "active"; else:  $field["currentstyle"] = ""; endif;$mod = ($e % 2 );$i= intval($key) + 1;?>
					<li>
						<h3><?php echo $field['title']; ?></h3>
						<a href="<?php echo $field['links']; ?>" <?php echo $field['target']; ?>><img src="<?php echo $field['litpic']; ?>" /></a>
					</li>
					<?php ++$e; endforeach; endif; else: echo htmlspecialchars_decode("");endif; ?>
				</ul>
			</div>
		</div>
		<!--banner end-->
		<!--main block-->
		<div class="main_wrap">
			<!--template-->
			<section class="template_wrap">
				<?php  if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = "3"; endif; if(isset($ui_row) && !empty($ui_row)) : $row = $ui_row; else: $row = 10; endif; $tagChannelartlist = new \think\template\taglib\eyou\TagChannelartlist; $_result = $tagChannelartlist->getChannelartlist($typeid, "self"); if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $e = 1;$__LIST__ = is_array($_result) ? array_slice($_result,0, $row, true) : $_result->slice(0, $row, true); if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$channelartlist): $channelartlist["typename"] = text_msubstr($channelartlist["typename"], 0, 100, false); $__LIST__[$key] = $_result[$key] = $channelartlist;$mod = ($e % 2 );$i= intval($key) + 1;?>
				<ul>
					 <?php  if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  if(isset($ui_row) && !empty($ui_row)) : $row = $ui_row; else: $row = 15; endif; $channeltype = ""; $param = array(      "typeid"=> $typeid,      "notypeid"=> "",      "flag"=> "",      "noflag"=> "",      "channel"=> $channeltype, ); $tag = array (
  'row' => '15',
  'titlelen' => '20',
  'infolen' => '22',
); $tagArclist = new \think\template\taglib\eyou\TagArclist; $_result = $tagArclist->getArclist($param, $row, "", "","desc","",$tag,"0");if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $e = 1;$__LIST__ = is_array($_result) ? array_slice($_result,0, $row, true) : $_result->slice(0, $row, true); if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$field): $aid = $field["aid"];$field["title"] = text_msubstr($field["title"], 0, 20, false);$field["seo_description"] = text_msubstr($field["seo_description"], 0, 22, true);$mod = ($e % 2 );$i= intval($key) + 1;?>
           			 <li>
           			 	<a href="#" href="<?php echo $field['arcurl']; ?>" title="<?php echo $field['title']; ?>">
           			 		<h3><?php echo $field['title']; ?></h3>
           			 		<p class="tag"><?php  $tagField = new \think\template\taglib\eyou\TagField; $__VALUE__ = $tagField->getField("seo_tab", $field['aid']); echo $__VALUE__; ?></p>
           			 		<img alt="<?php echo $field['title']; ?>" src="<?php echo $field['litpic']; ?>" />
						</a>
         			 </li>
         			 <?php ++$e; $aid = 0; endforeach; endif; else: echo htmlspecialchars_decode("");endif; ?>
				</ul>
				<?php ++$e; endforeach; endif; else: echo htmlspecialchars_decode("");endif; $typeid = $row = ""; unset($channelartlist); ?>
			</section>
			<!--template end-->
			<!--service-->
			<section class="service_wrap">
				<div class="wrap">
					<h2>SERVICE</h2>
					<p>WHAT CAN WE DO</p>
					<ul class="service_list">
						<li>
							<u></u>
							<i class="fas fa-chalkboard"></i>
							<h4>RWD WEB DESIGN</h4>
							<p>定制式響應式網站</p>
						</li>
						<li>
							<u></u>
							<i class="fas fa-shopping-cart"></i>
							<h4>ON-LINE SHOP</h4>
							<p>線上購物平台</p>
						</li>
						<li>
							<u></u>
							<i class="far fa-clone"></i>
							<h4>DIY WEB PROGRAM</h4>
							<p>套版式模組架站系統</p>
						</li>
						<li>
							<u></u>
							<i class="far fa-hdd"></i>
							<h4>COMPUTER DESIGN</h4>
							<p>平面印刷設計</p>
						</li>
						<li>
							<u></u>
							<i class="fas fa-paper-plane"></i>
							<h4>VI/CI DESIGN</h4>
							<p>企業形象規劃</p>
						</li>
					</ul>
					<p class="small-txt">One-stop internet service.</p>
				</div>
			</section>
			<!--service end-->
			<!--about-->
			<section class="about_wrap">
				<div class="wrap">
					<div class="tab hd">
						<ul>
							<li><span>ABOUT</span></li>
							<li><span>SPECT</span></li>
							<li><span>SPECIAL</span></li>
						</ul>
					</div>
					<div class="tabContent bd">
						<ul>
							<li>
								<h3>我們是之間設計</h3>
								<p>我們始終相信：創造力才是進步的原動力…<br>之間視覺設計工作室 是由一群熱愛設計的年輕人組成<br>我們堅持依照每個客戶的商業發展目標與需求，去規劃出最佳的設計方案<br>努力創造『最大價值化』的作品，幫助客戶從同行中脫穎而出。<br>成功 來自完美細節的累積 傑出 源自我們對設計的堅持<br><br>我們希望，每一秒鐘 都能創造更多的可能性</p>
							</li>
						</ul>
						<ul>
							<li>
								<h3>我們不滿足自己的成績 更加在乎您的成功</h3>
								<p>與其自賣自誇 我們更加相信 做好案子之後的口耳相傳<br>之間設計想做的 不僅只是服務好每一位客戶，<br>更加重視您的品牌發展與信譽。<br>不只是為您想好下一步，而是站在您的角度，為您設想更多。<br>用話術去解決客戶 不難。<br>用心去規劃好每一個案子，感動每一個客戶，才是真功夫。<br>我們相信 用心對待 絕對能換來最好的成績<br>創造更多雙贏的可能性。</p>
							</li>
						</ul>
						<ul>
							<li>
								<h3>我們堅持 做好案子 而不是做完案子</h3>
								<p>好的設計 不是用趕的 就能做的出來的<br>如果你想要快速的做完您的需求，<br>我們可能不適合你。<br>之間設計堅持 每一個案子都必需是經得起時間的考驗的<br>不論多久，再回頭來看，也依然是一個好的作品<br>我們希望我們經手過的每一個案子 都是可以讓我們驕傲
提出來的證明<br>因此 用心去思考 努力在每一個可能之間 尋找更多的創新<br>就如同好酒一般 需要時間去孕釀<br>才能粹取餘韻不絕的香醇</p>
							</li>
						</ul>
					</div>
				</div>
				<div class="countArea">
					<ul>
						<li>
							<div class="count"><b class="num-year">0</b>年</div>
							<p>設計經驗</p>
						</li>
						<li>
							<div class="count"><b class="num-people">7</b>位</div>
							<p>工作夥伴</p>
						</li>
						<li>
							<div class="count"><b class="num-program" data-end="6">0</b>大</div>
							<p>設計方案</p>
						</li>
						<li>
							<div class="count"><b class="num-web" data-end="133">0</b>+</div>
							<p>網頁作品</p>
						</li>
						<li>
							<div class="count"><b class="num-case" data-end="514">0</b>+</div>
							<p>設計作品</p>
						</li>
					</ul>
				</div>
			</section>
			<!--about end-->
			<!--news-->
			<div class="news_wrap">
				<h2>NEWS</h2>
				<p>ONE & ONE News</p>
				<div class="news_list">
					<div class="news_box">
						<div class="hd">
							<a class="prev"><i class="fas fa-angle-left"></i></a>
							<a class="next"><i class="fas fa-angle-right"></i></a>
						</div>
						<div class="bd">
							<ul>
								 <?php  $tagUiarclist = new \think\template\taglib\eyou\TagUiarclist; $__LIST__ = $tagUiarclist->getUiarclist("2","203", "index"); if((is_array($__LIST__)) && (!empty($__LIST__["info"]) || (($__LIST__["info"] instanceof \think\Collection || $__LIST__["info"] instanceof \think\Paginator ) && $__LIST__["info"]->isEmpty()))): $field = $__LIST__;  $ui_typeid = !empty($field["info"]["typeid"]) ? $field["info"]["typeid"] : ""; if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  $tagType = new \think\template\taglib\eyou\TagType; $_result = $tagType->getType($typeid, "self", ""); if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator):  $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("默认值");else: $field = $__LIST__; if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  if(isset($ui_row) && !empty($ui_row)) : $row = $ui_row; else: $row = 4; endif; $channeltype = ""; $param = array(      "typeid"=> $typeid,      "notypeid"=> "",      "flag"=> "",      "noflag"=> "",      "channel"=> $channeltype, ); $tag = array (
  'row' => '4',
  'titlelen' => '30',
  'infolen' => '160',
); $tagArclist = new \think\template\taglib\eyou\TagArclist; $_result = $tagArclist->getArclist($param, $row, "", "","desc","",$tag,"0");if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $e = 1;$__LIST__ = is_array($_result) ? array_slice($_result,0, $row, true) : $_result->slice(0, $row, true); if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$field): $aid = $field["aid"];$field["title"] = text_msubstr($field["title"], 0, 30, false);$field["seo_description"] = text_msubstr($field["seo_description"], 0, 160, true);$mod = ($e % 2 );$i= intval($key) + 1;?>
           						<li>
           							<div class="news_txt">
										<h4><a href="<?php echo $field['arcurl']; ?>" title="<?php echo $field['title']; ?>"> <?php echo $field['title']; ?> </a></h4>
										<p class="ellipsis"><?php  $tagField = new \think\template\taglib\eyou\TagField; $__VALUE__ = $tagField->getField("txt_description", $field['aid']); echo $__VALUE__; ?></p>
									</div>
									<div class="news_day">
										<span class="md"><?php echo MyDate('m-d',$field['add_time']); ?></span>
										<span class="year"><?php echo MyDate('Y',$field['add_time']); ?></span>
									</div>
           						</li>
           						<?php ++$e; $aid = 0; endforeach; endif; else: echo htmlspecialchars_decode("");endif; endif; else: echo htmlspecialchars_decode("默认值");endif; $ui_typeid = $ui_row = ""; endif; ?>
							</ul>
						</div>
					</div>
				</div>
				<div class="more">
					<a href="#">MORE</a>
				</div>
			</div>
			<!--news end-->
			<section class="html_item">
				<ul>
					<li><img src="template/pc/images/icon-html5.png" alt="使用HTML5語法架站"/></li>
					<li><img src="template/pc/images/icon-css3.png" alt="使用CSS3語法"/></li>
					<li><img src="template/pc/images/icon-jquery.png" alt="使用jquery效果"/></li>
					<li><img src="template/pc/images/icon-rwd.png" alt="網站全部響應式建構"/></li>
					<li><img src="template/pc/images/icon-w3c.png" alt="符合W3C規範"/></li>
				</ul>				
			</section>
		</div>
		<!--main block end-->
		<!--footer-s--> 
		<footer>
	<section class="seo_wrap">
		<div class="container">
			<h3><?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_attr_9"); echo $__VALUE__; ?></h3>
		</div>
	</section>
	<section class="footer_info">
		<div class="container">
			<div class="logo"><a title="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_name"); echo $__VALUE__; ?>" href="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_cmsurl"); echo $__VALUE__; ?>/"><img src="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_attr_11"); echo $__VALUE__; ?>" /></a></div>
			<div class="info">
				<p>Mail：<a href="mailto:<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_attr_1"); echo $__VALUE__; ?>"><?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_attr_1"); echo $__VALUE__; ?></a></p>
				<p>Tel：<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_attr_2"); echo $__VALUE__; ?></p>
				<p>Add：<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_attr_13"); echo $__VALUE__; ?></p>
				<p>Line:<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_attr_3"); echo $__VALUE__; ?></p>
				<p>FB：<a href="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_attr_17"); echo $__VALUE__; ?>" target="_blank"><?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_attr_15"); echo $__VALUE__; ?></a></p>
			</div>
			<ul class="sub_menu">
				 <?php  $tagUichannel = new \think\template\taglib\eyou\TagUichannel; $__LIST__ = $tagUichannel->getUichannel("72","301", "footer"); if((is_array($__LIST__)) && (!empty($__LIST__["info"]) || (($__LIST__["info"] instanceof \think\Collection || $__LIST__["info"] instanceof \think\Paginator ) && $__LIST__["info"]->isEmpty()))): $field = $__LIST__;  $ui_typeid = !empty($field["info"]["typeid"]) ? $field["info"]["typeid"] : ""; if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  $tagType = new \think\template\taglib\eyou\TagType; $_result = $tagType->getType($typeid, "self", ""); if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator):  $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: $field = $__LIST__;?>
				<li><a href="<?php echo $field['typeurl']; ?>" target="_self" title="<?php echo $field['typename']; ?>"><?php echo $field['typename']; ?></a></li>
				<?php endif; else: echo htmlspecialchars_decode("");endif; $ui_typeid = $ui_row = ""; endif;  $tagUichannel = new \think\template\taglib\eyou\TagUichannel; $__LIST__ = $tagUichannel->getUichannel("4","301", "footer"); if((is_array($__LIST__)) && (!empty($__LIST__["info"]) || (($__LIST__["info"] instanceof \think\Collection || $__LIST__["info"] instanceof \think\Paginator ) && $__LIST__["info"]->isEmpty()))): $field = $__LIST__;  $ui_typeid = !empty($field["info"]["typeid"]) ? $field["info"]["typeid"] : ""; if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  $tagType = new \think\template\taglib\eyou\TagType; $_result = $tagType->getType($typeid, "self", ""); if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator):  $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: $field = $__LIST__;?>
				<li><a href="<?php echo $field['typeurl']; ?>" target="_self" title="<?php echo $field['typename']; ?>"><?php echo $field['typename']; ?></a></li>
				<?php endif; else: echo htmlspecialchars_decode("");endif; $ui_typeid = $ui_row = ""; endif;  $tagUichannel = new \think\template\taglib\eyou\TagUichannel; $__LIST__ = $tagUichannel->getUichannel("54","301", "footer"); if((is_array($__LIST__)) && (!empty($__LIST__["info"]) || (($__LIST__["info"] instanceof \think\Collection || $__LIST__["info"] instanceof \think\Paginator ) && $__LIST__["info"]->isEmpty()))): $field = $__LIST__;  $ui_typeid = !empty($field["info"]["typeid"]) ? $field["info"]["typeid"] : ""; if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  $tagType = new \think\template\taglib\eyou\TagType; $_result = $tagType->getType($typeid, "self", ""); if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator):  $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: $field = $__LIST__;?>
				<li><a href="<?php echo $field['typeurl']; ?>" target="_self" title="<?php echo $field['typename']; ?>"><?php echo $field['typename']; ?></a></li>
				<?php endif; else: echo htmlspecialchars_decode("");endif; $ui_typeid = $ui_row = ""; endif;  $tagUichannel = new \think\template\taglib\eyou\TagUichannel; $__LIST__ = $tagUichannel->getUichannel("56","301", "footer"); if((is_array($__LIST__)) && (!empty($__LIST__["info"]) || (($__LIST__["info"] instanceof \think\Collection || $__LIST__["info"] instanceof \think\Paginator ) && $__LIST__["info"]->isEmpty()))): $field = $__LIST__;  $ui_typeid = !empty($field["info"]["typeid"]) ? $field["info"]["typeid"] : ""; if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  $tagType = new \think\template\taglib\eyou\TagType; $_result = $tagType->getType($typeid, "self", ""); if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator):  $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: $field = $__LIST__;?>
				<li><a href="<?php echo $field['typeurl']; ?>" target="_self" title="<?php echo $field['typename']; ?>"><?php echo $field['typename']; ?></a></li>
				<?php endif; else: echo htmlspecialchars_decode("");endif; $ui_typeid = $ui_row = ""; endif;  $tagUichannel = new \think\template\taglib\eyou\TagUichannel; $__LIST__ = $tagUichannel->getUichannel("2","301", "footer"); if((is_array($__LIST__)) && (!empty($__LIST__["info"]) || (($__LIST__["info"] instanceof \think\Collection || $__LIST__["info"] instanceof \think\Paginator ) && $__LIST__["info"]->isEmpty()))): $field = $__LIST__;  $ui_typeid = !empty($field["info"]["typeid"]) ? $field["info"]["typeid"] : ""; if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  $tagType = new \think\template\taglib\eyou\TagType; $_result = $tagType->getType($typeid, "self", ""); if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator):  $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: $field = $__LIST__;?>
				<li><a href="<?php echo $field['typeurl']; ?>" target="_self" title="<?php echo $field['typename']; ?>"><?php echo $field['typename']; ?></a></li>
				<?php endif; else: echo htmlspecialchars_decode("");endif; $ui_typeid = $ui_row = ""; endif;  $tagUichannel = new \think\template\taglib\eyou\TagUichannel; $__LIST__ = $tagUichannel->getUichannel("6","301", "footer"); if((is_array($__LIST__)) && (!empty($__LIST__["info"]) || (($__LIST__["info"] instanceof \think\Collection || $__LIST__["info"] instanceof \think\Paginator ) && $__LIST__["info"]->isEmpty()))): $field = $__LIST__;  $ui_typeid = !empty($field["info"]["typeid"]) ? $field["info"]["typeid"] : ""; if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  $tagType = new \think\template\taglib\eyou\TagType; $_result = $tagType->getType($typeid, "self", ""); if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator):  $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: $field = $__LIST__;?>
				<li><a href="<?php echo $field['typeurl']; ?>" target="_self" title="<?php echo $field['typename']; ?>"><?php echo $field['typename']; ?></a></li>
				<?php endif; else: echo htmlspecialchars_decode("");endif; $ui_typeid = $ui_row = ""; endif; ?>
			</ul>
			<div class="QR_code"><img src="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_attr_4"); echo $__VALUE__; ?>" alt="掃描QR-Code 加入好友"/></div>
		</div>
	</section>
	<section class="copyRight">
		<div class="container">
			<p><?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_attr_19"); echo $__VALUE__; ?></p>
		</div>
	</section>
</footer>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-3205223-8"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-3205223-8');
</script>
 
		<!--footer-e--> 
	</div>
	<script type="text/javascript" src="template/pc/Scripts/fakeLoader/fakeLoader.min.js"></script>
	<div class="fakeLoader"></div>
	<script>
    	$(document).ready(function () {
        	$.fakeLoader({
				timeToHide:1200,
				zIndex:999,
            	bgColor: '#e74c3c',
                spinner: 'spinner2',
				imagePath:"yourPath/customizedImage.gif"
            });
        });
    </script>
    <link rel="stylesheet prefetch" href="template/pc/function/FlexSlider/css/flexslider.css">
    <script type="text/javascript" src="template/pc/function/FlexSlider/jquery.flexslider.js"></script>
    <script type="text/javascript">
		$(window).load(function() {
			$('.flexslider').flexslider();
		});
	</script>
	<script type="text/javascript" src="template/pc/function/animateNumbers/jquery.animateNumbers.js"></script>
	<script type="text/javascript">
		
	</script>
	<script type="text/javascript" src="template/pc/function/SuperSlide/jquery.SuperSlide.min.js"></script>
	<script type="text/javascript">
		$(function(){
			jQuery(".about_wrap .wrap").slide({
				effect:"left",
				autoPlay:true,
				trigger:"click",
				delayTime:1000
			});
			jQuery(".news_list").slide({
				titCell:".hd ul",
				mainCell:".bd ul",
				autoPage:true,
				effect:"left",
				autoPlay:true,
				pnLoop:false
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
</body>
</html>