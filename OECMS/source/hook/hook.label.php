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

function hook_get_label($params) {
    if (!empty($params)) {
        @extract($params);
        $name = strtolower(trim($params['name']));
        if (true === XValid::isSpChar($name)){
            
            $model_label = X::model('label', 'im');
            return $model_label->getOne($name);
            unset($model_label);        
        }
    } 
}
TPL::regFunction('label', 'hook_get_label');
?>
