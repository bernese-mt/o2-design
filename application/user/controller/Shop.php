<?php
/**
 * 易優CMS
 * ============================================================================
 * 版權所有 2016-2028 海南贊贊網路科技有限公司，並保留所有權利。
 * 網站地址: http://www.eyoucms.com
 * ----------------------------------------------------------------------------
 * 如果商業用途務必到官方購買正版授權, 以免引起不必要的法律糾紛.
 * ============================================================================
 * Author: 陳風任 <491085389@qq.com>
 * Date: 2019-3-20
 */

namespace app\user\controller;

use think\Db;
use think\Config;
use think\Page;

class Shop extends Base
{
    // 初始化
    public function _initialize() {
        parent::_initialize();
        $this->users_db              = Db::name('users');               // 會員數據表
        $this->users_money_db        = Db::name('users_money');         // 會員金額明細表

        $this->shop_cart_db          = Db::name('shop_cart');           // 購物車表
        $this->shop_order_db         = Db::name('shop_order');          // 訂單主表
        $this->shop_order_details_db = Db::name('shop_order_details');  // 訂單明細表
        $this->shop_address_db       = Db::name('shop_address');        // 收貨地址表

        $this->archives_db           = Db::name('archives');            // 產品表
        $this->product_attr_db       = Db::name('product_attr');        // 產品屬性表
        $this->product_attribute_db  = Db::name('product_attribute');   // 產品屬性標題表

        $this->region_db             = Db::name('region');              // 三級聯動地址總表

        $this->shipping_template_db  = Db::name('shop_shipping_template'); // 運費模板表

        $this->shop_model = model('Shop');  // 商城模型

        // 訂單中心是否開啟
        $redirect_url = '';
        $shop_open = getUsersConfigData('shop.shop_open');
        $web_users_switch = tpCache('web.web_users_switch');
        if (empty($shop_open)) { 
            // 訂單功能關閉，立馬跳到會員中心
            $redirect_url = url('user/Users/index');
            $msg = '訂單中心尚未開啟！';
        } else if (empty($web_users_switch)) { 
            // 前臺會員中心已關閉，跳到首頁
            $redirect_url = ROOT_DIR.'/';
            $msg = '會員中心尚未開啟！';
        }
        if (!empty($redirect_url)) {
            Db::name('users_menu')->where([
                    'mca'   => 'user/Shop/shop_centre',
                    'lang'  => $this->home_lang,
                ])->update([
                    'status'    => 0,
                    'update_time' => getTime(),
                ]);
            $this->error($msg, $redirect_url);
            exit;
        }
        // --end
    }

    // 購物車列表
    public function shop_cart_list()
    {
        // 數據由標籤調取產生
        return $this->fetch('users/shop_cart_list');
    }

    // 訂單管理列表，訂單中心
    public function shop_centre()
    {
        $result = [];
        // 應用搜索條件
        $keywords      = input('param.keywords/s');
        // 訂單狀態搜索
        $select_status = input('param.select_status');
        // 查詢訂單是否為空
        $result['data'] = $this->shop_model->GetOrderIsEmpty($this->users_id,$keywords,$select_status);
        // 是否移動端，1表示移動端，0表示PC端
        $result['IsMobile'] = isMobile() ? 1 : 0;
        // 菜單名稱
        $result['title'] = Db::name('users_menu')->where([
                'mca'   => 'user/Shop/shop_centre',
                'lang'  => $this->home_lang,
            ])->getField('title');
        // 載入數據
        $eyou = [
            'field' => $result,
        ];
        $this->assign('eyou',$eyou);
        return $this->fetch('users/shop_centre');
    }

    // 訂單數據詳情
    public function shop_order_details()
    {
        if (IS_GET) {
            // 數據由標籤調取產生
            return $this->fetch('users/shop_order_details');
        }else{
            $this->error('非法訪問！');
        }
    }

