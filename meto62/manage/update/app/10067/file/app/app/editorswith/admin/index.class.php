<?php

defined('IN_MET') or exit('No permission');

load::sys_class('admin');

class index extends admin {
	
    public function __construct() {
    	
		global $_M;
		
		parent::__construct();
    }
	
	
	
	public function doindex() {
		
	 	global $_M;
		
		$query = "SELECT * FROM {$_M[table][applist]} WHERE info='editor'";
	    $editorlist = DB::get_all($query);
	
		require $this->template('own/index');
	}
	

	 public function doeditorsave(){
            	global $_M;

           $query = "UPDATE {$_M[table][config]} set value= '{$_M[form][editor]}' WHERE name='met_editor'";
           DB::query($query);

           turnover("{$_M[url][own_form]}&a=doindex");

    }
	
		
}
	
	
?>