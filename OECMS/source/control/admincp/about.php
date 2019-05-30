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
    
    
    private $_catid = NULL;
    
    private $_treeid = NULL;
    
    private function _getItems() {
        $this->_catid = XRequest::getInt('catid');
    }

	
    public function action_run() {
        $this->checkAuth('about_volist');
        $this->_getItems();
        $searchsql = "";     
        if ($this->_catid>0) {
            $model = parent::model('category', 'am');
            $this->_treeid = $model->getTreeID($this->_catid);
            if ($this->_treeid>0) {
                $searchsql .= " AND v.treeid='".$this->_treeid."'";
            }
            unset($model);
        }
        
        $model = parent::model('about', 'am');
        list($total, $data) = $model->getList(array('page'=>$this->page, 'pagesize'=>$this->pagesize, 'searchsql'=>$searchsql));
        $url = XRequest::getPhpSelf();
        $url .= '?c=abount';
        $var_array = array(
            'page'=>$this->page,
            'nextpage'=>$this->page+1,
            'prepage'=>$this->page-1,
            'pagecount'=>ceil($total/$this->pagesize),
            'pagesize'=>$this->pagesize,
            'total'=>$total,
            'showpage'=>XPage::admin($total, $this->pagesize, $this->page, $url, 10),
            'catid'=>$this->_catid,
            'about'=>$data,
        );
        unset($model);
		TPL::assign($var_array);
		TPL::display($this->cptpl.'about.tpl');
	}
    
    
    public function action_edit() {
        $this->checkAuth('about_edit');
        $id = XRequest::getInt('id');
        $this->_validID($id);
        $this->_getItems();
        $model = parent::model('about', 'am');
        $data = $model->getData($id);
        unset($model);
        if (empty($data)) {
            XHandle::halt('对不起，读取数据不存在！', '', 1);
        }
        else {
            $var_array = array(
                'about'=>$data,
                'id'=>$id,
                'page'=>$this->page,
                'catid'=>$this->_catid,
            );
            TPL::assign($var_array);
            TPL::display($this->cptpl.'about.tpl');
        }
    }
    
    
    public function action_saveedit() {
        $this->checkAuth('about_edit');
        $this->_getItems();
        list($id, $args) = $this->_validEdit();
        $model = parent::model('about', 'am');
        $result = $model->doEdit($id, $args);
        unset($model);
        if (true === $result) {
            $this->log('about_edit', "id={$id}", 1);
            XHandle::halt('编辑成功', $this->cpfile.'?c=about&a=edit&catid='.$this->_catid.'&id='.$id.'', 0);
        }
        else {
            $this->log('about_edit', "id={$id}", 0);
            XHandle::halt('编辑失败', '', 1);
        }
    }
    
    
    private function _validEdit() {
        $id = XRequest::getInt('id');
        $args = XRequest::getGpc(array(
            'title', 'intro', 'thumbfiles', 'uploadfiles', 'tags',
            'metakeyword', 'metadescription', 'tplname', 
        ));
        $content = XRequest::getArgs('content', '', false);
        if ($id<1) {
            XHandle::halt('ID丢失', '', 1);
        }      
        if (empty($args['title'])) {
            XHandle::halt('标题不能为空', '', 1);
        }
        if (!empty($args['tplname'])){
            if (false === XValid::isSpChar($args['tplname'])) {
                XHandle::halt('模板文件格式不正确', '', 1);
            }
        }
        $args = array_merge($args, array('content'=>$content));     
        return array($id, $args);
    }
    
    
    private function _validID($id) {
        if (empty($id)) {
            XHandle::halt('ID丢失，请选择要修改的单页，如果没有，请在栏目设置里重新编辑生成单页。', '', 1);
        }
    }
}
?>
