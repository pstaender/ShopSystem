<?php

/**
 * MyShopOrder
 *
 * @package shopsystem
 * @author Philipp Staender
 * @description In this class you override shoppingCosts and discount methods
 * with your own rules... methods below are just examples...
 */

class MyShopOrder extends Extension {
	
	function calcShippingCosts($shippingMethod = null) {
		//write your own method for calculating the shipping costs
		//shipping mehods are defined in the model [enumValues]
		$amount = $this->owner->amount();
		$shipping = 0;
		$shipping = ($amount < 300) ? 20 : 50;
		if ($shippingMethod=="Express") $shipping = $shipping*1.25;
		return ($amount==0) ? 0 : $shipping;
	}
	
	function calcDiscount() {
		//write your own method for calculating a discount, if needed
		return 0;
	}
	
	function isComplete() {
		$required = array(
			"Total","Payment","Shipping","InvoiceAddress","DeliveryAddress"
			);
		$check = true;
		foreach ($required as $r) {
			if (!($this->owner->hasValue($r))) $check = false;
		}
		return $check;
	}
	
	function orderKey() {
		//place your own rule to generate an order key
		$name = preg_replace(array("/Ä|ä/","/Ö|ö/","/Ü|ü/","/ß/"),array("ae","oe","ue","ss"), $this->owner->InvoiceAddress()->Surname
		);
		return "FW-".strtoupper(
			preg_replace("/[^A-Za-z0-9]/","",$name) . "-" . preg_replace("/[^A-Za-z0-9]/","",$this->owner->InvoiceAddress()->ZipCode) . "-" . $this->owner->ID
			);
		
	}
		
}

?>