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

// Shop.Order.EmailSubject

$lang['de_DE']['Shop.CheckoutPage']['ContentContact'] = "Rechnungsanschrift (Inhalt)";

$lang['de_DE']['Shop']['AddToCart'] = 'Bestellen';

$lang['de_DE']['Shop.Cart']['Amount'] = 'Betrag';
$lang['de_DE']['Shop.Cart']['TaxVAT'] = 'MwSt.';
$lang['de_DE']['Shop.Cart']['TaxIncl'] = 'inkl.';
$lang['de_DE']['Shop.Cart']['TaxExcl'] = 'zzgl.';
$lang['de_DE']['Shop.Cart']['Total'] = 'Gesamtbetrag';
$lang['de_DE']['Shop.Cart']['SubTotal'] = 'Zwischensumme';
$lang['de_DE']['Shop.Cart']['Discount'] = 'Preisnachlass';
$lang['de_DE']['Shop.Cart']['ShippingCosts'] = 'Versandkosten';

$lang['de_DE']['Shop.Shipping']['Standard'] = 'Standardversand';
$lang['de_DE']['Shop.Shipping']['Express'] = 'Expressversand';
$lang['de_DE']['Shop.Shipping']['Title'] = 'Versandbschreibung';
$lang['de_DE']['Shop.Shipping']['Method'] = 'Versandart';
$lang['de_DE']['Shop.Shipping']['Note'] = 'Anmerkung zum Versand';
$lang['de_DE']['Shop.Shipping']['DateOfSending'] = 'Versendedatum';
$lang['de_DE']['Shop.Shipping']['TrackingID'] = 'Tracking ID';
$lang['de_DE']['Shop.Shipping']['ServiceProvider'] = 'Versanddienstleister';

$lang['de_DE']['Shop.Payment']['Invoice'] = 'per Rechnung';
$lang['de_DE']['Shop.Payment']['Prepayment'] = 'Vorauszahlung';
$lang['de_DE']['Shop.Payment']['Method'] = 'Zahlungsweise';

$lang['de_DE']['Shop']['GoCheckout'] = 'Zur Kasse gehen';

$lang['de_DE']['Shop.Order']['EmailSubject'] = 'Bestellbestätigung';
$lang['de_DE']['Shop.Order.Status']['Ordered'] = 'Bestellung aufgegeben';
$lang['de_DE']['Shop.Order.Status']['Unsubmitted'] = 'Bestellung nicht abgeschickt';
$lang['de_DE']['Shop.Order.Status']['Payed'] = 'Bezahlt';
$lang['de_DE']['Shop.Order.Status']['Shipped'] = 'Versendet';
$lang['de_DE']['Shop.Order.Status']['Declared'] = 'Reklamiert';

$lang['de_DE']['Shop.Contact']['Email'] = 'eMail';
$lang['de_DE']['Shop.Contact']['Company'] = 'Firmenname';
$lang['de_DE']['Shop.Contact']['FirstName'] = 'Vorname';
$lang['de_DE']['Shop.Contact']['Surname'] = 'Nachname';
$lang['de_DE']['Shop.Contact']['Phone'] = 'Telefonnummer';
$lang['de_DE']['Shop.Contact']['AdditionalAddress'] = 'Zusatzadresse';
$lang['de_DE']['Shop.Contact']['Street'] = 'Straße';
$lang['de_DE']['Shop.Contact']['ZipCode'] = 'PLZ';
$lang['de_DE']['Shop.Contact']['City'] = 'Stadt';
$lang['de_DE']['Shop.Contact']['Country'] = 'Land';
$lang['de_DE']['Shop.Contact']['Gender'] = 'Anrede';
$lang['de_DE']['Shop.Contact']['UseContactForShipping'] = 'Versand geht an Rechnungsaddresse';
$lang['de_DE']['Shop.Contact']['GenderMale'] = 'Herr';
$lang['de_DE']['Shop.Contact']['GenderFemale'] = 'Frau';
$lang['de_DE']['Shop.Contact']['GenderNotSpecified'] = 'Nicht angegeben';

