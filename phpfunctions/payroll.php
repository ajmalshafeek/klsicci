<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
if(!isset($_SESSION))
{
 	session_name($config['sessionName']);
	session_start();
}   
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/payroll.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organization.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organizationUser.php");

if(isset($_POST['editPayroll'])){
  $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
  <strong>FAILED!</strong> FAILED TO UPDATE PAYROLL SETTING \n
  </div>\n";

  $con = connectDb();

  $staffId = $_POST['orgStaffId'];
  $staffInfo = fetchPayrollRowByStaffId($con,$staffId);
  if($staffInfo['id']!=NULL){
      if(isset($_POST['basic'])){
        $basic = $_POST['basic'];
      }else{
        $basic = $staffInfo['basic'];
      }

      if(isset($_POST['epf'])){
        $epf = $_POST['epf'];
      }else{
        $epf = $staffInfo['epf'];
      }
      if(isset($_POST['epfEmployee'])){
        $epfEmpPer = $_POST['epfEmployee'];
      }else{
          $epfEmpPer = $staffInfo['epfEmployee'];
      }

      if(isset($_POST['epfEmp'])){
	      $epfEmp = $_POST['epfEmp'];
	  }else{
	      $epfEmp = $staffInfo['epfEmp'];
	  }
	  if(isset($_POST['epfEmpyr'])){
		  $epfEmpyr = $_POST['epfEmpyr'];
	  }else{
		  $epfEmpyr = $staffInfo['epfEmpyr'];
	  }
	  if(isset($_POST['eis'])){
		  $eis = $_POST['eis'];
	  }else{
		  $eis = $staffInfo['eis'];
	  }

	  if(isset($_POST['socso'])){
        $socso = $_POST['socso'];
      }else{
        $socso = $staffInfo['socso'];
      }

	  if(isset($_POST['socsoEmp'])){
        $socsoEmp = $_POST['socsoEmp'];
      }else{
        $socsoEmp = $staffInfo['socsoEmp'];
      }
	  if(isset($_POST['socsoEmpPer'])){
          $socsoEmpPer = $_POST['socsoEmpPer'];
      }else{
         $socsoEmpPer = $staffInfo['socsoEmpPer'];
      }
	  if(isset($_POST['socsoEmployerPer'])){
        $socsoEmployerPer = $_POST['socsoEmployerPer'];
      }else{
        $socsoEmployerPer = $staffInfo['socsoEmployerPer'];
      }

      if(isset($_POST['pcb'])){
        $pcb = $_POST['pcb'];
      }else{
        $pcb = $staffInfo['pcb'];
      }

      if(isset($_POST['bankName'])){
        $bankName = $_POST['bankName'];
      }else{
        $bankName = $staffInfo['bankName'];
      }

      if(isset($_POST['bankAcc'])){
        $bankAcc = $_POST['bankAcc'];
      }else{
        $bankAcc = $staffInfo['bankAcc'];
      }

      if(isset($_POST['nasionality'])){
        $nasionality = $_POST['nasionality'];
      }else{
        $nasionality = null;
      }

      if(isset($_POST['taxId'])){
        $taxId = $_POST['taxId'];
      }else{
        $taxId = null;
      }

      $feedback = updatePayroll($con,$staffId,$basic,$epf,$socso,$socsoEmp,$pcb,$bankName,$bankAcc,$nasionality,$taxId,$epfEmp,$epfEmpyr,$eis,$epfEmpPer,$socsoEmpPer,$socsoEmployerPer);

  }else{


      if(isset($_POST['basic'])){
        $basic = $_POST['basic'];
      }else{
        $basic = null;
      }

      if(isset($_POST['epf'])){
        $epf = $_POST['epf'];
      }else{
        $epf = null;
      }

      if(isset($_POST['epfEmployee'])){
          $epfEmpPer = $_POST['epfEmployee'];
      }else{
          $epfEmpPer = $staffInfo['epfEmployee'];
      }

	  if(isset($_POST['epfEmp'])){
		  $epfEmp = $_POST['epfEmp'];
	  }else{
		  $epfEmp = null;
	  }
	  if(isset($_POST['epfEmpyr'])){
		  $epfEmpyr = $_POST['epfEmpyr'];
	  }else{
		  $epfEmpyr = null;
	  }
	  if(isset($_POST['eis'])){
		  $eis = $_POST['eis'];
	  }else{
		  $eis = null;
	  }

      if(isset($_POST['socso'])){
        $socso = $_POST['socso'];
      }else{
        $socso = null;
      }


      if(isset($_POST['socsoEmp'])){
          $socsoEmp = $_POST['socsoEmp'];
      }else{
          $socsoEmp = null;
      }

      if(isset($_POST['socsoEmpPer'])){
          $socsoEmpPer = $_POST['socsoEmpPer'];
      }else{
          $socsoEmpPer = $staffInfo['socsoEmpPer'];
      }
      if(isset($_POST['socsoEmployerPer'])){
          $socsoEmployerPer = $_POST['socsoEmployerPer'];
      }else{
          $socsoEmployerPer = $staffInfo['socsoEmployerPer'];
      }
      if(isset($_POST['pcb'])){
        $pcb = $_POST['pcb'];
      }else{
        $pcb = null;
      }

      if(isset($_POST['bankName'])){
        $bankName = $_POST['bankName'];
      }else{
        $bankName = null;
      }

      if(isset($_POST['bankAcc'])){
        $bankAcc = $_POST['bankAcc'];
      }else{
        $bankAcc = null;
      }

      if(isset($_POST['nasionality'])){
        $nasionality = $_POST['nasionality'];
      }else{
        $nasionality = null;
      }

      if(isset($_POST['taxId'])){
        $taxId = $_POST['taxId'];
      }else{
        $taxId = null;
      }

      $feedback = insertPayroll($con,$staffId,$basic,$epf,$socso,$socsoEmp,$pcb,$bankName,$bankAcc,$nasionality,$taxId,$epfEmp,$epfEmpyr,$eis,$epfEmpPer,$socsoEmpPer,$socsoEmployerPer);
  }
  if($feedback){
    $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
    <strong>SUCCESS!</strong>STAFF'S PAYROLL SETTING IS SUCCESSFULLY UPDATED \n
    </div>\n";
  }

  header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/payroll/payrollSetting.php");
}

