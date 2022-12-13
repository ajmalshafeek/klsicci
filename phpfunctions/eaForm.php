<?php

$config = parse_ini_file(__DIR__ . "/../jsheetconfig.ini");



if (!isset($_SESSION)) {

    session_name($config['sessionName']);

    session_start();

}



require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/isLogin.php";

require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/connect.php";

require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/clientComplaint.php";
require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/eaform.php";



	if(isset($_POST['updateEAForm'])){
		$staffName="";
		if(isset($_POST['staffName'])){
			$staffName=$_POST['staffName'];}
		$position="";
		if(isset($_POST['position'])){
			$position=$_POST['position'];}
		$staffNum="";
		if(isset($_POST['staffNum'])){
			$staffNum=$_POST['staffNum'];}
		$icNum="";
		if(isset($_POST['icNum'])){
			$icNum=$_POST['icNum'];}
        $passNum="";
        if(isset($_POST['passNum'])){
            $passNum=$_POST['passNum'];}
		$perkesoNum="";

		if(isset($_POST['perkesoNum'])){
			$perkesoNum=$_POST['perkesoNum'];}

		$kwspNum="";
		if(isset($_POST['kwspNum'])){
			$kwspNum=$_POST['kwspNum'];}

		$childrenNum="";
		if(isset($_POST['childrenNum'])){
			$childrenNum=$_POST['childrenNum'];}

		$startDate="";
		if(isset($_POST['startDate'])){
			$startDate=$_POST['startDate'];
		}
		$endDate="";
		if(isset($_POST['endDate'])){
			$endDate=$_POST['endDate'];
		}
		$grossSalary="";
		if(isset($_POST['grossSalary'])){
			$grossSalary=$_POST['grossSalary'];}

		$fiBonus="";
		if(isset($_POST['fiBonus'])){
			$fiBonus=$_POST['fiBonus'];}
		$payFor="";
		if(isset($_POST['payFor'])){
			$payFor=$_POST['payFor'];}

		$payAmount="";
		if(isset($_POST['payAmount'])){
			$payAmount=$_POST['payAmount'];
		}

		$taxByEmployer="";
		if(isset($_POST['taxByEmployer'])){
			$taxByEmployer=$_POST['taxByEmployer'];
		}

		$esos="";
		if(isset($_POST['esos'])){
			$esos=$_POST['esos'];
		}

		$rewardFrom="";
		if(isset($_POST['rewardFrom'])){
			$rewardFrom=$_POST['rewardFrom'];
		}

		$rewardTo="";
		if(isset($_POST['rewardTo'])){
			$rewardTo=$_POST['rewardTo'];
		}

		$previousYear="";
		if(isset($_POST['previousYear'])){
			$previousYear=$_POST['previousYear'];
		}
		$currentYear="";
		if(isset($_POST['currentYear'])){
			$currentYear=$_POST['currentYear'];
		}
		$benefitsState="";
		if(isset($_POST['benefitsState'])){
			$benefitsState=$_POST['benefitsState'];
		}
		$benefitsAmount="";
		if(isset($_POST['benefitsAmount'])){
			$benefitsAmount=$_POST['benefitsAmount'];
		}
		$resiAdd="";
		if(isset($_POST['resiAdd'])){
			$resiAdd=$_POST['resiAdd'];
		}
		$resiValue="";
		if(isset($_POST['resiValue'])){
			$resiValue=$_POST['resiValue'];
		}
		$refundKWSP="";
		if(isset($_POST['refundKWSP'])){
			$refundKWSP=$_POST['refundKWSP'];
		}
		$reparationJob="";
		if(isset($_POST['reparationJob'])){
			$reparationJob=$_POST['reparationJob'];
		}
		$pensionAmount="";
		if(isset($_POST['pensionAmount'])){
			$pensionAmount=$_POST['pensionAmount'];
		}
		$annuitAmount="";
		if(isset($_POST['annuitAmount'])){
			$annuitAmount=$_POST['annuitAmount'];
		}
		$totalPension="";
		if(isset($_POST['totalPension'])){
			$totalPension=$_POST['totalPension'];
		}
		$pcbAmount="";
		if(isset($_POST['pcbAmount'])){
			$pcbAmount=$_POST['pcbAmount'];
		}
		$deductionCP="";
		if(isset($_POST['deductionCP'])){
			$deductionCP=$_POST['deductionCP'];
		}
		$zakatAmount="";
		if(isset($_POST['zakatAmount'])){
			$zakatAmount=$_POST['zakatAmount'];
		}
		$deductionTP1a="";
		if(isset($_POST['deductionTP1a'])){
			$deductionTP1a=$_POST['deductionTP1a'];
		}
		$deductionTP1b="";
		if(isset($_POST['deductionTP1b'])){
			$deductionTP1b=$_POST['deductionTP1b'];
		}
		$jumlahPele="";
		if(isset($_POST['jumlahPele'])){
			$jumlahPele=$_POST['jumlahPele'];
		}
		$fundName="";
		if(isset($_POST['fundName'])){
			$fundName=$_POST['fundName'];
		}
		$contribution="";
		if(isset($_POST['contribution'])){
			$contribution=$_POST['contribution'];
		}
		$perkesoPaidAmount="";
		if(isset($_POST['perkesoPaidAmount'])){
			$perkesoPaidAmount=$_POST['perkesoPaidAmount'];
		}

		$totalAllowances="";
		if(isset($_POST['totalAllowances'])){
			$totalAllowances=$_POST['totalAllowances'];
		}
		$officerName="";
		if(isset($_POST['officerName'])){
			$officerName=$_POST['officerName'];
		}

		$officerPosition="";
		if(isset($_POST['officerPosition'])) {
			$officerPosition = $_POST['officerPosition'];
		}
		$employerNameAdd="";
		if(isset($_POST['employerNameAdd'])){
			$employerNameAdd=$_POST['employerNameAdd'];
		}
		$employerNum="";
		if(isset($_POST['employerNum'])){
			$employerNum=$_POST['employerNum'];
		}

		$date="";
		if(isset($_POST['date'])){
			$date=$_POST['date'];
		}
		$year="";
		if(isset($_POST['year'])){
			$year=$_POST['year'];
		}

		$status=$_POST['updateEAForm'];
		$con=connectDb();
		$result="";
		$editToId=0;
		$editToId=$_POST['eaFormIdEdit'];
		$result = updateEAForm($con,
			$staffName,
			$position,
			$staffNum,
			$icNum,
			$passNum,
			$perkesoNum,
			$kwspNum,
			$childrenNum,
			$startDate,
			$endDate,
			$grossSalary,
			$fiBonus,
			$payFor,
			$payAmount,
			$taxByEmployer,
			$esos,
			$rewardFrom,
			$rewardTo,
			$previousYear,
			$currentYear,
			$benefitsState,
			$benefitsAmount,
			$resiAdd,
			$resiValue,
			$refundKWSP,
			$reparationJob,
			$pensionAmount,
			$annuitAmount,
			$totalPension,
			$pcbAmount,
			$deductionCP,
			$zakatAmount,
			$deductionTP1a,
			$deductionTP1b,
			$jumlahPele,
			$fundName,
			$contribution,
			$perkesoPaidAmount,
			$totalAllowances,
			$officerName,
			$officerPosition,
			$employerNameAdd,
			$employerNum,
			$year,
			$date,
			$status,
			$editToId);

		$message="";
		if($result){
			$message="<div class='alert alert-success' role='alert'>\n
					<strong>SUCCESS!</strong> EA FORM SUCCESSFULLY ";if($status==0){$message.="SUBMIT";}else{$message.="SAVE IN DRAFT";}
			"\n</div>\n";
		}
		else{
			$message="<div class='alert alert-danger' role='alert'>\n
					<strong>FAILED!</strong> EA FORM NOT ";if($status==0){$message.="SUBMIT";}else{$message.="SAVE IN DRAFT";}
			"\n</div>\n";
		}
		$_SESSION['feedback']=$message;
		header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/payroll/vieweaform.php");
	}
	if (isset($_POST['SaveEAForm'])) {
		$staffName="";
		if(isset($_POST['staffName'])){
		$staffName=$_POST['staffName'];}
		$position="";
		if(isset($_POST['position'])){
		$position=$_POST['position'];}
		$staffNum="";
		if(isset($_POST['staffNum'])){
		$staffNum=$_POST['staffNum'];}
		$icNum="";
		if(isset($_POST['icNum'])){
		$icNum=$_POST['icNum'];}
        $passNum="";
        if(isset($_POST['passNum'])){
            $passNum=$_POST['passNum'];}
		$perkesoNum="";
		if(isset($_POST['perkesoNum'])){
		$perkesoNum=$_POST['perkesoNum'];}

		$kwspNum="";
		if(isset($_POST['kwspNum'])){
		$kwspNum=$_POST['kwspNum'];}

		$childrenNum="";
		if(isset($_POST['childrenNum'])){
		$childrenNum=$_POST['childrenNum'];}

		$startDate="";
		if(isset($_POST['startDate'])){
		$startDate=$_POST['startDate'];
		}
		$endDate="";
		if(isset($_POST['endDate'])){
			$endDate=$_POST['endDate'];
		}
		$grossSalary="";
		if(isset($_POST['grossSalary'])){
		$grossSalary=$_POST['grossSalary'];}

		$fiBonus="";
		if(isset($_POST['fiBonus'])){
			$fiBonus=$_POST['fiBonus'];}
		$payFor="";
		if(isset($_POST['payFor'])){
			$payFor=$_POST['payFor'];}

		$payAmount="";
		if(isset($_POST['payAmount'])){
			$payAmount=$_POST['payAmount'];
		}

		$taxByEmployer="";
		if(isset($_POST['taxByEmployer'])){
			$taxByEmployer=$_POST['taxByEmployer'];
		}

		$esos="";
		if(isset($_POST['esos'])){
			$esos=$_POST['esos'];
		}

		$rewardFrom="";
		if(isset($_POST['rewardFrom'])){
			$rewardFrom=$_POST['rewardFrom'];
		}

		$rewardTo="";
		if(isset($_POST['rewardTo'])){
			$rewardTo=$_POST['rewardTo'];
		}

		$previousYear="";
		if(isset($_POST['previousYear'])){
			$previousYear=$_POST['previousYear'];
		}
		$currentYear="";
		if(isset($_POST['currentYear'])){
			$currentYear=$_POST['currentYear'];
		}
		$benefitsState="";
		if(isset($_POST['benefitsState'])){
			$benefitsState=$_POST['benefitsState'];
		}
		$benefitsAmount="";
		if(isset($_POST['benefitsAmount'])){
			$benefitsAmount=$_POST['benefitsAmount'];
		}
		$resiAdd="";
		if(isset($_POST['resiAdd'])){
			$resiAdd=$_POST['resiAdd'];
		}
		$resiValue="";
		if(isset($_POST['resiValue'])){
			$resiValue=$_POST['resiValue'];
		}
		$refundKWSP="";
		if(isset($_POST['refundKWSP'])){
			$refundKWSP=$_POST['refundKWSP'];
		}
		$reparationJob="";
		if(isset($_POST['reparationJob'])){
			$reparationJob=$_POST['reparationJob'];
		}
		$pensionAmount="";
		if(isset($_POST['pensionAmount'])){
			$pensionAmount=$_POST['pensionAmount'];
		}
		$annuitAmount="";
		if(isset($_POST['annuitAmount'])){
			$annuitAmount=$_POST['annuitAmount'];
		}
		$totalPension="";
		if(isset($_POST['totalPension'])){
			$totalPension=$_POST['totalPension'];
		}
		$pcbAmount="";
		if(isset($_POST['pcbAmount'])){
			$pcbAmount=$_POST['pcbAmount'];
		}
		$deductionCP="";
		if(isset($_POST['deductionCP'])){
			$deductionCP=$_POST['deductionCP'];
		}
		$zakatAmount="";
		if(isset($_POST['zakatAmount'])){
			$zakatAmount=$_POST['zakatAmount'];
		}
		$deductionTP1a="";
		if(isset($_POST['deductionTP1a'])){
			$deductionTP1a=$_POST['deductionTP1a'];
		}
		$deductionTP1b="";
		if(isset($_POST['deductionTP1b'])){
			$deductionTP1b=$_POST['deductionTP1b'];
		}
		$jumlahPele="";
		if(isset($_POST['jumlahPele'])){
			$jumlahPele=$_POST['jumlahPele'];
		}
		$fundName="";
		if(isset($_POST['fundName'])){
			$fundName=$_POST['fundName'];
		}
		$contribution="";
		if(isset($_POST['contribution'])){
			$contribution=$_POST['contribution'];
		}
		$perkesoPaidAmount="";
		if(isset($_POST['perkesoPaidAmount'])){
			$perkesoPaidAmount=$_POST['perkesoPaidAmount'];
		}

		$totalAllowances="";
		if(isset($_POST['totalAllowances'])){
			$totalAllowances=$_POST['totalAllowances'];
		}
		$officerName="";
		if(isset($_POST['officerName'])){
			$officerName=$_POST['officerName'];
		}

		$officerPosition="";
		if(isset($_POST['officerPosition'])) {
			$officerPosition = $_POST['officerPosition'];
		}
		$employerNameAdd="";
		if(isset($_POST['employerNameAdd'])){
			$employerNameAdd=$_POST['employerNameAdd'];
		}
		$employerNum="";
		if(isset($_POST['employerNum'])){
			$employerNum=$_POST['employerNum'];
		}

		$date="";
		if(isset($_POST['date'])){
			$date=$_POST['date'];
		}
		$year="";
		if(isset($_POST['year'])){
			$year=$_POST['year'];
		}
		$status=$_POST['SaveEAForm'];
        $con=connectDb();
        if(checkEAForm($con,$staffName,$staffNum,$year)){

		$result = addEAForm($con,
				$staffName,
				$position,
				$staffNum,
				$icNum,
				$passNum,
				$perkesoNum,
				$kwspNum,
				$childrenNum,
				$startDate,
				$endDate,
				$grossSalary,
				$fiBonus,
				$payFor,
				$payAmount,
				$taxByEmployer,
				$esos,
				$rewardFrom,
				$rewardTo,
				$previousYear,
				$currentYear,
				$benefitsState,
				$benefitsAmount,
				$resiAdd,
				$resiValue,
				$refundKWSP,
				$reparationJob,
				$pensionAmount,
				$annuitAmount,
				$totalPension,
				$pcbAmount,
				$deductionCP,
				$zakatAmount,
				$deductionTP1a,
				$deductionTP1b,
				$jumlahPele,
				$fundName,
				$contribution,
				$perkesoPaidAmount,
				$totalAllowances,
				$officerName,
				$officerPosition,
				$employerNameAdd,
				$employerNum,
				$year,
				$date,
				$status);

		$message="";
		if($result){
			$message="<div class='alert alert-success' role='alert'>\n
					<strong>SUCCESS!</strong> EA FORM SUCCESSFULLY ";if($status==0){$message.="SUBMIT";}else{$message.="SAVE IN DRAFT";}
					"\n</div>\n";
		}
		else{
			$message="<div class='alert alert-danger' role='alert'>\n
					<strong>FAILED!</strong> EA FORM NOT ";if($status==0){$message.="SUBMIT";}else{$message.="SAVE IN DRAFT";}
			"\n</div>\n";
		}
        }else{
            $message="<div class='alert alert-danger' role='alert'>\n
					<strong>FAILED!</strong> ".$staffName." EA FORM FOR year ".$year." ALREADY  EXIST";
            "\n</div>\n";
        }
		$_SESSION['feedback']=$message;

				header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/payroll/eaform.php");
	}



