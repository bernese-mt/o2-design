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
class XSession {
	
	
	public static function set($key, $value='') {
		if (!session_id()) self::start();
		if (!is_array($key)) {
			$_SESSION[$key] = trim($value);
		} else {
			foreach ($key as $k => $v) $_SESSION[$k] = $v;
		}
		return true;
	}
	
	
	public static function get($key) {
		if (!session_id()) self::start();
		return (isset($_SESSION[$key])) ? XFilter::filterBadChar(trim($_SESSION[$key])) : NULL;
	}
	
	
	public static function del($key) {
		if (!session_id()) self::start();
		if (is_array($key)) {
			foreach ($key as $k){
				if (isset($_SESSION[$k])) unset($_SESSION[$k]);
			}
		} else {
			if (isset($_SESSION[$key])) unset($_SESSION[$key]);
		}
		return true;
	}
	
	
	public static function clear() {
		if (!session_id()) self::start();
		session_destroy();
		$_SESSION = array();
	}
	
	
	private static function start() {
		session_start();
	}
	
}?>
