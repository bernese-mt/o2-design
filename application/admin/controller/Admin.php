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

namespace app\admin\controller;

use think\Page;
use think\Verify;
use think\Db;
use think\db\Query;
use think\Session;
use app\admin\model\AuthRole;
use app\admin\logic\AjaxLogic;

class Admin extends Base {

    public function index()
    {
        $list = array();
        $keywords = input('keywords/s');

        $condition = array();
        if (!empty($keywords)) {
            $condition['a.user_name|a.true_name'] = array('LIKE', "%{$keywords}%");
        }

        /*權限控制 by 小虎哥*/
        $admin_info = session('admin_info');
        if (0 < intval($admin_info['role_id'])) {
            $condition['a.admin_id|a.parent_id'] = $admin_info['admin_id'];
        } else {
            if (!empty($admin_info['parent_id'])) {
                $condition['a.admin_id|a.parent_id'] = $admin_info['admin_id'];
            }
        }
        /*--end*/

        /**
         * 數據查詢
         */
        $count = DB::name('admin')->alias('a')->where($condition)->count();// 查詢滿足要求的總記錄數
        $Page = new Page($count, config('paginate.list_rows'));// 實例化分頁類 傳入總記錄數和每頁顯示的記錄數
        $list = DB::name('admin')->field('a.*, b.name AS role_name')
            ->alias('a')
            ->join('__AUTH_ROLE__ b', 'a.role_id = b.id', 'LEFT')
            ->where($condition)
            ->order('a.admin_id asc')
            ->limit($Page->firstRow.','.$Page->listRows)
            ->select();

        foreach ($list as $key => $val) {
            if (0 >= intval($val['role_id'])) {
                $val['role_name'] = !empty($val['parent_id']) ? '超級管理員' : '創始人';
            }
            $list[$key] = $val;
        }

        $show = $Page->show();// 分頁顯示輸出
        $this->assign('page',$show);// 賦值分頁輸出
        $this->assign('list',$list);// 賦值數據集
        $this->assign('pager',$Page);// 賦值分頁集

        /*第一次同步CMS用戶的欄目ID到權限組裏*/
        $this->syn_built_auth_role();
        /*--end*/

        return $this->fetch();
    }

