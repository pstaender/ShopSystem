<?php

// ShopOrder::$currency = array(
// 	"de_DE"=>"EUR"
// );
// ShopOrder::$currencyDefault = "EUR";
// ShopOrder::$vatType = "EXCL";
// ShopOrder::$tax = array(
// 	"de_DE"=>"19"
// 	);
// ShopOrder::$taxDefault = 0;
// ShopPayment::$priceInMethods = false;
// ShopShipping::$priceInMethods = true;
// ShopCheckoutPage::$termsAndConditionsAgreementRequired = true;

// ShopOrder::$emailUserAccount="shop@127.0.0.1";
// ShopOrder::$emailOrderConfirmation="shop@127.0.0.1";

const SHOPSYSTEM_DIR = "shopsystem";
const SHOPUSER_PATH = "user";

Director::addRules(100, array(
	'order/$Action/$ID/$OtherID' => 'ShopOrder_Controller',
	'cart/$Action/$ID/$OtherID' => 'ShopCart_Controller',
	'invoice/$Action/$ID/$OtherID' => 'ShopInvoice_Controller',
	SHOPUSER_PATH.'/$Action/$ID/$OtherID/$Filename' => 'ShopUser_Controller',
));

Object::add_extension("Float", "FloatExtension");
Object::add_extension("Int", "IntExtension");//optional

ShopOrder::dontDisplayExtensionNotice();

// Object::add_extension("ShopOrder", "MyShopOrder");
// Object::add_extension("ShopPayment", "MyShopPayment");
// Object::add_extension("ShopShipping", "MyShopShipping");
// Object::add_extension("ShopItem", "MyShopItem");
// Object::add_extension("ShopAddress", "MyShopAddress");
// Object::add_extension("ShopClient", "MyShopClient");

