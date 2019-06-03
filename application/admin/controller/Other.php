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

class Other extends Base
{
    /*
     * 初始化操作
     */
    public function _initialize() 
    {
        parent::_initialize();
        // 判斷是否有廣告位置
        if (strtolower(ACTION_NAME) != 'index') {
            $count = M('ad_position')->count('id');
            if (empty($count)) {
                $this->success('缺少廣告位置，正在前往中……', url('AdPosition/add'), '', 3);
                exit;
            }
        }
    }

    public function index()
    {
        $list = array();
        $get = input('get.');
        $pid = input('param.pid/d', 0);
        $keywords = input('keywords/s');
        $condition = array();
        // 應用搜索條件
        foreach (['keywords', 'pid'] as $key) {
            if (isset($get[$key]) && $get[$key] !== '') {
                if ($key == 'keywords') {
                    $condition['a.title'] = array('LIKE', "%{$get[$key]}%");
                } else {
                    $tmp_key = 'a.'.$key;
                    $condition[$tmp_key] = array('eq', $get[$key]);
                }
            }
        }

        // 多語言
        $condition['a.lang'] = array('eq', $this->admin_lang);

        $adM =  M('ad');
        $count = $adM->alias('a')->where($condition)->count();// 查詢滿足要求的總記錄數
        $Page = new Page($count, config('paginate.list_rows'));// 實例化分頁類 傳入總記錄數和每頁顯示的記錄數
        $list = $adM->alias('a')->where($condition)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        /*支援子目錄*/
        foreach ($list as $key => $val) {
            $val['litpic'] = handle_subdir_pic($val['litpic']);
            $list[$key] = $val;
        }
        /*--end*/

        $show = $Page->show();// 分頁顯示輸出
        $this->assign('page',$show);// 賦值分頁輸出
        $this->assign('list',$list);// 賦值數據集
        $this->assign('pager',$Page);// 賦值分頁對像

        $ad_position = model('AdPosition')->getAll('*','id');
        $this->assign('ad_position',$ad_position);

        $this->assign('pid',$pid);// 賦值分頁對像
        return $this->fetch();
    }
    
    /**
     * 新增
     */
    public function add()
    {
        $this->language_access(); // 多語言功能操作許可權

        if (IS_POST) {
            $post = input('post.');
            $is_remote = !empty($post['is_remote']) ? $post['is_remote'] : 0;
            $litpic = '';
            if ($is_remote == 1) {
                $litpic = $post['litpic_remote'];
            } else {
                $litpic = $post['litpic_local'];
            }
            $newData = array(
                'litpic'            => $litpic,
                'admin_id'  => session('admin_id'),
                'lang'  => $this->admin_lang,
                'sort_order'    => 100,
                'add_time'           => getTime(),
                'update_time'   => getTime(),
            );
            $data = array_merge($post, $newData);
            $insertId = M('ad')->insertGetId($data);

            if ($insertId) {

                /*同步廣告位置ID到多語言的模板變數里*/
                $this->syn_add_language_attribute($insertId);
                /*--end*/

                \think\Cache::clear('ad');
                adminLog('新增廣告：'.$post['title']);
                $this->success("操作成功", url('Other/index'));
            } else {
                $this->error("操作失敗");
            }
            exit;
        }

        $pid = input('param.pid/d', 0);
        $this->assign('pid', $pid);

        $ad_position = model('AdPosition')->getAll('*', 'id');
        $this->assign('ad_position', $ad_position);

        $ad_media_type = config('global.ad_media_type');
        $this->assign('ad_media_type', $ad_media_type);

        return $this->fetch();
    }

    
    /**
     * 編輯
     */
    public function edit()
    {
        if (IS_POST) {
            $post = input('post.');
            if(!empty($post['id'])){
                $is_remote = !empty($post['is_remote']) ? $post['is_remote'] : 0;
                $litpic = '';
                if ($is_remote == 1) {
                    $litpic = $post['litpic_remote'];
                } else {
                    $litpic = $post['litpic_local'];
                }
                $newData = array(
                    'litpic'            => $litpic,
                    'update_time'       => getTime(),
                );
                $data = array_merge($post, $newData);
                $r = M('ad')->where([
                        'id'    => $post['id'],
                    ])
                    ->cache(true,null,'ad')
                    ->update($data);
            }
            if ($r) {
                adminLog('編輯廣告');
                $this->success("操作成功", url('Other/index'));
            } else {
                $this->error("操作失敗");
            }
        }

        $assign_data = array();

        $id = input('id/d');
        $field = M('ad')->where([
                'id'    => $id,
            ])->find();
        if (empty($field)) {
            $this->error('廣告不存在，請聯繫管理員！');
            exit;
        }
        if (is_http_url($field['litpic'])) {
            $field['is_remote'] = 1;
            $field['litpic_remote'] = handle_subdir_pic($field['litpic']);
        } else {
            $field['is_remote'] = 0;
            $field['litpic_local'] = handle_subdir_pic($field['litpic']);
        }
        
        /*支援子目錄*/
        $field['intro'] = handle_subdir_pic($field['intro'], 'html');
        /*--end*/

        $assign_data['field'] = $field;
        $assign_data['ad_position'] = model('AdPosition')->getAll('*', 'id');

        $assign_data['ad_media_type'] = config('global.ad_media_type');

        $this->assign($assign_data);
        return $this->fetch();
    }
    
