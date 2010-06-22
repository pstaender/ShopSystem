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
	
	static $eu_states = array(
		"BE","BG","DK","DE","EE","FI","FR","GR","IE","IT","LV","LT","LU","MT","NL","AT",
		"PL","PT","RO","SE","SK","SI","ES","CZ","HU","GB","CY"
		);
		
	function extraStatics() {
		//use it for define extra fields you need
		return array(
			'db' => array(
				'Weight' => 'Float',
				)
			);
	}
	
	function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->addFieldsToTab('Root.Content.Shop', array(
			new TextField('Weight')
			));
		return $fields;
	}
			
	function calcShippingCosts($shippingMethod = null) {
		//write your own method for calculating the shipping costs
		//shipping methods are defined in the model [enumValues]
		$amount = $this->owner->amount();
		$shipping = 0;
		$shipping = ($amount < 300) ? 20 : 50;
		if (!$shippingMethod) $shippingMethod=$this->owner->Shipping()->Method;
		$shippingMethod = strtolower($shippingMethod);
		if ($shippingMethod=="express") $shipping = $shipping*1.25;
		return ($amount==0) ? 0 : $shipping;
	}

	function calcPaymentCosts($paymentMethod = null) {
		//write your own method for calculating the payment costs
		//payment methods are defined in the model [enumValues]
		$amount = $this->owner->amount();
		if (!$paymentMethod) $paymentMethod=strtolower($this->owner->Payment()->Method);
		$paymentMethod = strtolower($paymentMethod);
		$payment = 0;
		if ($paymentMethod=="creditcard") {
			$payment = $amount * 0.01;
		}
		return $payment;
	}
	
	function calcDiscount() {
		//write your own method for calculating a discount, if needed
		$disc = 0;
		//example, education discount of 20%
		if (trim(strtoupper($this->owner->CouponCode))=="EDUCATION") $disc = $this->owner->Amount()*0.2;
		return $disc;
	}
	
	function calculate() {
		return;
	}
				
	function generateOrderKey() {
		//place your own rule to generate an order key
		$name = preg_replace(array("/Ä|ä/","/Ö|ö/","/Ü|ü/","/ß/"),array("ae","oe","ue","ss"), $this->owner->InvoiceAddress()->Surname
		);
		return "FW-".strtoupper(
			preg_replace("/[^A-Za-z0-9]/","",$name) . "-" . preg_replace("/[^A-Za-z0-9]/","",$this->owner->InvoiceAddress()->ZipCode) . "-" . $this->owner->ID
			);
		
	}
	
	function isComplete() {
		$required = array(
			"Total","PaymentID","ShippingID","InvoiceAddressID","DeliveryAddressID"
			);
		$check = true;
		foreach ($required as $r) {
			if (!($this->owner->hasValue($r))) $check = false;
		}
		if ($this->owner->amountBelowMin()) $check = false;
		return $check;
	}
			
}

?>