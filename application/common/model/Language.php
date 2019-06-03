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
 * 模型
 */
class Language extends Model
{
    //初始化
    protected function initialize()
    {
        // 需要呼叫`Model`的`initialize`方法
        parent::initialize();
    }

    /**
     * 後置操作方法
     * 自定義的一個函式 用於數據新增之後做的相應處理操作, 使用時手動呼叫
     * @param int $aid 產品id
     * @param array $post post數據
     * @param string $opt 操作
     */
    public function afterAdd($insertId = '', $post = [])
    {
        $mark = trim($post['mark']);

        /*設定預設語言，只允許有一個是預設，其他取消*/
        if (1 == intval($post['is_home_default'])) {
            $this->where('id','NEQ',$insertId)->update([
                'is_home_default' => 0,
                'update_time' => getTime(),
            ]);
            /*多語言 設定預設前臺語言*/
            if (is_language()) {
                $langRow = \think\Db::name('language')->order('id asc')
                    ->cache(true, EYOUCMS_CACHE_TIME, 'language')
                    ->select();
                foreach ($langRow as $key => $val) {
                    tpCache('system', ['system_home_default_lang'=>$mark], $val['mark']);
                }
            } else { // 單語言
                tpCache('system', ['system_home_default_lang'=>$mark]);
            }
            /*--end*/
        }
        /*--end*/

        /*複製欄目表以及關聯數據*/
        $syn_status = $this->syn_arctype($mark, $post['copy_lang']);
        if (false === $syn_status) {
            return $syn_status;
        }
        /*--end*/

        /*複製閱讀許可權表數據*/
        $arcrank_db = Db::name('arcrank');
        $arcrankCount = $arcrank_db->where('lang',$mark)->count();
        if (empty($arcrankCount)) {
            $arcrankRow = $arcrank_db->field('id,lang',true)
                ->where('lang', $post['copy_lang'])
                ->order('id asc')
                ->select();
            if (!empty($arcrankRow)) {
                foreach ($arcrankRow as $key => $val) {
                    $arcrankRow[$key]['lang'] = $mark;
                }
                $insertNum = $arcrank_db->insertAll($arcrankRow);
                if ($insertNum != count($arcrankRow)) {
                    return false;
                }
            }
        }
        /*--end*/

        /*複製基礎資訊表數據*/
        $config_db = Db::name('config');
        $configCount = $config_db->where('lang',$mark)->count();
        if (empty($configCount)) {
            $configRow = $config_db->field('id,lang',true)
                ->where('lang', $post['copy_lang'])
                ->order('id asc')
                ->select();
            if (!empty($configRow)) {
                foreach ($configRow as $key => $val) {
                    $configRow[$key]['lang'] = $mark;
                    /*臨時測試*/
                    if ($val['name'] == 'web_name') {
                        $configRow[$key]['value'] = $mark.$val['value'];
                    }
                    /*--end*/
                }
                $insertObject = model('Config')->saveAll($configRow);
                $insertNum = count($insertObject);
                if ($insertNum != count($configRow)) {
                    return false;
                }
            }
        }
        /*--end*/

        /*複製自定義變數表數據*/
        $configattribute_db = Db::name('config_attribute');
        $configattributeCount = $configattribute_db->where('lang',$mark)->count();
        if (empty($configattributeCount)) {
            $configAttrRow = $configattribute_db->field('attr_id,lang',true)
                ->where('lang', $post['copy_lang'])
                ->order('attr_id asc')
                ->select();
            if (!empty($configAttrRow)) {
                foreach ($configAttrRow as $key => $val) {
                    $configAttrRow[$key]['lang'] = $mark;
                }
                $insertObject = model('ConfigAttribute')->saveAll($configAttrRow);
                $insertNum = count($insertObject);
                if ($insertNum != count($configAttrRow)) {
                    return false;
                }
            }
        }
        /*--end*/

        /*複製廣告位置表以及廣告表數據*/
        $syn_status = $this->syn_ad_position($mark, $post['copy_lang']);
        if (false === $syn_status) {
            return $syn_status;
        }
        /*--end*/

        /*複製友情鏈接表數據*/
        $links_db = Db::name('links');
        $linksCount = $links_db->where('lang',$mark)->count();
        if (empty($linksCount)) {
            $linksRow = $links_db->field('id,lang',true)
                ->where('lang', $post['copy_lang'])
                ->order('id asc')
                ->select();
            if (!empty($linksRow)) {
                foreach ($linksRow as $key => $val) {
                    $linksRow[$key]['lang'] = $mark;
                    $linksRow[$key]['title'] = $mark.$val['title']; // 臨時測試
                }
                $insertObject = model('Links')->saveAll($linksRow);
                $insertNum = count($insertObject);
                if ($insertNum != count($linksRow)) {
                    return false;
                }
            }
        }
        /*--end*/

        /*複製郵件模板表數據*/
        $smtp_tpl_db = Db::name('smtp_tpl');
        $smtptplCount = $smtp_tpl_db->where('lang',$mark)->count();
        if (empty($smtptplCount)) {
            $smtptplRow = $smtp_tpl_db->field('tpl_id,lang',true)
                ->where('lang', $post['copy_lang'])
                ->order('tpl_id asc')
                ->select();
            if (!empty($smtptplRow)) {
                foreach ($smtptplRow as $key => $val) {
                    $smtptplRow[$key]['lang'] = $mark;
                }
                $insertObject = model('SmtpTpl')->saveAll($smtptplRow);
                $insertNum = count($insertObject);
                if ($insertNum != count($smtptplRow)) {
                    return false;
                }
            }
        }
        /*--end*/

        /*複製模板語言包變數表數據*/
        $langpack_db = Db::name('language_pack');
        $langpackCount = $langpack_db->where('lang',$mark)->count();
        if (empty($langpackCount)) {
            $langpackRow = $langpack_db->field('id,lang',true)
                ->where([
                    'lang'  => $post['copy_lang'],
                    'is_syn'    => 0,
                ])
                ->order('id asc')
                ->select();
            if (!empty($langpackRow)) {
                foreach ($langpackRow as $key => $val) {
                    $langpackRow[$key]['lang'] = $mark;
                }
                $insertObject = model('LanguagePack')->saveAll($langpackRow);
                $insertNum = count($insertObject);
                if ($insertNum != count($langpackRow)) {
                    return false;
                }
            }
        }
        /*--end*/
        
        /*統計多語言數量*/
        $this->setLangNum();
        /*--end*/

        \think\Cache::clear('language');
        delFile(RUNTIME_PATH.'cache'.DS.$mark, true);

        return true;
    }

