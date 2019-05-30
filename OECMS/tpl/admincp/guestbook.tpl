<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<!--{$page_charset}-->" />
<title>网站留言</title>
<!--{include file="<!--{$cptplpath}-->headerjs.tpl"}-->
<!--{assign var='pluginevent' value=XHook::doAction('adm_head')}-->
</head>
<body>
<!--{assign var='pluginevent' value=XHook::doAction('adm_main_top')}-->
<!--{if $a eq "run"}-->
<div class="main-wrap">
  <div class="path"><p>当前位置：用户管理<span>&gt;&gt;</span>网站留言<span>&gt;&gt;</span><a href="<!--{$cpfile}-->?c=guestbook">留言列表</a></p></div>
  <div class="main-cont">
    <h3 class="title"><a href="<!--{$cpfile}-->?c=guestbook&a=add" class="btn-general"><span>添加留言</span></a>留言列表</h3>
	<div class="search-area ">
	  <form method="post" id="search_form" action="<!--{$cpfile}-->?c=guestbook" />
	  <div class="item">
		<label>留言标题：</label><input type="text" id="stitle" name="stitle" size="15" class="input-150" value="<!--{$stitle}-->" />&nbsp;&nbsp;&nbsp;
		<input type="submit" class="button_s" value="搜 索" />
	  </div>
	  </form>
	</div>
	<form action="<!--{$cpfile}-->?c=guestbook&a=del" method="post" name="myform" id="myform" style="margin:0">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table" align="center">
	  <thead class="tb-tit-bg">
	  <tr>
	    <th width="8%"><div class="th-gap">选择</div></th>
		<th width="12%"><div class="th-gap">姓名</div></th>
		<th width="20%"><div class="th-gap">标题</div></th>
		<th width="16%"><div class="th-gap">留言时间</div></th>
		<th width="10%"><div class="th-gap">IP</div></th>
		<th width="8%"><div class="th-gap">审核</div></th>
		<th width="12%"><div class="th-gap">回复</div></th>
		<th><div class="th-gap">操作</div></th>
	  </tr>
	  </thead>
	  <tfoot class="tb-foot-bg"></tfoot>
	  <!--{foreach $guestbook as $volist}-->
	  <tr onMouseOver="overColor(this)" onMouseOut="outColor(this)">
	    <td align="center"><input name="id[]" type="checkbox" value="<!--{$volist.gid}-->" onClick="checkItem(this, 'chkAll')"><!--{$volist.gid}--></td>
		<td align="left"><!--{$volist.username}--></td>
		<td align="left"><!--{$volist.title}--></td>
		<td align="center" title="<!--{$volist.addtime|date_format:"%Y-%m-%d %H:%M:%S"}-->"><!--{$volist.addtime|date_format:"%Y-%m-%d %H:%M:%S"}--></td>
		<td align="center"><!--{$volist.ip}--></td>
		<td align="center">
		<!--{if $volist.flag==0}-->
			<input type="hidden" id="attr_flag<!--{$volist.gid}-->" value="flagopen" />
			<img id="flag<!--{$volist.gid}-->" src="<!--{$urlpath}-->tpl/static/images/no.gif" onClick="javascript:fetch_ajax('flag','<!--{$volist.gid}-->');" style="cursor:pointer;">
		<!--{else}-->
			<input type="hidden" id="attr_flag<!--{$volist.gid}-->" value="flagclose" />
			<img id="flag<!--{$volist.gid}-->" src="<!--{$urlpath}-->tpl/static/images/yes.gif" onClick="javascript:fetch_ajax('flag','<!--{$volist.gid}-->');" style="cursor:pointer;">	
		<!--{/if}-->
        </td>
		<td align="center">
		<!--{if $volist.replyflag==0}-->
		<font color="#999999">未回复</font>
		<!--{else}-->
		<font color="green">已回复</font><br />
		<!--{$volist.replytime|date_format:"%Y/%m/%d"}-->
		<!--{/if}-->
		</td>
		<td align="center"><a href="<!--{$cpfile}-->?c=guestbook&a=edit&id=<!--{$volist.gid}-->&page=<!--{$page}-->&<!--{$urlitem}-->" class="icon-edit">回复/编辑</a>&nbsp;&nbsp;<a href="<!--{$cpfile}-->?c=guestbook&a=del&id[]=<!--{$volist.gid}-->" onClick="{if(confirm('确定要删除吗？')){return true;} return false;}" class="icon-del">删除</a></td>
	  </tr>
	  <!--{foreachelse}-->
      <tr>
	    <td colspan="8" align="center">暂无信息</td>
	  </tr>
	  <!--{/foreach}-->
	  <!--{if $total>0}-->
	  <tr>
		<td align="center"><input name="chkAll" type="checkbox" id="chkAll" onClick="checkAll(this, 'id[]')" value="checkbox"></td>
		<td class="hback" colspan="7"><input class="button" name="btn_del" type="button" value="删 除" onClick="{if(confirm('确定要删除吗？')){$('#myform').submit();return true;}return false;}" class="button">&nbsp;&nbsp;共[ <b><!--{$total}--></b> ]条记录</td>
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
  <div class="path"><p>当前位置：用户管理<span>&gt;&gt;</span>网站留言<span>&gt;&gt;</span><a href="<!--{$cpfile}-->?c=guestbook&a=add">添加留言</a></p></div>
  <div class="main-cont">
	<h3 class="title"><a href="<!--{$cpfile}-->?c=guestbook" class="btn-general"><span>返回列表</span></a>添加留言</h3>
    <form name="myform" id="myform" method="post" action="<!--{$cpfile}-->?c=guestbook" onsubmit='return checkform();' />
	<input type='hidden' name='a' id='a' value='saveadd' />
	<table cellpadding='1' cellspacing='1' class='tab'>
	  <tr>
		<td class='hback_1' width='15%'>姓名 <span class="f_red">*</span> </td>
		<td class='hback' width='85%'><input type="text" name="username" id="username" class="input-80"  /> <span id='dusername' class='f_red'></span> 姓名长度不能大于50个任意字符</td>
	  </tr>
	  <tr>
		<td class='hback_1'>标题 <span class="f_red">*</span> </td>
		<td class='hback'><input type="text" name="title" id="title" class="input-txt"  /> <span id='dtitle' class='f_red'></span> </td>
	  </tr>
	  <tr>
		<td class='hback_1'>邮箱 <span class="f_red">*</span> </td>
		<td class='hback'><input type="text" name="email" id="email" class="input-200"  /> <span id='demail' class='f_red'></span> </td>
	  </tr>
	  <tr>
		<td class='hback_1'>留言内容 <span class="f_red">*</span> </td>
		<td class='hback'><textarea name="content" id="content" style="width:50%;height:90px;overflow:auto;"></textarea>  <span id='dcontent' class='f_red'></span></td>
	  </tr>
	  <tr>
	    <td class='hback_1'>审核状态 <span class='f_red'></span></td>
		<td><input type="radio" name="flag" id="flag" value="1" checked />通过，<input type="radio" name="flag" id="flag" value="0" />锁定</td>
	  </tr>
	  <tr>
	    <td class='hback_1'>留言时间 <span class='f_red'></span></td>
		<td><input type="text" name="addtime" id="addtime" class="input-150" value="<!--{$timeline|date_format:"%Y-%m-%d %H:%M:%S"}-->" /> <span id='daddtime' class='f_red'></span> 当前时间为：<!--{$timeline|date_format:"%Y-%m-%d %H:%M:%S"}--> 注意不要改变格式。</td>
	  </tr>

	  <tr>
		<td class='hback_yellow' colspan="2">联系方式 选填 </td>
	  </tr>
	  <tr>
		<td class='hback_1'>QQ <span class="f_red"></span> </td>
		<td class='hback'><input type="text" name="qq" id="qq" class="input-100" /> <span id='dqq' class='f_red'></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>MSN <span class="f_red"></span> </td>
		<td class='hback'><input type="text" name="msn" id="msn" class="input-150" /> <span id='dmsn' class='f_red'></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>联系地址 <span class="f_red"></span> </td>
		<td class='hback'><input type="text" name="address" id="address" class="input-txt" /> <span id='daddress' class='f_red'></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>联系电话 <span class="f_red"></span> </td>
		<td class='hback'><input type="text" name="telephone" id="telephone" class="input-150" /> <span id='dtelephone' class='f_red'></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>手机号码 <span class="f_red"></span> </td>
		<td class='hback'><input type="text" name="mobile" id="mobile" class="input-150" /> <span id='dmobile' class='f_red'></span></td>
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
  <div class="path"><p>当前位置：用户管理<span>&gt;&gt;</span>网站留言<span>&gt;&gt;</span>编辑/回复留言</p></div>
  <div class="main-cont">
	<h3 class="title"><a href="<!--{$cpfile}-->?c=guestbook&<!--{$comeurl}-->" class="btn-general"><span>返回列表</span></a>编辑/回复留言</h3>
    <form name="myform" id="myform" method="post" action="<!--{$cpfile}-->?c=guestbook&<!--{$comeurl}-->" onsubmit='return checkform();' />
    <input type="hidden" name="a" value="saveedit" />
	<input type="hidden" name="id" value="<!--{$id}-->" />
	<table cellpadding='1' cellspacing='2' class='tab'>
	  <tr>
		<td class='hback_1' width='15%'>姓名 <span class="f_red">*</span> </td>
		<td class='hback' width='85%'><input type="text" name="username" id="username" class="input-80" value="<!--{$guestbook.username}-->" /> <span id='dusername' class='f_red'></span> 姓名长度不能大于50个任意字符</td>
	  </tr>
	  <tr>
		<td class='hback_1'>标题 <span class="f_red">*</span> </td>
		<td class='hback'><input type="text" name="title" id="title" class="input-txt" value="<!--{$guestbook.title}-->" /> <span id='dtitle' class='f_red'></span> </td>
	  </tr>
	  <tr>
		<td class='hback_1'>邮箱 <span class="f_red">*</span> </td>
		<td class='hback'><input type="text" name="email" id="email" class="input-200" value="<!--{$guestbook.email}-->" /> <span id='demail' class='f_red'></span> </td>
	  </tr>
	  <tr>
		<td class='hback_1'>留言内容 <span class="f_red">*</span> </td>
		<td class='hback'><textarea name="content" id="content" style="width:50%;height:90px;overflow:auto;"><!--{$guestbook.content}--></textarea>  <span id='dcontent' class='f_red'></span></td>
	  </tr>
	  <tr>
	    <td class='hback_1'>审核状态 <span class='f_red'></span></td>
		<td><input type="radio" name="flag" id="flag" value="1"<!--{if $guestbook.flag==1}--> checked<!--{/if}--> />通过，<input type="radio" name="flag" id="flag" value="0"<!--{if $guestbook.flag==0}--> checked<!--{/if}--> />锁定</td>
	  </tr>
	  <tr>
	    <td class='hback_1'>留言时间 <span class='f_red'></span></td>
		<td><input type="text" name="addtime" id="addtime" class="input-150" value="<!--{$guestbook.addtime|date_format:"%Y-%m-%d %H:%M:%S"}-->" /> <span id='daddtime' class='f_red'></span> 当前时间为：<!--{$timeline|date_format:"%Y-%m-%d %H:%M:%S"}--> 注意不要改变格式。</td>
	  </tr>
	  <tr>
	    <td class='hback_1'>IP <span class='f_red'></span></td>
		<td><!--{$guestbook.ip}--></td>
	  </tr>

	  <tr>
		<td class='hback_yellow' colspan="2">回复留言 </td>
	  </tr>
	  <tr>
		<td class='hback_1'>回复人 <span class="f_red"></span> </td>
		<td class='hback'><input type="text" name="replyuser" id="replyuser" class="input-80" value="<!--{$guestbook.replyuser}-->" /> <span id='dreplyuser' class='f_red'></span> </td>
	  </tr>
	  <tr>
		<td class='hback_1'>回复内容 <span class="f_red"></span> </td>
		<td class='hback'><textarea name="replycontent" id="replycontent" style="width:50%;height:90px;overflow:auto;"><!--{$guestbook.replycontent}--></textarea>  <span id='dreplycontent' class='f_red'></span></td>
	  </tr>

	  <tr>
		<td class='hback_yellow' colspan="2">联系方式 </td>
	  </tr>
	  <tr>
		<td class='hback_1'>QQ <span class="f_red"></span> </td>
		<td class='hback'><!--{$guestbook.qq}--> <span id='dqq' class='f_red'></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>MSN <span class="f_red"></span> </td>
		<td class='hback'><!--{$guestbook.msn}--> <span id='dmsn' class='f_red'></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>联系地址 <span class="f_red"></span> </td>
		<td class='hback'><!--{$guestbook.address}--> <span id='daddress' class='f_red'></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>联系电话 <span class="f_red"></span> </td>
		<td class='hback'><!--{$guestbook.telephone}--> <span id='dtelephone' class='f_red'></span></td>
	  </tr>
	  <tr>
		<td class='hback_1'>手机号码 <span class="f_red"></span> </td>
		<td class='hback'><!--{$guestbook.mobile}--> <span id='dmobile' class='f_red'></span></td>
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

	t = "username";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("姓名不能为空", t);
		$('#'+t).focus();
		return false;
	}

	t = "title";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("标题不能为空", t);
		$('#'+t).focus();
		return false;
	}

	t = "email";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("邮箱不能为空", t);
		$('#'+t).focus();
		return false;
	}

	t = "content";
	v = $("#"+t).val();
	if(v=="") {
		dmsg("留言内容不能为空", t);
		$('#'+t).focus();
		return false;
	}

	return true;
}

</script>