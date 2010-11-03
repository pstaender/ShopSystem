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
		$this->Orders = DataObject::get("ShopOrder","ClientID=".Member::currentUserID());
		return array();
	}
	
	function me() {
		return $this->index();
	}
	
	function logout() {
		Director::redirect(ViewableData::baseHref()."Security/Logout");
	}
	
	// function reset_password() {
	// 	if ((isset($_GET['email'])) && (isset($_GET['hash']))) {
	// 		if ($member=DataObject::get_one("ShopClient","Email LIKE '".Convert::raw2SQL($_GET['email'])."' AND ConfirmHash LIKE '".$_GET['hash']."'")) {
	// 			$this->Password = $member->Password = substr(md5("shopsystem".time().rand(10,1000)),0,8);
	// 			$member->ConfirmHash = "";
	// 			$member->write();
	// 			$this->Successfull = true;
	// 		}
	// 	}
	// 	return array();
	// }
	// 
	// function EmailPasswordForm() {
	// 	$validator = new RequiredFields('Email');
	// 	$email = (isset($_REQUEST['email'])) ? Convert::raw2SQL($_REQUEST['email']) : "";
	// 	$fields = new FieldSet(
	// 		new EmailField('Email', _t("Shop.User.YourEmail","%Your email-address%"), $email)
	// 	);
	// 	$actions = new FieldSet(
	// 	   new FormAction('doSubmitEmailPassword',  _t("Shop.User.PasswordRequest","%Password Request%"))
	// 	);
	// 	$form = new Form($this, 'EmailPasswordForm', $fields, $actions, $validator);
	// 	return $form;
	// }
	// 
	// function doSubmitEmailPassword($data,$form) {
	// 	if (isset($data["Email"])) {
	// 		if (!(strlen($data["Email"])>0)) return array();
	// 		else $emailAddress = Convert::raw2SQL($data["Email"]);
	// 		if ($member = DataObject::get_one("ShopClient", "Email LIKE '".$emailAddress."'")) {
	// 			$this->User = $member;
	// 			$hash = $member->ConfirmHash = substr(md5("shopsystem".time().rand(10,1000)),0,8);
	// 			$this->ConfirmLink = ViewableData::baseHref().SHOPUSER_PATH."/reset_password/?email=".$emailAddress."&hash=".$hash;
	// 			$member->write();
	// 			$email = new Email();
	// 			$email->from = ShopOrder::$emailUserAccount;
	// 			$email->to = $emailAddress;
	// 			$email->subject = _t("Shop.User.EmailSubjectPasswordConfirmHash","%Password recover link%");
	// 			$email->ss_template = 'EmailResetPasswordConfirm';
	// 			$email->populateTemplate($this);
	// 			$email->send();
	// 			Director::redirectBack();
	// 		}
	// 	}
	// }
	
}