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

namespace app\admin\logic;

use think\Model;
use think\db;
/**
 * 檔案管理邏輯定義
 * Class CatsLogic
 * @package admin\Logic
 */
class FilemanagerLogic extends Model
{
    public $globalTpCache = array();
    public $baseDir = ''; // 伺服器站點根目錄絕對路徑
    public $maxDir = '';
    public $replaceImgOpArr = array(); // 替換許可權
    public $editOpArr = array(); // 編輯許可權
    public $renameOpArr = array(); // 改名許可權
    public $delOpArr = array(); // 刪除許可權
    public $moveOpArr = array(); // 移動許可權
    public $editExt = array(); // 允許新增/編輯副檔名檔案

    /**
     * 解構函式
     */
    function  __construct() {
        $this->globalTpCache = tpCache('global');
        $this->baseDir = rtrim(ROOT_PATH, DS); // 伺服器站點根目錄絕對路徑
        $this->maxDir = $this->globalTpCache['web_templets_dir']; // 預設檔案管理的最大級別目錄
        // 替換許可權
        $this->replaceImgOpArr = array('gif','jpg','svg');
        // 編輯許可權
        $this->editOpArr = array('txt','htm','js','css');
        // 改名許可權
        $this->renameOpArr = array('dir','gif','jpg','svg','flash','zip','exe','mp3','wmv','rm','txt','htm','js','css','other');
        // 刪除許可權
        $this->delOpArr = array('dir','gif','jpg','svg','flash','zip','exe','mp3','wmv','rm','txt','htm','php','js','css','other');
        // 移動許可權
        $this->moveOpArr = array('gif','jpg','svg','flash','zip','exe','mp3','wmv','rm','txt','htm','js','css','other');
        // 允許新增/編輯副檔名檔案
        $this->editExt = array('htm','js','css','txt');
    }

    /**
     * 編輯檔案
     *
     * @access    public
     * @param     string  $filename  檔名
     * @param     string  $activepath  目前路徑
     * @param     string  $content  檔案內容
     * @return    string
     */
    public function editFile($filename, $activepath = '', $content = '')
    {
        $fileinfo = pathinfo($filename);
        $ext = strtolower($fileinfo['extension']);

        /*不允許越過指定最大級目錄的檔案編輯*/
        $tmp_max_dir = preg_replace("#\/#i", "\/", $this->maxDir);
        if (!preg_match("#^".$tmp_max_dir."#i", $activepath)) {
            return '沒有操作許可權！';
        }
        /*--end*/

        /*允許編輯的檔案型別*/
        if (!in_array($ext, $this->editExt)) {
            return '只允許操作檔案型別如下：'.implode('|', $this->editExt);
        }
        /*--end*/

        $filename = str_replace("..", "", $filename);
        $file = $this->baseDir."$activepath/$filename";
        if (!is_writable(dirname($file))) {
            return "請把模板檔案目錄設定為可寫入許可權！";
        }
        if ('css' != $ext) {
            $content = htmlspecialchars_decode($content, ENT_QUOTES);
            $content = preg_replace("/(@)?eval(\s*)\(/i", 'intval(', $content);
            // $content = preg_replace("/\?\bphp\b/i", "？ｍｕｍａ", $content);
        }
        $fp = fopen($file, "w");
        fputs($fp, $content);
        fclose($fp);
        return true;
    }

    /**
     * 上傳檔案
     *
     * @param     string  $dirname  新目錄
     * @param     string  $activepath  目前路徑
     * @param     boolean  $replace  是否替換
     */
    public function upload($fileElementId, $activepath = '', $replace = false)
    {
        $file = request()->file($fileElementId);
        if (is_object($file) && !is_array($file)) {
            $fileArr[] = $file;
        } else if (!is_object($file) && is_array($file)) {
            $fileArr = $file;
        }
        $i = 0;
        foreach ($fileArr as $key => $fileObj) {
            if (empty($fileObj)) {
                continue;
            }
            if($this->uploadfile($fileObj, $activepath, $replace)) {
                $i++;
            }
        }

        return "成功上傳 $i 個檔案到: $activepath";
    }

