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

namespace app\admin\logic;

use think\Model;
use think\Db;
/**
 * 欄位邏輯定義
 * Class CatsLogic
 * @package admin\Logic
 */
class FieldLogic extends Model
{
    /**
     * 獲得欄位建立資訊
     *
     * @access    public
     * @param     string  $dtype  欄位型別
     * @param     string  $fieldname  欄位名稱
     * @param     string  $dfvalue  預設值
     * @param     string  $fieldtitle  欄位標題
     * @return    array
     */
    function GetFieldMake($dtype, $fieldname, $dfvalue, $fieldtitle)
    {
        $fields = array();
        if("int" == $dtype)
        {
            $default_sql = '';
            if(preg_match("#[0-9]#", $dfvalue))
            {
                $default_sql = "DEFAULT '$dfvalue'";
            }
            $maxlen = 10;
            $fields[0] = " `$fieldname` int($maxlen) NOT NULL $default_sql COMMENT '$fieldtitle';";
            $fields[1] = "int($maxlen)";
            $fields[2] = $maxlen;
        }
        else if("datetime" == $dtype)
        {
            $default_sql = '';
            if(preg_match("#[0-9\-]#", $dfvalue))
            {
                $dfvalue = strtotime($dfvalue);
                $default_sql = "DEFAULT '$dfvalue'";
            }
            $maxlen = 11;
            $fields[0] = " `$fieldname` int($maxlen) NOT NULL $default_sql COMMENT '$fieldtitle';";
            $fields[1] = "int($maxlen)";
            $fields[2] = $maxlen;
        }
        else if("switch" == $dtype)
        {
            if(empty($dfvalue) || preg_match("#[^0-9]#", $dfvalue))
            {
                $dfvalue = 1;
            }
            $maxlen = 1;
            $fields[0] = " `$fieldname` tinyint($maxlen) NOT NULL DEFAULT '$dfvalue' COMMENT '$fieldtitle';";
            $fields[1] = "tinyint($maxlen)";
            $fields[2] = $maxlen;
        }
        else if("float" == $dtype)
        {
            $default_sql = '';
            if(preg_match("#[0-9\.]#", $dfvalue))
            {
                $default_sql = "DEFAULT '$dfvalue'";
            }
            $maxlen = 9;
            $fields[0] = " `$fieldname` float($maxlen,2) NOT NULL $default_sql COMMENT '$fieldtitle';";
            $fields[1] = "float($maxlen,2)";
            $fields[2] = $maxlen;
        }
        else if("decimal" == $dtype)
        {
            $default_sql = '';
            if(preg_match("#[0-9\.]#", $dfvalue))
            {
                $default_sql = "DEFAULT '$dfvalue'";
            }
            $maxlen = 10;
            $fields[0] = " `$fieldname` decimal($maxlen,2) NOT NULL $default_sql COMMENT '$fieldtitle';";
            $fields[1] = "decimal($maxlen,2)";
            $fields[2] = $maxlen;
        }
        else if("img" == $dtype)
        {
            if(empty($dfvalue)) {
                $dfvalue = '';
            }
            $maxlen = 250;
            $fields[0] = " `$fieldname` varchar($maxlen) NOT NULL DEFAULT '$dfvalue' COMMENT '$fieldtitle';";
            $fields[1] = "varchar($maxlen)";
            $fields[2] = $maxlen;
        }
        else if("imgs" == $dtype)
        {
            if(empty($dfvalue)) {
                $dfvalue = '';
            }
            $maxlen = 1001;
            $fields[0] = " `$fieldname` varchar($maxlen) NOT NULL DEFAULT '$dfvalue' COMMENT '$fieldtitle';";
            $fields[1] = "varchar($maxlen)";
            $fields[2] = $maxlen;
        }
        else if("files" == $dtype)
        {
            if(empty($dfvalue)) {
                $dfvalue = '';
            }
            $maxlen = 1002;
            $fields[0] = " `$fieldname` varchar($maxlen) NOT NULL DEFAULT '$dfvalue' COMMENT '$fieldtitle';";
            $fields[1] = "varchar($maxlen)";
            $fields[2] = $maxlen;
        }
        else if("multitext" == $dtype)
        {
            $maxlen = 0;
            $fields[0] = " `$fieldname` text COMMENT '$fieldtitle';";
            $fields[1] = "text";
            $fields[2] = $maxlen;
        }
        else if("htmltext" == $dtype)
        {
            $maxlen = 0;
            $fields[0] = " `$fieldname` longtext COMMENT '$fieldtitle';";
            $fields[1] = "longtext";
            $fields[2] = $maxlen;
        }
        else if("checkbox" == $dtype)
        {
            $maxlen = 0;
            $dfvalue = str_replace(',', "','", $dfvalue);
            $dfvalue = "'".$dfvalue."'";
            $fields[0] = " `$fieldname` SET($dfvalue) NULL COMMENT '$fieldtitle';";
            $fields[1] = "SET($dfvalue)";
            $fields[2] = $maxlen;
        }
        else if("select" == $dtype || "radio" == $dtype)
        {
            $maxlen = 0;
            $dfvalue = str_replace(',', "','", $dfvalue);
            $dfvalue = "'".$dfvalue."'";
            $fields[0] = " `$fieldname` enum($dfvalue) NULL COMMENT '$fieldtitle';";
            $fields[1] = "enum($dfvalue)";
            $fields[2] = $maxlen;
        }
        else
        {
            if(empty($dfvalue))
            {
                $dfvalue = '';
            }
            $maxlen = 200;
            $fields[0] = " `$fieldname` varchar($maxlen) NOT NULL DEFAULT '$dfvalue' COMMENT '$fieldtitle';";
            $fields[1] = "varchar($maxlen)";
            $fields[2] = $maxlen;
        }

        return $fields;
    }

