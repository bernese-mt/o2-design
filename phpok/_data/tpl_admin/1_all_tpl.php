<?php if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head_open","file",true,false); ?>
<script type="text/javascript">
function save()
{
	if(typeof(CKEDITOR) != "undefined"){
		for(var i in CKEDITOR.instances){
			CKEDITOR.instances[i].updateElement();
		}
	}
	var opener = $.dialog.opener;
	$("#post_save").ajaxSubmit({
		'url':get_url('all','tpl_setting_save'),
		'type':'post',
		'dataType':'json',
		'success':function(rs){
			if(rs.status){
				$.dialog.alert('操作成功',function(){
					opener.$.dialog.close();
				},'succeed');
				return true;
			}
			$.dialog.alert(rs.info);
			return false;
		}
	});
}
</script>
<div class="list">
<form method="post" id="post_save" onsubmit="return false">
<table width="100%" class="list" cellpadding="0" cellspacing="0">
<tr>
	<th style="text-align:right">名称</th>
	<th>默认模板</th>
	<th style="text-align:left;">自定义模板</th>
</tr>
<?php $tmpid["num"] = 0;$tpls=is_array($tpls) ? $tpls : array();$tmpid = array();$tmpid["total"] = count($tpls);$tmpid["index"] = -1;foreach($tpls as $key=>$value){ $tmpid["num"]++;$tmpid["index"]++; ?>
<tr>
	<td align="right"><?php if($value['title']){ ?><?php echo $value['title'];?><?php } else { ?><?php echo $value['default'];?><?php } ?></td>
	<td class="center"><?php echo $value['default'];?><?php if($tplext){ ?>.<?php echo $tplext;?><?php } ?></td>
	<td>
		<select name="<?php echo $key;?>">
			<option value=""><?php echo P_Lang("使用默认模板…");?></option>
			<?php $idx["num"] = 0;$filelist=is_array($filelist) ? $filelist : array();$idx = array();$idx["total"] = count($filelist);$idx["index"] = -1;foreach($filelist as $k=>$v){ $idx["num"]++;$idx["index"]++; ?>
			<option value="<?php echo $v['id'];?>"<?php if($value['tpl'] && $v['id'] == $value['tpl']){ ?> selected<?php } ?>><?php echo $v['title'];?></option>
			<?php } ?>
		</select>
	</td>
</tr>
<?php } ?>
</table>
</form>
</div>

<?php $this->output("foot_open","file",true,false); ?>