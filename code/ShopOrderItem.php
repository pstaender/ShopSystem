<?php

class ShopOrderItem extends DataObject {
	
	static $db = array(
		"Version"=>"Int",
		"Price"=>"Float",
		"VAT"=>"Enum('INCL,EXCL','INCL')",
		"Currency"=>"Enum('EUR','EUR')",
		"Title"=>"Varchar(250)",
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
		
	function total() {
		return $this->Price*$this->Quantity;
	}
	
	function onBeforeWrite() {
		$this->Total = $this->total();
		return parent::onBeforeWrite();
	}
	
}

?>