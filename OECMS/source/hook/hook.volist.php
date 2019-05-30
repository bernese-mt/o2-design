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

function vo_list($extracts) {
    $params = XHandle::buildTagArray($extracts);
    if (!empty($params)) {
        
        @extract($params);
        
		$mod = strtolower(trim($params['mod']));
		
		$type = strtolower(trim($params['type']));
		
		$where = XFilter::filterSql(trim($params['where']));
		
		$orderby = XFilter::filterSql(trim($params['orderby']));
		
		$num = intval($params['num']);
		
		$catid = intval($params['catid']);
		
        $value	= intval($params['value']);
		
        
        if ($mod == 'article') {
			$model_article = X::model('article', 'im');
			return $model_article->getVolist($where, $orderby, $num);
			unset($model_article);

		}
        
        elseif ($mod == 'product') {
			$model_product = X::model('product', 'im');
			return $model_product->getVolist($where, $orderby, $num);
			unset($model_product);
		}
        
        elseif ($mod == 'photo') {
			$model_photo = X::model('photo', 'im');
			return $model_photo->getVolist($where, $orderby, $num);
			unset($model_photo);
        }
        
        elseif ($mod == 'download') {
			$model_download = X::model('download', 'im');
			return $model_download->getVolist($where, $orderby, $num);
			unset($model_download);
        }
        
        elseif ($mod == 'hr') {
			$model_hr = X::model('hr', 'im');
			return $model_hr->getVolist($where, $orderby, $num);
			unset($model_hr);
        }
        
        elseif ($mod == 'about') {
			$model_about = X::model('about', 'im');
			return $model_about->getVolist($where, $orderby, $num);
			unset($model_about);
        }
	}
}
?>
