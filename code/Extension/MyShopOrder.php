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
		//shipping methods are defined in the model [enumValues]
		$amount = $this->owner->amount();
		$shipping = 0;
		$shipping = ($amount < 300) ? 20 : 50;
		if ($shippingMethod=="Express") $shipping = $shipping*1.25;
		return ($amount==0) ? 0 : $shipping;
	}

	function calcPaymentCosts($paymentMethod = null) {
		//write your own method for calculating the payment costs
		//payment methods are defined in the model [enumValues]
		$amount = $this->owner->amount();
		if ($paymentMethod) $paymentMethod = strtolower($paymentMethod);
		$payment = 0;
		if ($paymentMethod=="creditcard") {
			$payment = $amount * 0.01;
		}
		return $payment;
	}
	
	function calcDiscount() {
		//write your own method for calculating a discount, if needed
		return 0;
	}
		
	function orderKey() {
		//place your own rule to generate an order key
		$name = preg_replace(array("/Ä|ä/","/Ö|ö/","/Ü|ü/","/ß/"),array("ae","oe","ue","ss"), $this->owner->InvoiceAddress()->Surname
		);
		return "FW-".strtoupper(
			preg_replace("/[^A-Za-z0-9]/","",$name) . "-" . preg_replace("/[^A-Za-z0-9]/","",$this->owner->InvoiceAddress()->ZipCode) . "-" . $this->owner->ID
			);
		
	}
	
	function isComplete() {
		$required = array(
			"Total","Payment","Shipping","InvoiceAddressID","DeliveryAddressID"
			);
		$check = true;
		foreach ($required as $r) {
			if (!($this->owner->hasValue($r))) $check = false;
		}
		if ($this->owner->amountBelowMin()) $check = false;
		return $check;
	}
	
	
	function isNotCompleteMessage() {
		return "Your order couldn't submitted because of the foloowing errors:";
	}
		
}

?>