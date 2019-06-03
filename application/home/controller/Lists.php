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

namespace app\home\controller;

use think\Db;

class Lists extends Base
{
    // 模型標識
    public $nid = '';
    // 模型ID
    public $channel = '';

    public function _initialize() {
        parent::_initialize();
    }

    /**
     * 欄目列表
     */
    public function index($tid = '')
    {
        /*獲取目前欄目ID以及模型ID*/
        $page_tmp = input('param.page/s', 0);
        if (empty($tid) || !is_numeric($page_tmp)) {
            abort(404,'頁面不存在');
        }

        $map = [];
        /*URL上參數的校驗*/
        $seo_pseudo = config('ey_config.seo_pseudo');
        if (3 == $seo_pseudo)
        {
            if (stristr($this->request->url(), '&c=Lists&a=index&')) {
                abort(404,'頁面不存在');
            }
            $map = array('a.dirname'=>$tid);
        }
        else if (1 == $seo_pseudo)
        {
            $seo_dynamic_format = config('ey_config.seo_dynamic_format');
            if (2 == $seo_dynamic_format && stristr($this->request->url(), '&c=Lists&a=index&')) {
                abort(404,'頁面不存在');
            } else if (!is_numeric($tid) || strval(intval($tid)) !== strval($tid)) {
                abort(404,'頁面不存在');
            }
            $map = array('a.id'=>$tid);
        }
        /*--end*/
        $map['a.is_del'] = 0; // 回收站功能
        $map['a.lang'] = $this->home_lang; // 多語言
        $row = M('arctype')->field('a.id, a.current_channel, b.nid')
            ->alias('a')
            ->join('__CHANNELTYPE__ b', 'a.current_channel = b.id', 'LEFT')
            ->where($map)
            ->find();
        if (empty($row)) {
            abort(404,'頁面不存在');
        }
        $tid = $row['id'];
        $this->nid = $row['nid'];
        $this->channel = intval($row['current_channel']);
        /*--end*/

        $result = $this->logic($tid); // 模型對應邏輯

        $eyou = array(
            'field' => $result,
        );
        $this->eyou = array_merge($this->eyou, $eyou);
        $this->assign('eyou', $this->eyou);

        /*模板檔案*/
        $viewfile = !empty($result['templist'])
        ? str_replace('.'.$this->view_suffix, '',$result['templist'])
        : 'lists_'.$this->nid;
        /*--end*/

        /*多語言內建模板檔名*/
        if (!empty($this->home_lang)) {
            $viewfilepath = TEMPLATE_PATH.$this->theme_style.DS.$viewfile."_{$this->home_lang}.".$this->view_suffix;
            if (file_exists($viewfilepath)) {
                $viewfile .= "_{$this->home_lang}";
            }
        }
        /*--end*/

        // /*模板檔案*/
        // $viewfile = $filename = !empty($result['templist'])
        // ? str_replace('.'.$this->view_suffix, '',$result['templist'])
        // : 'lists_'.$this->nid;
        // /*--end*/

        // /*每個欄目內建模板檔名*/
        // $viewfilepath = TEMPLATE_PATH.$this->theme_style.DS.$filename."_{$result['id']}.".$this->view_suffix;
        // if (file_exists($viewfilepath)) {
        //     $viewfile = $filename."_{$result['id']}";
        // }
        // /*--end*/

        // /*多語言內建模板檔名*/
        // if (!empty($this->home_lang)) {
        //     $viewfilepath = TEMPLATE_PATH.$this->theme_style.DS.$filename."_{$this->home_lang}.".$this->view_suffix;
        //     if (file_exists($viewfilepath)) {
        //         $viewfile = $filename."_{$this->home_lang}";
        //     }
        //     /*每個欄目內建模板檔名*/
        //     $viewfilepath = TEMPLATE_PATH.$this->theme_style.DS.$filename."_{$result['id']}_{$this->home_lang}.".$this->view_suffix;
        //     if (file_exists($viewfilepath)) {
        //         $viewfile = $filename."_{$result['id']}_{$this->home_lang}";
        //     }
        //     /*--end*/
        // }
        // /*--end*/

        return $this->fetch(":{$viewfile}");
    }

