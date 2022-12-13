<?php
//use Spipu\Html2Pdf\Html2Pdf; //exabyte no support
if(!isset($_SESSION))
{
 	session_name($config['sessionName']);
	session_start();
}
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");

require $_SERVER['DOCUMENT_ROOT'].$config['appRoot'].'/external/mPDF/vendor/autoload.php';
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/purchaseOrder.php");


function generatePurchaseOrderPDF($supplier,$shippingVia,$deliveryDate,$poDate,$pdfName,$remarks,$product,$qty,$price){
  $config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");

	$html2pdf=new \Mpdf\Mpdf();

    $content = purchaseOrderDesign($supplier,$shippingVia,$deliveryDate,$poDate,$pdfName,$remarks,$product,$qty,$price);

		$pdf=$content;
		$html2pdf->writeHTML($pdf);


		//$html2pdf->output();
		return $html2pdf;

}
?>
