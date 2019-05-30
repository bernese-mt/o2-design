/*
	[OEcms] (C)2012-2099 OEdev,Inc.
	$Id: public.js 2013-01-28 $
*/
/**
 * 添加收藏夹
 * @param:: string $url URL地址
 * @param:: string $title 标题
*/
function addfavorite(url, title) {
    var vDomainName = window.location.href;
    var description = document.title;
    try{
		//IE
        window.external.AddFavorite(vDomainName,description);
    }
	catch(e){
		//FF
        window.sidebar.addPanel(description,vDomainName,"");
    }
}

/**
 * 设置主页
 * @param:: string obj
 * @param:: string vrl
*/
function sethomepage(obj, vrl){
	try{
		obj.style.behavior='url(#default#homepage)';obj.setHomePage(vrl);
	}
	catch(e){
		if(window.netscape) {
			try { 
				netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");  
			}  
			catch (e){ 
				alert("此操作被浏览器拒绝！\n请在浏览器地址栏输入“about:config”并回车\n然后将 [signed.applets.codebase_principal_support]的值设置为'true',双击即可。");
			}
			var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components.interfaces.nsIPrefBranch);
			prefs.setCharPref('browser.startup.homepage',vrl);
		}
	}
}

/**
 * 复制URL地址
 * @param:: string id
*/
function copy_url(id){
	document.getElementById(id).select();
	build_copycode(id);
}
function build_copycode(id){
	var testCode = document.getElementById(id).value;
	if (build_copy2clipboard(testCode) != false) {
		alert("复制成功，您可以使用Ctrl+V 贴到需要的地方去！");
	}
}
build_copy2clipboard = function(txt) {
	if (window.clipboardData) {
		window.clipboardData.clearData();
		window.clipboardData.setData("Text",txt);
	}
	else if (navigator.userAgent.indexOf("Opera") != -1) {
		window.location=txt;
	}
	else if (window.netscape) 
	{
		try{
			netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
		}
		catch(e){
			alert("您的firefox安全限制限制您进行剪贴板操作，请打开’about:config’将signed.applets.codebase_principal_support’设置为true’之后重试，相对路径为firefox根目录/greprefs/all.js");
			return false;
		}
		var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
		if (!clip) return;
		var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);
		if (!trans) return;
		trans.addDataFlavor('text/unicode');
		var str = new Object();
		var len = new Object();
		var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
		var copytext = txt;str.data = copytext;
		trans.setTransferData("text/unicode",str,copytext.length*2);
		var clipid = Components.interfaces.nsIClipboard;
		if (!clip) return false;
		clip.setData(trans,null,clipid.kGlobalClipboard);
	}
}

//图片缩略图效果
function DrawImage(ImgD,FitWidth,FitHeight){
    var image=new Image();
	image.src=ImgD.src;
	if(image.width>0 && image.height>0){
		if(image.width/image.height>= FitWidth/FitHeight){
			if(image.width>FitWidth){
				ImgD.width=FitWidth;
				ImgD.height=(image.height*FitWidth)/image.width;
			}else{
				ImgD.width=image.width; 
				ImgD.height=image.height;
			}
		}else{
			if(image.height>FitHeight){
				ImgD.height=FitHeight;
				ImgD.width=(image.width*FitHeight)/image.height;
			}else{
				ImgD.width=image.width; 
				ImgD.height=image.height;
			} 
		}
	}
	//居中
	if(ImgD.height < FitHeight ){
		var paddH = parseInt((FitHeight - ImgD.height)/2);
		ImgD.style.paddingTop    = paddH+"px";
		ImgD.style.paddingBottom = paddH+"px";
	}
	if(ImgD.width < FitWidth ){
		var paddW = parseInt((FitWidth - ImgD.width)/2);
		ImgD.style.paddingRight = paddW+"px";
		ImgD.style.paddingLeft  = paddW+"px";
	}
	//var paddH = parseInt((FitHeight - ImgD.height)/2);
	//var paddW = parseInt((FitWidth - ImgD.width)/2);
	//var paddH = ((FitHeight - ImgD.height)/2);
	//var paddW = ((FitWidth - ImgD.width)/2);
	//ImgD.style.padding = paddH+"px "+paddW+"px "+paddH+"px "+paddW+"px";
 }


