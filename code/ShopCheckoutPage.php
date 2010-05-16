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
				$homepage = new Page();
				$homepage->Title = _t('Shop.CheckoutPageTitleDefault', 'Checkout');
				$homepage->Content = _t('SiteTree.CheckoutPageContentDefault', '
				<h2>Welcome to the checkout Page</h2>
				<p>Please feel free to replace this text with your own shopinformations...</p>
				');
				$homepage->URLSegment = 'checkout';
				$homepage->Status = 'Published';
				$homepage->write();
				// $homepage->publish('Stage', 'Live');
				$homepage->flushCache();
				DB::alteration_message('ShopCheckoutPage created', 'created');
			}
		}
	}	
}

class ShopCheckoutPage_Controller extends ShopController {
	
	static $allowed_actions = array(
		"contact","shippingaddress","shipping","payment","summary","complete",
		"ContactForm",
		);
		
	// function init() {
	// 	exit(var_dump(self::getCountryDropdown("DE","Deutschland")));
	// }
		
	function contact() {
		return array();
	}
	
	function ContactForm() {
		$form = $this->createContactForm(new FormAction('doSubmitContact', _t("Shop.Form.Apply","%Submit%")), "ContactForm", new CheckboxField('UseContactForShipping', _t("Shop.Contact.UseContactForShipping","%UseContactForShipping%")));
		
		if ($address = DataObject::get_by_id("ShopAddress",ShopOrder::orderSession()->ShippingContactID)) $form->loadDataFrom($address);
		
		return $form;
	}
	
	function ShippingAddressForm() {
		$form = $this->createContactForm(new FormAction('doSubmitContact', _t("Shop.Form.Apply","%Submit%")), "ContactForm", new HiddenField('ThisIsShippingAddress',"true"));
		
		if ($address = DataObject::get_by_id("ShopAddress",ShopOrder::orderSession()->BillingContactID)) $form->loadDataFrom($address);
		
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
		$session->BillingContactID))) $contact = new ShopAddress();
		$form->saveInto($contact);
		$contact->OrderID = $session->ID;
		$contact->write();
		$session->BillingContactID = $contact->ID;
		$session->write();
		$action = "contact";
		if ($data['UseContactForShipping']) {
			exit();
			if (!($shipping = DataObject::get_by_id("ShopAddress",$session->ShippingContactID))) $shipping = new ShopAddress();
			$form->saveInto($shipping);
			$shipping->OrderID = $session->ID;
			$shipping->write();
			$session->ShippingContactID = $shipping->ID;
			$session->write();
			$link = $this->step($this->step($action)->Next)->Next;			
		} else {
			$link = $this->step($action)->Next;
		}
		Director::redirect($this->Link().$link);
	}
		
	// private static function getLanguageDropdown($priority="de") {
	// 	$languages = i18n::$common_languages;
	// 	$res = array();
	// 	foreach($languages as $lan => $text) {
	// 		$res[] = array(
	// 			$lan=>next($text)
	// 			);
	// 	}
	// 	$res = array_merge(array($priority => next($languages[$priority])), $res);
	// 	return $res;
	// }
	
	function ShippingForm() {
		
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
			"NextText"=>_t("Shop.Checkout.".$order[$next],"%".$order[$next]."%"),
			"CurrText"=>_t("Shop.Checkout.".$action,"%".$action."%"),
			"PrevText"=>_t("Shop.Checkout.".$order[$prev],"%".$order[$prev]."%"),
			
			));
	}
	
}

?>