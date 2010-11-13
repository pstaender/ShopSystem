<?php

class MyShopShipping extends Extension {
	
	function extraStatics() {
		//use it for define extra fields you need
		return array(
			);
	}
	
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
	
	function calculate($shippingMethod = null) {
		//write your own method for calculating the shipping costs
		//shipping methods are defined in the model [enumValues]
		$amount = ($this->owner->Order()) ? $this->owner->Order()->amount() : 0;
		$shipping = 0;
		if (!($amount>0)) {
			$this->owner->Price = 0;
		}
		$country = strtoupper($this->owner->Country);
		
		//fw
		//Alle Länder
		$shipping = ($amount <= 199) ? 15.4 : 32.5;
		//EU
		if (in_array($country,ShopOrder::$euStates)) $shipping = ($amount <= 199) ? 11.1 : 19.5;
		//DE
		if (($country=="DE") || ($country=="")) $shipping = ($amount <= 199) ? 3.6 : 9.5;
		//fw
		if (!$shippingMethod) $shippingMethod=$this->owner->Method;
		$shippingMethod = strtolower($shippingMethod);
		if ($shippingMethod=="express") $shipping = $shipping*1.25;
		return ($amount==0) ? 0 : $shipping;		
	}
	
}

