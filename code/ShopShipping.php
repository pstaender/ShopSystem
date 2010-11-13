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
	
	static $casting = array(
	  'Price' => 'Float',
	);
		
	function getFrontendFields($param = null) {
		$fields = parent::getFrontendFields($param);
		$fields->replaceField(
			"Method",
			new DropdownField("Method",_t("Shop.Shipping.Method","%ShippingMethod%"), $this->methodFields(), $this->Method
			));
		return $fields;
	}
	
	function methodFields() {
		try {
			return parent::methodFields();
		} catch (Exception $e) {
			return array();
		}
	}
	
	function methodTitle() {
		$title = $this->Method;
		return _t("Shop.Shipping.{$title}","%{$title}%");
	}
	
	function calculate($shippingMethod) {
		try {
			$this->Price = parent::calculate($shippingMethod);
		} catch (Exception $e) {
			ShopOrder::displayExtensionNoticeFor("ShopShipping::calculate");
		}
	}
	
	function Price() {
		try {
			//if you wish, override with your own extension
			return parent::Price();
		} catch (Exception $e) {
			return $this->Price;
		}
	}
	
}

