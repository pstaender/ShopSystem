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
		"OrderKey"=>"Varchar(200)",
		"Status"=>"Enum('Unsubmitted,Ordered,Payed,Sended,Declared','Unsubmitted')",
		"Tax"=>"Int",
		"VAT"=>"Enum('INCL,EXCL','INCL')",
		"VATAmount"=>"Float",
		"Discount"=>"Float",
		"SubTotal"=>"Float",
		"Total"=>"Float",
		"Currency"=>"Enum('EUR','EUR')",
		"IP"=>"Varchar(200)",
		"Note"=>"Text",
		"InternalNote"=>"Text",
		"TaxIDNumber"=>"Varchar(250)",
		"CouponCode"=>"Varchar(60)",
		"PlacedOrderOn"=>"SS_DateTime",
		);
	
	static $has_one = array(
		"Client"=>"ShopClient",
		"InvoiceAddress"=>"ShopAddress",
		"DeliveryAddress"=>"ShopAddress",
		"Payment"=>"ShopPayment",
		"Shipping"=>"ShopShipping",
		"Invoice"=>"ShopInvoice",
		);
	
	static $belongs_to = array(
		"Client"=>"ShopClient"
		);
		
	static $has_many = array(
		"Items"=>"ShopOrderItem",
		"Events"=>"ShopOrderEvent",
		);
	
	static $summary_fields = array(
		"ID",
		"OrderKey",
		"Status",
		"PlacedOrderOn",
		"Email",
		"Tax",
		"VAT",
		"Shipping.Price",
		"Discount",
		"SubTotal",
		"Total",
		"Client.Email",
		"Client.ClientKey",
		"IP",
		"InvoiceAddress.FirstName",
		"InvoiceAddress.Surname",
		"Note",
		);
		
	static $searchable_fields = array(
		"ID",
		"Status",
		"Email",
		"OrderKey",
		"InvoiceAddress.FirstName",
		"InvoiceAddress.Surname",
		);
	
	static $default_sort = 'LastEdited DESC';
	
	static $casting = array(
	  'Amount' => 'Float',
		'VATAmount' => 'Float',
		'Discount' => 'Float',
		'ShippingCosts' => 'Float',
		'PaymentCosts' => 'Float',
	);
	
	static $hashField = "shoppinghash";
	
	static $country = null;
	static $tax = array(
		"de_DE"=>"19",
		);
	static $taxDefault = 0;
	static $currency = array(
		"de_DE"=>"EUR",
		);
	static $currencyDefault = "EUR";
	static $vatType = "EXCL";
	static $minAmount = "10";
	
	static $emailOrderConfirmation = null;
	static $emailOrderShipped = null;
	static $emailInvoice = null;
	static $emailUserAccount = null;
	
	static $euStates = array(
		"BE","BG","DK","DE","EE","FI","FR","GR","IE","IT","LV","LT","LU","MT","NL","AT",
		"PL","PT","RO","SE","SK","SI","ES","CZ","HU","GB","CY"
		);
	
	static $displayExtensionNotice = true;
	private static $extensionNoticesDisplayed = array();
	
	static $round = 2;
	
	function getCMSFields() {
		$fields = parent::getCMSFields();
		$invoice = null;
		if ($this->Invoice()) $invoice = new LiteralField("InvoiceLink",'<a href="'.$this->Invoice()->Link().'" target="_invoicewindow">'._t("Shop.Invoice.ViewInvoice","%View Invoice%").'</a>');
		$fields->addFieldsToTab('Root.Main',array(
			new HeaderField(_t("Shop.Invoice.Invoice","%Invoice%"),3),
			$invoice,
			));
		$fields->replaceField(
			"InvoiceAddressID",
			new LiteralField("InvoiceAddress","<h4>"._t("Shop.Checkout.Invoiceaddress","%InvoiceAddress%")." </h4><h5>#".$this->InvoiceAddressID."</h5>".$this->InvoiceAddress()->Nice())
			);
		$fields->replaceField(
			"DeliveryAddressID",
			new LiteralField("DeliveryAddress","<h4>"._t("Shop.Checkout.Deliveryaddress","%DeliveryAddress%")." </h4><h5>#".$this->DeliveryAddressID."</h5>".$this->DeliveryAddress()->Nice())
			);
		if ($this->Shipping()) {
			$fields->replaceField(
				"ShippingID",
				new LiteralField("ShippingTitle","<h4>"._t("Shop.Shipping.".$this->Shipping()->Method,"%ShippingMethod%")." </h4><h5>#".$this->ShippingID."</h5>")
				);
			$fields->insertAfter(
				new LiteralField("ShippingPrice","<h5>".$this->Shipping()->Price." ".$this->Currency."</h5>"),
				"ShippingTitle"
				);
		}
		if ($this->Payment()) {
			$fields->replaceField(
				"PaymentID",
				new LiteralField("PaymentTitle","<h4>"._t("Shop.Payment.".$this->Payment()->Method,"%PaymentMethod%")." </h4><h5>#".$this->PaymentID."</h5>")
				);
			$fields->insertAfter(
				new LiteralField("PaymentPrice","<h45>".$this->Payment()->Price." ".$this->Currency."</h5>"),
				"PaymentTitle"
				);
		}
		
		return $fields;
	}
	
	function amount($round = 2) {
		$sum = 0;
		foreach($this->Items() as $item) {
			$sum = $sum + ($item->Price()*$item->Quantity);
		}
		return $round ? round($sum, $round) : $sum;
	}
	
	function amountBelowMin() {
		return ($this->amount()<=self::$minAmount) ? true : false;
	}
	
	function calculate() {
		$round = self::$round;
		$amount = $this->amount();
		$this->calcShippingCosts();
		$this->calcPaymentCosts();
		$this->calcDiscount();
		
		$this->SubTotal = $amount - $this->Discount +$this->Shipping()->Price();
		$this->Total = $this->SubTotal + $this->Payment()->Price();
		
		$this->calcTax();
		$tax = 1+($this->Tax/100);
		if ($this->VAT=="INCL") {
			$this->VATAmount = round($amount - ($this->Total / $tax),$round);
		}
		if ($this->VAT=="EXCL") {
			$this->VATAmount = round(($amount/100) * $this->Tax,$round);
			$this->Total = $this->Total + $this->VATAmount;
		}
		try {
			parent::calculate();
		} catch (Exception $e) {
			//don't display a message, because it's not necessary/bestpractice to override the calculate method
		}
	}
	
	function DoCalculate() {
		$this->calculate();
		return null;
	}
	
	function calculateAndWrite() {
		$this->calculate();
		$this->Shipping()->write();
		$this->Payment()->write();
		$this->write();
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
	
	function shippingCosts($shippingMethod = null) {
		$this->calcShippingCosts($shippingMethod);
		return $this->Shipping()->Price();
	}
		
	function calcShippingCosts($shippingMethod = null) {
		if ($shipping=$this->Shipping()) return $shipping->calculate($shippingMethod);
	}

	function calcPaymentCosts($paymentMethod=null) {
		if ($payment=$this->Payment()) return $payment->calculate($paymentMethod);
	}
	
	function calcTax() {
		try {
			parent::calcTax();
			return true;
		} catch (Exception $e) {
			ShopOrder::displayExtensionNoticeFor("ShopOrder::calcTax");
		}
	}
	
	function calcDiscount() {
		try {
			parent::calcDiscount();
			return true;
		} catch (Exception $e) {
			ShopOrder::displayExtensionNoticeFor("ShopOrder::calcDiscount");
		}
	}
	
	function isIncomplete() {
		return ($this->isComplete()) ? false : true;
	}
	
	function isComplete($default=true) {
		try {
			return parent::isComplete();
		} catch(Exception $e) {
			ShopOrder::displayExtensionNoticeFor("ShopOrder::isComplete");
			return (boolean) $default;
		}
	}
	
	function isIncompleteCause() {
		//define your own isIncompleteCause rules with MyShopOrder.php
		$this->isIncompleteReasons = null;
		try {
			$this->isIncompleteReasons = parent::isIncompleteCause();
			return $this->isIncompleteReasons;
		} catch(Exception $e) {
			ShopOrder::displayExtensionNoticeFor("ShopOrder::isIncompleteCause");
			return null;
		}
	}
	
	function incompleteReasonsForTemplate() {
		$string = "";
		if ($this->isIncompleteReasons) foreach($this->isIncompleteReasons as $field => $reason) {
			$string .= "<li class=\"incompleteReason\" id=\"{$field}Reason\">".$reason."</li>";
		}
		return "<ul class=\"missingFieldReasons\">".$string."</ul>";
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
			$s->write();//generate ID
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
		//create address fields for shipping+invoicing
		//is now done in ShopCheckoutPage --> doSubmitEmailForm

		if (!($s->PaymentID>0)) {
			$p = new ShopPayment();
			$p->Price = 0;
			$p->OrderID = $s->ID;
			$p->write();
			$s->PaymentID = $p->ID;
		}
		if (!($s->ShippingID>0)) {
			$d = new ShopShipping();
			$d->OrderID = $s->ID;
			$d->Price = 0;
			$d->write();
			$s->ShippingID = $d->ID;
		}
		//save session
		$s->write();
		return $s;
	}
	
	static function orderSession() {
		return self::checkForSessionOrCreate();
	}
	
	static function getLocalTax() {
		return isset(self::$tax[self::getLocalCountry()]) ? self::$tax[self::getLocalCountry()] : self::$taxDefault;
	}
	
	static function getLocalCountry() {
		return self::$country ? self::$country : i18n::get_locale();
	}
	
	static function getLocalCurrency() {
		return isset(self::$currency[self::getLocalCountry()]) ? self::$currency[self::getLocalCountry()] : self::$currencyDefault;
	}
	
	static function getVATType() {
		return self::$vatType;
	}

	static function addItem($id, $quantity = 1, $optionID = null) {
		$id = (int) $id;
		if ($item = DataObject::get_by_id("ShopItem", $id)) {
			//select first option, if none option is selected
			if ( ($item->Options()->Count()) && ($optionID==null) ) {
				$optionID = ($item->Options()) ? $item->Options()->First()->ID : (int) $optionID;
			}
			$optionSQL = ($optionID>0) ? " AND OptionID = $optionID " : "";
			//map item to orderitem, similar to a quick snapshot of the soled item for later
			$orderSession = self::orderSession();
			if (!($orderItem = DataObject::get_one("ShopOrderItem","OrderID = ".$orderSession->ID." AND OriginalItemID = ".$item->ID." ".$optionSQL))) $orderItem = new ShopOrderItem();
			if ($orderSession->Status=="Unsubmitted") {
				$orderItem->Version = $item->Version;
				$orderItem->Price = $item->Price;
				$orderItem->Title = $item->Title;
				$orderItem->ProductKey = $item->ProductKey;
				$orderItem->Currency = $item->Currency;
				$orderItem->Quantity = $orderItem->Quantity + $quantity;
				$orderItem->OriginalItemID = $item->ID;
				$orderItem->OriginalItem = $item;
				$orderItem->OrderID = ShopOrder::orderSession()->ID;
				$orderItem->VAT = $item->VATType();
				//if option is selected
				if ($optionID) {
					foreach($item->Options() as $option) {
						$optionFound=true;
						if ($option->ID==$optionID) {
							//if option belongs to item, add option
							$orderItem->OptionID = $option->ID;
						}
					}
					if (!$optionFound) $orderItem->OptionID = $item->Options()->First()->ID;
				}
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
	
	function statusTranslated() {
		return _t("Shop.Order.Status.".$this->Status,"%".$this->Status."%");
	}
	
	function sendOrderConfirmationTo($emailAddr) {
		if ($from = ShopOrder::getEmailFor("OrderConfirmation")) {
			$email = new Email();
			$email->from = $from;
			$email->to = $emailAddr;
			$email->subject = _t("Shop.Order.EmailSubject","%Thanks for your order%");
			$email->ss_template = 'EmailOrderConfirmation';
			$email->populateTemplate($this);
			$email->send();
		}
	}
		
	function sendOrderConfirmation($email=null) {
		if ($email==null) $email = $this->emailFromClient();
		$this->sendOrderConfirmationTo($email);
	}

	function emailFromClient() {
		if ($client = $this->Client()) if ($client->Status=="Customer") return $client->Email;
		else return $this->Email;
	}

	static function getEmailFor($section) {
		switch (strtolower($section)) {
			case "orderconfirmation" :
				return (self::$emailOrderConfirmation==null) ? Email::getAdminEmail() : self::$emailOrderConfirmation;
				break;
			case "ordershipped" :
				return (self::$emailOrderShipped==null) ? Email::getAdminEmail() : self::$emailOrderShipped;
				break;
			case "invoice" :
				return (self::$emailInvoice==null) ? Email::getAdminEmail() : self::$emailInvoice;
				break;
			case "useraccount" :
				return (self::$emailUserAccount==null) ? Email::getAdminEmail() : self::$emailUserAccount;
				break;
		}
	}
	
	function onBeforeWrite() {
		parent::onBeforeWrite();
		$changed = $this->getChangedFields();
		if (isset($changed['Status'])) {
			if ($changed['Status']['before']!=$changed['Status']['after']) {
					//Status has changed, so add an "history" event
					$event = new ShopOrderEvent();
					$event->Title = $this->Status;
					$event->Description = "Changed status from '".$changed['Status']['before']."' to '".$changed['Status']['after'];
					$event->OrderID = $this->ID;
					$event->Status = "Closed";
					$event->write();
			}
		}
	}
	
	static function displayExtensionNoticeFor($function) {
		//$function = MyClass::myFunction
		if (self::$displayExtensionNotice) if (!isset(self::$extensionNoticesDisplayed[$function])) echo self::$extensionNoticesDisplayed[$function]="<p><strong>Notice:</strong> You should add an extension class for <strong>'".substr($function,0,strpos($function,":"))."'</strong> to extend <strong>$function()</strong> with your own method...</p>";
	}
	
	static function doDisplayExtensionNotice() {
		self::$displayExtensionNotice=true;
	}
	
	static function dontDisplayExtensionNotice() {
		self::$displayExtensionNotice=false;
	}
	
}

class ShopOrder_Controller extends Page_Controller {
	
	static $allowed_actions = array(
		"add",
		"dev"=>"ADMIN",
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
	
	function dev() {
		if ($email = Debug::get_send_errors_to()) return array();
		else {
			user_error("You haven't set a debug eMail... please do in your '_config.php' so to proceed... <pre>Debug::send_errors_to('your@email.com');</pre>");
		}
	}
	
	function sendToEmail() {
		return Debug::get_send_errors_to();
	}
	
}