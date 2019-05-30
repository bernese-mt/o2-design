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
class adminbase extends X {
    
    var $cpfile;
    
    var $cptpl;
    
    var $pagesize = 30;
    var $page = 1;
    
	
    public function __construct() {
        
        if ($GLOBALS['c'] != 'login') {
            $this->checkLogin();
        }
        
		$this->db = parent::$obj;
        $this->cpfile = __ADMIN_FILE__;
        $this->cptpl = 'tpl/'.__ADMIN_TPLDIR__.'/';
        
        TPL::assign(array('a'=>$GLOBALS['a']));        
        
        $this->page = intval(XRequest::getArgs('page'));
        if ($this->page<1) {
            $this->page = 1;
        }
	}
	
	public function checkLogin() {
		if(false === parent::model('login', 'am')->checkLogin()){
			XHandle::redirect($this->cpfile.'?c=login');
		}
	}
    
    
    public function checkAuth($value) {
        $auth = parent::model('login', 'am');
        $auth->checkAuth($value);
		unset($auth);
    }
    
    
    public function log($em, $oplog = '', $result = 1) { 
        
        require_once BASE_ROOT.'./source/include/elements.php';
        $emarray = array();
        $emarray = auth_get_em_array();

        
        $log_string = '';
        if (!empty($em)) {
            $log_string = $emarray[$em];
        } 
        if (!empty($oplog)) {
            $log_string .= !empty($log_string) ? '-'.$oplog : $oplog;
        }   
        
        
        if (!empty($log_string)) {
            $logid = parent::$obj->fetch_newid("SELECT MAX(logid) FROM ".DB_PREFIX."log", 1);
            $log_array = array(
                'logid'=>$logid,
                'username'=>parent::$wrap_admin['adminname'],
                'ip'=>XRequest::getip(),
                'content'=>$log_string,
                'logtype'=>1,
                'timeline'=>time(),
                'success'=>$result,
            );
            parent::$obj->insert(DB_PREFIX.'log', $log_array);
            unset($log_array);
        } 
    }
    
    
    public function saveLoginLog($username, $content, $success=0) {
        $logid = parent::$obj->fetch_newid("SELECT MAX(logid) FROM ".DB_PREFIX."log", 1);
        $log_array = array(
            'logid'=>$logid,
            'username'=>$username,
            'ip'=>XRequest::getip(),
            'content'=>$content,
            'logtype'=>1,
            'timeline'=>time(),
            'success'=>$success,
        );
        parent::$obj->insert(DB_PREFIX.'log', $log_array);
        unset($username, $content, $success, $logid, $log_array);
    }
}
?>
