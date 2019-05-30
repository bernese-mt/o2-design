define(function(require, exports, module) {

	var $ = jQuery = require('jquery');
	require('epl/include/cookie');
	require('edturl/markdown/dist/to-markdown');
	require('pub/bootstrap/js/bootstrap.min');
	var editor = $('textarea.ckeditor');
	var kk=$('.v52fmbx_dlbox_content textarea').text();
			 var tt=toMarkdown(kk,{gfm: true});
			 console.log(tt);
			
			 $('.v52fmbx_dlbox_content textarea').text(tt);
	   var deps = [
                "../../../app/app/editormd/jquery", 
                 "../../../app/app/editormd/editormd",
                "../../../app/app/editormd/plugins/link-dialog/link-dialog",
                "../../../app/app/editormd/plugins/reference-link-dialog/reference-link-dialog",
                "../../../app/app/editormd/plugins/image-dialog/image-dialog",
                "../../../app/app/editormd/plugins/code-block-dialog/code-block-dialog",
                "../../../app/app/editormd/plugins/table-dialog/table-dialog",
                "../../../app/app/editormd/plugins/emoji-dialog/emoji-dialog",
                "../../../app/app/editormd/plugins/goto-line-dialog/goto-line-dialog",
                "../../../app/app/editormd/plugins/help-dialog/help-dialog",
                "../../../app/app/editormd/plugins/html-entities-dialog/html-entities-dialog", 
                "../../../app/app/editormd/plugins/preformatted-text-dialog/preformatted-text-dialog",
                "../../../app/app/editormd/css/editormd.min.css", 
            ];

            
		if(editor.length){
			
				seajs.use(deps,function($, editormd){
			 	editor.each(function(){
		           var name = jQuery(this).attr('name');

				   jQuery(this).parent().attr("id",name);
				
		
  var testEditor = editormd('content',{
                width   : "100%",
                height  : 500,
                htmlDecode : true,
                saveHTMLToTextarea : true,
                path    : "../../../app/app/editormd/lib/",
                imageUpload:true,
                imageFormats   : ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
                imageUploadURL : adminurl+'n=editormd&c=upload&a=doeditor',                
            });

		});
  });

 }
});