    // 訂單提交
    public function shop_under_order($error='true')
    {
        if (empty($error)) {
            $this->error('沒有提交數據！');
        }
        // 數據由標籤調取產生
        return $this->fetch('users/shop_under_order');
    }

    // 收貨地址管理列表
    public function shop_address_list()
    {
        // 數據由標籤調取產生
        return $this->fetch('users/shop_address_list');
    }

    // 取消訂單
    public function shop_order_cancel()
    {
        if (IS_AJAX_POST) {
            $order_id = input('param.order_id');
            if (!empty($order_id)) {
                // 更新條件
                $Where = [
                    'order_id' => $order_id,
                    'users_id' => $this->users_id,
                    'lang'     => $this->home_lang,
                ];
                // 更新數據
                $Data  = [
                    'order_status' => '-1',
                    'update_time'  => getTime(),
                ];
                // 更新訂單主表
                $return = $this->shop_order_db->where($Where)->update($Data);
                if (!empty($return)) {
                    // 新增訂單操作記錄
                    AddOrderAction($order_id,$this->users_id,'0','0','0','0','訂單取消！','會員關閉訂單！');
                    $this->success('訂單已取消！');
                }else{
                    $this->error('操作失敗！');
                }
            }
        }
    }

    // 立即購買
    public function shop_buy_now()
    {
        if (IS_AJAX_POST) {
            $param = input('param.');
            // 數量不可為空
            if (empty($param['num'])) {
                $this->error('請選擇數量！');
            }
            // 查詢條件
            $archives_where = [
                'arcrank' => array('egt','0'), //帶審覈稿件不查詢(同等偽刪除)
                'aid'     => $param['aid'],
                'lang'    => $this->home_lang,
            ];
            $count = $this->archives_db->where($archives_where)->count();
            // 跳轉下單頁
            if (!empty($count)) {
                // 對ID和訂單號加密，拼裝url路徑
                $querydata = [
                    'aid'         => $param['aid'],
                    'product_num' => $param['num'],
                ];
                $querystr   = base64_encode(serialize($querydata));
                $url = urldecode(url('user/Shop/shop_under_order', ['querystr'=>$querystr]));
                $this->success('立即購買！',$url);
            }else{
                $this->error('該商品不存在或已下架！');
            }
        }else {
            $this->error('非法訪問！');
        }
    }

    // 新增購物車數據
    public function shop_add_cart()
    {
        if (IS_AJAX_POST) {
            $param = input('param.');
            // 數量不可為空
            if (empty($param['num'])) {
                $this->error('請選擇數量！');
            }
            // 查詢條件
            $archives_where = [
                'arcrank' => array('egt','0'), //帶審覈稿件不查詢(同等偽刪除)
                'aid'     => $param['aid'],
                'lang'    => $this->home_lang,
            ];
            $count = $this->archives_db->where($archives_where)->count();
            // 加入購物車處理
            if (!empty($count)) {
                // 查詢條件
                $cart_where = [
                    'users_id'   => $this->users_id,
                    'product_id' => $param['aid'],
                    'lang'       => $this->home_lang,
                ];
                $cart_data = $this->shop_cart_db->where($cart_where)->field('product_num')->find();
                if (!empty($cart_data)) {
                    // 購物車內已有相同產品，進行數量更新。
                    $data['product_num'] = $param['num'] + $cart_data['product_num']; //與購物車數量進行疊加
                    $data['update_time'] = getTime();
                    $cart_id = $this->shop_cart_db->where($cart_where)->update($data);
                }else{
                    // 購物車內還未有相同產品，進行新增。
                    $data['users_id']    = $this->users_id;
                    $data['product_id']  = $param['aid'];
                    $data['product_num'] = $param['num'];
                    $data['add_time']    = getTime();
                    $cart_id = $this->shop_cart_db->add($data);
                }
                if (!empty($cart_id)) {
                    $this->success('加入購物車成功！');
                }else{
                    $this->error('加入購物車失敗！');
                }
            }else{
                $this->error('該商品不存在或已下架！');
            }

        }else {
            $this->error('非法訪問！');
        }
    }

