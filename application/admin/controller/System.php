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
use think\Cache;
use think\Request;
use think\Page;

class System extends Base
{
    // 選項卡是否顯示
    public $tabase = '';
    
    public function _initialize() {
        parent::_initialize();
        $this->tabase = input('param.tabase/d');
    }

    public function index()
    {
        $this->redirect(url('System/web'));
    }

    /**
     * 網站設定
     */
    public function web()
    {
        $inc_type =  'web';
        $root_dir = ROOT_DIR; // 支援子目錄

        if (IS_POST) {
            $param = input('post.');
            $param['web_keywords'] = str_replace('，', ',', $param['web_keywords']);
            $param['web_description'] = filter_line_return($param['web_description']);
            
            // 網站根網址
            $web_basehost = rtrim($param['web_basehost'], '/');
            if (!is_http_url($web_basehost) && !empty($web_basehost)) {
                $web_basehost = 'http://'.$web_basehost;
            }
            $param['web_basehost'] = $web_basehost;

            // 網站logo
            $web_logo_is_remote = !empty($param['web_logo_is_remote']) ? $param['web_logo_is_remote'] : 0;
            $web_logo = '';
            if ($web_logo_is_remote == 1) {
                $web_logo = $param['web_logo_remote'];
            } else {
                $web_logo = $param['web_logo_local'];
            }
            $param['web_logo'] = $web_logo;
            unset($param['web_logo_is_remote']);
            unset($param['web_logo_remote']);
            unset($param['web_logo_local']);

            // 瀏覽器地址圖示
            if (!empty($param['web_ico']) && !is_http_url($param['web_ico'])) {
                $source = realpath(preg_replace('#^'.$root_dir.'/#i', '', $param['web_ico']));
                $destination = realpath('favicon.ico');
                if (file_exists($source) && @copy($source, $destination)) {
                    $param['web_ico'] = $root_dir.'/favicon.ico';
                }
            }

            tpCache($inc_type, $param);
            write_global_params(); // 寫入全域性內建參數
            $this->success('操作成功', url('System/web'));
            exit;
        }

        $config = tpCache($inc_type);
        // 網站logo
        if (is_http_url($config['web_logo'])) {
            $config['web_logo_is_remote'] = 1;
            $config['web_logo_remote'] = handle_subdir_pic($config['web_logo']);
        } else {
            $config['web_logo_is_remote'] = 0;
            $config['web_logo_local'] = handle_subdir_pic($config['web_logo']);
        }

        $config['web_ico'] = preg_replace('#^(/[/\w]+)?(/)#i', $root_dir.'$2', $config['web_ico']); // 支援子目錄
        
        /*系統模式*/
        $web_cmsmode = isset($config['web_cmsmode']) ? $config['web_cmsmode'] : 2;
        $this->assign('web_cmsmode', $web_cmsmode);
        /*--end*/

        /*自定義變數*/
        $eyou_row = M('config_attribute')->field('a.attr_id, a.attr_name, a.attr_var_name, a.attr_input_type, b.value, b.id, b.name')
            ->alias('a')
            ->join('__CONFIG__ b', 'b.name = a.attr_var_name AND b.lang = a.lang', 'LEFT')
            ->where([
                'b.lang'    => $this->admin_lang,
                'a.inc_type'    => $inc_type,
                'b.is_del'  => 0,
            ])
            ->order('a.attr_id asc')
            ->select();
        foreach ($eyou_row as $key => $val) {
            $val['value'] = handle_subdir_pic($val['value'], 'html'); // 支援子目錄
            $val['value'] = handle_subdir_pic($val['value']); // 支援子目錄
            $eyou_row[$key] = $val;
        }
        $this->assign('eyou_row',$eyou_row);
        /*--end*/

        $this->assign('config',$config);//目前配置項
        return $this->fetch();
    }

