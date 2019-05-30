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

function hook_get_category($params) {
    if (!empty($params)) {
        @extract($params);
		$type = empty($params['type']) ? 'url' : trim($params['type']);
		$id = intval($params['id']);
		$name = empty($params['name']) ? '' : trim($params['name']);
		$class = empty($params['class']) ? '' : trim($params['class']);
		$target = empty($params['target']) ? '_self' : trim($params['target']);
		$title = empty($params['title']) ? 'none' : trim($params['title']);
		
		if ($type == 'url') {
			
			if (true === XValid::isSpChar($name)) {
				$val = $name;
				$valtype = 2;
			}
			
			else {
				$val = $id;
				$valtype = 1;
			}
			$model = X::model('category', 'im');
			return $model->getCategoryUrl($val, $valtype, $title, $class, $target);
			unset($model);
		}
    } 
}
TPL::regFunction('category', 'hook_get_category');


function vo_category($extracts) {
    $params = XHandle::buildTagArray($extracts);
    if (!empty($params)) {
        @extract($params);
		
		$type = strtolower(trim($params['type']));
        
        
        $args = array();
        if (true === XValid::isSpChar($params['module'])) {
            $args['moduel'] = trim($params['module']);
        }
        if (true === XValid::isNumber($params['treeid'])) {
            $args['treeid'] = $params['treeid'];
        }
        if (true === XValid::isNumber($params['rootid'])) {
            $args['rootid'] = $params['rootid'];
        }
        if (true === XValid::isNumber($params['ismenu'])) {
            $args['ismenu'] = $params['ismenu'];
        }
        if (true === XValid::isNumber($params['isaccessory'])) {
            $args['isaccessory'] = $params['isaccessory'];
        }
		if (true === XValid::isNumber($params['num'])) {
			$args['num'] = $params['num'];
		}
        
        $model = X::model('category', 'im');
        
        if ($type == 'rootmenu') {
            return $model->rootMenu();
        }
        
        elseif ($type == 'sedmenu') {
			return $model->sedMenu();
        }
		
		elseif ($type == 'submenu') {
			return $model->subMenu($args);
		}
		
		elseif ($type == 'rootcat') {
			return $model->rootCategory($args);
		}
		
		elseif ($type == 'treecat') {
			return $model->treeCategory($args);
		}
		unset($model);
        
	}
}
?>
