<?php /* Smarty version Smarty-3.0.5, created on 2019-05-07 15:41:19
         compiled from "/home/o2design/public_html/OECMS/tpl/templets/default/block_top.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1654792615cd1369f843700-70405787%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd907767303d475cbcc829c76d1eb638ad6cc3f3e' => 
    array (
      0 => '/home/o2design/public_html/OECMS/tpl/templets/default/block_top.tpl',
      1 => 1557211262,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1654792615cd1369f843700-70405787',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
  <div id="top">
	<div class="sidebar">
	  <div class="lang">
        <a href="###" onclick="addfavorite('<?php echo $_smarty_tpl->getVariable('config')->value['sitename'];?>
', '<?php echo $_smarty_tpl->getVariable('config')->value['siteurl'];?>
');">添加收藏</a>
        <span>|</span>
	    <a href="###" onclick="sethomepage(this, window.location);">设为主页</a>
	  </div>
	  <h1><?php echo hook_get_label(array('name'=>'toptips'),$_smarty_tpl);?>
</h1>
	</div>		 
	<a><img src="<?php echo $_smarty_tpl->getVariable('config')->value['logo'];?>
" style="margin-top:5px; margin-left:5px;"/></a>
  </div><!-- #top //-->
  
  <div id="head">		 
    <ul id="nav" style=" width:780px;">
	  <li class="home"></li>
	  <?php $_smarty_tpl->tpl_vars['mymenu'] = new Smarty_variable(vo_category("type={sedmenu}"), null, null);?>
	  <?php  $_smarty_tpl->tpl_vars['parent'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('mymenu')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['parent']->key => $_smarty_tpl->tpl_vars['parent']->value){
?>
	  <li class="class1" id="nav_<?php echo $_smarty_tpl->tpl_vars['parent']->value['catid'];?>
">
	  <?php if ($_smarty_tpl->tpl_vars['parent']->value['url']==''){?>
	  <a><?php echo $_smarty_tpl->tpl_vars['parent']->value['catname'];?>
</a>
	  <?php }else{ ?>
	  <a href="<?php echo $_smarty_tpl->tpl_vars['parent']->value['url'];?>
"><?php echo $_smarty_tpl->tpl_vars['parent']->value['catname'];?>
</a>
	  <?php }?>
	  <?php if (!empty($_smarty_tpl->tpl_vars['parent']->value['childmenu'])){?>
	    <ul style="display:none;">
		  <?php  $_smarty_tpl->tpl_vars['child'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['parent']->value['childmenu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['child']->key => $_smarty_tpl->tpl_vars['child']->value){
?>
	      <li><a href="<?php echo $_smarty_tpl->tpl_vars['child']->value['url'];?>
"><?php echo $_smarty_tpl->tpl_vars['child']->value['catname'];?>
</a></li>
		  <?php }} ?>
	    </ul>
	  <?php }?>
	  </li>
	  <li class="line"></li> 
	  <?php }} ?>
	</ul>
    
	<form method="post" action="<?php echo $_smarty_tpl->getVariable('urlpath')->value;?>
index.php?c=search" name="myform" id="myform" onsubmit="return checksearch();" />
	<div class="search">
	  <h3></h3>
	  <ul>
	    <li>
		<select name="type" id="type">
		<option value="product"<?php if ($_smarty_tpl->getVariable('type')->value=='product'){?> selected<?php }?>>&nbsp;产品&nbsp;</option>
		<option value="photo"<?php if ($_smarty_tpl->getVariable('type')->value=='photo'){?> selected<?php }?>>&nbsp;图库&nbsp;</option>
		<option value="article"<?php if ($_smarty_tpl->getVariable('type')->value=='article'){?> selected<?php }?>>&nbsp;文章&nbsp;</option>
		<option value="download"<?php if ($_smarty_tpl->getVariable('type')->value=='download'){?> selected<?php }?>>&nbsp;下载&nbsp;</option>
		<option value="hr"<?php if ($_smarty_tpl->getVariable('type')->value=='hr'){?> selected<?php }?>>&nbsp;招聘&nbsp;</option>
		</select>
		<span class='parasearch_input'><input type='text' name='keyword' id="keyword" value="<?php echo $_smarty_tpl->getVariable('keyword')->value;?>
" /></span>
		</li>
		<li><span class='parasearch_search'><input class='searchimage' type='image' src='<?php echo $_smarty_tpl->getVariable('skinpath')->value;?>
images/navserach.gif' /></span></li>
	  </ul>
	</div>
	</form>
	<div class="clear"></div>
  </div>
<script language="javascript">
function checksearch(){
	if($("#type").val()==""){
		alert("请选择搜索频道.");
		return false;
	}
	if($("#keyword").val()==""){
		alert("关键字不能为空.");
		return false;
	}	
}
</script>