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

if (!function_exists('convert_arr_key')) 
{
    /**
     * 將數據庫中查出的列表以指定的 id 作為陣列的鍵名 
     *
     * @param array $arr 陣列
     * @param string $key_name 陣列鍵名
     * @return array
     */
    function convert_arr_key($arr, $key_name)
    {
        $arr2 = array();
        foreach($arr as $key => $val){
            $arr2[$val[$key_name]] = $val;        
        }
        return $arr2;
    }
}

if (!function_exists('func_encrypt')) 
{
    /**
     * md5加密 
     *
     * @param string $str 字串
     * @return array
     */
    function func_encrypt($str){
        $auth_code = tpCache('system.system_auth_code');
        if (empty($auth_code)) {
            $auth_code = \think\Config::get('AUTH_CODE');
            /*多語言*/
            if (is_language()) {
                $langRow = \think\Db::name('language')->order('id asc')->select();
                foreach ($langRow as $key => $val) {
                    tpCache('system', ['system_auth_code'=>$auth_code], $val['mark']);
                }
            } else { // 單語言
                tpCache('system', ['system_auth_code'=>$auth_code]);
            }
            /*--end*/
        }

        return md5($auth_code.$str);
    }
}
   
if (!function_exists('get_arr_column')) 
{         
    /**
     * 獲取陣列中的某一列
     *
     * @param array $arr 陣列
     * @param string $key_name  列名
     * @return array  返回那一列的陣列
     */
    function get_arr_column($arr, $key_name)
    {
        $arr2 = array();
        foreach($arr as $key => $val){
            $arr2[] = $val[$key_name];        
        }
        return $arr2;
    }
}

if (!function_exists('parse_url_param')) 
{  
    /**
     * 獲取url 中的各個參數  類似於 pay_code=alipay&bank_code=ICBC-DEBIT
     * @param string $url
     * @return type
     */
    function parse_url_param($url = ''){
        $url = rtrim($url, '/');
        $data = array();
        $str = explode('?',$url);
        $str = end($str);
        $parameter = explode('&',$str);
        foreach($parameter as $val){
            if (!empty($val)) {
                $tmp = explode('=',$val);
                $data[$tmp[0]] = $tmp[1];
            }
        }
        return $data;
    }
}

if (!function_exists('clientIP')) 
{  
    /**
     * 客戶端IP
     */
    function clientIP() {
        $ip = request()->ip();
        if(preg_match('/^((?:(?:25[0-5]|2[0-4]\d|((1\d{2})|([1-9]?\d)))\.){3}(?:25[0-5]|2[0-4]\d|((1\d{2})|([1 -9]?\d))))$/', $ip))          
            return $ip;
        else
            return '';
    }
}

if (!function_exists('serverIP')) 
{  
    /**
     * 伺服器端IP
     */
    function serverIP(){   
        return gethostbyname($_SERVER["SERVER_NAME"]);   
    }  
}

if (!function_exists('recurse_copy')) 
{  
    /**
     * 自定義函式遞迴的複製帶有多級子目錄的目錄
     * 遞迴複製資料夾
     *
     * @param type $src 原目錄
     * @param type $dst 複製到的目錄
     */                        
    //參數說明：            
    //自定義函式遞迴的複製帶有多級子目錄的目錄
    function recurse_copy($src, $dst)
    {
        $now = getTime();
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== $file = readdir($dir)) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    recurse_copy($src . '/' . $file, $dst . '/' . $file);
                }
                else {
                    if (file_exists($dst . DIRECTORY_SEPARATOR . $file)) {
                        if (!is_writeable($dst . DIRECTORY_SEPARATOR . $file)) {
                            // exit($dst . DIRECTORY_SEPARATOR . $file . '不可寫');
                            return '網站目錄沒有寫入許可權，請調整許可權';
                        }
                        // @unlink($dst . DIRECTORY_SEPARATOR . $file);
                    }
                    // if (file_exists($dst . DIRECTORY_SEPARATOR . $file)) {
                    //     @unlink($dst . DIRECTORY_SEPARATOR . $file);
                    // }
                    $copyrt = @copy($src . DIRECTORY_SEPARATOR . $file, $dst . DIRECTORY_SEPARATOR . $file);
                    if (!$copyrt) {
                        // echo 'copy ' . $dst . DIRECTORY_SEPARATOR . $file . ' failed';
                        return '網站目錄沒有寫入許可權，請調整許可權';
                    }
                }
            }
        }
        closedir($dir);

        return true;
    }
}

if (!function_exists('delFile')) 
{  
    /**
     * 遞迴刪除資料夾
     *
     * @param string $path 目錄路徑
     * @param boolean $delDir 是否刪除空目錄
     * @return boolean
     */
    function delFile($path, $delDir = FALSE) {
        if(!is_dir($path))
            return FALSE;       
        $handle = @opendir($path);
        if ($handle) {
            while (false !== ( $item = readdir($handle) )) {
                if ($item != "." && $item != "..")
                    is_dir("$path/$item") ? delFile("$path/$item", $delDir) : @unlink("$path/$item");
            }
            closedir($handle);
            if ($delDir) {
                return @rmdir($path);
            }
        }else {
            if (file_exists($path)) {
                return @unlink($path);
            } else {
                return FALSE;
            }
        }
    }
}

if (!function_exists('getDirFile')) 
{ 
    /**
     * 遞迴讀取資料夾檔案
     *
     * @param string $directory 目錄路徑
     * @param string $dir_name 顯示的目錄字首路徑
     * @param array $arr_file 是否刪除空目錄
     * @return boolean
     */
    function getDirFile($directory, $dir_name='', &$arr_file = array()) {
        if (!file_exists($directory) ) {
            return false;
        }

        $mydir = dir($directory);
        while($file = $mydir->read())
        {
            if((is_dir("$directory/$file")) AND ($file != ".") AND ($file != ".."))
            {
                if ($dir_name) {
                    getDirFile("$directory/$file", "$dir_name/$file", $arr_file);
                } else {
                    getDirFile("$directory/$file", "$file", $arr_file);
                }
                
            }
            else if(($file != ".") AND ($file != ".."))
            {
                if ($dir_name) {
                    $arr_file[] = "$dir_name/$file";
                } else {
                    $arr_file[] = "$file";
                }
            }
        }
        $mydir->close();

        return $arr_file;
    }
}

if (!function_exists('ey_scandir')) 
{ 
    /**
     * 部分空間爲了安全起見，禁用scandir函式
     *
     * @param string $dir 路徑
     * @return array
     */
    function ey_scandir($dir, $type = 'all')
    {
        if(function_exists('scandir')){
            $files = scandir($dir);
        } else {
            $files = [];
            $mydir = dir($dir);
            while($file = $mydir->read())
            {
                $files[] = "$file";
            }
            $mydir->close();
        }
        $arr_file = [];
        foreach ($files as $key => $val) {
            if(($val != ".") AND ($val != "..")){
                if ('all' == $type) {
                    $arr_file[] = "$val";
                } else if ('file' == $type && is_file($val)) {
                    $arr_file[] = "$val";
                } else if ('dir' == $type && is_dir($val)) {
                    $arr_file[] = "$val";
                }
            }
        }

        return $arr_file;
    }
}

