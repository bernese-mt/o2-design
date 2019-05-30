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
        $this->checkAuth('relatedlink_volist');
        $searchsql = "";
        $model = parent::model('relatedlink', 'am');
        list($total, $data) = $model->getList(array('page'=>$this->page, 'pagesize'=>$this->pagesize, 'searchsql'=>$searchsql));
        $url = XRequest::getPhpSelf();
        $url .= '?c=relatedlink';
        $var_array = array(
            'page'=>$this->page,
            'nextpage'=>$this->page+1,
            'prepage'=>$this->page-1,
            'pagecount'=>ceil($total/$this->pagesize),
            'pagesize'=>$this->pagesize,
            'total'=>$total,
            'showpage'=>XPage::admin($total, $this->pagesize, $this->page, $url, 10),
            'relatedlink'=>$data,
        );
        unset($model);
		TPL::assign($var_array);
		TPL::display($this->cptpl.'relatedlink.tpl');
	}
    
    
    public function action_add() {
        $this->checkAuth('relatedlink_add');
        TPL::assign($var_array);
        TPL::display($this->cptpl.'relatedlink.tpl'); 
    }
    
    
    public function action_edit() {
        $this->checkAuth('relatedlink_edit');
        $id = XRequest::getInt('id');
        $this->_validID($id);
        $model = parent::model('relatedlink', 'am');
        $data = $model->getData($id);
        unset($model);
        if (empty($data)) {
            XHandle::halt('对不起，读取数据不存在！', '', 1);
        }
        else {
            $var_array = array(
                'relatedlink'=>$data,
                'id'=>$id,
                'page'=>$this->page,
            );
            TPL::assign($var_array);
            TPL::display($this->cptpl.'relatedlink.tpl');
        }
    }
    
    
    public function action_saveadd() {
        $this->checkAuth('relatedlink_add');
        $args = $this->_validAdd();
        $model = parent::model('relatedlink', 'am');
        $result = $model->doAdd($args);
        unset($model); 
        if (true === $result) {
            $this->log('relatedlink_add', '', 1);
            XHandle::halt('添加成功', $this->cpfile.'?c=relatedlink', 0);
        }
        else {
            $this->log('relatedlink_add', '', 0);
            XHandle::halt('添加失败', '', 1);
        }
	}
    
    
    public function action_saveedit() {
        $this->checkAuth('relatedlink_edit');
        list($id, $args) = $this->_validEdit();
        $model = parent::model('relatedlink', 'am');
        $result = $model->doEdit($id, $args);
        unset($model);
        if (true === $result) {
            $this->log('relatedlink_edit', "id={$id}", 1);
            XHandle::halt('编辑成功', $this->cpfile.'?c=relatedlink&page='.$this->page.'', 0);
        }
        else {
            $this->log('relatedlink_edit', "id={$id}", 0);
            XHandle::halt('编辑失败', '', 1);
        }
    }
    
    
    public function action_del() {
        $this->checkAuth('relatedlink_del');
        $array_id = XRequest::getArray('id');
        $this->_validID($array_id);
        $model = parent::model('relatedlink', 'am');
        for($ii=0; $ii<count($array_id); $ii++){
            $id = intval($array_id[$ii]);
            $result = $model->doDel($id);            
        }
        unset($model);
        if (true === $result) {
            $this->log('relatedlink_del', "id={$array_id}", 1);
            XHandle::halt('删除成功', $this->cpfile.'?c=relatedlink', 0);
        }
        else {
            $this->log('relatedlink_del', "id={$array_id}", 0);
            XHandle::halt('删除失败', '', 1);
        }  
    }
    
     
    public function action_batch() {
        $this->checkAuth('relatedlink_edit');
        $array_id = XRequest::getArray('id');
        $this->_validID($array_id);
        $model = parent::model('relatedlink', 'am');
        for($ii=0; $ii<count($array_id); $ii++){
            $id = intval($array_id[$ii]);
            $args = $this->_validBatch($id);
            $result = $model->doEdit($id, $args);
        }
        unset($model);
        if (true === $result) {
            $this->log('relatedlink_edit', "id={$array_id}", 1);
            XHandle::halt('更新成功', $this->cpfile.'?c=relatedlink', 0);
        }
        else {
            $this->log('relatedlink_edit', "id={$array_id}", 0);
            XHandle::halt('更新失败', '', 1);
        }  
    }
    
    
    public function action_update() {
        $this->checkAuth('relatedlink_edit');
        $args = XRequest::getGpc(array('id', 'type'));
        $model = parent::model('relatedlink', 'am');
        $model->doUpdate(intval($args['id']), trim($args['type']));
        unset($model);
    }
    
    
    private function _validAdd() {
        $args = XRequest::getGpc(array(
            'linktag', 'color', 'target', 'nofollow', 'flag',
            'article', 'product', 'photo', 'download', 'about'
        ));
        $url = XRequest::getArgs('url', '', false);
        if (empty($args['linktag'])) {
            XHandle::halt('链接文字不能为空', '', 1);
        }
        if (empty($url)) {
            XHandle::halt('链接地址不能为空', '', 1);
        }
        $args['target'] = intval($args['target']);
        $args['nofollow'] = intval($args['nofollow']);
        $args['flag'] = intval($args['flag']);
        $args['article'] = intval($args['article']);
        $args['product'] = intval($args['product']);
        $args['photo'] = intval($args['photo']);
        $args['download'] = intval($args['download']);
        $args['about'] = intval($args['about']);
        $args = array_merge($args, array('url'=>$url));
        return $args;
    }
    
    
    private function _validEdit() {
        $id = XRequest::getInt('id');
        $args = XRequest::getGpc(array(
            'linktag', 'color', 'target', 'nofollow', 'flag',
            'article', 'product', 'photo', 'download', 'about'
        ));
        $url = XRequest::getArgs('url', '', false);
        if (empty($args['linktag'])) {
            XHandle::halt('链接文字不能为空', '', 1);
        }
        if (empty($url)) {
            XHandle::halt('链接地址不能为空', '', 1);
        }
        $args['target'] = intval($args['target']);
        $args['nofollow'] = intval($args['nofollow']);
        $args['flag'] = intval($args['flag']);
        $args['article'] = intval($args['article']);
        $args['product'] = intval($args['product']);
        $args['photo'] = intval($args['photo']);
        $args['download'] = intval($args['download']);
        $args['about'] = intval($args['about']);
        $args = array_merge($args, array('url'=>$url));
        return array($id, $args);
    }
    
    
    private function _validID($id) {
        if (empty($id)) {
            XHandle::halt('ID丢失，请选择要操作的ID', '', 1);
        }
    }
    
     
    private function _validBatch($id) {
        $linktag = XRequest::getArgs('linktag_'.$id);
        $url = XRequest::getArgs('url_'.$id, '', false);
        $color = XRequest::getArgs('color_'.$id);
        $target = XRequest::getInt('target_'.$id);
        $nofollow = XRequest::getInt('nofollow_'.$id);
        $flag = XRequest::getInt('flag_'.$id);
        $article = XRequest::getInt('article_'.$id);
        $product = XRequest::getInt('product_'.$id);
        $photo = XRequest::getInt('photo_'.$id);
        $download = XRequest::getInt('download_'.$id);
        $about = XRequest::getInt('about_'.$id);
        if (empty($linktag)) {
            XHandle::halt('链接文字不能为空，所在ID：'.$id.'', '', 1);
        }
        if (empty($url)) {
            XHandle::halt('链接地址不能为空，所在ID：'.$id.'', '', 1);
        }
        $args = array(
            'linktag'=>$linktag,
            'url'=>$url,
            'color'=>$color,
            'target'=>$target,
            'nofollow'=>$nofollow,
            'flag'=>$flag,
            'article'=>$article,
            'product'=>$product,
            'photo'=>$photo,
            'download'=>$download,
            'about'=>$about,
        );
        return $args;
    }
}
?>
