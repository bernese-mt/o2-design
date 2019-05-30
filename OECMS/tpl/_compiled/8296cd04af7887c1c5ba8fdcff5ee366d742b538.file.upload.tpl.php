<?php /* Smarty version Smarty-3.0.5, created on 2019-05-07 15:43:15
         compiled from "/home/o2design/public_html/OECMS/tpl/admincp/upload.tpl" */ ?>
<?php /*%%SmartyHeaderCode:18483046675cd13713515b33-36939976%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8296cd04af7887c1c5ba8fdcff5ee366d742b538' => 
    array (
      0 => '/home/o2design/public_html/OECMS/tpl/admincp/upload.tpl',
      1 => 1557211185,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18483046675cd13713515b33-36939976',
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
<title>上传图片/附件</title>
<script type='text/javascript' src='<?php echo $_smarty_tpl->getVariable('urlpath')->value;?>
tpl/static/js/jquery.min.js'></script>
<style type="text/css">
body {font-size: 9pt;margin: 0px;padding: 0px;background-color:#fff;}
.bj {padding-top: 3px;padding-bottom: 3px;}
form {margin: 0px;padding: 0px;}
.file {border:1px solid #e0e0e0;font-size:9pt;width:200px;position:relative;}
.button{background:none repeat scroll 0 0 #4e6a81;
border-color:#dddddd #000000 #000000 #dddddd;
border-style:solid;
border-width:2px;
color:#FFFFFF;cursor:pointer;letter-spacing:0.1em;overflow:visible;padding:3px 15px;width:auto;cursor:pointer;text-decoration:none;}
</style>
</head>
<body>
<div id="upload_box" style="display:block">
<form action="<?php echo $_smarty_tpl->getVariable('cpfile')->value;?>
?c=upload&a=saveupload&multipart=<?php echo $_smarty_tpl->getVariable('multipart')->value;?>
" method="post" enctype="multipart/form-data" name="up_form" id="up_form" onSubmit="return checkupload();">
<input type="hidden" name="module" id="module" value="<?php echo $_smarty_tpl->getVariable('module')->value;?>
" />
<input type="hidden" name="formname" id="formname" value="<?php echo $_smarty_tpl->getVariable('formname')->value;?>
" />
<input type="hidden" name="uploadinput" id="uploadinput" value="<?php echo $_smarty_tpl->getVariable('uploadinput')->value;?>
" />
<input type="hidden" name="thumbinput" id="thumbinput" value="<?php echo $_smarty_tpl->getVariable('thumbinput')->value;?>
" />
<input type="hidden" name="previewid" id="previewid" value="<?php echo $_smarty_tpl->getVariable('previewid')->value;?>
" />
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
	<td><input name="<?php echo $_smarty_tpl->getVariable('multipart')->value;?>
" id="<?php echo $_smarty_tpl->getVariable('multipart')->value;?>
" type="file" class="file" /></td>
    <td>&nbsp;<input type="submit" name="btn_upload" id="btn_upload" value="上传"  /></td>
  </tr>
</table>
</form>
</div>
<div id="upload_loading" style="display:none" class="bj"><img src="<?php echo $_smarty_tpl->getVariable('urlpath')->value;?>
tpl/static/images/uploading.gif" alt="文件上传中，请稍后..." width="220" height="19" border="0" /></div>
</body>
</html>
<script type="text/javascript">
function checkupload() {
	var t = "";
	var v = "";

	t = "<?php echo $_smarty_tpl->getVariable('multipart')->value;?>
";
	v = $("#"+t).val();
	if(v=="") {
		alert("请选择要上传的图片/附件");
		return false;
	}
    
	$('#upload_box').hide();
	$('#upload_loading').show();

	return true;
}
</script>