function payslip(){
    $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
    $slip="<div class='p-3' style='border: 4px double black;width:100%;'>";
    $slip.="<img style='width:40%;' src='https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/resources/".$_SESSION['orgLogo'].".png' >";
    $slip.="<div class='row pt-3 pr-3 pl-3'><div class='p-3' style='border:1px solid black;width:70%'><center><h3><b>SALARY SLIP</b></h3><h6><b>Month Year</b></h6></center></div><div class='p-3' style='background:#FF8585;border:1px solid black;width:30%;border-left:0px;'><center><h2><b>CONFIDENTIAL</b></h2></center></div></div>";
    $slip.="<div class='row pb-0 pr-3 pl-3'><div class='p-3' style='border:1px solid black;border-top:0px;width:50%'><p>Name: Syahid</p><p>Employee ID: 950329045191</p></div><div class='p-3' style='border:1px solid black;border-top:0px;width:50%;border-left:0px;'><p>Designation: Sample</p><p>Department: IT Department</p><p>Salary Month: RM 1800</p></div></div>";
    $slip.="<div class='row pb-0 pr-3 pl-3'><div class='p-1' style='background:red;color:white;border:1px solid black;border-top:0px;width:50%'><center>Description</center></div><div class='p-1' style='background:red;color:white;border:1px solid black;border-top:0px;width:25%;border-left:0px;'><center>Earnings</center></div><div class='p-1' style='background:red;color:white;border:1px solid black;border-top:0px;width:25%;border-left:0px;'><center>Deductions</center></div></div>";
    $slip.="<div class='row pb-0 pr-3 pl-3'><div class='p-1' style='border:1px solid black;border-top:0px;width:50%'><p>Basic Salary</p><p>EPF(%)</p><p>SOCSO</p><p>PCB</p></div><div class='p-1' style='border:1px solid black;border-top:0px;width:25%;border-left:0px;'><p style='text-align:right;'>1,800</p></div><div class='p-1' style='border:1px solid black;border-top:0px;width:25%;border-left:0px;'><p style='text-align:right;'>-</p><p style='text-align:right;'>11</p><p style='text-align:right;'>11</p><p style='text-align:right;'>11</p></div></div>";
    $slip.="<div class='row pb-0 pr-3 pl-3'><div class='p-1' style='border:1px solid black;border-top:0px;width:50%'><p>Total</p></div><div class='p-1' style='border:1px solid black;border-top:0px;width:25%;border-left:0px;'><p style='text-align:right;'>1,800</p></div><div class='p-1' style='border:1px solid black;border-top:0px;width:25%;border-left:0px;'><p style='text-align:right;'>33</p></div></div>";
    $slip.="<div class='row pb-0 pr-3 pl-3'><div class='p-3' style='border:1px solid black;border-top:0px;width:50%'><p>Payment Date: 1/3/2020</p><p>Bank Name: CIMB</p><p>Bank Account Name: Muhammad Syahid bin Nor Azman</p><p>Bank Account: 12345678</p></div><div style='border:1px solid black;border-top:0px;width:50%;border-left:0px;'><div style='background:red;color:white;border-bottom:1px solid black;'><center><b>NET PAY</b></center></div><div style='background:#FF8585;border-bottom:1px solid black;'><center><h4>1,800.00</h4></center></div><div class='pt-5' style='height:100%'><center><h4>#VALUE!</h4></center></div></div></div>";
    $slip.="<div class='row pb-0 pr-3 pl-3'><center><p><b>This is a computer genetared document</b></p></center></div>";
    $slip.="</div>";

    echo $slip;
}
function checknum($str){
    if( is_string( $str ) ) {
        return str_replace( ',', '', $str );
    }else{
        return $str;
    }
}
if(isset($_GET['payslip'])){
    $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
    $con = connectDb();

    $staffId = $_GET['staffId'];
    $month = $_GET['month'];
    $year = $_GET['yearSlip'];
    $datePayment = $_GET['datePayment'];
    $datePayment = date("d/m/Y", strtotime($datePayment));
	
	$allowance=$_GET['allowance'];
	$claims=$_GET['claims'];
	$commissions=$_GET['commissions'];
	$ot=$_GET['ot'];
    $bonus=$_GET['bonus'];
    $deduction=$_GET['deduction'];

	if($allowance ==""){
		$allowance=0;
	}else{
		$allowance=number_format($allowance,2);
	}
	if($claims ==""){
		$claims=0;
	}else{
		$claims=number_format($claims,2);
	}
	if($commissions ==""){
		$commissions=0;
	}else{
		$commissions=number_format($commissions,2);
	}
	if($ot ==""){
		$ot=0;
	}else{
		$ot=number_format($ot,2);
	}
    if($bonus==""){
        $bonus=0;
    }
    else{
        $bonus=number_format($bonus,2);
    }
    if($deduction==""){
        $deduction=0;
    }
    else{
        $deduction=number_format($deduction,2);
    }

    $test = $staffId."<br>".$month."<br>".$year."<br>".$datePayment;
    file_put_contents('./error.log',$test,FILE_APPEND);
    $staff = getOrganizationUserDetails($con,$staffId);
    $staffName = $staff['fullName'];
    $employeeId = "00000".$staff['id'];
    $designation = $staff['staffDesignation'];
    $department = $staff['department'];

    //ORGANIZATION DETAILS
    $orgId = $_SESSION['orgId'];
    $organization = getOrganizationDetails($con,$orgId);
    $address = "<p style='font-size:10px;width:196px;position:relative;float: left;margin: 0;'>".$organization['address1'].", ".$organization['address2']."<br>".$organization['city'].", ".$organization['postalCode']."<br>".$organization['state']."</p>";

    $payroll = fetchPayrollRowByStaffId($con,$staffId);

    $nasionalityCheck = $payroll['nasionality'];
    if ($nasionalityCheck==0) {
      $nasionality = "Malaysian";
    }elseif ($nasionalityCheck==1) {
      $nasionality = "Foreigner";
    }
    $taxId = $payroll['taxId'];
    $salaryMonthCalc = $payroll['basic'];
   // $epfCalc = $payroll['epf'];
	$epfCalc="";
	if(isset($payroll['epfEmpyr'])){
	$epfCalc = $payroll['epfEmpyr'];
		$epf=$epfCalc;
	}

    $socsoCalc = $payroll['socso'];
    $pcbCalc = $payroll['pcb'];

	// $epfTotal = ($salaryMonthCalc*$epfCalc)/100;//Getting actual value RM of EPF

    $salaryMonth = number_format($payroll['basic'],2);
    // $epf = number_format($epfTotal,2);
    $socso = number_format($payroll['socso'],2);
    $socsoEmp = number_format($payroll['socsoEmp'],2);
    $pcb = number_format($payroll['pcb'],2);
	$eis= number_format($payroll['eis'],2);
	$epfEmp= number_format($payroll['epfEmp'],2);
	$epfEmpyr= number_format($payroll['epfEmpyr'],2);
	//$advance="";
	//$advance=$allowance + $claims + $commissions + $ot + $bonus;
	$advance=checknum($allowance) + checknum($claims) + checknum($commissions) + checknum($ot)+ checknum($bonus) - checknum($deduction);
    //$totalEarning = number_format(($salaryMonth + $advance),2);
    $totalEarning = checknum($advance) + checknum($payroll['basic']);


    if ($nasionalityCheck==0) {
      $console = "<script>console.log('nasionality=0')</script>";
      $totalDeductionCalc = checknum($socsoEmp) + checknum($pcbCalc)+checknum($eis)+checknum($epfEmp);
      $epfRight=number_format($epfEmp,2);
      $socsoRight=number_format($socsoEmp,2);
      $pcbRight=number_format($pcb,2);
	  $eisRight=number_format($eis,2);

      $epfLeft=number_format($epfEmpyr,2);
      $socsoLeft=number_format($socso,2);
      $pcbLeft="-";
      $eisLeft=number_format($eis,2);


    }elseif($nasionalityCheck==1){
      $console = "<script>console.log('nasionality=1')</script>";
      $totalDeductionCalc = checknum($pcbCalc) + checknum($epfEmp);

      $epfRight=number_format($epfEmp,2);
      $socsoRight="-";
      $pcbRight=number_format($pcb,2);
	  $eisRight="-";

      $epfLeft=number_format($epfEmpyr,2);
      $socsoLeft="-";
      $pcbLeft="-";
      $eisLeft="-";
    }

    $data=array();
    $loanAmount=0;
    $emi=0;
    $condition=false;
    if (isset($_SESSION['staffloan']) && $_SESSION['staffloan'] == 1) {
        require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/role.php");
        $data=getLoanDetails($staffId);
        if(!empty($data)){
            $loanAmount=$data['amount'];
            $emi=$data['emi'];
            $pending=$loanAmount-$emi;
            $date4=$data['start_date'];
            $date2=date("Y-m-d",mktime(0,0,0,$month,1,$year));
            $ts1 = strtotime($date4);
            $ts2 = strtotime($date2);

            $year1 = date('Y', $ts1);
            $year2 = date('Y', $ts2);

            $month1 = date('m', $ts1);
            $month2 = date('m', $ts2);

            $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
            if($diff>=0 && $pending>=0){
                $condition=true;
                $totalDeductionCalc=checknum($totalDeductionCalc)+checknum($emi);
                $_SESSION['loanAmount']=$loanAmount;
                $_SESSION['pending']=$pending;
                $_SESSION['emi']=$emi;
                $_SESSION['start_date']=$date4;
                $_SESSION['date2']=$date2;
            }
        }
    }

    $totalDeduction = number_format($totalDeductionCalc,2);
    $netPayCalc = $totalEarning - $totalDeductionCalc;

    $netPay = number_format($netPayCalc,2);

    $bankName = $payroll['bankName'];
    $bankAcc = $payroll['bankAcc'];

    switch ($month) {
    case "1":
        $monthText = "January";
        break;
    case "2":
        $monthText = "February";
        break;
    case "3":
        $monthText = "March";
        break;
    case "4":
        $monthText = "April";
        break;
    case "5":
        $monthText = "May";
        break;
    case "6":
        $monthText = "Jun";
        break;
    case "7":
        $monthText = "July";
        break;
    case "8":
        $monthText = "August";
        break;
    case "9":
        $monthText = "September";
        break;
    case "10":
        $monthText = "October";
        break;
    case "11":
        $monthText = "November";
        break;
    case "12":
        $monthText = "December";
        break;
    default:
        $monthText = "Error";
    }
    //define session value to be used in phpfunctions/pdf/payslipPDF.php
    $_SESSION['nasionalityCheck'] = $nasionalityCheck;
    $_SESSION['address'] = $organization['address1'].", ".$organization['address2']."<br>".$organization['city'].", ".$organization['postalCode']."<br>".$organization['state'];
    $_SESSION['monthText'] = $monthText;
    $_SESSION['staffName'] = $staffName;
    $_SESSION['staffId'] = $staffId;
    $_SESSION['taxId'] = $taxId;
    $_SESSION['nasionality'] = $nasionality;
    $_SESSION['employeeId'] = $employeeId;
    $_SESSION['designation'] = $designation;
    $_SESSION['department'] = $department;
    $_SESSION['salaryMonth'] = $salaryMonth;
    $_SESSION['year'] = $year;
    $_SESSION['socsoEmp'] = $socsoEmp;

    $_SESSION['allowance'] = $allowance;
    $_SESSION['claims'] = $claims;
    $_SESSION['commissions'] = $commissions;
    $_SESSION['ot'] = $ot;
	$_SESSION['bonus']=$bonus;
	$_SESSION['deduction']=$deduction;

    $_SESSION['epf'] = $epfCalc;
    $_SESSION['epfPerc'] = $epfCalc;
    $_SESSION['socso'] = $socso;
    $_SESSION['pcb'] = $pcb;

    $_SESSION['totalEarning'] = $totalEarning;
	$_SESSION['totalDeduction'] = $totalDeduction;
    $_SESSION['datePayment'] = $datePayment;
    $_SESSION['bankName'] = $bankName;
    $_SESSION['bankAcc'] = $bankAcc;
    $_SESSION['netPay'] = $netPay;
    $_SESSION['epfLeft'] = $epfLeft;
    $_SESSION['epfRight'] = $epfRight;
    $_SESSION['socsoLeft'] = $socsoLeft;
    $_SESSION['socsoRight'] = $socsoRight;
    $_SESSION['pcbLeft'] = $pcbLeft;
    $_SESSION['pcbRight'] = $pcbRight;

	$_SESSION['eis'] = $eis;
	$_SESSION['epfEmp'] = $epfEmp;
	$_SESSION['epfEmpyr'] = $epfEmpyr;

	$_SESSION['eisLeft']=$eisLeft;
	$_SESSION['eisRight']=$eisRight;


    $style="style='margin-bottom:2px'";	
	$adisplay="";
	$ndisplay="";
	$adisplay.="<p style='text-align:right;'>&nbsp;</p><p style='text-align:right;'>&nbsp;</p><p style='text-align:right;'>&nbsp;</p><p style='text-align:right;'>&nbsp;</p>";
	if($allowance !=0){
		$ndisplay.="<p>Allowance</p>";
		$adisplay.="<p style='text-align:right;'>".$allowance."</p>";
	}
	if($claims !=0){
		$ndisplay.="<p>Claims</p>";
		$adisplay.="<p style='text-align:right;'>".$claims."</p>";
		
	}
	if($commissions !=0){
		$ndisplay.="<p>Commissions</p>";
		$adisplay.="<p style='text-align:right;'>".$commissions."</p>";
	}
	if($ot !=0){
		$ndisplay.="<p>OT</p>";
		$adisplay.="<p style='text-align:right;'>".$ot."</p>";
	}
    if($bonus !=0){
        $ndisplay.="<p>Bonus</p>";
        $adisplay.="<p style='text-align:right;'>".$bonus."</p>";
    }
    if($deduction !=0){
        $ndisplay.="<p>Deduction</p>";
        $adisplay.="<p style='text-align:right;'>".$deduction."</p>";
    }
	

    $slip=$console."<div class='p-3' style='border: 4px double black;width:100%;'>";
    $slip.="";
    $slip.='<!--<div class="row pt-3 pr-3 pl-3">
    <div class="p-3" style="border:1px solid black;width:70%">
        <img style="width:20%;float:left" src="https://'.$_SERVER['HTTP_HOST'].$config['appRoot']."/resources/".$_SESSION['orgLogo'].'.png" >
        <center><h3><b>SALARY SLIP</b></h3></center>'.$address.'<center><h7><b>'.$monthText.' '.$year.'</b></h7></center>
    </div>
    

    <div class="p-3" style="background:#FF8585;border:1px solid black;width:30%;border-left:0px;">
        <center><h2><b>CONFIDENTIAL</b></h2></center>
    </div>
</div>
-->
<div class="row pt-3 pr-3 pl-3">
    <div class="col" colspan="3" style="width:100%;"><img style="width:100%;" src="https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/smu_header.png" ></div>
</div>


<div class="row pb-0 pr-3 pl-3">
    <div class="p-3" style="border:1px solid black;border-top:0px;width:50%">
        <p style="margin-bottom:2px" >Name: '.$staffName.'</p>
        <p style="margin-bottom:2px" >Employee ID: '.$employeeId.'</p>
        <p style="margin-bottom:2px">Tax ID: '.$taxId.'</p>
        <p style="margin-bottom:2px">Nationality: '.$nasionality.'</p>
    </div>

    <div class="p-3" style="border:1px solid black;border-top:0px;width:50%;border-left:0px;">';
        if(isset($designation)&&!empty($designation)){$slip.='<p>Designation: '.$designation.'</p>';}
		if(isset($department)&&!empty($department)){$slip.='<p>Department: '.$department.'</p>';}
	//	$slip.='<p>Salary Month: RM '.$salaryMonth.'</p>';
		$slip.='<p>Salary Month: '.$monthText.' '.$year.'</p>';
    $slip.='</div>

</div>

<div class="row pb-0 pr-3 pl-3">
    <div class="p-1" style="background:red;color:white;border:1px solid black;border-top:0px;width:50%">
    <center>Description</center></div>

    <div class="p-1" style="background:red;color:white;border:1px solid black;border-top:0px;width:25%;border-left:0px;"><center>Earnings</center></div>

    <div class="p-1" style="background:red;color:white;border:1px solid black;border-top:0px;width:25%;border-left:0px;"><center>Deductions</center></div>
</div>

    <div class="row pb-0 pr-3 pl-3">
        <div class="p-1" style="border:1px solid black;border-top:0px;width:50%">
        <p>Basic Salary</p>
        <p>EPF(%)</p>
        <p>SOCSO</p>
        <p>PCB</p>
        <p>EIS</p>';
         if ((isset($_SESSION['staffloan']) && ($_SESSION['staffloan'] == 1)) && (($emi!=0) && ($condition==true))) {
             $slip.='<p>EMI</p>';
         }
             $slip.=''.$ndisplay.'
        </div>
        <div class="p-1" style="border:1px solid black;border-top:0px;width:25%;border-left:0px;">
            <p style="text-align:right;">RM '.$salaryMonth.'</p>'.$adisplay.'
        </div>

        <div class="p-1" style="border:1px solid black;border-top:0px;width:25%;border-left:0px;">

        <div style="text-align:right;border-bottom:1px solid black;margin-bottom:16px;display: flex;">
        <div style="display:inline-block; width: 50%; padding-left:20px;padding-right:20px;border-right:1px solid black;">Employer</div>
        <div style="display:inline-block;width:50%;padding-left:20px;padding-right:20px">Employee</div>
        </div>

        <div style="text-align:right;border-bottom:1px solid black;margin-bottom:16px;display: flex;">
            <div style="display:inline-block;width:50%;padding-right:20px;border-right:1px solid black;">&nbsp;'.$epfLeft.'</div>
            <div style="display:inline-block;width:50%;padding-right:20px">&nbsp;'.$epfRight.'</div>
        </div>

        <div style="text-align:right;border-bottom:1px solid black;margin-bottom:16px;display: flex;">
            <div style="display:inline-block;width:50%;padding-right:20px;border-right:1px solid black;">&nbsp;'.$socsoLeft.'</div>
            <div style="display:inline-block;width:50%;padding-right:20px">&nbsp;'.$socsoRight.'</div>
        </div>

        <div style="text-align:right;border-bottom:1px solid black;margin-bottom:16px;display:flex;">
            <div style="display:inline-block;width:50%;padding-right:20px;border-right:1px solid black;">&nbsp;'.$pcbLeft.'</div>
            <div style="display:inline-block;width:50%;padding-right:20px">&nbsp;'.$pcbRight.'</div>
        </div>

        <div style="text-align:right;border-bottom:1px solid black;margin-bottom:16px;display:flex;">
            <div style="display:inline-block;width:50%;padding-right:20px;border-right:1px solid black;">&nbsp;'.$eisLeft.'</div>
            <div style="display:inline-block;width:50%;padding-right:20px">&nbsp;'.$eisRight.'</div>
        </div>';
        if ((isset($_SESSION['staffloan']) && ($_SESSION['staffloan'] == 1)) && (($emi!=0) && ($condition==true))) {
            $slip .= '<div style="text-align:right;border-bottom:1px solid black;margin-bottom:16px;display:flex;">
            <div style="display:inline-block;width:50%;padding-right:20px;border-right:1px solid black;">&nbsp;-</div>
            <div style="display:inline-block;width:50%;padding-right:20px">&nbsp;' . $emi . '</div>
        </div>';
         }
        $slip.='<!--	//allowance
                //$slip.=$adisplay; -->
        </div>
    </div>
    <div class="row pb-0 pr-3 pl-3">
        <div class="p-1" style="border:1px solid black;border-top:0px;width:50%">
            <p>Total</p>
        </div>
        <div class="p-1" style="border:1px solid black;border-top:0px;width:25%;border-left:0px;">
            <p style="text-align:right;">RM '.number_format($totalEarning,2).'</p>
        </div>
        <div class="p-1" style="border:1px solid black;border-top:0px;width:25%;border-left:0px;">
            <p style="text-align:right;">RM '.number_format($totalDeduction,2).'</p>
        </div>
    </div>

    <div class="row pb-0 pr-3 pl-3">
        <div class="p-3" style="border:1px solid black;border-top:0px;width:50%">
            <p>Payment Date: '.$datePayment.'</p>
            <p>Bank Name: '.$bankName.'</p>
            <p>Bank Account Name: '.$staffName.'</p>
            <p>Bank Account: '.$bankAcc.'</p>
        </div>

        <div style="border:1px solid black;border-top:0px;width:50%;border-left:0px;">
            <div style="background:red;color:white;border-bottom:1px solid black;"><center><b>NET PAY</b></center></div>

            <div style="background:#FF8585;border-bottom:1px solid black;"><center><h5>RM '.$netPay.'</h5></center></div>

            <div class="pt-5" style="height:100%"><center><h4></h4></center></div>
        </div>
    </div>

    <div class="pb-0 pr-3 pl-3">
        <center><p><i><b>This is a computer genetared document</b></i></p></center>
    </div>';
    $_SESSION['payslip'] = $slip;
    echo $slip;
}

