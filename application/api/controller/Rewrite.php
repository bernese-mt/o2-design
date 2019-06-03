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

namespace app\api\controller;

class Rewrite extends Base
{
    /*
     * 初始化操作
     */
    
    public function _initialize() {
        parent::_initialize();
    }

    /**
     * 檢測伺服器是否支援URL重寫隱藏應用的入口檔案index.php
     */
    public function testing()
    {
        ob_clean();
        exit('Congratulations on passing');
    }

    /**
     * 設定隱藏index.php
     */
    public function setInlet()
    {
        $seo_inlet = input('param.seo_inlet/d', 1);
        /*多語言*/
        if (is_language()) {
            $langRow = \think\Db::name('language')->order('id asc')->select();
            foreach ($langRow as $key => $val) {
                tpCache('seo', ['seo_inlet'=>$seo_inlet], $val['mark']);
            }
        } else { // 單語言
            tpCache('seo', ['seo_inlet'=>$seo_inlet]);
        }
        /*--end*/
        ob_clean();
        exit('Congratulations on passing');
    }
}