<!--{include file="<!--{$tplpath}-->block_header.tpl"}-->
<div id="wrap">
  <!--{include file="<!--{$tplpath}-->block_top.tpl"}-->
  <!--{include file="<!--{$tplpath}-->block_banner.tpl"}-->
  <style type="text/css">.other {width: 60px;padding-right: 6px;padding-left:2px;}</style>
  <div id="web">
    <div id="left">
	  <h3 class="title"><span><a href="<!--{$url_index}-->">首 页</a>&nbsp;&nbsp;&gt;&gt;&nbsp;&nbsp;<!--{$thepath}--></span></h3>
	  <div class="webcontent">
	    <div id="showproduct">
		  <dl>
		    <dt style="width:364px;">
			  
			  <!--{if $product.uploadfiles!=''}-->
			  <span class='info_img' id='imgqwe'><a id='view_bigimg' href='<!--{$product.uploadfiles}-->' title="查看大图" target='_blank'>
              <img id='view_img' border='0' style="width:350px; height:200px"   onload="javascript:DrawImage(this,'350','200');" src='<!--{$product.uploadfiles}-->'></a></span>
			  <script type='text/javascript'>var zoomImagesURI   = '<!--{$skinpath}-->images/zoom/';</script>
			  <script src='<!--{$skinpath}-->js/zoom.js' language='javascript' type='text/javascript'></script>
			  <script src='<!--{$skinpath}-->js/zoomhtml.js' language='javascript' type='text/javascript'></script>
			  <script type='text/javascript'>	window.onload==setupZoom();	</script>
			  <!--{else}-->
			  <span class='info_img'><img id='view_img' border='0' onload="javascript:DrawImage(this,'350','200');" src='<!--{$product.thumbfiles}-->'></span>
			  <!--{/if}-->
			  <div style="padding-top:5px;"></div>
              <span class='other' onmouseover="changepic('<!--{$product.uploadfiles}-->')"><img  border='0' width="50" height="50" src='<!--{$product.thumbfiles}-->'></span>
              <!--{if !empty($product.gallery)}-->
               <!--{foreach $product.gallery as $val}-->
              <span class='other' onmouseover="changepic('<!--{$val.imgurl}-->')"><img  border='0' width="50" height="50" src='<!--{$val.imgthumb}-->'></span>
              <!--{/foreach}-->
              <!--{/if}-->
			</dt>
			
			<dd style="width:326px;">
			  <ul class="list">
			    <li class="title"><h1>名称 ：<span><!--{$product.productname}--></span></h1></li>
				<li><b>编号 : </b><span><!--{$product.productsn}--></span></li>
				<li><b>分类 : </b><span><a href="<!--{$catinfo.url}-->"><!--{$catinfo.catname}--></a></span></li>
				<li><b>日期 : </b><span><!--{$product.addtime|date_format:'%Y-%m-%d'}--></span></li>
                <li><b>价格 : </b><span><!--{$product.bprice}--></span></li>
				<li class="description"><span></span></li>
			  </ul>
			  <div class="feedback"><a href="<!--{$urlpath}-->c=guestbook">在线留言</a></div>
			</dd>
		  </dl>
		  
		  <div style="clear:both;"></div>
		  <h3 class="hr"><span>详细介绍</span></h3>
		  <div class="text editor">
		    <!--{$product.content}-->
			<br />
		    <!--{assign var='pluginaction' value=XHook::doAction('content_share')}-->
		  </div>
		  <div class="hits">点击次数：<span><!--{$product.hits}--></span>&nbsp;&nbsp;发布日期：<!--{$product.addtime|date_format:"%Y/%m/%d"}-->&nbsp;&nbsp;【<a href='javascript:window.print();'>打印此页</a>】&nbsp;&nbsp;【<a href='javascript:self.close()'>关闭</a>】</div>
		  
		  <div class="page">上一篇：<!--{$previous_item}--><br>下一篇：<!--{$next_item}--></div>
		</div>
      </div>

    </div><!-- $left //-->
    
    <div id="right">
	  <h3 class="title"><span>产品分类</span></h3>
	  <div class="webnav"> 
	  <!--{include file="<!--{$tplpath}-->block_productcat.tpl"}-->
	  </div><!-- $webnav -->
      <!--{include file="<!--{$tplpath}-->block_newnews.tpl"}--> 
      <!--{include file="<!--{$tplpath}-->block_contact.tpl"}-->
	</div><!-- $right //-->
    <div style="clear:both;"></div>

  </div>

  <!--{include file="<!--{$tplpath}-->block_footer.tpl"}-->
</div>
<script type="text/javascript" src="<!--{$skinpath}-->js/screen.js"></script>
</body>
</html>
<script language="javascript">
function changepic(val){
	$("#view_img").attr("src",val);
	$("#view_bigimg").attr("href",val)
}
</script>