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

class Index extends Base
{
    public function index()
    {
        $language_db = Db::name('language');

        /*多語言列表*/
        $web_language_switch = tpCache('web.web_language_switch');
        $languages = [];
        if (1 == intval($web_language_switch)) {
            $languages = $language_db->field('a.mark, a.title')
                ->alias('a')
                ->where('a.status',1)
                ->getAllWithIndex('mark');
        }
        $this->assign('languages', $languages);
        $this->assign('web_language_switch', $web_language_switch);
        /*--end*/

        /*網站首頁鏈接*/
        // 去掉入口檔案
        $inletStr = '/index.php';
        $seo_inlet = config('ey_config.seo_inlet');
        1 == intval($seo_inlet) && $inletStr = '';
        // --end
        $home_default_lang = config('ey_config.system_home_default_lang');
        $admin_lang = $this->admin_lang;
        $home_url = request()->domain().ROOT_DIR.'/';  // 支援子目錄
        if ($home_default_lang != $admin_lang) {
            $home_url = $language_db->where(['mark'=>$admin_lang])->getField('url');
            if (empty($home_url)) {
                $seoConfig = tpCache('seo');
                $seo_pseudo = !empty($seoConfig['seo_pseudo']) ? $seoConfig['seo_pseudo'] : config('ey_config.seo_pseudo');
                if (1 == $seo_pseudo) {
                    $home_url = request()->domain().ROOT_DIR.$inletStr; // 支援子目錄
                    if (!empty($inletStr)) {
                        $home_url .= '?';
                    } else {
                        $home_url .= '/?';
                    }
                    $home_url .= http_build_query(['lang'=>$admin_lang]);
                } else {
                    $home_url = request()->domain().ROOT_DIR.$inletStr.'/'.$admin_lang; // 支援子目錄
                }
            }
        }
        $this->assign('home_url', $home_url);
        /*--end*/

        $this->assign('admin_info', getAdminInfo(session('admin_id')));
        $this->assign('menu',getMenuList());

        /*檢測是否存在會員中心模板*/
        $globalConfig = tpCache('global');
        if ('v1.0.1' > getVersion('version_themeusers') && !empty($globalConfig['web_users_switch'])) {
            $is_syn_theme_users = 1;
        } else {
            $is_syn_theme_users = 0;
        }
        $this->assign('is_syn_theme_users',$is_syn_theme_users);
        /*--end*/

        return $this->fetch();
    }
   
    public function welcome()
    {
        $globalConfig = tpCache('global');
        /*百度分享*/
        $share = array(
            'bdText'    => $globalConfig['web_title'],
            'bdPic'     => is_http_url($globalConfig['web_logo']) ? $globalConfig['web_logo'] : request()->domain().$globalConfig['web_logo'],
            'bdUrl'     => $globalConfig['web_basehost'],
        );
        $this->assign('share',$share);
        /*--end*/

        /*系統提示*/
        $system_explanation_welcome = $globalConfig['system_explanation_welcome'];
        $sqlfiles = glob(DATA_PATH.'sqldata/*');
        foreach ($sqlfiles as $file) {
            if(stristr($file, getCmsVersion())){
                $system_explanation_welcome = 1;
            }
        }
        $this->assign('system_explanation_welcome', $system_explanation_welcome);
        /*--end*/

        $this->assign('sys_info',$this->get_sys_info());
        $this->assign('web_show_popup_upgrade', $globalConfig['web_show_popup_upgrade']);

        $ajaxLogic = new \app\admin\logic\AjaxLogic;
        $ajaxLogic->update_robots(); // 自動糾正蜘蛛抓取檔案rotots.txt
        $ajaxLogic->update_template('users'); // 升級前臺會員中心的模板檔案

        return $this->fetch();
    }
    
