<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:32:"./template/pc/lists_download.htm";i:1557219895;s:57:"/home/o2design/public_html/EyouCMS/template/pc/header.htm";i:1557303003;s:57:"/home/o2design/public_html/EyouCMS/template/pc/footer.htm";i:1557302387;}*/ ?>
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
  <?php  $tagStatic = new \think\template\taglib\eyou\TagStatic; $__VALUE__ = $tagStatic->getStatic("skin/css/basic.css","",""); echo $__VALUE__;  $tagStatic = new \think\template\taglib\eyou\TagStatic; $__VALUE__ = $tagStatic->getStatic("skin/css/common.css","",""); echo $__VALUE__; ?> 
  <style>
    body{background-position: center;background-repeat: no-repeat;background-color: ;font-family:;}
  </style> 
    <!--[if lte IE 9]>
    <?php  $tagStatic = new \think\template\taglib\eyou\TagStatic; $__VALUE__ = $tagStatic->getStatic("skin/js/lteie9.js","",""); echo $__VALUE__; ?>
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
<main e-page="lists_download">
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
  <section class="ey-download animsition"> 
   <div class="container"> 
    <div class="row"> 
     <div class="col-md-9 ey-download-body"> 
      <div class="row"> 
       <div class="ey-download-list"> 
        <ul class="ulstyle ey-pager-ajax imagesize" data-scale="1" m-id="met_download"> 
          <?php  $typeid = "";  if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  $param = array(      "typeid"=> $typeid,      "notypeid"=> "",      "flag"=> "",      "noflag"=> "",      "channel"=> "", ); $tagList = new \think\template\taglib\eyou\TagList; $_result_tmp = $tagList->getList($param, 10, "", "", "desc");if(is_array($_result_tmp) || $_result_tmp instanceof \think\Collection || $_result_tmp instanceof \think\Paginator): $i = 0; $e = 1; $__LIST__ = $_result = $_result_tmp["list"]; $__PAGES__ = $_result_tmp["pages"];if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$field): $aid = $field["aid"];$field["title"] = text_msubstr($field["title"], 0, 20, false);$field["seo_description"] = text_msubstr($field["seo_description"], 0, 160, true);$mod = ($e % 2 );$i= intval($key) + 1;?>
          <li class="list-group-item"> 
          <div class="media"> 
           <div class="media-left p-r-5 p-l-10 hidden-xs-down"> 
            <a href="<?php echo $field['arcurl']; ?>" title="<?php echo $field['title']; ?>" target="_self"> <i class="icon fa-file-archive-o blue-grey-400"></i> </a> 
           </div> 
           <div class="media-body"> 
            <?php  if(!isset($aid) || empty($aid)) : $aid = $field['aid']; endif; $tagArcview = new \think\template\taglib\eyou\TagArcview; $_result = $tagArcview->getArcview($aid, ""); if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator):  $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: $view = $__LIST__;if(is_array($view['file_list']) || $view['file_list'] instanceof \think\Collection || $view['file_list'] instanceof \think\Paginator): $i = 0; $e = 1; $__LIST__ = $view['file_list'];if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$vo): $mod = ($e % 2 );$i= intval($key) + 1;?>
              <a class="btn btn-outline btn-primary btn-squared pull-xs-right" href="<?php echo $vo['downurl']; ?>" title="<?php echo $vo['title']; ?>"><?php  $tagLang = new \think\template\taglib\eyou\TagLang; $__VALUE__ = $tagLang->getLang('a:1:{s:4:"name";s:6:"yybl10";}'); echo $__VALUE__; ?>(<?php echo $i; ?>)</a>
              <?php ++$e; endforeach; endif; else: echo htmlspecialchars_decode("");endif; endif; else: echo htmlspecialchars_decode("");endif; unset($aid); ?>
            <h4 class="media-heading font-size-16"> <a class="name" href="<?php echo $field['arcurl']; ?>" title="<?php echo $field['title']; ?>" target="_self"><?php echo $field['title']; ?></a> </h4> 
            <small class="font-size-14 blue-grey-500"> <span>浏览量：<?php echo $field['click']; ?>次</span> <span class="m-l-10">日期：<?php echo MyDate('Y-m-d',$field['add_time']); ?></span> </small> 
           </div> 
          </div>
          </li> 
          <?php ++$e; $aid = 0; endforeach; endif; else: echo htmlspecialchars_decode("");endif; ?>
        </ul> 
        <div class="m-t-20 text-xs-center hidden-sm-down" m-type="nosysdata"> 
          <ul class="tcdPageCode">
           <?php  $__PAGES__ = isset($__PAGES__) ? $__PAGES__ : ""; $tagPagelist = new \think\template\taglib\eyou\TagPagelist; $__VALUE__ = $tagPagelist->getPagelist($__PAGES__, "index,end,pre,next", "2"); echo $__VALUE__; ?>
          </ul>
        </div> 
       </div> 
      </div> 
     </div> 
     <div class="col-md-3" m-id="downlaod_bar" m-type="nocontent"> 
      <div class="row"> 
       <div class="ey-bar"> 
        <?php  $tagSearchform = new \think\template\taglib\eyou\TagSearchform; $_result = $tagSearchform->getSearchform("","","","",""); if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $e = 1; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$field): $mod = ($e % 2 );$i= intval($key) + 1;?>
        <form class="sidebar-search" method="get" action="<?php echo $field['action']; ?>">
          <?php echo $field['hidden']; ?>
         <div class="form-group"> 
          <div class="input-search"> 
           <button type="submit" class="input-search-btn"> <i class="icon fa-search"></i> </button> 
           <input type="text" class="form-control" name="keywords" placeholder="<?php  $tagLang = new \think\template\taglib\eyou\TagLang; $__VALUE__ = $tagLang->getLang('a:1:{s:4:"name";s:5:"yybl6";}'); echo $__VALUE__; ?>" /> 
          </div> 
         </div> 
        </form> 
        <?php ++$e; endforeach;endif; else: echo htmlspecialchars_decode("");endif; ?>
        <div class="sidebar-news-list recommend"> 
         <h3 class="m-0"><?php  $tagLang = new \think\template\taglib\eyou\TagLang; $__VALUE__ = $tagLang->getLang('a:1:{s:4:"name";s:5:"yybl7";}'); echo $__VALUE__; ?></h3> 
         <ul class="list-icons list-group-bordered m-t-10 m-b-0"> 
         <!-- 循环输出指定最顶级栏目ID的文档列表，并按随机排序 start -->
         <?php  if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = "5"; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  if(isset($ui_row) && !empty($ui_row)) : $row = $ui_row; else: $row = 5; endif; $channeltype = ""; $param = array(      "typeid"=> $typeid,      "notypeid"=> "",      "flag"=> "",      "noflag"=> "",      "channel"=> $channeltype, ); $tag = array (
  'typeid' => '5',
  'limit' => '0,5',
  'titlelen' => '30',
  'orderby' => 'rand',
); $tagArclist = new \think\template\taglib\eyou\TagArclist; $_result = $tagArclist->getArclist($param, $row, "rand", "","desc","",$tag,"0");if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $e = 1;$__LIST__ = is_array($_result) ? array_slice($_result,0, $row, true) : $_result->slice(0, $row, true); if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$field): $aid = $field["aid"];$field["title"] = text_msubstr($field["title"], 0, 30, false);$field["seo_description"] = text_msubstr($field["seo_description"], 0, 160, true);$mod = ($e % 2 );$i= intval($key) + 1;?>
         <li> <a href="<?php echo $field['arcurl']; ?>" title="<?php echo $field['title']; ?>"><?php echo $field['title']; ?></a> </li> 
         <?php ++$e; $aid = 0; endforeach; endif; else: echo htmlspecialchars_decode("");endif; ?>
         <!-- 循环输出指定最顶级栏目ID的文档列表，并按随机排序 end -->
         </ul> 
        </div> 
       </div> 
      </div> 
     </div> 
    </div> 
   </div> 
  </section> 
</main>
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
  <?php  $tagUi = new \think\template\taglib\eyou\TagUi; $__VALUE__ = $tagUi->getUi(); echo $__VALUE__; ?>
 </body>
</html>