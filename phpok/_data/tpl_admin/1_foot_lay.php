<?php if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");} ?>	<?php echo $app->plugin_html_ap("body");?>
	<?php if($sys['debug'] && !$is_open){ ?>
	<div style="text-align:center;padding:10px 0;">
	    <?php echo debug_time();?>
	</div>
	<?php } ?>
</div>
<?php echo $app->plugin_html_ap("foot");?>
<script type="text/javascript">
$(document).ready(function(){
	if(layui && layui != 'undefined' && layui.form){
		window.setTimeout(function(){
			layui.form.render();
		}, 500);
	}
});
</script>
<?php echo $app->plugin_html_ap("phpokbody");?></body>
</html>