    // 統一修改購物車數量
    // symbol 加或減數量或直接修改數量
    public function cart_unified_algorithm(){
        if (IS_AJAX_POST) {
            $aid    = input('post.aid');
            $symbol = input('post.symbol');
            $num    = input('post.num');
            // 查詢條件
            $archives_where = [
                'arcrank' => array('egt','0'),
                'aid'     => $aid,
                'lang'    => $this->home_lang,
            ];
            $archives_count = $this->archives_db->where($archives_where)->count();
            if (!empty($archives_count)) {
                // 查詢條件
                $cart_where = [
                    'users_id'    => $this->users_id,
                    'product_id'  => $aid,
                    'lang'        => $this->home_lang,
                ];
                // 判斷追加查詢條件，當減數量時，商品數量最少為1
                if ('-' == $symbol) {
                    $cart_where['product_num'] = array('gt','1');
                }
                $cart_data = $this->shop_cart_db->where($cart_where)->field('product_num')->find();
                // 處理購物車產品數量
                if (!empty($cart_data)) {
                    // 更新陣列
                    if ('+' == $symbol) {
                        $data['product_num'] = $cart_data['product_num'] + 1;
                    }else if ('-' == $symbol) {
                        $data['product_num'] = $cart_data['product_num'] - 1;
                    }else if ('change' == $symbol) {
                        $data['product_num'] = $num;
                    }
                    $data['update_time'] = getTime();
                    // 更新數據
                    $cart_id = $this->shop_cart_db->where($cart_where)->update($data);

                    $CaerWhere = [
                        'a.users_id' => $this->users_id,
                        'a.lang'     => $this->home_lang,
                        'a.selected' => 1,
                    ];

                    // 計算金額數量
                    $CartData = $this->shop_cart_db
                        ->field('sum(a.product_num) as num, sum(a.product_num * b.users_price) as price')
                        ->alias('a') 
                        ->join('__ARCHIVES__ b', 'a.product_id = b.aid', 'LEFT')
                        ->where($CaerWhere)
                        ->find();
                    if (empty($CartData['num']) && empty($CartData['price'])) {
                        $CartData['num']   = '0';
                        $CartData['price'] = '0';
                    }
                    if (!empty($cart_id)) {
                        $this->success('操作成功！','',['NumberVal'=>$CartData['num'],'AmountVal'=>$CartData['price']]);
                    }
                }else{
                    $this->error('商品數量最少為1','',['error'=>'0']);
                }
            }else{
                $this->error('該商品不存在或已下架！');
            }
        }
    }

    // 刪除購物車內的產品
    public function cart_del()
    {
        if (IS_AJAX_POST) {
            $cart_id = input('post.cart_id');
            if (!empty($cart_id)) {
                // 刪除條件
                $cart_where = [
                    'cart_id'  => $cart_id,
                    'users_id' => $this->users_id,
                    'lang'     => $this->home_lang,
                ];
                // 刪除數據
                $return = $this->shop_cart_db->where($cart_where)->delete();
            }
            if (!empty($return)) {
                $this->success('操作成功！');
            }else{
                $this->error('刪除失敗！');
            }
        }
    }

    // 選中產品
    public function cart_checked()
    {
        if (IS_AJAX_POST) {
            $cart_id  = input('post.cart_id');
            $selected = input('post.selected');
            // 更新陣列
            if (!empty($selected)) {
                $selected = '0';
            }else{
                $selected = '1';
            }
            $data['selected']    = $selected;
            $data['update_time'] = getTime();
            // 更新條件
            if ('*' == $cart_id) {
                $cart_where = [
                    'users_id' => $this->users_id,
                    'lang'     => $this->home_lang,
                ];
            }else{
                $cart_where = [
                    'cart_id'  => $cart_id,
                    'users_id' => $this->users_id,
                    'lang'     => $this->home_lang,
                ];
            }
            // 更新數據
            $return = $this->shop_cart_db->where($cart_where)->update($data);
            if (!empty($return)) {
                $this->success('操作成功！');
            }else{
                $this->error('操作失敗！');
            }
        }
    }

