<!--<?php
# MetInfo Enterprise Content Management System
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.

defined('IN_MET') or exit('No permission');

require $this->template('ui/head');
echo <<<EOT
-->
<link rel="stylesheet" href="{$_M[url][own_tem]}css/metinfo.css?{$jsrand}" />
<div class="v52fmbx">
	<dl>
		<dd class="ftype_description">
			学习模板制作必须具备 HTML 以及 CSS 基础，甚至需要逐步了解 JS 和 PHP 基础。
		</dd>
	</dl>
	<h3 class="v52fmbx_hr">使用帮助手册</h3>
	<dl>
		<dd>
			<a href="http://help.metinfo.cn/help" target="_blank">使用帮助手册</a>
		</dd>
	</dl>
	<h3 class="v52fmbx_hr">模板制作</h3>
	<dl>
		<dd>
			<a href="http://help.metinfo.cn/muban" target="_blank">模板制作</a>
		</dd>
	</dl>
	<h3 class="v52fmbx_hr">开发文档</h3>
	<dl>
		<dd>
			<a href="http://help.metinfo.cn/yingyong" target="_blank">开发文档</a>
		</dd>
	</dl>
	<h3 class="v52fmbx_hr">模板标签</h3>
	<dl>
		<dd>
			<a href="http://help.metinfo.cn/mblable" target="_blank">模板标签</a>
		</dd>
	</dl>
	<h3 class="v52fmbx_hr">UI修改文档</h3>
	<dl>
		<dd>
			<a href="http://help.metinfo.cn/ui" target="_blank">UI修改文档</a>
		</dd>
	</dl>
</div>
<!--
EOT;
require $this->template('ui/foot');

# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>