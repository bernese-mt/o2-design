<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<!--{$page_charset}-->" />
<title>关联链接</title>
<!--{include file="<!--{$cptplpath}-->headerjs.tpl"}-->
<!--{assign var='pluginevent' value=XHook::doAction('adm_head')}-->
</head>
<body>
<!--{assign var='pluginevent' value=XHook::doAction('adm_main_top')}-->
<!--{if $a eq "run"}-->
<div class="main-wrap">
  <div class="path"><p>当前位置：系统设置<span>&gt;&gt;</span>其他设置<span>&gt;&gt;</span><a href="<!--{$cpfile}-->?c=relatedlink">关联链接</a></p></div>
  <div class="main-cont">
    <h3 class="title"><a href="<!--{$cpfile}-->?c=relatedlink&a=add" class="btn-general"><span>添加链接</span></a>关联链接</h3>

	<form action="<!--{$cpfile}-->?c=relatedlink" method="post" name="myform" id="myform" style="margin:0">
	<input type='hidden' name='a' id='a'>
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table" align="center">
	  <thead class="tb-tit-bg">
	  <tr>
	    <th width="8%"><div class="th-gap">选择</div></th>
		<th width="12%"><div class="th-gap">链接文字</div></th>
		<th width="22%"><div class="th-gap">链接地址</div></th>
		<th width="10%"><div class="th-gap">颜色</div></th>
		<th width="8%"><div class="th-gap">打开</div></th>
		<th width="8%"><div class="th-gap">nofollow</div></th>
		<th width="8%"><div class="th-gap">状态</div></th>
		<th><div class="th-gap">指定范围</div></th>
	  </tr>
	  </thead>
	  <tfoot class="tb-foot-bg"></tfoot>
	  <!--{foreach $relatedlink as $volist}-->
	  <tr onMouseOver="overColor(this)" onMouseOut="outColor(this)">
	    <td align="center">
		<input name="id[]" type="checkbox" value="<!--{$volist.id}-->" onClick="checkItem(this, 'chkAll')">
		</td>
		<td align="left"><input type='text' name='linktag_<!--{$volist.id}-->' value="<!--{$volist.linktag}-->" class='input-s' /></td>
		<td align="left"><input type='text' name='url_<!--{$volist.id}-->' value="<!--{$volist.url}-->" class='input-150' /></td>
		<td align="left"><input type='text' name='color_<!--{$volist.id}-->' value="<!--{$volist.color}-->" class='input-s' /></td>
		<td align="center">
		<select name="target_<!--{$volist.id}-->">
		<option value="1"<!--{if $volist.target=='1'}--> selected<!--{/if}-->>本页</option>
		<option value="2"<!--{if $volist.target=='2'}--> selected<!--{/if}-->>新页</option>
		</select>
		</td>
		<td align="center">
		<select name="nofollow_<!--{$volist.id}-->">
		<option value="1"<!--{if $volist.nofollow=='1'}--> selected<!--{/if}-->>是</option>
		<option value="0"<!--{if $volist.nofollow=='0'}--> selected<!--{/if}-->>否</option>
		</select>
		</td>
		<td align="center">
		<select name="flag_<!--{$volist.id}-->">
		<option value="1"<!--{if $volist.flag=='1'}--> selected<!--{/if}-->>正常</option>
		<option value="0"<!--{if $volist.flag=='0'}--> selected<!--{/if}-->>锁定</option>
		</select>
        </td>
		<td align="left">
		<input type="checkbox" name="article_<!--{$volist.id}-->"  value="1"<!--{if $volist.article=='1'}--> checked<!--{/if}--> />文章，
		<input type="checkbox" name="product_<!--{$volist.id}-->" value="1"<!--{if $volist.product=='1'}--> checked<!--{/if}--> />产品<br />
		<input type="checkbox" name="photo_<!--{$volist.id}-->" value="1"<!--{if $volist.photo=='1'}--> checked<!--{/if}--> />图库，
		<input type="checkbox" name="download_<!--{$volist.id}-->" value="1"<!--{if $volist.download=='1'}--> checked<!--{/if}--> />下载，
		<input type="checkbox" name="about_<!--{$volist.id}-->" value="1"<!--{if $volist.about=='1'}--> checked<!--{/if}--> />单页

		</td>
	  </tr>
	  <!--{foreachelse}-->
      <tr>
	    <td colspan="8" align="center">暂无信息</td>
	  </tr>
	  <!--{/foreach}-->
	  <!--{if $total>0}-->
	  <tr>
		<td align="center"><input name="chkAll" type="checkbox" id="chkAll" onClick="checkAll(this, 'id[]')" value="checkbox"></td>
		<td class="hback" colspan="7">
		<input class="button" name="btn_update" type="button" value="更 新" onClick="javascript:$('#a').val('batch');$('#myform').submit();" class="button">
		&nbsp;&nbsp;<input class="button" name="btn_del" type="button" value="删 除" onClick="{if(confirm('确定要删除吗？')){$('#a').val('del');$('#myform').submit();return true;}return false;}" class="button">
		
		&nbsp;&nbsp;共[ <b><!--{$total}--></b> ]条记录</td>
	  </tr>
	  <!--{/if}-->
	</table>
	</form>
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

