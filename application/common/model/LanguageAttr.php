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
use think\Request;

/**
 * 模型
 */
class LanguageAttr extends Model
{
    //初始化
    protected function initialize()
    {
        // 需要呼叫`Model`的`initialize`方法
        parent::initialize();
    }

    /**
     * 獲取關聯繫結的變數值
     * @param string|array $bind_value 繫結之前的值，或者繫結之後的值
     * @param string $group 分組
     */
    public function getBindValue($bind_value = '', $attr_group = 'arctype', $langvar = '')
    {
        /*單語言情況下不執行多語言程式碼*/
        if (!is_language()) {
            return $bind_value;
        }
        /*--end*/

        $main_lang = get_main_lang();
        $lang = $main_lang;
        if ('admin' == request()->module()) {
            $lang = get_admin_lang();
        } else {
            $lang = get_home_lang();
        }

        if (!empty($bind_value) && $main_lang != $lang) {
            switch ($attr_group) {
                case 'arctype':
                    {
                        if (!is_array($bind_value)) { // 獲取關聯繫結的欄目ID
                            $typeidArr = explode(',', $bind_value);
                            $row = Db::name('language_attr')->field('attr_name')
                                ->where([
                                    'attr_value'    => ['IN', $typeidArr],
                                    'attr_group' => $attr_group,
                                ])->select();
                            if (!empty($row)) {
                                $row2 = Db::name('language_attr')->field('attr_name,attr_value')
                                    ->where([
                                        'attr_name' => ['IN', get_arr_column($row, 'attr_name')],
                                        'lang'  => $lang,
                                        'attr_group' => $attr_group,
                                    ])->select();
                                if (1 < count($typeidArr)) {
                                    $bind_value = implode(',', get_arr_column($row2, 'attr_value'));
                                } else {
                                    if(empty($row2)) {
                                         $bind_value = '';  
                                    } else {
                                         $bind_value = $row2[0]['attr_value'];  
                                    }
                                }
                            }
                        }
                    }
                    break;

                case 'product_attribute':
                    {
                        if (is_array($bind_value)) {
                            !empty($langvar) && $lang = $langvar;
                            $row = Db::name('language_attr')->field('attr_name, attr_value')
                                ->where([
                                    'attr_value'    => ['IN', get_arr_column($bind_value, 'attr_id')],
                                    'attr_group' => $attr_group,
                                ])->getAllWithIndex('attr_value');
                            if (!empty($row)) {
                                $row2 = Db::name('language_attr')->field('attr_name, attr_value')
                                    ->where([
                                        'attr_name' => ['IN', get_arr_column($row, 'attr_name')],
                                        'lang'  => $lang,
                                        'attr_group' => $attr_group,
                                    ])->getAllWithIndex('attr_name');
                                if (!empty($row2)) {
                                    foreach ($bind_value as $key => $val) {
                                        if (!empty($row[$val['attr_id']])) {
                                            $val['attr_id'] = $row2[$row[$val['attr_id']]['attr_name']]['attr_value'];
                                        }
                                        $bind_value[$key] = $val;
                                    }
                                }
                            }
                        } else { // 獲取關聯繫結的產品屬性ID
                            $attr_name = 'attr_'.$bind_value;
                            $bind_value = Db::name('language_attr')->where([
                                    'attr_name'    => $attr_name,
                                    'lang'  => $lang,
                                    'attr_group' => $attr_group,
                                ])->getField('attr_value');
                            empty($bind_value) && $bind_value = '';
                        }
                    }
                    break;

                case 'guestbook_attribute':
                    {
                        if (is_array($bind_value)) {
                            !empty($langvar) && $lang = $langvar;
                            $row = Db::name('language_attr')->field('attr_name, attr_value')
                                ->where([
                                    'attr_value'    => ['IN', get_arr_column($bind_value, 'attr_id')],
                                    'attr_group' => $attr_group,
                                ])->getAllWithIndex('attr_value');

                            if (!empty($row)) {
                                $row2 = Db::name('language_attr')->field('attr_name, attr_value')
                                    ->where([
                                        'attr_name' => ['IN', get_arr_column($row, 'attr_name')],
                                        'lang'  => $lang,
                                        'attr_group' => $attr_group,
                                    ])->getAllWithIndex('attr_name');
                                if (!empty($row2)) {
                                    foreach ($bind_value as $key => $val) {
                                        if (!empty($row[$val['attr_id']])) {
                                            $val['attr_id'] = $row2[$row[$val['attr_id']]['attr_name']]['attr_value'];
                                        }
                                        $bind_value[$key] = $val;
                                    }
                                }
                            }
                        } else { // 獲取關聯繫結的留言屬性ID
                            $attr_name = 'attr_'.$bind_value;
                            $bind_value = Db::name('language_attr')->where([
                                    'attr_name'    => $attr_name,
                                    'lang'  => $lang,
                                    'attr_group' => $attr_group,
                                ])->getField('attr_value');
                            empty($bind_value) && $bind_value = '';
                        }
                    }
                    break;

                case 'ad_position':
                    {
                        if (!is_array($bind_value)) {// 獲取關聯繫結的廣告位置ID
                            $attr_name = 'adp'.$bind_value;
                            $bind_value = Db::name('language_attr')->where([
                                    'attr_name'    => $attr_name,
                                    'lang'  => $lang,
                                    'attr_group' => $attr_group,
                                ])->getField('attr_value');
                            empty($bind_value) && $bind_value = '';
                        }
                    }
                    break;

                case 'ad':
                    {
                        if (!is_array($bind_value)) {// 獲取關聯繫結的廣告ID
                            $attr_name = 'ad'.$bind_value;
                            $bind_value = Db::name('language_attr')->where([
                                    'attr_name'    => $attr_name,
                                    'lang'  => $lang,
                                    'attr_group' => $attr_group,
                                ])->getField('attr_value');
                            empty($bind_value) && $bind_value = '';
                        }
                    }
                    break;
                
                default:
                    # code...
                    break;
            }
        }

        return $bind_value;
    }

    /**
     * 獲取關聯繫結的主語言的變數值
     * @param string|array $bind_value 繫結之前的值，或者繫結之後的值
     * @param string $group 分組
     */
    public function getBindMainValue($bind_value = '', $attr_group = 'arctype')
    {
        /*單語言情況下不執行多語言程式碼*/
        if (!is_language()) {
            return $bind_value;
        }
        /*--end*/

        $main_lang = get_main_lang();

        if (!empty($bind_value)) {
            switch ($attr_group) {
                case 'ad':
                    {
                        if (!is_array($bind_value)) {// 獲取關聯繫結的廣告ID
                            $attr_name = Db::name('language_attr')->where([
                                    'attr_value'    => $bind_value,
                                    'attr_group'    => $attr_group,
                                ])->getField('attr_name');
                            var_dump($bind_value);exit;
                            $attr_value = Db::name('language_attr')->where([
                                    'attr_name'    => $attr_name,
                                    'lang'  => $main_lang,
                                    'attr_group' => $attr_group,
                                ])->getField('attr_value');
                            !empty($attr_value) && $bind_value = $attr_value;
                        }
                    }
                    break;
                
                default:
                    # code...
                    break;
            }
        }

        return $bind_value;
    }
}