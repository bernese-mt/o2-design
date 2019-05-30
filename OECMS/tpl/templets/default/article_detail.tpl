<!--{include file="<!--{$tplpath}-->block_header.tpl"}-->
<div id="wrap">
  <!--{include file="<!--{$tplpath}-->block_top.tpl"}-->
  <!--{include file="<!--{$tplpath}-->block_banner.tpl"}-->
  <div id="web">
    <div id="left">
	  
	  <h3 class="title"><span><a href="<!--{$config.siteurl}-->">首 页</a>&nbsp;&nbsp;&gt;&gt;&nbsp;&nbsp;<!--{$thepath}--></span></h3>
	  <div class="webcontent">
	    <div id="shownews">
		  <h1 class="title"><!--{$article.title}--></h1>
		  <div class="text editor">
		    <div class="editor">
			<!--{$article.content}-->
			<br />
		    <!--{assign var='pluginaction' value=XHook::doAction('content_share')}-->
			</div>
		  </div>
		  
		  <div class="hits">点击次数：<span><!--{$article.hits}--></span>&nbsp;&nbsp;发布日期：<!--{$article.addtime|date_format:"%Y-%m-%d"}-->&nbsp;&nbsp;【<a href='javascript:window.print()'>打印此页</a>】&nbsp;&nbsp;【<a href='javascript:self.close()'>关闭</a>】</div>
		  <div class="page">上一篇：<!--{$previous_item}--><br>下一篇：<!--{$next_item}--></div>
        </div>
	  </div><!--#webcontent //-->
	
	</div><!--$left-->

    <div id="right">
      <h3 class="title"><span>资讯分类</span></h3>
	  <div class="webnav"> 
        <!--{include file="<!--{$tplpath}-->block_articlecat.tpl"}-->
      </div>
      <!--{include file="<!--{$tplpath}-->block_article.tpl"}--> 
      <!--{include file="<!--{$tplpath}-->block_contact.tpl"}-->   
    </div><!--#right //-->

    <div style="clear:both;"></div>
  </div>
  <!--{include file="<!--{$tplpath}-->block_footer.tpl"}-->
</div>
<script type="text/javascript" src="<!--{$skinpath}-->js/screen.js"></script>
</body>
</html>