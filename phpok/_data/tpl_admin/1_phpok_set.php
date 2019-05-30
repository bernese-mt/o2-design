<?php if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head_lay","file",true,false); ?>
<div class="layui-card">
	<div class="layui-card-header"><?php if($id){ ?><?php echo P_Lang("编辑规则");?><?php } else { ?><?php echo P_Lang("添加规则");?><?php } ?></div>
	<div class="layui-card-body">
		<form method="post" class="layui-form" id="post_save" onsubmit="return $.admin_call.save('post_save')">
		<?php if($id){ ?><input type="hidden" name="id" id="id" value="<?php echo $id;?>"/><?php } ?>
		<div class="layui-form-item">
			<label class="layui-form-label">
				<?php echo P_Lang("标题");?>
			</label>
			<div class="layui-input-block">
				<input id="title" name="title" value="<?php echo $rs['title'];?>" class="layui-input" type="text">
			</div>
			<div class="layui-input-block mtop"><?php echo P_Lang("填写该调用的基本说明，不超过80汉字");?></div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">
				<?php echo P_Lang("标识串");?>
			</label>
			<div class="layui-input-inline default-auto">
				<input id="identifier" name="identifier" value="<?php echo $rs['identifier'];?>" class="layui-input" type="text">
			</div>
			<div class="layui-input-inline auto" id="HTML-POINT-PHPOK-IDENTIFIER"><input type="button" value="<?php echo P_Lang("随机码");?>" onclick="random_string(10)" class="layui-btn layui-btn-sm" /></div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">
				<i class="layui-icon layui-tips" lay-tips="<?php echo P_Lang("请选择要调用的数据类型，注意，不同的调用类型会展现出不同的数据条件，请仔细阅读");?>">&#xe702;</i>
				<?php echo P_Lang("调用类型");?>
			</label>
			<div class="layui-input-block">
				<?php $tmpid["num"] = 0;$phpok_type_list=is_array($phpok_type_list) ? $phpok_type_list : array();$tmpid = array();$tmpid["total"] = count($phpok_type_list);$tmpid["index"] = -1;foreach($phpok_type_list as $key=>$value){ $tmpid["num"]++;$tmpid["index"]++; ?>
                <input name="type_id" value="<?php echo $key;?>" title="<?php echo $value['title'];?>" showid="<?php echo $value['showid'];?>" ajax="<?php echo $value['ajax'];?>" <?php if($rs['type_id'] == $key){ ?> checked<?php } ?> type="radio" onclick="$.admin_call.type_id(this.value)" value="<?php echo $key;?>" >
                <?php } ?>
			</div>
		</div>
		<div class="layui-form-item hide" ext="param" name="ext_pid">
			<label class="layui-form-label title">
				<?php echo P_Lang("关联项目");?></label>
			<div class="layui-input-inline auto">
				<select id="pid" name="pid" lay-ignore onchange="$.admin_call.update_param(this.value)">
                    <option value=""><?php echo P_Lang("请选择…");?></option>
                    <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id = array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist as $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                    <option value="<?php echo $value['id'];?>"<?php if($rs['pid'] == $value['id']){ ?> selected<?php } ?> module="<?php echo $value['module'];?>" rootcate="<?php echo $value['cate'];?>" parentid="<?php echo $value['parent_id'];?>"><?php echo $value['space'];?><?php echo $value['title'];?></option>
                    <?php } ?>
                </select>
			</div>
			<div class="layui-input-inline auto gray lh38"><?php echo P_Lang("动态改变使用方法");?> <span class="red">pid=<?php echo P_Lang("项目ID");?></span></div>
		</div>
        <div class="layui-form-item hide" id="html_cateid" name="ext_cateid" ext="param">
        	<label class="layui-form-label">
        		<?php echo P_Lang("分类");?>
        	</label>
        	<div class="layui-input-inline default-auto">
        		<select name="cateid" id="cateid">
                    <option value="<?php echo $rs['cateid'];?>"><?php echo P_Lang("请选择…");?></option>
                </select>
        	</div>
        	<div class="layui-input-inline auto gray lh38"><?php echo P_Lang("选择要绑定的分类，请先勾选项目");?></div>
        </div>
        <div class="layui-form-item hide" name="ext_is_list" ext="param">
        	<label class="layui-form-label">
        		<?php echo P_Lang("列表模式");?>
        	</label>
        	<div class="layui-input-inline auto">
        		<input name="is_list" value="1" title="<?php echo P_Lang("列表模式");?>" <?php if($rs['is_list'] || !$id){ ?> checked<?php } ?> type="radio">
        		<input name="is_list" value="0" title="<?php echo P_Lang("只读一条");?>" <?php if($id && !$rs['is_list']){ ?> checked<?php } ?> type="radio" />
        	</div>
        </div>
        <div class="layui-form-item hide" name="ext_in_sub" ext="param">
        	<label class="layui-form-label">
        		<?php echo P_Lang("子主题");?>
        	</label>
        	<div class="layui-input-inline auto">
        		<input name="in_sub" value="0" title="<?php echo P_Lang("禁用");?>" <?php if(!$rs['in_sub']){ ?> checked<?php } ?> type="radio"/>
        		<input name="in_sub" value="1" title="<?php echo P_Lang("启用");?>" <?php if($rs['in_sub']){ ?> checked<?php } ?> type="radio">
        	</div>
        	<div class="layui-input-inline auto gray lh38"><?php echo P_Lang("是否同时读取子主题信息");?></div>
        </div>
        <div class="layui-form-item hide" id="html_psize" name="ext_psize" ext="param">
        	<label class="layui-form-label">
        		<?php echo P_Lang("调用数量");?>
        	</label>
        	<div class="layui-input-inline">
        		<input id="psize" name="psize" value="<?php echo $rs['psize'] ? $rs['psize'] : 0;?>" class="layui-input" type="text">
        	</div>
        	<div class="layui-input-inline auto gray lh38"><?php echo P_Lang("设置调用的最大值，设为0表示不限制数量");?></div>
        </div>
        <div class="layui-form-item hide" name="ext_offset" ext="param">
        	<label class="layui-form-label">
        		<?php echo P_Lang("开始位置");?>
        	</label>
        	<div class="layui-input-inline">
        		<input id="offset" name="offset" value="<?php echo $rs['offset'] ? $rs['offset'] : 0;?>" class="layui-input" type="text">
        	</div>
        	<div class="layui-input-inline auto gray lh38"><?php echo P_Lang("不设置将使用0开始调用（即第一条开始）");?></div>
        </div>
        <div class="layui-form-item hide" name="ext_fields_need" ext="param">
        	<label class="layui-form-label">
        		<?php echo P_Lang("值必须存在");?>
        	</label>
        	<div class="layui-input-block">
	        	<div class="w99">
		        	<div class="layui-col-md9">
        				<input id="fields_need" name="fields_need" value="<?php echo $rs['fields_need'];?>" class="layui-input" type="text" />
        			</div>
        			<div class="layui-col-md2" style="margin-left:10px;">
	        			<input class="layui-btn layui-btn-primary" onclick="$('#fields_need').val('')" value="<?php echo P_Lang("清空");?>" type="button" />
	        		</div>
	        	</div>
	        	<div class='clear'></div>
        	</div>
        	<div class="layui-input-block mtop hide">
	        	<div class="layui-btn-group" id="fields_need_list"></div>
        	</div>
        	<div class="layui-input-block">
	        	<?php echo P_Lang("设置哪些字段值必须存在，不存在的值将不被调用，动态配置");?>
	        	<span class="red">[fields_need=<?php echo P_Lang("标识");?>]</span>
        	</div>
        </div>
        <div class="layui-form-item hide" name="ext_fields" ext="param">
        	<label class="layui-form-label">
        		<?php echo P_Lang("扩展字段");?>
        	</label>
        	<div class="layui-input-block">
        		<div class="w99">
		        	<div class="layui-col-md9">
        				<input  id="fields" name="fields" value="<?php echo $rs['fields'] ? $rs['fields'] :'*';?>" class="layui-input" type="text">
        			</div>
        			<div class="layui-col-md2" style="margin-left:10px;">
	        			<input class="layui-btn layui-btn-primary" onclick="$('#fields').val('')" value="<?php echo P_Lang("清空");?>" type="button">
	        		</div>
	        	</div>
        	</div>
        	<div class="layui-input-block mtop">
	        	<div class="layui-btn-group" id="fields_list">
                    <input value="<?php echo P_Lang("全部字段");?>" onclick="input_fields('*')" class="layui-btn layui-btn-sm" type="button">
                </div>
        	</div>
        	<div class="layui-input-block">
	        	<?php echo P_Lang("指定读取的列表包含哪些扩展字段，多个标识用英文逗号隔开，动态配置");?>
	        	<span class="red">[fields=<?php echo P_Lang("字段标识");?>]</span>
        	</div>
        </div>
        <div class="layui-form-item hide" name="ext_keywords" ext="param">
        	<label class="layui-form-label">
        		<?php echo P_Lang("关键字");?>
        	</label>
        	<div class="layui-input-inline default-auto">
        		<input id="keywords" name="keywords" value="<?php echo $rs['keywords'];?>" class="layui-input" type="text">
        	</div>
        	<div class="layui-input-inline auto gray lh38">
	        	<?php echo P_Lang("多个关键字用英文逗号隔开，适用于获取相关内容，动态配置");?>
	        	<span class="red">[keywords=<?php echo P_Lang("关键字");?>]</span>
        	</div>
        </div>
        <div class="layui-form-item hide" name="ext_orderby" ext="param">
        	<label class="layui-form-label">
	        	<i class="layui-icon layui-tips" lay-tips="<?php echo P_Lang("设置数据常用的排序方法");?>">&#xe702;</i>
        		<?php echo P_Lang("数据排序");?>
        	</label>
        	<div class="layui-input-block">
	        	<div class="w99">
		        	<div class="layui-col-md9">
			        	<input id="orderby" name="orderby" value="<?php echo $rs['orderby'];?>" class="layui-input" type="text">
        			</div>
        			<div class="layui-col-md2" style="margin-left:10px;">
	        			<input class="layui-btn layui-btn-primary" onclick="$('#orderby').val('')" value="<?php echo P_Lang("清空");?>" type="button">
	        		</div>
	        	</div>
        	</div>
        	<div class="layui-input-block mtop">
	        	<div class="layui-btn-group" id="orderby_li">
                    <input value="<?php echo P_Lang("点击");?>" onclick="phpok_admin_orderby('orderby','l.hits')" class="layui-btn layui-btn-sm" type="button">
                    <input value="<?php echo P_Lang("时间");?>" onclick="phpok_admin_orderby('orderby','l.dateline')" class="layui-btn layui-btn-sm" type="button">
                    <input value="<?php echo P_Lang("回复时间");?>" onclick="phpok_admin_orderby('orderby','l.replydate')" class="layui-btn layui-btn-sm" type="button">
                    <input value="ID" onclick="phpok_admin_orderby('orderby','l.id')" class="layui-btn layui-btn-sm" type="button">
                    <input value="<?php echo P_Lang("人工");?>" onclick="phpok_admin_orderby('orderby','l.sort')" class="layui-btn layui-btn-sm" type="button">
                    <input value="<?php echo P_Lang("随机，慎用");?>" onclick="$('#orderby').val('RAND()')" class="layui-btn layui-btn-sm" type="button">
                </div>
        	</div>
        </div>
        <div class="layui-form-item hide" name="ext_attr" ext="param">
        	<label class="layui-form-label">
        		<?php echo P_Lang("主题属性");?>
        	</label>
        	<div class="layui-input-inline default-auto">
	        	<select name="attr">
	        		<option value=""><?php echo P_Lang("不使用");?></option>
		        	<?php $tmpid["num"] = 0;$attrlist=is_array($attrlist) ? $attrlist : array();$tmpid = array();$tmpid["total"] = count($attrlist);$tmpid["index"] = -1;foreach($attrlist as $key=>$value){ $tmpid["num"]++;$tmpid["index"]++; ?>
		        	<option value="<?php echo $key;?>"<?php if($rs['attr'] == $key){ ?> selected<?php } ?>><?php echo $value;?>_<?php echo $key;?></option>
		        	<?php } ?>
	        	</select>
        	</div>
        	<div class="layui-input-inline auto gray lh38"><?php echo P_Lang("设置要调用的主题属性，仅支持单选，请慎用");?></div>
        </div>
        <div class="layui-form-item hide" name="ext_title_id" ext="param">
        	<label class="layui-form-label">
        		<?php echo P_Lang("主题标识或ID");?>
        	</label>
        	<div class="layui-input-inline default-auto">
	        	<div class="w99">
		        	<div class="layui-col-md9">
			        	<input id="title_id" name="title_id" value="<?php echo $rs['title_id'];?>" class="layui-input" type="text">
        			</div>
        			<div class="layui-col-md2" style="margin-left:10px;">
	        			<input class="layui-btn layui-btn-primary" onclick="$('#fields_need').val('')" value="<?php echo P_Lang("清空");?>" type="button">
	        		</div>
	        	</div>
        	</div>
        	<div class="layui-input-inline auto gray lh38">
	        	<?php echo P_Lang("填写标识或数字，当填写为纯数字，表示ID，动态配置");?>
	        	<span class="red">[title_id=<?php echo P_Lang("主题ID");?>]</span>
	        </div>
        </div>
        <div class="layui-form-item hide" name="ext_fields_format" ext="param">
        	<label class="layui-form-label">
        		<?php echo P_Lang("格式化");?>
        	</label>
        	<div class="layui-input-inline auto">
        		<input name="fields_format" value="0" title="<?php echo P_Lang("禁用");?>" <?php if(!$rs['fields_format']){ ?> checked<?php } ?> type="radio" />
        		<input name="fields_format" value="1" title="<?php echo P_Lang("启用");?>" <?php if($rs['fields_format']){ ?> checked<?php } ?> type="radio" />
        	</div>
        	<div class="layui-input-inline auto gray lh38"><?php echo P_Lang("启用格式化后，系统会输出一些预设表单的HTML");?></div>
        </div>
        <div class="layui-form-item hide" ext="param" name="ext_user_ext">
        	<label class="layui-form-label">
        		<?php echo P_Lang("会员扩展");?>
        	</label>
        	<div class="layui-input-inline auto">
        		<input name="user_ext" value="0" title="<?php echo P_Lang("禁用");?>" <?php if(!$rs['user_ext']){ ?> checked<?php } ?> type="radio">
	        	<input name="user_ext" value="1" title="<?php echo P_Lang("启用");?>" <?php if($rs['user_ext']){ ?> checked<?php } ?> type="radio">
        	</div>
        	<div class="layui-input-inline auto gray lh38"><?php echo P_Lang("读取数据是否同时读取相应的自定义内容数据");?></div>
        </div>
        <div class="layui-form-item hide" ext="param" name="ext_user">
        	<label class="layui-form-label">
	        	<i class="layui-icon layui-tips" lay-tips="<?php echo P_Lang("多个账号用英文逗号隔开");?>">&#xe702;</i>
        		<?php echo P_Lang("会员账号");?>
        	</label>
        	<div class="layui-input-block">
        		<div class="w99">
        			<div class="layui-col-md9">
        		    	<input id="user" name="user" value="<?php echo $rs['user'];?>" class="layui-input" type="text">
        			</div>
        			<div class="layui-col-md3">
        				<input class="layui-btn layui-btn-primary" onclick="$('#user').val('')" value="<?php echo P_Lang("清空");?>" type="button">
        			</div>
        		</div>
        	</div>
        </div>
        <div class="layui-form-item hide" ext="param" name="ext_usergroup">
        	<label class="layui-form-label">
        		<?php echo P_Lang("会员组");?>
        	</label>
        	<div class="layui-input-inline default-auto">
        		<select name="usergroup" id="usergroup">
                    <option value="">请选择…</option>
                    <?php $rslist_id["num"] = 0;$usergroup=is_array($usergroup) ? $usergroup : array();$rslist_id = array();$rslist_id["total"] = count($usergroup);$rslist_id["index"] = -1;foreach($usergroup as $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                    <option value="<?php echo $value['id'];?>"<?php if($rs['usergroup'] == $value['id']){ ?> selected<?php } ?>><?php echo $value['title'];?></option>
                    <?php } ?>
                </select>
        	</div>
        	<div class="layui-input-inline auto gray lh38"><?php echo P_Lang("指定多个会员账号后，会员组选项将无效");?></div>
        </div>
        <div class="layui-form-item hide" ext="param" name="ext_sqlinfo">
        	<label class="layui-form-label">
        		<?php echo P_Lang("自定义SQL");?>
        	</label>
        	<div class="layui-input-block">
        		<textarea name="sqlinfo" id="sqlinfo" class="layui-textarea" style="height:200px;"><?php echo $rs['sqlinfo'];?></textarea>
        	</div>
        	<div class="layui-input-block mtop"><?php echo P_Lang("这里的自定义的SQL不支持变量，请仔细检查");?></div>
        </div>
        <div class="layui-form-item">
        	<label class="layui-form-label">
        		<?php echo P_Lang("状态");?>
        	</label>
        	<div class="layui-input-inline auto">
        		<input name="status" value="0" title="<?php echo P_Lang("未审核");?>" <?php if($id && !$rs['status']){ ?> checked<?php } ?> type="radio">
	        	<input name="status" value="1" title="<?php echo P_Lang("已审核");?>" <?php if(!$id || $rs['status']){ ?> checked<?php } ?> type="radio">
        	</div>
        	<div class="layui-input-inline auto gray lh38"><?php echo P_Lang("未审核下不能被前台调用");?></div>
        </div>
        <div class="layui-form-item">
        	<label class="layui-form-label">
	        	<i class="layui-icon layui-tips" lay-tips="<?php echo P_Lang("是否启用API接口调用数据，此项很重要，敏感数据（如会员请禁用）请不要启用，为安全考虑，自定义SQL需要在配置文件中启用才有效");?>">&#xe702;</i>
        		<?php echo P_Lang("API调用");?>
        	</label>
        	<div class="layui-input-inline default-auto">
        		<input name="is_api" value="0" title="<?php echo P_Lang("禁用");?>" <?php if(!$rs['is_api']){ ?> checked<?php } ?> type="radio">
	        	<input name="is_api" value="1" title="<?php echo P_Lang("支持");?>" <?php if($rs['is_api']){ ?> checked<?php } ?> type="radio">
        	</div>
        	<div class="layui-input-inline auto gray lh38"></div>
        </div>
        <div class="layui-form-item">
        	<label class="layui-form-label">
        		&nbsp;
        	</label>
        	<div class="layui-input-inline default-auto">
	        	<input type="submit" value="<?php echo P_Lang("提交保存");?>" class="layui-btn layui-btn-lg layui-btn-normal" />
        	</div>
        </div>
		</form>
	</div>