    /**
     * 統計多語言數量
     */
    public function setLangNum()
    {
        \think\Cache::clear('system_langnum');
        $languageRow = Db::name('language')->field('mark')->select();
        foreach ($languageRow as $key => $val) {
            tpCache('system', ['system_langnum'=>count($languageRow)], $val['mark']);
        }
    }

    /**
     * 後置操作方法
     * 自定義的一個函式 用於數據刪除之後做的相應處理操作, 使用時手動呼叫
     * @param int $aid 產品id
     * @param array $post post數據
     * @param string $opt 操作
     */
    public function afterDel($id_arr = [], $lang_list = [])
    {
        if (!empty($id_arr) && !empty($lang_list)) {
            \think\Cache::clear('language');
            foreach ($lang_list as $key => $lang) {
                delFile(RUNTIME_PATH.'cache'.DS.$lang, true);
                @unlink(APP_PATH."lang/{$lang}.php");
            }
            /*統計多語言數量*/
            $this->setLangNum();
            /*同步刪除模板欄目繫結表數據*/
            Db::name('language_attr')->where("lang",'IN',$lang_list)->delete();
            /*同步刪除模板語言變數表數據*/
            Db::name('language_pack')->where("lang",'IN',$lang_list)->delete();
            /*同步刪除閱讀許可權表數據*/
            Db::name('arcrank')->where("lang",'IN',$lang_list)->delete();
            /*同步刪除基礎資訊表數據*/
            Db::name('config')->where("lang",'IN',$lang_list)->delete();
            /*同步刪除自定義變數表數據*/
            Db::name('config_attribute')->where("lang",'IN',$lang_list)->delete();
            /*同步刪除欄目表以及文件表數據*/
            $typeids = Db::name('arctype')->where("lang",'IN',$lang_list)->column('id');
            //待刪除欄目ID集合
            Db::name('arctype')->where("lang",'IN',$lang_list)->delete(); // 欄目表
            $aids = Db::name('archives')->where("typeid",'IN',$typeids)->column('aid'); 
            //待刪除文件ID集合
            Db::name('archives')->where("aid",'IN',$aids)->delete(); // 文件主表
            Db::name('article_content')->where("aid",'IN',$aids)->delete(); // 文章內容表
            Db::name('download_content')->where("aid",'IN',$aids)->delete(); // 軟體內容表
            Db::name('download_file')->where("aid",'IN',$aids)->delete(); // 軟體附件表
            Db::name('guestbook')->where("aid",'IN',$aids)->delete(); // 留言主表
            Db::name('guestbook_attr')->where("aid",'IN',$aids)->delete(); // 留言內容表
            Db::name('images_content')->where("aid",'IN',$aids)->delete(); // 圖集內容表
            Db::name('images_upload')->where("aid",'IN',$aids)->delete(); // 圖集圖片表
            Db::name('product_content')->where("aid",'IN',$aids)->delete(); // 產品內容表
            Db::name('product_img')->where("aid",'IN',$aids)->delete(); // 產品圖集表
            Db::name('single_content')->where("aid",'IN',$aids)->delete(); // 單頁內容表
            /*同步刪除產品屬性表數據*/
            Db::name('product_attribute')->where("lang",'IN',$lang_list)->delete();
            /*同步刪除留言屬性表數據*/
            Db::name('guestbook_attribute')->where("lang",'IN',$lang_list)->delete();
            /*同步刪除廣告表數據*/
            Db::name('ad')->where("lang",'IN',$lang_list)->delete();
            /*同步刪除廣告位置表數據*/
            Db::name('ad_position')->where("lang",'IN',$lang_list)->delete();
            /*同步刪除友情鏈接表數據*/
            Db::name('links')->where("lang",'IN',$lang_list)->delete();
            /*同步刪除視覺化表數據*/
            Db::name('ui_config')->where("lang",'IN',$lang_list)->delete();
            /*同步刪除Tag標籤表數據*/
            Db::name('taglist')->where("lang",'IN',$lang_list)->delete();
            /*同步刪除標籤索引表數據*/
            Db::name('tagindex')->where("lang",'IN',$lang_list)->delete();
            /*同步刪除郵件模板表數據*/
            Db::name('smtp_tpl')->where("lang",'IN',$lang_list)->delete();
            /*同步刪除郵件發送記錄表數據*/
            Db::name('smtp_record')->where("lang",'IN',$lang_list)->delete();
        }
    }

