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
function generateInvoicePDF($myOrgName,$orgPhone,$orgFaxNo,$customerName,$attention,$customerAddress,$myOrgAddress,$quotationNumber,$quotationDate,$dueDate,$quotationTotalAmount,$itemList,$quotConfig,$footerContent){
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
	//
	$html2pdf=new \Mpdf\Mpdf([
		'mode' => 'utf-8',
		'format' => [205,500], 
		'margin_left' => 2,
		'margin_right' => 2,
		'margin_top' => 2,
		'margin_bottom' => 2,
		'margin_header' => 0,
		'margin_footer' => 0 ]
	);
		
		//$html2pdf->setDefaultFont('dejavusans');
		//$html2pdf->AddFont('dejavusans');
		
		//$head='<link rel="stylesheet" type="text/css" href="https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/quotation/createQuotation/css/myQuotationStyle.css" />';
		//createQuotation/css/myQuotationStyle.css" />';
		$table="";
		$style='
		<style>
		table{
			width:600pt;
			
		}
		table td{
			font-weight:bold;
			font-family: Arial, Helvetica, sans-serif;
		}
		table.quotation-org-Header{
			width:600pt;
			border-collapse:collapse;
	
			
		}
		table.quotation-Header{
			width:600pt;
	//		border-collapse:collapse;
			
		}
		.quotation-Header-Title{
			width:600pt;
			text-align:center;
			background-color:black;
			color:white;
			font-size:24px;
			
		}
		.myOrgName{
			text-align:right;
			width:40%;
			font-weight:bold;
			font-size:24px;
		}
		.myOrgAddress{
			//width:27pt;
			text-align:right;
			font-size:24px;
	
		}
	
		.myOrgLogo{
			width:60%;
			height:100px;
			//border:1px solid red;
		}
		.contact{
			text-align:right;
			font-size:24px;
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
			font-size:20px;
			
		}
		.meta-body{
			border:1px solid black;
			padding: 5px; 
			text-align:right;
			font-size:20px;
		}

		.meta{
			font-size:20px;
			
		}

		
		td{
		//	border:1px solid black;
		}
		th{
			border:1px solid black;
		}
	
		.quotation-Content{
			width:600pt;
			border-collapse:collapse;
		}
	
		.th-Item,.th-Description,.th-UnitCost,.th-Quantity,.th-Price{
			background-color:#eee;
			border:1px solid black;
			padding: 5px;
			font-size:20px;
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
			font-size:40px;
		//	font-family: "Courier New", Courier, monospace;
		}
		.td-Item{
			width:25%;
			border-left:1px solid black;
		}
		.td-Description{
			width:40%;
		}
		.td-UnitCost{
			width:10%;
		}
		.td-Quantity{
			width:10%;
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
			font-size:20px;
		}
		.footer-body-TOTAL{
			padding: 10px;
			font-size:24px;
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
	
	$quotTableOrgHeader='
			<table class="quotation-org-Header">
				<tbody>
				
					<tr>	
						<td class="myOrgLogo" rowspan="4" valign="bottom">
						<img  style="height:100px;max-width:272pt"  src="./../resources/'.$_SESSION['orgLogo'].'.png" alt="logo" />
	
	
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
					INVOICE
					
				</th>
				</tr>
				<tr>
					<th style="border:none">&nbsp; 
					</th>
				</tr>
			</thead>
			
			<tr>
				<td class="meta" rowspan="4" valign="top" style="width:60pt" > <b>TO : </b>
				</td>
				<td  class="customerName">
					'.$customerName.',
				</td>
	
				<td class="meta-head">
					INVOICE #
				</td>
				<td class="meta-body">
					'.$quotationNumber.'
					
				</td>
	
			</tr>
			
			<tr>
				<td rowspan="3" class="meta">
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
				<td class="meta">
				<b>ATTN : </b>
				</td>
				<td colspan="3" class="meta">
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
		<table class="quotation-Content">
			<thead>
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
			</thead>
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
					'.$itemDescription.'
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
		$stampSrc='./../resources/'.$quotConfig['orgId'].'/myOrg/'.$quotConfig['stampName'].'.png';
		if (@@file_exists($stampSrc)) {
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
					<td class="blank" colspan="5" style="font-size:18px;padding:10px;font-weight:bold;text-transform: uppercase;">
					<br/>
				<br/>
					'.$extraNote.'
					</td>
				</tr>

				<tr>

					<td class="blank" colspan="3">
					</td>
					<td style="font-size:18px;	text-transform: uppercase;padding:10px;font-weight:bold;text-align:center;" colspan="2" >
						'.$myOrgName.'
						<br/>
						'.$stamp.'<hr/>
						<br/>
						AUTHORIZED BY
					</td>

				</tr>
			</tbody>
			
		</table>

	'; 
	/*
<page_footer class="page_Footer">
		This is computer generated document no signature required
	</page_footer>

	removed as html2pdf not supported by exabyte
	*/
		$html2pdf->SetFooter('|<span style="font-size:10pt">This is computer generated document no signature required</span>|');

		$quotTable.=$style;
		$quotTable.=$quotTableOrgHeader;
		$quotTable.=$quotTableHeader;
		$quotTable.=$quotTableContent;
		
	
		$html2pdf->writeHTML($quotTable);
		
		
		//$html2pdf->output();
		return $html2pdf;
		
	}
	?>