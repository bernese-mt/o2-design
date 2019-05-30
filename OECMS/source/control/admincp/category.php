<?php
/**
 * [OECMS] (C)2012-2099 OEdev,Inc.
 * <E-Mail：phpcoo@qq.com>
 * Url www.oedev.net, www.phpcoo.com
 * Update 2013.02.05
*/
class control extends adminbase {
    
	
    public function action_run() {
        $this->checkAuth('category_volist');
        $model = parent::model('category', 'am');
        list($total, $data) = $model->getList();
        $url = XRequest::getPhpSelf();
        $url .= '?c=category';
     	foreach($data as $key => $value){
    		if($value['depth']==0){
    			$data[$key]['tree_node'] = $value['catname'];
    		}else{
    			$tree = "";
    			if($value['depth']==1){
    				$tree = "&nbsp;&nbsp;├ ";
    			}else{
    				for($ii=2;$ii<=$value['depth'];$ii++){
    					$tree .= "&nbsp;&nbsp;│";
    				}
    				$tree .= "&nbsp;&nbsp;├ ";
    			}
    			$data[$key]['tree_node'] = $tree.$value['catname'];
    		}
    	}
        $var_array = array(
            'total'=>$total,
            'category'=>$data,
        );
        unset($model);
		TPL::assign($var_array);
		TPL::display($this->cptpl.'category.tpl');
	}
    
    
    public function action_add_nodelist() {
        $this->checkAuth('category_add');
        parent::loadLib('mod');
        $var_array = array(
            'module_select'=>XMod::selectListModule('', 'modalias'),
        );
        TPL::assign($var_array);        
        TPL::display($this->cptpl.'category.tpl'); 
    }
    
    
    public function action_saveadd_nodelist() {
        $this->checkAuth('category_add');
        $args = $this->_validAddNodeList();
        $model = parent::model('category', 'am');
        $result = $model->doAddNodeList($args);
        if (true === $result) {
            $this->log('category_add', '', 1);
            XHandle::halt('添加成功', $this->cpfile.'?c=category', 0);
        }
        else {
            $this->log('category_add', '', 0);
            XHandle::halt('添加失败', '', 1);
        }
        unset($model);	
	}
    
    public function action_edit_nodelist() {
        $id = XRequest::getInt('id');
        $this->_validID($id);
        $model = parent::model('category', 'am');
        $data = $model->getData($id);
        unset($model);
        if (empty($data)) {
            XHandle::halt('对不起，读取数据不存在！', '', 1);
        }
        else {
            parent::loadLib('mod');
            $var_array = array(
                'category'=>$data,
                'id'=>$id,
            );
            TPL::assign($var_array);
            TPL::display($this->cptpl.'category.tpl');
        }
    }
    
    public function action_saveedit_nodelist() {
        $this->checkAuth('category_edit');
        list($id, $array) = $this->_validEditNodeList();
        $model = parent::model('category', 'am');
        $result = $model->doEditNodeList($id, $array);
        unset($model);
        if (true === $result) {
            $this->log('category_edit', "id={$id}", 1);
            XHandle::halt('编辑成功', $this->cpfile.'?c=category', 0);
        }
        else {
            $this->log('category_edit', "id={$id}", 0);
            XHandle::halt('编辑失败', '', 1);
        }
    }
    
    
    
    public function action_add_nodeabout() {
        $this->checkAuth('category_add');
        TPL::display($this->cptpl.'category.tpl'); 
    } 
    
    public function action_saveadd_nodeabout() {
        $this->checkAuth('category_add');
        $args = $this->_validAddNodeAbout();
        $model = parent::model('category', 'am');
        $result = $model->doAddNodeAbout($args);
        if (true === $result) {
            $this->log('category_add', '', 1);
            XHandle::halt('添加成功', $this->cpfile.'?c=category', 0);
        }
        else {
            $this->log('category_add', '', 0);
            XHandle::halt('添加失败', '', 1);
        }
        unset($model);	
	}
    
    public function action_edit_nodeabout() {
        $id = XRequest::getInt('id');
        $this->_validID($id);
        $model = parent::model('category', 'am');
        $data = $model->getData($id);
        unset($model);
        if (empty($data)) {
            XHandle::halt('对不起，读取数据不存在！', '', 1);
        }
        else {
            $var_array = array(
                'category'=>$data,
                'id'=>$id,
            );
            TPL::assign($var_array);
            TPL::display($this->cptpl.'category.tpl');
        }
    }
    
