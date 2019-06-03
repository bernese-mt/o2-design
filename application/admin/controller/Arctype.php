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
use app\common\logic\ArctypeLogic;
use app\admin\logic\FieldLogic;

class Arctype extends Base
{
    public $fieldLogic;
    // 欄目對應模型ID
    public $arctype_channel_id = '';
    // 允許發佈文件的模型ID
    public $allowReleaseChannel = array();
    // 禁用的目錄名稱
    public $disableDirname = [];
    
    public function _initialize() {
        parent::_initialize();
        $this->fieldLogic = new FieldLogic();
        $this->allowReleaseChannel = config('global.allow_release_channel');
        $this->arctype_channel_id = config('global.arctype_channel_id');
        $this->disableDirname = config('global.disable_dirname');

        /*相容每個使用者的自定義欄位，重新產生數據表字段快取檔案*/
        $arctypeFieldInfo = include DATA_PATH.'schema/'.PREFIX.'arctype.php';
        foreach (['del_method'] as $key => $val) {
            if (!isset($arctypeFieldInfo[$val])) {
                try {
                    schemaTable('arctype');
                } catch (\Exception $e) {}
                break;
            }
        }
        /*--end*/
    }

    public function index()
    {
        $arctype_list = array();
        // 目錄列表
        $arctypeLogic = new ArctypeLogic(); 
        $where['is_del'] = '0'; // 回收站功能
        $arctype_list = $arctypeLogic->arctype_list(0, 0, false, 0, $where, false);
        $this->assign('arctype_list', $arctype_list);

        // 模型列表
        $channeltype_list = getChanneltypeList();
        $this->assign('channeltype_list', $channeltype_list);

        // 欄目最多級別
        $arctype_max_level = intval(config('global.arctype_max_level'));
        $this->assign('arctype_max_level', $arctype_max_level);

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
            if ($post) {
                /*目錄名稱*/
                $dirname = $this->get_dirpinyin($post['typename'], $post['dirname']);
                $dirname = preg_replace('/(\s)+/', '_', $dirname);
                if (2 >= strlen($dirname)) { // 避免與多語言標識衝突
                    $this->error('目錄名稱不能少於2個字元');
                }
                // 多語言
                $langMark = Db::name('language')->column('mark');
                $this->disableDirname = array_merge($this->disableDirname, $langMark);
                // 檢測
                if (in_array(strtolower($dirname), $this->disableDirname)) {
                    $this->error('目錄名稱與系統內建重名，請更改！');
                }
                /*--end*/
                $dirpath = rtrim($post['dirpath'],'/');
                /* ------臨時程式碼，當能支援靜態頁面產生，再去掉 */
                $dirpath = $dirpath . '/' . $dirname;
                /* -----------end----------- */
                $typelink = !empty($post['is_part']) ? $post['typelink'] : '';
                /*封面圖的本地/遠端圖片處理*/
                $is_remote = !empty($post['is_remote']) ? $post['is_remote'] : 0;
                $litpic = '';
                if ($is_remote == 1) {
                    $litpic = $post['litpic_remote'];
                } else {
                    $litpic = $post['litpic_local'];
                }
                /*--end*/
                // 獲取頂級模型ID
                if (empty($post['parent_id'])) {
                    $channeltype = $post['current_channel'];
                } else {
                    $channeltype = M('arctype')->where('id', $post['parent_id'])->getField('channeltype');
                }
                /*SEO描述*/
                $seo_description = $post['seo_description'];
                /*--end*/
                /*處理自定義欄位值*/
                $addonField = array();
                if (!empty($post['addonField'])) {
                    $addonField = $this->fieldLogic->handleAddonField($this->arctype_channel_id, $post['addonField']);
                }
                /*--end*/
                $newData = array(
                    'dirname' => $dirname,
                    'dirpath'   => $dirpath,
                    'typelink' => $typelink,
                    'litpic'    => $litpic,
                    'channeltype'   => $channeltype,
                    'current_channel' => $post['current_channel'],
                    'seo_keywords' => str_replace('，', ',', $post['seo_keywords']),
                    'seo_description' => $seo_description,
                    'admin_id'  => session('admin_info.admin_id'),
                    'lang'  => $this->admin_lang,
                    'sort_order'    => 100,
                    'add_time'  => getTime(),
                    'update_time'  => getTime(),
                );
                $data = array_merge($post, $newData, $addonField);
                $insertId = model('Arctype')->addData($data);
                if($insertId){
                    $_POST['id'] = $insertId;

                    /*同步欄目ID到多語言的模板欄目變數里*/
                    $this->syn_add_language_attribute($insertId);
                    /*--end*/

                    adminLog('新增欄目：'.$data['typename']);
                    $this->success("操作成功!", url('Arctype/index'));
                    exit;
                }
            }
            $this->error("操作失敗!");
            exit;
        }

