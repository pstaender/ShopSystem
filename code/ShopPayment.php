<?php

class ShopPayment extends DataObject {
	
	static $db = array(
		"Title"=>"Varchar(200)",
		"Method"=>"Enum('Invoice,Prepayment','Invoice')",
		"Note"=>"Text",
		"DateOfRecieving"=>"Date",
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
			new DropdownField("Method",_t("Shop.Payment.Method","%ShippingMethod%"), self::methodFields(), $this->Method
			));
		return $fields;
	}
	
	static function methodFields() {
		//generate a select field from all enum values
		$shippingMethods = singleton("ShopPayment")->dbObject('Method')->enumValues();
		$order = ShopOrder::orderSession();
		$ship = array();
		foreach ($shippingMethods as $name => $value) {
			$price = self::$priceInMethods ? " (".$order->calcPaymentCosts($name)." ".ShopOrder::getLocalCurrency().")" : "";
			$ship[$name] = _t("Shop.Payment.{$value}","%{$value}%").$price;
		}
		return $ship;
	}
	
	function methodTitle() {
		$title = $this->Method;
		return _t("Shop.Payment.{$title}","%{$title}%");
	}
	
}

?>