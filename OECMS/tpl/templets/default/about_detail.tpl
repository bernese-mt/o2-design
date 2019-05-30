<!--{include file="<!--{$tplpath}-->block_header.tpl"}-->
<div id="wrap">
  <!--{include file="<!--{$tplpath}-->block_top.tpl"}-->
  <!--{include file="<!--{$tplpath}-->block_banner.tpl"}-->
  <div id="web">
    <div id="left">
	  <h3 class="title"><span><!--{$about.title}--></span></h3>
	  <div class="webcontent">
	    <div class="showtext editor">
		  <p><!--{$about.content}--></p>
		</div>
      </div>
	</div>
	<!---left //-->
    <div id="right">
	  <h3 class="title"><span>关于我们</span></h3>
	  <div class="webnav"> 
		<div id="web-sidebar">
		  <!--{assign var='aboutcat' value=vo_category("type={rootcat} rootid={<!--{$rootid}-->}")}-->
		  <!--{foreach $aboutcat as $val}-->
		  <dl>
		    <dt class="part2">
			  <a href="<!--{$val.url}-->"><!--{$val.catname}--></a>
			</dt>
		  </dl>	
		  <!--{/foreach}-->
		</div>
	  </div>
      <!--{include file="<!--{$tplpath}-->block_newnews.tpl"}--> 
      <!--{include file="<!--{$tplpath}-->block_contact.tpl"}-->  
    </div>
	<!-- $right //--->
    <div style="clear:both;"></div>
  </div>
  <!--#web //-->
  <!--{include file="<!--{$tplpath}-->block_footer.tpl"}-->
</div>
<script type="text/javascript" src="<!--{$skinpath}-->js/screen.js"></script>
</body>
</html>