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
    
    private $cid = 0;
    
    private $id = 0;
    
    
    private $treeid = 0;
    private $rootid = 0;
    private $catinfo = array();
    
    
    private $_tplname = NULL;
    private $_tplfile = NULL;
    
    
    
    private function _getDetailItems() {
        $path_info = $GLOBALS['path_info'];
        if (!empty($path_info) && isset($path_info['id'])) {
            $this->id = intval($path_info['id']);
        }
        else {
            $this->id = XRequest::getInt('id');
        }
        if ($this->id<1) {
            XHandle::halt('对不起，内容ID参数无效！', '', 1);
        }
    }
    
    public function control_run() {
        $this->control_detail();
    }
    
    
    public function control_detail() {
        $this->_getDetailItems();
        
        $model = parent::model('about', 'im');
        list($data, $this->catinfo) = $model->getOneData($this->id);        
        if (empty($data)) {
            XHandle::halt('对不去，载入内容信息不存在！', '', 1);
        }
        unset($model);
        
        
        if (!empty($data['tpldetail'])) {
            $this->_tplname = $data['tpldetail'];
        }
        else {
            if (!empty($this->catinfo['tpldetail'])) {
                $this->_tplname = $this->catinfo['tpldetail'];
            }
            else {
                $this->_tplname = $this->catinfo['mod_tpldetail'];
            }
        }
        $this->_tplfile = $this->getTPLFile($this->_tplname);
        
        
        $this->treeid = intval($this->catinfo['treeid']);
        $this->rootid = intval($this->catinfo['rootid']);
        
        
        $this->getMeta('ch_about_detail');
        $page_title = str_ireplace(
            array('{title}', '{metadescription}', '{metakeyword}', '{cat.name}', '{cat.metatitle}', '{cat.metadescription}', '{cat.metakeyword}') ,
            array($data['title'], $data['metadescription'], $data['metakeyword'], $this->catinfo['catname'], $this->catinfo['metatitle'], $this->catinfo['metadescription'], $this->catinfo['metakeyword']), 
            $this->metawrap['title']
        );
        $page_description = str_ireplace(
            array('{title}', '{metadescription}', '{metakeyword}', '{cat.name}', '{cat.metatitle}', '{cat.metadescription}', '{cat.metakeyword}') ,
            array($data['title'], $data['metadescription'], $data['metakeyword'], $this->catinfo['catname'], $this->catinfo['metatitle'], $this->catinfo['metadescription'], $this->catinfo['metakeyword']), 
            $this->metawrap['description']
        );
        $page_keyword = str_ireplace(
            array('{title}', '{metadescription}', '{metakeyword}', '{cat.name}', '{cat.metatitle}', '{cat.metadescription}', '{cat.metakeyword}') ,
            array($data['title'], $data['metadescription'], $data['metakeyword'], $this->catinfo['catname'], $this->catinfo['metatitle'], $this->catinfo['metadescription'], $this->catinfo['metakeyword']), 
            $this->metawrap['keyword']
        );
        
        
        $m = parent::model('category', 'im');
        $thepath = $m->getCategoryUrl($data['catid'], 1)."&nbsp;&nbsp;&gt;&gt;&nbsp;&nbsp;"."<a href=".$data['url'].">".$data['title']."</a>";
        unset($m);
               
        
        $var_array = array(
            'about'=>$data,
            'attr'=>$attr,
            'thepath'=>$thepath,
            'page_title'=>$page_title,
            'page_keyword'=>$page_keyword,
            'page_description'=>$page_description,
            'id'=>$this->id,
            'treeid'=>$this->treeid,
            'rootid'=>$this->rootid,
        );
        TPL::assign($var_array);
        TPL::display($this->_tplfile); 
    }
}
?>
