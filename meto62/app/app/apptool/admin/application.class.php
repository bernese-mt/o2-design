<?php

defined('IN_MET') or exit('No permission');

load::sys_class('admin');
load::sys_func('file');

//接口初始化
class application extends admin {
	
    private $clangs = ''; //返回的文字
    private $url; //返回URL
    private $appfile; //应用文件
    
    public function __construct() {
        global $_M;
        parent::__construct();
    }
    
    //ajaxURL
    private function ajaxurl() {
        global $_M;
        return $_M['url']['own_form'];
    }
    
    //公共查询方法
    private function sqlone($tname,$where = '') {
        global $_M;
        $table  = $_M['table'][$tname];
        if($where){
            $where  = 'where '.$where;
        }
        return DB::get_one("select * from {$table} {$where}");
    }
    
    //安装应用初始化
    public function doindex(){
        global $_M;
        $ajaxurl    = $this->ajaxurl();
        if($_M['form']['url']){
            $url    = '
                <dl><dt>安装提示</dt><dd style="color: #23b7e5;">
                   '.base64_decode($_M['form']['url']).'
                </dd></dl>';
        }
        $appfile    = $_M['form']['appfile'];
        require $this->template('own/application');
    }

    //进行执行安装
    public function doaddapp(){
        global $_M;
        $this->appfile    = $_M['form']['appfile'];
        $where  = "m_name='{$this->appfile}'";
        $applist = $this->sqlone('applist',$where);
        if($applist){
            $this->clangs = get_word($applist['appname']).' 应用已存在，请勿重复安装。';
            $this->turnover();
        }
        
        $file   = path_relative("app/app/{$this->appfile}/admin/install.class.php");
        require_once($file);
        if(get_parent_class('install')){
            $url  = $_M[url][site_admin].'index.php?lang='.$_M[langset].'&n='.$this->appfile.'&c=install&a=dosql';
            $url    = '<a href="'.$url.'" target="_blank">【点击URL安装新应用】'.$url.'</a>';
            $this->url  = urlencode(base64_encode($url));
            $this->clangs   = '点击URL安装新应用';
        }else{
            $install = new install();
            $this->clangs   = @$install->dosql();
            if($this->clangs){
                if($this->clangs == 'complete'){
                    $this->clangs   = '安装完成';
                }
            }else{
                $this->clangs   = '安装完成';
            }
            $this->url  = base64_encode($this->clangs);
        }
        $this->turnover();
    }
    
    //返回方法
    private function turnover() {
        global $_M;
        turnover($_M['url']['own_form'] . 'a=doindex&url='.$this->url.'&appfile='.$this->appfile, $this->clangs);
    }
	
    //应用安装文件名检测，查看是否存在
    public function doappfile(){
        global $_M;
        $file   = '../app/app/'.$_M['form']['appfile'];
        if(is_dir($file)){
            $file  = $file.'/admin/install.class.php';
            if(file_exists($file)){
                $where  = "m_name='{$_M['form']['appfile']}'";
                $applist = $this->sqlone('applist',$where);
                if($applist){
                    echo '0|'.get_word($applist['appname']).' 应用已存在，请勿重复安装。';
                }else{
                    echo '1|'.$_M['form']['appfile'].'可以正常安装。';
                }
            }else{
                echo '0|../app/app/'.$_M['form']['appfile'].'/ 下不存在install.class.php文件';
            }
        }else{
            echo '0|../app/app/ 下不存在文件夹 '.$_M['form']['appfile'];
        }
    }
}

?>