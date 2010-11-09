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
	
	$(".buttonOrder").bind('click', function() {
		var id = $(this).attr('key');
		var optionID = 0;
		var button = $(this);
		button.addClass('loading');
		if ($(".productOptions .productOption.selected").length>0) optionID = $(".productOptions .productOption.selected").attr('option');
		var url = "cart/add/"+$(this).attr('key')+"/?optionid="+optionID;
		
		$(".shoppingCartHolder").load(url, function() {
				//after loading url, do
				//fw
				button.removeClass('loading');
				$('#ShoppingItemsCountIcon').html($('#ShoppingCartItemsCount').attr('value'));
		});
	});
	
	//select option
	$(".productOptions .productOption").bind('click',function() {
		$(".productOptions .productOption").removeClass('selected');
		$(this).addClass('selected');
	})
	
	//on cart buttons
	$(".shoppingCart .action div").live("click", function() {
		var id = $(this).parent().parent().attr('key');
		var url = "cart/add/"+$(this).attr('key')+"/"+$(this).attr('quantity')+"?optionid="+$(this).attr('option');
		//$(this).parent().parent().parent().parent().parent().load(url);
		cartHolder.load(url, function() {
			//after loading url, do
			//fw
			$('#ShoppingItemsCountIcon').html($('#ShoppingCartItemsCount').attr('value'));
			
		});
		
	});
})
})(jQuery);
