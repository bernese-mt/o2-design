/*
	[OEcms] (C)2012-2099 OEdev,Inc.
	$Id: admincp.js 2013-01-28 $
*/
/**
 * $Id    : jquery for ajax更改状态值
 * @params: url   发送URL
 * @params: imgid 标签ID
 * @params: id    ID
*/
function fetch_ajax(imgid,id){
	var requesturl = document.URL;
	var filename = requesturl.split("&")[0];
	var img_on  = _ROOT_PATH + "tpl/static/images/yes.gif";
	var img_off = _ROOT_PATH + "tpl/static/images/no.gif";
    var type  = $("#attr_"+imgid+id).val();
	if(type == imgid+"close"){
		$("#"+imgid+id).attr("src",img_off);
		$("#attr_"+imgid+id).val(imgid+"open");
		$.ajax({
			type: "POST",
			url: filename,
			data: "a=update&type="+type+"&id="+id+"&rnd="+get_rndnum(6),
	        dataType : "text",
			success: function(data) {}
		});
	}
	else {
		$("#"+imgid+id).attr("src",img_on);
		$("#attr_"+imgid+id).val(imgid+"close");
		$.ajax({
			type: "POST",
			url: filename,
			data: "a=update&type="+type+"&id="+id+"&rnd="+get_rndnum(6),
	        dataType : "text",
			success: function(data) {}
		});
	}
}


/*--------------- ajax 批量添加 Start ---------------------*/
/**
 * 批量添加图库
 * @param:: my
 * @param:: module
*/
function album_add(my, module) {
	$('#load_imgtips').html('正在为您努力加载中...');
	var requesturl = document.URL;
	var filename = requesturl.split("?")[0];
	//统计imglist 个数
	var imgnum = $('.imglist') ? $('.imglist').length : 0;
	//当前最大排序
	var maxsort = parseInt($("input[name='imgmaxsort']").val());
	//ajax 排序 sort
	var sort = 0;
	if (maxsort>imgnum) {
		sort = maxsort;
	}
	else {
		sort = imgnum;
	}
	//URL
	var url = filename+'?c=ajax&a=add_album&sort='+sort+"&module="+module+"&rnd="+get_rndnum(8);
	var dom = $("tr.imglist");
	var at = dom.length>0 ? dom.eq(dom.length-1) : $('#list-top');
    $.ajax({
    url : url, 
    type: "GET",
	dataType: "json",
    success: function(data){
		var json = eval(data);
		album_addtr(at, json.list, 0);
		$('#load_imgtips').empty();
		//$("input[name='imgnum']").val(function(){return parseInt($(this).val())+1});

		//成功后imgmaxsort+1
		$("input[name='imgmaxsort']").val((maxsort+1));
		//alert($("input[name='imgmaxsort']").val());
    }
    });
	return false;
}

/**
 * 删除相册上传框
*/
function album_del(my, sortid) {
	//移除tr
	my.parent('td').parent('tr').remove();
	//统计imglist 个数
	var imgnum = $('.imglist') ? $('.imglist').length : 0;
	//imgmaxsort最大值
	var maxsort = parseInt($("input[name='imgmaxsort']").val());
	//如果没有相框 重置0
	if (imgnum == 0) {
		$("input[name='imgmaxsort']").val(0);
	}
	//有相框
	else {
		//如果删除的ID是当前最大的ID，则imgmaxsort-1
		if(sortid == maxsort) {
			$("input[name='imgmaxsort']").val((maxsort-1));
		}

	}
}

/**
 * 统计相册上传框数量
*/
function album_countnums() {
	$("input[name='imgnum']").val(function(){return parseInt($(this).val())-1});
}
function album_addtr(h,t,i){
	h.after(t);
	var l = i!=1?i:1;
	h.next('tr').find("input[type='text']").eq(l).focus();
}


/**
 * 批量添加订单物品清单
 * @param:: my
 * @param:: module
*/
function item_add(my) {
	$('#load_itemtips').html('正在为您努力加载中...');
	var requesturl = document.URL;
	var filename = requesturl.split("?")[0];
	//统计itemlist 个数
	var itemnum = $('.itemlist') ? $('.itemlist').length : 0;
	//当前最大排序
	var maxsort = parseInt($("input[name='itemmaxsort']").val());
	//ajax 排序 sort
	var sort = 0;
	if (maxsort>itemnum) {
		sort = maxsort;
	}
	else {
		sort = itemnum;
	}
	//URL
	var url = filename+'?c=ajax&a=add_goods&sort='+sort+"&rnd="+get_rndnum(8);
	var dom = $("tr.itemlist");
	var at = dom.length>0 ? dom.eq(dom.length-1) : $('#list-top');
    $.ajax({
    url : url, 
    type: "GET",
	dataType: "json",
    success: function(data){
		var json = eval(data);
		item_addtr(at, json.list, 0);
		$('#load_itemtips').empty();
		//成功后itemmaxsort+1
		$("input[name='itemmaxsort']").val((maxsort+1));
    }
    });
	return false;
}

/**
 * 删除物品列表
*/
function item_del(my, sortid) {
	//移除tr
	my.parent('td').parent('tr').remove();
	//统计itemlist 个数
	var itemnum = $('.itemlist') ? $('.itemlist').length : 0;
	//itemmaxsort最大值
	var maxsort = parseInt($("input[name='itemmaxsort']").val());
	//如果没有列表 重置0
	if (itemnum == 0) {
		$("input[name='itemmaxsort']").val(0);
	}
	//有列表
	else {
		//如果删除的ID是当前最大的ID，则itemmaxsort-1
		if(sortid == maxsort) {
			$("input[name='itemmaxsort']").val((maxsort-1));
		}

	}
}

/**
 * 统计物品列表数量
*/
function item_countnums() {
	$("input[name='itemnum']").val(function(){return parseInt($(this).val())-1});
}
//focus
function item_addtr(h,t,i){
	h.after(t);
	var l = i!=1?i:1;
	h.next('tr').find("input[type='text']").eq(l).focus();
}
/*--------------- 批量添加 End ---------------------*/

