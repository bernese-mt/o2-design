<?php if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head_lay","file",true,false); ?>
<div class="layui-card">
	<div class="layui-card-body">
		<form method="post" id="_listedit" class="layui-form" onsubmit="return $.admin_list.single_save()">
		<input type="hidden" name="id" id="id" value="<?php echo $id;?>" />
		<input type="hidden" name="pid" id="project_id" value="<?php echo $pid;?>" />
		<?php $tmpid["num"] = 0;$extlist=is_array($extlist) ? $extlist : array();$tmpid = array();$tmpid["total"] = count($extlist);$tmpid["index"] = -1;foreach($extlist as $key=>$value){ $tmpid["num"]++;$tmpid["index"]++; ?>
		<div class="layui-form-item">
			<label class="layui-form-label">
				<?php echo $value['title'];?>
			</label>
			<?php if($value['note']){ ?><div class="layui-input-block gray" style="line-height:38px;"><?php echo $value['note'];?></div><?php } ?>
			<div class="layui-input-block">
				<?php echo $value['html'];?>
				<?php if($popedom['ext'] && $value['is_edit'] && $session['adm_develop']){ ?>
				<div class="layui-btn-group">
					<?php if($ext_module != 'add-list'){ ?>
					<input type="button" value="<?php echo P_Lang("编辑");?>" class="layui-btn layui-btn-xs" onclick="ext_edit('<?php echo $value['identifier'];?>','<?php echo $ext_module;?>')" />
					<?php } ?>
					<input type="button" value="<?php echo P_Lang("删除");?>" class="layui-btn layui-btn-xs layui-btn-danger" onclick="ext_delete('<?php echo $value['identifier'];?>','<?php echo $ext_module;?>','<?php echo $value['title'];?>')" />
				</div>
				<?php } ?>
			</div>
		</div>
		<?php } ?>
		<div class="layui-form-item">
			<label class="layui-form-label">&nbsp;</label>
			<div class="layui-input-inline default-auto">
				<input type="submit" value="<?php echo P_Lang("提交保存");?>" class="layui-btn layui-btn-danger" />
			</div>
			<div class="layui-input-inline auto gray"></div>
		</div>
		</form>
	</div>
</div>
<?php $this->output("foot_lay","file",true,false); ?>