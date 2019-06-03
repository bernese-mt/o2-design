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

namespace app\user\model;

use think\Model;
use think\Db;
use think\Config;
use think\Page;

/**
 * 商城
 */
class Shop extends Model
{
    //初始化
    protected function initialize()
    {
        // 需要呼叫`Model`的`initialize`方法
        parent::initialize();
        $this->home_lang = get_home_lang();
    }

    // 處理購買訂單，超過指定時間修改爲已訂單過期，針對未付款訂單
    public function UpdateShopOrderData($users_id){
        $time  = getTime() - Config::get('global.get_shop_order_validity');
        $where = array(
            'users_id'     => $users_id,
            'order_status' => 0,
            'add_time'     => array('<',$time),
        );
        $data = [
            'order_status' => 4, // 訂單取消
            'update_time'  => getTime(),
        ];

        // 查詢訂單id陣列用於新增訂單操作記錄
        $OrderIds = Db::name('shop_order')->field('order_id')->where($where)->select();

        //批量修改訂單狀態 
        Db::name('shop_order')->where($where)->update($data);
        
        // 新增訂單操作記錄
        if (!empty($OrderIds)) {
	        AddOrderAction($OrderIds,$users_id,'0','4','0','0','訂單過期！','會員未在訂單有效期內支付，訂單過期！');
        }
    }

    // 通過商品名稱模糊查詢訂單資訊
    public function QueryOrderList($pagesize,$users_id,$keywords,$query_get){
        // 商品名稱模糊查詢訂單明細表，獲取訂單主表ID
        $DetailsWhere = [
            'users_id' => $users_id,
            'lang'     => $this->home_lang,
        ];
        $DetailsWhere['product_name'] =  ['LIKE', "%{$keywords}%"];
        $DetailsData = Db::name('shop_order_details')->field('order_id')->where($DetailsWhere)->select();
        // 若查無數據，則返回false
        if (empty($DetailsData)) {
            return false;
        }

        $order_ids = '';
        // 處理訂單ID，查詢訂單主表資訊
        foreach ($DetailsData as $key => $value) {
            if ('0' < $key) {
                $order_ids .= ',';
            }
            $order_ids .= $value['order_id'];
        }
        // 查詢條件
        $OrderWhere = [
            'users_id' => $users_id,
            'lang'     => $this->home_lang,
            'order_id' => ['IN', $order_ids],
        ];

        $paginate_type = 'userseyou';
        if (isMobile()) {
            $paginate_type = 'usersmobile';
        }

        $paginate = array(
            'type'     => $paginate_type,
            'var_page' => config('paginate.var_page'),
            'query'    => $query_get,
        );

        $pages = Db::name('shop_order')
            ->field("*")
            ->where($OrderWhere)
            ->order('add_time desc')
            ->paginate($pagesize, false, $paginate);

        $data['list']  = $pages->items();
        $data['pages'] = $pages;

        return $data;
    }

    public function GetOrderIsEmpty($users_id,$keywords,$select_status){
        // 基礎查詢條件
        $OrderWhere = [
            'users_id' => $users_id,
            'lang'     => $this->home_lang,
        ];

        // 應用搜索條件
        if (!empty($keywords)) {
            $OrderWhere['order_code'] =  ['LIKE', "%{$keywords}%"];
        }

        // 訂單狀態搜索
        if (!empty($select_status)) {
            if ('dzf' === $select_status) {
                $select_status = 0;
            }
            $OrderWhere['order_status'] = $select_status;
        }

        $order = Db::name('shop_order')->where($OrderWhere)->count();
        // 查詢存在數據，則返回1
        if (!empty($order)) {
            $data = '1';
            return $data;
            exit;
        }
        
        // 查詢訂單明細表
        if (empty($order) && !empty($keywords)) {
            $DetailsWhere = [
                'users_id' => $users_id,
                'lang'     => $this->home_lang,
            ];
            $DetailsWhere['product_name'] =  ['LIKE', "%{$keywords}%"];
            $DetailsData = Db::name('shop_order_details')->field('order_id')->where($DetailsWhere)->select();
            // 查詢無數據，則返回0
            if (empty($DetailsData)) {
                $data = '0';
                return $data;
                exit;
            }

            $order_ids = '';
            // 處理訂單ID，查詢訂單主表資訊
            foreach ($DetailsData as $key => $value) {
                if ('0' < $key) {
                    $order_ids .= ',';
                }
                $order_ids .= $value['order_id'];
            }
            // 查詢條件
            $OrderWhere = [
                'users_id' => $users_id,
                'lang'     => $this->home_lang,
                'order_id' => ['IN', $order_ids],
            ];

            $order2 = Db::name('shop_order')->where($OrderWhere)->count();
            if (!empty($order2)) {
                $data = '1';
                return $data;
            }else{
                $data = '0';
                return $data;
            }
        }
    }
}