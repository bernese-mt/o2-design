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
use app\admin\logic\FieldLogic;

/**
 * 模型欄位控制器
 */
class Field extends Base
{
    public $fieldLogic;
    public $arctype_channel_id;

    public function _initialize() {
        parent::_initialize();
        $this->language_access(); // 多語言功能操作許可權
        $this->fieldLogic = new FieldLogic();
        $this->arctype_channel_id = config('global.arctype_channel_id');
    }

    /**
     * 模型欄位管理
     */
    public function channel_index()
    {
        /*同步欄目繫結的自定義欄位*/
        $this->syn_channelfield_bind();
        /*--end*/

        $channel_id = input('param.channel_id/d', 1);
        $assign_data = array();
        $condition = array();
        // 獲取到所有GET參數
        $param = input('param.');

        /*同步更新附加表字段到自定義模型欄位表中*/
        if (empty($param['searchopt'])) {
            $this->fieldLogic->synChannelTableColumns($channel_id);
        }
        /*--end*/

        // 應用搜索條件
        foreach (['keywords'] as $key) {
            if (isset($param[$key]) && $param[$key] !== '') {
                if ($key == 'keywords') {
                    $condition['a.name'] = array('LIKE', "%{$param[$key]}%");
                    // 過濾指定欄位
                    // $banFields = ['id'];
                    // $condition['a.name'] = array(
                    //     array('LIKE', "%{$param[$key]}%"),
                    //     array('notin', $banFields),
                    // );
                } else {
                    $condition['a.'.$key] = array('eq', $param[$key]);
                }
            }
        }

        /*顯示主表與附加表*/
        $condition['a.channel_id'] = array('IN', [$channel_id]);

        /*模型列表*/
        $channeltype_list = model('Channeltype')->getAll('*', [], 'id');
        $assign_data['channeltype_list'] = $channeltype_list;
        /*--end*/

        if (1 == $channeltype_list[$channel_id]['ifsystem']) {
            $condition['a.ifmain'] = 0;
        } else {
            $condition['a.ifcontrol'] = 0;
        }

        $cfieldM =  M('channelfield');
        $count = $cfieldM->alias('a')->where($condition)->count('a.id');// 查詢滿足要求的總記錄數
        $Page = $pager = new Page($count, config('paginate.list_rows'));// 實例化分頁類 傳入總記錄數和每頁顯示的記錄數
        $list = $cfieldM->field('a.*')
            ->alias('a')
            ->where($condition)
            ->order('a.sort_order asc, a.ifmain asc, a.ifcontrol asc, a.id desc')
            ->limit($Page->firstRow.','.$Page->listRows)
            ->select();

        $show = $Page->show();// 分頁顯示輸出
        $assign_data['page'] = $show; // 賦值分頁輸出
        $assign_data['list'] = $list; // 賦值數據集
        $assign_data['pager'] = $Page; // 賦值分頁對像

        /*欄位型別列表*/
        $assign_data['fieldtypeList'] = M('field_type')->field('name,title')->getAllWithIndex('name');
        /*--end*/

        /*模型ID*/
        $assign_data['channel_id'] = $channel_id;
        /*--end*/

        $this->assign($assign_data);
        return $this->fetch();
    }

    /**
     * 同步欄目繫結的自定義欄位
     */
    private function syn_channelfield_bind()
    {
        $field_ids = Db::name('channelfield')->where([
                'ifmain'  => 0,
                'channel_id'=> ['NEQ', -99],
            ])->column('id');
        if (!empty($field_ids)) {
            $totalRow = Db::name('channelfield_bind')->count();
            if (empty($totalRow)) {
                $sveData = [];
                foreach ($field_ids as $key => $val) {
                    $sveData[] = [
                        'typeid'        => 0,
                        'field_id'      => $val,
                        'add_time'      => getTime(),
                        'update_time'   => getTime(),
                    ];
                }
                model('ChannelfieldBind')->saveAll($sveData);
            }
        }
    }
    
