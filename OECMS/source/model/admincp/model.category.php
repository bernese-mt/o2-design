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

class categoryAModel extends X {
    
    public function getList() {
        $count_sql = "SELECT COUNT(catid) FROM ".DB_PREFIX."category";
        $count = parent::$obj->fetch_count($count_sql);
        return array($count, $this->getAllNode());
    }
    
    
    public function getData($id) {
        $sql = "SELECT v.*, m.modname,m.color AS modcolor FROM ".DB_PREFIX."category AS v".
                " LEFT JOIN ".DB_PREFIX."module AS m ON v.modalias=m.alias".
                " WHERE v.catid='".intval($id)."'";
        return parent::$obj->fetch_first($sql);
    }
    
    
    public function getCatName($id) {
        $sql = "SELECT modalias,catname FROM ".DB_PREFIX."category WHERE catid='".intval($id)."'";
        $rows = parent::$obj->fetch_first($sql);
        if (!empty($rows)) {
            return $rows;
        }
        else {
            return '';
        }
        unset($rows);
    }
    
    
    public function getCatModule($id) {
        $sql = "SELECT v.modalias,m.modname FROM ".DB_PREFIX."category AS v".
                " LEFT JOIN ".DB_PREFIX."module AS m ON v.modalias=m.alias".
                " WHERE v.catid='".intval($id)."'";
        return parent::$obj->fetch_first($sql);
    }
    
    
    
    public function doAddNodeList($array) {
        $catid = parent::$obj->fetch_newid('SELECT MAX(catid) FROM '.DB_PREFIX.'category', 1);
        $array = array_merge(
            array('catid'=>$catid),
            $array
        );
        $res = parent::$obj->insert(DB_PREFIX.'category', $array);
        if (true === $res) {
            $this->_updateCache();
        }
        return $res;
    }
    
    
    public function doEditNodeList($id, $array) {
        $res = parent::$obj->update(DB_PREFIX.'category', $array, "catid='{$id}'");
        if (true === $res) {
            $this->_updateCache();
        }
        return $res;
    }
    
    
    public function doAddList($array) {
        
        $depth = 0;
        $treeid = $this->getTreeID(intval($array['rootid']));
        $module = $this->getCatModule(intval($array['rootid']));
        $root_depath = $this->_getDepth(intval($array['rootid']));
        $depth = ($root_depath+1);
        $catid = parent::$obj->fetch_newid('SELECT MAX(catid) FROM '.DB_PREFIX.'category', 1);
        $array = array_merge(
            array(
                'catid'=>$catid,
                'treeid'=>$treeid,
                'modalias'=>$module['modalias'],
                'depth'=>$depth,         
            ),
            $array
        );
        $res = parent::$obj->insert(DB_PREFIX.'category', $array);
        if (true === $res) {
            $this->_updateCache();
        }
        return $res;
    }
    
    
    public function doEditList($id, $array) {
        
        $self_depth = $this->_getDepth($id);
        
        $parent_depth = $this->_getDepth($array['rootid']);
        
        if ($array['rootid']>0 && $self_depth<$parent_depth) {
            
            $childs = $this->getChildIDs($id);
            if (true === XHandle::foundInChar($childs, $array['rootid'])) {
                return 1;
            }
        } 
        
        if ($array['rootid'] == 0) {
            $depth = 0;
        }
        else {
            $depth = ($parent_depth+1);
        }
        $array = array_merge($array, array('depth'=>$depth));
        $result = parent::$obj->update(DB_PREFIX.'category', $array, "catid='".intval($id)."'");
        if (true === $result) {
            
            $this->_updateChildDepth($id, $depth);
            $this->_updateCache();      
            return 2;
        }
        else {
            return 3;
        }
    }
    
    
    public function doAddNodeAbout($array) {
        $catid = parent::$obj->fetch_newid('SELECT MAX(catid) FROM '.DB_PREFIX.'category', 1);
        $array = array_merge(
            array(
                'catid'=>$catid,
                'modalias'=>'about',
            ),
            $array
        );
        $result = parent::$obj->insert(DB_PREFIX.'category', $array);
        
        if (true === $result) {
            $abid = parent::$obj->fetch_newid('SELECT MAX(abid) FROM '.DB_PREFIX.'about', 1);
            $about_array = array(
                'abid'=>$abid,
                'treeid'=>$catid,
                'catid'=>$catid,
                'title'=>$array['catname'],
            );
            parent::$obj->insert(DB_PREFIX.'about', $about_array);
            
            $this->_updateCache();
        }
        return $result;
    }
    
