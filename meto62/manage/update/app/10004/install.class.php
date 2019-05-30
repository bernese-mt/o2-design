<?php
defined('IN_MET') or exit('No permission');//所有文件都是已这句话开头，保证系统单入口。

	load::sys_class('admin');//包含后台基类，如果是前台包含web.class.php。‘.class.php’ 可以省略。
	load::sys_func('file');

	class install  {//继承后台基类。类名称要与文件名一致
		public $appNo;
		public function __construct() {
			//如果重写了初始化方法,一定要调用父类的初始化函数。
			$this->appNo = 10004;
		}
		public function dosql(){//定义自己的方法
			global $_M;		
			$no = $this->appNo;
			$ver = '1.2';
			$m_name = 'replace';
			$m_class = 'replace';
			$m_action = 'doindex';
			$appname = '内容批量替换器';
			$info = '能够批量替换内容文字、超链接、图片路径，如公司地址、电话、某个链接地址变更，逐个修改效率太低，此应用是很好的解决办法。';
			$addtime = 1423045243;
			$updateime = 1476068006;
			$query = "SELECT ver FROM {$_M['table']['applist']} WHERE no={$this->appNo}";
			$beser=DB::get_one($query);
			if($ver>$beser['ver']){
				$del="DELETE FROM {$_M['table']['applist']} WHERE no={$this->appNo}";
				DB::query($del);
			}
			
			if( DB::counter($_M['table']['applist'], "WHERE no={$this->appNo}", "*")<=0 ){
				$query = "INSERT INTO {$_M['table']['applist']} (
							no, 
							ver,
							m_name,
							m_class,
							m_action,
							appname,
							info,
							addtime,
							updatetime
							) VALUES (
							'{$no}', 
							'{$ver}',
							'{$m_name}',
							'{$m_class}',
							'{$m_action}',
							'{$appname}',
							'{$info}',
							'{$addtime}',
							'{$updateime}'
				)";
				
				DB::query($query);
				
			
			}
			
			
			
		}
}
?>