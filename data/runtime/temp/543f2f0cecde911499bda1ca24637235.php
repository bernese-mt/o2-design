<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:30:"./template/pc/view_product.htm";i:1558669036;s:49:"/home/o2design/public_html/template/pc/header.htm";i:1559112671;s:49:"/home/o2design/public_html/template/pc/footer.htm";i:1559113908;}*/ ?>
<!DOCTYPE html>
<html>
 <head> 
  <title><?php echo $eyou['field']['seo_title']; ?></title> 
  <meta name="renderer" content="webkit" /> 
  <meta charset="utf-8" /> 
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" /> 
  <meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=0,minimal-ui" /> 
  <meta name="description" content="<?php echo $eyou['field']['seo_description']; ?>" /> 
  <meta name="keywords" content="<?php echo $eyou['field']['seo_keywords']; ?>" />
  <link href="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_cmspath"); echo $__VALUE__; ?>/favicon.ico" rel="shortcut icon" type="image/x-icon" /> 
  <?php  $tagStatic = new \think\template\taglib\eyou\TagStatic; $__VALUE__ = $tagStatic->getStatic("skin/css/basic.css","","",""); echo $__VALUE__;  $tagStatic = new \think\template\taglib\eyou\TagStatic; $__VALUE__ = $tagStatic->getStatic("skin/css/common.css","","",""); echo $__VALUE__; ?> 
  <style>
    body{background-position: center;background-repeat: no-repeat;background-color: ;font-family:;}
  </style> 
    <!--[if lte IE 9]>
    <?php  $tagStatic = new \think\template\taglib\eyou\TagStatic; $__VALUE__ = $tagStatic->getStatic("skin/js/lteie9.js","","",""); echo $__VALUE__; ?>
    <![endif]--> 
 </head> 
 <!--[if lte IE 8]>
    <div class="text-xs-center m-b-0 bg-blue-grey-100 alert">
    <button type="button" class="close" aria-label="Close" data-dismiss="alert">
        <span>×</span>
    </button>
    你正在使用一个 <strong>过时</strong> 的浏览器。请 <a href=https://browsehappy.com/ target=_blank>升级您的浏览器</a>，以提高您的体验。</div>
