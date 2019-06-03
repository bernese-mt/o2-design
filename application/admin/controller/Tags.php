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

class Tags extends Base
{
    public function index()
    {
        $list = array();
        $keywords = input('keywords/s');

        $condition = array();
        if (!empty($keywords)) {
            $condition['tag'] = array('LIKE', "%{$keywords}%");
        }

        // 多語言
        $condition['lang'] = array('eq', $this->admin_lang);

        $tagsM =  M('tagindex');
        $count = $tagsM->where($condition)->count('id');// 查詢滿足要求的總記錄數
        $Page = $pager = new Page($count, config('paginate.list_rows'));// 實例化分頁類 傳入總記錄數和每頁顯示的記錄數
        $list = $tagsM->where($condition)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        $show = $Page->show();// 分頁顯示輸出
        $this->assign('page',$show);// 賦值分頁輸出
        $this->assign('list',$list);// 賦值數據集
        $this->assign('pager',$pager);// 賦值分頁對像
        return $this->fetch();
    }
    
    /**
     * 刪除
     */
    public function del()
    {
        if (IS_POST) {
            $id_arr = input('del_id/a');
            $id_arr = eyIntval($id_arr);
            if(!empty($id_arr)){
                $result = M('tagindex')->field('tag')
                    ->where([
                        'id'    => ['IN', $id_arr],
                        'lang'  => $this->admin_lang,
                    ])->select();
                $title_list = get_arr_column($result, 'tag');

                $r = M('tagindex')->where([
                        'id'    => ['IN', $id_arr],
                        'lang'  => $this->admin_lang,
                    ])->delete();
                if($r){
                    M('taglist')->where([
                        'tid'    => ['IN', $id_arr],
                        'lang'  => $this->admin_lang,
                    ])->delete();
                    adminLog('刪除Tags標籤：'.implode(',', $title_list));
                    $this->success('刪除成功');
                }else{
                    $this->error('刪除失敗');
                }
            } else {
                $this->error('參數有誤');
            }
        }
        $this->error('非法訪問');
    }
    
    /**
     * 清空
     */
    public function clearall()
    {
        $r = M('tagindex')->where([
                'lang'  => $this->admin_lang,
            ])->delete();
        if(false !== $r){
            M('taglist')->where([
                'lang'  => $this->admin_lang,
            ])->delete();
            adminLog('清空Tags標籤');
            $this->success('操作成功');
        }else{
            $this->error('操作失敗');
        }
    }
}