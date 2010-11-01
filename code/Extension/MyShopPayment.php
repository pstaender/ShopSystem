<?php

class MyShopPayment extends DataObjectDecorator {
		
		//override with your own fields+rules, if you wish
		static function methodFields() {
			//generate a select field from all enum values
			$paymentMethods = singleton("ShopPayment")->dbObject('Method')->enumValues();
			$order = ShopOrder::orderSession();
			$pay = array();
			
			//fw
			$pay = array(
				"Prepayment"=>"Vorkasse (Pflichtfeld außer bei DE)",
				);
			if (strtoupper($order->InvoiceAddress()->Country)=="DE") {
				$pay = array_merge($pay,array(
					"Invoice" => "Auf Rechnung",
					));
			}
			return $pay;
			//fw
			
			
			foreach ($paymentMethods as $name => $value) {
				$price = ShopPayment::$priceInMethods ? " (".$order->calcPaymentCosts($name)." ".ShopOrder::getLocalCurrency().")" : "";
				$pay[$name] = _t("Shop.Payment.{$value}","%{$value}%").$price;
			}
			return $pay;
		}
		
}

