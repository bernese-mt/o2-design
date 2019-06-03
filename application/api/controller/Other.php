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

class Other extends Base
{
    /*
     * 初始化操作
     */
    
    public function _initialize() {
        parent::_initialize();
        session('user'); // 哪裡用到 session_id() , 哪個檔案就加上這行程式碼
    }

    /**
     * 廣告位js
     */
    public function other_show()
    {
        $pid = input('pid/d',1);
        $row = input('row/d',1);
        $where = array(
            'pid'=>$pid,
            'status'=>1,
            'start_time'=>array('lt', getTime()),
        );
        $ad = M("ad")->where($where)
            ->where('end_time', ['>', getTime()], ['=', 0], 'or')
            ->order("sort_order asc")
            ->limit($row)
            ->cache(true,EYOUCMS_CACHE_TIME, 'ad')
            ->select();
        $this->assign('ad',$ad);
        return $this->fetch();
    }
}