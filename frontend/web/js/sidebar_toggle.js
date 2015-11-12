(function($){
    $(document).ready(function() {
$(function() {
	if($.cookie('sidebar_state') === 'show'){
		if ($('body').hasClass("sidebar-collapse")) {
            $('body').removeClass("sidebar-collapse");
		};
    };
	if($.cookie('sidebar_state') === 'hide'){
		if (!$('body').hasClass("sidebar-collapse")) {
            $('body').addClass("sidebar-collapse");
		};
    };
	$("a.sidebar-toggle").on('click',function() {
        if (!$('body').hasClass("sidebar-collapse")) {
			$.cookie('sidebar_state',"hide", { expires: 30 });
		} else {
            $.cookie('sidebar_state',"show", { expires: 30 });
			$('body').removeClass("sidebar-collapse");
		}
	});
});
});
})(jQuery);
