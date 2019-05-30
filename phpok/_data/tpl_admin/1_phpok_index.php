<?php if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head_lay","file",true,false); ?>
<div class="layui-card">
	<div class="layui-card-header ">
		<?php echo P_Lang("列表");?>
		<div class="fr">
			<button class="layui-btn layui-btn-sm" onclick="$.win('<?php echo P_Lang("添加新调用");?>','<?php echo phpok_url(array('ctrl'=>'call','func'=>'set'));?>')">
				<i class="layui-icon">&#xe608;</i> <?php echo P_Lang("添加调用");?>
			</button>
		</div>
	</div>

	<div class="layui-card-body">
		<table class="layui-table">
			<thead>
			<tr>
				<th>ID</th>
				<th></th>
				<th><?php echo P_Lang("名称");?></th>
				<th><?php echo P_Lang("类型");?></th>
				<th><?php echo P_Lang("关联的项目");?></th>
				<th><?php echo P_Lang("关联的分类");?></th>
				<th><?php echo P_Lang("参数变量");?></th>
				<th width="150"><?php echo P_Lang("操作");?></th>
			</tr>
			</thead>
			<tbody>
			<?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id = array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist as $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
			<tr id="edit_<?php echo $value['id'];?>">
				<td><?php echo $value['id'];?></td>
				<td class="center"><span class="status<?php echo $value['status'];?>" id="status_<?php echo $value['id'];?>" <?php if($popedom['modify']){ ?>onclick="$.admin_call.status(<?php echo $value['id'];?>)"<?php } else { ?> style="cursor:default"<?php } ?> value="<?php echo $value['status'];?>"></span></td>
				<td><?php echo $value['title'];?><?php if($value['is_api']){ ?> <i class="red"><small><?php echo P_Lang("远程");?></small></i><?php } ?></td>
				<td><?php echo $phpok_type_list[$value['type_id']]['title'];?></td>
				<td><?php echo $value['project'] ? $value['project'] : '-';?></td>
				<td><?php echo $value['cate'] ? $value['cate'] : '-';?></td>
				<td><?php echo $value['identifier'];?></td>
				<td>
					<div class="layui-btn-group">
						<?php if($popedom['modify']){ ?><input type="button" value="<?php echo P_Lang("编辑");?>" onclick="$.win('<?php echo P_Lang("编辑调用");?>','<?php echo phpok_url(array('ctrl'=>'call','func'=>'set','id'=>$value['id']));?>')" class="layui-btn  layui-btn-sm" /><?php } ?>
						<?php if($popedom['delete']){ ?><input type="button" value="<?php echo P_Lang("删除");?>" onclick="$.admin_call.del('<?php echo $value['id'];?>','<?php echo $value['title'];?>')" class="layui-btn layui-btn-sm layui-btn-danger" /><?php } ?>
					</div>
				</td>
			</tr>
			<?php } ?>
			</tbody>
		</table>
		<?php if($total && $total>$psize){ ?><div class="center"><?php $this->output("pagelist","file",true,false); ?></div><?php } ?>
	</div>
</div>

<?php $this->output("foot_lay","file",true,false); ?>
