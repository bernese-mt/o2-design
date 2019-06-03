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
 * Date: 2019-2-12
 */

namespace app\admin\controller;

use think\Page;
use think\Db;
use think\Config;
use app\admin\logic\MemberLogic;

class Member extends Base {

    /**
     * 構造方法
     */
    public function __construct(){
        parent::__construct();
        /*會員中心數據表*/
        $this->users_db        = Db::name('users');         // 使用者資訊表
        $this->users_list_db   = Db::name('users_list');    // 使用者資料表
        $this->users_level_db  = Db::name('users_level');   // 使用者等級表
        $this->users_config_db = Db::name('users_config');  // 使用者配置表
        $this->users_money_db  = Db::name('users_money');   // 使用者充值表
        $this->field_type_db   = Db::name('field_type');    // 欄位屬性表
        $this->users_parameter_db = Db::name('users_parameter'); // 使用者屬性表
        /*結束*/

        /*訂單中心數據表*/
        $this->shop_address_db   = Db::name('shop_address');    // 使用者地址表
        $this->shop_cart_db      = Db::name('shop_cart');       // 使用者購物車表
        $this->shop_order_db     = Db::name('shop_order');      // 使用者訂單主表
        $this->shop_order_log_db = Db::name('shop_order_log');  // 使用者訂單操作記錄表
        $this->shop_order_details_db = Db::name('shop_order_details');  // 使用者訂單副表
        /*結束*/

        // 是否開啟支付功能設定
        $UsersConfigData = getUsersConfigData('all');
        $this->assign('userConfig',$UsersConfigData);
    }

    // 使用者列表
    public function users_index()
    {
        $list = array();
        $keywords = input('keywords/s');

        $condition = array();
        // 應用搜索條件
        if (!empty($keywords)) {
            $condition['a.username'] = array('LIKE', "%{$keywords}%");
        }

        $condition['a.is_del'] = 0;
        // 多語言
        $condition['a.lang'] = array('eq', $this->admin_lang);

        /**
         * 數據查詢
         */
        $count = $this->users_db->alias('a')->where($condition)->count();// 查詢滿足要求的總記錄數
        $Page = new Page($count, config('paginate.list_rows'));// 實例化分頁類 傳入總記錄數和每頁顯示的記錄數
        $list = $this->users_db->field('a.*,b.level_name')
            ->alias('a')
            ->join('__USERS_LEVEL__ b', 'a.level = b.level_id', 'LEFT')
            ->where($condition) 
            ->order('a.users_id desc')
            ->limit($Page->firstRow.','.$Page->listRows)
            ->select();

        $show = $Page->show();// 分頁顯示輸出
        $this->assign('page',$show);// 賦值分頁輸出
        $this->assign('list',$list);// 賦值數據集
        $this->assign('pager',$Page);// 賦值分頁集

        /*糾正數據*/
        $web_is_authortoken = tpCache('web.web_is_authortoken');
        (is_realdomain() && !empty($web_is_authortoken)) && getUsersConfigData('shop', ['shop_open'=>0]);
        
        /*檢測是否存在會員中心模板*/
        if ('v1.0.1' > getVersion('version_themeusers')) {
            $is_syn_theme_users = 1;
        } else {
            $is_syn_theme_users = 0;
        }
        $this->assign('is_syn_theme_users',$is_syn_theme_users);
        /*--end*/

        return $this->fetch();
    }

    // 檢測並第一次從官方同步會員中心的前臺模板
    public function ajax_syn_theme_users()
    {
        $msg = '下載會員中心模板包異常，請第一時間聯繫技術支援，排查問題！';
        $memberLogic = new MemberLogic;
        $data = $memberLogic->syn_theme_users();
        if (true !== $data) {
            if (1 <= intval($data['code'])) {
                $this->success('初始化成功！', url('Member/users_index'));
            } else {
                if (is_array($data)) {
                    $msg = $data['msg'];
                }
            }
        }

        /*多語言*/
        if (is_language()) {
            $langRow = \think\Db::name('language')->order('id asc')
                ->cache(true, EYOUCMS_CACHE_TIME, 'language')
                ->select();
            foreach ($langRow as $key => $val) {
                tpCache('web', ['web_users_switch'=>0], $val['mark']);
            }
        } else { // 單語言
            tpCache('web', ['web_users_switch'=>0]);
        }
        /*--end*/

        $this->error($msg);
    }

    // 使用者批量新增
    public function users_batch_add()
    {
        if (IS_POST) {
            $post = input('post.');

            $username = $post['username'];
            if (empty($username)) {
                $this->error('使用者名稱不能為空！');
            }

            if (empty($post['password'])) {
                $this->error('登錄密碼不能為空！');
            }
            
            $password = func_encrypt($post['password']);

            $usernameArr = explode("\r\n", $username);
            $usernameArr = array_filter($usernameArr);//去除陣列空值
            $usernameArr = array_unique($usernameArr); //去重

            $addData = [];
            $usernameList = $this->users_db->where([
                    'username'  => ['IN', $usernameArr],
                    'lang'      => $this->admin_lang,
                ])->column('username');
            foreach ($usernameArr as $key => $val) {
                if(trim($val) == '' || empty($val) || in_array($val, $usernameList) || !preg_match("/^[\x{4e00}-\x{9fa5}\w\-\_\@\#]{2,30}$/u", $val))
                {
                    continue;
                }

                $addData[] = [
                    'username'          => $val,
                    'password'          => $password,
                    'level'             => $post['level'],
                    'register_place'    => 1,
                    'reg_time'          => getTime(),
                    'lang'              => $this->admin_lang,
                    'add_time'          => getTime(),
                ];
            }
            if (!empty($addData)) {
                $r = model('Member')->saveAll($addData);
                if (!empty($r)) {
                    adminLog('批量新增使用者：'.get_arr_column($addData, 'username'));
                    $this->success('操作成功！', url('Member/users_index'));
                } else {
                    $this->error('操作失敗');
                }
            } else {
                $this->success('操作成功！', url('Member/users_index'));
            }
        }

        $user_level = $this->users_level_db->field('level_id,level_name')
            ->where(['lang'=>$this->admin_lang])
            ->order('level_value asc')
            ->select();
        $this->assign('user_level',$user_level);

        return $this->fetch();
    }

