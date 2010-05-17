<?php

class ShopInvoice extends DataObject {
	
	static $db = array(
		"DateOfDelivery"=>"Date",
		"PublicURL"=>"Varchar(64)",
		"InvoiceKey"=>"Varchar(200)",
		);
		
	static $has_one = array(
		"Order"=>"ShopOrder",
		);
	
}

class ShopInvoice_Controller extends ContentController {
	
	static $allowed_actions = array(
		"view"
		);
	
	function view() {
		if ($ID = Director::urlParam("ID")) {
			if ($invoice=DataObject::get_one("ShopInvoice","PublicURL = '".Convert::Raw2SQL($ID)."'")) {
				$this->Invoice = $invoice;
			}
		}
		return array();
	}
	
	function Items() {
		return DataObject::get_by_id("ShopOrder",$this->OrderID)->Items();
	}
	
}

?>