    public function get_sys_info()
    {
        $sys_info['os']             = PHP_OS;
        $sys_info['zlib']           = function_exists('gzclose') ? 'YES' : '<font color="red">NO（請開啟 php.ini 中的php-zlib擴充套件）</font>';//zlib
        $sys_info['safe_mode']      = (boolean) ini_get('safe_mode') ? 'YES' : 'NO';//safe_mode = Off       
        $sys_info['timezone']       = function_exists("date_default_timezone_get") ? date_default_timezone_get() : "no_timezone";
        $sys_info['curl']           = function_exists('curl_init') ? 'YES' : '<font color="red">NO（請開啟 php.ini 中的php-curl擴充套件）</font>';  
        $sys_info['web_server']     = $_SERVER['SERVER_SOFTWARE'];
        $sys_info['phpv']           = phpversion();
        $sys_info['ip']             = serverIP();
        $sys_info['postsize']       = @ini_get('file_uploads') ? ini_get('post_max_size') :'unknown';
        $sys_info['fileupload']     = @ini_get('file_uploads') ? ini_get('upload_max_filesize') :'unknown';
        $sys_info['max_ex_time']    = @ini_get("max_execution_time").'s'; //指令碼最大執行時間
        $sys_info['set_time_limit'] = function_exists("set_time_limit") ? true : false;
        $sys_info['domain']         = $_SERVER['HTTP_HOST'];
        $sys_info['memory_limit']   = ini_get('memory_limit');
        $sys_info['version']        = file_get_contents(DATA_PATH.'conf/version.txt');
        $mysqlinfo = Db::query("SELECT VERSION() as version");
        $sys_info['mysql_version']  = $mysqlinfo[0]['version'];
        if(function_exists("gd_info")){
            $gd = gd_info();
            $sys_info['gdinfo']     = $gd['GD Version'];
        }else {
            $sys_info['gdinfo']     = "未知";
        }
        if (extension_loaded('zip')) {
            $sys_info['zip']     = "YES";
        } else {
            $sys_info['zip']     = '<font color="red">NO（請開啟 php.ini 中的php-zip擴充套件）</font>';
        }
        $upgradeLogic = new \app\admin\logic\UpgradeLogic();
        $sys_info['curent_version'] = $upgradeLogic->curent_version; //目前程式版本
        $sys_info['web_name'] = tpCache('global.web_name');

        return $sys_info;
    }

    /**
     * 錄入商業授權
     */
    public function authortoken()
    {
        $inc_type = 'web';
        if (IS_POST) {
            $web_authortoken = input('post.web_authortoken/s', '');
            $web_authortoken = trim($web_authortoken);
            $result = false;
            /*多語言*/
            if (is_language()) {
                $langRow = Db::name('language')->cache(true, EYOUCMS_CACHE_TIME, 'language')
                    ->order('id asc')
                    ->select();
                foreach ($langRow as $key => $val) {
                    $result = tpCache($inc_type, ['web_authortoken'=>$web_authortoken], $val['mark']);
                }
            } else { // 單語言
                $result = tpCache($inc_type, array('web_authortoken'=>$web_authortoken));
            }
            /*--end*/
            if ($result) {
                $domain = config('service_ey');
                $domain = base64_decode($domain);
                $vaules = array(
                    'authortoken_code'   => $web_authortoken,
                    'client_domain' => urldecode($this->request->host(true)),
                );
                $url = $domain.'/index.php?m=api&c=Service&a=check_authortoken&'.http_build_query($vaules);
                $context = stream_context_set_default(array('http' => array('timeout' => 3,'method'=>'GET')));
                $response = @file_get_contents($url,false,$context);
                $params = json_decode($response,true);
                $msg = '授權成功';
                $wait = 1;
                if (false === $response || (is_array($params) && 1 == $params['code'])) {
                    $source = realpath('public/static/admin/images/logo_ey.png');
                    $destination = realpath('public/static/admin/images/logo.png');
                    @copy($source, $destination);
                } else {
                    $msg = '儲存成功'.$params['msg'];
                    $wait = 3;
                }

                session('isset_author', null);
                adminLog('錄入商業授權');
                $this->success($msg, request()->baseFile(), '', $wait, [], '_parent');
            }else{
                $this->error("授權失敗!", url('Index/authortoken'));
            }
            exit;
        }
        $web_authortoken = tpCache($inc_type.'.web_authortoken');
        $this->assign('web_authortoken', $web_authortoken);
        $this->assign('inc_type', $inc_type);

        return $this->fetch();
    }

