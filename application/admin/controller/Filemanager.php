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
use app\admin\controller\Base;
use think\Controller;
use think\Db;
use app\admin\logic\FilemanagerLogic;

class Filemanager extends Base
{
    public $filemanagerLogic;
    public $baseDir = '';
    public $maxDir = '';
    public $globalTpCache = array();

    public function _initialize() {
        parent::_initialize();
        $this->filemanagerLogic = new FilemanagerLogic(); 
        $this->globalTpCache = $this->filemanagerLogic->globalTpCache;
        $this->baseDir = $this->filemanagerLogic->baseDir; // 伺服器站點根目錄絕對路徑
        $this->maxDir = $this->filemanagerLogic->maxDir; // 預設檔案管理的最大級別目錄
    }

    public function index()
    {
        // 獲取到所有GET參數
        $param = input('param.', '', null);
        $activepath = input('param.activepath', '', null);
        $activepath = $this->filemanagerLogic->replace_path($activepath, ':', true);

        /*目前目錄路徑*/
        $activepath = !empty($activepath) ? $activepath : $this->maxDir;
        $tmp_max_dir = preg_replace("#\/#i", "\/", $this->maxDir);
        if (!preg_match("#^".$tmp_max_dir."#i", $activepath)) {
            $activepath = $this->maxDir;
        }
        /*--end*/

        $inpath = "";
        $activepath = str_replace("..", "", $activepath);
        $activepath = preg_replace("#^\/{1,}#", "/", $activepath); // 多個斜桿替換為單個斜桿
        if($activepath == "/") $activepath = "";

        if(empty($activepath)) {
            $inpath = $this->baseDir.$this->maxDir;
        } else {
            $inpath = $this->baseDir.$activepath;
        }

        $list = $this->filemanagerLogic->getDirFile($inpath, $activepath);
        $assign_data['list'] = $list;

        /*檔案操作*/
        $assign_data['replaceImgOpArr'] = $this->filemanagerLogic->replaceImgOpArr;
        $assign_data['editOpArr'] = $this->filemanagerLogic->editOpArr;
        $assign_data['renameOpArr'] = $this->filemanagerLogic->renameOpArr;
        $assign_data['delOpArr'] = $this->filemanagerLogic->delOpArr;
        $assign_data['moveOpArr'] = $this->filemanagerLogic->moveOpArr;
        /*--end*/

        $assign_data['activepath'] = $activepath;

        $this->assign($assign_data);
        return $this->fetch();
    }


    /**
     * 替換圖片
     */
    public function replace_img()
    {
        if (IS_POST) {
            $post = input('post.', '', null);
            $activepath = !empty($post['activepath']) ? trim($post['activepath']) : '';
            if (empty($activepath)) {
                $this->error('參數有誤');
                exit;
            }

            $file = request()->file('upfile');
            if (empty($file)) {
                $this->error('請選擇上傳圖片！');
                exit;
            } else {
                $image_upload_limit_size = intval(tpCache('basic.file_size') * 1024 * 1024);
                $result = $this->validate(
                    ['file' => $file],
                    ['file'=>'image|fileSize:'.$image_upload_limit_size],
                    ['file.image' => '上傳檔案必須為圖片','file.fileSize' => '上傳圖片過大']
                );
                if (true !== $result || empty($file)) {
                    $this->error($result);
                    exit;
                }
            }

            $res = $this->filemanagerLogic->upload('upfile', $activepath, $post['filename']);
            $this->success('操作成功！', url('Filemanager/index', array('activepath'=>$this->filemanagerLogic->replace_path($activepath, ':', false))));
            exit;
        }

        $filename = input('param.filename/s', '', null);

        $activepath = input('param.activepath/s', '', null);
        $activepath = $this->filemanagerLogic->replace_path($activepath, ':', true);
        if ($activepath == "") $activepathname = "根目錄";
        else $activepathname = $activepath;

        $info = array(
            'activepath'    => $activepath,
            'activepathname'    => $activepathname,
            'filename'  => $filename,
        );
        $this->assign('info', $info);
        return $this->fetch();
    }

