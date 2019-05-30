<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<!--{$page_charset}-->" />
<title>管理员</title>
<!--{include file="<!--{$cptplpath}-->headerjs.tpl"}-->
<!--{assign var='pluginevent' value=XHook::doAction('adm_head')}-->
</head>
<body>
<!--{assign var='pluginevent' value=XHook::doAction('adm_main_top')}-->
<!--{if $a eq "run"}-->
<div class="main-wrap">
  <form name="myform" method="post" action="<!--{$cpfile}-->?c=setting" />
  <input type="hidden" name="a" value="savebase" />
  <div class="path"><p>当前位置：系统设置<span>&gt;&gt;</span>基础设置<span>&gt;&gt;</span><a href="<!--{$cpfile}-->?c=setting">站点设置</a></p></div>
  <div class="main-cont">
	<h3 class="title">站点信息设置</h3>
	<div class="set-area">
	  <div class="form web-info-form">
		<div class="form-row">
		  <label class="form-field">网站名称</label>
		  <div class="form-cont"><input name="sitename" id="sitename" class="input-txt" type="text" value="<!--{$sitename}-->" /><span id="dsitename"></span></div>
		</div>
		<div class="form-row">
		  <label class="form-field">网站地址</label>
		  <div class="form-cont"><input name="siteurl" id="siteurl" class="input-txt" type="text" value="<!--{$siteurl}-->" /><span id="dsiteurl"></span><p class="form-tips">（以“http://”开头，“/”结束）</p></div>
		</div>
		<div class="form-row">
		  <label class="form-field">备案号码</label>
		  <div class="form-cont"><input name="icpcode" id="icpcode" class="input-txt" type="text" value="<!--{$icpcode}-->" /><span id="dicpcode"></span><p class="form-tips">（网站备案信息将显示在页面底部）</p></div>
		</div>
		<div class="form-row">
		  <label for="declare" class="form-field">流量统计代码</label>
		  <div class="form-cont"><textarea name="tjcode" id="tjcode" class="input-area area-s4 code-area" style="width:500px;height:60px;"><!--{$tjcode}--></textarea></div>
		</div>
	  </div>
	</div>
    
	<h3 class="title">网站LOGO设置</h3>
	<div class="set-area">
	  <div class="form web-info-form">
	    <div class="form-row">
		  <label class="form-field">LOGO图片</label>
		  <div class="form-cont">

		  <table border="0" cellspacing="0" cellpadding="0">
		    <tr>
			  <td><input type="text" name="logo" id="logo" value="<!--{$logo}-->" class="input-txt" /> <span id='dlogo' class='f_red'></span></td>
			  <td>
			  <iframe id='iframe_t' border='0' frameborder='0' scrolling='no' style="width:260px;height:25px;padding:0px;margin:0px;" src='<!--{$cpfile}-->?c=upload&formname=myform&module=article&uploadinput=logo&multipart=sf_upload&previewid=previewsrc'></iframe>
			  </td>
			</tr>
		  </table>
		  上传图片只支持：gif,jpeg,jpg,png格式
			
		  </div>
		</div>
		<div class="form-row">
		  <label class="form-field">LOGO大小</label>
		  <div class="form-cont">宽：<input name="logowidth" id="logowidth" type="text" size="5" value="<!--{$logowidth}-->" />px；高：<input name="logoheight" id="logoheight" type="text" size="5" value="<!--{$logoheight}-->" />px</div>
		</div>
        
		<div class="form-row">
		  <label class="form-field">LOGO预览</label>
		  <div class="form-cont">
		  <span id="previewsrc">
		  <!--{if $logo != ''}-->
		  <img src='<!--{$urlpath}--><!--{$logo}-->' />
		  <!--{/if}-->
		  </span>
		  </div>
		</div>

		<div class="btn-area"><input type="submit" name="btn_save" class="button" value="更新保存" /></div>
	  </div>
	</div>
  </div>
  </form>
</div>
<!--{/if}-->