if(isset($_GET['getStaffSlipSetting'])){
   $con=connectDb();
   $staffId= $_GET['getStaffSlipSetting'];
   $data = fetchPayrollRowByStaffId($con,$staffId);
   echo json_encode($data);
}

function payrollTable(){
    $con=connectDb();
    $dataList = fetchPayrollRowAll($con);
    $table = "<table id='tablePayroll'><thead><tr><th>No.</th><th>Staff Name</th><th>Basic Salary</th><th>EPF(%)</th><th>SOCSO</th><th>PCB</th><th>Bank Name</th><th>Bank Account No.</th></tr></thead><tbody>";
    $no = 1;
    foreach($dataList as $data){
        $staff = getOrganizationUserDetails($con,$data['staffId']);

        $table.="<tr><td>".$no."</td><td>".$staff['fullName']."</td><td>".$data['basic']."</td><td>".$data['epf']."</td><td>".$data['socso']."</td><td>".$data['pcb']."</td><td>".$data['bankName']."</td><td>".$data['bankAcc']."</td></tr>";
        $no++;
    }
    $table.="</tbody></table>";
    return $table;
}

if(isset($_GET['printPayslip'])){
        require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/pdf/payslipPDF.php");
        require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/payroll.php");
		$con=connectDb();
		$monthText = $_SESSION['monthText'];
        $staffName = $_SESSION['staffName'];
        $staffId = $_SESSION['staffId'];
        $employeeId = $_SESSION['employeeId'];
        $designation = $_SESSION['designation'];
        $department = $_SESSION['department'];
        $salaryMonth = $_SESSION['salaryMonth'];
        $epf = $_SESSION['epf'];
        $epfPerc = $_SESSION['epfPerc'];
        $socso = $_SESSION['socso'];
        $pcb = $_SESSION['pcb'];
		$allowance = $_SESSION['allowance'];
        $claims = $_SESSION['claims'];
        $commissions = $_SESSION['commissions'];
        $ot = $_SESSION['ot'];
        $bonus = $_SESSION['bonus'];
		
        $totalEarning = $_SESSION['totalEarning'];
        $totalDeduction = $_SESSION['totalDeduction'];
        $datePayment = $_SESSION['datePayment'];
        $bankName = $_SESSION['bankName'];
        $bankAcc = $_SESSION['bankAcc'];
        $netPay = $_SESSION['netPay'];

		$eis=$_SESSION['eis'];
		$epfEmp=$_SESSION['epfEmp'];
		$epfEmpyr=$_SESSION['epfEmpyr'];

		$eisLeft=$_SESSION['eisLeft'];
		$eisRight=$_SESSION['eisRight'];

        $orgId=$_SESSION['orgId'];
		$payslipDirectory=$_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/".$orgId."/payslip/";
		if (!file_exists($payslipDirectory)) {
			mkdir($payslipDirectory, 0777, true);
		}

		$payslipPDF=generatePayslipPDF();
		$payslipName = "P".rand(1000000,9999999).".pdf";
		$payslipPDF->output($payslipDirectory."/".$payslipName,'F');

		insertPayslipInformation($con,$monthText,$staffName,$staffId,$employeeId,$designation,$department,$salaryMonth,$epf,$epfPerc,$socso,$pcb,$allowance,$claims,$commissions,$ot,$bonus,$totalEarning,$totalDeduction,$datePayment,$bankName,$bankAcc,$netPay,$payslipName,null);
    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/payroll/print.php");
}

