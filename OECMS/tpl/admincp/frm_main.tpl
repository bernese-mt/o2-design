<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<!--{$page_charset}-->" />
<!--{include file="<!--{$cptplpath}-->headerjs.tpl"}-->
<title>首页</title>
<link type="text/css" rel="stylesheet" href="<!--{$cppath}-->css/admin.css" media="screen" />
</head>
<body>
<div class="main-wrap">
<div class="path">
  <p>当前位置：管理首页<span></span></p>
</div>
<div class="main-cont">
  <h3 class="title">产品服务</h3>
  <div class="box">
    <div class="btn-group clear">
	  <a class="btn-general" href="http://www.phpcoo.com/" target="_blank"><span>OEcms官方网站</span></a>
	  <a class="btn-general" href="http://bbs.phpcoo.com/" target="_blank"><span>技术论坛</span></a>
	  <a class="btn-general" href="http://www.phpcoo.com/contact" target="_blank"><span>联系客服</span></a>
	  <a class="btn-general" href="mailto:phpcoo@qq.com"><span>意见反馈</span></a>
	  <a class="btn-general" href="http://demo.phpcoo.com/" target="_blank"><span>在线演示</span></a>
	</div>
  </div>

  <h3 class="title">官方最新动态</h3>
  <div class="box">
    <ul class="news-item" id="news">
	  <script language="javascript" src="http://www.phpcoo.com/data/include/oecms.php"></script>
	</ul>
  </div>

  <h3 class="title" style='display:block;'>网站基本数据</h3>
  <div class="box" style="display:block;width:700px;">
    <ul class="data-item">
	  <li>文章模型：<span><!--{$count.article}--></span></li>
	  <li>产品模型：<span><!--{$count.product}--></span></li>
	  <li>图库模型：<span><!--{$count.photo}--></span></li>
	</ul>
	<ul class="data-item">
	  <li>下载模型：<span><!--{$count.download}--></span></li>
	  <li>招聘模型：<span><!--{$count.hr}--></span></li>
	  <li>单页模型：<span><!--{$count.about}--></span></li>
	</ul>
  </div>

  <div style="clear:both;"></div>

  <h3 class="title" style='display:block;'>服务器信息</h3>
  <div class="box" style="width:100%;overflow:hidden;">
	<ul class="group-item">
	  <li>服务器IP：<span><!--{$sysdata.serverip}--></span></li>
	  <li>客户端IP：<span><!--{$sysdata.clientip}--></span></li>
	  <li>操作系统：<span><!--{$sysdata.os}--></span></li>
	</ul>

	<ul class="group-item">
	  <li>web引擎：<span><!--{$sysdata.webengine|filterhtml:30}--></span></li>
	  <li>PHP版本：<span><!--{$sysdata.phpversion}--></span></li>
	  <li>curl支持：<span><!--{$sysdata.curl}--></span></li>
	</ul>

	<ul class="group-item">
	  <li>GD版本：<span><!--{$sysdata.gd}--></span></li>
	  <li>iconv支持：<span><!--{$sysdata.iconv}--></span></li>
	  <li>url fopen：<span><!--{$sysdata.urlopen}--></span></li>
	</ul>
  </div>

</div>
<div style="clear:both;"></div>
</div>
</body>
</html>