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

return array(
    // +----------------------------------------------------------------------
    // | 應用設定
    // +----------------------------------------------------------------------

    // 預設Host地址
    'app_host'               => '',
    // 應用名稱空間
    'app_namespace'          => 'app',
    // 應用除錯模式
    'app_debug'              => false,
    // 應用Trace
    'app_trace'              => false,
    // 應用模式狀態
    'app_status'             => '',
    // 是否支援多模組
    'app_multi_module'       => true,
    // 入口自動繫結模組
    'auto_bind_module'       => false,
    // 註冊的根名稱空間
    'root_namespace'         => array(),
    // 擴充套件函式檔案
    'extra_file_list'        => array(APP_PATH . 'helper' . EXT, THINK_PATH . 'helper' . EXT, APP_PATH . 'function' . EXT),
    // 預設輸出型別
    'default_return_type'    => 'html',
    // 預設AJAX 數據返回格式,可選json xml ...
    'default_ajax_return'    => 'json',
    // 預設JSONP格式返回的處理方法
    'default_jsonp_handler'  => 'jsonpReturn',
    // 預設JSONP處理方法
    'var_jsonp_handler'      => 'callback',
    // 預設時區
    'default_timezone'       => 'PRC',
    // 是否開啟多語言
    'lang_switch_on'         => true,
    // 預設全域性過濾方法 用逗號分隔多個
    'default_filter'         => 'strip_sql,htmlspecialchars', // htmlspecialchars
    // 預設語言
    'default_lang'           => 'cn',
    // 應用類庫後綴
    'class_suffix'           => false,
    // 控制器類後綴
    'controller_suffix'      => false,
    // 是否https鏈接
    'is_https'               => false,

    // +----------------------------------------------------------------------
    // | 模組設定
    // +----------------------------------------------------------------------

    // 預設模組名
    'default_module'         => 'home',
    // 禁止訪問模組
    'deny_module_list'       => array('common'),
    // 預設控制器名
    'default_controller'     => 'Index',
    // 預設操作名
    'default_action'         => 'index',
    // 預設驗證器
    'default_validate'       => '',
    // 預設的空控制器名
    'empty_controller'       => 'Error',
    // 操作方法後綴
    'action_suffix'          => '',
    // 自動搜索控制器
    'controller_auto_search' => false,

    // +----------------------------------------------------------------------
    // | URL設定
    // +----------------------------------------------------------------------

    // PATHINFO變數名 用於相容模式
    'var_pathinfo'           => 's',
    // 相容PATH_INFO獲取
    'pathinfo_fetch'         => array('ORIG_PATH_INFO', 'REDIRECT_PATH_INFO', 'REDIRECT_URL'),
    // pathinfo分隔符
    'pathinfo_depr'          => '/',
    // URL偽靜態後綴
    'url_html_suffix'        => 'html',
    // URL普通方式參數 用於自動產生
    'url_common_param'       => false,
    // URL參數方式 0 按名稱成對解析 1 按順序解析
    'url_param_type'         => 0,
    // 是否開啟路由
    'url_route_on'           => true,
    // 路由使用完整匹配
    'route_complete_match'   => false,
    // 路由配置檔案（支援配置多個）
    'route_config_file'      => array('route'),
    // 是否強制使用路由
    'url_route_must'         => false,
    // 域名部署
    'url_domain_deploy'      => false,
    // 域名根，如thinkphp.cn
    'url_domain_root'        => '',
    // 是否自動轉換URL中的控制器和操作名
    'url_convert'            => false,
    // 預設的訪問控制器層
    'url_controller_layer'   => 'controller',
    // 表單請求型別偽裝變數
    'var_method'             => '_method',
    // 表單ajax偽裝變數
    'var_ajax'               => '_ajax',
    // 表單pjax偽裝變數
    'var_pjax'               => '_pjax',
    // 是否開啟請求快取 true自動快取 支援設定請求快取規則
    'request_cache'          => false,
    // 請求快取有效期
    'request_cache_expire'   => null,
    // 全域性請求快取排除規則
    'request_cache_except'   => array(),

    // +----------------------------------------------------------------------
    // | 模板設定
    // +----------------------------------------------------------------------

    'template'               => array(
        // 模板引擎型別 支援 php think 支援擴充套件
        'type'         => 'Think',
        // 模板路徑
        'view_path'    => '',
        // 模板後綴
        'view_suffix'  => 'htm',
        // 模板檔名分隔符
        'view_depr'    => DS,
        // 模板引擎普通標籤開始標記
        'tpl_begin'    => '{',
        // 模板引擎普通標籤結束標記
        'tpl_end'      => '}',
        // 標籤庫標籤開始標記
        'taglib_begin' => '{',
        // 標籤庫標籤結束標記
        'taglib_end'   => '}',
    ),

    // 檢視輸出字串內容替換
    'view_replace_str'       => array(),
    // 預設跳轉頁面對應的模板檔案
    'dispatch_error_tmpl' => 'public/static/common/dispatch_jump.htm',
    // 預設成功跳轉對應的模板檔案
    'dispatch_success_tmpl' => 'public/static/common/dispatch_jump.htm', 

    // +----------------------------------------------------------------------
    // | 異常及錯誤設定
    // +----------------------------------------------------------------------

    // 異常頁面的模板檔案 
    'exception_tmpl'         => THINK_PATH . 'tpl' . DS . 'think_exception.tpl',
    // errorpage 錯誤頁面
    'error_tmpl'         => THINK_PATH . 'tpl' . DS . 'think_error.tpl', 
    

    // 錯誤顯示資訊,非除錯模式有效
    'error_message'          => '頁面錯誤！請稍後再試～',
    // 顯示錯誤資訊
    'show_error_msg'         => true,
    // 異常處理handle類 留空使用 \think\exception\Handle
    'exception_handle'       => '',

    // +----------------------------------------------------------------------
    // | 日誌設定
    // +----------------------------------------------------------------------

    'log'                    => array(
        // 日誌記錄方式，內建 file socket 支援擴充套件
        'type'  => 'File',
        // 日誌儲存目錄
        'path'  => LOG_PATH,
        // 日誌記錄級別
        'level' => array('error'),
        // 日誌開關  1 開啟 0 關閉
        'switch' => 0,  
    ),

    // +----------------------------------------------------------------------
    // | Trace設定 開啟 app_trace 后 有效
    // +----------------------------------------------------------------------
    'trace'                  => array(
        // 內建Html Console 支援擴充套件
        'type' => 'Html',
    ),

    // +----------------------------------------------------------------------
    // | 快取設定
    // +----------------------------------------------------------------------

    'cache'                  => array(
        // 驅動方式
        'type'   => 'File',
        // 快取儲存目錄
        'path'   => CACHE_PATH,
        // 快取字首
        'prefix' => '',
        // 快取有效期 0表示永久快取
        'expire' => 0,
    ),

    // +----------------------------------------------------------------------
    // | 會話設定
    // +----------------------------------------------------------------------

    'session'                => array(
        'id'             => '',
        // SESSION_ID的提交變數,解決flash上傳跨域
        'var_session_id' => '',
        // SESSION 字首
        'prefix'         => 'think',
        // 驅動方式 支援redis memcache memcached
        'type'           => '',
        // 是否自動開啟 SESSION
        'auto_start'     => true,
        // 主機
        // 'host'           => '127.0.0.1',
        // 埠
        // 'port'           => 11211,
        'path'  => 'data/session',
    ),

    // +----------------------------------------------------------------------
    // | Cookie設定
    // +----------------------------------------------------------------------
    'cookie'                 => array(
        // cookie 名稱字首
        'prefix'    => '',
        // cookie 儲存時間
        'expire'    => 0,
        // cookie 儲存路徑
        'path'      => '/',
        // cookie 有效域名
        'domain'    => '',
        //  cookie 啟用安全傳輸
        'secure'    => false,
        // httponly設定
        'httponly'  => '', // 設定為true時，通過阻止 JS 讀取 Cookie 來 防止XSS 攻擊
        // 是否使用 setcookie
        'setcookie' => true,
    ),
    
    // +----------------------------------------------------------------------
    // | Memcache設定(支援集群)
    // +----------------------------------------------------------------------
    'memcache'             => array(
        'switch'    => 0, // 0 關閉，1 開啟
        'host' => '127.0.0.1,127.0.0.2', // 多個集群IP用,隔開
        'port' => '11211,11212', // 多個集群埠號用,隔開
        'expire' => 0,
    ),

    //分頁配置
    'paginate'      => array(
        'type'      => 'eyou',
        'var_page'  => 'page',
        'list_rows' => 15,
    ),
    // 密碼加密串
    'AUTH_CODE' => "!*&^eyoucms<>|?", //安裝完畢之後不要改變，否則所有密碼都會出錯
    
    // 核心字串
    'service_ey' => "aHR0cDovL3NlcnZpY2UuZXlvdWNtcy5jb20=",
    'service_ey_token' => "0763150235251e259b1a47f2838ecc26",
    
    // +----------------------------------------------------------------------
    // | 驗證碼
    // +----------------------------------------------------------------------
    'captcha' => array(
        'default'    => [
            // 驗證碼字符集合
            'codeSet'  => '2345678abcdefhijkmnpqrstuvwxyz', 
            // 驗證碼字型大小(px)
            'fontSize' => 35, 
            // 是否畫混淆曲線
            'useCurve' => false, 
            // 是否新增雜點
            'useNoise' => false, 
            // 驗證碼圖片高度
            'imageH'   => 0,
            // 驗證碼圖片寬度
            'imageW'   => 0, 
            // 驗證碼位數
            'length'   => 4, 
            // 驗證成功后是否重置        
            'reset'    => false,
            // 驗證碼字型，不設定隨機獲取
            'fontttf' => '4.ttf',
        ],
        // 後臺登錄驗證碼配置
        'admin_login'   => [
            'is_on' => 1, // 開關
            'config' => [],
        ],
        // 表單提交驗證碼配置
        'form_submit'   => [
            'is_on' => 1, // 開關
            'config' => [],
        ],
        // 會員登錄驗證碼配置
        'users_login'   => [
            'is_on' => 0, // 開關
            'config' => [],
        ],
        // 會員註冊驗證碼配置
        'users_reg'   => [
            'is_on' => 0, // 開關
            'config' => [],
        ],
        // 會員找回密碼驗證碼配置
        'users_retrieve_password'   => [
            'is_on' => 0, // 開關
            'config' => [],
        ],
    ),

    // +----------------------------------------------------------------------
    // | 404頁面跳轉
    // +----------------------------------------------------------------------
    'http_exception_template' => array(
        // 定義404錯誤的重定向頁面地址
        404 => ROOT_PATH.'public/static/errpage/404.html',
        // 還可以定義其它的HTTP status
        401 => ROOT_PATH.'public/static/errpage/401.html',
    ),

    /**假設這個訪問地址是 www.xxxxx.dev/home/goods/goodsInfo/id/1.html 
     *就儲存名字為 home_goods_goodsinfo_1.html     
     *配置成這樣, 指定 模組 控制器 方法名 參數名
     */
    // true 開啟頁面快取
    'HTML_CACHE_STATUS' => true,
    // 快取的頁面，規則：模組 控制器 方法名 參數名
    'HTML_CACHE_ARR'    => [
        // 首頁
        'home_Index_index'      => ['filename'=>'index', 'cache'=>7200],
        // [普通偽靜態]文章
        'home_Article_index'    => ['filename'=>'channel', 'cache'=>7200],
        'home_Article_lists'    => ['filename'=>'lists', 'p'=>array('tid','page'), 'cache'=>7200],
        'home_Article_view'     => ['filename'=>'view', 'p'=>array('dirname','aid'), 'cache'=>7200],
        // [普通偽靜態]產品
        'home_Product_index'    => ['filename'=>'channel', 'cache'=>7200],
        'home_Product_lists'    => ['filename'=>'lists', 'p'=>array('tid','page'), 'cache'=>7200],
        'home_Product_view'     => ['filename'=>'view', 'p'=>array('dirname','aid'), 'cache'=>7200],
        // [普通偽靜態]圖集
        'home_Images_index'     => ['filename'=>'channel', 'cache'=>7200],
        'home_Images_lists'     => ['filename'=>'lists', 'p'=>array('tid','page'), 'cache'=>7200],
        'home_Images_view'      => ['filename'=>'view', 'p'=>array('dirname','aid'), 'cache'=>7200],
        // [普通偽靜態]下載
        'home_Download_index'   => ['filename'=>'channel', 'cache'=>7200],
        'home_Download_lists'   => ['filename'=>'lists', 'p'=>array('tid','page'), 'cache'=>7200],
        'home_Download_view'    => ['filename'=>'view', 'p'=>array('dirname','aid'), 'cache'=>7200],
        // [普通偽靜態]單頁
        'home_Single_index'     => ['filename'=>'channel', 'cache'=>7200],
        'home_Single_lists'     => ['filename'=>'lists', 'p'=>array('tid','page'), 'cache'=>7200],
        // [超短偽靜態]列表頁
        'home_Lists_index'      => ['filename'=>'lists', 'p'=>array('tid','page'), 'cache'=>7200],
        // [超短偽靜態]內容頁
        'home_View_index'       => ['filename'=>'view', 'p'=>array('dirname','aid'), 'cache'=>7200],
    ],

    // +----------------------------------------------------------------------
    // | 簡訊設定
    // +----------------------------------------------------------------------
    // 開啟除錯模式，跳過手機接收簡訊這一塊
    'sms_debug' => true,
    //簡訊使用場景
    'SEND_SCENE' => array(
        '1'=>array('使用者註冊','驗證碼${code}，您正在註冊成為${product}使用者，感謝您的支援！','regis_sms_enable'),
        '2'=>array('使用者找回密碼','驗證碼${code}，用於密碼找回，如非本人操作，請及時檢查賬戶安全','forget_pwd_sms_enable'),
        '3'=>array('身份驗證','尊敬的使用者，您的驗證碼為${code}, 請勿告訴他人.','bind_mobile_sms_enable'),
        '4'=>array('訊息通知','您有新的訊息：${content}，請注意查收！','messages_notice'),
    ),

    // +----------------------------------------------------------------------
    // | 郵件設定
    // +----------------------------------------------------------------------
    //郵件使用場景
    'send_email_scene' => [
        1   => ['scene'=>1], // 留言表單
        2   => ['scene'=>2], // 會員註冊
        3   => ['scene'=>3], // 繫結郵箱
        4   => ['scene'=>4], // 找回密碼
    ],
);
