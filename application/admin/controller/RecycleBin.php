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
 * Date: 2018-12-25
 */

namespace app\admin\controller;

use think\Page;
use think\Db;
use app\common\logic\ArctypeLogic;
// use app\admin\logic\RecycleBinLogic;

/**
 * 模型欄位控制器
 */
class RecycleBin extends Base
{
    public $recyclebinLogic;
    public $arctype_channel_id;
    public $arctypeLogic;

    public function _initialize() {
        parent::_initialize();
        $this->language_access(); // 多語言功能操作許可權
        $this->arctypeLogic = new ArctypeLogic(); 
        // $this->recyclebinLogic = new RecycleBinLogic();
        
        $this->arctype  = Db::name('arctype');  // 欄目數據表
        $this->archives = Db::name('archives'); // 內容數據表
        $this->config   = Db::name('config');   // 配置數據表
        $this->config_attribute     = Db::name('config_attribute');     // 自定義變數數據表
        $this->product_attribute    = Db::name('product_attribute');    // 產品屬性數據表
        $this->product_attr         = Db::name('product_attr');         // 產品屬性內容表
        $this->guestbook_attribute  = Db::name('guestbook_attribute');  // 留言表單數據表
        $this->guestbook_attr         = Db::name('guestbook_attr');         // 留言表單內容表
        $this->arctype_channel_id = config('global.arctype_channel_id');
        
    }

    /**
     * 回收站管理 - 欄目列表
     */
    public function arctype_index()
    {
        $list = array();
        $keywords = input('keywords/s');

        $condition = array();
        // 應用搜索條件
        if (!empty($keywords)) {
            $condition['a.typename'] = array('LIKE', "%{$keywords}%");
        }

        $condition['a.is_del'] = 1;
        $condition['a.lang']    = $this->admin_lang;

        $count = $this->arctype->alias('a')->where($condition)->count();// 查詢滿足要求的總記錄數
        $pageObj = new Page($count, config('paginate.list_rows'));// 實例化分頁類 傳入總記錄數和每頁顯示的記錄數
        $list = $this->arctype->alias('a')
            ->field('a.id, a.typename, a.current_channel, a.update_time')
            ->where($condition)
            ->order('a.update_time desc')
            ->limit($pageObj->firstRow.','.$pageObj->listRows)
            ->select();

        $pageStr = $pageObj->show();// 分頁顯示輸出
        $this->assign('pageStr',$pageStr);// 賦值分頁輸出
        $this->assign('list',$list);// 賦值數據集
        $this->assign('pageObj',$pageObj);// 賦值分頁對像

        // 模型列表
        $channeltype_list = getChanneltypeList();
        $this->assign('channeltype_list', $channeltype_list);

        return $this->fetch();
    }

