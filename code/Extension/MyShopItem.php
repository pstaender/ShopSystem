<?php

class MyShopItem extends DataObjectDecorator {
	
	function extraStatics() {
		return array(
			'db' => array(
				'Summary' => 'Text',
				'OrderCode' => 'Varchar(100)',
				)
			);
	}
	
	function getCMSFields() {
		parent::getCMSFields();
		$this->extend('updateCMSFields',$fields);
		return $fields;
	}
	
	function updateCMSFields(Fieldset $fields) {
		$fields->addFieldsToTab('Root.Content.Shop', array(
			new TextareaField('Summary', _t('Shop.ShopItem.Summary','%Summary%')),
			new TextField('OrderCode', 'Bestellnummer'),
			));
	}
	
}

?>