    /**
     * 新增-模型欄位
     */
    public function channel_add()
    {
        $channel_id = input('param.channel_id/d', 0);
        if (empty($channel_id)) {
            $this->error('參數有誤！');
        }

        if (IS_POST) {
            $post = input('post.', '', 'trim');

            if (empty($post['dtype']) || empty($post['title']) || empty($post['name'])) {
                $this->error("缺少必填資訊！");
            }

            if (1 == preg_match('/^([_]+|[0-9]+)$/', $post['name'])) {
                $this->error("欄位名稱格式不正確！");
            } else if (preg_match('/^type/', $post['name'])) {
                $this->error("模型欄位名稱不允許以type開頭！");
            }

            /*去除中文逗號，過濾左右空格與空值*/
            $dfvalue = str_replace('，', ',', $post['dfvalue']);
            $dfvalueArr = explode(',', $dfvalue);
            foreach ($dfvalueArr as $key => $val) {
                $tmp_val = trim($val);
                if ('' == $tmp_val) {
                    unset($dfvalueArr[$key]);
                    continue;
                }
                $dfvalueArr[$key] = trim($val);
            }
            $dfvalue = implode(',', $dfvalueArr);
            /*--end*/

            /*預設值必填欄位*/
            $fieldtype_list = model('Field')->getFieldTypeAll('name,title,ifoption', 'name');
            if (isset($fieldtype_list[$post['dtype']]) && 1 == $fieldtype_list[$post['dtype']]['ifoption']) {
                if (empty($dfvalue)) {
                    $this->error("你設定了欄位為【".$fieldtype_list[$post['dtype']]['title']."】型別，預設值不能為空！ ");
                }
            }
            /*--end*/

            /*目前模型對應的數據表*/
            $table = M('channeltype')->where('id',$channel_id)->getField('table');
            $table = PREFIX.$table.'_content';
            /*--end*/

            /*檢測欄位是否存在於主表與附加表中*/
            if (true == $this->fieldLogic->checkChannelFieldList($table, $post['name'], $channel_id)) {
                $this->error("欄位名稱 ".$post['name']." 與系統欄位衝突！");
            }
            /*--end*/

            if (empty($post['typeids'])) {
                $this->error('請選擇可見欄目！');
            }

            /*組裝完整的SQL語句，並執行新增欄位*/
            $fieldinfos = $this->fieldLogic->GetFieldMake($post['dtype'], $post['name'], $dfvalue, $post['title']);
            $ntabsql = $fieldinfos[0];
            $buideType = $fieldinfos[1];
            $maxlength = $fieldinfos[2];
            $sql = " ALTER TABLE `$table` ADD  $ntabsql ";
            if (false !== Db::execute($sql)) {
                /*儲存新增欄位的記錄*/
                $newData = array(
                    'dfvalue'   => $dfvalue,
                    'maxlength' => $maxlength,
                    'define'  => $buideType,
                    'ifcontrol' => 0,
                    'sort_order'    => 100,
                    'add_time' => getTime(),
                    'update_time' => getTime(),
                );
                $data = array_merge($post, $newData);
                M('channelfield')->save($data);
                $field_id = M('channelfield')->getLastInsID();
                /*--end*/
                
                /*儲存欄目與欄位繫結的記錄*/
                $typeids = $post['typeids'];
                if (!empty($typeids)) {
                    /*多語言*/
                    if (is_language()) {
                        $attr_name_arr = [];
                        foreach ($typeids as $key => $val) {
                            $attr_name_arr[] = 'tid'.$val;
                        }
                        $new_typeid_arr = Db::name('language_attr')->where([
                                'attr_name' => ['IN', $attr_name_arr],
                                'attr_group'    => 'arctype',
                            ])->column('attr_value');
                        !empty($new_typeid_arr) && $typeids = $new_typeid_arr;
                    }
                    /*--end*/
                    $addData = [];
                    foreach ($typeids as $key => $val) {
                        if (1 < count($typeids) && empty($val)) {
                            continue;
                        }
                        $addData[] = [
                            'typeid'        => $val,
                            'field_id'      => $field_id,
                            'add_time'      => getTime(),
                            'update_time'   => getTime(),
                        ];
                    }
                    !empty($addData) && model('ChannelfieldBind')->saveAll($addData);
                }
                /*--end*/
                
                /*重新產生數據表字段快取檔案*/
                try {
                    schemaTable($table);
                } catch (\Exception $e) {}
                /*--end*/

                \think\Cache::clear('channelfield');
                $this->success("操作成功！", url('Field/channel_index', array('channel_id'=>$channel_id)));
            }
            $this->error('操作失敗');
        }

        /*欄位型別列表*/
        $assign_data['fieldtype_list'] = model('Field')->getFieldTypeAll('name,title,ifoption');
        /*--end*/

        /*允許發佈文件列表的欄目*/
        $select_html = allow_release_arctype(0, [$channel_id]);
        $this->assign('select_html',$select_html);
        /*--end*/
        
        /*模型ID*/
        $assign_data['channel_id'] = $channel_id;
        /*--end*/

        $this->assign($assign_data);
        return $this->fetch();
    }
    
