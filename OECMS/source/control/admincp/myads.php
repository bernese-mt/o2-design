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
class control extends adminbase {
    
    
	private $_comeurl = NUll;
    private $_urlitem = NULL;
    private $szoneid = NULL;
    private $sname = NULL;
    
    
    private function _getItems() {
        $this->szoneid = XRequest::getInt('szoneid');
        $this->sname = XRequest::getGpc('sname');
        $this->_urlitem = "szoneid=".$this->szoneid."&sname=".urlencode($this->sname)."";
        $this->_comeurl = $this->_urlitem."&page=".$this->page."";
    }

	
    public function action_run() {
        $this->checkAuth('myads_volist');
        $this->_getItems();
        $searchsql = "";
        if ($this->szoneid>0) {
            $searchsql .= " AND v.zoneid='".$this->szoneid."'";
        }
        if (!empty($this->sname)) {
            $searchsql .= " AND lower(v.adname) LIKE '%".strtolower($this->sname)."%'";
        }
        $model = parent::model('myads', 'am');
        list($total, $data) = $model->getList(array('page'=>$this->page, 'pagesize'=>$this->pagesize, 'searchsql'=>$searchsql));
        
        foreach($data as $key=>$value){
            $data[$key]['normbody'] = XHandle::dounSerialize($value['normbody']);
        }
        $url = XRequest::getPhpSelf();
        $url .= '?c=myads&'.$this->_urlitem;
        parent::loadLib('mod');
        $zone_select = XMod::selectZone($this->szoneid, 'szoneid');
        $var_array = array(
            'page'=>$this->page,
            'nextpage'=>$this->page+1,
            'prepage'=>$this->page-1,
            'pagecount'=>ceil($total/$this->pagesize),
            'pagesize'=>$this->pagesize,
            'total'=>$total,
            'showpage'=>XPage::admin($total, $this->pagesize, $this->page, $url, 10),
            'urlitem'=>$this->_urlitem,
            'comeurl'=>$this->_comeurl,
            'szoneid'=>$this->szoneid,
            'sname'=>$this->sname,
            'myads'=>$data,
            'zone_select'=>$zone_select,
        );
        unset($model);
		TPL::assign($var_array);
		TPL::display($this->cptpl.'myads.tpl');
	}
    
    
    public function action_add() {
        $this->checkAuth('myads_add');
        parent::loadLib('mod');
        $var_array = array(
            'zone_select'=>XMod::selectZone('', 'zoneid'),
        );
        TPL::assign($var_array);
        TPL::display($this->cptpl.'myads.tpl'); 
    }
    
    
    public function action_edit() {
        $this->checkAuth('myads_edit');
        $id = XRequest::getInt('id');
        $this->_validID($id);
        $this->_getItems();
        $model = parent::model('myads', 'am');
        $data = $model->getData($id);
        unset($model);
        if (empty($data)) {
            XHandle::halt('对不起，读取数据不存在！', '', 1);
        }
        else {
            parent::loadLib('mod');
            
            $data['normbody'] = XHandle::dounSerialize($data['normbody']);
            $var_array = array(
                'myads'=>$data,
                'id'=>$id,
                'page'=>$this->page,
                'comeurl'=>$this->_comeurl,
                'zone_select'=>XMod::selectZone($data['zoneid'], 'zoneid'),
            );
            TPL::assign($var_array);
            TPL::display($this->cptpl.'myads.tpl');
        }
    }
    
    
    public function action_saveadd() {
        $this->checkAuth('myads_add');
        $args = $this->_validAdd();
        $model = parent::model('myads', 'am');
        if (true === $model->doCheckTagName($args['tagname'])) {
            XHandle::halt('对不起，该广告标识已存在，请填写另外一个。', '', 1);
        }
        else {
            $result = $model->doAdd($args);
            if (true === $result) {
                $this->log('myads_add', '', 1);
                XHandle::halt('添加成功', $this->cpfile.'?c=myads', 0);
            }
            else {
                $this->log('myads_add', '', 0);
                XHandle::halt('添加失败', '', 1);
            }
        }
        unset($model);	
	}
    
    
    public function action_saveedit() {
        $this->checkAuth('myads_edit');
        $this->_getItems();
        list($id, $args) = $this->_validEdit();
        $model = parent::model('myads', 'am');
        $result = $model->doEdit($id, $args);
        unset($model);
        if (true === $result) {
            $this->log('myads_add', "id={$id}", 1);
            XHandle::halt('编辑成功', $this->cpfile.'?c=myads&'.$this->_comeurl.'', 0);
        }
        else {
            $this->log('myads_add', "id={$id}", 0);
            XHandle::halt('编辑失败', '', 1);
        }
        
    }
    
    
    public function action_del() {
        $this->checkAuth('myads_del');
        $array_id = XRequest::getArray('id');
        $this->_validID($array_id);
        $model = parent::model('myads', 'am');
        for($ii=0; $ii<count($array_id); $ii++){
            $id = intval($array_id[$ii]);
            if (true === $model->doCheckSystemAds($id)){
                XHandle::halt('对不起，不能删除系统默认的广告。', '', 1);
            }
            $result = $model->doDel($id);            
        }
        unset($model);
        
        if (true === $result) {
            $this->log('myads_del', "id={$array_id}", 1);
            XHandle::halt('删除成功', $this->cpfile.'?c=myads', 0);
        }
        else {
            $this->log('myads_del', "id={$array_id}", 0);
            XHandle::halt('删除失败', '', 1);
        }
         
    }
    
    
    public function action_update() {
        $this->checkAuth('myads_edit');
        $args = XRequest::getGpc(array('id', 'type'));
        $model = parent::model('myads', 'am');
        $model->doUpdate(intval($args['id']), trim($args['type']));
        unset($model);
    }
    
    
    private function _validAdd() {
        $args = XRequest::getGpc(array(
            'zoneid', 'adname', 'tagname', 'url', 'target', 
            'orders', 'timeset', 'starttime', 'endtime', 'flag'
        ));
        $normbody = XRequest::getGpc(array('type', 'uploadfiles', 'title', 'width', 'height'));
        
        if (empty($args['adname'])) {
            XHandle::halt('广告名称不能为空', '', 1);
        }
        if (empty($args['tagname'])) {
            XHandle::halt('广告标识不能为空', '', 1);
        }
        else {
            if (false === XValid::isSpChar($args['tagname'])) {
                XHandle::halt('广告标识格式不正确', '', 1);
            }
        }
        if (empty($normbody['uploadfiles'])) {
            XHandle::halt('图片/Flash源不能为空', '', 1);
        }       
        $args['zoneid'] = intval($args['zoneid']);
        $args['orders'] = intval($args['orders']);
        $args['timeset'] = intval($args['timeset']);
        $args['starttime'] = intval($args['starttime']);
        $args['endtime'] = intval($args['endtime']);
        $args['flag'] = intval($args['flag']);
        $args['target'] = intval($args['target']);
        if (empty($args['url'])) {
            $args['url'] = '#';
        }
        
        $normbody['width'] = intval($normbody['width']);
        $normbody['height'] = intval($normbody['height']);
        
        
        $body = serialize($normbody);
        $args = array_merge($args, array('normbody'=>$body));
        return $args;
    }
    
    
    private function _validEdit() {
        $id = XRequest::getInt('id');
        $args = XRequest::getGpc(array(
            'zoneid', 'adname', 'url', 'target', 'orders',
            'timeset', 'starttime', 'endtime', 'flag'
        ));
        
        $normbody = XRequest::getGpc(array('type', 'uploadfiles', 'title', 'width', 'height'));
        
        if ($id<1) {
            XHandle::halt('ID丢失', '', 1);
        }
        if (empty($args['adname'])) {
            XHandle::halt('广告名称不能为空', '', 1);
        }
        if (empty($normbody['uploadfiles'])) {
            XHandle::halt('图片/Flash源不能为空', '', 1);
        }       
        $args['zoneid'] = intval($args['zoneid']);
        $args['orders'] = intval($args['orders']);
        $args['timeset'] = intval($args['timeset']);
        $args['starttime'] = intval($args['starttime']);
        $args['endtime'] = intval($args['endtime']);
        $args['flag'] = intval($args['flag']);
        $args['target'] = intval($args['target']);
        if (empty($args['url'])) {
            $args['url'] = '#';
        }
        
        $normbody['width'] = intval($normbody['width']);
        $normbody['height'] = intval($normbody['height']);
        
        
        $body = serialize($normbody);
        $args = array_merge($args, array('normbody'=>$body));
        return array($id, $args);
    }
    
    
    private function _validID($id) {
        if (empty($id)) {
            XHandle::halt('ID丢失，请选择要操作的ID', '', 1);
        }
    }
}
?>
