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
 * Date: 2019-03-26
 */

namespace app\admin\controller;

use think\Page;
use think\Db;
use think\Config;
use app\admin\logic\ShopLogic;

class Shop extends Base {

    private $UsersConfigData = [];

    /**
     * 構造方法
     */
    public function __construct(){
        parent::__construct();
        $this->users_db              = Db::name('users');                   // 使用者資訊表
        $this->shop_order_db         = Db::name('shop_order');              // 訂單主表
        $this->shop_order_details_db = Db::name('shop_order_details');      // 訂單明細表
        $this->shop_address_db       = Db::name('shop_address');            // 收貨地址表
        $this->shop_express_db       = Db::name('shop_express');            // 物流名字表
        $this->shop_order_log_db  = Db::name('shop_order_log');             // 訂單操作表
        $this->shipping_template_db  = Db::name('shop_shipping_template');  // 運費模板表

        // 會員中心配置資訊
        $this->UsersConfigData = getUsersConfigData('all');
        $this->assign('userConfig',$this->UsersConfigData);
    }

    /**
     * 商城設定
     */
    public function conf(){
        if (IS_POST) {
            $post = input('post.');
            if (!empty($post)) {
                foreach ($post as $key => $val) {
                    getUsersConfigData($key, $val);
                }
                $this->success('設定成功！');
            }
        }

        // 商城配置資訊
        $ConfigData = getUsersConfigData('shop');
        $this->assign('Config',$ConfigData);
        return $this->fetch('conf');
    }

    /**
     *  訂單列表
     */
    public function index()
    {
        // 初始化陣列和條件
        $list  = array();
        $Where = [
            'lang'   => $this->admin_lang,
        ];
        // 訂單號查詢
        $order_code = input('order_code/s');
        if (!empty($order_code)) {
            $Where['order_code'] = array('LIKE', "%{$order_code}%");
        }
        // 訂單狀態查詢
        $order_status = input('order_status/s');
        if (!empty($order_status)) {
            $Where['order_status'] = $order_status;
        }
        // 查詢滿足要求的總記錄數
        $count = $this->shop_order_db->where($Where)->count('order_id');
        // 實例化分頁類 傳入總記錄數和每頁顯示的記錄數
        $pageObj = new Page($count, config('paginate.list_rows'));
        // 訂單主表數據查詢
        $list = $this->shop_order_db->where($Where)
            ->order('order_id desc')
            ->limit($pageObj->firstRow.','.$pageObj->listRows)
            ->select();
        // 分頁顯示輸出
        $pageStr = $pageObj->show();
        // 獲取訂單狀態
        $admin_order_status_arr = Config::get('global.admin_order_status_arr');
        // 獲取訂單方式名稱
        $pay_method_arr = Config::get('global.pay_method_arr');
        // 訂單狀態篩選陣列
        $OrderStatus = array(
            0 => array(
                'order_status' => '1',
                'status_name'  => '待發貨',
            ),
            1 => array(
                'order_status' => '2',
                'status_name'  => '已發貨',
            ),
            2 => array(
                'order_status' => '3',
                'status_name'  => '已完成',
            ),
        );
        // 數據載入
        $this->assign('pageObj', $pageObj);
        $this->assign('list', $list);
        $this->assign('pageStr', $pageStr);
        $this->assign('admin_order_status_arr',$admin_order_status_arr);
        $this->assign('pay_method_arr',$pay_method_arr);
        $this->assign('OrderStatus', $OrderStatus);

        /*檢測是否存在訂單中心模板*/
        if ('v1.0.1' > getVersion('version_themeshop') && !empty($this->UsersConfigData['shop_open'])) {
            $is_syn_theme_shop = 1;
        } else {
            $is_syn_theme_shop = 0;
        }
        $this->assign('is_syn_theme_shop',$is_syn_theme_shop);
        /*--end*/

        return $this->fetch();
    }

