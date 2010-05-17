<?php

class ShopCheckoutPage extends SiteTree {
	
	static $db = array(
		"ContentContact"=>"HTMLText",
		"ContentShippingAddress"=>"HTMLText",
		"ContentShipping"=>"HTMLText",
		"ContentPayment"=>"HTMLText",
		"ContentSummary"=>"HTMLText",
		"ContentComplete"=>"HTMLText",
		"ContentError"=>"HTMLText",
		);
	
	static $has_one = array(
		);
		
	function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->addFieldsToTab("Root.Content.Shop", array(
			new HTMLEditorField('ContentContact', _t('Shop.CheckoutPage.ContactText','%Contact Address Text%')),
			));
		return $fields;
	}
	
	function requireDefaultRecords() {
		parent::requireDefaultRecords();
		// default ceckoutpages
		if($this->class == 'ShopCheckoutPage') {
			if(!DataObject::get('ShopCheckoutPage')) {
				$p = new ShopCheckoutPage();
				$p->Title = _t('Shop.CheckoutPageTitleDefault', 'Checkout');
				$p->Content = _t('SiteTree.CheckoutPageContentDefault', '
				<h2>Welcome to the checkout Page</h2>
				<p>Please feel free to replace this text with your own shopinformations...</p>
				');
				$p->URLSegment = 'checkout';
				$p->Status = 'Published';
				$p->write();
				$p->publish('Stage', 'Live');
				$p->flushCache();
				// $p->ContentContact = "<h2>Billing Address</h2>
				// <p>Please fill out the billing address.</p>";
				// $p->ContentShippingAddress = "<h2>Shipping Address</h2>
				// <p>Please fill out the shipping address.</p>";
				// $p->ContentShipping = "<h2>Shipping Method</h2>
				// <p>Please select your preferred shipping method.</p>";
				// $p->ContentPayment = "<h2>Payment Method</h2>
				// <p>Please choose your preffered payment methos.</p>";
				// $p->ContentSummary = "<h2>Order summary</h2>
				// <p>Take a look of your order and click on „Place order” to finish your shopping session.</p>";
				// $p->ContentComplete = "<h2>Thanks for your order!</h2>
				// <p>Your order has been submitted.</p>
				// <p>You'll recieve an eMail with your order details soon.</p>";
				// $p->ContentError = "<h2>Order could not be placed</h2>
				// <p>Sorry, but your order couldn't proceed complety. Please contact us for further information. Thanks!</p>";
				// $p->write();
				DB::alteration_message('ShopCheckoutPage created', 'created');
			}
		}
	}	
}

class ShopCheckoutPage_Controller extends ShopController {
	
	static $allowed_actions = array(
		"contact","shippingaddress","shipping","payment","summary","complete",
		"ContactForm","ShippingAddressForm","ShippingMethodForm","PaymentMethodForm"
		);
		
		
	function complete() {
		$session = ShopOrder::orderSession();
		if ($session->isComplete()) {
			//create invoice
			$invoice = new ShopInvoice();
			$invoice->PublicURL = substr(md5(rand(0,99999)/time()),0,6);
			$invoice->OrderID = $session->ID;
			$invoice->DateOfDelivery = time();
			$invoice->write();
			//send email with invoice link
			return array();
		} else {
			$this->ContentError = "Error";
			return array();
		}
	}
		
	function ContactForm() {
		$form = $this->createContactForm(new FormAction('doSubmitContact', _t("Shop.Form.Next","%Next%")), "ContactForm", new CheckboxField('UseContactForShipping', _t("Shop.Contact.UseContactForShipping","%UseContactForShipping%")));
		
		if ($address = DataObject::get_by_id("ShopAddress",ShopOrder::orderSession()->InvoiceAddressID)) $form->loadDataFrom($address);
		
		return $form;
	}
	
	function ShippingAddressForm() {
		$form = $this->createContactForm(new FormAction('doSubmitContact', _t("Shop.Form.Next","%Next%")), "ShippingAddressForm", new HiddenField('ThisIsShippingAddress',"ThisIsShippingAddress","true"));
		
		if ($address = DataObject::get_by_id("ShopAddress",ShopOrder::orderSession()->DeliveryAddressID)) $form->loadDataFrom($address);
		
		return $form;
	}
	
	private function createContactForm(FormAction $formAction, $formName, $additionalField = null) {
		//use all fields + translate them
		$contact = singleton("ShopAddress");
		$labels = array();
		foreach (ShopAddress::$db as $field => $type) {
			$restrictedFields[] = $field;
			$labels = array_merge($labels,array(
				$field => _t("Shop.Contact.$field","%{$field}%")
				));
		}
		$contact->set_stat("field_labels",$labels);
		$actions = new FieldSet(
		   $formAction
		);
		$validator = new RequiredFields(ShopAddress::$required_fields);
		$fields = $contact->getFrontendFields($restrictedFields);
		if ($additionalField) $fields->push($additionalField);
		return new Form($this, $formName, $fields, $actions, $validator);
	}
	
