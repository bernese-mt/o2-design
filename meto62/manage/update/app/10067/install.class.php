<?php
defined('IN_MET') or exit ('No permission');

load::sys_class('admin');
load::sys_class('nav.class.php');
load::sys_func('file');
class install  {

	 private $m_name = 'editorswith';

	public function __construct() {
		  
	}
	public function dosql() {
		global $_M;
		$no = 10067;
		
		$query = "select m_name = 'editorswith' from {$_M['table']['applist']} where no = '$no'";
		$stall = DB::get_one($query);
		
		
				
		if(!$stall){
				 
            $query = "UPDATE {$_M[table][applist]} set info= 'editor' WHERE m_name='ueditor'";
            DB::query($query);

			$query = "INSERT INTO {$_M['table']['applist']} SET no='$no',ver='1.0',m_name='editorswith',m_class='index',m_action='doindex',appname='markdown编辑器',info='用于切换到不同的编辑器'";
            DB::query($query);


			$query = "INSERT INTO {$_M['table']['applist']} SET no='1',ver='1.0',m_name='editormd',m_class='index',m_action='doindex',appname='markdown编辑器',info='editor',display='0'";
			DB::query($query);

		    $old = PATH_ALL_APP.$this->m_name.'/editormd';
            movedir($old,PATH_ALL_APP.'/editormd');
            
            if(file_exists(PATH_WEB.'app/system/admin/templates/web/theme/js/ckeditor.js')){
        	
      	     movefile(PATH_WEB.'app/app/editorswith/ckeditor.js',PATH_WEB.'app/system/admin/templates/web/theme/js/ckeditor.js', $overWrite =true);
        
          }

		
		}
		
	
	}
}
?>