$lang['de_DE']['Shop.Checkout']['TaxIDNumber'] = 'Umsatzsteuer-Identifikationsnummer';
$lang['de_DE']['Shop.Checkout']['CouponCode'] = 'Gutscheincode';
$lang['de_DE']['Shop.Checkout']['ShippingMethod'] = "Versandart";
$lang['de_DE']['Shop.Checkout']['Contact'] = "Rechnungsanschrift";
$lang['de_DE']['Shop.Checkout']['Shippingaddress'] = "Versandanschrift";
$lang['de_DE']['Shop.Checkout']['PaymentMethod'] = "Zahlungsweise";
$lang['de_DE']['Shop.Checkout']['Shipping'] = "Versandart";
$lang['de_DE']['Shop.Checkout']['Payment'] = "Zahlungsart";
$lang['de_DE']['Shop.Checkout']['Summary'] = "Zusammenfassung";
$lang['de_DE']['Shop.Checkout']['Complete'] = "Bestellung abschließen";
$lang['de_DE']['Shop.Checkout']['PlaceOrder'] = "Bestellung abschließen";
$lang['de_DE']['Shop.Checkout']['Note'] = "Anmerkung / Nachricht zur Bestellung";
$lang['de_DE']['Shop.Checkout'][''] = "Warenkorb";
$lang['de_DE']['Shop.Checkout']['ContentEmail'] = "Text für eMail-Seite";
$lang['de_DE']['Shop.Checkout']['ContentInvoiceAddress'] = "Text für Rechnunganschriftseite";
$lang['de_DE']['Shop.Checkout']['ContentDeliveryAddress'] = "Text für Lieferanschriftseite";
$lang['de_DE']['Shop.Checkout']['ContentShipping'] = "Text für Versandseite";
$lang['de_DE']['Shop.Checkout']['ContentPayment'] = "Text für Bezahlungsseite";
$lang['de_DE']['Shop.Checkout']['ContentSummary'] = "Text für Zusammenfassungsseite";
$lang['de_DE']['Shop.Checkout']['ContentComplete'] = "Text für 'Erfolgreich Bestellung aufgegeben'-Seite";
$lang['de_DE']['Shop.Checkout']['ContentError'] = "Text für Fehlernachricht, bei Bestellabschluss";
$lang['de_DE']['Shop.Checkout']['ContentMinimalAmount'] = "Text, falls der Mindestbestellwert nicht erreicht ist";

$lang['de_DE']['Shop.Form']['Save'] = "Speichern";
$lang['de_DE']['Shop.Form']['Apply'] = "Übernehmen";
$lang['de_DE']['Shop.Form']['Next'] = "Weiter";
$lang['de_DE']['Shop.Form']['Submit'] = "Absenden";
$lang['de_DE']['Shop.Invoice']['Invoice'] = "Rechnung";
$lang['de_DE']['Shop.Admin']['DateOfDelivery']
=
$lang['de_DE']['Shop.Invoice']['DateOfDelivery'] = "Lieferdatum";
$lang['de_DE']['Shop.Invoice']['InvoiceKey'] = "Rechnungsnummer";
$lang['de_DE']['Shop.Invoice']['DateOfInvoice'] = "Rechnungsdatum";
$lang['de_DE']['Shop.Invoice']['InvoiceAddress'] = "Rechnungsaddresse";
$lang['de_DE']['Shop.Invoice']['Quantity'] = "Menge";
$lang['de_DE']['Shop.Invoice']['Product'] = "Produkt";
$lang['de_DE']['Shop.Invoice']['Price'] = "Preis";
$lang['de_DE']['Shop.Invoice']['OrderKey'] = "Bestellnummer";
$lang['de_DE']['Shop.Invoice']['PDFLink'] = "Link zur PDF Datei";
$lang['de_DE']['Shop.Invoice']['EmailSubject'] = "Ihr Rechnung für Ihre Bestellung";
$lang['de_DE']['Shop.Invoice']['ViewInvoice'] = "Rechnung ansehen";

$lang['de_DE']['Shop.Company']['Name'] = "Mein Firmenname";
$lang['de_DE']['Shop.Company']['Street'] = "Straße";
$lang['de_DE']['Shop.Company']['ZipCode'] = "50733";
$lang['de_DE']['Shop.Company']['City'] = "Köln";
$lang['de_DE']['Shop.Company']['Phone'] = "0163 1649827";
$lang['de_DE']['Shop.Company']['Email'] = "philipp.staender@gmail.com";