    /**
     * 建立語言時，同步第一個語言的欄目到新語言里
     *
     * @param string $mark 新增語言
     * @param string $copy_lang 複製語言
     */
    private function syn_arctype($mark = '', $copy_lang = 'cn')
    {
        $arctype_db = Db::name('arctype');

        /*刪除新增語言之前的多餘數據*/
        $count = $arctype_db->where('lang',$mark)->count();
        if (!empty($count)) {
            $arctype_db->where("lang",$mark)->delete();
        }
        /*--end*/

        $bindArctypeArr = []; // 源欄目ID與目標欄目ID的對應陣列
        $arctypeLogic = new \app\common\logic\ArctypeLogic;
        $arctypeList = $arctypeLogic->arctype_list(0, 0, false, 0, ['lang'=>$copy_lang]);

        if (empty($mark) || empty($arctypeList)) {
            return -1;
        }

        /*複製產品屬性表數據*/
        $bindProductAttributeArr = []; // 源產品屬性ID與目標產品屬性ID的對應陣列
        $product_attribute_db = Db::name('product_attribute');
        $productAttributeRow = $product_attribute_db->where('lang',$copy_lang)
            ->order('attr_id asc')
            ->select();
        $productAttributeRow = group_same_key($productAttributeRow, 'typeid');
        /*--end*/

        /*複製留言屬性表數據*/
        $bindgbookAttributeArr = []; // 源留言屬性ID與目標留言屬性ID的對應陣列
        $guestbook_attribute_db = Db::name('guestbook_attribute');
        $gbookAttributeRow = $guestbook_attribute_db->where('lang',$copy_lang)
            ->order('attr_id asc')
            ->select();
        $gbookAttributeRow = group_same_key($gbookAttributeRow, 'typeid');
        /*--end*/

        /*複製欄目表數據*/
        $arctype_M = model('Arctype');
        foreach ($arctypeList as $key => $val) {
            $data = $val;
            unset($data['id']);
            $data['lang'] = $mark;
            $data['typename'] = $mark.$data['typename']; // 臨時測試
            $data['parent_id'] = !empty($bindArctypeArr[$val['parent_id']]) ? $bindArctypeArr[$val['parent_id']] : 0;
            $typeid = $arctype_M->addData($data);
            if (empty($typeid)) {
                return false; // 同步失敗
            }
            $bindArctypeArr[$val['id']] = $typeid;
            /*複製產品屬性表數據*/
            if (!empty($productAttributeRow[$val['id']])) {
                foreach ($productAttributeRow[$val['id']] as $k2 => $v2) {
                    $proArr = $v2;
                    $proArr['typeid'] = $typeid;
                    $proArr['lang'] = $mark;
                    unset($proArr['attr_id']);
                    $proArr['attr_name'] = $mark.$proArr['attr_name']; // 臨時測試
                    $new_attr_id = $product_attribute_db->insertGetId($proArr);
                    if (empty($new_attr_id)) {
                        return false; // 同步失敗
                    }
                    $bindProductAttributeArr[$v2['attr_id']] = $new_attr_id;
                }
            }
            /*--end*/
            /*複製留言屬性表數據*/
            if (!empty($gbookAttributeRow[$val['id']])) {
                foreach ($gbookAttributeRow[$val['id']] as $k2 => $v2) {
                    $gbArr = $v2;
                    $gbArr['typeid'] = $typeid;
                    $gbArr['lang'] = $mark;
                    unset($gbArr['attr_id']);
                    $gbArr['attr_name'] = $mark.$gbArr['attr_name']; // 臨時測試
                    $new_attr_id = $guestbook_attribute_db->insertGetId($gbArr);
                    if (empty($new_attr_id)) {
                        return false; // 同步失敗
                    }
                    $bindgbookAttributeArr[$v2['attr_id']] = $new_attr_id;
                }
            }
            /*--end*/
        }
        /*--end*/

        $langAttrData = [];

        /*新增欄目ID與源欄目ID的繫結*/
        foreach ($bindArctypeArr as $key => $val) {
            $langAttrData[] = [
                'attr_name' => 'tid'.$key,
                'attr_value'    => $val,
                'lang'  => $mark,
                'attr_group' => 'arctype',
                'add_time'  => getTime(),
                'update_time'  => getTime(),
            ];
        }
        /*--end*/
        /*新增產品屬性ID與源產品屬性ID的繫結*/
        foreach ($bindProductAttributeArr as $key => $val) {
            $langAttrData[] = [
                'attr_name' => 'attr_'.$key,
                'attr_value'    => $val,
                'lang'  => $mark,
                'attr_group' => 'product_attribute',
                'add_time'  => getTime(),
                'update_time'  => getTime(),
            ];
        }
        /*--end*/
        /*新增留言屬性ID與源留言屬性ID的繫結*/
        foreach ($bindgbookAttributeArr as $key => $val) {
            $langAttrData[] = [
                'attr_name' => 'attr_'.$key,
                'attr_value'    => $val,
                'lang'  => $mark,
                'attr_group' => 'guestbook_attribute',
                'add_time'  => getTime(),
                'update_time'  => getTime(),
            ];
        }
        /*--end*/

        // 批量儲存
        if (!empty($langAttrData)) {
            $insertObject = model('LanguageAttr')->saveAll($langAttrData);
            $insertNum = count($insertObject);
            if ($insertNum != count($langAttrData)) {
                return false;
            }
        }

        return true;
    }

