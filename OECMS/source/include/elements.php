<?php
/**
 * [OECMS] (C)2012-2099 OEdev,Inc.
 * <E-Mail：phpcoo@qq.com>
 * Url www.oedev.net, www.phpcoo.com
 * Update 2013.01.28
*/
if(!defined('IN_OECMS')) {
	exit('Access Denied');
}
function auth_elements () {
	$elements[] = null;
    
	$elements[0] = array(
		'id'=>'em_1',
		'name'=>'基础设置',
		'value'=>array(
			'setting'=>'系统设置',
		)
	);
    
    $elements[1] = array(
        'id'=>'em_2',
        'name'=>'管理权限',
        'value'=>array(
            'admin_volist'=>'管理员列表',
            'admin_add'=>'添加管理员',
            'admin_edit'=>'修改管理员',
            'admin_del'=>'删除管理员',
            'admin_editpassword'=>'修改密码',
            
            'authgroup_volist'=>'权限组列表',
            'authgroup_add'=>'添加权限组',
            'authgroup_edit'=>'修改权限组',
            'authgroup_del'=>'删除权限组',
        )
    );
    
    $elements[2] = array(
        'id'=>'em_3',
        'name'=>'其他设置',
        'value'=>array(
            'htmllabel_volist'=>'HTML标签列表',
            'htmllabel_add'=>'添加HTML标签',
            'htmllabel_edit'=>'编辑HTML标签',
            'htmllabel_del'=>'删除HTML标签',

            'deliverycorp_volist'=>'物流公司列表',
            'deliverycorp_add'=>'添加物流公司',
            'deliverycorp_edit'=>'修改物流公司',
            'deliverycorp_del'=>'删除物流公司',
            
            'shiptype_volist'=>'配送方式列表',
            'shiptype_add'=>'添加配送方式',
            'shiptype_edit'=>'修改配送方式',
			'shiptype_del'=>'删除配送方式',
            
            'relatedlink_volist'=>'关联链接列表',
            'relatedlink_add'=>'添加关联链接',
            'relatedlink_edit'=>'修改关联链接',
            'relatedlink_del'=>'删除关联链接',
            
            'zone_volist'=>'广告版位列表',
            'zone_add'=>'添加广告版位',
            'zone_edit'=>'编辑广告版位',
            'zone_del'=>'删除广告版位',
            
            'myads_volist'=>'广告列表',
            'myads_add'=>'添加广告',
            'myads_edit'=>'编辑广告',
            'myads_del'=>'删除广告',

        )
    );
    
    $elements[3] = array(
        'id'=>'em_4',
        'name'=>'模块管理',
        'value'=>array(
            'category_volist'=>'栏目列表',
            'category_add'=>'添加栏目',
            'category_edit'=>'修改栏目',
            'category_del'=>'删除栏目',
            
            'module_volist'=>'模型列表',
            'module_edit'=>'修改模型',

            'modattr_volist'=>'模型字段列表',
            'modattr_add'=>'添加模型字段',
            'modattr_edit'=>'修改模型字段',
            'modattr_del'=>'删除模型字段',
        )
    );
    
    $elements[4] = array(
        'id'=>'em_5',
        'name'=>'产品管理',
        'value'=>array(
            'product_volist'=>'产品列表',
            'product_add'=>'添加产品',
            'product_edit'=>'修改产品',
            'product_del'=>'删除产品',
        )
    );
    
    $elements[5] = array(
        'id'=>'em_6',
        'name'=>'文章管理',
        'value'=>array(
            'article_volist'=>'文章列表',
            'article_add'=>'添加文章',
            'article_edit'=>'修改文章',
            'article_del'=>'删除文章',
        )
    );
    
    $elements[6] = array(
        'id'=>'em_7',
        'name'=>'图库管理',
        'value'=>array(
            'photo_volist'=>'图库列表',
            'photo_add'=>'添加图库',
            'photo_edit'=>'修改图库',
            'photo_del'=>'删除图库',

        )
    );
    
    $elements[7] = array(
        'id'=>'em_8',
        'name'=>'下载管理',
        'value'=>array(
            'download_volist'=>'下载列表',
            'download_add'=>'添加下载',
            'download_edit'=>'修改下载',
            'download_del'=>'删除下载',
        )
    );
    
    $elements[8] = array(
        'id'=>'em_9',
        'name'=>'招聘管理',
        'value'=>array(
            'hr_volist'=>'招聘列表',
            'hr_add'=>'添加招聘',
            'hr_edit'=>'修改招聘',
            'hr_del'=>'删除招聘',
        )
    );
    
    $elements[9] = array(
        'id'=>'em_10',
        'name'=>'单页管理',
        'value'=>array(
            'about_volist'=>'单页列表',
            'about_add'=>'添加单页',
            'about_edit'=>'修改单页',
            'about_del'=>'删除单页',
        )
    );
    
    $elements[10] = array(
        'id'=>'em_11',
        'name'=>'会员管理',
        'value'=>array(
            'usergroup_volist'=>'会员组列表',
            'usergroup_add'=>'添加会员组',
            'usergroup_edit'=>'修改会员组',
            'usergroup_del'=>'删除会员组',
            
            'user_volist'=>'会员列表',
            'user_add'=>'添加会员',
            'user_edit'=>'修改会员',
            'user_del'=>'删除会员',
            
            'comment_volist'=>'评论列表',
            'comment_edit'=>'修改评论',
            'comment_del'=>'删除评论',

            'guestbook_volist'=>'留言列表',
            'guestbook_edit'=>'修改留言',
            'guestbook_del'=>'删除留言',

			'money_volist'=>'现金列表',
			'money_add'=>'添加现金',
			

        )
    );
    
    $elements[11] = array(
        'id'=>'em_12',
        'name'=>'模板界面',
        'value'=>array(
            'skin_volist'=>'风格列表',
			'skin_edit'=>'切换风格',
			'skin_del'=>'删除风格',

			'templet_volist'=>'模板文件列表',
            'templet_edit'=>'修改模板文件',
            'templet_del'=>'删除模板文件',

        )
    );
    
    $elements[12] = array(
        'id'=>'em_13',
        'name'=>'应用扩展',
        'value'=>array(
            'log_volist'=>'系统日志列表',
			'log_del'=>'删除系统日志',
            'plugin'=>'插件管理',
			'upload'=>'上传图片/附件',
        )
    );
    
    $elements[13] = array(
        'id'=>'em_14',
        'name'=>'订单管理',
        'value'=>array(
            'order_volist'=>'订单列表',
            'order_add'=>'添加订单',
            'order_edit'=>'编辑订单',
            'order_del'=>'删除订单',
			'order_manage'=>'操作订单',
			'order_appinvoice'=>'申请开票',
			
            'payment_volist'=>'收款单列表',
            'payment_add'=>'添加收款单',
            'payment_view'=>'查看收款单',
            'payment_del'=>'删除收款单',
            
            'refund_volist'=>'退款单列表',
            'refund_add'=>'添加退款单',
            'refund_view'=>'查看退款单',
            'refund_del'=>'删除退款单',
            
            'delivery_volist'=>'发货单列表',
            'delivery_add'=>'添加发货单',
            'delivery_view'=>'查看发货单',
            'delivery_del'=>'删除发货单',

            'return_volist'=>'退货货单列表',
            'return_add'=>'添加退货单',
            'returny_view'=>'查看退货单',
            'return_del'=>'删除退货单',
        )
    );
    
    $elements[14] = array(
        'id'=>'em_15',
        'name'=>'财务管理',
        'value'=>array(
            'account_volist'=>'财务帐户列表',
			'account_add'=>'添加财务帐户',
			'account_edit'=>'修改财务帐户',
		    'account_del'=>'删除财务帐户',
            
            'paramter_volist'=>'财务参数列表',
			'paramter_add'=>'添加财务参数',
			'paramter_edit'=>'修改财务参数',
		    'paramter_del'=>'删除财务参数',
            
            'finance_volist'=>'财务收支',
            'finance_add'=>'添加财务收支',
            'finance_edit'=>'修改财务收支',
            'finance_del'=>'删除财务收支',
			'finance_view'=>'查看财务收支',

            'invoice_volist'=>'开票列表',
            'invoice_open'=>'开发票',
            'invoice_view'=>'查看发票',
			'invoice_edit'=>'修改发票',
			'invoice_del'=>'删除发票',

        )
    );
        
    return $elements;

}

/**
 * 获取所有权限
 * @return 一维数组
*/ 
function auth_get_em_array() {
	$emarray = array();
	$em_list = auth_elements();
	foreach ($em_list as $key=>$value) {
		if (!empty($emarray)) {
			$emarray = array_merge($emarray, $value['value']);
		}
		else {
			$emarray = $value['value'];
		}
	}
	return $emarray;
}
?>