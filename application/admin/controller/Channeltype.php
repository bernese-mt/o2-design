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
use think\template\taglib\Eyou;

class Channeltype extends Base
{
    // 系統預設的模型ID，不可刪除
    private $channeltype_system_id = [];

    // 系統內建不可用的模型標識，防止與home分組的控制器重名覆蓋，導致網站報錯
    private $channeltype_system_nid = ['base','index','lists','search','tags','view','left','right','top','bottom','ajax'];

    // 數據庫對像
    public $channeltype_db;
    
    public function _initialize() {
        parent::_initialize();
        $eyou = new Eyou('');
        $this->channeltype_system_nid = array_merge($this->channeltype_system_nid, array_keys($eyou->getTags()));
        $this->channeltype_db = Db::name('channeltype');
        $this->channeltype_system_id = $this->channeltype_db->where([
                'ifsystem'  => 1,
            ])->column('id');
    }

    public function index()
    {
        // model('Channeltype')->setChanneltypeStatus(); // 根據前端模板自動開啟系統模型

        $list = array();
        $param = input('param.');
        $condition = array();
        // 應用搜索條件
        foreach (['keywords'] as $key) {
            if (isset($param[$key]) && $param[$key] !== '') {
                if ($key == 'keywords') {
                    $condition['a.title'] = array('LIKE', "%{$param[$key]}%");
                } else {
                    $condition['a.'.$key] = array('eq', $param[$key]);
                }
            }
        }

        $count = $this->channeltype_db->alias('a')->where($condition)->count('id');// 查詢滿足要求的總記錄數
        $pageObj = new Page($count, config('paginate.list_rows'));// 實例化分頁類 傳入總記錄數和每頁顯示的記錄數
        $list = $this->channeltype_db->alias('a')
            ->where($condition)
            ->order('ifsystem desc, id asc')
            ->limit($pageObj->firstRow.','.$pageObj->listRows)
            ->select();

        $pageStr = $pageObj->show();// 分頁顯示輸出
        $this->assign('pageStr',$pageStr);// 賦值分頁輸出
        $this->assign('list',$list);// 賦值數據集
        $this->assign('pageObj',$pageObj);// 賦值分頁對像

        return $this->fetch();
    }
    
    /**
     * 新增
     */
    public function add()
    {
        if (IS_POST) {
            $post = input('post.');
            if (!empty($post)) {
                $post['title'] = trim($post['title']);
                if (empty($post['title'])) {
                    $this->error('模型名稱不能為空！');
                }

                $post['nid'] = trim($post['nid']);
                if (empty($post['nid'])) {
                    $this->error('模型標識不能為空！');
                } else {
                    if (!preg_match('/^([a-z]+)([a-z0-9]*)$/i', $post['nid'])) {
                        $this->error('模型標識必須以小寫字母開頭！');
                    } else if (in_array($post['nid'], $this->channeltype_system_nid)) {
                        $this->error('系統禁用目前模型標識，請更改！');
                    }
                }

                $post['nid']    = strtolower($post['nid']);
                $nid = $post['nid'];
                $post['ctl_name'] = ucwords($nid);
                $post['table']    = $nid;
                
                if($this->channeltype_db->where(['nid'=>$nid])->count('id') > 0){
                    $this->error('該模型標識已存在，請檢查', url('Channeltype/index'));
                }

                // 建立檔案以及數據表
                $this->create_sql_file($post);

                $nowData = array(
                    'ntitle'        => $post['title'],
                    'nid'           => $nid,
                    'add_time'      => getTime(),
                    'update_time'   => getTime(),
                );
                $data = array_merge($post, $nowData);
                $insertId = $this->channeltype_db->insertGetId($data);
                $_POST['id'] = $insertId;
                if ($insertId) {
                    // 複製模型欄位基礎數據
                    $fieldLogic = new FieldLogic;
                    $fieldLogic->synArchivesTableColumns($insertId);
                    try {
                        schemaTable($post['table'].'_content');
                    } catch (\Exception $e) {}

                    delFile(CACHE_PATH, true);
                    extra_cache('admin_channeltype_list_logic', NULL);
                    adminLog('新增模型：'.$post['title']);
                    $this->success("操作成功", url('Channeltype/index'));
                }
            }
            $this->error("操作失敗");
        }

        return $this->fetch();
    }

