<?php
/**
 * 批量处理<插件安装>
 * @package phpok\plugins
 * @作者 phpok.com
 * @版本 5.0.135
 * @授权 http://www.phpok.com/lgpl.html PHPOK开源授权协议：GNU Lesser General Public License
 * @时间 2018年12月21日 15时16分
**/
class install_pledit extends phpok_plugin
{
	public $me;
	public function __construct()
	{
		parent::plugin();
		$this->me = $this->_info();
	}
	
	/**
	 * 插件安装时，增加的扩展表单输出项，如果不使用，请删除这个方法
	**/
	public function index()
	{
		//return $this->_tpl('setting.html');
	}
	
	/**
	 * 插件安装时，保存扩展参数，如果不使用，请删除这个方法
	**/
	public function save()
	{
		$id = $this->_id();
		$ext = array();
		//$ext['扩展参数字段名'] = $this->get('表单字段名');
		$this->_save($ext,$id);
	}
	
	
}