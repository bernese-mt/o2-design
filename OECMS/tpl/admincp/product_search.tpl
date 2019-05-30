<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<!--{$page_charset}-->" />
<title>查询物品</title>
<!--{include file="<!--{$cptplpath}-->headerjs.tpl"}-->
</head>
<body>
<!--{if $a eq "search"}-->
<div class="main-wrap">
  <div class="main-cont">
	<div class="search-area ">
	  <form method="post" id="search_form" action="<!--{$cpfile}-->?c=product&a=search&sortid=<!--{$sortid}-->" />
	  <div class="item">
	    <label>模块：</label><!--{$select_tree}-->&nbsp;&nbsp;
		<label>物品名称：</label><input type="text" id="sname" name="sname" size="15" class="input-150" value="<!--{$sname}-->" />&nbsp;&nbsp;&nbsp;
		<input type="submit" class="button_s" value="搜 索" />
	  </div>
	  </form>
	</div>
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table" align="center">
	  <thead class="tb-tit-bg">
	  <tr>
		<th width="12%"><div class="th-gap">物品ID</div></th>
		<th width="18%"><div class="th-gap">分类</div></th>
		<th width="35%"><div class="th-gap">名称</div></th>
		<th width="12%"><div class="th-gap">公开价格</div></th>
		<th width="12%"><div class="th-gap">销售价格</div></th>
		<th><div class="th-gap">选择</div></th>
	  </tr>
	  </thead>
	  <tfoot class="tb-foot-bg"></tfoot>
	  <!--{foreach $product as $volist}-->
	  <tr onMouseOver="overColor(this)" onMouseOut="outColor(this)">
	    <td align="center"><!--{$volist.productid}--></td>
		<td align="center"><!--{$volist.catname}--></td>
		<td align="left"><!--{$volist.productname}--></td>
		<td align="left"><!--{$volist.oprice}--></td>
		<td align="left"><!--{$volist.bprice}--></td>
		<td align="center"><a href="javascript:void(0);" onclick="select_goods('<!--{$sortid}-->', '<!--{$volist.productid}-->', '<!--{$volist.productname}-->', '<!--{$volist.bprice}-->')">选择</a></td>
	  </tr>
	  <!--{foreachelse}-->
      <tr>
	    <td colspan="6" align="center">暂无信息</td>
	  </tr>
	  <!--{/foreach}-->
	</table>
	<!--{if $pagecount>1}-->
	<table width='95%' border='0' cellspacing='0' cellpadding='0' align='center' style="margin-top:10px;">
	  <tr>
		<td align='center'><!--{$showpage}--></td>
	  </tr>
	</table>
	<!--{/if}-->
  </div>
</div>
<!--{/if}-->
</body>
</html>
<script type="text/javascript">
function select_goods(sortid, productid, productname, price) {
    window.parent.$('#item_gid_'+sortid).val(productid);
	window.parent.$('#item_id_'+sortid).html(productid);
	window.parent.$('#item_name_'+sortid).html(productname);
	window.parent.$('#item_price_'+sortid).html(price);
	window.parent.$('#item_num_'+sortid).val(1);
	window.parent.tb_remove();
}
</script>