    /**
     * 編輯
     */
    public function edit()
    {
        $id = input('id/d');

        if (IS_POST) {
            $post = input('post.');
            if(!empty($post['id'])){
                $post['title'] = trim($post['title']);

                if (in_array($post['id'], $this->channeltype_system_id)) {
                    unset($post['title']);
                } else {
                    if (empty($post['title'])) {
                        $this->error('模型名稱不能為空！');
                    }

                    $map = array(
                        'id'    => ['NEQ', $post['id']],
                        'nid' => strtolower($post['nid']),
                    );
                    if($this->channeltype_db->where($map)->count('id') > 0){
                        $this->error('該模型標識已存在，請檢查', url('Channeltype/index'));
                    }
                }

                $nowData = array(
                    'update_time'       => getTime(),
                );
                unset($post['nid']);
                $data = array_merge($post, $nowData);
                $r = $this->channeltype_db
                    ->where(['id'=>$post['id']])
                    ->cache(true,null,"channeltype")
                    ->update($data);
                if ($r) {
                    extra_cache('admin_channeltype_list_logic', NULL);
                    adminLog('編輯模型：'.$data['title']);
                    $this->success("操作成功", url('Channeltype/index'));
                }
            }
            $this->error("操作失敗");
        }

        $assign_data = array();

        $info = $this->channeltype_db->field('a.*')
            ->alias('a')
            ->where(array('a.id'=>$id))
            ->find();
        if (empty($info)) {
            $this->error('數據不存在，請聯繫管理員！');
            exit;
        }
        $assign_data['field'] = $info;

        $this->assign($assign_data);
        return $this->fetch();
    }

    
    /**
     * 刪除
     */
    public function del()
    {
        $id_arr = input('del_id/a');
        $id_arr = eyIntval($id_arr);
        if(IS_POST){
            if(!empty($id_arr)){
                foreach ($id_arr as $key => $val) {
                    if (array_key_exists($val, $this->channeltype_system_id)) {
                        $this->error('系統內建模型，禁止刪除！');
                    }
                } 

                $result = $this->channeltype_db->field('title,nid')->where("id",'IN',$id_arr)->select();
                $title_list = get_arr_column($result, 'title');

                $r = $this->channeltype_db->where("id",'IN',$id_arr)->delete();
                if ($r) {
                    // 刪除欄目
                    $arctype = Db::name('arctype')->where("channeltype",'IN',$id_arr)
                        ->whereOr("current_channel", 'IN', $id_arr)
                        ->delete();
                    // 刪除文章
                    $archives = Db::name('archives')->where("channel",'IN',$id_arr)->delete();
                    // 刪除自定義欄位
                    $channelfield = Db::name('channelfield')->where("channel_id",'IN',$id_arr)->delete();

                    // 刪除檔案
                    foreach ($result as $key => $value) {
                        $nid = $value['nid'];

                        try {
                            // 刪除相關數據表
                            Db::execute('DROP TABLE '.PREFIX.$nid.'_content');
                        } catch (\Exception $e) {}

                        $filelist_path = 'data/model/custom_model_path/'.$nid.'.filelist.txt';
                        $fileStr = file_get_contents($filelist_path);
                        $filelist = explode("\n\r", $fileStr);
                        foreach ($filelist as $k1 => $v1) {
                            $v1 = trim($v1);
                            if (!empty($v1)) {
                                @unlink($v1);
                            }
                        }
                        @unlink($filelist_path);
                        delFile('application/admin/template/'.$nid, true);
                    }
                    
                    delFile(CACHE_PATH, true);
                    extra_cache('admin_channeltype_list_logic', NULL);
                    adminLog('刪除模型：'.implode(',', $title_list));
                    $this->success('刪除成功');
                }
                $this->error('刪除失敗');
            }
            $this->error('參數有誤');
        }
        $this->error('非法訪問');
    }

