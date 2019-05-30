<!--{include file="<!--{$tplpath}-->block_header.tpl"}-->
<div id="wrap">
  <!--{include file="<!--{$tplpath}-->block_top.tpl"}-->
  <!--{include file="<!--{$tplpath}-->block_banner.tpl"}-->
  <div id="web">
    <div id="left">
	  <h3 class="title"><span>在线留言</span></h3>
	  <div class="webcontent">
	    <div class="showtext editor">
		  <p>
			<form method="post" action="<!--{$urlpath}-->index.php?c=guestbook" name="myform" id="myform" onsubmit="return checkform();" />
			<input type="hidden" name="a" value="saveadd" />
			<table cellpadding='0' cellspacing='0' border="0" style="line-height:45px;" width="90%" align="center">
			  <tr>
				<td class='hback_1' width='15%'><b>姓 名：</b></td>
				<td class='hback' width='85%'><input name="username" id="username" type="text" class="input_w1" /> <font color='red'>*</font></td>
			  </tr>
			  <tr>
				<td><b>标 题：</b></td>
				<td><input name="title" id="title" type="text" class="input_w1" /> <font color='red'>*</font></td>
			  </tr>
			  <tr>
				<td><b>邮 箱：</b></td>
				<td><input name="email" id="email" type="text" class="input_w1" /> <font color='red'>*</font></td>
			  </tr>
			  <tr>
				<td><b>留言内容：</b></td>
				<td><textarea name="content" id="content" style="width:70%;height:90px;overflow:auto;"></textarea><font color='red'>*</font></td>
			  </tr>
			  <tr>
				<td><b>QQ：</b></td>
				<td><input name="qq" id="qq" type="text" class="input_w1" /></td>
			  </tr>
			  <tr>
				<td><b>MSN：</b></td>
				<td><input name="msn" id="msn" type="text" class="input_w1" /></td>
			  </tr>
			  <tr>
				<td><b>联系地址：</b></td>
				<td><input name="address" id="address" type="text" class="input_w1" /></td>
			  </tr>
			  <tr>
				<td><b>联系电话：</b></td>
				<td><input name="telephone" id="telephone" type="text" class="input_w1" /></td>
			  </tr>
			  <tr>
				<td><b>手机号码：</b></td>
				<td><input name="mobile" id="mobile" type="text" class="input_w1" /></td>
			  </tr>

			  <tr>
				<td><b>验 证 码：</b></td>
				<td><input id="checkcode" name="checkcode" type="text" class="input_w2" /> <font color='red'>*</font> <img id="checkCodeImg" src="<!--{$urlpath}-->source/include/imagecode.php?act=verifycode" style="vertical-align:middle;" /> <a href="javascript:refreshCc();">看不清楚，换一张</a></td>
			  </tr>

			  <tr>
				<td colspan='2' align="center" height="50px;">
                <input type="submit" name="btn_save" value=" 提交保存 " class="button_w1" />&nbsp;&nbsp;
                <input type="reset" name="btn_reset" value=" 重 置 " class="button_w2" /></td>
			  </tr>
			</table>

			</form>


          </p>
		</div>
      </div>
	</div><!---left //-->

    <div id="right">
	  <!--{include file="<!--{$tplpath}-->block_aboutcat.tpl"}-->
      <!--{include file="<!--{$tplpath}-->block_newnews.tpl"}--> 
      <!--{include file="<!--{$tplpath}-->block_contact.tpl"}-->  
    </div>
	<!-- $right //--->

    <div style="clear:both;"></div>
  </div><!--#web //-->
  <!--{include file="<!--{$tplpath}-->block_footer.tpl"}-->
</div>
<script type="text/javascript" src="<!--{$skinpath}-->js/screen.js"></script>
</body>
</html>
<script language="javascript">
function checkform(){
	if($("#username").val()==""){
		alert("姓名不能为空.");
		return false;
	}
	if($("#title").val()==""){
		alert("标题不能为空.");
		return false;
	}
	if($("#email").val()==""){
		alert("邮箱不能为空.");
		return false;
	}
	if($("#content").val()==""){
		alert("留言内容不能为空.");
		return false;
	}
	if($("#checkcode").val()==""){
		alert("验证码不能为空.");
		return false;
	}
	
}
function refreshCc() {
	var ccImg = document.getElementById("checkCodeImg");
	if (ccImg) {
		ccImg.src= ccImg.src + '&' +Math.random();
	}
}
</script>