    /**
     * 核心設定
     */
    public function web2()
    {
        $this->language_access(); // 多語言功能操作許可權

        $inc_type = 'web';

        if (IS_POST) {
            $param = input('post.');

            /*EyouCMS安裝目錄*/
            empty($param['web_cmspath']) && $param['web_cmspath'] = ROOT_DIR; // 支援子目錄
            $web_cmspath = trim($param['web_cmspath'], '/');
            $web_cmspath = !empty($web_cmspath) ? '/'.$web_cmspath : '';
            $param['web_cmspath'] = $web_cmspath;
            /*--end*/
            /*外掛入口*/
            $web_weapp_switch = $param['web_weapp_switch'];
            $web_weapp_switch_old = tpCache('web.web_weapp_switch');
            /*--end*/
            /*會員入口*/
            $web_users_switch = $param['web_users_switch'];
            $web_users_switch_old = tpCache('web.web_users_switch');
            /*--end*/
            /*自定義後臺路徑名*/
            $adminbasefile = trim($param['adminbasefile']).'.php'; // 新的檔名
            $param['web_adminbasefile'] = ROOT_DIR.'/'.$adminbasefile; // 支援子目錄
            $adminbasefile_old = trim($param['adminbasefile_old']).'.php'; // 舊的檔名
            unset($param['adminbasefile']);
            unset($param['adminbasefile_old']);
            if ('index.php' == $adminbasefile) {
                $this->error("新後臺地址禁止使用index", null, '', 1);
            }
            /*--end*/
            $param['web_sqldatapath'] = '/'.trim($param['web_sqldatapath'], '/'); // 數據庫備份目錄
            $param['web_htmlcache_expires_in'] = intval($param['web_htmlcache_expires_in']); // 頁面快取有效期
            /*多語言入口*/
            $web_language_switch = $param['web_language_switch'];
            $web_language_switch_old = tpCache('web.web_language_switch');
            /*--end*/

            /*多語言*/
            if (is_language()) {
                $langRow = \think\Db::name('language')->order('id asc')
                    ->cache(true, EYOUCMS_CACHE_TIME, 'language')
                    ->select();
                foreach ($langRow as $key => $val) {
                    tpCache($inc_type,$param,$val['mark']);
                    write_global_params($val['mark']); // 寫入全域性內建參數
                }
            } else {
                tpCache($inc_type,$param);
                write_global_params(); // 寫入全域性內建參數
            }
            /*--end*/

            $refresh = false;
            $gourl = request()->domain().ROOT_DIR.'/'.$adminbasefile; // 支援子目錄
            /*更改自定義後臺路徑名*/
            if ($adminbasefile_old != $adminbasefile && eyPreventShell($adminbasefile_old)) {
                if (file_exists($adminbasefile_old)) {
                    if(rename($adminbasefile_old, $adminbasefile)) {
                        $refresh = true;
                    }
                } else {
                    $this->error("根目錄{$adminbasefile_old}檔案不存在！", null, '', 2);
                }
            }
            /*--end*/

            /*更改之後，需要重新整理後臺的參數*/
            if ($web_weapp_switch_old != $web_weapp_switch || $web_language_switch_old != $web_language_switch || $web_users_switch_old != $web_users_switch) {
                $refresh = true;
            }
            /*--end*/
            
            /*重新整理整個後臺*/
            if ($refresh) {
                $this->success('操作成功', $gourl, '', 1, [], '_parent');
            }
            /*--end*/

            $this->success('操作成功', url('System/web2'));
        }

        $config = tpCache($inc_type);
        //自定義後臺路徑名
        $baseFile = explode('/', $this->request->baseFile());
        $web_adminbasefile = end($baseFile);
        $adminbasefile = preg_replace('/^(.*)\.([^\.]+)$/i', '$1', $web_adminbasefile);
        $this->assign('adminbasefile', $adminbasefile);
        // 數據庫備份目錄
        $sqlbackuppath = config('DATA_BACKUP_PATH');
        $this->assign('sqlbackuppath', $sqlbackuppath);

        $this->assign('config',$config);//目前配置項
        return $this->fetch();
    }

    /**
     * 附件設定
     */
    public function basic()
    {
        $inc_type =  'basic';

        if (IS_POST) {
            $param = input('post.');

            // 禁止php副檔名的附件型別
            $param['image_type'] = str_ireplace('|php|', '|', '|'.$param['image_type'].'|');
            $param['image_type'] = trim($param['image_type'], '|');
            $param['file_type'] = str_ireplace('|php|', '|', '|'.$param['file_type'].'|');
            $param['file_type'] = trim($param['file_type'], '|');
            $param['media_type'] = str_ireplace('|php|', '|', '|'.$param['media_type'].'|');
            $param['media_type'] = trim($param['media_type'], '|');

            /*多語言*/
            if (is_language()) {
                $newParam['basic_indexname'] = $param['basic_indexname'];
                tpCache($inc_type,$newParam);

                $synLangParam = $param; // 同步更新多語言的數據
                unset($synLangParam['basic_indexname']);
                $langRow = \think\Db::name('language')->order('id asc')
                    ->cache(true, EYOUCMS_CACHE_TIME, 'language')
                    ->select();
                foreach ($langRow as $key => $val) {
                    tpCache($inc_type, $synLangParam, $val['mark']);
                }
            } else {
                tpCache($inc_type,$param);
            }
            /*--end*/
            $this->success('操作成功', url('System/basic'));
        }

        $config = tpCache($inc_type);
        $this->assign('config',$config);//目前配置項
        return $this->fetch();
    }

