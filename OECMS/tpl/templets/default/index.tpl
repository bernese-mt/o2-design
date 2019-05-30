<!--{include file="<!--{$tplpath}-->block_header.tpl"}-->
<div id="wrap">
  <!--{include file="<!--{$tplpath}-->block_top.tpl"}-->
	<!--{assign var='zone' value=get_zone('index_slide_banner')}-->
	<!--{if !empty($zone.ads)}-->
	<script src="<!--{$urlpath}-->tpl/static/js/jquery.KinSlideshow-1.2.1.min.js" type="text/javascript"></script>
	<script type="text/javascript">
	$(function(){
		$("#KinSlideshow").KinSlideshow({
				moveStyle:"left",
				intervalTime:5,
				mouseEvent:"mouseover",
				titleFont:{TitleFont_size:14,TitleFont_color:"#fff"},
				btn:{btn_bgColor:"#fff",btn_bgHoverColor:"#0088cd",
				btn_fontColor:"#333",btn_fontHoverColor:"#fff",btn_fontFamily:"Verdana",
				btn_borderColor:"#ddd",btn_borderHoverColor:"#efefef",
				btn_borderWidth:1,btn_bgAlpha:0.7},
				titleBar:{titleBar_height:40,titleBar_bgColor:"#5bafe1",titleBar_alpha:0.5}
		});
	})
	</script>
	<div id="KinSlideshow" style="visibility:hidden;"> 
	<!--{foreach $zone.ads as $volist}-->
	<a href="<!--{$volist.url}-->"><img src="<!--{$volist.uploadfiles}-->" width="<!--{$volist.width}-->" height="<!--{$volist.height}-->"  alt="<!--{$volist.title}-->" /></a>
	<!--{/foreach}-->
	</div>
	<!--{/if}-->

  <div id="main">

    <div id="left">

	  <h3 class="title">
	  <!--{category id='2' title='More' class='more' target='_blank'}-->	  
	  <span>产品类别</span></h3>
	  <div class="list1">
	    <div id="web-sidebar">
		  <!--{assign var='treeproduct' value=vo_category("type={treecat} rootid={2}")}-->
		  <!--{foreach $treeproduct as $parent}-->
		  <dl>
		    <dt class="part2" id="part1-id<!--{$parent.catid}-->">
			<b><a href="<!--{$parent.url}-->"><!--{$parent.catname}--></a></b>
			</dt>
			<dd class="part3dom">
			  <!--{foreach $parent.childcatgory as $child}-->
			  <h4 class="part3" id="part2-id<!--{$child.catid}-->"><a href="<!--{$child.url}-->"><!--{$child.catname}--></a></h4>
			  <!--{/foreach}-->
			</dd>
		  </dl>	
		  <!--{/foreach}-->
		</div>
		
		<!-- #web-sidebar //-->
		<script type="text/javascript">
			$(document).ready(function(){
				var Plug1 = 0; 
				var Plug2 = 1; 
				var i = 0;
				var SideBar = $("#web-sidebar");
				var SideBar_dt = SideBar.find("dt.part2");
				var SideBar_dd = SideBar.find("dd.part3dom");
				var part1_dom = $("#part1-id0"); 
				var part2_dom = $("#part2-id0"); 
				var part_dom_dd = part1_dom.next("dd.part3dom");
					SideBar_dd.css("display","none");
					part1_dom.addClass("ondown");
					part2_dom.addClass("ondown");
				if(Plug1 == 1){ SideBar_dd.css("display","block"); SideBar_dt.addClass("part_on"); i = 1;} 
				
				if(Plug2 == 1 && part1_dom.length!=0){
						part_dom_dd.css("display","block");
						i = 1; 
				}
				
				SideBar_dt.click(function(){
					i++;if(i>1)i=0;
					i==1?SideBar_on($(this)):SideBar_out($(this));	
				});
				
		//****************


			});
			
			function SideBar_on(dom){
				var dd = dom.next("dd.part3dom")
					dom.addClass("part_on");
					dom.removeClass("part_out");
					dd.css("display","block");
			}
			
			function SideBar_out(dom){
				var dd = dom.next("dd.part3dom")
					dom.addClass("part_out");
					dom.removeClass("part_on");
					dd.css("display","none");
			}

			
		//************

		</script>


      </div>
	  <!-- $list1 //--->
	  
	  <h3 class="title">
	  <!--{category id='1' title='More' class='more' target='_blank'}-->  
	  <span>最新动态</span></h3>
	  <ul class="list2">
     <!--{assign var='newnews' value=vo_list("mod={article} where={v.treeid='1'} orderby={ORDER BY v.articleid DESC}")}-->
	    <!--{foreach $newnews as $volist}-->
	    <li><a href="<!--{$volist.url}-->" title="<!--{$volist.title}-->"><!--{$volist.sort_title}--></a></li>
		<!--{/foreach}-->
	  </ul>

	  <h3 class="title">
	  <!--{category id='25' title='More' class='more' target='_blank'}-->
	  <span>联系方式</span></h3>
	  <div class="text editor">
	    <p><!--{label name='contact'}--></p>
	  </div>
	</div>
	<!-- #left //-->

	<div id="right">
	  <h3 class="title">
	  <!--{category name='aboutus' title='更多' class='more' target='self'}-->
	  <span>公司简介</span></h3>
	  <div class="text">
	    <div class="editor">
		  <p>
		  <!--{assign var='ads' value=get_ads('index_ads_01')}-->
		  <!--{if !empty($ads)}-->
		  <img width="<!--{$ads.width}-->" height="<!--{$ads.height}-->" hspace="2" align="left" vspace="2" src="<!--{$ads.uploadfiles}-->" />
          <!--{/if}-->
		  </p>
		  <p><!--{label name='about'}--></p>
		</div>
	  </div>
	  
	  <h3 class="title line">
	  <!--{category id='2' title='More' class='more' target='_blank'}-->
	  <span>最新产品</span></h3>
	  <div class="list">
	    <ul id="drawimg">
		  <!--{assign var='newproduct' value=vo_list("mod={product} where={v.treeid='2'}")}-->
		  <!--{foreach $newproduct as $volist}-->
		  <li style="width:158px; height:183px;">
		    <a href="<!--{$volist.url}-->" class="imgbox" target="_blank"><img src="<!--{$volist.thumbfiles}-->" onload="javascript:DrawImage(this,'150','150');" alt="<!--{$volist.productname}-->" /></a>
			<h4><a href="<!--{$volist.url}-->" title="<!--{$volist.productname}-->"><!--{$volist.sort_productname}--></a></h4>
		  </li>
		  <!--{/foreach}-->
		</ul>
		<div style="clear:both;"></div>
	  </div><!-- #list //-->
	
	</div><!-- #right //-->
	<div style="clear:both;"></div>
  </div><!-- #main //-->
  <!--{include file="<!--{$tplpath}-->block_footer.tpl"}-->
</div>
<script type="text/javascript" src="<!--{$skinpath}-->js/screen.js"></script>
</body>
</html>