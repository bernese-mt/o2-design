<?php
/**
 * [OECMS] (C)2012-2099 OEdev,Inc.
 * <E-Mail：phpcoo@qq.com>
 * Url www.oedev.net, www.phpcoo.com
 * Update 2013.02.05
*/
if(!defined('IN_OECMS')) {
	exit('Access Denied');
}
class XMod extends X{
    
    
    public static function checkBox($value, $name, $text='') {
		$temp = "<input type='checkbox' id='$name' name='$name' value='1'";
		if($value=="1"){
			$temp .=" checked";
		}
		$temp .= ">$text";
		return $temp; 
    }
    
     
    public static function selecAuthGroup($value, $name, $text='请选择') {
        $temps = '';
        $temps .= "<select name='$name' id='$name'>";
        $temps .= "<option value=''>$text</option>";
        $option = parent::model('authgroup', 'am');
        
        $temps .= $option->getCacheOptions($value);
        unset($option);           
        $temps .= "</select>";
        return $temps;
    }
    
     
    public static function selectZone($value, $name, $text='请选择') {
        $temps = '';
        $temps .= "<select name='$name' id='$name'>";
        $temps .= "<option value=''>$text</option>";
        
        $model = parent::model('zone', 'am');
        $temps .= $model->getZoneOption(intval($value));
        unset($model);
                 
        $temps .= "</select>";
        return $temps;
    }
    
     
    public static function selectModule($value, $name, $text='请选择') {
        $temps = '';
        $temps .= "<select name='$name' id='$name'>";
        $temps .= "<option value=''>$text</option>";
        
        $model = parent::model('module', 'am');
        $temps .= $model->getModuleOption(trim($value));
        unset($model);
                 
        $temps .= "</select>";
        return $temps;
    }
    
     
    public static function selectListModule($value, $name, $text='请选择') {
        $temps = '';
        $temps .= "<select name='$name' id='$name'>";
        $temps .= "<option value=''>$text</option>";
        
        $model = parent::model('module', 'am');
        $temps .= $model->getListModuleOption(trim($value));
        unset($model);
                 
        $temps .= "</select>";
        return $temps;
    }
    
     
    public static function selectCategoryOneNode($nodeid, $value, $name, $text='请选择') {
        $temps = '';
        $temps .= "<select name='$name' id='$name'>";
        $temps .= "<option value=''>$text</option>";
        
        $model = parent::model('category', 'am');
        $temps .= $model->getOneNodeOption(intval($nodeid), $value);
        unset($model);
                 
        $temps .= "</select>";
        return $temps;
    }
    
     
    public static function selectCategoryRoot($module, $value, $name, $text='请选择') {
        $temps = '';
        $temps .= "<select name='$name' id='$name'>";
        $temps .= "<option value=''>$text</option>";
        
        $model = parent::model('category', 'am');
        $temps .= $model->getOptionRoot(trim($module), $value);
        unset($model);
                 
        $temps .= "</select>";
        return $temps;
    }
    
     
    public static function selectNodeEnableChilds($nodeid, $value, $name, $text='请选择', $css='disoptions') {
        $temps = '';
        $temps .= "<select name='{$name}' id='{$name}' class='{$css}'>";
        $temps .= "<option value=''>$text</option>";
        $model = parent::model('category', 'am');
        $temps .= $model->getNodeEnableChilds(intval($nodeid), $value);
        unset($model);    
        $temps .= "</select>";
        return $temps;
    }
    
    
    public static function attrRadioBox($name, $attrvalue, $invalue='') {
        $temps ='';
        if (!empty($attrvalue)) {
            $attrvalue = nl2br($attrvalue);
            $array = explode('<br />', $attrvalue);
            for($i=0;$i<count($array);$i++){
                $temps .= "<input type='radio' name='{$name}' id='{$name}' value='".trim($array[$i])."'";
                if (trim($invalue == trim($array[$i]))) {
                    $temps .= " checked";
                }
                $temps .= " />".$array[$i].'&nbsp;&nbsp;';
            }
        }
        return $temps;
    }
    
    
    public static function attrCheckBox($name, $attrvalue, $invalue='') {
        $temps ='';
        if (!empty($attrvalue)) {
            $attrvalue = nl2br($attrvalue);
            $array = explode('<br />', $attrvalue);
            for($i=0;$i<count($array);$i++){
                $temps .= "<input type='checkbox' name='{$name}[]' id='{$name}[]' value='".trim($array[$i])."'";
                if (!empty($invalue)) {
                    if (true === XHandle::foundInChar($invalue, trim($array[$i]), ',')) {
                        $temps .= " checked";
                    }
                }
                $temps .= " />".$array[$i].'&nbsp;&nbsp;';
            }
        }
        return $temps;
    }
    
    
    public static function attrOptions($name, $attrvalue, $invalue='') {
        $temps ="<select name='{$name}' id='{$name}'>";
        if (!empty($attrvalue)) {
            $attrvalue = nl2br($attrvalue);
            $array = explode('<br />', $attrvalue);
            for($i=0;$i<count($array);$i++){
                $temps .= "<option value='".trim($array[$i])."'";
                if (trim($invalue == trim($array[$i]))) {
                    $temps .= " selected";
                }
                $temps .= ">".$array[$i].'</option>';
            }
        }
        $temps .= "</select>";
        return $temps;
    }
    
    
     
