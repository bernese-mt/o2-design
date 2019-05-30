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
class modattrIModel extends X {
       
    public function getAttrList($module, $relid) {
        if (in_array($module, array('article', 'product', 'photo', 'download', 'hr')) && $relid>0) {
            $sql = "SELECT v.extvalue,m.typename,m.inputtype,m.attrname".
                    " FROM ".DB_PREFIX.$module."_attr AS v".
                    " LEFT JOIN ".DB_PREFIX."module_attr AS m ON v.aid=m.aid".
                    " WHERE v.relid='{$relid}'".
                    " ORDER BY m.orders ASC";
            $data = parent::$obj->getall($sql);
            $i = 1;
            foreach ($data as $key=>$value) {
                if ($value['inputtype'] == 'attchment' || $value['inputtype'] == 'img') {
                    if (!empty($value['extvalue']) && substr($value['extvalue'], 0, 15) == 'data/attachment') {
                        $data[$key]['extvalue'] = parent::$urlpath.$value['extvalue'];
                    }
                }
                $data[$key]['i'] = $i;
                $i = ($i+1);
            }
            return $data;
        }
        else {
            return NULL;
        }
    }
    
    
    public function assembleAttr($module, $relid) {
        $data = array();
        if (in_array($module, array('article', 'product', 'photo', 'download', 'hr')) && $relid>0) {
            $array = $this->getAttrList($module, $relid);
            foreach ($array as $key=>$value) {
                
                $data['extend'][$value['attrname']] = array(
                    'text'=>$value['typename'],
                    'value'=>$value['extvalue']
                );
            }
        }
        return $data;
        
    }
}
?>
