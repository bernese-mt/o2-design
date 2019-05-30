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
    private $_tplfile = NULL;
	
    public function control_run() {
        $this->getMeta('ch_index');
        $var_array = array(
            'page_title'=>$this->metawrap['title'],
            'page_description'=>$this->metawrap['description'],
            'page_keyword'=>$this->metawrap['keyword'],
        );
        $this->_tplfile = $this->getTPLFile('index');
        TPL::assign($var_array);
		TPL::display($this->_tplfile);
	}
}
?>
