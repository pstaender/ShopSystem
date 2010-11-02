<?php

class MyShopClient extends DataObjectDecorator {
	
	function extraStatics() {
		return array(
			'db' => array(
				'OldID' => 'Int',
				)
			);
	}
	
}
