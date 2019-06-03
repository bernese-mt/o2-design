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
use app\admin\logic\ProductLogic;

class Product extends Base
{
    // 模型標識
    public $nid = 'product';
    // 模型ID
    public $channeltype = '';
    // 表單型別
    public $attrInputTypeArr = array();
    
    public function _initialize() {
        parent::_initialize();
        $channeltype_list = config('global.channeltype_list');
        $this->channeltype = $channeltype_list[$this->nid];
        $this->attrInputTypeArr = config('global.attr_input_type_arr');
        $this->assign('nid', $this->nid);
        $this->assign('channeltype', $this->channeltype);
    }

    /**
     * 文章列表
     */
    public function index()
    {
        $assign_data = array();
        $condition = array();
        // 獲取到所有GET參數
        $param = input('param.');
        $flag = input('flag/s');
        $typeid = input('typeid/d', 0);
        $begin = strtotime(input('add_time_begin'));
        $end = strtotime(input('add_time_end'));

        // 應用搜索條件
        foreach (['keywords','typeid','flag'] as $key) {
            if (isset($param[$key]) && $param[$key] !== '') {
                if ($key == 'keywords') {
                    $condition['a.title'] = array('LIKE', "%{$param[$key]}%");
                } else if ($key == 'typeid') {
                    $typeid = $param[$key];
                    $hasRow = model('Arctype')->getHasChildren($typeid);
                    $typeids = get_arr_column($hasRow, 'id');
                    /*許可權控制 by 小虎哥*/
                    $admin_info = session('admin_info');
                    if (0 < intval($admin_info['role_id'])) {
                        $auth_role_info = $admin_info['auth_role_info'];
                        if(! empty($auth_role_info)){
                            if(isset($auth_role_info['only_oneself']) && 1 == $auth_role_info['only_oneself']){
                                $condition['a.admin_id'] = $admin_info['admin_id'];
                            }
                            if(! empty($auth_role_info['permission']['arctype'])){
                                if (!empty($typeid)) {
                                    $typeids = array_intersect($typeids, $auth_role_info['permission']['arctype']);
                                }
                            }
                        }
                    }
                    /*--end*/
                    $condition['a.typeid'] = array('IN', $typeids);
                } else if ($key == 'flag') {
                    $condition['a.'.$param[$key]] = array('eq', 1);
                } else {
                    $condition['a.'.$key] = array('eq', $param[$key]);
                }
            }
        }

        // 時間檢索
        if ($begin > 0 && $end > 0) {
            $condition['a.add_time'] = array('between',"$begin,$end");
        } else if ($begin > 0) {
            $condition['a.add_time'] = array('egt', $begin);
        } else if ($end > 0) {
            $condition['a.add_time'] = array('elt', $end);
        }

        // 模型ID
        $condition['a.channel'] = array('eq', $this->channeltype);
        // 多語言
        $condition['a.lang'] = array('eq', $this->admin_lang);
        // 回收站
        $condition['a.is_del'] = array('eq', 0);

        /**
         * 數據查詢，搜索出主鍵ID的值
         */
        $count = DB::name('archives')->alias('a')->where($condition)->count('aid');// 查詢滿足要求的總記錄數
        $Page = new Page($count, config('paginate.list_rows'));// 實例化分頁類 傳入總記錄數和每頁顯示的記錄數
        $list = DB::name('archives')
            ->field("a.aid")
            ->alias('a')
            ->where($condition)
            ->order('a.aid desc')
            ->limit($Page->firstRow.','.$Page->listRows)
            ->getAllWithIndex('aid');

        /**
         * 完善數據集資訊
         * 在數據量大的情況下，經過優化的搜索邏輯，先搜索出主鍵ID，再通過ID將其他資訊補充完整；
         */
        if ($list) {
            $aids = array_keys($list);
            $fields = "b.*, a.*, a.aid as aid";
            $row = DB::name('archives')
                ->field($fields)
                ->alias('a')
                ->join('__ARCTYPE__ b', 'a.typeid = b.id', 'LEFT')
                ->where('a.aid', 'in', $aids)
                ->getAllWithIndex('aid');
            foreach ($list as $key => $val) {
                $row[$val['aid']]['arcurl'] = get_arcurl($row[$val['aid']]);
                $row[$val['aid']]['litpic'] = handle_subdir_pic($row[$val['aid']]['litpic']); // 支援子目錄
                $list[$key] = $row[$val['aid']];
            }
        }
        $show = $Page->show(); // 分頁顯示輸出
        $assign_data['page'] = $show; // 賦值分頁輸出
        $assign_data['list'] = $list; // 賦值數據集
        $assign_data['pager'] = $Page; // 賦值分頁對像

        // 欄目ID
        $assign_data['typeid'] = $typeid; // 欄目ID
        /*目前欄目資訊*/
        $arctype_info = array();
        if ($typeid > 0) {
            $arctype_info = M('arctype')->field('typename')->find($typeid);
        }
        $assign_data['arctype_info'] = $arctype_info;
        /*--end*/

        /*選項卡*/
        $tab = input('param.tab/d', 3);
        $assign_data['tab'] = $tab;
        /*--end*/

        $this->assign($assign_data);
        return $this->fetch();
    }

