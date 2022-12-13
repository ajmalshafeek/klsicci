<?php

//if(!isset($_SESSION))
//{
 //	session_name($config['sessionName']);
//	session_start();
//}

$config=parse_ini_file(__DIR__."/jsheetconfig.ini");
//date_default_timezone_set("Asia/Kuala_Lumpur");
//require(__DIR__.'/mPDF/vendor/autoload.php');
require $_SERVER['DOCUMENT_ROOT'].$config['appRoot'].'/external/mPDF/vendor/autoload.php';

include_once ("db.php");

$today_date=date('Y-m-d');
//$sql = "SELECT * FROM `invoice_scheduler` where  schedule_date= '$today_date' AND status=0";
$sql = "SELECT * FROM `invoice_scheduler` where status=0";
$result = mysqli_query($con, $sql);
$createInvoiceFunction=false;
$next=nextDate($today_date);
while($row = mysqli_fetch_assoc($result)){
    $repeat=$row['recurring'];
	$sql1 = "SELECT * FROM `invoice_scheduler_sub` where  invoice_scheduler_id= ".$row['id']."";
	$result1 = mysqli_query($con, $sql1);
	while($row1 = mysqli_fetch_assoc($result1)){
        $createInvoiceFunction=false;

		$client = "SELECT * FROM `clientcompany` where  id= ".$row1['client_id']."";
		$client_details = mysqli_query($con, $client);
		$cd = $client_details->fetch_assoc();
		
		$invoice = "SELECT * FROM `invoice` where  id= ".$row1['invoice_id']."";
		$invoice_details = mysqli_query($con, $invoice);
		$ind = $invoice_details->fetch_assoc();
        print("<br />");
        print_r($ind);
        print("<br />");

        if( dateEqGtCompare($ind['enddate'],$today_date) && dateEqCompare($row['next'],$today_date) ){
        if(strcasecmp($row['recurring'],"Month")==0){
            $createInvoiceFunction=true;
        }
        elseif(strcasecmp($row['recurring'],"Year")==0){
            $createInvoiceFunction=true;
        }

        }
    if($createInvoiceFunction==true) {

    $invoice_mid = "SELECT MAX(id) as id FROM `invoice` where  1=1";
    $invoice_mxid = mysqli_query($con, $invoice_mid);
    $mxid = $invoice_mxid->fetch_assoc();
    $imxid = $mxid['id'] + 1;
    $ninvoice_id = sprintf("%010d", $imxid);
    $org = "SELECT * FROM `organization` where  id= 2";
    $org_details = mysqli_query($con, $org);
    $orgn = $org_details->fetch_assoc();

    $duedate = date('Y-m-d', strtotime($today_date . ' + 10 days'));
    $iquery = "insert into invoice (customerName,customerAddress,invoiceNo,customerId,attention,createdBy,createdDate,invoiceDate,total,dueDate,status,orgId,footerId,isrecurring);
		values('" . $ind['customerName'] . "','" . $ind['customerAddress'] . "','" . $ninvoice_id . "','" . $ind['customerId'] . "','" . $ind['attention'] . "','" . $ind['createdBy'] . "','" . $today_date . "','" . $today_date . "','" . $ind['total'] . "','" . $duedate . "',1,'" . $config['orgId'] . "',1,0)";
    //print_r($iquery);
    $iresult = mysqli_query($con, $iquery);
    $last_id = mysqli_insert_id($con);

    $invoice_citem = "SELECT * FROM `invoiceitem` where  invoiceId= '$ind[id]'";
    $invoice_citems = mysqli_query($con, $invoice_citem);
    while ($itemc = $invoice_citems->fetch_assoc()) {
        $in = $itemc['itemName'];
        $id = $itemc['itemDescription'];
        $ic = $itemc['itemPrice'];
        $iq = $itemc['quantity'];
        $it = $itemc['total'];
        $itenquery = "insert into invoiceitem(invoiceId,itemName,itemDescription,itemPrice,quantity,total)values('" . $last_id . "','" . $in . "','" . $id . "','" . $ic . "','" . $iq . "','" . $it . "')";
        //print_r($itenquery);
        mysqli_query($con, $itenquery) or die(mysqli_error(e));
    }

    $myOrgName = $orgn['name'];
    $myOrgAddress = $orgn['address1'] . ',<br/>' . $orgn['address2'] . ',<br/>' . $orgn['postalCode'] . ' ' . $orgn['city'] . ',<br/>' . $orgn['state'];
    $orgPhone = $orgn['contact'];
    $orgFaxNo = $orgn['faxNo'];
    $customerName = $cd['name'];
    $quotationNumber = $ninvoice_id;
    $customerAddress = $ind['customerAddress'];
    $quotationDate = date('Y-M-d', strtotime($today_date));
    $dueDate = date('Y-M-d', strtotime($today_date . ' + 10 days'));
    $attention = $ind['attention'];

    $html2pdf = new \Mpdf\Mpdf();
    $table = "";
    $style = '
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
    $quotTable = "";
    //$quot.=$head;
//	$quotTable.=$style;
    $myOrLogo = "";
    $orgLogoSrc = 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/resources/' . $orgn['logoPath'] . '.png';
    if (@getimagesize($orgLogoSrc)) {

        $myOrLogo = '	<img  style="height:100px;max-width:272pt"  src="' . $orgLogoSrc . '" alt="logo" />';
    }


    $quotTableOrgHeader = '
		<table class="quotation-org-Header">
			<tbody>

				<tr>
					<td class="myOrgLogo" rowspan="4" valign="bottom">

					' . $myOrLogo . '

					</td>

					<td class="myOrgName" valign="bottom">
						' . $myOrgName . '
					</td>

				</tr>

				<tr>
					<td class="myOrgAddress" valign="top">
						' . nl2br($myOrgAddress) . '
					</td>


				</tr>
				<tr>

					<td class="contact" valign="top">
						Phone No : ' . $orgPhone . '
					</td>
				</tr>
				<tr>

					<td class="contact" valign="top">
						Fax No. : ' . $orgFaxNo . '
					</td>
				</tr>
			</tbody>
		</table>';
    $quotTableHeader = '
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
			<td rowspan="4" valign="top" > <b>TO : </b>
			</td>
			<td  class="customerName">
				' . $customerName . ',
			</td>

			<td class="meta-head">
				INVOICE #
			</td>
			<td class="meta-body">
				' . $quotationNumber . '

			</td>

		</tr>

		<tr class="meta">
			<td rowspan="3">
			' . nl2br($customerAddress) . '

			</td>
			<td class="meta-head">
				DATE
			</td>
			<td class="meta-body">
				' . $quotationDate . '

			</td>
		</tr>
		<tr>
			<td class="meta-head">
				DUE DATE
			</td>
			<td class="meta-body">
				' . $dueDate . '

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
				' . $attention . '
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
    $quotTableHeader .= '
	</tbody>
	</table>
	';
    $quotTableContent = '
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

    $quotItemList = '';
    $quotationTotalAmount = '';
    $sum = '';
    //for( $i=0; $i<$maxItemIndex; $i++ )
    $invoice_item = "SELECT * FROM `invoiceitem` where  invoiceId= '$ind[id]'";
    $invoice_items = mysqli_query($con, $invoice_item);
    while ($item = $invoice_items->fetch_assoc()) {
//print_r($item);	
        $itemName = $item['itemName'];
        $itemDescription = $item['itemDescription'];
        $itemCost = $item['itemPrice'];
        $itemQty = $item['quantity'];
        $price = $item['total'];
        $quotationTotalAmount += $price;
        $quotItemList .= '
			<tr>
				<td class="td-Item" valign="top">
				' . $itemName . '
				</td>

				<td class="td-Description" valign="top">
				' . nl2br($itemDescription) . '
				</td>

				<td class="td-UnitCost" valign="top">
				' . $itemCost . '
				</td>

				<td class="td-Quantity" valign="top">
				' . $itemQty . '
				</td>

				<td class="td-Price" valign="top">
				' . $price . '
				</td>
			</tr>
			';
    }

    $quotTableContent .= $quotItemList;

    $invoice_setting = "SELECT * FROM `quotationconfig` where  id= 1";
    $invoice_settings = mysqli_query($con, $invoice_setting);
    $quotConfig = $invoice_settings->fetch_assoc();
    $stamp = "";
    if ($quotConfig['isStamp']) {
        $stampSrc = $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . '/resources/' . $quotConfig['orgId'] . '/myOrg/' . $quotConfig['stampName'] . '.png';
        if (@getimagesize($stampSrc)) {
            $stamp = '<img style="height:100px;max-width:100px" src="' . $stampSrc . '" alt="logo" />';
        }
    }
    $extraNote = $quotConfig['extraNote'];
    $quotTableContent .= '
			<tr>
				<td colspan="2"  class="blank" style="border-right:1px solid black;">


				</td>

				<td colspan="2" class="footer-head-TOTAL" >
					TOTAL
				</td>

				<td class="footer-body-TOTAL" >
					' . number_format($quotationTotalAmount, 2) . '
				</td>

			</tr>
			<tr>
				<td class="blank" colspan="5" style="padding:10px;font-weight:bold">
				<br/>
				<br/>
				' . $extraNote . '
				</td>
			</tr>

			<tr>
				<td class="blank" colspan="3">
				</td>
				<td class="blank" colspan="2" style="padding:10px;font-weight:bold;text-align:center;">
					' . $myOrgName . '
					<br/>
					' . $stamp . '<hr/>
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

    $quotTable .= $style;
    $quotTable .= $quotTableOrgHeader;
    $quotTable .= $quotTableHeader;
    $quotTable .= $quotTableContent;


    $html2pdf->writeHTML($quotTable);
    //$html2pdf->writeHTML('test');


    //$html2pdf->output();


    //$dir = "C:/xampp/htdocs/jsoft/resources/2/invoice/";
    //$dir = 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/'.$quotConfig['orgId'].'/invoice/';
    $dir = $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . '/resources/' . $quotConfig['orgId'] . '/invoice/';


    ob_clean();


// $html2pdf->Output();


    $filename = $ninvoice_id . '(' . date('Hmis') . ')';
    $filename1 = $filename . ".pdf";


    $str = "UPDATE `invoice` SET  `fileName`='$filename' WHERE `id`='$last_id'";

    $top_result1 = mysqli_query($con, $str);


    $html2pdf->output($dir . $filename1, 'F');   // check
    //return $html2pdf;


//email content area
    $to = $cd['emailAddress'];
//$to        = "chidhambaramc@gmail.com"; 
    $from = "admin@jsoft.my";
    $subject = "Invoice";
    $body = "Dear $attention,<br/><br/>
				Great Day!!!<br/>Please find your attachment invoice. We appreciate your prompt payment.<br/><br/>
				<b>Invoice Number:</b> $ninvoice_id<br/>
				<b>Invoice Date: </b> $quotationDate<br/>
				<b>Amount Due:</b> $myOrgName <br/>
				<b>Due Date:</b> $dueDate <br/><br/><br/>
				Sincerely,<br/>
				$myOrgName<br/>
				$myOrLogo<br/>
				$myOrgAddress";


//$pdfLocation = "C:/xampp/htdocs/jsoft/resources/2/invoice/".$filename1;
    $pdfLocation = $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . '/resources/' . $quotConfig['orgId'] . '/invoice/' . $filename1;
    $pdfName = $filename1;
    $filetype = "application/pdf";

    $eol = PHP_EOL;
    $semi_rand = md5(time());
    $mime_boundary = "==Multipart_Boundary_$semi_rand";
    $headers = "From: $from $eol $myOrgName $eol" .
        "Content-Type: multipart/mixed;$eol boundary=\"$mime_boundary\"";


    $message = "--$mime_boundary$eol" .
        "Content-Type: text/html; charset=\"iso-8859-1\"$eol" .
        "Content-Transfer-Encoding: 7bit$eol$eol$body$eol";


    $file = fopen($pdfLocation, 'rb');
    $data = fread($file, filesize($pdfLocation));
    fclose($file);
    $pdf = chunk_split(base64_encode($data));

    $message .= "--$mime_boundary$eol" .
        "Content-Type: $filetype;$eol name=\"$pdfName\"$eol" .
        "Content-Disposition: attachment;$eol filename=\"$pdfName\"$eol" .
        "Content-Transfer-Encoding: base64$eol$eol$pdf$eol--$mime_boundary--";

    if (mail($to, $subject, $message, $headers)) {

        $str = "UPDATE `invoice_scheduler` SET  `next`='".$next."' WHERE `id`='" . $row['id'] . "'";
        mysqli_query($con, $str);
        //print_r($body);
        echo "The email was sent.";
    } else {
        echo "There was an error sending the mail.";
    }

//end email content area
}
	}
	
}

function dateEqCompare($var,$var1){
   $date= explode("-",$var);
    $date1=explode("-",$var1);
    $re=false;
    if((int)$date[0]==(int)$date1[0]){
        if((int)$date[1]==(int)$date1[1]){
            if((int)$date[2]==(int)$date1[2]){
                $re=true;
                print_r("eq".$var);
            }
        }
    }

    return $re;
}
function dateEqGtCompare($var,$var1){
    $date= explode("-",$var);
    $date1=explode("-",$var1);
    $re=false;
    if((int)$date[0]>(int)$date1[0]){
        $re=true;
    }
    elseif((int)$date[0]==(int)$date1[0]){
        if((int)$date[1]>(int)$date1[1]){$re=true;}
        elseif((int)$date[1]==(int)$date1[1]){
            if((int)$date[2]>=(int)$date1[2]){
                $re=true;
            }
        }
    }
    return $re;
}
function nextDate($var){
    $date = date('Y-m-d', strtotime('+1 month', strtotime($var)));
    return $date;
}
?>