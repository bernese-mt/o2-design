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
class XOption extends X{
	
	
    public static function get($optionname){
        $need_unserialize = array(
            'active_plugins', 'site_base', 'upload_config', 
            'index_style', 'main_style', 'order_config', 'site_rewrite',
        );
		if (!empty($optionname)){
            
            $cache = parent::import('cache', 'lib');
            $data = $cache->readCache('options');
            unset($cache);
            if (!empty($data)) {        
                if (in_array($optionname, $need_unserialize)){
                    parent::loadUtil('handle');
                    return XHandle::dounSerialize($data[$optionname]);
                }
                else {
                    return $data[$optionname];
                }
            }
            else {
                return array();
            }
		}
	}

    
	public static function updateOption($name, $value){
		parent::$obj->update(DB_PREFIX."options", array('optionvalue'=>$value), "optionname='{$name}'");
        
        $cache = parent::import('cache', 'lib');
        $cache->updateCache('options');
        unset($cache);
	}
}
?>
