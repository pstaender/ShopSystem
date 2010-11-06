<?php

class IntExtension extends Extension {
	
	static $zipCodeMinDigits = 5;
	
	function ZipCode() {
		$value = $this->owner->value;
		if (strlen($value)<self::$zipCodeMinDigits) {
			for($i=0;$i<=(abs((strlen($this->owner->value)+1)-self::$zipCodeMinDigits));$i++) {
				$value = "0".$value;
			}
		}
		return $value;
	}
	
}