</div>
<div class="clear"></div>

<div class="hide" id="orderby_default">
	<input type="button" value="<?php echo P_Lang("点击");?>" onclick="phpok_admin_orderby('orderby','l.hits')" class="layui-btn layui-btn-sm" />
	<input type="button" value="<?php echo P_Lang("时间");?>" onclick="phpok_admin_orderby('orderby','l.dateline')" class="layui-btn layui-btn-sm" />
	<input type="button" value="<?php echo P_Lang("回复时间");?>" onclick="phpok_admin_orderby('orderby','l.replydate')" class="layui-btn layui-btn-sm" />
	<input type="button" value="ID" onclick="phpok_admin_orderby('orderby','l.id')" class="layui-btn layui-btn-sm" />
	<input type="button" value="<?php echo P_Lang("人工");?>" onclick="phpok_admin_orderby('orderby','l.sort')" class="layui-btn layui-btn-sm" />
	<input type="button" value="<?php echo P_Lang("随机，慎用");?>" onclick="$('#orderby').val('RAND()')" class="layui-btn layui-btn-sm" />
</div>
<div class="hide" id="fields_need_default">
	<input type="button" value="<?php echo P_Lang("会员");?>" onclick="fields_click('l.user_id')" class="layui-btn layui-btn-sm" />
</div>

<?php $this->output("foot_lay","file",true,false); ?>