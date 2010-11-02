<?php

class ShopItem extends SiteTree {
	
	static $db = array(
		"Featured"=>"Boolean",
		"StockQuantity"=>"Int",
		"ProductKey"=>"Varchar(100)",
		"StockDate"=>"Date",
		"Price"=>"Float",
		"Currency"=>"Enum('EUR','EUR')",
		"Summary"=>"Text",
		"OrderCount"=>"Int",
		"ViewCount"=>"Int",
		);
	
	static $has_one = array(
		"Picture"=>"Image",
		"PictureFolder"=>"Folder",
		);
			
	static $default_sort =  "Featured, Sort DESC";
	
	static $icon = 'shopsystem/images/icons/blocks';
	
	function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->addFieldsToTab("Root.Content.Shop", array(
			new TextField('ProductKey', _t("Shop.Item.ProductKey","%ProductKey%")),
			new NumericField('Price', _t("Shop.Item.Price","%Price%")),
			new CheckboxField('Featured', _t("Shop.Item.Featured","%Featured%")),
			new TextareaField('Summary', _t("Shop.Item.Summary","%Summary%"),5),
			new TextField('StockQuantity', _t("Shop.Item.StockQuantity","%StockQuantity%")),
			new DateField('StockDate', _t("Shop.Item.StockDate","%StockDate%")),
			new DropdownField('Currency', _t("Sshop.Item.Currency","%Currency%"), singleton($this->ClassName)->dbObject('Currency')->enumValues()),
			));
		$fields->addFieldsToTab('Root.Content.'._t("Shop.Item.Pictures","%Pictures%"), array(
			new FileIFrameField('Picture', _t("Shop.Item.Picture","%Picture%")),
			new TreeDropdownField('Folder',  _t("Sshop.Item.PictureFolder","%PictureFolder%"), "Folder" ),
			
			));
		return $fields;
	}
	
	function VATType() {
		//if items is set to INHERIT returns the (global) default value set in ShopOrder
		return ($this->VAT=="INHERIT") ? ShopOrder::getVATType() : $this->VAT;
	}
		
}

class ShopItem_Controller extends ShopController {
	
	
}
