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

namespace app\common\model;

use think\Db;
use think\Model;

/**
 * 欄目
 */
class Arctype extends Model
{
    //初始化
    protected function initialize()
    {
        // 需要呼叫`Model`的`initialize`方法
        parent::initialize();
    }

    /**
     * 獲取單條記錄
     * @author wengxianhu by 2017-7-26
     */
    public function getInfo($id, $field = '', $get_parent = false)
    {
        if (empty($field)) {
            $field = 'c.*, a.*';
        }
        $field .= ', a.id as typeid';

        /*目前欄目資訊*/
        $result = db('Arctype')->field($field)
            ->alias('a')
            ->where('a.id', $id)
            ->join('__CHANNELTYPE__ c', 'c.id = a.current_channel', 'LEFT')
            ->cache(true,EYOUCMS_CACHE_TIME,"arctype")
            ->find();
        /*--end*/
        if (!empty($result)) {
            $result['typeurl'] = $this->getTypeUrl($result); // 目前欄目的URL

            if ($get_parent) {
                /*獲取目前欄目父級欄目資訊*/
                if ($result['parent_id'] > 0) {
                    $parent_row = db('Arctype')->field($field)
                        ->alias('a')
                        ->where('a.id', $result['parent_id'])
                        ->join('__CHANNELTYPE__ c', 'c.id = a.current_channel', 'LEFT')
                        ->cache(true,EYOUCMS_CACHE_TIME,"arctype")
                        ->find();
                    $ptypeurl = $this->getTypeUrl($parent_row);
                    $parent_row['typeurl'] = $ptypeurl;
                } else {
                    $parent_row = $result;
                }
                /*--end*/
                
                /*給每個父類欄位開頭加上p*/
                foreach ($parent_row as $key => $val) {
                    $newK = 'p'.$key;
                    $parent_row[$newK] = $val;
                }
                /*--end*/
                $result = array_merge($result, $parent_row);
            } else {
                if (!empty($result['parent_id'])) {
                    // 目前欄目的父級欄目資訊
                    $parent_row = M('arctype')->where('id', $result['parent_id'])
                        ->cache(true,EYOUCMS_CACHE_TIME,"arctype")
                        ->find();
                    $ptypeurl = $this->getTypeUrl($parent_row);
                    $ptypename = $parent_row['typename'];
                    $pdirname = $parent_row['dirname'];
                    // 目前欄目的頂級欄目資訊
                    if (!isset($result['toptypeurl'])) {
                        $allPid = $this->getAllPid($id);
                        $toptypeinfo = current($allPid);
                        $toptypeurl = $this->getTypeUrl($toptypeinfo);
                        $toptypename = $ptypename;
                        $topdirname = $pdirname;
                    }
                    // end
                } else {
                    // 目前欄目的父級欄目資訊 或 頂級欄目的資訊
                    $toptypeurl = $ptypeurl = $result['typeurl'];
                    $toptypename = $ptypename = $result['typename'];
                    $topdirname = $pdirname = $result['dirname'];
                }
                // 目前欄目的父級欄目資訊
                $result['ptypeurl'] = $ptypeurl;
                $result['ptypename'] = $ptypename;
                $result['pdirname'] = $pdirname;
                // 目前欄目的頂級欄目資訊
                !isset($result['toptypeurl']) && $result['toptypeurl'] = $toptypeurl;
                !isset($result['toptypename']) && $result['toptypename'] = $toptypename;
                !isset($result['topdirname']) && $result['topdirname'] = $topdirname;
                // end
            }
        }

        return $result;
    }

    /**
     * 根據目錄名稱獲取單條記錄
     * @author wengxianhu by 2018-4-20
     */
    public function getInfoByDirname($dirname)
    {
        $field = 'c.*, a.*, a.id as typeid';

        $result = db('Arctype')->field($field)
            ->alias('a')
            ->where('a.dirname', $dirname)
            ->join('__CHANNELTYPE__ c', 'c.id = a.current_channel', 'LEFT')
            ->cache(true,EYOUCMS_CACHE_TIME,"arctype")
            ->find();
        if (!empty($result)) {
            $result['typeurl'] = $this->getTypeUrl($result);

            $result_tmp = M('arctype')->where('id', $result['parent_id'])->find();
            $result['ptypeurl'] = $this->getTypeUrl($result_tmp);
        }

        return $result;
    }