    /**
     * 編輯
     */
    public function edit()
    {
        if (IS_POST) {
            $post = input('post.', '', null);
            $content = input('post.content', '', null);
            $filename = !empty($post['filename']) ? trim($post['filename']) : '';
            $content = !empty($content) ? $content : '';
            $activepath = !empty($post['activepath']) ? trim($post['activepath']) : '';

            if (empty($filename) || empty($activepath)) {
                $this->error('參數有誤');
                exit;
            }

            $r = $this->filemanagerLogic->editFile($filename, $activepath, $content);
            if ($r === true) {
                $this->success('操作成功！', url('Filemanager/index', array('activepath'=>$this->filemanagerLogic->replace_path($activepath, ':', false))));
                exit;
            } else {
                $this->error($r);
                exit;
            }
        }

        $activepath = input('param.activepath/s', '', null);
        $activepath = $this->filemanagerLogic->replace_path($activepath, ':', true);

        $filename = input('param.filename/s', '', null);

        $activepath = str_replace("..", "", $activepath);
        $filename = str_replace("..", "", $filename);
        $path_parts  = pathinfo($filename);
        $path_parts['extension'] = strtolower($path_parts['extension']);

        /*不允許越過指定最大級目錄的檔案編輯*/
        $tmp_max_dir = preg_replace("#\/#i", "\/", $this->filemanagerLogic->maxDir);
        if (!preg_match("#^".$tmp_max_dir."#i", $activepath)) {
            $this->error('沒有操作許可權！');
            exit;
        }
        /*--end*/
        
        /*允許編輯的檔案型別*/
        if (!in_array($path_parts['extension'], $this->filemanagerLogic->editExt)) {
            $this->error('只允許操作檔案型別如下：'.implode('|', $this->filemanagerLogic->editExt));
            exit;
        }
        /*--end*/

        /*讀取檔案內容*/
        $file = $this->baseDir."$activepath/$filename";
        $content = "";
        if(is_file($file))
        {
            $filesize = filesize($file);
            if (0 < $filesize) {
                $fp = fopen($file, "r");
                $content = fread($fp, $filesize);
                fclose($fp);
                if ('css' != $path_parts['extension']) {
                    $content = htmlspecialchars($content, ENT_QUOTES);
                    $content = preg_replace("/(@)?eval(\s*)\(/i", 'intval(', $content);
                    // $content = preg_replace("/\?\bphp\b/i", "？ｍｕｍａ", $content);
                }
            }
        }
        /*--end*/

        if($path_parts['extension'] == 'js'){
            $extension = 'text/javascript';
        } else if($path_parts['extension'] == 'css'){
            $extension = 'text/css';
        } else {
            $extension = 'text/html';
        }

        $info = array(
            'filename'  => $filename,
            'activepath'=> $activepath,
            'extension' => $extension,
            'content'   => $content,
        );
        $this->assign('info', $info);
        return $this->fetch();
    }

    /**
     * 新建檔案
     */
    public function newfile()
    {
        if (IS_POST) {
            $post = input('post.', '', null);
            $content = input('post.content', '', null);
            $filename = !empty($post['filename']) ? trim($post['filename']) : '';
            $content = !empty($content) ? $content : '';
            $activepath = !empty($post['activepath']) ? trim($post['activepath']) : '';

            if (empty($filename) || empty($activepath)) {
                $this->error('參數有誤');
                exit;
            }

            $r = $this->filemanagerLogic->editFile($filename, $activepath, $content);
            if ($r === true) {
                $this->success('操作成功！', url('Filemanager/index', array('activepath'=>$this->filemanagerLogic->replace_path($activepath, ':', false))));
                exit;
            } else {
                $this->error($r);
                exit;
            }
        }

        $activepath = input('param.activepath/s', '', null);
        $activepath = $this->filemanagerLogic->replace_path($activepath, ':', true);
        $filename = 'newfile.txt';
        $content = "";
        $info = array(
            'filename'  => $filename,
            'activepath'=> $activepath,
            'content'   => $content,
        );
        $this->assign('info', $info);
        return $this->fetch();
    }
}
