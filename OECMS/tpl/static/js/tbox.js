/*
	[OEcms] (C)2012-2099 OEdev,Inc.
	$Id: tbox.js 2013-01-28 $
*/

/**
 * 获取当前文件名
*/
function get_curl() {
	var curl = document.URL;
	var filename = curl.split("?")[0];
	return filename;
}
/**
 * 关闭 thickbox窗口
*/
function tbox_close(){
	window.parent.tb_remove();
}
/**
 * 关闭thickbox并刷新父页
*/
function tbox_close_reload(){
	window.parent.tb_remove();
	window.parent.location.reload();
}
/**
 * 弹出选择模板页
 * @param:: string title 标题
 * @param:: int inputid 接收框
*/
function tbox_templet(title, inputid) {
	var filename = get_curl();
	var url = filename + "?c=templet&a=select&inputid="+inputid+"&fromtype=jdbox&r="+get_rndnum(6);
	url = url + "&keepThis=true&TB_iframe=true&width=700&height=400";
	tb_show(title, url, false);
}

/*--------------- thickbox操作 End -------------------*/