<!--{include file="<!--{$tplpath}-->block_header.tpl"}-->
<div id="wrap">
  <!--{include file="<!--{$tplpath}-->block_top.tpl"}-->
  <!--{include file="<!--{$tplpath}-->block_banner.tpl"}-->
  <style type="text/css">.other {width: 60px;padding-right: 6px;padding-left:2px;}</style>

  <div id="web">
    
    <div id="left">
	  <h3 class="title"><span><a href="<!--{$url_index}-->">首 页</a>&nbsp;&nbsp;&gt;&gt;&nbsp;&nbsp;<!--{$thepath}--></span></span></h3>
	  <div class="webcontent">
	    <div id="showproduct">
		  <dl>
		    <dt style="width:364px;">
			  <!--{if $photo.uploadfiles!=''}-->
			  <span class='info_img' id='imgqwe'><a id='view_bigimg' href='<!--{$photo.uploadfiles}-->' title="查看大图" target='_blank'><img id='view_img' border='0'  style="width:350px; height:200px" onload="javascript:DrawImage(this,'200','200');" src='<!--{$photo.uploadfiles}-->'></a></span>
			  <script type='text/javascript'>var zoomImagesURI   = '<!--{$skinpath}-->images/zoom/';</script>
			  <script src='<!--{$skinpath}-->js/zoom.js' language='javascript' type='text/javascript'></script>
			  <script src='<!--{$skinpath}-->js/zoomhtml.js' language='javascript' type='text/javascript'></script>
			  <script type='text/javascript'>	window.onload==setupZoom();	</script>
			  <!--{else}-->
			  <span class='info_img'><img id='view_img' border='0' onload="javascript:DrawImage(this,'200','200');" src='<!--{$photo.thumbfiles}-->'></span>
			  <!--{/if}-->
			  
			  <div style="padding-top:5px;"></div>
              <span class='other' onmouseover="changepic('<!--{$photo.uploadfiles}-->')"><img  border='0' width="50" height="50" src='<!--{$photo.thumbfiles}-->'></span>
              <!--{if !empty($photo.gallery)}-->
               <!--{foreach $photo.gallery as $val}-->
              <span class='other' onmouseover="changepic('<!--{$val.imgurl}-->')"><img  border='0' width="50" height="50" src='<!--{$val.imgthumb}-->'></span>
              <!--{/foreach}-->
              <!--{/if}-->
			</dt>
			
			<dd style="width:326px;">
			  <ul class="list">
			    <li class="title"><h1>名称 ：<span><!--{$photo.title}--></span></h1></li>
				<li><b>分类 : </b><span><a href="<!--{$catinfo.url}-->"><!--{$catinfo.catname}--></a></span></li>
				<li><b>日期 : </b><span><!--{$photo.addtime|date_format:'%Y-%m-%d'}--></span></li>
			  </ul>
			</dd>
		  </dl>
		  
		  <div style="clear:both;"></div>
		  <h3 class="hr"><span>图片内容</span></h3>
		  <div class="text editor">
		    <!--{$photo.content}-->
			<br />
		    <!--{assign var='pluginaction' value=XHook::doAction('content_share')}-->
		  </div>
		  <div class="hits">点击次数：<span><!--{$photo.hits}--></span>&nbsp;&nbsp;发布日期：<!--{$photo.addtime|date_format:"%Y/%m/%d"}-->&nbsp;&nbsp;【<a href='javascript:window.print()'>打印此页</a>】&nbsp;&nbsp;【<a href='javascript:self.close()'>关闭</a>】</div>
		  
		  <div class="page">上一篇：<!--{$previous_item}--><br>下一条：<!--{$next_item}--></div>
		</div>
      </div>

    </div><!-- $left //-->
	
	<div id="right">
      <h3 class="title"><span>图片分类</span></h3>
	  <div class="webnav"> 
        <!--{include file="<!--{$tplpath}-->block_photocat.tpl"}-->
      </div>

      <!--{include file="<!--{$tplpath}-->block_newnews.tpl"}--> 
      <!--{include file="<!--{$tplpath}-->block_contact.tpl"}-->  

    </div><!--#right //-->
    <div style="clear:both;"></div>
  </div>
  <!--{include file="<!--{$tplpath}-->block_footer.tpl"}-->
</div>
<script type="text/javascript" src="<!--{$skinpath}-->js/screen.js"></script>
<script language="javascript">
function changepic(val){
	$("#view_img").attr("src",val);
	$("#view_bigimg").attr("href",val)
}
</script>
</body>
</html>