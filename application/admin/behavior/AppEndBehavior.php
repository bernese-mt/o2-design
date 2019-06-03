<?php

namespace app\admin\behavior;

/**
 * 系統行為擴充套件：
 */
class AppEndBehavior {
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
        $this->resetAuthor(); // 臨時處理授權問題
        $this->clearHtmlCache(); // 變動數據之後，清除頁面快取和數據
        $this->seodescriptionHandle(); // 發佈或編輯時，擷取SEO描述長度
        $this->sitemap(); // 自動產生sitemap
    }

    /**
     * 自動產生sitemap
     * @access public
     */
    private function sitemap()
    {
        /*只有相應的控制器和操作名才執行，以便提高效能*/
        $systemCtl= ['Arctype'];
        $ctlArr = \think\Db::name('channeltype')
            ->where('nid','NOTIN', ['guestbook','single'])
            ->column('ctl_name');
        $ctlArr = array_merge($systemCtl, $ctlArr);
        $actArr = ['add','edit'];
        if ('POST' == self::$method && in_array(self::$controllerName, $ctlArr) && in_array(self::$actionName, $actArr)) {
            sitemap_auto();
        }
        /*--end*/
    }

    /**
     * 臨時處理授權問題
     */
    private function resetAuthor()
    {
        /*在以下相應的控制器和操作名里執行，以便提高效能*/
        $ctlActArr = array(
            'Index@index',
        );
        $ctlActStr = self::$controllerName.'@'.self::$actionName;
        if (in_array($ctlActStr, $ctlActArr) && 'GET' == self::$method) {
            if(!empty($_SESSION['isset_resetAuthor']))
                return true;
            $_SESSION['isset_resetAuthor'] = 1;

            session('isset_author', null);
        }
        /*--end*/
    }

    /**
     * 數據變動之後，清理頁面和數據快取
     */
    private function clearHtmlCache()
    {
        /*在以下相應的控制器和操作名里執行，以便提高效能*/
        $actArr = ['add','edit','del','recovery','changeTableVal'];
        if ('POST' == self::$method) {
            foreach ($actArr as $key => $val) {
                if (preg_match('/^((.*)_)?('.$val.')$/i', self::$actionName)) {
                    foreach ([HTML_ROOT,CACHE_PATH] as $k2 => $v2) {
                        delFile($v2);
                    }
                    break;
                }
            }
        }
    }

    /**
     * 發佈或編輯時，擷取SEO描述長度
     * @access private
     */
    private function seodescriptionHandle()
    {
        /*只有相應的控制器和操作名才執行，以便提高效能*/
        if ('POST' == self::$method) {
            $ctlArr = \think\Db::name('channeltype')->field('id,ctl_name')
                ->where('ifsystem',0)
                ->getAllWithIndex('ctl_name');
            $actArr = ['add','edit'];
            if (!empty($ctlArr[self::$controllerName]) && in_array(self::$actionName, $actArr)) {
                /*擷取SEO描述長度*/
                $contentField = \think\Db::name('channelfield')->where([
                        'channel_id'    => $ctlArr[self::$controllerName]['id'],
                        'dtype'         => 'htmltext',
                    ])->getField('name');
                if (!empty($_POST['addonFieldExt'][$contentField])) {
                    $content = $_POST['addonFieldExt'][$contentField];
                    $seo_description = @msubstr(checkStrHtml($content), 0, \think\Config::get('global.arc_seo_description_length'), false);
                    $saveData = [
                        'seo_description'   => $seo_description,
                        'update_time'       => getTime(),
                    ];
                    \think\Db::name('archives')->where('aid', $_POST['aid'])->update($saveData);
                }
                /*--end*/
            }
            /*--end*/
        }
    }
}
