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

$cacheKey = "extra_global_channeltype";
$channeltype_row = \think\Cache::get($cacheKey);
if (empty($channeltype_row)) {
    $channeltype_row = \think\Db::name('channeltype')->field('id,nid')
        ->where([
            'status' => 1,
        ])
        ->order('id asc')
        ->select();
    \think\Cache::set($cacheKey, $channeltype_row, EYOUCMS_CACHE_TIME, "channeltype");
}

$channeltype_list = [];
$allow_release_channel = [];
foreach ($channeltype_row as $key => $val) {
    $channeltype_list[$val['nid']] = $val['id'];
    if (!in_array($val['nid'], ['guestbook','single'])) {
        array_push($allow_release_channel, $val['id']);
    }
}

return array(
    // CMS根目錄資料夾
    'wwwroot_dir' => ['application','core','data','extend','html','public','template','uploads','vendor','weapp'],
    // 禁用的目錄名稱
    'disable_dirname' => ['application','core','data','extend','html','install','public','plugins','uploads','template','vendor','weapp','tags','search','user','users','member','reg','centre','login'],
    // 發送郵箱預設有效時間，會員中心，郵箱驗證時用到
    'email_default_time_out' => 3600,
    // 郵箱發送倒計時 2分鐘
    'email_send_time' => 120,
    // 充值訂單預設有效時間，會員中心用到，2小時
    'get_order_validity' => 7200,
    // 支付訂單預設有效時間，商城中心用到，2小時
    'get_shop_order_validity' => 7200,
    // 文件SEO描述擷取長度，一個字元表示一個漢字或字母
    'arc_seo_description_length' => 125,
    // 欄目最多級別
    'arctype_max_level' => 3,
    // 模型標識
    'channeltype_list' => $channeltype_list,
    // 發佈文件的模型ID
    'allow_release_channel' => $allow_release_channel,
    // 廣告型別
    'ad_media_type' => array(
        1   => '圖片',
        // 2   => 'flash',
        // 3   => '文字',
    ),
    'attr_input_type_arr' => array(
        0   => '單行文字',
        1   => '下拉框',
        2   => '多行文字',
        3   => 'HTML文字',
    ),
    // 欄目自定義欄位的channel_id值
    'arctype_channel_id' => -99,
    // 欄目表原始欄位
    'arctype_table_fields' => array('id','channeltype','current_channel','parent_id','typename','dirname','dirpath','englist_name','grade','typelink','litpic','templist','tempview','seo_title','seo_keywords','seo_description','sort_order','is_hidden','is_part','admin_id','is_del','del_method','status','lang','add_time','update_time'),
    // 網路圖片副檔名
    'image_ext' => 'jpg,jpeg,gif,bmp,ico,png',
    // 後臺語言Cookie變數
    'admin_lang' => 'admin_lang',
    // 前臺語言Cookie變數
    'home_lang' => 'home_lang',
    // URL全域性參數（比如：視覺化uiset、多模板v、多語言lang）
    'parse_url_param'   => ['uiset','v','lang'],
    // 使用者金額明細型別
    'pay_cause_type_arr' => array(
        0   => '消費',
        1   => '賬戶充值',
        // 2   => '後續新增',
    ),
    // 充值狀態
    'pay_status_arr' => array(
        // 0   => '失敗',
        1   => '未付款',
        // 2   => '已付款',
        3   => '已充值',
        4   => '訂單取消',
        // 5   => '後續新增',
    ),
    // 支付方式
    'pay_method_arr' => array(
        'wechat'     => '微信',
        'alipay'     => '支付寶',
        'artificial' => '手工充值',
        'balance'    => '餘額',
        'admin_pay'  => '管理員代付',
        'delivery_pay' => '貨到付款',
    ),
    // 縮圖預設寬高度
    'thumb' => [
        'open'  => 0,
        'mode'  => 2,
        'color' => '#FFFFFF',
        'width' => 300,
        'height' => 300,
    ],
    // 訂單狀態
    'order_status_arr' => array(
        -1  => '已關閉',
        0   => '待付款',
        1   => '待發貨',
        2   => '待收貨',
        3   => '訂單完成',
        4   => '訂單過期',
        // 5   => '後續新增',
    ),
    // 訂單狀態，後臺使用
    'admin_order_status_arr' => array(
        -1  => '訂單關閉',
        0   => '未付款',
        1   => '待發貨',
        2   => '已發貨',
        3   => '已完成',
        4   => '訂單過期',
    ),
    // 清理檔案時，需要查詢的數據表和欄位
    'get_tablearray' => array(
        0 => array(
            'table' => 'ad',
            'field' => 'litpic',
        ),
        1 => array(
            'table' => 'archives',
            'field' => 'litpic',
        ),
        2 => array(
            'table' => 'arctype',
            'field' => 'litpic',
        ),
        3 => array(
            'table' => 'images_upload',
            'field' => 'image_url',
        ),
        4 => array(
            'table' => 'links',
            'field' => 'logo',
        ),
        5 => array(
            'table' => 'product_img',
            'field' => 'image_url',
        ),
        6 => array(
            'table' => 'ad',
            'field' => 'intro',
        ),
        7 => array(
            'table' => 'article_content',
            'field' => 'content',
        ),
        8 => array(
            'table' => 'download_content',
            'field' => 'content',
        ),
        9 => array(
            'table' => 'images_content',
            'field' => 'content',
        ),
        10 => array(
            'table' => 'product_content',
            'field' => 'content',
        ),
        11 => array(
            'table' => 'single_content',
            'field' => 'content',
        ),
        12 => array(
            'table' => 'config',
            'field' => 'value',
        ),
        13 => array(
            'table' => 'ui_config',
            'field' => 'value',
        ),
        14 => array(
            'table' => 'download_file',
            'field' => 'file_url',
        ),
        15 => array(
            'table' => 'users',
            'field' => 'head_pic',
        ),
        16 => array(
            'table' => 'shop_order_details',
            'field' => 'litpic',
        ),
        // 後續可持續新增數據表和欄位，格式參照以上
    ),
);
