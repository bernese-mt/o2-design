function overnav(dom){
                var my = dom.find('ul');
                var my_w = dom.outerWidth(true) + 18;
                var my_h = dom.outerHeight(true);
	                my.css({
					    "position" : "absolute",
					    "display" : "block",
					    "height" : "auto",
					    "left" : "-9px",
					    "top" : my_h,
					    "width" : my_w
					});
}
function outnav(dom){
    var my = dom.find('ul');
	    my.css({
			"display" : "none"
		});
}