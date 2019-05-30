define(function(require, exports, module) {
	var common = require('common'); //加载公共函数文件（Jqery、语言文字等）
	
		  $('#class1select').change(function(locationid, classtype){
			
				var locationid = document.myform.class1.options[document.myform.class1.selectedIndex].value;
				var classtype = 1;
				var i;
				if (classtype == 1 && document.myform.class2.length > 1) {
					document.myform.class2.length = 1;
					document.myform.class3.length = 1;
				}
				if (classtype == 2 && document.myform.class3.length > 1) document.myform.class3.length = 1;
				for (i = 0; i < onecount; i++){
					if (lev[i][1] == locationid){
						if (classtype == 1) document.myform.class2.options[document.myform.class2.length] = new Option(lev[i][0], lev[i][2]);
						else document.myform.class3.options[document.myform.class3.length] = new Option(lev[i][0], lev[i][2]);
					}
				}
		  });
			
			
			
		 $('#class2select').change(function(locationid, classtype){
		
			var locationid =document.myform.class2.options[document.myform.class2.selectedIndex].value;
			var classtype = 2;
			var i;
			if (classtype == 1 && document.myform.class2.length > 1) {
				document.myform.class2.length = 1;
				document.myform.class3.length = 1;
			}
			if (classtype == 2 && document.myform.class3.length > 1) document.myform.class3.length = 1;
			for (i = 0; i < onecount; i++){
				if (lev[i][1] == locationid){
					if (classtype == 1) document.myform.class2.options[document.myform.class2.length] = new Option(lev[i][0], lev[i][2]);
					else document.myform.class3.options[document.myform.class3.length] = new Option(lev[i][0], lev[i][2]);
				}
			}
	  });
	
	
	
		 $("#submit").click(function(){
			var class1=$('#class1select').val();
			var class2=$('#class2select').val();
			var class3=$('#class3select').val();
			var type=$("input[name='typex']:checked").val();
			var retexted=$('#retexted').val();
			var retext=$('#retext').val();
			if(class1==-1){
				alert("请选择栏目");
				$('#class1select').focus();
				return false;
			}
			if(!retexted){
				alert("被替换文字不能为空");
				$('#retexted').focus();
				return false;
			}
			if(!retext){
				alert("替换成文字不能为空");
				$('#retext').focus();
				return false;
			}
			$('#submit').attr("disabled","true");
			$.ajax({
				url: own_form+'a=domodify&action=modify'+'&class1='+class1+'&class2='+class2+'&class3='+class3,
				type: 'POST',
				data: '',
				success: function(result) {
					$('#idlist').val(result);
					replace_id(0,type,retexted,retext);
				}
			});
		return false;
		});
			

	
	function replace_id(num,type,retexted,retext){
			var idlist=$('#idlist').val();
			var ids=idlist.split("|");
			var id=ids[num].split("-");
			$.ajax({
				url: own_form+'a=domodify&action=modifyid'+'&table='+id[1]+'&id='+id[0]+'&type='+type+'&retexted='+retexted+'&retext='+retext,
				type: 'POST',
				data: '',
				success: function(result) {
					num_next=num+1;
					var len=ids.length-1;
					$('#schedule').empty();
					$('#schedule').append(num_next+'/'+len);
					if(num==ids.length-1){
						$('#schedule').empty();
						$('#schedule').append('完成');
						$('#submit').removeAttr("disabled");
					}
					else{
						replace_id(num_next,type,retexted,retext);
					}
					
				}
			});
		return false;
		}
});
