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
		if ($orderID = (int) Director::urlParam("ID")) if ($optionID = (int) Director::urlParam("OtherID")) if ($item = DataObject::get_one("ShopOrderItem","OrderID = $orderID AND OptionID = $optionID")) if ($item->hasDownload()) {
			//item is found and belongs to order/is ordered and has a download attached
			$file = $item->DownloadFile();
			$filename = $file->Filename;
			$path = BASE_PATH."/".$file->Filename;
			//if file doesn't exists, generate heavy error for noticing
			if(!(file_exists($path))) user_error("Error while ShopClient requested purchased Download. The file does not exists!
			Filename: '$filename'
			Filepath: '$path'
			OrderID: '$orderID'
			ItemID: '$itemID'
			");
			header('Content-type: application/x-octet-stream');
			header('Content-disposition: attachment; filename=' . $file->Name);
			readfile($path);
			exit();
		}
		exit("<h2>"._t("Shop.Download.NotValid","%The requested Download is not valid%")."</h2>");
		return array();
	}
	
	function order() {
		$this->Orders = self::ordersOfCurrentMember();
		return array();
	}
	
	private static function ordersOfCurrentMember() {
		return DataObject::get("ShopOrder","ClientID=".Member::currentUserID(),"Created DESC");
	}
	
	private static function currentShopClient() {
		return DataObject::get_by_id("ShopClient",Member::currentUserID());
	}
	
	function me() {
		return $this->index();
	}
	
	function logout() {
		Director::redirect(ViewableData::baseHref()."Security/Logout");
	}
	
}