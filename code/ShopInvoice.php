<?php

class ShopInvoice extends DataObject {
	
	static $db = array(
		"DateOfDelivery"=>"Date",
		"DateOfInvoice"=>"Date",
		"PublicURL"=>"Varchar(64)",
		"InvoiceKey"=>"Varchar(200)",
		"Note"=>"Text",
		);
		
	static $has_one = array(
		"Order"=>"ShopOrder",
		);
		
	static $field_labels = array(
		"InvoiceKey"=>"Test",
		);
		
	static $summary_fields = array(
				"ID", "InvoiceKey", //"Order.Company","Order.Surname","Order.Total","DateOfInvoice"
				);
		// 
		// 		static $searchable_fields = array(
		// 			'CompanyName' => array(
		// 				'field'=>'TextField',
		// 				'filter'=>'PartialMatchFilter'
		// 				),
		// 			'Homepage',
		// 			'ZipCode',
		// 			'City',
		// 			'IsPremium',
		// 		);
		// 		
	
	function getCMSFields() {
	return ShopLocalization::generateTranslationFieldsForBackend("Shop.Admin",self::$db,self::$has_one,parent::getCMSFields());
	}
	
	static function generatePublicURL($maxLength = 5) {
		return substr(md5(rand(0,99999)/time()),0,$maxLength);
	}
	
}

class ShopInvoice_Controller extends ContentController {
	
	static $allowed_actions = array(
		"view"
		);
	
	function view() {
		if ($ID = Director::urlParam("ID")) {
			if ($invoice=DataObject::get_one("ShopInvoice","PublicURL = '".Convert::Raw2SQL($ID)."'")) {
				$this->Invoice = $invoice;
				if (isset($_REQUEST['remove'])) {
					//remove invoice from public by generating a new public url
					$invoice->PublicURL = ShopInvoice::generatePublicURL();
					$invoice->write();
				}
			}
		}
		return array();
	}
	
	function Items() {
		return DataObject::get_by_id("ShopOrder",$this->OrderID)->Items();
	}
	
}

?>