    /**
     * 檢測是否有子欄目
     * @author wengxianhu by 2017-7-26
     */
    public function hasChildren($id)
    {
        $count = db('Arctype')->where('parent_id', $id)->count('id');

        return ($count > 0 ? 1 : 0);
    }

    /**
     * 獲取欄目的URL
     */
    public function getTypeUrl($res)
    {
        if ($res['is_part'] == 1) {
            $typeurl = $res['typelink'];
        } else {
            $ctl_name = get_controller_byct($res['current_channel']);
            $typeurl = typeurl('home/'.$ctl_name."/lists", $res);
        }

        return $typeurl;
    }


    /**
     * 獲取指定級別的欄目列表
     * @param string type son表示下一級欄目,self表示同級欄目,top頂級欄目
     * @param boolean $self 包括自己本身
     * @author wengxianhu by 2018-4-26
     */
    public function getChannelList($id = '', $type = 'son')
    {
        $result = array();
        switch ($type) {
            case 'son':
                $result = $this->getSon($id, false);
                break;

            case 'self':
                $result = $this->getSelf($id);
                break;

            case 'top':
                $result = $this->getTop();
                break;

            case 'sonself':
                $result = $this->getSon($id, true);
                break;
        }

        return $result;
    }

    /**
     * 獲取下一級欄目
     * @param string $self true表示沒有子欄目時，獲取同級欄目
     * @author wengxianhu by 2017-7-26
     */
    public function getSon($id, $self = false)
    {
        $result = array();
        if (empty($id)) {
            return $result;
        }

        $arctypeLogic = new \app\common\logic\ArctypeLogic();
        $arctype_max_level = intval(config('global.arctype_max_level'));
        $map = array(
            'is_hidden'   => 0,
            'status'  => 1,
            'is_del'  => 0, // 回收站功能
        );
        $res = $arctypeLogic->arctype_list($id, 0, false, $arctype_max_level, $map);

        if (!empty($res)) {
            $arr = group_same_key($res, 'parent_id');
            for ($i=0; $i < $arctype_max_level; $i++) {
                foreach ($arr as $key => $val) {
                    foreach ($arr[$key] as $key2 => $val2) {
                        if (!isset($arr[$val2['id']])) continue;
                        $val2['children'] = $arr[$val2['id']];
                        $arr[$key][$key2] = $val2;
                    }
                }
            }
            if (isset($arr[$id])) {
                $result = $arr[$id];
            }
        }

        if (empty($result) && $self == true) {
            $result = $this->getSelf($id);
        }

        return $result;
    }

    /**
     * 獲取同級欄目
     * @author wengxianhu by 2017-7-26
     */
    public function getSelf($id)
    {
        $result = array();
        if (empty($id)) {
            return $result;
        }

        $map = array(
            'id'   => $id,
            'is_hidden'   => 0,
            'status'  => 1,
            'is_del'  => 0, // 回收站功能
        );
        $res = M('arctype')->field('parent_id')->where($map)->find();

        if ($res) {
            $newId = $res['parent_id'];
            $arctypeLogic = new \app\common\logic\ArctypeLogic();
            $arctype_max_level = intval(config('global.arctype_max_level'));
            $map = array(
                'is_hidden'   => 0,
                'status'  => 1,
            );
            $res = $arctypeLogic->arctype_list($newId, 0, false, $arctype_max_level, $map);

            if (!empty($res)) {
                $arr = group_same_key($res, 'parent_id');
                for ($i=0; $i < $arctype_max_level; $i++) { 
                    foreach ($arr as $key => $val) {
                        foreach ($arr[$key] as $key2 => $val2) {
                            if (!isset($arr[$val2['id']])) continue;
                            $val2['children'] = $arr[$val2['id']];
                            $arr[$key][$key2] = $val2;
                        }
                    }
                }
                $result = $arr[$newId];
            }
        }
        return $result;
    }

