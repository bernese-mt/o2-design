  $(document).ready(function(){
        var Left = $('#left');
        var Right = $('#right');
        var Left_cont = Left.find("div.webcontent");
		    Left.outerHeight() < Right.outerHeight()?Left.height(Right.outerHeight()):Right.height(Left.outerHeight());
		
		var Left_max = Left.outerHeight() - 35;
		    Left_cont.height(Left_max);
		
    $('#nav li.class1').hover(function(){overnav($(this))},function(){outnav($(this))});
	
    var input = $("#head span.parasearch_input input");
		if (input == '') {
			input.attr("value","输入关键字");
		}
	    input.click(
		    function(){
			    if($(this).attr("value")=="输入关键字")$(this).attr("value","");
			}
		);
  });