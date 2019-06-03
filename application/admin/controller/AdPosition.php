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

class AdPosition extends Base
{
    private $ad_position_system_id = array(); // 系統預設位置ID，不可刪除

    public function _initialize() {
        parent::_initialize();
    }

    public function index()
    {
        $list = array();
        $get = input('get.');
        $keywords = input('keywords/s');
        $condition = [];
        // 應用搜索條件
        foreach (['keywords'] as $key) {
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

        $adPositionM =  M('ad_position');
        $count = $adPositionM->alias('a')->where($condition)->count();// 查詢滿足要求的總記錄數
        $Page = new Page($count, config('paginate.list_rows'));// 實例化分頁類 傳入總記錄數和每頁顯示的記錄數
        $list = $adPositionM->alias('a')->where($condition)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        // 獲取指定位置的廣告數目
        $cid = get_arr_column($list, 'id');
        $ad_list = M('ad')->field('pid, count(id) AS has_children')
            ->where([
                'pid'   => ['IN', $cid],
                'lang'  => $this->admin_lang,
            ])->group('pid')
            ->getAllWithIndex('pid');
        $this->assign('ad_list', $ad_list);

        $show = $Page->show();// 分頁顯示輸出
        $this->assign('page',$show);// 賦值分頁輸出
        $this->assign('list',$list);// 賦值數據集
        $this->assign('pager',$Page);// 賦值分頁對像
        return $this->fetch();
    }
    
    /**
     * 新增
     */
    public function add()
    {
        //防止php超時
        function_exists('set_time_limit') && set_time_limit(0);

        $this->language_access(); // 多語言功能操作許可權

        if (IS_POST) {
            $post = input('post.');

            $map = array(
                'title' => trim($post['title']),
                'lang'  => $this->admin_lang,
            );
            if(M('ad_position')->where($map)->count() > 0){
                $this->error('該廣告名稱已存在，請檢查', url('AdPosition/index'));
            }

            // 新增廣告位置表資訊
            $data = array(
                'title'       => trim($post['title']),
                'intro'       => $post['intro'],
                'admin_id'    => session('admin_id'),
                'lang'        => $this->admin_lang,
                'add_time'    => getTime(),
                'update_time' => getTime(),
            );
            $insertId = M('ad_position')->insertGetId($data);

            if ($insertId) {
                // 同步廣告位置ID到多語言的模板變數里，新增多語言廣告位
                $this->syn_add_language_attribute($insertId);

                // 讀取組合廣告位的圖片及資訊
                $i = '1';
                foreach ($post['img_litpic'] as $key => $value) {
                    if (!empty($value)) {
                        if (!empty($post['img_target'][$key])) {
                            $target = '1';
                        }else{
                            $target = '0';
                        }
                        // 主要參數
                        $AdData['litpic']      = $value;
                        $AdData['pid']         = $insertId;
                        $AdData['title']       = trim($post['img_title'][$key]);
                        $AdData['links']       = $post['img_links'][$key];
                        $AdData['target']      = $target;
                        // 其他參數
                        $AdData['media_type']  = 1;
                        $AdData['admin_id']    = session('admin_id');
                        $AdData['lang']        = $this->admin_lang;
                        $AdData['sort_order']  = $i++;
                        $AdData['add_time']    = getTime();
                        $AdData['update_time'] = getTime();
                        // 新增到廣告圖表
                        $ad_id = Db::name('ad')->add($AdData);
                        // 同步多語言
                        $this->syn_add_ad_language_attribute($ad_id);
                    }
                }
                adminLog('新增廣告：'.$post['title']);
                $this->success("操作成功", url('AdPosition/index'));
            } else {
                $this->error("操作失敗", url('AdPosition/index'));
            }
            exit;
        }

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
                if(array_key_exists($post['id'], $this->ad_position_system_id)){
                    $this->error("不可更改系統預定義位置", url('AdPosition/edit',array('id'=>$post['id'])));
                }

                $map = array(
                    'id'    => array('NEQ', $post['id']),
                    'title' => trim($post['title']),
                    'lang'  => $this->admin_lang,
                );

                if(Db::name('ad_position')->where($map)->count() > 0){
                    $this->error('該廣告名稱已存在，請檢查', url('AdPosition/index'));
                }

                $data = array(
                    'id'          => $post['id'],
                    'title'       => trim($post['title']),
                    'intro'       => $post['intro'],
                    'update_time' => getTime(),
                );
                $r = Db::name('ad_position')->update($data);
            }

            if ($r) {
                $i = '1';
                $ad_db = Db::name('ad');
                // 讀取組合廣告位的圖片及資訊
                foreach ($post['img_litpic'] as $key => $value) {
                    if (!empty($value)) {
                        // 是否新視窗打開
                        if (!empty($post['img_target'][$key])) {
                            $target = '1';
                        }else{
                            $target = '0';
                        }
                        // 廣告位ID，為空則表示新增
                        $ad_id = $post['img_id'][$key];
                        if (!empty($ad_id)) {
                            // 查詢更新條件
                            $where = [
                                'id'   => $ad_id,
                                'lang' => $this->admin_lang,
                            ];
                            if ($ad_db->where($where)->count() > 0) {
                                // 主要參數
                                $AdData['litpic']      = $value;
                                $AdData['title']       = $post['img_title'][$key];
                                $AdData['links']       = $post['img_links'][$key];
                                $AdData['target']      = $target;
                                // 其他參數
                                $AdData['sort_order']  = $i++;
                                $AdData['update_time'] = getTime();
                                // 更新，不需要同步多語言
                                $ad_db->where($where)->update($AdData);
                            }else{
                                // 主要參數
                                $AdData['litpic']      = $value;
                                $AdData['pid']         = $post['id'];
                                $AdData['title']       = $post['img_title'][$key];
                                $AdData['links']       = $post['img_links'][$key];
                                $AdData['target']      = $target;
                                // 其他參數
                                $AdData['media_type']  = 1;
                                $AdData['admin_id']    = session('admin_id');
                                $AdData['lang']        = $this->admin_lang;
                                $AdData['sort_order']  = $i++;
                                $AdData['add_time']    = getTime();
                                $AdData['update_time'] = getTime();
                                $ad_id = $ad_db->add($AdData);
                                // 同步多語言
                                $this->syn_add_ad_language_attribute($ad_id);
                            }
                        }else{
                            // 主要參數
                            $AdData['litpic']      = $value;
                            $AdData['pid']         = $post['id'];
                            $AdData['title']       = $post['img_title'][$key];
                            $AdData['links']       = $post['img_links'][$key];
                            $AdData['target']      = $target;
                            // 其他參數
                            $AdData['media_type']  = 1;
                            $AdData['admin_id']    = session('admin_id');
                            $AdData['lang']        = $this->admin_lang;
                            $AdData['sort_order']  = $i++;
                            $AdData['add_time']    = getTime();
                            $AdData['update_time'] = getTime();
                            $ad_id = $ad_db->add($AdData);
                            // 同步多語言
                            $this->syn_add_ad_language_attribute($ad_id);
                        }
                    }
                }

                adminLog('編輯廣告：'.$post['title']);
                $this->success("操作成功", url('AdPosition/index'));
            } else {
                $this->error("操作失敗");
            }
        }