    // 訂單提交處理邏輯，新增商品資訊及計算價格等
    public function shop_payment_page()
    {
        if (IS_POST) {
            // 提交的訂單資訊判斷
            $post = input('post.');

            if (empty($post)) { 
                $this->error('訂單產生失敗，商品數據有誤！'); 
            }
            if (!empty($post['aid'])) {
                $aid  = unserialize(base64_decode($post['aid']));
            }
            if (!empty($post['num'])) {
                $num  = unserialize(base64_decode($post['num']));
            }
            if (!empty($post['type'])) {
                $type = unserialize(base64_decode($post['type']));
            }

            // 產品ID是否存在
            if (!empty($aid)) {
                // 商品數量判斷
                if ($num <= '0') {
                    $this->error('訂單產生失敗，商品數量有誤！');
                }
                // 訂單來源判斷
                if ($type != '1') {
                    $this->error('訂單產生失敗，提交來源有誤！');
                }
                // 立即購買查詢條件
                $ArchivesWhere = [
                    'aid'  => $aid,
                    'lang' => $this->home_lang,
                ];
                $list = $this->archives_db->field('aid,title,litpic,users_price,prom_type')->where($ArchivesWhere)->select();
                $list[0]['product_num']      = $num;
                $list[0]['under_order_type'] = $type;
            }else{
                // 購物車查詢條件
                $cart_where = [
                    'a.users_id' => $this->users_id,
                    'a.lang'     => $this->home_lang,
                    'a.selected' => 1,
                ];
                $list = $this->shop_cart_db->field('a.*,b.aid,b.title,b.litpic,b.users_price,b.prom_type')
                    ->alias('a') 
                    ->join('__ARCHIVES__ b', 'a.product_id = b.aid', 'LEFT')
                    ->where($cart_where)
                    ->select();
            }

            // 沒有相應的產品
            if (empty($list)) {
                $this->error('訂單產生失敗，沒有相應的產品！');
            }

            // 產品數據處理
            $PromType = '1'; // 1表示為虛擬訂單
            $TotalAmount = $TotalNumber = '';
            foreach ($list as $value) {
                if (!empty($value['users_price']) && !empty($value['product_num'])) {
                    // 合計金額
                    $TotalAmount += sprintf("%.2f", $value['users_price'] * $value['product_num']);
                    // 合計數量
                    $TotalNumber += $value['product_num'];
                    // 判斷訂單型別，目前邏輯：一個訂單中，只要存在一個普通產品(實物產品，需要發貨物流)，則為普通訂單
                    if (empty($value['prom_type'])) {
                        $PromType = '0';// 0表示為普通訂單
                    }
                }
            }

            $AddrData = [];
            // 非虛擬訂單則查詢運費資訊
            if (empty($PromType)) {
                // 沒有選擇收貨地址
                if (empty($post['addr_id'])) {
                    $this->error('訂單產生失敗，請新增收貨地址！');
                }

                // 查詢收貨地址
                $AddrWhere = [
                    'addr_id'  => $post['addr_id'],
                    'users_id' => $this->users_id,
                    'lang'     => $this->home_lang,
                ];
                $AddressData = $this->shop_address_db->where($AddrWhere)->find();
                if (empty($AddressData)) {
                    $this->error('訂單產生失敗，請新增收貨地址！');
                }

                $Shipping = getUsersConfigData('shop.shop_open_shipping');
                if (!empty($Shipping)) {
                    // 通過省份獲取運費模板中的運費價格
                    $shipping  = $this->shipping_template_db->where('province_id',$AddressData['province'])->field('template_money')->find();
                    if ('0.00' == $shipping['template_money']) {
                        // 省份運費價格為0時，使用統一的運費價格，固定ID為100000
                        $shipping = $this->shipping_template_db->where('province_id','100000')->field('template_money')->find();
                    }
                    // 合計金額加上運費價格
                    $TotalAmount += $shipping['template_money'];
                }else{
                    $shipping['template_money'] = '0.00';
                }

                // 拼裝陣列
                $AddrData = [
                    'consignee'    => $AddressData['consignee'],
                    'country'      => $AddressData['country'],
                    'province'     => $AddressData['province'],
                    'city'         => $AddressData['city'],
                    'district'     => $AddressData['district'],
                    'address'      => $AddressData['address'],
                    'mobile'       => $AddressData['mobile'],
                    'shipping_fee' => $shipping['template_money'],
                ];
            }

            // 新增到訂單主表
            $time = getTime();
            $OrderData = [
                'order_code'        => date('Ymd').$time.rand(10,100), //訂單產生規則
                'users_id'          => $this->users_id,
                'order_status'      => 0,
                'add_time'          => $time,
                'payment_method'    => $post['payment_method'],
                'order_total_amount'=> $TotalAmount,
                'order_amount'      => $TotalAmount,
                'order_total_num'   => $TotalNumber,
                'prom_type'         => $PromType,
                'user_note'         => $post['message'],
                'lang'              => $this->home_lang,
            ];
            
            // 存在收貨地址則追加合併到主表陣列
            if (!empty($AddrData)) {
                $OrderData = array_merge($OrderData, $AddrData); 
            }

            if ('1' == $post['payment_method']) {
                // 追加新增到訂單主表的陣列
                $OrderData['order_status'] = 1; // 標記已付款
                $OrderData['pay_time']     = $time;
                $OrderData['pay_name']     = 'delivery_pay';// 貨到付款
                $OrderData['update_time']  = $time;
            }

            // 數據驗證
            $rule = [
                'payment_method' => 'require|token',
            ];
            $message = [
                'payment_method.require' => '不可為空！',
            ];
            $validate = new \think\Validate($rule, $message);
            if(!$validate->check($post)){
                $this->error('非法操作！');
            }

            $OrderId = $this->shop_order_db->add($OrderData);

            if (!empty($OrderId)) {
                $cart_ids   = '';
                $attr_value = '';
                // 新增到訂單明細表
                foreach ($list as $key => $value) {
                    // 產品屬性處理
                    $AttrWhere = [
                        'a.aid'     => $value['aid'],
                        'b.lang'    => $this->home_lang,
                    ];
                    $AttrData = Db::name('product_attr')
                        ->alias('a')
                        ->field('a.attr_value,b.attr_name')
                        ->join('__PRODUCT_ATTRIBUTE__ b', 'a.attr_id = b.attr_id', 'LEFT')
                        ->where($AttrWhere)
                        ->order('b.sort_order asc, a.attr_id asc')
                        ->select();
                    foreach ($AttrData as $val) {
                        $attr_value .= $val['attr_name'].'：'.$val['attr_value'].'<br/>';
                    }

                    // 處理產品屬性
                    $Data = [
                        'attr_value' => htmlspecialchars($attr_value),
                        // 後續新增
                    ];

                    $OrderDetailsData[] = [
                        'order_id'      => $OrderId,
                        'users_id'      => $this->users_id,
                        'product_id'    => $value['aid'],
                        'product_name'  => $value['title'],
                        'num'           => $value['product_num'],
                        'data'          => serialize($Data),
                        'product_price' => $value['users_price'],
                        'prom_type'     => $value['prom_type'],
                        'litpic'        => $value['litpic'],
                        'add_time'      => $time,
                        'lang'          => $this->home_lang,
                    ];
                    if (empty($value['under_order_type'])) {
                        // 處理購物車ID
                        if ($key > '0') {
                            $cart_ids .= ',';
                        }
                        $cart_ids .= $value['cart_id'];
                    }
                }
                $DetailsId = $this->shop_order_details_db->insertAll($OrderDetailsData);

                if (!empty($OrderId) && !empty($DetailsId)) {
                    // 清理購物車中已下單的ID
                    if (!empty($cart_ids)) {
                        $this->shop_cart_db->where('cart_id','IN',$cart_ids)->delete();
                    }

                    // 新增訂單操作記錄
                    AddOrderAction($OrderId,$this->users_id);
                    if ('0' == $post['payment_method']) {
                        // 線上付款時，跳轉至付款頁
                        // 對ID和訂單號加密，拼裝url路徑
                        $querydata = [
                            'order_id'   => $OrderId,
                            'order_code' => $OrderData['order_code'],
                        ];
                        $querystr   = base64_encode(serialize($querydata));
                        $PaymentUrl = urldecode(url('user/Pay/pay_recharge_detail',['querystr'=>$querystr]));
                    }else{
                        // 無需跳轉付款頁，直接跳轉訂單列表頁
                        $PaymentUrl = urldecode(url('user/Shop/shop_centre'));
                        
                        // 貨到付款時，再次新增一條訂單操作記錄
                        AddOrderAction($OrderId,$this->users_id,'0','1','0','1','貨到付款！','會員選擇貨到付款，款項由快遞代收！');
                    }
                    $this->success('訂單已產生！',$PaymentUrl);
                }else{
                    $this->error('訂單產生失敗，商品數據有誤！');
                }
            }else{
                $this->error('訂單產生失敗，商品數據有誤！');
            }
        }
    }

