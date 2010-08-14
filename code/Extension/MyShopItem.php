<?php

class MyShopItem extends DataObjectDecorator {
	
	function extraStatics() {
		return array(
			'db' => array(
				'Summary' => 'Text',
				)
			);
	}
	
	// function getCMSFields() {
	// 	parent::getCMSFields();
	// 	$this->extend('updateCMSFields',$fields);
	// 	return $fields;
	// }
	// 
	// function updateCMSFields(Fieldset $fields) {
	// 	$fields->push(new TextField('Summary', _t('Shop.ShopItem.Summary','%Summary%')));
	// }
	
}

?>