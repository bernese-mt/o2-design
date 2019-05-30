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
    private $id = NULL;

    private function _getItems() {
        $this->id = XRequest::getArgs('id');
    }

	
    public function action_run() {
        $this->checkAuth('skin_volist');
        $model = parent::model('skin', 'am');
        
        list($count, $data) = $model->getList();
        $i = 1;
        foreach($data as $key=>$value) {
            $data[$key]['i'] = $i;
            $i = $i+1;
        }       
        
        $usingskin= $model->getCopyRight(); 
        $var_array = array(
            'total'=>$count,
            'skin'=>$data,
            'usingskin'=>$usingskin,
        );
        unset($model); 
        TPL::assign($var_array);
		TPL::display($this->cptpl.'skin.tpl');
	}
    
    
    public function action_usetpl() {
        $this->checkAuth('skin_edit');
        $this->_getItems();
        $this->_validID();
        $model = parent::model('skin', 'am');
        $result = $model->doUseTemplet($this->id);
        unset($model);
        if (true === $result) {
            $this->log('skin_edit', "id={$this->id}", 1);
            XHandle::halt('主题模板设置成功', $this->cpfile.'?c=skin', 0);
        }   
        else {
            $this->log('skin_edit', "id={$this->id}", 0);
            XHandle::halt('对不起，主题模板设置失败', '', 1);
        }
    }
    
    
    public function action_del() {
        $this->checkAuth('skin_del');
        $this->_getItems();
        $this->_canDelID();
        $model = parent::model('skin', 'am');
        $result = $model->doDelTemplet($this->id);
        unset($model);        
        if (true === $result) {
            $this->log('skin_del', "id={$this->id}", 1);
            XHandle::halt('主题模板删除成功', $this->cpfile.'?c=skin', 0);
        }
        else {
            $this->log('skin_del', "id={$this->id}", 0);
            XHandle::halt('对不起，主题模板删除失败！', '', 1);
        }
    }
    
    
    private function _validID() {
        if (false === XValid::mathDir($this->id)) {
            XHandle::halt('对不起，模板目录错误或者不存在！', '', 1);
        }
    }
    
    
    private function _canDelID() {
        $this->_validID();
        parent::loadLib('option');
        if (trim($this->id) == trim(XOption::get('nonce_templet'))){
            XHandle::halt('对不起，该主题模板正在使用，不能删除！', '', 1);
        }
    }
    
}
?>
