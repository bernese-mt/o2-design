<?php
# MetInfo Enterprise Content Management System
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.

defined('IN_MET') or exit('No permission');

load::sys_class('web');

class demo extends web {
    public function __construct() {
        global $_M;
        parent::__construct();
    }

    public function doindex()
    {
        global $_M;
        $lang = $_M['form']['lang'];
        $this->doGetDemodata($lang);
    }

    public function doGetDemodata($lang = '')
    {
        global $_M;
        $lang = $lang ? $lang : $_M['lang'];
        $sql = "SELECT * FROM {$_M['table']['templates']} WHERE no = 'metv6s' AND lang = '{$lang}'";
        $data = DB::get_all($sql);
        $str = '';
        foreach ($data as $val) {
            $str .= "INSERT INTO met_templates VALUES ({$val['id']}, 'metv6s', {$val['pos']}, {$val['no_order']}, {$val['type']},{$val['style']}, '{$val['selectd']}', '{$val['name']}', '{$val['value']}', '{$val['defaultvalue']}', '{$val['valueinfo']}', '{$val['tips']}', '{$val['lang']}', {$val['bigclass']});\n";
        }
        dump($str);
        file_put_contents(__DIR__ . '/templates.sql', $str);

    }
}

# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>
