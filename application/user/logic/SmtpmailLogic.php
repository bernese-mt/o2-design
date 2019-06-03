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

namespace app\user\logic;

use think\Model;
use think\Db;
use think\Request;
use think\Config;

/**
 * 郵箱邏輯定義
 * Class CatsLogic
 * @package user\Logic
 */
class SmtpmailLogic extends Model
{
    private $home_lang = 'cn';

    /**
     * 初始化操作
     */
    public function _initialize() {
        parent::_initialize();
        $this->home_lang = get_home_lang();
    }

    /**
     * 發送郵件
     */
    public function send_email($email = '', $title = '', $type = 'reg', $scene = 2)
    {
        if (empty($email)) {
            return ['code'=>0, 'msg'=>"郵箱地址參數不能為空！"];
        }

        // 查詢擴充套件是否開啟
        $openssl_funcs = get_extension_funcs('openssl');
        if (!$openssl_funcs) {
            return ['code'=>0, 'msg'=>"請聯繫空間商，開啟php的 <font color='red'>openssl</font> 擴充套件！"];
        }

        // 是否填寫郵件配置
        $smtp_config = tpCache('smtp');
        foreach ($smtp_config as $key => $val) {
            if (empty($val)) {
                return ['code'=>0, 'msg'=>"該功能待開放，網站管理員尚未完善郵件配置！"];
            }
        }

        // 郵件使用場景
        $send_email_scene = config('send_email_scene');
        $send_scene = $send_email_scene[$scene]['scene'];

        // 獲取郵件模板
        $emailtemp = M('smtp_tpl')->where([
                'send_scene'=> $send_scene,
                'lang'      => $this->home_lang,
            ])->find();

        // 是否啟用郵件模板
        if (empty($emailtemp) || empty($emailtemp['is_open'])) {
            return ['code'=>0, 'msg'=>"該功能待開放，網站管理員尚未啟用(<font color='red'>{$emailtemp['tpl_name']}</font>)郵件模板"];
        }

        $users_id = session('users_id');

        if ('retrieve_password' == $type) 
        {
            //找回密碼
            // 判斷郵箱是否存在
            $where = array(
                'info'     => array('eq',$email),
                'lang'     => array('eq',$this->home_lang),
            );
            $users_list = M('users_list')->where($where)->field('info')->find();

            // 判斷會員是否已繫結郵箱
            $userswhere = array(
                'email'    => array('eq',$email),
                'lang'     => array('eq',$this->home_lang),
            );
            $usersdata = M('users')->where($userswhere)->field('is_email,is_activation')->find();
            if (!empty($usersdata)) {
                if (empty($usersdata['is_activation'])) {
                    return ['code'=>0, 'msg'=>'該會員尚未啟用，不能找回密碼！'];
                } else if (empty($usersdata['is_email'])) {
                    return ['code'=>0, 'msg'=>'郵箱地址未繫結，不能找回密碼！'];
                }
            }

            if (!empty($users_list)) {
                // // 判斷是否已發送過驗證鏈接，鏈接一小時內有效
                // $where_ = array(
                //     'email'     => array('eq',$email),
                //     'status'    => array('eq',0),
                //     'lang'      => $this->home_lang,
                // );
                // $isrecord = M('smtp_record')
                //         ->where($where_)
                //         ->field('record_id,add_time')
                //         ->order('add_time desc')
                //         ->find();
                $time = getTime();

                // // 郵箱驗證碼有效期
                // if (!empty($isrecord) && ($time - $isrecord['add_time']) < Config::get('global.email_default_time_out')) {
                //     return ['code'=>1, 'msg'=>'驗證碼已發送至郵箱：'.$email.'，請登錄郵箱檢視驗證碼！'];
                // }

                // 數據新增
                $datas['source']    = 4; // 來源，與場景ID對應：0=預設，2=註冊，3=繫結郵箱，4=找回密碼
                $datas['email']     = $email;
                $datas['code']      = rand(100000,999999);
                $datas['lang']      = $this->home_lang;
                $datas['add_time']  = $time;
                M('smtp_record')->add($datas);
            }else{
                return ['code'=>0, 'msg'=>'郵箱地址不存在！'];
            }
        }
        else if ('bind_email' == $type) 
        {
            // 郵箱繫結
            // 判斷郵箱是否已存在
            $listwhere = array(
                'info'     => array('eq',$email),
                'users_id' => array('neq',$users_id),
                'lang'     => array('eq',$this->home_lang),
            );
            $users_list = M('users_list')->where($listwhere)->field('info')->find();

            // 判斷會員是否已繫結相同郵箱
            $userswhere = array(
                'users_id' => array('eq',$users_id),
                'email'    => array('eq',$email),
                'is_email' => 1,
                'lang'     => array('eq',$this->home_lang),
            );

            $usersdata = M('users')->where($userswhere)->field('is_email')->find();
            if (!empty($usersdata['is_email'])) {
                return ['code'=>0, 'msg'=>'郵箱已繫結，無需重新繫結！'];
            }

            // 郵箱數據處理
            if (empty($users_list)) {
                // // 判斷是否已發送過驗證鏈接，鏈接一小時內有效
                // $where_ = array(
                //     'email'     => array('eq',$email),
                //     'users_id'  => array('eq',$users_id),
                //     'status'    => array('eq',0),
                //     'lang'      => $this->home_lang,
                // );
                // $isrecord = M('smtp_record')
                //         ->where($where_)
                //         ->field('record_id,add_time')
                //         ->order('add_time desc')
                //         ->find();
                $time = getTime();

                // // 郵箱驗證碼有效期
                // if (!empty($isrecord) && ($time - $isrecord['add_time']) < Config::get('global.email_default_time_out')) {
                //     return ['code'=>1, 'msg'=>'驗證碼已發送至郵箱：'.$email.'，請登錄郵箱檢視驗證碼！'];
                // }

                // 數據新增
                $datas['source']    = 3; // 來源，與場景ID對應：0=預設，2=註冊，3=繫結郵箱，4=找回密碼
                $datas['email']     = $email;
                $datas['users_id']  = $users_id;
                $datas['code']      = rand(100000,999999);
                $datas['lang']      = $this->home_lang;
                $datas['add_time']  = $time;
                M('smtp_record')->add($datas);
            }else{
                return ['code'=>0, 'msg'=>"郵箱已經存在，不可以繫結！"];
            }
        }
        else if ('reg' == $type) 
        {
            // 註冊
            // 判斷郵箱是否已存在
            $where = array(
                'info' => array('eq',$email),
                'lang' => array('eq',$this->home_lang),
            );
            $users_list = M('users_list')->where($where)->field('info')->find();

            if (empty($users_list)) {
                // // 判斷是否已發送過驗證鏈接，鏈接一小時內有效
                // $where_ = array(
                //     'email'     => array('eq',$email),
                //     'status'    => array('eq',0),
                //     'lang'      => $this->home_lang,
                // );
                // $isrecord = M('smtp_record')
                //         ->where($where_)
                //         ->field('record_id,add_time')
                //         ->order('add_time desc')
                //         ->find();
                $time = getTime();

                // // 郵箱驗證碼有效期
                // if (!empty($isrecord) && ($time - $isrecord['add_time']) < Config::get('global.email_default_time_out')) {
                //     return ['code'=>1, 'msg'=>'驗證碼已發送至郵箱：'.$email.'，請登錄郵箱檢視驗證碼！'];
                // }

                // 數據新增
                $datas['source']    = 2; // 來源，與場景ID對應：0=預設，2=註冊，3=繫結郵箱，4=找回密碼
                $datas['email']     = $email;
                $datas['code']      = rand(100000,999999);
                $datas['lang']      = $this->home_lang;
                $datas['add_time']  = $time;
                M('smtp_record')->add($datas);
            }else{
                return ['code'=>0, 'msg'=>'郵箱已存在！'];
            }
        }

        // 判斷標題拼接
        $web_name = $emailtemp['tpl_name'].'：'.$title.'-'.tpCache('web.web_name');

        $content = '感謝您的註冊,您的郵箱驗證碼為: '.$datas['code'];
        $html = "<p style='text-align: left;'>{$web_name}</p><p style='text-align: left;'>{$content}</p>";
        if (isMobile()) {
            $html .= "<p style='text-align: left;'>——來源：移動端</p>";
        } else {
            $html .= "<p style='text-align: left;'>——來源：電腦端</p>";
        }


        // 實例化類庫，呼叫發送郵件
        $res = send_email($email,$emailtemp['tpl_title'],$html, $send_scene);
        if (intval($res['code']) == 1) {
            return ['code'=>1, 'msg'=>$res['msg']];
        } else {
            return ['code'=>0, 'msg'=>$res['msg']];
        }
    }
}