    /**
     *  訂單詳情
     */
    public function order_details()
    {
        $order_id = input('param.order_id');
        if (!empty($order_id)) {
            // 查詢訂單資訊
            $this->GetOrderData($order_id);
            // 查詢訂單操作記錄
            $Action = $this->shop_order_log_db->where('order_id',$order_id)->order('action_id desc')->select();
            // 操作記錄數據處理
            foreach ($Action as $key => $value) {
                if ('0' == $value['action_user']) {
                    // 若action_user為0，表示會員操作，根據訂單號中的ID獲取會員名。
                    $username = $this->users_db->field('username')->where('users_id',$value['users_id'])->find();
                    $Action[$key]['username'] = '會 &nbsp; 員: '.$username['username'];
                }else{
                    // 若action_user不為0，表示管理員操作，根據ID獲取管理員名。
                    $user_name = Db::name('admin')->field('user_name')->where('admin_id',$value['action_user'])->find();
                    $Action[$key]['username'] = '管理員: '.$user_name['user_name'];
                }

                // 操作時，訂單發貨狀態
                $Action[$key]['express_status'] = '未發貨';
                if ('1' == $value['express_status']) {
                    $Action[$key]['express_status'] = '已發貨';
                }

                // 操作時，訂單付款狀態
                $Action[$key]['pay_status'] = '未支付';
                if ('1' == $value['pay_status']) {
                    $Action[$key]['pay_status'] = '已支付';
                }
            }

            $this->assign('Action', $Action);
            return $this->fetch('order_details');
        }else{
            $this->error('非法訪問！');
        }
    }

    /**
     *  訂單發貨
     */
    public function order_send()
    {
        $order_id = input('param.order_id');
        if ($order_id) {
            // 查詢訂單資訊
            $this->GetOrderData($order_id);
            return $this->fetch('order_send');
        }
    }

    /**
     *  訂單發貨操作
     */
    public function order_send_operating()
    {
        if (IS_POST) {
            $post = input('post.');
            // 條件陣列
            $Where = [
                'order_id'   => $post['order_id'],
                'users_id'   => $post['users_id'],
                'lang'       => $this->admin_lang,
            ];

            // 更新陣列
            $UpdateData = [
                'order_status'  => 2,
                'express_order' => $post['express_order'],
                'express_name'  => $post['express_name'],
                'express_code'  => $post['express_code'],
                'express_time'  => getTime(),
                'consignee'     => $post['consignee'],
                'update_time'   => getTime(),
                'note'          => $post['note'],
                'virtual_delivery' => $post['virtual_delivery'],
            ];
            
            // 訂單操作記錄邏輯
            $LogWhere = [
                'order_id'       => $post['order_id'],
                'express_status' => 1,
            ];
            $LogData   = $this->shop_order_log_db->where($LogWhere)->count();
            if (!empty($LogData)) {
                // 數據存在則表示為修改發貨內容
                $OrderData = $this->shop_order_db->where($Where)->field('prom_type')->find();
                $Desc = '修改發貨內容！';
                if ('1' == $post['prom_type']) {
                    // 提交的數據為虛擬訂單
                    if ($OrderData['prom_type'] != $post['prom_type']) {
                        // 此處判斷後，提交的訂單型別和數據庫中的訂單型別不相同，表示普通訂單修改爲虛擬訂單
                        $Note = '管理員將普通訂單修改爲虛擬訂單！';
                        if (!empty($post['virtual_delivery'])) {
                            // 若存在數據則拼裝
                            $Note .= '給買家回覆：'.$post['virtual_delivery'];
                        }
                    }else{
                        // 繼續保持為虛擬訂單修改
                        $Note = '虛擬訂單，無需物流。';
                        if (!empty($post['virtual_delivery'])) {
                            // 若存在數據則拼裝
                            $Note .= '給買家回覆：'.$post['virtual_delivery'];
                        }
                    }
                }else{
                    // 提交的數據為普通訂單
                    if ($OrderData['prom_type'] != $post['prom_type']) {
                        // 這一段暫時無用，因為發貨時，暫時無法選擇將虛擬訂單修改爲普通訂單
                        $Note = '管理員將虛擬訂單修改爲普通訂單！';
                        if (!empty($post['virtual_delivery'])) {
                            // 若存在數據則拼裝
                            $Note .= '給買家回覆：'.$post['virtual_delivery'];
                        }
                    }else{
                        // 繼續保持為普通訂單修改
                        $Note = '使用'.$post['express_name'].'發貨成功！';
                    }
                }
                $UpdateData['prom_type'] = $post['prom_type'];
            }else{
                // 數據不存在則表示為初次發貨，拼裝發貨內容
                $Desc = '發貨成功！';
                $Note = '使用'.$post['express_name'].'發貨成功！';
                if ('1' == $post['prom_type']) {
                    // 若為虛擬訂單，無需發貨物流。
                    $UpdateData['prom_type'] = $post['prom_type'];
                    $Note = '虛擬訂單，無需物流。';
                    if (!empty($post['virtual_delivery'])) {
                        // 若存在數據則拼裝
                        $Note .= '給買家回覆：'.$post['virtual_delivery'];
                    }
                }
            }

            if (empty($post['prom_type']) && empty($post['express_order'])) {
                $this->error('配送單號不能為空！');
            }

            // 更新訂單主表資訊
            $IsOrder = $this->shop_order_db->where($Where)->update($UpdateData);
            if (!empty($IsOrder)) {
                // 更新訂單明細表資訊
                $Data['update_time'] = getTime();
                $this->shop_order_details_db->where('order_id',$post['order_id'])->update($Data);
                // 新增訂單操作記錄
                AddOrderAction($post['order_id'],'0',session('admin_id'),'2','1','1',$Desc,$Note);
                $this->success('發貨成功');
            } else {
                $this->error('發貨失敗');
            }
        }
    }

