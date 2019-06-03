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
 * Date: 2019-1-25
 */

namespace app\user\controller;

use think\Db;
// use think\Session;
use think\Config;
use think\Verify;
use app\user\logic\SmtpmailLogic;

class Users extends Base
{
    public $smtpmailLogic;

    public function _initialize() {
        parent::_initialize();
        $this->smtpmailLogic = new SmtpmailLogic;
        $this->users_db       = Db::name('users');      // 用戶數據表
        $this->users_level_db = Db::name('users_level'); // 使用者等級表
        $this->users_parameter_db  = Db::name('users_parameter'); // 使用者屬性表
        $this->users_list_db  = Db::name('users_list'); // 使用者屬性資訊表
        $this->users_config_db= Db::name('users_config');// 使用者配置表
        $this->users_money_db = Db::name('users_money');// 使用者金額明細表
        $this->smtp_record_db = Db::name('smtp_record');// 發送郵箱記錄表

    }

    // 會員中心首頁
    public function index()
    {
        $result = [];

        // 資料資訊
        $result['users_para'] = model('Users')->getDataParaList($this->users_id);
        $this->assign('users_para',$result['users_para']);

        // 菜單名稱
        $result['title'] = Db::name('users_menu')->where([
                'mca'   => 'user/Users/index',
                'lang'  => $this->home_lang,
            ])->getField('title');

        $eyou = array(
            'field' => $result,
        );
        $this->assign('eyou', $eyou);

        return $this->fetch('users_centre');
    }

    // 登陸
    public function login()
    {
        if ($this->users_id > 0) {
            $this->redirect('user/Users/centre');
            exit;
        }

        $is_vertify = 1; // 預設開啟驗證碼
        $users_login_captcha = config('captcha.users_login');
        if (!function_exists('imagettftext') || empty($users_login_captcha['is_on'])) {
            $is_vertify = 0; // 函式不存在，不符合開啟的條件
        }
        $this->assign('is_vertify', $is_vertify);

        if (IS_AJAX_POST) {
            $post = input('post.');
            $post['username'] = trim($post['username']);

            if (empty($post['username'])) {
                $this->error('使用者名稱不能為空！', null, ['status'=>1]);
            } else if(!preg_match("/^[\x{4e00}-\x{9fa5}\w\-\_\@\#]{2,30}$/u", $post['username'])){
                $this->error('使用者名稱不正確！', null, ['status'=>1]);
            }

            if (empty($post['password'])) {
                $this->error('密碼不能為空！', null, ['status'=>1]);
            }

            if (1 == $is_vertify) {
                if (empty($post['vertify'])) {
                    $this->error('圖片驗證碼不能為空！', null, ['status'=>1]);
                }
            }

            $users = $this->users_db->where([
                    'username'  => $post['username'],
                    'is_del'    => 0,
                    'lang'      => $this->home_lang,
                ])->find();
            if (!empty($users)) {
                if (empty($users['is_activation'])) {
                    $this->error('該使用者尚未啟用，請聯繫管理員！', null, ['status'=>1]);
                }

                $users_id = $users['users_id'];
                if (strval($users['password']) === strval(func_encrypt($post['password']))) {

                    // 處理判斷驗證碼
                    if (1 == $is_vertify) {
                        $verify = new Verify();
                        if (!$verify->check($post['vertify'], "users_login")) {
                            $this->error('驗證碼錯誤', null, ['status'=>'vertify']);
                        }
                    }

                    // 判斷是前臺還是後臺註冊的使用者，後臺註冊不受註冊驗證影響，1為後臺註冊，2為前臺註冊。
                    if (2 == $users['register_place']) {
                        $usersVerificationRow = M('users_config')->where([
                                'name'  => 'users_verification',
                                'lang'  => $this->home_lang,
                            ])->find();
                        if ($usersVerificationRow['update_time'] <= $users['reg_time']) {
                            // 判斷是否需要後臺審覈
                            if ($usersVerificationRow['value'] == 1 && $users['is_activation'] == 0) {
                                $this->error('管理員審覈中，請稍等！', null, ['status'=>2]);
                            }
                        }
                    }

                    // 使用者users_id存入session
                    session('users_id',$users_id);
                    session('users',$users);
                    setcookie('users_id',$users_id,null);

                    $data = [
                        'last_ip'       => clientIP(),
                        'last_login'    => getTime(),
                        'login_count'   => Db::raw('login_count+1'),
                    ];
                    $this->users_db->where('users_id',$users_id)->update($data);

                    $url =  input('post.referurl/s', null, 'htmlspecialchars_decode,urldecode');
                    $this->success('登錄成功', $url);
                }else{
                    $this->error('使用者名稱或密碼不正確！', null, ['status'=>1]);
                }
            }else{
                $this->error('該使用者名稱不存在，請註冊！', null, ['status'=>1]);
            }
        }

        $referurl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : url("user/Users/centre");
        $this->assign('referurl', $referurl);

        return $this->fetch('users_login');
    }

