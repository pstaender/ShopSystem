<?php

class ShopAddress extends DataObject {
	
	static $db = array(
		"Company"=>"Varchar(200)",
		"Gender"=>"Enum('m,f,-','-')",
		"FirstName"=>"Varchar(200)",
		"Surname"=>"Varchar(200)",
		"Street"=>"Varchar(200)",
		"ZipCode"=>"Int",
		"City"=>"Varchar(100)",
		"AdditionalAddress"=>"Varchar(200)",
		"Phone"=>"Varchar(200)",
		"Country"=>"Varchar(50)",
		);
	
	static $required_fields = array(
		"FirstName",
		"Surname",
		// "Phone",
		"Street",
		"ZipCode",
		"City"
		);
	
	static $belongs_to = array(
		"Client"=>"ShopClient",
		"Order"=>"ShopOrder",
		);

	static $has_one = array(
		"Client"=>"ShopClient",
		"Order"=>"ShopOrder",
		);
	
	function getFrontendFields($restrictedFields = null) {
			$fields = $this->scaffoldFormFields(array(
					'fieldClasses' => array(),
					'restrictFields' => $restrictedFields
				)
			);
			$fields->push(new HiddenField("ID", null, $this->ID));
			$labels=singleton($this->ClassName)->stat("field_labels");
			$fields->replaceField("Country",new DropdownField("Country",$labels["Country"],self::getCountryDropdown("DE","Deutschland")));
			$gender = array(
				"-"=>_t("Shop.Contact.GenderNotSpecified","%empty%"),
				"m"=>_t("Shop.Contact.GenderMale","%Male%"),
				"f"=>_t("Shop.Contact.GenderFemale","%Female%"),
				);
			$fields->replaceField("Gender",new DropdownField("Gender",$labels["Gender"],$gender));
			return $fields;
		}
		
	function isComplete() {
		//define your own isComplete rules with MyShopAddress.php
		if (parent::isComplete()) {
			return true;
		} else {
			foreach (self::$required_fields as $field) {
				if (!(strlen($this->$field)>0)) return false;
			}
			return true;
		}
	}
		
	static function getCountryDropdown($priority = null, $countryName = null) {
			$countries = array_flip(ShopLocalization::$countries);
			if ($priority) {
				if ($countryName) {
					unset($countries[$priority]);
					$countries = array_merge(array($priority=>$countryName), $countries);
				}
				else $countries = array_merge(array($priority => $countries[$priority]), $countries);
			}
			return $countries;
		}
		
}

?>