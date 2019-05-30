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
    
    private $stitle = NULL;

    private function _getItems() {
        $this->stitle = XRequest::getGpc('stitle');
        $this->_urlitem = "stitle=".urlencode($this->stitle)."";
        $this->_comeurl = $this->_urlitem."&page=".$this->page."";
    }

	
    public function action_run() {
        $this->checkAuth('guestbook_volist');
        $this->_getItems();
        
        $searchsql = "";
        if (!empty($this->stitle)) {
            $searchsql .= " AND lower(v.title) LIKE '%".strtolower($this->stitle)."%'";
        }
        $model = parent::model('guestbook', 'am');
        list($total, $data) = $model->getList(array('page'=>$this->page, 'pagesize'=>$this->pagesize, 'searchsql'=>$searchsql));
        
        $url = XRequest::getPhpSelf();
        $url .= '?c=guestbook&'.$this->_urlitem;
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
            'stitle'=>$this->stitle,
            'guestbook'=>$data,
        );
        unset($model);
		TPL::assign($var_array);
		TPL::display($this->cptpl.'guestbook.tpl');
	}
    
    
    public function action_add() {
        $this->checkAuth('guestbook_add');
        $this->_getItems();
           
        $var_array = array(
            'timeline'=>time(),
        );
        TPL::assign($var_array);
        TPL::display($this->cptpl.'guestbook.tpl');
    }
    
    
    public function action_edit() {
        $this->checkAuth('guestbook_edit');
        $this->_getItems();
        
        $id = XRequest::getInt('id');
        $this->_validID($id);
        $model = parent::model('guestbook', 'am');
        list($data) = $model->getData($id);
        if (empty($data)) {
            XHandle::halt('对不起，读取数据不存在！', '', 1);
        }
        else {

            $var_array = array(
                'guestbook'=>$data,
                'id'=>$id,
                'page'=>$this->page,
                'comeurl'=>$this->_comeurl,
                'timeline'=>time(),
            );
            TPL::assign($var_array);
            TPL::display($this->cptpl.'guestbook.tpl');
        }
        unset($model);
    }
    
    
    public function action_saveadd() {
        $this->checkAuth('guestbook_add');
        $args = $this->_validAdd();
        $model = parent::model('guestbook', 'am');
        $result = $model->doAdd($args);
        unset($model);
        if (true === $result) {
            $this->log('guestbook_add', '', 1);
            XHandle::halt('添加成功', $this->cpfile.'?c=guestbook', 0);
        }
        else {
            $this->log('guestbook_add', '', 0);
            XHandle::halt('添加失败', '', 1);
        }
        
	}
    
    
    public function action_saveedit() {
        $this->checkAuth('guestbook_edit');
        $this->_getItems();
        
        list($id, $args) = $this->_validEdit();
        $model = parent::model('guestbook', 'am');
        $result = $model->doEdit($id, $args);
		unset($model);
        if (true === $result) {
            $this->log('guestbook_edit', "id={$id}", 1);
			XHandle::halt('编辑成功', $this->cpfile.'?c=guestbook&'.$this->_comeurl.'', 0);
		}
        else {
            $this->log('guestbook_edit', "id={$id}", 0);
			XHandle::halt('编辑失败', '', 1);
		}
        
    }
    
    
    public function action_del() {
        $this->checkAuth('guestbook_del');
        $this->_getItems();
        
        $array_id = XRequest::getArray('id');
        $this->_validID($array_id);
        $model = parent::model('guestbook', 'am');
        for($ii=0; $ii<count($array_id); $ii++){
            $id = intval($array_id[$ii]);
            $result = $model->doDel($id);            
        }
        unset($model);
        
        if (true === $result) {
            $this->log('guestbook_del', "id={$array_id}", 1);
            XHandle::halt('删除成功', $this->cpfile.'?c=guestbook', 0);
        }
        else {
            $this->log('guestbook_del', "id={$array_id}", 0);
            XHandle::halt('删除失败', '', 1);
        }
         
    }
    
    
    public function action_update() {
        $this->checkAuth('guestbook_edit');
        $args = XRequest::getGpc(array('id', 'type'));
        $model = parent::model('guestbook', 'am');
        $model->doUpdate(intval($args['id']), trim($args['type']));
        unset($model);
    }
    
    
    private function _validAdd() {
        $args = XRequest::getGpc(array(
            'title', 'username', 'email', 'content',
            'flag', 'qq', 'msn', 'address', 'telephone', 'mobile',
			'addtime',
        ));
        if (empty($args['title'])) {
            XHandle::halt('请填写标题', '', 1);
        }
        if (empty($args['username'])) {
            XHandle::halt('请填写姓名', '', 1);
        }
        if (empty($args['email'])) {
            XHandle::halt('请填写邮箱', '', 1);
        }
		else {
			if (false === XValid::isEmail($args['email'])) {
				XHandle::halt('邮箱格式不正确', '', 1);
			}
		}
        if (empty($args['content'])) {
            XHandle::halt('请填写留言内容', '', 1);
        }
        $args['flag'] = intval($args['flag']);
		$args['addtime'] = strtotime(trim(str_replace('&nbsp;', ' ', $args['addtime'])));
        return $args;
    }
    
    
    private function _validEdit() {
        $id = XRequest::getInt('id');
        $args = XRequest::getGpc(array(
            'title', 'username', 'email', 'content', 'flag',
			'addtime', 'replycontent', 'replyuser',
        ));
        if ($id<1) {
            XHandle::halt('ID丢失', '', 1);
        }
        if (empty($args['title'])) {
            XHandle::halt('请填写标题', '', 1);
        }
        if (empty($args['username'])) {
            XHandle::halt('请填写姓名', '', 1);
        }
        if (empty($args['email'])) {
            XHandle::halt('请填写邮箱', '', 1);
        }
		else {
			if (false === XValid::isEmail($args['email'])) {
				XHandle::halt('邮箱格式不正确', '', 1);
			}
		}
        if (empty($args['content'])) {
            XHandle::halt('请填写留言内容', '', 1);
        }
        $args['flag'] = intval($args['flag']);
		$args['addtime'] = strtotime(trim(str_replace('&nbsp;', ' ', $args['addtime'])));
		if (!empty($args['replycontent'])) {
			$args = array_merge($args, array('replyflag'=>1, 'replytime'=>time(),));
		}
        return array($id, $args);
    }
    
    
    private function _validID($id) {
        if (empty($id)) {
            XHandle::halt('ID丢失，请选择要操作的ID', '', 1);
        }
    }
    
}
?>