if (!function_exists('group_same_key')) 
{ 
    /**
     * 將二維陣列以元素的某個值作為鍵，並歸類陣列
     *
     * array( array('name'=>'aa','type'=>'pay'), array('name'=>'cc','type'=>'pay') )
     * array('pay'=>array( array('name'=>'aa','type'=>'pay') , array('name'=>'cc','type'=>'pay') ))
     * @param $arr 陣列
     * @param $key 分組值的key
     * @return array
     */
    function group_same_key($arr,$key){
        $new_arr = array();
        foreach($arr as $k=>$v ){
            $new_arr[$v[$key]][] = $v;
        }
        return $new_arr;
    }
}

if (!function_exists('get_rand_str')) 
{ 
    /**
     * 獲取隨機字串
     * @param int $randLength  長度
     * @param int $addtime  是否加入目前時間戳
     * @param int $includenumber   是否包含數字
     * @return string
     */
    function get_rand_str($randLength=6,$addtime=1,$includenumber=0){
        if ($includenumber){
            $chars='abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNPQEST123456789';
        }else {
            $chars='abcdefghijklmnopqrstuvwxyz';
        }
        $len=strlen($chars);
        $randStr='';
        for ($i=0;$i<$randLength;$i++){
            $randStr.=$chars[rand(0,$len-1)];
        }
        $tokenvalue=$randStr;
        if ($addtime){
            $tokenvalue=$randStr.getTime();
        }
        return $tokenvalue;
    }
}

if (!function_exists('httpRequest')) 
{ 
    /**
     * CURL請求
     *
     * @param $url 請求url地址
     * @param $method 請求方法 get post
     * @param null $postfields post數據陣列
     * @param array $headers 請求header資訊
     * @param bool|false $debug  除錯開啟 預設false
     * @return mixed
     */
    function httpRequest($url, $method="GET", $postfields = null, $headers = array(), $timeout = 30, $debug = false) {
        $method = strtoupper($method);
        $ci = curl_init();
        /* Curl settings */
        curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ci, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0");
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 60); /* 在發起連線前等待的時間，如果設定為0，則無限等待 */
        // curl_setopt($ci, CURLOPT_TIMEOUT, 7); /* 設定cURL允許執行的最長秒數 */
        curl_setopt($ci, CURLOPT_TIMEOUT, $timeout); /* 設定cURL允許執行的最長秒數 */
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
        switch ($method) {
            case "POST":
                curl_setopt($ci, CURLOPT_POST, true);
                if (!empty($postfields)) {
                    $tmpdatastr = is_array($postfields) ? http_build_query($postfields) : $postfields;
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $tmpdatastr);
                }
                break;
            default:
                curl_setopt($ci, CURLOPT_CUSTOMREQUEST, $method); /* //設定請求方式 */
                break;
        }
        $ssl = preg_match('/^https:\/\//i',$url) ? TRUE : FALSE;
        curl_setopt($ci, CURLOPT_URL, $url);
        if($ssl){
            curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE); // https請求 不驗證證書和hosts
            curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, FALSE); // 不從證書中檢查SSL加密演算法是否存在
        }
        //curl_setopt($ci, CURLOPT_HEADER, true); /*啟用時會將標頭檔案的資訊作為數據流輸出*/
        if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off')) {
            curl_setopt($ci, CURLOPT_FOLLOWLOCATION, 1);
        }
        curl_setopt($ci, CURLOPT_MAXREDIRS, 2);/*指定最多的HTTP重定向的數量，這個選項是和CURLOPT_FOLLOWLOCATION一起使用的*/
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ci, CURLINFO_HEADER_OUT, true);
        /*curl_setopt($ci, CURLOPT_COOKIE, $Cookiestr); * *COOKIE帶過去** */
        $response = curl_exec($ci);
        $requestinfo = curl_getinfo($ci);
        $http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
        if ($debug) {
            echo "=====post data======\r\n";
            var_dump($postfields);
            echo "=====info===== \r\n";
            print_r($requestinfo);
            echo "=====response=====\r\n";
            print_r($response);
        }
        curl_close($ci);
        return $response;
        //return array($http_code, $response,$requestinfo);
    }
}

if (!function_exists('check_mobile')) 
{
    /**
     * 檢查手機號碼格式
     *
     * @param $mobile 手機號碼
     */
    function check_mobile($mobile){
        if(preg_match('/1\d{10}$/',$mobile))
            return true;
        return false;
    }
}

if (!function_exists('check_telephone')) 
{
    /**
     * 檢查固定電話
     *
     * @param $mobile
     * @return bool
     */
    function check_telephone($mobile){
        if(preg_match('/^([0-9]{3,4}-)?[0-9]{7,8}$/',$mobile))
            return true;
        return false;
    }
}

if (!function_exists('check_email')) 
{
    /**
     * 檢查郵箱地址格式
     *
     * @param $email 郵箱地址
     */
    function check_email($email){
        if(filter_var($email,FILTER_VALIDATE_EMAIL))
            return true;
        return false;
    }
}

if (!function_exists('getSubstr')) 
{
    /**
     * 實現中文字串擷取無亂碼的方法
     *
     * @param string $string 字串
     * @param intval $start 起始位置
     * @param intval $length 擷取長度
     * @return string
     */
    function getSubstr($string='', $start=0, $length=NULL) {
        if(mb_strlen($string,'utf-8')>$length){
            $str = msubstr($string, $start, $length, true,'utf-8');
            return $str;
        }else{
            return $string;
        }
    }
}

if (!function_exists('msubstr')) 
{
    /**
     * 字串擷取，支援中文和其他編碼
     *
     * @param string $str 需要轉換的字串
     * @param string $start 開始位置
     * @param string $length 擷取長度
     * @param string $suffix 截斷顯示字元
     * @param string $charset 編碼格式
     * @return string
     */
    function msubstr($str='', $start=0, $length=NULL, $suffix=false, $charset="utf-8") {
        if(function_exists("mb_substr"))
            $slice = mb_substr($str, $start, $length, $charset);
        elseif(function_exists('iconv_substr')) {
            $slice = iconv_substr($str,$start,$length,$charset);
            if(false === $slice) {
                $slice = '';
            }
        }else{
            $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
            $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
            $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
            $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
            preg_match_all($re[$charset], $str, $match);
            $slice = join("",array_slice($match[0], $start, $length));
        }

        $str_len = strlen($str); // 原字串長度
        $slice_len = strlen($slice); // 擷取字串的長度
        if ($slice_len < $str_len) {
            $slice = $suffix ? $slice.'...' : $slice;
        }

        return $slice;
    }
}