    /**
     * 圖片水印
     */
    public function water()
    {
        $this->language_access(); // 多語言功能操作許可權

        $inc_type =  'water';

        if (IS_POST) {
            $param = input('post.');
            $tabase = input('post.tabase/d');
            unset($param['tabase']);

            $mark_img_is_remote = !empty($param['mark_img_is_remote']) ? $param['mark_img_is_remote'] : 0;
            $mark_img = '';
            if ($mark_img_is_remote == 1) {
                $mark_img = $param['mark_img_remote'];
            } else {
                $mark_img = $param['mark_img_local'];
            }
            $param['mark_img'] = $mark_img;
            unset($param['mark_img_is_remote']);
            unset($param['mark_img_remote']);
            unset($param['mark_img_local']);

            /*多語言*/
            if (is_language()) {
                $langRow = \think\Db::name('language')->order('id asc')
                    ->cache(true, EYOUCMS_CACHE_TIME, 'language')
                    ->select();
                foreach ($langRow as $key => $val) {
                    tpCache($inc_type, $param, $val['mark']);
                }
            } else {
                tpCache($inc_type,$param);
            }
            /*--end*/
            $this->success('操作成功', url('System/'.$inc_type, ['tabase'=>$tabase]));
        }

        $config = tpCache($inc_type);
        if (is_http_url($config['mark_img'])) {
            $config['mark_img_is_remote'] = 1;
            $config['mark_img_remote'] = handle_subdir_pic($config['mark_img']);
        } else {
            $config['mark_img_is_remote'] = 0;
            $config['mark_img_local'] = handle_subdir_pic($config['mark_img']);
        }

        $this->assign('config',$config);//目前配置項
        return $this->fetch();
    }

    /**
     * 縮圖配置
     */
    public function thumb()
    {
        $this->language_access(); // 多語言功能操作許可權

        $inc_type =  'thumb';

        if (IS_POST) {
            $param = input('post.');
            $tabase = input('post.tabase/d');
            unset($param['tabase']);
            isset($param['thumb_width']) && $param['thumb_width'] = preg_replace('/[^0-9]/', '', $param['thumb_width']);
            isset($param['thumb_height']) && $param['thumb_height'] = preg_replace('/[^0-9]/', '', $param['thumb_height']);

            $thumbConfig = tpCache('thumb'); // 舊數據

            /*多語言*/
            if (is_language()) {
                $langRow = \think\Db::name('language')->order('id asc')
                    ->cache(true, EYOUCMS_CACHE_TIME, 'language')
                    ->select();
                foreach ($langRow as $key => $val) {
                    tpCache($inc_type, $param, $val['mark']);
                }
            } else {
                tpCache($inc_type,$param);
            }
            /*--end*/

            /*校驗配置是否改動，若改動將會清空縮圖目錄*/
            unset($param['__token__']);
            if (md5(serialize($param)) != md5(serialize($thumbConfig))) {
                delFile(RUNTIME_PATH.'html'); // 清空快取頁面
                delFile(UPLOAD_PATH.'thumb'); // 清空縮圖
            }
            /*--end*/

            $this->success('操作成功', url('System/'.$inc_type, ['tabase'=>$tabase]));
        }

        $config = tpCache($inc_type);

        // 設定縮圖預設配置
        if (!isset($config['thumb_open'])) {
            /*多語言*/
            $thumbextra = config('global.thumb');
            $param = [
                'thumb_open'    => $thumbextra['open'],
                'thumb_mode'    => $thumbextra['mode'],
                'thumb_color'   => $thumbextra['color'],
                'thumb_width'   => $thumbextra['width'],
                'thumb_height'  => $thumbextra['height'],
            ];
            if (is_language()) {
                $langRow = \think\Db::name('language')->order('id asc')->select();
                foreach ($langRow as $key => $val) {
                    tpCache($inc_type, $param, $val['mark']);
                }
            } else {
                tpCache($inc_type,$param);
            }
            $config = tpCache($inc_type);
            /*--end*/
        }

        $this->assign('config',$config);//目前配置項
        return $this->fetch();
    }

    /**
     * 郵件配置
     */
    public function smtp()
    {
        $inc_type =  'smtp';

        if (IS_POST) {
            $param = input('post.');
            /*多語言*/
            if (is_language()) {
                $langRow = \think\Db::name('language')->order('id asc')
                    ->cache(true, EYOUCMS_CACHE_TIME, 'language')
                    ->select();
                foreach ($langRow as $key => $val) {
                    tpCache($inc_type, $param, $val['mark']);
                }
            } else {
                tpCache($inc_type,$param);
            }
            /*--end*/
            $this->success('操作成功', url('System/smtp'));
        }

        $config = tpCache($inc_type);
        $this->assign('config',$config);//目前配置項
        return $this->fetch();
    }

