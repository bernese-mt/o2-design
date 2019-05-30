<!--{include file="<!--{$tplpath}-->block_header.tpl"}-->
<div id="wrap">
  <!--{include file="<!--{$tplpath}-->block_top.tpl"}-->
  <!--{include file="<!--{$tplpath}-->block_banner.tpl"}-->
  <div id="web">
    <div id="left">
      <h3 class="title"><span><a href="<!--{$url_index}-->">首 页</a>&nbsp;&nbsp;&gt;&gt;&nbsp;&nbsp;<!--{$menu.ch_product_search.chname}-->&nbsp;&nbsp;&gt;&gt;&nbsp;&nbsp;<!--{$keyword}--></span></h3>
	  <div class="webcontent">
	    <div id="product-list">
		  
		  <div id="plug2">
		    <!--{foreach $search as $volist}-->
		    <dl style="height:165px;">
			  <dt style="width:158px;">
			    <a href="<!--{$volist.url}-->" title="<!--{$volist.productname}-->" target="_blank"><img src="<!--{$volist.thumbfiles}-->" alt="<!--{$volist.productname}-->" title="<!--{$volist.productname}-->" onload="javascript:DrawImage(this,'150','150');" /></a>
			  </dt>
			  <dd style="width:167px;">
			    <ul>
				  <li><b>名称 : </b><span class="title"><a href="<!--{$volist.url}-->" title="<!--{$volist.productname}-->"><!--{$volist.productname|truncate:15:0:"utf-8"}--></a></span></li>
				  <li><b>编号 : </b><span><!--{$volist.productsn}--></span></li>
				  <li><b>分类 : </b><span><a href="<!--{$volist.caturl}-->"><!--{$volist.catname}--></a></span></li>
				  <li><b>日期 : </b><span><!--{$volist.addtime|date_format:'%Y-%m-%d'}--></span></li>
				</ul>
				<div class="detail"><a href="<!--{$volist.url}-->" title="详细介绍" target="_blank"><img src="<!--{$skinpath}-->images/picd.gif" title="详细介绍" alt="详细介绍" /></a></div>
			  </dd>
			</dl>
			<!--{/foreach}-->
		  
		  </div><!-- #plug2 //-->
		  <div style="clear:both;"></div>
		</div>
		<!--{if $showpage!=""}-->
		<div class="clear"></div>
		<div class="pagecode"><!--{$showpage}--></div> 
		<!--{/if}-->	  
	  </div><!-- $webcontent //-->
	
	</div><!-- #left //-->

    <div id="right">
	  <h3 class="title"><span>产品分类</span></h3>
	  <div class="webnav"> 
	    <div id="web-sidebar">
		  <!--{assign var='treeproduct' value=vo_category("type={treecat} rootid={2}")}-->
		  <!--{foreach $treeproduct as $parent}-->
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