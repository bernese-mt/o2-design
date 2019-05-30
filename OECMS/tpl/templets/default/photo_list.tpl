<!--{include file="<!--{$tplpath}-->block_header.tpl"}-->
<div id="wrap">
  <!--{include file="<!--{$tplpath}-->block_top.tpl"}-->
  <!--{include file="<!--{$tplpath}-->block_banner.tpl"}-->
  <style type="text/css">#img_list #plug1 li{ width:158px; height:183px;}</style>

  <div id="web">
    
	<div id="left">
	  <h3 class="title"><span><a href="<!--{$url_index}-->">首 页</a>&nbsp;&nbsp;>>&nbsp;&nbsp;<!--{$thepath}--></span></h3>
	  <div class="webcontent">
	    <div id="img_list">
		  <div id="plug1">
		    <ul>
			  <!--{foreach $photo as $volist}-->
			  <li>
			    <span class='info_img' ><a href='<!--{$volist.url}-->' target='_blank'  ><img src="<!--{$volist.thumbfiles}-->" alt="<!--{$volist.title}-->" onload="javascript:DrawImage(this,'150','150');" /></a></span><span class='info_title' ><a title='<!--{$volist.title}-->' href="<!--{$volist.url}-->" target='_blank' ><!--{$volist.title}--></a></span>
			  </li>
			  <!--{/foreach}-->
			</ul>
			<div style=" clear:both;"></div>
		  </div>
		  <!--{if $showpage!=""}-->
		  <div class="clear"></div>
		  <div class="pagecode"><!--{$showpage}--></div> 
		  <!--{/if}-->
		</div>
	  </div>
	</div><!-- $left //-->
	
	<div id="right">
      <h3 class="title"><span>图库分类</span></h3>
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
</body>
</html>