    public function doEditNodeAbout($id, $array) {
        $result = parent::$obj->update(DB_PREFIX.'category', $array, "catid='".intval($id)."'");
        if (true === $result) {
            
            $sql = "SELECT * FROM ".DB_PREFIX."about WHERE catid='".intval($id)."'";
            $rows = parent::$obj->fetch_first($sql);
            if (empty($rows)) {
                $abid = parent::$obj->fetch_newid('SELECT MAX(abid) FROM '.DB_PREFIX.'about', 1);
                $about_array = array(
                    'abid'=>$abid,
                    'treeid'=>$id,
                    'catid'=>$id,
                    'title'=>$array['catname'],
                );
                parent::$obj->insert(DB_PREFIX.'about', $about_array);
            }
            
            $this->_updateCache();
        }
        return $result;
    }
    
    
    public function doAddAbout($array) {
        
        $depth = 0;
        $treeid = $this->getTreeID(intval($array['rootid']));
        $module = $this->getCatModule(intval($array['rootid']));
        $root_depath = $this->_getDepth(intval($array['rootid']));
        $depth = ($root_depath+1);
        $catid = parent::$obj->fetch_newid('SELECT MAX(catid) FROM '.DB_PREFIX.'category', 1);
        $array = array_merge(
            array(
                'catid'=>$catid,
                'treeid'=>$treeid,
                'modalias'=>$module['modalias'],
                'depth'=>$depth,         
            ),
            $array
        );
        $result = parent::$obj->insert(DB_PREFIX.'category', $array);
        
        
        if (true === $result) {
            $abid = parent::$obj->fetch_newid('SELECT MAX(abid) FROM '.DB_PREFIX.'about', 1);
            $about_array = array(
                'abid'=>$abid,
				'modalias'=>'about',
                'treeid'=>$treeid,
                'catid'=>$catid,
                'title'=>$array['catname'],
            );
            parent::$obj->insert(DB_PREFIX.'about', $about_array);
            
            $this->_updateCache();
        }
        return $result;
    }
    
    public function doEditAbout($id, $array) {
        
        $self_depth = $this->_getDepth($id);
        
        $parent_depth = $this->_getDepth($array['rootid']);
        
        if ($array['rootid']>0 && $self_depth<$parent_depth) {
            
            $childs = $this->getChildIDs($id);
            if (true === XHandle::foundInChar($childs, $array['rootid'])) {
                return 1;
            }
        } 
        
        if ($array['rootid'] == 0) {
            $depth = 0;
        }
        else {
            $depth = ($parent_depth+1);
        }
        $array = array_merge($array, array('depth'=>$depth));
        $result = parent::$obj->update(DB_PREFIX.'category', $array, "catid='".intval($id)."'");
        if (true === $result) {
            
            $this->_updateChildDepth($id, $depth);
            
            
            $treeid = $this->getTreeID(intval($array['rootid']));
            $sql = "SELECT * FROM ".DB_PREFIX."about WHERE catid='".intval($id)."'";
            $rows = parent::$obj->fetch_first($sql);
            if (empty($rows)) {
                $abid = parent::$obj->fetch_newid('SELECT MAX(abid) FROM '.DB_PREFIX.'about', 1);
                $about_array = array(
                    'abid'=>$abid,
                    'treeid'=>$treeid,
                    'catid'=>$id,
                    'title'=>$array['catname'],
                );
                parent::$obj->insert(DB_PREFIX.'about', $about_array);
            }
            
            $this->_updateCache();
                     
            return 2;
        }
        else {
            return 3;
        }
    }
  
    
    
    
    public function doAddNodeOutside($array) {
        $catid = parent::$obj->fetch_newid('SELECT MAX(catid) FROM '.DB_PREFIX.'category', 1);
        $array = array_merge(
            array(
                'catid'=>$catid,
                'modalias'=>'outside',
                'linktype'=>2, 
            ),
            $array
        );
        $res = parent::$obj->insert(DB_PREFIX.'category', $array);
        if (true === $res) {
            $this->_updateCache();
        }
        return $res;
    }
    
