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

class aboutAModel extends X {
    
    public function getList($items) {
        $where      = " WHERE 1=1".$items['searchsql'];
        $start      = ($items['page']-1)*$items['pagesize'];
        $countsql   = "SELECT COUNT(*) AS my_count FROM ".DB_PREFIX."about AS v".$where;
        $total      = parent::$obj->fetch_count($countsql);
        $sql        = "SELECT v.*, c.catname,c.orders FROM ".DB_PREFIX."about AS v".
                        " LEFT JOIN ".DB_PREFIX."category AS c ON v.catid=c.catid".
                      $where." ORDER BY c.orders ASC LIMIT ".$start.", ".$items['pagesize']."";
        $array = parent::$obj->getall($sql);
        return array($total, $array);
    }
    
    
    public function getData($id) {
        $sql = "SELECT v.*, c.catname FROM ".DB_PREFIX."about AS v".
                " LEFT JOIN ".DB_PREFIX."category AS c ON v.catid=c.catid".
                " WHERE v.abid='".intval($id)."'";
        return parent::$obj->fetch_first($sql);
    }
    
    
    public function doAdd($catid) {
        $sql = "SELECT * FROM ".DB_PREFIX."category WHERE catid='".intval($catid)."'";
        $rows = parent::$obj->fetch_first($sql);
        if (!empty($rows)) {
            $m = parent::model('category', 'am');
            $treeid = $m->getTreeID($catid);
            unset($m);
            
            $abid = parent::$obj->fetch_newid("SELECT MAX(abid) FROM ".DB_PREFIX."about", 1);
            $array = array(
                'abid'=>$abid,
                'treeid'=>$treeid,
                'catid'=>$catid,
                'title'=>$rows['catname'],
            );
            return parent::$obj->insert(DB_PREFIX.'about', $array);
        }
        else {
            return false;
        }
        unset($rows);     
    }
    
    
    public function doEdit($id, $array) {
        return parent::$obj->update(DB_PREFIX.'about', $array, "abid='".intval($id)."'");
    }
    
    
    public function doDel($id) {
        $sql = "DELETE FROM ".DB_PREFIX."about WHERE abid='".intval($id)."'";
        return parent::$obj->query($sql);
    }
    
    
    public function doAddExistsFileName($filename) {
        $sql = "SELECT abid FROM ".DB_PREFIX."about WHERE filename='{$filename}'";
        $rows = parent::$obj->fetch_first($sql);
        if (!empty($rows)) {
            return true;
        }
        else {
            return false;
        }
    }
    
    
    public function doEditExistsFileName($id, $filename) {
        $sql = "SELECT COUNT(*) AS my_count FROM ".DB_PREFIX."about WHERE filename='{$filename}' AND abid<>'{$id}'";
        $count = parent::$obj->fetch_count($sql);
        if ($count>0) {
            return true;
        }
        else {
            return false;
        }
    }
   
}
?>
