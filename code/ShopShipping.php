<?php

class ShopShipping extends DataObject {
	
	static $db = array(
		"Title"=>"Varchar(200)",
		"Method"=>"Enum('Standard,Express','Standard')",
		"Note"=>"Text",
		"DateOfSending"=>"Date",
		"TrackingID"=>"Varchar(200)",
		"ServiceProvider"=>"Varchar(200)",
		"Price"=>"Float",
		);
	
	static $has_one = array(
		"Order"=>"ShopOrder"
		);
		
	static $belongs_to = array(
		"Order"=>"ShopOrder"
		);
	
	static $required_fields = array(
		"Method",
		);
		
	static $priceInMethods = true;
		
	function getFrontendFields($param = null) {
		$fields = parent::getFrontendFields($param);
		$fields->replaceField(
			"Method",
			new DropdownField("Method",_t("Shop.Shipping.Method","%ShippingMethod%"), self::methodFields(), $this->Method
			));
		return $fields;
	}
	
	static function methodFields() {
		//generate a select field from all enum values
		$shippingMethods = singleton("ShopShipping")->dbObject('Method')->enumValues();
		$order = ShopOrder::orderSession();
		$ship = array();
		foreach ($shippingMethods as $name => $value) {
			$price = self::$priceInMethods ? " (".$order->calcShippingCosts($name)." ".ShopOrder::getLocalCurrency().")" : "";
			$ship[$name] = _t("Shop.Shipping.{$value}","%{$value}%").$price;
		}
		return $ship;
	}
	
	function methodTitle() {
		$title = $this->Method;
		return _t("Shop.Shipping.{$title}","%{$title}%");
	}
	
}

?>