    public function doEditNodeOutside($id, $array) {
        $res = parent::$obj->update(DB_PREFIX.'category', $array, "catid='{$id}'");
        if (true === $res) {
            $this->_updateCache();
        }
        return $res;
    }
    
    
    public function doDel($id) {
        
        if (true === $this->checkExistsChild($id)) {
            return false;
        }
        else {
            $sql = "SELECT * FROM ".DB_PREFIX."category WHERE catid='".intval($id)."'";
            $rows = parent::$obj->fetch_first($sql);
            if (!empty($rows)) {
                
                if ($rows['modalias'] == 'about') {
                    parent::$obj->query("DELETE FROM ".DB_PREFIX."about WHERE catid='{$id}'");
                }
                $res = parent::$obj->query("DELETE FROM ".DB_PREFIX."category WHERE catid='{$id}'");
                if (true === $res) {
                    $this->_updateCache();
                }
                return $res;
            }
            else {
                return false;
            }
            unset($rows);
        }
    }
    

    
    
    public function doUpdate($id, $type) {
        $id = intval($id);
        $res = false;
        if ($type == 'flagopen') {
            $res = parent::$obj->query("UPDATE ".DB_PREFIX."category SET flag='1' WHERE catid='{$id}'");     
        }
        elseif ($type == 'flagclose') {
            $res = parent::$obj->query("UPDATE ".DB_PREFIX."category SET flag='0' WHERE catid='{$id}'"); 
        }
        elseif ($type == 'ismenuopen') {
            $res = parent::$obj->query("UPDATE ".DB_PREFIX."category SET ismenu='1' WHERE catid='{$id}'");
        }
        elseif ($type == 'ismenuclose') {
            $res = parent::$obj->query("UPDATE ".DB_PREFIX."category SET ismenu='0' WHERE catid='{$id}'");
        }
        elseif ($type == 'isaccessoryopen') {
            $res = parent::$obj->query("UPDATE ".DB_PREFIX."category SET isaccessory='1' WHERE catid='{$id}'");
        }
        elseif ($type == 'isaccessoryclose') {
            $res = parent::$obj->query("UPDATE ".DB_PREFIX."category SET isaccessory='0' WHERE catid='{$id}'");
        }
        return $res;
    }
    
    
    public function checkExistsChild($id) {
        $sql = "SELECT COUNT(*) AS my_count FROM ".DB_PREFIX."category WHERE rootid='".intval($id)."'";
        $count = parent::$obj->fetch_count($sql);
        if ($count>0) {
            return true;
        }
        else {
            return false;
        }
    }
    
    
    public function doCheckSystemCat($id) {
        $sql = "SELECT catname FROM ".DB_PREFIX."category WHERE issystem='1' AND catid='".intval($id)."'";
        return parent::$obj->check_data($sql);
    }
    
    
    
    public function doCheckDirName($name) {
        $sql = "SELECT `catid` FROM ".DB_PREFIX."category WHERE `dirname`='{$name}'";
        return parent::$obj->check_data($sql);
    }
    
    
    
