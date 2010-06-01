<?php

class ShopClient extends Member {
	
	static $db = array(
		"ClientKey"=>"Varchar(200)",
		);
	
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