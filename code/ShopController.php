<?php

class ShopController extends Page_Controller {
	
	function init() {
		parent::init();
		//try to include the jquery library by sapphire, else jquery 1.4.2 in the shopsystem folder
		if (file_exists(THIRDPARTY_PATH.'/jquery/jquery-packed.js')) Requirements::JavaScript(THIRDPARTY_DIR.'/jquery/jquery-packed.js');
		else Requirements::JavaScript(SHOPSYSTEM_DIR.'/javascript/_jquery-packed.js');
		Requirements::JavaScript(SHOPSYSTEM_DIR.'/javascript/application.js');
		Requirements::ThemedCSS('shoppingcart');
		Requirements::ThemedCSS('shop');
	}
	
	function CheckoutPage() {
		return DataObject::get_one("ShopCheckoutPage");
	}
	
	function Tax() {
		return ShopOrder::getLocalTax();
	}
	
	function Cart() {
		return ShopOrder::orderSession();
	}
	
	function VAT() {
		return ShopOrder::getVATType();
	}
	
	function MostFeaturedItems() {
		return DataObject::get("ShopItem","Featured = 1");
	}
	
}

?>