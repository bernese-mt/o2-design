<?php /* Smarty version Smarty-3.0.5, created on 2019-05-07 15:41:19
         compiled from "/home/o2design/public_html/OECMS/tpl/templets/default/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:10012505995cd1369f68e650-20265226%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '464d590bf3de4ee18ca0f602fcc5e8f0c93e7c4e' => 
    array (
      0 => '/home/o2design/public_html/OECMS/tpl/templets/default/index.tpl',
      1 => 1557211253,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '10012505995cd1369f68e650-20265226',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
﻿<?php $_template = new Smarty_Internal_Template(($_smarty_tpl->getVariable('tplpath')->value)."block_header.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>
<div id="wrap">
  <?php $_template = new Smarty_Internal_Template(($_smarty_tpl->getVariable('tplpath')->value)."block_top.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>
	<?php $_smarty_tpl->tpl_vars['zone'] = new Smarty_variable(get_zone('index_slide_banner'), null, null);?>
	<?php if (!empty($_smarty_tpl->getVariable('zone')->value['ads'])){?>
	<script src="<?php echo $_smarty_tpl->getVariable('urlpath')->value;?>
tpl/static/js/jquery.KinSlideshow-1.2.1.min.js" type="text/javascript"></script>
	<script type="text/javascript">
	$(function(){
		$("#KinSlideshow").KinSlideshow({
				moveStyle:"left",
				intervalTime:5,
				mouseEvent:"mouseover",
				titleFont:{TitleFont_size:14,TitleFont_color:"#fff"},
				btn:{btn_bgColor:"#fff",btn_bgHoverColor:"#0088cd",
				btn_fontColor:"#333",btn_fontHoverColor:"#fff",btn_fontFamily:"Verdana",
				btn_borderColor:"#ddd",btn_borderHoverColor:"#efefef",
				btn_borderWidth:1,btn_bgAlpha:0.7},
				titleBar:{titleBar_height:40,titleBar_bgColor:"#5bafe1",titleBar_alpha:0.5}
		});
	})
	</script>
	<div id="KinSlideshow" style="visibility:hidden;"> 
	<?php  $_smarty_tpl->tpl_vars['volist'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('zone')->value['ads']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['volist']->key => $_smarty_tpl->tpl_vars['volist']->value){
?>
	<a href="<?php echo $_smarty_tpl->tpl_vars['volist']->value['url'];?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['volist']->value['uploadfiles'];?>
" width="<?php echo $_smarty_tpl->tpl_vars['volist']->value['width'];?>
" height="<?php echo $_smarty_tpl->tpl_vars['volist']->value['height'];?>
"  alt="<?php echo $_smarty_tpl->tpl_vars['volist']->value['title'];?>
" /></a>
	<?php }} ?>
	</div>
	<?php }?>

  <div id="main">

    <div id="left">

	  <h3 class="title">
	  <?php echo hook_get_category(array('id'=>'2','title'=>'More','class'=>'more','target'=>'_blank'),$_smarty_tpl);?>
	  
	  <span>产品类别</span></h3>
	  <div class="list1">
	    <div id="web-sidebar">
		  <?php $_smarty_tpl->tpl_vars['treeproduct'] = new Smarty_variable(vo_category("type={treecat} rootid={2}"), null, null);?>
		  <?php  $_smarty_tpl->tpl_vars['parent'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('treeproduct')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['parent']->key => $_smarty_tpl->tpl_vars['parent']->value){
?>
		  <dl>
		    <dt class="part2" id="part1-id<?php echo $_smarty_tpl->tpl_vars['parent']->value['catid'];?>
">
			<b><a href="<?php echo $_smarty_tpl->tpl_vars['parent']->value['url'];?>
"><?php echo $_smarty_tpl->tpl_vars['parent']->value['catname'];?>
</a></b>
			</dt>
			<dd class="part3dom">
			  <?php  $_smarty_tpl->tpl_vars['child'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['parent']->value['childcatgory']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['child']->key => $_smarty_tpl->tpl_vars['child']->value){
?>
			  <h4 class="part3" id="part2-id<?php echo $_smarty_tpl->tpl_vars['child']->value['catid'];?>
"><a href="<?php echo $_smarty_tpl->tpl_vars['child']->value['url'];?>
"><?php echo $_smarty_tpl->tpl_vars['child']->value['catname'];?>
</a></h4>
			  <?php }} ?>
			</dd>
		  </dl>	
		  <?php }} ?>
		</div>
		
		<!-- #web-sidebar //-->
		<script type="text/javascript">
			$(document).ready(function(){
				var Plug1 = 0; 
				var Plug2 = 1; 
				var i = 0;
				var SideBar = $("#web-sidebar");
				var SideBar_dt = SideBar.find("dt.part2");
				var SideBar_dd = SideBar.find("dd.part3dom");
				var part1_dom = $("#part1-id0"); 
				var part2_dom = $("#part2-id0"); 
				var part_dom_dd = part1_dom.next("dd.part3dom");
					SideBar_dd.css("display","none");
					part1_dom.addClass("ondown");
					part2_dom.addClass("ondown");
				if(Plug1 == 1){ SideBar_dd.css("display","block"); SideBar_dt.addClass("part_on"); i = 1;} 
				
				if(Plug2 == 1 && part1_dom.length!=0){
						part_dom_dd.css("display","block");
						i = 1; 
				}
				
				SideBar_dt.click(function(){
					i++;if(i>1)i=0;
					i==1?SideBar_on($(this)):SideBar_out($(this));	
				});
				
		//****************


			});
			
			function SideBar_on(dom){
				var dd = dom.next("dd.part3dom")
					dom.addClass("part_on");
					dom.removeClass("part_out");
					dd.css("display","block");
			}
			
			function SideBar_out(dom){
				var dd = dom.next("dd.part3dom")
					dom.addClass("part_out");
					dom.removeClass("part_on");
					dd.css("display","none");
			}

			
		//************

		</script>


      </div>
	  <!-- $list1 //--->
	  
	  <h3 class="title">
	  <?php echo hook_get_category(array('id'=>'1','title'=>'More','class'=>'more','target'=>'_blank'),$_smarty_tpl);?>
  
	  <span>最新动态</span></h3>
	  <ul class="list2">
     <?php $_smarty_tpl->tpl_vars['newnews'] = new Smarty_variable(vo_list("mod={article} where={v.treeid='1'} orderby={ORDER BY v.articleid DESC}"), null, null);?>
	    <?php  $_smarty_tpl->tpl_vars['volist'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('newnews')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['volist']->key => $_smarty_tpl->tpl_vars['volist']->value){
?>
	    <li><a href="<?php echo $_smarty_tpl->tpl_vars['volist']->value['url'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['volist']->value['title'];?>
"><?php echo $_smarty_tpl->tpl_vars['volist']->value['sort_title'];?>
</a></li>
		<?php }} ?>
	  </ul>

	  <h3 class="title">
	  <?php echo hook_get_category(array('id'=>'25','title'=>'More','class'=>'more','target'=>'_blank'),$_smarty_tpl);?>

	  <span>联系方式</span></h3>
	  <div class="text editor">
	    <p><?php echo hook_get_label(array('name'=>'contact'),$_smarty_tpl);?>
</p>
	  </div>
	</div>
	<!-- #left //-->

	<div id="right">
	  <h3 class="title">
	  <?php echo hook_get_category(array('name'=>'aboutus','title'=>'更多','class'=>'more','target'=>'self'),$_smarty_tpl);?>

	  <span>公司简介</span></h3>
	  <div class="text">
	    <div class="editor">
		  <p>
		  <?php $_smarty_tpl->tpl_vars['ads'] = new Smarty_variable(get_ads('index_ads_01'), null, null);?>
		  <?php if (!empty($_smarty_tpl->getVariable('ads')->value)){?>
		  <img width="<?php echo $_smarty_tpl->getVariable('ads')->value['width'];?>
" height="<?php echo $_smarty_tpl->getVariable('ads')->value['height'];?>
" hspace="2" align="left" vspace="2" src="<?php echo $_smarty_tpl->getVariable('ads')->value['uploadfiles'];?>
" />
          <?php }?>
		  </p>
		  <p><?php echo hook_get_label(array('name'=>'about'),$_smarty_tpl);?>
</p>
		</div>
	  </div>
	  
	  <h3 class="title line">
	  <?php echo hook_get_category(array('id'=>'2','title'=>'More','class'=>'more','target'=>'_blank'),$_smarty_tpl);?>

	  <span>最新产品</span></h3>
	  <div class="list">
	    <ul id="drawimg">
		  <?php $_smarty_tpl->tpl_vars['newproduct'] = new Smarty_variable(vo_list("mod={product} where={v.treeid='2'}"), null, null);?>
		  <?php  $_smarty_tpl->tpl_vars['volist'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('newproduct')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['volist']->key => $_smarty_tpl->tpl_vars['volist']->value){
?>
		  <li style="width:158px; height:183px;">
		    <a href="<?php echo $_smarty_tpl->tpl_vars['volist']->value['url'];?>
" class="imgbox" target="_blank"><img src="<?php echo $_smarty_tpl->tpl_vars['volist']->value['thumbfiles'];?>
" onload="javascript:DrawImage(this,'150','150');" alt="<?php echo $_smarty_tpl->tpl_vars['volist']->value['productname'];?>
" /></a>
			<h4><a href="<?php echo $_smarty_tpl->tpl_vars['volist']->value['url'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['volist']->value['productname'];?>
"><?php echo $_smarty_tpl->tpl_vars['volist']->value['sort_productname'];?>
</a></h4>
		  </li>
		  <?php }} ?>
		</ul>
		<div style="clear:both;"></div>
	  </div><!-- #list //-->
	
	</div><!-- #right //-->
	<div style="clear:both;"></div>
  </div><!-- #main //-->
  <?php $_template = new Smarty_Internal_Template(($_smarty_tpl->getVariable('tplpath')->value)."block_footer.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>
</div>
<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('skinpath')->value;?>
js/screen.js"></script>
</body>
</html>