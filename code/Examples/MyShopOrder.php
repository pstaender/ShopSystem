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
	
	static $required_fields = array(
		"Total",
		"PaymentID",
		"ShippingID",
		"InvoiceAddressID",
		"DeliveryAddressID"
		);
		
	function extraStatics() {
		//use it for define extra fields you need
		return array(
			'db' => array(
				'Weight' => 'Float',
				'OldID' => 'Int',
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
	
	function calcTax() {
		$country = strtoupper($this->owner->InvoiceAddress()->Country);
		if ((in_array($country,ShopOrder::$euStates)) && ($country!="DE")) $this->owner->Tax = 0.0;
		else $this->owner->Tax = 19.0;
		// exit($this->owner->TAX);
	}
	
	function calcDiscount() {
		//write your own method for calculating a discount, if needed
		$discount = 0;
		//fw
		//Preislachlass für bpp+ringfoto mitglieder
		if (strtolower($this->owner->CouponCode)=="bpp") {
			$discount = $this->owner->amount()*0.15;
		}
		if (strtolower($this->owner->CouponCode)=="ringfoto") {
			$discount = $this->owner->amount()*0.1;
		}
		$discount = 0;
		//fw
		
		//example, education discount of 20%
		if (trim(strtoupper($this->owner->CouponCode))=="EDUCATION") $discount = $this->owner->Amount()*0.8;
		$this->owner->Discount = $discount;
	}
	
	function calculate() {
		//define your own calculation
		//set your value to these following fields
		//the values will be written to record in ShopOrder::calculate()
		
		//fw
		//wenn eu-land + UST angegebn, keine MwSt!
		$country = strtoupper($this->owner->InvoiceAddress()->Country);
		if ( in_array($country,ShopOrder::$euStates) && (strlen(trim($this->owner->TaxIDNumber))>0) ) {
			$this->owner->VATAmount = 0.0;
		}
		//fw
		
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
		$this->owner->isIncomplete = (sizeof($this->isIncompleteCause())>0) ? false : true;
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
		$this->owner->isIncompleteReasons = $reasons;
	}
			
}

