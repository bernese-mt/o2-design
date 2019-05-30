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

class settingAModel extends X {
    
    public function getOptions($option) {
        parent::loadLib('option');
        return XOption::get($option); 
    }
    
    
    public function doSave($option, $array) {
        $data = serialize($array);
        parent::loadLib('option');
        XOption::updateOption($option, $data);
        return true;
    }
    
    
    public function doUpdate($option, $string){
        $array = array(
            'optionvalue'=>$string,
        );
        $result = parent::$obj->update(DB_PREFIX.'options', $array, "optionname='".$option."'");
        if (true === $result){
            
            $cache = parent::import('cache', 'lib');
            $cache->updateCache('options');
            unset($cache);
            return true;
        }
        else {
            return false;
        } 
    }
    
    
    public function doUpdateCache() {
        $cache = parent::import('cache', 'lib');
        $cache->updateCache();
        unset($cache);
    }
    
        
}
?>