    /*
     * 管理員登陸
     */
    public function login()
    {
        if (session('?admin_id') && session('admin_id') > 0) {
            $web_adminbasefile = tpCache('web.web_adminbasefile');
            $web_adminbasefile = !empty($web_adminbasefile) ? $web_adminbasefile : '/login.php';
            $this->success("您已登錄", $web_adminbasefile);
        }
      
        // $gb_funcs = get_extension_funcs('gd');
        $is_vertify = 1; // 默認開啓驗證碼
        $admin_login_captcha = config('captcha.admin_login');
        if (!function_exists('imagettftext') || empty($admin_login_captcha['is_on'])) {
            $is_vertify = 0; // 函數不存在，不符合開啓的條件
        }

        if (IS_POST) {

            $post = input('post.');

            if (!function_exists('session_start')) {
                $this->error('請聯系空間商，開啓php的session擴展！');
            }
            if (!testWriteAble(DATA_PATH.'session')) {
                $this->error('請仔細檢查以下問題：<br/>1、磁盤空間大小是否100%；<br/>2、站點目錄權限是否爲755；<br/>3、站點目錄的權限，禁止用root:root ；<br/>4、如還沒解決，請點擊：<a href="http://www.eyoucms.com/wenda/6958.html" target="_blank">檢視教程</a>');
            }
            
            if (1 == $is_vertify) {
                $verify = new Verify();
                if (!$verify->check(input('post.vertify'), "admin_login")) {
                    $this->error('驗證碼錯誤');
                }
            }
            $condition['user_name'] = input('post.user_name/s');
            $condition['password'] = input('post.password/s');
            if (!empty($condition['user_name']) && !empty($condition['password'])) {
                $condition['password'] = func_encrypt($condition['password']);
                $admin_info = M('admin')->where($condition)->find();
                if (is_array($admin_info)) {
                    if ($admin_info['status'] == 0) {
                        $this->error('賬號被禁用！');
                    }

                    // 數據驗證
                    $rule = [
                        'user_name'    => 'require|token',
                    ];
                    $message = [
                        'user_name.require' => '用戶名不能爲空！',
                    ];
                    $validate = new \think\Validate($rule, $message);
                    if(!$validate->batch()->check($post))
                    {
                        $this->error('登錄校驗失敗，請重新整理頁面重試~');
                    }

                    $role_id = !empty($admin_info['role_id']) ? $admin_info['role_id'] : -1;
                    $auth_role_info = array();
                    $role_name = !empty($admin_info['parent_id']) ? '超級管理員' : '創始人';
                    if (0 < intval($role_id)) {
                        $auth_role_info = M('auth_role')
                            ->field("a.*, a.name AS role_name")
                            ->alias('a')
                            ->where('a.id','eq', $role_id)
                            ->find();
                        if (!empty($auth_role_info)) {
                            $auth_role_info['language'] = unserialize($auth_role_info['language']);
                            $auth_role_info['cud'] = unserialize($auth_role_info['cud']);
                            $auth_role_info['permission'] = unserialize($auth_role_info['permission']);
                            $role_name = $auth_role_info['name'];
                        }
                    }
                    $admin_info['auth_role_info'] = $auth_role_info;
                    $admin_info['role_name'] = $role_name;

                    $last_login_time = getTime();
                    $last_login_ip = clientIP();
                    $login_cnt = $admin_info['login_cnt'] + 1;
                    M('admin')->where("admin_id = ".$admin_info['admin_id'])->save(array('last_login'=>$last_login_time, 'last_ip'=>$last_login_ip, 'login_cnt'=>$login_cnt, 'session_id'=>$this->session_id));
                    $admin_info['last_login'] = $last_login_time;
                    $admin_info['last_ip'] = $last_login_ip;

                    session('admin_id',$admin_info['admin_id']);
                    /*過濾存儲在session檔案的敏感資訊*/
                    foreach (['user_name','true_name','password'] as $key => $val) {
                        unset($admin_info[$val]);
                    }
                    /*--end*/
                    session('admin_info', $admin_info);
                    session('admin_login_expire', getTime()); // 登錄有效期
                    adminLog('後臺登錄');
                    $url = session('from_url') ? session('from_url') : request()->baseFile();
                    $this->success('登錄成功', $url);
                } else {
                    $this->error('賬號密碼不正確');
                }
            } else {
                $this->error('請填寫賬號密碼');
            }
        }

        $this->assign('is_vertify', $is_vertify);

        $ajaxLogic = new AjaxLogic;
        $ajaxLogic->login_handle();

        return $this->fetch();
    }

    /**
     * 驗證碼獲取
     */
    public function vertify()
    {
        /*驗證碼外掛開關*/
        $admin_login_captcha = config('captcha.admin_login');
        $config = (!empty($admin_login_captcha['is_on']) && !empty($admin_login_captcha['config'])) ? $admin_login_captcha['config'] : config('captcha.default');
        /*--end*/
        ob_clean(); // 清空緩存，才能顯示驗證碼
        $Verify = new Verify($config);
        $Verify->entry('admin_login');
        exit();
    }
    
    /**
     * 修改管理員密碼
     * @return \think\mixed
     */
    public function admin_pwd()
    {
        $admin_id = input('admin_id/d',0);
        $oldPwd = input('old_pw/s');
        $newPwd = input('new_pw/s');
        $new2Pwd = input('new_pw2/s');
       
        if(!$admin_id){
            $admin_id = session('admin_id');
        }
        $info = M('admin')->where("admin_id", $admin_id)->find();
        $info['password'] =  "";
        $this->assign('info',$info);
        
        if(IS_POST){
            //修改密碼
            $enOldPwd = func_encrypt($oldPwd);
            $enNewPwd = func_encrypt($newPwd);
            $admin = M('admin')->where('admin_id' , $admin_id)->find();
            if(!$admin || $admin['password'] != $enOldPwd){
                exit(json_encode(array('status'=>-1,'msg'=>'舊密碼不正確')));
            }else if($newPwd != $new2Pwd){
                exit(json_encode(array('status'=>-1,'msg'=>'兩次密碼不一致')));
            }else{
                $data = array(
                    'update_time'   => getTime(),
                    'password'      => $enNewPwd,
                );
                $row = M('admin')->where('admin_id' , $admin_id)->save($data);
                if($row){
                    adminLog('修改管理員密碼');
                    exit(json_encode(array('status'=>1,'msg'=>'操作成功')));
                }else{
                    exit(json_encode(array('status'=>-1,'msg'=>'操作失敗')));
                }
            }
        }

        if (IS_AJAX) {
            return $this->fetch('admin/admin_pwd_ajax');
        } else {
            return $this->fetch('admin/admin_pwd');
        }
    }
    