if (!function_exists('html_msubstr')) 
{
    /**
     * 擷取內容清除html之後的字串長度，支援中文和其他編碼
     *
     * @param string $str 需要轉換的字串
     * @param string $start 開始位置
     * @param string $length 擷取長度
     * @param string $suffix 截斷顯示字元
     * @param string $charset 編碼格式
     * @return string
     */
    function html_msubstr($str='', $start=0, $length=NULL, $suffix=false, $charset="utf-8") {
        if (is_language() && 'cn' != get_current_lang()) {
            $length = $length * 2;
        }
        $str = eyou_htmlspecialchars_decode($str);
        $str = checkStrHtml($str);
        return msubstr($str, $start, $length, $suffix, $charset);
    }
}

if (!function_exists('text_msubstr')) 
{
    /**
     * 針對多語言擷取，其他語言的擷取是中文語言的2倍長度
     *
     * @param string $str 需要轉換的字串
     * @param string $start 開始位置
     * @param string $length 擷取長度
     * @param string $suffix 截斷顯示字元
     * @param string $charset 編碼格式
     * @return string
     */
    function text_msubstr($str='', $start=0, $length=NULL, $suffix=false, $charset="utf-8") {
        if (is_language() && 'cn' != get_current_lang()) {
            $length = $length * 2;
        }
        return msubstr($str, $start, $length, $suffix, $charset);
    }
}

if (!function_exists('eyou_htmlspecialchars_decode')) 
{
    /**
     * 自定義只針對htmlspecialchars編碼過的字串進行解碼
     *
     * @param string $str 需要轉換的字串
     * @param string $start 開始位置
     * @param string $length 擷取長度
     * @param string $suffix 截斷顯示字元
     * @param string $charset 編碼格式
     * @return string
     */
    function eyou_htmlspecialchars_decode($str='') {
        if (is_string($str) && stripos($str, '&lt;') !== false && stripos($str, '&gt;') !== false) {
            $str = htmlspecialchars_decode($str);
        }
        return $str;
    }
}

if (!function_exists('isMobile')) 
{
    /**
     * 判斷目前訪問的使用者是  PC端  還是 手機端  返回true 為手機端  false 為PC 端
     * 是否移動端訪問
     *
     * @return boolean
     */
    function isMobile()
    {
        return request()->isMobile();

        // 如果有HTTP_X_WAP_PROFILE則一定是移動裝置
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
        return true;

        // 如果via資訊含有wap則一定是移動裝置,部分服務商會遮蔽該資訊
        if (isset ($_SERVER['HTTP_VIA']))
        {
        // 找不到為flase,否則為true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }
        // 腦殘法，判斷手機發送的客戶端標誌,相容性有待提高
        if (isset ($_SERVER['HTTP_USER_AGENT']))
        {
            $clientkeywords = array ('nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile');
            // 從HTTP_USER_AGENT中查詢手機瀏覽器的關鍵字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
                return true;
        }
            // 協議法，因為有可能不準確，放到最後判斷
        if (isset ($_SERVER['HTTP_ACCEPT']))
        {
        // 如果只支援wml並且不支援html那一定是移動裝置
        // 如果支援wml和html但是wml在html之前則是移動裝置
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
            {
                return true;
            }
        }
                return false;
     }
}

if (!function_exists('isWeixin')) 
{
    /**
     * 是否微信端訪問
     *
     * @return boolean
     */
    function isWeixin() {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return true;
        } return false;
    }
}

if (!function_exists('isQq')) 
{
    /**
     * 是否QQ端訪問
     *
     * @return boolean
     */
    function isQq() {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'QQ') !== false) {
            return true;
        } return false;
    }
}

if (!function_exists('isAlipay')) 
{
    /**
     * 是否支付端訪問
     *
     * @return boolean
     */
    function isAlipay() {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'AlipayClient') !== false) {
            return true;
        } return false;
    }
}

if (!function_exists('getFirstCharter')) 
{
    /**
     * php獲取中文字元拼音首字母
     *
     * @param string $str 中文
     * @return boolean
     */
    function getFirstCharter($str){
          if(empty($str))
          {
                return '';          
          }
          $fchar=ord($str{0});
          if($fchar>=ord('A')&&$fchar<=ord('z')) return strtoupper($str{0});
          $s1=iconv('UTF-8','gb2312',$str);
          $s2=iconv('gb2312','UTF-8',$s1);
          $s=$s2==$str?$s1:$str;
          $asc=ord($s{0})*256+ord($s{1})-65536;
         if($asc>=-20319&&$asc<=-20284) return 'A';
         if($asc>=-20283&&$asc<=-19776) return 'B';
         if($asc>=-19775&&$asc<=-19219) return 'C';
         if($asc>=-19218&&$asc<=-18711) return 'D';
         if($asc>=-18710&&$asc<=-18527) return 'E';
         if($asc>=-18526&&$asc<=-18240) return 'F';
         if($asc>=-18239&&$asc<=-17923) return 'G';
         if($asc>=-17922&&$asc<=-17418) return 'H';
         if($asc>=-17417&&$asc<=-16475) return 'J';
         if($asc>=-16474&&$asc<=-16213) return 'K';
         if($asc>=-16212&&$asc<=-15641) return 'L';
         if($asc>=-15640&&$asc<=-15166) return 'M';
         if($asc>=-15165&&$asc<=-14923) return 'N';
         if($asc>=-14922&&$asc<=-14915) return 'O';
         if($asc>=-14914&&$asc<=-14631) return 'P';
         if($asc>=-14630&&$asc<=-14150) return 'Q';
         if($asc>=-14149&&$asc<=-14091) return 'R';
         if($asc>=-14090&&$asc<=-13319) return 'S';
         if($asc>=-13318&&$asc<=-12839) return 'T';
         if($asc>=-12838&&$asc<=-12557) return 'W';
         if($asc>=-12556&&$asc<=-11848) return 'X';
         if($asc>=-11847&&$asc<=-11056) return 'Y';
         if($asc>=-11055&&$asc<=-10247) return 'Z';
         return null;
    }
}

if (!function_exists('pinyin_long')) 
{
    /**
     * 獲取整條字串漢字拼音首字母
     *
     * @param $zh
     * @return string
     */
    function pinyin_long($zh){
        $ret = "";
        $s1 = iconv("UTF-8","gb2312", $zh);
        $s2 = iconv("gb2312","UTF-8", $s1);
        if($s2 == $zh){$zh = $s1;}
        for($i = 0; $i < strlen($zh); $i++){
            $s1 = substr($zh,$i,1);
            $p = ord($s1);
            if($p > 160){
                $s2 = substr($zh,$i++,2);
                $ret .= getFirstCharter($s2);
            }else{
                $ret .= $s1;
            }
        }
        return $ret;
    }
}

if (!function_exists('respose')) 
{
    /**
     * 參數 is_jsonp 為true，表示跨域ajax請求的返回值
     *
     * @param string $res 陣列
     * @param bool $is_jsonp 是否跨域
     * @return string
     */
    function respose($res, $is_jsonp = false){
        if (true === $is_jsonp) {
            exit(input('callback')."(".json_encode($res).")");
        } else {
            exit(json_encode($res));
        }
    }
}

if (!function_exists('urlsafe_b64encode')) 
{
    function urlsafe_b64encode($string) 
    {
        $data = base64_encode($string);
        $data = str_replace(array('+','/','='),array('-','_',''),$data);
        return $data;
    }
}

