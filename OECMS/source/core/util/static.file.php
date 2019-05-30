<?php
/**
 * [OECMS] (C)2012-2099 OEdev,Inc.
 * <E-Mail：phpcoo@qq.com>
 * Url www.oedev.net, www.phpcoo.com
 * Update 2013.02.05
*/
if(!defined('IN_OECMS')) {
	exit('Access Denied');
}
class  XFile{

	
	public static function createFile($filename) {
		if (!empty($filename)) {
			if (file_exists($filename)) return false;
			self::createDir(dirname($filename)); 
			return @file_put_contents($filename, '');
		}
		else {
			return false;
		}
	}

	
	public static function writeFile($filename, $content='', $type = 1) {
		if (!empty($filename)) {
			$full_filename = BASE_ROOT.$filename;
			if ($type == 1) {
				if (file_exists($full_filename)) self::delFile($filename); 
				
				self::createFile($filename);
				
				return self::writeFile($filename, $content, 2);
			} else {
				if (!is_writable($full_filename)) return false;
				$handle = @fopen($full_filename, 'a');
				if (!$handle) return false;
				$result = @fwrite($handle, $content);
				if (!$result) return false;
				@fclose($handle);
				return true;
			}
		}
		else {
			return false;
		}
	}
	
	
	public static function copyFile($filename, $newfilename) {
		if (!empty($filename) && !empty($newfilename)) {
			$full_filename = BASE_ROOT.$filename;
            
            if (XHandle::getStrpos($full_filename, 'db.inc.php')) {
                return false;
            }
            else {
    			
    			if (!file_exists($full_filename) || !is_writable($full_filename)) {
    				return false;
    			}
    			else {
    				
    				self::createDir(dirname($newfilename));
    				
    				return @copy($full_filename, BASE_ROOT.$newfilename);
    			}
            }
		}
		else {
			return false;
		}
	}
	
	
	public static function moveFile($filename, $newfilename) {
		if(!empty($filename) && !empty($newfilename)) {
			$full_filename = BASE_ROOT.$filename;
            
			if (!file_exists($full_filename) || !is_writable($full_filename)){
				return false;
			}
			else {
                
                if (XHandle::getStrpos($full_filename, 'db.inc.php')) {
                    return false;                    
                }
                else {
    				
    				self::createDir(dirname($newfilename));
    				
    				return @rename($full_filename, BASE_ROOT.$newfilename);
                }
			}
		}
		else {
			return false;
		}
	}
	
	
	public static function delFile($filename) {
		if (!empty($filename)) {
			$full_filename = BASE_ROOT.$filename;
			if (!file_exists($full_filename) || !is_writable($full_filename)) return true;
			return @unlink($full_filename);
		}
		else {
			return false;
		}
	}
	
	
	public static function getFileInfo($filename) {
		if (!empty($filename)) {
			$filename = BASE_ROOT.$filename;
			if (file_exists($filename)){
				return array(
					'atime' => date("Y-m-d H:i:s", fileatime($filename)),
					'ctime' => date("Y-m-d H:i:s", filectime($filename)),
					'mtime' => date("Y-m-d H:i:s", filemtime($filename)),
					'size'  => filesize($filename),
					'type'  => filetype($filename)	
				);
			}
			else {
				return array(
					'atime' => '',
					'ctime' => '',
					'mtime' => '',
					'size'  => '',
					'type'  => '',	
				);
			}
		}
		else {
			return array(
				'atime' => '',
				'ctime' => '',
				'mtime' => '',
				'size'  => '',
				'type'  => '',	
			);
		}
	}
	
	
	public static function createFolder($path) {
		if (!empty($path)) {
			$path = BASE_ROOT.$path;
			if (!is_dir($path)) return false;
			@mkdir($path);
			@chmod($path, 0777);
			return true;
		}
		else {
			return false;
		}
	}
	
    
    public static function readFile($filenamepath){
        $filename=BASE_ROOT.$filenamepath;
        if ($fp = @fopen($filename, 'r')) {
				$data = fread($fp, filesize($filename));
				fclose($fp);
			}
          if($data){
            return $data;
          }else{
            return false;
          }
    }
    
    
	
	public static function createDir($dir){
		if (!empty($dir)) {
			$edir = explode('/',$dir);
			for($i=0;$i<count($edir);$i++){
				$edirm = $edir[0];
				for($ii=1;$ii<=$i;$ii++){
					$edirm = $edirm.'/'.$edir[$ii];
				}
				if(file_exists(BASE_ROOT.$edirm) && is_dir(BASE_ROOT.$edirm)){
				}else{
					@mkdir (BASE_ROOT.$edirm,0777);
				}
			}
		}
	}

	
	public static function delDir ($dir){
		if (!empty($dir)) {
			
			$dh = @opendir($dir);
			while ($file=@readdir($dh)) {
				if($file!="." && $file!="..") {
					$fullpath=	$dir."/".$file;
					if(!is_dir($fullpath)) {
						@unlink($fullpath);
					} else {
						self::delDir($fullpath);
					}
				}
			}
			@closedir($dh);
			
			if(@rmdir($dir)) {
				return true;
			}
			else {
				return false;
			}		
		}
        else {
            return false;
        }
	}
}	
?>
