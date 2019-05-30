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

class templetAModel extends X {
    
    
    public function getList($dir) {
        parent::loadLib('option');
        $nonce_templet = XOption::get('nonce_templet');
        $path = BASE_ROOT.'tpl/templets/'.$nonce_templet.'/';
        if (!empty($dir)) {
            $path .= $dir.'/';
        }
        if (false === XValid::isDir($path)) {
            XHandle::halt('对不起，读取模板目录失败，请检测目录是否存在！', '', 1);
        }
        
        $handle = @opendir($path) OR die('OEcms templet path error!');
        $templets = array();
    	$i = 0;
    	$ii = 1;
        while ($file = @readdir($handle)){
            if ($file!='.' && $file!='..') {
                
                
                if (true === XValid::isDir($path.$file)) {
                    $templets[] = array(
                        'i'=>$ii,
                        'type'=>1,
                        'filename'=>$file,
                        'size'=>filesize($path.$file),
                        'timeline'=>filemtime($path.$file),
                        'dir'=>$dir.'/'.$file,
                        'filepath'=>'',
                        'extension'=>'',
                        'tplname'=>'',
                    );
                }
                else {
                    $arr_file = explode('.', $file);                   
                    $templets[] = array(
                        'i'=>$ii,
                        'type'=>2,
                        'filename'=>$file,
                        'size'=>filesize($path.$file),
                        'timeline'=>filemtime($path.$file),
                        'dir'=>'',
                        'filepath'=>$dir.'/'.$file,
                        'extension'=>end($arr_file),
                        'tplname'=>$arr_file[0],
                    );
                }
                $i = $i+1;
                $ii = ($ii+1);
            }
    	}
    	closedir($handle);
    	$tplnums = count($templets);
        
        
        $model = parent::model('skin', 'am');
        $nonce = $model->getCopyRight();
        unset($model);   
        return array($tplnums, $templets, $nonce);
    }
    
    
    public function getData($file) {
        
        parent::loadLib('option');
        $nonce_templet = XOption::get('nonce_templet');
        $filepath = BASE_ROOT.'tpl/templets/'.$nonce_templet.$file;
        $content = '';
    	$handle = @fopen($filepath, 'r');
   		while(!feof($handle)){
			$content = $content.$this->_enCode(fgets($handle));
		}
		fclose($handle);
        
        
        $model = parent::model('skin', 'am');
        $nonce = $model->getCopyRight();
        unset($model);
        return array($content, $nonce, $this->_getDir($file));
    }
    
    
    public function doEdit($file, $content){
        parent::loadLib('option');
        $nonce_templet = XOption::get('nonce_templet');
        $filepath = 'tpl/templets/'.$nonce_templet.$file;
        $content = $this->_deCode($content);
                
        
        parent::loadUtil('file');
        return XFile::writeFile($filepath, $content);
    }
    
    
    public function doDelFile($file) {
        parent::loadLib('option');
        $nonce_templet = XOption::get('nonce_templet');
        $filepath = 'tpl/templets/'.$nonce_templet.$file;
        
        
        parent::loadUtil('file');
        $result = XFile::delFile($filepath); 
        return array($result, $this->_getDir($file));
    }
    
    
    public function doDelFolder($folder) {
        parent::loadLib('option');
        $nonce_templet = XOption::get('nonce_templet');
        $folderpath = BASE_ROOT.'tpl/templets/'.$nonce_templet.$folder;
        
        
        parent::loadUtil('file');
        return XFile::delDir($folderpath);
    }
    
    
    private function _enCode($str){
    	$str=str_replace('<', '&lt;', $str);
    	$str=str_replace('>', '&gt;', $str);
    	return $str;
    }
    private function _deCode($str){
    	$str=str_replace('&lt;', '<', $str);
    	$str=str_replace('&gt;', '>', $str);
    	return $str;
    }
    
    private function _getDir($file) {
        $string = explode('/', $file);
        $count = count($string);
        if ($count>2) {
            return str_replace('/'.$string[$count-1], '', $file);
        }
        else {
            return '';
        }
    }
}
?>
