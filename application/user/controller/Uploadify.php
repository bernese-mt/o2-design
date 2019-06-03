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

namespace app\user\controller;

class Uploadify extends Base {

    private $image_type = '';
    private $sub_name = '';
    private $fileExt = 'jpg,png,gif,jpeg,bmp,ico';
    private $savePath = 'allimg/';
    private $upload_path = '';
    
    /**
     * 解構函式
     */
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Shanghai");
        $this->savePath = input('savepath/s','allimg').'/';
        error_reporting(E_ERROR | E_WARNING);
        header("Content-Type: text/html; charset=utf-8");
        
        $this->sub_name = date('Ymd/');
        $this->image_type = tpCache('basic.image_type');
        $this->image_type = !empty($this->image_type) ? str_replace('|', ',', $this->image_type) : 'jpg,gif,png,bmp,jpeg,ico';
        $this->upload_path = UPLOAD_PATH.'user/'.$this->users_id.'/';
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
            'upload' =>url('Uploadify/imageUp',array('savepath'=>$path,'pictitle'=>'banner','dir'=>'images')),
            'fileList'=>url('Uploadify/fileList',array('path'=>$path)),
            'size' => $size,
            'type' => $this->image_type,
            'input' => input('input'),
            'func' => empty($func) ? 'undefined' : $func,
        );
        $this->assign('info',$info);
        return $this->fetch('./application/user/template/uploadify/upload.htm');
    }
    
    /*
     * 刪除上傳的圖片
     */
    public function delupload()
    {
        if (IS_POST) {
            $action = input('post.action/s','del');                
            $filename= input('post.filename/s');
            $filename= empty($filename) ? input('url') : $filename;
            $filename= str_replace('../','',$filename);
            $filename= trim($filename,'.');
            $filename= trim($filename,'/');
            if(eyPreventShell($filename) && $action=='del' && !empty($filename) && file_exists($filename)){
                $fileArr = explode('/', $filename);
                if ($fileArr[2] != $this->users_id) {
                    return false;
                }
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
    
    // public function fileList(){
    //     /* 判斷型別 */
    //     $type = input('type','Images');
    //     switch ($type){
    //         /* 列出圖片 */
    //         case 'Images' : $allowFiles = str_replace(',', '|', $this->image_type);break;
        
    //         case 'Flash' : $allowFiles = 'flash|swf';break;
        
    //         /* 列出檔案 */
    //         default : 
    //         {
    //             $file_type = tpCache('basic.file_type');
    //             $media_type = tpCache('basic.media_type');
    //             $allowFiles = $file_type.'|'.$media_type;
    //         }
    //     }

    //     $listSize = 102400000;
        
    //     $key = empty($_GET['key']) ? '' : $_GET['key'];
        
    //     /* 獲取參數 */
    //     $size = isset($_GET['size']) ? htmlspecialchars($_GET['size']) : $listSize;
    //     $start = isset($_GET['start']) ? htmlspecialchars($_GET['start']) : 0;
    //     $end = $start + $size;
        
    //     $path = input('path','allimg');
    //     if (1 == preg_match('#\.#', $path)) {
    //         echo json_encode(array(
    //             "state" => "路徑不符合規範",
    //             "list" => array(),
    //             "start" => $start,
    //             "total" => 0
    //         ));
    //         exit;
    //     }
    //     $path = $this->upload_path.$path;

    //     /* 獲取檔案列表 */
    //     $files = $this->getfiles($path, $allowFiles, $key);
    //     if (empty($files)) {
    //         echo json_encode(array(
    //             "state" => "沒有相關檔案",
    //             "list" => array(),
    //             "start" => $start,
    //             "total" => count($files)
    //         ));
    //         exit;
    //     }
        
    //     /* 獲取指定範圍的列表 */
    //     $len = count($files);
    //     for ($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--){
    //         $list[] = $files[$i];
    //     }
        
    //     /* 返回數據 */
    //     $result = json_encode(array(
    //         "state" => "SUCCESS",
    //         "list" => $list,
    //         "start" => $start,
    //         "total" => count($files)
    //     ));
        
    //     echo $result;
    // }

    /**
     * 遍歷獲取目錄下的指定型別的檔案
     * @param $path
     * @param array $files
     * @return array
     */
    // private function getfiles($path, $allowFiles, $key, &$files = array()){
    //     if (!is_dir($path)) return null;
    //     if(substr($path, strlen($path) - 1) != '/') $path .= '/';
    //     $handle = opendir($path);
    //     while (false !== ($file = readdir($handle))) {
    //         if ($file != '.' && $file != '..') {
    //             $path2 = $path . $file;
    //             if (is_dir($path2)) {
    //                 $this->getfiles($path2, $allowFiles, $key, $files);
    //             } else {
    //                 if (preg_match("/\.(".$allowFiles.")$/i", $file) && preg_match("/.*". $key .".*/i", $file)) {
    //                     $files[] = array(
    //                         'url'=> ROOT_DIR.'/'.$path2, // 支援子目錄
    //                         'name'=> $file,
    //                         'mtime'=> filemtime($path2)
    //                     );
    //                 }
    //             }
    //         }
    //     }
    //     return $files;
    // }
    
    // public function index(){
        
    //     $CONFIG2 = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents("./public/plugins/Ueditor/php/config.json")), true);
    //     $action = $_GET['action'];
        
    //     switch ($action) {
    //         case 'config':
    //             $result =  json_encode($CONFIG2);
    //             break;
    //         /* 上傳圖片 */
    //         case 'uploadimage':
    //             $fieldName = $CONFIG2['imageFieldName'];
    //             $result = $this->imageUp();
    //             break;
    //         /* 上傳塗鴉 */
    //         case 'uploadscrawl':
    //             $config = array(
    //                 "pathFormat" => $CONFIG2['scrawlPathFormat'],
    //                 "maxSize" => $CONFIG2['scrawlMaxSize'],
    //                 "allowFiles" => $CONFIG2['scrawlAllowFiles'],
    //                 "oriName" => "scrawl.png"
    //             );
    //             $fieldName = $CONFIG2['scrawlFieldName'];
    //             $base64 = "base64";
    //             $result = $this->upBase64($config,$fieldName);
    //             break;
    //         /* 上傳視訊 */
    //         case 'uploadvideo':
    //             $fieldName = $CONFIG2['videoFieldName'];
    //             $result = $this->upFile($fieldName);
    //             break;
    //         /* 上傳檔案 */
    //         case 'uploadfile':
    //             $fieldName = $CONFIG2['fileFieldName'];
    //             $result = $this->upFile($fieldName);
    //             break;
    //         /* 列出圖片 */
    //         case 'listimage':
    //             $allowFiles = $CONFIG2['imageManagerAllowFiles'];
    //             $listSize = $CONFIG2['imageManagerListSize'];
    //             $path = $CONFIG2['imageManagerListPath'];
    //             $get = $_GET;
    //             $result =$this->fileList2($allowFiles,$listSize,$get);
    //             break;
    //         /* 列出檔案 */
    //         case 'listfile':
    //             $allowFiles = $CONFIG2['fileManagerAllowFiles'];
    //             $listSize = $CONFIG2['fileManagerListSize'];
    //             $path = $CONFIG2['fileManagerListPath'];
    //             $get = $_GET;
    //             $result = $this->fileList2($allowFiles,$listSize,$get);
    //             break;
    //         /* 抓取遠端檔案 */
    //         case 'catchimage':
    //             $config = array(
    //                 "pathFormat" => $CONFIG2['catcherPathFormat'],
    //                 "maxSize" => $CONFIG2['catcherMaxSize'],
    //                 "allowFiles" => $CONFIG2['catcherAllowFiles'],
    //                 "oriName" => "remote.png"
    //             );
    //             $fieldName = $CONFIG2['catcherFieldName'];
    //             /* 抓取遠端圖片 */
    //             $list = array();
    //             isset($_POST[$fieldName]) ? $source = $_POST[$fieldName] : $source = $_GET[$fieldName];
                
    //             foreach($source as $imgUrl){
    //                 $info = json_decode($this->saveRemote($config,$imgUrl),true);
    //                 array_push($list, array(
    //                     "state" => $info["state"],
    //                     "url" => $info["url"],
    //                     "size" => $info["size"],
    //                     "title" => htmlspecialchars($info["title"]),
    //                     "original" => str_replace("&amp;", "&", htmlspecialchars($info["original"])),
    //                     // "source" => htmlspecialchars($imgUrl)
    //                     "source" => str_replace("&amp;", "&", htmlspecialchars($imgUrl))
    //                 ));
    //             }

    //             $result = json_encode(array(
    //                 'state' => !empty($list) ? 'SUCCESS':'ERROR',
    //                 'list' => $list
    //             ));
    //             break;
    //         default:
    //             $result = json_encode(array(
    //                 'state' => '請求地址出錯'
    //             ));
    //             break;
    //     }

    //     /* 輸出結果 */
    //     if(isset($_GET["callback"])){
    //         if(preg_match("/^[\w_]+$/", $_GET["callback"])){
    //             echo htmlspecialchars($_GET["callback"]).'('.$result.')';
    //         }else{
    //             echo json_encode(array(
    //                 'state' => 'callback參數不合法'
    //             ));
    //         }
    //     }else{
    //         echo $result;
    //     }
    // }
    
    // //上傳檔案
    // private function upFile($fieldName){
    //     $image_upload_limit_size = intval(tpCache('basic.file_size') * 1024 * 1024);
    //     $file = request()->file($fieldName);
    //     if(empty($file)){
    //         return json_encode(['state' =>'ERROR，請上傳檔案']);
    //     }
    //     $error = $file->getError();
    //     if(!empty($error)){
    //         return json_encode(['state' =>$error]);
    //     }
    //     $result = $this->validate(
    //         ['file' => $file], 
    //         ['file'=>'fileSize:'.$image_upload_limit_size],
    //         ['file.fileSize' => '上傳檔案過大']
    //     );
    //     if (true !== $result || empty($file)) {
    //         $state = "ERROR" . $result;
    //         return json_encode(['state' =>$state]);
    //     }

    //     // 移動到框架應用根目錄/uploads/ 目錄下
    //     $savePath = $this->upload_path.$this->savePath.$this->sub_name;

    //     $ossConfig = tpCache('oss');
    //     if ($ossConfig['oss_switch']) {
    //         //商品圖片可選擇存放在oss
    //         $object = $savePath.md5(getTime().uniqid(mt_rand(), TRUE)).'.'.pathinfo($file->getInfo('name'), PATHINFO_EXTENSION);
    //         $ossClient = new \app\common\logic\OssLogic;
    //         $return_url = $ossClient->uploadFile($file->getRealPath(), $object);
    //         if (!$return_url) {
    //             $data = array('state' => 'ERROR'.$ossClient->getError());
    //         } else {
    //             $data = array(
    //                 'state'     => 'SUCCESS',
    //                 'url'       => $return_url,
    //                 'title'     => $file->getInfo('name'),
    //                 'original'  => $file->getInfo('name'),
    //                 'type'      => $file->getInfo('type'),
    //                 'size'      => $file->getInfo('size'),
    //             );
    //         }
    //         @unlink($file->getRealPath());
    //         return json_encode($data);
    //     } else {
    //         // 使用自定義的檔案儲存規則
    //         $info = $file->rule(function ($file) {
    //             return  md5(mt_rand());
    //         })->move($savePath);
    //     }
        
    //     if($info){
    //         $data = array(
    //             'state' => 'SUCCESS',
    //             'url' => '/'.$savePath.$info->getSaveName(),
    //             'title' => $info->getSaveName(),
    //             'original' => $info->getSaveName(),
    //             'type' => '.' . $info->getExtension(),
    //             'size' => $info->getSize(),
    //         );

    //         //圖片加水印
    //         if($data['state'] == 'SUCCESS'){
    //             $file_type = $file->getInfo('type');
    //             $file_ext = pathinfo($file->getInfo('name'), PATHINFO_EXTENSION);
    //             $fileextArr = explode(',', $this->fileExt);
    //             if (stristr($file_type, 'image') && 'ico' != $file_ext) {
    //                 $imgresource = ".".$data['url'];
    //                 $image = \think\Image::open($imgresource);
    //                 $water = tpCache('water');
    //                 $return_data['mark_type'] = $water['mark_type'];
    //                 if($water['is_mark']==1 && $image->width()>$water['mark_width'] && $image->height()>$water['mark_height']){
    //                     if($water['mark_type'] == 'text'){
    //                         //$image->text($water['mark_txt'],ROOT_PATH.'public/static/common/font/hgzb.ttf',20,'#000000',9)->save($imgresource);
    //                         $ttf = ROOT_PATH.'public/static/common/font/hgzb.ttf';
    //                         if (file_exists($ttf)) {
    //                             $size = $water['mark_txt_size'] ? $water['mark_txt_size'] : 30;
    //                             $color = $water['mark_txt_color'] ?: '#000000';
    //                             if (!preg_match('/^#[0-9a-fA-F]{6}$/', $color)) {
    //                                 $color = '#000000';
    //                             }
    //                             $transparency = intval((100 - $water['mark_degree']) * (127/100));
    //                             $color .= dechex($transparency);
    //                             $image->open($imgresource)->text($water['mark_txt'], $ttf, $size, $color, $water['mark_sel'])->save($imgresource);
    //                             $return_data['mark_txt'] = $water['mark_txt'];
    //                         }
    //                     }else{
    //                         /*支援子目錄*/
    //                         $water['mark_img'] = preg_replace('#^(/[/\w]+)?(/public/upload/|/uploads/)#i', '$2', $water['mark_img']); // 支援子目錄
    //                         /*--end*/
    //                         //$image->water(".".$water['mark_img'],9,$water['mark_degree'])->save($imgresource);
    //                         $waterPath = "." . $water['mark_img'];
    //                         if (eyPreventShell($waterPath) && file_exists($waterPath)) {
    //                             $quality = $water['mark_quality'] ? $water['mark_quality'] : 80;
    //                             $waterTempPath = dirname($waterPath).'/temp_'.basename($waterPath);
    //                             $image->open($waterPath)->save($waterTempPath, null, $quality);
    //                             $image->open($imgresource)->water($waterTempPath, $water['mark_sel'], $water['mark_degree'])->save($imgresource);
    //                             @unlink($waterTempPath);
    //                         }
    //                     }
    //                 }
    //             }
    //         }

    //         $data['url'] = ROOT_DIR.$data['url']; // 支援子目錄
    //     }else{
    //         $data = array('state' => 'ERROR'.$info->getError());
    //     }
    //     return json_encode($data);
    // }

    // //列出圖片
    // private function fileList2($allowFiles,$listSize,$get){
    //     $dirname = './'.$this->upload_path;
    //     $allowFiles = substr(str_replace(".","|",join("",$allowFiles)),1);
    //     /* 獲取參數 */
    //     $size = isset($get['size']) ? htmlspecialchars($get['size']) : $listSize;
    //     $start = isset($get['start']) ? htmlspecialchars($get['start']) : 0;
    //     $end = $start + $size;
    //     /* 獲取檔案列表 */
    //     $path = $dirname;
    //     $files = $this->getFiles($path,$allowFiles);
    //     if(empty($files)){
    //         return json_encode(array(
    //             "state" => "沒有相關檔案",
    //             "list" => array(),
    //             "start" => $start,
    //             "total" => count($files)
    //         ));
    //     }
    //     /* 獲取指定範圍的列表 */
    //     $len = count($files);
    //     for($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--){
    //         $list[] = $files[$i];
    //     }

    //     /* 返回數據 */
    //     $result = json_encode(array(
    //         "state" => "SUCCESS",
    //         "list" => $list,
    //         "start" => $start,
    //         "total" => count($files)
    //     ));

    //     return $result;
    // }
    
    // //抓取遠端圖片
    // private function saveRemote($config,$fieldName){
    //     $imgUrl = htmlspecialchars($fieldName);
    //     $imgUrl = str_replace("&amp;","&",$imgUrl);

    //     //http開頭驗證
    //     if(strpos($imgUrl,"http") !== 0){
    //         $data=array(
    //             'state' => '鏈接不是http鏈接',
    //         );
    //         return json_encode($data);
    //     }
    //     //獲取請求頭並檢測死鏈
    //     $heads = get_headers($imgUrl);
    //     if(!(stristr($heads[0],"200") && stristr($heads[0],"OK"))){
    //         $data=array(
    //             'state' => '鏈接不可用',
    //         );
    //         return json_encode($data);
    //     }
    //     //格式驗證(副檔名驗證和Content-Type驗證)
    //     if(preg_match("/^http(s?):\/\/mmbiz.qpic.cn\/(.*)/", $imgUrl) != 1){
    //         $fileType = strtolower(strrchr($imgUrl,'.'));
    //         if(!in_array($fileType,$config['allowFiles']) || (isset($heads['Content-Type']) && stristr($heads['Content-Type'],"image"))){
    //             $data=array(
    //                 'state' => '鏈接contentType不正確',
    //             );
    //             return json_encode($data);
    //         }
    //     }

    //     //打開輸出緩衝區並獲取遠端圖片
    //     ob_start();
    //     $context = stream_context_create(
    //         array('http' => array(
    //             'follow_location' => false // don't follow redirects
    //         ))
    //     );
    //     readfile($imgUrl,false,$context);
    //     $img = ob_get_contents();
    //     ob_end_clean();
    //     preg_match("/[\/]([^\/]*)[\.]?[^\.\/]*$/",$imgUrl,$m);

    //     $dirname = './'.$this->upload_path.'ueditor/'.$this->sub_name;
    //     $file['oriName'] = $m ? $m[1] : "";
    //     $file['filesize'] = strlen($img);
    //     $file['ext'] = strtolower(strrchr($config['oriName'],'.'));
    //     $file['name'] = uniqid().$file['ext'];
    //     $file['fullName'] = $dirname.$file['name'];
    //     $fullName = $file['fullName'];

    //     //檢查檔案大小是否超出限制
    //     if($file['filesize'] >= ($config["maxSize"])){
    //         $data=array(
    //             'state' => '檔案大小超出網站限制',
    //         );
    //         return json_encode($data);
    //     }

    //     //建立目錄失敗
    //     if(!file_exists($dirname) && !mkdir($dirname,0777,true)){
    //         $data=array(
    //             'state' => '目錄建立失敗',
    //         );
    //         return json_encode($data);
    //     }else if(!is_writeable($dirname)){
    //         $data=array(
    //             'state' => '目錄沒有寫許可權',
    //         );
    //         return json_encode($data);
    //     }

    //     //移動檔案
    //     if(!(file_put_contents($fullName, $img) && file_exists($fullName))){ //移動失敗
    //         $data=array(
    //             'state' => '寫入檔案內容錯誤',
    //         );
    //         return json_encode($data);
    //     }else{ //移動成功
    //         $data=array(
    //             'state' => 'SUCCESS',
    //             'url' => ROOT_DIR.substr($file['fullName'],1), // 支援子目錄
    //             'title' => $file['name'],
    //             'original' => $file['oriName'],
    //             'type' => $file['ext'],
    //             'size' => $file['filesize'],
    //         );

    //         $ossConfig = tpCache('oss');
    //         if ($ossConfig['oss_switch']) {
    //             //圖片可選擇存放在oss
    //             $savePath = $this->upload_path.$this->savePath.$this->sub_name;
    //             $object = $savePath.md5(getTime().uniqid(mt_rand(), TRUE)).'.'.pathinfo($data['url'], PATHINFO_EXTENSION);
    //             $getRealPath = ltrim($data['url'], '/');
    //             $ossClient = new \app\common\logic\OssLogic;
    //             $return_url = $ossClient->uploadFile($getRealPath, $object);
    //             if (!$return_url) {
    //                 $state = "ERROR" . $ossClient->getError();
    //                 $return_url = '';
    //             } else {
    //                 $state = "SUCCESS";
    //             }
    //             @unlink($getRealPath);
    //             $data['url'] = $return_url;
    //         }
    //     }
    //     return json_encode($data);
    // }

    // /*
    //  * 處理base64編碼的圖片上傳
    //  * 例如：塗鴉圖片上傳
    // */
    // private function upBase64($config,$fieldName){
    //     $base64Data = $_POST[$fieldName];
    //     $img = base64_decode($base64Data);

    //     $dirname = './'.$this->upload_path.'ueditor/'.$this->sub_name;
    //     $file['filesize'] = strlen($img);
    //     $file['oriName'] = $config['oriName'];
    //     $file['ext'] = strtolower(strrchr($config['oriName'],'.'));
    //     $file['name'] = uniqid().$file['ext'];
    //     $file['fullName'] = $dirname.$file['name'];
    //     $fullName = $file['fullName'];

    //     //檢查檔案大小是否超出限制
    //     if($file['filesize'] >= ($config["maxSize"])){
    //         $data=array(
    //             'state' => '檔案大小超出網站限制',
    //         );
    //         return json_encode($data);
    //     }

    //     //建立目錄失敗
    //     if(!file_exists($dirname) && !mkdir($dirname,0777,true)){
    //         $data=array(
    //             'state' => '目錄建立失敗',
    //         );
    //         return json_encode($data);
    //     }else if(!is_writeable($dirname)){
    //         $data=array(
    //             'state' => '目錄沒有寫許可權',
    //         );
    //         return json_encode($data);
    //     }

    //     //移動檔案
    //     if(!(file_put_contents($fullName, $img) && file_exists($fullName))){ //移動失敗
    //         $data=array(
    //             'state' => '寫入檔案內容錯誤',
    //         );
    //     }else{ //移動成功          
    //         $data=array(
    //             'state' => 'SUCCESS',
    //             'url' => substr($file['fullName'],1),
    //             'title' => $file['name'],
    //             'original' => $file['oriName'],
    //             'type' => $file['ext'],
    //             'size' => $file['filesize'],
    //         );
    //     }
        
    //     return json_encode($data);
    // }

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
            $savePath = $this->upload_path.$this->savePath.$this->sub_name;
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
        respose($return_data,'json');
    }
}