    /**
     * 檢測頻道模型相關的表字段是否已存在，包括：主表和附加表
     *
     * @access    public
     * @param     string  $slave_table  附加表
     * @return    string $fieldname 欄位名
     * @return    int $channel_id 模型ID
     * @param     array  $filter  過濾哪些欄位
     */
    public function checkChannelFieldList($slave_table, $fieldname, $channel_id, $filter = array())
    {
        // 欄目表字段
        $arctypeFieldArr = Db::getTableFields(PREFIX.'arctype'); 
        foreach ($arctypeFieldArr as $key => $val) {
            if (!preg_match('/^type/i',$val)) {
                array_push($arctypeFieldArr, 'type'.$val);
            }
        }
        $masterFieldArr = Db::getTableFields(PREFIX.'archives'); // 文件主表字段
        $slaveFieldArr = Db::getTableFields($slave_table); // 文件附加表字段
        $addfields = ['pageurl','has_children','typelitpic','arcurl','typeurl']; // 額外與欄位衝突的變數名
        $fieldArr = array_merge($slaveFieldArr, $masterFieldArr, $addfields, $arctypeFieldArr); // 合併欄位
        if (!empty($fieldname)) {
            if (!empty($filter) && is_array($filter)) {
                foreach ($filter as $key => $val) {
                    $k = array_search($val, $fieldArr);
                    if (false !== $k) {
                        unset($fieldArr[$k]);
                    }
                }
            }
            return in_array($fieldname, $fieldArr);
        }

        return true;
    }

    /**
     * 檢測指定表的欄位是否已存在
     *
     * @access    public
     * @param     string  $table  數據表
     * @return    string $fieldname 欄位名
     * @param     array  $filter  過濾哪些欄位
     */
    public function checkTableFieldList($table, $fieldname, $filter = array())
    {
        $fieldArr = Db::getTableFields($table); // 表字段
        if (!empty($fieldname)) {
            if (!empty($filter) && is_array($filter)) {
                foreach ($filter as $key => $val) {
                    $k = array_search($val, $fieldArr);
                    if (false !== $k) {
                        unset($fieldArr[$k]);
                    }
                }
            }
            return in_array($fieldname, $fieldArr);
        }

        return true;
    }

