	  <h3 class="title"><span>关于我们</span></h3>
	  <div class="webnav"> 
		<div id="web-sidebar">
		  <!--{assign var='aboutcat' value=vo_category("type={rootcat} rootid={6}")}-->
		  <!--{foreach $aboutcat as $val}-->
		  <dl>
		    <dt class="part2">
			  <a href="<!--{$val.url}-->"><!--{$val.catname}--></a>
			</dt>
		  </dl>	
		  <!--{/foreach}-->
		</div>
	  </div>
