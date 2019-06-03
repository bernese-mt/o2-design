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

//------------------------
// EyouCms 助手函式
//-------------------------

use think\Url;
use think\Config;

if (!function_exists('memcache')) 
{
    /**
     * 快取管理
     * @param mixed     $name 快取標識，具體檢視./app/extra/admin_memcache.php
     * @param mixed     $value 快取值
     * @return mixed
     */
    function memcache($name = null, $value = null, $options = false)
    {
        //暫時改用memcached
        return memcached($name, $value, $options);
        exit;


        //暫這麼連線  後期更改
        static $memcache;
        // $module = strtolower(MODULE_NAME);
        $data = Config::get('memcache_key');

        // 關閉memcached時，自動改用cache方式
        if (Config::get('memcache.switch') == 0) {
            if (empty($name) || empty($data[$name])) {
                return false;
            }
            $expire = $data[$name]['expire'];
            return cache($name, $value, $expire);
        }

        if ($options === false) {
            $options = Config::get('memcache');
        }

        $memcache = new \think\cache\driver\Memcache($options);
        if (is_null($name) && is_null($value)) {
            return $memcache;
        }

        if (empty($name) || empty($data[$name])) {
            return false;
        }

        $key = md5(strtolower($name));
        $expire = $data[$name]['expire'];
        $tag = $data[$name]['tag'];

        if (is_null($value)) {
            // 獲取快取
            return true === $memcache->has($key) ? $memcache->get($key) : false;
        } elseif ('' === $value) {
            // 刪除快取
            return $memcache->rm($key);
        } else {
            // 快取數據
            $expire = is_numeric($expire) ? $expire : null; //預設快捷快取設定過期時間

            if (is_null($tag) || empty($tag)) {
                return $memcache->set($key, $value, $expire);
            } else {
                // $memcache->tag = $tag;
                return $memcache->set($key, $value, $expire);
            }
        }
    }
}

if (!function_exists('memcached')) 
{
    /**
     * 快取管理
     * @param mixed     $name 快取標識，具體檢視./app/extra/admin_memcache.php
     * @param mixed     $value 快取值
     * @return mixed
     */
    function memcached($name = null, $value = null, $options = false)
    {
        //暫這麼連線  後期更改
        static $memcached;
        // $module = strtolower(MODULE_NAME);
        $data = Config::get('memcache_key');

        // 關閉memcached時，自動改用cache方式
        if (Config::get('memcache.switch') == 0) {
            if (empty($name) || empty($data[$name])) {
                return false;
            }
            $expire = $data[$name]['expire'];
            return cache($name, $value, $expire);
        }

        if ($options === false) {
            $options = Config::get('memcache');
        }

        $memcached = new \think\cache\driver\Memcached($options);
        if (is_null($name) && is_null($value)) {
            return $memcached;
        }

        if (empty($name) || empty($data[$name])) {
            return false;
        }

        $key = md5(strtolower($name));
        $expire = $data[$name]['expire'];
        $tag = $data[$name]['tag'];

        if (is_null($value)) {
            // 獲取快取
            return true === $memcached->has($key) ? $memcached->get($key) : false;
        } elseif ('' === $value) {
            // 刪除快取
            return $memcached->rm($key);
        } else {
            // 快取數據
            $expire = is_numeric($expire) ? $expire : null; //預設快捷快取設定過期時間

            if (is_null($tag) || empty($tag)) {
                return $memcached->set($key, $value, $expire);
            } else {
                // $memcached->tag = $tag;
                return $memcached->set($key, $value, $expire);
            }
        }
    }
}

if (!function_exists('extra_cache')) {
/**
 * 獲取和設定配置參數 支援批量定義
 * @param string|array $name 配置變數
 * @param mixed $value 配置值
 * @param mixed $default 預設值
 * @return mixed
 */
    function extra_cache($name, $value = '', $expire = 0) {
        $request = think\Request::instance();
        $module = strtolower($request->module());
        $keys_list = config('extra_cache_key');

        $key = md5(strtolower($name));
        if (!isset($keys_list[$name])) {
            return false;
        }
        $options = $keys_list[$name]['options'];
        $cache_conf = config('cache');
        if ($expire > 0) {
            $cache_conf['expire'] = $expire;
        } else {
            if (!empty($options['expire'])) {
                $cache_conf['expire'] = $options['expire'];
            }
        }
        if (!empty($options['prefix'])) {
            $cache_conf['prefix'] = $options['prefix'];
        }

        $tag = $keys_list[$name]['tag'];
        if (empty($tag)) {
            $tag = $module;
        }

        return cache($key, $value, $cache_conf, $tag);
   }   
}