    /**
     * 新增
     */
    public function add()
    {
        if (IS_POST) {
            $post = input('post.');
            $content = input('post.addonFieldExt.content', '', null);

            // 根據標題自動提取相關的關鍵字
            $seo_keywords = $post['seo_keywords'];
            // if (empty($seo_keywords)) {
            //     $seo_keywords = get_split_word($post['title'], $content);
            // }

            // 自動獲取內容第一張圖片作為封面圖
            $is_remote = !empty($post['is_remote']) ? $post['is_remote'] : 0;
            $litpic = '';
            if ($is_remote == 1) {
                $litpic = $post['litpic_remote'];
            } else {
                $litpic = $post['litpic_local'];
            }
            if (empty($litpic)) {
                $litpic = get_html_first_imgurl($content);
            }
            $post['litpic'] = $litpic;

            // SEO描述
            $seo_description = '';
            if (empty($post['seo_description']) && !empty($content)) {
                $seo_description = @msubstr(checkStrHtml($content), 0, config('global.arc_seo_description_length'), false);
            } else {
                $seo_description = $post['seo_description'];
            }

            // 外部鏈接跳轉
            $jumplinks = '';
            $is_jump = isset($post['is_jump']) ? $post['is_jump'] : 0;
            if (intval($is_jump) > 0) {
                $jumplinks = $post['jumplinks'];
            }

            // 模板檔案，如果文件模板名與欄目指定的一致，預設就為空。讓它跟隨欄目的指定而變
            if ($post['type_tempview'] == $post['tempview']) {
                unset($post['type_tempview']);
                unset($post['tempview']);
            }

            // --儲存數據
            $newData = array(
                'typeid'=> empty($post['typeid']) ? 0 : $post['typeid'],
                'channel'   => $this->channeltype,
                'is_b'      => empty($post['is_b']) ? 0 : $post['is_b'],
                'is_head'      => empty($post['is_head']) ? 0 : $post['is_head'],
                'is_special'      => empty($post['is_special']) ? 0 : $post['is_special'],
                'is_recom'      => empty($post['is_recom']) ? 0 : $post['is_recom'],
                'is_jump'     => $is_jump,
                'jumplinks' => $jumplinks,
                'seo_keywords'     => $seo_keywords,
                'seo_description'     => $seo_description,
                'admin_id'  => session('admin_info.admin_id'),
                'lang'  => $this->admin_lang,
                'sort_order'    => 100,
                'add_time'     => strtotime($post['add_time']),
                'update_time'  => strtotime($post['add_time']),
            );
            $data = array_merge($post, $newData);

            $aid = M('archives')->insertGetId($data);
            $_POST['aid'] = $aid;
            if ($aid) {
                // ---------後置操作
                model('Product')->afterSave($aid, $data, 'add');
                // ---------end
                adminLog('新增產品：'.$data['title']);
                $this->success("操作成功!", $post['gourl']);
                exit;
            }

            $this->error("操作失敗!");
            exit;
        }

        $typeid = input('param.typeid/d', 0);
        $assign_data['typeid'] = $typeid; // 欄目ID

        // 欄目資訊
        $arctypeInfo = Db::name('arctype')->find($typeid);

        /*允許發佈文件列表的欄目*/
        $arctype_html = allow_release_arctype($typeid, array($this->channeltype));
        $assign_data['arctype_html'] = $arctype_html;
        /*--end*/

        /*自定義欄位*/
        $addonFieldExtList = model('Field')->getChannelFieldList($this->channeltype);
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
        $assign_data['aid'] = 0;
        /*--end*/

        // 閱讀許可權
        $arcrank_list = get_arcrank_list();
        $assign_data['arcrank_list'] = $arcrank_list;

        /*產品參數*/
        $assign_data['canshu'] = $this->ajax_get_attr_input($typeid);
        /*--end*/
        
        /*模板列表*/
        $archivesLogic = new \app\admin\logic\ArchivesLogic;
        $templateList = $archivesLogic->getTemplateList($this->nid);
        $this->assign('templateList', $templateList);
        /*--end*/

        /*預設模板檔案*/
        $tempview = 'view_'.$this->nid.'.'.config('template.view_suffix');
        !empty($arctypeInfo['tempview']) && $tempview = $arctypeInfo['tempview'];
        $this->assign('tempview', $tempview);
        /*--end*/

        /*返回上一層*/
        $gourl = input('param.gourl/s', '');
        if (empty($gourl)) {
            $gourl = url('Product/index', array('typeid'=>$typeid));
        }
        $assign_data['gourl'] = $gourl;
        /*--end*/

        $ShopType = getUsersConfigData('shop.shop_type');
        $assign_data['ShopType'] = $ShopType;

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
            $typeid = input('post.typeid/d', 0);
            $content = input('post.addonFieldExt.content', '', null);

            // 根據標題自動提取相關的關鍵字
            $seo_keywords = $post['seo_keywords'];
            // if (empty($seo_keywords)) {
            //     $seo_keywords = get_split_word($post['title'], $content);
            // }

            // 自動獲取內容第一張圖片作為封面圖
            $is_remote = !empty($post['is_remote']) ? $post['is_remote'] : 0;
            $litpic = '';
            if ($is_remote == 1) {
                $litpic = $post['litpic_remote'];
            } else {
                $litpic = $post['litpic_local'];
            }
            if (empty($litpic)) {
                $litpic = get_html_first_imgurl($content);
            }
            $post['litpic'] = $litpic;

            // SEO描述
            $seo_description = '';
            if (empty($post['seo_description']) && !empty($content)) {
                $seo_description = @msubstr(checkStrHtml($content), 0, config('global.arc_seo_description_length'), false);
            } else {
                $seo_description = $post['seo_description'];
            }

            // --外部鏈接
            $jumplinks = '';
            $is_jump = isset($post['is_jump']) ? $post['is_jump'] : 0;
            if (intval($is_jump) > 0) {
                $jumplinks = $post['jumplinks'];
            }

            // 模板檔案，如果文件模板名與欄目指定的一致，預設就為空。讓它跟隨欄目的指定而變
            if ($post['type_tempview'] == $post['tempview']) {
                unset($post['type_tempview']);
                unset($post['tempview']);
            }

            // 同步欄目切換模型之後的文件模型
            $channel = Db::name('arctype')->where(['id'=>$typeid])->getField('current_channel');
            // --儲存數據
            $newData = array(
                'typeid'=> $typeid,
                'channel'   => $channel,
                'is_b'      => empty($post['is_b']) ? 0 : $post['is_b'],
                'is_head'      => empty($post['is_head']) ? 0 : $post['is_head'],
                'is_special'      => empty($post['is_special']) ? 0 : $post['is_special'],
                'is_recom'      => empty($post['is_recom']) ? 0 : $post['is_recom'],
                'is_jump'   => $is_jump,
                'jumplinks' => $jumplinks,
                'seo_keywords'     => $seo_keywords,
                'seo_description'     => $seo_description,
                'add_time'     => strtotime($post['add_time']),
                'update_time'     => getTime(),
            );
            $data = array_merge($post, $newData);

            $r = M('archives')->where([
                    'aid'   => $data['aid'],
                    'lang'  => $this->admin_lang,
                ])->update($data);
            
            if ($r) {
                // ---------後置操作
                model('Product')->afterSave($data['aid'], $data, 'edit');
                // ---------end
                adminLog('編輯產品：'.$data['title']);
                $this->success("操作成功!", $post['gourl']);
                exit;
            }

            $this->error("操作失敗!");
            exit;
        }

