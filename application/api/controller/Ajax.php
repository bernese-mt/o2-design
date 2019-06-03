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

namespace app\api\controller;

use think\Db;

class Ajax extends Base
{
    /*
     * 初始化操作
     */
    public function _initialize() {
        parent::_initialize();
    }

    /**
     * 內容頁瀏覽量的自增介面
     */
    public function arcclick()
    {
        if (IS_AJAX) {
            $aid = input('aid/d', 0);
            $click = 0;
            if (empty($aid)) {
                echo($click);
                exit;
            }

            if ($aid > 0) {
                $archives_db = Db::name('archives');
                $archives_db->where(array('aid'=>$aid))->setInc('click'); 
                $click = $archives_db->where(array('aid'=>$aid))->getField('click');
            }

            echo($click);
            exit;
        }
    }

    /**
     * arclist列表分頁arcpagelist標籤介面
     */
    public function arcpagelist()
    {
        $pnum = input('page/d', 0);
        $pagesize = input('pagesize/d', 0);
        $tagid = input('tagid/s', '');
        !empty($tagid) && $tagid = preg_replace("/[^a-zA-Z0-9-_]/",'', $tagid);

        if (empty($tagid) || empty($pnum)) {
            $this->error('參數有誤');
        }

        $data = [
            'code' => 1,
            'msg'   => '',
            'lastpage'  => 0,
        ];

        $arcmulti_db = Db::name('arcmulti');
        $arcmultiRow = $arcmulti_db->where(['tagid'=>$tagid])->find();
        if(!empty($arcmultiRow) && !empty($arcmultiRow['querysql']))
        {
            // arcpagelist標籤屬性pagesize優先順序高於arclist標籤屬性pagesize
            if (0 < intval($pagesize)) {
                $arcmultiRow['pagesize'] = $pagesize;
            }

            // 取出屬性並解析為變數
            $attarray = unserialize(stripslashes($arcmultiRow['attstr']));
            // extract($attarray, EXTR_SKIP); // 把陣列中的鍵名直接註冊爲了變數

            // 通過頁面及總數解析目前頁面數據範圍
            $pnum < 2 && $pnum = 2;
            $strnum = intval($attarray['row']) + ($pnum - 2) * $arcmultiRow['pagesize'];

            // 拼接完整的SQL
            $querysql = preg_replace('#LIMIT(\s+)(\d+)(,\d+)?#i', '', $arcmultiRow['querysql']);
            $querysql = preg_replace('#SELECT(\s+)(.*)(\s+)FROM#i', 'SELECT COUNT(*) AS totalNum FROM', $querysql);
            $queryRow = Db::query($querysql);
            if (!empty($queryRow)) {
                if (empty($arcmultiRow['innertext'])) {
                    $data['code'] = -1;
                    $data['msg'] = "模板追加檔案 arclist_{$tagid}.htm 不存在，或者檔案沒有內容！";
                    $this->error("標籤模板不存在", null, $data);
                }

                /*拼接完整的arclist標籤語法*/
                $offset = intval($strnum);
                $row = intval($offset) + intval($arcmultiRow['pagesize']);
                $innertext = "{eyou:arclist";
                foreach ($attarray as $key => $val) {
                    if (in_array($key, ['tagid','offset','row'])) {
                        continue;
                    }
                    $innertext .= " {$key}='{$val}'";
                }
                $innertext .= " limit='{$offset},{$row}'}";
                $innertext .= stripslashes($arcmultiRow['innertext']);
                $innertext .= "{/eyou:arclist}";
                /*--end*/
                $msg = $this->display($innertext); // 渲染模板標籤語法
                $data['msg'] = $msg;

                //是否到了最終頁
                if (!empty($queryRow[0]['totalNum']) && $queryRow[0]['totalNum'] <= $row) {
                    $data['lastpage'] = 1;
                }

            } else {
                $data['lastpage'] = 1;
            }
        }

        $this->success('請求成功', null, $data);
    }

    /**
     * 獲取表單令牌
     */
    public function get_token($name = '__token__')
    {
        if (IS_AJAX) {
            echo $this->request->token($name);
            exit;
        }
    }

    /**
     * 檢驗會員登錄
     */
    public function check_user()
    {
        if (IS_AJAX) {
            $type = input('param.type/s', 'default');
            $img = input('param.img/s');
            if ('login' == $type) {
                $users_id = session('users_id');
                if (!empty($users_id)) {
                    $currentstyle = input('param.currentstyle/s');
                    $users = M('users')->field('username,head_pic')
                        ->where([
                            'users_id'  => $users_id,
                            'lang'      => $this->home_lang,  
                        ])->find();
                    if (!empty($users)) {
                        $username = $users['username'];
                        $head_pic = get_head_pic($users['head_pic']);
                        if ('on' == $img) {
                            $users['html'] = "<img class='{$currentstyle}' alt='{$username}' src='{$head_pic}' />";
                        } else {
                            $users['html'] = $username;
                        }
                        $users['ey_is_login'] = 1;
                        $this->success('請求成功', null, $users);
                    }
                }
                $this->success('請先登錄', null, ['ey_is_login'=>0]);
            }
            else if ('reg' == $type)
            {
                if (session('?users_id')) {
                    $users['ey_is_login'] = 1;
                } else {
                    $users['ey_is_login'] = 0;
                }
                $this->success('請求成功', null, $users);
            }
            else if ('logout' == $type)
            {
                if (session('?users_id')) {
                    $users['ey_is_login'] = 1;
                } else {
                    $users['ey_is_login'] = 0;
                }
                $this->success('請求成功', null, $users);
            }
        }
        $this->error('訪問錯誤');
    }

