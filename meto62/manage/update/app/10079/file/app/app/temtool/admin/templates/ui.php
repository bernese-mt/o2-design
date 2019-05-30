<!--<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 

defined('IN_MET') or exit('No permission');

require $this->template('ui/head');
echo <<<EOT
-->
<link rel="stylesheet" href="{$_M[url][own_tem]}css/metinfo.css?{$jsrand}" />
<form method="POST" name="myform" class="ui-from" action="{$_M['url']['own_form']}a=doui_save" target="_self">
<div class="v52fmbx">
	<table class="display dataTable ui-table" data-table-ajaxurl="{$_M[url][own_form]}a=doui_list" data-table-pagelength="20">
		<thead>
			<tr>
					<th width="25" data-table-columnclass="met-center"><input name="id" data-table-chckall="id" type="checkbox" value="" /></th>
				<th  width="80">UI名</th>
				<th width="80">类型</th>
				<th  width="80">说明</th>
				<th  width="80">预览</th>
			</tr>
		</thead>
		<tbody>
		<!--这里的数据由控件自动生成-->
		</tbody>
		<tfoot>
				<tr>
					<th colspan="4" class="formsubmit">
						<input type="submit" name="save" value="保存" class="submit" />
					</th>
				</tr>
			</tfoot>
	</table>
</div>
</form>
<!--
EOT;
require $this->template('ui/foot');

# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>