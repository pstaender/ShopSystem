<?php

class ShopOrderEvent extends DataObject {
	
	static $db = array(
		"Title"=>"Varchar",
		"Description"=>"Text",
		"Status"=>"Enum('Open,Closed','Open')",
		"EventDate"=>"SS_DateTime",
		);
		
	static $has_one = array(
		"Order"=>"ShopOrder",
	);

	static $default_sort = 'EventDate DESC';
	
	static $singular_name = "Order event";
	static $plural_name = "Order historyevents";

	static $searchable_fields = array(
		'Title',
		'Status',
	);
	
	static $summary_fields = array(
		'EventDate',
		'Status',
		'Title',
		'Description',
	);
	
	function onBeforeWrite() {
		parent::onBeforeWrite();
		if(empty($this->EventDate)) $this->EventDate = time();
	}
		
}

