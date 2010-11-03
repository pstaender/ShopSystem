<?php

class FloatExtension extends Extension {
	
	static $decimalPoint = ",";
	static $decimalRound = 2;
	static $thousandsSeperator = ".";
	
	function Decimal() {
		return number_format($this->owner->value, self::$decimalRound , self::$decimalPoint, self::$thousandsSeperator);
	}

	
}