    /**
     * 刪除指定模型的表字段
     * @param int $id channelfield表ID
     * @return bool
     */
    public function delChannelField($id)
    {
        $code = 0;
        $msg = '參數有誤！';
        if (!empty($id)) {
            $id = intval($id);
            $row = model('Channelfield')->getInfo($id, 'channel_id,name,ifsystem');
            if (!empty($row['ifsystem'])) {
                return array('code'=>0, 'msg'=>'禁止刪除系統欄位！');
            }
            $fieldname = $row['name'];
            $channel_id = $row['channel_id'];
            $table = M('channeltype')->where('id',$channel_id)->getField('table');
            $table = PREFIX.$table.'_content';
            if ($this->checkChannelFieldList($table, $fieldname, $channel_id)) {
                $sql = "ALTER TABLE `{$table}` DROP COLUMN `{$fieldname}`;";
                if(false !== Db::execute($sql)) {
                    /*重新產生數據表字段快取檔案*/
                    try {
                        schemaTable($table);
                    } catch (\Exception $e) {}
                    /*--end*/
                    return array('code'=>1, 'msg'=>'刪除成功！');
                } else {
                    $code = 0;
                    $msg = '刪除失敗！'; 
                }
            } else {
                $code = 2;
                $msg = '欄位不存在！';
            }
        }

        return array('code'=>$code, 'msg'=>$msg);
    }

    /**
     * 刪除欄目的表字段
     * @param int $id channelfield表ID
     * @return bool
     */
    public function delArctypeField($id)
    {
        $code = 0;
        $msg = '參數有誤！';
        if (!empty($id)) {
            $id = intval($id);
            $row = model('Channelfield')->getInfo($id, 'name,ifsystem');
            if (!empty($row['ifsystem'])) {
                return array('code'=>0, 'msg'=>'禁止刪除系統欄位！');
            }
            $fieldname = $row['name'];
            $table = PREFIX.'arctype';
            if ($this->checkTableFieldList($table, $fieldname)) {
                $sql = "ALTER TABLE `{$table}` DROP COLUMN `{$fieldname}`;";
                if(false !== Db::execute($sql)) {
                    /*重新產生數據表字段快取檔案*/
                    try {
                        schemaTable($table);
                    } catch (\Exception $e) {}
                    /*--end*/
                    return array('code'=>1, 'msg'=>'刪除成功！');
                } else {
                    $code = 0;
                    $msg = '刪除失敗！'; 
                }
            } else {
                $code = 2;
                $msg = '欄位不存在！';
            }
        }

        return array('code'=>$code, 'msg'=>$msg);
    }

