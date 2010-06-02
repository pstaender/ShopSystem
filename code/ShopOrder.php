<?php

/**
 * ShopOrder
 *
 * @package shopsystem
 * @author Philipp Staender <philipp.staender@gmail.com>
 * @description ShopOrder handles the shoppingsession...
 */

class ShopOrder extends DataObject {
	
	static $db = array(
		"Email"=>"Varchar(250)",
		"Hash"=>"Varchar(32)",
		"Status"=>"Enum('Unsubmitted,Ordered,Payed,Sended,Declared','Unsubmitted')",
		"Tax"=>"Int",
		"VAT"=>"Enum('INCL,EXCL','INCL')",
		"VATAmount"=>"Float",
		"ShippingCosts"=>"Float",
		"Discount"=>"Float",
		"SubTotal"=>"Float",
		"Total"=>"Float",
		"Currency"=>"Enum('EUR','EUR')",
		"IP"=>"Varchar(200)",
		"Payment"=>"Enum('Invoice,Prepayment','Invoice')",
		"Shipping"=>"Enum('Standard,Express','Standard')",
		"Note"=>"Text",
		"InternalNote"=>"Text",
		"TrackingID"=>"Varchar(250)",
		"TaxIDNumber"=>"Varchar(250)",
		"CouponCode"=>"Varchar(60)",
		"OrderKey"=>"Varchar(200)",
		);
	
	static $has_one = array(
		"Client"=>"ShopClient",
		"InvoiceAddress"=>"ShopAddress",
		"DeliveryAddress"=>"ShopAddress",
		);
		
	static $has_many = array(
		"Items"=>"ShopOrderItem",
		);
	
	static $summary_fields = array(
		"Status","Tax","VAT","ShippingCosts","Discount","SubTotal","Total","Client.FirstName","Client.Surname"
		);
		
	static $hashField = "shoppinghash";
	
	static $country = null;
	static $tax = array(
		"de_DE"=>"19"
		);
	static $currency = "EUR";
	static $vatType = "EXCL";
	static $minAmount = "10";
	
	static $emailOrderConfirmation = null;
	static $emailOrderShipped = null;
	static $emailInvoice = null;
	
	function amount($round = 2) {
		$sum = 0;
		foreach($this->Items() as $item) {
			$sum = $sum + ($item->Price*$item->Quantity);
		}
		return $round ? round($sum, $round) : $sum;
	}
	
	function amountBelowMin() {
		return ($this->amount()<=self::$minAmount) ? true : false;
	}
	
	function calculate($round = 2) {
		$amount = $this->amount();
		$tax = 1+($this->Tax/100);
		$this->ShippingCosts = $this->calcShippingCosts($this->Shipping);
		
		$this->Discount = $this->calcDiscount();
		$this->SubTotal = $this->Total = $amount - $this->Discount + $this->ShippingCosts;
		if ($this->VAT=="INCL") {
			$this->VATAmount = round($amount - ($this->Total / $tax),$round);
		}
		if ($this->VAT=="EXCL") {
			$this->VATAmount = round(($amount/100) * $this->Tax,$round);
			$this->Total = $this->Total + $this->VATAmount;
		}
		parent::calculate();
		return $this->write();
	}
	
	function minAmount() {
		return self::$minAmount;
	}
	
	function switchVAT() {
		if (!$inclOrExcl) {
			if ($this->VAT=="INCL") $this->VAT = "EXCL";
			else $this->VAT = "INCL";
		}
		return $this->calculate();
	}
		
	function calcShippingCosts($shippingMethod = null) {
		//define your own shipping rules with MyShopOrder.php
		return parent::calcShippingCosts($shippingMethod) ? parent::calcShippingCosts($shippingMethod) : $this->ShippigCosts;
	}
	
	function calcDiscount() {
		//define your own discount rules with MyShopOrder.php
		return parent::calcDiscount();// : abs($this->Discount);
	}
	
	function isComplete() {
		//define your own isComplete rules with MyShopOrder.php
		if (parent::isComplete()) {
			return true;
		} else {
			return false;
		}
	}
	
	function shippingMethodFields() {
		//define your own shipping cost fields in MyShopOrder.php
		return parent::shippingMethodFields();
	}
	
	function paymentMethodFields() {
		//define your own payment methods fields in MyShopOrder.php
		return parent::paymentMethodFields();
	}
				
