<?php
//use Spipu\Html2Pdf\Html2Pdf; //exabyte no support
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
    session_name($config['sessionName']);
    session_start();
}
require $_SERVER['DOCUMENT_ROOT'].$config['appRoot'].'/external/mPDF/vendor/autoload.php';

function generatePayslipPDF(){

	$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");

	$html2pdf=new \Mpdf\Mpdf();
    $htmlStart = "<html>";

    //$headStart = "<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">";
    //<link href='https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/adminTheme/custom-css/custom-css.css' rel='stylesheet'>
    $style="
    <style>
    @page { size: landscape; }
      p{
          font-size:11px;
      }
      h3{
          font-size:19px;
      }
      h2{
          font-size:19px;
      }
      .row{
          border:1px solid black;
          border-bottom:0px;
      }
      .col{
          text-align:center;
      }
    </style>";
    
    //$headEnd = "</head>";
    $bodyStart = "<body>";
    $bodyEnd = "</body>";
    $htmlEnd = "</html>";


    $monthText = $_SESSION['monthText'];
    $staffName = $_SESSION['staffName'];
    $employeeId = $_SESSION['employeeId'];
    $designation = $_SESSION['designation'];
    $department = $_SESSION['department'];
    $salaryMonth = $_SESSION['salaryMonth'];
    $epf = $_SESSION['epf'];
    $socso = $_SESSION['socso'];
    $socsoEmp = $_SESSION['socsoEmp'];
    $pcb = $_SESSION['pcb'];
    $year=$_SESSION['year'];
    $totalDeduction = $_SESSION['totalDeduction'];
    if (isset($_SESSION['staffloan']) && $_SESSION['staffloan'] == 1) {
        $loadAmount = $_SESSION['loanAmount'];
        $emi = $_SESSION['emi'];
        $pending=$_SESSION['pending'];
        $date4 = $_SESSION['start_date'];
        $date2=date("Y-m-d");
        $ts1 = strtotime($date4);
        $ts2 = strtotime($date2);

        $year1 = date('Y', $ts1);
        $year2 = date('Y', $ts2);

        $month1 = date('m', $ts1);
        $month2 = date('m', $ts2);

        $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
       // $diffe=$loadAmount/$emi;
        if($diff>0 && $pending>=0){
            $condition=true;
          //  $diffe-$diff;
        }
    }
	$allowance = $_SESSION['allowance'];
    $claims = $_SESSION['claims'];
    $commissions = $_SESSION['commissions'];
    $ot = $_SESSION['ot'];
    $bonus=0;
    if(isset($_SESSION['bonus'])){
    $bonus=$_SESSION['bonus'];}
    $deduction=0;
    if(isset($_SESSION['deduction'])){
        $deduction=$_SESSION['deduction'];}
		
    $totalEarning = $_SESSION['totalEarning'];

    $datePayment = $_SESSION['datePayment'];
    $bankName = $_SESSION['bankName'];
    $bankAcc = $_SESSION['bankAcc'];
    $netPay = $_SESSION['netPay'];

	$eis=$_SESSION['eis'];
	$epfEmp=$_SESSION['epfEmp'];
	$epfEmpyr=$_SESSION['epfEmpyr'];


	$nasionalityCheck=$_SESSION['nasionalityCheck'];
	$epfRight=$epfEmp;
	$epfLeft=$epfEmpyr;
	if($nasionalityCheck==1){
		$socso="-";
			$eis="-";
	}
	$adisplay="";
	$ndisplay="";
	$edisplay="";
	//$adisplay.="<p style='text-align:right;'>&nbsp;</p>";
	if($allowance !=0){
		$ndisplay.="<p>Allowance</p>";
		$adisplay.="<p>".number_format($allowance,2)."</p>";
		$edisplay.="<p>-</p>";
	}
	if($claims !=0){
		$ndisplay.="<p>Claims</p>";
		$adisplay.="<p>".number_format($claims,2)."</p>";
		$edisplay.="<p>-</p>";
		
	}
	if($commissions !=0){
		$ndisplay.="<p>Commissions</p>";
		$adisplay.="<p>".number_format($commissions,2)."</p>";
		$edisplay.="<p>-</p>";
	}
	if($ot !=0){
		$ndisplay.="<p>OT</p>";
		$adisplay.="<p>".number_format($ot,2)."</p>";
		$edisplay.="<p>-</p>";
	}
    if($bonus !=0){
        $ndisplay.="<p>BONUS</p>";
        $adisplay.="<p>".number_format($bonus,2)."</p>";
        $edisplay.="<p>-</p>";
    }
    if($deduction !=0){
        $ndisplay.="<p>DEDUCTION</p>";
        $adisplay.="<p>".number_format($deduction,2)."</p>";
        $edisplay.="<p>-</p>";
    }


    $slip = "
    <div style='border: 4px double black;width:100%;height:396px;padding:5px'>
    <table style='border-collapse: collapse;width:100%'>
        <tr class='row'>
            <td class='col' colspan='3' style='width:100%;'><img style='width:100%;' src='https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/resources/smu_header.png' ></td>
        </tr> 
     <!-- <tr class='row'>
            <td class='col' style='width:20%;'><img style='width:20%;' src='https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/resources/".$_SESSION['orgLogo'].".png' ></td>
            <td class='col' style='width:50%;'><h3 style='margin-bottom:2px'>SALARY SLIP</h3><h7>".$monthText." ".$year."</h7></td>
            <td class='col' style='width:30%;border-left:1px solid black;'><h2>CONFIDENTIAL</h2></td>
        </tr> -->
    </table>
    <table style='border-collapse: collapse;width:100%'>
        <tr class='row'>
            <td style='width:50%'>
                <p>Name: ".$staffName."</p>
                <p>Employee ID: ".$employeeId."</p>
            </td>
            <td style='width:50%;border-left:1px solid black;'>";
	if(isset($department)&&!empty($designation)){
		$slip.="<p>Designation: ".$designation."</p>";}
	if(isset($department)&&!empty($department)){
		$slip.="<p>Department: ".$department."</p>";}
	$slip.="<p>Salary Month: RM".$netPay."</p>";
	//$slip.="<p>Salary Month: ".$monthText." ".$year."</p>";
    $slip.="</td>
        </tr>
    </table>
    <table style='border-collapse: collapse;width:100%'>
        <tr class='row'>
            <td style='color:white;background:red;width:50%;border-left:1px solid black;text-align:center'><p><b>Description</b></p></td>
            <td style='color:white;background:red;width:25%;border-left:1px solid black;text-align:center'><p><b>Earnings</b></p></td>
            <td style='color:white;background:red;width:25%;border-left:1px solid black;text-align:center'><p><b>Deductions</b></p></td>
        </tr>
    </table>
    <table style='border-collapse: collapse;width:100%'>
        <tr class='row'>
            <td style='width:50%;'>
                <p>Basic Salary</p>
                <p>EPF(%)</p>
                <p>SOCSO</p>
                <p>PCB</p>
                <p>EIS</p>";
    if ((isset($_SESSION['staffloan']) && ($_SESSION['staffloan'] == 1)) && (($condition==true) && ($emi!=0))) {
        $slip.='<p>EMI</p>';
    }
        $slip.="".$ndisplay."
            </td>
            <td style='width:25%;border-left:1px solid black;text-align:right'> <p>RM ".$salaryMonth."</p>
                <p>-</p>
                <p>-</p>
                <p>-</p>
                <p>-</p>";
    if ((isset($_SESSION['staffloan']) && ($_SESSION['staffloan'] == 1)) && (($condition==true) && ($emi!=0))) {
        $slip.='<p>-</p>';
    }
				$slip.="".$adisplay."
            </td>
            <td style='width:13%;border-left:1px solid black;text-align:right;vertical-align: top;' > <p>Employer</p>
                <p>".number_format($epfLeft,2)."</p>
                <p>".number_format($socso,2)."</p>
                <p>-</p>
                <p>".number_format($eis,2)."</p>";
    if ((isset($_SESSION['staffloan']) && ($_SESSION['staffloan'] == 1)) && (($condition==true) && ($emi!=0))) {
        $slip.="<p>-</p>";
    }
				$slip.=$edisplay."
            </td>
            <td style='width:12%;border-left:1px solid black;text-align:right;vertical-align: top;' ><p>Employee</p>
                <p>".number_format($epfRight,2)."</p>
                <p>".number_format($socsoEmp,2)."</p>
                <p>".number_format($pcb,2)."</p>
                <p>".number_format($eis,2)."</p>";
    if ((isset($_SESSION['staffloan']) && ($_SESSION['staffloan'] == 1)) && (($condition==true) && ($emi!=0))) {
        $slip.="<p>".number_format($emi,2)."</p>";
    }
				$slip.="".$edisplay."
            </td>
        </tr>
    </table>
    <table style='border-collapse: collapse;width:100%'>
        <tr class='row'>
            <td style='width:50%;'>
                <p>Total</p>
            </td>
            <td style='width:25%;border-left:1px solid black;text-align:right'> <p>RM ".number_format($totalEarning,2)."</p>
            </td>
            <td style='width:25%;border-left:1px solid black;text-align:right'> <p>RM ".number_format($totalDeduction,2)."</p>
            </td>
        </tr>
    </table>
    <table style='border-collapse: collapse;width:100%'>
        <tr class='row' style='border-bottom:1px solid black'>
            <td style='width:50%;'>
                <p>Payment Date: ".$datePayment."</p>
                <p>Bank Name: ".$bankName."</p>
                <p>Bank Account Name: ".$staffName."</p>
                <p>Bank Account: ".$bankAcc."</p>
            </td>
            <td style='width:50%;border-left:1px solid black;padding:0px'>
                <table style='border-collapse: collapse;width:100%'>
                    <tr style='border-bottom:1px solid black'>
                        <td style='background:red;color:white;text-align:center;border-top:1px solid black;border-bottom:1px solid black;'><p>NET PAY</p></td>
                    </tr>
                    <tr style='background:#FF8585;border-bottom:1px solid black'>
                        <td style='text-align:center;border-bottom:1px solid black;'><p>RM ".$netPay."</p></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table style='border-collapse: collapse;width:100%'>
        <tr>
            <td style='width:100%;text-align:center'>
                <p><b><i>This is a computer genetared document</i></b></p>
            </td>
        </tr>
    </table>
    </div>
    ";

        //$payslip = $htmlStart;
        //$payslip.= $headStart;
		$payslip=$style;
		//$payslip.=$headEnd;
		//$payslip.=$bodyStart;
		$payslip.=$slip;
		//$payslip.=$bodyEnd;
		//$payslip.=$htmlEnd;
	    //echo $payslip;
		$html2pdf->writeHTML($payslip);
		
		
		//$html2pdf->output();

		return $html2pdf;
		
}

?>