    // 使用者新增
    // public function users_add()
    // {
    //     if (IS_POST) {
    //         $post = input('post.');

    //         $count = $this->users_db->where([
    //                 'username'  => $post['username'],
    //                 'lang'      => $this->admin_lang,
    //             ])->count();
    //         if (!empty($count)) {
    //             $this->error('使用者名稱已存在！');
    //         }

    //         if (empty($post['password']) && empty($post['password2'])) {
    //             $this->error('登錄密碼不能為空！');
    //         } else {
    //             if ($post['password'] != $post['password2']) {
    //                 $this->error('兩次密碼輸入不一致！');
    //             }
    //         }

    //         $ParaData = [];
    //         if (is_array($post['users_'])) {
    //             $ParaData = $post['users_'];
    //         }
    //         unset($post['users_']);
    //         // 處理提交的使用者屬性中必填項是否為空
    //         // 必須傳入提交的使用者屬性陣列
    //         $EmptyData = model('Member')->isEmpty($ParaData);
    //         if ($EmptyData) {
    //             $this->error($EmptyData);
    //         }
            
    //         // 處理提交的使用者屬性中郵箱和手機是否已存在
    //         // isRequired方法傳入的參數有2個
    //         // 第一個必須傳入提交的使用者屬性陣列
    //         // 第二個users_id，註冊時不需要傳入，修改時需要傳入。
    //         $RequiredData = model('Member')->isRequired($ParaData);
    //         if ($RequiredData) {
    //             $this->error($RequiredData);
    //         }

    //         $post['password'] = func_encrypt($post['password']);// MD5加密
    //         unset($post['password2']);

    //         $post['register_place'] = 1; // 註冊位置，後臺註冊不受註冊驗證影響，1為後臺註冊，2為前臺註冊。
    //         $post['reg_time'] = getTime();
    //         $post['lang'] = $this->admin_lang;
    //         $users_id = $this->users_db->add($post);
    //         // 判斷使用者新增是否成功
    //         if (!empty($users_id)) {
    //             // 批量新增使用者屬性到屬性資訊表
    //             if (!empty($ParaData)) {
    //                 $betchData = [];
    //                 $usersparaRow = $this->users_parameter_db->where([
    //                         'lang'  => $this->admin_lang,
    //                         'is_hidden' => 0,
    //                     ])->getAllWithIndex('name');
    //                 foreach ($ParaData as $key => $value) {
    //                     $para_id = intval($usersparaRow[$key]['para_id']);
    //                     $betchData[] = [
    //                         'users_id'  => $users_id,
    //                         'para_id'   => $para_id,
    //                         'info'      => $value,
    //                         'lang'      => $this->admin_lang,
    //                         'add_time'  => getTime(),
    //                     ];
    //                 }
    //                 $this->users_list_db->insertAll($betchData);
    //             }

    //             // 查詢屬性表的手機號碼和郵箱地址，同步修改使用者資訊。
    //             $UsersListData = model('Member')->getUsersListData('*',$users_id);
    //             $UsersListData['update_time'] = getTime(); 
    //             $this->users_db->where([
    //                     'users_id'  => $users_id,
    //                     'lang'      => $this->admin_lang,
    //                 ])->update($UsersListData);

    //             adminLog('新增使用者：'.$post['username']);
    //             $this->success('操作成功！', url('Member/users_index'));
    //         }else{
    //             $this->error('操作失敗');
    //         }
    //     }

    //     $user_level = $this->users_level_db->field('level_id,level_name')
    //         ->where(['lang'=>$this->admin_lang])
    //         ->order('level_value asc')
    //         ->select();
    //     $this->assign('user_level',$user_level);

    //     // 資料資訊
    //     $users_para = model('Member')->getDataPara();
    //     $this->assign('users_para',$users_para);

    //     return $this->fetch();
    // }

