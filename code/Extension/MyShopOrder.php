<?php

class MyShopOrder extends Extension {
	
	function shippingCosts() {
		$amount = $this->owner->amount();
		$shipping = 0;
		$shipping = ($amount < 300) ? 20 : 50;
		return ($amount==0) ? 0 : $shipping;
	}
	
	function discount() {
		return 0;
	}
		
}

?>