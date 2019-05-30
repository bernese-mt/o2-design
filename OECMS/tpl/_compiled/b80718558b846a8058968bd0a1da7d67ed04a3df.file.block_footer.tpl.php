<?php /* Smarty version Smarty-3.0.5, created on 2019-05-07 15:41:19
         compiled from "/home/o2design/public_html/OECMS/tpl/templets/default/block_footer.tpl" */ ?>
<?php /*%%SmartyHeaderCode:11559731005cd1369f8f75b7-63865694%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b80718558b846a8058968bd0a1da7d67ed04a3df' => 
    array (
      0 => '/home/o2design/public_html/OECMS/tpl/templets/default/block_footer.tpl',
      1 => 1557211257,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11559731005cd1369f8f75b7-63865694',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
  <div id="footer">
    <div class="nav">
	<?php $_smarty_tpl->tpl_vars['submenu'] = new Smarty_variable(vo_category("type={submenu}"), null, null);?>
	<?php  $_smarty_tpl->tpl_vars['volist'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('submenu')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['volist']->key => $_smarty_tpl->tpl_vars['volist']->value){
?>
	<a href="<?php echo $_smarty_tpl->tpl_vars['volist']->value['url'];?>
"><?php echo $_smarty_tpl->tpl_vars['volist']->value['catname'];?>
</a>&nbsp;&nbsp;
	<?php }} ?>
	</div>
	<div class="text">
	  <div class="powered_by_oecms">
	    <?php echo $_smarty_tpl->getVariable('config')->value['site_footer'];?>
<br />
		<?php if ($_smarty_tpl->getVariable('config')->value['icpcode']!=''){?><?php echo $_smarty_tpl->getVariable('config')->value['icpcode'];?>
<?php }?> <?php echo $_smarty_tpl->getVariable('copyright_poweredby')->value;?>
&nbsp;&nbsp;
		&nbsp;&nbsp;<?php if ($_smarty_tpl->getVariable('config')->value['tjcode']!=''){?><?php echo $_smarty_tpl->getVariable('config')->value['tjcode'];?>
<?php }?>
	  </div>
	</div>
  </div>
<?php $_smarty_tpl->tpl_vars['pluginevent'] = new Smarty_variable(XHook::doAction('event_runtime'), null, null);?>
<?php $_smarty_tpl->tpl_vars['pluginevent'] = new Smarty_variable(XHook::doAction('event_online'), null, null);?>