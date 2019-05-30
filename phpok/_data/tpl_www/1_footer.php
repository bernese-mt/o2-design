<?php if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");} ?><?php echo phpok_head_css();?>
<?php echo phpok_head_js();?>
<?php if($config['code'] && $config['code']['statjs']){ ?>
<div style="display:none"><?php echo $config['code']['statjs'];?></div>
<?php } ?>
<?php echo $app->plugin_html_ap("phpokbody");?></body>
</html>