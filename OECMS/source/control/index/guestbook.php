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
class control extends indexbase {   
    
    private $_tplname = NULL;
    private $_tplfile = NULL;
    
    
    public function control_run() {
        $this->getMeta('ch_guestbook');
        
        $page_title = $this->metawrap['title'];
        $page_description = $this->metawrap['description'];
        $page_keyword = $this->metawrap['keyword'];
        
        $var_array = array(
            'page_title'=>$page_title,
            'page_keyword'=>$page_keyword,
            'page_description'=>$page_description,
        );
        $this->_tplfile = $this->getTPLFile('guestbook'); 
        TPL::assign($var_array);
        TPL::display($this->_tplfile); 
    }
    
    
    public function control_saveadd() {
        $args = $this->_validAdd();
        $model = parent::model('guestbook','im');
        $result = $model->doAdd($args);
        unset($model);
        if (true === $result) {
            XHandle::halt('留言成功', parent::$urlpath.'index?c=guestbook', 0);
        }
        else {
            XHandle::halt('留言失败', '', 1);
        }
    }
    
    private function _validAdd() {
        $args = XRequest::getGpc(array(
            'title', 'username', 'email', 'content',
            'qq', 'msn', 'address', 'telephone', 'mobile',
            'checkcode', 
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
        parent::loadUtil('session');
        if ($args['checkcode'] != XSession::get('verifycode')) {
            XHandle::halt('验证码不正确', '', 1);
        }
        unset($args['checkcode']);
        return $args;
    }
}
?>
