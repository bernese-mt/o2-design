<!--{include file="<!--{$tplpath}-->block_header.tpl"}-->
<div id="wrap">
  <!--{include file="<!--{$tplpath}-->block_top.tpl"}-->
  <!--{include file="<!--{$tplpath}-->block_banner.tpl"}-->

  <div id="web">
  
    <div id="left">
	  <h3 class="title"><span><a href="<!--{$url_index}-->">首 页</a>&nbsp;&nbsp;>>&nbsp;&nbsp;<!--{$thepath}--></span></h3>
	  <div class="webcontent">
	    <div id="showjob">
		  <h1 class="title">招聘职位：<!--{$hr.title}--></h1>
		  <div class="para">
		    <ul>
			  <li class='info_deal'><b>职位编号 ：</b> #<!--{$hr.hrid}--></li>
			  <li class='info_person'><b>招聘人数 ： </b> <!--{$hr.number}--> 人</li>
			  <li class='info_address'><b>工作地点 ：</b> <!--{$hr.workarea}--></li>
			  <li class='info_updatetime'><b>发布日期 ：</b> <!--{$hr.addtime|date_format:'%Y-%m-%d'}--></li>
			  <li class='info_validity'><b>浏览次数 ：</b> <!--{$hr.hits}--></li>
			</ul>
		  </div>
		  <h3 class="hr"><span>招聘内容</span></h3>
		  <div class="text editor">
		  <!--{$hr.content}-->
			<br />
		    <!--{assign var='pluginaction' value=XHook::doAction('content_share')}-->
		  </div>

		  <!--{if !empty($hr.hrcontact)}-->
		  <h3 class="hr"><span>招聘负责人联系方式 </span></h3>
		  <div class="text editor"><!--{$hr.hrcontact}--></div>
		  <!--{/if}-->

		  <div class="hits"></div>
		  <div class="page">上一条：<!--{$previous_item}--><br>下一条：<!--{$next_item}--></div>
		</div>
	  </div>
	</div><!--#left //-->

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