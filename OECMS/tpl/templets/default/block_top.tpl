  <div id="top">
	<div class="sidebar">
	  <div class="lang">
        <a href="###" onclick="addfavorite('<!--{$config.sitename}-->', '<!--{$config.siteurl}-->');">添加收藏</a>
        <span>|</span>
	    <a href="###" onclick="sethomepage(this, window.location);">设为主页</a>
	  </div>
	  <h1><!--{label name='toptips'}--></h1>
	</div>		 
	<a><img src="<!--{$config.logo}-->" style="margin-top:5px; margin-left:5px;"/></a>
  </div><!-- #top //-->
  
  <div id="head">		 
    <ul id="nav" style=" width:780px;">
	  <li class="home"></li>
	  <!--{assign var='mymenu' value=vo_category("type={sedmenu}")}-->
	  <!--{foreach $mymenu as $parent}-->
	  <li class="class1" id="nav_<!--{$parent.catid}-->">
	  <!--{if $parent.url==''}-->
	  <a><!--{$parent.catname}--></a>
	  <!--{else}-->
	  <a href="<!--{$parent.url}-->"><!--{$parent.catname}--></a>
	  <!--{/if}-->
	  <!--{if !empty($parent.childmenu)}-->
	    <ul style="display:none;">
		  <!--{foreach $parent.childmenu as $child}-->
	      <li><a href="<!--{$child.url}-->"><!--{$child.catname}--></a></li>
		  <!--{/foreach}-->
	    </ul>
	  <!--{/if}-->
	  </li>
	  <li class="line"></li> 
	  <!--{/foreach}-->
	</ul>
    
	<form method="post" action="<!--{$urlpath}-->index.php?c=search" name="myform" id="myform" onsubmit="return checksearch();" />
	<div class="search">
	  <h3></h3>
	  <ul>
	    <li>
		<select name="type" id="type">
		<option value="product"<!--{if $type == 'product'}--> selected<!--{/if}-->>&nbsp;产品&nbsp;</option>
		<option value="photo"<!--{if $type == 'photo'}--> selected<!--{/if}-->>&nbsp;图库&nbsp;</option>
		<option value="article"<!--{if $type == 'article'}--> selected<!--{/if}-->>&nbsp;文章&nbsp;</option>
		<option value="download"<!--{if $type == 'download'}--> selected<!--{/if}-->>&nbsp;下载&nbsp;</option>
		<option value="hr"<!--{if $type == 'hr'}--> selected<!--{/if}-->>&nbsp;招聘&nbsp;</option>
		</select>
		<span class='parasearch_input'><input type='text' name='keyword' id="keyword" value="<!--{$keyword}-->" /></span>
		</li>
		<li><span class='parasearch_search'><input class='searchimage' type='image' src='<!--{$skinpath}-->images/navserach.gif' /></span></li>
	  </ul>
	</div>
	</form>
	<div class="clear"></div>
  </div>
<script language="javascript">
function checksearch(){
	if($("#type").val()==""){
		alert("请选择搜索频道.");
		return false;
	}
	if($("#keyword").val()==""){
		alert("关键字不能为空.");
		return false;
	}	
}
</script>