if (!function_exists('getTime')) 
{
    /**
     * 獲取目前時間戳
     *
     */
    function getTime()
    {
        return time();
    }
}

if (!function_exists('trim_space')) 
{
    /**
     * 過濾前後空格等多種字元
     *
     * @param string $str 字串
     * @param array $arr 特殊字元的陣列集合
     * @return string
     */
    function trim_space($str, $arr = array())
    {
        if (empty($arr)) {
            $arr = array(' ', '　');
        }
        foreach ($arr as $key => $val) {
            $str = preg_replace('/(^'.$val.')|('.$val.'$)/', '', $str);
        }

        return $str;
    }
}

if (!function_exists('func_preg_replace')) 
{
    /**
     * 替換指定的符號
     *
     * @param array $arr 特殊字元的陣列集合
     * @param string $replacement 符號
     * @param string $str 字串
     * @return string
     */
    function func_preg_replace($arr = array(), $replacement = ',', $str = '')
    {
        if (empty($arr)) {
            $arr = array('，');
        }
        foreach ($arr as $key => $val) {
            $str = preg_replace('/('.$val.')/', $replacement, $str);
        }

        return $str;
    }
}

if (!function_exists('db_create_in')) 
{
    /**
     * 建立像這樣的查詢: "IN('a','b')";
     *
     * @param    mixed      $item_list      列表陣列或字串,如果為字串時,字串只接受數字串
     * @param    string   $field_name     欄位名稱
     * @return   string
     */
    function db_create_in($item_list, $field_name = '')
    {
        if (empty($item_list))
        {
            return $field_name . " IN ('') ";
        }
        else
        {
            if (!is_array($item_list))
            {
                $item_list = explode(',', $item_list);
                foreach ($item_list as $k=>$v)
                {
                    $item_list[$k] = intval($v);
                }
            }

            $item_list = array_unique($item_list);
            $item_list_tmp = '';
            foreach ($item_list AS $item)
            {
                if ($item !== '')
                {
                    $item_list_tmp .= $item_list_tmp ? ",'$item'" : "'$item'";
                }
            }
            if (empty($item_list_tmp))
            {
                return $field_name . " IN ('') ";
            }
            else
            {
                return $field_name . ' IN (' . $item_list_tmp . ') ';
            }
        }
    }
}

if (!function_exists('static_version')) 
{
    /**
     * 給靜態檔案追加版本號，實時重新整理瀏覽器快取
     *
     * @param    string   $file     為遠端檔案
     * @return   string
     */
    function static_version($file)
    {
        static $request = null;
        null == $request && $request = \think\Request::instance();
        // ---判斷本地檔案是否存在，否則返回false，以免@get_headers方法導致崩潰
        if (is_http_url($file)) { // 判斷http路徑
            if (preg_match('/^http(s?):\/\/'.$request->host(true).'/i', $file)) { // 判斷目前域名的本地伺服器檔案(這僅用於單臺伺服器，多臺稍作修改便可)
                // $pattern = '/^http(s?):\/\/([^.]+)\.([^.]+)\.([^\/]+)\/(.*)$/';
                $pattern = '/^http(s?):\/\/([^\/]+)(.*)$/';
                preg_match_all($pattern, $file, $matches);//正規表示式
                if (!empty($matches)) {
                    $filename = $matches[count($matches) - 1][0];
                    if (!file_exists(realpath(ltrim($filename, '/')))) {
                        return false;
                    }
                    $http_url = $file = $request->domain().$filename;
                }
            }
            
        } else {
            if (!file_exists(realpath(ltrim($file, '/')))) {
                return false;
            }
            $http_url = $request->domain().$file;
        }
        // -------------end---------------

        $parseStr = '';
        $headInf = @get_headers($http_url,1); 
        $update_time_str = !empty($headInf['Last-Modified']) ? '?t='.strtotime($headInf['Last-Modified']) : ''; 
        $type = strtolower(substr(strrchr($file, '.'), 1));
        switch ($type) {
            case 'js':
                $parseStr .= '<script type="text/javascript" src="' . $file .$update_time_str.'"></script>';
                break;
            case 'css':
                $parseStr .= '<link rel="stylesheet" type="text/css" href="' . $file .$update_time_str.'" />';
                break;
            case 'ico':
                $parseStr .= '<link rel="shortcut icon" href="' . $file .$update_time_str.'" />';
                break;
        }

        return $parseStr;
    }
}

if (!function_exists('tp_mkdir')) 
{
    /**
     * 遞迴建立目錄 
     *
     * @param string $path 目錄路徑，不帶反斜槓
     * @param intval $purview 目錄許可權碼
     * @return boolean
     */  
    function tp_mkdir($path, $purview = 0777)
    {
        if (!is_dir($path)) {
            tp_mkdir(dirname($path), $purview);
            if (!mkdir($path, $purview)) {
                return false;
            }
        }
        return true;
    }
}

if (!function_exists('format_bytes')) 
{
    /**
     * 格式化位元組大小
     *
     * @param  number $size      位元組數
     * @param  string $delimiter 數字和單位分隔符
     * @return string            格式化后的帶單位的大小
     */
    function format_bytes($size, $delimiter = '') {
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
        return round($size, 2) . $delimiter . $units[$i];
    }
}

if (!function_exists('is_http_url')) 
{
    /**
     * 判斷url是否完整的鏈接
     *
     * @param  string $url 網址
     * @return boolean
     */
    function is_http_url($url)
    {
        // preg_match("/^(http:|https:|ftp:|svn:)?(\/\/).*$/", $url, $match);
        preg_match("/^((\w)*:)?(\/\/).*$/", $url, $match);
        if (empty($match)) {
            return false;
        } else {
            return true;
        }
    }
}

if (!function_exists('get_html_first_imgurl')) 
{
    /**
     * 獲取文章內容html中第一張圖片地址
     *
     * @param  string $html html程式碼
     * @return boolean
     */
    function get_html_first_imgurl($html){
        $pattern = '~<img [^>]*[\s]?[\/]?[\s]?>~';
        preg_match_all($pattern, $html, $matches);//正規表示式把圖片的整個都獲取出來了
        $img_arr = $matches[0];//圖片
        $first_img_url = "";
        if (!empty($img_arr)) {
            $first_img = $img_arr[0];
            $p="#src=('|\")(.*)('|\")#isU";//正規表示式
            preg_match_all ($p, $first_img, $img_val);
            if(isset($img_val[2][0])){
                $first_img_url = $img_val[2][0]; //獲取第一張圖片地址
            }
        }

        return $first_img_url;
    }
}

