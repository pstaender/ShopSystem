;(function($) {
$(document).ready(function() {
	
	var pathArray = window.location.pathname.split( '/' );
	var host = pathArray[2];
	
	var cartHolder = $(".shoppingCartHolder");
	
	//load cart information
	cartHolder.load('cart/get');
	
	$(".buttonOrder").bind('click', function() {
		var id = $(this).attr('key');
		var url = "cart/add/"+$(this).attr('key')+"/";
		$(".shoppingCartHolder").load(url);
	});
	
	//on cart buttons
	$(".shoppingCart .action div").live("click", function() {
		var id = $(this).parent().parent().attr('key');
		var url = "cart/add/"+$(this).attr('key')+"/"+$(this).attr('quantity');
		//$(this).parent().parent().parent().parent().parent().load(url);
		cartHolder.load(url);
	});
})
})(jQuery);
