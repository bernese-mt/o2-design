<?php /* Smarty version Smarty-3.0.5, created on 2019-05-07 15:40:25
         compiled from "/home/o2design/public_html/OECMS/tpl/admincp/frm_menu.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2906314745cd136693ab414-47319961%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '54225f5b844023af96e2f46f9f54beb64dad8b5f' => 
    array (
      0 => '/home/o2design/public_html/OECMS/tpl/admincp/frm_menu.tpl',
      1 => 1557211191,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2906314745cd136693ab414-47319961',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="<?php echo $_smarty_tpl->getVariable('page_charset')->value;?>
">
<title>menu</title>
<link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('cppath')->value;?>
css/menu.css" type="text/css" /> 
<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('urlpath')->value;?>
tpl/static/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('cppath')->value;?>
js/menu.js"></script>
</head>
<body style="overflow-x:hidden;">
<div id="accordion2">
  
  <?php if ($_smarty_tpl->getVariable('mod')->value=='setting'){?>
  <div class="switchable-trigger" onClick="menu('label_1');"><i id='label_1_css' class="moo-icon-2"></i><h3>基础设置</h3></div>
  <ul class="switchable-panel" id='label_1'>
    <li>
	  <a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=frame&a=main" target='main'>系统信息</a>&nbsp;&nbsp;
	  <a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=setting" target='main'>站点设置</a>
	</li>
	<li>
	  <a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=setting&a=footer" target='main'>底部信息</a>&nbsp;&nbsp;
	  <a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=setting&a=upload" target='main'>图片设置</a>
    </li>
	<li>
	  <a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=seo" target='main'>SEO设置</a>&nbsp;&nbsp;
	  <a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=setting&a=rewrite" target='main'>伪静态设置</a>
	</li>
	<li>
	  
    </li>
  </ul>

  <div class="switchable-trigger" onClick="menu('label_2');"><i id='label_2_css' class="moo-icon-2"></i><h3>管理员帐号</h3></div>
  <ul class="switchable-panel" id='label_2' style="display:;">
    <li>
	  <a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=admin" target='main'>帐号列表</a>&nbsp;&nbsp;
	  <a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=admin&a=add" target='main'>添加管理员</a>
	</li>
	<li>
	  <a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=admin&a=editpassword">修改密码</a>
    </li>
  </ul>

  <div class="switchable-trigger last-trigger" onClick="menu('label_4');"><i id='label_4_css' class="moo-icon-2"></i><h3>其他设置</h3></div>
  <ul class="switchable-panel last-pannel" id='label_4' style="display:;">
    <li>
	  <a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=htmllabel" target='main'>自定义HTML标签</a>
	</li>
	<li>
	  <a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=zone" target='main'>广告版位</a>&nbsp;&nbsp;
	  <a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=myads" target='main'>广告管理</a>
    </li>
	<li style="display:none;">
	  <a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=relatedlink" target='main'>关联链接设置</a>
	</li>
  </ul>
  <?php }?>

  <?php if ($_smarty_tpl->getVariable('mod')->value=='skin'){?>
  <div class="switchable-trigger" onClick="menu('label_1');"><i id='label_1_css' class="moo-icon-2"></i><h3>主题模板</h3></div>
  <ul class="switchable-panel" id='label_1'>
    <li>
	  <a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=skin" target='main'>设置模板</a>
	</li>
	<li>
	  <a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=setting&a=index_style" target='main'>首页配置</a>
    </li>
	<li>
	  <a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=setting&a=main_style" target='main'>界面配置</a>
	</li>
  </ul>

  <div class="switchable-trigger" onClick="menu('label_2');"><i id='label_2_css' class="moo-icon-2"></i><h3>模板管理</h3></div>
  <ul class="switchable-panel" id='label_2' style="display:;">
    <li><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=templet">模板文件</a></li>
  </ul>
  <?php }?>

  <?php if ($_smarty_tpl->getVariable('mod')->value=='app'){?>
  <div class="switchable-trigger" onClick="menu('label_1');"><i id='label_1_css' class="moo-icon-2"></i><h3>扩展应用</h3></div>
  <ul class="switchable-panel" id='label_1'>
	<li>
	  <a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=log" target='main'>系统操作日志</a>
    </li>
	<li>
	  <a href='<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=setting&a=clearcache'>清除页面缓存</a>
	</li>
	<li>
	  <a href='<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=setting&a=updatecache'>更新数据缓存</a>
	</li>
    <li>
	  <a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=guestbook" target='main'>留言列表</a>
	</li>
  </ul>

  <div class="switchable-trigger" onClick="menu('label_2');"><i id='label_2_css' class="moo-icon-2"></i><h3>插件中心</h3></div>
  <ul class="switchable-panel" id='label_2' style="display:;">
    <li><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=plugin" target='main'>插件列表</a></li>
	<?php $_smarty_tpl->tpl_vars['plugin_data'] = new Smarty_variable(XHook::doAction('adm_sidebar_ext'), null, null);?>
  </ul>
  <?php }?>

  
  <?php if ($_smarty_tpl->getVariable('mod')->value=='content'){?>

  <div class="switchable-trigger" onClick="menu('label_1');"><i id='label_1_css' class="moo-icon-2"></i><h3>栏目管理</h3></div>
  <ul class="switchable-panel" id='label_1' style="display:;">
    <li><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=category">栏目设置</a> <a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=module">模块类型</a> </li>
  </ul>

  <?php if (!empty($_smarty_tpl->getVariable('category')->value)){?>
  <?php  $_smarty_tpl->tpl_vars['volist'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('category')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['volist']->key => $_smarty_tpl->tpl_vars['volist']->value){
?>

  <?php if ($_smarty_tpl->tpl_vars['volist']->value['modalias']=='about'){?>
  <div class="switchable-trigger" onClick="menu('model_<?php echo $_smarty_tpl->tpl_vars['volist']->value['catid'];?>
');"><i id='model_<?php echo $_smarty_tpl->tpl_vars['volist']->value['catid'];?>
_css' class="moo-icon-2"></i><h3><?php echo $_smarty_tpl->tpl_vars['volist']->value['catname'];?>
</h3></div>
  <ul class="switchable-panel" id='model_<?php echo $_smarty_tpl->tpl_vars['volist']->value['catid'];?>
'>
    <?php if (!empty($_smarty_tpl->tpl_vars['volist']->value['child'])){?>
	<?php  $_smarty_tpl->tpl_vars['mychild'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['volist']->value['child']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['mychild']->key => $_smarty_tpl->tpl_vars['mychild']->value){
?>
    <li>&nbsp;&nbsp;&nbsp;<a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=about&a=edit&catid=<?php echo $_smarty_tpl->tpl_vars['mychild']->value['catid'];?>
&id=<?php echo $_smarty_tpl->tpl_vars['mychild']->value['abid'];?>
"><?php echo $_smarty_tpl->tpl_vars['mychild']->value['catname'];?>
</a></li>
	<?php }} ?>
	<?php }?>
  </ul>
  <?php }else{ ?>
  <div class="switchable-trigger" onClick="menu('model_<?php echo $_smarty_tpl->tpl_vars['volist']->value['catid'];?>
');"><i id='model_<?php echo $_smarty_tpl->tpl_vars['volist']->value['catid'];?>
_css' class="moo-icon-2"></i><h3><?php echo $_smarty_tpl->tpl_vars['volist']->value['catname'];?>
</h3></div>
  <ul class="switchable-panel" id='model_<?php echo $_smarty_tpl->tpl_vars['volist']->value['catid'];?>
'>
    <li><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=modattr&smodule=<?php echo $_smarty_tpl->tpl_vars['volist']->value['modalias'];?>
&streeid=<?php echo $_smarty_tpl->tpl_vars['volist']->value['catid'];?>
">模块字段</a> <a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=modattr&a=add&smodule=<?php echo $_smarty_tpl->tpl_vars['volist']->value['modalias'];?>
&streeid=<?php echo $_smarty_tpl->tpl_vars['volist']->value['catid'];?>
">添加字段</a></li>
    <li><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=<?php echo $_smarty_tpl->tpl_vars['volist']->value['modalias'];?>
&tid=<?php echo $_smarty_tpl->tpl_vars['volist']->value['catid'];?>
">内容列表</a> <a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=<?php echo $_smarty_tpl->tpl_vars['volist']->value['modalias'];?>
&a=add&tid=<?php echo $_smarty_tpl->tpl_vars['volist']->value['catid'];?>
">添加内容</a></li>
  </ul>
  <?php }?>
  <?php }} ?>
  <?php }?>


  <?php }?>

</div>
</body>
</html>
<script language="javascript">
function menu(id) {
	if ($("#"+id).css("display") == "none") {
		$("#"+id).css("display","block");
		$("#"+id+'_css').removeClass('moo-icon-1');
		$("#"+id+'_css').addClass('moo-icon-2');
	} else {
		$("#"+id).css("display","none");
		$("#"+id+'_css').removeClass('moo-icon-2');
		$("#"+id+'_css').addClass('moo-icon-1');
	}
}
</script>