    public function action_saveedit_nodeabout() {
        $this->checkAuth('category_edit');
        list($id, $array) = $this->_validEditNodeAbout();
        $model = parent::model('category', 'am');
        $result = $model->doEditNodeAbout($id, $array);
        unset($model);
        if (true === $result) {
            $this->log('category_edit', "id={$id}", 1);
            XHandle::halt('编辑成功', $this->cpfile.'?c=category', 0);
        }
        else {
            $this->log('category_edit', "id={$id}", 0);
            XHandle::halt('编辑失败', '', 1);
        }
    }
    
 
     
    public function action_add_nodeoutside() {
        $this->checkAuth('category_add');    
        TPL::display($this->cptpl.'category.tpl'); 
    } 
    
    public function action_saveadd_nodeoutside() {
        $this->checkAuth('category_add');
        $args = $this->_validAddNodeOutside();
        $model = parent::model('category', 'am');
        $result = $model->doAddNodeOutside($args);
        if (true === $result) {
            $this->log('category_add', '', 1);
            XHandle::halt('添加成功', $this->cpfile.'?c=category', 0);
        }
        else {
            $this->log('category_add', '', 0);
            XHandle::halt('添加失败', '', 1);
        }
        unset($model);	
	}
    
    public function action_edit_nodeoutside() {
        $id = XRequest::getInt('id');
        $this->_validID($id);
        $model = parent::model('category', 'am');
        $data = $model->getData($id);
        unset($model);
        if (empty($data)) {
            XHandle::halt('对不起，读取数据不存在！', '', 1);
        }
        else {
            $var_array = array(
                'category'=>$data,
                'id'=>$id,
            );
            TPL::assign($var_array);
            TPL::display($this->cptpl.'category.tpl');
        }
    }
    
    public function action_saveedit_nodeoutside() {
        $this->checkAuth('category_edit');
        list($id, $array) = $this->_validEditNodeOutside();
        $model = parent::model('category', 'am');
        $result = $model->doEditNodeOutside($id, $array);
        unset($model);
        if (true === $result) {
            $this->log('category_edit', "id={$id}", 1);
            XHandle::halt('编辑成功', $this->cpfile.'?c=category', 0);
        }
        else {
            $this->log('category_edit', "id={$id}", 0);
            XHandle::halt('编辑失败', '', 1);
        }
    }
    
    
    
    public function action_add_list() {
        $this->checkAuth('category_add');
        $rootid = $this->_validRootID();
        
        $model = parent::model('category', 'am');
        
        $treeid = $model->getTreeID($rootid);
        
        $moddata = $model->getCatModule($treeid);
        unset($model);
        
        
        parent::loadLib('mod');
        $var_array = array(
            'rootid'=>$rootid,
            'modname'=>$moddata['modname'],
            'root_select'=>XMod::selectCategoryOneNode($treeid, $rootid, 'rootid', '==请选择=='),
        );
        TPL::assign($var_array);        
        TPL::display($this->cptpl.'category.tpl'); 
    }
    
    public function action_saveadd_list() {
        $this->checkAuth('category_add');
        $args = $this->_validAddList();
        $model = parent::model('category', 'am');
        $result = $model->doAddList($args);
        unset($model);
        if (true === $result) {
            $this->log('category_add', '', 1);
            XHandle::halt('添加成功', $this->cpfile.'?c=category', 0);
        }
        else {
            $this->log('category_add', '', 0);
            XHandle::halt('添加失败', '', 1);
        }
	}
    
    public function action_edit_list() {
        $this->checkAuth('category_edit');
        $id = XRequest::getInt('id');
        $this->_validID($id);
        $model = parent::model('category', 'am');
        $data = $model->getData($id);
        unset($model);
        if (empty($data)) {
            XHandle::halt('对不起，读取数据不存在！', '', 1);
        }
        else {
            parent::loadLib('mod');
            $var_array = array(
                'category'=>$data,
                'id'=>$id,
                'root_select'=>XMod::selectCategoryOneNode($data['treeid'], $data['rootid'], 'rootid', '==请选择=='),
            );
            TPL::assign($var_array);
            TPL::display($this->cptpl.'category.tpl');
        }
    }
    
