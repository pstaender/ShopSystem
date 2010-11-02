<?php

class ShopOrderEvent extends DataObject {
	
	static $db = array(
		"Title"=>"Varchar",
		"Description"=>"Text",
		"Status"=>"Enum('Open,Closed','Open')",
		);
		
	static $has_one = array(
		"Order"=>"ShopOrder",
	);

	static $default_sort = 'Created DESC';
	
	static $singular_name = "Order event";
	static $plural_name = "Order historyevents";

	static $searchable_fields = array(
		'Title',
		'Status',
	);
	
	static $summary_fields = array(
		'Title',
		'Status',
		'Description',
	);
		
}

?>