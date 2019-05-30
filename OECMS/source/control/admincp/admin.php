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
    private $sgroupid = NULL;
    private $susername = NULL; 

    private function getItems() {
        
    }
    private function _getItems() {
        $this->sgroupid = XRequest::getInt('sgroupid');
        $this->susername = XRequest::recArgs('susername');
        $this->_urlitem = "sgroupid=".$this->sgroupid."&susername=".urlencode($this->susername)."";
        $this->_comeurl = $this->_urlitem."&page=".$this->page."";
    }

	
    public function action_run() {
        $this->checkAuth('admin_volist');
        $this->_getItems();
        $searchsql = "";
        if ($this->sgroupid>0) {
            $searchsql .= " AND v.groupid='".$this->sgroupid."'";
        }
        if (!empty($this->susername)) {
            $searchsql .= " AND lower(v.adminname) LIKE '%".strtolower($this->susername)."%'";
        }
        $model = parent::model('admin', 'am');
        list($datacount, $data) = $model->getList(array('page'=>$this->page, 'pagesize'=>$this->pagesize, 'searchsql'=>$searchsql));
        $url = XRequest::getPhpSelf();
        $url .= '?c=admin&'.$this->_urlitem;
        $var_array = array(
            'page'=>$this->page,
            'nextpage'=>$this->page+1,
            'prepage'=>$this->page-1,
            'pagecount'=>ceil($datacount/$this->pagesize),
            'pagesize'=>$this->pagesize,
            'total'=>$datacount,
            'showpage'=>XPage::admin($datacount, $this->pagesize, $this->page, $url, 10),
            'itemurl'=>$this->_urlitem,
            'comeurl'=>$this->_comeurl,
            'sgroupid'=>$this->sgroupid,
            'susername'=>$this->susername,
            'admin'=>$data,
        );
        unset($model);
		TPL::assign($var_array);
		TPL::display($this->cptpl.'admin.tpl');
	}
    
    
    public function action_add() {
        $this->checkAuth('admin_add');
        TPL::display($this->cptpl.'admin.tpl'); 
    }
    
    
    public function action_edit() {
        $this->checkAuth('admin_edit');
        $id = XRequest::getInt('id');
        $this->_validID($id);
        $this->_getItems();
        $model = parent::model('admin', 'am');
        $data = $model->getData($id);
        if (empty($data)) {
            XHandle::halt('对不起，读取数据不存在！', '', 1);
        }
        else {
            parent::loadLib('mod');
            $var_array = array(
                'admin'=>$data,
                'id'=>$id,
                'comeurl'=>$this->_comeurl,
            );
            TPL::assign($var_array);
            TPL::display($this->cptpl.'admin.tpl');
        }
        unset($model);
    }
    
     
    public function action_editpassword() {
        $this->checkAuth('admin_editpassword');
        $var_array = array(
            'uc_adminname'=>parent::$wrap_admin['adminname'],
        );
        TPL::assign($var_array);
        TPL::display($this->cptpl.'admin.tpl'); 
    }
    
    
    public function action_saveadd() {
        $this->checkAuth('admin_add');
		$args = XRequest::getGpc(
            array('adminname', 'password', 'confirmpassword', 
                'groupid', 'super', 'flag', 'memo')
        );
        $args = $this->_validAdd($args);
        $model = parent::model('admin', 'am');
        if (true === $model->doCheckUserName($args['adminname'])) {
            XHandle::halt('对不起，该帐号已存在，请填写另外一个。', '', 1);
        }
        else {
            $result = $model->doAdd($args);
            if (true === $result) {
                $this->log('admin_add', '', 1);
                XHandle::halt('添加成功', $this->cpfile.'?c=admin', 0);
            }
            else {
                $this->log('admin_add', '', 0);
                XHandle::halt('添加失败', '', 1);
            }
        }
        unset($model);	
	}
    
    
    public function action_saveedit() {
        $this->checkAuth('admin_edit');
        $id = XRequest::getInt('id');
        $args = XRequest::getGpc(
            array(
                'password', 'confirmpassword', 'groupid', 'super', 'flag', 'memo'
            )
        );
        $args = $this->_validEdit($id, $args);
        $this->_getItems();
        $model = parent::model('admin', 'am');
        $result = $model->doEdit($id, $args);
        if (true === $result) {
            $this->log('admin_edit', "id={$id}", 1);
            XHandle::halt('编辑成功', $this->cpfile.'?c=admin&'.$this->_comeurl.'', 0);
        }
        else {
            $this->log('admin_edit', "id={$id}", 0);
            XHandle::halt('编辑失败', '', 1);
        }
        unset($model);
    }
    
    
    public function action_del() {
        $this->checkAuth('admin_del');
        $array_id = XRequest::getArray('id');
        $this->_validID($array_id);
        $model = parent::model('admin', 'am');
        for($ii=0; $ii<count($array_id); $ii++){
            $id = intval($array_id[$ii]);
            if (true === $model->doCheckUid($id)){
                XHandle::halt('对不起，您不能删除自己的帐号！', '', 1);
            }
            $result = $model->doDel($id);            
        }
        
        if (true === $result) {
            $this->log('admin_del', "id={$array_id}", 1);
            XHandle::halt('删除成功', $this->cpfile.'?c=admin', 0);
        }
        else {
            $this->log('admin_del', "id={$array_id}", 0);
            XHandle::halt('删除失败', '', 1);
        }
        unset($model); 
    }
    
    
    public function action_savepassword() {
        $this->checkAuth('admin_editpassword');
        $args = array(
            'oldpassword'=>XRequest::recArgs('oldpassword'),
            'password'=>XRequest::recArgs('password'),
            'confirmpassword'=>XRequest::recArgs('confirmpassword'),
        );
        $args = $this->_validPassword($args);
        $model = parent::model('admin', 'am');
        if (true === $model->doEditPass($args['password'])){
            $this->log('admin_editpassword', '', 1);
            XHandle::halt('恭喜你，修改密码成功，请记住新密码。', $this->cpfile.'?c=admin&a=editpassword', 0);
        } 
        else {
            $this->log('admin_editpassword', '', 0);
            XHandle::halt('对不起，修改密码失败！', '', 1);
        } 
        unset($model);            
    }
    
    
    public function action_update() {
        $this->checkAuth('admin_edit');
        $args = XRequest::getGpc(array('id', 'type'));
        $model = parent::model('admin', 'am');
        $model->doUpdate(intval($args['id']), trim($args['type']));
        unset($model);
    }
    
    
    private function _validAdd($array) {
        if (!XValid::isUserArgs($array['adminname'])) {
            XHandle::halt('帐号格式不正确，只能由字母、数字、下横线、中文组成', '', 1);
        }
        if (!XValid::isLength($array['adminname'], 4, 16)){
            XHandle::halt('帐号长度不正确，必须为4-16个字符', '', 1);
        }
        if (!XValid::isLength($array['password'], 4, 16)){
            XHandle::halt('密码长度不正确，必须为4-16个字符', '', 1);
        }
        if ($array['confirmpassword'] != $array['password']){
            XHandle::halt('确认密码不正确', '', 1);
        }
        $array['groupid'] = intval($array['groupid']);
        $array['super'] = intval($array['super']); 
        $array['flag'] = intval($array['flag']);
        unset($array['confirmpassword']);
        return $array;
    }
    
    
    private function _validEdit($id, $array) {
        if ($id<1) {
            XHandle::halt('ID丢失', '', 1);
        }
        if (!empty($array['password'])) {
            if (!XValid::isLength($array['password'], 4, 16)){
                XHandle::halt('密码长度不正确，必须为4-16个字符', '', 1);
            }
            if ($array['confirmpassword'] != $array['password']){
                XHandle::halt('确认密码不正确', '', 1);
            }
            
            unset($array['confirmpassword']);
        }
        else {
            unset($array['password']);
            unset($array['confirmpassword']);
        }
        $array['groupid'] = intval($array['groupid']);
        $array['super'] = intval($array['super']); 
        $array['flag'] = intval($array['flag']); 
        return $array;
    }
    
    
    private function _validID($id) {
        if (empty($id)) {
            XHandle::halt('ID丢失，请选择要操作的ID', '', 1);
        }
    }
    
    
    private function _validPassword($array) {
        if (empty($array['oldpassword'])) {
            XHandle::halt('请输入原密码', '', 1);
        }
        else {
            $model = parent::model('admin', 'am');
            if (false === $model->doCheckPassword($array['oldpassword'])) {
                XHandle::halt('对不起，原密码不正确！', '', 1);
            }
            unset($model);
        }
        if (!XValid::isLength($array['password'], 4, 16)){
            XHandle::halt('新密码长度不正确，必须为4-16个字符', '', 1);
        }
        if ($array['confirmpassword'] != $array['password']){
            XHandle::halt('确认密码不正确', '', 1);
        }    
        return $array;
        $this->_getItems();
    }
}
?>