    /**
     * 回收站管理 - 欄目還原
     */
    public function arctype_recovery()
    {
        if (IS_POST) {
            $del_id = input('post.del_id/d', 0);
            if(!empty($del_id)){
                // 目前欄目資訊
                $row = $this->arctype->field('id, parent_id, current_channel, typename')
                    ->where([
                        'id'    => $del_id,
                        'is_del'=> 1,
                    ])
                    ->find();

                if ($row) {
                    $id_arr = [$row['id']];
                    // 多語言處理邏輯
                    if (is_language()) {
                        $attr_name_arr = 'tid'.$row['id'];
                        $id_arr = Db::name('language_attr')->where([
                                'attr_name'  => $attr_name_arr,
                                'attr_group' => 'arctype',
                            ])->column('attr_value');
                        
                        $list = $this->arctype->field('id,del_method')
                            ->where([
                                'id' => ['IN', $id_arr],
                            ])
                            ->whereOr([
                                'parent_id' => ['IN', $id_arr],
                            ])->select();
                    }else{
                        $list = $this->arctype->field('id,del_method,parent_id')
                        ->where([
                            'parent_id' => ['IN', $id_arr],
                        ])
                        ->select();
                    }
                }else{
                    $this->error('參數錯誤');
                }
                
                // 需要更新的數據
                $data['is_del']     = 0; // is_del=0為正常數據
                $data['update_time']= getTime(); // 更新修改時間
                $data['del_method'] = 0; // 恢復刪除方式為預設

                // 還原數據條件邏輯
                // 欄目邏輯
                $map = 'is_del=1';
                // 多語言處理邏輯
                if (is_language()) {
                    $where = $map.' and (';
                    $ids = get_arr_column($list, 'id');
                    !empty($ids) && $where .= 'id IN ('.implode(',', $ids).') OR parent_id IN ('.implode(',', $ids).')';
                }else{
                    $where  = $map.' and (id='.$del_id.' or parent_id='.$del_id;
                    if (0 == intval($row['parent_id'])) {
                        foreach ($list as $value) {
                            if (2 == intval($value['del_method'])) {
                                $where .= ' or parent_id='.$value['id'];
                            }
                        }
                    }
                }

                $where .= ')';
                // 文章邏輯
                $where1 = $map.' and typeid in (';
                // 欄目數據更新
                $arctype = $this->arctype->where($where)->select();
                foreach ($arctype as $key => $value) {
                    $where = 'is_del=1 and ';

                    if (0 == intval($value['parent_id'])) {
                        $where .= 'id='.$value['id'];
                    }else if(0 < intval($value['parent_id'])){
                        $where .= '(id='.$value['id'].' or id='.$value['parent_id'].')';
                    }

                    if (!in_array($value['id'], $id_arr)) {
                        $where .= ' and del_method=2'; // 不是目前欄目或對應的多語言欄目，則只還原被動刪除欄目
                    }

                    $this->arctype->where($where)->update($data);

                    // 還原父級欄目，不還原主動刪除的子欄目下的文件
                    if (in_array($value['id'], $id_arr) || 2 == intval($value['del_method'])) {
                        $where1 .= $value['id'].',';
                    }
                }
                $where1 = rtrim($where1,',');
                $where1 .= ') and del_method=2';

                // 還原三級欄目時需要一併還原頂級欄目
                // 多語言處理邏輯
                if (is_language()) {
                    foreach ($list as $key => $value) {
                        $parent_id = intval($value['parent_id']);
                        if (0 < $parent_id) {
                            $where = 'id='.$parent_id;
                            $r1 = $this->arctype->where($where)->find();
                            $parent_id = intval($r1['parent_id']);
                            if (0 < $parent_id) {
                                $where = 'is_del=1 and id='.$parent_id;
                                $this->arctype->where($where)->update($data);
                            }
                        }
                    }
                }else{
                    $parent_id = intval($arctype['0']['parent_id']);
                    if (0 < $parent_id) {
                        $where = 'id='.$parent_id;
                        $r1 = $this->arctype->where($where)->find();
                        $parent_id = intval($r1['parent_id']);
                        if (0 < $parent_id) {
                            $where = 'is_del=1 and id='.$parent_id;
                            $this->arctype->where($where)->update($data);
                        }
                    }
                }

                // 內容數據更新 -  還原父級欄目，不還原主動刪除的子欄目下的文件
                $r = $this->archives->where($where1)->update($data);

                if (false !== $r) {
                    delFile(CACHE_PATH);
                    adminLog('還原欄目：'.$row['typename']);
                    $this->success('操作成功');
                }
            }
            $this->error('操作失敗');
        }
        $this->error('非法訪問');
    }

