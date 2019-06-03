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

namespace app\home\controller;
use think\Controller;
use app\common\controller\Common;
use think\Db;
use think\Request;
use app\home\logic\FieldLogic;

class Base extends Common {

    public $fieldLogic;

    /**
     * 初始化操作
     */
    public function _initialize() {
        parent::_initialize();

        $this->fieldLogic = new FieldLogic();
        
        // 設定URL模式
        set_home_url_mode();
    }

    /**
     * 301重定向到新的偽靜態格式（針對被搜索引擎收錄的舊偽靜態URL）
     * @param intval $id 欄目ID/文件ID
     * @param string $dirname 目錄名稱
     * @param string $type 欄目頁/文件頁
     * @return void
     */
    public function jumpRewriteFormat($id, $dirname = null, $type = 'lists')
    {
        $seo_pseudo = config('ey_config.seo_pseudo');
        $seo_rewrite_format = config('ey_config.seo_rewrite_format');
        if (3 == $seo_pseudo && 1 == $seo_rewrite_format) {
            if ('lists' == $type) {
                $url = typeurl('home/Lists/index', array('dirname'=>$dirname));
            } else {
                $url = arcurl('home/View/index', array('dirname'=>$dirname, 'aid'=>$id));
            }
            //重定向到指定的URL地址 並且使用301
            $this->redirect($url, 301);
        }
    }
}