    /**
     * 更換後臺logo
     */
    public function edit_adminlogo()
    {
        $filename = input('param.filename/s', '');
        if (!empty($filename)) {
            $source = realpath(preg_replace('#^'.ROOT_DIR.'/#i', '', $filename)); // 支援子目錄
            $web_is_authortoken = tpCache('web.web_is_authortoken');
            if (empty($web_is_authortoken)) {
                $destination = realpath('public/static/admin/images/logo.png');
            } else {
                $destination = realpath('public/static/admin/images/logo_ey.png');
            }
            if (@copy($source, $destination)) {
                $this->success('操作成功');
            }
        }
        $this->error('操作失敗');
    }

    /**
     * 待處理事項
     */
    public function pending_matters()
    {
        $html = '<div style="text-align: center; margin: 20px 0px; color:red;">惹妹子生氣了，沒啥好處理！</div>';
        echo $html;
    }
    
    /**
     * ajax 修改指定表數據欄位  一般修改狀態 比如 是否推薦 是否開啟 等 圖示切換的
     * table,id_name,id_value,field,value
     */
    public function changeTableVal()
    {
        if (IS_AJAX_POST) {
            $url = null;
            $data = [
                'refresh'   => 0,
            ];

            $table = input('post.table/s'); // 表名
            $id_name = input('post.id_name/s'); // 表主鍵id名
            $id_value = input('post.id_value/s'); // 表主鍵id值
            $field  = input('post.field/s'); // 修改哪個欄位
            $value  = input('post.value/s', '', null); // 修改欄位值  

            switch ($table) {
                // 會員等級表
                case 'users_level':
                    {
                        $return = model('UsersLevel')->isRequired($id_name,$id_value,$field,$value);
                        if (is_array($return)) {
                            $this->error($return['msg']);
                        }
                    }
                    break;
                
                // 會員屬性表
                case 'users_parameter':
                    {
                        $return = model('UsersParameter')->isRequired($id_name,$id_value,$field,$value);
                        if (is_array($return)) {
                            $this->error($return['msg']);
                        }
                    }
                    break;
                
                // 會員屬性表
                case 'users_menu':
                    {
                        Db::name('users_menu')->where('id','gt',0)->update([
                                'is_userpage'   => 0,
                                'update_time'   => getTime(),
                            ]);
                        $data['refresh'] = 1;
                    }
                    break;

                default:
                    # code...
                    break;
            }

            $savedata = [
                $field => $value,
                'update_time'   => getTime(),
            ];
            M($table)->where("$id_name = $id_value")->cache(true,null,$table)->save($savedata); // 根據條件儲存修改的數據

            // 以下程式碼可以考慮去掉，與行為里的清除快取重複 AppEndBehavior.php / clearHtmlCache
            switch ($table) {
                case 'auth_modular':
                    extra_cache('admin_auth_modular_list_logic', null);
                    extra_cache('admin_all_menu', null);
                    break;
                
                default:
                    // 清除logic邏輯定義的快取
                    extra_cache('admin_'.$table.'_list_logic', null);
                    // 清除一下快取
                    // delFile(RUNTIME_PATH.'html'); // 先清除快取, 否則不好預覽
                    \think\Cache::clear($table);
                    break;
            }

            /*清除頁面快取*/
            // $htmlCacheLogic = new \app\common\logic\HtmlCacheLogic;
            // $htmlCacheLogic->clear_archives();
            /*--end*/
            
            $this->success('更新成功', $url, $data);
        }
    }

