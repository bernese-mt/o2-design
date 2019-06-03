<?php
/**
 * 易優CMS
 * ============================================================================
 * 版權所有 2016-2028 海南贊贊網絡科技有限公司，並保留所有權利。
 * 網站地址: http://www.eyoucms.com
 * ----------------------------------------------------------------------------
 * 如果商業用途務必到官方購買正版授權, 以免引起不必要的法律糾紛.
 * ============================================================================
 * Author: 小虎哥 <1105415366@qq.com>
 * Date: 2018-4-3
 */

$icon_arr = array(
    'article' => 'fa fa-file-text',
    'product'  => 'fa fa-cubes',
    'images'  => 'fa fa-file-picture-o',
    'download'  => 'fa fa-cloud-download',
    'single'  => 'fa fa-bookmark-o',
    'about'  => 'fa fa-minus',
    'job'  => 'fa fa-minus',
    'guestbook'  => 'fa fa-file-text-o',
    'feedback'  => 'fa fa-file-text-o',
);
$main_lang= get_main_lang();
$admin_lang = get_admin_lang();
$domain = request()->domain();
$default_words = array();
$default_addcontent = array();

// 獲取有欄目的模型列表
$channel_list = model('Channeltype')->getArctypeChannel('yes');
foreach ($channel_list as $key => $val) {
    $default_words[] = array(
        'name'  => $val['ntitle'],
        'action'  => 'index',
        'controller'  => $val['ctl_name'],
        'url'  => $val['typelink'],
        'icon'  => $icon_arr[$val['nid']],
    );
    if (!in_array($val['nid'], array('single','guestbook','feedback'))) {
        $default_addcontent[] = array(
            'name'  => $val['ntitle'],
            'action'  => 'add',
            'controller'  => $val['ctl_name'],
            'url'  => $val['typelink'],
            'icon'  => $icon_arr[$val['nid']],
        );
    }
}

/*PC端可視編輯URl*/
$uiset_pc_url = '';
if (file_exists(ROOT_PATH.'template/pc/uiset.txt')) {
    $uiset_pc_url = url('Uiset/pc', array(), true, $domain);
}
/*--end*/

/*手機端可視編輯URl*/
$uiset_mobile_url = '';
if (file_exists(ROOT_PATH.'template/mobile/uiset.txt')) {
    $uiset_mobile_url = url('Uiset/mobile', array(), true, $domain);
}
/*--end*/

/*清理數據URl*/
$uiset_data_url = '';
if (!empty($uiset_pc_url) || !empty($uiset_mobile_url)) {
    $uiset_data_url = url('Uiset/ui_index', array(), true, $domain);
}
/*--end*/

/*可視編輯URL*/
$uiset_index_arr = array();
if (!empty($uiset_pc_url) || !empty($uiset_mobile_url)) {
    $uiset_index_arr = array(
        'url' => url('Uiset/index', array(), true, $domain),
        'is_menu' => 1,
    );
}
/*--end*/

/*SEO優化URl*/
$seo_index_arr = array();
if ($main_lang == $admin_lang) {
    $seo_index_arr = array(
        'is_menu' => 1,
    );
}
/*--end*/

/*備份還原URl*/
$tools_index_arr = array();
if ($main_lang == $admin_lang) {
    $tools_index_arr = array(
        'is_menu' => 1,
    );
}
/*--end*/

/*頻道模型URl*/
$channeltype_index_arr = array();
if ($main_lang == $admin_lang) {
    $channeltype_index_arr = array(
        'is_menu' => 1,
    );
}
/*--end*/

/*回收站URl*/
$recyclebin_index_arr = array();
if ($main_lang == $admin_lang) {
    $recyclebin_index_arr = array(
        'is_menu' => 1,
    );
}
/*--end*/

/*外掛應用URl*/
$weapp_index_arr = array();
// $weappDirList = glob(ROOT_PATH.'weapp/*');
if (1 == tpCache('web.web_weapp_switch') && file_exists(ROOT_PATH.'weapp')) {
    $weapp_index_arr = array(
        'is_menu' => 1,
    );
}
/*--end*/

