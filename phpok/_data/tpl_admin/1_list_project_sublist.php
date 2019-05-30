<?php if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");} ?><?php if($project_list){ ?>
<script type="text/javascript">
function pendding_info()
{
	$.phpok.json(get_url('index','pendding_sublist'),function(rs){
		$("em.toptip").remove();
		if(rs.status && rs.info){
			var list = rs.info;
			var html = '<em class="toptip">{total}</em>';
			var total = 0;
			for(var key in list){
				$("li[id=project_"+list[key]['id']+"] em").remove();
				$("li[id=project_"+list[key]['id']+"]").append(html.replace('{total}',list[key]['total']));
			}
			//每隔一分钟检测一次
			window.setTimeout("pendding_info()", 60000);
		}else{
			window.setTimeout("pendding_info()", 180000);
		}
	});
}

$(document).ready(function(){
	pendding_info();
	$("#project li").mouseover(function(){
		$(this).addClass("hover");
	}).mouseout(function(){
		$(this).removeClass("hover");
	}).click(function(){
		var url = $(this).attr("href");
		var txt = $(this).text();
		if(txt == '' || $.trim(txt) == ''){
			txt = $(this).attr('title');
		}
		if(url){
			$.win(txt,url);
			return true;
		}
		$.dialog.alert(p_lang('未指定动作'));
		return false;
	});
});
</script>
<ul class="project" id="project">
	<?php $project_list_id["num"] = 0;$project_list=is_array($project_list) ? $project_list : array();$project_list_id = array();$project_list_id["total"] = count($project_list);$project_list_id["index"] = -1;foreach($project_list as $key=>$value){ $project_list_id["num"]++;$project_list_id["index"]++; ?>
	<li id="project_<?php echo $value['id'];?>" title="<?php echo $value['title'];?>" status="<?php echo $value['status'];?>" href="<?php echo phpok_url(array('ctrl'=>'list','func'=>'action','id'=>$value['id']));?>">
		<div class="img"><img src="<?php echo $value['ico'] ? $value['ico'] : 'images/ico/default.png';?>" /></div>
		<div class="txt" id="txt_<?php echo $value['id'];?>"><?php echo $value['nick_title'] ? $value['nick_title'] : $value['title'];?></div>
	</li>
	<?php } ?>
</ul>
<div class="clear"></div>
<?php } ?>