if (!function_exists('checkStrHtml')) 
{
    /**
     * 過濾Html標籤
     *
     * @param     string  $string  內容
     * @return    string
     */
    function checkStrHtml($string){
        $string = trim_space($string);

        if(is_numeric($string)) return $string;
        if(!isset($string) or empty($string)) return '';

        $string = preg_replace('/[\\x00-\\x08\\x0B\\x0C\\x0E-\\x1F]/','',$string);
        $string  = ($string);

        $string = strip_tags($string,""); //清除HTML如<br />等程式碼
        // $string = str_replace("\n", "", str_replace(" ", "", $string));//去掉空格和換行
        $string = str_replace("\n", "", $string);//去掉空格和換行
        $string = str_replace("\t","",$string); //去掉製表符號
        $string = str_replace(PHP_EOL,"",$string); //去掉回車換行符號
        $string = str_replace("\r","",$string); //去掉回車
        $string = str_replace("'","『",$string); //替換單引號
        $string = str_replace("&amp;","&",$string);
        $string = str_replace("=★","",$string);
        $string = str_replace("★=","",$string);
        $string = str_replace("★","",$string);
        $string = str_replace("☆","",$string);
        $string = str_replace("√","",$string);
        $string = str_replace("±","",$string);
        $string = str_replace("‖","",$string);
        $string = str_replace("×","",$string);
        $string = str_replace("∏","",$string);
        $string = str_replace("∷","",$string);
        $string = str_replace("⊥","",$string);
        $string = str_replace("∠","",$string);
        $string = str_replace("⊙","",$string);
        $string = str_replace("≈","",$string);
        $string = str_replace("≤","",$string);
        $string = str_replace("≥","",$string);
        $string = str_replace("∞","",$string);
        $string = str_replace("∵","",$string);
        $string = str_replace("♂","",$string);
        $string = str_replace("♀","",$string);
        $string = str_replace("°","",$string);
        $string = str_replace("¤","",$string);
        $string = str_replace("◎","",$string);
        $string = str_replace("◇","",$string);
        $string = str_replace("◆","",$string);
        $string = str_replace("→","",$string);
        $string = str_replace("←","",$string);
        $string = str_replace("↑","",$string);
        $string = str_replace("↓","",$string);
        $string = str_replace("▲","",$string);
        $string = str_replace("▼","",$string);

        // --過濾微信表情
        $string = preg_replace_callback('/[\xf0-\xf7].{3}/', function($r) { return '';}, $string);

        return $string;
    }
}

