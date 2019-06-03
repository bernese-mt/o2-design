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

use think\Db;
use think\Page;
use app\common\logic\ArctypeLogic;

class Archives extends Base
{
    // 允許發佈文件的模型ID
    public $allowReleaseChannel = array();
    
    public function _initialize() {
        parent::_initialize();
        $this->allowReleaseChannel = config('global.allow_release_channel');
    }

    /**
     * 內容管理
     */
    public function index()
    {
        $arctype_list = array();
        // 目錄列表
        $arctypeLogic = new ArctypeLogic(); 
        $where['is_del'] = '0'; // 回收站功能
        $arctype_list = $arctypeLogic->arctype_list(0, 0, false, 0, $where, false);
        $zNodes = "[";
        foreach ($arctype_list as $key => $val) {
            $current_channel = $val['current_channel'];
            if (6 == $current_channel) {
                $gourl = url('Arctype/single_edit', array('typeid'=>$val['id']));
                $typeurl = url("Arctype/single_edit", array('typeid'=>$val['id'],'gourl'=>$gourl));
            } else if (8 == $current_channel) {
                $typeurl = url("Guestbook/index", array('typeid'=>$val['id']));
            } else {
                $typeurl = url('Archives/index_archives', array('typeid'=>$val['id']));
            }
            $typename = $val['typename'];
            $zNodes .= "{"."id:{$val['id']}, pId:{$val['parent_id']}, name:\"{$typename}\", url:'{$typeurl}',target:'content_body'";
            /*預設展開一級欄目*/
            if (empty($val['parent_id'])) {
                $zNodes .= ",open:true";
            }
            /*--end*/
            /*欄目有下級欄目時，顯示圖示*/
            if (1 == $val['has_children']) {
                $zNodes .= ",isParent:true";
            } else {
                $zNodes .= ",isParent:false";
            }
            /*--end*/
            $zNodes .= "},";
        }
        $zNodes .= "]";
        $this->assign('zNodes', $zNodes);

        return $this->fetch();
    }

