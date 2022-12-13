<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");


if(!isset($_SESSION))
{
	session_name($config['sessionName']);
	session_start();
}
        require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/invoicereport.php");
// debug




	if(isset($_POST['processinvoicereport'])){
        
ob_start();

		$timecategory = $_POST['timeCategory'];

		$datesearch="";
   $clientname="";
		if($timecategory){$datesearch=$_POST['dateYear'];}

		else{$datesearch=$_POST['dateMonth'];}

		if(isset($_POST['clientCompanyId'])){
		if($_POST['clientCompanyId']!=0){
			$customerid="and customerid=".$_POST['clientCompanyId']."";
		}
		else{$customerid="";}}

		if(isset($_POST['invoiceStatus'])){
		if($_POST['invoiceStatus']!=-1)
		{$invstatus="and status=".$_POST['invoiceStatus']."";}
		else{ $invstatus="";}}


		$query="";
    	$query = "select invoiceno,customername,invoicedate,total,duedate,status from invoice where invoicedate like '" . $datesearch . "%' and invalidate=0 ".$customerid." ".$invstatus;
		$con=connectdb();
       
		$temp="";
		$reportdetails=fetchinvoicereportlistvalid($con,$query);

		$table='<table  id="invoicereport" class="display" style="width:100%">';
		$table1= $table;
		$table1=$table1.'<thead><tr><th colspan="7">Invoice Report '.date("y/m/d h:s").'</th></tr></thead>';

			$temp='<thead><tr>
			<th>no</th>'.'<th>invoice no</th>'.'<th>customer name</th>'.'<th>date</th>'.'<th>amount rm</th>'.'<th>due date</th>'.'<th>status</th>';
			$table=$table.$temp;
			$table1=$table1.$temp;

		$headcount=5;
			$temp='</tr></thead>';
			$table=$table.$temp;
			$table1=$table1.$temp;
		$rowscount=0;

		$rownum=1;

		$temp='<tbody>';

		$table=$table.$temp;
			$table1=$table1.$temp;

		$totalpaid=0;
		$totalunpaid=0;
		foreach ($reportdetails as $data){

			$temp.'<tr>';
			$temp='<td>'.$rownum.'</td>';
			$temp=$temp.'<td>'.sprintf("%010d", $data['invoiceno']).'</td>';
			$temp=$temp.'<td>'.$data['customername'].'</td>';
			$temp=$temp.'<td>'.rtrim($data['invoicedate']," 00:00:00").'</td>';
			$temp=$temp.'<td>'.$data['total'].'</td>';
			$temp=$temp.'<td>'.rtrim($data['duedate']," 00:00:00").'</td>';
			$temp=$temp.'<td>';
			if($data['status']=="1"){$temp=$temp.'unpaid';}else{$temp=$temp.'paid';}
			$table.'</td>';
			$temp=$temp.'</tr>';
			$table=$table.$temp;
			$table1=$table1.$temp;
			if($data['status']=="1"){
				$totalunpaid=$totalunpaid+floatval(str_replace(",","",$data['total']));
			}
			else if($data['status']=="0"){
				$totalpaid=$totalpaid+floatval(str_replace(",","",$data['total']));
			}
			$rowscount++;

			$rownum++;
			if($_POST['clientCompanyId']!=0){
				$clientname=$data['customername'];
			}
		}
		$temp='<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
		$temp=$temp.'<tr><td></td><td></td><td colspan="2">total unpaid</td><td>'.$totalunpaid.'</td><td></td><td></td></tr>';
		$temp=$temp.'<tr><td></td><td></td><td colspan="2">total paid</td><td>'.$totalpaid.'</td><td></td><td></td></tr>';
		$temp=$temp.'<tr><td></td><td></td><td colspan="2">total outstanding</><td>'.($totalunpaid-$totalpaid).'</td><td></td><td></td></tr>';
		$table1=$table1.$temp;
		$temp='</tbody></table>';
		$table=$table.$temp;
		$table1=$table1.$temp;
		//file_put_contents("./payroll.log",print_r($reportdetails,true));

		if($rowscount==0){$table="<center><h3 style=\"color:#555;\">no record found</h3></center>";}

		$_SESSION['totalunpaid']=$totalunpaid;
		$_SESSION['totalpaid']=$totalpaid;
		$status="";
		if($_POST['invoiceStatus']==-1)
		{$status="all";$_SESSION['outstanding'] = true;}
		elseif ($_POST['invoiceStatus']==1)
		{$status="unpaid";$_SESSION['outstanding'] = false;}
		elseif ($_POST['invoiceStatus']==0)
		{$status="paid";$_SESSION['outstanding'] = false;}


		$_SESSION['datesearch']="Invoice Report Search: ".$clientname." ".$status." ".$datesearch;
        $_SESSION["totalunpaid"]=$totalunpaid;
		$_SESSION["totalpaid"]=$totalpaid;
		$_SESSION['invoicetable'] = $table;


		$_SESSION['invoicetableexport'] = $table1;


		header("location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/invoice/report/report.php");

	}
?>