/*會員中心URl*/
$users_index_arr = array();
if (1 == tpCache('web.web_users_switch') && $main_lang == $admin_lang) {
    $users_index_arr = array(
        'is_menu' => 1,
        'is_modules' => 1,
    );
}
/*--end*/

/**
 * 權限模塊屬性說明
 * array
 *      id  主鍵ID
 *      parent_id   父ID
 *      name    模塊名稱
 *      controller  控制器
 *      action  操作名
 *      url     跳轉鏈接(控制器與操作名爲空時，才使用url)
 *      target  打開視窗方式
 *      icon    菜單圖標
 *      grade   層級
 *      is_menu 是否顯示菜單
 *      is_modules  是否顯示權限模塊分組
 *      child   子模塊
 */
return  array(
    '1000'=>array(
        'id'=>1000,
        'parent_id'=>0,
        'name'=>'',
        'controller'=>'',
        'action'=>'',
        'url'=>'',
        'target'=>'workspace',
        'grade'=>0,
        'is_menu'=>1,
        'is_modules'=>1,
        'child'=>array(
            '1001' => array(
                'id'=>1001,
                'parent_id'=>1000,
                'name' => '類別管理',
                'controller'=>'Arctype',
                'action'=>'index',
                'url'=>'', 
                'target'=>'workspace',
                'icon'=>'fa fa-sitemap',
                'grade'=>1,
                'is_menu'=>1,
                'is_modules'=>1,
                'child' => array(),
            ),
            '1002' => array(
                'id'=>1002,
                'parent_id'=>1000,
                'name' => '內容管理',
                'controller'=>'Archives',
                'action'=>'index',
                'url'=>'', 
                'target'=>'workspace',
                'icon'=>'fa fa-list',
                'grade'=>1,
                'is_menu'=>1,
                'is_modules'=>1,
                'child' => array(),
            ),
            '1003' => array(
                'id'=>1003,
                'parent_id'=>1000,
                'name' => '廣告管理',
                'controller'=>'AdPosition',
                'action'=>'index',
                'url'=>'', 
                'target'=>'workspace',
                'icon'=>'fa fa-image',
                'grade'=>1,
                'is_menu'=>1,
                'is_modules'=>1,
                'child' => array(),
            ),
        ),
    ),
        
    '2000'=>array(
        'id'=>2000,
        'parent_id'=>0,
        'name'=>'設置',
        'controller'=>'',
        'action'=>'',
        'url'=>'', 
        'target'=>'workspace',
        'grade'=>0,
        'is_menu'=>1,
        'is_modules'=>1,
        'child'=>array(
            '2001' => array(
                'id'=>2001,
                'parent_id'=>2000,
                'name' => '基本資訊',
                'controller'=>'System',
                'action'=>'web',
                'url'=>'', 
                'target'=>'workspace',
                'icon'=>'fa fa-cog',
                'grade'=>1,
                'is_menu'=>1,
                'is_modules'=>1,
                'child' => array(),
            ),
            '2002' => array(
                'id'=>2002,
                'parent_id'=>2000,
                'name' => '可視編輯',
                'controller'=>'Weapp',
                'action'=>'index',
                'url'=>isset($uiset_index_arr['url']) ? $uiset_index_arr['url'] : '',
                'target'=>'workspace',
                'icon'=>'fa fa-tachometer',
                'grade'=>1,
                'is_menu'=>isset($uiset_index_arr['is_menu']) ? $uiset_index_arr['is_menu'] : 0,
                'is_modules'=>1,
                'child'=>array(
                    '2002001' => array(
                        'id'=>2002001,
                        'parent_id'=>2002,
                        'name' => '電腦版',
                        'controller'=>'',
                        'action'=>'',
                        'url'=>$uiset_pc_url, 
                        'target'=>'_blank',
                        'icon'=>'fa fa-desktop',
                        'grade'=>2,
                        'is_menu'=>1,
                        'is_modules'=>0,
                        'child' => array(),
                    ),
                    '2002002' => array(
                        'id'=>2002002,
                        'parent_id'=>2002,
                        'name' => '手機版',
                        'controller'=>'',
                        'action'=>'',
                        'url'=>$uiset_mobile_url, 
                        'target'=>'_blank',
                        'icon'=>'fa fa-mobile',
                        'grade'=>2,
                        'is_menu'=>1,
                        'is_modules'=>0,
                        'child' => array(),
                    ),
                    '2002003' => array(
                        'id'=>2002003,
                        'parent_id'=>2002,
                        'name' => '數據清理',
                        'controller'=>'Uiset',
                        'action'=>'ui_index',
                        'url'=>'', 
                        'target'=>'workspace',
                        'icon'=>'fa fa-undo',
                        'grade'=>2,
                        'is_menu'=>1,
                        'is_modules'=>0,
                        'child' => array(),
                    ),
                ),
            ),
            '2003' => array(
                'id'=>2003,
                'parent_id'=>2000,
                'name' => '營銷設置',
                'controller'=>'Other',
                'action'=>'index',
                'url'=>'', 
                'target'=>'workspace',
                'icon'=>'fa fa-paper-plane',
                'grade'=>1,
                'is_menu'=>1,
                'is_modules'=>1,
                'child'=>array(
                    '2003001' => array(
                        'id'=>2003001,
                        'parent_id'=>2003,
                        'name' => 'SEO優化', 
                        'controller'=>'Seo',
                        'action'=>'index',
                        'url'=>'',
                        'target'=>'workspace',
                        'icon'=>'fa fa-newspaper-o',
                        'grade'=>2,
                        'is_menu'=>isset($seo_index_arr['is_menu']) ? $seo_index_arr['is_menu'] : 0,
                        'is_modules'=>0,
                        'child' => array(),
                    ),
                    '2003002' => array(
                        'id'=>2003002,
                        'parent_id'=>2003,
                        'name' => '友情鏈接', 
                        'controller'=>'Links',
                        'action'=>'index', 
                        'url'=>'', 
                        'target'=>'workspace',
                        'icon'=>'fa fa-link',
                        'grade'=>2,
                        'is_menu'=>1,
                        'is_modules'=>0,
                        'child' => array(),
                    ),
                ),
            ),
            '2004' => array(
                'id'=>2004,
                'parent_id'=>2000,
                'name' => '進階選項',
                'controller'=>'Senior',
                'action'=>'index',
                'url'=>'', 
                'target'=>'workspace',
                'icon'=>'fa fa-code',
                'grade'=>1,
                'is_menu'=>1,
                'is_modules'=>1,
                'child' => array(
                    '2004001' => array(
                        'id'=>2004001,
                        'parent_id'=>2004,
                        'name' => '帳號管理', 
                        'controller'=>'Admin',
                        'action'=>'index', 
                        'url'=>'', 
                        'target'=>'workspace',
                        'icon'=>'fa fa-user',
                        'grade'=>2,
                        'is_menu'=>1,
                        'is_modules'=>0,
                        'child' => array(),
                    ),
                    '2004006' => array(
                        'id'=>2004006,
                        'parent_id'=>2004,
                        'name' => '回收站',
                        'controller'=>'RecycleBin',
                        'action'=>'arctype_index',
                        'url'=>'',
                        'target'=>'workspace',
                        'icon'=>'fa fa-recycle',
                        'grade'=>2,
                        'is_menu'=>isset($recyclebin_index_arr['is_menu']) ? $recyclebin_index_arr['is_menu'] : 0,
                        'is_modules'=>0,
                        'child' => array(),
                    ),
                    '2004003' => array(
                        'id'=>2004003,
                        'parent_id'=>2004,
                        'name' => '版型管理', 
                        'controller'=>'Filemanager',
                        'action'=>'index', 
                        'url'=>'', 
                        'target'=>'workspace',
                        'icon'=>'fa fa-folder-open',
                        'grade'=>2,
                        'is_menu'=>1,
                        'is_modules'=>0,
                        'child' => array(),
                    ),
                    '2004002' => array(
                        'id'=>2004002,
                        'parent_id'=>2004,
                        'name' => '備份還原', 
                        'controller'=>'Tools',
                        'action'=>'index',
                        'url'=>'',
                        'target'=>'workspace',
                        'icon'=>'fa fa-database',
                        'grade'=>2,
                        'is_menu'=>isset($tools_index_arr['is_menu']) ? $tools_index_arr['is_menu'] : 0,
                        'is_modules'=>0,
                        'child' => array(),
                    ),
                    // '2004004' => array(
                    //     'id'=>2004004,
                    //     'parent_id'=>2004,
                    //     'name' => '欄位管理', 
                    //     'controller'=>isset($field_cindex_arr['controller']) ? $field_cindex_arr['controller'] : '',
                    //     'action'=>isset($field_cindex_arr['action']) ? $field_cindex_arr['action'] : '',
                    //     'url'=>isset($field_cindex_arr['url']) ? $field_cindex_arr['url'] : '',
                    //     'target'=>'workspace',
                    //     'icon'=>'fa fa-cogs',
                    //     'grade'=>2,
                    //     'is_menu'=>0,
                    //     'is_modules'=>0,
                    //     'child' => array(),
                    // ),
                    '2004007' => array(
                        'id'=>2004007,
                        'parent_id'=>2004,
                        'name' => '頻道模型',
                        'controller'=>'Channeltype',
                        'action'=>'index',
                        'url'=>'',
                        'target'=>'workspace',
                        'icon'=>'fa fa-cube',
                        'grade'=>2,
                        'is_menu'=>isset($channeltype_index_arr['is_menu']) ? $channeltype_index_arr['is_menu'] : 0,
                        'is_modules'=>0,
                        'child' => array(),
                    ),
                    '2004005' => array(
                        'id'=>2004005,
                        'parent_id'=>2004,
                        'name' => '清除緩存',
                        'controller'=>'System',
                        'action'=>'clear_cache', 
                        'url'=>'', 
                        'target'=>'workspace',
                        'icon'=>'fa fa-undo',
                        'grade'=>2,
                        'is_menu'=>1,
                        'is_modules'=>0,
                        'child' => array(),
                    ),
                ),
            ),
            '2005' => array(
                'id'=>2005,
                'parent_id'=>2000,
                'name' => '外掛應用',
                'controller'=>'Weapp',
                'action'=>'index',
                'url'=>'',
                'target'=>'workspace',
                'icon'=>'fa fa-futbol-o',
                'grade'=>1,
                'is_menu'=>isset($weapp_index_arr['is_menu']) ? $weapp_index_arr['is_menu'] : 0,
                'is_modules'=>0,
                'child'=>array(),
            ),
            '2006' => array(
                'id'=>2006,
                'parent_id'=>2000,
                'name' => '會員中心',
                'controller'=>'Member',
                'action'=>'users_index',
                'url'=>'',
                'target'=>'workspace',
                'icon'=>'fa fa-user',
                'grade'=>1,
                'is_menu'=>isset($users_index_arr['is_menu']) ? $users_index_arr['is_menu'] : 0,
                'is_modules'=>isset($users_index_arr['is_modules']) ? $users_index_arr['is_modules'] : 0,
                'child' => array(),
            ),
            '2007' => array(
                'id'=>2007,
                'parent_id'=>2000,
                'name' => '功能開關',
                'controller'=>'Index',
                'action'=>'switch_map',
                'url'=>'',
                'target'=>'workspace',
                'icon'=>'fa fa-toggle-on',
                'grade'=>1,
                'is_menu'=>0,
                'is_modules'=>1,
                'child' => array(),
            ),
        ),
    ),
);