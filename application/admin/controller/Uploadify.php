<?php
/**
 * 易優CMS
 * ============================================================================
 * 版權所有 2016-2028 海南贊贊網路科技有限公司，並保留所有權利。
 * 網站地址: http://www.eyoucms.com
 * ----------------------------------------------------------------------------
 * 如果商業用途務必到官方購買正版授權, 以免引起不必要的法律糾紛.
 * ============================================================================
 * Author: 小虎哥 <1105415366@qq.com>
 * Date: 2018-4-3
 */
 
namespace app\admin\controller;

class Uploadify extends Base
{
    public $image_type = '';

    /**
     * 解構函式
     */
    function __construct() 
    {
        parent::__construct();
        $this->image_type = tpCache('basic.image_type');
        $this->image_type = !empty($this->image_type) ? str_replace('|', ',', $this->image_type) : 'jpg,gif,png,bmp,jpeg,ico';
    }

    public function upload()
    {
        $func = input('func');
        $path = input('path','allimg');
        $num = input('num/d', '1');
        $default_size = intval(tpCache('basic.file_size') * 1024 * 1024); // 單位為b
        $size = input('size/d'); // 單位為kb
        $size = empty($size) ? $default_size : $size*1024;
        $info = array(
            'num'=> $num,
            'title' => '',          
            'upload' =>url('Ueditor/imageUp',array('savepath'=>$path,'pictitle'=>'banner','dir'=>'images')),
            'fileList'=>url('Uploadify/fileList',array('path'=>$path)),
            'size' => $size,
            'type' => $this->image_type,
            'input' => input('input'),
            'func' => empty($func) ? 'undefined' : $func,
        );
        $this->assign('info',$info);
        return $this->fetch();
    }

    /**
     * 在彈出窗里的上傳圖片
     */
    public function upload_frame()
    {
        $func = input('func');
        $path = input('path','allimg');
        $num = input('num/d', '1');
        $default_size = intval(tpCache('basic.file_size') * 1024 * 1024); // 單位為b
        $size = input('size/d'); // 單位為kb
        $size = empty($size) ? $default_size : $size*1024;
        $info = array(
            'num'=> $num,
            'title' => '',          
            'upload' =>url('Ueditor/imageUp',array('savepath'=>$path,'pictitle'=>'banner','dir'=>'images')),
            'fileList'=>url('Uploadify/fileList',array('path'=>$path)),
            'size' => $size,
            'type' => $this->image_type,
            'input' => input('input'),
            'func' => empty($func) ? 'undefined' : $func,
        );
        $this->assign('info',$info);
        return $this->fetch();
    }

    /**
     * 後臺（產品）專用
     */
    public function upload_product()
    {
        $aid = input('aid/d');
        $func = input('func');
        $path = input('path','allimg');
        $num = input('num/d', '1');
        $default_size = intval(tpCache('basic.file_size') * 1024 * 1024); // 單位為b
        $size = input('size/d'); // 單位為kb
        $size = empty($size) ? $default_size : $size*1024;
        $field = array(
            'aid' => $aid,
            'num' => $num,
            'title' => '',          
            'upload' => url('Ueditor/imageUp',array('savepath'=>$path,'pictitle'=>'banner','dir'=>'images')),
            'fileList'=> url('Uploadify/fileList',array('path'=>$path)),
            'size' => $size,
            'type' => $this->image_type,
            'input' => input('input'),
            'func' => empty($func) ? 'undefined' : $func,
        );
        $this->assign('field',$field);
        return $this->fetch();
    }

    /**
     * 完整的上傳模板展示
     */
    public function upload_full()
    {
        $func = input('func');
        $path = input('path','allimg');
        $num = input('num/d', '1');
        $default_size = intval(tpCache('basic.file_size') * 1024 * 1024); // 單位為b
        $size = input('size/d'); // 單位為kb
        $size = empty($size) ? $default_size : $size*1024;
        $info = array(
            'num'=> $num,
            'title' => '',          
            'upload' =>url('Ueditor/imageUp',array('savepath'=>$path,'pictitle'=>'banner','dir'=>'images')),
            'fileList'=>url('Uploadify/fileList',array('path'=>$path)),
            'size' => $size,
            'type' => $this->image_type,
            'input' => input('input'),
            'func' => empty($func) ? 'undefined' : $func,
        );
        $this->assign('info',$info);
        return $this->fetch();
    }
    
