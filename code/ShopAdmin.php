<?php

class ShopAdmin extends ModelAdmin {
	
	static $managed_models = array(
		'ShopOrder',
		'ShopInvoice',
		'ShopAddress',
		'ShopOrderItem',
		'ShopClient',
	);

	static $url_segment = 'shopsystem';
	static $menu_title = 'Shop';
	
	
}

