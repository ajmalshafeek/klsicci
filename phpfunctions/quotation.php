<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
if(!isset($_SESSION)) 
{ 
 	session_name($config['sessionName']);
	session_start(); 
}

require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
date_default_timezone_set("Asia/Kuala_Lumpur");
	
	function latestQuotationNo(){
		$quotationNo=0;
		$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/quotation.php");
		$con=connectDb();
		$orgId=$_SESSION['orgId'];
		$quotationNo=getLatestQuotationNo($con,$orgId);
		if($quotationNo==null){
			$quotationNo="0";
			
		}

		$quotationNo++;
		
		$quotationNo=str_pad($quotationNo,10,"0",STR_PAD_LEFT);
		
		return $quotationNo;

	}
	
	
	if(isset($_POST['createQuotation'])){
		$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/quotation.php");

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/pdf/quotationPDF.php");
		$con=connectDb(); // query/connect.php

		$customerName=$_POST['quot_customerName'];
		$attention=$_POST['quot_attention'];
		
		$myOrgName=$_POST['quot_myOrgName'];
		$myOrgAddress=$_POST['quot_myOrgAddress'];
		$orgPhone=$_POST['quot_orgPhone'];
		$orgFaxNo=$_POST['quot_orgFaxNo'];

		$quotationNumber=$_POST['quot_quotationNo'];
		$quotationDate=$_POST['quot_quotationDate'];
		$quotationTotalAmount=$_POST['quot_totalAmount'];
		$maxItemIndex=$_POST['maxItemIndex'];
        $dueDate="";
		if(isset($_POST['quot_dueDate'])){
            $dueDate=$_POST['quot_dueDate'];
        }
		else{
            $dueDate=date("Y-m-d");
        }
		$dueDate=new DateTime($dueDate, new DateTimeZone('Asia/Singapore'));

		$orgId=$_SESSION['orgId'];
		
		$customerId=$_POST['quot_customerId'];
		$customerAddress=$_POST['quot_customerAddress'];
		$jobId=null;
		$createdBy=$_SESSION['userid'];
		$createdDate=date('Y-m-d H:i:s');
		$subTotal=null;
		$discount=null;
		$tax=null;
		$total=$quotationTotalAmount;
		$status=1;

		

		
		for( $i=0; $i<$maxItemIndex; $i++ ){
			$itemName=$_POST["itemName$i"];
			$itemDescription=$_POST["itemDesc$i"];
			$itemCost=$_POST["itemCost$i"];
			$itemQty=$_POST["itemQty$i"];
			$price=$_POST["price$i"];

			$item=array("itemName"=>$itemName,"itemDescription"=>$itemDescription,
			"itemCost"=>$itemCost,"itemQty"=>$itemQty,"price"=>$price);

			$itemList[]=$item;

			
		}
		//$quotationNo;
		$quotationDirectory=$_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/".$orgId."/quotation/";
		if (!file_exists($quotationDirectory)) {
			mkdir($quotationDirectory, 0777, true);
		}
		
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/quotationConfig.php");
		
		$quotConfig=fetchQuotationConfig($con,$orgId);
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/pdffooterlist.php");
		$footerId=$_POST["pdfFooter"];
		$pdfFooterList=fetchPdfFooterList($con,$footerId,$orgId);
		$content=$pdfFooterList[0]['content'];



		$quotationPDF=generateQuotationPDF($myOrgName,$orgPhone,$orgFaxNo,$customerName,$attention,
		$customerAddress,$myOrgAddress,$quotationNumber,$quotationDate,$dueDate,$quotationTotalAmount,
		$itemList,$quotConfig,$content); // phpfunction/pdf/quotationPDF.php

	//	$quotationPDF->output();
	//	$quotationPDF->output("$quotationDirectory/$quotationNumber.pdf",'F'); // save pdf to server // html2pdf method

		$quotationPDF->output("$quotationDirectory/$quotationNumber(".strtotime($createdDate).").pdf",'F'); // save pdf to server
		
		$quotationDate=date('Y-m-d');

		$total=preg_replace("/[^0-9\.]/", '', $total);

		
		$dueDate=date_format($dueDate,"Y-m-d");

		$fileName=$quotationNumber."(".strtotime($createdDate).")";
		$quotId=createQuotation($con,$customerName,$customerAddress,$jobId,$quotationNumber,$customerId,
		$attention,$createdBy,$createdDate,$quotationDate,$dueDate,$subTotal,$discount,$tax,$total,$status,$orgId,$fileName,$footerId); // query/quotation.php

		if($quotId>0){
			require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/quotationItem.php");
			foreach($itemList as $item){
				$itemName=$item['itemName'];
				$itemDescription=$item['itemDescription'];
				$itemCost=$item['itemCost'];
				$itemQty=$item['itemQty'];
				$price=$item['price'];
				$subTotal=null;
				$tax=null;
				$discount=null;
				$total=$price;

				$itemCost=preg_replace("/[^0-9\.]/", '', $itemCost);
				$total=preg_replace("/[^0-9\.]/", '', $total);

				$saveSucess=createQuotationItemBreakdown($con,$quotId,$itemName,$itemDescription,$itemCost,$itemQty,$subTotal,$tax,$discount,$total); // query/quotationitem.php
			}
		}

		if($saveSucess){
			$_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
					<strong>SUCCESS!</strong> QUOTATION CREATED SUCCESSFULLY \n
					</div>\n";
		}

		$_SESSION['quotationNumber']=$quotationNumber;
		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/quotation/mailQuotation.php");

		
		
		//return $quotationNumber;
	}
	
	if(isset($_POST['convert2Invoice'])){


		$con=connectDb();

		$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/quotation.php");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/quotationItem.php");

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/invoice.php");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/invoiceItem.php");	
		//require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/invoice.php");

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/pdf/invoicePDF.php");

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organization.php");	


		$quotationNo=$_POST['quotationNo'];

		$footerId='';
		if(isset($_POST['pdfFooter'])){
			$footerId=$_POST['pdfFooter'];
		}
		$quotationDetails=fetchQuotationDetailsByQuotationNo($con,$quotationNo);
		$quotationId=$quotationDetails['id'];
		$customerName=$quotationDetails['customerName'];
		$customerAddress=$quotationDetails['customerAddress'];
		$jobId=$quotationDetails['jobId'];

		$customerId=$quotationDetails['customerId'];

		$createdBy=$_SESSION['userid'];
		$createdDate=date('Y-m-d H:i:s');

		$invoiceDate=date('Y-m-d');

		$paymentDate=null;
		$subTotal=$quotationDetails['subTotal'];
		$discount=$quotationDetails['discount'];
		$tax=$quotationDetails['tax'];
		$total=$quotationDetails['total'];
		$amountPaid=null;
		$status=$quotationDetails['status'];

		$orgId=$_SESSION['orgId'];

		$invoiceNo=getLatestInvoiceNo($con,$orgId);
		
		if($invoiceNo==null){
			$invoiceNo="0";
		}

		$invoiceNo++;
		$invoiceNumber=$invoiceNo;
		$dueDate=$quotationDetails['dueDate'];
		$attention=$quotationDetails['attention'];
		$invoiceNumber=str_pad($invoiceNumber,10,"0",STR_PAD_LEFT);
		$fileName=$invoiceNumber."(".strtotime($createdDate).")";
		$invcId=createInvoice($con,$customerName,$customerAddress,$jobId,$invoiceNumber,
			$customerId,$attention,$createdBy,$createdDate,$invoiceDate,$paymentDate,$dueDate,$subTotal,$discount,$tax,$total,
			$amountPaid,$status,$orgId,$fileName,$footerId,null,null,null,null);

			$invoiceTotalAmount=$total;

		if($invcId>0){
			$quotationItemBreakdown=fetchQuotationItemBreakdownByQuotId($con,$quotationId);
			$itemList=array();
		
			foreach($quotationItemBreakdown as $item){
				
				$itemName=$item['itemName'];
				$itemDescription=$item['itemDescription'];
				$itemCost=$item['itemPrice'];
				$itemQty=$item['quantity'];
				$subTotal=$item['subTotal'];
				$tax=$item['tax'];
				$discount=$item['discount'];
				$total=$item['total'];
				$price=$itemCost*$itemQty;

				$item2=array("itemName"=>$itemName,"itemDescription"=>$itemDescription,
				"itemCost"=>$itemCost,"itemQty"=>$itemQty,"price"=>$price);

				$itemList[]=$item2;
			

				$saveSucess=createInvoiceItemBreakdown($con,$invcId,$itemName,$itemDescription,
				$itemCost,$itemQty,$subTotal,$tax,$discount,$total);
			}

			$invoiceDirectory=$_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/".$orgId."/invoice/";
			if (!file_exists($invoiceDirectory)) {
				mkdir($invoiceDirectory, 0777, true);
			}
	
			$orgDetails=getOrganizationDetails($con,$orgId);

			$myOrgName=$orgDetails['name'];
			$myOrgAddress=$orgDetails['address1'].",";
			if($orgDetails['address2']!=null){
				$myOrgAddress.= "<br/>".$orgDetails['address2'].",";
			}
			$myOrgAddress.= "<br/>".$orgDetails['postalCode']." ".$orgDetails['city'].","; 	
			$myOrgAddress.= "<br/>".$orgDetails['state'];
			$orgPhone=$orgDetails['contact'];
			$orgFaxNo=$orgDetails['faxNo']; 
			$invoiceNumber=str_pad($invoiceNumber,10,"0",STR_PAD_LEFT);
			$dueDate=new DateTime($dueDate,new DateTimeZone('Asia/Singapore'));
			
			require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/quotationConfig.php");
			$quotConfig=fetchQuotationConfig($con,$orgId);
			
			require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/pdffooterlist.php");
			$footerId=$_POST["pdfFooter"];
			$pdfFooterList=fetchPdfFooterList($con,$footerId,$orgId);
			$content=$pdfFooterList[0]['content'];

                $invoicePDF=generateInvoicePDF($myOrgName,$orgPhone,$orgFaxNo,$customerName,$attention,
                $customerAddress,$myOrgAddress,$invoiceNumber,$invoiceDate,
                $dueDate,$invoiceTotalAmount,$itemList,$quotConfig,$content,NULL);

			/*$invoicePDF=generateInvoicePDF($myOrgName,$orgPhone,$orgFaxNo,$customerName,$attention,
			 *$customerAddress,$myOrgAddress,$invoiceNumber,$invoiceDate,
			 *$dueDate,$invoiceTotalAmount,$itemList,$quotConfig,$content); // phpfunction/quotationPDF.php */
			//$invoicePDF->output(); // save pdf to server // html2pdf method
			$invoicePDF->output("$invoiceDirectory/$invoiceNumber(".strtotime($createdDate).").pdf",'F'); // save pdf to server // html2pdf method
	
			$status=0;
			$updateSucess=updateQuotationStatusByQuotId($con,$quotationId,$status);
		//	$deleteSucess=deleteQuotationItemByQuotId($con,$quotationId);
		//	$deleteSucess=deleteQuotationByQuotId($con,$quotationId);

			$_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
			<strong>SUCCESS!</strong> SUCCESSFULLY CONVERTED TO INVOICE \n
			</div>\n";
		}
		$_SESSION['invoiceNumber']=$invoiceNumber;
		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/invoice/mailInvoice.php");

	}

	if(isset($_POST['updateQuotation'])){
		$saveSucess=false;
		$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/quotation.php");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/pdf/quotationPDF.php");
		$con=connectDb(); 

		$customerName=$_POST['quot_customerName'];
		$attention=$_POST['quot_attention'];
		
		$myOrgName=$_POST['quot_myOrgName'];
		$myOrgAddress=$_POST['quot_myOrgAddress'];
		$orgPhone=$_POST['quot_orgPhone'];
		$orgFaxNo=$_POST['quot_orgFaxNo'];

		$quotationNumber=$_POST['quot_quotationNo'];
		$quotationDate=$_POST['quot_quotationDate'];
		$quotationTotalAmount=$_POST['quot_totalAmount'];
		$maxItemIndex=$_POST['maxItemIndex'];
		$dueDate=$_POST['quot_dueDate'];
		$dueDate=new DateTime($dueDate, new DateTimeZone('Asia/Singapore'));

		$orgId=$_SESSION['orgId'];

		$customerId=$_POST['quot_customerId'];
		$customerAddress=$_POST['quot_customerAddress'];
		$jobId=null;
		$createdBy=$_SESSION['userid'];
		$createdDate=date('Y-m-d H:i:s');
		$paymentDate=null;
		$subTotal=null;
		$discount=null;
		$tax=null;
		$amountPaid=null;
		$total=$quotationTotalAmount;
		$status=1;

		for( $i=0; $i<$maxItemIndex; $i++ ){
			$itemName=$_POST["itemName$i"];
			$itemDescription=$_POST["itemDesc$i"];
			$itemCost=$_POST["itemCost$i"];
			$itemQty=$_POST["itemQty$i"];
			$price=$_POST["price$i"];

			$item=array("itemName"=>$itemName,"itemDescription"=>$itemDescription,
			"itemCost"=>$itemCost,"itemQty"=>$itemQty,"price"=>$price);

			$itemList[]=$item;

			
		}

		$quotationDirectory=$_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/".$orgId."/quotation/";
		if (!file_exists($quotationDirectory)) {
			mkdir($quotationDirectory, 0777, true);
		}

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/quotationConfig.php");

		$quotConfig=fetchQuotationConfig($con,$orgId);
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/pdffooterlist.php");
		$footerId=$_POST["pdfFooter"];
		$pdfFooterList=fetchPdfFooterList($con,$footerId,$orgId);
		$content=$pdfFooterList[0]['content'];
        ob_start();
		$quotationPDF=generateQuotationPDF($myOrgName,$orgPhone,$orgFaxNo,$customerName,$attention,
		$customerAddress,$myOrgAddress,$quotationNumber,$quotationDate,
		$dueDate,$quotationTotalAmount,$itemList,$quotConfig,$content);
        ob_end_clean();

		$quotationDetails=fetchQuotationDetailsByQuotationNo($con,$quotationNumber);
		$deleteFileSuccess=unlink($quotationDirectory."".$quotationDetails['fileName'].'.pdf');

		$quotationPDF->output("$quotationDirectory/$quotationNumber(".strtotime($createdDate).").pdf",'F'); // save pdf to server // html2pdf method
		$quotationDate=date('Y-m-d');
		$total=preg_replace("/[^0-9\.]/", '', $total);
		$dueDate=date_format($dueDate,"Y-m-d");

		$quotationDetails=fetchQuotationDetailsByQuotationNo($con,$quotationNumber);
		$updateSuccess=false;

		$fileName=$quotationNumber."(".strtotime($createdDate).")";
		$updateSuccess=updateEditQuotation($con,$customerName,$customerAddress,$jobId,
		$quotationNumber,$customerId,$attention,$createdBy,$createdDate,$quotationDate,
		$dueDate,$subTotal,$discount,$tax,$total,$status,$orgId,$fileName,$quotationDetails['id'],$footerId); // query/invoice.php
		if($updateSuccess){
			require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/quotationItem.php");
			$deleteSuccess=deleteQuotationItemByQuotId($con,$quotationDetails['id']);
			foreach($itemList as $item){
				$itemName=$item['itemName'];
				$itemDescription=$item['itemDescription'];
				$itemCost=$item['itemCost'];
				$itemQty=$item['itemQty'];
				$price=$item['price'];
				$subTotal=null;
				$tax=null;
				$discount=null;
				$total=$price;

				$itemCost=preg_replace("/[^0-9\.]/", '', $itemCost);
				$total=preg_replace("/[^0-9\.]/", '', $total);

				$saveSucess=createQuotationItemBreakdown($con,$quotationDetails['id'],$itemName,$itemDescription,$itemCost,$itemQty,$subTotal,$tax,$discount,$total); // query/quotationitem.php
			}
		}

		if($saveSucess){
			$_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
					<strong>SUCCESS!</strong> QUOTATION UPDATED SUCCESSFULLY \n
					</div>\n";
		}
		
		$_SESSION['quotationNumber']=$quotationNumber;
		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/quotation/mailQuotation.php");

	}


	function quotationListTable($quotationId_,$customerName_,$jobId_,$quotationNo_,$customerId_,$createdBy_,
	$dateFrom_,$dateTo_,$status_,$orgId_){
 //       if($_SESSION['role']==1){
 //           return "";
 //       }
	    $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/quotation.php");



		$con=connectDb();

		$quotationId=$quotationId_;
		$customerName=$customerName_;
		$jobId=$jobId_;
		$quotationNo=$quotationNo_;
		$customerId=$customerId_;
		$createdBy=$createdBy_;
		$dateFrom=$dateFrom_;
		$dateTo=$dateTo_;
		$status=$status_;
		$orgId=$orgId_;

		$quotaionList=fetchQuotationList($con,$quotationId,$customerName,$jobId,$quotationNo,$customerId,
		$createdBy,$dateFrom,$dateTo,$status,$orgId);


		$table="";

		$table.='<table class="table table-hover table-bordered" id="quotationListTable" 
		width="100%" cellspacing="0" role="grid" style="width: 100%;">';
		  $table.="<thead class='thead-dark'>";

			$table.="<tr>";
				$table.="<th style='width:5%' ></th>";
				$table.="<th style='width:20%' scope='col'>Date</th>";
				$table.="<th scope='col'>Quotation #</th>";
				$table.="<th scope='col'>Created By</th>";
				$table.="<th scope='col'>Customer Name</th>";
				$table.="<th scope='col'>Quoted Amount</th>";
			//	$table.="<th scope='col'>CONVERT</th>";

			$table.="</tr>";

		$table.="</thead>";
		
		$table.="<tbody>";
		//data-toggle='modal' data-target='#quotationPDFModal' 
		foreach($quotaionList as $quotation){
			$table.="<tr  data-value='".$quotation['fileName']."'> ";
			$table.='<td style="text-align: center; vertical-align: middle;">
						<input type="checkbox"  value="'.str_pad($quotation['fileName'],10,"0",STR_PAD_LEFT).'" name="checkedRow[]" />
					</td>';
			$table.="<td  >".date('D d-M-y H:i:s A',strtotime($quotation['createdDate']))."</td>";
			$table.="<td  >".str_pad($quotation['quotationNo'],10,"0",STR_PAD_LEFT)."</td>";
			$table.="<td >".$quotation['name']."</td>";
			$table.="<td >".$quotation['customerName']."</td>";
			$table.="<td  >RM &nbsp".number_format($quotation['total'],2)."</td>";
			//$table.="<td  align='center' style='cursor:default'><i style='cursor:pointer;color:green' id='convertToInvoice' class='fa fa-refresh'  title='CONVERT TO QUOTATION' aria-hidden='true'></i></td>";

			$table.="</tr>";
			
		}
		$table.="</tbody>";
		$table.="</table>";
		

		return $table;

	}

	function getQuotationDetailsByQuotNo($quotationNo_){
		$con=connectDb();
		$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
		$quotationNo=$quotationNo_;
		$quotationDetails=null;
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/quotation.php");
		$quotationDetails=fetchQuotationDetailsByQuotationNo($con,$quotationNo);

		return $quotationDetails;

	}

	function htmlEncodeNewLine($text){
		$breaks = array("<br />","<br>","<br/>");  
		$text = str_ireplace($breaks,"", $text);
		$text = str_ireplace("\r\n","&#10;", $text);  
		return $text;
	}
	
	function getQuotationBreakdownByQuotId($quotationNo_){
		$con=connectDb();
		$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
		$quotationNo=$quotationNo_;
		$quotationBreakdown=null;
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/quotationItem.php");
		$quotationBreakdown=fetchQuotationItemBreakdownByQuotId($con,$quotationNo);
		$dataList=array();

		foreach($quotationBreakdown as $item){
			$item['itemDescription']=htmlEncodeNewLine($item['itemDescription']);
			$dataList[]=$item;

		}

		return $dataList;
	}