    /**
     * 獲取頂級欄目
     * @author wengxianhu by 2017-7-26
     */
    public function getTop()
    {
        $arctypeLogic = new \app\common\logic\ArctypeLogic();
        $arctype_max_level = intval(config('global.arctype_max_level'));
        $map = array(
            'is_hidden'   => 0,
            'status'  => 1,
            'is_del'  => 0, // 回收站功能
        );
        $res = $arctypeLogic->arctype_list(0, 0, false, $arctype_max_level, $map);

        $result = array();
        if (!empty($res)) {
            $arr = group_same_key($res, 'parent_id');
            for ($i=0; $i < $arctype_max_level; $i++) { 
                foreach ($arr as $key => $val) {
                    foreach ($arr[$key] as $key2 => $val2) {
                        if (!isset($arr[$val2['id']])) continue;
                        $val2['children'] = $arr[$val2['id']];
                        $arr[$key][$key2] = $val2;
                    }
                }
            }
            reset($arr);
            $firstResult = current($arr);
            $result = $firstResult;
        }

        return $result;
    }

    /**
     * 獲取目前欄目及所有子欄目
     * @param boolean $self 包括自己本身
     * @author wengxianhu by 2017-7-26
     */
    public function getHasChildren($id, $self = true)
    {
        $lang = get_current_lang(); // 多語言
        $cacheKey = "common_model_Arctype_getHasChildren_{$id}_{$self}_{$lang}";
        $result = cache($cacheKey);
        if (empty($result)) {
            $where = array(
                'c.status'  => 1,
                'c.lang'    => $lang,
                'c.is_del'  => 0,
            );
            $fields = "c.*, count(s.id) as has_children";
            $res = db('arctype')
                ->field($fields)
                ->alias('c')
                ->join('__ARCTYPE__ s','s.parent_id = c.id','LEFT')
                ->where($where)
                ->group('c.id')
                ->order('c.parent_id asc, c.sort_order asc, c.id')
                ->select();

            $result = arctype_options($id, $res, 'id', 'parent_id');

            if (!$self) {
                array_shift($result);
            }

            cache($cacheKey, $result, null, "arctype");
        }

        return $result;
    }

    /**
     * 獲取所有欄目
     * @param   int     $id     欄目的ID
     * @param   int     $selected   目前選中欄目的ID
     * @param   int     $channeltype      查詢條件
     * @author wengxianhu by 2017-7-26
     */
    public function getList($id = 0, $select = 0, $re_type = true, $map = array())
    {
        $id = $id ? intval($id) : 0;
        $select = $select ? intval($select) : 0;

        $arctypeLogic = new \app\common\logic\ArctypeLogic();
        $arctype_max_level = intval(config('global.arctype_max_level'));
        $options = $arctypeLogic->arctype_list($id, $select, $re_type, $arctype_max_level, $map);

        return $options;
    }


    /**
     * 預設獲取全部
     * @author 小虎哥 by 2018-4-16
     */
    public function getAll($field = '*', $map = array(), $index_key = '')
    {
        $lang = get_current_lang(); // 多語言
        $result = db('arctype')->field($field)
            ->where($map)
            ->where('lang',$lang)
            ->order('sort_order asc')
            ->cache(true,EYOUCMS_CACHE_TIME,"arctype")
            ->select();

        if (!empty($index_key)) {
            $result = convert_arr_key($result, $index_key);
        }

        return $result;
    }