    /**
     * 建立語言時，同步廣告位置以及廣告數據，並進行多語言關聯繫結
     *
     * @param string $mark 新增語言
     * @param string $copy_lang 複製語言
     */
    private function syn_ad_position($mark = '', $copy_lang = 'cn')
    {
        $ad_position_db = Db::name('ad_position');

        /*刪除新增語言之前的多餘數據*/
        $count = $ad_position_db->where('lang',$mark)->count();
        if (!empty($count)) {
            $ad_position_db->where("lang",$mark)->delete();
        }
        /*--end*/

        // 廣告位置列表
        $bindAdpositionArr = []; // 源廣告位置ID與目標廣告位置ID的對應陣列
        $adpositionList = $ad_position_db->where([
            'lang'=>$copy_lang
            ])->order('id asc')
            ->select();

        if (empty($mark) || empty($adpositionList)) {
            return -1;
        }

        /*複製廣告表數據*/
        $bindAdArr = []; // 源廣告ID與目標廣告ID的對應陣列
        $ad_db = Db::name('ad');
        $adRow = $ad_db->where('lang',$copy_lang)
            ->order('id asc')
            ->select();
        $adRow = group_same_key($adRow, 'pid');
        /*--end*/

        /*複製廣告位置表數據*/
        foreach ($adpositionList as $key => $val) {
            $data = $val;
            unset($data['id']);
            $data['lang'] = $mark;
            $data['title'] = $mark.$data['title']; // 臨時測試
            $pid = $ad_position_db->insertGetId($data);
            if (empty($pid)) {
                return false; // 同步失敗
            }
            $bindAdpositionArr[$val['id']] = $pid;
            /*複製廣告表數據*/
            if (!empty($adRow[$val['id']])) {
                foreach ($adRow[$val['id']] as $k2 => $v2) {
                    $adArr = $v2;
                    $adArr['pid'] = $pid;
                    $adArr['lang'] = $mark;
                    unset($adArr['id']);
                    $adArr['title'] = $mark.$adArr['title']; // 臨時測試
                    $new_ad_id = $ad_db->insertGetId($adArr);
                    if (empty($new_ad_id)) {
                        return false; // 同步失敗
                    }
                    $bindAdArr[$v2['id']] = $new_ad_id;
                }
            }
            /*--end*/
        }
        /*--end*/

        $langAttrData = [];

        /*新增廣告位置ID與源廣告位置ID的繫結*/
        foreach ($bindAdpositionArr as $key => $val) {
            $langAttrData[] = [
                'attr_name' => 'adp'.$key,
                'attr_value'    => $val,
                'lang'  => $mark,
                'attr_group' => 'ad_position',
                'add_time'  => getTime(),
                'update_time'  => getTime(),
            ];
        }
        /*--end*/
        /*新增廣告ID與源廣告ID的繫結*/
        foreach ($bindAdArr as $key => $val) {
            $langAttrData[] = [
                'attr_name' => 'ad'.$key,
                'attr_value'    => $val,
                'lang'  => $mark,
                'attr_group' => 'ad',
                'add_time'  => getTime(),
                'update_time'  => getTime(),
            ];
        }
        /*--end*/

        // 批量儲存
        if (!empty($langAttrData)) {
            $insertObject = model('LanguageAttr')->saveAll($langAttrData);
            $insertNum = count($insertObject);
            if ($insertNum != count($langAttrData)) {
                return false;
            }
        }

        return true;
    }
}