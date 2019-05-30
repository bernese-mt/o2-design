<?php if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file",true,false); ?>
<script type="text/javascript">
$(document).ready(function(){
	$("#project li").each(function(i){
		$(this).click(function(){
			var url = $(this).attr("href");
			var txt = $(this).text();
			if(txt == '' || $.trim(txt) == ''){
				txt = $(this).attr('title');
			}
			if(url){
				$.win(txt,url);
				return true;
			}
			return false;
		});
	});
});
</script>
<ul class="project" id="project">
	<?php if($popedom['site']){ ?>
	<li title="<?php echo P_Lang("配置网站信息，包括网址域名，布全局关键字等");?>" href="<?php echo phpok_url(array('ctrl'=>'all','func'=>'setting'));?>">
		<div class="img"><img src="images/ico/setting.png" class="ie6png"/></div>
		<div class="txt"><?php echo P_Lang("网站信息");?></div>
	</li>
	<?php } ?>
	<?php if($popedom['domain']){ ?>
	<li title="<?php echo P_Lang("网站可以绑定的域名");?>" href="<?php echo phpok_url(array('ctrl'=>'all','func'=>'domain'));?>">
		<div class="img"><img src="images/ico/alias.png" class="ie6png" /></div>
		<div class="txt"><?php echo P_Lang("网站域名");?></div>
	</li>
	<?php } ?>
	<?php if($popedom['gset'] || $popedom['set']){ ?>
	<?php $tmpid["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$tmpid = array();$tmpid["total"] = count($rslist);$tmpid["index"] = -1;foreach($rslist as $key=>$value){ $tmpid["num"]++;$tmpid["index"]++; ?>
	<li title="<?php echo $value['title'];?>" href="<?php echo phpok_url(array('ctrl'=>'all','func'=>'set','id'=>$value['id']));?>">
		<div class="img"><img src="<?php echo $value['ico'];?>" class="ie6png" /></div>
		<div class="txt"><?php echo P_Lang($value['title']);?></div>
	</li>
	<?php } ?>
	<?php } ?>
	<?php if($popedom['gset'] && $session['adm_develop']){ ?>
	<li title="<?php echo P_Lang("添加全局");?>" onclick="$.admin_all.group()" style="border: 1px dashed #cacaca;padding:20px;">
		<div class="img"><img src="images/plus.png" class="ie6png" /></div>
	</li>
	<?php } ?>
</ul>
<div class="clear"></div>
<?php $this->output("foot","file",true,false); ?>