    // 使用者編輯
    public function users_edit()
    {
        if (IS_POST) {
            $post = input('post.');

            if (isset($post['users_money'])) {
                $post['users_money'] = input('post.users_money/f');
            }

            if (!empty($post['password'])) {
                $post['password'] = func_encrypt($post['password']); // MD5加密
            } else {
                unset($post['password']);
            }

            $users_id = $post['users_id'];
            $ParaData = [];
            if (is_array($post['users_'])) {
                $ParaData = $post['users_'];
            }
            unset($post['users_']);

            // 處理提交的使用者屬性中必填項是否為空
            // 必須傳入提交的使用者屬性陣列
            /*$EmptyData = model('Member')->isEmpty($ParaData);
            if ($EmptyData) {
                $this->error($EmptyData);
            }*/
            
            // 處理提交的使用者屬性中郵箱和手機是否已存在
            // isRequired方法傳入的參數有2個
            // 第一個必須傳入提交的使用者屬性陣列
            // 第二個users_id，註冊時不需要傳入，修改時需要傳入。
            $RequiredData = model('Member')->isRequired($ParaData,$users_id);
            if ($RequiredData) {
                $this->error($RequiredData);
            }

            $users_where = [
                'users_id' => $users_id,
                'lang'     => $this->admin_lang,
            ];
            $userinfo = $this->users_db->where($users_where)->find();

            $post['update_time'] = getTime();
            unset($post['username']);
            $r = $this->users_db->where($users_where)->update($post);

            if ($r) {
                $row2 = $this->users_parameter_db->field('para_id,name')->getAllWithIndex('name');
                foreach ($ParaData as $key => $value) {
                    $data    = [];
                    $para_id = intval($row2[$key]['para_id']);
                    $where   = [
                        'users_id' => $post['users_id'],
                        'para_id'  => $para_id,
                        'lang'     => $this->admin_lang,
                    ];
                    $data['info']        = $value;
                    $data['update_time'] = getTime();

                    // 若資訊表中無數據則新增
                    $row = $this->users_list_db->where($where)->count();
                    if (empty($row)) {
                        $data['users_id'] = $post['users_id'];
                        $data['para_id']  = $para_id;
                        $data['lang']     = $this->admin_lang;
                        $data['add_time'] = getTime();
                        $this->users_list_db->add($data);
                    } else {
                        $this->users_list_db->where($where)->update($data);
                    }
                }

                // 查詢屬性表的手機號碼和郵箱地址，同步修改使用者資訊。
                $UsersListData = model('Member')->getUsersListData('*',$users_id);
                $UsersListData['update_time'] = getTime(); 
                $this->users_db->where($users_where)->update($UsersListData);

                adminLog('編輯使用者：'.$userinfo['username']);
                $this->success('操作成功', url('Member/users_index'));
            }else{
                $this->error('操作失敗');
            }
        }

        $users_id = input('param.id/d');

        // 使用者資訊
        $info = $this->users_db->where([
                'users_id'  => $users_id,
                'lang'      => $this->admin_lang,
            ])->find();
        $this->assign('info',$info);

        // 等級資訊
        $level = $this->users_level_db->field('level_id,level_name')
            ->where(['lang'=>$this->admin_lang])
            ->order('level_value asc')
            ->select();
        $this->assign('level',$level);

        // 屬性資訊
        $users_para = model('Member')->getDataParaList($users_id);
        $this->assign('users_para',$users_para);

        // 上一個頁面來源
        $from = input('param.from/s');
        if ('money_index' == $from) {
            $backurl = url('Member/money_index');
        } else {
            $backurl = url('Member/users_index');
        }
        $this->assign('backurl', $backurl);

        return $this->fetch();
    }

    // 使用者刪除
    public function users_del()
    {
        $users_id = input('del_id/a');
        $users_id = eyIntval($users_id);
        if (IS_AJAX_POST && !empty($users_id)) {
            // 刪除統一條件
            $Where = [
                'users_id'  => ['IN', $users_id],
                'lang'      => $this->admin_lang,
            ];

            $result = $this->users_db->field('username')->where($Where)->select();
            $username_list = get_arr_column($result, 'username');

            $return = $this->users_db->where($Where)->delete();
            if ($return) {
                /*刪除會員中心關聯數據表*/
                // 刪除使用者下的屬性
                $this->users_list_db->where($Where)->delete();
                // 刪除使用者下的屬性
                $this->users_money_db->where($Where)->delete();
                /*結束*/

                /*刪除訂單中心關聯數據表*/
                // 刪除使用者下的購物車表
                $this->shop_cart_db->where($Where)->delete();
                // 刪除使用者下的收貨地址表
                $this->shop_address_db->where($Where)->delete();
                // 刪除使用者下的訂單主表
                $this->shop_order_db->where($Where)->delete();
                // 刪除使用者下的訂單副表
                $this->shop_order_log_db->where($Where)->delete();
                // 刪除使用者下的訂單操作記錄表
                $this->shop_order_details_db->where($Where)->delete();
                /*結束*/

                adminLog('刪除使用者：'.implode(',', $username_list));
                $this->success('刪除成功');
            }else{
                $this->error('刪除失敗');
            }
        }
        $this->error('參數有誤');
    }

    // 級別列表
    public function level_index()
    {
        $list = array();
        $keywords = input('keywords/s');

        $condition = array();
        // 應用搜索條件
        if (!empty($keywords)) {
            $condition['a.level_name'] = array('LIKE', "%{$keywords}%");
        }
        // 多語言
        $condition['a.lang'] = array('eq', $this->admin_lang);

        /**
         * 數據查詢
         */
        $count = $this->users_level_db->alias('a')->where($condition)->count();// 查詢滿足要求的總記錄數
        $Page = new Page($count, config('paginate.list_rows'));// 實例化分頁類 傳入總記錄數和每頁顯示的記錄數
        $list = $this->users_level_db->field('a.*')
            ->alias('a')
            ->where($condition)
            ->order('a.level_value asc, a.level_id asc')
            ->limit($Page->firstRow.','.$Page->listRows)
            ->select();

        $show = $Page->show();// 分頁顯示輸出
        $this->assign('page',$show);// 賦值分頁輸出
        $this->assign('list',$list);// 賦值數據集
        $this->assign('pager',$Page);// 賦值分頁集

        // 用於判斷是否可以刪除使用者級別，當用戶級別下存在使用者時，不可刪除。
        $levelgroup = $this->users_db->field('level')
            ->where(['lang'=>$this->admin_lang])
            ->group('level')
            ->getAllWithIndex('level');
        $this->assign('levelgroup',$levelgroup);

        return $this->fetch();
    }

    // 級別 - 新增
    public function level_add()
    {   
        if (IS_POST) {
            $post = input('post.');
            $post['level_name'] = trim($post['level_name']);
            $post['level_value'] = intval(trim($post['level_value']));

            $levelRow = $this->users_level_db->field('level_name,level_value')
                ->where(['lang'=>$this->admin_lang])
                ->select();
            foreach ($levelRow as $key => $val) {
                if ($val['level_name'] == $post['level_name']) {
                    $this->error('級別名稱已存在！');
                } else if (intval($val['level_value']) == $post['level_value']) {
                    $this->error('使用者等級值不能重複！');
                }
            }

            $newData = [
                'level_value'   => intval($post['level_value']),
                'lang'  => $this->admin_lang,
                'add_time'  => getTime(),
            ];
            $data = array_merge($post, $newData);
            $r = $this->users_level_db->add($data);
            if ($r) {
                adminLog('新增使用者級別：'.$data['level_name']);
                $this->success('操作成功', url('Member/level_index'));
            } else {
                $this->error('操作失敗');
            }
        }

        return $this->fetch();
    }

