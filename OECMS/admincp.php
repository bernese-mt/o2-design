<?php
/**
 * [OECMS] (C)2012-2099 OEdev,Inc.
 * <E-Mail：phpcoo@qq.com>
 * Url www.oedev.net, www.phpcoo.com
 * Update 2013.01.28
*/
/* 载入主文件 */
require_once 'source/core/run.php';
/* 获取参数值 */
$c = XRequest::getGpc('c');
$a = XRequest::getGpc('a');
$c = empty($c) ? 'login' : $c;
$a = empty($a) ? 'run' : $a;

/* 载入Plugin插件 */
if (($c=='login' && $a=='login') || ($c=='login' && $a=='logout')){
}
else {
    X::importPlugin();
}

/* 允许运行的Module */
if(in_array($c, 
    array(
        'login', 'frame', 'admin', 'setting', 'plugin', 'log', 'seo', 
        'skin', 'templet', 'htmllabel', 'relatedlink', 'zone',
        'myads', 'module', 'category', 'modattr', 'about', 'article',
        'product', 'photo', 'download', 'hr', 'upload', 'ajax',
		'guestbook', 
    )
    )) {
	/* 载入control文件 */
	$control_base = BASE_ROOT.'./source/control/adminbase.php';
	$control_path = BASE_ROOT.'./source/control/admincp/'.$c.'.php';
	if (!file_exists($control_path)) {
		XHandle::error('Controller file:['.$c.'] is not found!');
	}
	else {
		require_once($control_base);
		require_once($control_path);
		/* 实例化control */
		$control = new control();
		/* 调用类的action方法 */
		$method = 'action_'.$a;
		if(method_exists($control, $method) && $a{0} != '_') {
			$control->$method();
		} else {
			XHandle::error('Controller Action ['.$a.'] not found!');
		}
	}
} 
else {
	XHandle::error('Controller ['.$c.'] is forbiden!');
}

?>