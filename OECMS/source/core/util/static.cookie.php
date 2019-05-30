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

class XCookie {
	
	private static $prefix = OECMS_COOKIEPRE; 
	private static $expire = 2592000; 
	private static $path   = '/'; 
	private static $domain = '';
	
	
	public static function set($name, $val, $expire = '', $path = '', $domain = '') {
		
        $expire = (empty($expire)) ? time() + 60 * 60 * 24 * 30 * 12 : $expire;
		$path   = (empty($path)) ? self::$path : $path; 
		$domain = (empty($domain)) ? self::$domain : $domain; 
		if (empty($domain)) {
			setcookie(self::$prefix.$name, $val, $expire, $path);
		} else {
			setcookie(self::$prefix.$name, $val, $expire, $path, $domain);
		}
		$_COOKIE[self::$prefix.$name] = $val;
	}
	
	
	public static function get($name) {
		return XFilter::filterBadChar($_COOKIE[self::$prefix.$name]);
	}
	
	
	public static function del($name) {
		self::set($name, '', 0);
		$_COOKIE[self::$prefix.$name] = '';
		unset($_COOKIE[self::$prefix.$name]);
	}
	
	
	public static function is_set($name) {
		return isset($_COOKIE[self::$prefix.$name]);
	}
}?>
