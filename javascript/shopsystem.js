;(function($) {
$(document).ready(function() {
	
	var pathArray = window.location.pathname.split( '/' );
	var host = pathArray[2];
	
	var cartHolder = $(".shoppingCartHolder");
	
	//load cart information
	cartHolder.load('cart/get', function() {
		//do something, after first load
		//eg. hide shopping cart
		//fw
		$('.shoppingCart').hide();
		$('#ShoppingItemsCountIcon').html($('#ShoppingCartItemsCount').attr('value'));
		
	});
	$("#ProductsQuickViewButton").hover(function(){
		$("#ProductsQuickView").stop().fadeTo(100,1.0);
	});
	$("#ProductsQuickView").click(function(){
		$("#ProductsQuickView").stop().fadeTo(100,0);
	});
	$(".buttonOrder").bind('click', function() {
		var id = $(this).attr('key');
		var url = "cart/add/"+$(this).attr('key')+"/";
		$(".shoppingCartHolder").load(url, function() {
				//after loading url, do
				//fw
				$('#ShoppingItemsCountIcon').html($('#ShoppingCartItemsCount').attr('value'));
		});
	});
	
	//on cart buttons
	$(".shoppingCart .action div").live("click", function() {
		var id = $(this).parent().parent().attr('key');
		var url = "cart/add/"+$(this).attr('key')+"/"+$(this).attr('quantity');
		//$(this).parent().parent().parent().parent().parent().load(url);
		cartHolder.load(url, function() {
			//after loading url, do
			//fw
			$('#ShoppingItemsCountIcon').html($('#ShoppingCartItemsCount').attr('value'));
			
		});
		
	});
})
})(jQuery);
