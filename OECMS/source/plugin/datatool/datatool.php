<?php
/*
Plugin Name: 数据库管理工具
Version: v1.0
Plugin URL:
Description: 数据管理工具，支持数据库分卷备份、恢复等操作。
For Version: OEcms v4所有版本
Author: OEcms官方
Author URL: http://www.phpcoo.com/
Last Update: 2013-02-05
*/
if(!defined('IN_OECMS')) {
	exit('Access Denied');
}
/**
 * 注册插件管理菜单
*/
function datatool_adm_sidebar() {
	echo "<li><a href='".__ADMIN_FILE__."?c=plugin&a=setting&plugin_id=datatool' target='main'>数据库备份</a></li>";
	echo "<li><a href='".__ADMIN_FILE__."?c=plugin&a=setting&plugin_id=datatool&do=import' target='main'>数据库恢复</a></li>";
}
XHook::addAction('adm_sidebar_ext', 'datatool_adm_sidebar');
?>