    // 使用者註冊
    public function reg()
    {
        if ($this->users_id > 0) {
            $this->redirect('user/Users/centre');
            exit;
        }

        $is_vertify = 1; // 預設開啟驗證碼
        $users_reg_captcha = config('captcha.users_reg');
        if (!function_exists('imagettftext') || empty($users_reg_captcha['is_on'])) {
            $is_vertify = 0; // 函式不存在，不符合開啟的條件
        }
        $this->assign('is_vertify', $is_vertify);

        if (IS_AJAX_POST) {
            $post = input('post.');
            $post['username'] = trim($post['username']);

            if (empty($post['username'])) {
                $this->error('使用者名稱不能為空！', null, ['status'=>1]);
            } else if(!preg_match("/^[\x{4e00}-\x{9fa5}\w\-\_\@\#]{2,30}$/u", $post['username'])){
                $this->error('請輸入2-30位的漢字、英文、數字、下劃線等組合', null, ['status'=>1]);
            }

            if (empty($post['password'])) {
                $this->error('登錄密碼不能為空！', null, ['status'=>1]);
            }

            if (empty($post['password2'])) {
                $this->error('重複密碼不能為空！', null, ['status'=>1]);
            }

            if (1 == $is_vertify) {
                if (empty($post['vertify'])) {
                    $this->error('圖片驗證碼不能為空！', null, ['status'=>1]);
                }
            }

            $count = $this->users_db->where([
                    'username'  => $post['username'],
                    'lang'      => $this->home_lang,
                ])->count();
            if (!empty($count)) {
                $this->error('使用者名稱已存在！', null, ['status'=>1]);
            }

            if (empty($post['password']) && empty($post['password2'])) {
                $this->error('登錄密碼不能為空！', null, ['status'=>1]);
            } else {
                if ($post['password'] != $post['password2']) {
                    $this->error('兩次密碼輸入不一致！', null, ['status'=>1]);
                }
            }
            
            // 處理使用者屬性數據
            $ParaData = [];
            if (is_array($post['users_'])) {
                $ParaData = $post['users_'];
            }
            unset($post['users_']);

            // 處理提交的使用者屬性中必填項是否為空
            // 必須傳入提交的使用者屬性陣列
            $EmptyData = model('Users')->isEmpty($ParaData);
            if (!empty($EmptyData)) {
                $this->error($EmptyData, null, ['status'=>1]);
            }

            // 處理提交的使用者屬性中郵箱和手機是否已存在
            // IsRequired方法傳入的參數有2個
            // 第一個必須傳入提交的使用者屬性陣列
            // 第二個users_id，註冊時不需要傳入，修改時需要傳入。
            $RequiredData = model('Users')->isRequired($ParaData);
            if (!empty($RequiredData)) {
                if (!is_array($RequiredData)) {
                    $this->error($RequiredData, null, ['status'=>1]);
                }
            }

            // 處理判斷驗證碼
            if (1 == $is_vertify) {
                $verify = new Verify();
                if (!$verify->check($post['vertify'], "users_reg")) {
                    $this->error('圖片驗證碼錯誤', null, ['status'=>'vertify']);
                }
            }

            if (!empty($RequiredData)) {
                // 查詢使用者輸入的郵箱並且為找回密碼來源的所有驗證碼
                $RecordWhere = [
                    'source'   => 2,
                    'email'    => $RequiredData['email'],
                    'users_id' => 0,
                    'status'   => 0,
                    'lang'     => $this->home_lang,
                ];
                $RecordData = [
                    'status'      => 1,
                    'update_time' => getTime(),
                ];
                // 更新數據
                $this->smtp_record_db->where($RecordWhere)->update($RecordData);
            }

            // 使用者設定
            $users_verification = !empty($this->usersConfig['users_verification']) ? $this->usersConfig['users_verification'] : 0;

            // 處理判斷是否為後臺審覈，verification=1為後臺審覈。
            if (1 == $users_verification) {
                $data['is_activation'] = 0;
            }

            // 新增使用者到使用者表
            $data['username']       = $post['username'];
            $data['password']       = func_encrypt($post['password']);
            $data['last_ip']        = clientIP();
            $data['reg_time']       = getTime();
            $data['last_login']     = getTime();
            $data['register_place'] = 2;  // 註冊位置，後臺註冊不受註冊驗證影響，1為後臺註冊，2為前臺註冊。
            $data['lang'] = $this->home_lang;
            
            $level_id = $this->users_level_db->where([
                    'is_system' => 1,
                    'lang'  => $this->home_lang,
                ])->getField('level_id');
            $data['level']  = $level_id;

            $users_id = $this->users_db->add($data);

            // 判斷使用者是否新增成功
            if (!empty($users_id)) {
                // 批量新增使用者屬性到屬性資訊表
                if (!empty($ParaData)) {
                    $betchData = [];
                    $usersparaRow = $this->users_parameter_db->where([
                            'lang'  => $this->home_lang,
                            'is_hidden' => 0,
                        ])->getAllWithIndex('name');
                    foreach ($ParaData as $key => $value) {
                        if(preg_match('/_code$/i', $key)){
                            continue;
                        }

                        // 若為陣列，則拆分成字串
                        if (is_array($value)) {
                            $value = implode(',', $value);
                        }

                        $para_id = intval($usersparaRow[$key]['para_id']);
                        $betchData[] = [
                            'users_id'  => $users_id,
                            'para_id'   => $para_id,
                            'info'      => $value,
                            'lang'      => $this->home_lang,
                            'add_time'  => getTime(),
                        ];
                    }
                    $this->users_list_db->insertAll($betchData);
                }

                // 查詢屬性表的手機號碼和郵箱地址,拼裝陣列$UsersListData
                $UsersListData = model('Users')->getUsersListData('*',$users_id);
                $UsersListData['login_count'] = 1;
                $UsersListData['update_time'] = getTime();
                if (2 == $users_verification) {
                    // 若開啟郵箱驗證並且通過郵箱驗證則繫結到使用者
                    $UsersListData['is_email'] = 1;
                }
                // 同步修改使用者資訊
                $this->users_db->where('users_id',$users_id)->update($UsersListData);

                session('users_id',$users_id);
                if (session('users_id')) {
                    $users = M('users')->where("users_id",$users_id)->find();
                    if (empty($users_verification)) {
                        // 無需審覈，直接登陸
                        $url = url('user/Users/centre');
                        $this->success('註冊成功！', $url, ['status'=>3]);
                    }else if (1 == $users_verification) {
                        // 需要後臺審覈
                        session('users_id',null);
                        $url = url('user/Users/login');
                        $this->success('註冊成功，等管理員啟用才能登錄！', $url, ['status'=>2]);
                    }else if (2 == $users_verification) {
                        // 註冊成功
                        $url = url('user/Users/centre');
                        $this->success('註冊成功，郵箱繫結成功，跳轉至會員中心！', $url, ['status'=>0]);
                    }
                }else{
                    $url = url('user/Users/login');
                    $this->success('註冊成功，請登錄！', $url, ['status'=>2]);
                }
            }
            $this->error('註冊失敗', null, ['status'=>4]);
        }

        // 使用者屬性資料資訊
        $users_para = model('Users')->getDataPara();
        $this->assign('users_para',$users_para);

        return $this->fetch('users_reg');
    }

