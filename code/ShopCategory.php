<?php

class ShopCategory extends SiteTree {
	
	static $db = array(
		"Featured"=>"Boolean",
		// "CategoryKey"="Varchar(200)",
		);
		
	static $has_one = array(
		"Picture"=>"Image",
		"PictureFolder"=>"Folder",
		);
	
	static $allowed_children = array(
		"ShopCategory",
		"ShopItem",
		);
	static $default_children = array(
		"ShopItem",
		);
	static $icon = 'shopsystem/images/icons/commerce';
	
	function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->addFieldsToTab('Root.Content.'._t("Shop.Category.Pictures","%Pictures%"), array(
			new FileIFrameField('Picture', _t("Shop.Category.Picture","%Picture%")),
			new TreeDropdownField('PictureFolder',  _t("Sshop.Category.PictureFolder","%PictureFolder%"), "Folder" ),
			));
		return $fields;
	}
	
	function Items() {
		$items = array();
		if ($children = $this->Children()) foreach ($children as $child) if ($child->ClassName=="ShopItem") $items[]=$child;
		return new DataObjectSet($items);
	}
	
}

class ShopCategory_Controller extends ShopController {
	
	function init() {
		parent::init();
		// ShopOrder::checkForSessionOrCreate();
	}
		
}

