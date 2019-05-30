	    <div id="web-sidebar">
		  <!--{assign var='treearticle' value=vo_category("type={treecat} rootid={<!--{$rootid}-->}")}-->
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