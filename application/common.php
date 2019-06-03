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

// 關閉所有PHP錯誤報告
error_reporting(0);

include_once EXTEND_PATH."function.php";

// 應用公共檔案

if (!function_exists('switch_exception')) 
{
    // 模板錯誤提示
    function switch_exception() {
        $web_exception = tpCache('web.web_exception');
        if (!empty($web_exception)) {
            config('ey_config.web_exception', $web_exception);
            error_reporting(-1);
        }
    }
}

if (!function_exists('tpCache')) 
{
    /**
     * 獲取快取或者更新快取，只適用於config表
     * @param string $config_key 快取檔名稱
     * @param array $data 快取數據  array('k1'=>'v1','k2'=>'v3')
     * @param array $options 快取配置
     * @param string $lang 語言標識
     * @return array or string or bool
     */
    function tpCache($config_key,$data = array(), $lang = '', $options = null){
        $tableName = 'config';
        $table_db = \think\Db::name($tableName);

        $param = explode('.', $config_key);
        $cache_inc_type = $tableName.$param[0];
        // $cache_inc_type = $param[0];
        $lang = !empty($lang) ? $lang : get_current_lang();
        if (empty($options)) {
            $options['path'] = CACHE_PATH.$lang.DS;
        }
        if(empty($data)){
            //如$config_key=shop_info則獲取網站資訊陣列
            //如$config_key=shop_info.logo則獲取網站logo字串
            $config = cache($cache_inc_type,'',$options);//直接獲取快取檔案
            if(empty($config)){
                //快取檔案不存在就讀取數據庫
                if ($param[0] == 'global') {
                    $param[0] = 'global';
                    $res = $table_db->where([
                        'lang'  => $lang,
                        'is_del'    => 0,
                    ])->select();
                } else {
                    $res = $table_db->where([
                        'inc_type'  => $param[0],
                        'lang'  => $lang,
                        'is_del'    => 0,
                    ])->select();
                }
                if($res){
                    foreach($res as $k=>$val){
                        $config[$val['name']] = $val['value'];
                    }
                    cache($cache_inc_type,$config,$options);
                }
                // write_global_params($lang, $options);
            }
            if(!empty($param) && count($param)>1){
                $newKey = strtolower($param[1]);
                return isset($config[$newKey]) ? $config[$newKey] : '';
            }else{
                return $config;
            }
        }else{
            //更新快取
            $result =  $table_db->where([
                'inc_type'  => $param[0],
                'lang'  => $lang,
                'is_del'    => 0,
            ])->select();
            if($result){
                foreach($result as $val){
                    $temp[$val['name']] = $val['value'];
                }
                $add_data = array();
                foreach ($data as $k=>$v){
                    $newK = strtolower($k);
                    $newArr = array(
                        'name'=>$newK,
                        'value'=>trim($v),
                        'inc_type'=>$param[0],
                        'lang'  => $lang,
                        'update_time'   => getTime(),
                    );
                    if(!isset($temp[$newK])){
                        array_push($add_data, $newArr); //新key數據插入數據庫
                    }else{
                        if ($v != $temp[$newK]) {
                            $table_db->where([
                                'name'  => $newK,
                                'lang'  => $lang,
                            ])->save($newArr);//快取key存在且值有變更新此項
                        }
                    }
                }
                if (!empty($add_data)) {
                    $table_db->insertAll($add_data);
                }
                //更新后的數據庫記錄
                $newRes = $table_db->where([
                    'inc_type'  => $param[0],
                    'lang'  => $lang,
                    'is_del'    => 0,
                ])->select();
                foreach ($newRes as $rs){
                    $newData[$rs['name']] = $rs['value'];
                }
            }else{
                if ($param[0] != 'global') {
                    foreach($data as $k=>$v){
                        $newK = strtolower($k);
                        $newArr[] = array(
                            'name'=>$newK,
                            'value'=>trim($v),
                            'inc_type'=>$param[0],
                            'lang'  => $lang,
                            'update_time'   => time(),
                        );
                    }
                    $table_db->insertAll($newArr);
                }
                $newData = $data;
            }

            $result = false;
            $res = $table_db->where([
                'lang'  => $lang,
                'is_del'    => 0,
            ])->select();
            if($res){
                $global = array();
                foreach($res as $k=>$val){
                    $global[$val['name']] = $val['value'];
                }
                $result = cache($tableName.'global',$global,$options);
            } 

            if ($param[0] != 'global') {
                $result = cache($cache_inc_type,$newData,$options);
            }
            
            return $result;
        }
    }
}

if (!function_exists('write_global_params')) 
{
    /**
     * 寫入全域性內建參數
     * @return array
     */
    function write_global_params($lang = '', $options = null)
    {
        $webConfigParams = \think\Db::name('config')->where([
            'inc_type'  => 'web',
            'lang'  => $lang,
            'is_del'    => 0,
        ])->select();
        $web_basehost = !empty($webConfigParams['web_basehost']) ? $webConfigParams['web_basehost'] : ''; // 網站根網址
        $web_cmspath = !empty($webConfigParams['web_cmspath']) ? $webConfigParams['web_cmspath'] : ''; // EyouCMS安裝目錄
        /*啟用絕對網址，開啟此項后附件、欄目連線、arclist內容等都使用http路徑*/
        $web_multi_site = !empty($webConfigParams['web_multi_site']) ? $webConfigParams['web_multi_site'] : '';
        if($web_multi_site == 1)
        {
            $web_mainsite = $web_basehost;
        }
        else
        {
            $web_mainsite = '';
        }
        /*--end*/
        /*CMS安裝目錄的網址*/
        $param['web_cmsurl'] = $web_mainsite.$web_cmspath;
        /*--end*/
        $param['web_templets_dir'] = $web_cmspath.'/template'; // 前臺模板根目錄
        $param['web_templeturl'] = $web_mainsite.$param['web_templets_dir']; // 前臺模板根目錄的網址
        $param['web_templets_pc'] = $web_mainsite.$param['web_templets_dir'].'/pc'; // 前臺PC模板主題
        $param['web_templets_m'] = $web_mainsite.$param['web_templets_dir'].'/mobile'; // 前臺手機模板主題
        $param['web_eyoucms'] = str_replace('#', '', '#h#t#t#p#:#/#/#w#w#w#.#e#y#o#u#c#m#s#.#c#o#m#'); // eyou網址

        /*將內建的全域性變數(頁面上沒有入口更改的全域性變數)儲存到web版塊里*/
        $inc_type = 'web';
        foreach ($param as $key => $val) {
            if (preg_match("/^".$inc_type."_(.)+/i", $key) !== 1) {
                $nowKey = strtolower($inc_type.'_'.$key);
                $param[$nowKey] = $val;
            }
        }
        tpCache($inc_type, $param, $lang, $options);
        /*--end*/
    }
}