<!--{if $a eq "footer"}-->
<div class="main-wrap">
  <div class="path"><p>当前位置：系统设置<span>&gt;&gt;</span>基础设置<span>&gt;&gt;</span><a href="<!--{$cpfile}-->?c=setting&a=footer">网站底部信息</a></p></div>
  <div class="main-cont">
	<h3 class="title">网站底部信息</h3>
    <form name="myform" method="post" action="<!--{$cpfile}-->?c=setting" />
    <input type="hidden" name="a" value="savefooter" />
	<table cellpadding='2' cellspacing='1' class='tab'>
	  <tr>
		<td class='hback_1' width='15%'>底部信息：</td>
		<td class='hback' width='85%'>
		  <textarea name="content" id="content" style="overflow:auto;width:98%;height:300px;display:none;"><!--{$content}--></textarea>
		  <script>var editor;KindEditor.ready(function(K) {editor = K.create('#content'); });</script>
		</td>
	  </tr>
	  <tr>
		<td class='hback_none'></td>
		<td class='hback_none'><input type="submit" name="btn_save" class="button" value="更新保存" /></td>
	  </tr>
	</table>
	</form>
  </div>
  <div style="clear:both;"></div>
</div>
<!--{/if}-->

<!--{if $a eq "upload"}-->
<div class="main-wrap">
  <div class="path"><p>当前位置：系统设置<span>&gt;&gt;</span>基础设置<span>&gt;&gt;</span><a href="<!--{$cpfile}-->?c=setting&a=upload">上传图片设置</a></p></div>
  <div class="main-cont">
	<h3 class="title">上传图片设置，本功能需要PHP环境支持GD库才生效
缩略图按原图比例缩小，宽高不会超过本设定，但都不能小于60px</h3>

    <form name="myform" method="post" action="<!--{$cpfile}-->?c=setting" />
    <input type="hidden" name="a" value="saveupload" />
	<table cellpadding='1' cellspacing='2' class='tab'>
	  <tr>
	    <td class='hback_1'>PHP环境信息：</td>
		<td><font color="green">PHP环境允许最大上传：<!--{$php_upload_maxsize}-->；GD库：<!--{$gd_version}--></font></td>
	  </tr>
	  <tr>
	    <td class='hback_1'>设置最大上传：</td>
		<td><input type='text' name='uploadmaxsize' id='uploadmaxsize' value='<!--{$uploadmaxsize}-->' size="5" />M (大小不能超过PHP环境允许的最大值)</td>
	  </tr>
	  <tr>
		<td class='hback_1' width="20%">图片最大尺寸： </td>
		<td class='hback' width="80%">宽：<input type="text" name="maxthumbwidth" id="maxthumbwidth" size="5" value="<!--{$maxthumbwidth}-->" /> 像素（px）  高：<input type="text" name="maxthumbheight" id="maxthumbheight" size="5" value="<!--{$maxthumbheight}-->" /> 像素（px）<br />
	如果用户上传一些尺寸很大的数码图片，则程序会按照本设置进行缩小该图片并显示，<br />比如可以设置为 宽：1024px，高：768px，但都不能小于300px。设置为0，则不做任何处理。</td>
	  </tr>

	  <tr>
		<td class='hback_1'>文章缩略图大小： </td>
		<td class='hback'>宽：<input type="text" name="thumbwidth" id="thumbwidth" size="5" value="<!--{$thumbwidth}-->" /> 像素（px） ， 高：<input type="text" name="thumbheight" id="thumbheight" size="5" value="<!--{$thumbheight}-->" /> 像素（px）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>产品缩略图大小： </td>
		<td class='hback'>宽：<input type="text" name="productthumbwidth" id="productthumbwidth" size="5" value="<!--{$productthumbwidth}-->" /> 像素（px） ， 高：<input type="text" name="productthumbheight" id="productthumbheight" size="5" value="<!--{$productthumbheight}-->" /> 像素（px）</td>
	  </tr>
	  <tr>
		<td class='hback_1'>图库缩略图大小： </td>
		<td class='hback'>宽：<input type="text" name="photothumbwidth" id="photothumbwidth" size="5" value="<!--{$photothumbwidth}-->" /> 像素（px） ， 高：<input type="text" name="photothumbheight" id="photothumbheight" size="5" value="<!--{$photothumbheight}-->" /> 像素（px）</td>
	  </tr>
	</table>
	<h3 class="title">图片水印设置（需要GD库支持）</h3>
	<table cellpadding='1' cellspacing='2' class='tab'>
	  <tr>
		<td class="hback_1" width="20%">是否启用： </td>
		<td class="hback" width="80%"><input type="radio" name="watermarkflag" value="1"<!--{if $watermarkflag==1}--> checked<!--{/if}--> />是，<input type="radio" name="watermarkflag" value="0"<!--{if $watermarkflag==0}--> checked<!--{/if}--> />否</td>
	  </tr>
	  <tr>
		<td class="hback_1">水印图片地址： </td>
		<td class="hback"><input type="text" name="watermarkfile" id="watermarkfile" size="45" value="<!--{$watermarkfile}-->" /> <br />默认为tpl/static/images/watermark.png，只支持JPG/GIF/PNG格式，推荐用透明的png图片 <br /><font color="red">注意图片地址相对于网站根目录</font></td>
	  </tr>
	  <tr>
		<td class="hback_1">水印位置： </td>
		<td class="hback"><input type="radio" name="watermarkpos" value="1"<!--{if $watermarkpos==1}--> checked<!--{/if}--> />顶端居左 <input type="radio" name="watermarkpos" value="2"<!--{if $watermarkpos==2}--> checked<!--{/if}--> />顶端居右 <input type="radio" name="watermarkpos" value="3"<!--{if $watermarkpos==3}--> checked<!--{/if}--> />底端居左 <input type="radio" name="watermarkpos" value="4"<!--{if $watermarkpos==4}--> checked<!--{/if}--> />底端居右  <input type="radio" name="watermarkpos" value="0"<!--{if $watermarkpos==0}--> checked<!--{/if}--> />随机</td>
	  </tr>

	  <tr>
		<td class='hback_none'></td>
		<td class='hback_none'><input type="submit" name="btn_save" class="button" value="更新保存" /></td>
	  </tr>
	</table>
	</form>
  </div>
  <div style="clear:both;"></div>
