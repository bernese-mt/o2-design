define(function(require, exports, module) {

	var $ = jQuery = require('jquery');
      require('edturl/markdown/dist/to-markdown');
	function editor(d,name,type,x,y) {
		var p=d.parents(".ftype_ckeditor");
		/*加载状态*/
		if(p.prev("dt").length<1){
			p.css({'padding':'0px','margin':'0px'});
			d.parent(".fbox").css("padding","0px 5px");
		}else{
			x = x?x:'98%';
		}
		p.find('.fbox').css('margin','0px');
		var kk=d.text();
		 var tt=toMarkdown(kk,{gfm: true});
		 d.text(tt);
		 var names=name+'s';
			d.parent('.fbox').attr("id",names);
		/*配置编辑器*/
	   var deps = [
                "../app/app/editormd/plugins/link-dialog/link-dialog",
                "../app/app/editormd/editormd", 
                "../app/app/editormd/plugins/reference-link-dialog/reference-link-dialog",
                "../app/app/editormd/plugins/image-dialog/image-dialog",
                "../app/app/editormd/plugins/code-block-dialog/code-block-dialog",
                "../app/app/editormd/plugins/table-dialog/table-dialog",
                "../app/app/editormd/plugins/emoji-dialog/emoji-dialog",
                "../app/app/editormd/plugins/goto-line-dialog/goto-line-dialog",
                "../app/app/editormd/plugins/help-dialog/help-dialog",
                "../app/app/editormd/plugins/html-entities-dialog/html-entities-dialog", 
                "../app/app/editormd/plugins/preformatted-text-dialog/preformatted-text-dialog",
                "../app/app/editormd/css/editormd.min.css", 
            ];
	 	seajs.use(deps, function($, editormd) {
			window['testEditor'+names]= editormd(names,{
                width   : "100%",
                height  : 500,
                htmlDecode : true,
                saveHTMLToTextarea : true,
                path    : "../app/app/editormd/lib/",
                imageUpload:true,
                imageFormats   : ["jpg", "jpeg", "gif", "png", "bmp", "webp",'ico'],
                imageUploadURL : adminurl+'n=editormd&c=upload&a=doeditor',               
            });
        	d.parent('.fbox').find('[name='+names+']').attr({name:name});
		});
	}
	
	exports.func = function(d){
		d = d.find('.ftype_ckeditor .fbox textarea');
		var d_fun=function(dom) {
			var n = dom.attr('name'),t=dom.attr('data-ckeditor-type'),x=dom.attr('data-ckeditor-x'),y=dom.attr('data-ckeditor-y');
			editor(dom,n,t,x,y);
		};
		d.each(function() {
			var $this_parents=$(this).parents('.tab-pane');
			if($this_parents.length){
				var $this=$(this),
					id=$this_parents.attr('id');
				if($this_parents.hasClass('active')){
					d_fun($this);
				}else{
					$('.nav-tabs>li>a[href=#'+id+']').one('click',function() {
						d_fun($this);
					})
				}
				
			}else{
				d_fun($(this));
			}
		})
	}
	
});


