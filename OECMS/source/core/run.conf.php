<?php
/**
 * [OECMS] (C)2012-2099 OEdev,Inc.
 * <E-Mail：phpcoo@qq.com>
 * Url www.oedev.net, www.phpcoo.com
 * Update 2013.02.05
*/
header("Content-type: text/html; charset=utf-8");
@set_time_limit(0);
@error_reporting(E_ALL & ~ E_NOTICE);
if(PHP_VERSION<"5.3"){
    @set_magic_quotes_runtime(0);
}
$oecms_mic_time  = explode(' ', microtime());
$oecms_starttime = $oecms_mic_time[1] + $oecms_mic_time[0];
define('IN_OECMS', TRUE);
define('BASE_ROOT', substr(dirname(__FILE__), 0, -11));
if (!function_exists('get_magic_quotes_gpc')) {
    define('MAGIC_QUOTES_GPC', false);
}else {
    define('MAGIC_QUOTES_GPC', @get_magic_quotes_gpc());
}

define('IS_CGI',substr(PHP_SAPI, 0,3)=='cgi' ? 1 : 0 );
define('IS_WIN',strstr(PHP_OS, 'WIN') ? 1 : 0 );
define('IS_CLI',PHP_SAPI=='cli'? 1   :   0);

if(!defined('_PHP_FILE_')) {
	if(IS_CGI) {
		
		$_temp  = explode('.php',$_SERVER['PHP_SELF']);
		define('_PHP_FILE_',  rtrim(str_replace($_SERVER['HTTP_HOST'],'',$_temp[0].'.php'),'/'));
	}else {
		define('_PHP_FILE_',    rtrim($_SERVER['SCRIPT_NAME'],'/'));
	}
}
if(PHP_VERSION < '4.1.0') {
	$_GET     = &$HTTP_GET_VARS;
	$_POST    = &$HTTP_POST_VARS;
	$_COOKIE  = &$HTTP_COOKIE_VARS;
	$_SERVER  = &$HTTP_SERVER_VARS;
	$_ENV     = &$HTTP_ENV_VARS;
	$_FILES   = &$HTTP_POST_FILES;
}


function daddslashes($string) {
    
	if(!MAGIC_QUOTES_GPC) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = daddslashes($val);
			}
		} else {
			$string = addslashes($string);
		}
	}
	return $string;
}
if (isset($_REQUEST['GLOBALS']) OR isset($_FILES['GLOBALS'])){
	exit('Request tainting attempted.');
}


$_GET       = daddslashes($_GET);
$_POST      = daddslashes($_POST);
$_COOKIE	= daddslashes($_COOKIE);
$_REQUEST   = daddslashes($_REQUEST);
$_FILES     = daddslashes($_FILES);
$_SERVER	= daddslashes($_SERVER);


require_once BASE_ROOT.'./source/core/util/static.runtime.php';
XRunTime::start();


$conf_packs = array('db.inc', 'config.inc', 'version.inc');
foreach($conf_packs as $key=>$value){
	if (!file_exists(BASE_ROOT.'./source/conf/'.$value.'.php')) {
		die("sorry, [$value] conf file is not exist!");
	}
    else {
		require_once BASE_ROOT.'./source/conf/'.$value.'.php';
	}
}

if (PHP_VERSION >= '5.1'){
    date_default_timezone_set(OECMS_TIMEZONE);
}

$plugin_hooks = array();
?>
