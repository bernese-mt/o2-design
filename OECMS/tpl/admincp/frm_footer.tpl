<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<!--{$page_charset}-->" />
<title>footer</title>
<link rel="stylesheet" type="text/css" href="<!--{$cppath}-->css/footer.css" />
<!--{include file="<!--{$cptplpath}-->headerjs.tpl"}-->
</head>
<body>
<div class="footer">
<table width="98%" border="0" cellpadding="0" cellspacing="0" align="left">
  <tr>
	<td>快捷操作：
	<a href="<!--{$cpfile}-->?c=category" target="main">栏目设置</a>&nbsp;|&nbsp;
	<a href="<!--{$cpfile}-->?c=module" target="main">模型设置</a>&nbsp;|&nbsp;
	<a href="<!--{$cpfile}-->?c=admin&a=editpassword" target="main">修改密码</a>&nbsp;|&nbsp;
    <a href="<!--{$cpfile}-->?c=login&a=logout" target='_top'>退出登录</a>&nbsp;|&nbsp;
	<a href="<!--{$cpfile}-->?c=setting&a=clearcache" target='main'>清除页面缓存</a>&nbsp;|&nbsp;
	<a href="<!--{$cpfile}-->?c=setting&a=updatecache" target='main'>更新数据缓存</a>
	</td>
	<td align="right" style="padding-right:10px;"><!--{$copyright_poweredby}--> Version <!--{$copyright_version}--><!--{$copyright_release}--></td>
  </tr>
</table>
</div>	
</body>
</html>