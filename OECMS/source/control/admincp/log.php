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
        $this->checkAuth('log_volist');
        $searchsql = "";
        $model = parent::model('log', 'am');
        list($total, $data) = $model->getList(array('page'=>$this->page, 'pagesize'=>$this->pagesize, 'searchsql'=>$searchsql));
        unset($model);
        $url = XRequest::getPhpSelf();
        $url .= '?c=log';
        $var_array = array(
            'page'=>$this->page,
            'nextpage'=>$this->page+1,
            'prepage'=>$this->page-1,
            'pagecount'=>ceil($total/$this->pagesize),
            'pagesize'=>$this->pagesize,
            'total'=>$total,
            'showpage'=>XPage::admin($total, $this->pagesize, $this->page, $url, 10),
            'log'=>$data,
        );
		TPL::assign($var_array);
		TPL::display($this->cptpl.'log.tpl');
	}
    
    
    public function action_del() {
        $this->checkAuth('log_del');
        $array_id = XRequest::getArray('id');
        $this->_validID($array_id);
        $model = parent::model('log', 'am');
        for($ii=0; $ii<count($array_id); $ii++){
            $id = intval($array_id[$ii]);
            $result = $model->doDel($id);            
        }
        unset($model);
        
        if (true === $result) {
            $this->log('log_del', "id={$array_id}", 1);
            XHandle::halt('删除成功', $this->cpfile.'?c=log', 0);
        }
        else {
            $this->log('log_del', "id={$array_id}", 0);
            XHandle::halt('删除失败', '', 1);
        }
    }
    
    
    public function action_delall() {
        $this->checkAuth('log_del');
        $model = parent::model('log', 'am');
        $result = $model->doDelAll();
        unset($model);
        if (true === $result) {
            $this->log('log_del', "id=All", 1);
            XHandle::halt('删除成功', $this->cpfile.'?c=log', 0);
        }
        else {
            $this->log('log_del', "id=All", 0);
            XHandle::halt('删除失败', '', 1);
        }
    }
    
    
    
    private function _validID($id) {
        if (empty($id)) {
            XHandle::halt('ID丢失，请选择要操作的ID', '', 1);
        }
    }
}
?>
