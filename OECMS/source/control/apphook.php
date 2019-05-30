<?php
/**
 * [OECMS] (C)2012-2099 OEdev,Inc.
 * <E-Mail：phpcoo@qq.com>
 * Url www.oedev.net, www.phpcoo.com
 * Update 2013.02.05
*/
if(!defined('IN_OECMS')) {
	exit('Access Denied');
}

function _loading_hook_hooks() {
    $hookpath = BASE_ROOT.'./source/hook/';
    $handle = @opendir($hookpath);
    $hookfile[] = null;
    while ($file = @readdir($handle)) {
        if (preg_match("/^[\w\.\/]+\.php$/", $file)) {
            require_once $hookpath.$file;
        }
    }
}
_loading_hook_hooks();
?>