    private function _getDepth($id) {
        $sql = "SELECT depth FROM ".DB_PREFIX."category WHERE catid='".intval($id)."'";
        $data = parent::$obj->fetch_first($sql);
        if (!empty($data)) {
            return intval($data['depth']);
        }
        else {
            return 0;
        }
    }
    
    
    public function getChildIDs($id, $args='') {
        $args = $args;
        $query = "SELECT catid,depth FROM ".DB_PREFIX."category WHERE rootid='".intval($id)."'";
        $data = parent::$obj->getall($query);
        foreach($data as $key=>$value) {
            $args = $args.$value['catid'].',';
            $args = $this->getChildIDs($value['catid'], $args);
        }
        return $args; 
    }
    
    
    private function _updateChildDepth($id, $depth) {
        $query = "SELECT catid FROM ".DB_PREFIX."category WHERE rootid='".intval($id)."'";
        $data = parent::$obj->getall($query);
        foreach($data as $key=>$value) {
            $child_depth = $depth+1;
            parent::$obj->update(DB_PREFIX.'category', array('depth'=>$child_depth), "catid='".$value['catid']."'");
            $this->_updateChildDepth($value['catid'], $child_depth);
        }
    }
    
    
    public function getAllNode() {
        $notes = array();
        $i = 0;
        $sql = "SELECT v.*,m.modname,m.color AS modcolor FROM ".DB_PREFIX."category AS v".
                " LEFT JOIN ".DB_PREFIX."module AS m ON v.modalias=m.alias".
                " WHERE v.rootid='0' ORDER BY v.orders ASC";
        $parent_data = parent::$obj->getall($sql);
        foreach ($parent_data as $key=>$value) {
            if ($value['linktpye'] == 2) {
                $url = $value['outurl'];
            }
            if ($value['modalias'] == 'about') {
                $abid = $this->_getAboutID($value['catid']);
                $url = XUrl::getContentUrl('about', $abid);
            }
            else {
                $url = XUrl::getCategoryUrl($value['modalias'], $value['catid'], $value['dirname']);
            }
            $notes = array_merge($notes , array(
                $i=>array(
                    'catid'=>$value['catid'],
                    'modalias'=>$value['modalias'],
                    'modname'=>$value['modname'],
                    'modcolor'=>$value['modcolor'],
                    'catname'=>$value['catname'],
                    'asname'=>$value['asname'],
                    'rootid'=>$value['rootid'],
                    'depth'=>$value['depth'],
                    'childs'=>$value['childs'],
                    'flag'=>$value['flag'],
                    'orders'=>$value['orders'],
                    'dirname'=>$value['dirname'],
                    'dirpath'=>$value['dirpath'],
                    'catpic'=>$value['catpic'],
                    'intro'=>$value['intro'],
                    'metatitle'=>$value['metatitle'],
                    'metakeyword'=>$value['metakeyword'],
                    'metadescription'=>$value['metadescription'],
                    'tplindex'=>$value['tplindex'],
                    'tpllist'=>$value['tpllist'],
                    'tpldetail'=>$value['tpldetail'],
                    'ismenu'=>$value['ismenu'],
                    'isaccessory'=>$value['isaccessory'],
                    'showpart'=>$value['showpart'],
                    'orderby'=>$value['orderby'],
                    'pagemax'=>$value['pagemax'],
                    'linktype'=>$value['linktype'],
                    'outurl'=>$value['outurl'],
                    'purview'=>$value['purview'],
                    'url'=>$url,

                )
            ));
            $i  = $i+1;
            $notes = array_merge($notes, $this->_getChildNode($value['catid'], $i));
        }
        return $notes;
    }
    
    private function _getChildNode($rootid, $i, $data=array()) {
        $nodes = array();
        $nodes = $data;
        $child_sql = "SELECT v.*, m.modname,m.color AS modcolor FROM ".DB_PREFIX."category AS v".
                        " LEFT JOIN ".DB_PREFIX."module AS m ON v.modalias=m.alias".
                        " WHERE v.rootid='$rootid' ORDER BY v.orders ASC";
        $child_data = parent::$obj->getall($child_sql);
        foreach ($child_data as $key=>$value) {
            if ($value['linktpye'] == 2) {
                $url = $value['outurl'];
            }
            if ($value['modalias'] == 'about') {
                $abid = $this->_getAboutID($value['catid']);
                $url = XUrl::getContentUrl('about', $abid);
            }
            else {
                $url = XUrl::getCategoryUrl($value['modalias'], $value['catid'], $value['dirname']);
            }
            $nodes = array_merge($nodes , array(
                $i=>array(
                    'catid'=>$value['catid'],
                    'modalias'=>$value['modalias'],
                    'modname'=>$value['modname'],
                    'modcolor'=>$value['modcolor'],
                    'catname'=>$value['catname'],
                    'asname'=>$value['asname'],
                    'rootid'=>$value['rootid'],
                    'depth'=>$value['depth'],
                    'childs'=>$value['childs'],
                    'flag'=>$value['flag'],
                    'orders'=>$value['orders'],
                    'dirname'=>$value['dirname'],
                    'dirpath'=>$value['dirpath'],
                    'catpic'=>$value['catpic'],
                    'intro'=>$value['intro'],
                    'metatitle'=>$value['metatitle'],
                    'metakeyword'=>$value['metakeyword'],
                    'metadescription'=>$value['metadescription'],
                    'tplindex'=>$value['tplindex'],
                    'tpllist'=>$value['tpllist'],
                    'tpldetail'=>$value['tpldetail'],
                    'ismenu'=>$value['ismenu'],
                    'isaccessory'=>$value['isaccessory'],
                    'showpart'=>$value['showpart'],
                    'orderby'=>$value['orderby'],
                    'pagemax'=>$value['pagemax'],
                    'linktype'=>$value['linktype'],
                    'outurl'=>$value['outurl'],
                    'purview'=>$value['purview'],
                    'url'=>$url,
                ),
            ));
            $i  = $i+1;
            $nodes = $this->_getChildNode($value['catid'], $i, $nodes);
        }
        return $nodes;         
    }
    
    
    
