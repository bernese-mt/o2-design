<?php

namespace app\common\behavior;

/**
 * 系統行為擴充套件：
 */
class AppInitBehavior {
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
        self::$method = request()->method();
        // file_put_contents ( DATA_PATH."log.txt", date ( "Y-m-d H:i:s" ) . "  " . var_export('admin_CoreProgramBehavior',true) . "\r\n", FILE_APPEND );
        $this->_initialize();
    }

    private function _initialize() {
        $this->saveSqlmode(); // 儲存mysql的sql-mode模式參數
    }

    /**
     * 儲存mysql的sql-mode模式參數
     */
    private function saveSqlmode(){
        /*在後臺模組才執行，以便提高效能*/
        if (!stristr(request()->baseFile(), 'index.php')) {
            if ('GET' == self::$method) {
                $key = 'isset_saveSqlmode';
                $sessvalue = session($key);
                if(!empty($sessvalue))
                    return true;
                session($key, 1);

                $sql_mode = db()->query("SELECT @@global.sql_mode AS sql_mode");
                $system_sql_mode = isset($sql_mode[0]['sql_mode']) ? $sql_mode[0]['sql_mode'] : '';
                /*多語言*/
                if (is_language()) {
                    $langRow = \think\Db::name('language')->cache(true, EYOUCMS_CACHE_TIME, 'language')
                        ->order('id asc')->select();
                    foreach ($langRow as $key => $val) {
                        tpCache('system', ['system_sql_mode'=>$system_sql_mode], $val['mark']);
                    }
                } else { // 單語言
                    tpCache('system', ['system_sql_mode'=>$system_sql_mode]);
                }
                /*--end*/
            }
        }
        /*--end*/
    }
}
