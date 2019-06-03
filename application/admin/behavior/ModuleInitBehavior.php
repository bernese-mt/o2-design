<?php

namespace app\admin\behavior;

/**
 * 系統行為擴充套件：
 */
class ModuleInitBehavior {
    protected static $actionName;
    protected static $controllerName;
    protected static $moduleName;
    protected static $method;

    /**
     * 構造方法
     * @param Request $request Request對像
     * @access public
     */
    public function __construct()
    {

    }

    // 行為擴充套件的執行入口必須是run
    public function run(&$params){
        self::$actionName = request()->action();
        self::$controllerName = request()->controller();
        self::$moduleName = request()->module();
        self::$method = request()->method();
        // file_put_contents ( DATA_PATH."log.txt", date ( "Y-m-d H:i:s" ) . "  " . var_export('admin_CoreProgramBehavior',true) . "\r\n", FILE_APPEND );
        $this->_initialize();
    }

    private function _initialize() {
        $this->setChanneltypeStatus();
        $this->update_databasefile();
        // $this->iisInlet();
        // $this->checkInlet();
    }

    /**
     * 根據前端模板自動開啟系統模型
     */
    private function setChanneltypeStatus()
    {
        /*不在以下相應的控制器和操作名里不往下執行，以便提高效能*/
        $ctlActArr = array(
            'Index@index',
            'System@clear_cache',
        );
        $ctlActStr = self::$controllerName.'@'.self::$actionName;
        if (!in_array($ctlActStr, $ctlActArr) || 'GET' != self::$method) {
            return true;
        }
        /*--end*/
        
        model('Channeltype')->setChanneltypeStatus();
    }

    /**
     * iis伺服器自動追加URL重寫，入口index.php被隱藏
     */
    private function iisInlet() {
        /*不在以下相應的控制器和操作名里不往下執行，以便提高效能*/
        $ctlActArr = array(
            'Admin@login',
            'System@clear_cache',
        );
        $ctlActStr = self::$controllerName.'@'.self::$actionName;
        $seo_inlet = tpCache('seo.seo_inlet');
        if (!in_array($ctlActStr, $ctlActArr) || 'GET' != self::$method || 1 == $seo_inlet) {
            return true;
        }
        /*--end*/

        $web_server = $_SERVER["SERVER_SOFTWARE"];
        if (stristr($web_server, 'iis')) {

            $indexRewrite = <<<EOF
<rule name="Imported Rule 1" enabled="true" stopProcessing="true">
                    <match url="^(.*)$" />
                    <conditions logicalGrouping="MatchAll">
                        <add input="{HTTP_HOST}" pattern="^(.*)$" />
                        <add input="{REQUEST_FILENAME}" matchType="IsFile" negate="true" />
                        <add input="{REQUEST_FILENAME}" matchType="IsDirectory" negate="true" />
                    </conditions>
                    <action type="Rewrite" url="index.php/{R:1}" />
                </rule>
EOF;
            if (file_exists(ROOT_PATH.'web.config')) {
                $webconfig = @file_get_contents(ROOT_PATH.'web.config');
                if (!stristr($webconfig, 'index.php/{r:')) {
                    if (stristr($webconfig, '<rules>')) {
                        $rewrite = <<<EOF

                {$indexRewrite}
            </rules>
EOF;
                        $webconfig = str_ireplace('</rules>', $rewrite, $webconfig);
                    } else {
                        $rewrite = <<<EOF

        <rewrite>
            <rules>
                {$indexRewrite}
            </rules>
        </rewrite>
    </system.webServer>
EOF;
                        $webconfig = str_ireplace('</system.webServer>', $rewrite, $webconfig);
                    }
                }
            } else {
                $webconfig = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<configuration>
    <system.webServer>
        <security>
            <requestFiltering allowDoubleEscaping="true"/>
        </security>

        <rewrite>
            <rules>
                {$indexRewrite}
            </rules>
        </rewrite>
    </system.webServer>
</configuration>
EOF;
            }

            @file_put_contents(ROOT_PATH . 'web.config', $webconfig);
        }
    }