    /**
     * 編輯-模型欄位
     */
    public function channel_edit()
    {
        $channel_id = input('param.channel_id/d', 0);
        if (empty($channel_id)) {
            $this->error('參數有誤！');
        }

        if (IS_POST) {
            $post = input('post.', '', 'trim');

            if (empty($post['dtype']) || empty($post['title']) || empty($post['name'])) {
                $this->error("缺少必填資訊！");
            }

            if (1 == preg_match('/^([_]+|[0-9]+)$/', $post['name'])) {
                $this->error("欄位名稱格式不正確！");
            } else if (preg_match('/^type/', $post['name'])) {
                $this->error("模型欄位名稱不允許以type開頭！");
            }

            $info = model('Channelfield')->getInfo($post['id'], 'ifsystem');
            if (!empty($info['ifsystem'])) {
                $this->error('系統欄位不允許更改！');
            }

            $old_name = $post['old_name'];
            /*去除中文逗號，過濾左右空格與空值*/
            $dfvalue = str_replace('，', ',', $post['dfvalue']);
            $dfvalueArr = explode(',', $dfvalue);
            foreach ($dfvalueArr as $key => $val) {
                $tmp_val = trim($val);
                if ('' == $tmp_val) {
                    unset($dfvalueArr[$key]);
                    continue;
                }
                $dfvalueArr[$key] = trim($val);
            }
            $dfvalue = implode(',', $dfvalueArr);
            /*--end*/

            /*預設值必填欄位*/
            $fieldtype_list = model('Field')->getFieldTypeAll('name,title,ifoption', 'name');
            if (isset($fieldtype_list[$post['dtype']]) && 1 == $fieldtype_list[$post['dtype']]['ifoption']) {
                if (empty($dfvalue)) {
                    $this->error("你設定了欄位為【".$fieldtype_list[$post['dtype']]['title']."】型別，預設值不能為空！ ");
                }
            }
            /*--end*/

            /*目前模型對應的數據表*/
            $table = M('channeltype')->where('id',$post['channel_id'])->getField('table');
            $table = PREFIX.$table.'_content';
            /*--end*/

            /*檢測欄位是否存在於主表與附加表中*/
            if (true == $this->fieldLogic->checkChannelFieldList($table, $post['name'], $channel_id, array($old_name))) {
                $this->error("欄位名稱 ".$post['name']." 與系統欄位衝突！");
            }
            /*--end*/

            if (empty($post['typeids'])) {
                $this->error('請選擇可見欄目！');
            }

            /*組裝完整的SQL語句，並執行編輯欄位*/
            $fieldinfos = $this->fieldLogic->GetFieldMake($post['dtype'], $post['name'], $dfvalue, $post['title']);
            $ntabsql = $fieldinfos[0];
            $buideType = $fieldinfos[1];
            $maxlength = $fieldinfos[2];
            $sql = " ALTER TABLE `$table` CHANGE COLUMN `{$old_name}` $ntabsql ";
            if (false !== Db::execute($sql)) {
                /*儲存更新欄位的記錄*/
                $newData = array(
                    'dfvalue'   => $dfvalue,
                    'maxlength' => $maxlength,
                    'define'  => $buideType,
                    'update_time' => getTime(),
                );
                $data = array_merge($post, $newData);
                M('channelfield')->where('id',$post['id'])->cache(true,null,"channelfield")->save($data);
                /*--end*/
                
                /*儲存欄目與欄位繫結的記錄*/
                $field_id = $post['id'];
                model('ChannelfieldBind')->where(['field_id'=>$field_id])->delete();
                $typeids = $post['typeids'];
                if (!empty($typeids)) {
                    /*多語言*/
                    if (is_language()) {
                        $attr_name_arr = [];
                        foreach ($typeids as $key => $val) {
                            $attr_name_arr[] = 'tid'.$val;
                        }
                        $new_typeid_arr = Db::name('language_attr')->where([
                                'attr_name' => ['IN', $attr_name_arr],
                                'attr_group'    => 'arctype',
                            ])->column('attr_value');
                        !empty($new_typeid_arr) && $typeids = $new_typeid_arr;
                    }
                    /*--end*/
                    $addData = [];
                    foreach ($typeids as $key => $val) {
                        if (1 < count($typeids) && empty($val)) {
                            continue;
                        }
                        $addData[] = [
                            'typeid'        => $val,
                            'field_id'      => $field_id,
                            'add_time'      => getTime(),
                            'update_time'   => getTime(),
                        ];
                    }
                    !empty($addData) && model('ChannelfieldBind')->saveAll($addData);
                }
                /*--end*/

                /*重新產生數據表字段快取檔案*/
                try {
                    schemaTable($table);
                } catch (\Exception $e) {}
                /*--end*/

                $this->success("操作成功！", url('Field/channel_index', array('channel_id'=>$post['channel_id'])));
            } else {
                $sql = " ALTER TABLE `$table` ADD  $ntabsql ";
                if (false === Db::execute($sql)) {
                    $this->error('操作失敗！');
                }
            }
        }

        $id = input('param.id/d', 0);
        $info = array();
        if (!empty($id)) {
            $info = model('Channelfield')->getInfo($id);
        }
        if (!empty($info['ifsystem'])) {
            $this->error('系統欄位不允許更改！');
        }
        $assign_data['info'] = $info;

        /*欄位型別列表*/
        $assign_data['fieldtype_list'] = model('Field')->getFieldTypeAll('name,title,ifoption');
        /*--end*/

        /*允許發佈文件列表的欄目*/
        $typeids = Db::name('channelfield_bind')->where(['field_id'=>$id])->column('typeid');
        $select_html = allow_release_arctype($typeids, [$channel_id]);
        $this->assign('select_html',$select_html);
        $this->assign('typeids',$typeids);
        /*--end*/
        
        /*模型ID*/
        $assign_data['channel_id'] = $channel_id;
        /*--end*/

        $this->assign($assign_data);
        return $this->fetch();
    }
    