<!--{if $a eq "add"}-->
<div class="main-wrap">
  <div class="path"><p>当前位置：系统设置<span>&gt;&gt;</span>其他设置<span>&gt;&gt;</span><a href="<!--{$cpfile}-->?c=relatedlink&a=add">添加关联链接</a></p></div>
  <div class="main-cont">
	<h3 class="title"><a href="<!--{$cpfile}-->?c=relatedlink" class="btn-general"><span>返回列表</span></a>添加关联链接</h3>
    <form name="myform" id="myform" method="post" action="<!--{$cpfile}-->?c=relatedlink" onsubmit='return checkform();' />
    <input type="hidden" name="a" value="saveadd" />
	<table cellpadding='1' cellspacing='1' class='tab'>
	  <tr>
		<td class='hback_1' width='15%'>链接文字 <span class="f_red">*</span> </td>
		<td class='hback' width='85%'><input type="text" name="linktag" id="linktag" class="input-txt" /> <span id='dlinktag' class='f_red'></span> </td>
	  </tr>
	  <tr>
		<td class='hback_1'>链接地址 <span class="f_red">*</span> </td>
		<td class='hback'><input type="text" name="url" id="url" class="input-txt" />  <span id='durl' class='f_red'></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>文字颜色 </td>
		<td class='hback'>
		
		<input type='text' name='color' id='color' class='input-s' /> 
		<select name="mycolor" id="mycolor" size="1" onchange="document.getElementById('color').value=this.value;">
		<option value="" style="color:black;background-color:">None</option>  
		<option value="#000000" style="color:black;background-color:black">Black</option>    
		<option value="#ff0000" style="color:red;background-color:red">Red</option>    
		<option value="#ffff00" style="color:yellow;background-color:yellow">Yellow</option>
		<option value="#ffc0cb" style="color:pink;background-color:pink">Pink</option>    
		<option value="#008000" style="color:green;background-color:green">Green</option>    
		<option value="#ffa500" style="color:orange;background-color:orange">Orange</option>    
		<option value="#800080" style="color:purple;background-color:purple">Purple</option>    
		<option value="#0000ff" style="color:blue;background-color:blue">Blue</option>    
		<option value="#f5f5dc" style="color:beige;background-color:beige">Beige</option>    
		<option value="#a52a2a" style="color:brown;background-color:brown">Brown</option>    
		<option value="#008080" style="color:teal;background-color:teal">Teal</option>    
		<option value="#000080" style="color:navy;background-color:navy">Navy</option>    
		<option value="#800000" style="color:maroon;background-color:maroon">Maroon</option>    
		<option value="#32cd32" style="color:limegreen;background-color:limegreen">LimeGreen</option>
		</select>  

		<span id='dcolor' class='f_red'></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>打开方式 </td>
		<td class='hback'><select name='target' id='target'><option value='1'>本页打开</option><option value='2'>新页打开</option></select></td>
	  </tr>
	  <tr>
		<td class='hback_1'>nofollow </td>
		<td class='hback'><select name='nofollow' id='nofollow'><option value='1'>是</option><option value='0' selected>否</option></select></td>
	  </tr>
	  <tr>
		<td class='hback_1'>状态设置 </td>
		<td class='hback'><input type="radio" name="flag" id="flag" value="1" checked />正常，<input type="radio" name="flag" id="flag" value="0" />锁定</td>
	  </tr>
	  <tr>
		<td class='hback_1'>指定范围</td>
		<td class='hback'>
		<input type="checkbox" name="article" id="article" value="1" checked />文章，
		<input type="checkbox" name="product" id="product" value="1" checked />产品，
		<input type="checkbox" name="photo" id="photo" value="1" checked />图库，
		<input type="checkbox" name="download" id="download" value="1" checked />下载，
		<input type="checkbox" name="about" id="about" value="1" checked />单页
		</td>
	  </tr>
	  <tr>
		<td class='hback_none'></td>
		<td class='hback_none'><input type="submit" name="btn_save" class="button" value="添加保存" /></td>
	  </tr>
	</table>
	</form>
  </div>
  <div style="clear:both;"></div>
</div>
<!--{/if}-->

