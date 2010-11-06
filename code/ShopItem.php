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
		// "OptionRequired"=>"Boolean",
		);
	
	static $has_one = array(
		"Picture"=>"Image",
		"PictureFolder"=>"Folder",
		"Download"=>"File",
		);
	
	static $has_many = array(
		"Options"=>"ShopItemOption",
		);
		
	static $defaults = array(
		// "OptionRequired"=>false,
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
			// new CheckboxField('OptionRequired', _t("Shop.Item.OptionRequired","%Option is required?%")),
			new TextField('StockQuantity', _t("Shop.Item.StockQuantity","%StockQuantity%")),
			new DateField('StockDate', _t("Shop.Item.StockDate","%StockDate%")),
			new DropdownField('Currency', _t("Shop.Item.Currency","%Currency%"), singleton($this->ClassName)->dbObject('Currency')->enumValues()),
			new ReadonlyField('OrderCount',_t("Shop.Item.OrderCount","%Orders count%", $this->OrderCount)),
			));
		$fields->addFieldsToTab('Root.Content.'._t("Shop.Item.Pictures","%Pictures%"), array(
			new FileIFrameField('Picture', _t("Shop.Item.Picture","%Picture%")),
			new TreeDropdownField('PictureFolderID',  _t("Shop.Item.PictureFolder","%PictureFolder%"), "Folder" ),
			// new DropdownField('','Choose a folder', DataObject::get("Folder")->toDropdownMap()));
			));
		$fields->addFieldsToTab('Root.Content.'._t("Shop.Item.Download","%Download%"), array(
			new FileIFrameField('Download', _t("Shop.Item.File","%File%")),			
			));
		
		$tablefield = new ComplexTableField(
				$controller = $this,
				'Options',
				'ShopItemOption',
				$fieldList = array(
					"Title"=>_t("Shop.ItemOption.Title","%Title%"),
					"OptionKey"=>_t("Shop.ItemOption.OptionKey","%Optionkey%"),
					"Modus"=>_t("Shop.ItemOption.Modus","%Modus of PriceCalculating%"),
					"PriceValue"=>_t("Shop.ItemOption.Price","%Price%"),
				)
			);
			$tablefield->setPermissions(
				array(
					"show",
					"edit",
					"add",
				)
			);
		$fields->findOrMakeTab("Root.Content."._t("Shop.Item.Options","%Option%"),$tablefield);
		return $fields;
	}
	
	function VATType() {
		//if items is set to INHERIT returns the (global) default value set in ShopOrder
		return ($this->VAT=="INHERIT") ? ShopOrder::getVATType() : $this->VAT;
	}
	
	function OutOfStock() {
		return ($this->StockQuantity==0) ? true : false;
	}
		
}

class ShopItem_Controller extends ShopController {
	
	
}