    /**
     * 郵件模板列表
     */
    public function smtp_tpl()
    {
        $list = array();
        $keywords = input('keywords/s');

        $map = array();
        if (!empty($keywords)) {
            $map['tpl_name'] = array('LIKE', "%{$keywords}%");
        }

        // 多語言
        $map['lang'] = array('eq', $this->admin_lang);

        $count = Db::name('smtp_tpl')->where($map)->count('tpl_id');// 查詢滿足要求的總記錄數
        $pageObj = new Page($count, config('paginate.list_rows'));// 實例化分頁類 傳入總記錄數和每頁顯示的記錄數
        $list = Db::name('smtp_tpl')->where($map)
            ->order('tpl_id asc')
            ->limit($pageObj->firstRow.','.$pageObj->listRows)
            ->select();
        $pageStr = $pageObj->show(); // 分頁顯示輸出
        $this->assign('list', $list); // 賦值數據集
        $this->assign('pageStr', $pageStr); // 賦值分頁輸出
        $this->assign('pageObj', $pageObj); // 賦值分頁對像
        
        return $this->fetch();
    }
    
    /**
     * 郵件模板列表 - 編輯
     */
    public function smtp_tpl_edit()
    {
        if (IS_POST) {
            $post = input('post.');
            $post['tpl_id'] = eyIntval($post['tpl_id']);
            if(!empty($post['tpl_id'])){
                $post['tpl_title'] = trim($post['tpl_title']);

                /*組裝儲存數據*/
                $nowData = array(
                    'update_time'   => getTime(),
                );
                $saveData = array_merge($post, $nowData);
                /*--end*/
                
                $r = Db::name('smtp_tpl')->where([
                        'tpl_id'    => $post['tpl_id'],
                        'lang'      => $this->home_lang,
                    ])->update($saveData);
                if ($r) {
                    $tpl_name = Db::name('smtp_tpl')->where([
                            'tpl_id'    => $post['tpl_id'],
                            'lang'      => $this->home_lang,
                        ])->getField('tpl_name');
                    adminLog('編輯郵件模板：'.$tpl_name); // 寫入操作日誌
                    $this->success("操作成功", url('System/smtp_tpl'));
                }
            }
            $this->error("操作失敗");
        }

        $id = input('id/d', 0);
        $row = Db::name('smtp_tpl')->where([
                'tpl_id'    => $id,
                'lang'      => $this->home_lang,
            ])->find();
        if (empty($row)) {
            $this->error('數據不存在，請聯繫管理員！');
            exit;
        }

        $this->assign('row',$row);
        return $this->fetch();
    }

    /**
     * 清空快取 - 相容升級沒重新整理後臺，點選報錯404，過1.2.5版本之後清除掉程式碼
     */
    public function clearCache()
    {
        return $this->clear_cache();
    }

    /**
     * 清空快取
     */
    public function clear_cache()
    {
        if (IS_POST) {
            if (!function_exists('unlink')) {
                $this->error('php.ini未開啟unlink函式，請聯繫空間商處理！');
            }

            $post = input('post.');

            if (!empty($post['clearHtml'])) { // 清除頁面快取
                $this->clearHtmlCache($post['clearHtml']);
            }

            if (!empty($post['clearCache'])) { // 清除數據快取
                $this->clearSystemCache($post['clearCache']);
            }

            // 清除其他臨時檔案
            $this->clearOtherCache();

            /*相容每個使用者的自定義欄位，重新產生數據表字段快取檔案*/
            $systemTables = ['arctype'];
            $data = Db::name('channeltype')
                ->where('nid','NEQ','guestbook')
                ->column('table');
            $tables = array_merge($systemTables, $data);
            foreach ($tables as $key => $table) {
                if ('arctype' != $table) {
                    $table = $table.'_content';
                }
                try {
                    schemaTable($table);
                } catch (\Exception $e) {}
            }
            /*--end*/

            /*清除舊升級備份包，保留最後一個備份檔案*/
            $backupArr = glob(DATA_PATH.'backup/v*_www');
            for ($i=0; $i < count($backupArr) - 1; $i++) { 
                delFile($backupArr[$i], true);
            }

            $backupArr = glob(DATA_PATH.'backup/*');
            foreach ($backupArr as $key => $filepath) {
                if (file_exists($filepath) && !stristr($filepath, '.htaccess') && !stristr($filepath, '_www')) {
                    if (is_dir($filepath)) {
                        delFile($filepath, true);
                    } else if (is_file($filepath)) {
                        @unlink($filepath);
                    }
                }
            }
            /*--end*/

            // cache('admin_ModuleInitBehavior_isset_checkInlet', 1); // 配合ModuleInitBehavior.php行為的checkInlet方法，進行自動隱藏index.php

            $request = Request::instance();
            $gourl = $request->baseFile();
            $lang = $request->param('lang/s');
            if (!empty($lang) && $lang != get_main_lang()) {
                $gourl .= "?lang={$lang}";
            }
            $this->success('操作成功', $gourl, '', 1, [], '_parent');
        }
        
        return $this->fetch();
    }