	static function checkForSessionOrCreate() {
		if (!($session=Session::get(self::$hashField))) {
			//create session
			$hash = substr(md5(rand(0,1000).time()),0,10);
			$s = new ShopOrder();
			if ($m=Member::currentUser()) $s->Member = $m;
			$s->Hash = $hash;
			Session::set(self::$hashField,$hash);
			$s->IP = $_SERVER['REMOTE_ADDR'];
			$s->Currency = self::getLocalCurrency();
			$s->VAT = self::getVATType();
			$s->Tax = self::getLocalTax();
			$s->write();
			// else user_error("Couldn't create ShoppingSession...");
		} else {
			if (!($s= DataObject::get_one("ShopOrder","Hash = '".Convert::Raw2SQL($session)."' AND Status = 'Unsubmitted'"))) {
				$hash = substr(md5(rand(0,1000).time()),0,10);
				$s = new ShopOrder();
				if ($m=Member::currentUser()) $s->Member = $m;
				$s->Hash = $hash;
				Session::set(self::$hashField,$hash);
				$s->IP = $_SERVER['REMOTE_ADDR'];
				$s->Currency = self::getLocalCurrency();
				$s->VAT = self::getVATType();
				$s->Tax = self::getLocalTax();
				$s->write();
			}
		}
		return $s;
	}
	
	static function orderSession() {
		return self::checkForSessionOrCreate();
	}
	
	static function getLocalTax() {
		return self::$tax[self::getLocalCountry()];
	}
	
	static function getLocalCountry() {
		return self::$country ? self::$country : i18n::get_locale();
	}
	
	static function getLocalCurrency() {
		return self::$currency;
	}
	
	static function getVATType() {
		return self::$vatType;
	}

	static function addItem($id, $quantity = 1) {
		$id = (int) $id;
		if ($item = DataObject::get_by_id("ShopItem", $id)) {
			//map item to orderitem, similar to a quick snapshot of the soled item for later
			$orderSession = self::orderSession();
			if (!($orderItem = DataObject::get_one("ShopOrderItem","OrderID = ".$orderSession->ID." AND OriginalItemID = ".$item->ID))) $orderItem = new ShopOrderItem();
			if ($orderSession->Status=="Unsubmitted") {
				$orderItem->Version = $item->Version;
				$orderItem->Price = $item->Price;
				$orderItem->Title = $item->Title;
				$orderItem->Currency = $item->Currency;
				$orderItem->Quantity = $orderItem->Quantity + $quantity;
				$orderItem->OriginalItemID = $item->ID;
				$orderItem->OriginalItem = $item;
				$orderItem->OrderID = ShopOrder::orderSession()->ID;
				$orderItem->VAT = $item->VATType();
				if ($orderItem->Quantity<1) $orderItem->Quantity = 0;
				$orderItem->write();
				return $orderItem;
			} else {
				user_error("You are not allowed to change an already submitted order.");
			}
		}
	}
	
	function itemsCount() {
		$items = 0;
		foreach ($this->Items() as $it) {
			$items = $items+($it->Quantity);
		}
		return $items;
	}
	
	function generateOrderKey() {
		return parent::generateOrderKey();
	}
	
	function sendOrderConfirmationTo($email) {
		if ($from = self::$emailOrderConformation) {
			$email = New Email_Template();
			$email->from = $form;
			$email->to = $email;
			$email->subject = _t("Shop.Order.EmailSubject","%Thanks for your order%");
			$email->ss_template = 'EmailOrderConfirmation';
			$email->populateTemplate($this);
			$email->send();
		}
	}
	
	function sendOrderConfirmation() {
		 $this->sendOrderConfirmationTo($this->emailFromClient());
	}

	function sendInvoiceTo($email) {
		if ($from = self::$emailInvoice) {
			$email = New Email_Template();
			$email->from = $form;
			$email->to = $email;
			$email->subject = _t("Shop.Invoice.EmailSubject","%Your invoice for your order%");
			$email->ss_template = 'EmailInvoice';
			$email->populateTemplate($this);
			$email->send();
		}
	}
	
	function emailFromClient() {
		if ($client = $this->Client()) return $client->Email;
		if ($addr = $this->InvoiceAddress()) return $addr->Email;
		if ($addr = $this->DeliveryAddress()) return $addr->Email;
	}
	
	function sendInvoice() {
		 $this->sendInvoiceTo($this->emailFromClient());
	}

}

class ShopOrder_Controller extends Page_Controller {
	
	static $allowed_actions = array(
		"add",
		);
				
	function add($request = null, $id = null) {
		if (!$id) $id = Director::urlParam("ID");
		if ($id) {
			if (isset($_REQUEST['quantity'])) $quantity = (int) $_REQUEST['quantity'];
			$item = ShopOrder::addItem($id, $quantity);
			if (isset($_REQUEST['ref'])) {
				if ($_REQUEST['ref']=="item") {
					//redirect to product page
					Director::redirect($item->OriginalItem->Link());
				}
			}
		}
	}
	
}

?>