if (!function_exists('saveRemote')) 
{
    /**
     * 抓取遠端圖片
     *
     * @param     string  $fieldName  遠端圖片url
     * @param     string  $savePath  儲存在public/upload的子目錄
     * @return    string
     */
    function saveRemote($fieldName, $savePath = 'temp/'){
        $allowFiles = [".png", ".jpg", ".jpeg", ".gif", ".bmp", "webp"];

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
        if(preg_match("/^http(s?):\/\/(mmbiz.qpic.cn|qimg.91ud.com)\/(.*)/", $imgUrl) != 1){
            $fileType = strtolower(strrchr($imgUrl,'.'));
            if(!in_array($fileType,$allowFiles) || stristr($heads['Content-Type'],"image")){
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

        $dirname = './'.UPLOAD_PATH.'remote/'.date('Y/m/d').'/';
        $file['oriName'] = $m ? $m[1] : "";
        $file['filesize'] = strlen($img);
        $file['ext'] = strtolower(strrchr('remote.png','.'));
        $file['name'] = uniqid().$file['ext'];
        $file['fullName'] = $dirname.$file['name'];
        $fullName = $file['fullName'];

        //檢查檔案大小是否超出限制
        if($file['filesize'] >= 10240000){
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
                'url' => substr($file['fullName'],1),
                'title' => $file['name'],
                'original' => $file['oriName'],
                'type' => $file['ext'],
                'size' => $file['filesize'],
            );

            $ossConfig = tpCache('oss');
            if ($ossConfig['oss_switch']) {
                //圖片可選擇存放在oss
                $savePath = $savePath.date('Y/m/d/');
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
}

if (!function_exists('func_common')) 
{
    /**
     * 自定義上傳
     *
     * @param     string  $fileElementId  上傳表單的ID
     * @param     string  $path  儲存在public/upload的子目錄
     * @param     string  $file_ext  圖片後綴名
     * @return    string
     */
    function func_common($fileElementId = 'uploadImage', $path = 'temp', $file_ext = "jpg|gif|png|bmp|jpeg"){
        $file = request()->file($fileElementId);

        if (empty($file)) {
            return ['errcode'=>1,'errmsg'=>'請選擇上傳圖片'];
        }
        
        $validate = array();
        /*檔案大小限制*/
        $validate_size = tpCache('basic.file_size');
        if (!empty($validate_size)) {
            $validate['size'] = $validate_size * 1024 * 1024; // 單位為b
        }
        /*--end*/
        /*副檔名限制*/
        $validate_ext = tpCache('basic.image_type');
        if (!empty($validate_ext)) {
            $validate['ext'] = explode('|', $validate_ext);
        }
        /*--end*/
        /*上傳檔案驗證*/
        if (!empty($validate)) {
            $is_validate = $file->check($validate);
            if ($is_validate === false) {
                return ['errcode'=>1,'errmsg'=>$file->getError()];
            }   
        }
        /*--end*/    
        /*驗證圖片一句話木馬*/
        $imgstr = @file_get_contents($_FILES[$fileElementId]['tmp_name']);
        if (false !== $imgstr && preg_match('#<\?php#i', $imgstr)) {
            return ['errcode'=>1,'errmsg'=>'上傳圖片不合格'];
        }
        /*--end*/ 
        
        $fileName = $_FILES[$fileElementId]['name'];
        // 提取檔名後綴
        $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);
        // 提取出檔名，不包括副檔名
        $newfileName = preg_replace('/\.([^\.]+)$/', '', $fileName);
        // 過濾檔名.\/的特殊字元，防止利用上傳漏洞
        $newfileName = preg_replace('#(\\\|\/|\.)#i', '', $newfileName);
        // 過濾后的新檔名
        $fileName = $newfileName.'.'.$file_ext;

        $savePath =  $path .'/'.date('Ymd/');
        $return_url = "";

        $ossConfig = tpCache('oss');
        if ($ossConfig['oss_switch']) {
            //圖片可選擇存放在oss
            $object = UPLOAD_PATH.$savePath.md5(getTime().uniqid(mt_rand(), TRUE)).'.'.$file_ext;
            $ossClient = new \app\common\logic\OssLogic;
            $return_url = $ossClient->uploadFile($file->getRealPath(), $object);
            if (!$return_url) {
                $state = "ERROR" . $ossClient->getError();
                $return_url = '';
            } else {
                $state = "SUCCESS";
            }
            @unlink($file->getRealPath());
        } else { // 使用自定義的檔案儲存規則
            $info = $file->rule(function($file){return md5(mt_rand());})->move(UPLOAD_PATH.$savePath);
            if($info){
                $return_url =  '/'.UPLOAD_PATH.$savePath.$info->getSaveName();
            }
        }
        
        if($return_url){
            return ['errcode'=>0,'errmsg'=>'上傳成功','img_url'=>$return_url];
        }else{
            return ['errcode'=>1,'errmsg'=>'上傳失敗'];
        }
    }
}

if (!function_exists('func_substr_replace')) 
{
    /**
     * 隱藏部分字串
     *
     * @param     string  $str  字串
     * @param     string  $replacement  替換顯示的字元
     * @param     intval  $start  起始位置
     * @param     intval  $length  隱藏長度
     * @return    string
     */
    function func_substr_replace($str, $replacement = '*', $start = 1, $length = 3)
    {
        $len = mb_strlen($str,'utf-8');
        if ($len > intval($start+$length)) {
            $str1 = msubstr($str,0,$start);
            $str2 = msubstr($str,intval($start+$length),NULL);
        } else {
            $str1 = msubstr($str,0,1);
            $str2 = msubstr($str,$len-1,1);    
            $length = $len - 2;        
        }
        $new_str = $str1;
        for ($i = 0; $i < $length; $i++) { 
            $new_str .= $replacement;
        }
        $new_str .= $str2;

        return $new_str;
    }
}

if (!function_exists('func_authcode')) 
{
    /**
     * 字串加密解密
     *
     * @param unknown $string   明文或密文
     * @param string $operation   DECODE表示解密,其它表示加密
     * @param string $key   密匙
     * @param number $expiry 密文有效期
     * @return string
     */
    function func_authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
        $ckey_length = 4;
        $key = md5($key != '' ? $key : 'zxsdcrtkbrecxm');
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

        $cryptkey = $keya.md5($keya.$keyc);
        $key_length = strlen($cryptkey);

        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        $rndkey = array();
        for($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if($operation == 'DECODE') {
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $keyc.str_replace('=', '', base64_encode($result));
        }

    }
}

/**
 *  獲取拼音以gbk編碼為準
 *
 * @param     string  $str     字串資訊
 * @param     int     $ishead  是否取頭字母
 * @param     int     $isclose 是否關閉字串資源
 * @return    string
 */
if ( ! function_exists('get_pinyin'))
{
    function get_pinyin($str, $ishead=0, $isclose=1)
    {
        // return SpGetPinyin(utf82gb($str), $ishead, $isclose);

        $s1 = iconv("UTF-8","gb2312", $str);
        $s2 = iconv("gb2312","UTF-8", $s1);
        if($s2 == $str){$str = $s1;}
        
        $pinyins = array();
        $restr = '';
        $str = trim($str);
        $slen = strlen($str);
        if($slen < 2)
        {
            return $str;
        }
        if(empty($pinyins))
        {
            $fp = fopen(DATA_PATH.'conf/pinyin.dat', 'r');
            while(!feof($fp))
            {
                $line = trim(fgets($fp));
                $pinyins[$line[0].$line[1]] = substr($line, 3, strlen($line)-3);
            }
            fclose($fp);
        }
        for($i=0; $i<$slen; $i++)
        {
            if(ord($str[$i])>0x80)
            {
                $c = $str[$i].$str[$i+1];
                $i++;
                if(isset($pinyins[$c]))
                {
                    if($ishead==0)
                    {
                        $restr .= $pinyins[$c];
                    }
                    else
                    {
                        $restr .= $pinyins[$c][0];
                    }
                }else
                {
                    $restr .= "_";
                }
            }else if( preg_match("/[a-z0-9]/i", $str[$i]) )
            {
                $restr .= $str[$i];
            }
            else
            {
                $restr .= "_";
            }
        }
        if($isclose==0)
        {
            unset($pinyins);
        }
        return strtolower($restr);
    }
}

if (!function_exists('filter_line_return')) 
{
    /**
     *  過濾換行回車符
     *
     * @param     string  $str     字串資訊
     * @return    string
     */
    function filter_line_return($str = '', $replace = '')
    {
        return str_replace(PHP_EOL, $replace, $str);
    }
}

if (!function_exists('MyDate')) 
{
    /**
     *  時間轉化日期格式
     *
     * @param     string  $format     日期格式
     * @param     intval  $t     時間戳
     * @return    string
     */
    function MyDate($format = 'Y-m-d', $t = '')
    {
        if (!empty($t)) {
            $t = date($format, $t);
        }
        return $t;
    }
}

if (!function_exists('arctype_options')) 
{
    /**
     * 過濾和排序所有文章欄目，返回一個帶有縮排級別的陣列
     *
     * @param   int     $id     上級欄目ID
     * @param   array   $arr        含有所有欄目的陣列
     * @param   string     $id_alias      id鍵名
     * @param   string     $pid_alias      父id鍵名
     * @return  void
     */
    function arctype_options($spec_id, $arr, $id_alias, $pid_alias)
    {
        $cat_options = array();

        if (isset($cat_options[$spec_id]))
        {
            return $cat_options[$spec_id];
        }

        if (!isset($cat_options[0]))
        {
            $level = $last_id = 0;
            $options = $id_array = $level_array = array();
            while (!empty($arr))
            {
                foreach ($arr AS $key => $value)
                {
                    $id = $value[$id_alias];
                    if ($level == 0 && $last_id == 0)
                    {
                        if ($value[$pid_alias] > 0)
                        {
                            break;
                        }

                        $options[$id]          = $value;
                        $options[$id]['level'] = $level;
                        $options[$id][$id_alias]    = $id;
                        // $options[$id]['typename']  = $value['typename'];
                        unset($arr[$key]);

                        if ($value['has_children'] == 0)
                        {
                            continue;
                        }
                        $last_id  = $id;
                        $id_array = array($id);
                        $level_array[$last_id] = ++$level;
                        continue;
                    }

                    if ($value[$pid_alias] == $last_id)
                    {
                        $options[$id]          = $value;
                        $options[$id]['level'] = $level;
                        $options[$id][$id_alias]    = $id;
                        // $options[$id]['typename']  = $value['typename'];
                        unset($arr[$key]);

                        if ($value['has_children'] > 0)
                        {
                            if (end($id_array) != $last_id)
                            {
                                $id_array[] = $last_id;
                            }
                            $last_id    = $id;
                            $id_array[] = $id;
                            $level_array[$last_id] = ++$level;
                        }
                    }
                    elseif ($value[$pid_alias] > $last_id)
                    {
                        break;
                    }
                }

                $count = count($id_array);
                if ($count > 1)
                {
                    $last_id = array_pop($id_array);
                }
                elseif ($count == 1)
                {
                    if ($last_id != end($id_array))
                    {
                        $last_id = end($id_array);
                    }
                    else
                    {
                        $level = 0;
                        $last_id = 0;
                        $id_array = array();
                        continue;
                    }
                }

                if ($last_id && isset($level_array[$last_id]))
                {
                    $level = $level_array[$last_id];
                }
                else
                {
                    $level = 0;
                    break;
                }
            }
            $cat_options[0] = $options;
        }
        else
        {
            $options = $cat_options[0];
        }

        if (!$spec_id)
        {
            return $options;
        }
        else
        {
            if (empty($options[$spec_id]))
            {
                return array();
            }

            $spec_id_level = $options[$spec_id]['level'];

            foreach ($options AS $key => $value)
            {
                if ($key != $spec_id)
                {
                    unset($options[$key]);
                }
                else
                {
                    break;
                }
            }

            $spec_id_array = array();
            foreach ($options AS $key => $value)
            {
                if (($spec_id_level == $value['level'] && $value[$id_alias] != $spec_id) ||
                    ($spec_id_level > $value['level']))
                {
                    break;
                }
                else
                {
                    $spec_id_array[$key] = $value;
                }
            }
            $cat_options[$spec_id] = $spec_id_array;

            return $spec_id_array;
        }
    }
}

if (!function_exists('img_replace_url')) 
{
    /**
     * 內容圖片地址替換成帶有http地址
     *
     * @param string $content 內容
     * @param string $imgurl 遠端圖片url
     * @return string
     */
    function img_replace_url($content='', $imgurl = '')
    {
        $pregRule = "/<img(.*?)src(\s*)=(\s*)[\'|\"](.*?(?:[\.jpg|\.jpeg|\.png|\.gif|\.bmp|\.ico]))[\'|\"](.*?)[\/]?(\s*)>/i";
        $content = preg_replace($pregRule, '<img ${1} src="'.$imgurl.'" ${5} />', $content);

        return $content;
    }
}

if (!function_exists('getCmsVersion')) 
{
    /**
     * 獲取目前CMS版本號
     *
     * @return string
     */
    function getCmsVersion()
    {
        $ver = 'v1.1.8';
        $version_txt_path = ROOT_PATH.'data/conf/version.txt';
        if(file_exists($version_txt_path)) {
            $fp = fopen($version_txt_path, 'r');
            $content = fread($fp, filesize($version_txt_path));
            fclose($fp);
            $ver = $content ? $content : $ver;
        } else {
            $r = tp_mkdir(dirname($version_txt_path));
            if ($r) {
                $fp = fopen($version_txt_path, "w+") or die("請設定".$version_txt_path."的許可權為777");
                $web_version = tpCache('system.system_version');
                $ver = !empty($web_version) ? $web_version : $ver;
                if (fwrite($fp, $ver)) {
                    fclose($fp);
                }
            }
        }
        return $ver;
    }
}

if (!function_exists('getVersion')) 
{
    /**
     * 獲取目前各種版本號
     *
     * @return string
     */
    function getVersion($filename='version', $ver='v1.0.0')
    {
        $version_txt_path = ROOT_PATH.'data/conf/'.$filename.'.txt';
        if(file_exists($version_txt_path)) {
            $fp = fopen($version_txt_path, 'r');
            $content = fread($fp, filesize($version_txt_path));
            fclose($fp);
            $ver = $content ? $content : $ver;
        } else {
            $r = tp_mkdir(dirname($version_txt_path));
            if ($r) {
                $fp = fopen($version_txt_path, "w+") or die("請設定".$version_txt_path."的許可權為777");
                if (fwrite($fp, $ver)) {
                    fclose($fp);
                }
            }
        }
        return $ver;
    }
}

if (!function_exists('getWeappVersion')) 
{
    /**
     * 獲取目前外掛版本號
     *
     * @param string $ocde 外掛標識
     * @return string
     */
    function getWeappVersion($code)
    {
        $ver = 'v1.0';
        $config_path = WEAPP_DIR_NAME.DS.$code.DS.'config.php';
        if(file_exists($config_path)) {
            $config = include $config_path;
            $ver = !empty($config['version']) ? $config['version'] : $ver;
        } else {
            die($code."外掛缺少".$config_path."配置檔案");
        }
        return $ver;
    }
}

if (!function_exists('strip_sql')) 
{
    /**
     * 轉換SQL關鍵字
     *
     * @param unknown_type $string
     * @return unknown
     */
    function strip_sql($string) {
        $pattern_arr = array(
                "/\bunion\b/i",
                "/\bselect\b/i",
                "/\bupdate\b/i",
                "/\bdelete\b/i",
                "/\boutfile\b/i",
                // "/\bor\b/i",
                "/\bchar\b/i",
                "/\bconcat\b/i",
                "/\btruncate\b/i",
                "/\bdrop\b/i",            
                "/\binsert\b/i", 
                "/\brevoke\b/i", 
                "/\bgrant\b/i",      
                "/\breplace\b/i", 
                // "/\balert\b/i", 
                "/\brename\b/i",            
                // "/\bmaster\b/i",
                "/\bdeclare\b/i",
                // "/\bsource\b/i",
                // "/\bload\b/i",
                // "/\bcall\b/i", 
                "/\bexec\b/i",         
                "/\bdelimiter\b/i",
                "/\bphar\b\:/i",
                "/\bphar\b/i",
                "/\@(\s*)\beval\b/i",
                "/\beval\b/i",
        );
        $replace_arr = array(
                'ｕｎｉｏｎ',
                'ｓｅｌｅｃｔ',
                'ｕｐｄａｔｅ',
                'ｄｅｌｅｔｅ',
                'ｏｕｔｆｉｌｅ',
                // 'ｏｒ',
                'ｃｈａｒ',
                'ｃｏｎｃａｔ',
                'ｔｒｕｎｃａｔｅ',
                'ｄｒｏｐ',            
                'ｉｎｓｅｒｔ',
                'ｒｅｖｏｋｅ',
                'ｇｒａｎｔ',
                'ｒｅｐｌａｃｅ',
                // 'ａｌｅｒｔ',
                'ｒｅｎａｍｅ',
                // 'ｍａｓｔｅｒ',
                'ｄｅｃｌａｒｅ',
                // 'ｓｏｕｒｃｅ',
                // 'ｌｏａｄ',
                // 'ｃａｌｌ',                 
                'ｅｘｅｃ',         
                'ｄｅｌｉｍｉｔｅｒ',
                'ｐｈａｒ',
                'ｐｈａｒ',
                '＠ｅｖａｌ',
                'ｅｖａｌ',
        );
     
        return is_array($string) ? array_map('strip_sql', $string) : preg_replace($pattern_arr, $replace_arr, $string);
    }
}

if (!function_exists('get_weapp_class')) 
{
    /**
     * 獲取外掛類的類名
     *
     * @param strng $name 外掛名
     * @param strng $controller 控制器
     * @return class
     */
    function get_weapp_class($name, $controller = ''){
        $controller = !empty($controller) ? $controller : $name;
        $class = WEAPP_DIR_NAME."\\{$name}\\controller\\{$controller}";
        return $class;
    }
}

if (!function_exists('view_logic')) 
{
    /**
     * 模型對應邏輯
     * @param intval $aid 文件ID
     * @param intval $channel 欄目ID
     * @param intval $result 陣列
     * @return array
     */
    function view_logic($aid, $channel, $result = array())
    {
        $result['image_list'] = $result['attr_list'] = $result['file_list'] = array();
        switch ($channel) {
            case '2': // 產品模型
            {
                /*產品相簿*/
                $image_list = model('ProductImg')->getProImg($aid);

                // 支援子目錄
                foreach ($image_list as $k1 => $v1) {
                    $image_list[$k1]['image_url'] = handle_subdir_pic($v1['image_url']);
                }
                
                $result['image_list'] = $image_list;
                /*--end*/

                /*產品參數*/
                $attr_list = model('ProductAttr')->getProAttr($aid);
                $attr_list = model('LanguageAttr')->getBindValue($attr_list, 'product_attribute', get_main_lang()); // 獲取多語言關聯繫結的值
                $result['attr_list'] = $attr_list;
                /*--end*/
                break;
            }

            case '3': // 圖集模型
            {
                /*圖集相簿*/
                $image_list = model('ImagesUpload')->getImgUpload($aid);
                
                // 支援子目錄
                foreach ($image_list as $k1 => $v1) {
                    $image_list[$k1]['image_url'] = handle_subdir_pic($v1['image_url']);
                }

                $result['image_list'] = $image_list;
                /*--end*/
                break;
            }

            case '4': // 下載模型
            {
                /*下載資料列表*/
                $file_list = model('DownloadFile')->getDownFile($aid);
                
                // 支援子目錄
                foreach ($file_list as $k1 => $v1) {
                    $file_list[$k1]['file_url'] = handle_subdir_pic($v1['file_url']);
                }

                $result['file_list'] = $file_list;
                /*--end*/
                break;
            }

            default:
            {
                break;
            }
        }

        return $result;
    }
}

if (!function_exists('uncamelize')) 
{
    /**
     * 駝峰命名轉下劃線命名
     * 思路:
     * 小寫和大寫緊挨一起的地方,加上分隔符,然後全部轉小寫
     */
    function uncamelize($camelCaps, $separator='_')
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
    }
}

if (!function_exists('GetDatabaseData')) 
{
    /**
     * 獲取數據中指定數據表的圖片和檔案路徑
     * 思路:
     * 
     */
    function GetDatabaseData($array)
    {
        $data  = $hello = array();
        $i = '0';
        foreach ($array as $value) {
            $where  = $value['field']."<>''";
            $result = M($value['table'])->field($value['field'])->where($where)->select();
            // 查詢多餘未使用檔案
            foreach ($result as $vv) {
                if ('litpic' == $value['field']) {
                    $path = parse_url($vv['litpic']);
                    if ($path['host']) {
                        $data[$i] = ROOT_DIR.$path['path'];
                    }else{
                        $data[$i] = $path['path'];
                    }
                    $i++;
                } else if ('image_url' == $value['field']) {
                    $path = parse_url($vv['image_url']);
                    if ($path['host']) {
                        $data[$i] = ROOT_DIR.$path['path'];
                    }else{
                        $data[$i] = $path['path'];
                    }
                    $i++;
                } else if ('logo' == $value['field']) {
                    $path = parse_url($vv['logo']);
                    if ($path['host']) {
                        $data[$i] = ROOT_DIR.$path['path'];
                    }else{
                        $data[$i] = $path['path'];
                    }
                    $i++;
                } else if ('file_url' == $value['field']) {
                    $path = parse_url($vv['file_url']);
                    if ($path['host']) {
                        $data[$i] = ROOT_DIR.$path['path'];
                    }else{
                        $data[$i] = $path['path'];
                    }
                    $i++;
                } else if ('head_pic' == $value['field']) {
                    $path = parse_url($vv['head_pic']);
                    if ($path['host']) {
                        $data[$i] = ROOT_DIR.$path['path'];
                    }else{
                        $data[$i] = $path['path'];
                    }
                    $i++;
                } else if ('intro' == $value['field']) {
                    $str = htmlspecialchars_decode($vv['intro']);
                    $str = strip_tags($str, '<img>');
                    preg_match_all('/\<img(.*?)src\=[\'|\"]([\w:\/\.]+)[\'|\"]/i', $str, $matches);
                    $match = $matches[2];
                    foreach ($match as $vvv) {
                        $data[$i] = $vvv;
                        $i++;
                    }
                } else if ('content' == $value['field']) {
                    $str = htmlspecialchars_decode($vv['content']);
                    $str = strip_tags($str, '<img>');
                    preg_match_all('/\<img(.*?)src\=[\'|\"]([\w:\/\.]+)[\'|\"]/i', $str, $matches);
                    $match = $matches[2];
                    foreach ($match as $vvv) {
                        $data[$i] = $vvv;
                        $i++;
                    }
                } else if ('config' == $value['table']) {
                    $strlen = strlen(ROOT_DIR);
                    if (substr($vv['value'], $strlen, 7 ) == '/public' || substr($vv['value'], $strlen, 7 ) == '/upload') {
                        $data[$i] = $vv['value'];
                        $i++;
                    }
                } else if ('ui_config' == $value['table']) {
                    $values = json_decode($vv['value'],true);
                    $str = htmlspecialchars_decode($values['info']['value']);
                    $str = strip_tags($str, '<img>');
                    preg_match_all('/\<img(.*?)src\=[\'|\"]([\w:\/\.]+)[\'|\"]/i', $str, $matches);
                    $match = $matches[2];
                    foreach ($match as $vvv) {
                        $data[$i] = $vvv;
                        $i++;
                    }
                } else if($value['channel_id']){
                    if ('imgs' == $value['dtype']) {
                        $hello = explode(',',$vv[$value['field']]);
                    } else if ('img' == $value['dtype']) {
                        $path = parse_url($vv[$value['field']]);
                        if ($path['host']) {
                            $data[$i] = ROOT_DIR.$path['path'];
                        }else{
                            $data[$i] = $path['path'];
                        }
                        $i++;
                    }
                }
            }
        }

        $data = array_merge($data,$hello);
        $data = array_unique($data);
        return $data;
    }
}

if (!function_exists('read_bidden_inc')) 
{
    /**
     * 讀取被禁止外部訪問的配置檔案
     * 
     */
    function read_bidden_inc($phpfilepath = '')
    {
        $data = @file($phpfilepath);
        if ($data) {
            $data = !empty($data[1]) ? json_decode($data[1]) : [];
        }
        return $data;
    }
}

if (!function_exists('write_bidden_inc')) 
{
    /**
     * 寫入被禁止外部訪問的配置檔案
     */
    function write_bidden_inc($arr = array(), $phpfilepath)
    {
        $r = tp_mkdir(dirname($phpfilepath));
        if ($r) {
            $setting = "<?php die('forbidden'); ?>\n";
            $setting .= json_encode($arr);
            $setting = str_replace("\/", "/",$setting);
            $incFile = fopen($phpfilepath, "w+");
            if (fwrite($incFile, $setting)) {
                fclose($incFile);
                return true;
            }else{
                return false;
            }
        }
    }
}

// if (!function_exists('get_auth_code')) 
// {
//     /**
//      * 密碼加密串
//      */
//     function get_auth_code()
//     {
//         $auth_code = \think\Config::get('AUTH_CODE');
//         $filepath = DATA_PATH.'conf/authcode.php';
//         if(file_exists($filepath)) {
//             $data = (array)read_bidden_inc($filepath);
//             $auth_code = !empty($data['auth_code']) ? $data['auth_code'] : $auth_code;
//         } else {
//             write_bidden_inc(['auth_code'=>$auth_code], $filepath);
//         }

//         return $auth_code;
//     }
// }