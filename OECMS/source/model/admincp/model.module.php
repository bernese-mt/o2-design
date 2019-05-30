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

class moduleAModel extends X {
    
    public function getList() {
        $countsql   = "SELECT COUNT(*) AS my_count FROM ".DB_PREFIX."module";
        $total      = parent::$obj->fetch_count($countsql);
        $sql        = "SELECT * FROM ".DB_PREFIX."module ORDER BY modid ASC";
        $array = parent::$obj->getall($sql);
        return array($total, $array);
    }
    
    
    public function getData($id) {
        $sql = "SELECT * FROM ".DB_PREFIX."module WHERE modid='".intval($id)."'";
        return parent::$obj->fetch_first($sql);
    }
    
    
    public function doEdit($id, $array) {
        return parent::$obj->update(DB_PREFIX.'module', $array, "modid='".intval($id)."'");
    }
        
    
    public function doUpdate($id, $type) {
        if ($type == 'flagopen') {
            return parent::$obj->query("UPDATE ".DB_PREFIX."module SET enabled='1' WHERE modid='".intval($id)."'");     
        }
        elseif ($type == 'flagclose') {
            return parent::$obj->query("UPDATE ".DB_PREFIX."module SET enabled='0' WHERE modid='".intval($id)."'"); 
        }
    }
    
    
    public function getModuleOption($invalue) {
        $sql = "SELECT modid,modname,alias FROM ".DB_PREFIX."module WHERE enabled='1' ORDER BY modid ASC";
        $data = parent::$obj->getall($sql);
        $temp = '';
        foreach($data as $key=>$value){
            $temp .= "<option value='".trim($value['alias'])."'";
            if (trim($invalue) == trim($value['alias'])) {
                $temp .= ' selected';
            }
            $temp .= ">".$value['modname']."</option>";
        } 
        return $temp;
    }
    
    
    public function getListModuleOption($invalue) {
        $sql = "SELECT modid,modname,alias FROM ".DB_PREFIX."module WHERE (alias!='about' AND alias!='outside') AND enabled='1'  ORDER BY modid ASC";
        $data = parent::$obj->getall($sql);
        $temp = '';
        foreach($data as $key=>$value){
            $temp .= "<option value='".trim($value['alias'])."'";
            if (trim($invalue) == trim($value['alias'])) {
                $temp .= ' selected';
            }
            $temp .= ">".$value['modname']."</option>";
        } 
        return $temp;
    }
    
    
}?>