if(isset($_GET['generatePayslipPDF'])){


    $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
    <strong>FAILED!</strong> FAILED TO CREATE PAYSLIP \n
    </div>\n";
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/pdf/payslipPDF.php");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/payroll.php");

    	$con=connectDb();
		$monthText = $_SESSION['monthText'];
        $staffName = $_SESSION['staffName'];
        $staffId = $_SESSION['staffId'];
        $employeeId = $_SESSION['employeeId'];
        $designation = $_SESSION['designation'];
        $department = $_SESSION['department'];
        $salaryMonth = $_SESSION['salaryMonth'];
      //  $slipYear=$_SESSION['salaryYear'];
        $epf = $_SESSION['epf'];
        $epfPerc = $_SESSION['epfPerc'];
        $socso = $_SESSION['socso'];
        $pcb = $_SESSION['pcb'];
	    $eis = $_SESSION['eis'];
	    $epfEmp = $_SESSION['epfEmp'];
	    $epfEmpyr = $_SESSION['epfEmpyr'];
        $allowance = $_SESSION['allowance'];
        $claims = $_SESSION['claims'];
        $commissions = $_SESSION['commissions'];
        $ot = $_SESSION['ot'];
        $year=$_SESSION['year'];
        $bonus="";
        $deduction="";
	    $datePayment = $_SESSION['datePayment'];//ET
    if(isset($_SESSION['bonus'])){
        $bonus = $_SESSION['bonus'];}
    if(isset($_SESSION['deduction'])){
        $deduction = $_SESSION['deduction'];}
        $totalEarning = $_SESSION['totalEarning'];
        $totalDeduction = $_SESSION['totalDeduction'];
        $bankName = $_SESSION['bankName'];
        $bankAcc = $_SESSION['bankAcc'];
        $netPay = $_SESSION['netPay'];

        $orgId=$_SESSION['orgId'];
    $pending=0;
		$payslipDirectory=$_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/".$orgId."/payslip/";
	//(START)DATE ERROR FIX: Have to change date format by using string replace due to unknown error
        $datePayment = str_replace("/","-",$datePayment);
		$datePayment = date("Y-m-d", strtotime($datePayment));
	//(END)DATE ERROR FIX
	$check=insertPayslipCheck($con,$staffId,$monthText,$datePayment);
	if($check['found']==0){
		if (!file_exists($payslipDirectory)) {
			mkdir($payslipDirectory, 0777, true);
		}
		$payslipPDF=generatePayslipPDF();
		$payslipName = "P".rand(1000000,9999999).".pdf";
		$payslipPDF->output($payslipDirectory."/".$payslipName,'F');
        $paymentVoucher = null;

    $feedback = insertPayslipInformation($con,$monthText,$staffName,$staffId,$employeeId,$designation,$department,$salaryMonth,$epf,$epfPerc,$socso,$pcb,$allowance,$claims,$commissions,$ot,$bonus,$totalEarning,$totalDeduction,$datePayment,$bankName,$bankAcc,$netPay,$payslipName,$paymentVoucher);

    if ($feedback) {
        $pending =0;
        $loanAmount=$_SESSION['loanAmount'];
        $pending=$_SESSION['pending'];

        $sql0 = "UPDATE `staffloan`
            SET `pending`='$pending' WHERE `sid`='$staffId' ";
        $res1 = mysqli_query(connectDb(),$sql0);

      $_SESSION['feedback']='<div class="alert alert-success" role="alert">
      <strong>SUCCESS!</strong>PAYSLIP IS CREATED<br />
     <a href="https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/payroll/viewPayslip.php">View Payslips</a>
      </div>';
    }}
	else{
		$_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
      <strong>FAILED!</strong>ALREADY EXIST \n
      </div>\n";
	}


    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/payroll/payroll.php");
}