    public function getOneNodeOption($nodeid, $invalue) {
        $temp = '';
        $node_sql = "SELECT catid,catname FROM ".DB_PREFIX."category".
                    " WHERE catid='".intval($nodeid)."'";
        $node_row = parent::$obj->fetch_first($node_sql);
        if (!empty($node_row)){
            $temp .= "<option value='".$node_row['catid']."'";
            if (intval($node_row['catid']) == intval($invalue)) {
                $temp .= " selected";
            }
            $temp .= ">".$node_row['catname']."</option>";
            
            $temp = $temp.$this->_getOptionChild($node_row['catid'], $invalue);
        }
        return $temp;
    }
    
        
    
    public function getAllTreeOption($inputvalue='') {
        $notes = '';
        $parent_sql = "SELECT catid,catname FROM ".DB_PREFIX."category".
                        " WHERE rootid='0' ORDER BY orders ASC";
        $parent_data = parent::$obj->getall($parent_sql);
        foreach($parent_data as $key=>$value){
            $notes .= "<option value='".$value['catid']."'";
            if (intval($value['catid']) == intval($inputvalue)) {
                $notes .= " selected";
            }
            $notes .= ">".$value['catname']."</option>";
            $notes = $notes.$this->_getOptionChild($value['catid'], $inputvalue);
        }
        return $notes;
    }
    
    private function _getOptionChild($rootid, $inputvalue, $args='') {
        $args = $args;
        $child_sql = "SELECT catid,catname,depth FROM ".DB_PREFIX."category".
                        " WHERE rootid='$rootid' ORDER BY orders ASC";
        $child_data = parent::$obj->getall($child_sql);
        foreach ($child_data as $key=>$value) {
            $args .= "<option value='".$value['catid']."'";
            if (intval($value['catid']) == intval($inputvalue)) {
                $args .= " selected";
            }
            $depth_mark ='';
			if($value['depth']==1){
				$depth_mark = "&nbsp;&nbsp;├ ";
			}else{
				for($i=2; $i<=$value['depth']; $i++){
					$depth_mark .= "&nbsp;&nbsp;│";
				}
				$depth_mark .= "&nbsp;&nbsp;├ ";
			}     
            $args .= ">".$depth_mark.$value['catname']."</option>";
            $args = $this->_getOptionChild($value['catid'], $inputvalue, $args);
        }
        return $args;
    }
    
    
    
    public function getForAdmMenu() {
        $root_sql = "SELECT catid,catname,modalias FROM ".DB_PREFIX."category WHERE modalias!='outside' AND rootid='0' AND flag='1' ORDER BY orders ASC";
        $root_data = parent::$obj->getall($root_sql);
        foreach($root_data as $key=>$value) {
            $root_data[$key]['child'] = $this->_getForAdmMenuChild($value['catid']);
        }
        return $root_data;
    }
    
    private function _getForAdmMenuChild($parentid) {
        $child_sql = "SELECT v.catid, v.catname, v.modalias, a.abid".
                        " FROM ".DB_PREFIX."category AS v".
                        " LEFT JOIN ".DB_PREFIX."about AS a ON v.catid=a.catid".
                        " WHERE v.rootid='$parentid' AND v.flag='1' ORDER BY v.orders ASC";
        return parent::$obj->getall($child_sql);
    }
    
    
    
