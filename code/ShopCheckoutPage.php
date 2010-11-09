<?php

//todo: company for client, default deliveryaddress form check

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
	static $icon = 'shopsystem/images/icons/cart';
	static $termsAndConditionsAgreementRequired = true;
		
	function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->addFieldsToTab("Root.Content.Shop", array(
			new HTMLEditorField('ContentEmail', _t('Shop.Checkout.ContentEmail','%ContentEmail%')),
			new HTMLEditorField('ContentInvoiceAddress', _t('Shop.Checkout.ContentInvoiceAddress','%ContentInvoiceAddress%')),
			new HTMLEditorField('ContentDeliveryAddress', _t('Shop.Checkout.ContentDeliveryAddress','%ContentDeliveryAddress%')),
			new HTMLEditorField('ContentShipping', _t('Shop.Checkout.ContentShipping','%ContentShipping%')),
			new HTMLEditorField('ContentPayment', _t('Shop.Checkout.ContentPayment','%ContentPayment%')),
			new HTMLEditorField('ContentSummary', _t('Shop.Checkout.ContentSummary','%ContentSummary%')),
			new HTMLEditorField('ContentComplete', _t('Shop.Checkout.ContentComplete','%ContentComplete%')),
			new HTMLEditorField('ContentError', _t('Shop.Checkout.ContentError','%ContentError%')),
			new HTMLEditorField('ContentMinimalAmount', _t('Shop.Checkout.ContentMinimalAmount','%ContentMinimalAmount%')),
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
		"email",
		"invoiceaddress",
		"deliveryaddress",
		"shipping",
		"payment",
		"summary",
		"complete",
		"empty",
		"minamount",
		"incomplete",
		"agree_terms_and_conditions",
		"EmailForm",
		"InvoiceAddressForm",
		"ShippingAddressForm",
		"ShippingMethodForm",
		"PaymentMethodForm",
		"SummaryForm"
		);
	
	static $steps = array(
		"",
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
			$email = $order->Email;
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
			new FieldSet(
				new FormAction('doSubmitEmailForm', _t("Shop.Form.Next","%Next%"))
				),
			new RequiredFields("Email")
			);
	}
	
	function doSubmitEmailForm($data,$form) {
		$originalEmail = $email = trim(Convert::Raw2SQL($data['Email']));
		$clientKey = trim(Convert::Raw2SQL($data['ClientKey']));
		$order = ShopOrder::orderSession();
		$clientID = $order->ClientID;
		if (($client = DataObject::get_one("ShopClient","ClientKey LIKE '".$clientKey."'"))) {
			//existing client
			$clientID = $client->ID;
			if ($lastOrder = DataObject::get_one("ShopOrder","ClientID = $clientID AND Status != 'Unsubmitted'",null,"Created DESC")) {
				if ($addr = $lastOrder->InvoiceAddress()) {
					//use the last invoice adress, if empty
					if ($order->InvoiceAddressID==0) {									
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
					if ($order->DeliveryAddressID==0) {
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
			//create new client, if not
			$client = ($order->Client()) ? $order->Client() : new ShopClient(); 
			$client->Status = "Customer";
			if ($clients=DataObject::get("ShopClient","Email LIKE '".$email."' OR Email LIKE 'shop.renamed.%.".$email."'")) {
				$client->Email = "shop.renamed.".$clients->count() .".". $email;
				$client->Status = "Guest";
			} else $client->Email = $email;
			$client->ClientKey = ShopClient::generateClientKey($email);
			$client->Password = $client->ClientKey;
			$client->write();
			//add to group
			$client->Groups()->add(DataObject::get_one("Group","Title LIKE 'ShopClients'"));
			$client->write();
			$clientID = $client->ID;
			//todo, send welcome email to client
			//create adress fields for invoice+shipping
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
		$order->Email = $originalEmail;
		$order->ClientID = $clientID;
		$order->CouponCode = strtoupper(Convert::raw2SQL($data['CouponCode']));
		$order->TaxIDNumber = Convert::raw2SQL($data['TaxIDNumber']);
		$client->ClientKey = $clientKey;
		$client->write();
		$order->write();
		$this->redirectToNextStep("email");
		return array();
	}
	
	//what to do on completing order
	function complete() {
		$this->setCheckoutStep(0);
		$session = ShopOrder::orderSession();
		$this->OrderIsPlaced = false;
		if ($session->isComplete()) {
			if ($session->Status=="Ordered") {
				//order already placed
				Director::redirect($this->dataRecord->Link()."already_placed");
				return array();
			}
			
			$session->Status = "Ordered";
			$session->OrderKey = $session->generateOrderKey();
			$session->PlacedOrderOn = time();
			//remove all items from order, where quantity not > 0
			if ($items = $session->Items()) foreach($items as $item) {
				if (!($item->Quantity>0)) $item->delete();
			}
			$session->Payment()->Price = $session->Total;
			$session->Payment()->write();
			if ($session->Items()) foreach($session->Items() as $item) {
				if ($orgItem=$item->OriginalItem()) {
					//if quantity in stock is 0 -> leave 0 and don't decrement 
					$orgItem->StockQuantity = $orgItem->StockQuantity - $item->Quantity;
					$orgItem->OrderCount++;
					$orgItem->writeToStage('Stage');
					$orgItem->publish('Stage', 'Live');
				}
			}
			$session->calculateAndWrite();
			$session->write();
			
			//create invoice
			$invoice = new ShopInvoice();
			$invoice->PublicURL = ShopInvoice::generatePublicURL();
			$invoice->OrderID = $session->ID;
			$invoice->DateOfDelivery = null;
			$invoice->DateOfInvoice = time();
			
			//increment invoicekey if exists, otherwise use the id
			if ($lastInvoice=DataObject::get_one("ShopInvoice",null,null,"ID DESC")) $invoice->InvoiceKey = ((int) preg_replace('/\D+/', '', $lastInvoice->InvoiceKey))+1;
			else $invoice->InvoiceKey = $invoice->ID;
			$invoice->write();
			$session->InvoiceID = $invoice->ID;
			$session->write();
			
			$this->OrderIsPlaced = true;
			$this->Order = $session;
			
			$session->sendOrderConfirmation();//to customer
			$session->sendOrderConfirmation(ShopOrder::getEmailFor("OrderConfirmation"));//to shop admin
			
			//send email with invoice link?!
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
		if (isset($data['ThisIsShippingAddress'])) {
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
			if (!$member->Country) $member->Country = Convert::raw2SQL($data['Country']);
			if ($member->Gender=="-") $member->Gender = Convert::raw2SQL($data['Gender']);
			$member->write();
		}
		if (isset($data['UseContactForShipping']))  {
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
			new FieldSet(
				new FormAction("doSubmitShippingMethodForm",_t("Shop.Form.Next","%Next%"))
				),
			$validator);
		//load existing data into form
		if ($data = ShopOrder::orderSession()->Shipping()) $form->loadDataFrom($data);
		return $form;
	}
	
	function doSubmitShippingMethodForm($data, $form) {
		$session = ShopOrder::orderSession();
		$session->Shipping()->Method = Convert::Raw2SQL($data['Method']);
		$session->Shipping()->write();
		$session->calculateAndWrite();
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
			$button = null;
			//translate labels
			foreach (ShopPayment::$db as $field => $type) {
				$labels = array_merge($labels,array(
					$field => _t("Shop.Payment.$field","%{$field}%")
					));
			}
			if ($order->isComplete()) $button = new FormAction("doSubmitPaymentMethodForm",_t("Shop.Form.Next","%Next%"));
			else $button = new LiteralField(
					"CompleteFieldsToProceeed",$order->incompleteReasonsForTemplate());
			
			$payment->set_stat("field_labels",$labels);
			$validator = new RequiredFields(ShopPayment::$required_fields);
			$fields = $payment->getFrontendFields($restrictedFields=array(
				"restrictFields" => array("Method"),
				));
			$form = new Form(
				$this,
				"PaymentMethodForm",
				$fields,
				new FieldSet($button),
				$validator);
			//load existing data into form
			if ($data = ShopOrder::orderSession()->Payment()) $form->loadDataFrom($data);
			return $form;
		}
	
	function doSubmitPaymentMethodForm($data, $form) {
		$session = ShopOrder::orderSession();
		$session->Payment()->Method = Convert::Raw2SQL($data['Method']);
		$session->Payment()->write();
		$session->calculateAndWrite();
		$session->write();
		$this->redirectToNextStep("payment");
	}
	
	function SummaryForm() {
		self::setCheckoutStep(5);
		$required = (ShopCheckoutPage::$termsAndConditionsAgreementRequired) ? new RequiredFields(array("TermsAndConditions")) : null;
		
		$form = new Form(
			$this,
			"SummaryForm",
			new FieldSet(
				new TextareaField('Note',_t("Shop.Checkout.Note","%Note%")),
				new CheckboxField('TermsAndConditions', _t("Shop.Checkout.AgreeToTermsAndConditions","%AgreeToTermsAndConditions%"))
				),
			new FieldSet(
				new FormAction("doSubmitSummaryForm",_t("Shop.Checkout.PlaceOrder","%PlaceOrder%"))
				),
				$required
			);
		if (ShopOrder::orderSession()->isComplete()) return $form;
	}
	
	
	function doSubmitSummaryForm($data, $form) {
		$session = ShopOrder::orderSession();
		$session->Note = Convert::Raw2SQL($data['Note']);
		if ((!(isset($data['TermsAndConditions']))) && (ShopCheckoutPage::$termsAndConditionsAgreementRequired))  {
			Director::redirect($this->Link()."agree_terms_and_conditions");
			return array();
		}
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
		Session::set('Shop.CheckoutStep.'.ShopOrder::orderSession()->Hash, (int) $value);
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

