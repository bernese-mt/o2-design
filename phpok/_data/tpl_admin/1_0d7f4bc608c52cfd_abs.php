<?php if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");} ?><div style="display:none" id="pl_edit_field_html">
	<select id="pl_edit_field">
		<?php $tmpid["num"] = 0;$fields=is_array($fields) ? $fields : array();$tmpid = array();$tmpid["total"] = count($fields);$tmpid["index"] = -1;foreach($fields as $key=>$value){ $tmpid["num"]++;$tmpid["index"]++; ?>
		<option value="<?php echo $key;?>"><?php echo $value;?></option>
		<?php } ?>
	</select>
</div>
<script type="text/javascript">
function pl_edit_act(pid)
{
	var url = get_plugin_url('pledit','edit','pid='+pid);
	var ids = $.checkbox.join();
	if(!ids){
		$.dialog.alert('请选择要批量修改的主题');
		return false;
	}
	url += "&ids="+$.str.encode(ids);
	var field = $("#pl_edit_field").val();
	if(!field){
		$.dialog.alert('请选择要批量修改的字段');
		return false;
	}
	url += "&field="+field;
	var text = $("#pl_edit_field").find('option:selected').text();
	if(!text){
		text = '未知';
	}
	$.dialog.open(url,{
		'title':'批量修改：'+text,
		'width':'720px',
		'height':'500px',
		'lock':true,
		'cancel':true,
		'okVal':'提交修改',
		'ok':function(){
			var iframe = this.iframe.contentWindow;
			if (!iframe.document.body) {
				alert('iframe还没加载完毕呢');
				return false;
			};
			iframe.save();
			return false;
		}
	})
}
$(document).ready(function(){
	var html = $("#pl_edit_field_html").html();
	$("#plugin_button").after('<li>'+html+'</li><li><input type="button" value="修改" onclick="pl_edit_act(<?php echo $rs['id'];?>)" class="layui-btn layui-btn-sm layui-btn-normal" /></li>');
});
</script>