    public function action_saveedit_list() {
        $this->checkAuth('category_edit');
        list($id, $args) = $this->_validEditList();
        $model = parent::model('category', 'am');
        $result = $model->doEditList($id, $args);
        unset($model);
        if ($result == 1) {
            $this->log('category_edit', "id={$id}", 0);
            XHandle::halt('编辑失败，移动栏目失败，不允许移动到同一树状子节点下面', '', 1);
        }
        elseif ($result == 2) {
            $this->log('category_edit', "id={$id}", 1);
            XHandle::halt('编辑成功', $this->cpfile.'?c=category', 0);
        }
        else {
            $this->log('category_edit', "id={$id}", 0);
            XHandle::halt('编辑失败', '', 1);
        }
    }
    
     
    public function action_add_about() {
        $this->checkAuth('category_add');
        $rootid = $this->_validRootID();
        
        $model = parent::model('category', 'am');
        
        $treeid = $model->getTreeID($rootid);
        
        $moddata = $model->getCatModule($treeid);
        unset($model);
        
        
        parent::loadLib('mod');
        $var_array = array(
            'rootid'=>$rootid,
            'modname'=>$moddata['modname'],
            'root_select'=>XMod::selectCategoryOneNode($treeid, $rootid, 'rootid', '==请选择=='),
        );
        TPL::assign($var_array);        
        TPL::display($this->cptpl.'category.tpl'); 
    }
    
    public function action_saveadd_about() {
        $this->checkAuth('category_add');
        $args = $this->_validAddAbout();
        $model = parent::model('category', 'am');
        $result = $model->doAddAbout($args);
        unset($model);
        if (true === $result) {
            XHandle::halt('添加成功', $this->cpfile.'?c=category', 0);
        }
        else {
            XHandle::halt('添加失败', '', 1);
        }
	}
    
    public function action_edit_about() {
        $this->checkAuth('category_edit');
        $id = XRequest::getInt('id');
        $this->_validID($id);
        $model = parent::model('category', 'am');
        $data = $model->getData($id);
        unset($model);
        if (empty($data)) {
            XHandle::halt('对不起，读取数据不存在！', '', 1);
        }
        else {
            parent::loadLib('mod');
            $var_array = array(
                'category'=>$data,
                'id'=>$id,
                'root_select'=>XMod::selectCategoryOneNode($data['treeid'], $data['rootid'], 'rootid', '==请选择=='),
            );
            TPL::assign($var_array);
            TPL::display($this->cptpl.'category.tpl');
        }
    }
    
    public function action_saveedit_about() {
        $this->checkAuth('category_edit');
        list($id, $args) = $this->_validEditAbout();
        $model = parent::model('category', 'am');
        $result = $model->doEditAbout($id, $args);
        unset($model);
        if ($result == 1) {
            $this->log('category_edit', "id={$id}", 0);
            XHandle::halt('编辑失败，移动栏目失败，不允许移动到同一树状子节点下面', '', 1);
        }
        elseif ($result == 2) {
            $this->log('category_edit', "id={$id}", 1);
            XHandle::halt('编辑成功', $this->cpfile.'?c=category', 0);
        }
        else {
            $this->log('category_edit', "id={$id}", 0);
            XHandle::halt('编辑失败', '', 1);
        }
    }
    
        
    
