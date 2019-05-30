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
    
    
    public function control_run() {
        $this->_getIndexItems();
        
        
        $this->getMeta('ch_photo_index');
        $page_title = str_ireplace(
            array('{cat.name}', '{cat.metatitle}', '{cat.metadescription}', '{cat.metakeyword}') ,
            array($this->catinfo['catname'], $this->catinfo['metatitle'], $this->catinfo['metadescription'], $this->catinfo['metakeyword']), 
            $this->metawrap['title']
        );
        $page_description = str_ireplace(
            array('{cat.name}', '{cat.metatitle}', '{cat.metadescription}', '{cat.metakeyword}') ,
            array($this->catinfo['catname'], $this->catinfo['metatitle'], $this->catinfo['metadescription'], $this->catinfo['metakeyword']), 
            $this->metawrap['description']
        );
        $page_keyword = str_ireplace(
            array('{cat.name}', '{cat.metatitle}', '{cat.metadescription}', '{cat.metakeyword}') ,
            array($this->catinfo['catname'], $this->catinfo['metatitle'], $this->catinfo['metadescription'], $this->catinfo['metakeyword']), 
            $this->metawrap['keyword']
        );
        $var_array = array(
            'catinfo'=>$this->catinfo,
            'treeid'=>$this->treeid,
            'rootid'=>$this->rootid,
            'cid'=>$this->cid,
            'page_title'=>$page_title,
            'page_description'=>$page_description,
            'page_keyword'=>$page_keyword,
        );
        $this->_tplfile = $this->getTPLFile($this->_tplname);
        TPL::assign($var_array);
        TPL::display($this->_tplfile);
    }
    
    
    public function control_list() {
       $this->_getListItems();
        $searchsql = '';
        
        $searchsql .= " AND v.treeid='{$this->treeid}'";
        
        if ($this->cid>0) {
            $m = parent::model('category', 'am');
            $childs = $m->getChildIDs($this->cid);
            if (!empty($childs)) {
                $searchsql .= " AND v.catid IN (".$childs.$this->cid.")";
            }
            else {
                $searchsql .= " AND v.catid='".$this->cid."'";
            }
            unset($m);
        }
        
        
        if ($this->catinfo['pagemax']<1) {
            $this->pagesize = parent::$cfg['photopagesize'];
        }
        else {
            $this->pagesize = $this->catinfo['pagemax'];
        }
        
        
        if ($this->catinfo['orderby'] == 1) {
            $orderby = ' ORDER BY v.updatetime DESC';
        }
        elseif ($this->catinfo['orderby'] == 2) {
            $orderby = ' ORDER BY v.addtime DESC';
        }
        elseif ($this->catinfo['orderby'] == 3) {
            $orderby = ' ORDER BY v.hits DESC';
        }
        elseif ($this->catinfo['orderby'] == 4) {
            $orderby = ' ORDER BY v.photoid DESC';
        }
        elseif ($this->catinfo['orderby'] == 5) {
            $orderby = ' ORDER BY v.photoid ASC';
        }
        else {
            $orderby = ' ORDER BY v.addtime DESC';
        }
        
        
        
        $model = parent::model('photo', 'im');
        list($total, $data) = $model->getList(
            array(
                'page'=>$this->page, 
                'pagesize'=>$this->pagesize, 
                'searchsql'=>$searchsql,
                'orderby'=>$orderby,
            )
        );
        unset($model);
        
        
        if (parent::$cfg['urlsuffix'] == 'html') {
            $url = parent::$urlpath.$this->catinfo['dirname'].'/';
            $showpage = XPage::indexHtml($total, $this->pagesize, $this->page, $url, 10);
        }
        else {
            $url = XRequest::getPhpSelf().'?c=photo&a=list&cid='.$this->cid;
            $showpage = XPage::index($total, $this->pagesize, $this->page, $url, 10);
        }
        
        
        $this->getMeta('ch_photo_list');
        $page_title = str_ireplace(
            array('{page}', '{cat.name}', '{cat.metatitle}', '{cat.metadescription}', '{cat.metakeyword}') ,
            array($this->page, $this->catinfo['catname'], $this->catinfo['metatitle'], $this->catinfo['metadescription'], $this->catinfo['metakeyword']), 
            $this->metawrap['title']
        );
        $page_description = str_ireplace(
            array('{page}', '{cat.name}', '{cat.metatitle}', '{cat.metadescription}', '{cat.metakeyword}') ,
            array($this->page, $this->catinfo['catname'], $this->catinfo['metatitle'], $this->catinfo['metadescription'], $this->catinfo['metakeyword']), 
            $this->metawrap['description']
        );
        $page_keyword = str_ireplace(
            array('{page}', '{cat.name}', '{cat.metatitle}', '{cat.metadescription}', '{cat.metakeyword}') ,
            array($this->page, $this->catinfo['catname'], $this->catinfo['metatitle'], $this->catinfo['metadescription'], $this->catinfo['metakeyword']), 
            $this->metawrap['keyword']
        );
        
        
        $m = parent::model('category', 'im');
        $thepath = $m->getCategoryUrl($this->cid, 1, $this->catinfo['catname']);
        if ($this->rootid != $this->cid && $this->rootid >0) {
            $thepath = $m->getCategoryUrl($this->rootid, 1).'&nbsp;&nbsp;&gt;&gt;&nbsp;&nbsp;'.$thepath;
        }
        unset($m);
        
        
        $var_array = array(
            'page'=>$this->page,
            'nextpage'=>$this->page+1,
            'prepage'=>$this->page-1,
            'pagecount'=>ceil($total/$this->pagesize),
            'pagesize'=>$this->pagesize,
            'total'=>$total,
            'showpage'=>$showpage,
            'treeid'=>$this->treeid,
            'rootid'=>$this->rootid,
            'catid'=>$this->cid,
			'cid'=>$this->cid,
            'photo'=>$data,
            'catinfo'=>$this->catinfo,
            'page_title'=>$page_title,
            'page_keyword'=>$page_keyword,
            'page_description'=>$page_description,
            'thepath'=>$thepath,
        );
        
        $this->_tplfile = $this->getTPLFile($this->_tplname);
		TPL::assign($var_array);
		TPL::display($this->_tplfile); 
    }
    
    
    public function control_detail() {
        $this->_getDetailItems();
        
        $model = parent::model('photo', 'im');
        list($data, $attr, $this->catinfo) = $model->getOneData($this->id);        
        if (empty($data)) {
            XHandle::halt('对不去，载入内容信息不存在！', '', 1);
        }
        
        $previous_item = $model->getPrevious($data['treeid'], $this->id);
        if (!empty($previous_item)) {
            $previous_item = "<a href='".$previous_item['url']."'>".$previous_item['title']."</a>";
        }
        else {
            $previous_item = '没有了';
        }
        $next_item = $model->getNext($data['treeid'], $this->id);
        if (!empty($next_item)) {
            $next_item = "<a href='".$next_item['url']."'>".$next_item['title']."</a>";
        }
        else {
            $next_item = '没有了';
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
        if (empty($this->_tplname)) {
            $this->_tplname = 'photo_detail';
        }
        $this->_tplfile = $this->getTPLFile($this->_tplname);
        
        
        $this->treeid = intval($this->catinfo['treeid']);
        $this->rootid = intval($this->catinfo['rootid']);
        
        
        $this->getMeta('ch_photo_detail');
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
        if ($this->rootid != $data['catid'] && $this->rootid >0) {
            $thepath = $m->getCategoryUrl($this->rootid, 1).'&nbsp;&nbsp;&gt;&gt;&nbsp;&nbsp;'.$thepath;
        }
        unset($m);
               
        
        $var_array = array(
            'photo'=>$data,
            'attr'=>$attr,
            'catinfo'=>$this->catinfo,
            'thepath'=>$thepath,
            'page_title'=>$page_title,
            'page_keyword'=>$page_keyword,
            'page_description'=>$page_description,
            'treeid'=>$this->treeid,
            'rootid'=>$this->rootid,
            'previous_item'=>$previous_item,
            'next_item'=>$next_item,
            'id'=>$this->id,
        );
        TPL::assign($var_array);
        TPL::display($this->_tplfile); 
    }
    
    
    private function _getIndexItems() {
        $path_info = $GLOBALS['path_info'];
        if (!empty($path_info) && isset($path_info['cid'])) {
            $this->cid = intval($path_info['cid']);
        }
        else {
            $this->cid = XRequest::getInt('cid');
        }
        if ($this->cid<1) {
            XHandle::halt('对不起，栏目/分类ID有错！', '', 1);
        }
        
        $m_cat = parent::model('category', 'im');
        $this->catinfo = $m_cat->getOneData($this->cid);
        unset($m_cat);
        
        if (empty($this->catinfo)) {
            XHandle::halt('对不起，载入栏目/分类失败！', '', 1);            
        }
        else {
            
            
            if ($this->catinfo['treeid'] == 0) {
                $this->treeid = $this->cid;
                $this->rootid = $this->cid;
            }
            else {
                $this->treeid = $this->catinfo['treeid'];
                $this->rootid = $this->catinfo['rootid'];
            }
            
            
            if (empty($this->catinfo['tplindex'])) {
                if (!empty($this->catinfo['mod_tplindex'])) {
                    
                    $this->_tplname = $this->catinfo['mod_tplindex'];
                }
                else {
                    $this->_tplname = 'photo_index';
                }
            }
            else {
                $this->_tplname = $this->catinfo['tplindex'];
            }
            if (false === $this->existsTplFile($this->_tplname)) {
                
                $this->_tplname = '';
                $this->control_list();
                exit;
            }
        }        
    }
    
    
    private function _getListItems() {
        $path_info = $GLOBALS['path_info'];
        if (!empty($path_info) && isset($path_info['cid'])) {
            $this->cid = intval($path_info['cid']);
        }
        else {
            $this->cid = XRequest::getInt('cid');
        }
        if ($this->cid<1) {
            XHandle::halt('对不起，栏目/分类ID有错！', '', 1);
        }
        
        $m_cat = parent::model('category', 'im');
        $this->catinfo = $m_cat->getOneData($this->cid);
        unset($m_cat);
        
        if (empty($this->catinfo)) {
            XHandle::halt('对不起，载入栏目/分类失败！', '', 1);            
        }
        else {
            
            
            if ($this->catinfo['treeid'] == 0) {
                $this->treeid = $this->cid;
                $this->rootid = $this->cid;
            }
            else {
                $this->treeid = $this->catinfo['treeid'];
                $this->rootid = $this->catinfo['rootid'];
            }
            
            
            if (empty($this->catinfo['tpllist'])) {
                if (!empty($this->catinfo['mod_tpllist'])) {
                    
                    $this->_tplname = $this->catinfo['mod_tpllist'];
                }
                else {
                    $this->_tplname = 'photo_list';
                }
            }
            else {
                $this->_tplname = $this->catinfo['tpllist'];
            }
        }
    }
    
    
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
}
?>