    // 新增收貨地址
    public function shop_add_address()
    {
        if (IS_AJAX_POST) {
            $post = input('post.');
            if (empty($post['consignee'])) {
                $this->error('收貨人姓名不可為空！');
            }
            if (empty($post['mobile'])) {
                $this->error('收貨人手機不可為空！');
            }
            if (empty($post['province'])) {
                $this->error('收貨省份不可為空！');
            }
            if (empty($post['address'])) {
                $this->error('詳細地址不可為空！');
            }
            // 新增數據
            $post['users_id']   = $this->users_id;
            $post['add_time']   = getTime();
            $post['lang']       = $this->home_lang;
            $addr_id = $this->shop_address_db->add($post);

            // 根據地址ID查詢相應的中文名字
            $post['country']  = '中國';
            $post['province'] = get_province_name($post['province']);
            $post['city']     = get_city_name($post['city']);
            $post['district'] = get_area_name($post['district']);
            if (!empty($addr_id)) {
                $post['addr_id']  = $addr_id;
                $this->success('新增成功！','',$post);
            }else{
                $this->error('數據有誤！');
            }
        }

        $types = input('param.type');
        if ('list' == $types || 'order' == $types) {
            $Where = [
                'users_id' => $this->users_id,
                'lang'     => $this->home_lang,
            ];
            $addr_num = $this->shop_address_db->where($Where)->count();

            $eyou = [
                'field'    => [
                    'Province' => get_province_list(),
                    'types'    => $types,
                    'addr_num' => $addr_num,
                ],
            ];
            $this->assign('eyou',$eyou);
        }else{
            $this->error('非法來源！');
        }

        return $this->fetch('users/shop_add_address');
    }

