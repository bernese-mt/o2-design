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

class guestbookAModel extends X {
    
    public function getList($items) {
        $where      = " WHERE 1=1".$items['searchsql'];
        $start      = ($items['page']-1)*$items['pagesize'];
        $countsql   = "SELECT COUNT(v.gid) FROM ".DB_PREFIX."guestbook AS v".$where;
        $total      = parent::$obj->fetch_count($countsql);
        $sql        = "SELECT v.*FROM ".DB_PREFIX."guestbook AS v".
                      $where." ORDER BY v.addtime DESC LIMIT ".$start.", ".$items['pagesize']."";
        $array = parent::$obj->getall($sql);
        return array($total, $array);
    }
    
    
    public function getData($id) {
        $sql = "SELECT v.* FROM ".DB_PREFIX."guestbook AS v".
                " WHERE v.gid='".intval($id)."'";
        $data = parent::$obj->fetch_first($sql);
        return array($data);
    }
    
    
    public function doAdd($array) {
        $gid = parent::$obj->fetch_newid('SELECT MAX(gid) FROM '.DB_PREFIX.'guestbook', 1);
        $array = array_merge(array(
                'gid'=>$gid,
				'ip'=>XRequest::getip(),
            ), $array);
        $result = parent::$obj->insert(DB_PREFIX.'guestbook', $array);
		return $result;        
    }
    
    
    public function doEdit($id, $array) {
        $result = parent::$obj->update(DB_PREFIX.'guestbook', $array, "gid='".intval($id)."'");        
        return $result;
    }
    
    
    public function doDel($id) {
		return parent::$obj->query("DELETE FROM ".DB_PREFIX."guestbook WHERE gid='".intval($id)."'");
    }
        
    
    public function doUpdate($id, $type) {
        if ($type == 'flagopen') {
            return parent::$obj->query("UPDATE ".DB_PREFIX."guestbook SET flag='1' WHERE gid='".intval($id)."'");     
        }
        elseif ($type == 'flagclose') {
            return parent::$obj->query("UPDATE ".DB_PREFIX."guestbook SET flag='0' WHERE gid='".intval($id)."'"); 
        }
    }
}
?>
