<?php
defined('IN_MET') or exit ('No permission');

load::sys_class('admin');
load::sys_class('nav.class.php');
load::sys_func('file');

class uninstall extends admin {
    
    private $no;    //应用NO值
    private $m_name;  //应用文件夹
    
    public function __construct() {
        global $_M;
        parent::__construct();
        $this->no   = $_M['form']['no'];
    }

    public function dodel() {
        global $_M;
        //准备一些提前删除的内容
        $file   = $this->sqlone('applist');
        $this->m_name = $file['m_name'];
        //删除固定的app表
        $this->appsql();
        //删除应用文件
        $this->delfile();
    }


    //删除固定的app表
    private function appsql() {
        global $_M;
        //删除栏目接口表
        $this->delsql('ifcolumn');
        
        //删除应用生成文件所调用事件的信息表
        $this->delsql('ifcolumn_addfile');
        
        //删除会员侧导航信息表
        $this->delsql('ifmember_left');
        
        //删除应用插件表
        $this->delsql('app_plugin');

        //删除网站后台栏目信息表
        $where  = "field='{$this->no}'";
        $this->delsql('admin_column',$where);
        
        //删除网站栏目信息表
        $where  = "module='{$this->no}'";
        $this->delsql('column',$where);
        
        //删除语言表
        $where  = "app='{$this->no}'";
        $this->delsql('language',$where);
        
        //删除应用注册表
        $this->delsql('applist');
    }

    //删除应用文件夹
    private function delfile() {
        deldir('../app/app/'.$this->m_name);
    }
    
    //公共查询方法
    private function sqlone($tname,$where = '') {
        global $_M;
        $table  = $_M['table'][$tname];
        if(!$where){
            $where  = "no='{$this->no}'";
        }
        return DB::get_one("select * from {$table} where {$where}");
    }
    
    //公共删除数据
    private function delsql($tname,$where = '') {
        global $_M;
        $table  = $_M['table'][$tname];
        if(!$where){
            $where  = "no='{$this->no}'";
        }
        DB::query("DELETE FROM {$table} WHERE {$where}");
    }
}
?>