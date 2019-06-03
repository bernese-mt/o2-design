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
// 整站cache快取key鍵值存放處，統一管理
// 注意：鍵名要唯一，不然會出現快取錯亂。
// 參考格式如下：
// 格式：模組_控制器_操作名[_序號]
// 1、中括號的序號可選，在同一個操作名內用於區分開。
// 2、鍵名不區分大寫小寫，要注意大小寫，系統自己轉為小寫處理在md5()加密處理。

return array(
    /* -------------------------全域性使用------------------------- */
    'common_getEveryTopDirnameList_model'     => array(
        'tag'=>'arctype', 'options'=>array('expire'=>0, 'prefix'=>'')
    ),

    /* -------------------------前臺使用------------------------- */
    // 'home_base_global_assign'     => array(
    //     'tag'=>'home_base', 'options'=>array('expire'=>43200, 'prefix'=>'')
    // ),

    /* -------------------------後臺使用------------------------- */
    'admin_all_menu'     => array(
        'tag'=>'admin_common', 'options'=>array('expire'=>43200, 'prefix'=>'')
    ),
    'admin_auth_role_list_logic'     => array(
        'tag'=>'admin_logic', 'options'=>array('expire'=>-1, 'prefix'=>'')
    ),
    'admin_auth_modular_list_logic'     => array(
        'tag'=>'admin_logic', 'options'=>array('expire'=>-1, 'prefix'=>'')
    ),
    'admin_channeltype_list_logic'     => array(
        'tag'=>'admin_logic', 'options'=>array('expire'=>86400, 'prefix'=>'')
    ),
);