    /**
     * 清空數據快取
     */
    public function fastClearCache($arr = array())
    {
        $this->clearSystemCache();
        $script = "<script>parent.layer.msg('操作成功', {time:3000,icon: 1});window.location='".url('Index/welcome')."';</script>";
        echo $script;
    }

    /**
     * 清空數據快取
     */
    public function clearSystemCache($arr = array())
    {
        if (empty($arr)) {
            delFile(rtrim(RUNTIME_PATH, '/'), true);
        } else {
            foreach ($arr as $key => $val) {
                delFile(RUNTIME_PATH.$val, true);
            }
        }

        /*多語言*/
        if (is_language()) {
            $langRow = Db::name('language')->order('id asc')
                ->cache(true, EYOUCMS_CACHE_TIME, 'language')
                ->select();
            foreach ($langRow as $key => $val) {
                tpCache('global', '', $val['mark']);
            }
        } else { // 單語言
            tpCache('global');
        }
        /*--end*/

        return true;
    }

    /**
     * 清空頁面快取
     */
    public function clearHtmlCache($arr = array())
    {
        if (empty($arr)) {
            delFile(rtrim(HTML_ROOT, '/'), true);
        } else {
            foreach ($arr as $key => $val) {
                $fileList = glob(HTML_ROOT.'http*/'.$val.'*');
                if (!empty($fileList)) {
                    foreach ($fileList as $k2 => $v2) {
                        if (file_exists($v2) && is_dir($v2)) {
                            delFile($v2, true);
                        } else if (file_exists($v2) && is_file($v2)) {
                            @unlink($v2);
                        }
                    }
                }
                if ($val == 'index') {
                    foreach (['index.html','indexs.html'] as $sk1 => $sv1) {
                        $filename = ROOT_PATH.$sv1;
                        if (file_exists($filename)) {
                            @unlink($filename);
                        }
                    }
                }
            }
        }
    }

    /**
     * 清除其他臨時檔案
     */
    private function clearOtherCache()
    {
        $arr = [
            'template',
        ];
        foreach ($arr as $key => $val) {
            delFile(RUNTIME_PATH.$val, true);
        }

        return true;
    }
      
    /**
     * 發送測試郵件
     */
    public function send_email()
    {
        $param = $smtp_config = input('post.');
        $title = '演示標題';
        $content = '演示一串隨機數字：' . mt_rand(100000,999999);
        $res = send_email($param['smtp_from_eamil'], $title, $content, 0, $smtp_config);
        if (intval($res['code']) == 1) {
            /*多語言*/
            if (is_language()) {
                $langRow = \think\Db::name('language')->order('id asc')
                    ->cache(true, EYOUCMS_CACHE_TIME, 'language')
                    ->select();
                foreach ($langRow as $key => $val) {
                    tpCache('smtp', $smtp_config, $val['mark']);
                }
            } else {
                tpCache('smtp',$smtp_config);
            }
            /*--end*/
            $this->success($res['msg']);
        } else {
            $this->error($res['msg']);
        }
    }
      
    /**
     * 發送測試簡訊
     */
    public function send_mobile()
    {
        $param = input('post.');
        $res = sendSms(4,$param['sms_test_mobile'],array('content'=>mt_rand(1000,9999)));
        exit(json_encode($res));
    }