    // 會員中心
    public function centre()
    {
        $result = Db::name('users_menu')->where(['is_userpage'=>1,'lang'=>$this->home_lang])->find();
        $mca = !empty($result['mca']) ? $result['mca'] : 'user/Users/index';
        $this->redirect($mca);
    }

    // 修改資料
    public function centre_update()
    {
        if (IS_AJAX_POST) {
            $post = input('post.');
            $ParaData = [];
            if (is_array($post['users_'])) {
                $ParaData = $post['users_'];
            }
            unset($post['users_']);

            // 處理提交的使用者屬性中必填項是否為空
            // 必須傳入提交的使用者屬性陣列
            $EmptyData = model('Users')->isEmpty($ParaData);
            if ($EmptyData) {
                $this->error($EmptyData);
            }

            // 處理提交的使用者屬性中郵箱和手機是否已存在
            // IsRequired方法傳入的參數有2個
            // 第一個必須傳入提交的使用者屬性陣列
            // 第二個users_id，註冊時不需要傳入，修改時需要傳入。
            $RequiredData = model('Users')->isRequired($ParaData,$this->users_id);
            if ($RequiredData) {
                $this->error($RequiredData);
            }

            /*處理屬性表的數據修改新增*/
            $row2 = $this->users_parameter_db->field('para_id,name')->getAllWithIndex('name');
            if(!empty($row2)){
                foreach ($ParaData as $key => $value) {
                    if (!isset($row2[$key])) {
                        continue;
                    }

                    // 若為陣列，則拆分成字串
                    if (is_array($value)) {
                        $value = implode(',', $value);
                    }

                    $data = [];
                    $para_id = intval($row2[$key]['para_id']);
                    $where = [
                        'users_id'  => $this->users_id,
                        'para_id'  => $para_id,
                        'lang'  => $this->home_lang,
                    ];
                    $data['info']       = $value;
                    $data['update_time'] = getTime();

                    // 若資訊表中無數據則新增
                    $row = $this->users_list_db->where($where)->count();
                    if (empty($row)) {
                        $data['users_id'] = $this->users_id;
                        $data['para_id']  = $para_id;
                        $data['lang']     = $this->home_lang;
                        $data['add_time'] = getTime();
                        $this->users_list_db->add($data);
                    } else {
                        $this->users_list_db->where($where)->update($data);
                    }
                }
            }
            
            // 查詢屬性表的手機和郵箱資訊，同步修改使用者資訊
            $usersData = model('Users')->getUsersListData('*',$this->users_id);
            $usersData['update_time'] = getTime();
            $return = $this->users_db->where('users_id',$this->users_id)->update($usersData);
            if ($return) {
                $this->success('操作成功');
            }
            $this->error('操作失敗');
        }
        $this->error('訪問錯誤！');
    }