    // 級別 - 編輯
    public function level_edit()
    {
        if (IS_POST) {
            $post = input('post.');
            $post['level_name'] = trim($post['level_name']);
            $post['level_value'] = intval(trim($post['level_value']));

            $levelRow = $this->users_level_db->field('level_name,level_value')
                ->where([
                    'level_id'      => ['NEQ', $post['level_id']],
                    'lang'      => $this->admin_lang,
                ])
                ->select();
            foreach ($levelRow as $key => $val) {
                if ($val['level_name'] == $post['level_name']) {
                    $this->error('級別名稱已存在！');
                } else if (intval($val['level_value']) == $post['level_value']) {
                    $this->error('使用者等級值不能重複！');
                }
            }

            $newData = [
                'level_value'   => intval($post['level_value']),
                'update_time'  => getTime(),
            ];
            $data = array_merge($post, $newData);
            $r = $this->users_level_db->where([
                    'level_id'  => $post['level_id'],
                    'lang'      => $this->admin_lang,
                ])->update($data);
            if ($r) {
                adminLog('編輯使用者級別：'.$data['level_name']);
                $this->success('操作成功', url('Member/level_index'));
            } else {
                $this->error('操作失敗');
            }
        }

        $id = input('get.id/d');

        $info = $this->users_level_db->where([
                'level_id'  => $id,
                'lang'  => $this->admin_lang,
            ])->find();
        $this->assign('info',$info);

        return $this->fetch();
    }

    // 級別 - 刪除
    public function level_del()
    {
        $level_id = input('del_id/a');
        $level_id = eyIntval($level_id);

        if (IS_AJAX_POST && !empty($level_id)) {
            $result = $this->users_level_db->field('level_name,is_system')
                ->where([
                    'level_id'  => ['IN', $level_id],
                    'lang'      => $this->admin_lang,
                ])
                ->select();
            $level_name_list = get_arr_column($result, 'level_name');

            foreach ($result as $val) {
                if (1 == intval($val['is_system'])) {
                    $this->error('系統內建，不可刪除！');
                }
            }

            $info = $this->users_db->where([
                    'level' => ['IN', $level_id],
                    'lang'  => $this->admin_lang,
                ])->count();
            if (!empty($info)) {
                $this->error('選中的級別存在使用者，不可刪除！');
            }

            $return = $this->users_level_db->where([
                    'level_id'  => ['IN', $level_id],
                    'lang'      => $this->admin_lang,
                ])->delete();
            if ($return) {
                adminLog('刪除使用者級別：'.implode(',', $level_name_list));
                $this->success('刪除成功');
            }else{
                $this->error('刪除失敗');
            }
        }
        $this->error('參數有誤');
    }

    // 屬性列表
    public function attr_index()
    {
        //屬性數據
        $info = $this->users_parameter_db->field('a.*,a.title,b.title as dtypetitle')
            ->alias('a')
            ->join('__FIELD_TYPE__ b', 'a.dtype = b.name', 'LEFT')
            ->order('a.is_system desc,a.sort_order asc,a.para_id desc')
            ->where('a.lang',$this->admin_lang)
            ->select();
        foreach ($info as $key => $value) {
            if ('email' == $value['dtype']) {
                $info[$key]['dtypetitle'] = '郵箱地址';
            } else if ('mobile' == $value['dtype']) {
                $info[$key]['dtypetitle'] = '手機號碼';
            }
        }
        $this->assign('info',$info);
        return $this->fetch();
    }

    // 屬性新增
    public function attr_add()
    {   
        if (IS_POST) {
            $post = input('post.');
            $post['title'] = trim($post['title']);

            if (empty($post['title'])) {
                $this->error('屬性標題不能為空！');
            }
            if (empty($post['dtype'])) {
                $this->error('請選擇屬性型別！');
            }

            $count = $this->users_parameter_db->where([
                    'title'=>$post['title']
                ])->count();
            if (!empty($count)) {
                $this->error('屬性標題已存在！');
            }

            $post['dfvalue']     = str_replace('，', ',', $post['dfvalue']);
            $post['dfvalue'] = trim($post['dfvalue'], ',');
            $post['add_time'] = getTime();
            $post['lang']        = $this->admin_lang;
            $post['sort_order'] = '100';
            $para_id = $this->users_parameter_db->insertGetId($post);
            if (!empty($para_id)) {
                $name = 'para_'.$para_id;
                $return = $this->users_parameter_db->where('para_id',$para_id)
                    ->update([
                        'name'  => $name,
                        'update_time'   => getTime(),
                    ]);
                if ($return) {
                    adminLog('新增使用者屬性：'.$post['title']);
                    $this->success('操作成功',url('Member/attr_index'));
                }
            }
            $this->error('操作失敗');
        }

        $field = $this->field_type_db->field('name,title,ifoption')
            ->where([
                'name'  => ['IN', ['text','checkbox','multitext','radio','select']]
            ])
            ->select();
        $this->assign('field',$field);
        return $this->fetch();
    }

