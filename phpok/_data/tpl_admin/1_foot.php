<?php if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");} ?>		<br />
		<?php echo $app->plugin_html_ap("body");?>
	</div>
	<div class="clear"></div>
</div>
<div class="foot" style="text-align:center;"><?php if($sys['debug']){ ?><?php echo debug_time();?><?php } ?></div>
<?php echo $app->plugin_html_ap("foot");?>
<script type="text/javascript">
$(document).ready(function(){
	var r_menu_in_copy = [{
		'text':p_lang('复制'),
		'func':function(){
			var info = $("#smart-phpok-copy-html").val();
			if(window.clipboardData && info != ''){
				window.clipboardData.setData("Text", info);
				$.dialog.tips(p_lang('文本复制成功，请按 CTRL+V 粘贴'));
				return true;
			}
			if(document.execCommand && info != ''){
				$("#smart-phpok-copy-html").focus().select();
				document.execCommand("copy",false,null);
				$.dialog.tips(p_lang('文本复制成功，请按 CTRL+V 粘贴'));
				return true;
			}
			$.dialog.tips(p_lang('复制失败，请按 CTRL+C 进行复制操作'));
			return true;
		}
	},{
		'text':p_lang('刷新'),
		'func':function(){
			$.phpok.reload();
		}
	}];
	var r_menu_not_copy = [{
		'text':p_lang('刷新'),
		'func':function(){
			$.phpok.reload();
		}
	}];
	var r_menu = [[{
		'text':'<?php echo P_Lang("刷新");?>',
		'func':function(){
			$.phpok.reload();
		}
	}],[{
		'text':"<?php echo P_Lang("清空缓存");?>",
		'func': function() {
			top.$.admin_index.clear();
		}
	},{
		'text':'<?php echo P_Lang("修改我的信息");?>',
		'func':function(){
			top.$.admin_index.me();
		}
	},{
		'text':'<?php echo P_Lang("访问网站首页");?>',
		'func':function(){
			var url = "<?php echo $sys['www_file'];?>?siteId=<?php echo $session['admin_site_id'];?>";
			$.phpok.open(url,false);
		}
	}],[{
		'text':'<?php echo P_Lang("网页属性");?>',
		'func':function(){
			var url = window.location.href;
			//去除随机数
			url = url.replace(/\_noCache=[0-9\.]+/g,'');
			if(url.substr(-1) == '&' || url.substr(-1) == '?'){
				url = url.substr(0,url.length-1);
			}
			top.$.dialog({
				'title':'<?php echo P_Lang("网址属性");?>',
				'content':'<?php echo P_Lang("网址：");?>'+url+'<br /><div style="text-indent:36px"><a href="'+url+'" target="_blank" class="red"><?php echo P_Lang("新窗口打开");?></a></div>',
				'lock':true,
				'drag':false,
				'fixed':true
			});
		}
	},{
		'text': "<?php echo P_Lang("新标签打开");?>",
		'func': function() {
			var url = window.location.href;
			url = $.phpok.nocache(url);
			var title = top.$(".layui-this").find("span").text();
			if(!title){
				title = $("title").text();
				if(!title){
					title = "#";
				}
			}
			$.win(title,url);
		}
	}],[{
		'text': "<?php echo P_Lang("帮助说明");?>",
		'func': function() {
			top.$("a[layadmin-event=about]").click();
		}
	}]];
	$(window).smartMenu(r_menu,{
		'name':'smart',
		'textLimit':8,
		'beforeShow':function(){
			$.smartMenu.remove();
			r_menu[0] = r_menu_not_copy;
			if(!document.queryCommandSupported('copy')){
				return true;
			}
			var info = window.getSelection ?  (window.getSelection()).toString() : (document.selection.createRange ? document.selection.createRange().text : '');
			if(info == '' && $("input[type=text]:focus").length>0){
				obj = $("input[type=text]:focus")[0];
				info = obj.value.substring(obj.selectionStart,obj.selectionEnd);
			}
			if(info == '' && $("textarea:focus").length>0){
				obj = $("textarea:focus")[0];
				info = obj.value.substring(obj.selectionStart,obj.selectionEnd);
			}
			if(info){
				info = info.replace(/<.+>/g,'');
			}
			if(info != ''){
				$("#smart-phpok-copy-html").remove();
				var html = '<input type="text" id="smart-phpok-copy-html" value="'+info+'" style="position:absolute;left:-9999px;top:-9999px;" />'
				$('body').append(html);
				r_menu[0] = r_menu_in_copy;
			}
		}
	});
});
</script>
<?php echo $app->plugin_html_ap("phpokbody");?></body>
</html>