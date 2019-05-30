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

class articleAModel extends X {
    
    public function getList($items) {
        $where      = " WHERE 1=1".$items['searchsql'];
        $start      = ($items['page']-1)*$items['pagesize'];
        $countsql   = "SELECT COUNT(*) AS my_count FROM ".DB_PREFIX."article AS v".$where;
        $total      = parent::$obj->fetch_count($countsql);
        $sql        = "SELECT v.*,c.catname FROM ".DB_PREFIX."article AS v".
                        " LEFT JOIN ".DB_PREFIX."category AS c ON v.catid=c.catid".
                      $where." ORDER BY v.addtime DESC LIMIT ".$start.", ".$items['pagesize']."";
        $array = parent::$obj->getall($sql);
        foreach ($array as $key=>$value) {
            $array[$key]['url'] = XUrl::getContentUrl('article', $value['articleid']);
        }
        return array($total, $array);
    }
    
    
    public function getData($id) {
        $sql = "SELECT v.*,c.catname FROM ".DB_PREFIX."article AS v".
                " LEFT JOIN ".DB_PREFIX."category AS c ON v.catid=c.catid".
                " WHERE v.articleid='".intval($id)."'";
        $data = parent::$obj->fetch_first($sql);
        
        $attr_sql = "SELECT * FROM ".DB_PREFIX."article_attr WHERE relid='".intval($id)."'";
        $attr_data = parent::$obj->getall($attr_sql);
        return array($data, $attr_data);
    }
    
    
    public function doAdd($array, $attrs) {
        $articleid = parent::$obj->fetch_newid('SELECT MAX(articleid) FROM '.DB_PREFIX.'article', 1);
        
        $m = parent::model('category', 'am');
        $data = $m->getCatModule(intval($array['treeid']));
        $modalias = $data['modalias'];
        unset($m);
        $array = array_merge(array(
                'articleid'=>$articleid,
                'modalias'=>$modalias,
                'updatetime'=>time(),
            ), $array);
        $result = parent::$obj->insert(DB_PREFIX.'article', $array);
        
        
        if (true === $result) {
            if (!empty($attrs)) {
                foreach ($attrs as $key=>$value) {
                    $attr_sql = "SELECT id FROM ".DB_PREFIX."article_attr WHERE aid='".intval($value['aid'])."' AND relid='".intval($articleid)."'";
                    $attr_rows = parent::$obj->fetch_first($attr_sql);
                    
                    if (empty($attr_rows)) {
                        $id = parent::$obj->fetch_newid("SELECT MAX(id) FROM ".DB_PREFIX."article_attr", 1);
                        $attr_array = array(
                            'id'=>$id,
                            'aid'=>intval($value['aid']),
                            'extvalue'=>trim($value['extvalue']),
                            'relid'=>$articleid,
                        );
                        parent::$obj->insert(DB_PREFIX.'article_attr', $attr_array);
                    }
                    
                    else {
                        $attr_array = array(
                            'extvalue'=>trim($value['extvalue']),
                        );
                        parent::$obj->update(DB_PREFIX.'article_attr', $attr_array, "id='".intval($attr_rows['id'])."'");
                    }
                }
            }
        }
        return $result;
    }
    
    
    public function doEdit($id, $array, $attrs) {
        $result = parent::$obj->update(DB_PREFIX.'article', $array, 'articleid='.$id.'');
        
        
        if (true === $result) {
            if (!empty($attrs)) {
                foreach ($attrs as $key=>$value) {
                    $attr_sql = "SELECT id FROM ".DB_PREFIX."article_attr WHERE aid='".intval($value['aid'])."' AND relid='".intval($id)."'";
                    $attr_rows = parent::$obj->fetch_first($attr_sql);
                    
                    if (empty($attr_rows)) {
                        $attr_id = parent::$obj->fetch_newid("SELECT MAX(id) FROM ".DB_PREFIX."article_attr", 1);
                        $attr_array = array(
                            'id'=>$attr_id,
                            'aid'=>intval($value['aid']),
                            'extvalue'=>trim($value['extvalue']),
                            'relid'=>$id,
                        );
                        parent::$obj->insert(DB_PREFIX.'article_attr', $attr_array);
                    }
                    
                    else {
                        $attr_array = array(
                            'extvalue'=>trim($value['extvalue']),
                        );
                        parent::$obj->update(DB_PREFIX.'article_attr', $attr_array, "id='".intval($attr_rows['id'])."'");
                    }
                }
            }
        }
        
        return $result;
    }
    
    
    public function doDel($id) {
        $sql = "SELECT thumbfiles,uploadfiles FROM ".DB_PREFIX."article WHERE articleid='".intval($id)."'";
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
            
            parent::$obj->query("DELETE FROM ".DB_PREFIX."article WHERE articleid='".intval($id)."'");
            
            parent::$obj->query("DELETE FROM ".DB_PREFIX."article_attr WHERE relid='".intval($id)."'");
            return true;
        }
        else {
            return false;
        }
    }
        
    
    public function doUpdate($id, $type) {
        if ($type == 'flagopen') {
            return parent::$obj->query("UPDATE ".DB_PREFIX."article SET flag='1' WHERE articleid='".intval($id)."'");     
        }
        elseif ($type == 'flagclose') {
            return parent::$obj->query("UPDATE ".DB_PREFIX."article SET flag='0' WHERE articleid='".intval($id)."'"); 
        }
        elseif ($type == 'istopopen') {
            return parent::$obj->query("UPDATE ".DB_PREFIX."article SET istop='1' WHERE articleid='".intval($id)."'");   
        }
        elseif ($type == 'istopclose') {
            return parent::$obj->query("UPDATE ".DB_PREFIX."article SET istop='0' WHERE articleid='".intval($id)."'");   
        }
        elseif ($type == 'eliteopen') {
            return parent::$obj->query("UPDATE ".DB_PREFIX."article SET elite='1' WHERE articleid='".intval($id)."'");   
        }
        elseif ($type == 'eliteclose') {
            return parent::$obj->query("UPDATE ".DB_PREFIX."article SET elite='0' WHERE articleid='".intval($id)."'");   
        }
    }
    
    
    public function doAddExistsFileName($filename) {
        $sql = "SELECT articleid FROM ".DB_PREFIX."article WHERE filename='{$filename}'";
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
        $sql = "SELECT COUNT(*) AS my_count FROM ".DB_PREFIX."article WHERE filename='{$filename}' AND articleid<>'{$id}'";
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
