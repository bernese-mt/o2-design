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
 * Date: 2019-2-20
 */
namespace app\user\model;

use think\Model;
use think\Config;

/**
 * 會員
 */
class Users extends Model
{
    private $home_lang = 'cn';
    private $appid = '';
    private $mchid = '';
    private $key = '';

    //初始化
    protected function initialize()
    {
        // 需要呼叫`Model`的`initialize`方法
        parent::initialize();
        $this->home_lang = get_home_lang();
    }

    // 判斷會員屬性中必填項是否為空
    // 傳入參數：
    // $post_users ：會員屬性資訊陣列
    // return error：錯誤提示
    public function isEmpty($post_users = [])
    {
        $error = '';
        // 會員屬性
        $where = array(
            'lang'        => $this->home_lang,
            'is_hidden'   => 0, // 是否隱藏屬性，0為否
            'is_required' => 1, // 是否必填屬性，1為是
        );
        $para_data = M('users_parameter')->where($where)->field('title,name')->select();
        // 處理提交的屬性中必填項是否為空
        foreach ($para_data as $key => $value) {
            if (isset($post_users[$value['name']])) {
                if (is_array($post_users[$value['name']])) {
                    $post_users[$value['name']] = implode(',', $post_users[$value['name']]);
                }
                $attr_value = trim($post_users[$value['name']]);
                if (empty($attr_value)) {
                    return $value['title'].'不能為空！';;
                }
            }
        }
    }

    // 判斷郵箱和手機是否存在，並且判斷驗證碼是否驗證通過
    // 傳入參數：
    // $post_users:會員屬性資訊陣列
    // $users_id:會員ID，註冊時不需要傳入，修改時需要傳入。
    // return error
    public function isRequired($post_users = [],$users_id='')
    {
        if (empty($post_users)) {
            return false;
        }

        // 處理郵箱和手機是否存在
        $where_1 = [
            'name'     => ['LIKE', ["email_%","mobile_%"], 'OR'],
            'is_system'=> 1,
            'lang'     => $this->home_lang,
        ];
        $users_parameter = M('users_parameter')->where($where_1)->field('para_id,title,name')->getAllWithIndex('name');

        $email = '';
        $email_code = '';
        $mobile = '';
        $mobile_code = '';
        /*獲取郵箱和手機號碼*/
        foreach ($post_users as $key => $val) {
            if (preg_match('/^email_/i', $key)) {
                if (!preg_match('/_code$/i', $key)) {
                    $email = $val;
                    if (!empty($val) && !check_email($val)) {
                        return $users_parameter[$key]['title'].'格式不正確！';
                    }
                } else {
                    $email_code = $val;
                }
            } else if (preg_match('/^mobile_/i', $key)) {
                if (!preg_match('/_code$/i', $key)) {
                    $mobile = $val;
                    if (!empty($val) && !check_mobile($val)) {
                        return $users_parameter[$key]['title'].'格式不正確！';
                    }
                } else {
                    $mobile_code = $val;
                }
            }
        }
        /*--end*/

        $users_verification = getUsersConfigData('users.users_verification');
        if ('2' == $users_verification) {
            $time = getTime();
            /*處理郵箱驗證碼邏輯*/
            if (!empty($email)) {
                $where = [
                    'email' => $email,
                    'code'  => $email_code,
                    'lang'  => $this->home_lang,
                ];
                !empty($users_id) && $where['users_id'] = $users_id;
                $record = M('smtp_record')->where($where)->field('record_id,status,add_time')->find();
                if (!empty($record)) {
                    $record['add_time'] += Config::get('global.email_default_time_out');
                    if (1 == $record['status'] || $record['add_time'] <= $time) {
                        return '郵箱驗證碼已被使用或超時，請重新發送！';
                    }else{
                        // 返回后處理郵箱驗證碼失效操作
                        $data = [
                            'code_status' => 1,// 正確
                            'email'       => $email,
                        ];
                        return $data;
                    }
                }else{
                    if (!empty($users_id)) {
                        // 當會員修改郵箱地址，驗證碼為空或錯誤返回
                        $row = $this->getUsersListData('email',$users_id);
                        if ($email != $row['email']) {
                            return '郵箱驗證碼不正確，請重新輸入！';
                        }
                    }else{
                        // 當會員註冊時，驗證碼為空或錯誤返回
                        return '郵箱驗證碼不正確，請重新輸入！';
                    }
                }
            }
            /*--end*/
        }

        foreach ($users_parameter as $key => $value) {
            if (isset($post_users[$value['name']])) {
                $where_2 = [
                    'para_id'  => ['EQ', $value['para_id']],
                    'info'     => trim($post_users[$value['name']]),
                    'users_id' => ['NEQ', $users_id],
                    'lang'     => $this->home_lang,
                ];

                // 若users_id為空，則清除條件中的users_id條件
                if (empty($users_id)) { unset($where_2['users_id']); }

                $users_list = M('users_list')->where($where_2)->field('info')->find();
                if (!empty($users_list['info'])) {
                    return $value['title'].'已存在！';
                }
            }
        }
    }