    /**
     * 退出登陸
     */
    public function logout()
    {
        adminLog('安全退出');
        session_unset();
        // session_destroy();
        session::clear();
        cookie('admin-treeClicked', null); // 清除並恢複欄目列表的展開方式
        $this->success("安全退出", request()->baseFile());
    }
    
    /**
     * 新增管理員
     */
    public function admin_add()
    {
        $this->language_access(); // 多語言功能操作權限

        if (IS_POST) {
            $data = input('post.');

            if (0 < intval(session('admin_info.role_id'))) {
                $this->error("超級管理員才能操作！");
            }

            if (empty($data['password']) || empty($data['password2'])) {
                $this->error("密碼不能爲空！");
            }else if ($data['password'] != $data['password2']) {
                $this->error("兩次密碼輸入不一致！");
            }

            $data['user_name'] = trim($data['user_name']);
            $data['password'] = func_encrypt($data['password']);
            $data['password2'] = func_encrypt($data['password2']);
            $data['role_id'] = intval($data['role_id']);
            $data['parent_id'] = session('admin_info.admin_id');
            $data['add_time'] = getTime();
            if (empty($data['pen_name'])) {
                $data['pen_name'] = $data['user_name'];
            }
            if (M('admin')->where("user_name", $data['user_name'])->count()) {
                $this->error("此用戶名已被註冊，請更換",url('Admin/admin_add'));
            } else {
                $admin_id = M('admin')->insertGetId($data);
                if ($admin_id) {
                    adminLog('新增管理員：'.$data['user_name']);
                    $this->success("操作成功", url('Admin/index'));
                } else {
                    $this->error("操作失敗");
                }
            }
        }

        // 權限組
        $admin_role_list = model('AuthRole')->getRoleAll();
        $this->assign('admin_role_list', $admin_role_list);

        // 模塊組
        $modules = getAllMenu();
        $this->assign('modules', $modules);

        // 權限集
        $auth_rules = get_auth_rule(['is_modules'=>1]);
        $auth_rule_list = group_same_key($auth_rules, 'menu_id');
        $this->assign('auth_rule_list', $auth_rule_list);

        // 欄目
        $arctype_data = $arctype_array = array();
        $arctype = M('arctype')->select();
        if(! empty($arctype)){
            foreach ($arctype as $item){
                if($item['parent_id'] <= 0){
                    $arctype_data[] = $item;
                }
                $arctype_array[$item['parent_id']][] = $item;
            }
        }
        $this->assign('arctypes', $arctype_data);
        $this->assign('arctype_array', $arctype_array);

        // 外掛
        $plugins = model('Weapp')->getList(['status'=>1]);
        $this->assign('plugins', $plugins);

        return $this->fetch();
    }
    
    /**
     * 編輯管理員
     */
    public function admin_edit()
    {
        if (IS_POST) {
            $data = input('post.');
            $id = $data['admin_id'];

            if ($id == session('admin_info.admin_id')) {
                unset($data['role_id']); // 不能修改自己的權限組
            } else if (0 < intval(session('admin_info.role_id')) && session('admin_info.admin_id') != $id) {
                $this->error('禁止更改別人的資訊！');
            }

            if (!empty($data['password']) || !empty($data['password2'])) {
                if ($data['password'] != $data['password2']) {
                    $this->error("兩次密碼輸入不一致！");
                }
            }

            $user_name = $data['user_name'];
            if(empty($data['password'])){
                unset($data['password']);
            }else{
                $data['password'] = func_encrypt($data['password']);
            }
            unset($data['user_name']);
            
            if (empty($data['pen_name'])) {
                $data['pen_name'] = $user_name;
            }

            /*不允許修改自己的權限組*/
            if (isset($data['role_id'])) {
                if (0 < intval(session('admin_info.role_id')) && intval($data['role_id']) != session('admin_info.role_id')) {
                    $data['role_id'] = session('admin_info.role_id');
                }
            }
            /*--end*/
            $data['update_time'] = getTime();
            $r = M('admin')->where('admin_id', $id)->save($data);
            if ($r) {
                /*過濾存儲在session檔案的敏感資訊*/
                $admin_info = session('admin_info');
                $admin_info = array_merge($admin_info, $data);
                foreach (['user_name','true_name','password','password2'] as $key => $val) {
                    unset($newData[$val]);
                }
                session('admin_info', $admin_info);
                /*--end*/
                adminLog('編輯管理員：'.$user_name);
                $this->success("操作成功",url('Admin/index'));
            } else {
                $this->error("操作失敗");
            }
        }

        $id = input('get.id/d', 0);
        $info = M('admin')->field('a.*')
            ->alias('a')
            ->where("a.admin_id", $id)->find();
        $info['password'] =  "";
        $this->assign('info',$info);

        // 當前角色資訊
        $admin_role_model = model('AuthRole');
        $role_info = $admin_role_model->getRole(array('id' => $info['role_id']));
        $this->assign('role_info', $role_info);

        // 權限組
        $admin_role_list = $admin_role_model->getRoleAll();
        $this->assign('admin_role_list', $admin_role_list);

        // 模塊組
        $modules = getAllMenu();
        $this->assign('modules', $modules);

        // 權限集
        $auth_rules = get_auth_rule(['is_modules'=>1]);
        $auth_rule_list = group_same_key($auth_rules, 'menu_id');
        $this->assign('auth_rule_list', $auth_rule_list);

        // 欄目
        $arctype_data = $arctype_array = array();
        $arctype = M('arctype')->select();
        if(! empty($arctype)){
            foreach ($arctype as $item){
                if($item['parent_id'] <= 0){
                    $arctype_data[] = $item;
                }
                $arctype_array[$item['parent_id']][] = $item;
            }
        }
        $this->assign('arctypes', $arctype_data);
        $this->assign('arctype_array', $arctype_array);

        // 外掛
        $plugins = model('Weapp')->getList(['status'=>1]);
        $this->assign('plugins', $plugins);

        return $this->fetch();
    }
    
