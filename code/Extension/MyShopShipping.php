<?php

class MyShopShipping extends DataObjectDecorator {
	
	//override with your own fields+rules, if you wish
	static function methodFields() {
		//generate a select field from all enum values
		$shippingMethods = singleton("ShopShipping")->dbObject('Method')->enumValues();
		$order = ShopOrder::orderSession();
		$ship = array();
		
		//fw
		$ship = array(
			"Standard" => "Standardversand (".$order->shippingCosts("Standard")." ".ShopOrder::getLocalCurrency().")",
			);
		return $ship;
		//fw
		
		foreach ($shippingMethods as $name => $value) {
			$price = ShopShipping::$priceInMethods ? " (".$order->calcShippingCosts($name)." ".ShopOrder::getLocalCurrency().")" : "";
			$ship[$name] = _t("Shop.Shipping.{$value}","%{$value}%").$price;
		}
		return $ship;
		
	}
	
}