    /**
     * 回收站管理 - 欄目刪除
     */
    public function arctype_del()
    {
        // if (IS_POST) {
        //     $post = input('post.');
        //     $post['del_id'] = eyIntval($post['del_id']);

        //     /*目前欄目資訊*/
        //     $row = M('arctype')->field('id, current_channel, typename')
        //         ->where([
        //             'id'    => $post['del_id'],
        //             'lang'  => $this->admin_lang,
        //         ])
        //         ->find();
            
        //     $r = model('arctype')->del($post['del_id']);
        //     if (false !== $r) {
        //         adminLog('刪除欄目：'.$row['typename']);
        //         $this->success('刪除成功');
        //     } else {
        //         $this->error('刪除失敗');
        //     }
        // }
        // $this->error('非法訪問');

        if (IS_POST) {
            $del_id = input('post.del_id/d', 0);
            if(!empty($del_id)){
                // 目前欄目資訊
                $row = $this->arctype->field('id, parent_id, current_channel, typename')
                    ->where([
                        'id'    => $del_id,
                        'is_del'=> 1,
                    ])
                    ->find();
                if ($row) {
                    $id_arr = $row['id'];
                    // 多語言處理邏輯
                    if (is_language()) {
                        $attr_name_arr = 'tid'.$row['id'];
                        $id_arr = Db::name('language_attr')->where([
                                'attr_name'  => $attr_name_arr,
                                'attr_group' => 'arctype',
                            ])->column('attr_value');
                        
                        $list = $this->arctype->field('id,del_method')
                            ->where([
                                'id' => ['IN', $id_arr],
                            ])
                            ->whereOr([
                                'parent_id' => ['IN', $id_arr],
                            ])->select();
                    }else{
                        $list = $this->arctype->field('id,del_method')
                        ->where([
                            'parent_id' => ['IN', $id_arr],
                        ])
                        ->select();
                    }
                }else{
                    $this->error('參數錯誤');
                }

                // 刪除條件邏輯
                // 欄目邏輯
                $map = 'is_del=1';
                // 多語言處理邏輯
                if (is_language()) {
                    $where = $map.' and (';
                    $ids = get_arr_column($list, 'id');
                    !empty($ids) && $where .= 'id IN ('.implode(',', $ids).') OR parent_id IN ('.implode(',', $ids).')';
                }else{
                    $ids = [$del_id];
                    $where  = $map.' and (id='.$del_id.' or parent_id='.$del_id;
                    if (0 == intval($row['parent_id'])) {
                        foreach ($list as $value) {
                            if (2 == intval($value['del_method'])) {
                                $where .= ' or parent_id='.$value['id'];
                            }
                        }
                    }
                }
                $where .= ')';

                // 文章邏輯
                $where1 = $map.' and typeid in (';

                // 查詢欄目回收站數據並拼裝刪除文章邏輯
                $arctype  = $this->arctype->field('id')->where($where)->select();
                foreach ($arctype as $key => $value) {
                    $where1 .= $value['id'].',';
                }
                $where1 = rtrim($where1,',');
                $where1 .= ')';
                
                // 欄目數據刪除
                $r = $this->arctype->where($where)->delete();
                if($r){
                    // Tag標籤刪除
                    Db::name('tagindex')->where([
                            'typeid'    => ['IN', $ids],
                        ])->delete();
                    // 內容數據刪除
                    $r = $this->archives->where($where1)->delete();
                    $msg = '';
                    if (!$r) {
                        $msg = '，文件清空失敗！';
                    }
                    adminLog('刪除欄目：'.$row['typename']);
                    $this->success("操作成功".$msg);
                }
            }
            $this->error("操作失敗!");
        }
        $this->error('非法訪問');
    }
    
