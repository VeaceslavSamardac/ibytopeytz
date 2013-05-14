jQuery(function() {
	jQuery(".logged-in div.hidden").tabs(".logged-in div.panes > div");
	var scrollable = new Array();
	scrollable[0] = jQuery(".challenges-images-1").scrollable({circular: true}).navigator();//.autoscroll();
	scrollable[1] = jQuery(".challenges-images-2").scrollable({circular: true}).navigator();//.autoscroll();
});
