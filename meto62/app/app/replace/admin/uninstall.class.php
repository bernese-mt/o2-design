<?php
defined('IN_MET') or exit('No permission');//所有文件都是已这句话开头，保证系统单入口。

load::sys_class('admin');//包含后台基类，如果是前台包含web.class.php。‘.class.php’ 可以省略。
load::sys_func('file');
define('PATH_APP_FILE', PATH_APP.'app'.'/'.'webui'.'/');
class uninstall extends admin {//继承后台基类。类名称要与文件名一致
    public function __construct() {
        parent::__construct();//如果重写了初始化方法,一定要调用父类的初始化函数。
    }
	public function dodel(){//定义自己的方法
		global $_M;
		
		echo '应用卸载中...<br />';
		$no = '10004';
		$delApp = "DELETE FROM {$_M['table']['applist']} WHERE no = '{$no}' ";	
		if(DB::query($delApp)){
			
			//写入删除文件功能
			
			$fileDir = PATH_WEB.'/app/app/replace';
			if ( deldir($fileDir,$type = 0) ){
				echo '目录删除成功！<br />';
			}else{
				echo '目录删除失败，请检查目录权限！<br />';
				exit;
			}
			
			echo '卸载成功!';
		}else{
			echo '卸载失败!';
		}
	}
}
?>