if (isset($_GET['completeexcel'])) {

    $con = connectDb();

    $details = fetchComplaintCompleteForExcel($con);

    echo $details;

}

if (isset($_GET['uncompleteexcel'])) {

        $con = connectDb();

    $details = fetchComplaintUncompleteForExcel($con);

   echo $details;

}
	if(isset($_GET['getOfficerDetailWithId'])){

		$con=connectDb();
		$staffId= $_GET['getOfficerDetailWithId'];

		 $data=fetchEaOfficerbyId($con,$staffId);
	     echo json_encode($data);

	}




	function eaTable(){
		$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/eaform.php");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organizationUser.php");


		$con=connectDb();
		$i=1;
		$dataList = fetchEAFormAll($con);

		$table = "
    <table class='table' id='payslipListTable' width='100%' cellspacing='0' role='grid' style='width: 100%;'>

		<thead class='thead-dark'>
			<tr>
      			<th scope='col' style='width:5%' >No</th>
				<th scope='col'>Staff ID</th>
				<th scope='col'>Staff Name</th>
			  <th scope='col'>Year</th>
			  <th scope='col'>Status</th>
			  </tr>
		</thead>
		<tbody>
    ";
		$dataList = array_reverse($dataList, true);
		foreach($dataList as $data){
				$table.="
      <tr>
       		<td  scope='col' style='width:5%' data-value='".$data['id']."'>".$i."</td>
				<td  scope='col' data-value='".$data['id']."'>".$data['staffNum']."</td>
				<td  scope='col' data-value='".$data['id']."'>".$data['staffName']."</td>
				<td  scope='col' data-value='".$data['id']."'>".$data['year']."</td>
			  <td  scope='col' data-value='".$data['id']."'>";
			if($data['status']==0){ $table.='Submitted';}else{
				$table.="<div class='dropdown'>";
				$table.="<button type='button' class='btn  dropdown-toggle' data-toggle='dropdown'>
							Draft
							</button>
							<div class='dropdown-menu'>";

				$table.="<button type='button' data-toggle='modal' data-target='#staffEditModal' class='dropdown-item' onclick='eaEdit(this)' value='$data[id]' style='cursor:pointer'>ACTIONS</button>";


				$table.="	</div>
							</div>";
			}
			$table.="</td>
			</tr>
    ";
			$i++;
		}
		$table.="
        </tbody>
    </table>
    ";
		return $table;
	}

	function dropDownListEaOfficerList(){
		$con=connectDb();
		$staff=getOfficerList($con);
		foreach ($staff as $data){
			echo '<option value="'.$data['name'].'" data-id="'.$data['id'].'" >'.$data['name'].'</option>';
		}
	}


	function fetchEaFormDetails($id){
		$con=connectDb();
		$dataList=fetchEAForm($con,$id);
		return $dataList;
	}
	function dropdownlistEaSearchStaff(){
		$con=connectDb();
		$dataList=fetchEAStaffList($con);

		return $dataList;

	}

	if (isset($_POST['eaReportGenerate'])) {

		$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/eaform.php");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organizationUser.php");

		if(isset($_POST['staffNum'])){
		$staffNum=$_POST['staffNum'];}
		if(isset($_POST['year'])){
		$year=$_POST['year'];}

		$con=connectDb();
		$i=1;
		$dataList = getEAFormAllSubmitted($con,$staffNum,$year);