    public function getTreeID($id) {
        $treeid = 0;
        $sql = "SELECT catid,rootid FROM ".DB_PREFIX."category WHERE catid='".intval($id)."'";
        $rows = parent::$obj->fetch_first($sql);
        if (!empty($rows)) {
            if (intval($rows['rootid']) == 0){
                $treeid = $rows['catid'];
            }
            else {
                $treeid = $this->getTreeID($rows['rootid']);
            }
        }
        return $treeid;
    }
    
    
    public function getOptionRoot($module, $invalue) {
        $temp = '';
        $sql = "SELECT catid,catname FROM ".DB_PREFIX."category WHERE rootid='0' AND flag='1'";
        if (true === XValid::isSpChar($module)) {
            $sql .= " AND modalias='".$module."'";
        }
        $sql .= " ORDER BY orders ASC";
        $data = parent::$obj->getall($sql);
        foreach($data as $key=>$value) {
            $temp .= "<option value='".$value['catid']."'";
            if (intval($value['catid']) == intval($invalue)) {
                $temp .= " selected";
            }
            $temp .= ">".$value['catname']."</option>";
        }
        return $temp;        
    }
    
    
    public function getNodeEnableChilds($nodeid, $invalue, $args='') {
        $args = $args;
        $child_sql = "SELECT catid,catname,depth,linktype FROM ".DB_PREFIX."category".
                        " WHERE rootid='{$nodeid}' AND flag='1' ORDER BY orders ASC";
        $child_data = parent::$obj->getall($child_sql);
        foreach ($child_data as $key=>$value) {
            
            if ($value['linktype'] == 1){
                $args .= "<option value='".$value['catid']."'";
                if (intval($value['catid']) == intval($invalue)) {
                    $args .= " selected";
                }
                $depth_mark ='';
                if ($value['depth']>1) {
        			if($value['depth']==2){
        				$depth_mark = "&nbsp;&nbsp;├ ";
        			}else{
        				for($i=3; $i<=$value['depth']; $i++){
        					$depth_mark .= "&nbsp;&nbsp;│";
        				}
        				$depth_mark .= "&nbsp;&nbsp;├ ";
        			}   
                }  
                $args .= ">".$depth_mark.$value['catname']."</option>";
            }
            
            else {
                $depth_mark ='';
                if($value['depth']>1) {
        			if($value['depth']==2){
        				$depth_mark = "&nbsp;&nbsp;├ ";
        			}else{
        				for($i=3; $i<=$value['depth']; $i++){
        					$depth_mark .= "&nbsp;&nbsp;│";
        				}
        				$depth_mark .= "&nbsp;&nbsp;├ ";
        			}
                }
                $args .= "<optgroup label='".$depth_mark.$value['catname']."'></optgroup>";
            }
            $args = $this->getNodeEnableChilds($value['catid'], $invalue, $args);
        }
        return $args;
    }
    
    
    public function getCatalogData() {
        $sql = "SELECT `dirname` FROM ".DB_PREFIX."category".
                " WHERE `dirname`!=''";
        $data = parent::$obj->getall($sql);
        return $data;
        unset($sql, $data);
    }
    
    
    private function _updateCache() {
        $cache = parent::import('cache', 'lib');
        $cache->updateCache('catalog');
        unset($cache);
    }
    
    
    public function isForbidCatname($name) {
        $res = false;
        if (!empty($name)) {
            $forbids = require_once(BASE_ROOT.'./source/conf/fbcatname.php');
            if (in_array($name, $forbids)) {
                $res = true;
            }
        }
        return $res;
    }
    
    
    private function _getAboutID($cid) {
        $abid = 0;
        $sql = "SELECT `abid` FROM ".DB_PREFIX."about".
                " WHERE `catid`='{$cid}'";
        $rows = parent::$obj->fetch_first($sql);
        if (!empty($rows)) {
            $abid = $rows['abid'];
        }
        unset($sql, $rows);
        return $abid;
    }
    
}

?>