    // 屬性修改
    public function attr_edit()
    {
        $para_id = input('param.id/d');

        if (IS_POST && !empty($para_id)) {
            $post = input('post.');
            $post['title'] = trim($post['title']);

            if (empty($post['title'])) {
                $this->error('屬性標題不能為空！');
            }
            if (empty($post['dtype'])) {
                $this->error('請選擇屬性型別！');
            }

            $count = $this->users_parameter_db->where([
                    'title'     => $post['title'],
                    'para_id'   => ['NEQ', $post['para_id']],
                ])->count();
            if ($count) {
                $this->error('屬性標題已存在！');
            }

            $post['dfvalue'] = str_replace('，', ',', $post['dfvalue']);
            $post['dfvalue'] = trim($post['dfvalue'], ',');
            $post['update_time'] = getTime();
            $return = $this->users_parameter_db->where([
                    'para_id'   => $para_id,
                    'lang'      => $this->admin_lang,
                ])->update($post);
            if ($return) {
                adminLog('編輯使用者屬性：'.$post['title']);
                $this->success('操作成功',url('Member/attr_index'));
            }else{
                $this->error('操作失敗');
            }
        }

        $info = $this->users_parameter_db->where([
                'para_id'   => $para_id,
                'lang'      => $this->admin_lang,
            ])->find();
        $this->assign('info',$info);

        $field = $this->field_type_db->field('name,title,ifoption')
            ->where([
                'name'  => ['IN', ['text','checkbox','multitext','radio','select']]
            ])
            ->select();
        $this->assign('field',$field);

        return $this->fetch();
    }

    // 屬性刪除
    public function attr_del()
    {
        $para_id = input('del_id/a');
        $para_id = eyIntval($para_id);

        if (IS_AJAX_POST && !empty($para_id)) {
            $result = $this->users_parameter_db->field('title')
                ->where([
                    'para_id'  => ['IN', $para_id],
                    'lang'      => $this->admin_lang,
                ])
                ->select();
            $title_list = get_arr_column($result, 'title');

            // 刪除使用者屬性表數據
            $return = $this->users_parameter_db->where([
                    'para_id'  => ['IN', $para_id],
                    'lang'      => $this->admin_lang,
                ])->delete();

            if ($return) {
                // 刪除使用者屬性資訊表數據
                $this->users_list_db->where([
                        'para_id'  => ['IN', $para_id],
                        'lang'      => $this->admin_lang,
                    ])->delete();
                adminLog('刪除使用者屬性：'.implode(',', $title_list));
                $this->success('刪除成功');
            }else{
                $this->error('刪除失敗');
            }
        }
        $this->error('參數有誤');
    }

    // 功能設定
    public function users_config()
    {
        if (IS_POST) {
            $post = input('post.');

            /*商城入口*/
            $shop_open = $post['shop']['shop_open'];
            $shop_open_old = getUsersConfigData('shop.shop_open');
            /*--end*/

            // 郵件驗證的檢測
            if (2 == $post['users']['users_verification']) {
                $users_config_email = $this->users_config_email();
                if (!empty($users_config_email)) {
                   $this->error($users_config_email);
                }
            }
            // 第三方登錄
            if (1 == $post['oauth']['oauth_open']) {
                empty($post['oauth']['oauth_qq']) && $post['oauth']['oauth_qq'] = 0;
                empty($post['oauth']['oauth_weixin']) && $post['oauth']['oauth_weixin'] = 0;
                empty($post['oauth']['oauth_weibo']) && $post['oauth']['oauth_weibo'] = 0;
            }
            foreach ($post as $key => $val) {
                getUsersConfigData($key, $val);
            }
            $this->success('操作成功');
        }

        // 獲取使用者配置資訊
        $UsersConfigData = getUsersConfigData('all');
        $this->assign('info',$UsersConfigData);

        /*檢測是否存在訂單中心模板*/
        if ('v1.0.1' > getVersion('version_themeshop') && !empty($UsersConfigData['shop_open'])) {
            $is_syn_theme_shop = 1;
        } else {
            $is_syn_theme_shop = 0;
        }
        $this->assign('is_syn_theme_shop',$is_syn_theme_shop);
        /*--end*/

        return $this->fetch();
    }

    // 第三方登錄配置
    public function ajax_set_oauth_config()
    {
        $oauth = input('param.oauth/s', 'qq');

        return $this->fetch();
    }

    // 郵件驗證的檢測
    public function ajax_users_config_email()
    {   
        if (IS_AJAX) {
            // 郵件驗證的檢測
            $users_config_email = $this->users_config_email();
            if (!empty($users_config_email)) {
               $this->error($users_config_email);
            }
            $this->success('檢驗通過');
        }
        $this->error('參數有誤');
    }
        
    private function users_config_email(){
        // 使用者屬性資訊
        $where = array(
            'name'      => ['LIKE', "email_%"],
            'lang'      => $this->admin_lang,
            'is_system' => 1,
        );
        // 是否要為必填項
        $param = $this->users_parameter_db->where($where)->field('title,is_hidden')->find();
        if (empty($param) || 1 == $param['is_hidden']) {
            return "請先把使用者屬性的<font color='red'>{$param['title']}</font>設定為顯示，且為必填項！";
        }

        $param = $this->users_parameter_db->where($where)->field('title,is_required')->find();
        if (empty($param) || 0 == $param['is_required']) {
            return "請先把使用者屬性的<font color='red'>{$param['title']}</font>設定為必填項！";
        }

        // 是否開啟郵箱發送擴充套件
        $openssl_funcs = get_extension_funcs('openssl');
        if (!$openssl_funcs) {
            return "請聯繫空間商，開啟php的 <font color='red'>openssl</font> 擴充套件！";
        }

        $send_email_scene = config('send_email_scene');
        $scene = $send_email_scene[2]['scene'];

        // 自動啟用註冊郵件模板
        Db::name('smtp_tpl')->where([
                'send_scene'    => $scene,
                'lang'          => $this->admin_lang,
            ])->update([
                'is_open'       => 1,
                'update_time'   => getTime(),
            ]);

        // 是否填寫郵件配置
        $smtp_config = tpCache('smtp');
        foreach ($smtp_config as $val) {
            if (empty($val)) {
                return "請先完善<font color='red'>(郵件配置)</font>，具體步驟【基本資訊】->【介面配置】->【郵件配置】";
            }
        }

        return '';
    }