	function doSubmitContact($data, $form) {
		$session = ShopOrder::orderSession();
		if (!($contact = DataObject::get_by_id("ShopAddress",
		$session->InvoiceAddressID))) $contact = new ShopAddress();
		$action = "contact";
		if ($data['ThisIsShippingAddress']=="true") {
			$action = "shippingaddress";
			if (!($shipping = DataObject::get_by_id("ShopAddress",$session->DeliveryAddressID))) $shipping = new ShopAddress();
			$form->saveInto($shipping);
			$shipping->OrderID = $session->ID;
			$shipping->write();
			$session->DeliveryAddressID = $shipping->ID;
			$session->write();
			$link = $this->step($this->step($action)->Next)->Next;
		} else {
			$form->saveInto($contact);
			$contact->OrderID = $session->ID;
			$contact->write();
			$session->InvoiceAddressID = $contact->ID;
			$session->write();
		}
		if ($data['UseContactForShipping'])  {
			if (!($shipping = DataObject::get_by_id("ShopAddress",$session->DeliveryAddressID))) $shipping = new ShopAddress();
			$form->saveInto($shipping);
			$shipping->OrderID = $session->ID;
			$shipping->write();
			$session->DeliveryAddressID = $shipping->ID;
			$session->write();
			$link = $this->step($this->step($action)->Next)->Next;			
		} else {
			$link = $this->step($action)->Next;
		}
		Director::redirect($this->Link().$link);
	}
	
	function ShippingMethodForm() {
		//let visitor choose the shipping method
		$shippingMethods = singleton('ShopOrder')->dbObject('Shipping')->enumValues();
		$ship = array();
		$order = ShopOrder::orderSession();
		foreach ($shippingMethods as $name => $value) {
			$ship[$name] = _t("Shop.Shipping.{$value}","%{$value}%")." (".$order->calcShippingCosts($name)." ".ShopOrder::getCurrency().")";
			// $value = $value ."()";
		}
		$form = new Form(
			$this,
			"ShippingMethodForm",
			new FieldSet(
				new DropdownField("ShippingMethod",_t("Shop.Checkout.ShippingMethod","%ShippingMethod%"), $ship, ShopOrder::orderSession()->Shipping)
				),
			new FormAction("doSubmitShippingMethodForm",_t("Shop.Form.Next","%Next%")),
			new RequiredFields(
				"ShippingMethod"
				)
			);
			return $form;
	}
	
	function doSubmitShippingMethodForm($data, $form) {
		$session = ShopOrder::orderSession();
		$action = "shipping";
		$session->Shipping = $data['ShippingMethod'];
		$session->calculate();
		$session->write();
		Director::redirect($this->Link().$this->step($action)->Next);
	}
	
	function PaymentMethodForm() {
		//let visitor choose the payment method
		$paymentMethods = singleton('ShopOrder')->dbObject('Payment')->enumValues();
		$pay = array();
		$order = ShopOrder::orderSession();
		foreach ($paymentMethods as $name => $value) {
			$pay[$name] = _t("Shop.Payment.{$value}","%{$value}%");
		}
		$form = new Form(
			$this,
			"PaymentMethodForm",
			new FieldSet(
				new DropdownField("PaymentMethod",_t("Shop.Checkout.PaymentMethod","%PaymentMethod%"), $pay, ShopOrder::orderSession()->Payment)
				),
			new FormAction("doSubmitPaymentMethodForm",_t("Shop.Form.Next","%Next%")),
			new RequiredFields(
				"PaymentMethod"
				)
			);
			return $form;
	}
	
	function doSubmitPaymentMethodForm($data, $form) {
		$session = ShopOrder::orderSession();
		$action = "payment";
		$session->Payment = $data['PaymentMethod'];
		$session->write();
		Director::redirect($this->Link().$this->step($action)->Next);
	}
	
	function step($action = null) {
		$order = array("", "contact","shippingaddress","shipping","payment","summary","complete");
		if (!$action) $action = Director::urlParam("Action");
		if ($found=array_search($action,$order)) {
			if (($found+1)>sizeof($order)) $next = $found;
			else $next = $found+1;
			if (($found-1)<1) $prev = $found;
			else $prev = $found-1;
		} else {
			$next = 1;
			$prev = 0;
		}
		return new ArrayData(array(
			"Next"=>$order[$next],
			"Curr"=>$action,
			"Prev"=>$order[$prev],
			"NextText"=>_t("Shop.Checkout.".ucfirst($order[$next]),"%".ucfirst($order[$next]."%")),
			"CurrText"=>_t("Shop.Checkout.".ucfirst($action),"%".ucfirst($action."%")),
			"PrevText"=>_t("Shop.Checkout.".ucfirst($order[$prev]),"%".ucfirst($order[$prev])."%"),
			
			));
	}
	
}

?>