        $assign_data = array();

        $id = input('id/d');
        $info = model('Product')->getInfo($id);
        if (empty($info)) {
            $this->error('數據不存在，請聯繫管理員！');
            exit;
        }
        /*相容採集沒有歸屬欄目的文件*/
        if (empty($info['channel'])) {
            $channelRow = Db::name('channeltype')->field('id as channel')
                ->where('id',$this->channeltype)
                ->find();
            $info = array_merge($info, $channelRow);
        }
        /*--end*/
        $typeid = $info['typeid'];

        // 欄目資訊
        $arctypeInfo = Db::name('arctype')->find($typeid);

        $info['channel'] = $arctypeInfo['current_channel'];
        if (is_http_url($info['litpic'])) {
            $info['is_remote'] = 1;
            $info['litpic_remote'] = handle_subdir_pic($info['litpic']);
        } else {
            $info['is_remote'] = 0;
            $info['litpic_local'] = handle_subdir_pic($info['litpic']);
        }
    
        // SEO描述
        if (!empty($info['seo_description'])) {
            $info['seo_description'] = @msubstr(checkStrHtml($info['seo_description']), 0, config('global.arc_seo_description_length'), false);
        }

        $assign_data['field'] = $info;

        // 產品相簿
        $proimg_list = model('ProductImg')->getProImg($id);
        foreach ($proimg_list as $key => $val) {
            $proimg_list[$key]['image_url'] = handle_subdir_pic($val['image_url']); // 支援子目錄
        }
        $assign_data['proimg_list'] = $proimg_list;

