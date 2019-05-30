<?php if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");} ?><script id="<?php echo $_rs['identifier'];?>" type="text/plain" style="<?php echo $_rs['form_style'];?>"><?php echo $_rs['content'];?></script>
<script type="text/javascript">
var toolbars_<?php echo $_rs['id'];?> = [[
	'fullscreen','source','removeformat', 'pasteplain','|',
	'bold', 'italic', 'underline', 'strikethrough','|',
	'superscript', 'subscript','|',
	'justifyleft', 'justifycenter', 'justifyright', '|',
	'insertorderedlist', 'insertunorderedlist','forecolor', 'backcolor',  '|',
	'indent', 'horizontal','blockquote','|',
	'link', 'unlink', 'anchor','|',
	<?php if($_rs['btns']['image']){ ?>'simpleupload','insertimage',<?php } ?>
	<?php if($_rs['btns']['video']){ ?>'insertvideo',<?php } ?>
	<?php if($_rs['btns']['file']){ ?>'attachment',<?php } ?>
	<?php if($_rs['btns']['info']){ ?>'phpokinfo',<?php } ?>
	<?php if($_rs['btns']['map']){ ?>'map',<?php } ?>
	<?php if($_rs['btns']['page']){ ?>'pagebreak',<?php } ?>
	<?php if($_rs['btns']['image'] || $_rs['btns']['video'] || $_rs['btns']['file'] || $_rs['btns']['info'] || $_rs['btns']['map'] || $_rs['btns']['page']){ ?>'|',<?php } ?>
	<?php if($_rs['btns']['spechars']){ ?>'spechars',<?php } ?>
	<?php if($_rs['btns']['emotion']){ ?>'emotion',<?php } ?>
	'insertpbefore','insertpafter','|',
	<?php if($_rs['btns']['fontfamily']){ ?>'fontfamily',<?php } ?>
	<?php if($_rs['btns']['fontsize']){ ?>'fontsize',<?php } ?>
	<?php if($_rs['btns']['paragraph']){ ?>'paragraph',<?php } ?>
	<?php if($_rs['btns']['insertcode']){ ?>'insertcode',<?php } ?>
	<?php if($_rs['btns']['table']){ ?>
	'inserttable','deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols','|',
	<?php } ?>
	'help'
]];
var edit_config_<?php echo $_rs['id'];?> = {
	 'UEDITOR_HOME_URL':webroot+'js/ueditor/'
	,'serverUrl':get_url('ueditor')
	,'toolbars':toolbars_<?php echo $_rs['id'];?>
	<?php if($_rs['btns']['info']){ ?>,'labelMap':{'phpokinfo':'主题库'}<?php } ?>
    ,'initialFrameWidth':'100%'
    ,'allowDivTransToP': false
    ,'initialFrameHeight':'<?php echo $_rs['height'];?>'
	,'sourceEditorFirst':<?php echo $_rs['is_code'] ? 'true' :'false';?>
	,'readonly':<?php echo $_rs['is_read'] ? 'true' :'false';?>
	,'pasteplain':<?php echo $_rs['paste_text'] ? 'true' : 'false';?>
	,'autoFloatEnabled':<?php echo $_rs['is_float'] ? 'true' : 'false';?>
	,'autoHeightEnabled':<?php echo $_rs['auto_height'] ? 'true' : 'false';?>
	,'zIndex':99
	<?php if($_rs['btns']['page']){ ?>,'pageBreakTag':'[:page:]'<?php } ?>
	,'textarea':'<?php echo $_rs['identifier'];?>'
	<?php if($_rs['btns']['info']){ ?>,'iframeUrlMap':{'phpokinfo':get_url('ueditor','info')}<?php } ?>
};
var edit_<?php echo $_rs['id'];?> = UE.getEditor('<?php echo $_rs['identifier'];?>',edit_config_<?php echo $_rs['id'];?>);
</script>