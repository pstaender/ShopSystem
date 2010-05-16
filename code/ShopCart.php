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
			if (ShopOrder::addItem((int) $id, $quantity)) {
				$this->Message = "OK";
				return array();
			}
		}
	}
	
	function get() {
		return array();
	}
} 

?>