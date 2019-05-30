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

class relatedlinkAModel extends X {
    
    public function getList($items) {
        $where      = " WHERE 1=1".$items['searchsql'];
        $start      = ($items['page']-1)*$items['pagesize'];
        $countsql   = "SELECT COUNT(id) FROM ".DB_PREFIX."relatedlink".$where;
        $total      = parent::$obj->fetch_count($countsql);
        $sql        = "SELECT * FROM ".DB_PREFIX."relatedlink".
                      $where." ORDER BY id DESC LIMIT ".$start.", ".$items['pagesize']."";
        $array = parent::$obj->getall($sql);
        return array($total, $array);
    }
    
    
    public function getData($id) {
        $sql = "SELECT * FROM ".DB_PREFIX."relatedlink WHERE id='".intval($id)."'";
        return parent::$obj->fetch_first($sql);
    }
    
    
    public function doAdd($array) {
        $id = parent::$obj->fetch_newid('SELECT MAX(id) FROM '.DB_PREFIX.'relatedlink', 1);
        $array = array_merge(array(
                'id'=>$id,
                'timeline'=>time(),
            ), $array);
        return parent::$obj->insert(DB_PREFIX.'relatedlink', $array); 
    }
    
    
    public function doEdit($id, $array) {
        return parent::$obj->update(DB_PREFIX.'relatedlink', $array, 'id='.$id.'');
    }
    
    
    public function doDel($id) {
        $sql = "DELETE FROM ".DB_PREFIX."relatedlink WHERE id='".intval($id)."'";
        return parent::$obj->query($sql);
    }
        
    
    public function doUpdate($id, $type) {
        if ($type == 'flagopen') {
            return parent::$obj->query("UPDATE ".DB_PREFIX."relatedlink SET flag='1' WHERE id='".intval($id)."'");     
        }
        elseif ($type == 'flagclose') {
            return parent::$obj->query("UPDATE ".DB_PREFIX."relatedlink SET flag='0' WHERE id='".intval($id)."'"); 
        }
    }
}
?>