    /**
     * 模型對應邏輯
     * @param intval $tid 欄目ID
     * @return array
     */
    private function logic($tid = '')
    {
        $result = array();

        if (empty($tid)) {
            return $result;
        }

        switch ($this->channel) {
            case '6': // 單頁模型
            {
                $arctype_info = model('Arctype')->getInfo($tid);
                if ($arctype_info) {
                    // 讀取目前欄目的內容，否則讀取每一級第一個子欄目的內容，直到有內容或者最後一級欄目為止。
                    $result = $this->readContentFirst($tid);
                    // 閱讀許可權
                    if ($result['arcrank'] == -1) {
                        $this->success('待審覈稿件，你沒有許可權閱讀！');
                        exit;
                    }
                    // 外部鏈接跳轉
                    if ($result['is_part'] == 1) {
                        header('Location: '.$result['typelink']);
                        exit;
                    }
                    /*自定義欄位的數據格式處理*/
                    $result = $this->fieldLogic->getChannelFieldList($result, $this->channel);
                    /*--end*/
                    $result = array_merge($arctype_info, $result);
                }
                break;
            }

            default:
            {
                $result = model('Arctype')->getInfo($tid);
                break;
            }
        }

        if (!empty($result)) {
            /*自定義欄位的數據格式處理*/
            $result = $this->fieldLogic->getTableFieldList($result, config('global.arctype_channel_id'));
            /*--end*/
        }

        /*是否有子欄目，用於標記【全部】選中狀態*/
        $result['has_children'] = model('Arctype')->hasChildren($tid);
        /*--end*/

        // seo
        $result['seo_title'] = set_typeseotitle($result['typename'], $result['seo_title']);

        /*獲取目前頁面URL*/
        $result['pageurl'] = request()->url(true);
        /*--end*/

        /*給沒有type字首的欄位新增一個帶字首的欄位，並賦予相同的值*/
        foreach ($result as $key => $val) {
            if (!preg_match('/^type/i',$key)) {
                $result['type'.$key] = $val;
            }
        }
        /*--end*/

        return $result;
    }

    /**
     * 讀取指定欄目ID下有內容的欄目資訊，只讀取每一級的第一個欄目
     * @param intval $typeid 欄目ID
     * @return array
     */
    private function readContentFirst($typeid)
    {
        $result = false;
        while (true)
        {
            $result = model('Single')->getInfoByTypeid($typeid);
            if (empty($result['content']) && 'lists_single.htm' == strtolower($result['templist'])) {
                $map = array(
                    'parent_id' => $result['typeid'],
                    'current_channel' => 6,
                    'is_hidden' => 0,
                    'status'    => 1,
                );
                $row = M('arctype')->where($map)->field('*')->order('sort_order asc')->find(); // 查詢下一級的單頁模型欄目
                if (empty($row)) { // 不存在並返回目前欄目資訊
                    break;
                } elseif (6 == $row['current_channel']) { // 存在且是單頁模型，則進行繼續往下查詢，直到有內容為止
                    $typeid = $row['id'];
                }
            } else {
                break;
            }
        }

        return $result;
    }

