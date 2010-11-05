<?php

class ShopOrderItem extends DataObject {
	
	static $db = array(
		"Version"=>"Int",
		"Price"=>"Float",
		"VAT"=>"Enum('INCL,EXCL','INCL')",
		"Currency"=>"Enum('EUR','EUR')",
		"Title"=>"Varchar(250)",
		"ProductKey"=>"Varchar(100)",
		"OptionPriceDifference"=>"Float",
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
		"Option"=>"ShopItemOption",
		);
		
	static $summary_fields = array(
		"Quantity","Price","Title","SubTotal","Total"
		);
		
	function Total() {
		return $this->Price()*$this->Quantity;
	}
	
	function onBeforeWrite() {
		parent::onBeforeWrite();
		$this->Total = $this->total();
	}
	
	function Price() {
		return ($this->OptionID) ? $this->Option()->Price() : $this->Price;
	}
	
	function OptionPriceDifference() {
		return ($option = $this->Option()) ? $this->Price - $option->Price() : 0;
	}
	
	function OptionTitle() {
		return ($option = $this->Option()) ? $option->Title : null;
	}
	
}