$lang['de_DE']['Shop.Checkout']['Email'] = "eMail-Addresse";
$lang['de_DE']['Shop.Checkout']['Invoiceaddress'] = "Rechnungsanschrift";
$lang['de_DE']['Shop.Checkout']['Deliveryaddress'] = "Lieferanschrift";
$lang['de_DE']['Shop.Checkout']['Index'] = "Ihre Bestellung";
$lang['de_DE']['Shop.Checkout']['IncompleteOrder'] = "Ihre Bestellung ist unvollständig";
$lang['de_DE']['Shop.Checkout']['YourInvoiceAddress'] = "Ihre Rechnungsanschrift";
$lang['de_DE']['Shop.Checkout']['YourDeliveryAddress'] = "Ihre Lieferanschrift";

$lang['de_DE']['Shop.Checkout']['ClientKey'] = "Kundennummer (falls vorhanden)";
$lang['de_DE']['Shop.Checkout']['AgreeToTermsAndConditions'] = "Hiermit stimme ich den AGBs zu";

$lang['de_DE']['Shop.OrderIncomplete']['MissingFields'] = "Sie haben nicht alle Pflichtfelder ausgefüllt.";
$lang['de_DE']['Shop.OrderIncomplete']['AmountBelowMin'] = "Der Warenkorb erfüllt nicht den Mindestbetrag.";
$lang['de_DE']['Shop.OrderIncomplete']['EmailMissing'] = "Es ist keine eMail-Adresse angegeben.";
$lang['de_DE']['Shop.OrderIncomplete']['InvoiceAddressIncomplete'] = "Sie haben keine gültige Rechnungsanschrift angegeben.";
$lang['de_DE']['Shop.OrderIncomplete']['DeliveryAddressIncomplete'] = "Sie haben keine gültige Lieferanschrift angegeben.";


//Items

$lang['de_DE']['Shop.Item']['ProductKey'] = "Artikelnummer";
$lang['de_DE']['Shop.Item']['Price'] = "Preis";
$lang['de_DE']['Shop.Item']['Featured'] = "Hervorheben";
$lang['de_DE']['Shop.Item']['Summary'] = "Kurzbeschreibung";
$lang['de_DE']['Shop.Item']['OptionRequired'] = "Eine Option muss gewählt sein";
$lang['de_DE']['Shop.Item']['StockQuantity'] = "Verfügbare Stückzahl";
$lang['de_DE']['Shop.Item']['StockDate'] = "Verfügbarkeitsdatum";
$lang['de_DE']['Shop.Item']['Currency'] = "Währung";
$lang['de_DE']['Shop.Item']['Pictures'] = "Produktfoto(s)";
$lang['de_DE']['Shop.Item']['Picture'] = "Produktfoto";
$lang['de_DE']['Shop.Item']['PictureFolder'] = "Ordner mit Produktfotos";
$lang['de_DE']['Shop.Item']['Download'] = "Download";
$lang['de_DE']['Shop.Item']['File'] = "Datei für Download";
$lang['de_DE']['Shop.Item']['OrderCount'] = "Verkaufsanzahl";
$lang['de_DE']['Shop.Download']['NotValid'] = "Die angeforderter Downloadlink ist ungültig";

//Shop.ItemOption.Title
// "OptionKey"=>_t("Shop.ItemOption.OptionKey","%Optionkey%"),
// "Modus"=>_t("Shop.ItemOption.Modus","%Modus of PriceCalculating%"),
// "Price"=>_t("Shop.ItemOption.Price","%Price%"),
// $fields->findOrMakeTab("Root.Content."._t("Shop.Item.Options","%Option%"),$tablefield);

//Shop.Category.Picture(s)
//Shop.Item.Picture(s)
//Shop.Item.PictureFolder

//Shop.User.PasswordRequest
//hop.User.YourEmail
// Shop.User.EmailSubjectPasswordConfirmHash
// Shop.User.EmailSubjectPasswordReset