    /**
     * 獲取使用者資訊
     */
    public function get_tag_user_info()
    {
        $t_uniqid = input('param.t_uniqid/s', '');
        if (IS_AJAX && !empty($t_uniqid)) {
            $users_id = session('users_id');
            if (!empty($users_id)) {
                $users = Db::name('users')->field('b.*, a.*')
                    ->alias('a')
                    ->join('__USERS_LEVEL__ b', 'a.level = b.level_id', 'LEFT')
                    ->where([
                        'a.users_id' => $users_id,
                        'a.lang'     => $this->home_lang,
                    ])->find();
                if (!empty($users)) {
                    $users['reg_time'] = MyDate('Y-m-d H:i:s', $users['reg_time']);
                    $users['update_time'] = MyDate('Y-m-d H:i:s', $users['update_time']);
                } else {
                    $users = [];
                    $tableFields1 = Db::name('users')->getTableFields();
                    $tableFields2 = Db::name('users_level')->getTableFields();
                    $tableFields = array_merge($tableFields1, $tableFields2);
                    foreach ($tableFields as $key => $val) {
                        $users[$val] = '';
                    }
                }
                $users['url'] = url('user/Users/centre');
                unset($users['password']);
                unset($users['paypwd']);
                $dtypes = [];
                foreach ($users as $key => $val) {
                    $html_key = md5($key.'-'.$t_uniqid);
                    $users[$html_key] = $val;

                    $dtype = 'txt';
                    if (in_array($key, ['head_pic'])) {
                        $dtype = 'img';
                    } else if (in_array($key, ['url'])) {
                        $dtype = 'href';
                    }
                    $dtypes[$html_key] = $dtype;

                    unset($users[$key]);
                }

                $data = [
                    'ey_is_login'   => 1,
                    'users'  => $users,
                    'dtypes'  => $dtypes,
                ];
                $this->success('請求成功', null, $data);
            }
            $this->success('請先登錄', null, ['ey_is_login'=>0]);
        }
        $this->error('訪問錯誤');
    }

    // 驗證碼獲取
    public function vertify()
    {
        $type = input('param.type/s', 'default');
        $configList = \think\Config::get('captcha');
        $captchaArr = array_keys($configList);
        if (in_array($type, $captchaArr)) {
            /*驗證碼外掛開關*/
            $admin_login_captcha = config('captcha.'.$type);
            $config = (!empty($admin_login_captcha['is_on']) && !empty($admin_login_captcha['config'])) ? $admin_login_captcha['config'] : config('captcha.default');
            /*--end*/
            ob_clean(); // 清空快取，才能顯示驗證碼
            $Verify = new \think\Verify($config);
            $Verify->entry($type);
        }
        exit();
    }
      
    /**
     * 郵箱發送
     */
    public function send_email()
    {
        // 超時後，斷掉郵件發送
        function_exists('set_time_limit') && set_time_limit(5);

        $type = input('param.type/s');
        
        // 留言發送郵件
        if (IS_AJAX_POST && 'gbook_submit' == $type) {
            $tid = input('param.tid/d');
            $aid = input('param.aid/d');

            $send_email_scene = config('send_email_scene');
            $scene = $send_email_scene[1]['scene'];

            $web_name = tpCache('web.web_name');
            // 判斷標題拼接
            $arctype  = M('arctype')->field('typename')->find($tid);
            $web_name = $arctype['typename'].'-'.$web_name;

            // 拼裝發送的字串內容
            $row = M('guestbook_attribute')->field('a.attr_name, b.attr_value')
                ->alias('a')
                ->join('__GUESTBOOK_ATTR__ b', 'a.attr_id = b.attr_id AND a.typeid = '.$tid, 'LEFT')
                ->where([
                    'b.aid' => $aid,
                ])
                ->order('a.attr_id sac')
                ->select();
            $content = '';
            foreach ($row as $key => $val) {
                $content .= $val['attr_name'] . '：' . $val['attr_value'].'<br/>';
            }
            $html = "<p style='text-align: left;'>{$web_name}</p><p style='text-align: left;'>{$content}</p>";
            if (isMobile()) {
                $html .= "<p style='text-align: left;'>——來源：移動端</p>";
            } else {
                $html .= "<p style='text-align: left;'>——來源：電腦端</p>";
            }
            
            // 發送郵件
            $res = send_email(null,null,$html, $scene);
            if (intval($res['code']) == 1) {
                $this->success($res['msg']);
            } else {
                $this->error($res['msg']);
            }
        }
    }
}