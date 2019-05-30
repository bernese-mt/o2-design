<?php if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");} ?><input type="hidden" name="<?php echo $_rs['identifier'];?>" id="<?php echo $_rs['identifier'];?>" value="<?php echo $_rs['content'];?>" />
<input type="hidden" id="<?php echo $_rs['identifier'];?>_status" value="" />
<style type="text/css">
.<?php echo $_rs['identifier'];?>_thumb{float:left;width:144px;margin:3px 5px;padding:1px;border:1px solid #ccc;border-radius:3px;position:relative;}
.<?php echo $_rs['identifier'];?>_thumb .sort{position:absolute;right:5px;top:5px;}
.<?php echo $_rs['identifier'];?>_thumb .sort input.taxis{width:40px;border:1px solid #ccc;border-radius:3px;height:22px;text-align:center;padding:3px;}
</style>
<div class="_e_upload">
	<div class="_select">
		<table>
		<tr>
			<td><div id="<?php echo $_rs['identifier'];?>_picker"></div></td>
			<td><div><input type="button" class="layui-btn layui-btn-sm" value="<?php echo P_Lang("选择");?><?php echo $_rs['upload_type']['title'];?>" onclick="$.phpokform.upload_select('<?php echo $_rs['identifier'];?>','<?php echo $_rs['cate_id'];?>',<?php echo $_rs['is_multiple'] ? 'true' : 'false';?>)" class="layui-btn layui-btn-sm button" /></div></td>
			<td id="<?php echo $_rs['identifier'];?>_sort" style="display:none">
				<div class="layui-btn-group">
					<input type="button" value="<?php echo P_Lang("排序");?>" class="layui-btn layui-btn-sm" onclick="$.phpokform.upload_sort('<?php echo $_rs['identifier'];?>')" />
					<input type="button" value="<?php echo P_Lang("自然排序");?>" class="layui-btn layui-btn-sm" onclick="$.phpokform.upload_sort('<?php echo $_rs['identifier'];?>','title')" />
				</div>
			</td>
		</tr>
		</table>
	</div>
	<div class="_progress" id="<?php echo $_rs['identifier'];?>_progress"></div>
	<div class="_list" id="<?php echo $_rs['identifier'];?>_list"></div>
	<div class="clear"></div>
</div>
<script type="text/javascript">
var obj_<?php echo $_rs['identifier'];?>;
$(document).ready(function(){
	//清空本地存储，防止异常删除
	$.phpok.undata('upload-<?php echo $_rs['identifier'];?>');
	obj_<?php echo $_rs['identifier'];?> = new $.admin_upload({
		'id':'<?php echo $_rs['identifier'];?>',
		'server':'<?php echo $sys['url'];?><?php echo phpok_url(array('ctrl'=>'upload','func'=>'save'));?>',
		'cateid':'<?php echo $_rs['cate_id'];?>',
		'pick':{'id':'#<?php echo $_rs['identifier'];?>_picker','multiple':<?php echo $_rs['is_multiple'] ? 'true' : 'false';?>,'innerHTML':'<span class="layui-icon">&#xe67c;</span> <?php echo P_Lang("选择本地文件");?>'},
		'resize':false,
		'multiple':"<?php echo $_rs['is_multiple'] ? 'true' : 'false';?>",
		"formData":{'<?php echo session_name();?>':'<?php echo session_id();?>'},
		'fileVal':'upfile',
		'disableGlobalDnd':true,
		'compress':<?php echo $_rs['upload_compress'];?>,
		'auto':true,
		'sendAsBinary':<?php echo $_rs['upload_binary'];?>,
		'accept':{'title':'<?php echo $_rs['upload_type']['title'];?>(<?php echo $_rs['upload_type']['swfupload'];?>)','extensions':'<?php echo $_rs['upload_type']['ext'];?>'},
		'fileSingleSizeLimit':'<?php echo $_rs['upload_type']['maxsize']*100;?>'
	});
	$.phpokform.upload_showhtml('<?php echo $_rs['identifier'];?>',<?php echo $_rs['is_multiple'] ? true : false;?>);
});
</script>