if (isset($_GET['generatepaymentVoucher'])) {
  $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
  <strong>FAILED!</strong> FAILED TO CREATE PAYMETN VOUCHER \n
  </div>\n";
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/pdf/payslipPDF.php");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/payroll.php");

  $con=connectDb();
  //(START)GENERATE PAYMENT VOUCHER
  $methodOfPayment = $_SESSION['methodOfPayment'];//methodOfPayment
  $theSumOf = $_SESSION['theSumOf'];//theSumOf
  $being = $_SESSION['being'];//being
  $payee = $_SESSION['payee'];//payee
  $date = $_SESSION['date'];//date
  $amount = $_SESSION['amount'];//amount
  $approvedBy = $_SESSION['approvedBy'];//approvedBy
  $paidBy = $_SESSION['paidBy'];//paidBy
  $to = $_SESSION['to'];//to
    $bonus="";
    if(isset($_SESSION['bonus'])){
        $bonus = $_SESSION['bonus'];}
  $refNo = substr($payslipName, 0 , (strrpos($payslipName, ".")));

  $paymentVoucher = buildpaymentVoucher($refNo,$amount,$date,$methodOfPayment,$to,$theSumOf,$being,$payee,$approvedBy,$paidBy);
  //(END)GENERATE PAYMENT VOUCHER


  //(START)DATE ERROR FIX: Have to change date format by using string replace due to unknown error
  $date = str_replace("/","-",$date);
  $date = date("Y-m-d", strtotime($date));
  //(END)DATE ERROR FIX

  //(START)FULFILLING THE ARGUMENTS
  $monthText = getMonthInText($date);
  $staffName = $being;
  $staffId = null;
  $employeeId = null;
  $designation = null;
  $department = null;
  $salaryMonth = $amount;
  $epf = null;
  $epfPerc = null;
  $socso = null;
  $pcb = null;
  $totalEarning = $amount;
  $totalDeduction = null;
  $datePayment = $date;
  $bankName = null;
  $bankAcc = null;
  $netPay = $amount;
  $payslipName = null;
  $paymentVoucher = $paymentVoucher;
 
  //(END)FULILLING THE ARGUMENTS

  $feedback = insertPayslipInformation($con,$monthText,$staffName,$staffId,$employeeId,$designation,$department,$salaryMonth,$epf,$epfPerc,$socso,$pcb,null,null,null,null,null,$totalEarning,$totalDeduction,$datePayment,$bankName,$bankAcc,$netPay,$payslipName,$paymentVoucher);

  if ($feedback) {
    $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
    <strong>SUCCESS!</strong>PAYMENT VOUCHER IS CREATED \n
    </div>\n";
  }

  header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/payroll/payroll.php");
}