    /**
     * 查詢快遞名字及Code
     */
    public function order_express()
    {
        $ExpressData = array();
        $Where = array();
        $keywords = input('keywords/s');
        if (!empty($keywords)) {
            $Where['express_name'] = array('LIKE', "%{$keywords}%");
        }

        $count = $this->shop_express_db->where($Where)->count('express_id');// 查詢滿足要求的總記錄數
        $pageObj = new Page($count, '10');// 實例化分頁類 傳入總記錄數和每頁顯示的記錄數
        $ExpressData = $this->shop_express_db->where($Where)
            ->order('sort_order asc,express_id asc')
            ->limit($pageObj->firstRow.','.$pageObj->listRows)
            ->select();

        $pageStr = $pageObj->show(); 
        $this->assign('ExpressData', $ExpressData);
        $this->assign('pageStr', $pageStr);
        $this->assign('pageObj', $pageObj);
        return $this->fetch('order_express');
    }

    /**
     *  管理員後臺標記訂單狀態
     */
    public function order_mark_status()
    {
        if (IS_POST) {
            $post = input('post.');
            // 條件陣列
            $Where = [
                'order_id' => $post['order_id'],
                'users_id' => $post['users_id'],
                'lang'     => $this->admin_lang,
            ];

            if ('ddsc' == $post['status_name']) {
                // 訂單刪除
                $IsDelete = $this->shop_order_db->where($Where)->delete();
                if (!empty($IsDelete)) {
                    // 同步刪除訂單下的產品
                    $this->shop_order_details_db->where($Where)->delete();
                    // 同步刪除訂單下的操作記錄
                    $this->shop_order_log_db->where($Where)->delete();
                    $this->success('刪除成功！');
                }else{
                    $this->error('數據錯誤！');
                }
            }else{
                $OrderData = $this->shop_order_db->where($Where)->find();

                // 更新陣列
                $UpdateData = [
                    'update_time'  => getTime(),
                ];

                // 根據不同操作標記不同操作內容
                if ('yfk' == $post['status_name']) {
                    // 訂單標記為付款，追加更新陣列
                    $UpdateData['order_status'] = '1';
                    $UpdateData['pay_time']     = getTime();
                    // 管理員付款
                    $UpdateData['pay_name']     = 'admin_pay';

                    /*用於新增訂單操作記錄*/
                    $order_status   = '1'; // 訂單狀態
                    $express_status = '0'; // 發貨狀態
                    $pay_status     = '1'; // 支付狀態
                    $action_desc    = '付款成功！'; // 操作明細
                    $action_note    = '管理員確認訂單付款！'; // 操作備註
                    /*結束*/

                }else if ('ysh' == $post['status_name']) {
                    // 訂單確認收貨，追加更新陣列
                    $UpdateData['order_status'] = '3';
                    $UpdateData['confirm_time'] = getTime();

                    /*用於新增訂單操作記錄*/
                    $order_status   = '3'; // 訂單狀態
                    $express_status = '1'; // 發貨狀態
                    $pay_status     = '1'; // 支付狀態
                    $action_desc    = '確認收貨！'; // 操作明細
                    $action_note    = '管理員確認訂單已收貨！'; // 操作備註
                    /*結束*/

                }else if ('gbdd' == $post['status_name']) {
                    // 訂單關閉，追加更新陣列
                    $UpdateData['order_status'] = '-1';

                    /*用於新增訂單操作記錄*/
                    $order_status = '-1'; // 訂單狀態
                    if ('0' == $OrderData['order_status'] || '1' == $OrderData['order_status']) {
                        $express_status = '0'; // 發貨狀態
                        $pay_status     = '0'; // 支付狀態
                    }else{
                        $express_status = '1'; // 發貨狀態
                        $pay_status     = '1'; // 支付狀態
                    }
                    $action_desc  = '訂單關閉！'; // 操作明細
                    $action_note  = '管理員關閉訂單！'; // 操作備註
                    /*結束*/
                }

                // 更新訂單主表
                $IsOrder = $this->shop_order_db->where($Where)->update($UpdateData);
                if (!empty($IsOrder)) {
                    // 更新訂單明細表
                    $Data['update_time'] = getTime();
                    $this->shop_order_details_db->where('order_id',$post['order_id'])->update($Data);

                    // 新增訂單操作記錄
                    AddOrderAction($post['order_id'],'0',session('admin_id'),$order_status,$express_status,$pay_status,$action_desc,$action_note);

                    $this->success('操作成功！');
                }
            }
        }else{
            $this->error('非法訪問！');
        }
    }

