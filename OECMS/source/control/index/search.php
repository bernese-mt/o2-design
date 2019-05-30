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
    
    private $type = null;
    
    private $keyword = null;
    
    private $_tplname = NULL;
    private $_tplfile = NULL;
    
    private $fieldname = null;
    
    private function _getSearchItems() {
        $this->type = XRequest::getArgs('type');
        $this->keyword = XRequest::getArgs('keyword');
        if (!in_array($this->type, array('article', 'product', 'photo', 'hr', 'download'))) {
            XHandle::halt('请选择要搜索的类型', '', 1);
        }
        if (empty($this->keyword)) {
            XHandle::halt('请输入要搜索的关键字', '', 1);
        }
        if ($this->type == 'product') {
            $this->fieldname = 'productname';
        }
        else {
            $this->fieldname = 'title';
        }
        $this->_tplname = $this->type.'_search';
    }
    
    
    public function control_run() {
        $this->_getSearchItems();
        $searchsql = '';
        $orderby = '';
        $searchsql .= " AND v.{$this->fieldname} LIKE '%{$this->keyword}%'";
        
        if ($this->type == 'article') {
            $pagesize = parent::$cfg['articlepagesize'];
        }
        elseif ($this->type == 'product') {
            $pagesize = parent::$cfg['productpagesize'];
        }
        elseif ($this->type == 'photo') {
            $pagesize = parent::$cfg['photopagesize'];
        }
        elseif ($this->type == 'hr') {
            $pagesize = parent::$cfg['hrpagesize'];
        }
        elseif ($this->type == 'download') {
            $pagesize = parent::$cfg['downpagesize'];
        }
        
        $model = parent::model($this->type, 'im');
        list($total, $data) = $model->getList(
            array(
                'page'=>$this->page, 
                'pagesize'=>$pagesize, 
                'searchsql'=>$searchsql,
                'orderby'=>$orderby,
            )
        );
        unset($model);
        
        $url = XRequest::getPhpSelf().'?c=search&type='.$this->type.'&keyword='.urlencode($this->keyword);
        $showpage = XPage::index($total, $pagesize, $this->page, $url, 10);
        
        $this->getMeta('ch_'.$this->type.'_search');
        $page_title = str_ireplace('{page}', $this->page, $this->metawrap['title']);
        $page_description = str_ireplace('{page}', $this->page, $this->metawrap['description']);
        $page_keyword = str_ireplace('{page}', $this->page, $this->metawrap['keyword']);
        
        
        $var_array = array(
            'page'=>$this->page,
            'nextpage'=>$this->page+1,
            'prepage'=>$this->page-1,
            'pagecount'=>ceil($total/$pagesize),
            'pagesize'=>$pagesize,
            'total'=>$total,
            'showpage'=>$showpage,
            'search'=>$data,
            'page_title'=>$page_title,
            'page_keyword'=>$page_keyword,
            'page_description'=>$page_description,
            'type'=>$this->type,
            'keyword'=>$this->keyword,
        );
        
        $this->_tplfile = $this->getTPLFile($this->_tplname);
		TPL::assign($var_array);
		TPL::display($this->_tplfile); 
    }
}
?>
