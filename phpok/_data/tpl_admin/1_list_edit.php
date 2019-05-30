<?php if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head_lay","file",true,false); ?>
<script type="text/javascript" src="<?php echo include_js('list.js');?>"></script>
<form method="post" id="_listedit" class="layui-form" onsubmit="return $.admin_list_edit.save()">
<input type="hidden" name="id" id="id" value="<?php echo $id;?>" />
<input type="hidden" name="pid" id="pid" value="<?php echo $pid;?>" />
<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $parent_id;?>" />
<div class="layui-card">
	<div class="layui-card-header">
		<?php echo P_Lang("主要信息");?>
		<div class="fr">
			<label id="auto_save_tip"><input type="checkbox" onclick="$.admin_list_edit.autosave_checkbox(this)" lay-ignore /> <span><?php echo P_Lang("开启自动保存功能");?></span></label>
		</div>
	</div>
	<div class="layui-card-body">
		<input type="hidden" name="style" id="style" value="<?php echo $rs['style'];?>" />
		<div class="layui-form-item">
			<label class="layui-form-label"><?php if($p_rs['alias_title']){ ?><?php echo $p_rs['alias_title'];?><?php } else { ?><?php echo P_Lang("主题");?><?php } ?></label>
			<div class="layui-input-block">
				<div class="layui-col-md9">
					<input type="text" name="title" id="title" value="<?php echo $rs['title'];?>" class="layui-input"<?php if($rs['style']){ ?> style="<?php echo $rs['style'];?>"<?php } ?> placeholder="<?php echo P_Lang("不能超过80个汉字");?><?php if($p_rs['alias_note']){ ?>，<?php echo $p_rs['alias_note'];?><?php } ?>" />
				</div>
				<div class="layui-col-md2" style="margin-left:10px;margin-top:3px;">
					<button type="button" class="layui-btn layui-btn-sm" onclick="$.admin_list.style_setting('style','title')">
						<i class="layui-icon">&#xe64e;</i> <?php echo P_Lang("样式");?>
					</button>
				</div>
			</div>
		</div>		
		<?php if($attrlist && $p_rs['is_attr']){ ?>
		<div class="layui-form-item">
			<label class="layui-form-label"><?php echo P_Lang("属性");?></label>
			<div class="layui-input-block">
				<?php $attrlist_id["num"] = 0;$attrlist=is_array($attrlist) ? $attrlist : array();$attrlist_id = array();$attrlist_id["total"] = count($attrlist);$attrlist_id["index"] = -1;foreach($attrlist as $key=>$value){ $attrlist_id["num"]++;$attrlist_id["index"]++; ?>
				<input type="checkbox" name="attr[]" lay-skin="primary" id="_attr_<?php echo $key;?>" title="<?php echo $value['val'];?>" value="<?php echo $key;?>"<?php if($value['status']){ ?> checked<?php } ?> />
				<?php } ?>
			</div>
		</div>
		<?php } ?>
		<?php if($p_rs['is_identifier'] == 2){ ?>
		<div class="layui-form-item">
			<label class="layui-form-label"><?php echo P_Lang("自定义标识");?></label>
			<div class="layui-input-inline default-auto">
				<input type="text" id="identifier" name="identifier" value="<?php echo $rs['identifier'];?>" class="layui-input" />
			</div>
			<div class="layui-input-inline auto" id="HTML-POINT-PHPOK-IDENTIFIER">
				<input type="button" value="<?php echo P_Lang("随机码");?>" onclick="$.admin.rand()" class="layui-btn layui-btn-sm" />
			</div>
			<div class="layui-form-mid">
				<?php echo P_Lang("仅支持字母、数字、下划线或中划线且必须是字母开头");?>
			</div>
		</div>
		<?php } ?>
		
		<?php if($p_rs['cate']){ ?>
		<div class="layui-form-item">
			<label class="layui-form-label"><?php echo P_Lang("主分类");?></label>
			<div class="layui-input-inline default-auto">
				<select name="cate_id" id="cate_id">
					<option value=""><?php echo P_Lang("请选择…");?></option>
					<?php $tmpid["num"] = 0;$catelist=is_array($catelist) ? $catelist : array();$tmpid = array();$tmpid["total"] = count($catelist);$tmpid["index"] = -1;foreach($catelist as $key=>$value){ $tmpid["num"]++;$tmpid["index"]++; ?>
					<option value="<?php echo $value['id'];?>"<?php if($value['id'] == $rs['cate_id']){ ?> selected<?php } ?>><?php echo $value['_space'];?><?php echo $value['title'];?></option>
					<?php } ?>
				</select>
			</div>
			<div class="layui-form-mid"><?php echo P_Lang("主分类不能为空");?></div>
		</div>
		<?php } ?>
		
		<?php if($p_rs['cate'] && $p_rs['cate_multiple']){ ?>
		<div class="layui-form-item">
			<label class="layui-form-label"><?php echo P_Lang("扩展分类");?></label>
			<div class="layui-input-block">
				<select name="ext_cate_id[]" class="w99" lay-ignore multiple="multiple">
				<?php $tmpid["num"] = 0;$catelist=is_array($catelist) ? $catelist : array();$tmpid = array();$tmpid["total"] = count($catelist);$tmpid["index"] = -1;foreach($catelist as $key=>$value){ $tmpid["num"]++;$tmpid["index"]++; ?>
				<option value="<?php echo $value['id'];?>"<?php if($extcate && in_array($value['id'],$extcate)){ ?> selected<?php } ?>><?php echo $value['_space'];?><?php echo $value['title'];?></option>
				<?php } ?>
				</select>
			</div>
			<div class="layui-input-block mtop"><?php echo P_Lang("按钮CTRL进行多选");?></div>
		</div>
		<?php } ?>
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
		<?php if($p_rs['is_tag'] == 2){ ?>
		<div class="layui-form-item">
			<label class="layui-form-label"><?php echo P_Lang("标签");?></label>
			<div class="layui-input-block">
				<input type="text" name="tag" id="tag" value="<?php echo $rs['tag'];?>" class="layui-input" />
			</div>
			<div class="layui-input-block mtop">
				<div class="layui-btn-group">
					<?php if($tag_config['count'] && $taglist){ ?>
					<?php $tmpid["num"] = 0;$taglist=is_array($taglist) ? $taglist : array();$tmpid = array();$tmpid["total"] = count($taglist);$tmpid["index"] = -1;foreach($taglist as $key=>$value){ $tmpid["num"]++;$tmpid["index"]++; ?>
					<input type="button" value="<?php echo $value['title'];?>" onclick="$.phpok_list.tag_append(this.value,'<?php echo $tag_config['separator'];?>')" class="layui-btn layui-btn-sm" />
					<?php } ?>
					<?php } ?>
					<input type="button" value="<?php echo P_Lang("更多选择");?>" onclick="$.phpok_list.tag()" class="layui-btn layui-btn-sm layui-btn-warm" />
					<input type="button" value="<?php echo P_Lang("清空");?>" onclick="$('input[name=tag]').val('')" class="layui-btn layui-btn-sm layui-btn-danger" />
				</div>
			</div>
			<div class="layui-input-block mtop"><?php echo P_Lang("多个标签用 [title] 分开，最多不能超过10个",array('title'=>'<span style="color:red">'.$tag_config['separator'].'</span>'));?></div>
		</div>
		<?php } ?>
		<?php if($p_rs['is_userid'] == 2){ ?>
		<div class="layui-form-item">
			<label class="layui-form-label">
				<?php echo P_Lang("会员");?>
			</label>
			<div class="layui-input-inline auto">
				<?php echo form_edit('user_id',$rs['user_id'],'user');?>
			</div>
			<div class="layui-input-inline auto gray lh38"><?php echo P_Lang("绑定会员功能，允许会员对主题进行修改或删除，需要开放发布权限");?></div>
		</div>
		<?php } ?>
		<?php if($p_rs['is_tpl_content'] == 2){ ?>
		<div class="layui-form-item">
			<label class="layui-form-label">
				<?php echo P_Lang("内容模板");?>
			</label>
			<div class="layui-input-inline default-auto">
				<input type="text" id="tpl" name="tpl" class="layui-input" value="<?php echo $rs['tpl'];?>" />
			</div>
			<div class="layui-input-inline auto gray">
				<div class="layui-btn-group">
					<input type="button" value="<?php echo P_Lang("选择");?>" onclick="phpok_tpl_open('tpl')" class="layui-btn layui-btn-sm" />
					<input type="button" value="<?php echo P_Lang("清空");?>" onclick="$('#tpl').val('');" class="layui-btn layui-btn-sm" />
				</div>
			</div>
			<div class="layui-input-inline auto gray lh38">
				<?php echo P_Lang("为空将使用");?> <span class="red"><?php echo $p_rs['tpl_content'] ? $p_rs['tpl_content'] : $p_rs['identifier'].'_content';?></span>
			</div>
		</div>
		<?php } ?>
		<?php if($popedom['ext'] && $session['adm_develop']){ ?>
		<div class="layui-form-item">
			<label class="layui-form-label"><?php echo P_Lang("扩展字段");?></label>
			<div class="layui-input-inline default">
				<select id="_tmp_select_add">
					<option value=""><?php echo P_Lang("请选择要添加的扩展字段…");?></option>
					<?php $extfields_id["num"] = 0;$extfields=is_array($extfields) ? $extfields : array();$extfields_id = array();$extfields_id["total"] = count($extfields);$extfields_id["index"] = -1;foreach($extfields as $key=>$value){ $extfields_id["num"]++;$extfields_id["index"]++; ?>
					<option value="<?php echo $value['identifier'];?>"><?php echo $value['title'];?> - <?php echo $value['identifier'];?></option>
					<?php } ?>
				</select>
			</div>
			<div class="layui-input-inline auto">
				<div class="layui-btn-group">
					<input type="button" value="<?php echo P_Lang("快速添加");?>" onclick="$.admin_list.update_select_add('<?php echo $ext_module;?>')"  class="layui-btn" />
					<input type="button" value="<?php echo P_Lang("创建新的扩展字段");?>" onclick="ext_add('<?php echo $ext_module;?>')" class="layui-btn" />
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
<?php if($p_rs['is_biz']){ ?>
<div class="layui-card">
	<div class="layui-card-header"><?php echo P_Lang("电子商务");?></div>
	<div class="layui-card-body">
		<div class="layui-form-item">
			<label class="layui-form-label">
				<?php echo P_Lang("产品类型");?>
			</label>
			<div class="layui-input-inline auto">
				<input type="radio" title="<?php echo P_Lang("实物");?>" name="is_virtual" value="0"<?php if(!$rs['is_virtual']){ ?> checked<?php } ?> />
				<input type="radio" title="<?php echo P_Lang("服务");?>" name="is_virtual" value="1"<?php if($rs['is_virtual']){ ?> checked<?php } ?> />
			</div>
			<div class="layui-input-inline auto gray lh38"><?php echo P_Lang("实物产品加入购物车后需要填写收件地址，服务不需要");?></div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">
				<?php echo P_Lang("销售价格");?>
			</label>
			<div class="layui-input-inline default-auto">
				<ul class="layout">
					<li><input type="text" name="price" id="price" value="<?php echo $rs['price'];?>" class="layui-input" /></li>
					<li>
						<select name="currency_id" id="currency_id">
							<?php $currency_list_id["num"] = 0;$currency_list=is_array($currency_list) ? $currency_list : array();$currency_list_id = array();$currency_list_id["total"] = count($currency_list);$currency_list_id["index"] = -1;foreach($currency_list as $key=>$value){ $currency_list_id["num"]++;$currency_list_id["index"]++; ?>
							<option value="<?php echo $value['id'];?>"<?php if($rs['currency_id'] == $value['id']){ ?> selected<?php } ?> code="<?php echo $value['code'];?>" rate="<?php echo $value['val'];?>" sleft="<?php echo $value['symbol_left'];?>" sright="<?php echo $value['symbol_right'];?>"><?php echo $value['title'];?>_<?php echo P_Lang("汇率");?> <?php echo $value['val'];?></option>
							<?php } ?>
						</select>
					</li>
				</ul>
			</div>
			<div class="layui-input-inline auto gray lh38"><?php echo P_Lang("设置产品的价格及货币类型");?></div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">
				<i class="layui-icon layui-tips" lay-tips="<?php echo P_Lang("填写产品的计量单位，以方便结算");?>">&#xe702;</i>
				<?php echo P_Lang("计量单位");?>
			</label>
			<div class="layui-input-inline">
				<input type="text" id="unit" class="layui-input" name="unit" value="<?php echo $rs['unit'];?>" />
			</div>
			<?php if($unitlist){ ?>
			<div class="layui-input-inline auto">
				<div class="layui-btn-group">
					<?php $tmpid["num"] = 0;$unitlist=is_array($unitlist) ? $unitlist : array();$tmpid = array();$tmpid["total"] = count($unitlist);$tmpid["index"] = -1;foreach($unitlist as $key=>$value){ $tmpid["num"]++;$tmpid["index"]++; ?>
					<input type="button" value="<?php echo $value;?>" onclick="$('#unit').val(this.value)" class="layui-btn layui-btn-sm" />
					<?php } ?>
				</div>
			</div>
			<?php } ?>
		</div>
		<?php if($freight && $freight['type'] == 'weight'){ ?>
		<div class="layui-form-item">
			<label class="layui-form-label">
				<?php echo P_Lang("重量");?>
			</label>
			<div class="layui-input-inline auto">
				<input type="text" id="weight" class="layui-input" name="weight" value="<?php echo $rs['weight'];?>" />
			</div>
			<div class="layui-input-inline auto gray lh38">Kg <?php echo P_Lang("可用于计算基于重量的运费，单位是千克，请注意换算");?></div>
		</div>
		<?php } ?>
		<?php if($freight && $freight['type'] == 'volume'){ ?>
		<div class="layui-form-item">
			<label class="layui-form-label">
				<?php echo P_Lang("体积");?>
			</label>
			<div class="layui-input-inline auto">
				<input type="text" id="volume" name="volume" class="layui-input" value="<?php echo $rs['volume'];?>" /> 
			</div>
			<div class="layui-input-inline auto gray lh38">M<sup>3</sup> <?php echo P_Lang("设置产品体积，用于计算基于体积的运费，单位是立方米，请注意换算");?></div>
		</div>
		<?php } ?>
		<?php if($p_rs['biz_attr']){ ?>
		<div class="layui-form-item">
			<label class="layui-form-label title" style="margin-top:3px;">
				<?php echo P_Lang("产品属性");?>
			</label>
			<div class="layui-input-block"><?php echo P_Lang("负号表示价格下调，加号或无符号表示价格上调，如+10或10，表示加10，-10表示减10");?></div>
			<div class="layui-input-block">
				<input type="hidden" name="_biz_attr" id="_biz_attr" value="<?php echo $_attr;?>" />
				<div>
					<table cellpadding="0" cellspacing="0">
					<tr>
						<td>
							<select id="biz_attr_id" lay-ignore onchange="$.admin_list_edit.attr_add(this.value)">
								<option value=""><?php echo P_Lang("请选择一个属性操作内容…");?></option>
								<?php $tmpid["num"] = 0;$biz_attrlist=is_array($biz_attrlist) ? $biz_attrlist : array();$tmpid = array();$tmpid["total"] = count($biz_attrlist);$tmpid["index"] = -1;foreach($biz_attrlist as $key=>$value){ $tmpid["num"]++;$tmpid["index"]++; ?>
								<option value="<?php echo $value['id'];?>"><?php echo $value['title'];?></option>
								<?php } ?>
							</select>
						</td>
						<td>&nbsp;</td>
						<td><input type="button" value="<?php echo P_Lang("添加新属性");?>" onclick="$.admin_list_edit.attr_create()" class="layui-btn layui-btn-sm" /></td>
					</tr>
					</table>
				</div>
				<ul id="biz_attr_options"></ul>
			</div>
			
		</div>
		<?php } ?>
	</div>
</div>
<?php } ?>

<?php if($p_rs['is_seo']){ ?>
<div class="layui-card">
	<div class="layui-card-header hand" onclick="$.admin.card(this)">
		<?php echo P_Lang("SEO优化");?>
		<i class="layui-icon<?php if($p_rs['is_seo'] == 1){ ?> layui-icon-right<?php } else { ?> layui-icon-down<?php } ?>"></i>
	</div>
	<div class="layui-card-body<?php if($p_rs['is_seo'] == 1){ ?> hide<?php } ?>">
		<div class="layui-form-item">
			<label class="layui-form-label">
				<?php echo P_Lang("SEO标题");?>
			</label>
			<div class="layui-input-block">
				<input type="text" id="seo_title" name="seo_title" class="layui-input" value="<?php echo $rs['seo_title'];?>" />
			</div>
			<div class="layui-input-block mtop">
				<?php echo P_Lang("设置此标题后，网站Title将会替代默认定义的，不能超过50个汉字");?>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">
				<?php echo P_Lang("SEO关键字");?>
			</label>
			<div class="layui-input-block">
				<input type="text" id="seo_keywords" name="seo_keywords" class="layui-input" value="<?php echo $rs['seo_keywords'];?>" />
			</div>
			<div class="layui-input-block mtop"><?php echo P_Lang("多个关键字用英文逗号隔开，为空将使用默认");?></div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">
				<?php echo P_Lang("SEO描述");?>
			</label>
			<div class="layui-input-block">
				<textarea name="seo_desc" id="seo_desc" class="layui-textarea"><?php echo $rs['seo_desc'];?></textarea>
			</div>
			<div class="layui-input-block mtop"><?php echo P_Lang("简单描述该主题信息，用于搜索引挈，不支持HTML，不能超过80个汉字");?></div>
		</div>
	</div>
</div>
<?php } ?>

<div class="layui-card">
	<div class="layui-card-header hand" onclick="$.admin.card(this)">
		<?php echo P_Lang("扩展信息");?>
		<i class="layui-icon layui-icon-right"></i>
	</div>
	<div class="layui-card-body hide">
		<?php if($p_rs['is_identifier'] == 1){ ?>
		<div class="layui-form-item">
			<label class="layui-form-label"><?php echo P_Lang("自定义标识");?></label>
			<div class="layui-input-inline default-auto">
				<input type="text" id="identifier" name="identifier" value="<?php echo $rs['identifier'];?>" class="layui-input" />
			</div>
			<div class="layui-input-inline auto" id="HTML-POINT-PHPOK-IDENTIFIER">
				<input type="button" value="<?php echo P_Lang("随机码");?>" onclick="$.admin.rand()" class="layui-btn layui-btn-sm" />
			</div>
			<div class="layui-input-inline auto gray"><?php echo P_Lang("仅支持字母、数字、下划线或中划线且必须是字母开头");?></div>
		</div>
		<?php } ?>
		<?php if($p_rs['is_userid'] == 1){ ?>
		<div class="layui-form-item">
			<label class="layui-form-label">
				<?php echo P_Lang("会员");?>
			</label>
			<div class="layui-input-inline auto">
				<?php echo form_edit('user_id',$rs['user_id'],'user');?>
			</div>
			<div class="layui-input-inline auto gray lh38"><?php echo P_Lang("绑定会员功能，允许会员对主题进行修改或删除，需要开放发布权限");?></div>
		</div>
		<?php } ?>
		<?php if($p_rs['is_tag'] == 1){ ?>
		<div class="layui-form-item">
			<label class="layui-form-label"><?php echo P_Lang("标签");?></label>
			<div class="layui-input-block">
				<input type="text" name="tag" id="tag" value="<?php echo $rs['tag'];?>" class="layui-input" />
			</div>
			<div class="layui-input-block mtop">
				<div class="layui-btn-group">
					<?php if($tag_config['count'] && $taglist){ ?>
					<?php $tmpid["num"] = 0;$taglist=is_array($taglist) ? $taglist : array();$tmpid = array();$tmpid["total"] = count($taglist);$tmpid["index"] = -1;foreach($taglist as $key=>$value){ $tmpid["num"]++;$tmpid["index"]++; ?>
					<input type="button" value="<?php echo $value['title'];?>" onclick="$.phpok_list.tag_append(this.value,'<?php echo $tag_config['separator'];?>')" class="layui-btn layui-btn-sm" />
					<?php } ?>
					<?php } ?>
					<input type="button" value="<?php echo P_Lang("更多选择");?>" onclick="$.phpok_list.tag()" class="layui-btn layui-btn-sm layui-btn-warm" />
					<input type="button" value="<?php echo P_Lang("清空");?>" onclick="$('input[name=tag]').val('')" class="layui-btn layui-btn-sm layui-btn-danger" />
				</div>
			</div>
			<div class="layui-input-block mtop"><?php echo P_Lang("多个标签用 [title] 分开，最多不能超过10个",array('title'=>'<span style="color:red">'.$tag_config['separator'].'</span>'));?></div>
		</div>
		<?php } ?>
		<?php if($p_rs['is_tpl_content'] == 1){ ?>
		<div class="layui-form-item">
			<label class="layui-form-label">
				<?php echo P_Lang("内容模板");?>
			</label>
			<div class="layui-input-inline default-auto">
				<input type="text" id="tpl" name="tpl" class="layui-input" value="<?php echo $rs['tpl'];?>" />
			</div>
			<div class="layui-input-inline auto gray">
				<div class="layui-btn-group">
					<input type="button" value="<?php echo P_Lang("选择");?>" onclick="phpok_tpl_open('tpl')" class="layui-btn layui-btn-sm" />
					<input type="button" value="<?php echo P_Lang("清空");?>" onclick="$('#tpl').val('');" class="layui-btn layui-btn-sm" />
				</div>
			</div>
			<div class="layui-input-inline auto gray lh38">
				<?php echo P_Lang("为空将使用");?> <span class="red"><?php echo $p_rs['tpl_content'] ? $p_rs['tpl_content'] : $p_rs['identifier'].'_content';?></span>
			</div>
		</div>
		<?php } ?>
		<div class="layui-form-item">
			<label class="layui-form-label">
				<?php echo P_Lang("状态");?>
			</label>
			<div class="layui-input-inline auto">
				<input type="radio" title="<?php echo P_Lang("未审核");?>" name="status" id="status_0" value="0"<?php if($id && !$rs['status']){ ?> checked<?php } ?> />
				<input type="radio" name="status" title="<?php echo P_Lang("已审核");?>" id="status_1" value="1"<?php if(!$id || $rs['status']){ ?> checked<?php } ?> />
			</div>
			<div class="layui-input-inline auto gray lh38"><?php echo P_Lang("未审核主题前台不可用，不可访问");?></div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">
				<?php echo P_Lang("是否隐藏");?>
			</label>
			<div class="layui-input-inline auto">
				<input type="radio" title="<?php echo P_Lang("显示");?>" name="hidden" id="hidden_0" value="0"<?php if(!$rs['hidden']){ ?> checked<?php } ?> />
				<input type="radio" title="<?php echo P_Lang("隐藏");?>" name="hidden" id="hidden_1" value="1"<?php if($rs['hidden']){ ?> checked<?php } ?> />
			</div>
			<div class="layui-input-inline auto gray lh38"><?php echo P_Lang("隐藏的主题在列表中不可见，但可以手工输网址访问");?></div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">
				<?php echo P_Lang("发布时间");?>
			</label>
			<div class="layui-input-inline auto">
				<input type="text" id="dateline" name="dateline" class="layui-input" value="<?php if($rs['dateline']){ ?><?php echo date('Y-m-d H:i:s',$rs['dateline']);?><?php } ?>" />
			</div>
			<div class="layui-input-inline auto">
				<div class="layui-btn-group">
					<input type="button" value="<?php echo P_Lang("时间选择器");?>" onclick="$.phpokform.laydate_button('dateline','datetime')" class="layui-btn layui-btn-sm" id="btn_dateline_datetime" />
					<input type="button" value="<?php echo P_Lang("清空");?>" onclick="$.phpokform.clear('dateline')" class="layui-btn layui-btn-sm" />
				</div>
			</div>
			<div class="layui-input-inline auto gray lh30"><?php echo P_Lang("自定义发布时间，留空使用系统时间");?></div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">
				<?php echo P_Lang("阅读次数");?>
			</label>
			<div class="layui-input-inline auto">
				<input type="text" id="hits" name="hits" class="layui-input" value="<?php echo $rs['hits'];?>" />
			</div>
			<div class="layui-input-inline auto gray lh30"><?php echo P_Lang("正常情况请不要设置，以保证数据的准确，仅支持整数");?></div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">
				<?php echo P_Lang("财富基数");?>
			</label>
			<div class="layui-input-inline auto">
				<input type="text" id="integral" class="layui-input" name="integral" value="<?php echo $rs['integral'];?>" />
			</div>
			<div class="layui-input-inline auto gray lh38"><?php echo P_Lang("只支持整数，用于计算会员虚拟财富增减");?></div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">
				<?php echo P_Lang("排序");?>
			</label>
			<div class="layui-input-inline auto">
				<input type="text" id="sort" name="sort" class="layui-input" value="<?php echo $rs['sort'];?>" />
			</div>
			<div class="layui-input-inline auto gray lh38"><?php echo P_Lang("排序值只支持数字，不清楚请留空");?></div>
		</div>
	</div>
</div>
<div class="submit-info">
	<div class="layui-container center">
		<input type="submit" value="<?php echo P_Lang("保存");?>" class="layui-btn layui-btn-lg layui-btn-danger" />
		<input type="button" value="<?php echo P_Lang("取消关闭");?>" class="layui-btn layui-btn-lg layui-btn-primary" onclick="$.admin.close()" />
	</div>
</div>
<div class="submit-info-clear"></div>
</form>
<?php $this->output("foot_lay","file",true,false); ?>