    /**
     * 刪除
     */
    public function del()
    {
        $this->language_access(); // 多語言功能操作許可權

        $id_arr = input('del_id/a');
        $id_arr = eyIntval($id_arr);
        if(!empty($id_arr)){

            /*多語言*/
            $attr_name_arr = [];
            foreach ($id_arr as $key => $val) {
                $attr_name_arr[] = 'ad'.$val;
            }
            if (is_language()) {
                $new_id_arr = Db::name('language_attr')->where([
                        'attr_name' => ['IN', $attr_name_arr],
                        'attr_group'    => 'ad',
                    ])->column('attr_value');
                !empty($new_id_arr) && $id_arr = $new_id_arr;
            }
            /*--end*/
            $r = M('ad')->where([
                    'id'    => ['IN', $id_arr],
                ])
                ->cache(true,null,'ad')
                ->delete();
            if ($r) {

                /*多語言*/
                if (!empty($attr_name_arr)) {
                    M('language_attr')->where([
                            'attr_name' => ['IN', $attr_name_arr],
                            'attr_group'    => 'ad',
                        ])->delete();
                    M('language_attribute')->where([
                            'attr_name' => ['IN', $attr_name_arr],
                            'attr_group'    => 'ad',
                        ])->delete();
                }
                /*--end*/

                adminLog('刪除廣告-id：'.implode(',', $id_arr));
                $this->success('刪除成功');
            } else {
                $this->error('刪除失敗');
            }
        }else{
            $this->error('參數有誤');
        }
    }

    /**
     * ui美化新增
     */
    public function ui_add()
    {
        $this->language_access(); // 多語言功能操作許可權

        if (IS_POST) {
            $post = input('post.');
            $is_remote = !empty($post['is_remote']) ? $post['is_remote'] : 0;
            $litpic = '';
            if ($is_remote == 1) {
                $litpic = $post['litpic_remote'];
            } else {
                $litpic = $post['litpic_local'];
            }
            $newData = array(
                'media_type'    => 1,
                'litpic'            => $litpic,
                'lang'  => get_current_lang(),
                'add_time'       => getTime(),
                'update_time'       => getTime(),
            );
            $data = array_merge($post, $newData);
            $insertId = M('ad')->insertGetId($data);
            if ($insertId) {

                /*同步廣告位置ID到多語言的模板變數里*/
                $this->syn_add_language_attribute($insertId);
                /*--end*/

                \think\Cache::clear('ad');
                adminLog('新增廣告：'.$post['title']);
                $this->success('操作成功');
            } else {
                $this->error('操作失敗');
            }
        }

        $edit_id = input('param.edit_id/d', 0);
        $pid = input('param.pid/d', 0);
        /*多語言*/
        $new_pid = model('LanguageAttr')->getBindValue($pid, 'ad_position');
        !empty($new_pid) && $pid = $new_pid;
        /*--end*/
        $assign_data = array();
        $assign_data['ad_position'] = model('AdPosition')->getInfo($pid);
        $assign_data['edit_id'] = $edit_id;

        $this->assign($assign_data);
        return $this->fetch();
    }