    /**
     * 回收站管理 - 內容列表
     */
    public function archives_index()
    {
        $assign_data = array();
        $condition = array();
        // 獲取到所有URL參數
        $param = input('param.');

        // 應用搜索條件
        foreach (['keywords','typeid'] as $key) {
            if (isset($param[$key]) && $param[$key] !== '') {
                if ($key == 'keywords') {
                    $condition['a.title'] = array('LIKE', "%{$param[$key]}%");
                } else {
                    $condition['a.'.$key] = array('eq', $param[$key]);
                }
            }
        }

        $condition['a.channel'] = array('neq', 6); // 排除單頁模型

        /*多語言*/
        $condition['a.lang'] = array('eq', $this->admin_lang);
        /*--end*/

        $condition['a.is_del'] = array('eq', 1); // 回收站功能

        /**
         * 數據查詢，搜索出主鍵ID的值
         */
        $count = $this->archives->alias('a')->where($condition)->count('aid');// 查詢滿足要求的總記錄數
        $pageObj = new Page($count, config('paginate.list_rows'));// 實例化分頁類 傳入總記錄數和每頁顯示的記錄數
        $list = $this->archives->field("a.aid,a.channel")
            ->alias('a')
            ->where($condition)
            ->order('a.aid desc')
            ->limit($pageObj->firstRow.','.$pageObj->listRows)
            ->getAllWithIndex('aid');

        /**
         * 完善數據集資訊
         * 在數據量大的情況下，經過優化的搜索邏輯，先搜索出主鍵ID，再通過ID將其他資訊補充完整；
         */
        if ($list) {
            $aids = array_keys($list);
            $fields = "a.aid, a.title, a.typeid, a.litpic, a.update_time, b.typename";
            $row = DB::name('archives')
                ->field($fields)
                ->alias('a')
                ->join('__ARCTYPE__ b', 'a.typeid = b.id', 'LEFT')
                ->where('a.aid', 'in', $aids)
                ->getAllWithIndex('aid');

            foreach ($list as $key => $val) {
                $row[$val['aid']]['litpic'] = handle_subdir_pic($row[$val['aid']]['litpic']); // 支援子目錄
                $list[$key] = $row[$val['aid']];
            }
        }
        $pageStr = $pageObj->show(); // 分頁顯示輸出
        $assign_data['pageStr'] = $pageStr; // 賦值分頁輸出
        $assign_data['list'] = $list; // 賦值數據集
        $assign_data['pageObj'] = $pageObj; // 賦值分頁對像

        $this->assign($assign_data);
        return $this->fetch();
    }

    /**
     * 回收站管理 - 內容還原
     */
    public function archives_recovery()
    {
        $id_arr = input('del_id/a');
        $id_arr = eyIntval($id_arr);
        if(IS_POST && !empty($id_arr)){

            // 目前文件資訊
            $row = $this->archives->field('aid, title, typeid')
                ->where([
                    'aid'   => ['IN', $id_arr],
                    'is_del'    => 1,
                    'lang'  => $this->admin_lang,
                ])
                ->select();
            if (!empty($row)) {
                $id_arr = get_arr_column($row, 'aid');

                // 關聯的欄目ID集合
                $typeids = [];
                $typeidArr = get_arr_column($row, 'typeid');
                $typeidArr = array_unique($typeidArr);
                foreach ($typeidArr as $key => $val) {
                    $pidArr = model('Arctype')->getAllPid($val);
                    $typeids = array_merge($typeids, get_arr_column($pidArr, 'id'));
                }
                $typeids = array_unique($typeids);

                if (!empty($typeids)) {
                    // 多語言處理邏輯
                    if (is_language()) {
                        $attr_name_arr = [];
                        foreach ($typeids as $key => $val) {
                            array_push($attr_name_arr, 'tid'.$val);
                        }
                        $attr_value = Db::name('language_attr')->where([
                                'attr_name'  => ['IN', $attr_name_arr],
                                'attr_group' => 'arctype',
                            ])->column('attr_value');
                        $attr_value && $typeids = $attr_value;
                    }

                    // 還原數據
                    $r = $this->arctype->where([
                            'id'    => ['IN', $typeids],
                        ])
                        ->update([
                            'is_del'    => 0,
                            'del_method'    => 0,
                            'update_time'   => getTime(),
                        ]);
                    if ($r) {
                        $r2 = $this->archives->where([
                                'aid'   => ['IN', $id_arr],
                            ])
                            ->update([
                                'is_del'    => 0,
                                'del_method'    => 0,
                                'update_time'   => getTime(),
                            ]);
                        if ($r2) {
                            delFile(CACHE_PATH);
                            adminLog('還原文件：'.implode('|', get_arr_column($row, 'title')));
                            $this->success('操作成功');
                        } else {
                            $this->success('關聯欄目還原成功，文件還原失敗！');
                        }
                    }
                    $this->error('操作失敗');
                }
            }
            $this->error('參數有誤');
        }
        $this->error('非法訪問');
    }