</div>
<!--{/if}-->

<!--{if $a eq "rewrite"}-->
<div class="main-wrap">
  <div class="path"><p>当前位置：系统设置<span>&gt;&gt;</span>基础设置<span>&gt;&gt;</span><a href="<!--{$cpfile}-->?c=setting&a=rewrite">伪静态设置</a></p></div>
  <div class="main-cont">
	<h3 class="title">站点伪静态设置（开启Rewrite功能会将URL静态化，提高搜索引擎的抓取）</h3>
    <form name="myform" method="post" action="<!--{$cpfile}-->?c=setting" />
    <input type="hidden" name="a" value="saverewrite" />
	<table cellpadding='3' cellspacing='3' class='tab'>
	  <tr>
		<td class="hback_c1" width="20%">页面访问方式 </td>
		<td class="hback_c1" width="80%">
        <input type="radio" name="urlsuffix" id="urlsuffix" value="php"<!--{if $config.urlsuffix=='php'}--> checked<!--{/if}--> />PHP动态页&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" name="urlsuffix"  id="urlsuffix" value="html"<!--{if $config.urlsuffix=='html'}--> checked<!--{/if}--> />
        HTML伪静态 <br /><font color="red">
        </td>
	  </tr>
	  <tr>
		<td class='hback_none'></td>
		<td class='hback_none'><input type="submit" name="btn_save" class="button" value="编辑保存" /></td>
	  </tr>
	</table>
	<h3 class="title">伪静态规则说明：</h3>
	<table cellpadding='5' cellspacing='5' class='tab'>

	  <td style="color:#666666;line-height:20px;">
	  1、开启伪静态功能前，请确保您的网站支持相关伪静态；<br />
	  2、网站根目录伪静态规则文件：<br />
		apache.htaccess：适用于Apache；<br />
		rewrite.htaccess：适用于IIS，Rewrite3.1版本下有效，如果您的网站安装在二级目录，应该修改参数.htaccess文件 RewriteBase 对应目录；<br />
		nginx.htaccess：适用于Nginx<br />

		启用规则文件：根据您空间的Web解析环境，使用对应的规则文件，将规则文件命名为.htaccess即可，默认的.htaccess为Apache。<br />

       (温馨提示：选择THML伪静态时，空间必须开启伪静态功能，否则无法使用)</font><br />
	  </td>
	</table>
	</form>
  </div>
  <div style="clear:both;"></div>
</div>
<!--{/if}-->

