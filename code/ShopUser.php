<?php

class ShopUser_Controller extends ShopController {
	
	static $allowed_actions = array(
		"me"=>true,
		"logout"=>true,
		"download"=>true,
		"order"=>true,
		);
	
	function init() {
		parent::init();
		if(!Permission::check("SHOPUSER_ACCOUNT")) Security::permissionFailure();
	}
		
	
	function index() {
		return array();
	}

	function download() {
		return array();
	}
	
	function order() {
		$this->Orders = DataObject::get("ShopOrder","ClientID=".Member::currentUserID(),"Created DESC");
		return array();
	}
	
	function me() {
		return $this->index();
	}
	
	function logout() {
		Director::redirect(ViewableData::baseHref()."Security/Logout");
	}
	
}