    /**
     * 回收站管理 - 內容刪除
     */
    public function archives_del()
    {
        $id_arr = input('del_id/a');
        $id_arr = eyIntval($id_arr);
        if(IS_POST && !empty($id_arr)){
            // 目前文件資訊
            $row = $this->archives->field('aid, title, channel')
                ->where([
                    'aid'   => ['IN', $id_arr],
                    'is_del'    => 1,
                    'lang'  => $this->admin_lang,
                ])
                ->select();
            if (!empty($row)) {
                $id_arr = get_arr_column($row, 'aid');

                // 內容數據刪除
                $r = $this->archives->where([
                        'aid'   => ['IN', $id_arr],
                    ])
                    ->delete();
                if($r){
                    /*按模型分組，然後進行分組刪除*/
                    $row2Group = group_same_key($row, 'channel');
                    if (!empty($row2Group)) {
                        $channelids = array_keys($row2Group);
                        $channeltypeRow = Db::name('channeltype')->field('id,table')
                            ->where([
                                'id'    => ['IN', $channelids],
                            ])->getAllWithIndex('id');
                        foreach ($row2Group as $key => $val) {
                            $table = $channeltypeRow[$key]['table'];
                            $aidarr_tmp = get_arr_column($val, 'aid');
                            model($table)->afterDel($id_arr);
                        }
                    }
                    /*--end*/

                    adminLog('刪除文件：'.implode('|', get_arr_column($row, 'title')));
                    $this->success("操作成功!");
                }
                $this->error("操作失敗!");
            }
        }
        $this->error("參數有誤!");
    }

    /**
     * 回收站管理 - 自定義變數列表
     */
    public function customvar_index()
    {
        $list = array();
        $keywords = input('keywords/s');

        $condition = array();
        // 應用搜索條件
        if (!empty($keywords)) {
            $condition['a.attr_name'] = array('LIKE', "%{$keywords}%");
        }

        $attr_var_names = M('config')->field('name')
            ->where([
                'is_del'    => 1,
                'lang'  => $this->admin_lang,
            ])->getAllWithIndex('name');
        $condition['a.attr_var_name'] = array('IN', array_keys($attr_var_names));
        $condition['a.lang']    = $this->admin_lang;

        $count = M('config_attribute')->alias('a')->where($condition)->count();// 查詢滿足要求的總記錄數
        $pageObj = new Page($count, config('paginate.list_rows'));// 實例化分頁類 傳入總記錄數和每頁顯示的記錄數
        $list = M('config_attribute')->alias('a')
            ->field('a.*, b.id')
            ->join('__CONFIG__ b', 'b.name = a.attr_var_name AND a.lang = b.lang', 'LEFT')
            ->where($condition)
            ->order('a.update_time desc')
            ->limit($pageObj->firstRow.','.$pageObj->listRows)
            ->select();

        $pageStr = $pageObj->show();// 分頁顯示輸出
        $this->assign('pageStr',$pageStr);// 賦值分頁輸出
        $this->assign('list',$list);// 賦值數據集
        $this->assign('pageObj',$pageObj);// 賦值分頁對像

        return $this->fetch();
    }

