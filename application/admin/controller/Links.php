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
use think\Cache;

class Links extends Base
{
    public function index()
    {
        $list = array();
        $keywords = input('keywords/s');

        $condition = array();
        if (!empty($keywords)) {
            $condition['title'] = array('LIKE', "%{$keywords}%");
        }

        // 多語言
        $condition['lang'] = array('eq', $this->admin_lang);

        $linksM =  M('links');
        $count = $linksM->where($condition)->count('id');// 查詢滿足要求的總記錄數
        $Page = $pager = new Page($count, config('paginate.list_rows'));// 實例化分頁類 傳入總記錄數和每頁顯示的記錄數
        $list = $linksM->where($condition)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        $show = $Page->show();// 分頁顯示輸出
        $this->assign('page',$show);// 賦值分頁輸出
        $this->assign('list',$list);// 賦值數據集
        $this->assign('pager',$pager);// 賦值分頁對像
        return $this->fetch();
    }

    /**
     * 新增友情鏈接
     */
    public function add()
    {
        if (IS_POST) {
            $post = input('post.');

            // 處理LOGO
            $is_remote = !empty($post['is_remote']) ? $post['is_remote'] : 0;
            $logo = '';
            if ($is_remote == 1) {
                $logo = $post['logo_remote'];
            } else {
                $logo = $post['logo_local'];
            }
            $post['logo'] = $logo;
            // --儲存數據
            $nowData = array(
                'typeid'    => empty($post['typeid']) ? 1 : $post['typeid'],
                'url'    => trim($post['url']),
                'lang'  => $this->admin_lang,
                'sort_order'    => 100,
                'add_time'    => getTime(),
                'update_time'    => getTime(),
            );
            $data = array_merge($post, $nowData);
            $insertId = M('links')->insertGetId($data);
            if (false !== $insertId) {
                Cache::clear('links');
                adminLog('新增友情鏈接：'.$post['title']);
                $this->success("操作成功!", url('Links/index'));
            }else{
                $this->error("操作失敗!", url('Links/index'));
            }
            exit;
        }

        return $this->fetch();
    }
    
    /**
     * 編輯友情鏈接
     */
    public function edit()
    {
        if (IS_POST) {
            $post = input('post.');
            $r = false;
            if(!empty($post['id'])){
                // 處理LOGO
                $is_remote = !empty($post['is_remote']) ? $post['is_remote'] : 0;
                $logo = '';
                if ($is_remote == 1) {
                    $logo = $post['logo_remote'];
                } else {
                    $logo = $post['logo_local'];
                }
                $post['logo'] = $logo;
                // --儲存數據
                $nowData = array(
                    'typeid'    => empty($post['typeid']) ? 1 : $post['typeid'],
                    'url'    => trim($post['url']),
                    'update_time'    => getTime(),
                );
                $data = array_merge($post, $nowData);
                $r = M('links')->where([
                        'id'    => $post['id'],
                        'lang'  => $this->admin_lang,
                    ])
                    ->cache(true, null, "links")
                    ->update($data);
            }
            if (false !== $r) {
                adminLog('編輯友情鏈接：'.$post['title']);
                $this->success("操作成功!",url('Links/index'));
            }else{
                $this->error("操作失敗!",url('Links/index'));
            }
            exit;
        }

        $id = input('id/d');
        $info = M('links')->where([
                'id'    => $id,
                'lang'  => $this->admin_lang,
            ])->find();
        if (empty($info)) {
            $this->error('數據不存在，請聯繫管理員！');
            exit;
        }
        if (is_http_url($info['logo'])) {
            $info['is_remote'] = 1;
            $info['logo_remote'] = handle_subdir_pic($info['logo']);
        } else {
            $info['is_remote'] = 0;
            $info['logo_local'] = handle_subdir_pic($info['logo']);
        }
        $this->assign('info',$info);

        return $this->fetch();
    }
    
    /**
     * 刪除友情鏈接
     */
    public function del()
    {
        if (IS_POST) {
            $id_arr = input('del_id/a');
            $id_arr = eyIntval($id_arr);
            if(!empty($id_arr)){
                $result = M('links')->field('title')
                    ->where([
                        'id'    => ['IN', $id_arr],
                        'lang'  => $this->admin_lang,
                    ])->select();
                $title_list = get_arr_column($result, 'title');

                $r = M('links')->where([
                        'id'    => ['IN', $id_arr],
                        'lang'  => $this->admin_lang,
                    ])
                    ->cache(true, null, "links")
                    ->delete();
                if($r){
                    adminLog('刪除友情鏈接：'.implode(',', $title_list));
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
}