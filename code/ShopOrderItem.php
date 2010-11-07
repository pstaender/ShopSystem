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
		"Quantity","Price","Title","Option.Title","Option.OptionKey","SubTotal","Total"
		);
		
	static $casting = array(
	  'Price' => 'Float',
		'Total' => 'Float',
		'OptionPriceDifference' => 'Float',
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
		return ($option = $this->Option()) ? $option->PriceDifference() : 0;
	}
	
	function OptionPriceDifferenceText() {
		return ($option = $this->Option()) ? $option->PriceDifferenceText() : null;
	}
	
	function OptionTitle() {
		return ($option = $this->Option()) ? $option->Title : null;
	}
	
	function hasDownload() {
		//has downoad, if option has a file attached if order is set to "ordered" or "sended"
		if ($option=$this->Option()) if ($option->Download()) if ($order=$this->Order()) if (($order->Status=="Payed") || (($order->Status=="Sended"))) return true;
		return false;
	}
	
	function DownloadFile() {
		if ($this->hasDownload()) if ($download=$this->Option()->Download()) {
			$file = $download;
			if ($order = $this->Order()) $file->DownloadURL = "user/download/".$order->ID."/".$this->OptionID."/".$download->Name;
			return $file;
		}
	}
	
}
