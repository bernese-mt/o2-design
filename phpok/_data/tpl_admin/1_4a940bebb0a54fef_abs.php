<?php if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");} ?><script type="text/javascript">
function copy_id()
{
	var url = get_url("plugin","ajax") + "&id=<?php echo $plugin_rs['id'];?>";
	url += "&exec=copy_id";
	var ids = $.input.checkbox_join();
	if(!ids)
	{
		$.dialog.alert("请选择要复制的主题");
		return false;
	}
	var list = ids.split(",");
	url += "&ids="+$.str.encode(ids);
	$.dialog.prompt("请设置要将选中的主题复制数量（范围：1－10）：",function(val){
		if(!val || parseInt(val)<1)
		{
			$.dialog.alert("复制的数量不能小于1");
			return false;
		}
		if(val && parseInt(val) > 10)
		{
			$.dialog.alert("复制的数量不能大于10");
			return false;
		}
		url += "&count="+val;
		var rs = json_ajax(url);
		if(rs.status == "ok")
		{
			$.dialog.alert("主题复制成功",function(){
				direct(window.location.href);
			});
		}
		else
		{
			$.dialog.alert("主题复制失败");
			return false;
		}
	},"<?php echo $plugin_rs['param']['max_count'];?>");
}
$(document).ready(function(){
	//执行HTML
	$("#plugin_button").after('<li><input type="button" value="复制" class="layui-btn layui-btn-sm" onclick="copy_id()" /></li>');
});
</script>
