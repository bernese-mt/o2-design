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

class Download extends Base
{
    // 模型標識
    public $nid = 'download';
    // 模型ID
    public $channeltype = '';
    
    public function _initialize() {
        parent::_initialize();
        $channeltype_list = config('global.channeltype_list');
        $this->channeltype = $channeltype_list[$this->nid];
    }

    public function lists($tid)
    {
        $tid_tmp = $tid;
        $seo_pseudo = config('ey_config.seo_pseudo');
    	if (empty($tid)) {
            $map = array(
                'channeltype'   => $this->channeltype,
                'parent_id' => 0,
                'is_hidden' => 0,
                'status'    => 1,
            );
    	} else {
            if (3 == $seo_pseudo) {
                $map = array('dirname'=>$tid);
            } else {
                if (!is_numeric($tid) || strval(intval($tid)) !== strval($tid)) {
                    abort(404,'頁面不存在');
                }
                $map = array('id'=>$tid);
            }
        }
        $map['lang'] = $this->home_lang; // 多語言
        $row = M('arctype')->field('id,dirname')->where($map)->order('sort_order asc')->limit(1)->find();
        $tid = !empty($row['id']) ? intval($row['id']) : 0;
        $dirname = !empty($row['dirname']) ? $row['dirname'] : '';
        
        /*301重定向到新的偽靜態格式*/
        $this->jumpRewriteFormat($tid, $dirname, 'lists');
        /*--end*/

        if (3 == $seo_pseudo) {
            $tid = $dirname;
        } else {
            $tid = $tid_tmp;
        }

        return action('home/Lists/index', $tid);
    }

    public function view($aid)
    {
        $param = I('param.');
        $aid = !empty($param['aid']) ? intval($param['aid']) : '';
        if (empty($aid)) {
            abort(404,'頁面不存在');
        }
        $result = model('Download')->getInfo($aid);
        if (empty($result)) {
            abort(404,'頁面不存在');
        } elseif ($result['arcrank'] == -1) {
            $this->success('待審覈稿件，你沒有許可權閱讀！');
            exit;
        }
        // 外部鏈接跳轉
        if ($result['is_jump'] == 1) {
            header('Location: '.$result['jumplinks']);
            exit;
        }
        /*--end*/

        $tid = $result['typeid'];
        $arctypeInfo = model('Arctype')->getInfo($tid);
        /*301重定向到新的偽靜態格式*/
        $this->jumpRewriteFormat($aid, $arctypeInfo['dirname'], 'view');
        /*--end*/

        return action('home/View/index', $aid);
    }
}