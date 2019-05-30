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

class htmllabelAModel extends X {
    
    public function getList($items) {
        $where      = " WHERE 1=1".$items['searchsql'];
        $start      = ($items['page']-1)*$items['pagesize'];
        $countsql   = "SELECT COUNT(labelid) FROM ".DB_PREFIX."htmllabel".$where;
        $total      = parent::$obj->fetch_count($countsql);
        $sql        = "SELECT * FROM ".DB_PREFIX."htmllabel".
                      $where." ORDER BY labelid DESC LIMIT ".$start.", ".$items['pagesize']."";
        $array = parent::$obj->getall($sql);
        return array($total, $array);
    }
    
    
    public function getData($id) {
        $sql = "SELECT * FROM ".DB_PREFIX."htmllabel WHERE labelid='".intval($id)."'";
        return parent::$obj->fetch_first($sql);
    }
    
    
    public function doAdd($array) {
        $labelid = parent::$obj->fetch_newid('SELECT MAX(labelid) FROM '.DB_PREFIX.'htmllabel', 1);
        $array = array_merge(array(
                'labelid'=>$labelid,
                'timeline'=>time(),
            ), $array);
        
        $result = parent::$obj->insert(DB_PREFIX.'htmllabel', $array);
        if (true === $result) {
            $cache = parent::import('cache', 'lib');
            $cache->updateCache('htmllabel');
            unset($cache);
        }
        return $result;
    }
    
    
    public function doEdit($id, $array) {
        $result = parent::$obj->update(DB_PREFIX.'htmllabel', $array, 'labelid='.$id.'');
        if (true === $result) {
            $cache = parent::import('cache', 'lib');
            $cache->updateCache('htmllabel');
            unset($cache);
        }
        return $result;
    }
    
    
    public function doDel($id) {
        $sql = "DELETE FROM ".DB_PREFIX."htmllabel WHERE labelid='".intval($id)."'";
        $result = parent::$obj->query($sql);
        if (true === $result) {
            $cache = parent::import('cache', 'lib');
            $cache->updateCache('htmllabel');
            unset($cache);
        }
        return $result;
    }
        
    
    public function doUpdate($id, $type) {
        if ($type == 'flagopen') {
            $result = parent::$obj->query("UPDATE ".DB_PREFIX."htmllabel SET flag='1' WHERE labelid='".intval($id)."'");     
        }
        elseif ($type == 'flagclose') {
            $result = parent::$obj->query("UPDATE ".DB_PREFIX."htmllabel SET flag='0' WHERE labelid='".intval($id)."'"); 
        }
        if (true === $result) {
            $cache = parent::import('cache', 'lib');
            $cache->updateCache('htmllabel');
            unset($cache);
        }
        return $result;
    }
    
    
    public function doCheckLabel($name) {
        $sql = "SELECT labelid FROM ".DB_PREFIX."htmllabel WHERE lower(labelname)='".strtolower($name)."'";
        return parent::$obj->check_data($sql);
    }
    
    
    public function doCheckSystemLabel($id){
        $sql = "SELECT labelid FROM ".DB_PREFIX."htmllabel WHERE `issystem`='1' AND labelid='".intval($id)."'";
        return parent::$obj->check_data($sql);    
    }
    
}
?>