    /**
     * 同步模型附加表的欄位記錄
     * @author 小虎哥 by 2018-4-16
     */
    public function synChannelTableColumns($channel_id)
    {
        $this->synArchivesTableColumns($channel_id);

        // $cacheKey = "admin-FieldLogic-synChannelTableColumns-{$channel_id}";
        // $cacheValue = cache($cacheKey);
        // if (!empty($cacheValue)) {
        //     return true;
        // }

        $channelfieldArr = M('channelfield')->field('name,dtype')->where('channel_id',$channel_id)->getAllWithIndex('name');

        $new_arr = array(); // 表字段陣列
        $addData = array(); // 數據儲存變數

        $table = M('channeltype')->where('id',$channel_id)->getField('table');
        $tableExt = PREFIX.$table.'_content';
        $rowExt = Db::query("SHOW FULL COLUMNS FROM {$tableExt}");
        foreach ($rowExt as $key => $val) {
            $fieldname = $val['Field'];
            if (in_array($fieldname, array('id','add_time','update_time','aid','typeid'))) {
                continue;
            }
            $new_arr[] = $fieldname;
            // 對比欄位記錄 表字段有 欄位新增記錄沒有
            if (empty($channelfieldArr[$fieldname])) {
                $dtype = $this->toDtype($val['Type']);
                $dfvalue = $this->toDefault($val['Type'], $val['Default']);
                if (in_array($fieldname, array('content'))) {
                    $ifsystem = 1;
                } else {
                    $ifsystem = 0;
                }
                $maxlength = preg_replace('/^([^\(]+)\(([^\)]+)\)(.*)/i', '$2', $val['Type']);
                $maxlength = intval($maxlength);
                $addData[] = array(
                    'name'  => $fieldname,
                    'channel_id'  => $channel_id,
                    'title'  => !empty($val['Comment']) ? $val['Comment'] : $fieldname,
                    'dtype' => $dtype,
                    'define'    => $val['Type'],
                    'maxlength' => $maxlength,
                    'dfvalue'   => $dfvalue,
                    'ifeditable'    => 1,
                    'ifsystem'  => $ifsystem,
                    'ifmain'    => 0,
                    'ifcontrol' => 0,
                    'add_time'  => getTime(),
                    'update_time'  => getTime(),
                );
            }
        }
        if (!empty($addData)) {
            M('channelfield')->insertAll($addData);
        }

        /*欄位新增記錄有，表字段沒有*/
        foreach($channelfieldArr as $k => $v){
            if (!in_array($k, $new_arr)) {
                $map = array(
                    'channel_id'    => $channel_id,
                    'ifmain'    => 0,
                    'name'  => $v['name'],
                );
                M('channelfield')->where($map)->delete();
            }
        }
        /*--end*/

        \think\Cache::clear('channelfield');

        // cache($cacheKey, 1, null, 'channelfield');
    }

    /**
     * 同步文件主表的欄位記錄到指定模型
     * @author 小虎哥 by 2018-4-16
     */
    public function synArchivesTableColumns($channel_id = '')
    {
        $channelfieldArr = M('channelfield')->field('name,dtype')->where('channel_id',$channel_id)->getAllWithIndex('name');

        $new_arr = array(); // 表字段陣列
        $addData = array(); // 數據儲存變數

        $controlFields = ['litpic','author'];

        $table = PREFIX.'archives';
        $row = Db::query("SHOW FULL COLUMNS FROM {$table}");
        $row = array_reverse($row);
        foreach ($row as $key => $val) {
            $fieldname = $val['Field'];
            $new_arr[] = $fieldname;
            // 對比欄位記錄 表字段有 欄位新增記錄沒有
            if (empty($channelfieldArr[$fieldname])) {
                $dtype = $this->toDtype($val['Type']);
                $dfvalue = $this->toDefault($val['Type'], $val['Default']);
                if (in_array($fieldname, $controlFields)) {
                    $ifcontrol = 0;
                } else {
                    $ifcontrol = 1;
                }
                $maxlength = preg_replace('/^([^\(]+)\(([^\)]+)\)(.*)/i', '$2', $val['Type']);
                $maxlength = intval($maxlength);
                $addData[] = array(
                    'name'  => $fieldname,
                    'channel_id'  => $channel_id,
                    'title'  => !empty($val['Comment']) ? $val['Comment'] : $fieldname,
                    'dtype' => $dtype,
                    'define'    => $val['Type'],
                    'maxlength' => $maxlength,
                    'dfvalue'   => $dfvalue,
                    'ifeditable'    => 1,
                    'ifsystem' => 1,
                    'ifmain'    => 1,
                    'ifcontrol' => $ifcontrol,
                    'add_time'  => getTime(),
                    'update_time'  => getTime(),
                );
            }
        }
        if (!empty($addData)) {
            M('channelfield')->insertAll($addData);
        }

        /*欄位新增記錄有，表字段沒有*/
        foreach($channelfieldArr as $k => $v){
            if (!in_array($k, $new_arr)) {
                $map = array(
                    'channel_id'  => $channel_id,
                    'ifmain'    => 1,
                    'name'  => $v['name'],
                );
                M('channelfield')->where($map)->delete();
            }
        }
        /*--end*/
    }