    /**
     * 刪除管理員
     */
    public function admin_del()
    {
        $this->language_access(); // 多語言功能操作權限

        if (IS_POST) {
            $id_arr = input('del_id/a');
            $id_arr = eyIntval($id_arr);
            if (in_array(session('admin_id'), $id_arr)) {
                $this->error('禁止刪除自己');
            }
            if (!empty($id_arr)) {
                if (0 < intval(session('admin_info.role_id')) || !empty($parent_id) ) {
                    $count = M('admin')->where("admin_id in (".implode(',', $id_arr).") AND role_id = -1")
                        ->count();
                    if (!empty($count)) {
                        $this->error('禁止刪除超級管理員');
                    }
                }

                $result = M('admin')->field('user_name')->where("admin_id",'IN',$id_arr)->select();
                $user_names = get_arr_column($result, 'user_name');

                $r = M('admin')->where("admin_id",'IN',$id_arr)->delete();
                if($r){
                    adminLog('刪除管理員：'.implode(',', $user_names));
                    $this->success('刪除成功');
                }else{
                    $this->error('刪除失敗');
                }
            }else{
                $this->error('參數有誤');
            }
        }
        $this->error('非法操作');
    }

    /*
     * 第一次同步CMS用戶的欄目ID到權限組裏
     * 默認賦予內建權限所有的內容欄目權限
     */
    private function syn_built_auth_role()
    {
        $authRole = new AuthRole;
        $roleRow = $authRole->getRoleAll(['built_in'=>1,'update_time'=>['elt',0]]);
        if (!empty($roleRow)) {
            $saveData = [];
            foreach ($roleRow as $key => $val) {
                $permission = $val['permission'];
                $arctype = M('arctype')->where('status',1)->column('id');
                if (!empty($arctype)) {
                    $permission['arctype'] = $arctype;
                } else {
                    unset($permission['arctype']);
                }
                $saveData[] = array(
                    'id'    => $val['id'],
                    'permission'    => $permission,
                    'update_time'   => getTime(),
                );
            }
            $authRole->saveAll($saveData);
        }
    }

    /*
     * 設置admin表數據
     */
    public function ajax_setfield()
    {
        if (IS_POST) {
            $admin_id = session('admin_id');
            $field  = input('field'); // 修改哪個欄位
            $value  = input('value', '', null); // 修改欄位值  
            if (!empty($admin_id)) {
                $r = M('admin')->where('admin_id',intval($admin_id))->save([
                        $field=>$value,
                        'update_time'=>getTime(),
                    ]); // 根據條件儲存修改的數據
                if ($r) {
                    /*更新存儲在session裏的資訊*/
                    $admin_info = session('admin_info');
                    $admin_info[$field] = $value;
                    session('admin_info', $admin_info);
                    /*--end*/
                    $this->success('操作成功');
                }
            }
        }
        $this->error('操作失敗');
    }
}