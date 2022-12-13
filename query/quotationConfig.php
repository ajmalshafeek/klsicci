<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/genFunc.php");



	function fetchQuotationConfig($con,$orgId){

		$query="SELECT * FROM quotationconfig WHERE orgId=? ";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$orgId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);

		$row=$result->fetch_assoc();

		mysqli_stmt_close($stmt);

		return $row;
	}

	function updateQuotationConfig($con,$isStamp,$extraNote,$stampName,$quotationMailBody,$invoiceMailBody,$replaceLogo,$headerNote,$isLogo,$logoName,$isSign,$signName,$orgId){
		$success=false;
		$query="UPDATE quotationconfig SET isStamp=?, extraNote=?,stampName=?,
		quotationMailBody=?, invoiceMailBody=?, replaceLogo=?, headerNote=?,isLogo=?,logoName=?,isSign=?,signName=? WHERE orgId=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'issssisisisi',$isStamp,$extraNote,$stampName,$quotationMailBody,$invoiceMailBody,$replaceLogo,$headerNote,$isLogo,$logoName,$isSign,$signName,$orgId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		return $success;
	}

	function updateFormat($con,$format,$orgId){
		$success=false;
		$query="UPDATE quotationconfig SET format=? WHERE orgId=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ii',$format,$orgId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		return $success;
	}
?>
