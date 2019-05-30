<?php if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head_lay","file",true,false); ?>
<?php $this->output("list_project_sublist","file",true,false); ?>

<?php if($rs['module'] && $search_list){ ?>
<div class="layui-card" id="search_html"<?php if(!$keywords){ ?> style="display:none"<?php } ?>>
	<div class="layui-card-header"><?php echo P_Lang("搜索");?></div>
	<div class="layui-card-body">
		<form method="post" class="layui-form" action="<?php echo phpok_url(array('ctrl'=>'list','func'=>'action','id'=>$pid));?>">
		<div class="layui-form-item phpok-search">
			<?php $tmpid["num"] = 0;$search_list=is_array($search_list) ? $search_list : array();$tmpid = array();$tmpid["total"] = count($search_list);$tmpid["index"] = -1;foreach($search_list as $key=>$value){ $tmpid["num"]++;$tmpid["index"]++; ?>
			<div class="layui-inline">
				<label class="layui-form-label"><?php echo $value['title'];?></label>
				<div class="layui-input-block">
					<input type="text" name="keywords[<?php echo $value['identifier'];?>]" class="layui-input"<?php if($keywords){ ?> value="<?php echo $keywords[$value['identifier']];?>"<?php } ?> placeholder="<?php if($value['search'] == 1){ ?><?php echo P_Lang("仅支持精确搜索");?><?php } else { ?><?php echo P_Lang("支持模糊搜索");?><?php } ?>" />
				</div>
			</div>
			<?php } ?>
			<div class="layui-inline">
				<label class="layui-form-label">&nbsp;</label>
				<div class="layui-input-block">
					<input type="submit" value="<?php echo P_Lang("提交搜索");?>" class="layui-btn layui-btn-normal" />
					<input type="button" value="<?php echo P_Lang("取消搜索");?>" onclick="$.phpok.go('<?php echo phpok_url(array('ctrl'=>'list','func'=>'action','id'=>$rs['id']));?>')" class="layui-btn layui-btn-primary" />
				</div>
			</div>
		</div>
		</form>
	</div>
</div>
<?php } ?>


<?php if($rs['admin_note']){ ?>
<div class="layui-card">
	<div class="layui-card-body"><?php echo $rs['admin_note'];?></div>
</div>
<?php } ?>

<div class="layui-card">
	<div class="layui-card-header">
		<?php echo P_Lang("列表");?>
		<span id="AP_ACTION_HTML"></span>
		<div class="layui-btn-group fr">
			<?php if($popedom['add']){ ?>
			<button class="layui-btn layui-btn-sm layui-btn-danger" onclick="$.win('<?php echo $rs['title'];?>_<?php echo P_Lang("添加内容");?>','<?php echo phpok_url(array('ctrl'=>'list','func'=>'edit2','pid'=>$pid));?>')"><span class="layui-icon">&#xe654;</span><?php echo P_Lang("添加内容");?></button>
			<?php } ?>
			<?php if($rs['module'] && $search_list){ ?>
			<button class="layui-btn layui-btn-sm" onclick="$.admin.hide_show('search_html')"><span class="layui-icon">&#xe615;</span><?php echo P_Lang("搜索");?></button>
			<?php } ?>
			<?php if($popedom['set'] || $session['admin_rs']['if_system']){ ?>
			<button class="layui-btn layui-btn-sm" onclick="$.phpok_list.set(<?php echo $pid;?>)"><span class="layui-icon">&#xe642;</span><?php echo P_Lang("编辑项目");?></button>
			<?php } ?>
		</div>
	</div>
	<?php if($rslist){ ?>
	
	
	<div class="layui-card-body">
		<table class="layui-table">
		<thead>
		<tr>
			<th>&nbsp;</th>
			<?php $layout_id["num"] = 0;$layout=is_array($layout) ? $layout : array();$layout_id = array();$layout_id["total"] = count($layout);$layout_id["index"] = -1;foreach($layout as $key=>$value){ $layout_id["num"]++;$layout_id["index"]++; ?>
			<th class="lft"><?php echo $value;?></th>
			<?php } ?>
			<th width="120px">&nbsp;</th>
		</tr>
		</thead>
		<?php $tmpid["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$tmpid = array();$tmpid["total"] = count($rslist);$tmpid["index"] = -1;foreach($rslist as $key=>$value){ $tmpid["num"]++;$tmpid["index"]++; ?>
		<tr id="list_<?php echo $value['id'];?>">
			<td class="center"><?php echo $value['id'];?></td>
			<?php $layout_id["num"] = 0;$layout=is_array($layout) ? $layout : array();$layout_id = array();$layout_id["total"] = count($layout);$layout_id["index"] = -1;foreach($layout as $k=>$v){ $layout_id["num"]++;$layout_id["index"]++; ?>
				<?php if(is_array($value[$k])){ ?>
					<?php $c_list = $value[$k]['_admin'];?>
					<?php if($c_list['type'] == 'pic'){ ?>
					<td><img src="<?php echo $c_list['info'];?>" width="28px" height="28px" border="0" class="hand" onclick="preview_attr('<?php echo $c_list['id'];?>')" style="border:1px solid #dedede;padding:1px;" /></td>
					<?php } else { ?>
						<?php if(is_array($c_list['info'])){ ?>
						<td><?php echo implode(' / ',$c_list['info']);?></td>
						<?php } else { ?>
						<td><?php echo $c_list['info'] ? $c_list['info'] : '-';?></td>
						<?php } ?>
					<?php } ?>
				<?php } else { ?>
				<td><?php echo $value[$k] ? $value[$k] : '-';?></td>
				<?php } ?>
			<?php } ?>
			<td>
				<div class="layui-btn-group">
					<?php if($popedom['modify']){ ?><input type="button" value="<?php echo P_Lang("编辑");?>" onclick="$.win('<?php echo $rs['title'];?>_<?php echo P_Lang("编辑");?>_#<?php echo $value['id'];?>','<?php echo phpok_url(array('ctrl'=>'list','func'=>'edit2','id'=>$value['id'],'pid'=>$pid));?>')" class="layui-btn layui-btn-sm" /><?php } ?>
					<?php if($popedom['delete']){ ?><input type="button" value="<?php echo P_Lang("删除");?>" onclick="$.admin_list.single_delete('<?php echo $pid;?>','<?php echo $value['id'];?>')" class="layui-btn layui-btn-sm layui-btn-danger" /><?php } ?>
				</div>
			</td>
		</tr>
		<?php } ?>
		</table>
		<div align="center"><?php $this->output("pagelist","file",true,false); ?></div>
	</div>
	<?php } ?>
</div>
<?php $this->output("foot_lay","file",true,false); ?>