    /**
     * 內容管理 - 所有文件列表風格（只針對ey_archives表，排除單頁記錄）
     */
    public function index_archives()
    {
        $assign_data = array();
        $condition = array();
        // 獲取到所有URL參數
        $param = input('param.');
        $typeid = input('typeid/d', 0);

        /*跳轉到指定欄目的文件列表*/
        if (0 < intval($typeid)) {
            $row = db('arctype')
                ->alias('a')
                ->field('b.ctl_name,b.id')
                ->join('__CHANNELTYPE__ b', 'a.current_channel = b.id', 'LEFT')
                ->where('a.id', 'eq', $typeid)
                ->find();
            $ctl_name = $row['ctl_name'];
            $current_channel = $row['id'];
            if (6 == $current_channel) {
                $gourl = url('Arctype/single_edit', array('typeid'=>$typeid));
                $gourl = url("Arctype/single_edit", array('typeid'=>$typeid,'gourl'=>$gourl));
                $this->redirect($gourl);
            } else if (8 == $current_channel) {
                $gourl = url("Guestbook/index", array('typeid'=>$typeid));
                $this->redirect($gourl);
            }
        }
        /*--end*/

        // 應用搜索條件
        foreach (['keywords','typeid'] as $key) {
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
                } else {
                    $condition['a.'.$key] = array('eq', $param[$key]);
                }
            }
        }
        
        /*許可權控制 by 小虎哥*/
        if (empty($typeid)) {
            $typeids = [];
            $admin_info = session('admin_info');
            if (0 < intval($admin_info['role_id'])) {
                $auth_role_info = $admin_info['auth_role_info'];
                if(! empty($auth_role_info)){
                    if(isset($auth_role_info['only_oneself']) && 1 == $auth_role_info['only_oneself']){
                        $condition['a.admin_id'] = $admin_info['admin_id'];
                    }
                    if(! empty($auth_role_info['permission']['arctype'])){
                        $typeids = $auth_role_info['permission']['arctype'];
                    }
                }
            }
            if (!empty($typeids)) {
                $condition['a.typeid'] = array('IN', $typeids); 
            }
        }
        /*--end*/

        if (empty($typeid)) {
            // 只顯示允許發佈文件的模型，且是開啟狀態
            $channelIds = Db::name('channeltype')->where('status',0)
                ->whereOr('id','IN',[6,8])->column('id');
            $condition['a.channel'] = array('NOT IN', $channelIds);
        } else {
            // 只顯示目前欄目對應模型下的文件
            $current_channel = Db::name('arctype')->where('id',$typeid)->getField('current_channel');
            $condition['a.channel'] = array('eq', $current_channel);
        }

        /*多語言*/
        $condition['a.lang'] = array('eq', $this->admin_lang);
        /*--end*/

        /*回收站數據不顯示*/
        $condition['a.is_del'] = array('eq', 0);
        /*--end*/

        /**
         * 數據查詢，搜索出主鍵ID的值
         */
        $count = DB::name('archives')->alias('a')->where($condition)->count('aid');// 查詢滿足要求的總記錄數
        $Page = new Page($count, config('paginate.list_rows'));// 實例化分頁類 傳入總記錄數和每頁顯示的記錄數
        $list = DB::name('archives')
            ->field("a.aid,a.channel")
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

            /*獲取當頁文件的所有模型*/
            $channelIds = get_arr_column($list, 'channel');
            $channelRow = Db::name('channeltype')->field('id, ctl_name')
                ->where('id','IN',$channelIds)
                ->getAllWithIndex('id');
            $assign_data['channelRow'] = $channelRow;
            /*--end*/

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

        /*允許發佈文件列表的欄目*/
        $assign_data['arctype_html'] = allow_release_arctype($typeid, array());
        /*--end*/
        
        /*返回上一層鏈接*/
        $gourl = url('Archives/index_archives', array('typeid'=>$typeid));
        $assign_data['gourl'] = $gourl;
        /*--end*/

        $this->assign($assign_data);
        return $this->fetch('index_archives');
    }

    /**
     * 內容管理 - 欄目展開風格
     */
    private function index_arctype() {
        $arctype_list = array();
        // 目錄列表
        $arctypeLogic = new ArctypeLogic(); 
        $arctype_list = $arctypeLogic->arctype_list(0, 0, false, 0, array(), false);
        $this->assign('arctype_list', $arctype_list);

        // 模型列表
        $channeltype_list = getChanneltypeList();
        $this->assign('channeltype_list', $channeltype_list);

        // 欄目最多級別
        $arctype_max_level = intval(config('global.arctype_max_level'));
        $this->assign('arctype_max_level', $arctype_max_level);

        // 允許發佈文件的模型
        $this->assign('allow_release_channel', $this->allowReleaseChannel);

        return $this->fetch('index_arctype');
    }

    /**
     * 發佈文件
     */
    public function add()
    {
        $typeid = input('param.typeid/d', 0);
        if (!empty($typeid)) {
            $row = db('arctype')
                ->alias('a')
                ->field('b.ctl_name,b.id')
                ->join('__CHANNELTYPE__ b', 'a.current_channel = b.id', 'LEFT')
                ->where('a.id', 'eq', $typeid)
                ->find();
            $gourl = url('Archives/index_archives', array('typeid'=>$typeid));
            $jumpUrl = url("{$row['ctl_name']}/add", array('typeid'=>$typeid,'gourl'=>$gourl));
        } else {
            $jumpUrl = url("Archives/release");
        }
        $this->redirect($jumpUrl);
    }

    /**
     * 編輯文件
     */