if (!function_exists('write_html_cache')) 
{
    /**
     * 寫入靜態頁面快取
     */
    function write_html_cache($html = ''){
        $html_cache_status = config('HTML_CACHE_STATUS');
        $html_cache_arr = config('HTML_CACHE_ARR');
        if ($html_cache_status && !empty($html_cache_arr) && !empty($html)) {
            $home_lang = get_home_lang(); // 多語言
            $request = \think\Request::instance();
            $param = input('param.');

            /*URL模式是否啟動頁面快取（排除admin後臺、前臺視覺化裝修）*/
            $uiset = input('param.uiset/s', 'off');
            $uiset = trim($uiset, '/');
            if ('on' == $uiset || 'admin' == $request->module()) {
                return false;
            }
            $seo_pseudo = config('ey_config.seo_pseudo');
            if (!in_array($seo_pseudo, array(1,3))) { // 排除普通動態模式
                return false;
            }
            /*--end*/

            if (1 == $seo_pseudo) {
                isset($param['tid']) && $param['tid'] = input('param.tid/d');
            } else {
                isset($param['tid']) && $param['tid'] = input('param.tid/s');
            }
            isset($param['page']) && $param['page'] = input('param.page/d');

            // aid唯一性的處理
            if (isset($param['aid'])) {
                if (strval(intval($param['aid'])) !== strval($param['aid'])) {
                    abort(404,'頁面不存在');
                }
                $param['aid'] = intval($param['aid']);
            }

            $m_c_a_str = $request->module().'_'.$request->controller().'_'.$request->action(); // 模組_控制器_方法
            $m_c_a_str = strtolower($m_c_a_str);
            //exit('write_html_cache寫入快取<br/>');
            foreach($html_cache_arr as $mca=>$val)
            {
                $mca = strtolower($mca);
                if($mca != $m_c_a_str) //不是目前 模組 控制器 方法 直接跳過
                    continue;

                if (empty($val['filename'])) {
                    continue;
                }

                $cache_tag = ''; // 快取標籤
                $filename = '';
                // 組合參數  
                if(isset($val['p']))
                {
                    $tid = '';
                    if (in_array('tid', $val['p'])) {
                        $tid = $param['tid'];
                        if (strval(intval($tid)) != strval($tid)) {
                            $tid = \think\Db::name('arctype')->where([
                                    'dirname'   => $tid,
                                    'lang'  => $home_lang,
                                ])->getField('id');
                            $param['tid']   = $tid;
                        }
                    }

                    foreach ($val['p'] as $k=>$v) {
                        if (isset($param[$v])) {
                            if (preg_match('/\/$/i', $filename)) {
                                $filename .= $param[$v];
                            } else {
                                if (!empty($filename)) {
                                    $filename .= '_';
                                }
                                $filename .= $param[$v];
                            }
                        }
                    }
                    /*針對列表快取的標籤*/
                    !empty($tid) && $cache_tag = $tid;
                    /*--end*/
                    /*針對內容快取的標籤*/
                    $aid = input("param.aid/d");
                    !empty($aid) && $cache_tag = $aid;
                    /*--end*/
                }
                empty($filename) && $filename = 'index';

                // 快取時間
                $web_cmsmode = tpCache('web.web_cmsmode');
                if (1 == intval($web_cmsmode)) { // 永久
                    $path = HTML_PATH.$val['filename'].DS.$home_lang;
                    if (isMobile()) {
                        $path .= "_mobile";
                    } else {
                        $path .= "_pc";
                    }
                    $filename = $path.'_html'.DS."{$filename}.html";
                    tp_mkdir(dirname($filename));
                    !empty($html) && file_put_contents($filename, $html);
                } else {
                    $path = HTML_PATH.$val['filename'].DS.$home_lang;
                    if (isMobile()) {
                        $path .= "_mobile";
                    } else {
                        $path .= "_pc";
                    }
                    $path .= '_cache'.DS;
                    $options = array(
                        'path'  => $path,
                        'expire'=> intval($web_htmlcache_expires_in),
                        'prefix'    => $cache_tag,
                    );
                    !empty($html) && html_cache($filename,$html,$options);
                }
            }
        }
    }
}

if (!function_exists('read_html_cache')) 
{
    /**
     * 讀取靜態頁面快取
     */
    function read_html_cache(){
        $html_cache_status = config('HTML_CACHE_STATUS');
        $html_cache_arr = config('HTML_CACHE_ARR');
        if ($html_cache_status && !empty($html_cache_arr)) {
            $home_lang = get_home_lang();
            $request = \think\Request::instance();
            $seo_pseudo = config('ey_config.seo_pseudo');
            $param = input('param.');

            if (1 == $seo_pseudo) {
                isset($param['tid']) && $param['tid'] = input('param.tid/d');
            } else {
                isset($param['tid']) && $param['tid'] = input('param.tid/s');
            }
            isset($param['page']) && $param['page'] = input('param.page/d');

            // aid唯一性的處理
            if (isset($param['aid'])) {
                if (strval(intval($param['aid'])) !== strval($param['aid'])) {
                    abort(404,'頁面不存在');
                }
                $param['aid'] = intval($param['aid']);
            }

            $m_c_a_str = $request->module().'_'.$request->controller().'_'.$request->action(); // 模組_控制器_方法
            $m_c_a_str = strtolower($m_c_a_str);
            //exit('read_html_cache讀取快取<br/>');
            foreach($html_cache_arr as $mca=>$val)
            {
                $mca = strtolower($mca);
                if($mca != $m_c_a_str) //不是目前 模組 控制器 方法 直接跳過
                    continue;

                if (empty($val['filename'])) {
                    continue;
                }

                $cache_tag = ''; // 快取標籤
                $filename = '';
                // 組合參數  
                if(isset($val['p']))
                {
                    $tid = '';
                    if (in_array('tid', $val['p'])) {
                        $tid = $param['tid'];
                        if (strval(intval($tid)) != strval($tid)) {
                            $tid = \think\Db::name('arctype')->where([
                                    'dirname'   => $tid,
                                    'lang'  => $home_lang,
                                ])->getField('id');
                            $param['tid']   = $tid;
                        }
                    }

                    foreach ($val['p'] as $k=>$v) {
                        if (isset($param[$v])) {
                            if (preg_match('/\/$/i', $filename)) {
                                $filename .= $param[$v];
                            } else {
                                if (!empty($filename)) {
                                    $filename .= '_';
                                }
                                $filename .= $param[$v];
                            }
                        }
                    }
                    /*針對列表快取的標籤*/
                    !empty($tid) && $cache_tag = $tid;
                    /*--end*/
                    /*針對內容快取的標籤*/
                    $aid = input("param.aid/d");
                    !empty($aid) && $cache_tag = $aid;
                    /*--end*/
                }
                empty($filename) && $filename = 'index';

                // 快取時間
                $web_cmsmode = tpCache('web.web_cmsmode');
                if (1 == intval($web_cmsmode)) { // 永久
                    $path = HTML_PATH.$val['filename'].DS.$home_lang;
                    if (isMobile()) {
                        $path .= "_mobile";
                    } else {
                        $path .= "_pc";
                    }
                    $filename = $path.'_html'.DS."{$filename}.html";
                    if(is_file($filename) && file_exists($filename))
                    {
                        echo file_get_contents($filename);
                        exit();
                    }
                } else {
                    $path = HTML_PATH.$val['filename'].DS.$home_lang;
                    if (isMobile()) {
                        $path .= "_mobile";
                    } else {
                        $path .= "_pc";
                    }
                    $path .= '_cache'.DS;
                    $options = array(
                        'path'  => $path,
                        'expire'=> intval($web_htmlcache_expires_in),
                        'prefix'    => $cache_tag,
                    );
                    $html = html_cache($filename, '', $options);
                    // $html = $html_cache->get($filename);
                    if($html)
                    {
                        echo $html;
                        exit();
                    }
                }
            }
        }
    }
}

if (!function_exists('get_head_pic')) 
{
    /**
     * 預設頭像
     */
    function get_head_pic($pic_url = '')
    {
        $default_pic = ROOT_DIR . '/public/static/common/images/bag-imgB.jpg';
        return empty($pic_url) ? $default_pic : $pic_url;
    }
}

if (!function_exists('get_default_pic')) 
{
    /**
     * 圖片不存在，顯示預設無圖封面
     * @param string $pic_url 圖片路徑
     * @param string|boolean $domain 完整路徑的域名
     */
    function get_default_pic($pic_url = '', $domain = false)
    {
        if (!is_http_url($pic_url)) {
            if (true === $domain) {
                $domain = request()->domain();
            } else if (false === $domain) {
                $domain = '';
            }
            
            $pic_url = preg_replace('#^(/[/\w]+)?(/public/upload/|/uploads/)#i', '$2', $pic_url); // 支援子目錄
            $realpath = realpath(trim($pic_url, '/'));
            if ( is_file($realpath) && file_exists($realpath) ) {
                $pic_url = $domain . ROOT_DIR . $pic_url;
            } else {
                $pic_url = $domain . ROOT_DIR . '/public/static/common/images/not_adv.jpg';
            }
        }

        return $pic_url;
    }
}