    /**
     * 獲取目前欄目的所有父級
     * @author wengxianhu by 2018-4-26
     */
    public function getAllPid($id)
    {
        $cacheKey = array(
            'common',
            'model',
            'Arctype',
            'getAllPid',
            $id,
        );
        $cacheKey = json_encode($cacheKey);
        $data = cache($cacheKey);
        if (empty($data)) {
            $data = array();
            $typeid = $id;
            $arctype_list = M('Arctype')->field('*, id as typeid')
                ->where([
                    'status'    => 1,
                    'is_del'    => 0,
                ])
                ->getAllWithIndex('id');
            if (isset($arctype_list[$typeid])) {
                // 第一個先裝起來
                $arctype_list[$typeid]['typeurl'] = $this->getTypeUrl($arctype_list[$typeid]);
                $data[$typeid] = $arctype_list[$typeid];
            } else {
                return $data;
            }

            while (true)
            {
                $typeid = $arctype_list[$typeid]['parent_id'];
                if($typeid > 0){
                    if (isset($arctype_list[$typeid])) {
                        $arctype_list[$typeid]['typeurl'] = $this->getTypeUrl($arctype_list[$typeid]);
                        $data[$typeid] = $arctype_list[$typeid];
                    }
                } else {
                    break;
                }
            }
            $data = array_reverse($data, true);

            cache($cacheKey, $data, null, "arctype");
        }

        return $data;
    }

    /**
     * 偽刪除指定欄目（包括子欄目、所有相關文件）
     */
    public function pseudo_del($typeid)
    {
        $childrenList = $this->getHasChildren($typeid); // 獲取目前欄目以及所有子欄目
        $typeidArr = get_arr_column($childrenList, 'id'); // 獲取欄目陣列里的所有欄目ID作為新的陣列
        $typeidArr2 = $typeidArr;

        /*多語言*/
        $attr_name_arr = [];
        foreach ($typeidArr as $key => $val) {
            $attr_name = 'tid'.$val;
            $attr_name_arr[$key] = $attr_name;
        }
        $attr_values = Db::name('language_attr')->where([
                'attr_name'    => ['IN', $attr_name_arr],
                'attr_group'    => 'arctype',
            ])->column('attr_value');
        !empty($attr_values) && $typeidArr = $attr_values;
        /*--end*/

        /*標記目前欄目以及子欄目為被動偽刪除*/
        $sta1 = Db::name('arctype')
            ->where([
                'id'    => ['IN', $typeidArr],
                'is_del'    => 0,
                'del_method' => 0,
            ])
            ->cache(true,null,"arctype")
            ->update([
                'is_del'    => 1,
                'del_method'    => 2, // 1為主動刪除，2為跟隨上級欄目被動刪除
                'update_time'   => getTime(),
            ]); // 偽刪除欄目
        /*--end*/

        /*標記目前欄目為主動偽刪除*/
        // 多語言
        $attr_values = Db::name('language_attr')->where([
                'attr_name'    => 'tid'.$typeid,
                'attr_group'    => 'arctype',
            ])->column('attr_value');
        !empty($attr_values) && $typeidArr2 = $attr_values;
        // --end
        $sta2 = Db::name('arctype')
            ->where([
                'id'    => ['IN', $typeidArr2],
            ])
            ->cache(true,null,"arctype")
            ->update([
                'is_del'    => 1,
                'del_method'    => 1, // 1為主動刪除，2為跟隨上級欄目被動刪除
                'update_time'   => getTime(),
            ]); // 偽刪除欄目
        /*--end*/

        if ($sta1 && $sta2) {
            model('Archives')->pseudo_del($typeidArr); // 刪除文件
            // 刪除多語言欄目關聯繫結
            if (!empty($attr_name_arr)) {
                Db::name('language_attribute')->where([
                    'attr_name'     => ['IN',$attr_name_arr],
                ])->update([
                    'is_del'    => 1,
                    'update_time'   => getTime(),
                ]);
            }
            /*--end*/

            /*清除頁面快取*/
            // $htmlCacheLogic = new \app\common\logic\HtmlCacheLogic;
            // $htmlCacheLogic->clear_arctype();
            /*--end*/

            return true;
        }

        return false;
    }