<![endif]--> 
 <body> 
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
  <div class="ey-banner-ny vertical-align text-center"> 
   <h1 class="vertical-align-middle"><?php echo $eyou['field']['typename']; ?></h1> 
  </div> 
  <section class="ey-crumbs hidden-sm-down" m-id="met_position" m-type="nocontent"> 
   <div class="container"> 
    <div class="row"> 
     <div class="border-bottom clearfix"> 
      <ol class="breadcrumb m-b-0 subcolumn-crumbs breadcrumb-arrow"> 
       <li class="breadcrumb-item"> <?php  $tagLang = new \think\template\taglib\eyou\TagLang; $__VALUE__ = $tagLang->getLang('a:1:{s:4:"name";s:5:"yybl5";}'); echo $__VALUE__; ?>： <?php  $typeid = ""; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  $tagPosition = new \think\template\taglib\eyou\TagPosition; $__VALUE__ = $tagPosition->getPosition($typeid, "", "crumb"); echo $__VALUE__; ?> </li> 
      </ol> 
     </div> 
    </div> 
   </div> 
  </section> 
  <section class="ey-column-nav" m-id="subcolumn_nav" m-type="nocontent"> 
   <div class="container"> 
    <div class="row"> 
     <ul class="clearfix ey-column-nav-ul"> 
      <li> <a href="<?php echo $eyou['field']['ptypeurl']; ?>" title="<?php  $tagLang = new \think\template\taglib\eyou\TagLang; $__VALUE__ = $tagLang->getLang('a:1:{s:4:"name";s:5:"yybl2";}'); echo $__VALUE__; ?>" <?php if($eyou['field']['has_children'] > '0'): ?>class="active"<?php endif; ?>><?php  $tagLang = new \think\template\taglib\eyou\TagLang; $__VALUE__ = $tagLang->getLang('a:1:{s:4:"name";s:5:"yybl2";}'); echo $__VALUE__; ?></a> </li> 
      <?php  if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  if(isset($ui_row) && !empty($ui_row)) : $row = $ui_row; else: $row = 10; endif; $tagChannel = new \think\template\taglib\eyou\TagChannel; $_result = $tagChannel->getChannel($typeid, "first", "active"); if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $e = 1;$__LIST__ = is_array($_result) ? array_slice($_result,0, $row, true) : $_result->slice(0, $row, true); if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$field): $field["typename"] = text_msubstr($field["typename"], 0, 100, false); $__LIST__[$key] = $_result[$key] = $field;$mod = ($e % 2 );$i= intval($key) + 1;?>
      <li class="dropdown"> <a href="<?php echo $field['typeurl']; ?>" title="<?php echo $field['typename']; ?>" <?php if($field['has_children'] > '0'): ?>class="dropdown-toggle" data-toggle="dropdown"<?php endif; ?>><?php echo $field['typename']; ?></a> 
        <?php if($field['has_children'] > '0'): ?>
        <div class="dropdown-menu animate"> 
          <a href="<?php echo $field['typeurl']; ?>" title="<?php  $tagLang = new \think\template\taglib\eyou\TagLang; $__VALUE__ = $tagLang->getLang('a:1:{s:4:"name";s:5:"yybl2";}'); echo $__VALUE__; ?>" class="dropdown-item "><?php  $tagLang = new \think\template\taglib\eyou\TagLang; $__VALUE__ = $tagLang->getLang('a:1:{s:4:"name";s:5:"yybl2";}'); echo $__VALUE__; ?></a> 
          <?php  if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  if(isset($ui_row) && !empty($ui_row)) : $row = $ui_row; else: $row = 10; endif;if(is_array($field['children']) || $field['children'] instanceof \think\Collection || $field['children'] instanceof \think\Paginator): $i = 0; $e = 1;$__LIST__ = is_array($field['children']) ? array_slice($field['children'],0,10, true) : $field['children']->slice(0,10, true); if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$field2): $field2["typename"] = text_msubstr($field2["typename"], 0, 100, false); $__LIST__[$key] = $_result[$key] = $field2;$mod = ($e % 2 );$i= intval($key) + 1;?>
          <a href="<?php echo $field2['typeurl']; ?>" title="<?php echo $field2['typename']; ?>" class="dropdown-item "><?php echo $field2['typename']; ?></a> 
          <?php ++$e; endforeach; endif; else: echo htmlspecialchars_decode("");endif; ?>
        </div>
        <?php endif; ?>
      </li> 
      <?php ++$e; endforeach; endif; else: echo htmlspecialchars_decode("");endif; ?>
     </ul> 
    </div> 
   </div> 
  </section> 
  <main class="ey-showproduct pagetype1 animsition" m-id="noset"> 
   <div class="ey-showproduct-head page-content block-bg"> 
    <div class="container"> 
     <div class="row"> 
      <div class="col-lg-7"> 
       <div class="ey-showproduct-list fngallery cover text-xs-center" id="ey-imgs-slick" m-id="noset" m-type="displayimgs"> 
        <?php if(is_array($eyou['field']['image_list']) || $eyou['field']['image_list'] instanceof \think\Collection || $eyou['field']['image_list'] instanceof \think\Paginator): $i = 0; $e = 1; $__LIST__ = $eyou['field']['image_list'];if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$field): $mod = ($e % 2 );$i= intval($key) + 1;?>
        <!--fngallery：启用lightGallery插件的类名--> 
        <div class="slick-slide"> 
         <a href="<?php echo $field['image_url']; ?>" data-size="400x400" data-med="<?php echo $field['image_url']; ?>" data-med-size="400x400" class="lg-item-box" data-src="<?php echo $field['image_url']; ?>" data-exthumbimage="<?php echo $field['image_url']; ?>" data-sub-html="<?php echo $eyou['field']['title']; ?>"> 
          <!--类名lg-item-box之前为initPhotoSwipeFromDOM插件所用参数；之后为lightGallery插件所用参数，lg-item-box：lightGallery插件对应的类名--> <img src="<?php echo $field['image_url']; ?>" class="img-fluid" alt="<?php echo $eyou['field']['title']; ?>" /> </a> 
        </div> 
        <?php ++$e; endforeach; endif; else: echo htmlspecialchars_decode("");endif; ?>
       </div> 
      </div> 
      <div class="col-lg-5"> 
       <div class="product-intro"> 
        <h1 class="m-t-0 font-size-24"><?php echo $eyou['field']['title']; ?></h1> 
        <p class="description"><?php echo $eyou['field']['seo_description']; ?></p> 
        <ul class="product-para paralist blocks-100 blocks-sm-2 blocks-md-3 blocks-lg-2"> 
          <?php  if(empty($aid)) : $aid_tmp = ""; endif;  $taid = 0;  if(!empty($aid_tmp)) : $taid = $aid_tmp; elseif(!empty($aid)) : $taid = $aid; endif; $tagAttribute = new \think\template\taglib\eyou\TagAttribute; $_result = $tagAttribute->getAttribute($taid, "auto"); if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $e = 1; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$attr): $mod = ($e % 2 );$i= intval($key) + 1;?>
          <li> <span><?php echo $attr['name']; ?>：</span> <?php echo $attr['value']; ?> </li> 
          <?php ++$e; endforeach;endif; else: echo htmlspecialchars_decode("");endif; ?>
        </ul> 
       </div> 
      </div> 
     </div> 
    </div> 
   </div> 
   <div class="ey-showproduct-body page-content"> 
    <div class="container"> 
     <div class="row"> 
      <div class="col-lg-9 pull-lg-right"> 
       <div class="row"> 
        <div class="panel panel-body m-b-0 product-detail" boxmh-mh=""> 
         <ul class="nav nav-tabs nav-tabs-line m-b-20 ey-showproduct-navtabs"> 
          <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#product-content0" data-get="product-details"><?php  $tagLang = new \think\template\taglib\eyou\TagLang; $__VALUE__ = $tagLang->getLang('a:1:{s:4:"name";s:5:"yybl9";}'); echo $__VALUE__; ?></a> </li> 
         </ul> 
         <article class="tab-content"> 
          <section class="tab-pane ey-editor clearfix animation-fade active" id="product-content0"> <?php echo $eyou['field']['content']; ?>
          </section>
         </article> 
         <div class="detail_tag font-size-14"> 
          <span></span> 
          <a href="" title=""></a> 
         </div> 
        </div> 
       </div> 
      </div> 
      <div class="col-lg-3"> 
       <div class="row"> 
        <aside class="panel panel-body m-b-0 product-hot ey-sidebar leftsidebar" boxmh-h="" m-id="product_bar"> 
         <div class="sidebar-piclist"> 
          <h3 class="m-0 font-size-16 font-weight-300"><?php  $tagLang = new \think\template\taglib\eyou\TagLang; $__VALUE__ = $tagLang->getLang('a:1:{s:4:"name";s:5:"yybl8";}'); echo $__VALUE__; ?></h3> 
          <ul class="blocks-2 blocks-md-3 blocks-lg-100 m-t-20 text-xs-center imagesize sidebar-piclist-ul" data-scale="x">
          <?php  if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  if(isset($ui_row) && !empty($ui_row)) : $row = $ui_row; else: $row = 3; endif; $channeltype = ""; $param = array(      "typeid"=> $typeid,      "notypeid"=> "",      "flag"=> "",      "noflag"=> "",      "channel"=> $channeltype, ); $tag = array (
  'row' => '3',
  'titlelen' => '30',
  'orderby' => 'rand',
); $tagArclist = new \think\template\taglib\eyou\TagArclist; $_result = $tagArclist->getArclist($param, $row, "rand", "","desc","",$tag,"0");if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $e = 1;$__LIST__ = is_array($_result) ? array_slice($_result,0, $row, true) : $_result->slice(0, $row, true); if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$field): $aid = $field["aid"];$field["title"] = text_msubstr($field["title"], 0, 30, false);$field["seo_description"] = text_msubstr($field["seo_description"], 0, 160, true);$mod = ($e % 2 );$i= intval($key) + 1;?>
           <li class="masonry-child"> <a href="<?php echo $field['arcurl']; ?>" title="<?php echo $field['title']; ?>" class="block m-b-0" target="_blank"> <img data-original="<?php echo $field['litpic']; ?>" class="cover-image" alt="<?php echo $field['title']; ?>" height="100" src="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $__VALUE__ = $tagGlobal->getGlobal("web_templets_pc"); echo $__VALUE__; ?>/skin/images/ajax-loader.gif" /></a> <h4 class="m-t-10 m-b-0 font-size-14"> <a href="<?php echo $field['arcurl']; ?>" title="<?php echo $field['title']; ?>" target="_blank"><?php echo $field['title']; ?></a> </h4> </li> 
          <?php ++$e; $aid = 0; endforeach; endif; else: echo htmlspecialchars_decode("");endif; ?>
          </ul> 
         </div> 
        </aside> 
       </div> 
      </div> 
     </div> 
    </div> 
   </div> 
  </main>
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
  <?php  $tagStatic = new \think\template\taglib\eyou\TagStatic; $__VALUE__ = $tagStatic->getStatic("skin/js/PhotoSwipe.js","","",""); echo $__VALUE__;  $tagUi = new \think\template\taglib\eyou\TagUi; $__VALUE__ = $tagUi->getUi(); echo $__VALUE__; ?>
 </body>
</html>