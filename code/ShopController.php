<?php

class ShopController extends Page_Controller {
	
	static $allowed_actions = array(
		"cleanup_orders"=>"shopadmin"
		);
	
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
	
	function cleanup_orders() {
		$holdbackTimeInSecs=6000;//10 hours
		$orders = DataObject::get("ShopOrder","Status = 'Unsubmitted' AND Created < '".date("Y-m-d G-m-i",(time()-$holdbackTimeInSecs))."'");
		if (!$orders) exit("No orders to cleanup...");
		foreach($orders as $order) {
			if ($order->InvoiceAddress()) $order->InvoiceAddress()->delete();
			if ($order->DeliveryAddress()) $order->DeliveryAddress()->delete();
			if ($order->Payment()) $order->Payment()->delete();
			if ($order->Shipping()) $order->Shipping()->delete();
			if ($order->Items()) {
				foreach($order->Items() as $item) $item->delete();
			}
			echo "ShopOrder record '".$order->ID."' with amount of '".$order->Amount."' deleted...<br />";
			$order->delete();
		}
	}
	
}

?>