    /**
     * 刪除指定欄目（包括子欄目、所有相關文件）
     */
    public function del($typeid)
    {
        $childrenList = $this->getHasChildren($typeid); // 獲取目前欄目以及所有子欄目
        $typeidArr = get_arr_column($childrenList, 'id'); // 獲取欄目陣列里的所有欄目ID作為新的陣列
        /*多語言*/
        $attr_name_arr = [];
        foreach ($typeidArr as $key => $val) {
            $attr_name = 'tid'.$val;
            $attr_name_arr[$key] = $attr_name;
        }
        $attr_values = Db::name('language_attr')->where([
                'attr_name'    => ['IN', $attr_name_arr],
                'attr_group'    => 'arctype',
            ])->column('attr_value');
        !empty($attr_values) && $typeidArr = $attr_values;
        /*--end*/
        $sta = Db::name('arctype')
            ->where([
                'id'    => ['IN', $typeidArr],
            ])
            ->cache(true,null,"arctype")
            ->delete(); // 刪除欄目
        if ($sta) {
            model('Archives')->del($typeidArr); // 刪除文件
            // 刪除多語言欄目關聯繫結
            if (!empty($attr_name_arr)) {
                Db::name('language_attribute')->where([
                    'attr_name'     => ['IN',$attr_name_arr],
                ])->update([
                    'is_del'    => 1,
                    'update_time'   => getTime(),
                ]);
            }
            /*--end*/

            /*清除頁面快取*/
            // $htmlCacheLogic = new \app\common\logic\HtmlCacheLogic;
            // $htmlCacheLogic->clear_arctype();
            /*--end*/

            return true;
        }

        return false;
    }

    /**
     * 每個欄目的頂級欄目的目錄名稱
     */
    public function getEveryTopDirnameList()
    {
        $result = extra_cache('common_getEveryTopDirnameList_model');
        if ($result === false)
        {
            $lang = get_current_lang(); // 多語言
            $fields = "c.id, c.parent_id, c.dirname, c.grade, count(s.id) as has_children";
            $row = db('arctype')
                ->field($fields)
                ->alias('c')
                ->join('__ARCTYPE__ s','s.parent_id = c.id','LEFT')
                ->where('c.lang',$lang)
                ->group('c.id')
                ->order('c.parent_id asc, c.sort_order asc, c.id')
                ->cache(true,EYOUCMS_CACHE_TIME,"arctype")
                ->select();
            $row = arctype_options(0, $row, 'id', 'parent_id');

            $result = array();
            foreach ($row as $key => $val) {
                if (empty($val['parent_id'])) {
                    $val['tdirname'] = $val['dirname'];
                } else {
                    $val['tdirname'] = isset($row[$val['parent_id']]['tdirname']) ? $row[$val['parent_id']]['tdirname'] : $val['dirname'];
                }
                $row[$key] = $val;
                $result[md5($val['dirname'])] = $val;
            }

            extra_cache('common_getEveryTopDirnameList_model', $result);
        }

        return $result;
    }

    /**
     * 新增欄目數據
     *
     * @param array $data
     * @return intval|boolean
     */
    public function addData($data = [])
    {
        $insertId = false;
        if (!empty($data)) {
            $insertId = M('arctype')->insertGetId($data);
            if($insertId){
                // --儲存單頁模型
                if ($data['current_channel'] == 6) {
                    $archivesData = array(
                        'title' => $data['typename'],
                        'typeid'=> $insertId,
                        'channel'   => $data['current_channel'],
                        'sort_order'    => 100,
                        'lang'  => $data['lang'],
                        'add_time'  => getTime(),
                    );
                    // $archivesData = array_merge($archivesData, $data);
                    $aid = M('archives')->insertGetId($archivesData);
                    if ($aid) {
                        // ---------後置操作
                        if (!isset($post['addonFieldExt'])) {
                            $post['addonFieldExt'] = array(
                                'typeid'    => $archivesData['typeid'],
                            );
                        } else {
                            $post['addonFieldExt']['typeid'] = $archivesData['typeid'];
                        }
                        $addData = array(
                            'addonFieldExt' => $post['addonFieldExt'],
                        );
                        $addData = array_merge($addData, $archivesData);
                        model('Single')->afterSave($aid, $addData, 'add');
                        // ---------end
                    }
                }

                /*同步欄目ID到許可權組，預設是賦予該欄目的許可權*/
                model('AuthRole')->syn_auth_role($insertId);
                /*--end*/

                /*清除頁面快取*/
                // $htmlCacheLogic = new \app\common\logic\HtmlCacheLogic;
                // $htmlCacheLogic->clear_arctype();
                /*--end*/

                // \think\Cache::clear("arctype");
                // extra_cache('admin_all_menu', NULL);
                // \think\Cache::clear('admin_archives_release');
            }
        }
        return $insertId;
    }

