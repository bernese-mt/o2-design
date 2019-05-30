<?php
defined('IN_MET') or exit ('No permission');

load::sys_class('admin');
load::sys_class('nav.class.php');
load::sys_func('file');

//需要手动安装，或者发给客户自己手动安装时请将标记① ②的注释去掉

class install //extends admin //①
{
    
    private $no;    //应用NO值
    private $m_name = 'apptool';    //应用文件
    private $ver = '1.0';    //应用文件

    public function __construct() {//②
        global $_M;
       // parent::__construct();
    }
    
    //安装应用
    public function dosql() {
        global $_M;
        $where  = "m_name='{$this->m_name}'";
        if($this->sqlone('applist',$where) != false){
           // echo '请勿重复安装';
           // return '请勿重复安装';
            exit;
        }
        do{
            $this->no = 10041;
            $stall = $this->sqlone('applist');
        } while ($stall != false);

        //开始执行程序[需要的东西可以在下面执行了]
        $this->appsql();
        //echo '安装成功';
        //return '安装成功';
    }
	
    //执行APP相关的表数据插入
    private function appsql() {
        global $_M;
        
        //注册应用
        $updatetime = time();
        $field = "no='{$this->no}',ver='{$this->ver}',m_name='{$this->m_name}',m_class='application',m_action='doindex',appname='应用安装助手',info='应用开发必备工具！能够快速安装应用，不必每次都要拼写安装URL进行安装。',addtime='{$updatetime}'";
        $this->addsql('applist',$field);
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

    //公共写入方法
    private function addsql($tname,$field) {
        global $_M;
        $table  = $_M['table'][$tname];
        DB::query("INSERT INTO {$table} SET {$field}");
    }
    
}
?>