if (!function_exists('html_cache')) {
/**
 * 獲取和設定配置參數 支援批量定義
 * @param string|array $name 配置變數
 * @param mixed $value 配置值
 * @param mixed $default 預設值
 * @return mixed
 */
    function html_cache($name, $value = '', $options = array()) {

        $new_conf = $options;

        if (!isset($options['path'])) {
            if (!stristr(request()->baseFile(), 'index.php')) {
                $lang = get_admin_lang();
            } else {
                $lang = get_home_lang();
            }
            if (isMobile()) {
                $path = HTML_PATH."other/{$lang}_mobile_cache/";
            } else {
                $path = HTML_PATH."other/{$lang}_pc_cache/";
            }
            $new_conf['path'] = $path;
        }

        if (is_numeric($options)) {
            $new_conf['expire'] = $options;
        }

        $cache_conf = config('cache');
        $cache_conf = array_merge($cache_conf, $new_conf);

        $tag = $cache_conf['prefix'];

        if (!is_array($name)) {
            $name = strtolower($name);
        } else {
            $name = array_merge($cache_conf, $name);
            return cache($name);
        }

        return cache($name, $value, $cache_conf, $tag);
   }   
}

if (!function_exists('typeurl')) {
    /**
     * 欄目Url產生
     * @param string        $url 路由地址
     * @param string|array  $param 變數
     * @param bool|string   $suffix 產生的URL後綴
     * @param bool|string   $domain 域名
     * @param string          $seo_pseudo URL模式
     * @param string          $seo_pseudo_format URL格式
     * @return string
     */
    function typeurl($url = '', $param = '', $suffix = true, $domain = false, $seo_pseudo = null, $seo_pseudo_format = null)
    {
        $eyouUrl = '';
        $uiset = I('param.uiset/s', 'off');
        $uiset = trim($uiset, '/');
        $seo_pseudo = !empty($seo_pseudo) ? $seo_pseudo : config('ey_config.seo_pseudo');
        if (empty($seo_pseudo_format)) {
            if (1 == $seo_pseudo) {
                $seo_pseudo_format = config('ey_config.seo_dynamic_format');
            }
        }

        if ('on' != $uiset && 1 == $seo_pseudo && 2 == $seo_pseudo_format) {
            if (is_array($param)) {
                $vars = array(
                    'tid'   => $param['id'],
                );
                $vars = http_build_query($vars);
            } else {
                $vars = $param;
            }
            $eyouUrl = url($url, array(), $suffix, $domain, $seo_pseudo, $seo_pseudo_format);
            $urlParam = parse_url($eyouUrl);
            $query_str = isset($urlParam['query']) ? $urlParam['query'] : '';
            if (empty($query_str)) {
                $eyouUrl .= '?';
            } else {
                $eyouUrl .= '&';
            }
            $eyouUrl .= $vars;
        } elseif ('on' != $uiset && 2 == $seo_pseudo) {
            $vars = array();
            $url = $param['dirpath']."/";
            $eyouUrl = url($url, $vars, false, request()->domain(), $seo_pseudo, $seo_pseudo_format);
        } elseif ('on' != $uiset && 3 == $seo_pseudo) {
            if (is_array($param)) {
                $vars = array(
                    'tid'   => $param['dirname'],
                );
            } else {
                $vars = $param;
            }
            /*偽靜態格式*/
            $seo_rewrite_format = config('ey_config.seo_rewrite_format');
            if (1 == intval($seo_rewrite_format)) {
                $eyouUrl = url('home/Lists/index', $vars, $suffix, $domain, $seo_pseudo, $seo_pseudo_format).'/';
            } else {
                $eyouUrl = url($url, $vars, $suffix, $domain, $seo_pseudo, $seo_pseudo_format); // 相容v1.1.6之前被搜索引擎收錄的URL
            }
            /*--end*/
        } else {
            if (is_array($param)) {
                $vars = array(
                    'tid'   => $param['id'],
                );
            } else {
                $vars = $param;
            }
            $eyouUrl = url('home/Lists/index', $vars, $suffix, $domain, $seo_pseudo, $seo_pseudo_format);
        }

        // $eyouUrl = auto_hide_index($eyouUrl);

        return $eyouUrl;
    }
}