        $assign_data = array();

        /* 模型 */
        $map = array(
            'status'    => 1,
        );
        $channeltype_list = model('Channeltype')->getAll('id,title,nid', $map, 'id');
        $this->assign('channeltype_list', $channeltype_list);

        // 新增欄目在指定的上一級欄目下
        $parent_id = input('param.parent_id/d');
        $grade = 0;
        $current_channel = '';
        $predirpath = tpCache('seo.seo_arcdir');
        $ptypename = '';
        if (0 < $parent_id) {
            $info = M('arctype')->where(array('id'=>$parent_id))->find();
            if ($info) {
                // 級別
                $grade = $info['grade'] + 1;
                // 菜單對應下的欄目
                // $selected = $info['id'];
                // 模型
                $current_channel = $info['current_channel'];
                // 上級目錄
                $predirpath = $info['dirpath'];
                // 上級欄目名稱
                $ptypename = $info['typename'];
            }
        }
        $this->assign('predirpath', $predirpath);
        $this->assign('parent_id', $parent_id);
        $this->assign('ptypename', $ptypename);
        $this->assign('grade',$grade);
        $this->assign('current_channel',$current_channel);
        
        /*發佈文件的模型ID，用於是否顯示文件模板列表*/
        $js_allow_channel_arr = '[';
        foreach ($this->allowReleaseChannel as $key => $val) {
            if ($key > 0) {
                $js_allow_channel_arr .= ',';
            }
            $js_allow_channel_arr .= $val;
        }
        $js_allow_channel_arr = $js_allow_channel_arr.']';
        $this->assign('js_allow_channel_arr', $js_allow_channel_arr);
        /*--end*/

        /*模板列表*/
        $templateList = $this->ajax_getTemplateList('add');
        $this->assign('templateList', $templateList);
        /*--end*/

        /*自定義欄位*/
        $assign_data['addonFieldList'] = model('Field')->getTabelFieldList(config('global.arctype_channel_id'));
        $assign_data['aid'] = 0;
        $assign_data['channeltype'] = 6;
        $assign_data['nid'] = 'arctype';
        /*--end*/

        $this->assign($assign_data);
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

                /*自己的上級不能是自己*/
                if (intval($post['id']) == intval($post['parent_id'])) {
                    $this->error('自己不能成為自己的上級欄目');
                }
                /*--end*/

                /*目錄名稱*/
                $dirname = $this->get_dirpinyin($post['typename'], $post['dirname'], $post['id']);
                $dirname = preg_replace('/(\s)+/', '_', $dirname);
                if (2 >= strlen($dirname)) { // 避免與多語言標識衝突
                    $this->error('目錄名稱不能少於2個字元');
                }
                // 多語言
                $langMark = Db::name('language')->column('mark');
                $this->disableDirname = array_merge($this->disableDirname, $langMark);
                // 檢測
                if (in_array(strtolower($dirname), $this->disableDirname)) {
                    $this->error('目錄名稱與系統內建重名，請更改！');
                }
                /*--end*/
                $dirpath = rtrim($post['dirpath'], '/');
                $typelink = !empty($post['is_part']) ? $post['typelink'] : '';
                /*封面圖的本地/遠端圖片處理*/
                $is_remote = !empty($post['is_remote']) ? $post['is_remote'] : 0;
                $litpic = '';
                if ($is_remote == 1) {
                    $litpic = $post['litpic_remote'];
                } else {
                    $litpic = $post['litpic_local'];
                }
                /*--end*/
                // 最頂級模型ID
                $channeltype = $post['channeltype'];
                // 目前更改的等級
                $grade = $post['grade']; 
                // 根據欄目ID獲取最新的最頂級模型ID
                if (intval($post['parent_id']) > 0) {
                    $arctype_row = M('arctype')->field('grade,channeltype')->where('id', $post['parent_id'])->find();
                    $channeltype = $arctype_row['channeltype'];
                    $grade = $arctype_row['grade'] + 1;
                }
                /*SEO描述*/
                $seo_description = $post['seo_description'];
                /*--end*/

