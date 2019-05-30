<?php if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");} ?><script type="text/javascript">
function youdao_translate()
{
	var c = $("#title").val() ? $("#title").val() : $("#title").text();
	if(!c){
		$.dialog.alert("无法取到要转拼音的内容！");
		return false;
	}
	var url = api_url('plugin','','id=<?php echo $pinfo['id'];?>&exec=fanyi&q='+$.str.encode(c));
	$.phpok.json(url,function(rs){
		if(rs.status){
			$("#identifier").val(rs.info);
			return true;
		}
		$.dialog.alert(rs.info);
		return false;
	});
}
//取得拼音
function pingyin_btn()
{
	var title = $("#title").val() ? $("#title").val() : $("#title").text();
	if(!title){
		$.dialog.alert('没有要拼音的标题');
		return false;
	}
	var url = api_plugin_url('<?php echo $pinfo['id'];?>','pingyin','title='+$.str.encode(title));
	$.phpok.json(url,function(rs){
		if(rs.status){
			$("#identifier").val(rs.info);
			return true;
		}
		$.dialog.alert(rs.info);
		return false;
	});
}

function py_btn()
{
	var title = $("#title").val() ? $("#title").val() : $("#title").text();
	if(!title){
		$.dialog.alert('没有要拼音的标题');
		return false;
	}
	var url = api_plugin_url('<?php echo $pinfo['id'];?>','py','title='+$.str.encode(title));
	$.phpok.json(url,function(rs){
		if(rs.status){
			$("#identifier").val(rs.info);
			return true;
		}
		$.dialog.alert(rs.info);
		return false;
	});
}

$(document).ready(function(){
	$("#HTML-POINT-PHPOK-IDENTIFIER").html($("#<?php echo $pinfo['id'];?>_hidden").html());
});
</script>
<div id="<?php echo $pinfo['id'];?>_hidden" style="display:none;">
	
	<div class="layui-btn-group">
		<input type="button" value="<?php echo P_Lang("随机码");?>" onclick="$.admin.rand()" class="layui-btn layui-btn-sm" />
		<?php if($is_youdao){ ?>
		<input type="button" value="<?php echo P_Lang("有道翻译");?>" onclick="youdao_translate()" class="layui-btn layui-btn-sm" />
		<?php } ?>
		<?php if($is_kunwu){ ?>
		<input type="button" value="<?php echo P_Lang("全拼音");?>" onclick="pingyin_btn()" class="layui-btn layui-btn-sm" />
		<input type="button" value="<?php echo P_Lang("简拼");?>" onclick="py_btn()" class="layui-btn layui-btn-sm" />
		<?php } ?>
		<input type="button" value="<?php echo P_Lang("清空");?>" onclick="$('#identifier').val('')" class="layui-btn layui-btn-sm layui-btn-normal" />
	</div>
</div>