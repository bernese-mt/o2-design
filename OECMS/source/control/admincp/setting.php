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
        $model = parent::model('setting', 'am');
        $data = $model->getOptions('site_base');
        unset($model);
        $var_array = array();
        if (!empty($data)) {
            parent::loadLib('mod');
            $data['tjcode'] = stripslashes($data['tjcode']);
            $data['timezone_select'] = XMod::selectTimezone($data['timezone'], 'timezone');
            $var_array = $data;
        }
        TPL::assign($var_array);
        TPL::display($this->cptpl.'setting.tpl');
	}
    
    
    public function action_footer() {
        $model = parent::model('setting', 'am');
        $data = $model->getOptions('site_footer');
        unset($model);
        $var_array = array('content'=>stripslashes($data));
        TPL::assign($var_array);
        TPL::display($this->cptpl.'setting.tpl');
    }
    
    
    public function action_rewrite() {
        $model = parent::model('setting', 'am');
        $data = $model->getOptions('site_rewrite');
        unset($model);
        $var_array = array('routetype'=>$data);
        
        TPL::assign($var_array);
        TPL::display($this->cptpl.'setting.tpl');
    }
    
    
    public function action_upload() {
        
        $php_upload_maxsize = ini_get('upload_max_filesize');
        
        if (function_exists("imagecreate")){
        	if(function_exists('gd_info')){
        		$gd_ver_info = gd_info();
        		$gd_ver = $gd_ver_info['GD Version'];
        	}else{
        		$gd_ver = '支持';
        	}
        }else{
        	$gd_ver = '不支持';
        }
        $var_array = array();
        parent::loadLib('option');
        $data = XOption::get('upload_config');
        if (!empty($data)) {
            $var_array = $data;
        }
        $var_array = array_merge($var_array, 
            array(
            'php_upload_maxsize'=>$php_upload_maxsize, 
            'gd_version'=>$gd_ver
        ));
        TPL::assign($var_array);
        TPL::display($this->cptpl.'setting.tpl');       
    }
    
    
    
    public function action_index_style() {
        $model = parent::model('setting', 'am');
        $data = $model->getOptions('index_style');
        unset($model);
        $var_array = array();
        if (!empty($data)) {
            $var_array = $data;
        }
        TPL::assign($var_array);
        TPL::display($this->cptpl.'setting.tpl');
    }
    
    public function action_main_style() {
        $model = parent::model('setting', 'am');
        $data = $model->getOptions('main_style');
        unset($model);
        $var_array = array();
        if (!empty($data)) {
            $var_array = $data;
        }
        TPL::assign($var_array);
        TPL::display($this->cptpl.'setting.tpl');
    }
    
    
    
    public function action_savebase() {
        $args = array();
        $args = $this->_validBase();
        $model = parent::model('setting', 'am');
        $result = $model->doSave('site_base', $args);
        unset($model);
        if (true === $result) {
            $this->log('setting', '站点设置', 1);
            XHandle::halt('保存成功', $this->cpfile.'?c=setting', 0);
        }
        else {
            $this->log('setting', '站点设置', 0);
            XHandle::halt('保存失败', '', 1);
        }  
    }
    
    
    public function action_savefooter() {
        $content = $this->_validFooter();
        $model = parent::model('setting', 'am');
        $result = $model->doUpdate('site_footer', $content);
        unset($model);
        if (true === $result) {
            $this->log('setting', '底部信息', 1);
            XHandle::halt('保存成功', $this->cpfile.'?c=setting&a=footer', 0);
        }else {
            $this->log('setting', '底部信息', 0);
            XHandle::halt('保存失败', '', 1);
        }     
    }
    
    
    public function action_saverewrite() {
        $args = $this->_validRewrite();
        $model = parent::model('setting', 'am');
        $result = $model->doSave('site_rewrite', $args);
        unset($model);
        if (true === $result) {
            $this->log('setting', '伪静态设置', 1);
            XHandle::halt('保存成功', $this->cpfile.'?c=setting&a=rewrite', 0);
        }
        else {
            $this->log('setting', '伪静态设置', 0);
            XHandle::halt('保存失败', '', 1);
        }     
    }
    
    
    public function action_saveupload() {
        $args = array();
        $args = $this->_validUpload();
        $model = parent::model('setting', 'am');
        $result = $model->doSave('upload_config', $args);
        unset($model);
        if (true === $result) {
            $this->log('setting', '图片设置', 1);
            XHandle::halt('保存成功', $this->cpfile.'?c=setting&a=upload', 0);
        }
        else {
            $this->log('setting', '图片设置', 0);
            XHandle::halt('保存失败', '', 1);
        }
    }
    
    
    
    public function action_save_index_style() {
        $args = array();
        $args = $this->_validIndexStyle();
        $model = parent::model('setting', 'am');
        $result = $model->doSave('index_style', $args);
        unset($model);
        if (true === $result) {
            $this->log('setting', '首页配置', 1);
            XHandle::halt('保存成功', $this->cpfile.'?c=setting&a=index_style', 0);
        }
        else {
            $this->log('setting', '首页配置', 0);
            XHandle::halt('保存失败', '', 1);
        }  
    }
    
    public function action_save_main_style() {
        $args = array();
        $args = $this->_validMainStyle();
        $model = parent::model('setting', 'am');
        $result = $model->doSave('main_style', $args);
        unset($model);
        if (true === $result) {
            $this->log('setting', '界面配置', 1);
            XHandle::halt('保存成功', $this->cpfile.'?c=setting&a=main_style', 0);
        }
        else {
            $this->log('setting', '界面配置', 0);
            XHandle::halt('保存失败', '', 1);
        }  
    }
    
    
    public function action_clearcache() {
        TPL::clearComplied();
        XHandle::halt('清除成功', $this->cpfile.'?c=setting', 0);
    }
    
    
    public function action_updatecache() {
        $model = parent::model('setting', 'am');
        $model->doUpdateCache();
        unset($model);
        XHandle::halt('更新成功', $this->cpfile.'?c=setting', 0);
    }
    
    
    private function _validBase() {
        $args = array();
        $args = XRequest::getGpc(array(
            'sitename', 'siteurl', 'logo', 'logowidth', 'logoheight',
            'timezone', 'icpcode',
        ));
        $tjcode = XRequest::getArgs('tjcode', '', false);
        if (empty($args['sitename'])) {
            XHandle::halt('网站名称不能为空', '', 1);
        }
        if (empty($args['siteurl'])) {
            XHandle::halt('网站URL地址不能为空', '', 1);
        }
        else {
     		if (substr($args['siteurl'], -1) != '/') {
    			$args['siteurl'] = $args['siteurl'].'/';
    		}
        }
        $args['timezone'] = trim($args['timezone']);
        if (false == XValid::isNumber($args['timezone'])) {
            $args['timezone'] = 8;
        }
        $args['logowidth'] = intval($args['logowidth']);
        $args['logoheight'] = intval($args['logoheight']);
        $args = array_merge($args, array('tjcode'=>$tjcode));
        return $args;
    }
    
    
    private function _validFooter() {
        $content = XRequest::getArgs('content', '', false);
        return $content; 
    }
    
    
    private function _validUpload(){
        $php_upload_maxsize = ini_get('upload_max_filesize');
        $args = XRequest::getGpc(
            array(
                'uploadmaxsize', 'maxthumbwidth', 'maxthumbheight', 'thumbwidth',
                'thumbheight', 'productthumbwidth', 'productthumbheight',
                'photothumbwidth', 'photothumbheight', 'watermarkflag',
                'watermarkfile', 'watermarkpos'
            )
        );
        $args['uploadmaxsize'] = floatval($args['uploadmaxsize']);
        if ($args['uploadmaxsize']>$php_upload_maxsize){
            $args['uploadmaxsize'] = $php_upload_maxsize;
        }
        $args['maxthumbwidth'] = intval($args['maxthumbwidth']);
        $args['maxthumbheight'] = intval($args['maxthumbheight']);
        $args['thumbwidth'] = intval($args['thumbwidth']);
        $args['thumbheight'] = intval($args['thumbheight']);
        $args['productthumbwidth'] = intval($args['productthumbwidth']);
        $args['productthumbheight'] = intval($args['productthumbheight']);
        $args['photothumbwidth'] = intval($args['photothumbwidth']);
        $args['photothumbheight'] = intval($args['photothumbheight']);
        $args['watermarkflag'] = intval($args['watermarkflag']);
        $args['watermarkpos'] = intval($args['watermarkpos']);
        return $args;
    }
    
    
    private function _validSeo() {
        $args = XRequest::getGpc(array(
            'sitetitle', 'metadescription', 'metakeyword'
        ));
        return $args;
    }
    
    
    private function _validRewrite() {
        $args = XRequest::getGpc(array('urlsuffix'));
        if (empty($args['urlsuffix'])) {
            $args['urlsuffix'] = 'php';
        }
        return $args;
    }
    
     
    private function _validSafety() {
        $args = array();
        $args = XRequest::getGpc(array(
            'admincode', 'commentcode', 'commentaudit',
            'regcode', 'logincode', 'forgetcode', 'forbidargs',
        ));
        $args['admincode'] = intval($args['admincode']);
        $args['commentcode'] = intval($args['commentcode']);
        $args['commentaudit'] = intval($args['commentaudit']);
        $args['regcode'] = intval($args['regcode']);
        $args['logincode'] = intval($args['logincode']);
		$args['forgetcode'] = intval($args['forgetcode']);
        return $args;
    }
    
    
    private function _validOrder() {
        $args = array();
        $args = XRequest::getGpc(array(
            'buynon', 'currency', 'symbol', 'taxrate', 'orderpagesize',
        ));
        $args['buynon'] = intval($args['buynon']);
        $args['taxrate'] = floatval($args['taxrate']);
        $args['orderpagesize'] = intval($args['orderpagesize']);
        if (empty($args['currency'])) {
            $args['currency'] = 'CNY';
        }
        if (empty($args['symbol'])) {
            $args['symbol'] = '￥';
        }
        if ($args['taxrate']<0) {
            $args['taxrate'] = 0;
        }
        return $args;
    }
    
    
    private function _validIndexStyle() {
        $args = XRequest::getGpc(array(
            'articlenum', 'articlelen',
            'productnum', 'productlen',
            'photonum', 'photolen',
            'downnum', 'downlen',
            'hrnum', 'hrlen',
        ));
        $args['articlenum'] = intval($args['articlenum']);
        $args['articlelen'] = intval($args['articlelen']);
        $args['productnum'] = intval($args['productnum']);
        $args['productlen'] = intval($args['productlen']);
        $args['photonum'] = intval($args['photonum']);
        $args['photolen'] = intval($args['photolen']);
        $args['downnum'] = intval($args['downnum']);
        $args['downlen'] = intval($args['downlen']);
        $args['hrnum'] = intval($args['hrnum']);
        $args['hrlen'] = intval($args['hrlen']);
        return $args;
    }
    
    
    private function _validMainStyle() {
        $args = XRequest::getGpc(array(
            'articlepagesize', 'productpagesize',
            'photopagesize', 'downpagesize',
            'hrpagesize',
        ));
        $args['articlepagesize'] = intval($args['articlepagesize']);
        $args['productpagesize'] = intval($args['productpagesize']);
        $args['photopagesize'] = intval($args['photopagesize']);
        $args['downpagesize'] = intval($args['downpagesize']);
        $args['hrpagesize'] = intval($args['hrpagesize']);
        return $args;
    }
    
    
    private function _validUser() {
        $args = XRequest::getGpc(array(
            'usercpflag', 'userreg', 'usergrid', 'lockusers', 'useraudit',
        ));
        $args['usercpflag'] = intval($args['usercpflag']);
        $args['userreg'] = intval($args['userreg']);
        $args['usergrid'] = intval($args['usergrid']);
        $args['useraudit'] = intval($args['useraudit']);
        return $args;
    }
}
?>