    // 更改密碼
    public function change_pwd()
    {
        if (IS_AJAX_POST) {
            $post = input('post.');
            if (empty($post['oldpassword'])) {
                $this->error('原密碼不能為空！');
            } else if (empty($post['password'])) {
                $this->error('新密碼不能為空！');
            } else if ($post['password'] != $post['password2']) {
                $this->error('重複密碼與新密碼不一致！');
            }

            $users = $this->users_db->field('password')->where([
                    'users_id'  => $this->users_id,
                    'lang'      => $this->home_lang,
                ])->find();
            if (!empty($users)) {
                if (strval($users['password']) === strval(func_encrypt($post['oldpassword']))) {
                    $r = $this->users_db->where([
                            'users_id'  => $this->users_id,
                            'lang'      => $this->home_lang,
                        ])->update([
                            'password'    => func_encrypt($post['password']),
                            'update_time' => getTime(),
                        ]);
                    if ($r) {
                        $this->success('修改成功');
                    }
                    $this->error('修改失敗');
                }else{
                    $this->error('原密碼錯誤，請重新輸入！');
                }
            }
            $this->error('登錄失效，請重新登錄！');
        }

        return $this->fetch('users_change_pwd');
    }

    // 找回密碼
    public function retrieve_password()
    {
        if ($this->users_id > 0) {
            $this->redirect('user/Users/centre');
            exit;
        }

        $is_vertify = 1; // 預設開啟驗證碼
        $users_retrieve_pwd_captcha = config('captcha.users_retrieve_password');
        if (!function_exists('imagettftext') || empty($users_retrieve_pwd_captcha['is_on'])) {
            $is_vertify = 0; // 函式不存在，不符合開啟的條件
        }
        $this->assign('is_vertify', $is_vertify);

        if (IS_AJAX_POST) {
            $post = input('post.');
            // POST數據基礎判斷
            if (empty($post['email'])) {
                $this->error('郵箱地址不能為空！');
            }
            if (1 == $is_vertify) {
                if (empty($post['vertify'])) {
                    $this->error('圖片驗證碼不能為空！');
                }
            }
            if (empty($post['email_code'])) {
                $this->error('郵箱驗證碼不能為空！');
            }

            // 判斷使用者輸入的郵箱是否存在
            $ListWhere = array(
                'info' => array('eq',$post['email']),
                'lang' => array('eq',$this->home_lang),
            );
            $ListData = $this->users_list_db->where($ListWhere)->field('users_id')->find();
            if (empty($ListData)) {
                $this->error('郵箱不存在，不能找回密碼！');
            }

            // 判斷使用者輸入的郵箱是否已繫結
            $UsersWhere = array(
                'email'    => array('eq',$post['email']),
                'lang'     => array('eq',$this->home_lang),
            );
            $UsersData = $this->users_db->where($UsersWhere)->field('is_email')->find();
            if (empty($UsersData['is_email'])) {
                $this->error('郵箱未繫結，不能找回密碼！');
            }

            // 查詢使用者輸入的郵箱驗證碼是否存在
            $RecordWhere = [
                'code'  => $post['email_code'],
                'lang'  => $this->home_lang,
            ];
            $RecordData = $this->smtp_record_db->where($RecordWhere)->field('status,add_time,email')->find();
            if (!empty($RecordData)) {
                // 郵箱驗證碼是否超時
                $time = getTime();
                $RecordData['add_time'] += Config::get('global.email_default_time_out');
                if ('1' == $RecordData['status'] || $RecordData['add_time'] <= $time) {
                    $this->error('郵箱驗證碼已被使用或超時，請重新發送！');
                }else{
                    // 圖形驗證碼判斷
                    if (1 == $is_vertify) {
                        $verify = new Verify();
                        if (!$verify->check($post['vertify'], "users_retrieve_password")) {
                            $this->error('圖形驗證碼錯誤，請重新輸入！');
                        }
                    }

                    session('users_retrieve_password_email', $post['email']); // 標識郵箱驗證通過
                    $em  = rand(10,99).base64_encode($post['email']).'/=';
                    $url = url('user/Users/reset_password',['em' => base64_encode($em)]);
                    $this->success('操作成功', $url);
                }

            }else{
                $this->error('郵箱驗證碼不正確，請重新輸入！');
            }
        }

        session('users_retrieve_password_email', null); // 標識郵箱驗證通過

        /*檢測使用者郵箱屬性是否開啟*/
        $usersparamRow = $this->users_parameter_db->where([
                'name'  => ['LIKE', 'email_%'],
                'is_hidden' => 1,
                'lang'  => $this->home_lang,
            ])->find();
        if (!empty($usersparamRow)) {
            $this->error('使用者郵箱屬性已關閉，請聯繫網站管理員 ！');
        }
        /*--end*/

        return $this->fetch();
    }

