<?php

include_once ("db.php");
	$customerid=$_POST['client_id'];
	$str="select id,invoiceNo from invoice where customerId='".$customerid."'";
	$invoice_result = mysqli_query($con,$str) or die(mysqli_error());
	//$invoices = $invoice_result->fetch_assoc();
	//$invoices = mysqli_fetch_array($invoice_result);
	//echo json_encode($invoices);
	echo '<option value="">-- Select Invoice --</option>';
	while ($row = mysqli_fetch_assoc($invoice_result))
	{
        
        echo "<option value='".$row['id']."'>".$row['invoiceNo']."</option>";
        
    }   

 ?>