    /**
     * 編輯欄目數據
     *
     * @param array $data
     * @return intval|boolean
     */
    public function updateData($data = [])
    {
        $bool = false;
        if (!empty($data)) {
            $admin_lang = get_admin_lang();
            $bool = M('arctype')->where([
                    'id'    => $data['id'],
                    'lang'  => $admin_lang,
                ])
                ->cache(true,null,"arctype")
                ->update($data);
            if($bool){
                /*批量更新所有子孫欄目的最頂級模型ID*/
                $allSonTypeidArr = $this->getHasChildren($data['id'], false); // 獲取目前欄目的所有子孫欄目（不包含目前欄目）
                if (!empty($allSonTypeidArr)) {
                    $i = 1;
                    $minuendGrade = 0;
                    foreach ($allSonTypeidArr as $key => $val) {
                        if ($i == 1) {
                            $firstGrade = intval($post['oldgrade']);
                            $minuendGrade = intval($grade) - $firstGrade;
                        }
                        $update_data = array(
                            'channeltype'        => $data['channeltype'],
                            'update_time'        => getTime(),
                            'grade'   =>  Db::raw('grade+'.$minuendGrade),
                        );
                        M('arctype')->where([
                                'id'    => $val['id'],
                                'lang'  => $admin_lang,
                            ])
                            ->cache(true,null,"arctype")
                            ->update($update_data);
                        ++$i;
                    }
                }
                /*--end*/

                // --儲存單頁模型
                if ($data['current_channel'] == 6) {
                    $archivesData = array(
                        'title' => $data['typename'],
                        'typeid'=> $data['id'],
                        'channel'   => $data['current_channel'],
                        'sort_order'    => 100,
                        'update_time'     => getTime(),
                    );
                    // $archivesData = array_merge($archivesData, $data);
                    $aid = M('single_content')->where(array('typeid'=>$data['id']))->getField('aid');
                    if (empty($aid)) {
                        $opt = 'add';
                        $archivesData['lang'] = get_admin_lang();
                        $archivesData['add_time'] = getTime();
                        $up = $aid = M('archives')->insertGetId($archivesData);
                    } else {
                        $opt = 'edit';
                        $up = M('archives')->where([
                                'aid'   => $aid,
                                'lang'  => get_admin_lang(),
                            ])->update($archivesData);
                    }
                    if ($up) {
                        // ---------後置操作
                        if (!isset($post['addonFieldExt'])) {
                            $post['addonFieldExt'] = array(
                                'typeid'    => $data['id'],
                            );
                        } else {
                            $post['addonFieldExt']['typeid'] = $data['id'];
                        }
                        $updateData = array(
                            'addonFieldExt' => $post['addonFieldExt'],
                        );
                        $updateData = array_merge($updateData, $archivesData);
                        model('Single')->afterSave($aid, $updateData, $opt);
                        // ---------end
                    }
                }

                /*同步更改其他語言關聯繫結的欄目模型*/
                $attr_name = Db::name('language_attr')->where([
                        'attr_value'     => $data['id'],
                        'attr_group'    => 'arctype',
                        'lang'  => get_admin_lang(),
                    ])->getField('attr_name');
                $attr_values = Db::name('language_attr')->where([
                        'attr_name'     => $attr_name,
                        'attr_group'    => 'arctype',
                    ])->column('attr_value');
                Db::name('arctype')->where([
                        'id'    => ['IN', $attr_values],
                    ])->update([
                        'current_channel'   => $data['current_channel'],
                        'update_time'   => getTime(),
                    ]);
                /*--end*/

                /*清除頁面快取*/
                // $htmlCacheLogic = new \app\common\logic\HtmlCacheLogic;
                // $htmlCacheLogic->clear_arctype();
                /*--end*/

                // \think\Cache::clear("arctype");
                // extra_cache('admin_all_menu', NULL);
                // \think\Cache::clear('admin_archives_release');
            }
        }
        return $bool;
    }
}