<?php

class ShopOrderItem extends DataObject {
	
	static $db = array(
		"Version"=>"Int",
		"Price"=>"Float",
		"VAT"=>"Enum('INCL,EXCL','INCL')",
		"Currency"=>"Enum('EUR','EUR')",
		"Title"=>"Varchar(250)",
		"ProductKey"=>"Varchar(100)",
		"Quantity"=>"Int",
		"SubTotal"=>"Float",
		"Total"=>"Float",
		);
		
	static $belongs_to = array(
		"Order"=>"ShopOrder",
		);
	
	static $has_one = array(
		"Order"=>"ShopOrder",
		"OriginalItem"=>"ShopItem",
		);
		
	static $summary_fields = array(
		"Quantity","Price","Title","SubTotal","Total"
		);
		
	function total() {
		return $this->Price*$this->Quantity;
	}
	
	function onBeforeWrite() {
		parent::onBeforeWrite();
		$this->Total = $this->total();
	}
	
}

?>