    /**
     * 刪除-模型欄位
     */
    public function channel_del()
    {
        $channel_id = input('channel_id/d', 0);
        $id = input('del_id/d', 0);
        if(!empty($id)){
            /*刪除表字段*/
            $row = $this->fieldLogic->delChannelField($id);
            /*--end*/
            if (0 < $row['code']) {
                $map = array(
                    'id'    => array('eq', $id),
                    'channel_id'    => $channel_id,
                );
                $result = M('channelfield')->field('channel_id,name')->where($map)->select();
                $name_list = get_arr_column($result, 'name');
                /*刪除欄位的記錄*/
                M('channelfield')->where($map)->delete();
                /*--end*/
                /*刪除欄目與欄位繫結的記錄*/
                model('ChannelfieldBind')->where(['field_id'=>$id])->delete();
                /*--end*/

                /*獲取模型標題*/
                $channel_title = '';
                if (!empty($channel_id)) {
                    $channel_title = M('channeltype')->where('id',$channel_id)->getField('title');
                }
                /*--end*/
                adminLog('刪除'.$channel_title.'欄位：'.implode(',', $name_list));
                $this->success('刪除成功');
            }

            \think\Cache::clear('channelfield');
            respose(array('status'=>0, 'msg'=>$row['msg']));

        }else{
            $this->error('參數有誤');
        }
    }

    /**
     * 欄目欄位 - 刪除多圖欄位的圖集
     */
    public function del_arctypeimgs()
    {
        $typeid = input('typeid/d','0');
        if (!empty($typeid)) {
            $path = input('filename',''); // 圖片路徑
            $fieldname = input('fieldname/s', ''); // 多圖欄位

            /*除去多圖欄位值中的圖片*/
            $info = M('arctype')->field("{$fieldname}")->where("id", $typeid)->find();
            $valueArr = explode(',', $info[$fieldname]);
            foreach ($valueArr as $key => $val) {
                if ($path == $val) {
                    unset($valueArr[$key]);
                }
            }
            $value = implode(',', $valueArr);
            M('arctype')->where('id', $typeid)->update(array($fieldname=>$value, 'update_time'=>getTime()));
            /*--end*/
        }
    }