    /*
     *  更新管理員備註
     */
    public function update_note()
    {
        if (IS_AJAX_POST) {
            $post = input('post.');
            if (!empty($post['order_id'])) {
                $UpdateData = [
                    'admin_note'  => $post['admin_note'],
                    'update_time' => getTime(),
                ];
                $return = $this->shop_order_db->where('order_id',$post['order_id'])->update($UpdateData);
                if (!empty($return)) {
                    $this->success('儲存成功！');
                }
            }else{
                $this->error('非法訪問！');
            }
        }else{
            $this->error('非法訪問！');
        }
    }

    /*
     *  運費模板列表
     */
    public function shipping_template()
    {
        $Where = [
            'a.level' => 1,
        ];

        $region_name = input('param.region_name');
        if (!empty($region_name)) {
            $Where['a.name'] = $region_name;
        }

        // 省份
        $Template = M('region')->field('a.id, a.name,b.template_money,b.template_id')
            ->alias('a')
            ->join('__SHOP_SHIPPING_TEMPLATE__ b', 'a.id = b.province_id', 'LEFT')
            ->where($Where)
            ->getAllWithIndex('id');
        $this->assign('Template', $Template);
        
        // 統一配送
        $info = $this->shipping_template_db->where('province_id','100000')->find();
        $this->assign('info', $info);

        return $this->fetch('shipping_template');
    }