    // 支付方式配置
    public function pay_set(){
        $payConfig = getUsersConfigData('pay');

        /*微信支付配置*/
        $wechat = !empty($payConfig['pay_wechat_config']) ? $payConfig['pay_wechat_config'] : [];
        $this->assign('wechat',unserialize($wechat));
        /*--end*/

        /*支付寶支付配置*/
        $alipay = !empty($payConfig['pay_alipay_config']) ? $payConfig['pay_alipay_config'] : [];
        $this->assign('alipay',unserialize($alipay));
        if (version_compare(PHP_VERSION,'5.5.0','<')) {
            $php_version = 1; // PHP5.4.0或更低版本，可使用舊版支付方式
        }else{
            $php_version = 0;// PHP5.5.0或更高版本，可使用新版支付方式，相容舊版支付方式
        }
        $this->assign('php_version',$php_version);
        /*--end*/

        return $this->fetch();
    }
    
    // 微信配信資訊
    public function wechat_set(){
        if (IS_POST) {
            $post = input('post.');
            if (empty($post['wechat']['appid'])) {
                $this->error('微信AppId不能為空！');
            }
            if (empty($post['wechat']['mchid'])) {
                $this->error('微信MchId不能為空！');
            }
            if (empty($post['wechat']['key'])) {
                $this->error('微信KEY值不能為空！');
            }

            $data = model('Pay')->payForQrcode($post['wechat']);
            if ($data['return_code'] == 'FAIL') {
                if ('簽名錯誤' == $data['return_msg']) {
                    $this->error('微信KEY值錯誤！');
                }else if ('appid不存在' == $data['return_msg']) {
                    $this->error('微信AppId錯誤！');
                }else if ('商戶號mch_id或sub_mch_id不存在' == $data['return_msg']) {
                    $this->error('微信MchId錯誤！');
                }
            }

            foreach ($post as $key => $val) {
                getUsersConfigData('pay', ['pay_wechat_config'=>serialize($val)]);
            }
            $this->success('操作成功');
        }
    }

    // 支付寶配信資訊
    public function alipay_set(){
        if (IS_POST) {
            $post = input('post.');
            $php_version = $post['alipay']['version'];
            if (0 == $php_version) {
                if (empty($post['alipay']['app_id'])) {
                    $this->error('支付APPID不能為空！');
                }
                if (empty($post['alipay']['merchant_private_key'])) {
                    $this->error('商戶私鑰不能為空！');
                }
                if (empty($post['alipay']['alipay_public_key'])) {
                    $this->error('支付寶公鑰不能為空！');
                }

                $order_number = getTime();
                $return = $this->check_alipay_order($order_number,'admin_pay',$post['alipay']);
                if ('ok' != $return) {
                    $this->error($return);
                }
            }else if (1 == $php_version) {
                if (empty($post['alipay']['account'])) {
                    $this->error('支付寶賬號不能為空！');
                }
                if (empty($post['alipay']['code'])) {
                    $this->error('交易安全校驗碼不能為空！');
                }
                if (empty($post['alipay']['id'])) {
                    $this->error('合作者身份ID不能為空！');
                }
            }

            // 處理數據中的空格和換行
            $post['alipay']['app_id']               = preg_replace('/\r|\n/', '', $post['alipay']['app_id']);
            $post['alipay']['merchant_private_key'] = preg_replace('/\r|\n/', '', $post['alipay']['merchant_private_key']);
            $post['alipay']['alipay_public_key']    = preg_replace('/\r|\n/', '', $post['alipay']['alipay_public_key']);

            foreach ($post as $key => $val) {
                getUsersConfigData('pay', ['pay_alipay_config'=>serialize($val)]);
            }
            $this->success('操作成功');
        }
    }

    // 充值記錄列表
    public function money_index()
    {
        $list = array();
        $keywords = input('keywords/s');

        $condition = array();
        // 應用搜索條件
        if (!empty($keywords)) {
            $condition['a.order_number'] = array('LIKE', "%{$keywords}%");
        }

        // 多語言
        $condition['a.lang'] = array('eq', $this->admin_lang);

        /**
         * 數據查詢
         */
        $count = $this->users_money_db->alias('a')->where($condition)->count();// 查詢滿足要求的總記錄數
        $Page = new Page($count, config('paginate.list_rows'));// 實例化分頁類 傳入總記錄數和每頁顯示的記錄數
        $list = $this->users_money_db->field('a.*,b.username')
            ->alias('a')
            ->join('__USERS__ b', 'a.users_id = b.users_id', 'LEFT')
            ->where($condition) 
            ->order('a.moneyid desc')
            ->limit($Page->firstRow.','.$Page->listRows)
            ->select();

        $show = $Page->show();// 分頁顯示輸出
        $this->assign('page',$show);// 賦值分頁輸出
        $this->assign('list',$list);// 賦值數據集
        $this->assign('pager',$Page);// 賦值分頁集

        // 訂單型別
        $pay_cause_type_arr = config('global.pay_cause_type_arr');
        $this->assign('pay_cause_type_arr',$pay_cause_type_arr);

        // 充值狀態
        $pay_status_arr = config('global.pay_status_arr');
        $this->assign('pay_status_arr',$pay_status_arr);

        // 支付方式
        $pay_method_arr = config('global.pay_method_arr');
        $this->assign('pay_method_arr',$pay_method_arr);

        return $this->fetch();
    }

