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
    
    
    private $smodule = NULL;
    
    private $streeid = NULL;
    
    private function _getItems() {
        $this->smodule = XRequest::getArgs('smodule');
        if (false === XValid::isSpChar($this->smodule)) {
            XHandle::halt("对不起，模型丢失。", '', 1);
        }
        $this->streeid = XRequest::getInt('streeid');
        $this->_urlitem = "smodule=".$this->smodule."&streeid=".$this->streeid."";
        $this->_comeurl = $this->_urlitem."&page=".$this->page."";
    }

	
    public function action_run() {
        $this->checkAuth('modattr_volist');
        $this->_getItems();
        $searchsql = "";
        $searchsql .= " AND v.modalias='".$this->smodule."'";      
        if ($this->streeid>0) {
            $searchsql .= " AND v.treeid='".$this->streeid."'";
        }
        
        $model = parent::model('modattr', 'am');
        list($total, $data) = $model->getList(array('page'=>$this->page, 'pagesize'=>$this->pagesize, 'searchsql'=>$searchsql));
        $url = XRequest::getPhpSelf();
        $url .= '?c=modattr&'.$this->_urlitem;
        
        parent::loadLib('mod');
        $root_select = XMod::selectCategoryRoot($this->smodule, $this->streeid, 'streeid', '所有栏目');
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
            'streeid'=>$this->streeid,
            'smodule'=>$this->smodule,
            'modattr'=>$data,
            'root_select'=>$root_select,
        );
        unset($model);
		TPL::assign($var_array);
		TPL::display($this->cptpl.'modattr.tpl');
	}
    
    
    public function action_add() {
        $this->checkAuth('modattr_add');
        $this->_getItems();
        
        parent::loadLib('mod');
        $var_array = array(
            'root_select'=>XMod::selectCategoryRoot($this->smodule, $this->streeid, 'treeid', '所有栏目'),
            'smodule'=>$this->smodule,
            'streeid'=>$this->streeid,
            'urlitem'=>$this->_urlitem,
            'comeurl'=>$this->_comeurl,
        );
        TPL::assign($var_array);
        TPL::display($this->cptpl.'modattr.tpl'); 
    }
    
    
    public function action_edit() {
        $this->checkAuth('modattr_edit');
        $id = XRequest::getInt('id');
        $this->_validID($id);
        $this->_getItems();
        $model = parent::model('modattr', 'am');
        $data = $model->getData($id);
        unset($model);
        if (empty($data)) {
            XHandle::halt('对不起，读取数据不存在！', '', 1);
        }
        else {
            
            parent::loadLib('mod');
            $var_array = array(
                'modattr'=>$data,
                'id'=>$id,
                'page'=>$this->page,
                'smodule'=>$this->smodule,
                'streeid'=>$this->streeid,
                'comeurl'=>$this->_comeurl,
                'root_select'=>XMod::selectCategoryRoot($data['modalias'], $data['treeid'], 'treeid', '所有栏目'),
            );
            TPL::assign($var_array);
            TPL::display($this->cptpl.'modattr.tpl');
        }
    }
    
    
    public function action_saveadd() {
        $this->checkAuth('modattr_add');
        $this->_getItems();
        $args = $this->_validAdd();
        $args = array_merge($args, array('modalias'=>$this->smodule));
        $model = parent::model('modattr', 'am');
        if (true === $model->doCheckAttrName($this->smodule, $args['attrname'])) {
            XHandle::halt('对不起，该字段已经存在，请填写另外一个。', '', 1);
        }
        else {
            $result = $model->doAdd($args);
            if (true === $result) {
                $this->log('modattr_add', '', 1);
                XHandle::halt('添加成功', $this->cpfile.'?c=modattr&smodule='.$this->smodule.'&streeid='.$this->streeid.'', 0);
            }
            else {
                $this->log('modattr_add', '', 0);
                XHandle::halt('添加失败', '', 1);
            }
        }
        unset($model);	
	}
    
    
    public function action_saveedit() {
        $this->checkAuth('modattr_edit');
        $this->_getItems();
        list($id, $args) = $this->_validEdit();
        $model = parent::model('modattr', 'am');
        $result = $model->doEdit($id, $args);
        unset($model);
        if (true === $result) {
            $this->log('modattr_edit', "id={$id}", 1);
            XHandle::halt('编辑成功', $this->cpfile.'?c=modattr&'.$this->_comeurl.'', 0);
        }
        else {
            $this->log('modattr_edit', "id={$id}", 0);
            XHandle::halt('编辑失败', '', 1);
        }
    }
    
    
    public function action_del() {
        $this->checkAuth('modattr_del');
        $array_id = XRequest::getArray('id');
        $this->_validID($array_id);
        $this->_getItems();
        $model = parent::model('modattr', 'am');
        for($ii=0; $ii<count($array_id); $ii++){
            $id = intval($array_id[$ii]);
            
            if (true === $model->doCheckSystemAttr($id)){
                XHandle::halt('对不起，不能删除系统默认的模型字段。', '', 1);
            }
            
            $result = $model->doDel($id);            
        }
        unset($model);
        
        if (true === $result) {
            $this->log('modattr_del', "id={$array_id}", 1);
            XHandle::halt('删除成功', $this->cpfile.'?c=modattr&smodule='.$this->smodule.'&streeid='.$this->streeid.'', 0);
        }
        else {
            $this->log('modattr_del', "id={$array_id}", 0);
            XHandle::halt('删除失败', '', 1);
        }
         
    }
    
    
    public function action_update() {
        $this->checkAuth('modattr_edit');
        $args = XRequest::getGpc(array('id', 'type'));
        $model = parent::model('modattr', 'am');
        $model->doUpdate(intval($args['id']), trim($args['type']));
        unset($model);
    }
    
    
    private function _validAdd() {
        $args = XRequest::getGpc(array(
            'treeid', 'typename', 'typeremark', 'attrname', 'inputtype',
            'attrvalue', 'attrwidth', 'attrheight', 'isvalid', 'validtext',
            'orders', 'flag'
        ));       
        if (empty($args['typename'])) {
            XHandle::halt('简述文字不能为为空', '', 1);
        }
        if (empty($args['attrname'])) {
            XHandle::halt('字段名称不能为空', '', 1);
        }
        else {
            if (false === XValid::isModuleAttr($args['attrname'])) {
                XHandle::halt('字段名称格式不正确', '', 1);
            }
            else {
                if (strlen($args['attrname'])<2 || strlen($args['attrname'])>40) {
                    XHandle::halt('字段长度不正确', '', 1);
                }
            }
        }
        if (empty($args['inputtype'])) {
            XHandle::halt('字段类型不能为空', '', 1);
        }
        
        




        
        $args['treeid'] = intval($args['treeid']);
        $args['attrwidth'] = intval($args['attrwidth']);
        $args['attrheight'] = intval($args['attrheight']);
        $args['isvalid'] = intval($args['isvalid']);
        $args['orders'] = intval($args['orders']);
        $args['flag'] = intval($args['flag']);
        return $args;
    }
    
    
    private function _validEdit() {
        $id = XRequest::getInt('id');
        $args = XRequest::getGpc(array(
            'treeid', 'typename', 'typeremark','attrvalue', 
            'attrwidth', 'attrheight', 'isvalid', 'validtext',
            'orders', 'flag'
        )); 
        if ($id<1) {
            XHandle::halt('ID丢失', '', 1);
        }      
        if (empty($args['typename'])) {
            XHandle::halt('简述文字不能为为空', '', 1);
        }        
        $args['treeid'] = intval($args['treeid']);
        $args['attrwidth'] = intval($args['attrwidth']);
        $args['attrheight'] = intval($args['attrheight']);
        $args['isvalid'] = intval($args['isvalid']);
        $args['orders'] = intval($args['orders']);
        $args['flag'] = intval($args['flag']);
        
        return array($id, $args);
    }
    
    
    private function _validID($id) {
        if (empty($id)) {
            XHandle::halt('ID丢失，请选择要操作的ID', '', 1);
        }
    }
}
?>
