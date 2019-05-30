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

class loginAModel extends X {

	
	public function doLogin($username, $password){
		$status = 0;
		$md5password = md5($password);
		$query	= "SELECT `adminid`, `adminname`, `password`, `flag` FROM ".DB_PREFIX."admin".
                    " WHERE `adminname`='{$username}' AND `password`='{$md5password}'";
		$data	= parent::$obj->fetch_first($query);
		if (!empty($data)) {
			
			if (intval($data['flag']) == 0){
				$status = 1;
			}
			
			else {
                $last_time = time();
				$array  = array(
					'logintimeline'=>$last_time,
					'logintimes'=>'[[logintimes+1]]',
					'loginip'=>XRequest::getip(),
				);
				parent::$obj->update(DB_PREFIX.'admin', $array, 'adminid='.intval($data['adminid']).'');
				$status = 3;
                $this->_output();
				
                $sid = '';
                $sid .= $data['adminid'].'\t';
                $sid .= $data['adminname'].'\t';
                $sid .= $data['password'].'\t';
                $sid .= $last_time;
                
                $code = parent::import('code', 'util');
                $sid = $code->authCode($sid, 'ENCODE', OECMS_RANDKEY);
                unset($code);                    
				parent::loadUtil('cookie');
				XCookie::set('app_admininfo', $sid);
			}
		}
		
		else {
			$status = 2;
		}
		return $status;
	}
    
    
    private function _output() {
        $m_output = parent::model('output', 'am');
        $m_output->tjSerial();
        unset($m_output);
    }
    
	
	public function checkLogin(){
		$status = false;
		parent::loadUtil('cookie');
		$sid = XCookie::get('app_admininfo');
        $code = parent::import('code', 'util');
        $sid = $code->authCode($sid, 'DECODE', OECMS_RANDKEY);
        unset($code);
        if (empty($sid)) {
            $status = false;
        }
        else {
            
            $adminInfo = explode('\t', $sid);
            $adminData = $this->_getUser(intval($adminInfo[0]));
            if (empty($adminData)) {
                $status = false;
            }
            else {
                
                if ($adminData['adminname'] != $adminInfo[1] || $adminData['password'] != $adminInfo[2]){
                    $status = false;
                }
                else {
                    parent::$wrap_admin = array(
                        'super'=>intval($adminData['super']),
                        'adminid'=>intval($adminData['adminid']),
                        'adminname'=>$adminData['adminname'],
                        'lasttime'=>$adminData['logintimeline'],
                        'groupname'=>$adminData['groupname'],
                        'auths'=>$adminData['auths'],
                    );
                    $status = true;
                }
            }
            
        }
		return $status;
	}
    
    
    public function loginLog() {
        $m_serial = parent::model('output', 'am');
        $m_serial->tjSerial();
        unset($m_serial);
    }
    
    
	public function logout(){
		parent::loadUtil('cookie');
		XCookie::del('app_admininfo');
        parent::$wrap_admin['super'] = NULL;
        parent::$wrap_admin['adminid'] = NULL;
        parent::$wrap_admin['adminname'] = NULL;
        parent::$wrap_admin['groupname'] = NULL;
        parent::$wrap_admin['auths'] = NULL;
        parent::$wrap_admin['lasttime'] = NULL;
		
	}
    
    
    private function _getUser($uid) {
        $sql = "SELECT v.*,g.groupname,g.auths".
                " FROM ".DB_PREFIX."admin AS v".
                " LEFT JOIN ".DB_PREFIX."authgroup AS g ON v.groupid=g.groupid".
                " WHERE v.adminid='".intval($uid)."'";
        return parent::$obj->fetch_first($sql);     
    }
    
    
    
    public function checkAuth($auth) {
        if (empty(parent::$wrap_admin['adminname'])) {
            XHandle::halt('对不起，您没有权限执行该操作！', '', 1);
        }
        else {
            
            if (intval(parent::$wrap_admin['super']) == 1) {
                
            }
            else {
                if (empty(parent::$wrap_admin['auths'])) {
                    XHandle::halt('对不起，您没有权限执行该操作！', '', 1);
                }
                else {
                    if (false === XHandle::getStrpos(parent::$wrap_admin['auths'], $auth)){
                        XHandle::halt('对不起，您没有权限执行该操作！<br />操作权限为：['.$auth.']', '', 1);
                    }
                }
            }
        }
    }
    
    
    public function copyRight() {
        echo("<div align='center'><p style='font-size:10px; font-family:Arial, Helvetica, sans-serif; line-height:120%;color:#999999'>Powered by <a href='".$this->cms_url."' target=_blank>".OECMS_VERSION."</a> &copy;2006-2012 OE Technology Inc.</p></div>");
    }

}
?>
