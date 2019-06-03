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
use think\Db;
use app\admin\logic\AjaxLogic;

/**
 * 所有ajax請求或者不經過許可權驗證的方法全放在這裡
 */
class Ajax extends Base {
    
    private $ajaxLogic;

    public function _initialize() {
        parent::_initialize();
        $this->ajaxLogic = new AjaxLogic;
    }

    /**
     * 進入歡迎頁面需要非同步處理的業務
     */
    public function welcome_handle()
    {
        $this->ajaxLogic->welcome_handle();
    }

    /**
     * 隱藏後臺歡迎頁的系統提示
     */
    public function explanation_welcome()
    {
        /*多語言*/
        if (is_language()) {
            $langRow = \think\Db::name('language')->field('mark')->order('id asc')->select();
            foreach ($langRow as $key => $val) {
                tpCache('system', ['system_explanation_welcome'=>1], $val['mark']);
            }
        } else { // 單語言
            tpCache('system', ['system_explanation_welcome'=>1]);
        }
        /*--end*/
    }

    /**
     * 版本檢測更新彈窗
     */
    public function check_upgrade_version()
    {
        $upgradeLogic = new \app\admin\logic\UpgradeLogic;
        $upgradeMsg = $upgradeLogic->checkVersion(); // 升級包訊息
        $this->success('檢測成功', null, $upgradeMsg);  
    }
}