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
class indexAModel extends X {
    
    
    public function isFunc($funname) {
        if (!empty($funname)) {
            if (function_exists($funname)) {
                return '<font color="green">√</font>';
            }
            else {
                return '<font color="red">×</font>';
            }
        }
        else {
            return '<font color="red">函数错误</font>';
        }
    }
    
    
    public function getVar($varName) {
        switch ($result = get_cfg_var($varName)){
            case 0:
                return '<font color="red">×</font>';
                break;
            case 1:
                return '<font color="green">√</font>';
                break;
            default:
                return $result;
                break;
        }
    }

    
    public function getServerIP() {
        return $_SERVER['SERVER_ADDR'];
    }
    
    
    public function getClientIP() {
        return XRequest::getip();
    }
    
    
    public function getWebEngine() {
        return $_SERVER['SERVER_SOFTWARE'];
    }
    
    
    public function getOs() {
        $os = explode(' ', php_uname());
        return $os[0];
    }
    
    
    public function getPhpVersion() {
        return PHP_VERSION;
    }
    
    
    public function getGD() {
        if (function_exists('gd_info')) {
            $gd_info = @gd_info();
	        return $gd_info['GD Version'];
        }
        else {
            return "<font color='red'>×</font>";
        }
    }
    
    
    public function doGetSysData() {
       
        $data = array(
            'serverip'=>$this->getServerIP(),
            'clientip'=>$this->getClientIP(),
            'os'=>$this->getOs(),
            'webengine'=>$this->getWebEngine(),
            'time'=>time(),
            'phpversion'=>$this->getPhpVersion(),
            'curl'=>$this->isFunc('curl'),
            'gd'=>$this->getGD(),
            'iconv'=>$this->isFunc('iconv'),
            'urlopen'=>$this->getVar('allow_url_fopen'),
        );
        return $data;
    }
}
?>
