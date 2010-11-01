<?php

class ShopClient extends Member {
	
	static $db = array(
		"Company"=>"Varchar(200)",
		"Country"=>"Varchar(50)",
		"ClientKey"=>"Varchar(200)",
		"Gender"=>"Enum('m,f,-','-')",
		"Status"=>"Enum('Customer,Guest,Unknown','Unknown')",
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
	
	static function generateClientKey($email) {
		if ($email) {
			$email = preg_replace("/[^A-Za-z0-9@]/","",$email);
			$email = preg_split("/[@]/",$email);
			$name = $email[0];
			$number = (int) date("Y",time()) + (int) date("m",time()) + (int) date("d",time());
			return $name."-".$number;
		}
	}
	
}

?>