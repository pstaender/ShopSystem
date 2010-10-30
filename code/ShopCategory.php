<?php

class ShopCategory extends SiteTree {
	
	static $db = array(
		"Featured"=>"Boolean",
		);
	
	static $allowed_children = array(
		"ShopCategory",
		"ShopItem",
		);
	static $default_children = array(
		"ShopItem",
		);
	static $icon = 'shopsystem/images/icons/commerce';
	
}

class ShopCategory_Controller extends ShopController {
	
	function init() {
		parent::init();
		// ShopOrder::checkForSessionOrCreate();
	}
	
	function Items() {
		return DataObject::get("ShopItem","ParentID = ".$this->dataRecord->ID);
	}
		
}

?>