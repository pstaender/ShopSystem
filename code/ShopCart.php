<?php

// class ShopCart extends Object {
// 	
// }

class ShopCart_Controller extends ShopController {
	
	static $allowed_actions = array(
		"add","get",
		);
	
	function add() {
		if ($id = Director::urlParam("ID")) {
			$quantity = (Director::urlParam("OtherID")) ? (int) Director::urlParam("OtherID") : 1;
			if ($item=DataObject::get_by_id("ShopItem",$id)) if ($item->StockQuantity>=0) if ($item->StockQuantity-$quantity<0) exit(_t("Shop.OutOfStock","%Out Of Stock%")); 
			$optionID = (isset($_REQUEST['optionid'])) ? (int) $_REQUEST['optionid'] : null;
			if (ShopOrder::addItem((int) $id, $quantity, $optionID)) {
				$this->Message = "OK";
				return array();
			}
		}
	}
	
	function get() {
		return array();
	}
} 