    /**
     * 回收站管理 - 自定義變數還原
     */
    public function customvar_recovery()
    {
        if (IS_POST) {
            $id_arr = input('del_id/a');
            $id_arr = eyIntval($id_arr);
            if(!empty($id_arr)){
                $attr_var_name = $this->config->where([
                        'id'    => ['IN', $id_arr],
                        'lang'  => $this->admin_lang,
                        'is_del'    => 1,
                    ])->column('name');

                $r = $this->config->where('name', 'IN', $attr_var_name)->update([
                        'is_del'    => 0,
                        'update_time'   => getTime(),
                    ]);
                if($r){
                    delFile(CACHE_PATH, true);
                    adminLog('還原自定義變數：'.implode(',', $attr_var_name));
                    $this->success("操作成功!");
                }else{
                    $this->error("操作失敗!");
                }
            }
        }
        $this->error("參數有誤!");
    }

    /**
     * 回收站管理 - 自定義變數刪除
     */
    public function customvar_del()
    {
        if (IS_POST) {
            $id_arr = input('del_id/a');
            $id_arr = eyIntval($id_arr);
            if(!empty($id_arr)){
                $attr_var_name = $this->config->where([
                        'id'    => ['IN', $id_arr],
                        'lang'  => $this->admin_lang,
                        'is_del'    => 1,
                    ])->column('name');

                $r = $this->config->where('name', 'IN', $attr_var_name)->delete();
                if($r){
                    // 同時刪除
                    $this->config_attribute->where('attr_var_name', 'IN', $attr_var_name)->delete();
                    adminLog('徹底刪除自定義變數：'.implode(',', $attr_var_name));
                    $this->success("操作成功!");
                }else{
                    $this->error("操作失敗!");
                }
            }
        }
        $this->error("參數有誤!");
    }

    /**
     * 回收站管理 - 產品屬性列表
     */
    public function proattr_index()
    {
        $list = array();
        $condition = array();
        // 獲取到所有URL參數
        $param = input('param.');

        // 應用搜索條件
        foreach (['keywords','typeid'] as $key) {
            if (isset($param[$key]) && $param[$key] !== '') {
                if ($key == 'keywords') {
                    $condition['a.attr_name'] = array('LIKE', "%{$param[$key]}%");
                } else {
                    $condition['a.'.$key] = array('eq', $param[$key]);
                }
            }
        }

        /*多語言*/
        $condition['a.lang'] = $this->admin_lang;
        /*--end*/

        $condition['a.is_del'] = array('eq', 1); // 回收站功能

        $count = $this->product_attribute->alias('a')->where($condition)->count();// 查詢滿足要求的總記錄數
        $pageObj = new Page($count, config('paginate.list_rows'));// 實例化分頁類 傳入總記錄數和每頁顯示的記錄數
        $list = $this->product_attribute->alias('a')
            ->field('a.*, b.typename')
            ->join('__ARCTYPE__ b', 'a.typeid = b.id', 'LEFT')
            ->where($condition)
            ->order('a.update_time desc')
            ->limit($pageObj->firstRow.','.$pageObj->listRows)
            ->select();

        $pageStr = $pageObj->show();// 分頁顯示輸出
        $this->assign('pageStr',$pageStr);// 賦值分頁輸出
        $this->assign('list',$list);// 賦值數據集
        $this->assign('pageObj',$pageObj);// 賦值分頁對像

        return $this->fetch();
    }

