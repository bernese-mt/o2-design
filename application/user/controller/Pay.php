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
 * Date: 2019-2-25
 */

namespace app\user\controller;

use think\Db;
// use think\Session;
use think\Config;
use think\Page;

class Pay extends Base
{
    public $php_version = '';

    public function _initialize() {
        parent::_initialize();
        $this->users_db       = Db::name('users');      // 會員數據表
        $this->users_money_db = Db::name('users_money');// 會員金額明細表
        $this->shop_order_db = Db::name('shop_order'); // 訂單主表
        $this->shop_order_details_db = Db::name('shop_order_details'); // 訂單明細表

        // 判斷PHP版本資訊
        if (version_compare(PHP_VERSION,'5.5.0','<')) {
            $this->php_version = 1; // PHP5.5.0以下版本，可使用舊版支付方式
        }else{
            $this->php_version = 0;// PHP5.5.0以上版本，可使用新版支付方式，相容舊版支付方式
        }

        // 支付功能是否開啟
        $redirect_url = '';
        $pay_open = getUsersConfigData('pay.pay_open');
        $web_users_switch = tpCache('web.web_users_switch');
        if (empty($pay_open)) { 
            // 支付功能關閉，立馬跳到會員中心
            $redirect_url = url('user/Users/index');
            $msg = '支付功能尚未開啟！';
        } else if (empty($web_users_switch)) { 
            // 前臺會員中心已關閉，跳到首頁
            $redirect_url = ROOT_DIR.'/';
            $msg = '會員中心尚未開啟！';
        }
        if (!empty($redirect_url)) {
            Db::name('users_menu')->where([
                    'mca'   => 'user/Pay/pay_consumer_details',
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

    // 消費明細
    public function pay_consumer_details()
    {
        // 訂單超過 get_order_validity 設定的時間，則修改訂單為已取消狀態，無需返回數據
        model('Pay')->UpdateOrderData($this->users_id);

        // 數據查詢
        $condition['a.users_id'] = $this->users_id;
        $condition['a.lang'] = $this->home_lang;
        $count = $this->users_money_db->alias('a')->where($condition)->count();// 查詢滿足要求的總記錄數
        $Page = new Page($count, config('paginate.list_rows'));// 實例化分頁類 傳入總記錄數和每頁顯示的記錄數
        $list = $this->users_money_db->field('a.*')
            ->alias('a')
            ->where($condition)
            ->order('a.moneyid desc')
            ->limit($Page->firstRow.','.$Page->listRows)
            ->select();
        $show = $Page->show();// 分頁顯示輸出
        $this->assign('page',$show);// 賦值分頁輸出
        $this->assign('list',$list);// 賦值數據集
        $this->assign('pager',$Page);// 賦值分頁集

        // 獲取金額明細型別
        $pay_cause_type_arr = Config::get('global.pay_cause_type_arr');
        $this->assign('pay_cause_type_arr',$pay_cause_type_arr);

        // 獲取金額明細狀態
        $pay_status_arr     = Config::get('global.pay_status_arr');
        $this->assign('pay_status_arr',$pay_status_arr);

        $result = [];

        // 菜單名稱
        $result['title'] = Db::name('users_menu')->where([
                'mca'   => 'user/Pay/pay_consumer_details',
                'lang'  => $this->home_lang,
            ])->getField('title');

        $eyou = array(
            'field' => $result,
        );
        $this->assign('eyou', $eyou);

        return $this->fetch('users/pay_consumer_details');
    }

    // 賬戶充值
    public function pay_account_recharge()
    {
        if (IS_AJAX_POST) {
            // 獲取微信配置資訊
            $pay_wechat_config = !empty($this->usersConfig['pay_wechat_config']) ? unserialize($this->usersConfig['pay_wechat_config']) : [];
            
            // 獲取支付寶配置資訊
            $pay_alipay_config = !empty($this->usersConfig['pay_alipay_config']) ? unserialize($this->usersConfig['pay_alipay_config']) : [];

            if (empty($pay_wechat_config) && empty($pay_alipay_config)) {
                $this->error('網站支付配置未完善，請聯繫管理員！');
            }

            $money = input('post.money/f');
            $unified_number = input('post.unified_number/s');
            if (!empty($unified_number) && !preg_match('/^\d+$/',$unified_number)) {
                $this->error('訂單號不存在！');
            }

            // 判斷是否為數字和數字字串
            if (!empty($money) && is_numeric($money)) {
                $moneyRow = [];
                if (!empty($unified_number)) {
                    $moneyRow = $this->users_money_db->where([
                            'order_number'  => $unified_number,
                            'status'    => 1,
                            'lang'  => $this->home_lang,
                        ])->find();
                }
                if (!empty($moneyRow)) { // 更改充值金額
                    $moneyid = $moneyRow['moneyid'];
                    $order_number = $moneyRow['order_number'];
                    $old_money = $moneyRow['money'];
                    $data = [
                        'money'         => $money,
                        'users_money'   => Db::raw('users_money-'.$old_money),
                        'status'        => 1,
                        'update_time'      => getTime(),
                    ];
                    $this->users_money_db->where([
                            'moneyid'   => $moneyid,
                            'users_id'  => $this->users_id,
                        ])->update($data);
                } else {
                    // 數據新增到訂單表
                    $users = M('users')->field('users_money')->where([
                            'users_id'  => $this->users_id,
                            'lang'  => $this->home_lang,
                        ])->find();
                    $pay_cause_type_arr = Config::get('global.pay_cause_type_arr');
                    $time = getTime();
                    $cause_type = 1;
                    $order_number = date('Ymd').$time.rand(10,100); //訂單產生規則
                    $data = [
                        'users_id'      => $this->users_id,
                        'cause_type'    => $cause_type,
                        'cause'         => $pay_cause_type_arr[$cause_type],
                        'money'         => $money,
                        'users_money'   => $users['users_money'] + $money,
                        'order_number'  => $order_number,
                        'status'        => 1,
                        'lang'          => $this->home_lang,
                        'add_time'      => $time,
                    ];
                    $moneyid = $this->users_money_db->add($data);
                }
                // 新增狀態
                if (!empty($moneyid)) {
                    // 對ID和訂單號加密，拼裝url路徑
                    $querydata = [
                        'moneyid'    => $moneyid,
                        'order_number' => $order_number,
                    ];
                    $querystr    = base64_encode(serialize($querydata));
                    $url = urldecode(url('user/Pay/pay_recharge_detail', ['querystr'=>$querystr]));
                    $this->success('等待支付', $url);
                }
                $this->error('充值表單提交失敗');
            }
            $this->error('請輸入正確的充值金額！');
        }

        $money = input('param.money/f');
        $this->assign('money', $money);

        $unified_number = input('param.unified_number/s');
        $this->assign('unified_number', $unified_number);

        return $this->fetch('users/pay_account_recharge');
    }

    // 充值詳情
    public function pay_recharge_detail()
    {
        $querystr   = input('param.querystr/s');
        $querydata  = unserialize(base64_decode($querystr));

        if (!empty($querydata['moneyid']) && !empty($querydata['order_number'])) {
            // 充值資訊
            $moneyid = !empty($querydata['moneyid']) ? intval($querydata['moneyid']) : 0;
            $order_number = !empty($querydata['order_number']) ? $querydata['order_number'] : '';
        } else if (!empty($querydata['order_id']) && !empty($querydata['order_code'])) {
            // 訂單資訊
            $order_id   = !empty($querydata['order_id']) ? intval($querydata['order_id']) : 0;
            $order_code = !empty($querydata['order_code']) ? $querydata['order_code'] : '';
        } else {
            $this->error('訂單不存在！');
        }

        if (is_array($querydata) && (!empty($order_id) || !empty($moneyid)) && (!empty($order_number) || !empty($order_code))) {

            $data = [];

            if (!empty($moneyid)) {
                // 獲取會員充值資訊
                $data = $this->users_money_db->where([
                        'moneyid'      => $moneyid,
                        'order_number' => $order_number,
                        'users_id'     => $this->users_id,
                        'lang'         => $this->home_lang,
                    ])->find();
                if (empty($data)) {
                    $this->error('訂單不存在！');
                }
                $data['transaction_type'] = '1'; // 交易型別，1為充值
                $data['unified_id']       = $data['moneyid'];
                $data['unified_amount']   = $data['money'];
                $data['unified_number']   = $data['order_number'];
                $this->assign('data',$data);
            }else if (!empty($order_id)) {
                $data = $this->shop_order_db->where([
                        'order_id'     => $order_id,
                        'order_code'   => $order_code,
                        'users_id'     => $this->users_id,
                        'lang'         => $this->home_lang,
                    ])->find();
                if (empty($data)) {
                    $this->error('訂單不存在！');
                }
                $data['transaction_type'] = '2'; // 交易型別，2為購買
                $data['unified_id']       = $data['order_id'];
                $data['unified_amount']   = $data['order_amount'];
                $data['unified_number']   = $data['order_code'];
                $data['cause'] = '購買產品';
                $this->assign('data',$data);
            }

            // 獲取微信配置資訊
            $pay_wechat_config = !empty($this->usersConfig['pay_wechat_config']) ? unserialize($this->usersConfig['pay_wechat_config']) : [];
            // 獲取支付寶配置資訊
            $pay_alipay_config = !empty($this->usersConfig['pay_alipay_config']) ? unserialize($this->usersConfig['pay_alipay_config']) : [];

            // 充值資訊存在時，傳入訂單號等資訊獲取支付寶支付鏈接
            $alipay_url = '';
            if (!empty($data)) {
                if (!empty($pay_alipay_config)) {
                    if ($this->php_version == 1) {
                        // 低於5.5版本，僅可使用舊版支付寶支付
                        $alipay_url = model('Pay')->getOldAliPayPayUrl($data, $pay_alipay_config);
                    }else if($this->php_version == 0){
                        // 高於或等於5.5版本，可使用新版支付寶支付
                        if (empty($pay_alipay_config['version'])) {
                            // 新版
                            $alipay_url = url('user/Pay/newAlipayPayUrl',['unified_number'=>$data['unified_number'],'unified_amount'=>$data['unified_amount'],'transaction_type'=>$data['transaction_type']]);
                        }else if($pay_alipay_config['version'] == 1){
                            // 舊版
                            $alipay_url = model('Pay')->getOldAliPayPayUrl($data, $pay_alipay_config);
                        }
                    }
                }
            }

            $isbrowser = $isweixin = 0;
            if (isMobile() && isWeixin()) {
                $isbrowser = 1;
            }

            if (isMobile() && !isWeixin()) {
                $isweixin = 1;
                // 移動端非微信H5頁面支付
                $out_trade_no = $data['unified_number'];
                $total_fee    = $data['unified_amount'];
                $weixin_url   = model('Pay')->getMobilePay($out_trade_no,$total_fee);
                $this->assign('weixin_url',$weixin_url);
            }

            $this->assign('isbrowser',$isbrowser);
            $this->assign('isweixin',$isweixin);
            $this->assign('alipay_url',$alipay_url);

            // 是否開啟微信支付方式
            $is_open_wechat = 1;
            if (!empty($pay_wechat_config)) {
                $is_open_wechat = !empty($pay_wechat_config['is_open_wechat']) ? $pay_wechat_config['is_open_wechat'] : 0;
            }
            $this->assign('is_open_wechat', $is_open_wechat);
            if ('1' == $is_open_wechat) {
                // 若沒有配置支付資訊，則提示
                $WechatMsg = '微信支付配置尚未配置完成。<br/>請前往會員中心-支付功能-微信支付配置<br/>填入收款的微信支付配置資訊！';
                $this->assign('WechatMsg', $WechatMsg);
            }

            // 是否開啟支付寶支付方式
            $is_open_alipay = 1;
            if (!empty($pay_alipay_config)) {
                $is_open_alipay = !empty($pay_alipay_config['is_open_alipay']) ? $pay_alipay_config['is_open_alipay'] : 0;
            }
            $this->assign('is_open_alipay', $is_open_alipay);
            if ('1' == $is_open_alipay) {
                // 若沒有配置支付資訊，則提示
                $AlipayMsg = '支付寶支付配置尚未配置完成。<br/>請前往會員中心-支付功能-支付寶支付配置<br/>填入收款的支付寶支付配置資訊！';
                $this->assign('AlipayMsg', $AlipayMsg);
            }

            return $this->fetch('users/pay_recharge_detail');
        }
        $this->error('參數錯誤！');
    }

    public function get_order_detail()
    {
        if (IS_AJAX_POST) {
            // 訂單號
            $unified_number = input('post.unified_number/s');
            $unified_id     = input('post.unified_id/d');
            $transaction_type = input('post.transaction_type/d');
            // 跳轉鏈接
            $url = urldecode(url('user/Pay/pay_success', ['transaction_type'=>$transaction_type]));
            if ('2' == $transaction_type) {
                // 購買訂單
                // 查詢條件
                $OrderWhere = array(
                    'order_id'   => $unified_id,
                    'order_code' => $unified_number,
                    'users_id'   => $this->users_id,
                    'lang'       => $this->home_lang,
                );
                $OrderRow  = $this->shop_order_db->where($OrderWhere)->field('order_status,pay_name')->find();
                if (!empty($OrderRow)) {
                    // 判斷返回
                    if ('alipay' == $OrderRow['pay_name'] && in_array($OrderRow['order_status'], [1])) {
                        $this->success('訂單已在支付寶付款完成！即將跳轉~~~', $url);
                    }else if ('wechat' == $OrderRow['pay_name'] && in_array($OrderRow['order_status'], [1])) {
                        $this->success('訂單已在微信付款完成！即將跳轉~~~', $url);
                    }else if ('balance' == $OrderRow['pay_name'] && in_array($OrderRow['order_status'], [1])) {
                        $this->success('訂單已使用餘額支付完成！即將跳轉~~~', $url);
                    }else{
                        $this->error('等待支付');
                    }
                }
            }else if ('1' == $transaction_type) {
                // 充值訂單
                // 查詢條件
                $where = array(
                    'moneyid'     => $unified_id,
                    'order_number' => $unified_number,
                    'users_id'     => $this->users_id,
                    'lang'         => $this->home_lang,
                );
                $moneyRow  = $this->users_money_db->where($where)->field('status,pay_method')->find();
                if (!empty($moneyRow)) {
                    // 判斷返回
                    if ('alipay' == $moneyRow['pay_method'] && in_array($moneyRow['status'], [2,3])) {
                        $this->success('訂單已在支付寶付款完成！即將跳轉~~~', $url);
                    }else if ('wechat' == $moneyRow['pay_method'] && in_array($moneyRow['status'], [2,3])) {
                        $this->success('訂單已在微信付款完成！即將跳轉~~~', $url);
                    }else if ('artificial' == $moneyRow['pay_method'] && in_array($moneyRow['status'], [2,3])) {
                        $this->success('訂單已人為處理完成！即將跳轉~~~', $url);
                    }else{
                        $this->error('等待支付');
                    }
                }
            }
        }
        $this->error('訪問錯誤');
    }

    // 選擇付款方式，目前用於微信，支付寶方式已直接呼叫鏈接
    public function pay_method()
    {
        // 付款方式，跳轉至微信支付還是支付寶支付。
        $pay_method = input('param.pay_method/s');
        // 訂單交易型別
        $transaction_type = input('param.transaction_type/s');
        // 訂單號
        $unified_number   = input('param.unified_number/s');
        // 訂單ID
        $unified_id       = input('param.unified_id/d');

        $this->assign('unified_number',$unified_number);
        $this->assign('transaction_type',$transaction_type);
        // 執行跳轉
        return $this->fetch('users/pay_'.$pay_method);
    }

    // 微信支付，獲取訂單資訊並呼叫微信介面，產生二維碼用於掃碼支付
    public function pay_wechat_png(){
        $users_id = session('users_id');
        if (!empty($users_id)) {
            $unified_number   = input('param.unified_number/s');
            $transaction_type = input('param.transaction_type/s');
            if ('2' == $transaction_type) {
                // 購買訂單
                $where  = array(
                    'users_id'   => $users_id,
                    'order_code' => $unified_number,
                );
                $data  = $this->shop_order_db->where($where)->find();
                $out_trade_no = $data['order_code'];
                $total_fee    = $data['order_amount'];
            }else if ('1' == $transaction_type) {
                // 充值訂單
                $where  = array(
                    'users_id'     => $users_id,
                    'order_number' => $unified_number,
                );
                $data  = $this->users_money_db->where($where)->find();
                $out_trade_no = $data['order_number'];
                $total_fee    = $data['money'];
            }
            
            // 調取微信支付鏈接
            $payUrl = model('Pay')->payForQrcode($out_trade_no,$total_fee);// PC呼叫

            // 產生二維碼載入在頁面上
            vendor('wechatpay.phpqrcode.phpqrcode');
            $qrcode = new \QRcode;
            $pngurl = $payUrl;
            $qrcode->png($pngurl);
            exit();
        }else{
            $this->redirect('user/Users/login');
        }
    }

    // ajax非同步查詢訂單狀態，輪詢方式（微信）
    public function pay_deal_with(){
        if (IS_AJAX_POST) {
            $unified_number   = input('post.unified_number/s');
            $transaction_type = input('post.transaction_type/s');
            if(!empty($unified_number)){
                // ajax非同步查詢訂單是否完成並處理相應邏輯返回。
                vendor('wechatpay.lib.WxPayApi');
                vendor('wechatpay.lib.WxPayConfig');
                // 實例化載入訂單號
                $input  = new \WxPayOrderQuery;
                $input->SetOut_trade_no($unified_number);

                // 處理微信配置數據
                $pay_wechat_config = getUsersConfigData('pay.pay_wechat_config');
                $pay_wechat_config = unserialize($pay_wechat_config);
                $config_data['app_id'] = $pay_wechat_config['appid'];
                $config_data['mch_id'] = $pay_wechat_config['mchid'];
                $config_data['key']    = $pay_wechat_config['key'];

                // 實例化微信配置
                $config = new \WxPayConfig($config_data);
                $wxpayapi = new \WxPayApi;

                if (empty($config->app_id)) {
                    $this->error('微信支付配置尚未配置完成。');
                }

                // 返回結果
                $result = $wxpayapi->orderQuery($config, $input);
                // 業務處理
                if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS'){
                    if ($result['trade_state'] == 'SUCCESS' && !empty($result['transaction_id'])) {
                        if ('2' == $transaction_type) {
                            // 付款成功
                            $order_data = $this->shop_order_db->where([
                                'order_code' => $result['out_trade_no'],
                                'users_id'   => $this->users_id,
                                'lang'       => $this->home_lang,
                            ])->find();

                            if (empty($order_data)) {
                                $this->error('支付異常，請重新整理頁面後重試');
                            }

                            // 微信付款成功后，訂單並未修改狀態時，修改訂單狀態並返回
                            if (empty($order_data['order_status'])) {
                                $OrderWhere = [
                                    'order_id'  => $order_data['order_id'],
                                    'users_id'  => $this->users_id,
                                    'lang'      => $this->home_lang,
                                ];
                                // 修改會員金額明細表中，對應的訂單數據，存入返回的數據，訂單已付款
                                $OrderData = [
                                    'order_status' => 1,
                                    'pay_name'     => 'wechat', //微信支付
                                    'pay_details'  => serialize($result),
                                    'pay_time'     => getTime(),
                                    'update_time'  => getTime(),
                                ];
                                $order_id = $this->shop_order_db->where($OrderWhere)->update($OrderData);

                                if (!empty($order_id)) {
                                    $DetailsData['update_time'] = getTime();
                                    $this->shop_order_details_db->where($OrderWhere)->update($DetailsData);

                                    // 新增訂單操作記錄
                                    AddOrderAction($order_id,$this->users_id,'0','1','0','1','支付成功！','會員使用微信完成支付！');

                                    // 訂單支付完成
                                    $url = urldecode(url('user/Pay/pay_success', ['transaction_type'=>$transaction_type]));
                                    $this->success('支付成功，即將跳轉~~~', $url, ['status'=>1]);
                                }
                            }

                            if ($order_data['order_status'] == 1 && !empty($order_data['pay_details'])) {
                                // 訂單已付款
                                $url = urldecode(url('user/Pay/pay_success', ['transaction_type'=>$transaction_type]));
                                $this->success('支付成功，即將跳轉~~~', $url, ['status'=>1]);
                            }

                            if ($order_data['order_status'] == 3) {
                                // 訂單已完成，待處理邏輯
                                // 待處理邏輯..........
                            }

                            if ($order_data['order_status'] == 4) {
                                // 訂單已取消，待處理邏輯
                                // 待處理邏輯..........
                            }

                        }else if ('1' == $transaction_type) {
                            // 付款成功
                            $moneydata = $this->users_money_db->where([
                                'order_number' => $result['out_trade_no'],
                                'users_id'     => $this->users_id,
                                'lang'         => $this->home_lang,
                            ])->find();

                            if (empty($moneydata)) {
                                $this->error('支付異常，請重新整理頁面後重試');
                            }

                            // 微信付款成功后，訂單並未修改狀態時，修改訂單狀態並返回
                            if ($moneydata['status'] == 1) {
                                // 修改會員金額明細表中，對應的訂單數據，存入返回的數據，訂單已付款
                                $data = [
                                    'status'        => 2,
                                    'pay_method'    => 'wechat', //微信支付
                                    'pay_details'   => serialize($result),
                                    'update_time'   => getTime(),
                                ];
                                $ismoney = $this->users_money_db->where([
                                        'moneyid'  => $moneydata['moneyid'],
                                        'users_id'  => $this->users_id,
                                    ])->update($data);

                                if (!empty($ismoney)) {
                                    // 同步修改會員的金額
                                    $usersdata = [
                                        'users_money' => Db::raw('users_money+'.($moneydata['money'])),
                                    ];
                                    $isusers = $this->users_db->where([
                                            'users_id'  => $this->users_id,
                                        ])->update($usersdata);

                                    if (!empty($isusers)) {
                                        // 業務處理完成，訂單已完成
                                        $data2 = [
                                            'status'    => 3,
                                            'update_time'   => getTime(),
                                        ];
                                        $this->users_money_db->where([
                                                'moneyid'  => $moneydata['moneyid'],
                                                'users_id'  => $this->users_id,
                                            ])->update($data2);
                                        $url = urldecode(url('user/Pay/pay_success', ['transaction_type'=>$transaction_type]));
                                        $this->success('充值成功，即將跳轉~~~', $url, ['status'=>1]);
                                    }else{
                                        $this->success('付款成功，但未充值成功，請聯繫管理員。', null, ['status'=>2]);
                                    }
                                }else{
                                    $this->success('付款成功，數據錯誤，未能充值成功，請聯繫管理員。', null, ['status'=>2]);
                                }
                            }

                            if ($moneydata['status'] == 2 && !empty($moneydata['pay_details'])) {
                                // 訂單已付款
                                $url = urldecode(url('user/Pay/pay_success', ['transaction_type'=>$transaction_type]));
                                $this->success('充值成功，即將跳轉~~~', $url, ['status'=>1]);
                            }

                            if ($moneydata['status'] == 3) {
                                // 訂單已完成，待處理邏輯
                                // 待處理邏輯..........
                            }

                            if ($moneydata['status'] == 4) {
                                // 訂單已取消，待處理邏輯
                                // 待處理邏輯..........
                            }
                        }
                    }else if ($result['trade_state'] == 'NOTPAY') {
                        // 付款中
                        $this->success('正在付款中~~~~', $url, ['status'=>0]);
                    }
                }else{
                    $msg = '訂單號：'.$unified_number.'，支付失敗，請重新下單支付。';
                    $this->error($msg, null, ['status'=>0]);
                }
            }
        }
        $this->error('訪問錯誤');
    }

    // 微信支付成功后跳轉到此頁面
    public function pay_success(){
        if ('1' == input('param.transaction_type')) {
            $url = urldecode(url('user/Pay/pay_consumer_details'));
        }else if ('2' == input('param.transaction_type')) {
            $url = urldecode(url('user/Shop/shop_centre'));
        }
        $this->assign('url',$url);
        return $this->fetch('users/pay_success');
    }

    // 新版支付寶支付
    public function newAlipayPayUrl(){
        $data['unified_number']   = input('param.unified_number/s');
        $data['unified_amount']   = input('param.unified_amount/f');
        $data['transaction_type'] = input('param.transaction_type/d');
        // 呼叫新版支付寶支付方法
        model('Pay')->getNewAliPayPayUrl($data);
    }

    // 支付寶回撥介面，處理訂單數據
    public function alipay_return(){
        $param = input('param.');
        $pay_alipay_config = getUsersConfigData('pay.pay_alipay_config');
        if (empty($pay_alipay_config)) {
            return false;
        }
        $is_alipay = unserialize($pay_alipay_config);
        if ($is_alipay['version'] == 0) {
            if ('2' == $param['transaction_type']) {
                if (!empty($param['trade_no']) && !empty($param['out_trade_no'])){
                    $order_data = $this->shop_order_db->where([
                        'order_code' => $param['out_trade_no'],
                        'users_id'   => $this->users_id,
                        'lang'       => $this->home_lang,
                    ])->find();
                    if (empty($order_data)) {
                        $this->error('支付異常，請重新整理頁面後重試');
                    }

                    // 支付寶付款成功后，訂單並未修改狀態時，修改訂單狀態並返回
                    if (empty($order_data['order_status'])) {
                        $OrderWhere = [
                            'order_id'  => $order_data['order_id'],
                            'users_id'  => $this->users_id,
                            'lang'      => $this->home_lang,
                        ];
                        $OrderData = [
                            'order_status' => 1,
                            'pay_name'     => 'alipay', //支付寶支付
                            'pay_details'  => serialize($param),
                            'pay_time'     => getTime(),
                            'update_time'  => getTime(),
                        ];
                        $order_id = $this->shop_order_db->where($OrderWhere)->update($OrderData);

                        if (!empty($order_id)) {
                            $DetailsData['update_time'] = getTime();
                            $this->shop_order_details_db->where($OrderWhere)->update($DetailsData);

                            // 新增訂單操作記錄
                            AddOrderAction($order_id,$this->users_id,'0','1','0','1','支付成功！','會員使用支付寶完成支付！');
                        }
                    }
                }
                $this->redirect('user/Shop/shop_centre');
            }else if ('1' == $param['transaction_type']) {
                if (!empty($param['trade_no']) && !empty($param['out_trade_no'])){
                    // 付款成功
                    $moneydata = $this->users_money_db->where('order_number',$param['out_trade_no'])->find();
                    if (!empty($moneydata)) {
                        // APPID和夥伴ID驗證相等
                        if ($is_alipay['app_id'] == $param['app_id'] && $is_alipay['id'] == $param['seller_id']) {
                            // 支付寶訂單處理流程
                            $pay_money = $param['total_amount'];
                            // 參數1為支付寶返回數據集
                            // 參數2為充值記錄表數據集
                            // 參數3為訂單實際付款金額
                            $this->OrderProcessing($param,$moneydata,$pay_money);
                        }
                    }
                }
                $this->redirect('user/Pay/pay_consumer_details');
            }
        }else if($is_alipay['version'] == 1){
            if ('2' == $param['transaction_type']) {
                if (!empty($param['trade_no']) && !empty($param['out_trade_no'])){
                    $order_data = $this->shop_order_db->where([
                        'order_code' => $param['out_trade_no'],
                        'users_id'   => $this->users_id,
                        'lang'       => $this->home_lang,
                    ])->find();
                    if (empty($order_data)) {
                        $this->error('支付異常，請重新整理頁面後重試');
                    }

                    // 支付寶付款成功后，訂單並未修改狀態時，修改訂單狀態並返回
                    if (empty($order_data['order_status'])) {
                        $OrderWhere = [
                            'order_id'  => $order_data['order_id'],
                            'users_id'  => $this->users_id,
                            'lang'      => $this->home_lang,
                        ];
                        $OrderData = [
                            'order_status' => 1,
                            'pay_name'     => 'alipay', //支付寶支付
                            'pay_details'  => serialize($param),
                            'pay_time'     => getTime(),
                            'update_time'  => getTime(),
                        ];
                        $order_id = $this->shop_order_db->where($OrderWhere)->update($OrderData);

                        if (!empty($order_id)) {
                            $DetailsData['update_time'] = getTime();
                            $this->shop_order_details_db->where($OrderWhere)->update($DetailsData);

                            // 新增訂單操作記錄
                            AddOrderAction($order_id,$this->users_id,'0','1','0','1','支付成功！','會員使用支付寶完成支付！');
                        }
                    }
                }
                $this->redirect('user/Shop/shop_centre');
            }else if ('1' == $param['transaction_type']) {
                if (!empty($param['trade_no']) && $param['trade_status'] == 'TRADE_SUCCESS') {
                    // 付款成功
                    $moneydata = $this->users_money_db->where('order_number',$param['out_trade_no'])->find();
                    // 夥伴ID驗證相等
                    if ($is_alipay['id'] == $param['seller_id']) {
                        // 支付寶訂單處理流程
                        $pay_money = $param['total_fee'];
                        // 參數1為支付寶返回數據集
                        // 參數2為充值記錄表數據集
                        // 參數3為訂單實際付款金額
                        $this->OrderProcessing($param,$moneydata,$pay_money);
                    }
                }

                if($param['trade_status'] == 'WAIT_BUYER_PAY'){
                    // 交易建立，等待買家付款
                }
                if($param['trade_status'] == 'TRADE_CLOSED'){
                    // 未付款交易超時關閉，或支付完成後全額退款
                }
                if($param['trade_status'] == 'TRADE_FINISHED'){
                    // 交易結束，不可退款
                }

                $this->redirect('user/Pay/pay_consumer_details');
            }
        }
    }

    // 餘額支付
    public function balance_payment()
    {
        if (IS_AJAX_POST) {
            $post = input('post.');
            $Data = $this->shop_order_db->field('order_amount')->find($post['unified_id']);
            if ($this->users['users_money'] >= $Data['order_amount']) {
                $Where = [
                    'users_id'   => $this->users_id,
                    'lang'       => $this->home_lang,
                ];

                $post['payment_amount'] = $Data['order_amount'];
                $post['payment_type']   = '餘額支付';
                $OrderData = [
                    'order_status' => 1,
                    'pay_name'     => 'balance',//餘額支付
                    'pay_details'  => serialize($post),
                    'pay_time'     => getTime(),
                    'update_time'  => getTime(),
                ];
                $OrderWhere = [
                    'order_id'   => $post['unified_id'],
                    'order_code' => $post['unified_number'],
                ];
                $OrderWhere = array_merge($Where, $OrderWhere);
                $return = $this->shop_order_db->where($OrderWhere)->update($OrderData);

                if (!empty($return)) {
                    $DetailsWhere = [
                        'order_id'   => $post['unified_id'],
                    ];
                    $DetailsWhere = array_merge($Where, $DetailsWhere);
                    $DetailsData['update_time'] = getTime();
                    $this->shop_order_details_db->where($DetailsWhere)->update($DetailsData);

                    $UsersData = [
                        'users_money' => $this->users['users_money'] - $Data['order_amount'],
                        'update_time' => getTime(),
                    ];
                    $users_id = $this->users_db->where($Where)->update($UsersData);
                    if (!empty($users_id)) {
                        // 新增訂單操作記錄
                        AddOrderAction($post['unified_id'],$this->users_id,'0','1','0','1','支付成功！','會員使用餘額完成支付！');

                        $url = urldecode(url('user/Pay/pay_success', ['transaction_type'=>2]));
                        $this->success('訂單已在餘額付款完成！即將跳轉~~~', $url);
                    }
                }else{
                    $this->error('訂單支付異常，請重新整理后再進行支付！');
                }
            }else{
                $url = urldecode(url('user/Pay/pay_account_recharge'));
                $this->error('餘額不足，若要使用餘額支付，請去充值！',$url);
            }
        }
    }

    // 支付寶訂單處理流程
    public function OrderProcessing($param,$moneydata,$pay_money){
        // 支付寶付款成功后，訂單並未修改狀態時，修改訂單狀態並返回
        if ($moneydata['status'] == 1) {
            $usersdata = $this->users_db->field('users_money')->find($moneydata['users_id']);
            // 修改會員金額明細表中，對應的訂單數據，存入返回的數據，訂單已付款
            $data['pay_method']  = 'alipay';//支付寶支付
            $data['pay_details'] = serialize($param);
            $data['status']      = 2;
            $data['update_time'] = getTime();
            $ismoney  = $this->users_money_db->where([
                    'moneyid'  => $moneydata['moneyid'],
                    'users_id'  => $this->users_id,
                ])->update($data);

            if (!empty($ismoney)) {
                // 同步修改會員的金額
                $usersdata['users_id']    = $this->users_id;
                $usersdata['users_money'] = Db::raw('users_money+'.$pay_money);
                $isusers = $this->users_db->update($usersdata);

                if (!empty($isusers)) {
                    // 業務處理完成，訂單已完成
                    $data_['status']      = 3;
                    $data_['update_time'] = getTime();
                    $this->users_money_db->where([
                            'moneyid'  => $moneydata['moneyid'],
                            'users_id'  => $this->users_id,
                        ])->update($data_);
                    $this->redirect('user/Pay/pay_consumer_details');
                }else{
                    $msg = '付款成功，但未充值成功，請聯繫管理員。';
                    $this->assign('msg', $msg);
                    return $this->fetch('users/pay_error');
                }
            }else{
                $msg = '付款成功，數據錯誤，未能充值成功，請聯繫管理員。';
                $this->assign('msg', $msg);
                return $this->fetch('users/pay_error');
            }
        }

        if ($moneydata['status'] == 2 && !empty($moneydata['pay_details'])) {
            // 訂單已付款
            $this->redirect('user/Pay/pay_consumer_details');
        }

        if ($moneydata['status'] == 3) {
            // 訂單已完成，待處理邏輯
            // 待處理邏輯..........
        }

        if ($users_money['status'] == 4) {
            // 訂單已取消，待處理邏輯
            // 待處理邏輯..........
        }
    }

    public function update_pay_method()
    {
        if (IS_AJAX_POST) {
            $post     = input('post.');
            if (!empty($post)) {
                $UpdateData['moneyid']       = $post['unified_id'];
                $UpdateData['unified_number'] = $post['unified_number'];
                $UpdateData['pay_method']     = $post['pay_method'];
                $UpdateData['update_time']     = getTime();
                $this->users_money_db->update($UpdateData);
            }
        }
    }

}