    /**
     * 模型欄位 - 刪除多圖欄位的圖集
     */
    public function del_channelimgs()
    {
        $aid = input('aid/d','0');
        $channel = input('channel/d', ''); // 模型ID
        if (!empty($aid) && !empty($channel)) {
            $path = input('filename',''); // 圖片路徑
            $fieldname = input('fieldname/s', ''); // 多圖欄位

            /*模型附加表*/
            $table = M('channeltype')->where('id',$channel)->getField('table');
            $tableExt = $table.'_content';
            /*--end*/

            /*除去多圖欄位值中的圖片*/
            $info = M($tableExt)->field("{$fieldname}")->where("aid", $aid)->find();
            $valueArr = explode(',', $info[$fieldname]);
            foreach ($valueArr as $key => $val) {
                if ($path == $val) {
                    unset($valueArr[$key]);
                }
            }
            $value = implode(',', $valueArr);
            M($tableExt)->where('aid', $aid)->update(array($fieldname=>$value, 'update_time'=>getTime()));
            /*--end*/
        }
    }

    /**
     * 顯示與隱藏
     */
    public function ajax_channel_show()
    {
        if (IS_POST) {
            $id = input('id/d');
            $ifeditable = input('ifeditable/d');
            if(!empty($id)){
                $row = Db::name('channelfield')->where([
                        'id'    => $id,
                    ])->find();
                if (!empty($row) && 1 == intval($row['ifcontrol'])) {
                    $this->error('系統內建表單，禁止操作！');
                }
                $r = Db::name('channelfield')->where([
                        'id'    => $id,
                    ])->update([
                        'ifeditable'    => $ifeditable,
                        'update_time'   => getTime(),
                    ]);
                if($r){
                    adminLog('操作自定義模型表單：'.$row['name']);
                    $this->success('操作成功');
                }else{
                    $this->error('操作失敗');
                }
            } else {
                $this->error('參數有誤');
            }
        }
        $this->error('非法訪問');
    }