    /**
     * 回收站管理 - 產品屬性還原
     */
    public function proattr_recovery()
    {
        $id_arr = input('del_id/a');
        $id_arr = eyIntval($id_arr);
        if(IS_POST && !empty($id_arr)){

            /*檢測關聯欄目是否已被偽刪除*/
            $row1 = $this->product_attribute->field('attr_id, typeid')
                ->where([
                    'attr_id'   => ['IN', $id_arr],
                ])->select();
            $row2 = $this->arctype->field('typename')
                ->where([
                    'id'    => ['IN', get_arr_column($row1, 'typeid')],
                    'is_del'    => 1,
                ])
                ->select();
            if (!empty($row2)) {
                $this->error('請先還原關聯欄目：<font color="red">'.implode(' , ', get_arr_column($row2, 'typename')).'</font>');
            }
            /*--end*/
            
            // 多語言處理邏輯
            if (is_language()) {
                $attr_name_arr = [];
                foreach ($id_arr as $key => $val) {
                    array_push($attr_name_arr, 'attr_'.$val);
                }
                $id_arr = Db::name('language_attr')->where([
                        'attr_name'  => ['IN', $attr_name_arr],
                        'attr_group' => 'product_attribute',
                    ])->column('attr_value');
            }

            $row = $this->product_attribute->field('attr_id, attr_name')
                ->where([
                    'attr_id'   => ['IN', $id_arr],
                ])->select();
            $id_arr = get_arr_column($row, 'attr_id');

            // 更新數據
            $r = $this->product_attribute->where([
                    'attr_id'   => ['IN', $id_arr],
                ])->update([
                    'is_del'    => 0,
                    'update_time'   => getTime(),
                ]);
            if($r){
                adminLog('還原產品參數：'.implode(',', get_arr_column($row, 'attr_name')));
                $this->success("操作成功!");
            }
            $this->error("操作失敗!");
        }
        $this->error("參數有誤!");
    }

    /**
     * 回收站管理 - 產品屬性刪除
     */
    public function proattr_del()
    {
        $id_arr = input('del_id/a');
        $id_arr = eyIntval($id_arr);
        if(IS_POST && !empty($id_arr)){
            // 多語言處理邏輯
            if (is_language()) {
                $attr_name_arr = [];
                foreach ($id_arr as $key => $val) {
                    array_push($attr_name_arr, 'attr_'.$val);
                }
                $id_arr = Db::name('language_attr')->where([
                        'attr_name'  => ['IN', $attr_name_arr],
                        'attr_group' => 'product_attribute',
                    ])->column('attr_value');
            }

            $row = $this->product_attribute->field('attr_id, attr_name')
                ->where([
                    'attr_id'   => ['IN', $id_arr],
                ])->select();
            $id_arr = get_arr_column($row, 'attr_id');

            // 產品屬性刪除
            $r = $this->product_attribute->where([
                    'attr_id'   => ['IN', $id_arr],
                ])->delete();
            if($r){
                // 同時刪除
                $this->product_attr->where([
                        'attr_id'   => ['IN', $id_arr],
                    ])->delete();
                adminLog('刪除產品參數：'.implode(',', get_arr_column($row, 'attr_name')));
                $this->success("操作成功!");
            }
            $this->error("操作失敗!");
        }
        $this->error("參數有誤!");
    }

    /**
     * 回收站管理 - 留言屬性列表
     */
    public function gbookattr_index()
    {
        $list = array();
        $condition = array();
        // 獲取到所有URL參數
        $param = input('param.');

        // 應用搜索條件
        foreach (['keywords','typeid'] as $key) {
            if (isset($param[$key]) && $param[$key] !== '') {
                if ($key == 'keywords') {
                    $condition['a.attr_name'] = array('LIKE', "%{$param[$key]}%");
                } else {
                    $condition['a.'.$key] = array('eq', $param[$key]);
                }
            }
        }

        /*多語言*/
        $condition['a.lang'] = $this->admin_lang;
        /*--end*/

        $condition['a.is_del'] = array('eq', 1); // 回收站功能

        $count = $this->guestbook_attribute->alias('a')->where($condition)->count();// 查詢滿足要求的總記錄數
        $pageObj = new Page($count, config('paginate.list_rows'));// 實例化分頁類 傳入總記錄數和每頁顯示的記錄數
        $list = $this->guestbook_attribute->alias('a')
            ->field('a.*, b.typename')
            ->join('__ARCTYPE__ b', 'a.typeid = b.id', 'LEFT')
            ->where($condition)
            ->order('a.update_time desc')
            ->limit($pageObj->firstRow.','.$pageObj->listRows)
            ->select();

        $pageStr = $pageObj->show();// 分頁顯示輸出
        $this->assign('pageStr',$pageStr);// 賦值分頁輸出
        $this->assign('list',$list);// 賦值數據集
        $this->assign('pageObj',$pageObj);// 賦值分頁對像

        return $this->fetch();
    }