    // 充值記錄編輯
    public function money_edit()
    {   
        $param = input('param.');
        $MoneyData = $this->users_money_db->find($param['moneyid']);
        $this->assign('MoneyData',$MoneyData);
        $UsersData = $this->users_db->find($MoneyData['users_id']);
        $this->assign('UsersData',$UsersData);
        
        // 支付寶查詢訂單
        if ('alipay' == $MoneyData['pay_method']) {
            $return = $this->check_alipay_order($MoneyData['order_number']);
            $this->assign('return',$return);
        }

        // 微信查詢訂單
        if ('wechat' == $MoneyData['pay_method']) {
            $return = $this->check_wechat_order($MoneyData['order_number']);
            $this->assign('return',$return);
        }

        // 人為處理訂單
        if ('artificial' == $MoneyData['pay_method']) {
            $return = '人為處理';
            $this->assign('return',$return);
        }

        // 獲取訂單狀態
        $pay_status_arr = Config::get('global.pay_status_arr');
        $this->assign('pay_status_arr',$pay_status_arr);

        // 支付方式
        $pay_method_arr = config('global.pay_method_arr');
        $this->assign('pay_method_arr',$pay_method_arr);

        return $this->fetch();
    }

    // 標記訂單邏輯
    public function money_mark_order()
    {
        if (IS_POST) {
            $moneyid     = input('param.moneyid/d');

            // 查詢訂單資訊
            $MoneyData = $this->users_money_db->where([
                'moneyid'     => $moneyid,
                'lang'         => $this->admin_lang,
            ])->find();

            // 處理訂單邏輯
            if (in_array($MoneyData['status'], [1,3])) {

                $users_id = $MoneyData['users_id'];
                $order_number = $MoneyData['order_number'];
                $return = '';
                if ('alipay' == $MoneyData['pay_method']) { // 支付寶查詢訂單
                    $return = $this->check_alipay_order($order_number);
                } else if ('wechat' == $MoneyData['pay_method']) { // 微信查詢訂單
                    $return = $this->check_wechat_order($order_number);
                } else if ('artificial' == $MoneyData['pay_method']) { // 手工充值訂單
                    $return = '手工充值';
                }
                
                $result = [
                    'users_id'    => $users_id,
                    'order_number'=> $order_number,
                    'status'      => '手動標記為已充值訂單',
                    'details'     => '充值詳情：'.$return,
                    'pay_method'  => 'artificial', //人為處理
                    'money'       => $MoneyData['money'],
                    'users_money' => $MoneyData['users_money'],
                ];

                // 標記為未付款
                if (3 == $MoneyData['status']) {
                    $result['status'] = '手動標記為未付款訂單';
                } else if (1 == $MoneyData['status']) {
                    $result['status'] = '手動標記為已充值訂單';
                }

                // 修改使用者充值明細表對應的訂單數據，存入返回的數據，訂單標記為已付款
                $Where = [
                    'moneyid'  => $MoneyData['moneyid'],
                    'users_id'  => $users_id,
                ];
                
                $UpdateData = [
                    'pay_details'   => serialize($result),
                    'update_time'   => getTime(),
                ];

                // 標記為未付款時則狀態更新為1
                if (3 == $MoneyData['status']) {
                    $UpdateData['status'] = 1;
                } else if (1 == $MoneyData['status']) {
                    $UpdateData['status'] = 3;
                }

                $IsMoney = $this->users_money_db->where($Where)->update($UpdateData);

                if (!empty($IsMoney)) {
                    // 同步修改使用者的金額
                    $UsersData = [
                        'update_time' => getTime(),
                    ];

                    // 標記為未付款時則減去金額
                    if (3 == $MoneyData['status']) {
                        $UsersData = $this->users_db->field('users_money')->find($users_id);
                        if ($UsersData['users_money'] <= $MoneyData['money']) {
                            $UsersData['users_money'] = 0;
                        }else{
                            $UsersData['users_money'] = Db::raw('users_money-'.$MoneyData['money']);
                        }
                    } else if (1 == $MoneyData['status']) {
                        $UsersData['users_money'] = Db::raw('users_money+'.$MoneyData['money']);
                    }

                    $IsUsers = $this->users_db->where('users_id',$users_id)->update($UsersData);
                    if (!empty($IsUsers)) {
                        $this->success('操作成功');
                    }
                }
            }
            $this->error('操作失敗');
        }
    }

    // 查詢訂單付款狀態(微信)
    private function check_wechat_order($order_number)
    {
        if (!empty($order_number)) {
            // 引入檔案
            vendor('wechatpay.lib.WxPayApi');
            vendor('wechatpay.lib.WxPayConfig');
            // 實例化載入訂單號
            $input  = new \WxPayOrderQuery;
            $input->SetOut_trade_no($order_number);

            // 處理微信配置數據
            $pay_wechat_config = getUsersConfigData('pay.pay_wechat_config');
            $pay_wechat_config = unserialize($pay_wechat_config);
            $config_data['app_id'] = $pay_wechat_config['appid'];
            $config_data['mch_id'] = $pay_wechat_config['mchid'];
            $config_data['key']    = $pay_wechat_config['key'];

            // 實例化微信配置
            $config = new \WxPayConfig($config_data);
            $wxpayapi = new \WxPayApi;

            // 返回結果
            $result = $wxpayapi->orderQuery($config, $input);

            // 判斷結果
            if ('ORDERNOTEXIST' == $result['err_code'] && 'FAIL' == $result['result_code']) {
                return '訂單在微信中不存在！';
            }else if ('NOTPAY' == $result['trade_state'] && 'SUCCESS' == $result['result_code']) {
                return '訂單在微信中產生，但並未支付完成！';
            }else if ('SUCCESS' == $result['trade_state'] && 'SUCCESS' == $result['result_code']) {
                return '訂單已使用'.$result['attach'].'完成！';
            }
        }else{
            return false;
        }
    }

