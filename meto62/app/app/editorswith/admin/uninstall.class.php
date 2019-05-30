<?php
defined('IN_MET') or exit ('No permission');

load::sys_class('admin');
load::sys_class('nav.class.php');
load::sys_func('file');

class uninstall extends admin {
    
    
    public function dodel() {
          global $_M;

               $query = "delete from {$_M['table']['applist']} where m_name='editormd'";
                 DB::query($query);

   
               $query = "delete from {$_M['table']['applist']} where m_name='editorswith'";
                 DB::query($query);

               $query = "UPDATE {$_M[table][config]} set value= 'ueditor' WHERE name='met_editor'";
                 DB::query($query);

                 if(file_exists(PATH_ALL_APP.'/editormd')){

                       deldir(PATH_ALL_APP.'/editormd');

                   }

                 if(file_exists(PATH_ALL_APP.'/editorswith')){

                  deldir(PATH_ALL_APP.'/editorswith');

                   }
         
    }
  
}
?>