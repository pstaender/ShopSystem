<?php

class ShopItemOption extends SiteTree {
	
	static $db = array(
		"OptionKey"=>"Varchar(50)",
		"PriceValue"=>"Float",
		"Modus"=>"Enum('+,*','+')",
		);

	static $has_one = array(
		"Download"=>"File",
		);
	
	static $casting = array(
	  'Price' => 'Float',
		'PriceDifference' => 'Float',
	);
	
	static $icon = 'shopsystem/images/icons/add_sub-album';
	
	function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->replaceField("Content",new TextareaField("Content",_t("SiteTree.Content","%Content%")));
		$fields->addFieldsToTab('Root.Content.Shop',array(
			new Textfield("PriceValue",_t("Shop.ItemOption.PriceValue","%PriceValue%")),
			new DropdownField("Modus",_t("Shop.ItemOption.Modus","%Modus%"),singleton($this->ClassName)->dbObject('Modus')->enumValues()),
			new Textfield("OptionKey",_t("Shop.ItemOption.OptionKey","%Option Key%")),
			new FileIFrameField('Download', _t("Shop.ItemOption.Download","%Download%")),
			));
		return $fields;
	}
	
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
	
	function Item() {
		return ($this->Parent()->ClassName=="ShopItem") ? $this->Parent() : null;
	}
	
	function PriceDifference() {
		return ($item = $this->Item()) ? ($this->Price() - $item->Price) : 0;
	}
	
	function PriceDifferenceText($currency=null) {
		$currency = ($currency) ? $this->Item()->Currency : "" ;
		$diff = $this->PriceDifference();
		return ($diff>=0) ? "+".FloatExtension::generateDecimal($diff)." ".$currency : FloatExtension::generateDecimal($diff)." ".$currency;
	}
	
	// function TitleComplete() {
	// 	$title = $this->Title();
	// 	if ($this->PriceValue>0) $title .= " ( + ".$this->PriceValue()->Decimal()." ".$this->Item()->Currency." )";
	// }
	
}