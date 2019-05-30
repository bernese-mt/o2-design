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
class XAssemble extends X{
    public static function __init() {
        self::_initConfig();
    }
     
     private static function _initConfig () {
        parent::loadLib('option');
        $nonce_templet = XOption::get('nonce_templet');
        $site_base = XOption::get('site_base');
        $site_footer = XOption::get('site_footer');
        $index_style = XOption::get('index_style');
        $main_style = XOPtion::get('main_style');
        $rewrite_config = XOption::get('site_rewrite');
		if (!empty($site_base['logo'])) {
			$site_base['logo'] = self::$urlpath.$site_base['logo'];
		}
        $array = array();
        $array = array_merge($array, $site_base, $index_style, 
            $main_style, $rewrite_config, 
            array(
                'site_footer'=>$site_footer,
                'templet'=>$nonce_templet,
            )
        );
        parent::$tplpath = parent::$tplpath.$array['templet'].'/';
        parent::$skinpath = parent::$urlpath.parent::$tplpath;
        parent::$cfg = $array;
        unset($array);
     }
}
?>
