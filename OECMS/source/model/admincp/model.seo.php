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
class seoAModel extends X {
    
    
    public function getAllList() {
        $sql = "SELECT * FROM ".DB_PREFIX."seo".
                " ORDER BY `orders` ASC";
        $data = parent::$obj->getall($sql);
        return $data;
    }
    
    
    public function getData($id) {
        $sql = "SELECT * FROM ".DB_PREFIX."seo WHERE `id`='{$id}'";
        return parent::$obj->fetch_first($sql);
    }
    
    
    public function doAdd($array) {
        $id = parent::$obj->fetch_newid('SELECT MAX(id) FROM '.DB_PREFIX.'seo', 1);
        $array = array_merge(array(
                'id'=>$id,
            ), $array);
        $result = parent::$obj->insert(DB_PREFIX.'seo', $array); 
        if (true === $result) {
            $this->createCache();
        }
        return $result;
    }
    
    
    public function doEdit($id, $array) {
        $result = parent::$obj->update(DB_PREFIX.'seo', $array, '`id`='.$id.'');
        if (true === $result) {
            $this->createCache();
        }
        return $result;
    }
    
    
    public function doUpdate($id, $args) {
        return parent::$obj->update(DB_PREFIX.'seo', $args, '`id`='.$id.'');
    }
    
    
    public function doDel($id) {
        $sql = "DELETE FROM ".DB_PREFIX."seo WHERE `id`='{$id}'";
        $result =  parent::$obj->query($sql);
        if (true === $result) {
            $this->createCache();
        }
        return $result;
    }
    
    
    public function createCache() {
        $cache = parent::import('cache', 'lib');
        $cache->updateCache('seo');
        unset($cache);
    }

}?>
