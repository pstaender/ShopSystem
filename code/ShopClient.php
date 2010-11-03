<?php

class ShopClient extends Member implements PermissionProvider {
	
	static $db = array(
		"Company"=>"Varchar(200)",
		"Country"=>"Varchar(50)",
		"ClientKey"=>"Varchar(200)",
		"Gender"=>"Enum('m,f,-','-')",
		"Status"=>"Enum('Customer,Guest,Unknown','Unknown')",
		"ConfirmHash"=>"Varchar(32)",
		);
	static $has_one = array(
		);
	static $has_many = array(
		"Orders"=>"ShopOrder"
		);
		
	static $page_length = 100;
		
	function getCMSFields() {
		$fields = parent::getCMSFields();
		return $fields;
	}
	
	function Salutation() {
		return self::salutationNice($this->Gender);
	}
	
	static function generateClientKey($email) {
		if ($email) {
			$email = preg_replace("/[^A-Za-z0-9@]/","",$email);
			$email = preg_split("/[@]/",$email);
			$name = $email[0];
			$number = (int) date("Y",time()) + (int) date("m",time()) + (int) date("d",time());
			return $name."-".$number;
		}
	}
	
	static function salutationNice($gender) {
		$translation = array(
			"-" => _t("Shop.Contact.GenderUnknown",""),
			"m" => _t("Shop.Contact.GenderMale","Mr."),
			"f" => _t("Shop.Contact.GenderFemale","Mrs."),
			);
		return (isset($translation[$gender])) ? $translation[$gender] : null;
	}
	
	function providePermissions() {
	    return array(
	      	"SHOPUSER_ACCOUNT" => _t("Shop.User.Account","%ShopUser has access to his shop account%"),
	    );
 	}
	
}