    // 查詢會員屬性資訊表的郵箱和手機欄位
    // 必須傳入參數：
    // users_id 會員ID
    // field    查詢欄位，email僅郵箱，mobile僅手機號，*為兩項都查詢。
    // return   Data
    public function getUsersListData($field,$users_id)
    {   
        $Data = array();
        if ('email' == $field || '*' == $field) {
            // 查詢郵箱
            $parawhere = [
                'name'      => ['LIKE', "email_%"],
                'is_system' => 1,
                'lang'     => $this->home_lang,
            ];
            $paraData = M('users_parameter')->where($parawhere)->field('para_id')->find();
            $listwhere = [
                'para_id'   => $paraData['para_id'],
                'users_id'  => $users_id,
                'lang'     => $this->home_lang,
            ];
            $listData = M('users_list')->where($listwhere)->field('users_id,info')->find();
            $Data['email'] = $listData['info'];
        }

        if ('mobile' == $field || '*' == $field) {
            // 查詢手機號
            $parawhere_1 = [
                'name'      => ['LIKE', "mobile_%"],
                'is_system' => 1,
                'lang'     => $this->home_lang,
            ];
            $paraData_1 = M('users_parameter')->where($parawhere_1)->field('para_id')->find();
            $listwhere_1 = [
                'para_id'   => $paraData_1['para_id'],
                'users_id'  => $users_id,
                'lang'     => $this->home_lang,
            ];
            $listData_1 = M('users_list')->where($listwhere_1)->field('users_id,info')->find();
            $Data['mobile'] = $listData_1['info'];
        }

        return $Data;
    }

    /**
     * 查詢解析數據表的數據用以構造from表單
     * @param   return $list
     * @param   用於新增，不攜帶數據
     * @author  陳風任 by 2019-2-20
     */
    public function getDataPara()
    {
        // 欄位及內容數據處理
        $where = array(
            'lang'       => $this->home_lang,
            'is_hidden'  => 0,
        );

        $row = M('users_parameter')->field('*')
            ->where($where)
            ->order('sort_order asc,para_id asc')
            ->select();

        // 根據所需數據格式，拆分成一維陣列
        $addonRow = array();

        // 根據不同欄位型別封裝數據
        $list = $this->showViewFormData($row, 'users_', $addonRow);
        return $list;
    }

    /**
     * 查詢解析數據表的數據用以構造from表單
     * @param   return $list
     * @param   用於修改，攜帶數據
     * @author  陳風任 by 2019-2-20
     */
    public function getDataParaList($users_id = '')
    {
        // 欄位及內容數據處理
        $row = M('users_parameter')->field('a.*,b.info,b.users_id')
            ->alias('a')
            ->join('__USERS_LIST__ b', "a.para_id = b.para_id AND b.users_id = {$users_id}", 'LEFT')
            ->where([
                'a.lang'       => $this->home_lang,
                'a.is_hidden'  => 0,
            ])
            ->order('a.sort_order asc,a.para_id asc')
            ->select();
        // 根據所需數據格式，拆分成一維陣列
        $addonRow = [];
        foreach ($row as $key => $value) {
            $addonRow[$value['name']] = $value['info'];
        }
        // 根據不同欄位型別封裝數據
        $list = $this->showViewFormData($row, 'users_', $addonRow);
        return $list;
    }