if(isset($_POST['removeQuotation'])){
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/quotation.php");
    $con=connectDb();
    $quotationNo=$_POST['quotationNo'];
    $status=deleteQuotation($con,$quotationNo);
    if($status){
        $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
				<strong>SUCCESS!</strong> SUCCESSFUL TO DELETED QUOTATION ".$quotationNo."\n
				</div>\n";
    }else{
        $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
				<strong>SUCCESS!</strong> FAILED TO DELETED QUOTATION ".$quotationNo."\n
				</div>\n";
    }
    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/quotation/viewQuotation.php");

}

	if(isset($_POST['quotationMailButton'])){
		
		$con=connectDb();
		$quotationNo=$_POST['quotationNo'];
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/quotation.php");
		$quotationDetails=fetchQuotationDetailsByQuotationNo($con,$quotationNo);
		$_SESSION['quotationNumber']=$quotationNo;
		
		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/quotation/mailQuotation.php");
	}

	if(isset($_POST['quotationEditButton'])){
		$con=connectDb();
		$quotationNo=$_POST['quotationNo'];
		
		$_SESSION['quotationNumber']=$quotationNo;
		$_SESSION['editType']="overrideQuotation";
		
		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/quotation/createQuotation.php");

	}

	if(isset($_POST['quotationEditCreateButton'])){
		$con=connectDb();
		$quotationNo=$_POST['quotationNo'];
		
		$_SESSION['quotationNumber']=$quotationNo;
		$_SESSION['editType']="newQuotation";
		//echo $_SESSION['quotationNumber']." : ".$_SESSION['editType'];
		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/quotation/createQuotation.php");

	}
	
	function getQuotationMailList($quotationId_){
		$con=connectDb();
		$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
		$quotationId=$quotationId_;
		$quotationMailList=null;
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/quotationMailList.php");
		$quotationMailList=fetchQuotationMailListByQuotId($con,$quotationId);

		return $quotationMailList;
	}

	if(isset($_POST['downloadSelectedQuotationFile'])){
		if (isset($_POST['checkedRow'])) 
		{
			
			// single file download
			if(sizeof($_POST['checkedRow'])==1){

			
				ob_start();
				$file=$_SERVER['DOCUMENT_ROOT'].$config['appRoot'].'/resources/'.$_SESSION['orgId'].'/quotation/'.$_POST['checkedRow'][0].'.pdf';
				
				$fileinfo = pathinfo($file);
				$fileName ="QUOTATION_".$_POST['checkedRow'][0]."_".$_SESSION['userid']."_".date('Y-m-d_H-i-s').".pdf";
				ob_clean();
				ob_end_flush();
				header('Content-Type: application/pdf');
				header("Content-Disposition: attachment; filename=\"$fileName\"");
				header('Content-Length: ' . filesize($file));
				readfile($file);
			}else{ // archived file download

				$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
				$files = array();
				foreach($_POST['checkedRow'] as $fileName){
					array_push($files,$_SERVER['DOCUMENT_ROOT'].$config['appRoot'].'/resources/'.$_SESSION['orgId'].'/quotation/'.$fileName.'.pdf');
					//array($_SERVER['DOCUMENT_ROOT'].$config['appRoot'].'/resources/2/quotation/0000000001(1545036822).pdf', $_SERVER['DOCUMENT_ROOT'].$config['appRoot'].'/resources/2/quotation/0000000002(1545037058).pdf');

				} 
			
				$zipfile = 'quotation_'.$_SESSION['userid'].'_'.date('Y-m-d_H-i-s').'.zip';
				$zip = new ZipArchive;
				$zip->open($zipfile, ZipArchive::CREATE);
				
				foreach ($files as $file) {
				
					if(file_exists($file)){
						$zip->addFromString("QUOTATION_".basename($file),  file_get_contents($file));  
					}
					
				}

				$zip->close();

				if(!$zipfile)
				{

					echo "Nothing in zip";
				}else 
				{  
					ob_clean();
					ob_end_flush();
					header('Content-Description: File Transfer');
					header('Content-Type: application/zip');
					header('Content-disposition: attachment; filename='.$zipfile);
					header('Content-Length: ' . filesize($zipfile));
					readfile($zipfile);
					unlink($zipfile);
				}
			}
		}

	}

if(isset($_GET['clientId'])){
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/quotation.php");
    $con=connectDb();
    $clientId=$_GET['clientId'];
    $row=fetchClientCompanyContactByQuotationId($con,$clientId);
    if(!empty($row)){
        echo $row;}
    else{
        echo "";
    }
}
?>