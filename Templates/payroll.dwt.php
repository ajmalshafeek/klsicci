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
    <?php $payroll='<div class="row pt-3 pr-3 pl-3">
    <div class="p-3" style="border:1px solid black;width:70%">
        <img style="width:20%;float:left" src="https://'.$_SERVER['HTTP_HOST'].$config['appRoot']."/resources/".$_SESSION['orgLogo'].'.png" >
        <center><h3><b>SALARY SLIP</b></h3></center>'.$address.'<center><h7><b>'.$monthText.' '.date("Y").'</b></h7></center>
    </div>          	

    <div class="p-3" style="background:#FF8585;border:1px solid black;width:30%;border-left:0px;">
        <center><h2><b>CONFIDENTIAL</b></h2></center>
    </div> 
</div>
    
<div class="row pb-0 pr-3 pl-3">
    <div class="p-3" style="border:1px solid black;border-top:0px;width:50%">
        <p style="margin-bottom:2px" >Name: '.$staffName.'</p>
        <p style="margin-bottom:2px" >Employee ID: '.$employeeId.'</p>
        <p style="margin-bottom:2px">Tax ID: '.$taxId.'</p>
        <p style="margin-bottom:2px">Nationality: '.$nasionality.'</p>
    </div>

    <div class="p-3" style="border:1px solid black;border-top:0px;width:50%;border-left:0px;">
        <p>Designation: '.$designation.'</p>
        <p>Department: '.$department.'</p>
        <p>Salary Month: RM '.$salaryMonth.'</p>
    </div>
          
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
        <p>EIS</p>
        '.$ndisplay.'
        </div>
        <div class="p-1" style="border:1px solid black;border-top:0px;width:25%;border-left:0px;">
            <p style="text-align:right;">'.$salaryMonth.'</p>'.$adisplay.'
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
        </div>

        <!--	//allowance
                //$slip.=$adisplay; -->
        </div>
    </div>
    <div class="row pb-0 pr-3 pl-3">
        <div class="p-1" style="border:1px solid black;border-top:0px;width:50%">
            <p>Total</p>
        </div>
        <div class="p-1" style="border:1px solid black;border-top:0px;width:25%;border-left:0px;">
            <p style="text-align:right;">'.$totalEarning.'</p>
        </div>
        <div class="p-1" style="border:1px solid black;border-top:0px;width:25%;border-left:0px;">
            <p style="text-align:right;">'.$totalDeduction.'</p>
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

            <div style="background:#FF8585;border-bottom:1px solid black;"><center><h5>'.$netPay.'</h5></center></div>

            <div class="pt-5" style="height:100%"><center><h4></h4></center></div>
        </div>
    </div>
    
    <div class="pb-0 pr-3 pl-3">
        <center><p><i><b>This is a computer genetared document</b></i></p></center>
    </div>';
    
