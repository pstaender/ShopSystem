<?php

class MyShopPayment extends Extension {
	
		function extraStatics() {
			//use it for define extra fields you need
			return array(
				);
		}
		
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
		
		function calculate($paymentMethod=null) {
			//write your own method for calculating the payment costs
			//payment methods are defined in the model [enumValues]
			return 0;
			$amount = $this->owner->Order()->amount();
			if (!$paymentMethod) $paymentMethod=strtolower($this->owner->Method);
			$paymentMethod = strtolower($paymentMethod);
			$payment = 0;
			if ($paymentMethod=="creditcard") {
				$payment = $amount * 0.01;
			}
			$this->owner->Price = $payment;
		}
		
}