    /**
     * 回收站管理 - 留言屬性還原
     */
    public function gbookattr_recovery()
    {
        $id_arr = input('del_id/a');
        $id_arr = eyIntval($id_arr);
        if(IS_POST && !empty($id_arr)){

            /*檢測關聯欄目是否已被偽刪除*/
            $row1 = $this->guestbook_attribute->field('attr_id, typeid')
                ->where([
                    'attr_id'   => ['IN', $id_arr],
                ])->select();
            $row2 = $this->arctype->field('typename')
                ->where([
                    'id'    => ['IN', get_arr_column($row1, 'typeid')],
                    'is_del'    => 1,
                ])
                ->select();
            if (!empty($row2)) {
                $this->error('請先還原關聯欄目：<font color="red">'.implode(' , ', get_arr_column($row2, 'typename')).'</font>');
            }
            /*--end*/

            // 多語言處理邏輯
            if (is_language()) {
                $attr_name_arr = [];
                foreach ($id_arr as $key => $val) {
                    array_push($attr_name_arr, 'attr_'.$val);
                }
                $id_arr = Db::name('language_attr')->where([
                        'attr_name'  => ['IN', $attr_name_arr],
                        'attr_group' => 'guestbook_attribute',
                    ])->column('attr_value');
            }

            $row = $this->guestbook_attribute->field('attr_id, attr_name')
                ->where([
                    'attr_id'   => ['IN', $id_arr],
                ])->select();
            $id_arr = get_arr_column($row, 'attr_id');

            // 更新數據
            $r = $this->guestbook_attribute->where([
                    'attr_id'   => ['IN', $id_arr],
                ])->update([
                    'is_del'    => 0,
                    'update_time'   => getTime(),
                ]);
            if($r){
                adminLog('還原留言表單：'.implode(',', get_arr_column($row, 'attr_name')));
                $this->success("操作成功!");
            }
            $this->error("操作失敗!");
        }
        $this->error("參數有誤!");
    }

    /**
     * 回收站管理 - 留言屬性刪除
     */
    public function gbookattr_del()
    {
        $id_arr = input('del_id/a');
        $id_arr = eyIntval($id_arr);
        if(IS_POST && !empty($id_arr)){
            // 多語言處理邏輯
            if (is_language()) {
                $attr_name_arr = [];
                foreach ($id_arr as $key => $val) {
                    array_push($attr_name_arr, 'attr_'.$val);
                }
                $id_arr = Db::name('language_attr')->where([
                        'attr_name'  => ['IN', $attr_name_arr],
                        'attr_group' => 'guestbook_attribute',
                    ])->column('attr_value');
            }

            $row = $this->guestbook_attribute->field('attr_id, attr_name')
                ->where([
                    'attr_id'   => ['IN', $id_arr],
                ])->select();
            $id_arr = get_arr_column($row, 'attr_id');

            // 產品屬性刪除
            $r = $this->guestbook_attribute->where([
                    'attr_id'   => ['IN', $id_arr],
                ])->delete();
            if($r){
                // 同時刪除
                $this->guestbook_attr->where([
                        'attr_id'   => ['IN', $id_arr],
                    ])->delete();
                adminLog('刪除留言表單：'.implode(',', get_arr_column($row, 'attr_name')));
                $this->success("操作成功!");
            }
            $this->error("操作失敗!");
        }
        $this->error("參數有誤!");
    }
}