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

class View extends Base
{
    // 模型標識
    public $nid = '';
    // 模型ID
    public $channel = '';
    // 模型名稱
    public $modelName = '';

    public function _initialize() {
        parent::_initialize();
    }

    /**
     * 內容頁
     */
    public function index($aid = '')
    {
        if (!is_numeric($aid) || strval(intval($aid)) !== strval($aid)) {
            abort(404,'頁面不存在');
        }

        $seo_pseudo = config('ey_config.seo_pseudo');

        /*URL上參數的校驗*/
        if (3 == $seo_pseudo)
        {
            if (stristr($this->request->url(), '&c=View&a=index&')) {
                abort(404,'頁面不存在');
            }
        }
        else if (1 == $seo_pseudo)
        {
            $seo_dynamic_format = config('ey_config.seo_dynamic_format');
            if (2 == $seo_dynamic_format && stristr($this->request->url(), '&c=View&a=index&')) {
                abort(404,'頁面不存在');
            }
        }
        /*--end*/

        $aid = intval($aid);
        $archivesInfo = M('archives')->field('a.typeid, a.channel, b.nid, b.ctl_name')
            ->alias('a')
            ->join('__CHANNELTYPE__ b', 'a.channel = b.id', 'LEFT')
            ->where([
                'a.aid'     => $aid,
                'a.is_del'      => 0,
            ])
            ->find();
        if (empty($archivesInfo)) {
            abort(404,'頁面不存在');
            // $this->redirect('/public/static/errpage/404.html', 301);
        }
        $this->nid = $archivesInfo['nid'];
        $this->channel = $archivesInfo['channel'];
        $this->modelName = $archivesInfo['ctl_name'];

        $result = model($this->modelName)->getInfo($aid);
        if ($result['arcrank'] == -1) {
            $this->success('待審覈稿件，你沒有許可權閱讀！');
        }
        // 外部鏈接跳轉
        if ($result['is_jump'] == 1) {
            header('Location: '.$result['jumplinks']);
            exit;
        }
        /*--end*/

        $tid = $result['typeid'];
        $arctypeInfo = model('Arctype')->getInfo($tid);
        /*自定義欄位的數據格式處理*/
        $arctypeInfo = $this->fieldLogic->getTableFieldList($arctypeInfo, config('global.arctype_channel_id'));
        /*--end*/
        if (!empty($arctypeInfo)) {

            /*URL上參數的校驗*/
            if (3 == $seo_pseudo) {
                $dirname = input('param.dirname/s');
                $dirname2 = '';
                $seo_rewrite_format = config('ey_config.seo_rewrite_format');
                if (1 == $seo_rewrite_format) {
                    $toptypeRow = model('Arctype')->getAllPid($tid);
                    $toptypeinfo = current($toptypeRow);
                    $dirname2 = $toptypeinfo['dirname'];
                } else if (2 == $seo_rewrite_format) {
                    $dirname2 = $arctypeInfo['dirname'];
                }
                if ($dirname != $dirname2) {
                    abort(404,'頁面不存在');
                }
            }
            /*--end*/

            // 是否有子欄目，用於標記【全部】選中狀態
            $arctypeInfo['has_children'] = model('Arctype')->hasChildren($tid);
            // 文件模板檔案，不指定文件模板，預設以欄目設定的為主
            empty($result['tempview']) && $result['tempview'] = $arctypeInfo['tempview'];
            
            /*給沒有type字首的欄位新增一個帶字首的欄位，並賦予相同的值*/
            foreach ($arctypeInfo as $key => $val) {
                if (!preg_match('/^type/i',$key)) {
                    $arctypeInfo['type'.$key] = $val;
                }
            }
            /*--end*/
        } else {
            abort(404,'頁面不存在');
        }
        $result = array_merge($arctypeInfo, $result);

        // 文件鏈接
        $result['arcurl'] = '';
        if ($result['is_jump'] != 1) {
            $result['arcurl'] = arcurl('home/View/index', $result, true, true);
        }
        /*--end*/
        
        /*獲取目前頁面URL*/
        $result['pageurl'] = request()->url(true);
        /*--end*/

        // seo
        $result['seo_title'] = set_arcseotitle($result['title'], $result['seo_title'], $result['typename']);
        $result['seo_description'] = @msubstr(checkStrHtml($result['seo_description']), 0, config('global.arc_seo_description_length'), false);

        /*支援子目錄*/
        $result['litpic'] = handle_subdir_pic($result['litpic']);
        /*--end*/

        $result = view_logic($aid, $this->channel, $result); // 模型對應邏輯

        /*自定義欄位的數據格式處理*/
        $result = $this->fieldLogic->getChannelFieldList($result, $this->channel);
        /*--end*/

        $eyou = array(
            'type'  => $arctypeInfo,
            'field' => $result,
        );
        $this->eyou = array_merge($this->eyou, $eyou);
        $this->assign('eyou', $this->eyou);

        /*模板檔案*/
        $viewfile = !empty($result['tempview'])
        ? str_replace('.'.$this->view_suffix, '',$result['tempview'])
        : 'view_'.$this->nid;
        /*--end*/

        /*多語言內建模板檔名*/
        if (!empty($this->home_lang)) {
            $viewfilepath = TEMPLATE_PATH.$this->theme_style.DS.$viewfile."_{$this->home_lang}.".$this->view_suffix;
            if (file_exists($viewfilepath)) {
                $viewfile .= "_{$this->home_lang}";
            }
        }
        /*--end*/

        return $this->fetch(":{$viewfile}");
    }

    /**
     * 下載檔案
     */
    public function downfile()
    {
        $file_id = I('param.id/d', 0);
        $uhash = I('param.uhash/s', '');

        if (empty($file_id) || empty($uhash)) {
            $this->error('下載地址出錯！');
            exit;
        }

        $map = array(
            'file_id'   => $file_id,
            'uhash' => $uhash,
        );
        $result = M('download_file')->where($map)->find();
        $filename = isset($result['file_url']) ? trim($result['file_url'], '/') : '';
        clearstatcache();
        if (empty($result) || !is_file(realpath($filename))) {
            $this->error('下載檔案不存在！');
            exit;
        }
        $file_url = is_http_url($result['file_url']) ? $result['file_url'] : realpath($filename);
        if (md5_file($file_url) != $result['md5file']) {
            $this->error('下載檔案包已損壞！');
            exit;
        }
        
        if (is_http_url($result['file_url'])) {
            header('Location: '. $downUrl);
            exit;
        } else {
            download_file($result['file_url'], $result['file_mime']);
            exit;
        }
    }
}