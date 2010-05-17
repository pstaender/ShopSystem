<?php

// Currency::setCurrencySymbol("€");

Director::addRules(100, array(
	'order/$Action/$ID/$OtherID' => 'ShopOrder_Controller',
	'cart/$Action/$ID/$OtherID' => 'ShopCart_Controller',
));

const SHOPSYSTEM_DIR = "shopsystem";

ShopOrder::$currency = "EUR";
ShopOrder::$vatType = "EXCL";
ShopOrder::$tax = array(
	"de_DE"=>"19"
	);

Object::add_extension("ShopOrder", "MyShopOrder");


?>