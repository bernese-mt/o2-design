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
    
    private $plugin_id = NULL;
    
    private $do = NULL;
       
    private function _getItems() {
        
        $this->plugin_id = XRequest::getArgs('plugin_id');
        $this->do = XRequest::getArgs('do');
    }

	
    public function action_run() {
        $this->checkAuth('plugin');
        $this->_getItems();
        
        $model = parent::model('plugin', 'am');
        $plugins = $model->getPlugins();
        unset($model);
        $var_array = array(
            'plugins' => $plugins,
        );
        TPL::assign($var_array);
		TPL::display($this->cptpl.'plugin.tpl');
	}
    
    
    public function action_active(){
        $this->checkAuth('plugin');
        $this->_getItems();
        
        $this->_checkPlugin();
        $model = parent::model('plugin', 'am');
        if($model->activePlugin($this->plugin_id)){
            $this->log('plugin', "激活id={$this->plugin_id}", 1);
            XHandle::halt('插件激活成功', $this->cpfile.'?c=plugin', 0);
        }
        else{
            $this->log('plugin', "激活id={$this->plugin_id}", 0);
            Xhandle::halt('插件激活失败', '', 1);
        }
		unset($model);
    }
    
    
    public function action_inactive(){
        $this->checkAuth('plugin');
        $this->_getItems();
        $this->_checkPlugin();
        $model = parent::model('plugin', 'am');
        if ($model->inactivePlugin($this->plugin_id)){
            $this->log('plugin', "禁用id={$this->plugin_id}", 1);
            XHandle::halt('插件禁用成功', $this->cpfile.'?c=plugin', 0);
        }
        else{
            $this->log('plugin', "禁用id={$this->plugin_id}", 0);
            XHandle::halt('插件禁用失败', '', 1);
        }
        unset($model);
    }
    
    
    public function action_del(){
        $this->checkAuth('plugin');
        $this->_getItems();
        $this->_checkPlugin();
        $model = parent::model('plugin', 'am');
        
        $model->inactivePlugin($this->plugin_id);
        $plugin_path = BASE_ROOT.'source/plugin/'.$this->plugin_id;
        parent::loadUtil('file');
        if (true === XFile::delDir($plugin_path)) {
            $this->log('plugin', "删除id={$this->plugin_id}", 1);
            XHandle::halt('插件删除成功', $this->cpfile.'?c=plugin', 0);
        }
        else{
            $this->log('plugin', "删除id={$this->plugin_id}", 0);
            XHandle::halt('插件删除失败', '', 1);
        }
        unset($model);
    }
    
    
    public function action_setting() {
        $this->_getItems();
        $this->_validPlugin();
        
        $plugin_path = BASE_ROOT.'./source/plugin/'.$this->plugin_id.'/admin.inc.php';
        if (file_exists($plugin_path)) {
            require_once($plugin_path);
            
            if (empty($this->do)){
                $this->do = 'setting';
            }
            
            $function_name = $this->plugin_id.'_plugin_'.$this->do;
            if (function_exists($function_name)) {
                
                $var_array = array(
                    'plugin_event'=>$function_name.'_event',
                    'plugin_name'=>$this->plugin_id,
                );
                TPL::assign($var_array);
         		TPL::display($this->cptpl.'plugin.tpl');
            }
            else {
                XHandle::halt('对不起，该插件没有'.$this->do.'方法！', '', 1);
            }
        }
        else {
            XHandle::halt('对不起，载入插件文件失败！', '', 1);
        }
    }
    
     
    public function action_save() {
        $this->_getItems();
        $this->_validPlugin();
        
        $plugin_path = BASE_ROOT.'./source/plugin/'.$this->plugin_id.'/admin.inc.php';
        if (file_exists($plugin_path)) {
            require_once($plugin_path);
            
            if (empty($this->do)){
                $this->do = 'setting';
            }
            
            $function_name = $this->plugin_id.'_plugin_'.$this->do;
            if (function_exists($function_name)) {
                call_user_func($function_name);
                
            }
            else {
                XHandle::halt('对不起，该插件没有'.$this->do.'方法！', '', 1);
            }
        }
        else {
            XHandle::halt('对不起，载入插件文件失败！', '', 1);
        }
    }
    
    
    private function _checkPlugin(){
        if (empty($this->plugin_id)){
            XHandle::halt('对不起，插件名不能为空！', '', 1);
        }
        if (false === XValid::isPlugin($this->plugin_id)) {
            XHandle::halt('对不起，插件名错误或者不存在！', '', 1);
        }
    }
    
    
    private function _validPlugin() {
        if (empty($this->plugin_id)){
            XHandle::halt('对不起，插件名不能为空！', '', 1);
        }
        if (false === XValid::isPlugin($this->plugin_id)) {
            XHandle::halt('对不起，插件名错误或者不存在！', '', 1);
        }
        
        parent::loadLib('option');
		$active_plugins = XOption::get('active_plugins');
        if (!is_array($active_plugins) || !in_array($this->plugin_id, $active_plugins)){
            XHandle::halt('对不起，该插件不存在或者已禁用。', $this->cpfile.'?c=plugin', 4);
        }
    }
}
?>
