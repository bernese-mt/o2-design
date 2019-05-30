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

class downloadAModel extends X {
    
    public function getList($items) {
        $where      = " WHERE 1=1".$items['searchsql'];
        $start      = ($items['page']-1)*$items['pagesize'];
        $countsql   = "SELECT COUNT(*) AS my_count FROM ".DB_PREFIX."download AS v".$where;
        $total      = parent::$obj->fetch_count($countsql);
        $sql        = "SELECT v.*,c.catname FROM ".DB_PREFIX."download AS v".
                        " LEFT JOIN ".DB_PREFIX."category AS c ON v.catid=c.catid".
                      $where." ORDER BY v.addtime DESC LIMIT ".$start.", ".$items['pagesize']."";
        $array = parent::$obj->getall($sql);
        foreach ($array as $key=>$value) {
            $array[$key]['url'] = XUrl::getContentUrl('download', $value['downid']);
        }
        return array($total, $array);
    }
    
    
    public function getData($id) {
        $sql = "SELECT v.*,c.catname FROM ".DB_PREFIX."download AS v".
                " LEFT JOIN ".DB_PREFIX."category AS c ON v.catid=c.catid".
                " WHERE v.downid='".intval($id)."'";
        $data = parent::$obj->fetch_first($sql);
        
        $attr_sql = "SELECT * FROM ".DB_PREFIX."download_attr WHERE relid='".intval($id)."'";
        $attr_data = parent::$obj->getall($attr_sql);
        return array($data, $attr_data);
    }
    
    
    public function doAdd($array, $attrs) {
        $downid = parent::$obj->fetch_newid('SELECT MAX(downid) FROM '.DB_PREFIX.'download', 1);
        
        $m = parent::model('category', 'am');
        $data = $m->getCatModule(intval($array['treeid']));
        $modalias = $data['modalias'];
        unset($m);
        $array = array_merge(array(
                'downid'=>$downid,
                'modalias'=>$modalias,
                'updatetime'=>time(),
            ), $array);
        $result = parent::$obj->insert(DB_PREFIX.'download', $array);
        
        
        if (true === $result) {
            if (!empty($attrs)) {
                foreach ($attrs as $key=>$value) {
                    $attr_sql = "SELECT id FROM ".DB_PREFIX."download_attr WHERE aid='".intval($value['aid'])."' AND relid='".intval($downid)."'";
                    $attr_rows = parent::$obj->fetch_first($attr_sql);
                    
                    if (empty($attr_rows)) {
                        $id = parent::$obj->fetch_newid("SELECT MAX(id) FROM ".DB_PREFIX."download_attr", 1);
                        $attr_array = array(
                            'id'=>$id,
                            'aid'=>intval($value['aid']),
                            'extvalue'=>trim($value['extvalue']),
                            'relid'=>$downid,
                        );
                        parent::$obj->insert(DB_PREFIX.'download_attr', $attr_array);
                    }
                    
                    else {
                        $attr_array = array(
                            'extvalue'=>trim($value['extvalue']),
                        );
                        parent::$obj->update(DB_PREFIX.'download_attr', $attr_array, "id='".intval($attr_rows['id'])."'");
                    }
                }
            }
        }
        return $result;
    }
    
    
    public function doEdit($id, $array, $attrs) {
        $result = parent::$obj->update(DB_PREFIX.'download', $array, "downid='".intval($id)."'");
        
        
        if (true === $result) {
            if (!empty($attrs)) {
                foreach ($attrs as $key=>$value) {
                    $attr_sql = "SELECT id FROM ".DB_PREFIX."download_attr WHERE aid='".intval($value['aid'])."' AND relid='".intval($id)."'";
                    $attr_rows = parent::$obj->fetch_first($attr_sql);
                    
                    if (empty($attr_rows)) {
                        $attr_id = parent::$obj->fetch_newid("SELECT MAX(id) FROM ".DB_PREFIX."download_attr", 1);
                        $attr_array = array(
                            'id'=>$attr_id,
                            'aid'=>intval($value['aid']),
                            'extvalue'=>trim($value['extvalue']),
                            'relid'=>$id,
                        );
                        parent::$obj->insert(DB_PREFIX.'download_attr', $attr_array);
                    }
                    
                    else {
                        $attr_array = array(
                            'extvalue'=>trim($value['extvalue']),
                        );
                        parent::$obj->update(DB_PREFIX.'download_attr', $attr_array, "id='".intval($attr_rows['id'])."'");
                    }
                }
            }
        }
        
        return $result;
    }
    
    
    public function doDel($id) {
        $sql = "SELECT thumbfiles,uploadfiles,fileurl FROM ".DB_PREFIX."download WHERE downid='".intval($id)."'";
        $rows = parent::$obj->fetch_first($sql);
        if (!empty($rows)) {
			
            
            if (!empty($rows['thumbfiles'])) {
                if (substr($rows['thumbfiles'], 0, 15) == 'data/attachment'){
                    parent::loadUtil('file');
                    XFile::delFile($rows['thumbfiles']);

					
                }
            }
            if (!empty($rows['uploadfiles'])) {
                if (substr($rows['uploadfiles'], 0, 15) == 'data/attachment'){
                    parent::loadUtil('file');
                    XFile::delFile($rows['uploadfiles']);
                }
            }
            
            if (!empty($rows['fileurl'])) {
                if (substr($rows['fileurl'], 0, 15) == 'data/attachment'){
                    parent::loadUtil('file');
                    XFile::delFile($rows['fileurl']);
                }
            }
            
            
            parent::$obj->query("DELETE FROM ".DB_PREFIX."download WHERE downid='".intval($id)."'");
            
            parent::$obj->query("DELETE FROM ".DB_PREFIX."download_attr WHERE relid='".intval($id)."'");
            return true;
        }
        else {
            return false;
        }
    }
        
    
    public function doUpdate($id, $type) {
        if ($type == 'flagopen') {
            return parent::$obj->query("UPDATE ".DB_PREFIX."download SET flag='1' WHERE downid='".intval($id)."'");     
        }
        elseif ($type == 'flagclose') {
            return parent::$obj->query("UPDATE ".DB_PREFIX."download SET flag='0' WHERE downid='".intval($id)."'"); 
        }
        elseif ($type == 'istopopen') {
            return parent::$obj->query("UPDATE ".DB_PREFIX."download SET istop='1' WHERE downid='".intval($id)."'");   
        }
        elseif ($type == 'istopclose') {
            return parent::$obj->query("UPDATE ".DB_PREFIX."download SET istop='0' WHERE downid='".intval($id)."'");   
        }
        elseif ($type == 'eliteopen') {
            return parent::$obj->query("UPDATE ".DB_PREFIX."download SET elite='1' WHERE downid='".intval($id)."'");   
        }
        elseif ($type == 'eliteclose') {
            return parent::$obj->query("UPDATE ".DB_PREFIX."download SET elite='0' WHERE downid='".intval($id)."'");   
        }
    }
    
    
    public function doAddExistsFileName($filename) {
        $sql = "SELECT downid FROM ".DB_PREFIX."download WHERE filename='{$filename}'";
        $rows = parent::$obj->fetch_first($sql);
        if (!empty($rows)) {
            return true;
        }
        else {
            return false;
        }
        unset($rows);
    }
    
    
    public function doEditExistsFileName($id, $filename) {
        $sql = "SELECT COUNT(downid) FROM ".DB_PREFIX."download WHERE filename='{$filename}' AND downid<>'{$id}'";
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
