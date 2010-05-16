<?php

/**
 * German (Germany) language pack
 * @package shopsystem
 * @subpackage i18n
 */

global $lang;

if(array_key_exists('de_DE', $lang) && is_array($lang['de_DE'])) {
	$lang['de_DE'] = array_merge($lang['en_US'], $lang['de_DE']);
} else {
	$lang['de_DE'] = $lang['en_US'];
}

$lang['de_DE']['Shop']['AddToCart'] = 'Bestellen';
$lang['de_DE']['Shop.Cart']['Amount'] = 'Betrag';
$lang['de_DE']['Shop.Cart']['TaxVAT'] = 'MwSt.';
$lang['de_DE']['Shop.Cart']['TaxIncl'] = 'inkl.';
$lang['de_DE']['Shop.Cart']['TaxExcl'] = 'zzgl.';
$lang['de_DE']['Shop.Cart']['Total'] = 'Gesamtbetrag';
$lang['de_DE']['Shop.Cart']['SubTotal'] = 'Zwischensumme';
$lang['de_DE']['Shop.Cart']['Discount'] = 'Preisnachlass';
$lang['de_DE']['Shop.Cart']['ShippingCosts'] = 'Versandkosten';

$lang['de_DE']['Shop']['GoCheckout'] = 'Zur Kasse gehen';
$lang['de_DE']['Shop.Contact']['Company'] = 'Firmenname';
$lang['de_DE']['Shop.Contact']['FirstName'] = 'Vorname';
$lang['de_DE']['Shop.Contact']['Surname'] = 'Nachname';
$lang['de_DE']['Shop.Contact']['AdditionalAddress'] = 'Zusatzadresse';
$lang['de_DE']['Shop.Contact']['Street'] = 'Straße';
$lang['de_DE']['Shop.Contact']['ZipCode'] = 'PLZ';
$lang['de_DE']['Shop.Contact']['City'] = 'Stadt';
$lang['de_DE']['Shop.Contact']['Country'] = 'Land';
$lang['de_DE']['Shop.Contact']['UseContactForShipping'] = 'Versand geht an Rechnungsaddresse';
$lang['de_DE']['Shop.Checkout']['contact'] = "Rechnungsanschrift";
$lang['de_DE']['Shop.Checkout']['shippingaddress'] = "Versandanschrift";
$lang['de_DE']['Shop.Checkout']['shipping'] = "Versandart";
$lang['de_DE']['Shop.Checkout']['payment'] = "Zahlungsart";
$lang['de_DE']['Shop.Checkout']['summary'] = "Zusammenfassung Ihrer Bestellung";
$lang['de_DE']['Shop.Checkout']['complete'] = "Bestellung abschließen";
$lang['de_DE']['Shop.Checkout'][''] = "Warenkorb";

$lang['de_DE']['Shop.Form']['Save'] = "Speichern";
$lang['de_DE']['Shop.Form']['Apply'] = "Übernehmen";
$lang['de_DE']['Shop.Form']['Submit'] = "Absenden";
?>