    /**
     * 新增自定義變數
     */
    public function customvar_add()
    {
        $this->language_access(); // 多語言功能操作許可權

        if (IS_POST) {
            $configAttributeM = model('ConfigAttribute');

            $post_data = input('post.');
            $attr_input_type = isset($post_data['attr_input_type']) ? $post_data['attr_input_type'] : '';

            if ($attr_input_type == 3) {
                // 本地/遠端圖片上傳的處理
                $is_remote = !empty($post_data['is_remote']) ? $post_data['is_remote'] : 0;
                $litpic = '';
                if ($is_remote == 1) {
                    $litpic = $post_data['value_remote'];
                } else {
                    $litpic = $post_data['value_local'];
                }
                $attr_values = $litpic;
            } else {
                $attr_values = input('attr_values');
                // $attr_values = str_replace('_', '', $attr_values); // 替換特殊字元
                // $attr_values = str_replace('@', '', $attr_values); // 替換特殊字元
                $attr_values = trim($attr_values);
                $attr_values = isset($attr_values) ? $attr_values : '';
            }

            $savedata = array(
                'inc_type'    => $post_data['inc_type'],
                'attr_name' => $post_data['attr_name'],
                'attr_input_type'   => $attr_input_type,
                'attr_values'   => $attr_values,
                'update_time'   => getTime(),
            );

            // 數據驗證            
            $validate = \think\Loader::validate('ConfigAttribute');
            if(!$validate->batch()->check($savedata))
            {
                $error = $validate->getError();
                $error_msg = array_values($error);
                $this->error($error_msg[0]);
            } else {
                $langRow = Db::name('language')->order('id asc')
                    ->cache(true, EYOUCMS_CACHE_TIME, 'language')
                    ->select();

                $attr_var_name = '';
                foreach ($langRow as $key => $val) {
                    $savedata['add_time'] = getTime();
                    $savedata['lang'] = $val['mark'];
                    $insert_id = Db::name('config_attribute')->insertGetId($savedata);
                    // 更新變數名
                    if (!empty($insert_id)) {
                        if (0 == $key) {
                            $attr_var_name = $post_data['inc_type'].'_attr_'.$insert_id;
                        }
                        Db::name('config_attribute')->where([
                                'attr_id'   => $insert_id,
                                'lang'  => $val['mark'],
                            ])->update(array('attr_var_name'=>$attr_var_name));
                    }
                }
                adminLog('新增自定義變數：'.$savedata['attr_name']);

                // 儲存到config表，更新快取
                $inc_type = $post_data['inc_type'];
                $configData = array(
                    $attr_var_name  => $attr_values,
                );

                // 多語言
                if (is_language()) {
                    foreach ($langRow as $key => $val) {
                        tpCache($inc_type, $configData, $val['mark']);
                    }
                } else { // 單語言
                    tpCache($inc_type, $configData);
                }

                $this->success('操作成功');
            }  
        }

        $inc_type = input('param.inc_type/s', '');
        $this->assign('inc_type', $inc_type);

        return $this->fetch();
    }

    /**
     * 編輯自定義變數
     */
    public function customvar_edit()
    {
        if (IS_POST) {
            $configAttributeM = model('ConfigAttribute');

            $post_data = input('post.');
            $attr_input_type = isset($post_data['attr_input_type']) ? $post_data['attr_input_type'] : '';

            if ($attr_input_type == 3) {
                // 本地/遠端圖片上傳的處理
                $is_remote = !empty($post_data['is_remote']) ? $post_data['is_remote'] : 0;
                $litpic = '';
                if ($is_remote == 1) {
                    $litpic = $post_data['value_remote'];
                } else {
                    $litpic = $post_data['value_local'];
                }
                $attr_values = $litpic;
            } else {
                $attr_values = input('attr_values');
                // $attr_values = str_replace('_', '', $attr_values); // 替換特殊字元
                // $attr_values = str_replace('@', '', $attr_values); // 替換特殊字元
                $attr_values = trim($attr_values);
                $attr_values = isset($attr_values) ? $attr_values : '';
            }

            $savedata = array(
                'inc_type'    => $post_data['inc_type'],
                'attr_name' => $post_data['attr_name'],
                'attr_input_type'   => $attr_input_type,
                'attr_values'   => $attr_values,
                'update_time'   => getTime(),
            );

            // 數據驗證            
            $validate = \think\Loader::validate('ConfigAttribute');
            if(!$validate->batch()->check($savedata))
            {
                $error = $validate->getError();
                $error_msg = array_values($error);
                $this->error($error_msg[0]);
            } else {
                $langRow = Db::name('language')->order('id asc')
                    ->cache(true, EYOUCMS_CACHE_TIME, 'language')
                    ->select();

                $configAttributeM->data($savedata,true); // 收集數據
                $configAttributeM->isUpdate(true, [
                        'attr_id'   => $post_data['attr_id'],
                        'lang'  => $this->admin_lang,
                    ])->save(); // 寫入數據到數據庫  
                // 更新變數名
                $attr_var_name = $post_data['name'];
                adminLog('編輯自定義變數：'.$savedata['attr_name']);

                // 儲存到config表，更新快取
                $inc_type = $post_data['inc_type'];
                $configData = array(
                    $attr_var_name  => $attr_values,
                );

                tpCache($inc_type, $configData);

                $this->success('操作成功');
            }  
        }

        $field = array();
        $id = input('param.id/d', 0);
        $field = M('config')->field('a.id, a.value, a.name, b.attr_id, b.attr_name, b.attr_input_type')
            ->alias('a')
            ->join('__CONFIG_ATTRIBUTE__ b', 'a.name=b.attr_var_name AND a.lang=b.lang', 'LEFT')
            ->where([
                'a.id'    => $id,
                'a.lang'  => $this->admin_lang,
            ])->find();
        if ($field['attr_input_type'] == 3) {
            if (is_http_url($field['value'])) {
                $field['is_remote'] = 1;
                $field['value_remote'] = $field['value'];
            } else {
                $field['is_remote'] = 0;
                $field['value_local'] = $field['value'];
            }
        }
        $this->assign('field', $field);

        $inc_type = input('param.inc_type/s', '');
        $this->assign('inc_type', $inc_type);

        return $this->fetch();
    }