function createPayslipPDF(){
    $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
        require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/pdf/payslipPDF.php");
        require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/payroll.php");

    	$con=connectDb();
		$monthText = $_SESSION['monthText'];
        $staffName = $_SESSION['staffName'];
        $staffId = $_SESSION['staffId'];
        $employeeId = $_SESSION['employeeId'];
        $designation = $_SESSION['designation'];
        $department = $_SESSION['department'];
        $salaryMonth = $_SESSION['salaryMonth'];
        $epf = $_SESSION['epf'];
        $epfPerc = $_SESSION['epfPerc'];
        $socso = $_SESSION['socso'];
        $pcb = $_SESSION['pcb'];
		$allowance = $_SESSION['allowance'];
        $claims = $_SESSION['claims'];
        $commissions = $_SESSION['commissions'];
        $ot = $_SESSION['ot'];
    $bonus="";
    if(isset($_SESSION['bonus'])){
        $bonus = $_SESSION['bonus'];}
		
        $totalEarning = $_SESSION['totalEarning'];
        $totalDeduction = $_SESSION['totalDeduction'];
        $datePayment = $_SESSION['datePayment'];
        $bankName = $_SESSION['bankName'];
        $bankAcc = $_SESSION['bankAcc'];
        $netPay = $_SESSION['netPay'];

        $orgId=$_SESSION['orgId'];
		$payslipDirectory=$_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/".$orgId."/payslip/";
		if (!file_exists($payslipDirectory)) {
			mkdir($payslipDirectory, 0777, true);
		}

		$payslipPDF=generatePayslipPDF();
		$payslipName = "P".rand(1000000,9999999).".pdf";
		$payslipPDF->output($payslipDirectory."/".$payslipName,'F');

		insertPayslipInformation($con,$monthText,$staffName,$staffId,$employeeId,$designation,$department,$salaryMonth,$epf,$epfPerc,$socso,$pcb,$allowance,$claims,$commissions,$ot,$bonus,$totalEarning,$totalDeduction,$datePayment,$bankName,$bankAcc,$netPay,$payslipName,null);
   



		//$invoicePDF->output("$payslipDirectory/$invoiceNumber(".strtotime($createdDate).").pdf",'F'); // save pdf to server // html2pdf method

	/*	-------------------------------MAIL-------------------------------------
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/configurationApp.php");
    $ccemail= getCCEmailId();
   require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/payroll.php");

    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");
    $orgDetails=fetchOrganizationDetails($_SESSION['orgId']);
    $messageBody="Payslip ".date("Y/m/d");
    $address=$orgDetails['supportEmail'];
	$email = new PHPMailer();

	$email->From      = $orgDetails['salesEmail'];
	$email->FromName  = strtoupper($orgDetails['name']);
	$email->Subject   = $_POST['subject'];
	$email->Body      = $messageBody;
	$email->IsHTML(true);

	$email->AddAddress( $address,$address);

    $email->AddCC($ccemail,$ccemail);

	$email->AddEmbeddedImage(dirname(__DIR__)."/resources/".$_SESSION['orgLogo'].".png", 'logo_2u');

	$file_to_attach = $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/".$orgId."/invoice/";

	$filename=$invoiceDetails['fileName'].".pdf";

	// add invoice file
	$email->AddAttachment( $file_to_attach.$filename , "INVOICE_#".$_SESSION['invoiceNumber'].".pdf" );

	if(!$email->send()) {
		echo 'Message could not be sent.';
		echo 'Mailer Error: ' . $email->ErrorInfo;
	} else {
		$mailMessage="<div class='alert alert-success' role='alert'>\n
				<strong>SUCCESS !</strong> INVOICE SENT SUCCESSFULLY\n
				</div>\n";
	}
	/*----------------------------(END)MAIL-----------------------------*/
    return true;
}
function payslipTable(){
    $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/payroll.php");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organizationUser.php");


    $con=connectDb();
    $i=1;
    $dataList = fetchPayslipRowAll($con);



    $table = "
    <table class='table' id='payslipListTable' width='100%' cellspacing='0' role='grid' style='width: 100%;'>

		<thead class='thead-dark'>
			<tr>
        <th style='width:5%' ></th>
				<th scope='col' style='width:5%' >No</th>
				<th scope='col'>Staff Name</th>
				<th scope='col'>Type</th>
			  <th scope='col'>Salary</th>
			  <th scope='col'>Net Pay</th>
			  <th scope='col'>Monthy/Year</th>
			</tr>
		</thead>
		<tbody>
    ";
    $dataList = array_reverse($dataList, true);

    foreach($dataList as $data){
    //$staff = getOrganizationUserDetails($con,$data['staffId']);
    $toggle = "onclick='loadPayslipPDF(".$data['id'].")' data-toggle='modal' data-target='#payslipPDFModal'";
$dataValue="";
    if ($data['payslip'] == null) {
      $type = "Payment Voucher";
        $dataValue=$data['payslip'];
    }elseif ($data['paymentVoucher'] == null) {
      $type = "Payslip";
        $dataValue=$data['paymentVoucher'];
    }

        $table.='
      <tr>
        <td style="width:5%"><input onclick="checkCheckbox()" type="checkbox" class="form-check-input" name="emailCheck[]" value="'.$data['id'].'"></td>
				<td '.$toggle.' scope="col" style="width:5%" data-value="'.$data['payslip'].'">'.$i.'</td>
				<td '.$toggle.' scope="col" data-value="'.$data['payslip'].'">'.$data['staffName'].'</td>
				<td '.$toggle.' scope="col" data-value="'.$data['payslip'].'">'.$type.'</td>
			  <td '.$toggle.' scope="col" data-value="'.$data['payslip'].'">'.$data['salaryMonth'].'</td>
			  <td '.$toggle.' scope="col" data-value="'.$data['payslip'].'">'.$data['netPay'].'</td>
			  <td '.$toggle.' scope="col" data-value="'.$data['payslip'].'">'.$data['monthText'].' '.date("Y",strtotime($data['datePayment'])).'</td>
			</tr>
    ';
    $i++;
    }
    $table.="
        </tbody>
    </table>
    ";
    return $table;
}

if(isset($_GET['loadPayslip'])){
    $payslipId = $_GET['loadPayslip'];
    file_put_contents("./0payroll.log","PayslipId: ".$payslipId."",FILE_APPEND);
    $con=connectDb();
    $row = fetchPayslipRowById($con,$payslipId);
    if ($row['payslip'] != null) {
      $pdfURL = "https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/resources/2/payslip/".$row['payslip'];
      $pdf = "<object id='payslipPDFObject' data='".$pdfURL."' frameborder='0' width='100%' height='400px' style='height: 85vh;'><p>Your web browser doesn't have a PDF plugin.Instead you can<a id='payslipPDFAnchor' target='_blank' href='".$pdfURL."'>click here to download the PDF file.</a></p></object>";
    }else {
      $pdf  = "";
    }

    echo $pdf;
}

if(isset($_GET['loadPaymentVoucher'])){

    $payslipId = $_GET['loadPaymentVoucher'];
    $con=connectDb();
    $row = fetchVoucherRowById($con,$payslipId);
    file_put_contents("./0payroll.log","\nVoucher: ".print_r($row,1)."",FILE_APPEND);
    if ($row['paymentVoucher'] != null) {
      $pdfURL = "https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/resources/2/paymentVoucher/".$row['paymentVoucher'];

      $pdf = "<object id='paymentVoucherPDFObject' data='".$pdfURL."' frameborder='0' width='100%' height='400px' style='height: 85vh;'><p>Your web browser doesn't have a PDF plugin.Instead you can<a id='paymentVoucherPDFAnchor' target='_blank' href='".$pdfURL."'>click here to download the PDF file.</a></p></object>";
    }else {
      $pdf = "";
    }

    echo $pdf;
}

