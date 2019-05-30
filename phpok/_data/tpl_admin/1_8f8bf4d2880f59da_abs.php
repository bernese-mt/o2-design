<?php if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");} ?><?php if($_rs['is_multiple']){ ?>
<script type="text/javascript">
function action_<?php echo $_rs['identifier'];?>_show()
{
	var tmp_id = $("#<?php echo $_rs['identifier'];?>").val();
	if(!tmp_id){
		$("#<?php echo $_rs['identifier'];?>_div").html('');
		return true;
	}
	var url = get_url('inp','','type=user&content='+$.str.encode(tmp_id));
	$.phpok.json(url,function(rs){
		if(rs.status == 'ok'){
			var lst = rs.content;
			var c = '';
			var m = 1;
			for(var i in lst){
				var class_cate_id = "cate_"+(m%9).toString();
				c += '<li id="<?php echo $_rs['identifier'];?>_div_'+lst[i]['id']+'">';
				c += '<div class="layui-btn-group">';
				c += '<input type="button" value="'+lst[i]['user']+'" onclick="phpok_user_preview(\''+lst[i]['id']+'\')" class="layui-btn layui-btn-xs" />';
				c += '<input type="button" value="'+p_lang('删除')+'" onclick="phpok_user_delete(\'<?php echo $_rs['identifier'];?>\',\''+lst[i]['id']+'\')" class="layui-btn layui-btn-xs layui-btn-danger" />';
				c += '</div>';
				c += '</li>';
				m++;
			}
			$("#<?php echo $_rs['identifier'];?>_div").html(c);
			return true;
		}
		$.dialog.alert(rs.content);
		return false;
	});
}
$(document).ready(function(){
	$("#_phpok_button_user_<?php echo $_rs['identifier'];?>").click(function(){
		var url = get_url("open","user") + "&id=<?php echo $_rs['identifier'];?>&multi=1";
		$.dialog.data("phpok_user_<?php echo $_rs['identifier'];?>",$("#<?php echo $_rs['identifier'];?>").val());
		$.dialog.open(url,{
			title: "会员管理器",
			lock : true,
			width: "700px",
			height: "70%",
			resize: false
			,"ok":function(){
				var data = $.dialog.data("phpok_user_<?php echo $_rs['identifier'];?>");
				$("#<?php echo $_rs['identifier'];?>").val(data);
				action_<?php echo $_rs['identifier'];?>_show();
				$.dialog.data("phpok_user_<?php echo $_rs['identifier'];?>","");
			}
			,"okVal":"确定"
		});
	});
	action_<?php echo $_rs['identifier'];?>_show();
});
</script>
<input type="hidden" name="<?php echo $_rs['identifier'];?>" id="<?php echo $_rs['identifier'];?>" value="<?php echo $_rs_content;?>" />
<input id="_phpok_button_user_<?php echo $_rs['identifier'];?>" type="button" value="<?php echo P_Lang("请选择会员");?>" class="layui-btn layui-btn-sm" /><?php if($_rs['note']){ ?> <span class="gray i"><?php echo $_rs['note'];?></span><?php } ?>
<ul class="layout" id="<?php echo $_rs['identifier'];?>_div" style="margin-top:5px;"></ul>
<div class="clear"></div>
<?php } else { ?>
<script type="text/javascript">
function action_<?php echo $_rs['identifier'];?>_show()
{
	var tmp_id = $("#<?php echo $_rs['identifier'];?>").val();
	if(tmp_id)
	{
		var url = get_url("inp")+"&type=user&content="+$.str.encode(tmp_id);
		var rs = json_ajax(url);
		if(rs.status == "ok")
		{
			var lst = rs.content;
			for(var i in lst)
			{
				$("#title_<?php echo $_rs['identifier'];?>").val(lst[i]['user']);
			}
		}
	}
}
$(document).ready(function(){
	$("#_phpok_button_user_<?php echo $_rs['identifier'];?>").click(function(){
		var url = get_url("open","user") + "&id=<?php echo $_rs['identifier'];?>";
		$.dialog.open(url,{
			title: "<?php echo P_Lang("会员管理器");?>",
			lock : true,
			width: "700px",
			height: "70%",
			resize: false
		});
	});
	action_<?php echo $_rs['identifier'];?>_show();
});
</script>
<input type="hidden" name="<?php echo $_rs['identifier'];?>" id="<?php echo $_rs['identifier'];?>" value="<?php echo $_rs_content;?>" />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td><input type="text" id="title_<?php echo $_rs['identifier'];?>" class="layui-input" name="title_<?php echo $_rs['identifier'];?>" disabled /></td>
	<td>&nbsp;</td>
	<td>
		<div class="layui-btn-group">
			<input type="button" value="<?php echo P_Lang("选择会员");?>" id="_phpok_button_user_<?php echo $_rs['identifier'];?>" class="layui-btn layui-btn-sm" />
			<input type="button" value="<?php echo P_Lang("删除");?>" onclick="$('#<?php echo $_rs['identifier'];?>').val('');$('#title_<?php echo $_rs['identifier'];?>').val('')" class="layui-btn layui-btn-sm" />
		</div>
	</td>
</tr>
</table>
<?php } ?>