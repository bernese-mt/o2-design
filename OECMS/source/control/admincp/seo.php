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

	
    public function action_run() {
        $model = parent::model('seo', 'am');
        $data = $model->getAllList();
        $total = count($data);
        unset($model);
        $var_array = array(
            'total'=>$total,
            'seo'=>$data,
        );
        unset($model);
		TPL::assign($var_array);
		TPL::display($this->cptpl.'seo.tpl');
	}
        
    
    public function action_edit() {
        $id = $this->_validID();
        $model = parent::model('seo', 'am');
        $data = $model->getData($id);
        unset($model);
        if (empty($data)) {
            XHandle::halt('对不起，读取数据不存在！', '', 1);
        }
        else {
            $var_array = array(
                'seo'=>$data,
                'id'=>$id,
            );
            TPL::assign($var_array);
            TPL::display($this->cptpl.'seo.tpl');
        }
    }

    
    public function action_saveedit() {
        list($id, $args) = $this->_validEdit();              
        $model = parent::model('seo', 'am');
        $result = $model->doEdit($id, $args);
        unset($model);
        if (true === $result) {
            XHandle::halt('编辑成功', $this->cpfile.'?c=seo', 0);
        }
        else {
            XHandle::halt('编辑失败', '', 1);
        } 
    }
    
    
    public function action_saveupdate() {
        $ids = $this->_validArrayID();
        $model = parent::model('seo', 'am');
        for($ii=0; $ii<count($ids); $ii++){
            $id = intval($ids[$ii]);
            $args = null;
            $args = $this->_validUpdate($id);
            $result = $model->doUpdate($id, $args);           
        }
        if (true === $result) {
            $model->createCache();
        }
        unset($model);
        if (true === $result) {
            XHandle::halt('编辑成功', $this->cpfile.'?c=seo', 0);
        }        
        else {
            XHandle::halt('编辑失败', '', 1);
        }
    }
    
    
    private function _validAdd() {
        $args = XRequest::getGpc(array(
            'idmark', 'chname', 'title', 'description', 'keyword', 
        ));   
        if (false === XValid::isSpChar($args['idmark'])) {
            XHandle::halt('标识格式不正确', '', 1);
        }
        return $args;        
    }
    
    
    private function _validEdit() {
        $id = $this->validID();
        $args = XRequest::getGpc(array(
            'chname', 'title', 'description', 'keyword',
        ));
        return array($id, $args); 
    }
    
    
    private function _validID() {
        $id = XRequest::getInt('id');
        if ($id < 1) {
            XHandle::halt('对不起，ID丢失。', '', 1);
        }
        else {
            return $id;
        }
    }
    
    
    private function _validArrayID() {     
        $ids = XRequest::getArray('id');
        if (empty($ids)) {
            XHandle::halt("对不起，ID错误！", '', 1);
        }
        return $ids;
    }
    
    
    private function _validUpdate($id) { 
        $chname = XRequest::getArgs('chname_'.$id);
        $title = XRequest::getArgs('title_'.$id);
        $description = XRequest::getArgs('description_'.$id);
        $keyword = XRequest::getArgs('keyword_'.$id);
        $args = array(
            'chname'=>$chname,
            'title'=>$title,
            'description'=>$description,
            'keyword'=>$keyword,
        );        
        return $args;
    }
}
?>