if(sizeof($dataList)>0) {
	$table = "
    <table class='table' id='payslipListTable' width='100%' cellspacing='0' role='grid' style='width: 100%;'>

		<thead class='thead-dark'>
			<tr>
        		<th scope='col' style='width:5%' >No</th>
        		<th scope='col'>Staff Number</th>
				<th scope='col'>Staff Name</th>
			  <th scope='col'>Year</th>
			  <th scope='col'></th>
			  </tr>
		</thead>
		<tbody>
    ";
	$dataList = array_reverse($dataList, TRUE);
	foreach ($dataList as $data) {
		$table .= "
      <tr>

				<td  scope='col' style='width:5%' data-value='" . $data['id'] . "'>" . $i . "</td>
				<td  scope='col' data-value='" . $data['id'] . "'>" . $data['staffNum'] . "</td>
				<td  scope='col' data-value='" . $data['id'] . "'>" . $data['staffName'] . "</td>
				<td  scope='col' data-value='" . $data['id'] . "'>" . $data['year'] . "</td>
			  <td  scope='col' data-value='" . $data['id'] . "'>";

			$table .= "<div class='dropdown'>";
			$table .= "<button type='button' class='btn  dropdown-toggle' data-toggle='dropdown'>
							Option
							</button>
							<div class='dropdown-menu'>";

			$table .= "<button type='button' data-toggle='modal' data-target='#staffEditModal' class='dropdown-item' onclick='eaEdit(this)' value='$data[id]' style='cursor:pointer'>ACTIONS</button>";


			$table .= "	</div>
							</div>";

		$table .= "</td>
			</tr>
    ";
		$i++;
	}
	$table .= "
        </tbody>
    </table>
    ";
}else{
	$table="<div class='mt-4'><center><h3>No Record found</h3></center></div>>";
}
		//$_SESSION['easearch']=$datesearch;

		$_SESSION['eaTable'] = $table;

		$_SESSION['eaTableExport'] = $table;

		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/payroll/eareport.php");

	}


	if(isset($_POST['editOfficerDetails'])){
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/session.php");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
		$id=$_POST['officerIdToEdit'];
		$con = connectDb();
		$sql = "SELECT * FROM `eaofficer` WHERE `id` = '$id'";
		$result = mysqli_query($con, $sql);
		$row = mysqli_fetch_array($result);

		$_SESSION['officerIdEdit'] = $row['id'];
		$_SESSION['officerName'] = $row['name'];
		$_SESSION['officerPosition'] = $row['position'];
		$_SESSION['officerAdd'] = $row['employerAdd'];
		$_SESSION['officerTel'] = $row['emloyerTel'];

		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/payroll/editOfficerDetails.php");

	}

	if(isset($_POST['removeOfficerDetails'])){
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/session.php");
		$_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
			<strong>FAILED!</strong>FAILED TO DELETE OFFICER \n
			</div>\n
			";
		$con=connectDb();
		$id=$_POST['officerIdToEdit'];
		$saveSuccess=deleteOfficerDetails($con,$id);
		if($saveSuccess){
			$_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
			<strong>SUCCESS!</strong> OFFICER SUCCESSFULLY REMOVED \n
			</div>\n";

		}
		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/payroll/viewOfficerDetails.php");

	}




	if(isset($_POST['editOfficerProcess'])){
		$lastId = $_SESSION['officerIdEdit'];
		$currentId =$_POST['Id'];
		$name=$_POST['name'];
		$position=$_POST['position'];
		$address=$_POST['address'];
		$tel=$_POST['tel'];


		$sql = 'UPDATE `eaofficer` SET `name`="'.$name.'",`position`="'.$position.'",`employerAdd`="'.$address.'",`emloyerTel`="'.$tel.'"  WHERE `id`='.$lastId;
		$result = mysqli_query(connectDb(),$sql);
		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/payroll/viewOfficerDetails.php");
		$_SESSION['feedback'] = "<div class='alert alert-success' role='alert'>\n
    <strong>SUCCESS!</strong> OFFICER INFORMATIONS SUCCESSFULLY UPDATED</div>";
	}

	if(isset($_POST['addOfficerDetails'])){

		$con=connectDb();
		$name=$_POST['name'];
		$position=$_POST['position'];
		$address=$_POST['address'];
		$tel=$_POST['tel'];
		$saveSuccess=addOfficerDetails($con,$name,$position,$address,$tel);
		if($saveSuccess){
			$_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
				<strong>SUCCESS!</strong> <strong>".$name."</strong> OFFICER SUCCESSFULLY ADDED</div>";
			// End of CLEANTO ADD STAFF
		}else{
			$_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
				<strong>FAILED!</strong> FAILED TO ADD OFFICER\n
				</div>\n";
		}

		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/payroll/addOfficerDetails.php");
	}


	function eaOfficerDetailsListTableEditable(){
		$con=connectDb();

		$table="<div class='table-responsive table-stripped table-bordered'>\n";
		$table.="<table id='dataTable' class='table' width='100%' cellspacing='0'>\n";
		$table.="<thead class='thead-dark'>\n";
		$table.="<tr>\n";
		$table.=	"<th>\n";
		$table.=		"Id\n";
		$table.=	"</th>\n";
		$table.=	"<th>\n";
		$table.=		"name\n";
		$table.= 	"</th>\n";
		$table.=	"<th>\n";
		$table.=		"Action\n";
		$table.= 	"</th>\n";
		$table.= "</tr>\n";
		$table.= "</thead >\n";
		$i=1;
		$orgId=$_SESSION['orgId'];
		$status=1;
		$role=null;
		$dataList=fetchOfficerDetailsList($con);
		$table.="<tbody>";
		foreach($dataList as $data){
			$table.= "<tr ";
			if($i%2==0)
				$table.= "style='background-color:#FFF5EB;'";
			else{
				$table.= "style='background-color:#F9F9F9;'";
			}$table.= ">";

			$table.=	"<td style='font-weight:bold'>";
			$table.=	$data['id'];
			$table.=	"</td>";
			$table.=	"<td>";
			$table.=		$data['name'];
			$table.=	"</td>";
			$table.="<td>";
			$table.="<div class='dropdown'>";
			$table.="<button type='button' class='btn  dropdown-toggle' data-toggle='dropdown'>
							OPTION
							</button>
							<div class='dropdown-menu'>";

			$table.="<button type='button' data-toggle='modal' data-target='#designationEditModal' class='dropdown-item' onclick='eaOfficerEdit(this)' value='$data[id]' style='cursor:pointer'>ACTIONS</button>";
			$table.="	</div>
							</div>";
			$table.="</td>";
			$table.= "</tr>";
		}

		$table.="</tbody>";
		$table.= "</table>";
		$table.= "</div>";
		echo $table;
	}

	if(isset($_POST['pdfDownload'])){
		 $id=$_POST['eaIdToEdit'];
		 $data=printEaReportTable($id);
		 $_SESSION['eaReport']=$data;
		 header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/payroll/report/pdf.php");
	}
	if(isset($_POST['printView'])){
		$id= $_POST['eaIdToEdit'];
		$data=printEaReportTable($id);
		 $_SESSION['eaReport']=$data;
		 header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/payroll/report/print.php");
	}

	function printEaReportTable($id){
		$con=connectDb();
		$data=fetchEAForm($con,$id);



		$table='<table width="900" border="0" class="fontsize" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td colspan="4">&nbsp;(C.P.8A - Pin. 2017)</td>
      <td class="td1">&nbsp;</td>
      <td class="td2">&nbsp;</td>
      <td colspan="6" align="center">MALAYSIA</td>
      <td colspan="3" style="padding: 3px" class="dark">Penyata Gaji Pekerja SWASTA</td>
      <td width="59" rowspan="2" class="fontsize20">&nbsp;EA</td>
    </tr>
    <tr>
      <td width="23" class="small">&nbsp;</td>
        <td width="23" class="small">&nbsp;</td>
        <td width="23" class="small">&nbsp;</td>
        <td class="td1">&nbsp;</td>
        <td class="td1">&nbsp;</td>
      <td>&nbsp;</td>
        <td colspan="6" align="center" style="font-size: 10pt"><strong>CUKAI PENDAPATAN</strong></td>
      <td colspan="3" align="center">No. Cukai Pendapatan Pekerja</td>
    </tr>
    <tr>
      <td colspan="3">No. Siri</td>
      <td>&nbsp;</td>
      <td colspan="2" class="insert"></td>
      <td colspan="6" align="center">PENYATA SARAAN DARIPADA PENGGAJIAN</td>
      <td colspan="4" class="insert"></td>
    </tr>
    <tr>
      <td colspan="4">No. Majikan E</td>
      <td colspan="2" class="insert"></td>
      <td colspan="5" align="center">BAGI TAHUN BERAKHIR 31 DISEMBER</td>
        <td class="insert">'.$data['year'].'</td>
      <td colspan="2">Cawangan LHDNM</td>
      <td colspan="2" class="insert"></td>
    </tr>
    <tr>
      <td colspan="16" class="dark" style="font-size: 10pt"><strong>BORANG EA INI PERLU DISEDIAKAN UNTUK DISERAHKAN KEPADA PEKERJA BAGI TUJUAN CUKAI PENDAPATAN</strong></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td class="td3">&nbsp;</td>
      <td class="td4">&nbsp;</td>
      <td width="23"  class="small">&nbsp;</td>
      <td width="23"  class="small">&nbsp;</td>
      <td width="23" class="small">&nbsp;</td>
      <td width="112">&nbsp;</td>
      <td width="48">&nbsp;</td>
      <td width="69">&nbsp;</td>
      <td width="107">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td class="dark"><strong>A</strong></td>
        <td colspan="5"><strong>BUTIRAN PEKERJA</strong></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>1.</td>
      <td colspan="4">Nama Penuh Pekerja/Pesara (En./Cik/Puan)
</td>
      <td colspan="10" class="insert">'.$data['staffName'].'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td colspan="4">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="3">&nbsp;</td>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>2.</td>
      <td colspan="2">Jawatan</td>
      <td colspan="4" class="insert">'.$data['position'].'</td>
       <td>&nbsp;</td>
        <td>3.</td>
      <td colspan="3">No. Kakitangan/No. Gaji</td>
      <td colspan="3" class="insert">'.$data['staffNum'].'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>4.</td>
      <td colspan="2">No. K.P. Baru </td>
      <td colspan="3" class="insert">'.$data['icNum'].'</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>5.
</td>
      <td colspan="3">No. Pasport</td>
      <td colspan="3" class="insert">'.$data['passNum'].'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>6.</td>
      <td colspan="2">No. KWSP</td>
      <td colspan="3" class="insert">'.$data['kwspNum'].'</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>7.</td>
      <td colspan="2">No. PERKESO</td>
      <td>&nbsp;</td>
      <td colspan="3" class="insert">'.$data['perkesoNum'].'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>8.</td>
      <td colspan="6">Bilangan Anak Yang Layak</td>
      <td>&nbsp;</td>
      <td>9.</td>
      <td colspan="6">Jika bekerja tidak genap setahun, nyatakan:</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="3">Untuk Pelepasan Cukai</td>
      <td colspan="2" class="insert">'.$data['childrenNum'].'</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>(a)</td>
      <td colspan="2">Tarikh mula bekerja</td>
      <td colspan="3" class="insert">'.checkData($data['startDate']).'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>(b)</td>
      <td colspan="2">Tarikh berhenti kerja</td>
      <td colspan="3" class="insert">'.checkData($data['endDate']).'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
        <td rowspan="2" class="dark"><strong>B</strong></td>
      <td colspan="12"><strong>PENDAPATAN PENGGAJIAN, MANFAAT DAN TEMPAT KEDIAMAN</strong></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="12"><strong>(Tidak Termasuk Elaun/Perkuisit/Pemberian/Manfaat Yang Dikecualikan Cukai)</strong></td>
        <td>&nbsp;</td>
        <td align="center"><strong>RM</strong></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="16">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>1.</td>
      <td>(a)</td>
      <td colspan="5">Gaji kasar, upah atau gaji cuti (termasuk gaji lebih masa)</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2" class="insert">'.checkData($data['grossSalary']).'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>(b)</td>
      <td colspan="5">Fi (termasuk fi pengarah), komisen atau bonus </td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2" class="insert">'.checkData($data['fiBonus']).'</td>

    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>(c)</td>
      <td colspan="8">Tip kasar, perkuisit, penerimaan sagu hati atau elaun-elaun lain (Perihal pembayaran:</td>
      <td colspan="2" class="insert">'.$data['payFor'].'</td>
      <td>)</td>
      <td colspan="2" class="insert">'.checkData($data['payAmount']).'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>(d)</td>
      <td colspan="5">Cukai Pendapatan yang dibayar oleh Majikan bagi pihak Pekerja</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2" class="insert">'.checkData($data['taxByEmployer']).'</td>
    </tr>
    <tr>
      <td height="29">&nbsp;</td>
      <td>&nbsp;</td>
      <td>(e)</td>
      <td colspan="5">Manfaat Skim Opsyen Saham Pekerja (ESOS)</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2" class="insert">'.checkData($data['esos']).'</td>
    </tr>

    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>(f)</td>
      <td colspan="2">Ganjaran bagi tempoh dari</td>
      <td colspan="2"  class="insert">'.$data['rewardFrom'].'</td>
      <td>&nbsp;</td>
      <td colspan="3">hingga</td>
      <td colspan="2"  class="insert">'.$data['rewardTo'].'</td>
      <td>&nbsp;</td>
      <td colspan="2" class="insert">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>2.</td>
      <td colspan="12">Butiran bayaran tunggakan dan lain-lain bagi tahun-tahun terdahulu dalam tahun semasa</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="3">Jenis pendapatan </td>
      <td align="right">(a)</td>
      <td colspan="6" class="insert">'.$data['previousYear'].'</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align="right">(b)</td>
      <td colspan="6" class="insert">'.$data['currentYear'].'</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2" class="insert">&nbsp;</td>
    </tr>
      <tr>
      <td colspan="16"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>3.</td>
      <td colspan="4">Manfaat berupa barangan (Nyatakan:</td>
      <td colspan="7" class="insert">'.$data['benefitsState'].'</td>
      <td>)</td>
      <td colspan="2" class="insert">'.checkData($data['benefitsAmount']).'</td>
    </tr>
      <tr>
      <td colspan="16"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>4.</td>
      <td colspan="3">Nilai tempat kediaman (Alamat:</td>
        <td colspan="8" class="insert" >'.$data['resiAdd'].'</td>
      <td>)</td>
      <td colspan="2" class="insert">'.checkData($data['resiValue']).'</td>
    </tr>
       <tr>
      <td colspan="16"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>5.</td>
      <td colspan="9">Bayaran balik daripada Kumpulan Wang Simpanan/Pencen yang tidak diluluskan</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2" class="insert">'.checkData($data['refundKWSP']).'</td>
    </tr>
      <tr>
      <td colspan="16"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>6.</td>
      <td colspan="4">Pampasan kerana kehilangan pekerjaan</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2" class="insert">'.checkData($data['reparationJob']).'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
        <td class="dark"><strong>C</strong></td>
      <td colspan="13"><strong>PENCEN DAN LAIN-LAIN</strong></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>1.</td>
      <td colspan="2">Pencen</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2" class="insert">'.checkData($data['pensionAmount']).'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>2.</td>
      <td colspan="4">Anuiti atau Bayaran Berkala yang lain</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2" class="insert">'.checkData($data['annuitAmount']).'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
        <td colspan="3"><strong>JUMLAH</strong></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2" class="insert">'.checkData($data['totalPension']).'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
        <td class="dark"><strong>D</strong></td>
      <td colspan="5"><strong>JUMLAH POTONGAN</strong></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>1.</td>
      <td colspan="5">Potongan Cukai Bulanan (PCB) yang dibayar kepada LHDNM</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2" class="insert">'.checkData($data['pcbAmount']).'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>2.</td>
      <td colspan="5">Arahan Potongan CP 38</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2" class="insert">'.checkData($data['deductionCP']).'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>3.</td>
      <td colspan="5">Zakat yang dibayar melalui potongan gaji</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2" class="insert">'.checkData($data['zakatAmount']).'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>4.</td>
      <td colspan="6">Jumlah tuntutan potongan oleh pekerja melalui Borang TP1 berkaitan:</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>(a)</td>
      <td colspan="5">Pelepasan</td>
      <td>RM</td>
      <td colspan="4" class="insert">'.checkData($data['deductionTP1a']).'</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>(b)</td>
      <td colspan="5">Zakat selain yang dibayar melalui potongan gaji bulanan</td>
      <td>RM</td>
      <td colspan="4" class="insert">'.checkData($data['deductionTP1b']).'</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="16"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>5.</td>
      <td colspan="4">Jumlah pelepasan bagi anak yang layak</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2" class="insert">'.checkData($data['jumlahPele']).'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
        <td class="dark"><strong>E</strong></td>
      <td colspan="15"><strong>CARUMAN YANG DIBAYAR OLEH PEKERJA KEPADA KUMPULAN WANG SIMPANAN/PENCEN YANG DILULUSKAN DAN PERKESO</strong></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>1.</td>
      <td colspan="3">Nama Kumpulan Wang</td>
      <td colspan="11" class="insert">'.$data['fundName'].'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="9">Amaun caruman yang wajib dibayar (nyatakan bahagian pekerja sahaja) </td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>RM</td>
      <td colspan="2" class="insert">'.checkData($data['contribution']).'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>2.</td>
      <td colspan="9">PERKESO : Amaun caruman yang wajib dibayar (nyatakan bahagian pekerja sahaja)</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>RM</td>
      <td colspan="2" class="insert">'.checkData($data['perkesoPaidAmount']).'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
        <td class="dark"><strong>F</strong></td>
      <td colspan="11"><strong>JUMLAH ELAUN / PERKUISIT / PEMBERIAN / MANFAAT YANG DIKECUALIKAN CUKAI</strong></td>
      <td>&nbsp;</td>
      <td>RM</td>
      <td colspan="2" class="insert">'.checkData($data['totalAllowances']).'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="11" rowspan="12" style="border: solid 1px black">
          <table width="100%" border="0" cellpadding="" cellspacing="2" class="fontsize">
        <tbody>
          <tr>
            <td width="5%"></td>
            <td width="30%" ></td>
            <td width="55%"></td>
            <td width="5%"></td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td>Nama Pegawai</td>

            <td class="insert">'.$data['officerName'].'</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td colspan="5"></td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td>Jawatan</td>

            <td class="insert">'.$data['officerPosition'].'</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td colspan="5"></td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td valign="top">Nama dan Alamat Majikan</td>

            <td class="insert" align="left" valign="top" height="60px">'.$data['employerNameAdd'].'</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td colspan="5"></td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td>No. Telefon Majikan</td>

            <td class="insert">'.$data['employerNum'].'</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>

            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          </tbody>
  </table>
</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
      <td colspan="2" >&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2">Tarikh</td>
      <td colspan="2" class="insert">'.$data['date'].'</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
  </tbody>
</table>';
return $table;
	}

	function checkData($data){
		if($data==0){
			return "";
		}else{
			return $data;}
	}