    /**
     * 留言提交 
     */
    public function gbook_submit()
    {
        $typeid = input('post.typeid/d');

        if (IS_POST && !empty($typeid)) {
            $post = input('post.');

            $token = '__token__';
            foreach ($post as $key => $val) {
                if (preg_match('/^__token__/i', $key)) {
                    $token = $key;
                    continue;
                }
            }
            $ip = clientIP();
            $map = array(
                'ip'    => $ip,
                'typeid'    => $typeid,
                'lang'      => $this->home_lang,
                'add_time'  => array('gt', getTime() - 60),
            );
            $count = M('guestbook')->where($map)->count('aid');
            if ($count > 0) {
                $this->error('同一個IP在60秒之內不能重複提交！');
            }

            $this->channel = Db::name('arctype')->where(['id'=>$typeid])->getField('current_channel');

            $newData = array(
                'typeid'    => $typeid,
                'channel'   => $this->channel,
                'ip'    => $ip,
                'lang'  => $this->home_lang,
                'add_time'  => getTime(),
                'update_time' => getTime(),
            );
            $data = array_merge($post, $newData);

            // 數據驗證
            $rule = [
                'typeid'    => 'require|token:'.$token,
            ];
            $message = [
                'typeid.require' => '表單缺少標籤屬性{$field.hidden}',
            ];
            $validate = new \think\Validate($rule, $message);
            if(!$validate->batch()->check($data))
            {
                $error = $validate->getError();
                $error_msg = array_values($error);
                $this->error($error_msg[0]);
            } else {
                $guestbookRow = [];
                /*處理是否重複表單數據的提交*/
                $formdata = $data;
                foreach ($formdata as $key => $val) {
                    if (in_array($key, ['typeid','lang']) || preg_match('/^attr_(\d+)$/i', $key)) {
                        continue;
                    }
                    unset($formdata[$key]);
                }
                $md5data = md5(serialize($formdata));
                $data['md5data'] = $md5data;
                $guestbookRow = M('guestbook')->field('aid')->where(['md5data'=>$md5data])->find();
                /*--end*/
                if (empty($guestbookRow)) { // 非重複表單的才能寫入數據庫
                    $aid = M('guestbook')->insertGetId($data);
                    if ($aid > 0) {
                        $this->saveGuestbookAttr($aid, $typeid);
                    }
                    /*外掛 - 郵箱發送*/
                    $data = [
                        'gbook_submit',
                        $typeid,
                        $aid,
                    ];
                    $dataStr = implode('|', $data);
                    /*--end*/
                } else {
                    // 存在重複數據的表單，將在後臺顯示在最前面
                    M('guestbook')->where('aid',$guestbookRow['aid'])->update([
                            'update_time'   => getTime(),
                        ]);
                }
                $this->success('操作成功！', null, $dataStr, 3);
            }  
        }

        $this->error('表單缺少標籤屬性{$field.hidden}');
    }

    /**
     *  給指定留言新增表單值到 guestbook_attr
     * @param int $aid  留言id
     * @param int $typeid  留言欄目id
     */
    private function saveGuestbookAttr($aid, $typeid)
    {  
        // post 提交的屬性  以 attr_id _ 和值的 組合爲鍵名    
        $post = input("post.");
        $attrArr = [];

        /*多語言*/
        if (is_language()) {
            foreach($post as $key => $val) {
                if (preg_match_all('/^attr_(\d+)$/i', $key, $matchs)) {
                    $attr_value = intval($matchs[1][0]);
                    $attrArr[$attr_value] = [
                        'attr_id'   => $attr_value,
                    ];
                }
            }
            $attrArr = model('LanguageAttr')->getBindValue($attrArr, 'guestbook_attribute'); // 多語言
        }
        /*--end*/

        foreach($post as $k => $v)
        {
            if(!strstr($k, 'attr_'))
                continue;                                 

            $attr_id = str_replace('attr_','',$k);

            /*多語言*/
            if (!empty($attrArr)) {
                $attr_id = $attrArr[$attr_id]['attr_id'];
            }
            /*--end*/

            //$v = str_replace('_', '', $v); // 替換特殊字元
            //$v = str_replace('@', '', $v); // 替換特殊字元
            $v = trim($v);
            $adddata = array(
                'aid'   => $aid,
                'attr_id'   => $attr_id,
                'attr_value'   => filter_line_return($v, '。'),
                'lang'  => $this->home_lang,
                'add_time'   => getTime(),
                'update_time'   => getTime(),
            );
            M('GuestbookAttr')->add($adddata);                       
        }
    }
}