if(isset($_GET['payrollAmount'])){
  $date = $_GET['dateMonth'];
  $dateMonth = date("m",strtotime($date));
  $dateYear =  date("Y", strtotime($date));
  $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/payroll.php");
  $con = connectDb();
  $sum = 0;
  $dataList = fetchPayrollListByDate($con,$dateYear,$dateMonth);
  foreach ($dataList as $data) {
    $str = $data['salaryMonth'];
    $newStr = str_replace(',', '', $str);
    $salaryMonth = intval($newStr);
    $sum = $sum + $salaryMonth;
  }
  echo $sum;
}

if (isset($_GET['removePayslip'])) {
  $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
  <strong>FAILED!</strong> FAILED TO REMOVE PAYSLIP \n
  </div>\n";

  $con = connectDb();
  $payslipId = $_GET['removePayslip'];
  $feeback = deletePayslipByPayslipId($con,$payslipId);
  if ($feeback) {
    $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
    <strong>SUCCESS!</strong>PAYSLIP IS REMOVED \n
    </div>\n";
  }
  header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/payroll/viewPayslip.php");
}

if (isset($_GET['loadPayslipDetails'])) {
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
  $con = connectDb();
  $payslipId = $_GET['loadPayslipDetails'];
  $row = fetchPayslipRowById($con,$payslipId);
  echo json_encode($row);
}

if (isset($_POST['updatePayroll'])) {
  $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
  <strong>FAILED!</strong> FAILED TO UPDATE PAYSLIP \n
  </div>\n";

  $con = connectDb();

  $payslipId = $_POST['payslipId'];

  $designation = $_POST['designation'];
  $department = $_POST['department'];
  $salaryMonth = $_POST['salaryMonth'];
  $epfPerc = $_POST['epfPerc'];
  $socso = $_POST['socso'];
  $pcb = $_POST['pcb'];
  $eis = $_POST['eis'];
  $epfEmp = $_POST['epfEmp'];
  $epfEmpyr = $_POST['epfEmpyr'];


  $allowance=empty($_POST['allowance'])?0:$_POST['allowance'];
  $claims=empty($_POST['claims'])?0:$_POST['claims'];
  $commissions=empty($_POST['commissions'])?0:$_POST['commissions'];
  $ot=empty($_POST['ot'])?0:$_POST['ot'];
  $bonus=empty($_POST['bonus'])?0:$_POST['bonus'];
  $datePayment = $_POST['datePayment'];
  $bankName = $_POST['bankName'];
  $bankAcc = $_POST['bankAcc'];

  //MONTH TEXT
  $monthText = getMonthInText($datePayment);
  //EPF
  $epf = $salaryMonth*$epfPerc/100;

  //TOTAL EARNING
  $totalEarning = $salaryMonth+$allowance+$claims+$commissions+$ot+$bonus;

  //TOTAL DEDUCTION
  $payslipId;
  $rowPayslip = fetchPayslipRowById($con,$payslipId);
  $staffId = $rowPayslip['staffId'];
  $rowPayroll = fetchPayrollRowByStaffId($con,$staffId);
  $nasionality = $rowPayroll['nasionality'];
  if ($nasionality==0) {
    $totalDeduction = $epf + $socso + $pcb;
  }elseif ($nasionality==1) {
    $totalDeduction = $pcb;
  }

  //NETPAY
  $netPay = $totalEarning - $totalDeduction;

  //OTHERS
  $rowOrgUser = fetchOrganizationUserbyId($con,$staffId);
  $staffName = $rowOrgUser['fullName'];
  $employeeId = str_pad($staffId, 7, '0', STR_PAD_LEFT);
	$_SESSION['eis'] = $eis;
	$_SESSION['epfEmp']=$epfEmp;
	$_SESSION['epfEmpyr']=$epfEmpyr;
	$_SESSION['nasionalityCheck']=$nasionality;

  //PAYSLIP NAME
  $payslip = generatePayslip($monthText,$staffName,$staffId,$employeeId,$designation,$department,$salaryMonth,$epf,$epfPerc,$socso,$pcb,$allowance,$claims,$commissions,$ot,$bonus,$totalEarning,$totalDeduction,$datePayment,$bankName,$bankAcc,$netPay);

  //UPDATE
  $feedback = updatePayslip($con,$monthText,$designation,$department,$salaryMonth,$epf,$epfPerc,$socso,$pcb,$allowance,$claims,$commissions,$ot,$bonus,$totalEarning,$totalDeduction,$datePayment,$bankName,$bankAcc,$netPay,$payslip,$payslipId);

  if ($feedback) {
    $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
    <strong>SUCCESS!</strong>STAFF'S PAYSLIP IS SUCCESSFULLY UPDATED \n
    </div>\n";
  }

  header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/payroll/viewPayslip.php");
}

function generatePayslip($monthText,$staffName,$staffId,$employeeId,$designation,$department,$salaryMonth,$epf,$epfPerc,$socso,$pcb,$allowance,$claims,$commissions,$ot,$bonus,$totalEarning,$totalDeduction,$datePayment,$bankName,$bankAcc,$netPay){
  $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/pdf/payslipPDF.php");

  $_SESSION['monthText'] = $monthText;
  $_SESSION['staffName'] = $staffName;
  $_SESSION['staffId'] = $staffId;
  $_SESSION['employeeId'] = $employeeId;
  $_SESSION['designation'] = $designation;
  $_SESSION['department'] = $department;
  $_SESSION['salaryMonth'] = $salaryMonth;
  $_SESSION['epf'] = $epf;
  $_SESSION['epfPerc'] = $epfPerc;
  $_SESSION['socso'] = $socso;
  $_SESSION['pcb'] = $pcb;
  
  $_SESSION['allowance'] = $allowance;
  $_SESSION['claims'] = $claims;
  $_SESSION['commissions'] = $commissions;
  $_SESSION['ot'] = $ot;
  $_SESSION['bonus']=$bonus;
  
  
  $_SESSION['totalEarning'] = $totalEarning;
  $_SESSION['totalDeduction'] = $totalDeduction;
  $_SESSION['datePayment'] = $datePayment;
  $_SESSION['bankName'] = $bankName;
  $_SESSION['bankAcc'] = $bankAcc;
  $_SESSION['netPay'] = $netPay;

  $orgId=$_SESSION['orgId'];
  $payslipDirectory=$_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/".$orgId."/payslip/";
  if (!file_exists($payslipDirectory)) {
    mkdir($payslipDirectory, 0777, true);
  }

  $payslipPDF=generatePayslipPDF();
  $payslipName = "P".rand(1000000,9999999).".pdf";
  $payslipPDF->output($payslipDirectory."/".$payslipName,'F');

  return $payslipName;
}

if (isset($_POST['emailPayrollSelected'])) {
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/mail.php");
  $payslips = $_POST['checkBoxArr'];
  $emails = $_POST['emails'];
  $emailsCc = $_POST['emailsCc'];

  $_SESSION['feedback'] = mailPayrolls($emails,$emailsCc,$payslips);
  header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/payroll/viewPayslip.php");
}

