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
        $this->checkAuth('module_volist');
        $model = parent::model('module', 'am');
        list($total, $data) = $model->getList();
        $url = XRequest::getPhpSelf();
        $url .= '?c=module';
        $var_array = array(
            'total'=>$total,
            'module'=>$data,
        );
        unset($model);
		TPL::assign($var_array);
		TPL::display($this->cptpl.'module.tpl');
	}
    
    
    public function action_saveupdate() {
        $this->checkAuth('module_edit');
        $array_id = XRequest::getArray('id');
        $this->_validID($array_id);
        $array = array();
        $model = parent::model('module', 'am');
        for($ii=0; $ii<count($array_id); $ii++){
            
            $id = intval($array_id[$ii]);
            $array = NULL;
            $array = $this->_validEdit($id);
            $result = $model->doEdit($id, $array);
        }
        unset($model);
        if (true === $result) {
            $this->log('module_edit', "id={$array_id}", 1);
            XHandle::halt('更新成功', $this->cpfile.'?c=module', 0);
        }
        else {
            $this->log('module_edit', "id={$array_id}", 0);
            XHandle::halt('更新失败', '', 1);
        }
        
    }
    
    
    public function action_update() {
        $this->checkAuth('module_edit');
        $args = XRequest::getGpc(array('id', 'type'));
        $model = parent::model('module', 'am');
        $model->doUpdate(intval($args['id']), trim($args['type']));
        unset($model);
    }
    
    
    private function _validEdit($id){
        $modname = XRequest::getArgs('modname_'.$id);
        $color = XRequest::getArgs('color_'.$id);
        $tplindex = XRequest::getArgs('tplindex_'.$id);
        $tpllist = XRequest::getArgs('tpllist_'.$id);
        $tpldetail = XRequest::getArgs('tpldetail_'.$id);
        $enabled = XRequest::getInt('enabled_'.$id);
        if (empty($modname)) {
            XHandle::halt('模块类型名称不能为空，所在ID={$id}', '', 1);
        }
        return array(
            'modname'=>$modname,
            'color'=>$color,
            'tplindex'=>$tplindex,
            'tpllist'=>$tpllist,
            'tpldetail'=>$tpldetail,
            'enabled'=>$enabled
        );
    }
    
    
    private function _validID($id) {
        if (empty($id)) {
            XHandle::halt('ID丢失，请选择要操作的ID', '', 1);
        }
    }
}
?>
