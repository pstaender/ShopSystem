<?php

class MyShoplient extends DataObjectDecorator {
	
	function extraStatics() {
		return array(
			'db' => array(
				'OldID' => 'Int',
				)
			);
	}
	
}
