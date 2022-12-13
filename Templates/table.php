<!doctype html>
<html>
<head>
<meta charset="utf-8">
<!-- TemplateBeginEditable name="doctitle" -->
<title>Untitled Document</title>
<!-- TemplateEndEditable -->
<!-- TemplateBeginEditable name="head" -->
<!-- TemplateEndEditable -->
</head>

<body>
    <table style='border-collapse: collapse;width:100%'>
        <tr class='row'>
            <td class='col' style='width:20%;'><img style='width:20%;' src='https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/resources/".$_SESSION['orgLogo'].".png' ></td>
            <td class='col' style='width:50%;'><h3 style='margin-bottom:2px'>SALARY SLIP</h3><h7>".$monthText." ".date("Y")."</h7></td>
            <td class='col' style='width:30%;border-left:1px solid black;'><h2>CONFIDENTIAL</h2></td>
        </tr>
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
	$slip.="<p>Salary Month: RM".$salaryMonth."</p>
	</td>
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
                <p>EIS</p>
				".$ndisplay."
            </td>
            <td style='width:25%;border-left:1px solid black;text-align:right'> <p>".$salaryMonth."</p>
                <p>-</p>
                <p>-</p>
                <p>-</p>
                <p>-</p>
				".$adisplay."
            </td>
            <td style='width:13%;border-left:1px solid black;text-align:center;'> <p style='color:white;background:red;width:100%;border-left:1px solid black;text-align:center'>Employer</p>
                <p>".$epf."</p>
                <p>-</p>
                <p>-</p>
                <p>-</p>
				".$edisplay."
				
            </td>
            <td style='width:12%;border-left:1px solid black;text-align:right'><p style='color:white;background:red;width:100%;border-left:1px solid black;text-align:center'>Employee</p>
                <p>-</p>
                <p>".$socso."</p>
                <p>".$pcb."</p>
                <p>".$eis."</p>
				".$edisplay."
				
            </td>
        </tr>
    </table>
    <table style='border-collapse: collapse;width:100%'>
        <tr class='row'>
            <td style='width:50%;'>
                <p>Total</p>
            </td>
            <td style='width:25%;border-left:1px solid black;text-align:right'> <p>".$totalEarning."</p>
            </td>
            <td style='width:25%;border-left:1px solid black;text-align:right'> <p>".$totalDeduction."</p>
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
                        <td style='text-align:center;border-bottom:1px solid black;'><p>".$netPay."</p></td>
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
</body>
</html>