    // 更新收貨地址
    public function shop_edit_address()
    {
        if (IS_AJAX_POST) {
            $post = input('post.');
            if (empty($post['consignee'])) {
                $this->error('收貨人姓名不可為空！');
            }
            if (empty($post['mobile'])) {
                $this->error('收貨人手機不可為空！');
            }
            if (empty($post['province'])) {
                $this->error('收貨省份不可為空！');
            }
            if (empty($post['address'])) {
                $this->error('詳細地址不可為空！');
            }
            // 更新條件及數據
            $post['users_id'] = $this->users_id;
            $post['add_time'] = getTime();
            $post['lang']     = $this->home_lang;

            $AddrWhere = [
                'addr_id'  => $post['addr_id'],
                'users_id' => $this->users_id,
                'lang'     => $this->home_lang,
            ];

            $addr_id = $this->shop_address_db->where($AddrWhere)->update($post);

            // 根據地址ID查詢相應的中文名字
            $post['country']  = '中國';
            $post['province'] = get_province_name($post['province']);
            $post['city']     = get_city_name($post['city']);
            $post['district'] = get_area_name($post['district']);
            if (!empty($addr_id)) {
                $this->success('修改成功！','',$post);
            }else{
                $this->error('數據有誤！');
            }
        }

        $AddrId   = input('param.addr_id');
        $AddrWhere = [
            'addr_id'  => $AddrId,
            'users_id' => $this->users_id,
            'lang'     => $this->home_lang,
        ];
        // 根據地址ID查詢相應的中文名字
        $AddrData = $this->shop_address_db->where($AddrWhere)->find();
        if (empty($AddrData)) {
            $this->error('數據有誤！');
        }
        $AddrData['country']  = '中國'; //國家
        $AddrData['Province'] = get_province_list(); // 省份
        $AddrData['City']     = $this->region_db->where('parent_id',$AddrData['province'])->select(); // 城市
        $AddrData['District'] = $this->region_db->where('parent_id',$AddrData['city'])->select(); // 縣/區/鎮
        $eyou = [
            'field'    => $AddrData,
        ];
        $this->assign('eyou',$eyou);
        return $this->fetch('users/shop_edit_address');
    }

