<?php

class ShopInvoice extends DataObject {
	
	static $db = array(
		"DateOfDelivery"=>"Date",
		"DateOfInvoice"=>"Date",
		"PublicURL"=>"Varchar(64)",
		"InvoiceKey"=>"Varchar(200)",
		"Note"=>"Text",
		);
		
	static $has_one = array(
		"Order"=>"ShopOrder",
		);
				
	static $summary_fields = array(
				"ID", "InvoiceKey", //"Order.Company","Order.Surname","Order.Total","DateOfInvoice"
				);
		// 
		// 		static $searchable_fields = array(
		// 			'CompanyName' => array(
		// 				'field'=>'TextField',
		// 				'filter'=>'PartialMatchFilter'
		// 				),
		// 			'Homepage',
		// 			'ZipCode',
		// 			'City',
		// 			'IsPremium',
		// 		);
		// 		
	
	function getCMSFields() {
		$fields = parent::getCMSFields();
	// return ShopLocalization::generateTranslationFieldsForBackend("Shop.Admin",self::$db,self::$has_one,$fields);
		$fields->insertAfter(
				new LiteralField(_t("Shop.Invoice.PDFLink","PDF Link"),'<h5><a href="'.Director::absoluteURL($this->pdfLink()).'" target="_blank">'._t("Shop.Invoice.PDFLink","PDF Link").'</a></h5>'),
			"OrderID"
			);
		$fields->insertAfter(
			new LiteralField(_t("Shop.Invoice.InvoiceLink","InvoiceLink"),'<h5><a href="'.Director::absoluteURL($this->link()).'" target="_blank">'._t("Shop.Invoice.InvoiceLink","Invoice Link").'</a></h5>'),
			"OrderID"
			);
		return $fields;
	}
	
	function link() {
		return "invoice/view/".$this->PublicURL;
	}
	
	function pdfLink() {
		return "invoice/pdf/".$this->PublicURL;
	}
	
	function generatePDF() {
		// tempfolder
		$tmpBaseFolder = TEMP_FOLDER . '/shopsystem';
		$tmpFolder = (project()) ? "$tmpBaseFolder/" . project() : "$tmpBaseFolder/site";
		Filesystem::removeFolder($tmpFolder);
		if(!file_exists($tmpFolder)) Filesystem::makeFolder($tmpFolder);
		$baseFolderName = basename($tmpFolder);
		//Get site
		Requirements::clear();
		$link = Director::absoluteURL($this->pdfLink()."/?view=1");
		$response = Director::test($link);
		$content = $response->getBody();
		$content = utf8_decode($content);
		$contentfile = "$tmpFolder/".$this->PublicURL.".html";
		if(!file_exists($contentfile)) {
			// Write to file
			if($fh = fopen($contentfile, 'w')) {
				fwrite($fh, $content);
				fclose($fh);
			}
		}
		return $contentfile;
	}
	
	static function generatePublicURL($maxLength = 5) {
		return substr(md5(rand(0,99999)/time()),0,$maxLength);
	}
	
}

class ShopInvoice_Controller extends ContentController {
	
	static $allowed_actions = array(
		"view","pdf"
		);
	
	function view() {
		if ($ID = Director::urlParam("ID")) {
			if ($invoice=DataObject::get_one("ShopInvoice","PublicURL = '".Convert::Raw2SQL($ID)."'")) {
				$this->Invoice = $invoice;
				if (isset($_REQUEST['remove'])) {
					//remove invoice from public by generating a new public url
					$invoice->PublicURL = ShopInvoice::generatePublicURL();
					$invoice->write();
				}
			}
		}
		return array();
	}

	function pdf() {		
		if ($ID = Director::urlParam("ID")) {
			if ($invoice=DataObject::get_one("ShopInvoice","PublicURL = '".Convert::Raw2SQL($ID)."'")) {
				$this->Invoice = $invoice;
				if (!(isset($_REQUEST['view']))) {
					//generate pdf
					
					require(dirname(__FILE__).'/Thirdparty/html2fpdf/html2fpdf.php');

					$pdf = new HTML2FPDF();
					$pdf->AddPage();
					$pdfPath = $invoice->generatePDF();
					$outputPath = TEMP_FOLDER."/shopsystem/";
					$outputFile = $outputPath.$invoice->PublicURL.".pdf";
					$fp = fopen($pdfPath,"r");
					$strContent = fread($fp, filesize($pdfPath));
					fclose($fp);
					$pdf->WriteHTML($strContent);
					$pdf->Output($outputFile);
					header('Content-type: application/pdf');
					header('Content-Disposition: attachment; filename="invoice.pdf"');
					echo file_get_contents($outputFile);
					//echo "PDF file is generated successfully!";
					exit();
				}
					
				if (isset($_REQUEST['remove'])) {
					//remove invoice from public by generating a new public url
					$invoice->PublicURL = ShopInvoice::generatePublicURL();
					$invoice->write();
				}
			}
		}
		return array();
	}
	
	function Items() {
		return DataObject::get_by_id("ShopOrder",$this->OrderID)->Items();
	}
	
}

?>