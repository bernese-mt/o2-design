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

class modattrAModel extends X {
    
    public function getList($items) {
        $where      = " WHERE 1=1".$items['searchsql'];
        $start      = ($items['page']-1)*$items['pagesize'];
        $countsql   = "SELECT COUNT(v.aid) FROM ".DB_PREFIX."module_attr AS v".$where;
        $total      = parent::$obj->fetch_count($countsql);
        $sql        = "SELECT v.*, m.modname, c.catname FROM ".DB_PREFIX."module_attr AS v".
                        " LEFT JOIN ".DB_PREFIX."module AS m ON v.modalias=m.alias".
                        " LEFT JOIN ".DB_PREFIX."category AS c ON v.treeid=c.catid".
                      $where." ORDER BY v.treeid ASC,v.orders ASC LIMIT ".$start.", ".$items['pagesize']."";
        $array = parent::$obj->getall($sql);
        return array($total, $array);
    }
    
    
    public function getData($id) {
        $sql = "SELECT v.*, m.modname,c.catname FROM ".DB_PREFIX."module_attr AS v".
                " LEFT JOIN ".DB_PREFIX."module AS m ON v.modalias=m.alias".
                " LEFT JOIN ".DB_PREFIX."category AS c ON v.treeid=c.catid".
                " WHERE v.aid='".intval($id)."'";
        return parent::$obj->fetch_first($sql);
    }
    
    
    public function doAdd($array) {
        $aid = parent::$obj->fetch_newid('SELECT MAX(aid) FROM '.DB_PREFIX.'module_attr', 1);
        $array = array_merge(array(
                'aid'=>$aid,
            ), $array);
        return parent::$obj->insert(DB_PREFIX.'module_attr', $array); 
    }
    
    
    public function doEdit($id, $array) {
        return parent::$obj->update(DB_PREFIX.'module_attr', $array, "aid='".intval($id)."'");
    }
    
    
    public function doDel($id) {
        $sql = "SELECT modalias,treeid FROM ".DB_PREFIX."module_attr WHERE aid='".intval($id)."'";
        $rows = parent::$obj->fetch_first($sql);
        if (!empty($rows)) {
            
            
            $attr_table = DB_PREFIX.$rows['modalias']."_attr";
            parent::$obj->query("DELETE FROM {$attr_table} WHERE aid='".intval($id)."'");
            
            return parent::$obj->query("DELETE FROM ".DB_PREFIX."module_attr WHERE aid='".intval($id)."'");
        }
        else {
            return false;
        }
    }
        
    
    public function doUpdate($id, $type) {
        if ($type == 'flagopen') {
            return parent::$obj->query("UPDATE ".DB_PREFIX."module_attr SET flag='1' WHERE aid='".intval($id)."'");     
        }
        elseif ($type == 'flagclose') {
            return parent::$obj->query("UPDATE ".DB_PREFIX."module_attr SET flag='0' WHERE aid='".intval($id)."'"); 
        }
        elseif ($type == 'isvalidopen') {
            return parent::$obj->query("UPDATE ".DB_PREFIX."module_attr SET isvalid='1' WHERE aid='".intval($id)."'"); 
        }
        elseif ($type == 'isvalidclose') {
            return parent::$obj->query("UPDATE ".DB_PREFIX."module_attr SET isvalid='0' WHERE aid='".intval($id)."'"); 
        }
    }
    
    
    public function doCheckAttrName($module, $name) {
        $sql = "SELECT aid FROM ".DB_PREFIX."module_attr WHERE modalias='".$module."' AND lower(attrname)='".strtolower($name)."'";
        return parent::$obj->check_data($sql);
    }
    
    
    public function doCheckSystemAttr($id){
        $sql = "SELECT aid FROM ".DB_PREFIX."module_attr WHERE `issystem`='1' AND aid='".intval($id)."'";
        return parent::$obj->check_data($sql);    
    }
    
    
    public function checkExistAttr($modalias, $treeid) {
        $sql = "SELECT COUNT(*) AS count FROM ".DB_PREFIX."module_attr WHERE (treeid='".intval($treeid)."' OR treeid='0') AND modalias='".$modalias."' AND flag='1'";
        $count = parent::$obj->fetch_count($sql);
        if ($count>0) {
            return true;
        }  
        else {
            return false;
        }
    }
    
    
    public function getAttrList($modalias, $treeid) {
        $sql = "SELECT * FROM ".DB_PREFIX."module_attr".
                " WHERE (treeid='".intval($treeid)."' OR treeid='0')".
                " AND modalias='".$modalias."' AND flag='1'".
                " ORDER BY orders ASC";
        return parent::$obj->getall($sql);
    }
    
    
    public function getAttrExtValue($modalias, $aid, $relid) {
        $sql = "SELECT extvalue FROM ".DB_PREFIX.$modalias."_attr".
                " WHERE aid='".intval($aid)."' AND relid='".intval($relid)."'";
        $rows = parent::$obj->fetch_first($sql);
        if (!empty($rows)) {
            return trim($rows['extvalue']);
        }
        else {
            return '';
        }  
    }
    
}
?>