    // 訂單批量刪除
    public function order_del()
    {
        $order_id = input('del_id/a');
        $order_id = eyIntval($order_id);
        if (IS_AJAX_POST && !empty($order_id)) {
            // 條件陣列
            $Where = [
                'order_id'  => ['IN', $order_id],
                'lang'      => $this->admin_lang,
            ];
            // 查詢數據，存在adminlog日誌
            $result = $this->shop_order_db->field('order_code')->where($Where)->select();
            $order_code_list = get_arr_column($result, 'order_code');
            // 刪除訂單列表數據
            $return = $this->shop_order_db->where($Where)->delete();
            if ($return) {
                // 同步刪除訂單下的產品
                $this->shop_order_details_db->where($Where)->delete();
                // 同步刪除訂單下的操作記錄
                $this->shop_order_log_db->where($Where)->delete();

                adminLog('刪除訂單：'.implode(',', $order_code_list));
                $this->success('刪除成功');
            }else{
                $this->error('刪除失敗');
            }
        }
        $this->error('參數有誤');
    }

    /*
     *  查詢會員訂單數據並載入，無返回
     */
    function GetOrderData($order_id)
    {
        // 獲取訂單數據
        $OrderData = $this->shop_order_db->find($order_id);

        // 獲取會員數據
        $UsersData = $this->users_db->find($OrderData['users_id']);
        // 目前單條訂單資訊的會員ID，存入session，用於新增訂單操作表
        session('OrderUsersId',$OrderData['users_id']);

        // 獲取訂單詳細表數據
        $DetailsData = $this->shop_order_details_db->where('order_id',$OrderData['order_id'])->select();

        // 獲取訂單狀態，後臺專用
        $admin_order_status_arr = Config::get('global.admin_order_status_arr');

        // 獲取訂單方式名稱
        $pay_method_arr = Config::get('global.pay_method_arr');

        // 處理訂單主表的地址數據處理，顯示中文名字
        $OrderData['country']  = '中國';
        $OrderData['province'] = get_province_name($OrderData['province']);
        $OrderData['city']     = get_city_name($OrderData['city']);
        $OrderData['district'] = get_area_name($OrderData['district']);

        $array_new = get_archives_data($DetailsData,'product_id');
        // 處理訂單詳細表數據處理
        foreach ($DetailsData as $key => $value) {
            // 產品屬性處理
            $value['data'] = unserialize($value['data']);
            $attr_value = htmlspecialchars_decode($value['data']['attr_value']);
            $attr_value = htmlspecialchars_decode($attr_value);
            $DetailsData[$key]['data'] = $attr_value;

            // 產品內頁地址
            $DetailsData[$key]['arcurl'] = get_arcurl($array_new[$value['product_id']]);
            
            // 小計
            $DetailsData[$key]['subtotal'] = $value['product_price'] * $value['num'];
        }

        // 訂單型別
        if (empty($OrderData['prom_type'])) {
            $OrderData['prom_type_name'] = '普通訂單';
        }else{
            $OrderData['prom_type_name'] = '虛擬訂單';
        }

        // 移動端查詢物流鏈接
        $MobileExpressUrl = "//m.kuaidi100.com/index_all.html?type=".$OrderData['express_code']."&postid=".$OrderData['express_order'];

        // 載入數據
        $this->assign('MobileExpressUrl', $MobileExpressUrl);
        $this->assign('OrderData', $OrderData);
        $this->assign('DetailsData', $DetailsData);
        $this->assign('UsersData', $UsersData);
        $this->assign('admin_order_status_arr',$admin_order_status_arr);
        $this->assign('pay_method_arr',$pay_method_arr);
    }

    // 檢測並第一次從官方同步訂單中心的前臺模板
    public function ajax_syn_theme_shop()
    {
        $msg = '下載訂單中心模板包異常，請第一時間聯繫技術支援，排查問題！';
        $shopLogic = new ShopLogic;
        $data = $shopLogic->syn_theme_shop();
        if (true !== $data) {
            if (1 <= intval($data['code'])) {
                $this->success('初始化成功！', url('Shop/index'));
            } else {
                if (is_array($data)) {
                    $msg = $data['msg'];
                }
            }
        }
        getUsersConfigData('shop', ['shop_open' => 0]);
        $this->error($msg);
    }
}