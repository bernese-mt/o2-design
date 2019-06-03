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

class Uiset extends Base
{
    public $theme_style;
    public $templateArr = array();

    /*
     * 初始化操作
     */
    public function _initialize() {
        parent::_initialize();
        $this->theme_style = 'pc';

        /*模板列表*/
        if (file_exists(ROOT_PATH.'template/pc/uiset.txt')) {
            array_push($this->templateArr, 'pc');
        }
        if (file_exists(ROOT_PATH.'template/mobile/uiset.txt')) {
            array_push($this->templateArr, 'mobile');
        }
        /*--end*/

        /*許可權控制 by 小虎哥*/
        $admin_info = session('admin_info');
        if (0 < intval($admin_info['role_id'])) {
            $auth_role_info = $admin_info['auth_role_info'];
            $permission = $auth_role_info['permission'];
            $auth_rule = get_auth_rule();
            $all_auths = []; // 系統全部許可權對應的菜單ID
            $admin_auths = []; // 使用者目前擁有許可權對應的菜單ID
            $diff_auths = []; // 使用者沒有被授權的許可權對應的菜單ID
            foreach($auth_rule as $key => $val){
                $all_auths = array_merge($all_auths, explode(',', $val['menu_id']), explode(',', $val['menu_id2']));
                if (in_array($val['id'], $permission['rules'])) {
                    $admin_auths = array_merge($admin_auths, explode(',', $val['menu_id']), explode(',', $val['menu_id2']));
                }
            }
            $all_auths = array_unique($all_auths);
            $admin_auths = array_unique($admin_auths);
            $diff_auths = array_diff($all_auths, $admin_auths);

            if(in_array(2002, $diff_auths)){
                $this->error('您沒有操作許可權，請聯繫超級管理員分配許可權');
            }
        }
        /*--end*/
    }

    /**
     * PC除錯外觀
     */
    public function pc()
    {
        // 支援子目錄
        $index_url = ROOT_DIR.'/index.php?m=home&c=Index&a=index&uiset=on&v=pc&lang='.$this->admin_lang;
        $this->redirect($index_url);
    }

    /**
     * 手機除錯外觀
     */
    public function mobile()
    {
        // 支援子目錄
        $index_url = ROOT_DIR.'/index.php?m=home&c=Index&a=index&uiset=on&v=mobile&lang='.$this->admin_lang;
        $this->redirect($index_url);
    }

    /**
     * 除錯外觀
     */
    public function index()
    {
        return $this->fetch();
    }

    /**
     * 數據列表
     */
    public function ui_index()
    {
        $condition = array();
        // 獲取到所有GET參數
        $param = input('param.');
        /*模板主題*/
        $param['theme_style'] = $this->theme_style = input('param.theme_style/s', 'pc');
        /*--end*/

        // 應用搜索條件
        foreach (['keywords','theme_style'] as $key) {
            if (isset($param[$key]) && $param[$key] !== '') {
                if ($key == 'keywords') {
                    $condition['a.page|a.type|a.name'] = array('eq', "%{$param[$key]}%");
                } else {
                    $condition['a.'.$key] = array('eq', $param[$key]);
                }
            }
        }

        /*多語言*/
        $condition['a.lang'] = $this->admin_lang;
        /*--end*/

        $list = array();

        $uiconfigM =  M('ui_config');
        $count = $uiconfigM->alias('a')->where($condition)->count('id');// 查詢滿足要求的總記錄數
        $Page = $pager = new Page($count, config('paginate.list_rows'));// 實例化分頁類 傳入總記錄數和每頁顯示的記錄數
        $list = $uiconfigM->alias('a')->where($condition)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        $show = $Page->show();// 分頁顯示輸出
        $this->assign('page',$show);// 賦值分頁輸出
        $this->assign('list',$list);// 賦值數據集
        $this->assign('pager',$pager);// 賦值分頁對像
        $this->assign('theme_style',$this->theme_style);// 模板主題
        $this->assign('templateArr',$this->templateArr);// 模板列表

        return $this->fetch();
    }
    
    /**
     * 刪除
     */
    public function del()
    {
        $id_arr = input('del_id/a');
        $id_arr = eyIntval($id_arr);
        if(!empty($id_arr)){
            $result = M('ui_config')->where("id",'IN',$id_arr)->getAllWithIndex('name');
            $r = M('ui_config')->where("id",'IN',$id_arr)->delete();
            if($r){
                \think\Cache::clear('ui_config');
                delFile(RUNTIME_PATH.'ui/'.$result['theme_style']);
                adminLog('刪除視覺化數據 e-id：'.implode(array_keys($result)));
                $this->success('刪除成功');
            }else{
                $this->error('刪除失敗');
            }
        }else{
            $this->error('參數有誤');
        }
    }
}