    public function action_del() {
        $this->checkAuth('category_del');
        $id = XRequest::getInt('id');
        $this->_validID($id);
        
        $model = parent::model('category', 'am');
        
        if (true === $model->doCheckSystemCat($id)) {
            XHandle::halt('读不起，该栏目是系统默认栏目，不能删除。', '', 1);
        }
        
        if (true === $model->checkExistsChild($id)) {
            XHandle::halt('对不起，该栏目下有子栏目，请从子栏目删除。', '', 1);
        }
        else {
            $result = $model->doDel($id);  
        }
        unset($model);      
        if (true === $result) {
            $this->log('category_del', "id={$id}", 1);
            XHandle::halt('删除成功', $this->cpfile.'?c=category', 0);
        }
        else {
            $this->log('category_del', "id={$id}", 0);
            XHandle::halt('删除失败', '', 1);
        }
        
    }
    
    
    public function action_update() {
        $this->checkAuth('category_edit');
        $args = XRequest::getGpc(array('id', 'type'));
        $model = parent::model('category', 'am');
        $model->doUpdate(intval($args['id']), trim($args['type']));
        unset($model);
    }
    
    
    private function _validAddNodeList() {
        $args = XRequest::getGpc(array(
            'modalias', 'catname', 'asname', 'dirname', 'orders', 'flag',
            'catpic', 'metatitle', 'metakeyword', 'metadescription',
            'purview', 'ismenu', 'isaccessory', 'linktype', 'outurl',
            'tplindex', 'tpllist', 'tpldetail', 'pagemax', 'orderby',
        ));
        if (!in_array($args['modalias'], array('article', 'product', 'photo', 'download', 'hr'))) {
            XHandle::halt('请选择模块类型', '', 1);
        }
        if (empty($args['catname'])) {
            XHandle::halt('栏目名称不能为空', '', 1);
        }
        if (false === XValid::isSpChar($args['dirname'])) {
            XHandle::halt('目录标识格式不正确', '', 1);
        }else {
            
            $m = parent::model('category', 'am');
            if (true === $m->isForbidCatname($args['dirname'])) {
                XHandle::halt('对不起，该栏目标识为系统禁止，请填写另外一个', '', 1);
            }
            if (true === $m->doCheckDirName($args['dirname'])) {
                XHandle::halt('对不起，该目录标识已经存在，请填写另外一个。', '', 1);
            }
            unset($m);
        }
        $args['orders'] = intval($args['orders']);
        $args['flag'] = intval($args['flag']);
        $args['purview'] = intval($args['purview']);
        $args['ismenu'] = intval($args['ismenu']);
        $args['isaccessory'] = intval($args['isaccessory']);
        $args['linktype'] = intval($args['linktype']);
        $args['pagemax'] = intval($args['pagemax']);
        $args['orderby'] = intval($args['orderby']);
        return $args;
    }
    
     
    private function _validEditNodeList() {
        $args = XRequest::getGpc(array(
            'catname', 'asname', 'orders', 'flag',
            'catpic', 'metatitle', 'metakeyword', 'metadescription',
            'purview', 'ismenu', 'isaccessory', 'linktype', 'outurl',
            'tplindex', 'tpllist', 'tpldetail', 'pagemax', 'orderby',
        ));
        $id = XRequest::getInt('id');
        if ($id<1) {
            XHandle::halt('对不起，ID丢失。', '', 1);
        }
        if (empty($args['catname'])) {
            XHandle::halt('栏目名称不能为空', '', 1);
        }
        $args['orders'] = intval($args['orders']);
        $args['flag'] = intval($args['flag']);
        $args['purview'] = intval($args['purview']);
        $args['ismenu'] = intval($args['ismenu']);
        $args['isaccessory'] = intval($args['isaccessory']);
        $args['linktype'] = intval($args['linktype']);
        $args['pagemax'] = intval($args['pagemax']);
        $args['orderby'] = intval($args['orderby']);
        return array($id, $args);
    }

    
    private function _validAddNodeAbout() {
        $args = XRequest::getGpc(array(
            'catname', 'asname', 'dirname', 'orders', 'flag', 'catpic', 'purview',
            'ismenu', 'isaccessory', 'linktype', 'outurl', 'tpldetail',
        ));
        if (empty($args['catname'])) {
            XHandle::halt('栏目名称不能为空', '', 1);
        }
        if (false === XValid::isSpChar($args['dirname'])) {
            XHandle::halt('目录标识格式不正确', '', 1);
        }else {
            
            $m = parent::model('category', 'am');
            if (true === $m->isForbidCatname($args['dirname'])) {
                XHandle::halt('对不起，该栏目标识为系统禁止，请填写另外一个', '', 1);
            }
            if (true === $m->doCheckDirName($args['dirname'])) {
                XHandle::halt('对不起，该目录标识已经存在，请填写另外一个。', '', 1);
            }
            unset($m);
        }
        $args['orders'] = intval($args['orders']);
        $args['flag'] = intval($args['flag']);
        $args['purview'] = intval($args['purview']);
        $args['ismenu'] = intval($args['ismenu']);
        $args['isaccessory'] = intval($args['isaccessory']);
        $args['linktype'] = intval($args['linktype']);
        return $args;
    }
    
     
    private function _validEditNodeAbout() {
        $args = XRequest::getGpc(array(
            'catname', 'asname', 'orders', 'flag', 'catpic', 'purview',
            'ismenu', 'isaccessory', 'linktype', 'outurl', 'tpldetail',
        ));
        $id = XRequest::getInt('id');
        if ($id<1) {
            XHandle::halt('对不起，ID丢失。', '', 1);
        }
        if (empty($args['catname'])) {
            XHandle::halt('栏目名称不能为空', '', 1);
        }
        $args['orders'] = intval($args['orders']);
        $args['flag'] = intval($args['flag']);
        $args['purview'] = intval($args['purview']);
        $args['ismenu'] = intval($args['ismenu']);
        $args['isaccessory'] = intval($args['isaccessory']);
        $args['linktype'] = intval($args['linktype']);
        return array($id, $args);
    }
    
    
    private function _validAddNodeOutside() {
        $args = XRequest::getGpc(array(
            'catname', 'orders', 'flag', 'ismenu', 'isaccessory', 'catpic', 'outurl',
        ));
        if (empty($args['catname'])) {
            XHandle::halt('栏目名称不能为空', '', 1);
        }
        $args['orders'] = intval($args['orders']);
        $args['flag'] = intval($args['flag']);
        $args['ismenu'] = intval($args['ismenu']);
        $args['isaccessory'] = intval($args['isaccessory']);
        return $args;
    }
    
     
    private function _validEditNodeOutside() {
        $args = XRequest::getGpc(array(
            'catname', 'orders', 'flag', 'ismenu', 'isaccessory', 'catpic', 'outurl',
        ));
        $id = XRequest::getInt('id');
        if ($id<1) {
            XHandle::halt('对不起，ID丢失。', '', 1);
        }
        if (empty($args['catname'])) {
            XHandle::halt('栏目名称不能为空', '', 1);
        }
        $args['orders'] = intval($args['orders']);
        $args['flag'] = intval($args['flag']);
        $args['ismenu'] = intval($args['ismenu']);
        $args['isaccessory'] = intval($args['isaccessory']);
        return array($id, $args);
    }
    
    
    
