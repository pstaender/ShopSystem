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
		"Hash"=>"Varchar(32)",
		"Status"=>"Enum('Unsubmitted,Ordered,Payed,Sended,Declared','Unsubmitted')",
		"SubTotal"=>"Float",
		"Tax"=>"Float",
		"VAT"=>"Enum('INCL,EXCL','INCL')",
		"VATAmount"=>"Float",
		"ShippingCosts"=>"Float",
		"Discount"=>"Float",
		"Total"=>"Float",
		"Currency"=>"Enum('EUR','EUR')",
		"IP"=>"Varchar(200)",
		"Payment"=>"Enum('Invoice','Invoice')",
		"Shipping"=>"Enum('Standard,Express','Standard')",
		);
	
	static $has_one = array(
		"Client"=>"ShopCustomer",
		"BillingContact"=>"ShopAddress",
		"ShippingContact"=>"ShopAddress",
		"Member"=>"Member",
		);
		
	static $has_many = array(
		"Items"=>"ShopOrderItem",
		);
		
	static $hashField = "shoppinghash";
	
	static $country = null;
	static $tax = array(
		"de_DE"=>"19"
		);
	static $currency = "EUR";
	static $vatType = "EXCL";
	
	function amount($round = 2) {
		$sum = 0;
		foreach($this->Items() as $item) {
			$sum = $sum + ($item->Price*$item->Quantity);
		}
		return $round ? round($sum, $round) : $sum;
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
		return $this->write();
	}
	
	function switchVAT() {
		if (!$inclOrExcl) {
			if ($this->VAT=="INCL") $this->VAT = "EXCL";
			else $this->VAT = "INCL";
		}
		return $this->calculate();
	}
	
	// function VATAmount() {
		// return round(abs($this->SubTotal - $this->Total),2);
	// }
	
	function calcShippingCosts($shippingMethod = null) {
		//define your own shipping rules with MyShopOrder.php
		return parent::calcShippingCosts($shippingMethod) ? parent::calcShippingCosts($shippingMethod) : $this->ShippigCosts;
	}
	
	function calcDiscount() {
		//define your own discount rules with MyShopOrder.php
		return parent::calcDiscount() ? parent::calcDiscount() : abs($this->Discount);
	}
	
	function isComplete() {
		//define your own isComplete rules with MyShopOrder.php
		if (parent::isComplete()) {
			$this->Status = "Ordered";
			$this->write();
			return true;
		} else {
			return false;
		}
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
			$s->Currency = self::getCurrency();
			$s->VAT = self::getVAT();
			$s->Tax = self::getTax();
			$s->write();
			// else user_error("Couldn't create ShoppingSession...");
		} else {
			if (!($s= DataObject::get_one("ShopOrder","Hash = '".Convert::Raw2SQL($session)."'"))) {
				$hash = substr(md5(rand(0,1000).time()),0,10);
				$s = new ShopOrder();
				if ($m=Member::currentUser()) $s->Member = $m;
				$s->Hash = $hash;
				Session::set(self::$hashField,$hash);
				$s->IP = $_SERVER['REMOTE_ADDR'];
				$s->Currency = self::getCurrency();
				$s->VAT = self::getVAT();
				$s->Tax = self::getTax();
				$s->write();
			}
		}
		return $s;
	}
	
	static function orderSession() {
		return self::checkForSessionOrCreate();
	}
	
	static function getTax() {
		return self::$tax[self::getCountry()];
	}
	
	static function getCountry() {
		return self::$country ? self::$country : i18n::get_locale();
	}
	
	static function getCurrency() {
		return self::$currency;
	}
	
	static function getVAT() {
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