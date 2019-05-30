<!--<?php
# MetInfo Enterprise Content Management System
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.

defined('IN_MET') or exit('No permission');

require $this->template('ui/head');
echo <<<EOT
-->
<link rel="stylesheet" href="{$_M[url][own_tem]}css/metinfo.css?{$jsrand}" />
<form method="POST" name="myform" class="ui-from" action="" target="_self">
	<div class="v52fmbx">
		<dl>
			<dd class="ftype_description">官方 6.0 新模板请使用“官方商业模板管理工具”应用导入管理。</dd>
		</dl>
		<table class="display dataTable ui-table" data-table-datatype="jsonp" data-table-ajaxurl="{$_M['url']['own_form']}a=dotable_temlist_json">
		    <thead>
		        <tr>
		            <th width="200" data-table-columnclass="met-center">预览图</th>
		            <th width="80" data-table-columnclass="met-center">模板编号</th>
					<th width="80">模板类型</th>
					<th>操作</th>

		        </tr>
		    </thead>
			<tfoot>
				<tr>
					<th colspan="3" class="formsubmit">
						<a href="{$_M['url']['own_name']}&c=temtool&a=dotemin" class="ui-addlist addtem" style="top:0px;left:0px;"><i class="fa fa-plus-circle"></i>新增模板</a>
					</th>
					<th>
						<span class="tips" style="font-weight:normal; color:#666; margin-left:10px;">
						列表中删除模板并不会删除 网站根目录/templates/ 下的模板文件夹
						</span>
					</th>
				</tr>
			</tfoot>
		</table>
	</div>
</form>
<div class="remodal" data-remodal-id="modal"><div class="temset_box"></div></div>
<!--
EOT;
require $this->template('ui/foot');

# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>