    /**
     * 檢測url入口index.php是否被重寫隱藏
     */
    private function checkInlet() {
        /*不在以下相應的控制器和操作名里不往下執行，以便提高效能*/
        $ctlActArr = array(
            'Index@welcome',
            'System@clear_cache',
        );
        $ctlActStr = self::$controllerName.'@'.self::$actionName;
        $cacheKey = 'admin_ModuleInitBehavior_isset_checkInlet';
        $cacheVal = cache($cacheKey);
        if ('POST' == self::$method && 'System@clear_cache' == $ctlActStr) {

        } else if (!in_array($ctlActStr, $ctlActArr) || !empty($cacheVal)) {
            return true;
        }
        cache($cacheKey, 1);
        /*--end*/

        $now_seo_inlet = 0; // 預設不隱藏入口
        
        /*檢測是否支援URL重寫隱藏應用的入口檔案index.php*/
        try {
            $response = false;

            $seo_force_inlet = tpCache('seo.seo_force_inlet');
            if (1 == $seo_force_inlet) {
                $response = 'Congratulations on passing';
            } else {
                $url = request()->domain().ROOT_DIR.'/api/Rewrite/testing.html';
                $context = stream_context_set_default(array('http' => array('timeout' => 3,'method'=>'GET')));
                $response = @file_get_contents($url,false,$context);

                if (false == $response) {
                    $ch = curl_init($url);            
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 3); // 設定cURL允許執行的最長秒數
                    $response = curl_exec ($ch);
                    curl_close ($ch);
                }
            }

            if ('Congratulations on passing' == $response) {
                $now_seo_inlet = 1;
            } else if (!empty($response) && !stristr($response, 'not found')) {
                /*解決部分空間file_get_contents獲取不到自身網址數據的問題*/
                $web_server = strtolower($_SERVER['SERVER_SOFTWARE']);
                if (stristr($web_server, 'apache') && file_exists('.htaccess')) {
                    $rewriteContent = @file_get_contents(ROOT_PATH.'.htaccess');
                    if (preg_match('#\#RewriteRule(\s+)\^\(\.\*\)\$(\s+)index.php\?s=/#i', $rewriteContent)) { // 有偽靜態規則，但被註釋
                        $now_seo_inlet = 0;
                    } else if (preg_match('#RewriteRule(\s+)\^\(\.\*\)\$(\s+)index.php\?s=/#i', $rewriteContent)) { // 有偽靜態規則，且啟用
                        $now_seo_inlet = 1;
                    }
                } else if (stristr($web_server, 'microsoft-iis')) {
                    $iisArr = explode('/', $web_server);
                    $iisversion = end($iisArr);
                    if (file_exists('web.config') && floatval(7) < floatval($iisversion)) {
                        $rewriteContent = @file_get_contents(ROOT_PATH.'web.config');
                        if (preg_match('#url(\s*)=(\s*)("|\')index.php/{r:#i', $rewriteContent)) {
                            $now_seo_inlet = 1;
                        }
                    }
                }
                /*--end*/
            }
        } catch (\Exception $e) {}
        /*--end*/

        $seo_inlet = tpCache('seo.seo_inlet');
        if ($seo_inlet != $now_seo_inlet) {
            /*多語言*/
            if (is_language()) {
                $langRow = \think\Db::name('language')->order('id asc')
                    ->cache(true, EYOUCMS_CACHE_TIME, 'language')
                    ->select();
                foreach ($langRow as $key => $val) {
                    tpCache('seo', ['seo_inlet'=>$now_seo_inlet], $val['mark']);
                }
            } else { // 單語言
                tpCache('seo', ['seo_inlet'=>$now_seo_inlet]);
            }
            /*--end*/
        }
    }

    /**
     * 修改數據庫配置檔案
     */
    private function update_databasefile()
    {
        /*不在以下相應的控制器和操作名里不往下執行，以便提高效能*/
        $ctlActArr = array(
            'Index@welcome',
            'Tools@index',
        );
        $ctlActStr = self::$controllerName.'@'.self::$actionName;
        if (!in_array($ctlActStr, $ctlActArr) || 'GET' != self::$method) {
            return true;
        }
        /*--end*/

        //讀取配置檔案，並替換真實配置數據
        $filename = APP_PATH . 'database.php';
        $databaseConf = include $filename;
        $sampleConf = include APP_PATH . 'database.php_read';
        if ($databaseConf['break_reconnect'] != $sampleConf['break_reconnect']) {
            $strConfig = @file_get_contents(APP_PATH . 'database.php_read');
            if (false != $strConfig) {
                $strConfig = str_replace('#DB_HOST#', $databaseConf['hostname'], $strConfig);
                $strConfig = str_replace('#DB_NAME#', $databaseConf['database'], $strConfig);
                $strConfig = str_replace('#DB_USER#', $databaseConf['username'], $strConfig);
                $strConfig = str_replace('#DB_PWD#', $databaseConf['password'], $strConfig);
                $strConfig = str_replace('#DB_PORT#', $databaseConf['hostport'], $strConfig);
                $strConfig = str_replace('#DB_PREFIX#', $databaseConf['prefix'], $strConfig);
                $strConfig = str_replace('#DB_CHARSET#', $databaseConf['charset'], $strConfig);
                @chmod($filename,0777); //數據庫配置檔案的地址
                is_writable($filename) && @file_put_contents($filename, $strConfig); //數據庫配置檔案的地址
            }
        }
    }

    /**
     * 根據IP判斷是否本地區域網訪問
     */
    /*private function is_local($ip){
        if(preg_match('/^(localhost|127\.|192\.)/', $ip) === 1){  
            return true;      
        }else{  
            return false;     
        }     
    }*/
}