        /*允許發佈文件列表的欄目，文件所在模型以欄目所在模型為主，相容切換模型之後的數據編輯*/
        $arctype_html = allow_release_arctype($typeid, array($info['channel']));
        $assign_data['arctype_html'] = $arctype_html;
        /*--end*/
        
        /*自定義欄位*/
        $addonFieldExtList = model('Field')->getChannelFieldList($info['channel'], 0, $id, $info);
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
        $assign_data['aid'] = $id;
        /*--end*/

        // 閱讀許可權
        $arcrank_list = get_arcrank_list();
        $assign_data['arcrank_list'] = $arcrank_list;

        /*產品參數*/
        $assign_data['canshu'] = $this->ajax_get_attr_input($typeid, $id);
        /*--end*/

        /*模板列表*/
        $archivesLogic = new \app\admin\logic\ArchivesLogic;
        $templateList = $archivesLogic->getTemplateList($this->nid);
        $this->assign('templateList', $templateList);
        /*--end*/

        /*預設模板檔案*/
        $tempview = $info['tempview'];
        empty($tempview) && $tempview = $arctypeInfo['tempview'];
        $this->assign('tempview', $tempview);
        /*--end*/

        /*返回上一層*/
        $gourl = input('param.gourl/s', '');
        if (empty($gourl)) {
            $gourl = url('Product/index', array('typeid'=>$typeid));
        }
        $assign_data['gourl'] = $gourl;
        /*--end*/

        // 處理產品價格屬性
        $ShopType = getUsersConfigData('shop.shop_type');
        if (empty($ShopType) || '1' == $ShopType) {
            if ($ShopType == $assign_data['field']['prom_type']) {
                $IsSame = '0'; // 相同
            }else{
                $IsSame = '1'; // 不相同
            }
            $assign_data['IsSame'] = $IsSame;
        }else{
            $assign_data['ShopType'] = $ShopType;
        }

