<!--{include file="<!--{$tplpath}-->block_header.tpl"}-->
<div id="wrap">
  <!--{include file="<!--{$tplpath}-->block_top.tpl"}-->
  <!--{include file="<!--{$tplpath}-->block_banner.tpl"}-->

  <div id="web">
    <div id="left">
	  <h3 class="title"><span><a href="<!--{$config.siteurl}-->">首 页</a>&nbsp;&nbsp;>>&nbsp;&nbsp;<!--{$menu.ch_article_search.chname}-->&nbsp;&nbsp;&gt;&gt;&nbsp;&nbsp;<!--{$keyword}--></span></h3>
	  <div class="webcontent">
	    
		<div id="news_list">
		  <ul id="plug1">
		    <!--{foreach $search as $volist}-->
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
	    <div id="web-sidebar">
		  <!--{assign var='treearticle' value=vo_category("type={treecat} rootid={1}")}-->
		  <!--{foreach $treearticle as $parent}-->
		  <dl>
		    <dt class="part2" id="part1-id<!--{$parent.catid}-->"><a href="<!--{$parent.url}-->"><!--{$parent.catname}--></a></dt>
			<dd class="part3dom">
			  <!--{foreach $parent.childcatgory as $child}-->
			  <h4 class="part3" id="part2-id<!--{$child.catid}-->"><a href="<!--{$child.url}-->"><!--{$child.catname}--></a></h4>
			  <!--{/foreach}-->
			</dd>
		  </dl>	
		  <!--{/foreach}-->
		</div>
		<!-- #web-sidebar //-->
		<script type="text/javascript" src="<!--{$skinpath}-->js/sidebar.js"></script>
      </div>
      <!--{include file="<!--{$tplpath}-->block_newnews.tpl"}--> 
      <!--{include file="<!--{$tplpath}-->block_contact.tpl"}-->   

    </div><!--#right //-->

    <div style="clear:both;"></div>
  </div>

  <!--{include file="<!--{$tplpath}-->block_footer.tpl"}-->
</div>
<script type="text/javascript" src="<!--{$skinpath}-->js/screen.js"></script>
</body>
</html>