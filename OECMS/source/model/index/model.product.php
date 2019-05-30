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
class productIModel extends X {
    
    public function getList($items) {
        $pagesize   = intval($items['pagesize']);
        $where      = " WHERE 1=1".$items['searchsql'];
        $start      = ($items['page']-1)*$pagesize;
        $countsql   = "SELECT COUNT(*) AS my_count FROM ".DB_PREFIX."product AS v".$where;
        $total      = parent::$obj->fetch_count($countsql);
        $sql        = "SELECT v.*,c.catname,c.asname,c.dirname,c.catpic".
                        ",c.linktype AS catlinktpye,c.outurl".
                        " FROM ".DB_PREFIX."product AS v".
                        " LEFT JOIN ".DB_PREFIX."category AS c ON v.catid=c.catid".
                      $where.$items['orderby']." LIMIT ".$start.", ".$pagesize."";
        $data = parent::$obj->getall($sql);
        return array($total, $this->_handleList($data));
    }
    
    
    public function getVolist($where='', $orderby='', $num=0) {
        $sql = "SELECT v.*,c.catname,c.asname,c.dirname,c.catpic,c.linktype AS catlinktpye,c.outurl".
                " FROM ".DB_PREFIX."product AS v".
                " LEFT JOIN ".DB_PREFIX."category AS c ON v.catid=c.catid".
                " WHERE v.flag='1'";
        $sql .= !empty($where) ? ' AND '.$where : '';
        $sql .= !empty($orderby) ? ' '.$orderby : ' ORDER BY v.addtime DESC';
        $num = intval($num)<1 ? intval(parent::$cfg['productnum']) : intval($num);
        $sql .= " LIMIT ".$num."";
        $data = parent::$obj->getall($sql);
        return $this->_handleList($data);
    }
    
    
    public function getOneData($id) {
        $data = $attr_data = $catdata = array();
        $sql = "SELECT v.*, m.tpldetail AS mod_tpldetail".
                " FROM ".DB_PREFIX."product AS v".
                " LEFT JOIN ".DB_PREFIX."module AS m ON v.modalias=m.alias".
                " WHERE v.productid='{$id}'";
        $data = parent::$obj->fetch_first($sql);
        if (!empty($data)) {
            
            $m_label = parent::model('label', 'im');
            if ($data['linktype'] == 2) {
                $data['url'] = $m_label->repLabel($data['linkurl']);
            }
            else {
                $data['url'] = XUrl::getContentUrl('product', $data['productid']);
            }
            unset($m_label);
            
            if (empty($data['thumbfiles'])) {
                $data['thumbfiles'] = parent::$urlpath.'tpl/static/images/nopic_s.jpg';
            }
            else {
                if (substr($data['thumbfiles'], 0, 15) == 'data/attachment') {
                    $data['thumbfiles'] = parent::$urlpath.$data['thumbfiles'];
                }
            }
            if (empty($data['uploadfiles'])) {
                $data['uploadfiles'] = parent::$urlpath.'tpl/static/images/nopic.jpg';
            }
            else {
                if (substr($data['uploadfiles'], 0, 15) == 'data/attachment') {
                    $data['uploadfiles'] = parent::$urlpath.$data['uploadfiles'];
                }
            }
            
            $m_atrr = parent::model('modattr', 'im');
            $attr_data = $m_atrr->getAttrList('product', $id);
            $extend_data = $m_atrr->assembleAttr('product', $id);
            if (!empty($extend_data)) {
                $data = array_merge($data, $extend_data);
            }
            unset($m_atrr);
           
            
            $m_category = parent::model('category', 'im');
            $catdata = $m_category->getOneData(intval($data['catid']));
            unset($m_category);
            
            
            $this->_updateHits($id);
            
            
            $data['gallery'] = XHandle::dounSerialize($data['albums']);
            if (is_array($data['gallery'])) {
                $i = 1;
                foreach ($data['gallery'] as $k=>$v) {
                    $data['gallery'][$k]['i'] = $i;
                    if (substr($v['imgurl'], 0, 15) == 'data/attachment') {
                        $data['gallery'][$k]['imgurl'] = parent::$urlpath.$v['imgurl'];
                    }
                    if (substr($v['imgthumb'], 0, 15) == 'data/attachment') {
                        $data['gallery'][$k]['imgthumb'] = parent::$urlpath.$v['imgthumb'];
                    }
                    $i++;
                }
                unset($i);
            }
        }
        return array($data, $attr_data, $catdata);
    }
    
    
    public function getPrevious($treeid, $id) {
        $treeid = intval($treeid);
        $id = intval($id);
        $query = "SELECT `productid`, `productname`, `linktype`, `linkurl`".
                " FROM ".DB_PREFIX."product".
                " WHERE `productid`<{$id} AND `flag`='1' AND `treeid`='{$treeid}'".
                " ORDER BY `productid` DESC LIMIT 1";
        $rows = parent::$obj->fetch_first($query);
        if (!empty($rows)) {
            if ($rows['linktype'] == 2) {
                $m_label = parent::model('label', 'im');
                $rows['url'] = $m_label->repLabel($rows['linkurl']);
                unset($m_label);
            }
            else {
                $rows['url'] = XUrl::getContentUrl('product', $rows['productid']);
            }
        }
        unset($query, $treeid, $id);
        return $rows;
    }
    
    
    public function getNext($treeid, $id) {
        $treeid = intval($treeid);
        $id = intval($id);
        $query = "SELECT `productid`, `productname`, `linktype`, `linkurl`".
                " FROM ".DB_PREFIX."product".
                " WHERE `productid`>{$id} AND `flag`='1' AND `treeid`='{$treeid}'".
                " ORDER BY `productid` ASC LIMIT 1";
        $rows = parent::$obj->fetch_first($query);
        if (!empty($rows)) {
            if ($rows['linktype'] == 2) {
                $m_label = parent::model('label', 'im');
                $rows['url'] = $m_label->repLabel($rows['linkurl']);
                unset($m_label);
            }
            else {
                $rows['url'] = XUrl::getContentUrl('product', $rows['productid']);
            }
        }
        unset($query, $treeid, $id);
        return $rows;
    }
    
    
    private function _handleList($data) {
        if (!empty($data)) {
            $i = 1;
            foreach($data as $key=>$value) {            
                
                $m_label = parent::model('label', 'im');
                if ($value['catlinktype'] == 2) {
                    $data[$key]['caturl'] = $m_label->repLabel($value['outurl']);
                }
                else {
                    $data[$key]['caturl'] = XUrl::getCategoryUrl('product', $value['catid'], $value['dirname']);
                }
                if ($value['linktype'] == 2) {
                    $data[$key]['url'] = $m_label->repLabel($value['linkurl']);
                }
                else {
                    $data[$key]['url'] = XUrl::getContentUrl('product', $value['productid']);
                }
                unset($m_label);
                
                
                if (empty($value['thumbfiles'])) {
                    $data[$key]['thumbfiles'] = parent::$urlpath.'tpl/static/images/nopic_s.jpg';
                }
                else {
                    if (substr($value['thumbfiles'], 0, 15) == 'data/attachment') {
                        $data[$key]['thumbfiles'] = parent::$urlpath.$value['thumbfiles'];
                    }
                }
                if (empty($value['uploadfiles'])) {
                    $data[$key]['uploadfiles'] = parent::$urlpath.'tpl/static/images/nopic.jpg';
                }
                else {
                    if (substr($value['uploadfiles'], 0, 15) == 'data/attachment') {
                        $data[$key]['uploadfiles'] = parent::$urlpath.$value['uploadfiles'];
                    }
                }
                
                $data[$key]['sort_productname'] = XHandle::cutStrLen($value['productname'], parent::$cfg['productlen']);
                $data[$key]['i'] = $i;
                $i = ($i+1); 
            }
        } 
        return $data;
    }
    
    
    private function _updateHits($id) {
        parent::$obj->update(DB_PREFIX.'product', array('hits'=>'[[hits+1]]'), 'productid='.$id.'');
    }
}
?>
