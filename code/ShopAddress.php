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
		"AdditionalAddress"=>"Text",
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
		
	static $summary_fields = array(
		"ID",
		"Company",
		"FirstName",
		"Surname",
		"Client.FirstName",
		"Client.Surname",
		"Order.ID",
		"Street",
		"ZipCode",
		"City",
		);
		
	static $searchable_fields = array(
		"ID",
		"FirstName",
		"Surname",
		"ZipCode",
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
	
	function Salutation() {
		return ShopClient::salutationNice($this->Gender);
	}
	
	function Nice() {
		return "<div class=\"shopAdressField\">
		<strong><p>{$this->Company}<br />
		".$this->Salutation()." {$this->FirstName} {$this->Surname}</p></strong>
		{$this->Street}<br />
		$this->ZipCode $this->City<br />{$this->Country}</p>
		<p>$this->Phone</p>
		<p>$this->AdditionalAddress</p></div>
		";
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

