<?php

namespace app\common\behavior;

use think\Hook;
// defined('THINK_PATH') or exit();
/**
 * 初始化鉤子資訊
 */
class InitHookBehavior {

    // 行為擴充套件的執行入口必須是run
    public function run(&$params){
/*        if (!defined('BIND_MODULE')) {
            throw new \Exception("非法訪問，系統尚未繫結外掛模組");
        }*/
        
        // file_put_contents ( DATA_PATH."log.txt", date ( "Y-m-d H:i:s" ) . "  " . var_export('core_InitHookBehavior',true) . "\r\n", FILE_APPEND );
        
        $data = cache('hooks');
        $hooks = M('hooks')->field('name,module')->where(array('status'=>1))->cache(true, EYOUCMS_CACHE_TIME, 'hooks')->select();
        if(empty($data) && !empty($hooks)){
            $exist = \think\Db::query('SHOW TABLES LIKE "'.config('database.prefix').'weapp"');
            if (!empty($exist)) {
                $weappRow = M('weapp')->field('code,status')->where(array('status'=>1))->getAllWithIndex('code');
                if (!empty($hooks)) {
                    foreach ($hooks as $key => $val) {
                        $module = $val['module'];
                        if (isset($weappRow[$module]) && !empty($module)) {
                            Hook::add($val['name'], get_weapp_class($module));
                        }
                    }
                    cache('hooks', Hook::get());
                }
            }
        }else{
            if (!empty($data)) {
                Hook::import($data, false);
            }
        }
    }
}
