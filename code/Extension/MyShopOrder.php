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
	
	//just for custom use, not necessary for the shopsystem
	static $euStates = array(
		"BE","BG","DK","DE","EE","FI","FR","GR","IE","IT","LV","LT","LU","MT","NL","AT",
		"PL","PT","RO","SE","SK","SI","ES","CZ","HU","GB","CY"
		);
		
	static $required_fields = array(
		"Total","PaymentID","ShippingID","InvoiceAddressID","DeliveryAddressID"
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
		$country = strtoupper($this->owner->DeliveryAddress()->Country);
		
		//fw
		//Alle Länder
		$shipping = ($amount <= 199) ? 15.4 : 32.5;
		//EU
		if (in_array($country,self::$euStates)) $shipping = ($amount <= 199) ? 11.1 : 19.5;
		//DE
		if ($country=="DE") $shipping = ($amount <= 199) ? 3.6 : 9.5;
		//fw
		
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
		
		//fw
		//Preislachlass für bpp+ringfoto mitglieder
		if (strtolower($this->owner->CouponCode)=="bpp") {
			return $this->owner->amount()*0.15;
		}
		if (strtolower($this->owner->CouponCode)=="ringfoto") {
			return $this->owner->amount()*0.1;
		}
		return 0;
		//fw
		
		
		
		$disc = 0;
		//example, education discount of 20%
		if (trim(strtoupper($this->owner->CouponCode))=="EDUCATION") $disc = $this->owner->Amount()*0.8;
		return $disc;
	}
	
	function calculate() {
		//define your own calculation
		//set your value to these following fields
		//the values will be written to record in ShopOrder::calculate()
		
		//fw
		//wenn eu-land + UST angegebn, keine MwSt!
		$country = strtoupper($this->owner->InvoiceAddress()->Country);
		if ( in_array($country,self::$euStates) && (strlen(trim($this->owner->TaxIDNumber))>0) ) {
			$this->owner->VATAmount = 0.0;
		}
		//fw
		
		return true;
	}
				
	function generateOrderKey() {
		//place your own rule to generate an order key
		$name = preg_replace(array("/Ä|ä/","/Ö|ö/","/Ü|ü/","/ß/"),array("ae","oe","ue","ss"), $this->owner->InvoiceAddress()->Surname
		);
		$prefix = "order-";
		
		//fw
		$prefix = "FW-";
		//fw
		
		return $prefix.strtoupper(
			preg_replace("/[^A-Za-z0-9]/","",$name) . "-" . preg_replace("/[^A-Za-z0-9]/","",$this->owner->InvoiceAddress()->ZipCode) . "-" . $this->owner->ID
			);
		
	}
	
	function isComplete() {
		return (sizeof($this->isIncompleteCause())>0) ? false : true;
	}
	
	function isIncompleteCause() {
		$reasons = array();
		$required = self::$required_fields;
		$requiredFields = true;
		foreach ($required as $r) {
			if (!($this->owner->hasValue($r))) $requiredFields=false;
		}
		if (!$requiredFields) $reasons["MissingFields"] = _t("Shop.OrderIncomplete.MissingFields","%You have not filled out all necessary fields, yet%");
		if ($this->owner->amountBelowMin()) $reasons["AmountBelowMin"] = _t("Shop.OrderIncomplete.AmountBelowMin","%The amount is below minimum%");
		if (!$this->owner->hasValue("Email")) $reasons["Email"] = _t("Shop.OrderIncomplete.EmailMissing","%You have not given an Email-address%");
		if (!$this->owner->InvoiceAddress()->isComplete()) $reasons["InvoiceAddress"] = _t("Shop.OrderIncomplete.InvoiceAddressIncomplete","%Invoiceaddress is incomplete%");
		if (!$this->owner->DeliveryAddress()->isComplete()) $reasons["DeliveryAddress"] = _t("Shop.OrderIncomplete.DeliveryAddressIncomplete","%Deliveryaddress is incomplete%"); 
		return $reasons;
	}
	
	function incompleteReasonsForTemplate() {
		$string = "";
		foreach($this->owner->isIncompleteCause() as $field => $reason) {
			$string .= "<li class=\"incompleteReason\" id=\"{$field}Reason\">".$reason."</li>";
		}
		return "<ul class=\"missingFieldReasons\">".$string."</ul>";
	}
			
}

