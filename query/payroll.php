<?php
function fetchPayrollRowAll($con){
  $dataList=array();
  $query="SELECT * FROM payroll WHERE 1";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  while($row=$result->fetch_assoc()){
    $dataList[]=$row;

  }
  mysqli_stmt_close($stmt);

  return $dataList;
}


function fetchPayrollRowByStaffId($con,$staffId){
  $query="SELECT * FROM payroll WHERE staffId=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$staffId);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  $row=$result->fetch_assoc();
  mysqli_stmt_close($stmt);

  return $row;
}


function insertPayroll($con,$staffId,$basic,$epf,$socso,$socsoEmp,$pcb,$bankName,$bankAcc,$nasionality,$taxId,$epfEmp,$epfEmpyr,$eis,$epfEmpPer,$socsoEmpPer,$socsoEmployerPer){
  $feedback = false;
  $query="INSERT INTO payroll (staffId,basic,epf,socso,socsoEmp,pcb,bankName,bankAcc,nasionality,taxId,epfEmp,epfEmpyr,eis,epfEmpPer,socsoEmpPer,socsoPer) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'issdddssisdddddd',$staffId,$basic,$epf,$socso,$socsoEmp,$pcb,$bankName,$bankAcc,$nasionality,$taxId,$epfEmp,$epfEmpyr,$eis,$epfEmpPer,$socsoEmpPer,$socsoEmployerPer);
  if(mysqli_stmt_execute($stmt)){
    $feedback = true;
  }
  mysqli_stmt_close($stmt);
  return $feedback;
}

function updatePayroll($con,$staffId,$basic,$epf,$socso,$socsoEmp,$pcb,$bankName,$bankAcc,$nasionality,$taxId,$epfEmp,$epfEmpyr,$eis,$epfEmpPer,$socsoEmpPer,$socsoEmployerPer){
  $success=false;
  $query="UPDATE payroll SET basic=?,epf=?,socso=?,socsoEmp=?,pcb=?,bankName=?,bankAcc=?,nasionality=?,taxId=?,epfEmp=?,epfEmpyr=?,eis=?,epfEmpPer=?,socsoEmpPer=?,socsoPer=? WHERE staffId=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'ssdddssisddddddi',$basic,$epf,$socso,$socsoEmp,$pcb,$bankName,$bankAcc,$nasionality,$taxId,$epfEmp,$epfEmpyr,$eis,$epfEmpPer,$socsoEmpPer,$socsoEmployerPer,$staffId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  return $success;
}

function insertPayslipInformation($con,$monthText,$staffName,$staffId,$employeeId,$designation,$department,$salaryMonth,$epf,$epfPerc,$socso,$pcb,$allowance,$claims,$commissions,$ot,$bonus,$totalEarning,$totalDeduction,$datePayment,$bankName,$bankAcc,$netPay,$payslip,$paymentVoucher){
  $feedback = false;

  $query="INSERT INTO payslip (
              monthText,
              staffName,
              staffId,
              employeeId,
              designation,
              department,
              salaryMonth,
              epf,
              epfPerc,
              socso,
              pcb,
			  allowance,
			  claims,
			  commissions,
			  ot,
			  bonus,
              totalEarning,
              totalDeduction,
              datePayment,
              bankName,
              bankAcc,
              netPay,
              payslip,
              paymentVoucher
  )
  VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'ssssssssisssssssssssssss',$monthText,$staffName,$staffId,$employeeId,$designation,$department,$salaryMonth,$epf,$epfPerc,$socso,$pcb,$allowance,$claims,$commissions,$ot,$bonus,$totalEarning,$totalDeduction,$datePayment,$bankName,$bankAcc,$netPay,$payslip,$paymentVoucher);
  if(mysqli_stmt_execute($stmt)){
    $feedback = true;
  }
  mysqli_stmt_close($stmt);

  return $feedback;
}
	function insertPayslipCheck($con,$staffId,$monthText,$datePayment){
		$dataList=array();
		$query="SELECT COUNT(*) AS found FROM payslip WHERE staffid=? AND monthText=? AND datePayment=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'iss',$staffId,$monthText,$datePayment);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row=$result->fetch_assoc()){
		$dataList=$row;
		}
		mysqli_stmt_close($stmt);

		return $dataList;
	}

function fetchPayslipRowAll($con){
  $dataList=array();
  $query="";
  if($_SESSION["role"]==1 || $_SESSION["role"] == 42 || $_SESSION['ManagerRole'] ){
  $query="SELECT * FROM payslip WHERE 1";
}
   else{
       $query="SELECT * FROM payslip WHERE 1 AND staffid='".$_SESSION["userid"]."'";
   }
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  while($row=$result->fetch_assoc()){
    $dataList[]=$row;

  }
  mysqli_stmt_close($stmt);

  return $dataList;
}

function fetchPayslipRowById($con,$payslipId){
  //$query="SELECT * FROM payslip WHERE id=?";
	$query="SELECT t1.*, p2.epfEmp, p2.epfEmpyr, p2.eis, p2.epf AS epfRate FROM payslip t1 JOIN payroll p2 WHERE  p2.staffId=t1.staffId AND t1.id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$payslipId);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  $row=$result->fetch_assoc();
  mysqli_stmt_close($stmt);

  return $row;
}
function fetchVoucherRowById($con,$payslipId){
  //$query="SELECT * FROM payslip WHERE id=?";
  $query="SELECT * FROM payslip WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$payslipId);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  $row=$result->fetch_assoc();
  mysqli_stmt_close($stmt);

  return $row;
}


function fetchPayrollListByDate($con,$dateYear,$dateMonth){
  $dataList = array();
  $query="SELECT * FROM payslip WHERE  YEAR(datePayment) = ? AND MONTH(datePayment) = ?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'ss',$dateYear,$dateMonth);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  while($row=$result->fetch_assoc()){
    $dataList[]=$row;

  }

  return $dataList;
}

function fetchPayrollListByYear($con,$dateYear){
  $dataList = array();
  $query="SELECT * FROM payslip WHERE  YEAR(datePayment) = ?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'s',$dateYear);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  while($row=$result->fetch_assoc()){
    $dataList[]=$row;

  }

  return $dataList;
}

//`payslip` TABLE

function deletePayslipByPayslipId($con,$payslipId){
  $success=false;

  $query="DELETE FROM payslip WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$payslipId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  mysqli_stmt_close($stmt);

  return $success;
}

function updatePayslip($con,$monthText,$designation,$department,$salaryMonth,$epf,$epfPerc,$socso,$pcb,$allowance,$claims,$commissions,$ot,$bonus,$totalEarning,$totalDeduction,$datePayment,$bankName,$bankAcc,$netPay,$payslip,$payslipId){
  $success=false;
  $query="UPDATE payslip SET monthText=?,designation=?,department=?,salaryMonth=?,epf=?,epfPerc=?,socso=?,pcb=?,allowance=?,claims=?,commissions=?,ot=?,bonus=?,totalEarning=?,totalDeduction=?,datePayment=?,bankName=?,bankAcc=?,netPay=?,payslip=? WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'sssssissssssssssssssi',$monthText,$designation,$department,$salaryMonth,$epf,$epfPerc,$socso,$pcb,$allowance,$claims,$commissions,$ot,$bonus,$totalEarning,$totalDeduction,$datePayment,$bankName,$bankAcc,$netPay,$payslip,$payslipId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  return $success;
}
?>