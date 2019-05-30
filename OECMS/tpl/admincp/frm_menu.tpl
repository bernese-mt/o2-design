<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="<!--{$page_charset}-->">
<title>menu</title>
<link rel="stylesheet" href="<!--{$cppath}-->css/menu.css" type="text/css" /> 
<script type="text/javascript" src="<!--{$urlpath}-->tpl/static/js/jquery.min.js"></script>
<script type="text/javascript" src="<!--{$cppath}-->js/menu.js"></script>
</head>
<body style="overflow-x:hidden;">
<div id="accordion2">
  
  <!--{if $mod eq 'setting'}-->
  <div class="switchable-trigger" onClick="menu('label_1');"><i id='label_1_css' class="moo-icon-2"></i><h3>基础设置</h3></div>
  <ul class="switchable-panel" id='label_1'>
    <li>
	  <a href="<!--{$cpfile}-->?c=frame&a=main" target='main'>系统信息</a>&nbsp;&nbsp;
	  <a href="<!--{$cpfile}-->?c=setting" target='main'>站点设置</a>
	</li>
	<li>
	  <a href="<!--{$cpfile}-->?c=setting&a=footer" target='main'>底部信息</a>&nbsp;&nbsp;
	  <a href="<!--{$cpfile}-->?c=setting&a=upload" target='main'>图片设置</a>
    </li>
	<li>
	  <a href="<!--{$cpfile}-->?c=seo" target='main'>SEO设置</a>&nbsp;&nbsp;
	  <a href="<!--{$cpfile}-->?c=setting&a=rewrite" target='main'>伪静态设置</a>
	</li>
	<li>
	  
    </li>
  </ul>

  <div class="switchable-trigger" onClick="menu('label_2');"><i id='label_2_css' class="moo-icon-2"></i><h3>管理员帐号</h3></div>
  <ul class="switchable-panel" id='label_2' style="display:;">
    <li>
	  <a href="<!--{$cpfile}-->?c=admin" target='main'>帐号列表</a>&nbsp;&nbsp;
	  <a href="<!--{$cpfile}-->?c=admin&a=add" target='main'>添加管理员</a>
	</li>
	<li>
	  <a href="<!--{$cpfile}-->?c=admin&a=editpassword">修改密码</a>
    </li>
  </ul>

  <div class="switchable-trigger last-trigger" onClick="menu('label_4');"><i id='label_4_css' class="moo-icon-2"></i><h3>其他设置</h3></div>
  <ul class="switchable-panel last-pannel" id='label_4' style="display:;">
    <li>
	  <a href="<!--{$cpfile}-->?c=htmllabel" target='main'>自定义HTML标签</a>
	</li>
	<li>
	  <a href="<!--{$cpfile}-->?c=zone" target='main'>广告版位</a>&nbsp;&nbsp;
	  <a href="<!--{$cpfile}-->?c=myads" target='main'>广告管理</a>
    </li>
	<li style="display:none;">
	  <a href="<!--{$cpfile}-->?c=relatedlink" target='main'>关联链接设置</a>
	</li>
  </ul>
  <!--{/if}-->

  <!--{if $mod eq 'skin'}-->
  <div class="switchable-trigger" onClick="menu('label_1');"><i id='label_1_css' class="moo-icon-2"></i><h3>主题模板</h3></div>
  <ul class="switchable-panel" id='label_1'>
    <li>
	  <a href="<!--{$cpfile}-->?c=skin" target='main'>设置模板</a>
	</li>
	<li>
	  <a href="<!--{$cpfile}-->?c=setting&a=index_style" target='main'>首页配置</a>
    </li>
	<li>
	  <a href="<!--{$cpfile}-->?c=setting&a=main_style" target='main'>界面配置</a>
	</li>
  </ul>

  <div class="switchable-trigger" onClick="menu('label_2');"><i id='label_2_css' class="moo-icon-2"></i><h3>模板管理</h3></div>
  <ul class="switchable-panel" id='label_2' style="display:;">
    <li><a href="<!--{$cpfile}-->?c=templet">模板文件</a></li>
  </ul>
  <!--{/if}-->

  <!--{if $mod eq 'app'}-->
  <div class="switchable-trigger" onClick="menu('label_1');"><i id='label_1_css' class="moo-icon-2"></i><h3>扩展应用</h3></div>
  <ul class="switchable-panel" id='label_1'>
	<li>
	  <a href="<!--{$cpfile}-->?c=log" target='main'>系统操作日志</a>
    </li>
	<li>
	  <a href='<!--{$cpfile}-->?c=setting&a=clearcache'>清除页面缓存</a>
	</li>
	<li>
	  <a href='<!--{$cpfile}-->?c=setting&a=updatecache'>更新数据缓存</a>
	</li>
    <li>
	  <a href="<!--{$cpfile}-->?c=guestbook" target='main'>留言列表</a>
	</li>
  </ul>

  <div class="switchable-trigger" onClick="menu('label_2');"><i id='label_2_css' class="moo-icon-2"></i><h3>插件中心</h3></div>
  <ul class="switchable-panel" id='label_2' style="display:;">
    <li><a href="<!--{$cpfile}-->?c=plugin" target='main'>插件列表</a></li>
	<!--{assign var='plugin_data' value=XHook::doAction('adm_sidebar_ext')}-->
  </ul>
  <!--{/if}-->

  
  <!--{if $mod eq 'content'}-->

  <div class="switchable-trigger" onClick="menu('label_1');"><i id='label_1_css' class="moo-icon-2"></i><h3>栏目管理</h3></div>
  <ul class="switchable-panel" id='label_1' style="display:;">
    <li><a href="<!--{$cpfile}-->?c=category">栏目设置</a> <a href="<!--{$cpfile}-->?c=module">模块类型</a> </li>
  </ul>

  <!--{if !empty($category)}-->
  <!--{foreach $category as $volist}-->

  <!--{if $volist.modalias=='about'}-->
  <div class="switchable-trigger" onClick="menu('model_<!--{$volist.catid}-->');"><i id='model_<!--{$volist.catid}-->_css' class="moo-icon-2"></i><h3><!--{$volist.catname}--></h3></div>
  <ul class="switchable-panel" id='model_<!--{$volist.catid}-->'>
    <!--{if !empty($volist.child)}-->
	<!--{foreach $volist.child as $mychild}-->
    <li>&nbsp;&nbsp;&nbsp;<a href="<!--{$cpfile}-->?c=about&a=edit&catid=<!--{$mychild.catid}-->&id=<!--{$mychild.abid}-->"><!--{$mychild.catname}--></a></li>
	<!--{/foreach}-->
	<!--{/if}-->
  </ul>
  <!--{else}-->
  <div class="switchable-trigger" onClick="menu('model_<!--{$volist.catid}-->');"><i id='model_<!--{$volist.catid}-->_css' class="moo-icon-2"></i><h3><!--{$volist.catname}--></h3></div>
  <ul class="switchable-panel" id='model_<!--{$volist.catid}-->'>
    <li><a href="<!--{$cpfile}-->?c=modattr&smodule=<!--{$volist.modalias}-->&streeid=<!--{$volist.catid}-->">模块字段</a> <a href="<!--{$cpfile}-->?c=modattr&a=add&smodule=<!--{$volist.modalias}-->&streeid=<!--{$volist.catid}-->">添加字段</a></li>
    <li><a href="<!--{$cpfile}-->?c=<!--{$volist.modalias}-->&tid=<!--{$volist.catid}-->">内容列表</a> <a href="<!--{$cpfile}-->?c=<!--{$volist.modalias}-->&a=add&tid=<!--{$volist.catid}-->">添加内容</a></li>
  </ul>
  <!--{/if}-->
  <!--{/foreach}-->
  <!--{/if}-->


  <!--{/if}-->

</div>
</body>
</html>
<script language="javascript">
function menu(id) {
	if ($("#"+id).css("display") == "none") {
		$("#"+id).css("display","block");
		$("#"+id+'_css').removeClass('moo-icon-1');
		$("#"+id+'_css').addClass('moo-icon-2');
	} else {
		$("#"+id).css("display","none");
		$("#"+id+'_css').removeClass('moo-icon-2');
		$("#"+id+'_css').addClass('moo-icon-1');
	}
}
</script>