    /*
     * 刪除上傳的圖片
     */
    public function delupload()
    {
        if (IS_POST) {
            $action = input('action','del');                
            $filename= input('filename/s');
            $filename= empty($filename) ? input('url') : $filename;
            $filename= str_replace('../','',$filename);
            $filename= trim($filename,'.');
            $filename= trim($filename,'/');
            if(eyPreventShell($filename) && $action=='del' && !empty($filename) && file_exists($filename)){
                $filetype = preg_replace('/^(.*)\.(\w+)$/i', '$2', $filename);
                $phpfile = strtolower(strstr($filename,'.php'));  //排除PHP檔案
                $size = getimagesize($filename);
                $fileInfo = explode('/',$size['mime']);
                if($fileInfo[0] != 'image' || $phpfile || !in_array($filetype, explode(',', config('global.image_ext')))){
                    exit;
                }
                if(@unlink($filename)){
                    echo 1;
                }else{
                    echo 0;
                }  
                exit;
            }
        }
    }
    
    public function fileList(){
        /* 判斷型別 */
        $type = input('type','Images');
        switch ($type){
            /* 列出圖片 */
            case 'Images' : $allowFiles = str_replace(',', '|', $this->image_type);break;
        
            case 'Flash' : $allowFiles = 'flash|swf';break;
        
            /* 列出檔案 */
            default : 
            {
                $file_type = tpCache('basic.file_type');
                $media_type = tpCache('basic.media_type');
                $allowFiles = $file_type.'|'.$media_type;
            }
        }

        $listSize = 102400000;
        
        $key = empty($_GET['key']) ? '' : $_GET['key'];
        
        /* 獲取參數 */
        $size = isset($_GET['size']) ? htmlspecialchars($_GET['size']) : $listSize;
        $start = isset($_GET['start']) ? htmlspecialchars($_GET['start']) : 0;
        $end = $start + $size;
        
        $path = input('path','allimg');
        if (1 == preg_match('#\.#', $path)) {
            echo json_encode(array(
                    "state" => "路徑不符合規範",
                    "list" => array(),
                    "start" => $start,
                    "total" => 0
            ));
            exit;
        }
        if ('adminlogo' == $path) {
            $path = 'public/static/admin/logo';
        } else {
            $path = UPLOAD_PATH.$path;
        }

        /* 獲取檔案列表 */
        $files = $this->getfiles($path, $allowFiles, $key);
        if (empty($files)) {
            echo json_encode(array(
                    "state" => "沒有相關檔案",
                    "list" => array(),
                    "start" => $start,
                    "total" => count($files)
            ));
            exit;
        }
        
        /* 獲取指定範圍的列表 */
        $len = count($files);
        for ($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--){
            $list[] = $files[$i];
        }
        
        /* 返回數據 */
        $result = json_encode(array(
                "state" => "SUCCESS",
                "list" => $list,
                "start" => $start,
                "total" => count($files)
        ));
        
        echo $result;
    }

    /**
     * 遍歷獲取目錄下的指定型別的檔案
     * @param $path
     * @param array $files
     * @return array
     */
    private function getfiles($path, $allowFiles, $key, &$files = array()){
        if (!is_dir($path)) return null;
        if(substr($path, strlen($path) - 1) != '/') $path .= '/';
        $handle = opendir($path);
        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..') {
                $path2 = $path . $file;
                if (is_dir($path2)) {
                    $this->getfiles($path2, $allowFiles, $key, $files);
                } else {
                    if (preg_match("/\.(".$allowFiles.")$/i", $file) && preg_match("/.*". $key .".*/i", $file)) {
                        $files[] = array(
                            'url'=> ROOT_DIR.'/'.$path2, // 支援子目錄
                            'name'=> $file,
                            'mtime'=> filemtime($path2)
                        );
                    }
                }
            }
        }
        return $files;
    }
}