    // 重置密碼
    public function reset_password()
    {
        if (IS_AJAX_POST) {
            $post = input('post.');
            if (empty($post['password'])) {
                $this->error('新密碼不能為空！');
            }
            if ($post['password'] != $post['password_']) {
                $this->error('兩次密碼輸入不一致！');
            }

            $email = session('users_retrieve_password_email');
            if (!empty($email)) {
                $data = [
                    'password'  => func_encrypt($post['password']),
                    'update_time'   => getTime(),
                ];
                $return  = $this->users_db->where([
                        'email' => $email,
                        'lang'  => $this->home_lang,
                    ])->update($data);
                if ($return) {
                    session('users_retrieve_password_email', null); // 標識郵箱驗證通過
                    $url = url('user/Users/login');
                    $this->success('重置成功！', $url);
                }
            }
            $this->error('重置失敗！');
        }

        // 沒有傳入郵箱，重定向至找回密碼頁面
        $em = input('param.em/s');
        $em = base64_decode(input('param.em/s'));
        $em = base64_decode(msubstr($em, 2, -2));
        $email = session('users_retrieve_password_email');
        if(empty($email) || !check_email($em) || $em != $email){
            $this->redirect('user/Users/retrieve_password');
            exit;
        }
        $users  = $this->users_db->where([
                'email' => $email,
                'lang'  => $this->home_lang,
            ])->find();

        if (!empty($users)) {
            // 查詢使用者輸入的郵箱並且為找回密碼來源的所有驗證碼
            $RecordWhere = [
                'source'   => 4,
                'email'    => $email,
                'users_id' => 0,
                'status'   => 0,
                'lang'     => $this->home_lang,
            ];
            // 更新數據
            $RecordData = [
                'status'      => 1,
                'update_time' => getTime(),
            ];
            $this->smtp_record_db->where($RecordWhere)->update($RecordData);
        }
        $this->assign('users', $users);
        return $this->fetch();
    }

    public function edit_users_head_pic(){
        if (IS_AJAX_POST) {
            $filename = input('param.filename/s', '');
            if (!empty($filename) && !is_http_url($filename)) {
                $head_pic_url = $filename;
                if (!empty($head_pic_url)) {
                    $usersData['head_pic']    = $head_pic_url;
                    $usersData['update_time'] = getTime();
                    $return = $this->users_db->where([
                            'users_id'  => $this->users_id,
                            'lang'      => $this->home_lang,
                        ])->update($usersData);
                }
                if ($return) {
                    $this->success('操作成功！');
                } else {
                    $this->error('操作失敗！');
                }
            }else{
                $this->error('上傳本地圖片錯誤！');
            }
        }
    }

