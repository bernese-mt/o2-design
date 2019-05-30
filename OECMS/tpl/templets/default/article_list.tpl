<!--{include file="<!--{$tplpath}-->block_header.tpl"}-->
<div id="wrap">
  <!--{include file="<!--{$tplpath}-->block_top.tpl"}-->
  <!--{include file="<!--{$tplpath}-->block_banner.tpl"}-->

  <div id="web">
    <div id="left">
	  <h3 class="title"><span><a href="<!--{$config.siteurl}-->">首 页</a>&nbsp;&nbsp;>>&nbsp;&nbsp;<!--{$thepath}--></span></h3>
	  <div class="webcontent">
	    
		<div id="news_list">
		  <ul id="plug1">
		    <!--{foreach $article as $volist}-->
		    <li>
			  <span><!--{$volist.addtime|date_format:'%Y-%m-%d'}--></span>
			  <a href="<!--{$volist.url}-->"><!--{$volist.title}--></a>
			</li>
			<!--{/foreach}-->
		  </ul>
		  <!--{if $showpage!=""}-->
		  <div class="clear"></div>
		  <div class="pagecode"><!--{$showpage}--></div> 
		  <!--{/if}-->
		</div>
	  
	  </div>
	</div><!--#left //-->

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