    /**
     * 刪除自定義變數
     */
    public function customvar_del()
    {
        $this->language_access(); // 多語言功能操作許可權

        $id = input('del_id/d');
        if(!empty($id)){
            $attr_var_name = M('config')->where([
                    'id'    => $id,
                    'lang'  => $this->admin_lang,
                ])->getField('name');

            $r = M('config')->where('name', $attr_var_name)->update(array('is_del'=>1, 'update_time'=>getTime()));
            if($r){
                M('config_attribute')->where('attr_var_name', $attr_var_name)->update(array('update_time'=>getTime()));
                adminLog('刪除自定義變數：'.$attr_var_name);
                $this->success('刪除成功');
            }else{
                $this->error('刪除失敗');
            }
        }else{
            $this->error('參數有誤');
        }
    }

    /**
     * 標籤呼叫的彈窗說明
     */
    public function ajax_tag_call()
    {
        if (IS_AJAX_POST) {
            $name = input('post.name/s');
            $msg = '';
            switch ($name) {
                case 'web_users_switch': // 會員功能入口標籤
                    {
                        $msg = '
<div yne-bulb-block="paragraph">
    <strong>前臺會員登錄註冊標籤呼叫</strong><br data-filtered="filtered">
    比如需要在PC通用頭部加入會員入口，複製下方程式碼在\template\pc\header.htm模板檔案里找到合適位置貼上
</div>
<br data-filtered="filtered">
<div yne-bulb-block="paragraph" style="color:red;">
    <div>
        {eyou:user type=\'open\'}
        &nbsp;</div>
    <div>
        &nbsp; &nbsp; &nbsp; &nbsp; {eyou:user type=\'login\'}</div>
    <div>
        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &lt;a href="{$field.url}" id="{$field.id}" &gt;登錄&lt;/a&gt;</div>
    <div>
        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; {$field.hidden}</div>
    <div>
        &nbsp; &nbsp; &nbsp; &nbsp; {/eyou:user}</div>
    <div>
        &nbsp;&nbsp;</div>
    <div>
        &nbsp; &nbsp; &nbsp; &nbsp; {eyou:user type=\'reg\'}</div>
    <div>
        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &lt;a href="{$field.url}" id="{$field.id}" &gt;註冊&lt;/a&gt;</div>
    <div>
        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; {$field.hidden}</div>
    <div>
        &nbsp; &nbsp; &nbsp; &nbsp; {/eyou:user}</div>
    <div>
        &nbsp;</div>
    <div>
        &nbsp; &nbsp; &nbsp; &nbsp; {eyou:user type=\'logout\'}</div>
    <div>
        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &lt;a href="{$field.url}" id="{$field.id}" &gt;退出&lt;/a&gt;</div>
    <div>
        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; {$field.hidden}</div>
    <div>
        &nbsp; &nbsp; &nbsp; &nbsp; {/eyou:user}
        &nbsp;</div>
    <div>
        {/eyou:user}</div>
</div>
';
                    }
                    break;

                case 'web_language_switch': // 多語言入口標籤
                    {
                        $msg = '
<div yne-bulb-block="paragraph">
    <strong>前臺多語言切換入口標籤呼叫</strong><br data-filtered="filtered">
    比如需要在PC通用頭部加入多語言切換，複製下方程式碼在\template\pc\header.htm模板檔案里找到合適位置貼上
</div>
<br data-filtered="filtered">
<div yne-bulb-block="paragraph" style="color:red">
    {eyou:language type=\'default\'}<br/>
    &nbsp;&nbsp;&nbsp;&nbsp;&lt;a href="{$field.url}"&gt;&lt;img src="{$field.logo}" alt="{$field.title}"&gt;{$field.title}&lt;/a&gt;<br/>
    {/eyou:language}</div>
';
                    }
                    break;

                case 'thumb_open':
                    {
                        $msg = '
<div yne-bulb-block="paragraph">
    <span style="color:red">（溫馨提示：高級呼叫不會受縮圖功能的開關影響！）</span></div>
<div yne-bulb-block="paragraph">
    【標籤方法的格式】</div>
<div yne-bulb-block="paragraph">
    &nbsp;&nbsp;&nbsp;&nbsp;thumb_img=###,寬度,高度,產生方式</div>
<br data-filtered="filtered">
<div yne-bulb-block="paragraph">
    【指定寬高度的呼叫】</div>
<div yne-bulb-block="paragraph">
    &nbsp;&nbsp;&nbsp;&nbsp;列表頁/內容頁：{$eyou.field.litpic<span style="color:red">|thumb_img=###,500,500</span>}</div>
<div yne-bulb-block="paragraph">
    &nbsp;&nbsp;&nbsp;&nbsp;標籤arclist/list里：{$field.litpic<span style="color:red">|thumb_img=###,500,500</span>}</div>
<br data-filtered="filtered">
<div yne-bulb-block="paragraph">
    【指定產生方式的呼叫】</div>
<div yne-bulb-block="paragraph">
    &nbsp;&nbsp;&nbsp;&nbsp;產生方式：1 = 拉伸；2 = 留白；3 = 截減；<br data-filtered="filtered">
    &nbsp;&nbsp;&nbsp;&nbsp;以標籤arclist為例：</div>
<div yne-bulb-block="paragraph">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;縮圖拉伸：{$field.litpic<span style="color:red">|thumb_img=###,500,500,1</span>}</div>
<div yne-bulb-block="paragraph">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;縮圖留白：{$field.litpic<span style="color:red">|thumb_img=###,500,500,2</span>}</div>
<div yne-bulb-block="paragraph">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;縮圖截減：{$field.litpic<span style="color:red">|thumb_img=###,500,500,3</span>}</div>
<div yne-bulb-block="paragraph">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;默&nbsp;認&nbsp;生&nbsp;成：{$field.litpic<span style="color:red">|thumb_img=###,500,500</span>}&nbsp;&nbsp;&nbsp;&nbsp;(以預設全域性配置的產生方式)</div>
';
                    }
                    break;
                
                case 'shop_open':
                    {
                        $msg = '
<div yne-bulb-block="paragraph">
    <strong>前臺產品內容頁的購買入口標籤呼叫</strong><br data-filtered="filtered">
    比如需要在產品模型的內容頁加入購買功能，複製下方程式碼在\template\pc\view_product.htm模板檔案里找到合適位置貼上
</div>
<br data-filtered="filtered">
<div yne-bulb-block="paragraph" style="color:red">
  &lt;!--購物車元件start--&gt; 
  <br data-filtered="filtered">
  {eyou:sppurchase id=\'field\'}
  <br data-filtered="filtered">&nbsp;&nbsp;&nbsp;&nbsp;
      &lt;div class="ey-price"&gt;&lt;span&gt;￥{$field.users_price}&lt;/span&gt; &lt;/div&gt;
      <br data-filtered="filtered">&nbsp;&nbsp;&nbsp;&nbsp;
      &lt;div class="ey-number"&gt;
      <br data-filtered="filtered">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &lt;label&gt;數量&lt;/label&gt;
        <br data-filtered="filtered">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &lt;div class="btn-input"&gt;
        <br data-filtered="filtered">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &lt;button class="layui-btn" {$field.ReduceQuantity}&gt;-&lt;/button&gt;
          <br data-filtered="filtered">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &lt;input type="text" class="layui-input" {$field.UpdateQuantity}&gt;
          <br data-filtered="filtered">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &lt;button class="layui-btn" {$field.IncreaseQuantity}&gt;+&lt;/button&gt;
          <br data-filtered="filtered">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &lt;/div&gt;
        <br data-filtered="filtered">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      &lt;/div&gt;
      <br data-filtered="filtered">&nbsp;&nbsp;&nbsp;&nbsp;
      &lt;div class="ey-buyaction"&gt;
      <br data-filtered="filtered">&nbsp;&nbsp;&nbsp;&nbsp;
      &lt;a class="ey-joinin" href="JavaScript:void(0);" {$field.ShopAddCart}&gt;加入購物車&lt;/a&gt;
      <br data-filtered="filtered">&nbsp;&nbsp;&nbsp;&nbsp;
      &lt;a class="ey-joinbuy" href="JavaScript:void(0);" {$field.BuyNow}&gt;立即購買&lt;/a&gt;
      <br data-filtered="filtered">&nbsp;&nbsp;&nbsp;&nbsp;
      &lt;/div&gt;
      <br data-filtered="filtered">&nbsp;&nbsp;&nbsp;&nbsp;
      {$field.hidden}
      <br data-filtered="filtered">
  {/eyou:sppurchase}
  <br data-filtered="filtered">
  &lt;!--購物車元件end--&gt; 
</div>
';
                    }
                    break;

                default:
                    # code...
                    break;
            }
            $this->success('請求成功', null, ['msg'=>$msg]);
        }
        $this->error('非法訪問！');
    }
}