    /**
     * 處理頁面顯示欄位的表單數據
     * @param array $list 欄位列表
     * @param array $formFieldStr 表單元素名稱的統一陣列字首
     * @param array $addonRow 欄位的數據
     * @author 陳風任 by 2019-2-20
     */
    public function showViewFormData($list, $formFieldStr, $addonRow = array())
    {
        if (!empty($list)) {
            foreach ($list as $key => $val) {
                $val['fieldArr'] = $formFieldStr;
                switch ($val['dtype']) {
                    case 'int':
                    {
                        if (isset($addonRow[$val['name']])) {
                            $val['dfvalue'] = $addonRow[$val['name']];
                        } else {
                            if(preg_match("#[^0-9]#", $val['dfvalue']))
                            {
                                $val['dfvalue'] = "";
                            }
                        }
                        break;
                    }

                    case 'float':
                    case 'decimal':
                    {
                        if (isset($addonRow[$val['name']])) {
                            $val['dfvalue'] = $addonRow[$val['name']];
                        } else {
                            if(preg_match("#[^0-9\.]#", $val['dfvalue']))
                            {
                                $val['dfvalue'] = "";
                            }
                        }
                        break;
                    }

                    case 'select':
                    {
                        $dfvalue = $val['dfvalue'];
                        $dfvalueArr = explode(',', $dfvalue);
                        $val['dfvalue'] = $dfvalueArr;
                        if (isset($addonRow[$val['name']])) {
                            $val['trueValue'] = explode(',', $addonRow[$val['name']]);
                        } else {
                            $dfTrueValue = !empty($dfvalueArr[0]) ? $dfvalueArr[0] : '';
                            $val['trueValue'] = array();
                        }
                        break;
                    }

                    case 'radio':
                    {
                        $dfvalue = $val['dfvalue'];
                        $dfvalueArr = explode(',', $dfvalue);
                        $val['dfvalue'] = $dfvalueArr;
                        if (isset($addonRow[$val['name']])) {
                            $val['trueValue'] = explode(',', $addonRow[$val['name']]);
                        } else {
                            $dfTrueValue = !empty($dfvalueArr[0]) ? $dfvalueArr[0] : '';
                            $val['trueValue'] = array($dfTrueValue);
                        }
                        break;
                    }

                    case 'checkbox':
                    {
                        $dfvalue = $val['dfvalue'];
                        $dfvalueArr = explode(',', $dfvalue);
                        $val['dfvalue'] = $dfvalueArr;
                        if (isset($addonRow[$val['name']])) {
                            $val['trueValue'] = explode(',', $addonRow[$val['name']]);
                        } else {
                            $val['trueValue'] = array();
                        }
                        break;
                    }

                    case 'img':
                    {
                        $val[$val['name'].'_eyou_is_remote'] = 0;
                        $val[$val['name'].'_eyou_remote'] = '';
                        $val[$val['name'].'_eyou_local'] = '';
                        if (isset($addonRow[$val['name']])) {
                            if (is_http_url($addonRow[$val['name']])) {
                                $val[$val['name'].'_eyou_is_remote'] = 1;
                                $val[$val['name'].'_eyou_remote'] = handle_subdir_pic($addonRow[$val['name']]);
                            } else {
                                $val[$val['name'].'_eyou_is_remote'] = 0;
                                $val[$val['name'].'_eyou_local'] = handle_subdir_pic($addonRow[$val['name']]);
                            }
                        }
                        break;
                    }

                    case 'imgs':
                    {
                        $val[$val['name'].'_eyou_imgupload_list'] = array();
                        if (isset($addonRow[$val['name']]) && !empty($addonRow[$val['name']])) {
                            $eyou_imgupload_list = explode(',', $addonRow[$val['name']]);
                            /*支援子目錄*/
                            foreach ($eyou_imgupload_list as $k1 => $v1) {
                                $eyou_imgupload_list[$k1] = handle_subdir_pic($v1);
                            }
                            /*--end*/
                            $val[$val['name'].'_eyou_imgupload_list'] = $eyou_imgupload_list;
                        }
                        break;
                    }

                    case 'datetime':
                    {
                        $val['dfvalue'] = !empty($addonRow[$val['name']]) ? date('Y-m-d H:i:s', $addonRow[$val['name']]) : date('Y-m-d H:i:s');
                        break;
                    }

                    case 'htmltext':
                    {
                        $val['dfvalue'] = isset($addonRow[$val['name']]) ? $addonRow[$val['name']] : $val['dfvalue'];
                        /*支援子目錄*/
                        $val['dfvalue'] = handle_subdir_pic($val['dfvalue'], 'html');
                        /*--end*/
                        break;
                    }
                    
                    default:
                    {
                        $val['dfvalue'] = isset($addonRow[$val['name']]) ? $addonRow[$val['name']] : $val['dfvalue'];
                        /*支援子目錄*/
                        if (is_string($val['dfvalue'])) {
                            $val['dfvalue'] = handle_subdir_pic($val['dfvalue'], 'html');
                            $val['dfvalue'] = handle_subdir_pic($val['dfvalue']);
                        }
                        /*--end*/
                        break;
                    }
                }
                $list[$key] = $val;
            }
        }
        return $list;
    }

}