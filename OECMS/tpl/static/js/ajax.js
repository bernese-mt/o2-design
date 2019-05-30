/*
	[OEcms] (C)2012-2099 OEdev,Inc.
	$Id: ajax.js 2013-01-28 $
*/
/* 
  后台 检测分类目录标识是否可用
  @params:: inputid 检测的input ID
  @params:: tipsid json显示结果位置
*/
function ajax_checkcatalog(inputid, tipsid){
	var name = $("#"+inputid).val();
	if(name != ''){
		$('#'+tipsid).html('loading...');
		$.ajax({
			type: "POST",
			url: get_curl() + "?c=ajax&a=checkcatalog",
			cache: false,
			data: {name: name, r:get_rndnum(8)},
			dataType: "json",
			beforeSend: function(XMLHttpRequest) {
				XMLHttpRequest.setRequestHeader("request_type","ajax");
			},
			success: function(data) {
				var json = eval(data);
				var response = json.response;
				var message = json.msg;

				if (response == true){
					$('#'+tipsid).html("<font color='green'>"+message+"</font>");
				}
				else {
					$('#'+tipsid).html("<font color='red'>"+message+"</font>");
				}
			},
			error: function() {

			}
		});
	} 
}