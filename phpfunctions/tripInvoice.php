<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
if(!isset($_SESSION))
{
 	session_name($config['sessionName']);
	session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
// debug
date_default_timezone_set("Asia/Kuala_Lumpur");
	if(isset($_GET['getInvoiceByInvNo'])){
		$con=connectDb();
		$invoiceNo=$_GET['getInvoiceByInvNo'];
		$invoiceDetails=null;
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/tripInvoice.php");
		$invoiceDetails=fetchTripInvoiceDetailsByInvoiceNo($con,$invoiceNo);
		echo json_encode($invoiceDetails);

	}

	function latestInvoiceNo(){
		$invoiceNo=0;
		$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/tripInvoice.php");
		$con=connectDb();
		$orgId=$_SESSION['orgId'];
		$invoiceNo=getLatestTripInvoiceNo($con,$orgId);
		if($invoiceNo==null){
			$invoiceNo="0";

		}

		$invoiceNo=$invoiceNo+1;
		$invoiceNo=str_pad($invoiceNo,10,"0",STR_PAD_LEFT);
		return $invoiceNo;

	}


	if(isset($_POST['createTripInvoice'])){
		$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/tripInvoice.php");

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/pdf/tripInvoicePDF.php");
		$con=connectDb(); // query/connect.php
        //recurring
        $recurringDate="";
        if(isset($_POST['quot_recurringDate'])){
            $recurringDate=$_POST['quot_recurringDate'];}
        else {$recurringDate=null;}
        $recurringEnd="";
        if(isset($_POST['quot_recurringEnd'])){
            $recurringEnd=$_POST['quot_recurringEnd'];}
        else {$recurringEnd=null;}
        $recurringBy="";

        if(isset($_POST['quot_recurringBy'])){
            $recurringBy=$_POST['quot_recurringBy'];}
        else {$recurringBy=null;}
        $recurring=0;
        if(isset($_POST['quot_recurringBy'])){
            $recurring=1;
        }
        else {$recurring=0;}

		$customerName=$_POST['quot_customerName'];
		$attention=$_POST['quot_attention'];

		$myOrgName=$_POST['quot_myOrgName'];
		$myOrgAddress=$_POST['quot_myOrgAddress'];
		$orgPhone=$_POST['quot_orgPhone'];
		$orgFaxNo=$_POST['quot_orgFaxNo'];

		$invoiceNumber=$_POST['quot_quotationNo'];
		$invoiceDate=$_POST['quot_quotationDate'];
		$quotationTotalAmount=$_POST['quot_totalAmount'];
		$maxItemIndex=$_POST['maxItemIndex'];
        $dueDate="";
        if(isset($_POST['quot_dueDate'])){
          //  $dueDate=$_POST['quot_dueDate'];
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
		$paymentDate=null;
		$subTotal=null;
		$discount=0;
		if(isset($_POST['quot_discount'])){
            $discount= $_POST['quot_discount'];
        }
        $pref=null;
		if(isset($_POST['quot_prefnumber'])){
		$pref=$_POST['quot_prefnumber'];
		}
		$tax=null;
		$amountPaid=null;
		$total=$quotationTotalAmount;
		$status=1;
		$po="";
		$ponumber="";
		if(isset($_POST['quot_ponum'])){
			$po=$_POST['quot_ponum'];

		}
		if(isset($_POST['quot_ponumber'])){
			$ponumber=$_POST['quot_ponumber'];
			
		}

		for( $i=0; $i<$maxItemIndex; $i++ ){
			$itemDate=$_POST["itemDate$i"];
			$itemJob=$_POST["itemJob$i"];
			$itemVehicle=$_POST["itemVehicle$i"];
			$itemDocNo=$_POST["itemDocNo$i"];
			$itemDescription=$_POST["itemDesc$i"];
			$unitType=$_POST["unitType$i"];
			$amount=$_POST["amount$i"];

			$item=array("itemDate"=>$itemDate,"itemJob"=>$itemJob,"itemVehicle"=>$itemVehicle,"itemDocNo"=>$itemDocNo, "itemDescription"=>$itemDescription,
			"unitType"=>$unitType,"amount"=>$amount);

			$itemList[]=$item;


		}
		//$quotationNo;
		$invoiceDirectory=$_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/".$orgId."/tripinvoice/".date("Y/M");
		if (!file_exists($invoiceDirectory)) {
			mkdir($invoiceDirectory, 0777, true);
		}

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/quotationConfig.php");
		$quotConfig=fetchQuotationConfig($con,$orgId);

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/pdffooterlist.php");
		$footerId=$_POST["pdfFooter"];
		$pdfFooterList=fetchPdfFooterList($con,$footerId,$orgId);
		$content=$pdfFooterList[0]['content'];


		$invoiceDate=date('Y-m-d');

		$total=preg_replace("/[^0-9\.]/", '', $total);


		$dDate=date_format($dueDate,"Y-m-d");
		$fileName=date("Y/M/").$invoiceNumber."(".strtotime($createdDate).")";

		$invcId=createTripInvoice($con,$customerName,$customerAddress,$jobId,$invoiceNumber,$customerId,
		$attention,$createdBy,$createdDate,$invoiceDate,$paymentDate,$dDate,$subTotal,$discount,$tax,
		$total,$amountPaid,$status,$orgId,$fileName,$footerId,$recurring,$recurringDate,$recurringEnd,$recurringBy); // query/tripInvoice.php
        $saveSucess=false;
		if($invcId>0){
			require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/tripInvoiceItem.php");
			foreach($itemList as $item){
                $itemDate=$item["itemDate"];
                $itemJob=$item["itemJob"];
                $itemVehicle=$item["itemVehicle"];
                $itemDocNo=$item["itemDocNo"];
                $itemDescription=$item["itemDescription"];
                $unitType=$item["unitType"];
                $amount=$item["amount"];

				$subTotal=null;
				$tax=null;
				$discounti=null;
				$total=$amount;

                $amount=preg_replace("/[^0-9\.]/", '', $amount);
				$total=preg_replace("/[^0-9\.]/", '', $total);

				$saveSucess=createTripInvoiceItemBreakdown($con,$invcId,$itemDate,$itemJob,$itemVehicle,$itemDocNo,$itemDescription,$unitType,
                    $amount,$subTotal,$tax,$discount,$total); // query/quotationitem.php


                if($saveSucess){
                    $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
					<strong>SUCCESS!</strong> INVOICE CREATED SUCCESSFULLY \n
					</div>\n";
                }
                else{
                    deleteTripInvoice($con,$invcId);
                }
			}
		}

        $invoicePDF=generateTripInvoicePDF($myOrgName,$orgPhone,$orgFaxNo,$customerName,$attention,
            $customerAddress,$myOrgAddress,$invoiceNumber,$invoiceDate,
            $dueDate,$quotationTotalAmount,$itemList,$quotConfig,$content,$ponumber,$pref,$discount); // phpfunction/pdf/tripInvoicePDF.php
        //	$invoicePDF->output();

        $invoicePDF->output("$invoiceDirectory/$invoiceNumber(".strtotime($createdDate).").pdf",'F'); // save pdf to server // html2pdf method
//test
        print_r("<br />after create invoice pdf".$dDate);

        $_SESSION['invoiceNumber']=$invoiceNumber;
    if($recurring==0) {
        header("Location: https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/invoiceTrip/mailTripInvoice.php");
    } else if($saveSucess&&$recurring==1){
    $_SESSION['reInvoiceid']=$invcId;
    $_SESSION['reCustomerId']=$customerId;
    $_SESSION['reStartDate']=$recurringDate;
    $_SESSION['recurring']=$recurringBy;
    header("Location: https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/recurringInvoice/scheduler-insert.php");
        }
		//return $quotationNumber;
	}

	if(isset($_POST['updateTripInvoice'])){
		$saveSucess=false;
		$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/tripInvoice.php");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/pdf/tripInvoicePDF.php");
		$con=connectDb();

		$customerName=$_POST['quot_customerName'];
		$attention=$_POST['quot_attention'];

		$myOrgName=$_POST['quot_myOrgName'];
		$myOrgAddress=$_POST['quot_myOrgAddress'];
		$orgPhone=$_POST['quot_orgPhone'];
		$orgFaxNo=$_POST['quot_orgFaxNo'];

		$invoiceNumber=$_POST['quot_quotationNo'];
		$invoiceDate=$_POST['quot_quotationDate'];
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

		$invoiceDirectory=$_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/".$orgId."/tripinvoice/".date("Y/M");

		if (!file_exists($invoiceDirectory)) {
			mkdir($invoiceDirectory, 0777, true);
		}

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/quotationConfig.php");
		$quotConfig=fetchQuotationConfig($con,$orgId);

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/pdffooterlist.php");
		$footerId=$_POST["pdfFooter"];
		$pdfFooterList=fetchPdfFooterList($con,$footerId,$orgId);
		$content=$pdfFooterList[0]['content'];

		$invoicePDF=generateTripInvoicePDF($myOrgName,$orgPhone,$orgFaxNo,$customerName,$attention,
		$customerAddress,$myOrgAddress,$invoiceNumber,$invoiceDate,
		$dueDate,$quotationTotalAmount,$itemList,$quotConfig,$content); // phpfunction/pdf/tripInvoicePDF.php
	//	$invoicePDF->output();

		$invoiceDetails=fetchTripInvoiceDetailsByInvoiceNo($con,$invoiceNumber);
		$deleteFileSuccess=unlink($invoiceDirectory."/".$invoiceDetails['fileName'].'.pdf');

		$invoicePDF->output("$invoiceDirectory/$invoiceNumber(".strtotime($createdDate).").pdf",'F'); // save pdf to server // html2pdf method
		$invoiceDate=date('Y-m-d');
		$total=preg_replace("/[^0-9\.]/", '', $total);
		$dueDate=date_format($dueDate,"Y-m-d");

		$invoiceDetails=fetchTripInvoiceDetailsByInvoiceNo($con,$invoiceNumber);
		$updateSuccess=false;
		$fileName=date("Y/M/").$invoiceNumber."(".strtotime($createdDate).")";
		$updateSuccess=updateTripEditInvoice($con,$customerName,$customerAddress,$jobId,$invoiceNumber,$customerId,
		$attention,$createdBy,$createdDate,$invoiceDate,$paymentDate,$dueDate,$subTotal,$discount,$tax,
		$total,$amountPaid,$status,$fileName,$orgId,$invoiceDetails['id'],$footerId); // query/tripInvoice.php
		if($updateSuccess){
			require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/tripInvoiceItem.php");
			$deleteSuccess=deleteInvoiceItemBreakdownByInvId($con,$invoiceDetails['id']);
			foreach($itemList as $item){
                $itemDate=$item["itemDate"];
                $itemJob=$item["itemJob"];
                $itemVehicle=$item["itemVehicle"];
                $itemDocNo=$item["itemDocNo"];
                $itemDescription=$item["itemDescription"];
                $unitType=$item["unitType"];
                $amount=$item["amount"];
				$subTotal=null;
				$tax=null;
				$discount=null;
				$total=$price;

				$itemCost=preg_replace("/[^0-9\.]/", '', $itemCost);
				$total=preg_replace("/[^0-9\.]/", '', $total);

				$saveSucess=createTripInvoiceItemBreakdown($con,$invoiceDetails['id'],$itemDate,$itemJob,$itemVehicle,$itemDocNo,$itemDescription,$unitType,
                        $amount,$subTotal,$tax,$discount,$total); // query/quotationitem.php
                //createTripInvoiceItemBreakdown($con,$invoiceDetails['id'],$itemName,$itemDescription,$itemCost,$itemQty,$subTotal,$tax,$discount,$total); // query/quotationitem.php
			}
		}

		if($saveSucess){
			$_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
					<strong>SUCCESS!</strong> INVOICE UPDATED SUCCESSFULLY \n
					</div>\n";
		}

		$_SESSION['invoiceNumber']=$invoiceNumber;

		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/invoiceTrip/mailTripInvoice.php");


	}

	if(isset($_POST['makeInvoicePaid'])){
		$_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
					<strong>FAILED!</strong> FAILED TO CHANGE INVOICE STATUS \n
					</div>\n";
		$con=connectDb();
		$paymentDate=$_POST['paymentDate'];
		$invoiceNo=$_POST['invoiceNo'];
		$status=0;

		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/tripInvoice.php");
		$invoiceDetails=fetchTripInvoiceDetailsByInvoiceNo($con,$invoiceNo);
		$amountPaid=$invoiceDetails['total'];
		$invoiceId=$invoiceDetails['id'];
		$updateSuccess=updateTripInvoice($con,$invoiceId,$paymentDate,$amountPaid,$status);
		if($updateSuccess){
			$_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
				<strong>SUCCESS!</strong> SUCCESSFULLY CHANGED STATUS TO PAID \n
				</div>\n";
		}
		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/invoiceTrip/viewTripInvoice.php");

	}

	if(isset($_POST['invoiceMailButton'])){

		$con=connectDb();
		$invoiceNo=$_POST['invoiceNo'];
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/tripInvoice.php");
		$invoiceDetails=fetchTripInvoiceDetailsByInvoiceNo($con,$invoiceNo);
		$_SESSION['invoiceNumber']=$invoiceNo;

		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/invoiceTrip/mailTripInvoice.php");
	}

	if(isset($_POST['removeInvoice'])){
        require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/tripInvoice.php");
		$con=connectDb();
		$invoiceNo=$_POST['invoiceNo'];
		$status=deleteTripInvoice($con,$invoiceNo);
        if($status){
            $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
				<strong>SUCCESS!</strong> SUCCESSFUL TO DELETED INVOICE ".$invoiceNo."\n
				</div>\n";
        }else{
            $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
				<strong>SUCCESS!</strong> FAILED TO DELETED INVOICE ".$invoiceNo."\n
				</div>\n";
        }
		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/invoiceTrip/viewTripInvoice.php");

	}

	if(isset($_POST['invoiceEditButton'])){
		$con=connectDb();
		$invoiceNo=$_POST['invoiceNo'];

		$_SESSION['invoiceNumber']=$invoiceNo;
		$_SESSION['editType']="overrideInvoice";
		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/invoiceTrip/createTripInvoice.php");

	}

	if(isset($_POST['invoiceEditCreateButton'])){
		$con=connectDb();
		$invoiceNo=$_POST['invoiceNo'];

		$_SESSION['invoiceNumber']=$invoiceNo;
		$_SESSION['editType']="newInvoice";

		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/invoiceTrip/createTripInvoice.php");

	}

  if (isset($_POST['invalidateButton'])) {
    $_SESSION['feedback'] = "<div class='alert alert-danger' role='alert'>\n
          <strong>FAILED!</strong> FAILED TO CHANGE INVOICE STATUS \n
          </div>\n";
    $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/tripInvoice.php");
    $con=connectDb();
    $remark = $_POST['remark'];
    $invoiceNo=$_POST['invoiceNo'];
    $feedback = invalidateTripInvoice($con,$invoiceNo,$remark );
    if ($feedback) {
      $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
					<strong>SUCCESS!</strong> INVOICE ".$invoiceNo." SUCCESSFULLY INVALIDATED\n
					</div>\n";
    }
    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/invoiceTrip/viewTripInvoice.php");
  }

  if (isset($_POST['revalidateButton'])) {
    $_SESSION['feedback'] = "<div class='alert alert-danger' role='alert'>\n
          <strong>FAILED!</strong> FAILED TO CHANGE INVOICE STATUS \n
          </div>\n";
    $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/tripInvoice.php");
    $con=connectDb();
    $invoiceNo=$_POST['invoiceNo'];
    echo $feedback = revalidateTripInvoice($con,$invoiceNo);
    if ($feedback) {
        echo "success";
      $_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
          <strong>SUCCESS!</strong> INVOICE ".$invoiceNo." SUCCESSFULLY REVALIDATED\n
          </div>\n";
    }
    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/invoiceTrip/viewTripInvoice.php");
  }


	function tripInvoiceListTable($invoiceId_,$customerName_,$jobId_,$invoiceNo_,$customerId_,$createdBy_,
	$dateFrom_,$dateTo_,$status_,$orgId_){
		$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/tripInvoice.php");

		$con=connectDb();

		$invoiceId=$invoiceId_;
		$customerName=$customerName_;
		$jobId=$jobId_;
		$invoiceNo=$invoiceNo_;
		$customerId=$customerId_;
		$createdBy=$createdBy_;
		$dateFrom=$dateFrom_;
		$dateTo=$dateTo_;
		$status=$status_;
		$orgId=$orgId_;



		$invoiceList=fetchTripInvoiceList($con,$invoiceId,$customerName,$jobId,$invoiceNo,$customerId,
		$createdBy,$dateFrom,$dateTo,$status,$orgId);
	    //	echo sizeof($quotaionList);

		$table="";
		$table.='<table class="table table-hover table-bordered table-" id="invoiceListTable"
		width="100%" cellspacing="0" role="grid" style="width: 100%;">';
		  $table.="<thead class='thead-dark'>";

			$table.="<tr>";
				$table.="<th style='width:5%' ></th>";
				$table.="<th style='width:20%' scope='col'>Date</th>";
				$table.="<th scope='col'>Invoice</th>";
				$table.="<th scope='col'>Created By</th>";
				$table.="<th scope='col'>Customer Name</th>";
				$table.="<th scope='col'>Invoiced Amount</th>";
				$table.="<th scope='col'>Status</th>";

			$table.="</tr>";

		$table.="</thead>";

		$table.="<tbody>";

		foreach($invoiceList as $invoice){
			$table.="<tr data-value='".$invoice['fileName']."'> ";
			$table.='<td style="text-align: center; vertical-align: middle;">
						<input type="checkbox"  value="'.str_pad($invoice['fileName'],10,"0",STR_PAD_LEFT).'" name="checkedRow[]" />
					</td>';
			$table.="<td  >".date('D d-M-y H:i:s A',strtotime($invoice['createdDate']))."</td>";
			$table.="<td  >".str_pad($invoice['invoiceNo'],10,"0",STR_PAD_LEFT)."</td>";
			$table.="<td >".$invoice['name']."</td>";
			$table.="<td >".$invoice['customerName']."</td>";
			$table.="<td  >RM &nbsp".number_format($invoice['total'],2)."</td>";
			$table.="<td  >";

      if ($_SESSION['paidStatus']) {
        if($invoice['status']==1){
            if($invoice['invalidate']==0){
            $table.='<div class="alert alert-danger" role="alert" style="text-align:center">
                <strong>UNPAID</strong>
                </div>';
            }else if($invoice['invalidate']==1){
                $table.='<div class="alert alert-warning" role="alert" style="text-align:center">
                <strong>INVALID</strong>
                </div>';
            }
        }else if($invoice['status']==0){
            if($invoice['invalidate']==0){
            $table.='<div class="alert alert-success" role="alert" style="text-align:center">
              <strong>PAID</strong>
             </div>';
            }else if($invoice['invalidate']==1){
                $table.='<div class="alert alert-warning" role="alert" style="text-align:center">
                <strong>INVALID</strong>
                </div>';
            }
        }
      }else {
        $table.='<div class="alert alert-primary" role="alert" style="text-align:center">
          <strong>RESTRICTED</strong>
         </div>';
      }

			$table.="</td>";


			$table.="</tr>";

		}
		$table.="</tbody>";
		$table.="</table>";


		return $table;
	}

	function getTripInvoiceDetailsByInvNo($invoiceNo_){
		$con=connectDb();
		$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
		$invoiceNo=$invoiceNo_;
		$invoiceDetails=null;
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/tripInvoice.php");
		$invoiceDetails=fetchTripInvoiceDetailsByInvoiceNo($con,$invoiceNo);

		return $invoiceDetails;

	}

	function htmlEncodeNewLine($text){
		$breaks = array("<br />","<br>","<br/>");
		$text = str_ireplace($breaks,"", $text);
		$text = str_ireplace("\r\n","&#10;", $text);
		return $text;
	}

	function getTripInvoiceBreakdownByInvId($invoiceId_){
		$con=connectDb();
		$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
		$invoiceId=$invoiceId_;
		$invoiceBreakdown=null;
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/tripInvoiceItem.php");
		$invoiceBreakdown=fetchTripInvoiceDetailsByInvoiceId($con,$invoiceId);
		$dataList=array();

		foreach($invoiceBreakdown as $item){
			$item['itemDescription']=htmlEncodeNewLine($item['itemDescription']);
			$dataList[]=$item;

		}

		return $dataList;
	}

	function getTripInvoiceMailList($invoiceId_){
		$con=connectDb();
		$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
		$invoiceId=$invoiceId_;
		$invoiceMailList=null;
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/tripInvoiceMailList.php");
		$invoiceMailList=fetchTripInvoiceMailListByInvId($con,$invoiceId);

		return $invoiceMailList;
	}

	if(isset($_POST['downloadSelectedInvoiceFile'])){
		if (isset($_POST['checkedRow']))
		{

			
			// single file download
			if(sizeof($_POST['checkedRow'])==1){


				ob_start();
				$file=$_SERVER['DOCUMENT_ROOT'].$config['appRoot'].'/resources/'.$_SESSION['orgId'].'/tripinvoice/'.$_POST['checkedRow'][0].'.pdf';

				$fileinfo = pathinfo($file);
				$fileName ="INVOICE_".$_POST['checkedRow'][0]."_".$_SESSION['userid']."_".date('Y-m-d_H-i-s').".pdf";
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
					array_push($files,$_SERVER['DOCUMENT_ROOT'].$config['appRoot'].'/resources/'.$_SESSION['orgId'].'/tripinvoice/'.$fileName.'.pdf');
					//array($_SERVER['DOCUMENT_ROOT'].$config['appRoot'].'/resources/2/quotation/0000000001(1545036822).pdf', $_SERVER['DOCUMENT_ROOT'].$config['appRoot'].'/resources/2/quotation/0000000002(1545037058).pdf');

				}

				$zipfile = 'INVOICE_'.$_SESSION['userid'].'_'.date('Y-m-d_H-i-s').'.zip';
				$zip = new ZipArchive;
				$zip->open($zipfile, ZipArchive::CREATE);

				foreach ($files as $file) {

					if(file_exists($file)){
						$zip->addFromString("INVOICE_".basename($file),  file_get_contents($file));
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

  function tripInvoicePaidCount(){
    $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/tripInvoice.php");
    $con = connectDb();

    $count = count(tripInvoiceListPaid($con));
    return $count;
  }

  function tripInvoiceUnpaidCount(){
    $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/tripInvoice.php");
    $con = connectDb();

    $count = count(invoiceTripListUnpaid($con));
    return $count;
  }

  function tripInvoiceInvalidCount(){
    $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/tripInvoice.php");
    $con = connectDb();

    $count = count(invoiceTripListInvalid($con));
    return $count;
  }


    if(isset($_GET['invoicePaidCount'])){
      $date = $_GET['dateMonth'];
      $dateMonth = date("m",strtotime($date));
      $dateYear =  date("Y", strtotime($date));
      $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/tripInvoice.php");
      $con = connectDb();

      $count = count(invoiceTripListPaidByDate($con,$dateYear,$dateMonth));
      echo $count;
    }

    if(isset($_GET['invoiceUnpaidCount'])){
      $date = $_GET['dateMonth'];
      $dateMonth = date("m",strtotime($date));
      $dateYear =  date("Y", strtotime($date));
      $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
  		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
  		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/tripInvoice.php");
  		$con = connectDb();

  		$count = count(invoiceTripListUnpaidByDate($con,$dateYear,$dateMonth));
  		echo $count;
    }

    if(isset($_GET['quotationConvCount'])){
      $date = $_GET['dateMonth'];
      $dateMonth = date("m",strtotime($date));
      $dateYear =  date("Y", strtotime($date));
      $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/quotation.php");
      $con = connectDb();

      $count = count(quotationListPaidByDate($con,$dateYear,$dateMonth));
      echo $count;
    }

    if(isset($_GET['quotationUnconvCount'])){
      $date = $_GET['dateMonth'];
      $dateMonth = date("m",strtotime($date));
      $dateYear =  date("Y", strtotime($date));
      $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
  		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
  		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/quotation.php");
  		$con = connectDb();

  		$count = count(quotationListUnpaidByDate($con,$dateYear,$dateMonth));
  		echo $count;
    }


    if(isset($_GET['invoiceAmount'])){
      $date = $_GET['dateMonth'];
      $dateMonth = date("m",strtotime($date));
      $dateYear =  date("Y", strtotime($date));
      $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/tripInvoice.php");
      $con = connectDb();
      $sum = 0;
      $dataList = invoiceTripListPaidByDate($con,$dateYear,$dateMonth);
      foreach ($dataList as $data) {
        $sum = $sum + $data['amountPaid'];
      }
      echo $sum;
    }

    if(isset($_GET['invoicePaidCompare'])){
      $dateYear = $_GET['dateMonth'];
      $startMonth=strtotime("12:00am January 1".$dateYear);
      //$dateMonth = date("m", $startMonth);
      //$dateYear =  date("Y", strtotime($date));
      $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/tripInvoice.php");
      $con = connectDb();
      $str = "[";
      for ($month = 1; $month <= 12 ; $month++) {
        $sum = 0;
        $dataList = invoiceTripListPaidByDate($con,$dateYear,$month);
        foreach ($dataList as $data) {
          $sum = $sum + $data['amountPaid'];
        }
        $str .= $sum;
        if ($month != 12) {
          $str .= ",";
        }
      }
      $str .= "]";
      echo $str;
    }

if (isset($_GET['showInvoiceReceipt'])) {
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/tripInvoice.php");
  $con = connectDb();
  $invoiceId = $_GET['showInvoiceReceipt'];
  $row = fetchTripInvoiceDetailsById($con,$invoiceId);
  echo json_encode($row);
}


if(isset($_GET['clientId'])){
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/tripInvoice.php");
    $con=connectDb();
    $clientId=$_GET['clientId'];
    $row=fetchClientCompanyContactByInvoiceId($con,$clientId);
    if(!empty($row)){
        echo $row;}
    else{
        echo "";
    }
}

?>