    // 刪除收貨地址
    public function shop_del_address()
    {
        if (IS_POST) {
            $addr_id = input('post.addr_id/d');
            $Where = [
                'addr_id'  => $addr_id,
                'users_id' => $this->users_id,
                'lang'     => $this->admin_lang,
            ];
            $return = $this->shop_address_db->where($Where)->delete();
            if ($return) {
                $this->success('刪除成功！');
            }else{
                $this->error('刪除失敗！');
            }
        }
    }

    // 更新收貨地址，設定為預設地址
    public function shop_set_default_address()
    {
        if (IS_AJAX_POST) {
            $post = input('post.');
            // 更新條件及數據
            $post['users_id']   = $this->users_id;
            $post['is_default'] = '1'; //設定為預設
            $post['add_time']   = getTime();
            $post['lang']       = $this->home_lang;

            $AddrWhere = [
                'addr_id'  => $post['addr_id'],
                'users_id' => $this->users_id,
                'lang'     => $this->home_lang,
            ];
            $addr_id = $this->shop_address_db->where($AddrWhere)->update($post);
            if (!empty($addr_id)) {
                // 把對應會員下的所有地址改為非預設
                $AddressWhere = [
                    'addr_id'  => array('NEQ',$post['addr_id']),
                    'users_id' => $this->users_id,
                    'lang'     => $this->home_lang,
                ];
                $data['is_default']  = '0';// 設定為非預設
                $data['update_time'] = getTime();
                $this->shop_address_db->where($AddressWhere)->update($data);
                $this->success('設定成功！');
            }else{
                $this->error('數據有誤！');
            }
        }
    }

    // 查詢運費
    public function shop_inquiry_shipping()
    {
        if (IS_AJAX_POST) {
            $Shipping = getUsersConfigData('shop.shop_open_shipping');
            if (empty($Shipping)) {
                $this->success('','',0);
            }
            // 查詢會員收貨地址，獲取省份
            $addr_id = input('post.addr_id');
            $where = [
                'addr_id'  => $addr_id,
                'users_id' => $this->users_id,
                'lang'     => $this->home_lang,
            ];
            $data  = $this->shop_address_db->where($where)->field('province')->find();

            // 通過省份獲取運費模板中的運費價格
            $data  = $this->shipping_template_db->where('province_id',$data['province'])->field('template_money')->find();
            if ('0.00' == $data['template_money']) {
                // 省份運費價格為0時，使用統一的運費價格，固定ID為100000
                $data = $this->shipping_template_db->where('province_id','100000')->field('template_money')->find();
            }
            $this->success('','',$data['template_money']);
        }else{
            $this->error('訂單號錯誤');
        }
    }