<!--{if $a eq "edit"}-->
<div class="main-wrap">
  <div class="path"><p>当前位置：系统设置<span>&gt;&gt;</span>其他设置<span>&gt;&gt;</span>编辑关联链接</p></div>
  <div class="main-cont">
	<h3 class="title"><a href="<!--{$cpfile}-->?c=relatedlink&page=<!--{$page}-->" class="btn-general"><span>返回列表</span></a>编辑关联链接</h3>
    <form name="myform" id="myform" method="post" action="<!--{$cpfile}-->?c=relatedlink" />
    <input type="hidden" name="a" value="saveedit" />
	<input type="hidden" name="id" value="<!--{$id}-->" />
	<table cellpadding='1' cellspacing='1' class='tab'>
	  <tr>
		<td class='hback_1' width='15%'>链接文字 <span class="f_red">*</span> </td>
		<td class='hback' width='85%'><input type="text" name="linktag" id="linktag" class="input-txt" value="<!--{$relatedlink.linktag}-->" /> <span id='dlinktag' class='f_red'></span> </td>
	  </tr>
	  <tr>
		<td class='hback_1'>链接地址 <span class="f_red">*</span> </td>
		<td class='hback'><input type="text" name="url" id="url" class="input-txt" value="<!--{$relatedlink.url}-->" />  <span id='durl' class='f_red'></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>文字颜色 </td>
		<td class='hback'>
		<input type='text' name='color' id='color' class='input-s' value="<!--{$relatedlink.color}-->" /> 
		<select name="mycolor" id="mycolor" size="1" onchange="document.getElementById('color').value=this.value;">
		<option value="" style="color:black;background-color:">None</option>  
		<option value="#000000" style="color:black;background-color:black">Black</option>    
		<option value="#ff0000" style="color:red;background-color:red">Red</option>    
		<option value="#ffff00" style="color:yellow;background-color:yellow">Yellow</option>
		<option value="#ffc0cb" style="color:pink;background-color:pink">Pink</option>    
		<option value="#008000" style="color:green;background-color:green">Green</option>    
		<option value="#ffa500" style="color:orange;background-color:orange">Orange</option>    
		<option value="#800080" style="color:purple;background-color:purple">Purple</option>    
		<option value="#0000ff" style="color:blue;background-color:blue">Blue</option>    
		<option value="#f5f5dc" style="color:beige;background-color:beige">Beige</option>    
		<option value="#a52a2a" style="color:brown;background-color:brown">Brown</option>    
		<option value="#008080" style="color:teal;background-color:teal">Teal</option>    
		<option value="#000080" style="color:navy;background-color:navy">Navy</option>    
		<option value="#800000" style="color:maroon;background-color:maroon">Maroon</option>    
		<option value="#32cd32" style="color:limegreen;background-color:limegreen">LimeGreen</option>
		</select>  
		
		<span id='dcolor' class='f_red'></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>打开方式 </td>
		<td class='hback'>
		<select name='target' id='target'>
		<option value='1'<!--{if $relatedlink.target=='1'}--> selected<!--{/if}-->>本页打开</option>
		<option value='2'<!--{if $relatedlink.target=='2'}--> selected<!--{/if}-->>新页打开</option>
		</select></td>
	  </tr>
	  <tr>
		<td class='hback_1'>nofollow </td>
		<td class='hback'>
		<select name='nofollow' id='nofollow'>
		<option value='1'<!--{if $relatedlink.nofollow=='1'}--> selected<!--{/if}-->>是</option>
		<option value='0'<!--{if $relatedlink.nofollow=='0'}--> selected<!--{/if}-->>否</option>
		</select></td>
	  </tr>
	  <tr>
		<td class='hback_1'>状态设置 </td>
		<td class='hback'><input type="radio" name="flag" id="flag" value="1"<!--{if $relatedlink.flag=='1'}--> checked<!--{/if}--> />正常，<input type="radio" name="flag" id="flag" value="0"<!--{if $relatedlink.flag=='0'}--> checked<!--{/if}--> />锁定</td>
	  </tr>
	  <tr>
		<td class='hback_1'>指定范围</td>
		<td class='hback'>
		<input type="checkbox" name="article" id="article" value="1"<!--{if $relatedlink.article=='1'}--> checked<!--{/if}--> />文章，
		<input type="checkbox" name="product" id="product" value="1"<!--{if $relatedlink.product=='1'}--> checked<!--{/if}--> />产品，
		<input type="checkbox" name="photo" id="photo" value="1"<!--{if $relatedlink.photo=='1'}--> checked<!--{/if}--> />图库，
		<input type="checkbox" name="download" id="download" value="1"<!--{if $relatedlink.download=='1'}--> checked<!--{/if}--> />下载，
		<input type="checkbox" name="about" id="about" value="1"<!--{if $relatedlink.about=='1'}--> checked<!--{/if}--> />单页
		</td>
	  </tr>
	  <tr>
		<td class='hback_none'></td>
		<td class='hback_none'><input type="submit" name="btn_save" class="button" value="更新保存" /></td>
	  </tr>
	</table>
	</form>
  </div>
  <div style="clear:both;"></div>
</div>
<!--{/if}-->
<!--{assign var='pluginevent' value=XHook::doAction('adm_footer')}-->
</body>
</html>
<script type="text/javascript">
function checkform() {
	var t = "";
	var v = "";

	t = "linktag";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("链接文字不能为空", t);
		return false;
	}
	t = "url";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("链接地址不能为空", t);
		return false;
	}
	return true;
}
</script>