    private function _validAddList() {
        $args = XRequest::getGpc(array(
            'rootid', 'catname', 'asname', 'dirname', 'orders', 'flag',
            'catpic', 'metatitle', 'metakeyword', 'metadescription',
            'purview', 'ismenu', 'isaccessory', 'linktype', 'outurl',
            'tplindex', 'tpllist', 'tpldetail', 'pagemax', 'orderby',
        ));
        $args['rootid'] = intval($args['rootid']);
        if ($args['rootid']<1) {
            XHandle::halt('上级栏目不能为空', '', 1);
        }
        if (empty($args['catname'])) {
            XHandle::halt('栏目名称不能为空', '', 1);
        }
        if (false === XValid::isSpChar($args['dirname'])) {
            XHandle::halt('目录标识格式不正确', '', 1);
        }else {
            
            $m = parent::model('category', 'am');
            if (true === $m->isForbidCatname($args['dirname'])) {
                XHandle::halt('对不起，该栏目标识为系统禁止，请填写另外一个', '', 1);
            }
            if (true === $m->doCheckDirName($args['dirname'])) {
                XHandle::halt('对不起，该目录标识已经存在，请填写另外一个。', '', 1);
            }
            unset($m);
        }
        $args['orders'] = intval($args['orders']);
        $args['flag'] = intval($args['flag']);
        $args['purview'] = intval($args['purview']);
        $args['ismenu'] = intval($args['ismenu']);
        $args['isaccessory'] = intval($args['isaccessory']);
        $args['linktype'] = intval($args['linktype']);
        $args['pagemax'] = intval($args['pagemax']);
        $args['orderby'] = intval($args['orderby']);
        return $args;
    }

    
    private function _validEditList() {
        $id = XRequest::getInt('id');
        $args = XRequest::getGpc(array(
            'rootid', 'catname', 'asname', 'orders', 'flag',
            'catpic', 'metatitle', 'metakeyword', 'metadescription',
            'purview', 'ismenu', 'isaccessory', 'linktype', 'outurl',
            'tplindex', 'tpllist', 'tpldetail', 'pagemax', 'orderby',
        ));
        if ($id<1) {
            XHandle::halt('ID丢失', '', 1);
        }
        $args['rootid'] = intval($args['rootid']);
        if ($args['rootid']<1) {
            XHandle::halt('上级栏目不能为空', '', 1);
        }
        if (empty($args['catname'])) {
            XHandle::halt('栏目名称不能为空', '', 1);
        }
        if ($id == $rootid) {
            XHandle::halt('对不起，上级栏目不能是自己！', '', 1);
        }
        $args['orders'] = intval($args['orders']);
        $args['flag'] = intval($args['flag']);
        $args['purview'] = intval($args['purview']);
        $args['ismenu'] = intval($args['ismenu']);
        $args['isaccessory'] = intval($args['isaccessory']);
        $args['linktype'] = intval($args['linktype']);
        $args['pagemax'] = intval($args['pagemax']);
        $args['orderby'] = intval($args['orderby']);
        
        
        return array($id, $args);
    }
    
    
    
