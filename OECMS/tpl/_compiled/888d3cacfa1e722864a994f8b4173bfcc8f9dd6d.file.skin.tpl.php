<?php /* Smarty version Smarty-3.0.5, created on 2019-05-07 15:40:32
         compiled from "/home/o2design/public_html/OECMS/tpl/admincp/skin.tpl" */ ?>
<?php /*%%SmartyHeaderCode:10530219835cd136705c57b2-79452890%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '888d3cacfa1e722864a994f8b4173bfcc8f9dd6d' => 
    array (
      0 => '/home/o2design/public_html/OECMS/tpl/admincp/skin.tpl',
      1 => 1557211184,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '10530219835cd136705c57b2-79452890',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $_smarty_tpl->getVariable('page_charset')->value;?>
" />
<title>主题模板</title>
<?php $_template = new Smarty_Internal_Template(($_smarty_tpl->getVariable('cptplpath')->value)."headerjs.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>
<?php $_smarty_tpl->tpl_vars['pluginevent'] = new Smarty_variable(XHook::doAction('adm_head'), null, null);?>
</head>
<body>
<?php $_smarty_tpl->tpl_vars['pluginevent'] = new Smarty_variable(XHook::doAction('adm_main_top'), null, null);?>
<?php if ($_smarty_tpl->getVariable('a')->value=="run"){?>
<div class="main-wrap">
  <div class="path"><p>当前位置：界面模板<span>&gt;&gt;</span>主题模板</p></div>
  <div class="main-cont">
    <h3 class="title"><a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=skin&a=style" class="btn-general"><span>配置界面风格</span></a>正在使用的主题模板</h3>

    <table cellspacing="10" cellpadding="0" width="80%" border="0">
      <tr>
        <td width="27%">
		<img src="<?php echo $_smarty_tpl->getVariable('usingskin')->value['preview'];?>
" width="240" height="180"  border="1" />	  
		</td>
		<td width="73%" style="line-height:25px;">
	    <?php echo $_smarty_tpl->getVariable('usingskin')->value['tplname'];?>
 <em><?php echo $_smarty_tpl->getVariable('usingskin')->value['version'];?>
</em><br>
	    <?php echo $_smarty_tpl->getVariable('usingskin')->value['author'];?>
<br>
	    <?php echo $_smarty_tpl->getVariable('usingskin')->value['forversion'];?>
<br>
		<?php echo $_smarty_tpl->getVariable('usingskin')->value['lastupdate'];?>
<br />
	    <p><?php echo $_smarty_tpl->getVariable('usingskin')->value['desc'];?>
</p>
	    </td>
      </tr>
    </table>

	<h3 class="title">主题模板库</h3>
	
	<table cellspacing="10" cellpadding="5" width="80%" border="0">
	  <tr>      
	    
		<?php  $_smarty_tpl->tpl_vars['volist'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('skin')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['volist']->key => $_smarty_tpl->tpl_vars['volist']->value){
?>
		<td align="center" width="33%" style='line-height:28px;border-bottom:1px dashed #999999;'>
		  <a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=skin&a=usetpl&id=<?php echo $_smarty_tpl->tpl_vars['volist']->value['tpldir'];?>
" onClick="{if(confirm('确定使用[<?php echo $_smarty_tpl->tpl_vars['volist']->value['tplname'];?>
]模板？')){return true;} return false;}">
		  <img alt="点击使用该模板" src="<?php echo $_smarty_tpl->tpl_vars['volist']->value['preview'];?>
" width="180" height="150" border="0" /></a><br />
		  <?php echo $_smarty_tpl->tpl_vars['volist']->value['tplname'];?>
<span>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=skin&a=del&id=<?php echo $_smarty_tpl->tpl_vars['volist']->value['tpldir'];?>
" onClick="{if(confirm('确定要删除该模板吗？一旦删除无法恢复！')){return true;} return false;}">删除</a></span>
        </td>

		<?php if ($_smarty_tpl->tpl_vars['volist']->value['i']%3==0){?>
		</tr>
		<tr>
		<?php }?>
		<?php }} ?>
	  
	  </tr>
	</table>



  </div>
</div>
<?php }?>

<?php $_smarty_tpl->tpl_vars['pluginevent'] = new Smarty_variable(XHook::doAction('adm_footer'), null, null);?>
</body>
</html>