                /*處理自定義欄位值*/
                $addonField = array();
                if (!empty($post['addonField'])) {
                    $addonField = $this->fieldLogic->handleAddonField($this->arctype_channel_id, $post['addonField']);
                }
                /*--end*/

                $newData = array(
                    'dirname' => $dirname,
                    'dirpath'   => $dirpath,
                    'typelink' => $typelink,
                    'litpic'    => $litpic,
                    'channeltype'   => $channeltype,
                    'grade' => $grade,
                    'seo_keywords' => str_replace('，', ',', $post['seo_keywords']),
                    'seo_description' => $seo_description,
                    'lang'  => $this->admin_lang,
                    'update_time'  => getTime(),
                );
                $data = array_merge($post, $newData, $addonField);
                $r = model('Arctype')->updateData($data);
                if($r){
                    adminLog('編輯欄目：'.$data['typename']);
                    $this->success("操作成功!", url('Arctype/index'));
                    exit;
                }
            }
            $this->error("操作失敗!");
            exit;
        }

        $assign_data = array();

        $id = input('id/d');
        $info = M('arctype')->where([
                'id'    => $id,
                'lang'  => $this->admin_lang,
            ])->find();
        if (empty($info)) {
            $this->error('數據不存在，請聯繫管理員！');
            exit;
        }
        // 欄目圖片處理
        if (is_http_url($info['litpic'])) {
            $info['is_remote'] = 1;
            $info['litpic_remote'] = handle_subdir_pic($info['litpic']);
        } else {
            $info['is_remote'] = 0;
            $info['litpic_local'] = handle_subdir_pic($info['litpic']);
        }
        $this->assign('field',$info);

        // 獲得上級目錄路徑
        if (!empty($info['dirpath'])) {
            $predirpath = preg_replace('/\/([^\/]*)$/i', '', $info['dirpath']);
        } else {
            $predirpath = tpCache('seo.seo_arcdir');
        }
        $this->assign('predirpath',$predirpath);

        // 是否有子欄目
        $hasChildren = model('Arctype')->hasChildren($id);
        if ($hasChildren > 0) {
            $select_html = M('arctype')->where('id', $info['parent_id'])->getField('typename');
            $select_html = !empty($select_html) ? $select_html : '頂級欄目';
        } else {
            // 所屬欄目
            // $channeltype = $info['channeltype'];
            $select_html = '<option value="0" data-grade="-1" data-dirpath="'.tpCache('seo.seo_arcdir').'">頂級欄目</option>';
            $selected = $info['parent_id'];
            $arctype_max_level = intval(config('global.arctype_max_level'));
            $arctypeLogic = new ArctypeLogic();
            $options = $arctypeLogic->arctype_list(0, $selected, false, $arctype_max_level - 1);
            foreach ($options AS $var)
            {
                $select_html .= '<option value="' . $var['id'] . '" data-grade="' . $var['grade'] . '" data-dirpath="'.$var['dirpath'].'"';
                $select_html .= ($selected == $var['id']) ? "selected='ture'" : '';
                $select_html .= ($id == $var['id']) ? "disabled='ture' style='background-color:#f5f5f5;' " : '';
                $select_html .= '>';
                if ($var['level'] > 0)
                {
                    $select_html .= str_repeat('&nbsp;', $var['level'] * 4);
                }
                $select_html .= htmlspecialchars(addslashes($var['typename'])) . '</option>';
            }
        }
        $this->assign('select_html',$select_html);
        $this->assign('hasChildren',$hasChildren);

        /* 模型 */
        $map = array(
            'status'    => 1,
        );
        $channeltype_list = model('Channeltype')->getAll('id,title,nid,ctl_name', $map, 'id');
        // 模型對應模板檔案不存在報錯
        if (!isset($channeltype_list[$info['current_channel']])) {
            $row = model('Channeltype')->getInfo($info['current_channel']);
            $file = 'lists_'.$row['nid'].'.htm';
            $this->error($row['title'].'缺少模板檔案'.$file);
        }
        // 選項卡內容的鏈接
        $ctl_name = $channeltype_list[$info['current_channel']]['ctl_name'];
        $list_url = url("{$ctl_name}/index")."?typeid={$id}";
        $this->assign('list_url', $list_url);
        $this->assign('channeltype_list', $channeltype_list);
        
        /*發佈文件的模型ID，用於是否顯示文件模板列表*/
        $js_allow_channel_arr = '[';
        foreach ($this->allowReleaseChannel as $key => $val) {
            if ($key > 0) {
                $js_allow_channel_arr .= ',';
            }
            $js_allow_channel_arr .= $val;
        }
        $js_allow_channel_arr = $js_allow_channel_arr.']';
        $this->assign('js_allow_channel_arr', $js_allow_channel_arr);
        /*--end*/

        /*選項卡*/
        $tab = input('param.tab/d', 1);
        $this->assign('tab', $tab);
        /*--end*/

        /*模板列表*/
        $templateList = $this->ajax_getTemplateList('edit', $info['templist'], $info['tempview']);
        $this->assign('templateList', $templateList);
        /*--end*/

        /*自定義欄位*/
        $assign_data['addonFieldList'] = model('Field')->getTabelFieldList(config('global.arctype_channel_id'), $id);
        $assign_data['aid'] = $id;
        $assign_data['channeltype'] = 6;
        $assign_data['nid'] = 'arctype';
        /*--end*/

        $this->assign($assign_data);
        return $this->fetch();
    }
    
    /**
     * 內容管理
     */
    public function single_edit()
    {
        if (IS_POST) {
            $post = input('post.');
            $typeid = input('post.typeid/d', 0);
            if(!empty($typeid)){
                $info = M('arctype')->field('id,typename,current_channel')
                    ->where([
                        'id'    => $typeid,
                        'lang'  => $this->admin_lang,
                    ])->find();
                $aid = M('archives')->where([
                        'typeid'    => $typeid,
                        'channel'   => 6,
                        'lang'  => $this->admin_lang,
                    ])->getField('aid');
                
                /*修復新增單頁欄目的關聯數據不完善，進行修復*/
                if (empty($aid)) {
                    $archivesData = array(
                        'title' => $info['typename'],
                        'typeid'=> $info['id'],
                        'channel'   => $info['current_channel'],
                        'sort_order'    => 100,
                        'add_time'  => getTime(),
                        'update_time'     => getTime(),
                        'lang'  => $this->admin_lang,
                    );
                    $aid = M('archives')->insertGetId($archivesData);
                }
                /*--end*/

                if (!isset($post['addonFieldExt'])) {
                    $post['addonFieldExt'] = array();
                }
                $updateData = array(
                    'aid'   => $aid,
                    'typename' => $info['typename'],
                    'addonFieldExt' => $post['addonFieldExt'],
                );
                model('Single')->afterSave($aid, $updateData, 'edit');

                \think\Cache::clear("arctype");
                adminLog('編輯欄目：'.$info['typename']);
                $this->success("操作成功!", $post['gourl']);
                exit;
            }
            $this->error("操作失敗!");
            exit;
        }

        $assign_data = array();

        $typeid = input('typeid/d');
        $info = M('arctype')->where([
                'id'    => $typeid,
                'lang'  => $this->admin_lang,
            ])->find();
        if (empty($info)) {
            $this->error('數據不存在，請聯繫管理員！');
            exit;
        }
        $assign_data['info'] = $info;

        /*自定義欄位*/
        $addonFieldExtList = model('Field')->getChannelFieldList(6, 0, $typeid, $info);
        $channelfieldBindRow = Db::name('channelfield_bind')->where([
                'typeid'    => ['IN', [0,$typeid]],
            ])->column('field_id');
        if (!empty($channelfieldBindRow)) {
            foreach ($addonFieldExtList as $key => $val) {
                if (!in_array($val['id'], $channelfieldBindRow)) {
                    unset($addonFieldExtList[$key]);
                }
            }
        }
        $assign_data['addonFieldExtList'] = $addonFieldExtList;
        $assign_data['aid'] = $typeid;
        $assign_data['channeltype'] = 6;
        $assign_data['nid'] = 'single';
        /*--end*/

        /*返回上一層*/
        $gourl = input('param.gourl/s', '');
        if (empty($gourl)) {
            $gourl = url('Arctype/index');
        }
        $assign_data['gourl'] = $gourl;
        /*--end*/

        $this->assign($assign_data);
        return $this->fetch();
    }
    
    /**
     * 偽刪除
     */
    public function pseudo_del()
    {
        if (IS_POST) {
            $this->language_access(); // 多語言功能操作許可權
            
            $post = input('post.');
            $post['del_id'] = eyIntval($post['del_id']);

            /*目前欄目資訊*/
            $row = M('arctype')->field('id, current_channel, typename')
                ->where([
                    'id'    => $post['del_id'],
                    'lang'  => $this->admin_lang,
                ])
                ->find();
            
            $r = model('arctype')->pseudo_del($post['del_id']);
            if (false !== $r) {
                adminLog('偽刪除欄目：'.$row['typename']);
                $this->success('刪除成功');
            } else {
                $this->error('刪除失敗');
            }
        }

        $this->error('非法訪問');
    }

    /**
     * 刪除[1.2.7之後廢棄]
     */
    public function del()
    {
        $this->language_access(); // 多語言功能操作許可權
        
        $post = input('post.');
        $post['del_id'] = eyIntval($post['del_id']);

        /*目前欄目資訊*/
        $row = M('arctype')->field('id, current_channel, typename')
            ->where([
                'id'    => $post['del_id'],
                'lang'  => $this->admin_lang,
            ])
            ->find();
        
        $r = model('arctype')->del($post['del_id']);
        if (false !== $r) {
            adminLog('刪除欄目：'.$row['typename']);
            $this->success('刪除成功');
        } else {
            $this->error('刪除失敗');
        }
    }

    /**
     * 獲取欄目的拼音，確保唯一性
     */
    private function get_dirpinyin($typename = '', $dirname = '', $id = 0)
    {
        if (empty($dirname)) {
            $dirname = get_pinyin($typename);
        }
        if (strval(intval($dirname)) == strval($dirname)) {
            $dirname .= get_rand_str(3,0,0);
        }
        $map = array(
            'dirname'   => $dirname,
            'lang'  => $this->admin_lang,
        );
        if (intval($id) > 0) {
            $map['id']  = array('neq', $id);
        }
        $result = M('arctype')->field('id')->where($map)->find();
        if (!empty($result)) {
            $nowDirname = $dirname.get_rand_str(3,0,0);
            return $this->get_dirpinyin($typename, $nowDirname, $id);
        }

        return $dirname;
    }

    /**
     * 通過模型獲取欄目
     */
    public function ajax_get_arctype($channeltype = 0)
    {
        $arctypeLogic = new ArctypeLogic();
        $arctype_max_level = intval(config('global.arctype_max_level'));
        $options = $arctypeLogic->arctype_list(0, 0, false, $arctype_max_level, array('channeltype'=>$channeltype));
        $select_html = '<option value="0" data-grade="-1">頂級欄目</option>';
        foreach ($options AS $var)
        {
            $select_html .= '<option value="' . $var['id'] . '" data-grade="' . $var['grade'] . '" data-dirpath="'.$var['dirpath'].'"';
            $select_html .= '>';
            if ($var['level'] > 0)
            {
                $select_html .= str_repeat('&nbsp;', $var['level'] * 4);
            }
            $select_html .= htmlspecialchars(addslashes($var['typename'])) . '</option>';
        }

        $returndata = array(
            'status' => 1,
            'select_html' => $select_html,
        );
        
        respose($returndata);
    }

    /**
     * 獲取欄目的拼音
     */
    public function ajax_get_dirpinyin($typename = '')
    {
        $typename = input('post.typename/s');
        $pinyin = get_pinyin($typename);

        respose(array(
            'status'    => 1,
            'msg'   => $pinyin
        ));
    }

    /**
     * 檢測檔案儲存目錄是否存在
     */
    public function ajax_check_dirpath()
    {
        $dirpath = input('post.dirpath/s');
        $id = input('post.id/d');
        $map = array(
            'dirpath' => $dirpath,
            'lang'  => $this->admin_lang,
        );
        if (intval($id) > 0) {
            $map['id'] = array('neq', $id);
        }
        $result = M('arctype')->where($map)->find();
        if (!empty($result)) {
            respose(array(
                'status'    => 0,
                'msg'   => '檔案儲存目錄已存在，請更改',
            ));
        } else {
            respose(array(
                'status'    => 1,
                'msg'   => '檔案儲存目錄可用',
            ));
        }
    }

    public function ajax_getTemplateList($opt = 'add', $templist = '', $tempview = '')
    {   
        $planPath = 'template/pc';
        $dirRes   = opendir($planPath);
        $view_suffix = config('template.view_suffix');

        /*模板PC目錄檔案列表*/
        $templateArr = array();
        while($filename = readdir($dirRes))
        {
            if (in_array($filename, array('.','..'))) {
                continue;
            }
            array_push($templateArr, $filename);
        }
        /*--end*/

        /*多語言全部標識*/
        $markArr = Db::name('language_mark')->column('mark');
        /*--end*/

        $templateList = array();
        $channelList = model('Channeltype')->getAll();
        foreach ($channelList as $k1 => $v1) {
            $l = 1;
            $v = 1;
            $lists = ''; // 銷燬列表模板
            $view = ''; // 銷燬文件模板
            $templateList[$v1['id']] = array();
            foreach ($templateArr as $k2 => $v2) {
                $v2 = iconv('GB2312', 'UTF-8', $v2);

                if ('add' == $opt) {
                    $selected = 0; // 預設選中狀態
                } else {
                    $selected = 1; // 預設選中狀態
                }

                preg_match('/^(lists|view)_'.$v1['nid'].'(_(.*))?(_'.$this->admin_lang.')?\.'.$view_suffix.'/i', $v2, $matches1);
                $langtpl = preg_replace('/\.'.$view_suffix.'$/i', "_{$this->admin_lang}.{$view_suffix}", $v2);
                if (file_exists(realpath($planPath.DS.$langtpl))) {
                    continue;
                } else if (preg_match('/^(.*)_([a-zA-z]{2,2})\.'.$view_suffix.'$/i',$v2,$matches2)) {
                    if (in_array($matches2[2], $markArr) && $matches2[2] != $this->admin_lang) {
                        continue;
                    }
                }

                if (!empty($matches1)) {
                    $selectefile = '';
                    if ('lists' == $matches1[1]) {
                        $lists .= '<option value="'.$v2.'" ';
                        $lists .= ($templist == $v2 || $selected == $l) ? " selected='true' " : '';
                        $lists .= '>'.$v2.'</option>';
                        $l++;
                    } else if ('view' == $matches1[1]) {
                        $view .= '<option value="'.$v2.'" ';
                        $view .= ($tempview == $v2 || $selected == $v) ? " selected='true' " : '';
                        $view .= '>'.$v2.'</option>';
                        $v++;
                    }
                }
            }
            $nofileArr = [];
            if ('add' == $opt) {
                if (empty($lists)) {
                    $lists = '<option value="">無</option>';
                    $nofileArr[] = "lists_{$v1['nid']}.{$view_suffix}";
                }
                
                if (empty($view)) {
                    $view = '<option value="">無</option>';
                    if (!in_array($v1['nid'], ['single','guestbook'])) {
                        $nofileArr[] = "view_{$v1['nid']}.{$view_suffix}";
                    }
                }
            } else {
                if (empty($lists)) {
                    $nofileArr[] = "lists_{$v1['nid']}.{$view_suffix}";
                }
                $lists = '<option value="">請選擇模板…</option>'.$lists;

                if (empty($view)) {
                    if (!in_array($v1['nid'], ['single','guestbook'])) {
                        $nofileArr[] = "view_{$v1['nid']}.{$view_suffix}";
                    }
                }
                $view = '<option value="">請選擇模板…</option>'.$view;
            }

            $msg = '';
            if (!empty($nofileArr)) {
                $msg = '<font color="red">該模型缺少模板檔案：'.implode(' 和 ', $nofileArr).'</font>';
            }

            $templateList[$v1['id']] = array(
                'lists' => $lists,
                'view' => $view,
                'msg'    => $msg,
                'nid'    => $v1['nid'],
            );
        }

        if (IS_AJAX) {
            $this->success('請求成功', null, ['templateList'=>$templateList]);
        } else {
            return $templateList;
        }
    }

    /**
     * 同步新增欄目ID到多語言的模板欄目變數里
     */
    private function syn_add_language_attribute($typeid)
    {
        /*單語言情況下不執行多語言程式碼*/
        if (!is_language()) {
            return true;
        }
        /*--end*/

        $attr_group = 'arctype';
        $admin_lang = $this->admin_lang;
        $main_lang = get_main_lang();
        $languageRow = Db::name('language')->field('mark')->order('id asc')->select();
        if (!empty($languageRow) && $admin_lang == $main_lang) { // 目前語言是主體語言，即語言列表最早新增的語言
            $arctypeRow = Db::name('arctype')->find($typeid);
            $attr_name = 'tid'.$typeid;
            $r = Db::name('language_attribute')->save([
                'attr_title'    => $arctypeRow['typename'],
                'attr_name'     => $attr_name,
                'attr_group'    => $attr_group,
                'add_time'      => getTime(),
                'update_time'   => getTime(),
            ]);
            if (false !== $r) {
                $data = [];
                foreach ($languageRow as $key => $val) {
                    /*同步新欄目到其他語言欄目列表*/
                    if ($val['mark'] != $admin_lang) {
                        $addsaveData = $arctypeRow;
                        $addsaveData['lang'] = $val['mark'];
                        $addsaveData['typename'] = $val['mark'].$addsaveData['typename']; // 臨時測試
                        $parent_id = Db::name('language_attr')->where([
                                'attr_name' => 'tid'.$arctypeRow['parent_id'],
                                'lang'  => $val['mark'],
                            ])->getField('attr_value');
                        $addsaveData['parent_id'] = intval($parent_id);
                        unset($addsaveData['id']);
                        $typeid = model('Arctype')->addData($addsaveData);
                    }
                    /*--end*/
                    
                    /*所有語言繫結在主語言的ID容器里*/
                    $data[] = [
                        'attr_name' => $attr_name,
                        'attr_value'    => $typeid,
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
     * 新建模板檔案
     */
    public function ajax_newtpl()
    {
        if (IS_POST) {
            $post = input('post.', '', null);
            $content = input('post.content', '', null);
            $view_suffix = config('template.view_suffix');
            if (!empty($post['filename'])) {
                if (!preg_match("/^[\w\-\_]{1,}$/u", $post['filename'])) {
                    $this->error('檔名稱只允許字母、數字、下劃線、連線符的任意組合！');
                }
                $filename = "{$post['type']}_{$post['nid']}_{$post['filename']}.{$view_suffix}";
            } else {
                $filename = "{$post['type']}_{$post['nid']}.{$view_suffix}";
            }

            $content = !empty($content) ? $content : '';
            $tpldirpath = !empty($post['tpldir']) ? '/template/'.trim($post['tpldir']) : '/template/pc';
            if (file_exists(ROOT_PATH.ltrim($tpldirpath, '/').'/'.$filename)) {
                $this->error('檔名稱已經存在，請重新命名！', null, ['focus'=>'filename']);
            }

            $nosubmit = input('param.nosubmit/d');
            if (1 == $nosubmit) {
                $this->success('檢測通過');
            }

            $filemanagerLogic = new \app\admin\logic\FilemanagerLogic;
            $r = $filemanagerLogic->editFile($filename, $tpldirpath, $content);
            if ($r === true) {
                $this->success('操作成功', null, ['filename'=>$filename,'type'=>$post['type']]);
            } else {
                $this->error($r);
            }
        }
        $type = input('param.type/s');
        $nid = input('param.nid/s');
        $tpldirList = glob('template/*');
        foreach ($tpldirList as $key => $val) {
            if (!preg_match('/template\/(pc|mobile)$/i', $val)) {
                unset($tpldirList[$key]);
            } else {
                $tpldirList[$key] = preg_replace('/^(.*)template\/(pc|mobile)$/i', '$2', $val);
            }
        }
        $this->assign('tpldirList', $tpldirList);
        $this->assign('type', $type);
        $this->assign('nid', $nid);
        return $this->fetch();
    }
}