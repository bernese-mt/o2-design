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
class XHook extends X{	
	
	public static function addAction($system_hook, $plugin_action){
		global $plugin_hooks;
		if (!@in_array($plugin_action, $plugin_hooks[$system_hook])){
			$plugin_hooks[$system_hook][] = $plugin_action;
		}
		return true;
	}

	
	public static function doAction($hook){
		global $plugin_hooks;
		$args = array_slice(func_get_args(), 1);
		if (isset($plugin_hooks[$hook])){
			foreach ($plugin_hooks[$hook] as $function){
				$string = call_user_func_array($function, $args);
			}
		}
	}
}
?>
