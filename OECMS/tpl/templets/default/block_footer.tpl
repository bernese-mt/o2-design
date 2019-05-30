  <div id="footer">
    <div class="nav">
	<!--{assign var='submenu' value=vo_category("type={submenu}")}-->
	<!--{foreach $submenu as $volist}-->
	<a href="<!--{$volist.url}-->"><!--{$volist.catname}--></a>&nbsp;&nbsp;
	<!--{/foreach}-->
	</div>
	<div class="text">
	  <div class="powered_by_oecms">
	    <!--{$config.site_footer}--><br />
		<!--{if $config.icpcode !=''}--><!--{$config.icpcode}--><!--{/if}--> <!--{$copyright_poweredby}-->&nbsp;&nbsp;
		&nbsp;&nbsp;<!--{if $config.tjcode !=''}--><!--{$config.tjcode}--><!--{/if}-->
	  </div>
	</div>
  </div>
<!--{assign var='pluginevent' value=XHook::doAction('event_runtime')}-->
<!--{assign var='pluginevent' value=XHook::doAction('event_online')}-->