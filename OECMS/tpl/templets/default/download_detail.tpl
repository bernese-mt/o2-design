<!--{include file="<!--{$tplpath}-->block_header.tpl"}-->
<div id="wrap">
  <!--{include file="<!--{$tplpath}-->block_top.tpl"}-->
  <!--{include file="<!--{$tplpath}-->block_banner.tpl"}-->
  <div id="web">
  
    <div id="left">
	  
	  <h3 class="title"><span><a href="<!--{$url_index}-->">首 页</a>&nbsp;&nbsp;&gt;&gt;&nbsp;&nbsp;<!--{$thepath}--></span></h3>
	  <div class="webcontent">
	    <div id="showdownload">
		  <h1 class="title"><!--{$download.title}--></h1>
		  <div class="Para">
		    <ul>
			  <li class='info_filesize'><b>文件大小 ：</b> <!--{$download.filesize}--> K</li>
			  <li class='info_para1' ><b>下载分类 ：</b> <!--{$download.catname}--></li>
			  <li class='info_para2' ><b>下载次数 ： </b> <!--{$download.downs}--> 次</li>
			</ul>
			<span class='info_download'><a href='<!--{$download.fileurl}-->' target='_blank'>点击下载</a></span>
		  </div>
          <div class="text editor">
		  <p><!--{$download.content|htmlencode}--></p>
		  <br />
		  <!--{assign var='pluginaction' value=XHook::doAction('content_share')}-->
		  </div>
		  <div class="hits">浏览次数：<span><!--{$download.hits}--></span>&nbsp;&nbsp;发布日期：<!--{$download.addtime|date_format:"%Y/%m/%d"}-->&nbsp;&nbsp;【<a href='javascript:window.print()'>打印此页</a>】&nbsp;&nbsp;【<a href='javascript:self.close()'>关闭</a>】</div>
		  <div class="page">上一条：<!--{$previous_item}--><br>下一条：<!--{$next_item}--></div>
		</div>
      </div>

    </div><!-- #left //-->

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

    <div style="clear:both;"></div>
  </div>

  <!--{include file="<!--{$tplpath}-->block_footer.tpl"}-->
</div>
<script type="text/javascript" src="<!--{$skinpath}-->js/screen.js"></script>
</body>
</html>