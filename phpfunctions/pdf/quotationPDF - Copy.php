<?php 


//use Spipu\Html2Pdf\Html2Pdf; //exabyte no support
if(!isset($_SESSION)) 
{ 
 	session_name($config['sessionName']);
	session_start(); 
} 
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");

//require $_SERVER['DOCUMENT_ROOT'].$config['appRoot'].'/external/html2pdf/vendor/autoload.php'; //exabyte no support
require $_SERVER['DOCUMENT_ROOT'].$config['appRoot'].'/external/mPDF/vendor/autoload.php';
//require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/quotation.php");
//require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/quotation.php");
function generateQuotationPDF($myOrgName,$orgPhone,$orgFaxNo,$customerName,$attention,$customerAddress,$myOrgAddress,$quotationNumber,$quotationDate,$dueDate,$quotationTotalAmount,$itemList,$quotConfig,$footerContent){
	$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
/*
	$customerName=$_POST['quot_customerName'];
	
	
	$myOrgName=$_POST['quot_myOrgName'];
	$myOrgAddress=$_POST['quot_myOrgAddress'];
	$quotationNumber=$_POST['quot_quotationNo'];
	$quotationDate=$_POST['quot_quotationDate'];
	$quotationTotalAmount=$_POST['quot_totalAmount'];
	$maxItemIndex=$_POST['maxItemIndex'];
*/
	//$quotationTotalAmount=$_POST[];


	//$html2pdf = new Html2Pdf('P','A4','en'); //exabyte no support
	$html2pdf=new \Mpdf\Mpdf();
	
	//$html2pdf->setDefaultFont('dejavusans');
	//$html2pdf->AddFont('dejavusans');
	
	//$head='<link rel="stylesheet" type="text/css" href="https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/quotation/createQuotation/css/myQuotationStyle.css" />';
	//createQuotation/css/myQuotationStyle.css" />';
	$table="";
	$style='
	<style>
	table{
		margin:10px;	
	}
	table.quotation-org-Header{
		width:550pt;
		border-collapse:collapse;

		
	}
	table.quotation-Header{
		width:550pt;
//		border-collapse:collapse;
		
	}
	.quotation-Header-Title{
		width:550pt;
		text-align:center;
		background-color:black;
		color:white;
		font-size:20px;
		
	}
	.myOrgName{
		text-align:right;
		width:40%;
		font-weight:bold;
		font-size:16px;
	}
	.myOrgAddress{
		//width:27pt;
		text-align:right;

	}

	.myOrgLogo{
		width:60%;
		height:100px;
		//border:1px solid red;
	}
	.contact{
		text-align:right;
	}
	.customerName{
		width:50%;
		font-weight:bold;
		font-size:24px;
	}
	
	.meta-head{
		border:1px solid black;
		//border-bottom:1px solid black;
		font-weight:bold;
		padding: 5px; 
		text-align:left;
		
		background-color:#eee;
		font-size:15px;
		
	}
	.meta-body{
		border:1px solid black;
		padding: 5px; 
		text-align:right;
	}
	td{
	//	border:1px solid black;
	}
	th{
		border:1px solid black;
	}

	.quotation-Content{
		width:550pt;
		border-collapse:collapse;
	}

	.th-Item,.th-Description,.th-UnitCost,.th-Quantity,.th-Price{
		background-color:#eee;
		border:1px solid black;
		padding: 5px;
		font-size:15px;
	}


	.th-Item{
		width:25%;
	}
	.th-Description{
		width:40%;
	}
	.th-UnitCost{
		width:10%;
	}
	.th-Quantity{
		width:10%
		text-align:center;
		
	}
	.th-Price{
		width:15%;
	}
	
	.td-Item,.td-Description,.td-UnitCost,.td-Quantity,.td-Price{
		//border-bottom:1px solid black;
		padding: 10px;
		border:1px solid black;

	}
	.td-Item{
		width:25%;
		border-left:1px solid black;
	}
	.td-Description{
		width:40%;
	}
	.td-UnitCost{
		width:15%;
	}
	.td-Quantity{
		width:5%;
		text-align:center;
	}
	.td-Price{
		width:15%;
		border-right:1px solid black;
	}
	
	.blank{
		//border-right:1px solid black;
	}
	.footer-head-TOTAL{
		padding: 10px;
		border:1px solid black;
		font-weight:bold;
		text-align:right;
	}
	.footer-body-TOTAL{
		padding: 10px;

		border:1px solid black;
	}
	
	.page_Footer{
		text-align:center;
		font-weight:bold;
	}
	td{
	//	border:1px solid black;
	}

	</style>';
	$quotTable="";
	//$quot.=$head;
//	$quotTable.=$style;
$myOrLogo="";
$orgLogoSrc='https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/'.$_SESSION['orgLogo'].'.png';
if (@getimagesize($orgLogoSrc)) {

	$myOrLogo='	<img  style="height:100px;max-width:272pt"  src="'.$orgLogoSrc.'" alt="logo" />';
}

$quotTableOrgHeader='
		<table class="quotation-org-Header">
			<tbody>
			
				<tr>	
					<td class="myOrgLogo" rowspan="4" valign="bottom">
					
					'.$myOrLogo.'

					</td>
				
					<td class="myOrgName" valign="bottom">
						'.$myOrgName.'
					</td>
					
				</tr>

				<tr>
					<td class="myOrgAddress" valign="top">
						'.nl2br($myOrgAddress).'
					</td>
				
				
				</tr>
				<tr>
					
					<td class="contact" valign="top">
						Phone No : '.$orgPhone.'
					</td>
				</tr>
				<tr>
					
					<td class="contact" valign="top">
						Fax No. : '.$orgFaxNo.'
					</td>
				</tr>
			</tbody>
		</table>';
	$quotTableHeader='
	<table  class="quotation-Header">
		
	<tbody>
		<thead>
			<tr>
			<th colspan="4" class="quotation-Header-Title">
				QUOTATION
				
			</th>
			</tr>
			<tr>
				<th style="border:none">&nbsp; 
				</th>
			</tr>
		</thead>
		
		<tr>
			<td rowspan="4" valign="top" > <b>TO : </b>
			</td>
			<td  class="customerName">
				'.$customerName.',
			</td>

			<td class="meta-head">
				QUOTATION #
			</td>
			<td class="meta-body">
				'.$quotationNumber.'
				
			</td>

		</tr>
		
		<tr class="meta">
			<td rowspan="3">
			'.nl2br($customerAddress).'

			</td>
			<td class="meta-head">
				DATE
			</td>
			<td class="meta-body">
				'.$quotationDate.'
				
			</td>
		</tr>
		<tr>	
			<td class="meta-head">
				DUE DATE
			</td>
			<td class="meta-body">
				'.date_format($dueDate,"Y-M-d").'
		
				</td>
		</tr>

		<tr>
			<td colspan="2" > &nbsp; <br/> 
			</td>
		</tr>
		<tr>
			<td >
			<b>ATTN : </b>
			</td>
			<td colspan="3">
				'.$attention.'
			</td>
		</tr>
		';
		
		/*
		<tr>
			<td class="meta-head">
				Total Amount
			</td>
			<td class="meta-body">
				<input type="text" readonly id="quot_totalAmount" class="totalAmount" name="quot_totalAmount" />
			</td>
		</tr>
		*/
		$quotTableHeader.='
	</tbody>
	</table>
	';
	$quotTableContent='
	<table class="quotation-Content"  repeat_header="1">
			<tr>
				<th class="th-Item">
					ITEM
				</th>
				<th class="th-Description">
					DESCRIPTION
				</th>
				<th class="th-UnitCost">
					UNIT COST
				</th>
				<th class="th-Quantity">
					QTY
				</th>
				<th class="th-Price">
					PRICE
				</th>
				
			</tr>
		<tbody>
		';
		$quotItemList='';
		//for( $i=0; $i<$maxItemIndex; $i++ )
		foreach($itemList as $item){
			$itemName=$item['itemName'];
			$itemDescription=$item['itemDescription'];
			$itemCost=$item['itemCost'];
			$itemQty=$item['itemQty'];
			$price=$item['price'];
			
			$quotItemList.='
			<tr>
				<td class="td-Item" valign="top">
				'.$itemName.'
				</td>
			
				<td class="td-Description" valign="top">
				'.nl2br($itemDescription).'
				</td>
	
				<td class="td-UnitCost" valign="top">
				'.$itemCost.'
				</td>
		
				<td class="td-Quantity" valign="top">
				'.$itemQty.'
				</td>
			
				<td class="td-Price" valign="top">
				'.$price.'
				</td>
			</tr>
			';
		}
		
	$quotTableContent.=$quotItemList;
/*	<tr>
	<td colspan="2" class="blank">
		
	</td>
	
	<td colspan="2" class="meta-head">
		Subtotal
	</td>
	
	<td style="border:1px solid black">
		RM 875.00
	</td>
	
</tr>*/

$stamp="";
if($quotConfig['isStamp']){
	$stampSrc='https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/'.$quotConfig['orgId'].'/myOrg/'.$quotConfig['stampName'].'.png';
	if (@getimagesize($stampSrc)) {
		$stamp='<img style="height:100px;max-width:100px" src="'.$stampSrc.'" alt="logo" />';
	}
}
$extraNote=$footerContent;
	$quotTableContent.='
			<tr>
				<td colspan="2"  class="blank" style="border-right:1px solid black;">
				
	
				</td>

				<td colspan="2" class="footer-head-TOTAL" >
					TOTAL 
				</td>

				<td class="footer-body-TOTAL" >
					'.$quotationTotalAmount.'
				</td>

			</tr>
			<tr>
				<td class="blank" colspan="5" style="padding:10px;">
				<br/>
				<br/>
				'.$extraNote.'
				</td>
			</tr>

			<tr>
				<td class="blank" colspan="3">
				</td>
				<td class="blank" colspan="2" style="padding:10px;font-weight:bold;text-align:center;">
					'.$myOrgName.'
					<br/>
					'.$stamp.'<hr/>
					<br/>
					AUTHORIZED BY
				</td>

			</tr>
		</tbody>
		
	</table>

	<br/>
	
	'; 
	/*
<page_footer class="page_Footer">
		This is computer generated document no signature required
	</page_footer>

	removed as html2pdf not supported by exabyte
	*/
	$html2pdf->SetFooter('|This is computer generated document no signature required|');

	$quotTable.=$style;
	$quotTable.=$quotTableOrgHeader;
	$quotTable.=$quotTableHeader;
	$quotTable.=$quotTableContent;
	

	$html2pdf->writeHTML($quotTable);
	
	
	//$html2pdf->output();
	return $html2pdf;
	
}
?>