    /**
     * 自定義上傳
     *
     * @param     object  $file  檔案對像
     * @param     string  $activepath  目前路徑
     * @param     boolean  $replace  是否替換
     */
    public function uploadfile($file, $activepath = '', $replace = false)
    {
        $validate = array();
        /*檔案大小限制*/
        $validate_size = tpCache('basic.file_size');
        if (!empty($validate_size)) {
            $validate['size'] = $validate_size * 1024 * 1024; // 單位為b
        }
        /*--end*/
        /*上傳檔案驗證*/
        if (!empty($validate)) {
            $is_validate = $file->check($validate);
            if ($is_validate === false) {
                return false;
            }   
        }
        /*--end*/

        $savePath = !empty($activepath) ? trim($activepath, '/') : UPLOAD_PATH.'temp';
        if (!file_exists($savePath)) {
            tp_mkdir($savePath);
        }

        if (false == $replace) {
            $fileinfo = $file->getInfo();
            $filename = pathinfo($fileinfo['name'], PATHINFO_BASENAME); //獲取上傳檔名
        } else {
            $filename = $replace;
        }

        // 使用自定義的檔案儲存規則
        $info = $file->move($savePath, $filename, true);
        if($info){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 目前目錄下的檔案列表
     */
    public function getDirFile($directory, $activepath = '',  &$arr_file = array()) {

        if (!file_exists($directory)) {
            return false;
        }

        $fileArr = $dirArr = $parentArr = array();

        $mydir = dir($directory);
        while($file = $mydir->read())
        {
            $filesize = $filetime = $intro = '';
            $filemine = 'file';

            if($file != "." && $file != ".." && !is_dir("$directory/$file"))
            {
                @$filesize = filesize("$directory/$file");
                @$filesize = format_bytes($filesize);
                @$filetime = filemtime("$directory/$file");
            }

            if ($file == '.') 
            {
                continue;
            } 
            else if($file == "..") 
            {
                if($activepath == "" || $activepath == $this->maxDir) {
                    continue;
                }
                $parentArr = array(
                    array(
                        'filepath'  => preg_replace("#[\/][^\/]*$#i", "", $activepath),
                        'filename'  => '上級目錄',
                        'filesize'  => '',
                        'filetime'  => '',
                        'filemine'  => 'dir',
                        'filetype'  => 'dir2',
                        'icon'      => 'file_topdir.gif',
                        'intro'  => '（目前目錄：'.$activepath.'）',
                    ),
                );
                continue;
            } 
            else if(is_dir("$directory/$file"))
            {
                if(preg_match("#^_(.*)$#i", $file)) continue; #遮蔽FrontPage擴充套件目錄和linux隱蔽目錄
                if(preg_match("#^\.(.*)$#i", $file)) continue;
                $file_info = array(
                    'filepath'  => $activepath.'/'.$file,
                    'filename'  => $file,
                    'filesize'  => '',
                    'filetime'  => '',
                    'filemine'  => 'dir',
                    'filetype'  => 'dir',
                    'icon'      => 'dir.gif',
                    'intro'     => '',
                );
                array_push($dirArr, $file_info);
                continue;
            }
            else if(preg_match("#\.(gif|png)#i",$file))
            {
                $filemine = 'image';
                $filetype = 'gif';
                $icon = 'gif.gif';
            }
            else if(preg_match("#\.(jpg|jpeg|bmp)#i",$file))
            {
                $filemine = 'image';
                $filetype = 'jpg';
                $icon = 'jpg.gif';
            }
            else if(preg_match("#\.(svg)#i",$file))
            {
                $filemine = 'image';
                $filetype = 'svg';
                $icon = 'jpg.gif';
            }
            else if(preg_match("#\.(swf|fla|fly)#i",$file))
            {
                $filetype = 'flash';
                $icon = 'flash.gif';
            }
            else if(preg_match("#\.(zip|rar|tar.gz)#i",$file))
            {
                $filetype = 'zip';
                $icon = 'zip.gif';
            }
            else if(preg_match("#\.(exe)#i",$file))
            {
                $filetype = 'exe';
                $icon = 'exe.gif';
            }
            else if(preg_match("#\.(mp3|wma)#i",$file))
            {
                $filetype = 'mp3';
                $icon = 'mp3.gif';
            }
            else if(preg_match("#\.(wmv|api)#i",$file))
            {
                $filetype = 'wmv';
                $icon = 'wmv.gif';
            }
            else if(preg_match("#\.(rm|rmvb)#i",$file))
            {
                $filetype = 'rm';
                $icon = 'rm.gif';
            }
            else if(preg_match("#\.(txt|inc|pl|cgi|asp|xml|xsl|aspx|cfm)#",$file))
            {
                $filetype = 'txt';
                $icon = 'txt.gif';
            }
            else if(preg_match("#\.(htm|html)#i",$file))
            {
                $filetype = 'htm';
                $icon = 'htm.gif';
            }
            else if(preg_match("#\.(php)#i",$file))
            {
                $filetype = 'php';
                $icon = 'php.gif';
            }
            else if(preg_match("#\.(js)#i",$file))
            {
                $filetype = 'js';
                $icon = 'js.gif';
            }
            else if(preg_match("#\.(css)#i",$file))
            {
                $filetype = 'css';
                $icon = 'css.gif';
            }
            else
            {
                $filetype = 'other';
                $icon = 'other.gif';
            }

            $file_info = array(
                'filepath'  => $activepath.'/'.$file,
                'filename'  => $file,
                'filesize'  => $filesize,
                'filetime'  => $filetime,
                'filemine'  => $filemine,
                'filetype'  => $filetype,
                'icon'      => $icon,
                'intro'     => $intro,
            );
            array_push($fileArr, $file_info);
        }
        $mydir->close();

        $arr_file = array_merge($parentArr, $dirArr, $fileArr);

        return $arr_file;
    }

    /**
     * 將冒號符反替換為反斜槓，適用於IIS伺服器在URL上的雙重轉義限制
     * @param string $filepath 相對路徑
     * @param string $replacement 目標字元
     * @param boolean $is_back false為替換，true為還原
     */
    public function replace_path($activepath, $replacement = ':', $is_back = false)
    {
        return replace_path($activepath, $replacement, $is_back);
    }
}