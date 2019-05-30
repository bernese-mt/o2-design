<?php
/**
 * [OECMS] (C)2012-2099 OEdev,Inc.
 * <E-Mail：phpcoo@qq.com>
 * Url www.oedev.net, www.phpcoo.com
 * Update 2013.02.05
*/
class cacheClass {
	
	
	private $db;
	
	private static $instance = null;

	private $options_cache;

	
	public function __construct() {
		$this->db = X::$obj;
	}

	
	public function updateCache($cacheMethodName = null) {
		
		if (is_string($cacheMethodName)) {
			if (method_exists($this, 'cache_' . $cacheMethodName)) {
				call_user_func(array($this, 'cache_' . $cacheMethodName));
			}
			return;
		}
		
		if (is_array($cacheMethodName)) {
			foreach ($cacheMethodName as $name) {
				if (method_exists($this, 'cache_' . $name)) {
					call_user_func(array($this, 'cache_' . $name));
				}
			}
			return;
		}
		
		if ($cacheMethodName == null) {
			
			$cacheMethodNames = get_class_methods($this);
			foreach ($cacheMethodNames as $method) {
				if (preg_match('/^cache_/', $method)) {
					call_user_func(array($this, $method));
				}
			}
		}
	}

	
	private function writeCache ($cacheData, $cacheName) {
		$cachefile = BASE_ROOT . './data/cache/' . $cacheName;
		@$fp = fopen($cachefile, 'wb') OR XHandle::halt('读取缓存数据失败。如果您使用的是Unix/Linux主机，请修改缓存目录 (data/cache) 下所有文件的权限为777。如果您使用的是Windows主机，请联系管理员，将该目录下所有文件设为everyone可写', '', 1);
		@$fw = fwrite($fp, $cacheData) OR XHandle::halt('写入缓存数据失败，缓存目录 (data/cache) 不可写');
		$this->{$cacheName.'_cache'} = null;
		@fclose($fp);
	}

	
	public function readCache($cacheName) {
		if ($this->{$cacheName.'_cache'} != null) {
			return $this->{$cacheName.'_cache'};
		} else {
			$cachefile = BASE_ROOT . './data/cache/' . $cacheName;
			
			if (!is_file($cachefile) || filesize($cachefile) <= 0) {
				if (method_exists($this, 'cache_' . $cacheName)) {
					call_user_func(array($this, 'cache_' . $cacheName));
				}
			}
			if ($fp = fopen($cachefile, 'r')) {
				$data = fread($fp, filesize($cachefile));
				fclose($fp);
				$this->{$cacheName.'_cache'} = unserialize($data);
				return $this->{$cacheName.'_cache'};
			}
		}
	}
    
     
    private function _unSerialize($string){
        if (!empty($string)) {
            if (strtolower(OECMS_CHARSET) == 'utf-8') {
                return $this->_utf_unserialize($string);
                
            }else{
                return $this->_gbk_unserialize($string);
            }
        }
        else {
            return '';
        }
    }
    private function _utf_unserialize($serial_str){
        $serial_str= preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $serial_str );
        $serial_str= str_replace("\r", "", $serial_str); 
        return @unserialize($serial_str);
    }
    private function _gbk_unserialize($serial_str) {  
        $serial_str = preg_replace('!s:(\d+):"(.*?)";!se', '"s:".strlen("$2").":\"$2\";"', $serial_str );
        $serial_str= str_replace("\r", "", $serial_str);      
        return @unserialize($serial_str);   
    }
    
    
	
	public function writeDcache ($cacheData, $cacheName) {
		$cachefile = BASE_ROOT.'./data/cache/'.$cacheName.'.cache';
		@$fp = fopen($cachefile, 'wb') OR XHandle::halt('读取缓存数据失败。如果您使用的是Unix/Linux主机，请修改缓存目录 (data/cache) 下所有文件的权限为777。如果您使用的是Windows主机，请联系管理员，将该目录下所有文件设为everyone可写', '', 1);
		@$fw = fwrite($fp, $cacheData) OR XHandle::halt('写入缓存数据失败，缓存目录 (data/cache) 不可写');
		@fclose($fp);
	}
    
	
	public function readDcache($cacheName, $serialize=1) {
        $cache_data = null;
        $cachefile = BASE_ROOT.'./data/cache/'.$cacheName.'.cache';
        if (!is_file($cachefile) || filesize($cachefile) <= 0) {
        }
        else {
            if ($fp = fopen($cachefile, 'r')) {
                $data = fread($fp, filesize($cachefile));
                fclose($fp);
                if ($serialize == 1) {
                    $cache_data = $this->_unSerialize($data);
                }
                else {
                    $cache_data = $data;
                }
            }
        }
        return $cache_data;
	}
    
    
    
    public function checkCaChe($cachename) {
        $cachefile = BASE_ROOT.'./data/cache/'.$cachename;
        if (file_exists($cachefile)) {
            return true;
        }
        else {
            return false;
        }
    }
    
    
    private function cache_options() {
        $options_cache = array();
        $data = $this->db->getall("SELECT * FROM ".DB_PREFIX."options");
        foreach($data as $key=>$value) {
            $options_cache[$value['optionname']] = $value['optionvalue'];
        }
        $cacheData  = serialize($options_cache);
        $this->writeCache($cacheData, 'options');
    }
    
    
    private function cache_htmllabel() {
        $cache_array = array();
        $data = $this->db->getall("SELECT `labelname`,`content` FROM ".DB_PREFIX."htmllabel");
        foreach($data as $key=>$value) {
            $cache_array[$value['labelname']] = $value['content'];
        }
        $cacheData = serialize($cache_array);
        $this->writeCache($cacheData, 'htmllabel');
    }
    
    
    private function cache_catalog() {
        $m = X::model('category', 'am');
        $cache_data = $m->getCatalogData();
        unset($m);
        $this->writeCache(serialize($cache_data), 'catalog');
    }
    
    
    private function cache_seo() {
        $cache_array = array();
        $sql = "SELECT `idmark`, `chname`, `title`, `description`, `keyword`".
                " FROM ".DB_PREFIX."seo ORDER BY `id` ASC";
        $data = $this->db->getall($sql);
        foreach ($data as $key=>$value) {
            $cache_array[$value['idmark']] = array(
                'chname'=>$value['chname'],
                'title'=>$value['title'],
                'description'=>$value['description'],
                'keyword'=>$value['keyword'],
            );
        }
        $cacheData = serialize($cache_array);
        $this->writeCache($cacheData, 'seo');
        unset($data, $cache_array, $cacheData);
    }

}
?>