        $this->assign($assign_data);
        return $this->fetch();
    }
    
    /**
     * 刪除
     */
    public function del()
    {
        if (IS_POST) {
            $archivesLogic = new \app\admin\logic\ArchivesLogic;
            $archivesLogic->del();
        }
    }

    /**
     * 刪除商品相簿圖
     */
    public function del_proimg()
    {
        if (IS_POST) {
            $filename= input('filename/s');
            $filename= str_replace('../','',$filename);
            $filename= trim($filename,'.');
            if(eyPreventShell($filename) && !empty($filename)){
                $filename_new = trim($filename,'/');
                $filetype = preg_replace('/^(.*)\.(\w+)$/i', '$2', $filename);
                $phpfile = strtolower(strstr($filename,'.php'));  //排除PHP檔案
                $size = getimagesize($filename_new);
                $fileInfo = explode('/',$size['mime']);
                if((file_exists($filename_new) && $fileInfo[0] != 'image') || $phpfile || !in_array($filetype, explode(',', config('global.image_ext')))){
                    exit;
                }
                if (!empty($filename)) {
                    M('product_img')->where("image_url = '$filename'")->delete();
                }
            }
        }
    }

    /**
     * 產品參數
     */
    public function attribute_index()
    {
        $assign_data = array();
        $condition = array();
        // 獲取到所有GET參數
        $get = input('get.');
        $typeid = input('typeid/d', 0);

        // 應用搜索條件
        foreach (['keywords','typeid'] as $key) {
            if (isset($get[$key]) && $get[$key] !== '') {
                if ($key == 'keywords') {
                    $condition['a.attr_name'] = array('LIKE', "%{$get[$key]}%");
                } else if ($key == 'typeid') {
                    $condition['a.typeid'] = array('eq', $get[$key]);
                } else {
                    $condition['a.'.$key] = array('eq', $get[$key]);
                }
            }
        }

        $condition['a.is_del'] = 0;
        // 多語言
        $condition['a.lang'] = $this->admin_lang;

        /**
         * 數據查詢，搜索出主鍵ID的值
         */
        $count = DB::name('product_attribute')->alias('a')->where($condition)->count();// 查詢滿足要求的總記錄數
        $Page = new Page($count, config('paginate.list_rows'));// 實例化分頁類 傳入總記錄數和每頁顯示的記錄數
        $list = DB::name('product_attribute')
            ->field("a.attr_id")
            ->alias('a')
            ->where($condition)
            ->order('a.sort_order asc, a.attr_id asc')
            ->limit($Page->firstRow.','.$Page->listRows)
            ->getAllWithIndex('attr_id');

        /**
         * 完善數據集資訊
         * 在數據量大的情況下，經過優化的搜索邏輯，先搜索出主鍵ID，再通過ID將其他資訊補充完整；
         */
        if ($list) {
            $attr_ids = array_keys($list);
            $fields = "b.*, a.*";
            $row = DB::name('product_attribute')
                ->field($fields)
                ->alias('a')
                ->join('__ARCTYPE__ b', 'a.typeid = b.id', 'LEFT')
                ->where('a.attr_id', 'in', $attr_ids)
                ->getAllWithIndex('attr_id');
            
            /*獲取多語言關聯繫結的值*/
            $row = model('LanguageAttr')->getBindValue($row, 'product_attribute', $this->main_lang); // 多語言
            /*--end*/

            foreach ($row as $key => $val) {
                $val['fieldname'] = 'attr_'.$val['attr_id'];
                $row[$key] = $val;
            }
            foreach ($list as $key => $val) {
                $list[$key] = $row[$val['attr_id']];
            }
        }
        $show = $Page->show(); // 分頁顯示輸出
        $assign_data['page'] = $show; // 賦值分頁輸出
        $assign_data['list'] = $list; // 賦值數據集
        $assign_data['pager'] = $Page; // 賦值分頁對像

        /*獲取目前模型欄目*/
        $selected = $typeid;
        $arctypeLogic = new ArctypeLogic();
        $map = array(
            'channeltype'    => $this->channeltype,
        );
        $arctype_max_level = intval(config('global.arctype_max_level'));
        $select_html = $arctypeLogic->arctype_list(0, $selected, true, $arctype_max_level, $map);
        $this->assign('select_html',$select_html);
        /*--end*/

        // 欄目ID
        $assign_data['typeid'] = $typeid; // 欄目ID
        /*目前欄目資訊*/
        $arctype_info = array();
        if ($typeid > 0) {
            $arctype_info = M('arctype')->field('typename')->find($typeid);
        }
        $assign_data['arctype_info'] = $arctype_info;
        /*--end*/
        
        /*選項卡*/
        $tab = input('param.tab/d', 3);
        $assign_data['tab'] = $tab;
        /*--end*/
        
        $assign_data['attrInputTypeArr'] = $this->attrInputTypeArr; // 表單型別

        $this->assign($assign_data);
        return $this->fetch();
    }

    /**
     * 新增產品參數
     */
    public function attribute_add()
    {
        //防止php超時
        function_exists('set_time_limit') && set_time_limit(0);
        
        if(IS_AJAX && IS_POST)//ajax提交驗證
        {
            $model = model('ProductAttribute');

            $attr_values = str_replace('_', '', input('attr_values')); // 替換特殊字元
            $attr_values = str_replace('@', '', $attr_values); // 替換特殊字元            
            $attr_values = trim($attr_values);
            
            $post_data = input('post.');
            $post_data['attr_values'] = $attr_values;

            $savedata = array(
                'attr_name' => $post_data['attr_name'],
                'typeid'    => $post_data['typeid'],
                'attr_input_type'   => isset($post_data['attr_input_type']) ? $post_data['attr_input_type'] : '',
                'attr_values'   => isset($post_data['attr_values']) ? $post_data['attr_values'] : '',
                'sort_order'    => $post_data['sort_order'],
                'lang'  => $this->admin_lang,
                'add_time'  => getTime(),
                'update_time'   => getTime(),
            );

            // 數據驗證            
            $validate = \think\Loader::validate('ProductAttribute');
            if(!$validate->batch()->check($savedata))
            {
                $error = $validate->getError();
                $error_msg = array_values($error);
                $return_arr = array(
                    'status' => -1,
                    'msg' => $error_msg[0],
                    'data' => $error,
                );
                respose($return_arr);
            } else {
                $model->data($savedata,true); // 收集數據
                $model->save(); // 寫入數據到數據庫
                $insertId = $model->getLastInsID();

                /*同步產品屬性ID到多語言的模板變數里*/
                $this->syn_add_language_attribute($insertId);
                /*--end*/

                $return_arr = array(
                     'status' => 1,
                     'msg'   => '操作成功',                        
                     'data'  => array('url'=>url('Product/attribute_index', array('typeid'=>$post_data['typeid']))),
                );
                adminLog('新增產品參數：'.$savedata['attr_name']);
                respose($return_arr);
            }  
        }

        $typeid = input('param.typeid/d', 0);
        $assign_data = array();

        /*允許發佈文件列表的欄目*/
        $arctype_html = allow_release_arctype($typeid, array($this->channeltype));
        $assign_data['arctype_html'] = $arctype_html;
        /*--end*/

        $this->assign($assign_data);
        return $this->fetch();
    }

    /**
     * 編輯產品參數
     */
    public function attribute_edit()
    {
        if(IS_AJAX && IS_POST)//ajax提交驗證
        {
            $model = model('ProductAttribute');

            $attr_values = str_replace('_', '', input('attr_values')); // 替換特殊字元
            $attr_values = str_replace('@', '', $attr_values); // 替換特殊字元            
            $attr_values = trim($attr_values);
            
            $post_data = input('post.');
            $post_data['attr_values'] = $attr_values;

            $savedata = array(
                'attr_id'   => $post_data['attr_id'],
                'attr_name' => $post_data['attr_name'],
                'typeid'    => $post_data['typeid'],
                'attr_input_type'   => isset($post_data['attr_input_type']) ? $post_data['attr_input_type'] : '',
                'attr_values'   => isset($post_data['attr_values']) ? $post_data['attr_values'] : '',
                'sort_order'    => $post_data['sort_order'],
                'update_time'   => getTime(),
            );

            // 數據驗證            
            $validate = \think\Loader::validate('ProductAttribute');
            if(!$validate->batch()->check($savedata))
            {
                $error = $validate->getError();
                $error_msg = array_values($error);
                $return_arr = array(
                    'status' => -1,
                    'msg' => $error_msg[0],
                    'data' => $error,
                );
                respose($return_arr);
            } else {
                $model->data($savedata,true); // 收集數據
                $model->isUpdate(true, [
                        'attr_id'   => $post_data['attr_id'],
                        'lang'  => $this->admin_lang,
                    ])->save(); // 寫入數據到數據庫     
                $return_arr = array(
                     'status' => 1,
                     'msg'   => '操作成功',                        
                     'data'  => array('url'=>url('Product/attribute_index', array('typeid'=>$post_data['typeid']))),
                );
                adminLog('編輯產品參數：'.$savedata['attr_name']);
                respose($return_arr);
            }  
        }  

        $assign_data = array();

        $id = input('id/d');
        /*獲取多語言關聯繫結的值*/
        $new_id = model('LanguageAttr')->getBindValue($id, 'product_attribute'); // 多語言
        !empty($new_id) && $id = $new_id;
        /*--end*/
        $info = M('ProductAttribute')->where([
                'attr_id'    => $id,
                'lang'  => $this->admin_lang,
            ])->find();
        if (empty($info)) {
            $this->error('數據不存在，請聯繫管理員！');
            exit;
        }
        $assign_data['field'] = $info;

        /*允許發佈文件列表的欄目*/
        $arctype_html = allow_release_arctype($info['typeid'], array($this->channeltype));
        $assign_data['arctype_html'] = $arctype_html;
        /*--end*/

        $this->assign($assign_data);
        return $this->fetch();
    }
    
    /**
     * 刪除產品參數
     */
    public function attribute_del()
    {
        $id_arr = input('del_id/a');
        $id_arr = eyIntval($id_arr);
        if(!empty($id_arr)){
            /*多語言*/
            if (is_language()) {
                $attr_name_arr = [];
                foreach ($id_arr as $key => $val) {
                    $attr_name_arr[] = 'attr_'.$val;
                }
                $new_id_arr = Db::name('language_attr')->where([
                        'attr_name' => ['IN', $attr_name_arr],
                        'attr_group'    => 'product_attribute',
                    ])->column('attr_value');
                !empty($new_id_arr) && $id_arr = $new_id_arr;
            }
            /*--end*/
            $r = M('ProductAttribute')->where([
                    'attr_id'   => ['IN', $id_arr],
                ])->update([
                    'is_del'    => 1,
                    'update_time'   => getTime(),
                ]);
            if($r){
                adminLog('刪除產品參數-id：'.implode(',', $id_arr));
                $this->success('刪除成功');
            }else{
                $this->error('刪除失敗');
            }
        }else{
            $this->error('參數有誤');
        }
    }

    /**
     * 動態獲取產品參數輸入框 根據不同的數據返回不同的輸入框型別
     */
    public function ajax_get_attr_input($typeid = '', $aid = '')
    {
        $productLogic = new ProductLogic();
        $str = $productLogic->getAttrInput($aid, $typeid);
        if (empty($str)) {
            $str = '<div style="font-size: 12px;text-align: center;">提示：該主欄目還沒有參數值，若有需要請點選【<a href="'.url('Product/attribute_index', array('typeid'=>$typeid)).'">產品參數</a>】進行更多操作。</div>';
        }

        if (IS_AJAX) {
            exit($str);
        } else {
            return $str;
        }
    }

    /**
     * 同步新增產品屬性ID到多語言的模板變數里
     */
    private function syn_add_language_attribute($attr_id)
    {
        /*單語言情況下不執行多語言程式碼*/
        if (!is_language()) {
            return true;
        }
        /*--end*/
        
        $attr_group = 'product_attribute';
        $admin_lang = $this->admin_lang;
        $main_lang = $this->main_lang;
        $languageRow = Db::name('language')->field('mark')->order('id asc')->select();
        if (!empty($languageRow) && $admin_lang == $main_lang) { // 目前語言是主體語言，即語言列表最早新增的語言
            $result = Db::name('product_attribute')->find($attr_id);
            $attr_name = 'attr_'.$attr_id;
            $r = Db::name('language_attribute')->save([
                'attr_title'    => $result['attr_name'],
                'attr_name'     => $attr_name,
                'attr_group'    => $attr_group,
                'add_time'      => getTime(),
                'update_time'   => getTime(),
            ]);
            if (false !== $r) {
                $data = [];
                foreach ($languageRow as $key => $val) {
                    /*同步新產品屬性到其他語言產品屬性列表*/
                    if ($val['mark'] != $admin_lang) {
                        $addsaveData = $result;
                        $addsaveData['lang'] = $val['mark'];
                        $newTypeid = Db::name('language_attr')->where([
                                'attr_name' => 'tid'.$result['typeid'],
                                'attr_group'    => 'arctype',
                                'lang'  => $val['mark'],
                            ])->getField('attr_value');
                        $addsaveData['typeid'] = $newTypeid;
                        unset($addsaveData['attr_id']);
                        $attr_id = Db::name('product_attribute')->insertGetId($addsaveData);
                    }
                    /*--end*/
                    
                    /*所有語言繫結在主語言的ID容器里*/
                    $data[] = [
                        'attr_name' => $attr_name,
                        'attr_value'    => $attr_id,
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