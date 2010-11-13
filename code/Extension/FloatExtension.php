<?php

//These Class is optional

class FloatExtension extends Extension {
	
	static $decimalPoint = ",";
	static $decimalRound = 2;
	static $thousandsSeperator = ".";
	
	function Decimal() {
		return self::generateDecimal($this->owner->value);
	}
	
	function generateDecimal($float) {
		return number_format($float, self::$decimalRound , self::$decimalPoint, self::$thousandsSeperator);
	}
	
}