function buildpaymentVoucher($refNo,$amount,$date,$methodOfPayment,$to,$theSumOf,$being,$payee,$approvedBy,$paidBy){
  $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/pdf/paymentVoucher.php");
  $paymentVoucherDirectory=$_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/".$_SESSION['orgId']."/paymentVoucher/";
  if (!file_exists($paymentVoucherDirectory)) {
    mkdir($paymentVoucherDirectory, 0777, true);
  }

  $paymentVoucherName = "P".rand(1000000,9999999).".pdf";
  $refNo = substr($paymentVoucherName, 0 , (strrpos($paymentVoucherName, ".")));
  $paymentVoucherPDF=generatePaymentVoucherPDF($refNo,$amount,$date,$methodOfPayment,$to,$theSumOf,$being,$payee,$approvedBy,$paidBy);
  $paymentVoucherPDF->output($paymentVoucherDirectory."/".$paymentVoucherName,'F');

  return $paymentVoucherName;
}

if (isset($_GET['paymentVoucherPreview'])) {
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organization.php");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organizationUser.php");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/payroll.php");
  $con = connectDb();
  $methodOfPayment = $_GET['methodOfPayment'];//methodOfPayment
  $theSumOf = $_GET['theSumOf'];//theSumOf
  $being = $_GET['being'];//being
  $payee = $_GET['payee'];//payee
  $date = $_GET['datePaymentVoucher'];//date
  $amountPaymentVoucher = $_GET['amountPaymentVoucher'];

  $staffId = $_GET['staffId'];

  $amount = $amountPaymentVoucher;//amount
  $rowOrg = getOrganizationDetails($con,$_SESSION['orgId']);
  $rowOrgUser = fetchOrganizationUserbyId($con,$_SESSION['userid']);
  $approvedBy = $rowOrgUser['fullName'];//approvedBy
  $paidBy = $rowOrg['name'];//paidBy

  $to = $payee;//to

  $refNo = "<i></u> [Generated after Payment Voucher is submitted]<u></i>";//refNo

  //(START)STORE SESSION FOR BUILDING PDF
  $_SESSION['methodOfPayment'] = $methodOfPayment;//methodOfPayment
  $_SESSION['theSumOf'] = $theSumOf;//theSumOf
  $_SESSION['being'] = $being;//being
  $_SESSION['payee'] = $payee;//payee
  $_SESSION['date'] = $date;//date
  $_SESSION['amount'] = $amount;//amount
  $_SESSION['approvedBy'] = $approvedBy;//approvedBy
  $_SESSION['paidBy'] = $paidBy;//paidBy
  $_SESSION['to'] = $to;//to
  //(END)STORE SESSION FOR BUILDING PDF

  echo paymentVoucherDesign($refNo,$amount,$date,$methodOfPayment,$to,$theSumOf,$being,$payee,$approvedBy,$paidBy);
}

function paymentVoucherDesign($refNo,$amount,$date,$methodOfPayment,$to,$theSumOf,$being,$payee,$approvedBy,$paidBy){
    $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
  $checkIcon='<img src="http://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/2/extra/check.png" width="16px" height="16px">';
    if ($methodOfPayment == 0) {
  //  $tickCash = "&#10004;";
    $tickCash = $checkIcon;
    $tickCheck = "";
  }elseif ($methodOfPayment == 1) {
    $tickCash = "";
    //$tickCheck = "&#10004;";
    $tickCheck = $checkIcon;
  }

  $heroFont = "font-size:30";
  $mainFont = "font-size:14";
  $content = "
  <div style='border: 2px solid black;width:100%;height:396px;padding:5px'>
  <table style='width:100%;'>
    <tr>
      <td style='text-align:center;".$heroFont.";'>
        Payment Voucher
      </td>
    </tr>
  </table>
  <table style='width:100%;'>
    <tr>
      <td style='text-align:left;".$mainFont.";'>
        Ref No:<u>".$refNo."</u>
      </td>
    </tr>
  </table>
  <table style='width:100%;border-top:1px solid black;border-right:1px solid black;border-collapse: collapse;'>
    <tr>
      <td style='width:50%;border-left:1px solid black;text-align:left;".$mainFont.";'>
        Amount(RM): ".number_format((float)$amount, 2, '.', '')."
      </td>
      <td style='width:50%;border-left:1px solid black;text-align:left;".$mainFont.";'>
        Date: ".$date."
      </td>
    </tr>
  </table>
  <table style='width:100%;border-top:1px solid black;border-right:1px solid black;border-collapse: collapse;'>
    <tr>
      <td style='border-left:1px solid black;text-align:center;".$mainFont.";'>
        <b>Method of Payment</b>
      </td>
    </tr>
  </table>
  <table style='width:100%;border-top:1px solid black;border-right:1px solid black;border-collapse: collapse;'>
    <tr>
      <td style='width:50%;border-left:1px solid black;text-align:left;".$mainFont.";'>
        Cash: ".$tickCash."
      </td>
      <td style='width:50%;border-left:1px solid black;text-align:left;".$mainFont.";'>
        Cheque: ".$tickCheck."
      </td>
    </tr>
  </table>
  <table style='width:100%;border-top:1px solid black;border-right:1px solid black;border-collapse: collapse;'>
    <tr>
      <td style='border-left:1px solid black;text-align:left;".$mainFont.";'>
        To: ".$to."
      </td>
    </tr>
  </table>
  <table style='width:100%;border-top:1px solid black;border-right:1px solid black;border-collapse: collapse;'>
    <tr>
      <td style='border-left:1px solid black;text-align:left;".$mainFont.";'>
        Payment for: ".$theSumOf."
      </td>
    </tr>
  </table>
  <table style='width:100%;border-top:1px solid black;border-right:1px solid black;border-collapse: collapse;'>
    <tr>
      <td style='width:66.66%;border-left:1px solid black;text-align:left;".$mainFont.";'>
        Remarks:<br>&nbsp;&nbsp;&nbsp;&nbsp;".$being."<br><br><br>
      </td>
      <td style='width:33.33%;border-left:1px solid black;text-align:left;".$mainFont.";'>
        Payee:<br>&nbsp;&nbsp;&nbsp;&nbsp;".$payee."<br><br><br>
      </td>
    </tr>
  </table>
  <table style='width:100%;border-top:1px solid black;border-bottom:1px solid black;border-right:1px solid black;border-collapse: collapse;'>
    <tr>
      <td style='width:33.33%;border-left:1px solid black;text-align:left;".$mainFont.";'>
        Approved By:<br>&nbsp;&nbsp;&nbsp;&nbsp;".$approvedBy."<br><br><br>
      </td>
      <td style='width:33.33%;border-left:1px solid black;text-align:left;".$mainFont.";'>
        Paid By:<br>&nbsp;&nbsp;&nbsp;&nbsp;".$paidBy."<br><br><br>
      </td>
      <td style='width:33.33%;border-left:1px solid black;text-align:left;".$mainFont.";'>
        Signature<br><br><br><br>
      </td>
    </tr>
  </table>
  </div>
  ";

  return $content;
}

function getMonthInText($date){
  $month = date("m", strtotime($date));
  switch ($month) {
    case '1':
      $monthText = "January";
      break;
    case '2':
      $monthText = "February";
      break;
    case '3':
      $monthText = "March";
      break;
    case '4':
      $monthText = "April";
      break;
    case '5':
      $monthText = "May";
      break;
    case '6':
      $monthText = "Jun";
      break;
    case '7':
      $monthText = "July";
      break;
    case '8':
      $monthText = "August";
      break;
    case '9':
      $monthText = "September";
      break;
    case '10':
      $monthText = "October";
      break;
    case '11':
      $monthText = "November";
      break;
    case '12':
      $monthText = "December";
      break;
    default:
      $monthText = "";
      break;
  }

  return $monthText;
}
?>