    /**
     * 同步欄目主表的欄位記錄
     * @author 小虎哥 by 2018-4-16
     */
    public function synArctypeTableColumns($channel_id = '')
    {
        $cacheKey = "admin-FieldLogic-synArctypeTableColumns-{$channel_id}";
        $cacheValue = cache($cacheKey);
        if (!empty($cacheValue)) {
            return true;
        }

        $channel_id = !empty($channel_id) ? $channel_id : config('global.arctype_channel_id');
        $channelfieldArr = M('channelfield')->field('name,dtype')->where('channel_id',$channel_id)->getAllWithIndex('name');

        $new_arr = array(); // 表字段陣列
        $addData = array(); // 數據儲存變數

        $table = PREFIX.'arctype';
        $row = Db::query("SHOW FULL COLUMNS FROM {$table}");
        $row = array_reverse($row);
        $arctypeTableFields = config('global.arctype_table_fields');
        foreach ($row as $key => $val) {
            $fieldname = $val['Field'];
            $new_arr[] = $fieldname;
            // 對比欄位記錄 表字段有 欄位新增記錄沒有
            if (empty($channelfieldArr[$fieldname])) {
                $dtype = $this->toDtype($val['Type']);
                $dfvalue = $this->toDefault($val['Type'], $val['Default']);
                if (in_array($fieldname, $arctypeTableFields)) {
                    $ifsystem = 1;
                } else {
                    $ifsystem = 0;
                }
                $maxlength = preg_replace('/^([^\(]+)\(([^\)]+)\)(.*)/i', '$2', $val['Type']);
                $maxlength = intval($maxlength);
                $addData[] = array(
                    'name'  => $fieldname,
                    'channel_id'  => $channel_id,
                    'title'  => !empty($val['Comment']) ? $val['Comment'] : $fieldname,
                    'dtype' => $dtype,
                    'define'    => $val['Type'],
                    'maxlength' => $maxlength,
                    'dfvalue'   => $dfvalue,
                    'ifeditable'    => 1,
                    'ifsystem' => $ifsystem,
                    'ifmain'    => 1,
                    'ifcontrol' => 1,
                    'add_time'  => getTime(),
                    'update_time'  => getTime(),
                );
            }
        }
        if (!empty($addData)) {
            M('channelfield')->insertAll($addData);
        }

        /*欄位新增記錄有，表字段沒有*/
        foreach($channelfieldArr as $k => $v){
            if (!in_array($k, $new_arr)) {
                $map = array(
                    'channel_id'  => $channel_id,
                    'name'  => $v['name'],
                );
                M('channelfield')->where($map)->delete();
            }
        }
        /*--end*/

        /*修復v1.1.9版本的admin_id為系統欄位*/
        M('channelfield')->where('name','admin_id')->update(['ifsystem'=>1]);
        /*--end*/

        \think\Cache::clear('channelfield');
        \think\Cache::clear("arctype");

        cache($cacheKey, 1, null, 'channelfield');
    }

