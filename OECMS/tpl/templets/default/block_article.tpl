      <h3 class="title line">
	    <!--{category name='news' class='more' title='More'}-->
		<span>最新文章</span>
	  </h3>
	  <ul class="list">
	    <!--{assign var='newnews' value=vo_list("mod={article} where={v.treeid=<!--{$treeid}-->} num={10}")}-->
	    <!--{foreach $newnews as $volist}-->
		<li><a href="<!--{$volist.url}-->" title="<!--{$volist.title}-->"><!--{$volist.sort_title}--></a></li>
		<!--{/foreach}-->
      </ul>