/*    public function edit()
    {
        $id = input('param.id/d', 0);
        $typeid = input('param.typeid/d', 0);
        $row = db('archives')
            ->alias('a')
            ->field('a.channel,b.ctl_name,b.id')
            ->join('__CHANNELTYPE__ b', 'a.channel = b.id', 'LEFT')
            ->where('a.aid', 'eq', $id)
            ->find();
        if (empty($row['channel'])) {
            $channelRow = Db::name('channeltype')->field('id as channel, ctl_name')
                ->where('id',1)
                ->find();
            $row = array_merge($row, $channelRow);
        }
        $gourl = url('Archives/index_archives', array('typeid'=>$typeid));
        $jumpUrl = url("{$row['ctl_name']}/edit", array('id'=>$id,'gourl'=>$gourl));
        $this->redirect($jumpUrl);
    }*/

    /**
     * 刪除文件
     */
    public function del()
    {
        if (IS_POST) {
            $archivesLogic = new \app\admin\logic\ArchivesLogic;
            $archivesLogic->del();
        }
    }
    
    /**
     * 移動
     */
    public function move()
    {
        if (IS_POST) {
            $post = input('post.');
            $typeid = !empty($post['typeid']) ? eyIntval($post['typeid']) : '';
            $aids = !empty($post['aids']) ? eyIntval($post['aids']) : '';

            if (empty($typeid) || empty($aids)) {
                $this->error('參數有誤，請聯繫技術支援');
            }

            // 獲取移動欄目的模型ID
            $current_channel = Db::name('arctype')->where([
                    'id'    => $typeid,
                    'lang'  => $this->admin_lang,
                ])->getField('current_channel');
            // 抽取相符合模型ID的文件aid
            $aids = Db::name('archives')->where([
                    'aid'   =>  ['IN', $aids],
                    'channel'   =>  $current_channel,
                    'lang'  => $this->admin_lang,
                ])->column('aid');
            // 移動文件處理
            $update_data = array(
                'typeid'    => $typeid,
                'update_time'   => getTime(),
            );
            $r = M('archives')->where([
                    'aid' => ['IN', $aids],
                ])->update($update_data);
            if($r){
                adminLog('移動文件-id：'.$aids);
                $this->success('操作成功');
            }else{
                $this->error('操作失敗');
            }
        }

        $typeid = input('param.typeid/d', 0);

        /*允許發佈文件列表的欄目*/
        $allowReleaseChannel = [];
        if (!empty($typeid)) {
            $channelId = Db::name('arctype')->where('id',$typeid)->getField('current_channel');
            $allowReleaseChannel[] = $channelId;
        }
        $arctype_html = allow_release_arctype($typeid, $allowReleaseChannel);
        $this->assign('arctype_html', $arctype_html);
        /*--end*/

        /*不允許發佈文件的模型ID，用於JS判斷*/
        // $js_allow_channel_arr = '[]';
        // if (!empty($allowReleaseChannel)) {
        //     $js_allow_channel_arr = '[';
        //     foreach ($allowReleaseChannel as $key => $val) {
        //         if ($key > 0) {
        //             $js_allow_channel_arr .= ',';
        //         }
        //         $js_allow_channel_arr .= $val;
        //     }
        //     $js_allow_channel_arr = $js_allow_channel_arr.']';
        // }
        // $this->assign('js_allow_channel_arr', $js_allow_channel_arr);
        /*--end*/

        /*表單提交URL*/
        $form_action = url('Archives/move');
        $this->assign('form_action', $form_action);
        /*--end*/

        return $this->fetch();
    }

    /**
     * 發佈內容
     */
    public function release()
    {
        $typeid = input('param.typeid/d', 0);
        if (0 < $typeid) {
            $param = input('param.');
            $row = db('arctype')
                ->field('b.ctl_name,b.id')
                ->alias('a')
                ->join('__CHANNELTYPE__ b', 'a.current_channel = b.id', 'LEFT')
                ->where('a.id', 'eq', $typeid)
                ->find();
            /*針對不支援發佈文件的模型*/
            if (!in_array($row['id'], $this->allowReleaseChannel)) {
                $this->error('該欄目不支援發佈文件！', url('Archives/release'));
                exit;
            }
            /*-----end*/

            $gourl = url('Archives/index_archives', array('typeid'=>$typeid), true, true);
            $jumpUrl = url("{$row['ctl_name']}/add", array('typeid'=>$typeid,'gourl'=>$gourl), true, true);
            header('Location: '.$jumpUrl);
            exit;
        }

        $iframe = input('param.iframe/d',0);

        /*允許發佈文件列表的欄目*/
        $select_html = allow_release_arctype();
        $this->assign('select_html',$select_html);
        /*--end*/

        /*不允許發佈文件的模型ID，用於JS判斷*/
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

        $this->assign('iframe', $iframe);
        $template = !empty($iframe) ? 'release_iframe' : 'release';

        return $this->fetch($template);
    }

    public function ajax_get_arctype()
    {
        $pid = input('pid/d');
        $html = '';
        $status = 0;
        if (0 < $pid) {
            $map = array(
                'current_channel'    => array('IN', $this->allowReleaseChannel),
                'parent_id' => $pid,
            );
            $row = model('Arctype')->getAll('id,typename', $map, 'id');
            if (!empty($row)) {
                $status = 1;
                $html = '<option value="0">請選擇欄目…</option>';
                foreach ($row as $key => $val) {
                    $html .= '<option value="'.$val['id'].'">'.$val['typename'].'</option>';
                }
            }
        }

        respose(array(
            'status'    => $status,
            'msg'   => $html,
        ));
    }
}