<?php

class ShopLocalization extends Object {
	
	static $countries = array(
      'Australia'       => 'AU',
      'Austria'         => 'AT',
      'Belgium'         => 'BE',
      'Brazil'          => 'BR',
      'Canada'          => 'CA',
      'China'           => 'CN',
      'Czech Republic'  => 'CZ',
      'Denmark'         => 'DK',
      'Finland'         => 'FI',
      'France'          => 'FR',
      'Germany'         => 'DE',
      'Greece'          => 'GR',
      'Hong Kong SAR'   => 'HK',
      'Hungary'         => 'HU',
      'Iceland'         => 'IS',
      'Ireland'         => 'IE',
      'Italy'           => 'IT',
      'Japan'           => 'JP',
      'Korea'           => 'KP',
      'Mexiko'          => 'MX',
      'The Netherlands' => 'NL',
      'New Zealand'     => 'NZ',
      'Norway'          => 'NO',
      'Poland'          => 'PL',
      'Portugal'        => 'PT',
      'Russia'          => 'RU',
      'Singapore'       => 'SG',
      'Slovakia'        => 'SK',
      'Spain'           => 'ES',
      'Sweden'          => 'SE',
      'Taiwan'          => 'TW',
      'Turkey'          => 'TR',
      'United Kingdom'  => 'GB',
      'United States'   => 'US',
  );

	static function generateTranslationFieldsForBackend($segment, array $db, array $has_one = null, FieldSet $fieldset) {
		return $fieldset;
		if (isset($has_one)) {
			$hasOne = array();
			foreach ($has_one as $field => $type) {
				$fieldName = $field."ID";
				$hasOne[$fieldName] = $type;
				$fieldset->renameField($fieldName, _t($segment,"%{$field}%"));
			}
		}
		foreach ($db as $field => $type) {
			$fieldset->renameField($field, _t($segment,"%{$field}%"));
		}
		return $fieldset;
	}
	
}