    /**
     * ui美化編輯
     */
    public function ui_edit()
    {
        if (IS_POST) {
            $post = input('post.');
            if(!empty($post['id'])){
                $is_remote = !empty($post['is_remote']) ? $post['is_remote'] : 0;
                $litpic = '';
                if ($is_remote == 1) {
                    $litpic = $post['litpic_remote'];
                } else {
                    $litpic = $post['litpic_local'];
                }
                $newData = array(
                    'litpic'            => $litpic,
                    'update_time'       => getTime(),
                );
                $data = array_merge($post, $newData);
                $r = M('ad')->where([
                        'id'    => $post['id'],
                    ])
                    ->cache(true,null,'ad')
                    ->update($data);
                if ($r) {
                    adminLog('編輯廣告：'.$post['title']);
                    $this->success('操作成功');
                }
            }
            $this->error('操作失敗');
        }

        $assign_data = array();

        $id = input('id/d');
        $field = M('ad')->where([
                'id'    => $id,
            ])->find();
        if (empty($field)) {
            $this->error('廣告不存在，請聯繫管理員！');
            exit;
        }
        if (is_http_url($field['litpic'])) {
            $field['is_remote'] = 1;
            $field['litpic_remote'] = $field['litpic'];
        } else {
            $field['is_remote'] = 0;
            $field['litpic_local'] = $field['litpic'];
        }
        $assign_data['field'] = $field;
        $assign_data['ad_position'] = model('AdPosition')->getInfo($field['pid']);

        $this->assign($assign_data);
        return $this->fetch();
    }
    
    /**
     * 刪除
     */
    public function ui_del()
    {
        $this->language_access(); // 多語言功能操作許可權
        
        $id_arr = input('del_id/a');
        $id_arr = eyIntval($id_arr);
        if(!empty($id_arr)){

            /*多語言*/
            $attr_name_arr = [];
            foreach ($id_arr as $key => $val) {
                $attr_name_arr[] = 'ad'.$val;
            }
            if (is_language()) {
                $new_id_arr = Db::name('language_attr')->where([
                        'attr_name' => ['IN', $attr_name_arr],
                        'attr_group'    => 'ad',
                    ])->column('attr_value');
                !empty($new_id_arr) && $id_arr = $new_id_arr;
            }
            /*--end*/

            $r = M('ad')->where([
                    'id'    => ['IN', $id_arr],
                ])
                ->cache(true,null,'ad')
                ->delete();
            if ($r) {

                /*多語言*/
                if (!empty($attr_name_arr)) {
                    M('language_attr')->where([
                            'attr_name' => ['IN', $attr_name_arr],
                            'attr_group'    => 'ad',
                        ])->delete();
                    M('language_attribute')->where([
                            'attr_name' => ['IN', $attr_name_arr],
                            'attr_group'    => 'ad',
                        ])->delete();
                }
                /*--end*/

                adminLog('刪除廣告-id：'.implode(',', $id_arr));
                $this->success('刪除成功');
            } else {
                $this->error('刪除失敗');
            }
        }else{
            $this->error('參數有誤');
        }
    }

    /**
     * 同步新增廣告ID到多語言的模板變數里
     */
    private function syn_add_language_attribute($ad_id)
    {
        /*單語言情況下不執行多語言程式碼*/
        if (!is_language()) {
            return true;
        }
        /*--end*/

        $attr_group = 'ad';
        $admin_lang = $this->admin_lang;
        $main_lang = get_main_lang();
        $languageRow = Db::name('language')->field('mark')->order('id asc')->select();
        if (!empty($languageRow) && $admin_lang == $main_lang) { // 目前語言是主體語言，即語言列表最早新增的語言
            $ad_db = Db::name('ad');
            $result = $ad_db->find($ad_id);
            $attr_name = 'ad'.$ad_id;
            $r = Db::name('language_attribute')->save([
                'attr_title'    => $result['title'],
                'attr_name'     => $attr_name,
                'attr_group'    => $attr_group,
                'add_time'      => getTime(),
                'update_time'   => getTime(),
            ]);
            if (false !== $r) {
                $data = [];
                foreach ($languageRow as $key => $val) {
                    /*同步新廣告到其他語言廣告列表*/
                    if ($val['mark'] != $admin_lang) {
                        $addsaveData = $result;
                        $addsaveData['lang'] = $val['mark'];
                        $newPid = Db::name('language_attr')->where([
                                'attr_name' => 'adp'.$result['pid'],
                                'attr_group'    => 'ad_position',
                                'lang'  => $val['mark'],
                            ])->getField('attr_value');
                        $addsaveData['pid'] = $newPid;
                        unset($addsaveData['id']);
                        $ad_id = $ad_db->insertGetId($addsaveData);
                    }
                    /*--end*/
                    
                    /*所有語言繫結在主語言的ID容器里*/
                    $data[] = [
                        'attr_name' => $attr_name,
                        'attr_value'    => $ad_id,
                        'lang'  => $val['mark'],
                        'attr_group'    => $attr_group,
                        'add_time'      => getTime(),
                        'update_time'   => getTime(),
                    ];
                    /*--end*/
                }
                if (!empty($data)) {
                    model('LanguageAttr')->saveAll($data);
                }
            }
        }
    }
}