    // 聯動地址獲取
    public function get_region_data(){
        $parent_id  = input('param.parent_id/d');
        $RegionData = $this->region_db->where("parent_id",$parent_id)->select();
        $html = '';
        if($RegionData){
            // 拼裝下拉選項
            foreach($RegionData as $value){
                $html .= "<option value='{$value['id']}'>{$value['name']}</option>";
            }
        }
        echo json_encode($html);
    }

    // 查詢物流，暫時棄用，已跳轉至快遞100鏈接查詢
    // public function shop_query_express()
    // {
    //     $param = input('param.');
    //     if (!empty($param)) {
    //         $order_id = $param['order_id'];
    //         if (!empty($order_id)) {
    //             // 查詢是否存在訂單資訊
    //             $OrderData = $this->shop_order_db->field('order_status,express_data')
    //                 ->where('order_id',$order_id)
    //                 ->find();
    //         }else{
    //             $this->error('產品ID不存在，請聯繫商家');
    //         }

    //         if ('3' == $OrderData['order_status']) {
    //             // 訂單已完成，物流資訊通過數據庫查詢返回
    //             $Data = unserialize($OrderData['express_data']);
    //             $ExpressData = $Data['data'];
    //         }else{
    //             // 判斷查詢資訊是否齊全
    //             if (empty($param['mobile'])) {
    //                 $this->error('物流資訊不全，缺少手機號，請聯繫商家');
    //             }else if (empty($param['express_order'])) {
    //                 $this->error('物流資訊不全，缺少運單號，請聯繫商家');
    //             }else if (empty($param['express_code'])) {
    //                 $this->error('物流資訊不全，缺少Code，請聯繫商家');
    //             }
    //             // 查詢實時物流資訊
    //             $return = queryExpress($param);
    //             if ('ok' == $return['message']) {
    //                 // 返回實時查詢的物流資訊
    //                 $ExpressData = $return['data'];
    //                 $Data['express_data'] = serialize($return);
    //                 $Data['update_time']  = getTime();
    //                 $this->shop_order_db->where('order_id',$order_id)->update($Data);
    //             }else{
    //                 // 物流介面錯誤，查詢數據庫返回目前節點物流資訊
    //                 $Data = unserialize($OrderData['express_data']);
    //                 $ExpressData = $Data['data'];
    //             }
    //         }
    //         $this->assign('ExpressData',$ExpressData);
    //         return $this->fetch('users/shop_query_express');
    //     } 
    // }

    // 會員提醒收貨
    public function shop_order_remind()
    {
        if (IS_AJAX_POST) {
            $post = input('post.');
            // 新增訂單操作記錄
            AddOrderAction($post['order_id'],$this->users_id,'0','1','0','1','提醒成功！','會員提醒管理員及時發貨！');
            $this->success('提醒成功！');
        }else{
            $this->error('訂單號錯誤');
        }
    }

    // 會員確認收貨
    public function shop_member_confirm()
    {
        if (IS_AJAX_POST) {
            $post = input('post.');
            // 更新條件
            $Where = [
                'order_id' => $post['order_id'],
                'users_id' => $this->users_id,
                'lang'     => $this->home_lang,
            ];
            // 更新數據
            $Data = [
                'order_status' => 3,
                'confirm_time' => getTime(),
                'update_time'  => getTime(),
            ];
            // 更新訂單主表
            $return = $this->shop_order_db->where($Where)->update($Data);

            if (!empty($return)) {
                // 更新數據
                $Data = [
                    'update_time'  => getTime(),
                ];
                // 更新訂單明細表
                $this->shop_order_details_db->where($Where)->update($Data);
                // 新增訂單操作記錄
                AddOrderAction($post['order_id'],$this->users_id,'0','3','1','1','確認收貨！','會員已確認收到貨物，訂單完成！');
                $this->success('會員確認收貨');
            }else{
                $this->error('訂單號錯誤');
            }
        }
    }
}