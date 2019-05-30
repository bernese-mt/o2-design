<!--{include file="<!--{$tplpath}-->block_header.tpl"}-->
<div id="wrap">
  <!--{include file="<!--{$tplpath}-->block_top.tpl"}-->
  <!--{include file="<!--{$tplpath}-->block_banner.tpl"}-->

  <div id="web">
    
	<div id="left">
	  <h3 class="title"><span><a href="<!--{$url_index}-->">首 页</a>&nbsp;&nbsp;&gt;&gt;&nbsp;&nbsp;<!--{$thepath}--></span></h3>
	  <div class="webcontent">
	    
		<div id="download_list">
		  <!--{foreach $download as $volist}-->
		  <dl id="plug1">
		    <dt><a href="<!--{$volist.url}-->"><!--{$volist.title}--></a></dt>
			<dd>
			  <div><a href="<!--{$volist.url}-->">查看详细</a></div>
			  <span><b>文件大小</b>：<!--{$volist.filesize}--> K</span>
			  <span><b>点击次数</b>：<!--{$volist.hits}--></span>
			  <span><b>发布日期</b>：<!--{$volist.addtime|date_format:'%Y/%m/%d'}--></span>
			</dd>
		  </dl>
		  <!--{/foreach}-->
		  
		  <!--{if $showpage!=""}-->
		  <div class="clear"></div>
		  <div class="pagecode"><!--{$showpage}--></div> 
		  <!--{/if}-->

		</div>
	  </div>
	
	</div><!--$left //-->

	<div id="right">
      <h3 class="title"><span>下载分类</span></h3>
	  <div class="webnav"> 
        <div id="web-sidebar">
		  <!--{assign var='downcategory' value=vo_category("type={rootcat} rootid={4}")}-->
		  <!--{foreach $downcategory as $volist}-->
          <dl>
		    <dt class="part2" id="part1-id<!--{$volist.cateid}-->"><a href="<!--{$volist.url}-->"><!--{$volist.catname}--></a></dt>
		  </dl>	
		  <!--{/foreach}-->
        </div>
      </div>
      <!--{include file="<!--{$tplpath}-->block_newnews.tpl"}--> 
      <!--{include file="<!--{$tplpath}-->block_contact.tpl"}-->  
    </div><!--#right //-->

    <div style=" clear:both;"></div>
  </div>

  <!--{include file="<!--{$tplpath}-->block_footer.tpl"}-->
</div>
<script type="text/javascript" src="<!--{$skinpath}-->js/screen.js"></script>
</body>
</html>