    public function bind_email()
    {
        if (IS_AJAX_POST) {
            $post = input('post.');
            if (!empty($post['email']) && !empty($post['email_code'])) {
                // 郵箱格式驗證是否正確
                if (!check_email($post['email'])) {
                    $this->error('郵箱格式不正確！');
                }

                // 是否已存在相同郵箱地址
                $ListWhere = [
                    'users_id' => ['NEQ',$this->users_id],
                    'info'     => $post['email'],
                    'lang'     => $this->home_lang,
                ];
                $ListData = $this->users_list_db->where($ListWhere)->count();
                if (!empty($ListData)) {
                    $this->error('該郵箱已存在，不可繫結！');
                }

                // 判斷驗證碼是否存在並且是否可用
                $RecordWhere = [
                    'email'     => $post['email'],
                    'code'      => $post['email_code'],
                    'users_id'  => $this->users_id,
                    'lang'      => $this->home_lang,
                ];
                $RecordData = $this->smtp_record_db->where($RecordWhere)->field('record_id,email,status,add_time')->find();
                if (!empty($RecordData)) {
                    // 驗證碼存在
                    $time   = getTime();
                    $RecordData['add_time'] += Config::get('global.email_default_time_out');
                    if (1 == $RecordData['status'] || $RecordData['add_time'] <= $time) {
                        // 驗證碼不可用
                        $this->error('郵箱驗證碼已被使用或超時，請重新發送！');
                    }else{
                        // 查詢使用者輸入的郵箱並且為繫結郵箱來源的所有驗證碼
                        $RecordWhere = [
                            'source'   => 3,
                            'email'    => $RecordData['email'],
                            'users_id' => $this->users_id,
                            'status'   => 0,
                            'lang'     => $this->home_lang,
                        ];

                        // 更新數據
                        $RecordData = [
                            'status'      => 1,
                            'update_time' => $time,
                        ];
                        $this->smtp_record_db->where($RecordWhere)->update($RecordData);

                        // 匹配查詢郵箱
                        $ParaWhere = [
                            'name'     => ['LIKE',"email_%"],
                            'is_system'=> 1,
                            'lang'     => $this->home_lang,
                        ];
                        $ParaData = $this->users_parameter_db->where($ParaWhere)->field('para_id')->find();

                        // 修改使用者屬性表資訊
                        $listCount = $this->users_list_db->where([
                                'para_id'  => $ParaData['para_id'],
                                'users_id' => ['EQ',$this->users_id],
                                'lang'     => $this->home_lang,
                            ])->count();
                        if (empty($listCount)) { // 後臺新增使用者，沒有使用者屬性記錄的情況
                            $ListData = [
                                'users_id' => $this->users_id,
                                'para_id'  => $ParaData['para_id'],
                                'info'     => $post['email'],
                                'lang'     => $this->home_lang,
                                'add_time' => $time,
                            ];
                            $IsList = $this->users_list_db->where($ListWhere)->add($ListData);
                        } else {
                            $ListWhere = [
                                'users_id' => $this->users_id,
                                'para_id'  => $ParaData['para_id'],
                                'lang'     => $this->home_lang,
                            ];
                            $ListData = [
                                'info'        => $post['email'],
                                'update_time' => $time,
                            ];
                            $IsList = $this->users_list_db->where($ListWhere)->update($ListData);
                        }

                        if (!empty($IsList)) {
                            // 同步修改使用者表郵箱地址，並繫結郵箱地址到使用者賬號
                            $UsersData = [
                                'users_id'    => $this->users_id,
                                'is_email'    => '1',
                                'email'       => $post['email'],
                                'update_time' => $time,
                            ];
                            $this->users_db->update($UsersData);

                            $this->success('操作成功！');
                        }else{
                            $this->error('網路錯誤，郵箱地址修改失敗，請重新獲取驗證碼！');
                        }
                    }
                }else{
                    $this->error('輸入的郵箱地址和郵箱驗證碼不一致，請重新輸入！');
                }
            }
        }
        $title = input('param.title/s');
        $this->assign('title',$title);
        return $this->fetch();
    }
    // 退出登陸
    public function logout()
    {
        session('users_id', null);
        setcookie('users_id','',getTime()-3600);
        $this->redirect(ROOT_DIR.'/');
    }
}