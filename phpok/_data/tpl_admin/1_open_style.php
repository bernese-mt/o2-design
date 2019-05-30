<?php if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->assign("nopadding","true"); ?><?php $this->assign("overflowy","true"); ?><?php $this->output("head_lay","file",true,false); ?>
<input type="hidden" id="id" value="<?php echo $id;?>" />
<input type="hidden" id="vid" value="<?php echo $vid;?>" />
<div class="layui-card">
	<input type="hidden" name="content" id="content" />
	<div class="layui-card-body layui-form">
		<div class="layui-form-item">
			<label class="layui-form-label">
				<?php echo P_Lang("加粗");?>
			</label>
			<div class="layui-input-block">
				<input type="checkbox" id="bold" title="<?php echo P_Lang("加粗");?>" />
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">
				<?php echo P_Lang("斜体");?>
			</label>
			<div class="layui-input-block">
				<input type="checkbox" id="italic" title="<?php echo P_Lang("斜体");?>" />
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">
				<?php echo P_Lang("划线");?>
			</label>
			<div class="layui-input-block">
				<input type="radio" name="text-decoration" id="text-decoration_default" checked title="<?php echo P_Lang("默认");?>" />
				<input type="radio" name="text-decoration" id="text-decoration_overline" value="overline" title="<?php echo P_Lang("上划线");?>" />
				<input type="radio" name="text-decoration" id="text-decoration_through" value="line-through" title="<?php echo P_Lang("删除线");?>" />
				<input type="radio" name="text-decoration" id="text-decoration_underline" value="underline" title="<?php echo P_Lang("下划线");?>" />
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">
				文本颜色
			</label>
			<div class="layui-input-block">
				<?php echo $colorhtml;?>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">
				背景色
			</label>
			<div class="layui-input-block">
				<?php echo $bgcolorhtml;?>
			</div>
		</div>
	</div>
</div>

<?php $this->assign("is_open","true"); ?><?php $this->output("foot_lay","file",true,false); ?>