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

use common\util\File;
use think\log;
use think\Image;
use think\Request;
use think\Db;

/**
 * Class UeditorController
 * @package admin\Controller
 */
class Ueditor extends Base
{
    private $sub_name = array('date', 'Ymd');
    private $savePath = 'allimg/';
    private $fileExt = 'jpg,png,gif,jpeg,bmp,ico';
    private $nowFileName = '';

    public function __construct()
    {
        parent::__construct();
        
        //header('Access-Control-Allow-Origin: http://www.baidu.com'); //設定http://www.baidu.com允許跨域訪問
        //header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With'); //設定允許的跨域header
        
        date_default_timezone_set("Asia/Shanghai");
        
        $this->savePath = input('savepath','allimg').'/';

        $this->nowFileName = input('nowfilename', '');
        if (empty($this->nowFileName)) {
            $this->nowFileName = md5(time().uniqid(mt_rand(), TRUE));
        }
        
        error_reporting(E_ERROR | E_WARNING);
        
        header("Content-Type: text/html; charset=utf-8");

        $image_type = tpCache('basic.image_type');
        $this->fileExt = !empty($image_type) ? str_replace('|', ',', $image_type) : $this->fileExt;
    }
    
    public function index(){
        
        $CONFIG2 = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents("./public/plugins/Ueditor/php/config.json")), true);
        $action = $_GET['action'];
        
        switch ($action) {
            case 'config':
                $result =  json_encode($CONFIG2);
                break;
            /* 上傳圖片 */
            case 'uploadimage':
                $fieldName = $CONFIG2['imageFieldName'];
                $result = $this->upFile($fieldName);
                break;
            /* 上傳塗鴉 */
            case 'uploadscrawl':
                $config = array(
                    "pathFormat" => $CONFIG2['scrawlPathFormat'],
                    "maxSize" => $CONFIG2['scrawlMaxSize'],
                    "allowFiles" => $CONFIG2['scrawlAllowFiles'],
                    "oriName" => "scrawl.png"
                );
                $fieldName = $CONFIG2['scrawlFieldName'];
                $base64 = "base64";
                $result = $this->upBase64($config,$fieldName);
                break;
            /* 上傳視訊 */
            case 'uploadvideo':
                $fieldName = $CONFIG2['videoFieldName'];
                $result = $this->upFile($fieldName);
                break;
            /* 上傳檔案 */
            case 'uploadfile':
                $fieldName = $CONFIG2['fileFieldName'];
                $result = $this->upFile($fieldName);
                break;
            /* 列出圖片 */
            case 'listimage':
                $allowFiles = $CONFIG2['imageManagerAllowFiles'];
                $listSize = $CONFIG2['imageManagerListSize'];
                $path = $CONFIG2['imageManagerListPath'];
                $get =$_GET;
                $result =$this->fileList($allowFiles,$listSize,$get);
                break;
            /* 列出檔案 */
            case 'listfile':
                $allowFiles = $CONFIG2['fileManagerAllowFiles'];
                $listSize = $CONFIG2['fileManagerListSize'];
                $path = $CONFIG2['fileManagerListPath'];
                $get = $_GET;
                $result = $this->fileList($allowFiles,$listSize,$get);
                break;
            /* 抓取遠端檔案 */
            case 'catchimage':
                $config = array(
                    "pathFormat" => $CONFIG2['catcherPathFormat'],
                    "maxSize" => $CONFIG2['catcherMaxSize'],
                    "allowFiles" => $CONFIG2['catcherAllowFiles'],
                    "oriName" => "remote.png"
                );
                $fieldName = $CONFIG2['catcherFieldName'];
                /* 抓取遠端圖片 */
                $list = array();
                isset($_POST[$fieldName]) ? $source = $_POST[$fieldName] : $source = $_GET[$fieldName];
                
                foreach($source as $imgUrl){
                    $info = json_decode($this->saveRemote($config,$imgUrl),true);
                    array_push($list, array(
                        "state" => $info["state"],
                        "url" => $info["url"],
                        "size" => $info["size"],
                        "title" => htmlspecialchars($info["title"]),
                        "original" => str_replace("&amp;", "&", htmlspecialchars($info["original"])),
                        // "source" => htmlspecialchars($imgUrl)
                        "source" => str_replace("&amp;", "&", htmlspecialchars($imgUrl))
                    ));
                }

                $result = json_encode(array(
                    'state' => !empty($list) ? 'SUCCESS':'ERROR',
                    'list' => $list
                ));
                break;
            default:
                $result = json_encode(array(
                    'state' => '請求地址出錯'
                ));
                break;
        }

        /* 輸出結果 */
        if(isset($_GET["callback"])){
            if(preg_match("/^[\w_]+$/", $_GET["callback"])){
                echo htmlspecialchars($_GET["callback"]).'('.$result.')';
            }else{
                echo json_encode(array(
                    'state' => 'callback參數不合法'
                ));
            }
        }else{
            echo $result;
        }
    }
    
    //上傳檔案
    private function upFile($fieldName){
        $image_upload_limit_size = intval(tpCache('basic.file_size') * 1024 * 1024);
        $file = request()->file($fieldName);
        if(empty($file)){
            return json_encode(['state' =>'ERROR，請上傳檔案']);
        }
        $error = $file->getError();
        if(!empty($error)){
            return json_encode(['state' =>$error]);
        }
        $result = $this->validate(
            ['file' => $file], 
            ['file'=>'fileSize:'.$image_upload_limit_size],
            ['file.fileSize' => '上傳檔案過大']
        );
        if (true !== $result || empty($file)) {
            $state = "ERROR" . $result;
            return json_encode(['state' =>$state]);
        }

        $ossConfig = tpCache('oss');
        if ($ossConfig['oss_switch']) {
            //商品圖片可選擇存放在oss
            $savePath = $this->savePath.date('Ymd/');
            $object = UPLOAD_PATH.$savePath.md5(getTime().uniqid(mt_rand(), TRUE)).'.'.pathinfo($file->getInfo('name'), PATHINFO_EXTENSION);
            $ossClient = new \app\common\logic\OssLogic;
            $return_url = $ossClient->uploadFile($file->getRealPath(), $object);
            if (!$return_url) {
                $data = array('state' => 'ERROR'.$ossClient->getError());
            } else {
                $data = array(
                    'state'     => 'SUCCESS',
                    'url'       => $return_url,
                    'title'     => $file->getInfo('name'),
                    'original'  => $file->getInfo('name'),
                    'type'      => $file->getInfo('type'),
                    'size'      => $file->getInfo('size'),
                );
            }
            @unlink($file->getRealPath());
            return json_encode($data);
        } else {
            // 移動到框架應用根目錄/public/uploads/ 目錄下
            $this->savePath = $this->savePath.date('Ymd/');
            // 使用自定義的檔案儲存規則
            $info = $file->rule(function ($file) {
                return  md5(mt_rand());
            })->move(UPLOAD_PATH.$this->savePath);
        }
        
        if($info){
            $data = array(
                'state' => 'SUCCESS',
                'url' => '/'.UPLOAD_PATH.$this->savePath.$info->getSaveName(),
                'title' => $info->getSaveName(),
                'original' => $info->getSaveName(),
                'type' => '.' . $info->getExtension(),
                'size' => $info->getSize(),
            );

            //圖片加水印
            if($data['state'] == 'SUCCESS'){
                $file_type = $file->getInfo('type');
                $file_ext = pathinfo($file->getInfo('name'), PATHINFO_EXTENSION);
                $fileextArr = explode(',', $this->fileExt);
                if (stristr($file_type, 'image') && 'ico' != $file_ext) {
                    $imgresource = ".".$data['url'];
                    $image = \think\Image::open($imgresource);
                    $water = tpCache('water');
                    $return_data['mark_type'] = $water['mark_type'];
                    if($water['is_mark']==1 && $image->width()>$water['mark_width'] && $image->height()>$water['mark_height']){
                        if($water['mark_type'] == 'text'){
                            //$image->text($water['mark_txt'],ROOT_PATH.'public/static/common/font/hgzb.ttf',20,'#000000',9)->save($imgresource);
                            $ttf = ROOT_PATH.'public/static/common/font/hgzb.ttf';
                            if (file_exists($ttf)) {
                                $size = $water['mark_txt_size'] ? $water['mark_txt_size'] : 30;
                                $color = $water['mark_txt_color'] ?: '#000000';
                                if (!preg_match('/^#[0-9a-fA-F]{6}$/', $color)) {
                                    $color = '#000000';
                                }
                                $transparency = intval((100 - $water['mark_degree']) * (127/100));
                                $color .= dechex($transparency);
                                $image->open($imgresource)->text($water['mark_txt'], $ttf, $size, $color, $water['mark_sel'])->save($imgresource);
                                $return_data['mark_txt'] = $water['mark_txt'];
                            }
                        }else{
                            /*支援子目錄*/
                            $water['mark_img'] = preg_replace('#^(/[/\w]+)?(/public/upload/|/uploads/)#i', '$2', $water['mark_img']); // 支援子目錄
                            /*--end*/
                            //$image->water(".".$water['mark_img'],9,$water['mark_degree'])->save($imgresource);
                            $waterPath = "." . $water['mark_img'];
                            if (eyPreventShell($waterPath) && file_exists($waterPath)) {
                                $quality = $water['mark_quality'] ? $water['mark_quality'] : 80;
                                $waterTempPath = dirname($waterPath).'/temp_'.basename($waterPath);
                                $image->open($waterPath)->save($waterTempPath, null, $quality);
                                $image->open($imgresource)->water($waterTempPath, $water['mark_sel'], $water['mark_degree'])->save($imgresource);
                                @unlink($waterTempPath);
                            }
                        }
                    }
                }
            }

            $data['url'] = ROOT_DIR.$data['url']; // 支援子目錄
        }else{
            $data = array('state' => 'ERROR'.$info->getError());
        }
        return json_encode($data);
    }

    //列出圖片
    private function fileList($allowFiles,$listSize,$get){
        $dirname = './'.UPLOAD_PATH;
        $allowFiles = substr(str_replace(".","|",join("",$allowFiles)),1);
        /* 獲取參數 */
        $size = isset($get['size']) ? htmlspecialchars($get['size']) : $listSize;
        $start = isset($get['start']) ? htmlspecialchars($get['start']) : 0;
        $end = $start + $size;
        /* 獲取檔案列表 */
        $path = $dirname;
        $files = $this->getFiles($path,$allowFiles);
        if(empty($files)){
            return json_encode(array(
                "state" => "no match file",
                "list" => array(),
                "start" => $start,
                "total" => count($files)
            ));
        }
        /* 獲取指定範圍的列表 */
        $len = count($files);
        for($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--){
            $list[] = $files[$i];
        }

        /* 返回數據 */
        $result = json_encode(array(
            "state" => "SUCCESS",
            "list" => $list,
            "start" => $start,
            "total" => count($files)
        ));

        return $result;
    }

    /*
     * 遍歷獲取目錄下的指定型別的檔案
     * @param $path
     * @param array $files
     * @return array
    */
    private function getFiles($path,$allowFiles,&$files = array()){
        if(!is_dir($path)) return null;
        if(substr($path,strlen($path)-1) != '/') $path .= '/';
        $handle = opendir($path);
            
        while(false !== ($file = readdir($handle))){
            if($file != '.' && $file != '..'){
                $path2 = $path.$file;
                if(is_dir($path2)){
                    $this->getFiles($path2,$allowFiles,$files);
                }else{
                    if(preg_match("/\.(".$allowFiles.")$/i",$file)){
                        $files[] = array(
                            'url' => substr($path2,1),
                            'mtime' => filemtime($path2)
                        );
                    }
                }
            }
        }       
        return $files;
    }

    //抓取遠端圖片
    private function saveRemote($config,$fieldName){
        $imgUrl = htmlspecialchars($fieldName);
        $imgUrl = str_replace("&amp;","&",$imgUrl);

        //http開頭驗證
        if(strpos($imgUrl,"http") !== 0){
            $data=array(
                'state' => '鏈接不是http鏈接',
            );
            return json_encode($data);
        }
        //獲取請求頭並檢測死鏈
        $heads = get_headers($imgUrl);
        if(!(stristr($heads[0],"200") && stristr($heads[0],"OK"))){
            $data=array(
                'state' => '鏈接不可用',
            );
            return json_encode($data);
        }
        //格式驗證(副檔名驗證和Content-Type驗證)
        if(preg_match("/^http(s?):\/\/mmbiz.qpic.cn\/(.*)/", $imgUrl) != 1){
            $fileType = strtolower(strrchr($imgUrl,'.'));
            if(!in_array($fileType,$config['allowFiles']) || (isset($heads['Content-Type']) && stristr($heads['Content-Type'],"image"))){
                $data=array(
                    'state' => '鏈接contentType不正確',
                );
                return json_encode($data);
            }
        }

        //打開輸出緩衝區並獲取遠端圖片
        ob_start();
        $context = stream_context_create(
            array('http' => array(
                'follow_location' => false // don't follow redirects
            ))
        );
        readfile($imgUrl,false,$context);
        $img = ob_get_contents();
        ob_end_clean();
        preg_match("/[\/]([^\/]*)[\.]?[^\.\/]*$/",$imgUrl,$m);

        $dirname = './'.UPLOAD_PATH.'ueditor/'.date('Ymd/');
        $file['oriName'] = $m ? $m[1] : "";
        $file['filesize'] = strlen($img);
        $file['ext'] = strtolower(strrchr($config['oriName'],'.'));
        $file['name'] = uniqid().$file['ext'];
        $file['fullName'] = $dirname.$file['name'];
        $fullName = $file['fullName'];

        //檢查檔案大小是否超出限制
        if($file['filesize'] >= ($config["maxSize"])){
            $data=array(
                'state' => '檔案大小超出網站限制',
            );
            return json_encode($data);
        }

        //建立目錄失敗
        if(!file_exists($dirname) && !mkdir($dirname,0777,true)){
            $data=array(
                'state' => '目錄建立失敗',
            );
            return json_encode($data);
        }else if(!is_writeable($dirname)){
            $data=array(
                'state' => '目錄沒有寫許可權',
            );
            return json_encode($data);
        }

        //移動檔案
        if(!(file_put_contents($fullName, $img) && file_exists($fullName))){ //移動失敗
            $data=array(
                'state' => '寫入檔案內容錯誤',
            );
            return json_encode($data);
        }else{ //移動成功
            $data=array(
                'state' => 'SUCCESS',
                'url' => ROOT_DIR.substr($file['fullName'],1), // 支援子目錄
                'title' => $file['name'],
                'original' => $file['oriName'],
                'type' => $file['ext'],
                'size' => $file['filesize'],
            );

            $ossConfig = tpCache('oss');
            if ($ossConfig['oss_switch']) {
                //圖片可選擇存放在oss
                $savePath = $this->savePath.date('Ymd/');
                $object = UPLOAD_PATH.$savePath.md5(getTime().uniqid(mt_rand(), TRUE)).'.'.pathinfo($data['url'], PATHINFO_EXTENSION);
                $getRealPath = ltrim($data['url'], '/');
                $ossClient = new \app\common\logic\OssLogic;
                $return_url = $ossClient->uploadFile($getRealPath, $object);
                if (!$return_url) {
                    $state = "ERROR" . $ossClient->getError();
                    $return_url = '';
                } else {
                    $state = "SUCCESS";
                }
                @unlink($getRealPath);
                $data['url'] = $return_url;
            }
        }
        return json_encode($data);
    }

    /*
     * 處理base64編碼的圖片上傳
     * 例如：塗鴉圖片上傳
    */
    private function upBase64($config,$fieldName){
        $base64Data = $_POST[$fieldName];
        $img = base64_decode($base64Data);

        $dirname = './'.UPLOAD_PATH.'ueditor/'.date('Ymd/');
        $file['filesize'] = strlen($img);
        $file['oriName'] = $config['oriName'];
        $file['ext'] = strtolower(strrchr($config['oriName'],'.'));
        $file['name'] = uniqid().$file['ext'];
        $file['fullName'] = $dirname.$file['name'];
        $fullName = $file['fullName'];

        //檢查檔案大小是否超出限制
        if($file['filesize'] >= ($config["maxSize"])){
            $data=array(
                'state' => '檔案大小超出網站限制',
            );
            return json_encode($data);
        }

        //建立目錄失敗
        if(!file_exists($dirname) && !mkdir($dirname,0777,true)){
            $data=array(
                'state' => '目錄建立失敗',
            );
            return json_encode($data);
        }else if(!is_writeable($dirname)){
            $data=array(
                'state' => '目錄沒有寫許可權',
            );
            return json_encode($data);
        }

        //移動檔案
        if(!(file_put_contents($fullName, $img) && file_exists($fullName))){ //移動失敗
            $data=array(
                'state' => '寫入檔案內容錯誤',
            );
        }else{ //移動成功          
            $data=array(
                'state' => 'SUCCESS',
                'url' => substr($file['fullName'],1),
                'title' => $file['name'],
                'original' => $file['oriName'],
                'type' => $file['ext'],
                'size' => $file['filesize'],
            );
        }
        
        return json_encode($data);
    }

    /**
     * @function imageUp
     */
    public function imageUp()
    {
        if (!IS_POST) {
            $return_data['state'] = '非法上傳';
            respose($return_data,'json');
        }
        
        $image_upload_limit_size = intval(tpCache('basic.file_size') * 1024 * 1024);
        // 上傳圖片框中的描述表單名稱，
        $pictitle = input('pictitle');
        $dir = input('dir');
        $title = htmlspecialchars($pictitle , ENT_QUOTES);        
        $path = htmlspecialchars($dir, ENT_QUOTES);
        //$input_file ['upfile'] = $info['Filedata'];  一個是上傳外掛裡面來的, 另外一個是 文章編輯器裡面來的
        // 獲取表單上傳檔案
        $file = request()->file('file');
        if(empty($file))
            $file = request()->file('upfile');    

        // ico圖片檔案不進行驗證
        if (pathinfo($file->getInfo('name'), PATHINFO_EXTENSION) != 'ico') {
            $result = $this->validate(
                ['file' => $file], 
                ['file'=>'image|fileSize:'.$image_upload_limit_size.'|fileExt:'.$this->fileExt],
                ['file.image' => '上傳檔案必須為圖片','file.fileSize' => '上傳檔案過大','file.fileExt'=>'上傳檔案後綴名必須為'.$this->fileExt]                
               );
        } else {
            $result = true;
        }

        /*驗證圖片一句話木馬*/
        $imgstr = @file_get_contents($file->getInfo('tmp_name'));
        if (false !== $imgstr && preg_match('#<\?php#i', $imgstr)) {
            $result = '上傳圖片不合格';
        }
        /*--end*/

        if (true !== $result || empty($file)) {
            $state = "ERROR：" . $result;
        } else {
            if ('adminlogo/' == $this->savePath) {
                $savePath = 'public/static/admin/logo/';
            } else {
                $savePath = UPLOAD_PATH.$this->savePath.date('Ymd/');
            }
            $ossConfig = tpCache('oss');
            if ($ossConfig['oss_switch']) {
                //商品圖片可選擇存放在oss
                $object = $savePath.md5(getTime().uniqid(mt_rand(), TRUE)).'.'.pathinfo($file->getInfo('name'), PATHINFO_EXTENSION);
                $ossClient = new \app\common\logic\OssLogic;
                $return_url = $ossClient->uploadFile($file->getRealPath(), $object);
                if (!$return_url) {
                    $state = "ERROR" . $ossClient->getError();
                    $return_url = '';
                } else {
                    $state = "SUCCESS";
                }
                @unlink($file->getRealPath());
            } else {
                // 移動到框架應用根目錄/public/uploads/ 目錄下
                $info = $file->rule(function ($file) {    
                return  md5(mt_rand()); // 使用自定義的檔案儲存規則
                })->move($savePath);
                if ($info) {
                    $state = "SUCCESS";
                } else {
                    $state = "ERROR" . $file->getError();
                }
                $return_url = '/'.$savePath.$info->getSaveName();
            }
            $return_data['url'] = ROOT_DIR.$return_url; // 支援子目錄
        }
        
        if($state == 'SUCCESS' && pathinfo($file->getInfo('name'), PATHINFO_EXTENSION) != 'ico'){
            if(true || $this->savePath=='news/'){ // 新增水印
                $imgresource = ".".$return_url;
                $image = \think\Image::open($imgresource);
                $water = tpCache('water');
                $return_data['mark_type'] = $water['mark_type'];
                if($water['is_mark']==1 && $image->width()>$water['mark_width'] && $image->height()>$water['mark_height']){
                    if($water['mark_type'] == 'text'){
                        //$image->text($water['mark_txt'],ROOT_PATH.'public/static/common/font/hgzb.ttf',20,'#000000',9)->save($imgresource);
                        $ttf = ROOT_PATH.'public/static/common/font/hgzb.ttf';
                        if (file_exists($ttf)) {
                            $size = $water['mark_txt_size'] ? $water['mark_txt_size'] : 30;
                            $color = $water['mark_txt_color'] ?: '#000000';
                            if (!preg_match('/^#[0-9a-fA-F]{6}$/', $color)) {
                                $color = '#000000';
                            }
                            $transparency = intval((100 - $water['mark_degree']) * (127/100));
                            $color .= dechex($transparency);
                            $image->open($imgresource)->text($water['mark_txt'], $ttf, $size, $color, $water['mark_sel'])->save($imgresource);
                            $return_data['mark_txt'] = $water['mark_txt'];
                        }
                    }else{
                        /*支援子目錄*/
                        $water['mark_img'] = preg_replace('#^(/[/\w]+)?(/public/upload/|/uploads/)#i', '$2', $water['mark_img']); // 支援子目錄
                        /*--end*/
                        //$image->water(".".$water['mark_img'],9,$water['mark_degree'])->save($imgresource);
                        $waterPath = "." . $water['mark_img'];
                        if (eyPreventShell($waterPath) && file_exists($waterPath)) {
                            $quality = $water['mark_quality'] ? $water['mark_quality'] : 80;
                            $waterTempPath = dirname($waterPath).'/temp_'.basename($waterPath);
                            $image->open($waterPath)->save($waterTempPath, null, $quality);
                            $image->open($imgresource)->water($waterTempPath, $water['mark_sel'], $water['mark_degree'])->save($imgresource);
                            @unlink($waterTempPath);
                        }
                    }
                }
            }
        }
        $return_data['title'] = $title;
        $return_data['original'] = ''; // 這裡好像沒啥用 暫時註釋起來
        $return_data['state'] = $state;
        $return_data['path'] = $path;

        // 是否開啟七牛云外掛
        $data = Db::name('weapp')->where('code','Qiniuyun')->field('status')->find();
        if (!empty($data) && 1 == $data['status']) {
            // 同步圖片到七牛云
            $return_data['url'] = SynchronizeQiniu($return_data['url']);
        }

        respose($return_data,'json');
    }
    
    /**
     * app檔案上傳
     */
    public function appFileUp()
    {      
        $image_upload_limit_size = intval(tpCache('basic.file_size') * 1024 * 1024);
        $path = UPLOAD_PATH.'soft/'.date('Ymd/');
        if (!file_exists($path)) {
            mkdir($path);
        }

        //$input_file  ['upfile'] = $info['Filedata'];  一個是上傳外掛裡面來的, 另外一個是 文章編輯器裡面來的
        // 獲取表單上傳檔案
        $file = request()->file('Filedata');
        if (empty($file)) {
            $file = request()->file('upfile');    
        }
        
        $result = $this->validate(
            ['file2' => $file], 
            ['file2'=>'fileSize:'.$image_upload_limit_size.'|fileExt:apk,ipa,pxl,deb'],
            ['file2.fileSize' => '上傳檔案過大', 'file2.fileExt' => '上傳檔案後綴名必須為：apk,ipa,pxl,deb']                    
           );
        if (true !== $result || empty($file)) {            
            $state = "ERROR" . $result;
        } else {
            $info = $file->rule(function ($file) {    
                return date('YmdHis_').input('Filename'); // 使用自定義的檔案儲存規則
            })->move($path);
            if ($info) {
                $state = "SUCCESS";                         
            } else {
                $state = "ERROR" . $file->getError();
            }
            $return_data['url'] = $path.$info->getSaveName();            
        }
        
        $return_data['title'] = 'app檔案';
        $return_data['original'] = ''; // 這裡好像沒啥用 暫時註釋起來
        $return_data['state'] = $state;
        $return_data['path'] = $path;        

        respose($return_data);
    }
    
    /**
     * 資料檔案上傳
     */
    public function downFileUp()
    {
        // ini_set('upload_max_filesize', '500M');
        // ini_set('post_max_size', '500M');

        $this->downFileUpMd5();
        exit;

        // Make sure file is not cached (as it happens for example on iOS devices)
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        // Support CORS
        // header("Access-Control-Allow-Origin: *");
        // other CORS headers if any...
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit; // finish preflight CORS requests here
        }


        if ( !empty($_REQUEST[ 'debug' ]) ) {
            $random = rand(0, intval($_REQUEST[ 'debug' ]) );
            if ( $random === 0 ) {
                header("HTTP/1.0 500 Internal Server Error");
                exit;
            }
        }

        // 5 minutes execution time
        @set_time_limit(5 * 60);

        // Settings
        // $targetDir = ini_get("upload_tmp_dir") . '/' . "plupload";
        $targetDir = UPLOAD_PATH.trim($this->savePath, '/').'_tmp/'.date('Ymd');
        $uploadDir = UPLOAD_PATH.$this->savePath.date('Ymd');

        $cleanupTargetDir = true; // Remove old files
        $maxFileAge = 5 * 3600; // Temp file age in seconds

        // Create target dir
        if (!file_exists($targetDir)) {
            @tp_mkdir($targetDir);
        }

        // Create target dir
        if (!file_exists($uploadDir)) {
            @tp_mkdir($uploadDir);
        }

        // Get a file name
        $fileSize = 0;
        $fileName = '';
        $fileMime = '';
        if (isset($_REQUEST["name"])) {
            $fileMime = $_REQUEST["type"]; // application/x-zip-compressed
            $lastModifiedDate = !empty($_REQUEST["lastModifiedDate"]) ? strtotime($_REQUEST["lastModifiedDate"]) : time(); // Tue Apr 03 2018 09:42:55 GMT+0800 (中國標準時間)
            $fileSize = $_REQUEST["size"]; // 檔案大小
            $fileName = $_REQUEST["name"]; // include_new.zip
        } elseif (!empty($_FILES)) {
            $fileName = $_FILES["file"]["name"];
            $fileSize = $_FILES["file"]["size"]; // 檔案大小
            $fileMime = $_FILES["file"]["type"]; // application/x-zip-compressed
        } else {
            $fileName = uniqid("file_");
        }
        // 提取檔名後綴
        $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);
        // 提取出檔名，不包括副檔名
        $newfileName = preg_replace('/\.([^\.]+)$/', '', $fileName);
        // 過濾檔名.\/的特殊字元，防止利用上傳漏洞
        $newfileName = preg_replace('#(\\\|\/|\.)#i', '', $newfileName);
        // 過濾后的新檔名
        $fileName = $newfileName.'.'.$file_ext;

        $upload_limit_size = intval(tpCache('basic.file_size') * 1024 * 1024);
        if ($fileSize >= $upload_limit_size) {
            respose(array(
                'jsonrpc'   => '2.0',
                'error' => array(
                    'code'  => 90,
                    'msg'   => '上傳檔案過大',
                ),
                'id'    => 'id',
            ));
        }
        $file_type = tpCache('basic.file_type');
        $file_type = !empty($file_type) ? $file_type : 'zip|gz|rar|iso|doc|xsl|ppt|wps';
        $file_type_arr = explode('|', $file_type);
        if (!in_array($file_ext, $file_type_arr)) {
            respose(array(
                'jsonrpc'   => '2.0',
                'error' => array(
                    'code'  => 91,
                    'msg'   => '上傳檔案後綴不正確',
                ),
                'id'    => 'id',
            ));
        }

        if ($this->nowFileName == -1) {
            // 提取出檔名，不包括副檔名
            $this->nowFileName = preg_replace('/\.([^\.]+)$/', '', $fileName);
        }
        $filePath = $targetDir . '/' . $fileName;
        $uploadPath = $uploadDir . '/' . $this->nowFileName .'.'.pathinfo($fileName, PATHINFO_EXTENSION);

        // Chunking might be enabled
        $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;

        // Remove old temp files
        if ($cleanupTargetDir) {
            if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
                respose(array(
                    'jsonrpc'   => '2.0',
                    'error' => array(
                        'code'  => 100,
                        'msg'   => '打開臨時目錄失敗。',
                    ),
                    'id'    => 'id',
                ));
            }

            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

                // If temp file is current file proceed to the next
                if ($tmpfilePath == "{$filePath}_{$chunk}.part" || $tmpfilePath == "{$filePath}_{$chunk}.parttmp") {
                    continue;
                }

                // Remove temp file if it is older than the max age and is not the current file
                if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $maxFileAge)) {
                    @unlink($tmpfilePath);
                }
            }
            closedir($dir);
        }


        // Open temp file
        if (!$out = @fopen("{$filePath}_{$chunk}.parttmp", "wb")) {
            respose(array(
                'jsonrpc'   => '2.0',
                'error' => array(
                    'code'  => 102,
                    'msg'   => '打開輸出流失敗。',
                ),
                'id'    => 'id',
            ));
        }

        if (!empty($_FILES)) {
            if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                respose(array(
                    'jsonrpc'   => '2.0',
                    'error' => array(
                        'code'  => 103,
                        'msg'   => '移動上傳檔案失敗。',
                    ),
                    'id'    => 'id',
                ));
            }

            // Read binary input stream and append it to temp file
            if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                respose(array(
                    'jsonrpc'   => '2.0',
                    'error' => array(
                        'code'  => 101,
                        'msg'   => '打開輸入流失敗。',
                    ),
                    'id'    => 'id',
                ));
            }
        } else {
            if (!$in = @fopen("php://input", "rb")) {
                respose(array(
                    'jsonrpc'   => '2.0',
                    'error' => array(
                        'code'  => 101,
                        'msg'   => '打開輸入流失敗。',
                    ),
                    'id'    => 'id',
                ));
            }
        }

        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }

        @fclose($out);
        @fclose($in);

        rename("{$filePath}_{$chunk}.parttmp", "{$filePath}_{$chunk}.part");

        $index = 0;
        $done = true;
        for( $index = 0; $index < $chunks; $index++ ) {
            if ( !file_exists("{$filePath}_{$index}.part") ) {
                $done = false;
                break;
            }
        }
        if ( $done ) {
            if (!$out = @fopen($uploadPath, "wb")) {
                respose(array(
                    'jsonrpc'   => '2.0',
                    'error' => array(
                        'code'  => 102,
                        'msg'   => '打開輸出流失敗。',
                    ),
                    'id'    => 'id',
                ));
            }

            if ( flock($out, LOCK_EX) ) {
                for( $index = 0; $index < $chunks; $index++ ) {
                    if (!$in = @fopen("{$filePath}_{$index}.part", "rb")) {
                        break;
                    }

                    while ($buff = fread($in, 4096)) {
                        fwrite($out, $buff);
                    }

                    @fclose($in);
                    @unlink("{$filePath}_{$index}.part");
                }

                flock($out, LOCK_UN);
            }
            @fclose($out);
        }

        $path = '/'.$uploadPath;
        // Return Success JSON-RPC response
        respose(array(
            'jsonrpc'   => '2.0',
            'error' => array(
                'code'  => 0,
                'msg'   => '上傳成功',
                'path'    => $path,
                'mime'  => $fileMime,
            ),
            'id'    => 'id',
        ));
    }

    public function downFileUpMd5()
    {
        // Make sure file is not cached (as it happens for example on iOS devices)
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        // Support CORS
        // header("Access-Control-Allow-Origin: *");
        // other CORS headers if any...
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit; // finish preflight CORS requests here
        }

        if ( !empty($_REQUEST[ 'debug' ]) ) {
            $random = rand(0, intval($_REQUEST[ 'debug' ]) );
            if ( $random === 0 ) {
                header("HTTP/1.0 500 Internal Server Error");
                exit;
            }
        }

        // 5 minutes execution time
        @set_time_limit(5 * 60);

        // Settings
        // $targetDir = ini_get("upload_tmp_dir") . '/' . "plupload";
        $targetDir = UPLOAD_PATH.trim($this->savePath, '/').'_tmp/'.date('Ymd');
        $uploadDir = UPLOAD_PATH.$this->savePath.date('Ymd');

        $cleanupTargetDir = true; // Remove old files
        $maxFileAge = 5 * 3600; // Temp file age in seconds

        // Create target dir
        if (!file_exists($targetDir)) {
            @tp_mkdir($targetDir);
        }

        // Create target dir
        if (!file_exists($uploadDir)) {
            @tp_mkdir($uploadDir);
        }

        // Get a file name
        $fileSize = 0;
        $fileName = '';
        $fileMime = '';
        if (isset($_REQUEST["name"])) {
            $fileMime = $_REQUEST["type"]; // application/x-zip-compressed
            $lastModifiedDate = !empty($_REQUEST["lastModifiedDate"]) ? strtotime($_REQUEST["lastModifiedDate"]) : time(); // Tue Apr 03 2018 09:42:55 GMT+0800 (中國標準時間)
            $fileSize = $_REQUEST["size"]; // 檔案大小
            $fileName = $_REQUEST["name"]; // include_new.zip
        } elseif (!empty($_FILES)) {
            $fileName = $_FILES["file"]["name"];
            $fileSize = $_FILES["file"]["size"]; // 檔案大小
            $fileMime = $_FILES["file"]["type"]; // application/x-zip-compressed
        } else {
            $fileName = uniqid("file_");
        }
        // 提取檔名後綴
        $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);
        // 提取出檔名，不包括副檔名
        $newfileName = preg_replace('/\.([^\.]+)$/', '', $fileName);
        // 過濾檔名.\/的特殊字元，防止利用上傳漏洞
        $newfileName = preg_replace('#(\\\|\/|\.)#i', '', $newfileName);
        // 過濾后的新檔名
        $fileName = $newfileName.'.'.$file_ext;

        $upload_limit_size = intval(tpCache('basic.file_size') * 1024 * 1024);
        if ($fileSize >= $upload_limit_size) {
            respose(array(
                'jsonrpc'   => '2.0',
                'error' => array(
                    'code'  => 90,
                    'msg'   => '上傳檔案過大',
                ),
                'id'    => 'id',
            ));
        }
        $file_type = tpCache('basic.file_type');
        $file_type = !empty($file_type) ? $file_type : 'zip|gz|rar|iso|doc|xsl|ppt|wps';
        $file_type_arr = explode('|', $file_type);
        if (!in_array($file_ext, $file_type_arr)) {
            respose(array(
                'jsonrpc'   => '2.0',
                'error' => array(
                    'code'  => 91,
                    'msg'   => '上傳檔案後綴不正確',
                ),
                'id'    => 'id',
            ));
        }

        $uhash_list = @file(APP_PATH.MODULE_NAME.'/conf/md5list2.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $uhash_list = $uhash_list ? $uhash_list : array();

        if (isset($_REQUEST["md5"]) && array_search($_REQUEST["md5"], $uhash_list ) !== FALSE ) {
            respose(array(
                'jsonrpc'   => '2.0',
                'error' => array(
                    'code'  => 0,
                    'msg'   => 'md5',
                ),
                'id'    => 'id',
                'exist' => 1,
            ));
        }

        if ($this->nowFileName == -1) {
            // 提取出檔名，不包括副檔名
            $this->nowFileName = preg_replace('/\.([^\.]+)$/', '', $fileName);
        }

        $filePath = $targetDir . '/' . $fileName;
        $uploadPath = $uploadDir . '/' . $this->nowFileName .'.'.pathinfo($fileName, PATHINFO_EXTENSION);

        // Chunking might be enabled
        $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;

        // Remove old temp files
        if ($cleanupTargetDir) {
            if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
                respose(array(
                    'jsonrpc'   => '2.0',
                    'error' => array(
                        'code'  => 100,
                        'msg'   => '打開臨時目錄失敗。',
                    ),
                    'id'    => 'id',
                ));
            }

            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

                // If temp file is current file proceed to the next
                if ($tmpfilePath == "{$filePath}.part") {
                    continue;
                }

                // Remove temp file if it is older than the max age and is not the current file
                if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {
                    @unlink($tmpfilePath);
                }
            }
            closedir($dir);
        }


        // Open temp file
        if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
            respose(array(
                'jsonrpc'   => '2.0',
                'error' => array(
                    'code'  => 102,
                    'msg'   => '打開輸出流失敗。',
                ),
                'id'    => 'id',
            ));
        }

        if (!empty($_FILES)) {
            if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                respose(array(
                    'jsonrpc'   => '2.0',
                    'error' => array(
                        'code'  => 103,
                        'msg'   => '移動上載檔案失敗。',
                    ),
                    'id'    => 'id',
                ));
            }

            // Read binary input stream and append it to temp file
            if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                respose(array(
                    'jsonrpc'   => '2.0',
                    'error' => array(
                        'code'  => 101,
                        'msg'   => '打開輸入流失敗',
                    ),
                    'id'    => 'id',
                ));
            }
        } else {
            if (!$in = @fopen("php://input", "rb")) {
                respose(array(
                    'jsonrpc'   => '2.0',
                    'error' => array(
                        'code'  => 101,
                        'msg'   => '打開輸入流失敗',
                    ),
                    'id'    => 'id',
                ));
            }
        }

        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }

        @fclose($out);
        @fclose($in);

        // Check if file has been uploaded
        $uhash = '';
        $md5file = '';
        if (!$chunks || $chunk == $chunks - 1) {
            // Strip the temp .part suffix off
            rename("{$filePath}.part", $filePath);

            rename($filePath, $uploadPath);
            $uhash = $this->uhash($uploadPath);
            $md5file = md5_file($uploadPath);
            array_push($uhash_list, $uhash);
            $uhash_list = array_unique($uhash_list);
            file_put_contents(APP_PATH.MODULE_NAME.'/conf/md5list2.txt', join($uhash_list, "\n"));
        }

        $path = '/'.$uploadPath;
        // Return Success JSON-RPC response
        respose(array(
            'jsonrpc'   => '2.0',
            'error' => array(
                'code'  => 0,
                'msg'   => '上傳成功',
                'path'    => $path,
                'mime'  => $fileMime,
                'uhash' => $uhash,
                'md5file' => $md5file,
            ),
            'id'    => 'id',
        ));
    }

    public function uhash( $file ) {
        $fragment = 65536;

        $rh = fopen($file, 'rb');
        $size = filesize($file);

        $part1 = fread( $rh, $fragment );
        fseek($rh, $size-$fragment);
        $part2 = fread( $rh, $fragment);
        fclose($rh);

        return md5( $part1.$part2 );
    }
}