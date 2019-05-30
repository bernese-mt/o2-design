<!--{include file="<!--{$tplpath}-->block_header.tpl"}-->
<div id="wrap">
  <!--{include file="<!--{$tplpath}-->block_top.tpl"}-->
  <!--{include file="<!--{$tplpath}-->block_banner.tpl"}-->

  <div id="web">
  
    <div id="left">
	  <h3 class="title"><span><a href="<!--{$url_index}-->">首 页</a>&nbsp;&nbsp;&gt;&gt;&nbsp;&nbsp;<!--{$thepath}--></span></h3>
	  <div class="webcontent">

        <div id="job_list">
		  <dl id="plug1">
		    <dt>
			  <span>浏览次数</span>
			  <span>查看详细</span>
			  <span>发布日期</span>
			  <span>工作地点</span>
			  <span>招聘人数</span>
			  招聘职位
			</dt>
			<!--{foreach $hr as $volist}-->
			<dd>
			  <span><!--{$volist.hits}--></span>
			  <span><a href="<!--{$volist.url}-->">查看详细</a></span>
			  <span><!--{$volist.addtime|date_format:'%Y/%m/%d'}--></span>
			  <span><!--{$volist.workarea}--></span>
			  <span><!--{$volist.number}-->人</span>
			  <a href="<!--{$volist.url}-->"><!--{$volist.title}--></a>
			</dd>
			<!--{/foreach}-->
		  </dl>
        </div>
		  <!--{if $showpage!=""}-->
		  <div class="clear"></div>
		  <div class="pagecode"><!--{$showpage}--></div> 
		  <!--{/if}-->
	  </div>

	</div><!-- $left //-->

    <div id="right">
      <h3 class="title"><span>招聘分类</span></h3>
	  <div class="webnav"> 
        <div id="web-sidebar">
		  <!--{assign var='hrcategory' value=vo_category("type={rootcat} rootid={5}")}-->
		  <!--{foreach $hrcategory as $volist}-->
          <dl>
		    <dt class="part2"><a href="<!--{$volist.url}-->"><!--{$volist.catname}--></a></dt>
		  </dl>	
		  <!--{/foreach}-->
        </div>
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