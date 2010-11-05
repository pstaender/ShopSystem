<?php

class ShopItemOption extends DataObject {
	
	static $db = array(
		"Title"=>"Varchar(100)",
		"Description"=>"Text",
		"OptionKey"=>"Varchar(50)",
		"PriceValue"=>"Float",
		"Modus"=>"Enum('*,+','+')",
		);

	static $has_one = array(
		"Item"=>"ShopItem"
		);
		
	static $default_sort = "LastEdited DESC";
		
	function Price() {
		$price = null;
		if ($item=$this->Item()) {
			if ($itemPrice=$item->Price) {
				if ($this->Modus=="*") $price = $itemPrice * $this->PriceValue;
				if ($this->Modus=="+") $price = $itemPrice + $this->PriceValue; 
			}
		}
		return $price;
	}
	
}