    private function _validAddAbout() {
        $args = XRequest::getGpc(array(
            'rootid', 'catname', 'asname', 'dirname', 'orders', 'flag', 'catpic',
            'purview', 'ismenu', 'isaccessory', 'linktype', 'outurl', 'tpldetail',
        ));
        $args['rootid'] = intval($args['rootid']);
        if ($args['rootid']<1) {
            XHandle::halt('上级栏目不能为空', '', 1);
        }
        if (empty($args['catname'])) {
            XHandle::halt('栏目名称不能为空', '', 1);
        }
        if (false === XValid::isSpChar($args['dirname'])) {
            XHandle::halt('目录标识格式不正确', '', 1);
        }else {
            
            $m = parent::model('category', 'am');
            if (true === $m->isForbidCatname($args['dirname'])) {
                XHandle::halt('对不起，该栏目标识为系统禁止，请填写另外一个', '', 1);
            }
            if (true === $m->doCheckDirName($args['dirname'])) {
                XHandle::halt('对不起，该目录标识已经存在，请填写另外一个。', '', 1);
            }
            unset($m);
        }
        $args['orders'] = intval($args['orders']);
        $args['flag'] = intval($args['flag']);
        $args['purview'] = intval($args['purview']);
        $args['ismenu'] = intval($args['ismenu']);
        $args['isaccessory'] = intval($args['isaccessory']);
        $args['linktype'] = intval($args['linktype']);
        return $args;
    }
    
     
    private function _validEditAbout() {
        $id = XRequest::getInt('id');
        $args = XRequest::getGpc(array(
            'rootid', 'catname', 'asname', 'orders', 'flag', 'catpic', 'purview',
            'ismenu', 'isaccessory', 'linktype', 'outurl', 'tpldetail',
        ));
        if ($id<1) {
            XHandle::halt('ID丢失', '', 1);
        }
        $args['rootid'] = intval($args['rootid']);
        if ($args['rootid']<1) {
            XHandle::halt('上级栏目不能为空', '', 1);
        }
        if (empty($args['catname'])) {
            XHandle::halt('栏目名称不能为空', '', 1);
        }
        if ($id == $rootid) {
            XHandle::halt('对不起，上级栏目不能是自己！', '', 1);
        }
        $args['orders'] = intval($args['orders']);
        $args['flag'] = intval($args['flag']);
        $args['purview'] = intval($args['purview']);
        $args['ismenu'] = intval($args['ismenu']);
        $args['isaccessory'] = intval($args['isaccessory']);
        $args['linktype'] = intval($args['linktype']);
        return array($id, $args);
    }
    
    
    private function _validID($id) {
        if (empty($id)) {
            XHandle::halt('ID丢失，请选择要操作的ID', '', 1);
        }
    }
    
    
    private function _validRootID() {
        $id = XRequest::getInt('rootid');
        if ($id<1) {
            XHandle::halt('对不起，一级栏目ID丢失。', '', 1);
        }
        return $id;
    }
    
}
?>
