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
class categoryIModel extends X {
    
    public function rootMenu() {
        $args = array(
            'rootid'=>0,
            'ismenu'=>1,
        );
        return $this->getChildCategory($args);
    }
    
    
    public function sedMenu() {
        $root_args = array(
            'rootid'=>0,
            'ismenu'=>1,
        );
        $data = $this->getChildCategory($root_args);
        foreach($data as $key=>$value) {
            $child_args = array(
                'rootid'=>$value['catid'],
                'ismenu'=>1,
            );
            $data[$key]['childmenu'] = $this->getChildCategory($child_args);
        }
        return $data;
    }
    
    
    public function subMenu($args=NULL){
        if (is_array($args)) {
            $args = array_merge($args, array('isaccessory'=>1));
        }
        else {
            $args = array('isaccessory'=>1);
        }
        return $this->getChildCategory($args);
    }
    
    
    public function rootCategory($args) {
        return $this->getChildCategory($args);
    }
    
    
    public function treeCategory($args) {
        $data = $this->getChildCategory($args);
        foreach($data as $key=>$value) {
            $child_args = array(
                'rootid'=>$value['catid'],
            );
            $data[$key]['childcatgory'] = $this->getChildCategory($child_args);
        }
        return $data;
    }
    
    
    public function getChildCategory($args) {
        $query = "SELECT catid,modalias,catname,asname,dirname,rootid,dirpath,linktype,outurl".
                    " FROM ".DB_PREFIX."category WHERE flag='1'";
        
        if (true == XValid::isSpChar($args['module'])) {
            $query .= " AND modalias='{$args['module']}'";
        }
        if (true === XValid::isNumber($args['treeid'])) {
            $query .= " AND treeid='{$args['treeid']}'";
        }
        if (true === XValid::isNumber($args['rootid'])) {
            $query .= " AND rootid='{$args['rootid']}'";
        }
        if (true === XValid::isNumber($args['ismenu'])){
            $query .= " AND ismenu='{$args['ismenu']}'";
        }
        if (true === XValid::isNumber($args['isaccessory'])){
            $query .= " AND isaccessory='{$args['isaccessory']}'";
        }
        $query .= " ORDER BY orders ASC";
		if (true === XValid::isNumber($args['num'])) {
			$query .= " LIMIT ".$args['num']."";
		}
        $data = parent::$obj->getall($query);
        $i = 1;
        foreach($data as $key=>$value) {
            $data[$key]['i'] = $i;
            
            
            if ($value['linktype'] == 2) {
                $m = parent::model('label', 'im');
                $data[$key]['url'] = $m->repLabel($value['outurl']);
                unset($m);
            }
            else {
                
                
                if ($value['modalias'] == 'about') {
                    if ($value['rootid'] == 0) {
                        $data[$key]['url'] = '';
                    }
                    else {
                        
                        $abid = $this->_getAboutID($value['catid']);
                        $data[$key]['url'] = XUrl::getContentUrl('about', $abid);
                    }
                }
                else {
                    $data[$key]['url'] = XUrl::getCategoryUrl($value['modalias'], $value['catid'], $value['dirname']);
                }
            }
        }
        return $data;
    }
    
    
    public function getModName($val, $type=1) {
        $modname = '';
        $query = "SELECT modalias FROM ".DB_PREFIX."category";
        if ($type == 1) {
            $val = intval($val);
             $query .= " WHERE catid='{$val}'";
             $rows = parent::$obj->fetch_first($query);
             if (!empty($rows)) {
                $modname = $rows['modalias'];
             }
             unset($rows);
        }
        else {
            if (true == XValid::isSpChar($val)) {
                $query .= " WHERE dirname='{$val}'";
                $rows = parent::$obj->fetch_first($query);
                if (!empty($rows)) {
                    $modname = $rows['modalias'];
                }
                unset($rows);
            }
        }
        return $modname;
    }
    
    
    public function getOneData($id) {
        $sql = "SELECT v.*,m.tplindex AS mod_tplindex,m.tpllist AS mod_tpllist".
                    ",m.tpldetail AS mod_tpldetail".
                    " FROM ".DB_PREFIX."category AS v".
                    " LEFT JOIN ".DB_PREFIX."module AS m ON m.alias=v.modalias".
                    " WHERE v.catid='{$id}'";
        $data = parent::$obj->fetch_first($sql);
        if (!empty($data)) {
            if ($data['linktype'] == 2) {
                $m = parent::model('label', 'im');
                $data['url'] = $m->repLabel($data['outlink']);
                unset($m);
            }
            else {
                $data['url'] = XUrl::getCategoryUrl($data['modalias'], $data['catid'], $data['dirname']);
            }
        }
        unset($sql);
        return $data;
    }
    
    
    public function getID($name) {
        $id = 0;
        if (true === XValid::isSpChar($name)) {
            $query = "SELECT catid FROM ".DB_PREFIX."category WHERE dirpath='{$name}'";
            $rows = parent::$obj->fetch_first($query);
            if (!empty($rows)) {
                $id = intval($rows['catid']);
            }
            unset($rows);
        }
        return $id;
    }
    
    
    public function getCategoryUrl($val, $valtype, $title='', $classname='', $target='_self') {
        $url = "";
        $query = "SELECT catid,modalias,rootid,catname,linktype,outurl,dirname FROM ".DB_PREFIX."category";
        if ($valtype == 1) {
            $query .= " WHERE catid='{$val}'";            
        }
        else {
            $query .= " WHERE dirname='{$val}'";
        }
        $rows = parent::$obj->fetch_first($query);
        if (!empty($rows)) {
            $title = empty($title) ? trim($rows['catname']) : $title;
            
            if ($rows['linktype'] == 2) {
                $m_label = parent::model('label', 'im');
                $caturl = $m_label->repLabel($rows['outurl']);
                unset($m_label);
            }
            else {
                if ($rows['modalias'] == 'about') {
                    $abid = $this->_getAboutID($rows['catid']);
                    $caturl = XUrl::getContentUrl('about', $abid);
                }
                else {
                    $caturl = XUrl::getCategoryUrl($rows['modalias'], $rows['catid'], $rows['dirname']);
                }
            }
        }
        else {
            $caturl = '';
        }
        unset($rows);
        if (!empty($caturl)) {
            return "<a href='{$caturl}' class='{$classname}' target='{$target}'>{$title}</a>";
        }
        else {
            return '';
        }
    }
    
    
    public function getCatID($module, $name) {
        $query = "SELECT catid FROM ".DB_PREFIX."category WHERE modalias='{$module}' AND dirname='{$name}'";
        $rows = parent::$obj->fetch_first($query);
        if (!empty($rows)) {
            return $rows['catid'];
        }
        else {
            return 0;
        }
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
