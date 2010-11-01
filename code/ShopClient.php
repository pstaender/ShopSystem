<?php

class ShopClient extends Member {
	
	static $db = array(
		"Company"=>"Varchar(200)",
		"Country"=>"Varchar(50)",
		"ClientKey"=>"Varchar(200)",
		"Gender"=>"Enum('m,f,-','-')",
		);
	static $has_one = array(
		"DefaultDeliveryAddress"=>"ShopAddress",
		"DefaultInvoiceAddress"=>"ShopAddress",
		);
	static $has_many = array(
		"Orders"=>"ShopOrder"
		);
		
	static $page_length = 100;
		
	function getCMSFields() {
		$fields = parent::getCMSFields();
		// $tablefield = new ComplexTableField(
		// 			$this,
		// 			'DefaultInoviceAddress',
		// 			'ShopAddress',
		// 			array(
		// 				"FirstName"=>"Firstname",
		// 				"Surname"=>"Surname",
		// 				),
		// 			null,
		// 			$sourceFilter = "ID = $this->DefaultInvoiceAddressID"	
		// 		);
		// $fields->insertBefore(new LiteralField('FIELDNAMELINK',"<a href=\"admin/shopsystem/ShopAddress/".$this->InvoiceAddressID."/edit/\" target=\"_blank\">Edit</a>"),'FirstName');
		// 	
		// $fields->addFieldToTab( 'Root.Main', $tablefield );
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