//弹出信息
function dc() {
  var elements = new Array();
  for (var i = 0; i < arguments.length; i++) {
    var element = arguments[i];
    if (typeof element == 'string') element = document.getElementById(element);
    if (arguments.length == 1) return element;
    elements.push(element);
  }
  return elements;
}

/* 随机数 */
function get_rndnum(n) {
	var chars = ['0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
	var res = "";
	for(var i = 0; i < n ; i ++) {
		var id = Math.ceil(Math.random()*35);
		res += chars[id];
	}
	return res;
}

/* menu */
function Menuon(ID) {
	try{dc('Tab'+ID).className='tab_on';}catch(e){}
}

/* close msg */
function closemsg() {
	try{dc('msgbox').innerHTML = '';dc('msgbox').style.display = 'none';}catch(e){}
}

/* dmsg */
function dmsg(str, id, s, t) {
	var t = t ? t : 5000;
	var s = s ? true : false;
	try{if(s){window.scrollTo(0,0);}dc('d'+id).innerHTML = '<img src="'+_ROOT_PATH+'tpl/static/images/check_error.gif" width="12" height="12" align="absmiddle"/> '+str+sound('tip');$(id).focus();}catch(e){}
	window.setTimeout(function(){dc('d'+id).innerHTML = '';}, t);
}

/* sound */
function sound(file) {
	return '<div style="float:left;"><embed src="'+_ROOT_PATH+'tpl/static/images/'+file+'.swf" quality="high" type="application/x-shockwave-flash" height="0" width="0" hidden="true"/></div>';
}

/* 信息全选控制 */
function checkAll(e, itemName){
  var aa = document.getElementsByName(itemName);
  for (var i=0; i<aa.length; i++)
   aa[i].checked = e.checked;
}

function checkItem(e, allName){
  var all = document.getElementsByName(allName)[0];
  if(!e.checked) all.checked = false;
  else{
    var aa = document.getElementsByName(e.name);
    for (var i=0; i<aa.length; i++)
     if(!aa[i].checked) return;
    all.checked = true;
  }
}

/* CSS背景控制 鼠标经过效果 */
function overColor(Obj) {
	var elements=Obj.childNodes;
	for(var i=0;i<elements.length;i++){
		elements[i].className="hback_o"
		Obj.bgColor="";
	}
	
}

/* 鼠标离开效果 */
function outColor(Obj){
	var elements=Obj.childNodes;
	for(var i=0;i<elements.length;i++){
		elements[i].className="hback";
		Obj.bgColor="";
	}
}

function IsDigit(){
    return ((event.keyCode >= 48) && (event.keyCode <= 57));
}
function IsDigit2(){
    return ((event.keyCode >= 48) && (event.keyCode <= 57) || (event.keyCode = 46));
}

/* 只能由汉字，字母，数字和下横线组合 */
function check_userstring(str){  
    var re1 = new RegExp("^([\u4E00-\uFA29]|[\uE7C7-\uE7F3]|_|[a-zA-Z0-9])*$");
	if(!re1.test(str)){
		return false;
	}else{
		return true;
	}
}

/* 判断字符长度，一个汉字为2个字符 */
function strlen(s){
	var l = 0;
	var a = s.split("");
	for (var i=0;i<a.length;i++){
		if (a[i].charCodeAt(0)<299){
			l++;
		}else{
			l+=2;
		}
	}
	return l;
}

/* 判断所选择数量 */
function check_count(id, my , num){
	var oEvent = document.getElementById('em_' + id + '_edit');
	var chks = oEvent.getElementsByTagName("INPUT");
	var count = 0;
	for(var i=0; i<chks.length; i++){
		if(chks[i].type=="checkbox"){
			if(chks[i].checked == true){
				count ++;
			}
			if(count > num){
				my.checked = false;
				alert('最多只能选择' + num + '项');
				return false;
			}
		}
	}
}


/**
  $Id: 检查Email是否合法
  retrun true,false
*/
function isEmail(str) {
	var re = /^([a-zA-Z0-9]+[_|\-|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\-|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
	return re.test(str);
}