if (!function_exists('handle_subdir_pic')) 
{
    /**
     * 處理子目錄與根目錄的圖片平緩切換
     * @param string $str 圖片路徑或html程式碼
     */
    function handle_subdir_pic($str = '', $type = 'img')
    {
        $root_dir = ROOT_DIR;
        switch ($type) {
            case 'img':
                if (!is_http_url($str) && !empty($str)) {
                    // if (!empty($root_dir)) { // 子目錄之間切換
                        $str = preg_replace('#^(/[/\w]+)?(/public/upload/|/uploads/)#i', $root_dir.'$2', $str);
                    // } else { // 子目錄與根目錄切換
                        // $str = preg_replace('#^(/[/\w]+)?(/public/upload/|/uploads/)#i', $root_dir.'$2', $str);
                    // }
                }else if (is_http_url($str) && !empty($str)) {
                    // 圖片路徑處理
                    $str     = preg_replace('#^(/[/\w]+)?(/public/upload/|/uploads/)#i', $root_dir.'$2', $str);
                    $StrData = parse_url($str);
                    $strlen  = strlen($root_dir);
                    if (empty($StrData['scheme'])) {
                        if ('/uploads/'==substr($StrData['path'],$strlen,9) || '/public/upload/'==substr($StrData['path'],$strlen,15)) {
                            // 七牛云配置處理
                            static $Qiniuyun = null;
                            if (null == $Qiniuyun) {
                                // 需要填寫你的 Access Key 和 Secret Key
                                $data     = M('weapp')->where('code','Qiniuyun')->field('data,status')->find();
                                $Qiniuyun = json_decode($data['data'], true);
                                $Qiniuyun['status'] = $data['status'];
                            }

                            // 是否開啟圖片加速
                            if ('1' == $Qiniuyun['status']) {
                                // 開啟
                                if ($Qiniuyun['domain'] == $StrData['host']) {
                                    $tcp = !empty($Qiniuyun['tcp']) ? $Qiniuyun['tcp'] : '';
                                    switch ($tcp) {
                                        case '2':
                                            $tcp = 'https://';
                                            break;

                                        case '3':
                                            $tcp = '//';
                                            break;
                                        
                                        case '1':
                                        default:
                                            $tcp = 'http://';
                                            break;
                                    }
                                    $str = $tcp.$Qiniuyun['domain'].$StrData['path'];
                                }else{
                                    // 若切換了儲存空間或訪問域名，與數據庫中儲存的圖片路徑域名不一致時，訪問本地路徑，保證圖片正常
                                    $str = $StrData['path'];
                                }
                            }else{
                                // 關閉
                                $str = $StrData['path'];
                            }
                        }
                    }
                }
                break;

            case 'html':
                // if (!empty($root_dir)) { // 子目錄之間切換
                    $str = preg_replace('#(.*)(\#39;|&quot;|"|\')(/[/\w]+)?(/public/upload/|/public/plugins/|/uploads/)(.*)#iU', '$1$2'.$root_dir.'$4$5', $str);
                // } else { // 子目錄與根目錄切換
                    // $str = preg_replace('#(.*)(\#39;|&quot;|"|\')(/[/\w]+)?(/public/upload/|/public/plugins/|/uploads/)(.*)#iU', '$1$2'.$root_dir.'$4$5', $str);
                // }
                break;
            
            default:
                # code...
                break;
        }

        return $str;
    }
}

/**
 * 獲取閱讀許可權
 */
if ( ! function_exists('get_arcrank_list'))
{
    function get_arcrank_list()
    {
        $result = \think\Db::name('arcrank')->where([
                'lang'  => get_admin_lang(),
            ])
            ->order('id asc')
            ->cache(true,0,"arcrank")
            ->getAllWithIndex('rank');

        return $result;
    }
}

