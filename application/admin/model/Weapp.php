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

namespace app\admin\model;

use think\Model;
// use app\admin\logic\WeappLogic;

/**
 * 外掛模型
 */
class Weapp extends Model
{
    public $weappLogic;
    
    //初始化
    protected function initialize()
    {
        // 需要呼叫`Model`的`initialize`方法
        parent::initialize();
        // $this->weappLogic = new WeappLogic();
    }

    /**
     * 獲取外掛列表
     */
    public function getList($where = array()){
        $result = M('weapp')->where($where)->getAllWithIndex('code');
        foreach ($result as $key => $val) {
            $config = include WEAPP_PATH.$val['code'].DS.'config.php';
            $val['config'] = json_encode($config);
            $result[$key] = $val;
        }
        return $result;
    }
}