if (!function_exists('arcurl')) {
    /**
     * 文件Url產生
     * @param string        $url 路由地址
     * @param string|array  $param 變數
     * @param bool|string   $suffix 產生的URL後綴
     * @param bool|string   $domain 域名
     * @param string          $seo_pseudo URL模式
     * @param string          $seo_pseudo_format URL格式
     * @return string
     */
    function arcurl($url = '', $param = '', $suffix = true, $domain = false, $seo_pseudo = '', $seo_pseudo_format = null)
    {
        // \think\Url::root('/');
        $eyouUrl = '';
        $uiset = I('param.uiset/s', 'off');
        $uiset = trim($uiset, '/');
        $seo_pseudo = !empty($seo_pseudo) ? $seo_pseudo : config('ey_config.seo_pseudo');
        if (empty($seo_pseudo_format)) {
            if (1 == $seo_pseudo) {
                $seo_pseudo_format = config('ey_config.seo_dynamic_format');
            }
        }
        
        if ('on' != $uiset && 1 == $seo_pseudo && 2 == $seo_pseudo_format) {
            if (is_array($param)) {
                $vars = array(
                    'aid'   => $param['aid'],
                );
                $vars = http_build_query($vars);
            } else {
                $vars = $param;
            }
            $eyouUrl = url($url, array(), $suffix, $domain, $seo_pseudo, $seo_pseudo_format);
            $urlParam = parse_url($eyouUrl);
            $query_str = isset($urlParam['query']) ? $urlParam['query'] : '';
            if (empty($query_str)) {
                $eyouUrl .= '?';
            } else {
                $eyouUrl .= '&';
            }
            $eyouUrl .= $vars;
        } elseif ($seo_pseudo == 2 && $uiset != 'on') {
            $vars = array();
            $aid = $param['aid'];
            $url = $param['dirpath']."/{$aid}.html";
            $eyouUrl = url($url, $vars, false, request()->domain(), $seo_pseudo, $seo_pseudo_format);
        } elseif ($seo_pseudo == 3 && $uiset != 'on') {
            /*偽靜態格式*/
            $seo_rewrite_format = config('ey_config.seo_rewrite_format');
            if (1 == intval($seo_rewrite_format)) {
                $url = 'home/View/index';
                /*URL里第一層級固定是頂級欄目的目錄名稱*/
                $tdirnameArr = every_top_dirname_list();
                if (!empty($param['dirname']) && isset($tdirnameArr[md5($param['dirname'])]['tdirname'])) {
                    $param['dirname'] = $tdirnameArr[md5($param['dirname'])]['tdirname'];
                }
                /*--end*/
            }
            /*--end*/
            if (is_array($param)) {
                $vars = array(
                    'aid'   => $param['aid'],
                    'dirname'   => $param['dirname'],
                );
            } else {
                $vars = $param;
            }
            $eyouUrl = url($url, $vars, $suffix, $domain, $seo_pseudo, $seo_pseudo_format);
        } else {
            if (is_array($param)) {
                $vars = array(
                    'aid'   => $param['aid'],
                );
                $vars = http_build_query($vars);
            } else {
                $vars = $param;
            }
            $eyouUrl = url('home/View/index', $vars, $suffix, $domain, $seo_pseudo, $seo_pseudo_format);
        }

        // $eyouUrl = auto_hide_index($eyouUrl);

        return $eyouUrl;
    }
}

if (!function_exists('eyIntval')) {
    /**
     * 強制把數值轉為整型
     * @param mixed        $data 任意數值
     * @return mixed
     */
    function eyIntval($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                $data[$key] = intval($val);
            }
        } else if (is_string($data) && stristr($data, ',')) {
            $arr = explode(',', $data);
            foreach ($arr as $key => $val) {
                $arr[$key] = intval($val);
            }
            $data = implode(',', $arr);
        } else {
            $data = intval($data);
        }

        return $data;
    }
}

if (!function_exists('eyPreventShell')) {
    /**
     * 驗證是否shell注入
     * @param mixed        $data 任意數值
     * @return mixed
     */
    function eyPreventShell($data = '')
    {
        $data = true;
        if (is_string($data) && (preg_match('/^phar:\/\//i', $data) || stristr($data, 'phar://'))) {
            $data = false;
        }

        return $data;
    }
}