if (!function_exists('thumb_img')) 
{
    /**
     * 縮圖 從原始圖來處理出來
     * @param type $original_img  圖片路徑
     * @param type $width     產生縮圖的寬度
     * @param type $height    產生縮圖的高度
     * @param type $thumb_mode    產生方式
     */
    function thumb_img($original_img = '', $width = '', $height = '', $thumb_mode = '')
    {
        // 縮圖配置
        $thumbConfig = tpCache('thumb');
        $thumbextra = config('global.thumb');

        if (!empty($width) || !empty($height) || !empty($thumb_mode)) { // 單獨在模板里呼叫，不受縮圖全域性開關影響

        } else { // 非單獨模板呼叫
            if (empty($thumbConfig['thumb_open'])) {
                return $original_img;
            }
        }

        // 未開啟縮圖，或遠端圖片
        if (is_http_url($original_img) || stristr($original_img, '/public/static/common/images/not_adv.jpg')) {
            return $original_img;
        } else if (empty($original_img)) {
            return ROOT_DIR.'/public/static/common/images/not_adv.jpg';
        }

        // 圖片檔名
        $filename = '';
        $imgArr = explode('/', $original_img);    
        $imgArr = end($imgArr);
        $filename = preg_replace("/\.([^\.]+)$/i", "", $imgArr);

        // 如果圖片參數是縮圖，則直接獲取到原圖，並進行縮略處理
        if (preg_match('/\/uploads\/thumb\/\d{1,}_\d{1,}\//i', $original_img)) {
            $file_ext = preg_replace("/^(.*)\.([^\.]+)$/i", "$2", $imgArr);
            $pattern = UPLOAD_PATH.'allimg/*/'.$filename;
            if (in_array(strtolower($file_ext), ['jpg','jpeg'])) {
                $pattern .= '.jp*g';
            } else {
                $pattern .= '.'.$file_ext;
            }
            $original_img_tmp = glob($pattern);
            if (!empty($original_img_tmp)) {
                $original_img = '/'.current($original_img_tmp);
            }
        }
        // --end

        $original_img1 = preg_replace('#^'.ROOT_DIR.'#i', '', handle_subdir_pic($original_img));
        $original_img1 = '.' . $original_img1; // 相對路徑
        //獲取影象資訊
        $info = @getimagesize($original_img1);
        //檢測影象合法性
        if (false === $info || (IMAGETYPE_GIF === $info[2] && empty($info['bits']))) {
            return $original_img;
        }

        // 縮圖寬高度
        empty($width) && $width = !empty($thumbConfig['thumb_width']) ? $thumbConfig['thumb_width'] : $thumbextra['width'];
        empty($height) && $height = !empty($thumbConfig['thumb_height']) ? $thumbConfig['thumb_height'] : $thumbextra['height'];
        $width = intval($width);
        $height = intval($height);

        //判斷縮圖是否存在
        $path = UPLOAD_PATH."thumb/{$width}_{$height}/";
        $img_thumb_name = "{$filename}";

        // 已經產生過這個比例的圖片就直接返回了
        if (is_file($path . $img_thumb_name . '.jpg')) return ROOT_DIR.'/' . $path . $img_thumb_name . '.jpg';
        if (is_file($path . $img_thumb_name . '.jpeg')) return ROOT_DIR.'/' . $path . $img_thumb_name . '.jpeg';
        if (is_file($path . $img_thumb_name . '.gif')) return ROOT_DIR.'/' . $path . $img_thumb_name . '.gif';
        if (is_file($path . $img_thumb_name . '.png')) return ROOT_DIR.'/' . $path . $img_thumb_name . '.png';
        if (is_file($path . $img_thumb_name . '.bmp')) return ROOT_DIR.'/' . $path . $img_thumb_name . '.bmp';

        if (!is_file($original_img1)) {
            return ROOT_DIR.'/public/static/common/images/not_adv.jpg';
        }

        try {
            vendor('topthink.think-image.src.Image');
            vendor('topthink.think-image.src.image.Exception');
            if(stristr($original_img1,'.gif'))
            {
                vendor('topthink.think-image.src.image.gif.Encoder');
                vendor('topthink.think-image.src.image.gif.Decoder');
                vendor('topthink.think-image.src.image.gif.Gif');               
            }           
            $image = \think\Image::open($original_img1);

            $img_thumb_name = $img_thumb_name . '.' . $image->type();
            // 產生縮圖
            !is_dir($path) && mkdir($path, 0777, true);
            // 填充顏色
            $thumb_color = !empty($thumbConfig['thumb_color']) ? $thumbConfig['thumb_color'] : $thumbextra['color'];
            // 產生方式參考 vendor/topthink/think-image/src/Image.php
            if (!empty($thumb_mode)) {
                $thumb_mode = intval($thumb_mode);
            } else {
                $thumb_mode = !empty($thumbConfig['thumb_mode']) ? $thumbConfig['thumb_mode'] : $thumbextra['mode'];
            }
            1 == $thumb_mode && $thumb_mode = 6; // 按照固定比例拉伸
            2 == $thumb_mode && $thumb_mode = 2; // 填充空白
            if (3 == $thumb_mode) {
                $img_width = $image->width();
                $img_height = $image->height();
                if ($width < $img_width && $height < $img_height) {
                    // 先進行縮圖等比例縮放型別，取出寬高中最小的屬性值
                    $min_width = ($img_width < $img_height) ? $img_width : 0;
                    $min_height = ($img_width > $img_height) ? $img_height : 0;
                    if ($min_width > $width || $min_height > $height) {
                        if (0 < intval($min_width)) {
                            $scale = $min_width / min($width, $height);
                        } else if (0 < intval($min_height)) {
                            $scale = $min_height / $height;
                        } else {
                            $scale = $min_width / $width;
                        }
                        $s_width  = $img_width / $scale;
                        $s_height = $img_height / $scale;
                        $image->thumb($s_width, $s_height, 1, $thumb_color)->save($path . $img_thumb_name, NULL, 100); //按照原圖的比例產生一個最大為$width*$height的縮圖並儲存
                    }
                }
                $thumb_mode = 3; // 截減
            }
            // 參考文章 http://www.mb5u.com/biancheng/php/php_84533.html  改動參考 http://www.thinkphp.cn/topic/13542.html
            $image->thumb($width, $height, $thumb_mode, $thumb_color)->save($path . $img_thumb_name, NULL, 100); //按照原圖的比例產生一個最大為$width*$height的縮圖並儲存
            //圖片水印處理
            $water = tpCache('water');
            if($water['is_mark']==1 && $water['is_thumb_mark'] == 1 && $image->width()>$water['mark_width'] && $image->height()>$water['mark_height']){
                $imgresource = '.' . ROOT_DIR . '/' . $path . $img_thumb_name;
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
            $img_url = ROOT_DIR.'/' . $path . $img_thumb_name;

            return $img_url;

        } catch (think\Exception $e) {

            return $original_img;
        }
    }
}

if (!function_exists('get_product_sub_images')) 
{
    /**
     * 產品相簿縮圖
     */
    function get_product_sub_images($sub_img, $aid, $width, $height)
    {
        //判斷縮圖是否存在
        $path = "public/upload/product/thumb/$aid/";
        $product_thumb_name = "product_sub_thumb_{$sub_img['img_id']}_{$width}_{$height}";
        
        //這個縮圖 已經產生過這個比例的圖片就直接返回了
        if (is_file($path . $product_thumb_name . '.jpg')) return '/' . $path . $product_thumb_name . '.jpg';
        if (is_file($path . $product_thumb_name . '.jpeg')) return '/' . $path . $product_thumb_name . '.jpeg';
        if (is_file($path . $product_thumb_name . '.gif')) return '/' . $path . $product_thumb_name . '.gif';
        if (is_file($path . $product_thumb_name . '.png')) return '/' . $path . $product_thumb_name . '.png';

        $ossClient = new \app\common\logic\OssLogic;
        if (($ossUrl = $ossClient->getProductAlbumThumbUrl($sub_img['image_url'], $width, $height))) {
            return $ossUrl;
        }
        
        $original_img = '.' . $sub_img['image_url']; //相對路徑
        if (!is_file($original_img)) {
            return '/public/static/common/images/not_adv.jpg';
        }

        try {
            vendor('topthink.think-image.src.Image');
            if(strstr(strtolower($original_img),'.gif'))
            {
                vendor('topthink.think-image.src.image.gif.Encoder');
                vendor('topthink.think-image.src.image.gif.Decoder');
                vendor('topthink.think-image.src.image.gif.Gif');
            }
            $image = \think\Image::open($original_img);

            $product_thumb_name = $product_thumb_name . '.' . $image->type();
            // 產生縮圖
            !is_dir($path) && mkdir($path, 0777, true);
            // 參考文章 http://www.mb5u.com/biancheng/php/php_84533.html  改動參考 http://www.thinkphp.cn/topic/13542.html
            $image->thumb($width, $height, 2)->save($path . $product_thumb_name, NULL, 100); //按照原圖的比例產生一個最大為$width*$height的縮圖並儲存
            //圖片水印處理
            $water = tpCache('water');
            if ($water['is_mark'] == 1) {
                $imgresource = './' . $path . $product_thumb_name;
                if ($width > $water['mark_width'] && $height > $water['mark_height']) {
                    if ($water['mark_type'] == 'img') {
                        //檢查水印圖片是否存在
                        $waterPath = "." . $water['mark_img'];
                        if (is_file($waterPath)) {
                            $quality = $water['mark_quality'] ?: 80;
                            $waterTempPath = dirname($waterPath).'/temp_'.basename($waterPath);
                            $image->open($waterPath)->save($waterTempPath, null, $quality);
                            $image->open($imgresource)->water($waterTempPath, $water['mark_sel'], $water['mark_degree'])->save($imgresource);
                            @unlink($waterTempPath);
                        }
                    } else {
                        //檢查字型檔案是否存在,注意是否有字型檔案
                        $ttf = ROOT_PATH.'public/static/common/font/hgzb.ttf';
                        if (file_exists($ttf)) {
                            $size = $water['mark_txt_size'] ?: 30;
                            $color = $water['mark_txt_color'] ?: '#000000';
                            if (!preg_match('/^#[0-9a-fA-F]{6}$/', $color)) {
                                $color = '#000000';
                            }
                            $transparency = intval((100 - $water['mark_degree']) * (127/100));
                            $color .= dechex($transparency);
                            $image->open($imgresource)->text($water['mark_txt'], $ttf, $size, $color, $water['mark_sel'])->save($imgresource);
                        }
                    }
                }
            }
            $img_url = '/' . $path . $product_thumb_name;

            return $img_url;
        } catch (think\Exception $e) {

            return $original_img;
        }
    }
}

if (!function_exists('get_controller_byct')) {
    /**
     * 根據模型ID獲取控制器的名稱
     * @return mixed
     */
    function get_controller_byct($current_channel)
    {
        $channeltype_info = model('Channeltype')->getInfo($current_channel);
        return $channeltype_info['ctl_name'];
    }
}

if (!function_exists('ui_read_bidden_inc')) {
    /**
     * 讀取被禁止外部訪問的配置檔案
     * @param string $filename 檔案路徑
     * @return mixed
     */
    function ui_read_bidden_inc($filename)
    {
        $data = false;
        if (file_exists($filename)) {
            $data = @file($filename);
            $data = json_decode($data[1], true);
        }

        if (empty($data)) {
            // -------------優先讀取配置檔案，不存在才讀取數據表
            $params = explode('/', $filename);
            $page = $params[count($params) - 1];
            $pagearr = explode('.', $page);
            reset($pagearr);
            $page = current($pagearr);
            $map = array(
                'page'   => $page,
                'theme_style'   => THEME_STYLE,
            );
            $result = M('ui_config')->where($map)->cache(true,EYOUCMS_CACHE_TIME,"ui_config")->select();
            if ($result) {
                $dataArr = array();
                foreach ($result as $key => $val) {
                    $k = "{$val['lang']}_{$val['type']}_{$val['name']}";
                    $dataArr[$k] = $val['value'];
                }
                $data = $dataArr;
            } else {
                $data = false;
            }
            //---------------end

            if (!empty($data)) {
                // ----------檔案不存在，並寫入檔案快取
                tp_mkdir(dirname($filename));
                $nowData = $data;
                $setting = "<?php die('forbidden'); ?>\n";
                $setting .= json_encode($nowData);
                $setting = str_replace("\/", "/",$setting);
                $incFile = fopen($filename, "w+");
                if ($incFile != false && fwrite($incFile, $setting)) {
                    fclose($incFile);
                }
                //---------------end
            }
        }
        
        return $data;
    }
}

if (!function_exists('ui_write_bidden_inc')) {
    /**
     * 寫入被禁止外部訪問的配置檔案
     * @param array $arr 配置變數
     * @param string $filename 檔案路徑
     * @param bool $is_append false
     * @return mixed
     */
    function ui_write_bidden_inc($data, $filename, $is_append = false)
    {
        $data2 = $data;
        if (!empty($filename)) {

            // -------------寫入數據表，同時寫入配置檔案
            reset($data2);
            $value = current($data2);
            $tmp_val = json_decode($value, true);
            $name = $tmp_val['id'];
            $type = $tmp_val['type'];
            $page = $tmp_val['page'];
            $lang = !empty($tmp_val['lang']) ? $tmp_val['lang'] : cookie(config('global.home_lang'));
            if (empty($lang)) {
                $lang = model('language')->order('id asc')
                    ->limit(1)
                    ->cache(true, EYOUCMS_CACHE_TIME, 'language')
                    ->getField('mark');
            }
            $theme_style = THEME_STYLE;
            $md5key = md5($name.$page.$theme_style.$lang);
            $savedata = array(
                'md5key'    => $md5key,
                'theme_style'  => $theme_style,
                'page'  => $page,
                'type'  => $type,
                'name'  => $name,
                'value' => $value,
                'lang'  => $lang,
            );
            $map = array(
                'name'   => $name,
                'page'   => $page,
                'theme_style'   => $theme_style,
                'lang'   => $lang,
            );
            $count = M('ui_config')->where($map)->count('id');
            if ($count > 0) {
                $savedata['update_time'] = getTime();
                $r = M('ui_config')->where($map)->cache(true,EYOUCMS_CACHE_TIME,'ui_config')->update($savedata);
            } else {
                $savedata['add_time'] = getTime();
                $savedata['update_time'] = getTime();
                $r = M('ui_config')->insert($savedata);
                \think\Cache::clear('ui_config');
            }

            if ($r) {

                // ----------同時寫入檔案快取
                tp_mkdir(dirname($filename));

                // 追加
                if ($is_append) {
                    $inc = ui_read_bidden_inc($filename);
                    if ($inc) {
                        $oldarr = (array)$inc;
                        $data = array_merge($oldarr, $data);
                    }
                }

                $setting = "<?php die('forbidden'); ?>\n";
                $setting .= json_encode($data);
                $setting = str_replace("\/", "/",$setting);
                $incFile = fopen($filename, "w+");
                if ($incFile != false && fwrite($incFile, $setting)) {
                    fclose($incFile);
                }
                //---------------end

                return true;
            }
        }

        return false;
    }
}

if (!function_exists('get_ui_inc_params')) {
    /**
     * 獲取模板主題的美化配置參數
     * @return mixed
     */
    function get_ui_inc_params($page)
    {
        $e_page = $page;
        $filename = RUNTIME_PATH.'ui/'.THEME_STYLE.'/'.$e_page.'.inc.php';
        $inc = ui_read_bidden_inc($filename);

        return $inc;
    }
}

if (!function_exists('allow_release_arctype')) 
{
    /**
     * 允許發佈文件的欄目列表
     */
    function allow_release_arctype($selected = 0, $allow_release_channel = array(), $selectform = true)
    {
        $where = [];

        $where['c.lang']   = get_current_lang(); // 多語言 by 小虎哥
        $where['c.is_del'] = 0; // 回收站功能

        /*許可權控制 by 小虎哥*/
        $admin_info = session('admin_info');
        if (0 < intval($admin_info['role_id'])) {
            $auth_role_info = $admin_info['auth_role_info'];
            if(! empty($auth_role_info)){
                if(! empty($auth_role_info['permission']['arctype'])){
                    $where['c.id'] = array('IN', $auth_role_info['permission']['arctype']);
                }
            }
        }
        /*--end*/

        if (!is_array($selected)) {
            $selected = [$selected];
        }

        $cacheKey = json_encode($selected).json_encode($allow_release_channel).$selectform.json_encode($where);
        $select_html = cache($cacheKey);
        if (empty($select_html) || false == $selectform) {
            /*允許發佈文件的模型*/
            $allow_release_channel = !empty($allow_release_channel) ? $allow_release_channel : config('global.allow_release_channel');

            /*所有欄目分類*/
            $arctype_max_level = intval(config('global.arctype_max_level'));
            $where['c.status'] = 1;
            $fields = "c.id, c.parent_id, c.current_channel, c.typename, c.grade, count(s.id) as has_children, '' as children";
            $res = db('arctype')
                ->field($fields)
                ->alias('c')
                ->join('__ARCTYPE__ s','s.parent_id = c.id','LEFT')
                ->where($where)
                ->group('c.id')
                ->order('c.parent_id asc, c.sort_order asc, c.id')
                ->cache(true,EYOUCMS_CACHE_TIME,"arctype")
                ->select();
            /*--end*/
            if (empty($res)) {
                return '';
            }

            /*過濾掉第三級欄目屬於不允許發佈的模型下*/
            foreach ($res as $key => $val) {
                if ($val['grade'] == ($arctype_max_level - 1) && !in_array($val['current_channel'], $allow_release_channel)) {
                    unset($res[$key]);
                }
            }
            /*--end*/

            /*所有欄目列表進行層次歸類*/
            $arr = group_same_key($res, 'parent_id');
            for ($i=0; $i < $arctype_max_level; $i++) {
                foreach ($arr as $key => $val) {
                    foreach ($arr[$key] as $key2 => $val2) {
                        if (!isset($arr[$val2['id']])) {
                            $arr[$key][$key2]['has_children'] = 0;
                            continue;
                        }
                        $val2['children'] = $arr[$val2['id']];
                        $arr[$key][$key2] = $val2;
                    }
                }
            }
            /*--end*/

            /*過濾掉第二級不包含允許發佈模型的欄目*/
            $nowArr = $arr[0];
            foreach ($nowArr as $key => $val) {
                if (!empty($nowArr[$key]['children'])) {
                    foreach ($nowArr[$key]['children'] as $key2 => $val2) {
                        if (empty($val2['children']) && !in_array($val2['current_channel'], $allow_release_channel)) {
                            unset($nowArr[$key]['children'][$key2]);
                        }
                    }
                }
                if (empty($nowArr[$key]['children']) && !in_array($nowArr[$key]['current_channel'], $allow_release_channel)) {
                    unset($nowArr[$key]);
                    continue;
                }
            }
            /*--end*/

            /*組裝成層級下拉選單框*/
            $select_html = '';
            if (false == $selectform) {
                $select_html = $nowArr;
            } else if (true == $selectform) {
                foreach ($nowArr AS $key => $val)
                {
                    $select_html .= '<option value="' . $val['id'] . '" data-grade="' . $val['grade'] . '" data-current_channel="' . $val['current_channel'] . '"';
                    $select_html .= (in_array($val['id'], $selected)) ? ' selected="ture"' : '';
                    if (!empty($allow_release_channel) && !in_array($val['current_channel'], $allow_release_channel)) {
                        $select_html .= ' disabled="true" style="background-color:#f5f5f5;"';
                    }
                    $select_html .= '>';
                    if ($val['grade'] > 0)
                    {
                        $select_html .= str_repeat('&nbsp;', $val['grade'] * 4);
                    }
                    $select_html .= htmlspecialchars(addslashes($val['typename'])) . '</option>';

                    if (empty($val['children'])) {
                        continue;
                    }
                    foreach ($nowArr[$key]['children'] as $key2 => $val2) {
                        $select_html .= '<option value="' . $val2['id'] . '" data-grade="' . $val2['grade'] . '" data-current_channel="' . $val2['current_channel'] . '"';
                        $select_html .= (in_array($val2['id'], $selected)) ? ' selected="ture"' : '';
                        if (!empty($allow_release_channel) && !in_array($val2['current_channel'], $allow_release_channel)) {
                            $select_html .= ' disabled="true" style="background-color:#f5f5f5;"';
                        }
                        $select_html .= '>';
                        if ($val2['grade'] > 0)
                        {
                            $select_html .= str_repeat('&nbsp;', $val2['grade'] * 4);
                        }
                        $select_html .= htmlspecialchars(addslashes($val2['typename'])) . '</option>';

                        if (empty($val2['children'])) {
                            continue;
                        }
                        foreach ($nowArr[$key]['children'][$key2]['children'] as $key3 => $val3) {
                            $select_html .= '<option value="' . $val3['id'] . '" data-grade="' . $val3['grade'] . '" data-current_channel="' . $val3['current_channel'] . '"';
                            $select_html .= (in_array($val3['id'], $selected)) ? ' selected="ture"' : '';
                            if (!empty($allow_release_channel) && !in_array($val3['current_channel'], $allow_release_channel)) {
                                $select_html .= ' disabled="true" style="background-color:#f5f5f5;"';
                            }
                            $select_html .= '>';
                            if ($val3['grade'] > 0)
                            {
                                $select_html .= str_repeat('&nbsp;', $val3['grade'] * 4);
                            }
                            $select_html .= htmlspecialchars(addslashes($val3['typename'])) . '</option>';
                        }
                    }
                }

                cache($cacheKey, $select_html, null, 'admin_archives_release');
                
            }
        }

        return $select_html;
    }
}

if (!function_exists('every_top_dirname_list')) 
{
    /**
     * 獲取一級欄目的目錄名稱
     */
    function every_top_dirname_list() {
        $arctypeModel = new \app\common\model\Arctype();
        $result = $arctypeModel->getEveryTopDirnameList();
        
        return $result;
    }
}

if (!function_exists('gettoptype')) 
{
    /**
     * 獲取目前欄目的第一級欄目
     */
    function gettoptype($typeid, $field = 'typename')
    {
        $parent_list = model('Arctype')->getAllPid($typeid); // 獲取目前欄目的所有父級欄目
        $result = current($parent_list); // 第一級欄目
        if (isset($result[$field]) && !empty($result[$field])) {
            return handle_subdir_pic($result[$field]); // 支援子目錄
        } else {
            return '';
        }
    }
}

if (!function_exists('get_main_lang')) 
{
    /**
     * 獲取主體語言（語言列表里最早的一條）
     */
    function get_main_lang()
    {
        $keys = 'common_get_main_lang';
        $main_lang = \think\Cache::get($keys);
        if (empty($main_lang)) {
            $main_lang = \think\Db::name('language')->order('id asc')
                ->limit(1)
                ->cache(true, EYOUCMS_CACHE_TIME, 'language')
                ->getField('mark');
            \think\Cache::set($keys, $main_lang);
        }

        return $main_lang;
    }
}

if (!function_exists('get_default_lang')) 
{
    /**
     * 獲取預設語言
     */
    function get_default_lang()
    {
        $request = \think\Request::instance();
        if (!stristr($request->baseFile(), 'index.php')) {
            $default_lang = get_admin_lang();
        } else {
            $default_lang = \think\Config::get('ey_config.system_home_default_lang');
        }

        return $default_lang;
    }
}

if (!function_exists('get_current_lang')) 
{
    /**
     * 獲取目前預設語言
     */
    function get_current_lang()
    {
        $request = \think\Request::instance();
        if (!stristr($request->baseFile(), 'index.php')) {
            $current_lang = get_admin_lang();
        } else {
            $current_lang = get_home_lang();
        }

        return $current_lang;
    }
}

if (!function_exists('get_admin_lang')) 
{
    /**
     * 獲取後臺目前語言
     */
    function get_admin_lang()
    {
        $keys = \think\Config::get('global.admin_lang');
        $admin_lang = \think\Cookie::get($keys);
        if (empty($admin_lang)) {
            $admin_lang = input('param.lang/s');
            empty($admin_lang) && $admin_lang = get_main_lang();
            \think\Cookie::set($keys, $admin_lang);
        }

        return $admin_lang;
    }
}

if (!function_exists('get_home_lang')) 
{
    /**
     * 獲取前臺目前語言
     */
    function get_home_lang()
    {
        $keys = \think\Config::get('global.home_lang');
        $home_lang = \think\Cookie::get($keys);
        if (empty($home_lang)) {
            $home_lang = input('param.lang/s');
            if (empty($home_lang)) {
                $home_lang = \think\Db::name('language')->where([
                        'is_home_default'   => 1,
                        'status'    => 1,
                    ])->getField('mark');
            }
            \think\Cookie::set($keys, $home_lang);
        }

        return $home_lang;
    }
}

if (!function_exists('is_language')) 
{
    /**
     * 是否多語言
     */
    function is_language()
    {
        $module = \think\Request::instance()->module();
        if (empty($module)) {
            $system_langnum = tpCache('system.system_langnum');
        } else {
            $system_langnum = config('ey_config.system_langnum');
        }

        if (1 < intval($system_langnum)) {
            return $system_langnum;
        } else {
            return false;
        }
    }
}

if (!function_exists('switch_language')) 
{
    /**
     * 多語言切換（預設中文）
     *
     * @param string $lang   語言變數值
     * @return void
     */
    function switch_language($lang = null) 
    {
        static $language_db = null;
        static $request = null;
        if (null == $language_db) {
            $language_db = \think\Db::name('language');
        }
        if (null == $request) {
            $request = \think\Request::instance();
        }

        $is_admin = false;
        if (!stristr($request->baseFile(), 'index.php')) {
            $is_admin = true;
            $langCookieVar = \think\Config::get('global.admin_lang');
        } else {
            $langCookieVar = \think\Config::get('global.home_lang');
        }
        \think\Lang::setLangCookieVar($langCookieVar);

        /*單語言執行程式碼*/
        $langRow = \think\Db::name('language')->field('mark')
            ->order('id asc')
            ->select();
        if (1 >= count($langRow)) {
            $langRow = current($langRow);
            $lang = $langRow['mark'];
            \think\Config::set('cache.path', CACHE_PATH.$lang.DS);
            \think\Cookie::set($langCookieVar, $lang);
            return true;
        }
        /*--end*/

        $current_lang = '';
        /*相容偽靜態多語言切換*/
        $pathinfo = $request->pathinfo();
        if (!empty($pathinfo)) {
            // $seo_pseudo = tpCache('seo.seo_pseudo');
            // if (3 == $seo_pseudo) {
                $s_arr = explode('/', $pathinfo);
                $count = $language_db->where(['mark'=>$s_arr[0]])->count();
                if (!empty($count)) {
                    $current_lang = $s_arr[0];
                }
            // }
        }
        /*--end*/

        $lang = $request->param('lang/s', $current_lang);
        $lang = trim($lang, '/');
        if (!empty($lang)) {
            // 處理訪問不存在的語言
            $lang = $language_db->where('mark',$lang)->getField('mark');
        }
        if (empty($lang)) {
            if ($is_admin) {
                // $current_lang = session('?admin_info.mark_lang') ? session('admin_info.mark_lang') : 'cn';
                $lang = \think\Db::name('language')->order('id asc')
                    ->getField('mark');
            } else {
                $lang = $language_db->where('is_home_default',1)->getField('mark');
            }
            // $lang = !empty($current_lang) ? $current_lang : get_main_lang();//\think\Lang::detect();
        }
        \think\Config::set('cache.path', CACHE_PATH.$lang.DS);
        $pre_lang = \think\Cookie::get($langCookieVar);
        \think\Cookie::set($langCookieVar, $lang);
        if ($pre_lang != $lang) {
            if ($is_admin) {
                \think\Db::name('admin')->where('admin_id', \think\Session::get('admin_id'))->update([
                    'mark_lang' =>  $lang,
                    'update_time'   => getTime(),
                ]);
            }
        }
    }
}

if (!function_exists('getUsersConfigData')) 
{
    // 專用於獲取users_config，會員配置表數據處理。
    // 參數1：必須傳入，傳入值不同，獲取數據不同：
    // 例：獲取配置所有數據，傳入：all，
    // 獲取分組所有數據，傳入：分組標識，如：member，
    // 獲取分組中的單個數據，傳入：分組標識.名稱標識，如：users.users_open_register
    // 參數2：data數據，為空則查詢，否則為新增或修改。
    // 參數3：多語言標識，為空則獲取目前預設語言。
    function getUsersConfigData($config_key,$data=array(),$lang='', $options = null){
        $tableName = 'users_config';
        $table_db = \think\Db::name($tableName);

        $param = explode('.', $config_key);
        $cache_inc_type = $tableName.$param[0];
        $lang = !empty($lang) ? $lang : get_current_lang();
        if (empty($options)) {
            $options['path'] = CACHE_PATH.$lang.DS;
        }
        if(empty($data)){
            //如$config_key=shop_info則獲取網站資訊陣列
            //如$config_key=shop_info.logo則獲取網站logo字串
            $config = cache($cache_inc_type,'',$options);//直接獲取快取檔案
            if(empty($config)){
                //快取檔案不存在就讀取數據庫
                if ($param[0] == 'all') {
                    $param[0] = 'all';
                    $res = $table_db->where([
                        'lang'  => $lang,
                    ])->select();
                } else {
                    $res = $table_db->where([
                        'inc_type'  => $param[0],
                        'lang'  => $lang,
                    ])->select();
                }
                if($res){
                    foreach($res as $k=>$val){
                        $config[$val['name']] = $val['value'];
                    }
                    cache($cache_inc_type,$config,$options);
                }
            }
            if(!empty($param) && count($param)>1){
                $newKey = strtolower($param[1]);
                return isset($config[$newKey]) ? $config[$newKey] : '';
            }else{
                return $config;
            }
        }else{
            //更新快取
            $result =  $table_db->where([
                'inc_type'  => $param[0],
                'lang'  => $lang,
            ])->select();
            if($result){
                foreach($result as $val){
                    $temp[$val['name']] = $val['value'];
                }
                $add_data = array();
                foreach ($data as $k=>$v){
                    $newK = strtolower($k);
                    $newArr = array(
                        'name'=>$newK,
                        'value'=>trim($v),
                        'inc_type'=>$param[0],
                        'lang'  => $lang,
                        'update_time'   => time(),
                    );
                    if(!isset($temp[$newK])){
                        array_push($add_data, $newArr); //新key數據插入數據庫
                    }else{
                        if ($v != $temp[$newK]) {
                            $table_db->where([
                                'name'  => $newK,
                                'lang'  => $lang,
                            ])->save($newArr);//快取key存在且值有變更新此項
                        }
                    }
                }
                if (!empty($add_data)) {
                    $table_db->insertAll($add_data);
                }
                //更新后的數據庫記錄
                $newRes = $table_db->where([
                    'inc_type'  => $param[0],
                    'lang'  => $lang,
                ])->select();
                foreach ($newRes as $rs){
                    $newData[$rs['name']] = $rs['value'];
                }
            }else{
                if ($param[0] != 'all') {
                    foreach($data as $k=>$v){
                        $newK = strtolower($k);
                        $newArr[] = array(
                            'name'=>$newK,
                            'value'=>trim($v),
                            'inc_type'=>$param[0],
                            'lang'  => $lang,
                            'update_time'   => time(),
                        );
                    }
                    $table_db->insertAll($newArr);
                }
                $newData = $data;
            }

            $result = false;
            $res = $table_db->where([
                'lang'  => $lang,
            ])->select();
            if($res){
                $global = array();
                foreach($res as $k=>$val){
                    $global[$val['name']] = $val['value'];
                }
                $result = cache($tableName.'all',$global,$options);
            } 

            if ($param[0] != 'all') {
                $result = cache($cache_inc_type,$newData,$options);
            }
            
            return $result;
        }
    }
}

if (!function_exists('send_email')) 
{
    /**
     * 郵件發送
     * @param $to    接收人
     * @param string $subject   郵件標題
     * @param string $content   郵件內容(html模板渲染后的內容)
     * @param string $scene   使用場景
     * @throws Exception
     * @throws phpmailerException
     */
    function send_email($to='', $subject='', $data=array(), $scene=0, $smtp_config = []){
        // 實例化類庫，呼叫發送郵件
        $emailLogic = new \app\common\logic\EmailLogic($smtp_config);
        $res = $emailLogic->send_email($to, $subject, $data, $scene);
        return $res;
    }
}

/**
 * 獲得全部省份列表
 */
function get_province_list()
{
    $result = extra_cache('global_get_province_list');
    if ($result == false) {
        $result = M('region')->field('id, name')
            ->where('level',1)
            ->getAllWithIndex('id');
        extra_cache('global_get_province_list', $result);
    }

    return $result;
}

/**
 * 獲得全部城市列表
 */
function get_city_list()
{
    $result = extra_cache('global_get_city_list');
    if ($result == false) {
        $result = M('region')->field('id, name')
            ->where('level',2)
            ->getAllWithIndex('id');
        extra_cache('global_get_city_list', $result);
    }

    return $result;
}

/**
 * 獲得全部地區列表
 */
function get_area_list()
{
    $result = extra_cache('global_get_area_list');
    if ($result == false) {
        $result = M('region')->field('id, name')
            ->where('level',3)
            ->getAllWithIndex('id');
        extra_cache('global_get_area_list', $result);
    }

    return $result;
}

/**
 * 根據地區ID獲得省份名稱
 */
function get_province_name($id)
{
    $result = get_province_list();
    return empty($result[$id]) ? '銀河系' : $result[$id]['name'];
}

/**
 * 根據地區ID獲得城市名稱
 */
function get_city_name($id)
{
    $result = get_city_list();
    return empty($result[$id]) ? '火星' : $result[$id]['name'];
}

/**
 * 根據地區ID獲得縣區名稱
 */
function get_area_name($id)
{
    $result = get_area_list();
    return empty($result[$id]) ? '部落' : $result[$id]['name'];
}

if (!function_exists('AddOrderAction')) 
{
    /**
     * 新增訂單操作表數據
     * 參數說明：
     * $OrderId       訂單ID或訂單ID陣列
     * $UsersId       會員ID，若不為0，則ActionUsers為0
     * $ActionUsers   操作員ID，為0，表示會員操作，反之則為管理員ID
     * $OrderStatus   操作時，訂單目前狀態
     * $ExpressStatus 操作時，訂單目前物流狀態
     * $PayStatus     操作時，訂單目前付款狀態
     * $ActionDesc    操作描述
     * $ActionNote    操作備註
     * 返回說明：
     * return 無需返回
     */
    function AddOrderAction($OrderId,$UsersId,$ActionUsers='0',$OrderStatus='0',$ExpressStatus='0',$PayStatus='0',$ActionDesc='提交訂單！',$ActionNote='會員提交訂單成功！')
    {
        if (is_array($OrderId) && '4' == $OrderStatus) {
            // OrderId為陣列並且訂單狀態為過期，則執行
            foreach ($OrderId as $key => $value) {
                $ActionData[] = [
                    'order_id'       => $value['order_id'],
                    'users_id'       => $UsersId,
                    'action_user'    => $ActionUsers,
                    'order_status'   => $OrderStatus,
                    'express_status' => $ExpressStatus,
                    'pay_status'     => $PayStatus,
                    'action_desc'    => $ActionDesc,
                    'action_note'    => $ActionNote,
                    'lang'           => get_home_lang(),
                    'add_time'       => getTime(),
                ];
            }
            // 批量新增
            M('shop_order_log')->insertAll($ActionData);
        }else{
            // OrderId不為陣列，則執行
            $ActionData = [
                'order_id'       => $OrderId,
                'users_id'       => $UsersId,
                'action_user'    => $ActionUsers,
                'order_status'   => $OrderStatus,
                'express_status' => $ExpressStatus,
                'pay_status'     => $PayStatus,
                'action_desc'    => $ActionDesc,
                'action_note'    => $ActionNote,
                'lang'           => get_home_lang(),
                'add_time'       => getTime(),
            ];
            // 單條新增
            M('shop_order_log')->add($ActionData);
        }
    }
}

if (!function_exists('download_file')) 
{
    /**
     * 下載檔案
     * @param $down_path 檔案路徑
     * @param $file_mime 檔案型別
     */
    function download_file($down_path = '', $file_mime = '')
    {
        /*支援子目錄*/
        $down_path = handle_subdir_pic($down_path);
        /*--end*/

        //檔名
        $filename = explode('/', $down_path);
        $filename = end($filename);
        //以只讀和二進制模式打開檔案
        $file = fopen('.'.$down_path, "rb");
        //檔案大小
        $file_size = filesize('.'.$down_path);
        //告訴瀏覽器這是一個檔案流格式的檔案    
        header("Content-type: ".$file_mime);
        //請求範圍的度量單位
        Header("Accept-Ranges: bytes");
        //Content-Length是指定包含于請求或響應中數據的位元組長度
        Header("Accept-Length: " . $file_size);
        //用來告訴瀏覽器，檔案是可以當做附件被下載，下載后的檔名稱為$filename該變數的值。
        Header("Content-Disposition: attachment; filename=" . $filename); 
        //讀取檔案內容並直接輸出到瀏覽器    
        echo fread($file, $file_size);    
        fclose($file);    
        exit();
    }
}

if (!function_exists('is_realdomain')) 
{
    /**
     * 簡單判斷目前訪問的域名是否真實
     * @param string $domain 不帶協議的域名
     * @return boolean
     */
    function is_realdomain($domain = '')
    {
        $is_real = false;
        $domain = !empty($domain) ? $domain : request()->host();
        if (!preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/i', $domain) && 'localhost' != $domain && '127.0.0.1' != serverIP()) {
            $is_real = true;
        }

        return $is_real;
    }
}

if (!function_exists('img_style_wh')) 
{
    /**
     * 追加指定內嵌樣式到編輯器內容的img標籤，相容圖片自動適應頁面
     */
    function img_style_wh($content = '', $title = '')
    {
        if (!empty($content)) {
            preg_match_all('/<img.*(\/)?>/iUs', $content, $imginfo);
            $imginfo = !empty($imginfo[0]) ? $imginfo[0] : [];
            if (!empty($imginfo)) {
                $num = 1;
                $appendStyle = "max-width:100%!important;height:auto;";
                $title = preg_replace('/("|\')/i', '', $title);
                foreach ($imginfo as $key => $imgstr) {
                    $imgstrNew = $imgstr;
                    
                    /* 相容已存在的多重追加樣式，處理去重 */
                    if (stristr($imgstrNew, $appendStyle.$appendStyle)) {
                        $imgstrNew = preg_replace('/'.$appendStyle.$appendStyle.'/i', '', $imgstrNew);
                    }
                    if (stristr($imgstrNew, $appendStyle)) {
                        $content = str_ireplace($imgstr, $imgstrNew, $content);
                        $num++;
                        continue;
                    }
                    /* end */

                    // 追加style屬性
                    $imgstrNew = preg_replace('/style(\s*)=(\s*)[\'|\"](.*?)[\'|\"]/i', 'style="'.$appendStyle.'${3}"', $imgstrNew);
                    if (!preg_match('/<img(.*?)style(\s*)=(\s*)[\'|\"](.*?)[\'|\"](.*?)[\/]?(\s*)>/i', $imgstrNew)) {
                        // 新增style屬性
                        $imgstrNew = str_ireplace('<img', "<img style=\"".$appendStyle."\" ", $imgstrNew);
                    }

                    // 移除img中多餘的title屬性
                    // $imgstrNew = preg_replace('/title(\s*)=(\s*)[\'|\"]([\w\.]*?)[\'|\"]/i', '', $imgstrNew);

                    // 追加alt屬性
                    $altNew = $title."(圖{$num})";
                    $imgstrNew = preg_replace('/alt(\s*)=(\s*)[\'|\"]([\w\.]*?)[\'|\"]/i', 'alt="'.$altNew.'"', $imgstrNew);
                    if (!preg_match('/<img(.*?)alt(\s*)=(\s*)[\'|\"](.*?)[\'|\"](.*?)[\/]?(\s*)>/i', $imgstrNew)) {
                        // 新增alt屬性
                        $imgstrNew = str_ireplace('<img', "<img alt=\"{$altNew}\" ", $imgstrNew);
                    }

                    // 追加title屬性
                    $titleNew = $title."(圖{$num})";
                    $imgstrNew = preg_replace('/title(\s*)=(\s*)[\'|\"]([\w\.]*?)[\'|\"]/i', 'title="'.$titleNew.'"', $imgstrNew);
                    if (!preg_match('/<img(.*?)title(\s*)=(\s*)[\'|\"](.*?)[\'|\"](.*?)[\/]?(\s*)>/i', $imgstrNew)) {
                        // 新增alt屬性
                        $imgstrNew = str_ireplace('<img', "<img alt=\"{$titleNew}\" ", $imgstrNew);
                    }
                    
                    // 新的img替換舊的img
                    $content = str_ireplace($imgstr, $imgstrNew, $content);
                    $num++;
                }
            }
        }

        return $content;
    }
}

if (!function_exists('get_archives_data')) 
{
    /**
     * 查詢文件主表資訊和文件欄目表資訊整合到一個陣列中
     * @param string $array 產品陣列資訊
     * @param string $id 產品ID，購物車下單頁傳入aid，訂單列表訂單詳情頁傳入product_id
     * @return return array_new
     */
    function get_archives_data($array,$id)
    {
        // 目前定義僅訂單中心使用
        
        if (empty($array) || empty($id)) {
            return false;
        }
        $array_new    = array();

        $aids         = get_arr_column($array, $id);
        $archivesList = \think\Db::name('archives')->field('*')->where('aid','IN',$aids)->getAllWithIndex('aid');
        $typeids      = get_arr_column($archivesList, 'typeid');
        $arctypeList  = \think\Db::name('arctype')->field('*')->where('id','IN',$typeids)->getAllWithIndex('id');
        
        foreach ($archivesList as $key2 => $val2) {
            $array_new[$key2] = array_merge($arctypeList[$val2['typeid']],$val2);
        }

        return $array_new;
    }
}

if (!function_exists('SynchronizeQiniu')) 
{
    /**
     * 參數說明：
     * $images   本地圖片地址
     * $Qiniuyun 七牛云外掛配置資訊
     * $is_tcp 是否攜帶協議
     * 返回說明：
     * return false 沒有配置齊全
     * return true  同步成功
     */
    function SynchronizeQiniu($images,$Qiniuyun=null,$is_tcp=false)
    {
        static $Qiniuyun = null;
        // 若沒有傳入配信資訊則讀取數據庫
        if (null == $Qiniuyun) {
            // 需要填寫你的 Access Key 和 Secret Key
            $data     = M('weapp')->where('code','Qiniuyun')->field('data')->find();
            $Qiniuyun = json_decode($data['data'], true);
        }
        // 配置為空則返回原圖片路徑
        if (empty($Qiniuyun)) {
            return $images;
        }

        //引入七牛云的相關檔案
        weapp_vendor('Qiniu.src.Qiniu.Auth', 'Qiniuyun');
        weapp_vendor('Qiniu.src.Qiniu.Storage.UploadManager', 'Qiniuyun');
        require_once ROOT_PATH.'weapp/Qiniuyun/vendor/Qiniu/autoload.php';

        // 配置資訊
        $accessKey = $Qiniuyun['access_key'];
        $secretKey = $Qiniuyun['secret_key'];
        $bucket    = $Qiniuyun['bucket'];
        $domain    = $Qiniuyun['domain'];
        // 圖片處理，去除圖片途徑中的第一個斜槓
        $images    = ltrim($images, '/'); 
        // 構建鑒權對像
        $auth      = new Qiniu\Auth($accessKey, $secretKey);
        // 產生上傳 Token
        $token     = $auth->uploadToken($bucket);
        // 要上傳檔案的本地路徑
        $filePath  = ROOT_PATH.$images;
        // 上傳到七牛後儲存的檔名
        $key       = $images;
        // 初始化 UploadManager 對象並進行檔案的上傳。
        $uploadMgr = new Qiniu\Storage\UploadManager;
        // 呼叫 UploadManager 的 putFile 方法進行檔案的上傳。
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        // list($ret, $err) = $uploadMgr->put($token, $key, $filePath);
        if (empty($err) || $err === null) {
            $tcp = '//';
            if ($is_tcp) {
                $tcp = !empty($Qiniuyun['tcp']) ? $Qiniuyun['tcp'] : '';
                switch ($tcp) {
                    case '2':
                        $tcp = 'https://';
                        break;

                    case '3':
                        $tcp = '//';
                        break;
                    
                    case '1':
                    default:
                        $tcp = 'http://';
                        break;
                }
            }
            return $tcp.$domain.'/'.$images;
        }
        return $images;
    }
}