    // 查詢訂單付款狀態(支付寶)
    private function check_alipay_order($order_number,$admin_pay='',$alipay='')
    {
        if (!empty($order_number)) {
            // 引入檔案
            vendor('alipay.pagepay.service.AlipayTradeService');
            vendor('alipay.pagepay.buildermodel.AlipayTradeQueryContentBuilder');

            // 實例化載入訂單號
            $RequestBuilder = new \AlipayTradeQueryContentBuilder;
            $out_trade_no   = trim($order_number);
            $RequestBuilder->setOutTradeNo($out_trade_no);

            // 處理支付寶配置數據
            if (empty($alipay)) {
                $pay_alipay_config = getUsersConfigData('pay.pay_alipay_config');
                if (empty($pay_alipay_config)) {
                    return false;
                }
                $alipay = unserialize($pay_alipay_config);
            }
            $config['app_id']     = $alipay['app_id'];
            $config['merchant_private_key'] = $alipay['merchant_private_key'];
            $config['charset']    = 'UTF-8';
            $config['sign_type']  = 'RSA2';
            $config['gatewayUrl'] = 'https://openapi.alipay.com/gateway.do';
            $config['alipay_public_key'] = $alipay['alipay_public_key'];

            // 實例化支付寶配置
            $aop = new \AlipayTradeService($config);

            // 返回結果
            if (!empty($admin_pay)) {
                $result = $aop->IsQuery($RequestBuilder,$admin_pay);
            }else{
                $result = $aop->Query($RequestBuilder);
            }

            $result = json_decode(json_encode($result),true);

            // 判斷結果
            if ('40004' == $result['code'] && 'Business Failed' == $result['msg']) {
                // 用於支付寶支付配置驗證
                if (!empty($admin_pay)) { return 'ok'; }
                // 用於訂單查詢
                return '訂單在支付寶中不存在！';
            }else if ('10000' == $result['code'] && 'WAIT_BUYER_PAY' == $result['trade_status']) {
                return '訂單在支付寶中產生，但並未支付完成！';
            }else if ('10000' == $result['code'] && 'TRADE_SUCCESS' == $result['trade_status']) {
                return '訂單已使用支付寶支付完成！';
            }

            // 用於支付寶支付配置驗證
            if (!empty($admin_pay) && !empty($result)) {
                if ('40001' == $result['code'] && 'Missing Required Arguments' == $result['msg']) {
                    return '商戶私鑰錯誤！';
                }
                if (!is_array($result)) {
                    return $result;
                }
            }
        }
    }

    /**
     * 版本檢測更新彈窗
     */
    public function ajax_check_upgrade_version()
    {
        $memberLogic = new MemberLogic;
        $upgradeMsg = $memberLogic->checkVersion(); // 升級包訊息
        $this->success('檢測成功', null, $upgradeMsg);  
    }

    /**
    * 一鍵升級
    */
    public function OneKeyUpgrade(){
        header('Content-Type:application/json; charset=utf-8');
        function_exists('set_time_limit') && set_time_limit(0);

        /*許可權控制 by 小虎哥*/
        $auth_role_info = session('admin_info.auth_role_info');
        if(0 < intval(session('admin_info.role_id')) && ! empty($auth_role_info) && intval($auth_role_info['online_update']) <= 0){
            $this->error('您沒有操作許可權，請聯繫超級管理員分配許可權');
        }
        /*--end*/

        $memberLogic = new MemberLogic;
        $data = $memberLogic->OneKeyUpgrade(); //升級包訊息
        if (1 <= intval($data['code'])) {
            $this->success($data['msg'], null, ['code'=>$data['code']]);
        } else {
            $msg = '模板升級異常，請第一時間聯繫技術支援，排查問題！';
            if (is_array($data)) {
                $msg = $data['msg'];
            }
            $this->error($msg);
        }
    }

    /**
    * 檢測目錄許可權
    */
    public function check_authority()
    {
        $filelist = input('param.filelist/s');
        $memberLogic = new MemberLogic;
        $data = $memberLogic->checkAuthority($filelist); //檢測目錄許可權
        if (is_array($data)) {
            if (1 == $data['code']) {
                $this->success($data['msg']);
            } else {
                $this->error($data['msg'], null, $data['data']);
            }
        } else {
            $this->error('檢測模板失敗', null, ['code'=>1]);
        }
    }

    // 前臺會員左側菜單
    public function ajax_menu_index()
    {
        $list = array();
        $condition = array();

        // 多語言
        $condition['a.lang'] = array('eq', $this->admin_lang);

        /**
         * 數據查詢
         */
        $count = Db::name('users_menu')->alias('a')->where($condition)->count();// 查詢滿足要求的總記錄數
        $Page = new Page($count, config('paginate.list_rows'));// 實例化分頁類 傳入總記錄數和每頁顯示的記錄數
        $row = Db::name('users_menu')->field('a.*')
            ->alias('a')
            ->where($condition)
            ->order('a.sort_order asc, a.id asc')
            ->limit($Page->firstRow.','.$Page->listRows)
            ->select();

        $list = [];
        $pay_open = getUsersConfigData('pay.pay_open');
        foreach ($row as $key => $val) {
            /*是否開啟支付功能*/
            if (1 != $pay_open && 'user/Pay/pay_consumer_details' == $val['mca']) {
                continue;
            }
            /*--end*/
            $list[] = $val;
        }

        $show = $Page->show();// 分頁顯示輸出
        $this->assign('page',$show);// 賦值分頁輸出
        $this->assign('list',$list);// 賦值數據集
        $this->assign('pager',$Page);// 賦值分頁集

        return $this->fetch();
    }
}