        $assign_data = array();

        $id = input('id/d');
        $field = M('ad_position')->field('a.*')
            ->alias('a')
            ->where(array('a.id'=>$id))
            ->find();
        if (empty($field)) {
            $this->error('廣告不存在，請聯繫管理員！');
            exit;
        }
        $assign_data['field'] = $field;

        // 廣告
        $ad_data = Db::name('ad')->where(array('pid'=>$field['id']))->order('sort_order asc')->select();
        foreach ($ad_data as $key => $val) {
            $ad_data[$key]['litpic'] = handle_subdir_pic($val['litpic']); // 支援子目錄
        }
        $assign_data['ad_data'] = $ad_data;
        
        $this->assign($assign_data);
        return $this->fetch();
    }

    /**
     * 刪除廣告圖片
     */
    public function del_imgupload()
    {
        $this->language_access(); // 多語言功能操作許可權
        $id_arr = input('del_id/a');
        $id_arr = eyIntval($id_arr);
        if(IS_POST && !empty($id_arr)){
            /*多語言*/
            $attr_name_arr = [];
            foreach ($id_arr as $key => $val) {
                $attr_name_arr[] = 'ad'.$val;
            }

            if (is_language()) {
                $new_id_arr = Db::name('language_attr')->where([
                        'attr_name'  => ['IN', $attr_name_arr],
                        'attr_group'    => 'ad',
                    ])->column('attr_value');
                !empty($new_id_arr) && $id_arr = $new_id_arr;
            }
            /*--end*/

            $r = Db::name('ad')->where([
                    'id' => ['IN', $id_arr],
                ])
                ->cache(true,null,'ad')
                ->delete();
            if ($r) {
                /*多語言*/
                if (!empty($attr_name_arr)) {
                    Db::name('language_attr')->where([
                            'attr_name' => ['IN', $attr_name_arr],
                            'attr_group'    => 'ad',
                        ])->delete();

                    Db::name('language_attribute')->where([
                            'attr_name' => ['IN', $attr_name_arr],
                            'attr_group'    => 'ad',
                        ])->delete();
                }
                /*--end*/
                adminLog('刪除廣告-id：'.implode(',', $id_arr));
            }
        }
    }

    /**
     * 刪除
     */
    public function del()
    {
        $this->language_access(); // 多語言功能操作許可權

        $id_arr = input('del_id/a');
        $id_arr = eyIntval($id_arr);
        if(IS_POST && !empty($id_arr)){
            foreach ($id_arr as $key => $val) {
                if(array_key_exists($val, $this->ad_position_system_id)){
                    $this->error('系統預定義，不能刪除');
                }
            }

            $ad_count = M('ad')->where('pid','IN',$id_arr)->count();
            if ($ad_count > 0){
                $this->error('該位置下有廣告，不允許刪除，請先刪除該位置下的廣告');
            }  

            /*多語言*/
            $attr_name_arr = [];
            foreach ($id_arr as $key => $val) {
                $attr_name_arr[] = 'adp'.$val;
            }
            if (is_language()) {
                $new_id_arr = Db::name('language_attr')->where([
                        'attr_name' => ['IN', $attr_name_arr],
                        'attr_group'    => 'ad_position',
                    ])->column('attr_value');
                !empty($new_id_arr) && $id_arr = $new_id_arr;
            }
            /*--end*/

            $r = M('ad_position')->where('id','IN',$id_arr)->delete();
            if ($r) {

                /*多語言*/
                if (!empty($attr_name_arr)) {
                    M('language_attr')->where([
                            'attr_name' => ['IN', $attr_name_arr],
                            'attr_group'    => 'ad_position',
                        ])->delete();
                    M('language_attribute')->where([
                            'attr_name' => ['IN', $attr_name_arr],
                            'attr_group'    => 'ad_position',
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
     * 同步新增廣告位置ID到多語言的模板變數里
     */
    private function syn_add_language_attribute($adp_id)
    {
        /*單語言情況下不執行多語言程式碼*/
        if (!is_language()) {
            return true;
        }
        /*--end*/

        $attr_group = 'ad_position';
        $admin_lang = $this->admin_lang;
        $main_lang = $this->main_lang;
        $languageRow = Db::name('language')->field('mark')->order('id asc')->select();
        if (!empty($languageRow) && $admin_lang == $main_lang) { // 目前語言是主體語言，即語言列表最早新增的語言
            $ad_position_db = Db::name('ad_position');
            $result = $ad_position_db->find($adp_id);
            $attr_name = 'adp'.$adp_id;
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
                    /*同步新廣告位置到其他語言廣告位置列表*/
                    if ($val['mark'] != $admin_lang) {
                        $addsaveData = $result;
                        $addsaveData['lang']  = $val['mark'];
                        $addsaveData['title'] = $val['mark'].$addsaveData['title'];
                        unset($addsaveData['id']);
                        $adp_id = $ad_position_db->insertGetId($addsaveData);
                    }
                    /*--end*/
                    
                    /*所有語言繫結在主語言的ID容器里*/
                    $data[] = [
                        'attr_name' => $attr_name,
                        'attr_value'    => $adp_id,
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

    /**
     * 同步新增廣告ID到多語言的模板變數里
     */
   private function syn_add_ad_language_attribute($ad_id)
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
                        $addsaveData['pid']   = $newPid;
                        $addsaveData['title'] = $val['mark'].$addsaveData['title'];
                        unset($addsaveData['id']);
                        $ad_id = $ad_db->insertGetId($addsaveData);
                    }
                    /*--end*/
                    
                    /*所有語言繫結在主語言的ID容器里*/
                    $data[] = [
                        'attr_name'   => $attr_name,
                        'attr_value'  => $ad_id,
                        'lang'        => $val['mark'],
                        'attr_group'  => $attr_group,
                        'add_time'    => getTime(),
                        'update_time' => getTime(),
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