<!--{if $a eq "index_style"}-->
<div class="main-wrap">
  <div class="path"><p>当前位置：界面模板<span>&gt;&gt;</span>主题模板<span>&gt;&gt;</span><a href="<!--{$cpfile}-->?c=setting&a=index_style">首页配置</a></p></div>
  <div class="main-cont">
	<h3 class="title">首页配置</h3>

    <form name="myform" method="post" action="<!--{$cpfile}-->?c=setting" />
    <input type="hidden" name="a" value="save_index_style" />
	<table cellpadding='2' cellspacing='2' class='tab'>
	  <tr>
	    <td class='hback_1' width='15%'>文章模块最新：</td>
		<td class="hback" width='85%'><input type="text" name="articlenum" id="articlenum" class="input-s" value="<!--{$articlenum}-->" />条，标题长度：<input type="text" name="articlelen" id="articlelen" class="input-s" value="<!--{$articlelen}-->" />个字符</td>
	  </tr>
	  <tr>
	    <td class='hback_1'>产品模块最新：</td>
		<td class="hback"><input type="text" name="productnum" id="productnum" class="input-s" value="<!--{$productnum}-->" />条，标题长度：<input type="text" name="productlen" id="productlen" class="input-s" value="<!--{$productlen}-->" />个字符</td>
	  </tr>
	  <tr>
	    <td class='hback_1'>图库模块最新：</td>
		<td class="hback"><input type="text" name="photonum" id="photonum" class="input-s" value="<!--{$photonum}-->" />条，标题长度：<input type="text" name="photolen" id="photolen" class="input-s" value="<!--{$photolen}-->" />个字符</td>
	  </tr>
	  <tr>
	    <td class='hback_1'>下载模块最新：</td>
		<td class="hback"><input type="text" name="downnum" id="downnum" class="input-s" value="<!--{$downnum}-->" />条，标题长度：<input type="text" name="downlen" id="downlen" class="input-s" value="<!--{$downlen}-->" />个字符</td>
	  </tr>
	  <tr>
	    <td class='hback_1'>招聘模块最新：</td>
		<td class="hback"><input type="text" name="hrnum" id="hrnum" class="input-s" value="<!--{$hrnum}-->" />条，标题长度：<input type="text" name="hrlen" id="hrlen" class="input-s" value="<!--{$hrlen}-->" />个字符</td>
	  </tr>
	  <tr>
		<td class='hback_none'></td>
		<td class='hback_none'><input type="submit" name="btn_save" class="button" value="更新保存" /></td>
	  </tr>
	</table>
	</form>
  </div>
  <div style="clear:both;"></div>
</div>
<!--{/if}-->

<!--{if $a eq "main_style"}-->
<div class="main-wrap">
  <div class="path"><p>当前位置：界面模板<span>&gt;&gt;</span>主题模板<span>&gt;&gt;</span><a href="<!--{$cpfile}-->?c=setting&a=index_style">界面配置</a></p></div>
  <div class="main-cont">
	<h3 class="title">界面配置</h3>

    <form name="myform" method="post" action="<!--{$cpfile}-->?c=setting" />
    <input type="hidden" name="a" value="save_main_style" />
	<table cellpadding='2' cellspacing='2' class='tab'>
	  <tr>
	    <td class='hback_1' width='15%'>文章模块列表页：</td>
		<td class="hback" width='85%'>每页显示 <input type="text" name="articlepagesize" id="articlepagesize" class="input-s" value="<!--{$articlepagesize}-->" />条</td>
	  </tr>
	  <tr>
	    <td class='hback_1'>产品模块列表页：</td>
		<td class="hback">每页显示 <input type="text" name="productpagesize" id="productpagesize" class="input-s" value="<!--{$productpagesize}-->" />条</td>
	  </tr>
	  <tr>
	    <td class='hback_1'>图库模块列表页：</td>
		<td class="hback">每页显示 <input type="text" name="photopagesize" id="photopagesize" class="input-s" value="<!--{$photopagesize}-->" />条</td>
	  </tr>
	  <tr>
	    <td class='hback_1'>下载模块列表页：</td>
		<td class="hback">每页显示 <input type="text" name="downpagesize" id="downpagesize" class="input-s" value="<!--{$downpagesize}-->" />条</td>
	  </tr>
	  <tr>
	    <td class='hback_1'>招聘模块列表页：</td>
		<td class="hback">每页显示 <input type="text" name="hrpagesize" id="hrpagesize" class="input-s" value="<!--{$hrpagesize}-->" />条</td>
	  </tr>
	  <tr>
		<td class='hback_none'></td>
		<td class='hback_none'><input type="submit" name="btn_save" class="button" value="更新保存" /></td>
	  </tr>
	</table>
	</form>
  </div>
  <div style="clear:both;"></div>
</div>
<!--{/if}-->


<!--{assign var='pluginevent' value=XHook::doAction('adm_footer')}-->
</body>
</html>
