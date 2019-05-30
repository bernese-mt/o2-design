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
class XUrl extends X{
    
    
    public static function getCategoryUrl($module, $cid, $catalog) {
        $url = '';
        if (parent::$cfg['urlsuffix'] == 'html') {
            $url = PATH_URL.$catalog.'/';
        }
        else {
            $url = PATH_URL.'index.php?c='.$module.'&cid='.$cid;
        }
        return $url;
    }    
    
    
    public static function getContentUrl($module, $id) {
        if (parent::$cfg['urlsuffix'] == 'php') {
            return PATH_URL.'index.php?c='.$module.'&id='.$id;
        }
        else {
            return PATH_URL.$module.'/'.$id.'.html';
        }
    }
    
    
    public static function getUri() {
        $path_info = array();
        $uri = self::_requestUri();
        $uri = str_ireplace(
            array('http://', 'index.php/', 'index.php?'), 
            array('', '', ''), 
            $uri
        );
        if (substr_count(OECMS_ROOT, '/') > 1) {
            $uri = str_ireplace(OECMS_ROOT, '', $uri);
            $uri = '/'.$uri;
        }
        $uri_array = explode('/', $uri);
        $uri_count = count($uri_array);
                
        
        if (parent::$cfg['urlsuffix'] == 'html') {
            
            if (in_array($uri_array[1], array('article', 'product', 'photo', 'download', 'hr', 'about')) && strpos($uri, '.html') && false == strpos($uri, 'page_')) {
                
                $id_string = @$uri_array[2];
                if (!empty($id_string)) {
                    $uri_id = str_replace('.html', '', trim($id_string));
                    $path_info = array(
                        'module'=>$uri_array[1],
                        'c'=>$uri_array[1],
                        'a'=>'detail',
                        'id'=>intval($uri_id),
                    );
                }
            }
            else {
                
                $catalog = @$uri_array[1];
                list($iscata, $cata_data) = self::_getCatalog($catalog);
                if (true === $iscata) {
                    
                    $page_string = @$uri_array[2];
                    if (strpos($uri, 'page')) {
                        $page_id = str_replace(array('page_', '.html'), array('', ''), $page_string);
                        $path_info = array(
                            'catalog'=>$catalog,
                            'module'=>$cata_data['modalias'],
                            'c'=>$cata_data['modalias'],
                            'a'=>'list',
                            'cid'=>intval($cata_data['catid']),
                            'page'=>intval($page_id),
                        );
                    }
                    else {
                        $path_info = array(
                            'catalog'=>$catalog,
                            'module'=>$cata_data['modalias'],
                            'c'=>$cata_data['modalias'],
                            'a'=>'run',
                            'cid'=>intval($cata_data['catid']),
                        );
                    }
                }
            }
            
        }
        
        else {
            
            if (strpos($uri, '&id')) {
                $path_info = array(
                    'a'=>'detail',
                );
            }
            
        }
        return $path_info;
    }  
    
    
    
    private function _getCatalog($name) {
        $res = false;
        $cata = array();
        if (true === XValid::isSpChar($name)) {
            $sql = "SELECT `catid`, `modalias` FROM ".DB_PREFIX."category".
                    " WHERE `dirname`='{$name}'";
            $rows = parent::$obj->fetch_first($sql);
            if (!empty($rows)) {
                $res = true;
                $cata = $rows;
            }
            unset($sql, $rows);  
        }
        return array($res, $cata);
    }
    
    
    
    private function _requestUri(){
        $_uri = null;
        if (isset($_SERVER['REQUEST_URI'])) {
            $_uri = $_SERVER['REQUEST_URI'];
        }
        else {
            if (isset($_SERVER['argv'])) {
                $_uri = $_SERVER['PHP_SELF'] .(empty($_SERVER['argv']) ? '' : ('?'. $_SERVER['argv'][0]));
            }
            else {
                $_uri = $_SERVER['PHP_SELF'] .(empty($_SERVER['QUERY_STRING']) ? '' : ('?'. $_SERVER['QUERY_STRING']));
            }
        }
        return $_uri;
    }
}
?>
