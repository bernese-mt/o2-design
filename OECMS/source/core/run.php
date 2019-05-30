<?php
/**
 * [OECMS] (C)2012-2099 OEdev,Inc.
 * <E-Mail：phpcoo@qq.com>
 * Url www.oedev.net, www.phpcoo.com
 * Update 2013.02.05
*/
require_once('run.conf.php');

if(!defined('OECMS')) {
	header("Location:install/index.php");
}
session_start();

require_once BASE_ROOT.'./source/core/oephp.php';
X::__run();

$util_packs = array('handle', 'request', 'valid', 'filter', 'page', 'file');
foreach($util_packs as $key=>$value){
	X::loadUtil($value);
}

$lib_packs = array('hook', 'url');
foreach($lib_packs as $key=>$value) {
    X::loadLib($value);
}

?>
