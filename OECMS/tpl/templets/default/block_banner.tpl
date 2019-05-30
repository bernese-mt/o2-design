<!--{assign var='ads' value=get_ads('banner_001')}-->
<!--{if !empty($ads)}-->
	<div id="flash">
    <a <!--{if !empty($ads.url) && $ads.url!='#'}-->href="<!--{$ads.url}-->" target="<!--{$ads.target}-->"<!--{/if}-->><img src="<!--{$ads.uploadfiles}-->" width='<!--{$ads.width}-->' height='<!--{$ads.height}-->' border='0' title="<!--{$ads.title}-->" /></a>
	</div>
<!--{/if}-->