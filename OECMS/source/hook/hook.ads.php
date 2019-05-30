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

function get_zone($idmark = '') {
    
    if (true === XValid::isSpChar($idmark)) {
        
        
        $model_zone = X::model('ads', 'im');
        return $model_zone->getZone($idmark);
        
        unset($model_zone);

    }
}


function get_ads($idmark = '') {
    if (true === XValid::isSpChar($idmark)){
        
         $model_ads = X::model('ads', 'im');
        return $model_ads->getAds($idmark);
        unset($model_ads);

    }
}
?>
