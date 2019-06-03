<?php

namespace app\home\behavior;

/**
 * 系統行為擴充套件：
 */
class ViewFilterBehavior {
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
        $this->_initialize($params);
    }

    private function _initialize(&$params) {
        $this->thirdcode($params); // 自動加上第三方統計程式碼
    }

    /**
     * 給模板加上第三方統計程式碼
     * @access public
     */
    private function thirdcode(&$params)
    {
        /*PC端與手機端的變數名自適應，可彼此通用*/
        $name = 'web_thirdcode_' . (isMobile() ? 'wap' : 'pc');
        /*--end*/
        $web_thirdcode = tpCache('web.'.$name);
        if (!empty($web_thirdcode)) {
            $params = str_ireplace('</body>', htmlspecialchars_decode($web_thirdcode)."\n</body>", $params);
        }
    }
}