    /**
     * 欄目欄位管理
     */
    public function arctype_index()
    {
        $channel_id = $this->arctype_channel_id;
        $assign_data = array();
        $condition = array();
        // 獲取到所有GET參數
        $param = input('param.');

        /*同步更新欄目主表字段到自定義欄位表中*/
        if (empty($param['searchopt'])) {
            $this->fieldLogic->synArctypeTableColumns($channel_id);
        }
        /*--end*/

        // 應用搜索條件
        foreach (['keywords'] as $key) {
            if (isset($param[$key]) && $param[$key] !== '') {
                if ($key == 'keywords') {
                    $condition['name'] = array('LIKE', "%{$param[$key]}%");
                } else {
                    $condition[$key] = array('eq', $param[$key]);
                }
            }
        }

        // 模型ID
        $condition['channel_id'] = array('eq', $channel_id);
        $condition['ifsystem'] = array('neq', 1);

        $cfieldM =  M('channelfield');
        $count = $cfieldM->where($condition)->count('id');// 查詢滿足要求的總記錄數
        $Page = $pager = new Page($count, config('paginate.list_rows'));// 實例化分頁類 傳入總記錄數和每頁顯示的記錄數
        $list = $cfieldM->where($condition)->order('sort_order asc, ifsystem asc, id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        $show = $Page->show();// 分頁顯示輸出
        $assign_data['page'] = $show; // 賦值分頁輸出
        $assign_data['list'] = $list; // 賦值數據集
        $assign_data['pager'] = $Page; // 賦值分頁對像

        /*欄位型別列表*/
        $assign_data['fieldtypeList'] = M('field_type')->field('name,title')->getAllWithIndex('name');
        /*--end*/

        $assign_data['channel_id'] = $channel_id;

        $this->assign($assign_data);
        return $this->fetch();
    }
    
    /**
     * 新增-欄目欄位
     */
    public function arctype_add()
    {
        $channel_id = $this->arctype_channel_id;
        if (empty($channel_id)) {
            $this->error('參數有誤！');
        }

        if (IS_POST) {
            $post = input('post.', '', 'trim');

            if (empty($post['dtype']) || empty($post['title']) || empty($post['name'])) {
                $this->error("缺少必填資訊！");
            }

            if (1 == preg_match('/^([_]+|[0-9]+)$/', $post['name'])) {
                $this->error("欄位名稱格式不正確！");
            }

            /*去除中文逗號，過濾左右空格與空值*/
            $dfvalue = str_replace('，', ',', $post['dfvalue']);
            $dfvalueArr = explode(',', $dfvalue);
            foreach ($dfvalueArr as $key => $val) {
                $tmp_val = trim($val);
                if ('' == $tmp_val) {
                    unset($dfvalueArr[$key]);
                    continue;
                }
                $dfvalueArr[$key] = trim($val);
            }
            $dfvalue = implode(',', $dfvalueArr);
            /*--end*/

            /*預設值必填欄位*/
            $fieldtype_list = model('Field')->getFieldTypeAll('name,title,ifoption', 'name');
            if (isset($fieldtype_list[$post['dtype']]) && 1 == $fieldtype_list[$post['dtype']]['ifoption']) {
                if (empty($dfvalue)) {
                    $this->error("你設定了欄位為【".$fieldtype_list[$post['dtype']]['title']."】型別，預設值不能為空！ ");
                }
            }
            /*--end*/

            /*欄目對應的單頁表*/
            $tableExt = PREFIX.'single_content';
            /*--end*/

            /*檢測欄位是否存在於主表與附加表中*/
            if (true == $this->fieldLogic->checkChannelFieldList($tableExt, $post['name'], 6)) {
                $this->error("欄位名稱 ".$post['name']." 與系統欄位衝突！");
            }
            /*--end*/

            /*組裝完整的SQL語句，並執行新增欄位*/
            $fieldinfos = $this->fieldLogic->GetFieldMake($post['dtype'], $post['name'], $dfvalue, $post['title']);
            $ntabsql = $fieldinfos[0];
            $buideType = $fieldinfos[1];
            $maxlength = $fieldinfos[2];
            $table = PREFIX.'arctype';
            $sql = " ALTER TABLE `$table` ADD  $ntabsql ";
            if (false !== Db::execute($sql)) {
                /*儲存新增欄位的記錄*/
                $newData = array(
                    'dfvalue'   => $dfvalue,
                    'maxlength' => $maxlength,
                    'define'  => $buideType,
                    'ifmain'    => 1,
                    'ifsystem'  => 0,
                    'sort_order'    => 100,
                    'add_time' => getTime(),
                    'update_time' => getTime(),
                );
                $data = array_merge($post, $newData);
                M('channelfield')->save($data);
                /*--end*/

                /*重新產生數據表字段快取檔案*/
                try {
                    schemaTable($table);
                } catch (\Exception $e) {}
                /*--end*/

                \think\Cache::clear('channelfield');
                \think\Cache::clear("arctype");
                $this->success("操作成功！", url('Field/arctype_index'));
            }
            $this->error('操作失敗');
        }

        /*欄位型別列表*/
        $assign_data['fieldtype_list'] = model('Field')->getFieldTypeAll('name,title,ifoption');
        /*--end*/
        
        /*模型ID*/
        $assign_data['channel_id'] = $channel_id;
        /*--end*/

        $this->assign($assign_data);
        return $this->fetch();
    }
    
    /**
     * 編輯-欄目欄位
     */
    public function arctype_edit()
    {
        $channel_id = $this->arctype_channel_id;
        if (empty($channel_id)) {
            $this->error('參數有誤！');
        }

        if (IS_POST) {
            $post = input('post.', '', 'trim');

            if (empty($post['dtype']) || empty($post['title']) || empty($post['name'])) {
                $this->error("缺少必填資訊！");
            }

            if (1 == preg_match('/^([_]+|[0-9]+)$/', $post['name'])) {
                $this->error("欄位名稱格式不正確！");
            }

            $info = model('Channelfield')->getInfo($post['id'], 'ifsystem');
            if (!empty($info['ifsystem'])) {
                $this->error('系統欄位不允許更改！');
            }

            $old_name = $post['old_name'];
            /*去除中文逗號，過濾左右空格與空值*/
            $dfvalue = str_replace('，', ',', $post['dfvalue']);
            $dfvalueArr = explode(',', $dfvalue);
            foreach ($dfvalueArr as $key => $val) {
                $tmp_val = trim($val);
                if ('' == $tmp_val) {
                    unset($dfvalueArr[$key]);
                    continue;
                }
                $dfvalueArr[$key] = trim($val);
            }
            $dfvalue = implode(',', $dfvalueArr);
            /*--end*/

            /*預設值必填欄位*/
            $fieldtype_list = model('Field')->getFieldTypeAll('name,title,ifoption', 'name');
            if (isset($fieldtype_list[$post['dtype']]) && 1 == $fieldtype_list[$post['dtype']]['ifoption']) {
                if (empty($dfvalue)) {
                    $this->error("你設定了欄位為【".$fieldtype_list[$post['dtype']]['title']."】型別，預設值不能為空！ ");
                }
            }
            /*--end*/

            /*欄目對應的單頁表*/
            $tableExt = PREFIX.'single_content';
            /*--end*/

            /*檢測欄位是否存在於主表與附加表中*/
            if (true == $this->fieldLogic->checkChannelFieldList($tableExt, $post['name'], 6, array($old_name))) {
                $this->error("欄位名稱 ".$post['name']." 與系統欄位衝突！");
            }
            /*--end*/

            /*組裝完整的SQL語句，並執行編輯欄位*/
            $fieldinfos = $this->fieldLogic->GetFieldMake($post['dtype'], $post['name'], $dfvalue, $post['title']);
            $ntabsql = $fieldinfos[0];
            $buideType = $fieldinfos[1];
            $maxlength = $fieldinfos[2];
            $table = PREFIX.'arctype';
            $sql = " ALTER TABLE `$table` CHANGE COLUMN `{$old_name}` $ntabsql ";
            if (false !== Db::execute($sql)) {
                /*儲存更新欄位的記錄*/
                $newData = array(
                    'dfvalue'   => $dfvalue,
                    'maxlength' => $maxlength,
                    'define'  => $buideType,
                    'ifmain'    => 1,
                    'ifsystem'  => 0,
                    'update_time' => getTime(),
                );
                $data = array_merge($post, $newData);
                M('channelfield')->where('id',$post['id'])->cache(true,null,"channelfield")->save($data);
                /*--end*/

                /*重新產生數據表字段快取檔案*/
                try {
                    schemaTable($table);
                } catch (\Exception $e) {}
                /*--end*/

                \think\Cache::clear("arctype");
                $this->success("操作成功！", url('Field/arctype_index'));
            } else {
                $sql = " ALTER TABLE `$table` ADD  $ntabsql ";
                if (false === Db::execute($sql)) {
                    $this->error('操作失敗！');
                }
            }
        }

        $id = input('param.id/d', 0);
        $info = array();
        if (!empty($id)) {
            $info = model('Channelfield')->getInfo($id);
        }
        if (!empty($info['ifsystem'])) {
            $this->error('系統欄位不允許更改！');
        }
        $assign_data['info'] = $info;

        /*欄位型別列表*/
        $assign_data['fieldtype_list'] = model('Field')->getFieldTypeAll('name,title,ifoption');
        /*--end*/
        
        /*模型ID*/
        $assign_data['channel_id'] = $channel_id;
        /*--end*/

        $this->assign($assign_data);
        return $this->fetch();
    }
    
    /**
     * 刪除-欄目欄位
     */
    public function arctype_del()
    {
        $channel_id = $this->arctype_channel_id;
        $id = input('del_id/d', 0);
        if(!empty($id)){
            /*刪除表字段*/
            $row = $this->fieldLogic->delArctypeField($id);
            /*--end*/
            if (0 < $row['code']) {
                $map = array(
                    'id'    => array('eq', $id),
                    'channel_id'    => $channel_id,
                );
                $result = M('channelfield')->field('channel_id,name')->where($map)->select();
                $name_list = get_arr_column($result, 'name');
                /*刪除欄位的記錄*/
                M('channelfield')->where($map)->delete();
                /*--end*/

                adminLog('刪除欄目欄位：'.implode(',', $name_list));
                $this->success('刪除成功');
            }

            \think\Cache::clear('channelfield');
            \think\Cache::clear("arctype");
            respose(array('status'=>0, 'msg'=>$row['msg']));

        }else{
            $this->error('參數有誤');
        }
    }
}