    public static function selectUserGroup($value, $name, $text='请选择') {
        $temps = '';
        $temps .= "<select name='$name' id='$name'>";
        $temps .= "<option value=''>$text</option>";
        $m = parent::model('usergroup', 'am');
        $temps .= $m->getOptions(intval($value));
        unset($m);
        $temps .= "</select>";
        return $temps;
    }


     
    public static function selectShipType($value, $name, $text='请选择') {
        $temps = '';
        $temps .= "<select name='$name' id='$name'>";
        $temps .= "<option value=''>$text</option>";
        $m = parent::model('shiptype', 'am');
        $temps .= $m->getOptions(intval($value));
        unset($m);
        $temps .= "</select>";
        return $temps;
    }
    
     
    public static function selectAccount($value, $name, $text='请选择') {
        $temps = '';
        $temps .= "<select name='$name' id='$name'>";
        $temps .= "<option value=''>$text</option>";
        $m = parent::model('account', 'am');
        $temps .= $m->getOptions(intval($value));
        unset($m);
        $temps .= "</select>";
        return $temps;
    }
    
     
    public static function selectParamter($value, $name, $type='in', $text='请选择') {
        $temps = '';
        $temps .= "<select name='$name' id='$name'>";
        $temps .= "<option value=''>$text</option>";
        $m = parent::model('paramter', 'am');
        $temps .= $m->getOptions(intval($value), $type);
        unset($m);
        $temps .= "</select>";
        return $temps;
    }
    
    
    public static function opLog($logtext, $type=1) {
        $array = array();
        if ($type == 1) {
            $array = array(
                'username'=>parent::$wrap_admin['adminname'],
            );
        }
        else {
            $array = array(
                'username'=>parent::$wrap_user['username'],
            );
        }
        $array = array_merge($array, array(
            'ip'=>XRequest::getip(),
            'content'=>$logtext,
            'logtype'=>$type,
            'timeline'=>time(),           
        ));
        $lg = parent::model('log', 'am');
        $lg->doAdd($array);
        unset($lg);
    }
    
    
    public static function selectTimezone($inputvalue, $name, $text='请选择') {
        $tzlist = array(
            '-12'=>'(标准时-12) 日界线西',
            '-11'=>'(标准时-11) 中途岛、萨摩亚群岛',
			'-10'=>'(标准时-10) 夏威夷',
			'-9'=>'(标准时-9) 阿拉斯加',
			'-8'=>'(标准时-8) 太平洋时间(美国和加拿大)',
			'-7'=>'(标准时-7) 山地时间(美国和加拿大)',
			'-6'=>'(标准时-6) 中部时间(美国和加拿大)、墨西哥城',
			'-5'=>'(标准时-5) 东部时间(美国和加拿大)、波哥大',
			'-4'=>'(标准时-4) 大西洋时间(加拿大)、加拉加斯',
			'-3.5'=>'(标准时-3:30) 纽芬兰',
			'-3'=>'(标准时-3) 巴西、布宜诺斯艾利斯、乔治敦',
			'-2'=>'(标准时-2) 中大西洋',
			'-1'=>'(标准时-1) 亚速尔群岛、佛得角群岛',
			'0'=>'(标准时) 西欧时间、伦敦、卡萨布兰卡',
			'1'=>'(标准时+1) 中欧时间、安哥拉、利比亚',
			'2'=>'(标准时+2) 东欧时间、开罗，雅典',
			'3'=>'(标准时+3) 巴格达、科威特、莫斯科',
			'3.5'=>'(标准时+3:30) 德黑兰',
			'4'=>'(标准时+4) 阿布扎比、马斯喀特、巴库',
			'4.5'=>'(标准时+4:30) 喀布尔',
			'5'=>'(标准时+5) 叶卡捷琳堡、伊斯兰堡、卡拉奇',
			'5.5'=>'(标准时+5:30) 孟买、加尔各答、新德里',
			'6'=>'(标准时+6) 阿拉木图、 达卡、新亚伯利亚',
			'7'=>'(标准时+7) 曼谷、河内、雅加达',
			'8'=>'(标准时+8) 北京、重庆、香港、新加坡',
			'9'=>'(标准时+9) 东京、汉城、大阪、雅库茨克',
			'9.5'=>'(标准时+9:30) 阿德莱德、达尔文',
			'10'=>'(标准时+10) 悉尼、关岛',
			'11'=>'(标准时+11) 马加丹、索罗门群岛',
			'12'=>'(标准时+12) 奥克兰、惠灵顿、堪察加半岛',
		);
        $options = '';
        $temps = '';
        $temps .= "<select name='$name' id='$name'>";
        $temps .= "<option value=''>$text</option>";
        foreach ($tzlist as $key=>$value) {
            $options .= "<option value='".$key."'";
            if (trim($inputvalue) == trim($key)) {
                $options .= ' selected';
            }
            $options .= ">".$value."</option>";
        }      
        $temps .= $options."</select>";
        return $temps;     
    }
	
}
?>
