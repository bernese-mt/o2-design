<?php

namespace app\admin\behavior;

/**
 * 系統行為擴充套件：新增/更新/刪除之後的後置操作
 */
load_trait('controller/Jump');
class ActionBeginBehavior {
    use \traits\controller\Jump;
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
        // file_put_contents ( DATA_PATH."log.txt", date ( "Y-m-d H:i:s" ) . "  " . var_export('admin_AfterSaveBehavior',true) . "\r\n", FILE_APPEND );
        $this->_initialize();
    }

    private function _initialize() {
        if ('POST' == self::$method) {
            $this->checkRepeatTitle();
            $this->clearWeapp();
        }
    }

    /**
     * 外掛每次post提交都清除外掛相關快取
     * @access private
     */
    private function clearWeapp()
    {
        /*只有相應的控制器和操作名才執行，以便提高效能*/
        $ctlActArr = array(
            'Weapp@*',
        );
        $ctlActStr = self::$controllerName.'@*';
        if (in_array($ctlActStr, $ctlActArr)) {
            \think\Cache::clear('hooks');
        }
        /*--end*/
    }

    /**
     * 發佈或編輯時，檢測文件標題的重複性
     * @access private
     */
    private function checkRepeatTitle()
    {
        /*只有相應的控制器和操作名才執行，以便提高效能*/
        $ctlArr = \think\Db::name('channeltype')->field('id,ctl_name,is_repeat_title')
            ->where('nid','NOTIN', ['guestbook','single'])
            ->getAllWithIndex('ctl_name');
        $actArr = ['add','edit'];
        if (!empty($ctlArr[self::$controllerName]) && in_array(self::$actionName, $actArr)) {
            /*模型否開啟文件重複標題的檢測*/
            if (empty($ctlArr[self::$controllerName]['is_repeat_title'])) {
                $map = array(
                    'title' => $_POST['title'],
                );
                if ('edit' == self::$actionName) {
                    $map['aid'] = ['NEQ', $_POST['aid']];
                }
                $count = \think\Db::name('archives')->where($map)->count('aid');
                if(!empty($count)){
                    $this->error('該標題已存在，請更改');
                }
            }
            /*--end*/
        }
        /*--end*/
    }
}
