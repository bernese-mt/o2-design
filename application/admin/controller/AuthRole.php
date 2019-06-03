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

namespace app\admin\controller;

use think\Page;
use think\Db;
use think\Validate;

class AuthRole extends Base {
    
    public function _initialize() {
        parent::_initialize();
        $this->language_access(); // 多語言功能操作許可權
    }
    
    /**
     * 許可權組管理
     */
    public function index()
    {   
        $map = array();
        $pid = input('pid/d');
        $keywords = input('keywords/s');

        if (!empty($keywords)) {
            $map['c.name'] = array('LIKE', "%{$keywords}%");
        }

        $AuthRole =  M('auth_role');     
        $count = $AuthRole->alias('c')->where($map)->count();// 查詢滿足要求的總記錄數
        $Page = new Page($count, 10);// 實例化分頁類 傳入總記錄數和每頁顯示的記錄數
        $fields = "c.*,s.name AS pname";
        $list = DB::name('auth_role')
            ->field($fields)
            ->alias('c')
            ->join('__AUTH_ROLE__ s','s.id = c.pid','LEFT')
            ->where($map)
            ->order('c.id asc')
            ->limit($Page->firstRow.','.$Page->listRows)
            ->select();
        $show = $Page->show();// 分頁顯示輸出
        $this->assign('page',$show);// 賦值分頁輸出
        $this->assign('list',$list);// 賦值數據集
        $this->assign('pager',$Page);// 賦值分頁集

        return $this->fetch();
    }
    
    /**
     * 新增許可權組
     */
    public function add()
    {
        if (IS_POST) {
            $rule = array(
                'name'  => 'require',
            );
            $msg = array(
                'name.require' => '許可權組名稱不能為空！',
            );
            $data = array(
                'name' => trim(input('name/s')),
            );
            $validate = new Validate($rule, $msg);
            $result   = $validate->check($data);
            if(!$result){
                $this->error($validate->getError());
            }

            $model = model('AuthRole');
            $count = $model->where('name', $data['name'])->count();
            if(! empty($count)){
                $this->error('該許可權組名稱已存在，請檢查');
            }
            $role_id = $model->saveAuthRole(input());
            if($role_id){
                adminLog('新增許可權組：'.$data['name']);
                $admin_role_list = model('AuthRole')->getRoleAll();
                $this->success('操作成功', url('AuthRole/index'), ['role_id'=>$role_id,'role_name'=>$data['name'],'admin_role_list'=>json_encode($admin_role_list)]);
            }else{
                $this->error('操作失敗');
            }
        }

        // 許可權組
        $admin_role_list = model('AuthRole')->getRoleAll();
        $this->assign('admin_role_list', $admin_role_list);

        // 模組組
        $modules = getAllMenu();
        $this->assign('modules', $modules);

        // 許可權集
        // $singleArr = array_multi2single($modules, 'child'); // 多維陣列轉為一維
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
        $plugins = false;
        $web_weapp_switch = tpCache('web.web_weapp_switch');
        if (1 == $web_weapp_switch) {
            $plugins = model('Weapp')->getList(['status'=>1]);
        }
        $this->assign('plugins', $plugins);

        return $this->fetch();
    }
    
    public function edit()
    {
        $id = input('id/d', 0);
        if($id <= 0){
            $this->error('非法訪問');
        }

        if (IS_POST) {
            $rule = array(
                'name'  => 'require',
            );
            $msg = array(
                'name.require' => '許可權組名稱不能為空！',
            );
            $data = array(
                'name' => trim(input('name/s')),
            );
            $validate = new Validate($rule, $msg);
            $result   = $validate->check($data);
            if(!$result){
                $this->error($validate->getError());
            }

            $model = model('AuthRole');
            $count = $model->where('name', $data['name'])
                ->where('id', '<>', $id)
                ->count();
            if(! empty($count)){
                $this->error('該許可權組名稱已存在，請檢查');
            }
            $role_id = $model->saveAuthRole(input(), true);
            if($role_id){
                adminLog('編輯許可權組：'.$data['name']);
                $this->success('操作成功', url('AuthRole/index'), ['role_id'=>$role_id,'role_name'=>$data['name']]);
            }else{
                $this->error('操作失敗');
            }
        }

        $model = model('AuthRole');
        $info = $model->getRole(array('id' => $id));
        if(empty($info)){
            $this->error('數據不存在，請聯繫管理員！');
        }
        $this->assign('info', $info);

        // 許可權組
        $admin_role_list = model('AuthRole')->getRoleAll();
        $this->assign('admin_role_list', $admin_role_list);

        // 模組組
        $modules = getAllMenu();
        $this->assign('modules', $modules);

        // 許可權集
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
        $plugins = false;
        $web_weapp_switch = tpCache('web.web_weapp_switch');
        if (1 == $web_weapp_switch) {
            $plugins = model('Weapp')->getList(['status'=>1]);
        }
        $this->assign('plugins', $plugins);

        return $this->fetch();
    }
    
    public function del()
    {
        $id_arr = input('del_id/a');
        $id_arr = eyIntval($id_arr);
        if (!empty($id_arr)) {

            $count = M('auth_role')->where(['built_in'=>1,'id'=>['IN',$id_arr]])->count();
            if (!empty($count)) {
                $this->error('系統內建不允許刪除！');
            }

            $role = M('auth_role')->where("pid",'IN',$id_arr)->select();
            if ($role) {
                $this->error('請先清空該許可權組下的子許可權組');
            }

            $role_admin = M('admin')->where("role_id",'IN',$id_arr)->select();
            if ($role_admin) {
                $this->error('請先清空所屬該許可權組的管理員');
            } else {
                $r = M('auth_role')->where("id",'IN',$id_arr)->delete();
                if($r){
                    adminLog('刪除許可權組');
                    $this->success('刪除成功');
                }else{
                    $this->error('刪除失敗');
                }
            }
        } else {
            $this->error('參數有誤');
        }
    }
}