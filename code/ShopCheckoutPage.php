<?php

class ShopCheckoutPage extends SiteTree {
	
	static $db = array(
		"ContentEmail"=>"HTMLText",
		"ContentInvoiceAddress"=>"HTMLText",
		"ContentDeliveryAddress"=>"HTMLText",
		"ContentShipping"=>"HTMLText",
		"ContentPayment"=>"HTMLText",
		"ContentSummary"=>"HTMLText",
		"ContentComplete"=>"HTMLText",
		"ContentError"=>"HTMLText",
		"ContentMinimalAmount"=>"HTMLText",
		);
	
	static $has_one = array(
		);
		
	function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->addFieldsToTab("Root.Content.Shop", array(
			new HTMLEditorField('ContentInvoiceAddress', _t('SHOP.CheckoutPage.ContentInvoiceAddress','%ContentInvoiceAddress%')),
			new HTMLEditorField('ContentDeliveryAddress', _t('Shop.CheckoutPage.ContentDeliveryAddress','%ContentDeliveryAddress%')),
			new HTMLEditorField('ContentShipping', _t('Shop.CheckoutPage.ContentShipping','%ContentShipping%')),
			new HTMLEditorField('ContentPayment', _t('Shop.CheckoutPage.ContentPayment','%ContentPayment%')),
			new HTMLEditorField('ContentSummary', _t('Shop.CheckoutPage.ContentSummary','%ContentSummary%')),
			new HTMLEditorField('ContentComplete', _t('Shop.CheckoutPage.ContentComplete','%ContentComplete%')),
			new HTMLEditorField('ContentError', _t('Shop.CheckoutPage.ContactError','%ContentError%')),
			new HTMLEditorField('ContentMinimalAmount', _t('Shop.CheckoutPage.ContentMinimalAmount','%ContentMinimalAmount%')),

			));
		return $fields;
	}
	
	function requireDefaultRecords() {
		parent::requireDefaultRecords();
		// default checkoutpage
		if($this->class == 'ShopCheckoutPage') {
			
			//create usergroup for shop client
			if (!DataObject::get_one("Group", "Title LIKE 'ShopClients'")) {
				$group = new Group();
				$group->Title = "ShopClients";
				$group->Code = "shop-clients";
				$group->Sort = (DataObject::get_one("Group","1",null,"Sort DESC")->Sort)+1;
				$group->write();
				DB::alteration_message('ShopClients usergroup created', 'created');
			}
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
				// $p->ContentInvoiceAddress = "<h2>Billing Address</h2>
				// <p>Please fill out the billing address.</p>";
				// $p->ContentDeliveryAddress = "<h2>Shipping Address</h2>
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
		"email","invoiceaddress","deliveryaddress","shipping","payment","summary","complete","empty", "minamount", "incomplete",
		"EmailForm","InvoiceAddressForm","ShippingAddressForm","ShippingMethodForm","PaymentMethodForm","SummaryForm"
		);
	
	static $steps = array(
		"index",
		"email",
		"invoiceaddress",
		"deliveryaddress",
		"shipping",
		"payment",
		"summary",
		"complete",
		);
		
	function init() {
		parent::init();		
		//optional, remove if you don't like a minimal amount check
		if ($this->Cart()->Amount()<ShopOrder::$minAmount) {
			if (!Director::urlParam("Action")=="minamount") Director::redirect($this->Link()."minamount");
			return;
		}
	}
		
	function index() {
		// todo, make it usabilitysafe for "all" users
		if (ShopOrder::orderSession()->isComplete()) {
			if ($step = self::getCheckoutStep()) $this->redirectToNextStep(self::$steps[$step]);	
		} else {
			//optional, deactive if you don't wanna skip the 1st step 'index'
			$this->redirectToNextStep("index");
		}
		return array();
	}
		
	function EmailForm() {
		self::setCheckoutStep(0);
		$clientKey = $email = null;
		$order = ShopOrder::orderSession();
		if (ShopOrder::orderSession()->Client()->ID>0) {
			$client = ShopOrder::orderSession()->Client();
			$email = $client->Email;
			$clientKey = $client->ClientKey;
		} else {
			$email = $order->Email;
		}
		return new Form(
			$this,
			"EmailForm",
			new FieldSet(
				new EmailField("Email",_t("Shop.Checkout.Email","%Email%"),$email),
				new TextField("ClientKey",_t("Shop.Checkout.ClientKey","%ClientKey%"),$clientKey),
				new TextField("CouponCode",_t("Shop.Checkout.CouponCode","%CouponCode%"),$order->CouponCode),
				new TextField("TaxIDNumber",_t("Shop.Checkout.TaxIDNumber","%TaxIDNumber%"),$order->TaxIDNumber)
			),	
			new FormAction('doSubmitEmailForm', _t("Shop.Form.Next","%Next%")),
			new RequiredFields("Email")
			);
	}
	
	function doSubmitEmailForm($data,$form) {
		$email = trim(Convert::Raw2SQL($data['Email']));
		$clientKey = trim(Convert::Raw2SQL($data['ClientKey']));
		$order = ShopOrder::orderSession();
		$clientID = 0;
		if ($client = DataObject::get_one("ShopClient","ClientKey LIKE '".$clientKey."'")) {
			//existing client
			$clientID = $client->ID;
			if ($lastOrder = DataObject::get_one("ShopOrder","ClientID = $clientID")) {				
				if ($addr = $lastOrder->InvoiceAddress()) {
					//use the last invoice adress, if empty
					if ($order->InvoiceAddress()->ID==0) {									
						$newAddr = new ShopAddress();
						$newAddr->merge($addr,'right');
						//restore the relations
						$newAddr->OrderID = $order->ID;
						$newAddr->ClientID = $clientID;
						$newAddr->write();
						$order->InvoiceAddressID = $newAddr->ID;					
						$order->write();
					}
				}
				if ($addr = $lastOrder->DeliveryAddress()) {
					//use the last shipping adress, if empty
					if ($order->DeliveryAddress()->ID==0) {
						$newAddr = new ShopAddress();
						$newAddr->merge($addr,'right');
						//restore the relations
						$newAddr->OrderID = $order->ID;
						$newAddr->ClientID = $clientID;
						$newAddr->write();
						$order->DeliveryAddressID = $newAddr->ID;					
						$order->write();
					} 
				}
			}
		} else {
			//create new client, if not exists
			if (!(DataObject::get_one("ShopClient","Email LIKE '".$email."'"))) {
				$client = new ShopClient();
				$client->Email = $email;
				$client->ClientKey = ShopClient::generateClientKey($email);
				$client->Password = $client->ClientKey;				
				$client->write();
				//add to group
				$client->Groups()->add(DataObject::get_one("Group","Title LIKE 'ShopClients'"));
				$client->write();
				$clientID = $client->ID;
				//todo, send welcome email to client
			}
			//create aadress fields for invoice+shipping
			if ($order->InvoiceAddress()->ID==0) {
				$a = new ShopAddress();
				$a->ClientID = $clientID;
				$a->Email = $email;
				$a->OrderID = $order->ID;
				$a->write();
				$order->InvoiceAddressID = $a->ID;
			}
			if ($order->DeliveryAddress()->ID==0) {
				$a = new ShopAddress();
				$a->ClientID = $clientID;
				$a->Email = $email;
				$a->OrderID = $order->ID;
				$a->write();
				$order->DeliveryAddressID = $a->ID;
			}
		}
		$order->Email = $email;
		$order->ClientID = $clientID;
		$order->CouponCode = strtoupper(Convert::raw2SQL($data['CouponCode']));
		$order->TaxIDNumber = Convert::raw2SQL($data['TaxIDNumber']);
		$order->write();
		$this->redirectToNextStep("email");
		return array();
	}
		
	function complete() {
		$session = ShopOrder::orderSession();
		if ($session->isComplete()) {
			//create invoice
			$session->Status = "Ordered";
			$session->OrderKey = $session->generateOrderKey();
			$session->write();
			
			$invoice = new ShopInvoice();
			$invoice->PublicURL = ShopInvoice::generatePublicURL();
			$invoice->OrderID = $session->ID;
			$invoice->DateOfDelivery = time();
			$invoice->DateOfInvoice = null;
			$invoice->write();
			$invoice->InvoiceKey = $invoice->ID;
			$invoice->write();
			$this->setCheckoutStep(0);
			//send email with invoice link
			return array();
		} else {
			Director::redirect($this->dataRecord->Link()."incomplete");
			return array();
		}
	}
		
	function InvoiceAddressForm() {
		self::setCheckoutStep(1);
		$form = $this->createContactForm(new FormAction('doSubmitInvoiceAddress', _t("Shop.Form.Next","%Next%")), "InvoiceAddressForm", new CheckboxField('UseContactForShipping', _t("Shop.Contact.UseContactForShipping","%UseContactForShipping%")));
		if ($data = ShopOrder::orderSession()->InvoiceAddress()) $form->loadDataFrom($data);
		return $form;
	}
	
	function ShippingAddressForm() {
		self::setCheckoutStep(2);
		//todo, eigene methode fuer submit
		$form = $this->createContactForm(new FormAction('doSubmitInvoiceAddress', _t("Shop.Form.Next","%Next%")), "ShippingAddressForm", new HiddenField('ThisIsShippingAddress',"ThisIsShippingAddress","true"));
		//load existing data into form
		if ($data = ShopOrder::orderSession()->DeliveryAddress()) $form->loadDataFrom($data);
		return $form;
	}
	
	function doSubmitInvoiceAddress($data, $form) {
		$session = ShopOrder::orderSession();
		if (!($contact = DataObject::get_by_id("ShopAddress",
		$session->InvoiceAddressID))) $contact = new ShopAddress();
		if ($data['ThisIsShippingAddress']=="true") {
			if (!($shipping = DataObject::get_by_id("ShopAddress",$session->DeliveryAddressID))) $shipping = new ShopAddress();
			$form->saveInto($shipping);
			$shipping->OrderID = $session->ID;
			$shipping->write();
			$session->DeliveryAddressID = $shipping->ID;
			$session->write();
			return $this->redirectToNextStep("deliveryaddress");
		} else {
			$form->saveInto($contact);
			$contact->OrderID = $session->ID;
			$contact->write();
			$session->InvoiceAddressID = $contact->ID;
			$session->write();
		}
		if ($member = $session->Client()) {
			//insert firtsname + surname to member
			if (!$member->FirstName) $member->FirstName = Convert::raw2SQL($data['FirstName']);
			if (!$member->Surname) $member->Surname = Convert::raw2SQL($data['Surname']);
			$member->write();
		}
		if ($data['UseContactForShipping'])  {
			if (!($shipping = DataObject::get_by_id("ShopAddress",$session->DeliveryAddressID))) $shipping = new ShopAddress();
			$form->saveInto($shipping);
			$shipping->OrderID = $session->ID;
			$shipping->write();
			$session->DeliveryAddressID = $shipping->ID;
			$session->write();
			return $this->redirectToNextStep("deliveryaddress");			
		} else {
			return $this->redirectToNextStep("invoiceaddress");
		}
	}
	
	function ShippingMethodForm() {
		self::setCheckoutStep(3);
		//let visitor choose the shipping method
		$order = ShopOrder::orderSession();
		$shipping = singleton("ShopShipping");
		$labels = array();
		//translate labels
		foreach (ShopShipping::$db as $field => $type) {
			$labels = array_merge($labels,array(
				$field => _t("Shop.Shipping.$field","%{$field}%")
				));
		}
		$shipping->set_stat("field_labels",$labels);
		$validator = new RequiredFields(ShopShipping::$required_fields);
		$fields = $shipping->getFrontendFields($restrictedFields=array(
			"restrictFields" => array("Method"),
			));
		$form = new Form(
			$this,
			"ShippingMethodForm",
			$fields,
			new FormAction("doSubmitShippingMethodForm",_t("Shop.Form.Next","%Next%")),
			$validator);
		//load existing data into form
		if ($data = ShopOrder::orderSession()->Shipping()) $form->loadDataFrom($data);
		return $form;
	}
	
	function doSubmitShippingMethodForm($data, $form) {
		$session = ShopOrder::orderSession();
		$session->Shipping()->Method = Convert::Raw2SQL($data['Method']);
		$session->Shipping()->write();
		$session->calculate();
		$session->write();
		$this->redirectToNextStep("shipping");
	}
	
	function PaymentMethodForm() {
			self::setCheckoutStep(4);
			//let visitor choose the payment method
			$order = ShopOrder::orderSession();
			//get shipping method fields
			$payment = singleton("ShopPayment");
			$labels = array();
			//translate labels
			foreach (ShopPayment::$db as $field => $type) {
				$labels = array_merge($labels,array(
					$field => _t("Shop.Payment.$field","%{$field}%")
					));
			}
			$payment->set_stat("field_labels",$labels);
			$validator = new RequiredFields(ShopPayment::$required_fields);
			$fields = $payment->getFrontendFields($restrictedFields=array(
				"restrictFields" => array("Method"),
				));
			$form = new Form(
				$this,
				"PaymentMethodForm",
				$fields,
				new FormAction("doSubmitPaymentMethodForm",_t("Shop.Form.Next","%Next%")),
				$validator);
			//load existing data into form
			if ($data = ShopOrder::orderSession()->Payment()) $form->loadDataFrom($data);
			return $form;
		}
	
	function doSubmitPaymentMethodForm($data, $form) {
		$session = ShopOrder::orderSession();
		$session->Payment()->Method = Convert::Raw2SQL($data['Method']);
		$session->Payment()->write();
		$session->calculate();
		$session->write();
		$this->redirectToNextStep("payment");
	}
	
	function SummaryForm() {
		self::setCheckoutStep(5);
		$form = new Form(
			$this,
			"SummaryForm",
			new FieldSet(
				new TextareaField('Note',_t("Shop.Checkout.Note","%Note%")),
				new CheckboxField('TermsAndConditions', _t("Shop.Checkout.AgreeToTermsAndConditions","%AgreeToTermsAndConditions%"))
				),
			new FormAction("doSubmitSummaryForm",_t("Shop.Checkout.PlaceOrder","%PlaceOrder%")),
			new RequiredFields(array("TermsAndConditions"))
			);
		if (ShopOrder::orderSession()->isComplete()) return $form;
	}
	
	
	function doSubmitSummaryForm($data, $form) {
		$session = ShopOrder::orderSession();
		$session->Note = Convert::Raw2SQL($data['Note']);
		$session->write();
		if ($session->isComplete()) $this->redirectToNextStep("summary");
		return array();
	}
	
	function currentStep() {
		return self::getCheckoutStep();
	}
	
	function checkoutSteps() {
		$data = array();
		$i=0;
		foreach (self::$steps as $step) {
			$data[] = $this->stepArrayData($i);
			$i++;
		}
		return new DataObjectSet($data);
	}
	
	private function redirectToNextStep($nameOfCurrentStep) {
		$found = false;
		$i=0;
		foreach (self::$steps as $step) {
			if (strtolower($step)==strtolower(trim($nameOfCurrentStep))) $found = true;
			if ($found) {
				$nextStep = $this->stepArrayData($i+1);
				if (!($this->isAjax())) {
					Director::redirect($nextStep->Link);
					return;
				}
			}
			$i++;
		}
	}
	
	private function stepArrayData($number) {
		$step = self::$steps[$number];
		$linkingMode = ($step==Director::urlParam("Action")) ? "current" : null;
		return new ArrayData(array(
			"URLSegment" => $step,
			"Link"=> $this->dataRecord->Link().$step,
			"Title" => _t("Shop.Checkout.".ucfirst($step),"%".ucfirst($step)."%"),
			"LinkingMode" => $linkingMode,
			));
		
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
	
	private static function setCheckoutStep($value) {
		if ($value==0) $value="0";
		Session::set('Shop.CheckoutStep', (int) $value);
		return (int) $value;
	}

	private static function getCheckoutStep() {
		return (int) Session::get('Shop.CheckoutStep');
	}
	
	private static function translateFieldLabels($className) {
		if ($class = singleton($className)) {
			//maybe working only with php 5.3+ ?! didn't test it with 5.2
			$dbFields = $className::$db;
			$labels = array();
			//translate labels
			foreach ($dbFields as $field => $type) {
				$labels = array_merge($labels,array(
					$field => _t("Shop.$className.$field","%{$field}%")
					));
			}
			$class->set_stat("field_labels",$labels);
			return $class;
		}
		
	}
	
}

?>