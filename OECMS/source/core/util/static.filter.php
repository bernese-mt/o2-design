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
class XFilter {
	
	
	public static function filterBadChar($str) {
		if (empty($str) OR $str == '') {
			return;
		}
		else {
			$badstring = array("'", '"', "\"", "=", "#", "$", ">", "<", "\\", "/*", "%", "\0", "%00", '*');
			$newstring = array('', '', '', '', '', '', '', '', '', '', '', '', '', '');
			$str = str_replace($badstring, $newstring, $str);
			return trim($str);
		}	
	}

	
	public static function stripArray(&$_data){
		if (is_array($_data)){
			foreach ($_data as $_key => $_value){
				$_data[$_key] = trim(self::stripArray($_value));
			}
			return $_data;
		}else{
			return stripslashes(trim($_data));
		}
	}	
	
	
	public static function filterSlashes(&$value) {
		if (get_magic_quotes_gpc()) return false;
		$value = (array) $value;
		foreach ($value as $key => $val) {
			if (is_array($val)) {
				self::filterSlashes($value[$key]);
			} else {
				$value[$key] = addslashes($val);
			}
		}
	}
	
	
	public static function filterScript($value) {
		if (empty($value)) {
			return '';
		}
		else {
			$value = preg_replace("/(javascript:)?on(click|load|key|mouse|error|abort|move|unload|change|dblclick|move|reset|resize|submit)/i","&111n\\2", $value);
			$value = preg_replace("/<script(.*?)>(.*?)<\/script>/si","",$value);
			$value = preg_replace("/<iframe(.*?)>(.*?)<\/iframe>/si","",$value);
			$value = preg_replace ("/<object.+<\/object>/iesU", '', $value);
			return $value;
		}
	}
	
	
	public static function filterHtml($value) {
		if (empty($value)) {
			return '';
		}
		else {
			if (function_exists('htmlspecialchars')) {
				return htmlspecialchars($value);
			}
			else {
				return str_replace(array("&", '"', "'", "<", ">"), array("&amp;", "&quot;", "&#039;", "&lt;", "&gt;"), $value);
			}
		}
	}
	
	
	public static function filterSql($value) {
		if (empty($value)) {
			return '';
		}
		else {
			$sql = array("select", 'insert', "update", "delete", "\'", "\/\*", 
							"\.\.\/", "\.\/", "union", "into", "load_file", "outfile");
			$sql_re = array("","","","","","","","","","","","");
			return str_ireplace($sql, $sql_re, $value);
		}
	}
	
	
	public static function filterStr($value) {
		if (empty($value)) {
			return '';
		}
		else {
			$value = trim($value);
			$badstr = array("\0", "%00", "\r", '&', '"', "'", "<", ">", "%3C", "%3E");
			$newstr = array('', '', '', '&amp;', '&quot;', '&#39;', "&lt;", "&gt;", "&lt;", "&gt;");
			$value  = str_ireplace($badstr, $newstr, $value);
			$value  = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4}));)/', '&\\1', $value);
            
			return $value;
		}
	}
	
	
	public static function filterUrl() {
		if (preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) !== preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST']))
			return false;
		return true;
	}
}
?>