    // 解析sql語句
    private function sql_split($sql, $tablepre) {
        if ($tablepre != "ey_")
            $sql = str_replace("`ey_", '`'.$tablepre, $sql);
              
        $sql = preg_replace("/TYPE=(InnoDB|MyISAM|MEMORY)( DEFAULT CHARSET=[^; ]+)?/", "ENGINE=\\1 DEFAULT CHARSET=utf8", $sql);
        
        $sql = str_replace("\r", "\n", $sql);
        $ret = array();
        $num = 0;
        $queriesarray = explode(";\n", trim($sql));
        unset($sql);
        foreach ($queriesarray as $query) {
            $ret[$num] = '';
            $queries = explode("\n", trim($query));
            $queries = array_filter($queries);
            foreach ($queries as $query) {
                $str1 = substr($query, 0, 1);
                if ($str1 != '#' && $str1 != '-')
                    $ret[$num] .= $query;
            }
            $num++;
        }
        return $ret;
    }

    // 建立檔案以及數據表
    private function create_sql_file($post) {
        $demopath = 'data/model/';
        $fileArr = []; // 產生的相關檔案記錄
        $filelist = getDirFile($demopath);
        foreach ($filelist as $key => $file) {
            if (stristr($file, 'custom_model_path')) {
                unset($filelist[$key]);
                continue;
            }
            $src = $demopath.$file;
            $dst = $file;
            $dst = str_replace('CustomModel', $post['ctl_name'], $dst);
            $dst = str_replace('custommodel', $post['nid'], $dst);
            /*記錄相關檔案*/
            if (!stristr($dst, 'custom_model_path')) {
                array_push($fileArr, $dst);
            }
            /*--end*/
            if(tp_mkdir(dirname($dst))) {
                $fileContent = @file_get_contents($src);
                $fileContent = str_replace('CustomModel', $post['ctl_name'], $fileContent);
                $fileContent = str_replace('custommodel', strtolower($post['nid']), $fileContent);
                $fileContent = str_replace('CUSTOMMODEL', strtoupper($post['nid']), $fileContent);
                $view_suffix = config('template.view_suffix');
                if (stristr($file, 'lists_custommodel.'.$view_suffix)) {
                    $replace = <<<EOF
<section class="article-list">
                    {eyou:list pagesize="10" titlelen="38"}
                    <article>
                        {eyou:notempty name="\$field.is_litpic"}
                        <a href="{\$field.arcurl}" title="{\$field.title}" style="float: left; margin-right: 10px"> <img src="{\$field.litpic}" alt="{\$field.title}" height="100" /> </a>
                        {/eyou:notempty} 
                        <h2><a href="{\$field.arcurl}" class="">{\$field.title}</a><span>{\$field.click}°C</span></h2>
                        <div class="excerpt">
                            <p>{\$field.seo_description}</p>
                        </div>
                        <div class="meta">
                            <span class="item"><time>{\$field.add_time|MyDate='Y-m-d',###}</time></span>
                            <span class="item">{\$field.typename}</span>
                        </div>
                    </article>
                    {/eyou:list}
                </section>
                <section class="list-pager">
                    {eyou:pagelist listitem='index,pre,pageno,next,end' listsize='2' /}
                    <div class="clear"></div>
                </section>
EOF;
                    $fileContent = str_replace("<!-- #list# -->", $replace, $fileContent);
                }
                $puts = @file_put_contents($dst, $fileContent);
                if (!$puts) {
                    $this->error('建立自定義模型產生相關檔案失敗，請檢查站點目錄許可權！');
                }
            }
        }
        @file_put_contents($demopath.'custom_model_path/'.$post['nid'].'.filelist.txt', implode("\n\r", $fileArr));

        // 建立自定義模型附加表
        $table = 'ey_'.$post['table'].'_content';
        $tableSql = <<<EOF
CREATE TABLE `{$table}` (
  `id`          int(10) NOT NULL    AUTO_INCREMENT,
  `aid`         int(10) DEFAULT '0' COMMENT         '文件ID',
  `add_time`    int(11) DEFAULT '0' COMMENT         '新增時間',
  `update_time` int(11) DEFAULT '0' COMMENT         '更新時間',
  PRIMARY KEY (`id`),
  KEY `aid` (`aid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='附加表';
EOF;
        $sqlFormat  = $this->sql_split($tableSql, PREFIX);

        // 執行SQL語句
        try {
            $counts = count($sqlFormat);
            for ($i = 0; $i < $counts; $i++) {
                $sql = trim($sqlFormat[$i]);
                if (stristr($sql, 'CREATE TABLE')) {
                    Db::execute($sql);
                } else {
                    if(trim($sql) == '')
                       continue;
                    Db::execute($sql);
                }
            }
        } catch (\Exception $e) {
            $this->error('數據庫表建立失敗，請檢查'.$table.'表是否存在並刪除，不行就請求技術支援！');
        }
    }

    /**
     * 檢測模板並啟用與禁用
     */
    public function ajax_show()
    {
        if (IS_POST) {
            $id = input('id/d');
            $status = input('status/d', 0);
            if(!empty($id)){
                $row = Db::name('channeltype')->where([
                        'id'    => $id,
                    ])->find();

                $nofileArr = [];
                /*檢測模板是否存在*/
                $tplplan = 'template/pc';
                $planPath = realpath($tplplan);
                if (!file_exists($planPath)) {
                    $this->success('操作成功', null, ['confirm'=>0]);
                }
                $view_suffix = config('template.view_suffix');
                // 檢測列表模板是否存在
                $lists_filename = 'lists_'.$row['nid'].'.'.$view_suffix;
                if (!file_exists($planPath.DS.$lists_filename)) {
                    $filename = ROOT_DIR.DS.$tplplan.DS.$lists_filename;
                    $nofileArr[] = [
                        'type'  => 'lists',
                        'title' => '列表模板：',
                        'file'  => str_replace('\\', '/', $filename),
                    ];
                }
                // 檢測文件模板是否存在
                if (!in_array($row['nid'], ['single','guestbook'])) {
                    $view_filename = 'view_'.$row['nid'].'.'.$view_suffix;
                    if (!file_exists($planPath.DS.$view_filename)) {
                        $filename = ROOT_DIR.DS.$tplplan.DS.$view_filename;
                        $nofileArr[] = [
                            'type'  => 'view',
                            'title' => '文件模板：',
                            'file'  => str_replace('\\', '/', $filename),
                        ];
                    }
                }
                /*--end*/

                if (empty($status) || (1 == $status && empty($nofileArr))) {
                    $r = Db::name('channeltype')->where([
                            'id'    => $id,
                        ])
                        ->cache(true,null,"channeltype")
                        ->update([
                            'status'    => $status,
                            'update_time'   => getTime(),
                        ]);
                    if($r){
                        extra_cache('admin_channeltype_list_logic', NULL);
                        adminLog('編輯【'.$row['title'].'】的狀態為：'.(!empty($status)?'啟用':'禁用'));
                        $this->success('操作成功', null, ['confirm'=>0]);
                    }else{
                        $this->error('操作失敗', null, ['confirm'=>0]);
                    }
                } else {
                    $tpltype = [];
                    $msg = "該模型缺少以下模板，系統將自動建立一個簡單模板檔案：<br/>";
                    foreach ($nofileArr as $key => $val) {
                        $msg .= '<font color="red">'.$val['title'].$val['file']."</font><br/>";
                        $tpltype[] = $val['type'];
                    }
                    $this->success($msg, null, ['confirm'=>1,'tpltype'=>base64_encode(json_encode($tpltype))]);
                }
            } else {
                $this->error('參數有誤');
            }
        }
        $this->error('非法訪問');
    }

    /**
     * 啟用並建立模板
     */
    public function ajax_check_tpl()
    {
        if (IS_POST) {
            $id = input('id/d');
            $status = input('status/d');
            if(!empty($id)){
                $row = Db::name('channeltype')->where([
                        'id'    => $id,
                    ])->find();
                $r = Db::name('channeltype')->where([
                        'id'    => $id,
                    ])
                    ->cache(true,null,"channeltype")
                    ->update([
                        'status'    => $status,
                        'update_time'   => getTime(),
                    ]);
                if($r){
                    $tpltype = input('post.tpltype/s');
                    $tpltype = json_decode(base64_decode($tpltype), true);
                    if (!empty($tpltype)) {
                        $view_suffix = config('template.view_suffix');
                        $themeStyleArr = ['pc','mobile'];
                        foreach ($themeStyleArr as $k1 => $theme) {
                            $tplplan = "template/{$theme}";
                            $planPath = realpath($tplplan);
                            if (file_exists($planPath)) {
                                foreach ($tpltype as $k2 => $val) {
                                    $source = realpath("data/model/template/{$theme}/{$val}_custommodel.{$view_suffix}");
                                    $dest = ROOT_PATH."template/{$theme}/{$val}_{$row['nid']}.{$view_suffix}";
                                    if (!file_exists($dest)) {
                                        $content = file_get_contents($source);
                                        if ('lists' == $val) {
                                            if ('download' == $row['nid'])
                                            {
                                                $replace = <<<EOF
<section class="article-list">
                    {eyou:list pagesize="10" titlelen="38"}
                    <article>
                        {eyou:notempty name="\$field.is_litpic"}
                        <a href="{\$field.arcurl}" title="{\$field.title}" style="float: left; margin-right: 10px"> <img src="{\$field.litpic}" alt="{\$field.title}" height="100" /> </a>
                        {/eyou:notempty} 
                        <h2><a href="{\$field.arcurl}" class="">{\$field.title}</a><span>{\$field.click}°C</span></h2>
                        <div class="excerpt">
                            <p>{\$field.seo_description}</p>
                        </div>
                        <div class="meta">
                            <span class="item"><time>{\$field.add_time|MyDate='Y-m-d',###}</time></span>
                            <span class="item">{\$field.typename}</span>
                            {eyou:arcview aid='\$field.aid' id='view'}
                                  {eyou:volist name="\$view.file_list" id='vo'}
                                  <span class="item"><a class="btn" href="{\$vo.downurl}" title="{\$vo.title}">下載包({\$i})</a></span>
                                  {/eyou:volist}
                            {/eyou:arcview}
                        </div>
                    </article>
                    {/eyou:list}
                </section>
                <section class="list-pager">
                    {eyou:pagelist listitem='index,pre,pageno,next,end' listsize='2' /}
                    <div class="clear"></div>
                </section>
EOF;
                                                $content = str_replace("<!-- #download# -->", $replace, $content);
                                            }
                                            else if ('single' == $row['nid']) 
                                            {
                                                $replace = <<<EOF
<article class="content">
                    <h1>{\$eyou.field.title}</h1>
                    <div class="post">
                        {\$eyou.field.content}
                    </div>
                </article>
EOF;
                                                $content = str_replace("<!-- #single# -->", $replace, $content);
                                            }
                                            else if ('guestbook' == $row['nid'])
                                            {
                                                $replace = <<<EOF
<article class="content">
                    <h1>{\$eyou.field.title}</h1>
                    <div class="post">
                        <div class="md_block">
                            <div style=" color: #ff0000">
                                製作易優留言表單，主要有三個步驟：<br>1，後臺>開啟留言模型，建立欄目並選擇留言模型。<br>2，打開根目錄>template>pc>lists_guestbook.htm模板檔案，按照易優表單標籤製作，<a href="http://www.eyoucms.com/doc/label/arc/502.html" target="_blank">點選這裡檢視教程</a><br>3，還有疑問可以加易優交流群（群號：<a target="_blank" href="//shang.qq.com/wpa/qunwpa?idkey=917f9a4cfe50fd94600c55eb75d9c6014a1842089b0479bc616fb79a1d85ae0b">704301718</a>）
                            </div>
                        </div>           
                    </div>
                </article>
                <section class="pager"></section>
EOF;
                                                $content = str_replace("<!-- #guestbook# -->", $replace, $content);
                                            } else {
                                                $replace = <<<EOF
<section class="article-list">
                    {eyou:list pagesize="10" titlelen="38"}
                    <article>
                        {eyou:notempty name="\$field.is_litpic"}
                        <a href="{\$field.arcurl}" title="{\$field.title}" style="float: left; margin-right: 10px"> <img src="{\$field.litpic}" alt="{\$field.title}" height="100" /> </a>
                        {/eyou:notempty} 
                        <h2><a href="{\$field.arcurl}" class="">{\$field.title}</a><span>{\$field.click}°C</span></h2>
                        <div class="excerpt">
                            <p>{\$field.seo_description}</p>
                        </div>
                        <div class="meta">
                            <span class="item"><time>{\$field.add_time|MyDate='Y-m-d',###}</time></span>
                            <span class="item">{\$field.typename}</span>
                        </div>
                    </article>
                    {/eyou:list}
                </section>
                <section class="list-pager">
                    {eyou:pagelist listitem='index,pre,pageno,next,end' listsize='2' /}
                    <div class="clear"></div>
                </section>
EOF;
                                                $content = str_replace("<!-- #list# -->", $replace, $content);
                                            }
                                        }
                                        else if ('view' == $val)
                                        { // 內建模型設有內容欄位
                                            if (1 == $row['ifsystem'])
                                            {
                                                $replace = <<<EOF
<div class="md_block">
                            {\$eyou.field.content}
                        </div>
EOF;
                                                $content = str_replace('<!-- #content# -->', $replace, $content);
                                            }
                                            if ('product' == $row['nid'])
                                            {
                                                $replace = <<<EOF
<div class="md_block">
                          <!--購物車元件start--> 
                          {eyou:sppurchase id='field'}
                              <div class="ey-price"><span>￥{\$field.users_price}</span> </div>
                              <div class="ey-number">
                                <label>數量</label>
                                <div class="btn-input">
                                  <button class="layui-btn" {\$field.ReduceQuantity}>-</button>
                                  <input type="text" class="layui-input" {\$field.UpdateQuantity}>
                                  <button class="layui-btn" {\$field.IncreaseQuantity}>+</button>
                                </div>
                              </div>
                              <div class="ey-buyaction">
                              <a class="ey-joinin" href="JavaScript:void(0);" {\$field.ShopAddCart}>加入購物車</a>
                              <a class="ey-joinbuy" href="JavaScript:void(0);" {\$field.BuyNow}>立即購買</a>
                              </div>
                              {\$field.hidden}
                          {/eyou:sppurchase}
                          <!--購物車元件end--> 
                        </div>
                        <div class="md_block">
                            <fieldset>
                                <legend>圖片集：</legend>
                                <div class="pic">
                                    <div class="wrap">
                                        {eyou:volist name="\$eyou.field.image_list"}
                                            <img src="{\$field.image_url}" alt="{\$eyou.field.title}" />
                                        {/eyou:volist}
                                    </div>
                                </div> 
                            </fieldset>
                        </div>
                        <div class="md_block">
                            <fieldset>
                                <legend>產品屬性：</legend>
                                {eyou:attribute type='auto'}
                                    {\$attr.name}：{\$attr.value}<br/>
                                {/eyou:attribute}
                            </fieldset>
                        </div>
EOF;
                                                $content = str_replace('<!-- #product# -->', $replace, $content);
                                            } else if ('images' == $row['nid']) {
                                                $replace = <<<EOF
<div class="md_block">
                            <fieldset>
                                <legend>圖片集：</legend>
                                <div class="pic">
                                    <div class="wrap">
                                        {eyou:volist name="\$eyou.field.image_list"}
                                            <img src="{\$field.image_url}" alt="{\$eyou.field.title}" />
                                        {/eyou:volist}
                                    </div>
                                </div> 
                            </fieldset>
                        </div>
EOF;
                                                $content = str_replace('<!-- #images# -->', $replace, $content);
                                            } else if ('download' == $row['nid']) {
                                                $replace = <<<EOF
<div class="md_block">
                            <fieldset>
                                <legend>下載地址：</legend>
                                 {eyou:volist name="\$eyou.field.file_list" id="field"}
                                    <a class="btn" href="{\$field.downurl}" title="{\$field.title}">下載包（{\$i}）</a> 
                                 {/eyou:volist}
                            </fieldset>
                        </div>
EOF;
                                                $content = str_replace('<!-- #download# -->', $replace, $content);
                                            }
                                        }
                                        @file_put_contents($dest, $content);
                                    }
                                }
                            }
                        }
                    }
                    extra_cache('admin_channeltype_list_logic', NULL);
                    adminLog('編輯【'.$row['title'].'】的狀態為：'.(!empty($status)?'啟用':'禁用'));
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
}