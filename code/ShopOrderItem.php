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
		//has downoad, if option DOWNLOAD is selected and if order is set to "ordered" or "shipped"
		if ($option=$this->Option()) if (strtolower($option->OptionKey)=="download") if ($order=$this->Order()) if (($order->Status=="Payed") || (($order->Status=="Sended"))) return true;
		return false;
	}
	
	function DownloadFile() {
		if ($this->hasDownload()) if ($orgItem = $this->OriginalItem()) if ($download=$orgItem->Download()) {
			$file = $download;
			if ($order = $this->Order()) $file->DownloadURL = "user/download/".$order->ID."/".$orgItem->ID."/".$download->Name;
			return $file;
		}
	}
	
}