    /**
     * 表字段型別轉為自定義欄位型別
     * @author 小虎哥 by 2018-4-16
     */
    public function toDtype($fieldtype = '')
    {
        if (preg_match('/^int/i', $fieldtype)) {
            $maxlen = preg_replace('/^int\((.*)\)/i', '$1', $fieldtype);
            if (10 == $maxlen) {
                $dtype = 'int';
            } else if (11 == $maxlen) {
                $dtype = 'datetime';
            }
        } else if (preg_match('/^longtext/i', $fieldtype)) {
            $dtype = 'htmltext';
        } else if (preg_match('/^text/i', $fieldtype)) {
            $dtype = 'multitext';
        } else if (preg_match('/^enum/i', $fieldtype)) {
            $dtype = 'select';
        } else if (preg_match('/^set/i', $fieldtype)) {
            $dtype = 'checkbox';
        } else if (preg_match('/^float/i', $fieldtype)) {
            $dtype = 'float';
        } else if (preg_match('/^decimal/i', $fieldtype)) {
            $dtype = 'decimal';
        } else if (preg_match('/^tinyint/i', $fieldtype)) {
            $dtype = 'switch';
        } else if (preg_match('/^varchar/i', $fieldtype)) {
            $maxlen = preg_replace('/^varchar\((.*)\)/i', '$1', $fieldtype);
            if (250 == $maxlen) {
                $dtype = 'img';
            } else if (1001 == $maxlen) {
                $dtype = 'imgs';
            } else if (1002 == $maxlen) {
                $dtype = 'files';
            } else {
                $dtype = 'text';
            }
        } else {
            $dtype = 'text';
        }

        return $dtype;
    }

    /**
     * 表字段的預設值
     * @author 小虎哥 by 2018-4-16
     */
    public function toDefault($fieldtype, $dfvalue = '')
    {
        if (preg_match('/^(enum|set)/i', $fieldtype)) {
            $str = preg_replace('/^(enum|set)\((.*)\)/i', '$2', $fieldtype);
            $str = str_replace("'", "", $str);
        } else {
            $str = $dfvalue;
        }
        $str = ("" != $str) ? $str : '';

        return $str;
    }

    /**
     * 處理自定義欄位的值
     * @author 小虎哥 by 2018-4-16
     */
    public function handleAddonField($channel_id, $dataExt)
    {
        $nowDataExt = array();
        if (!empty($dataExt) && !empty($channel_id)) {
            $fieldTypeList = model('Channelfield')->getListByWhere(array('channel_id'=>$channel_id), 'name,dtype', 'name');
            foreach ($dataExt as $key => $val) {
                
                $key = preg_replace('/^(.*)(_eyou_is_remote|_eyou_remote|_eyou_local)$/', '$1', $key);
                $dtype = !empty($fieldTypeList[$key]) ? $fieldTypeList[$key]['dtype'] : '';
                switch ($dtype) {

                    case 'checkbox':
                    {
                        $val = implode(',', $val);
                        break;
                    }

                    case 'switch':
                    case 'int':
                    {
                        $val = intval($val);
                        break;
                    }

                    case 'img':
                    {
                        $is_remote = !empty($dataExt[$key.'_eyou_is_remote']) ? $dataExt[$key.'_eyou_is_remote'] : 0;
                        if (1 == $is_remote) {
                            $val = $dataExt[$key.'_eyou_remote'];
                        } else {
                            $val = $dataExt[$key.'_eyou_local'];
                        }
                        break;
                    }

                    case 'imgs':
                    case 'files':
                    {
                        foreach ($val as $k2 => $v2) {
                            if (empty($v2)) {
                                unset($val[$k2]);
                                continue;
                            }
                            $val[$k2] = trim($v2);
                        }
                        $val = implode(',', $val);
                        break;
                    }

                    case 'datetime':
                    {
                        $val = !empty($val) ? strtotime($val) : getTime();
                        break;
                    }

                    case 'decimal':
                    {
                        $moneyArr = explode('.', $val);
                        $money1 = !empty($moneyArr[0]) ? intval($moneyArr[0]) : '0';
                        $money2 = !empty($moneyArr[1]) ? intval(msubstr($moneyArr[1], 0, 2)) : '00';
                        $val = $money1.'.'.$money2;
                        break;
                    }
                    
                    default:
                    {
                        $val = trim($val);
                        break;
                    }
                }
                $nowDataExt[$key] = $val;
            }
        }

        return $nowDataExt;
    }
}