    /**
     * 功能開關
     */
    public function switch_map()
    {
        if (IS_POST) {
            $inc_type = input('post.inc_type/s');
            $name = input('post.name/s');
            $value = input('post.value/s');

            $data = [];
            switch ($inc_type) {
                case 'pay':
                case 'shop':
                {
                    getUsersConfigData($inc_type, [$name => $value]);

                    // 同時開啟會員中心
                    if (1 == $value) {
                        /*多語言*/
                        if (is_language()) {
                            $langRow = \think\Db::name('language')->order('id asc')
                                ->cache(true, EYOUCMS_CACHE_TIME, 'language')
                                ->select();
                            foreach ($langRow as $key => $val) {
                                tpCache('web', ['web_users_switch' => 1], $val['mark']);
                            }
                        } else { // 單語言
                            tpCache('web', ['web_users_switch' => 1]);
                        }
                        /*--end*/
                    }

                    if (in_array($name, ['shop_open'])) {
                        // $data['reload'] = 1;
                        /*檢測是否存在訂單中心模板*/
                        if ('v1.0.1' > getVersion('version_themeshop') && !empty($value)) {
                            $is_syn = 1;
                        } else {
                            $is_syn = 0;
                        }
                        $data['is_syn'] = $is_syn;
                        /*--end*/
                        // 同步會員中心的左側菜單
                        if ('shop_open' == $name) {
                            Db::name('users_menu')->where([
                                    'mca'   => 'user/Shop/shop_centre',
                                    'lang'  => $this->home_lang,
                                ])->update([
                                    'status'    => (1 == $value) ? 1 : 0,
                                    'update_time'   => getTime(),
                                ]);
                        }
                    } else if ('pay_open' == $name) {
                        // 同步會員中心的左側菜單
                        Db::name('users_menu')->where([
                                'mca'   => 'user/Pay/pay_consumer_details',
                                'lang'  => $this->home_lang,
                            ])->update([
                                'status'    => (1 == $value) ? 1 : 0,
                                'update_time'   => getTime(),
                            ]);
                    }
                    break;
                }

                case 'web':
                {
                    /*多語言*/
                    if (is_language()) {
                        $langRow = \think\Db::name('language')->order('id asc')
                            ->cache(true, EYOUCMS_CACHE_TIME, 'language')
                            ->select();
                        foreach ($langRow as $key => $val) {
                            tpCache($inc_type, [$name => $value], $val['mark']);
                        }
                    } else { // 單語言
                        tpCache($inc_type, [$name => $value]);
                    }
                    /*--end*/

                    if (in_array($name, ['web_users_switch'])) {
                        // $data['reload'] = 1;
                        /*檢測是否存在會員中心模板*/
                        if ('v1.0.1' > getVersion('version_themeusers') && !empty($value)) {
                            $is_syn = 1;
                        } else {
                            $is_syn = 0;
                        }
                        $data['is_syn'] = $is_syn;
                        /*--end*/
                    }
                    break;
                }
            }

            $this->success('操作成功', null, $data);
        }

        $globalConfig = tpCache('global');
        $this->assign('globalConfig', $globalConfig);

        $UsersConfigData = getUsersConfigData('all');
        $this->assign('userConfig',$UsersConfigData);

        $is_online = 0;
        if (is_realdomain()) {
            $is_online = 1;
        }
        $this->assign('is_online',$is_online);

        /*檢測是否存在會員中心模板*/
        if ('v1.0.1' > getVersion('version_themeusers')) {
            $is_themeusers_exist = 1;
        } else {
            $is_themeusers_exist = 0;
        }
        $this->assign('is_themeusers_exist',$is_themeusers_exist);
        /*--end*/

        /*檢測是否存在商城中心模板*/
        if ('v1.0.1' > getVersion('version_themeshop')) {
            $is_themeshop_exist = 1;
        } else {
            $is_themeshop_exist = 0;
        }
        $this->assign('is_themeshop_exist',$is_themeshop_exist);
        /*--end*/

        return $this->fetch();
    }
}
