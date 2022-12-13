<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

if(!isset($_SESSION))
{
	session_name($config['sessionName']);
	session_start();
}


require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/isLogin.php");


		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organizationUser.php");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/leave.php");

function getMyLeaveModuleList(){
	$con=connectDb();
	$userid=$_SESSION['userid'];
	$data=fetchOrganizationUserbyId($con, $userid);
return $data;
}
function getLeaveTypeList(){
	$con=connectDb();
    $data=getLeaveType($con);
    $list="";
	file_put_contents("0leave.log","Leave Type: ".print_r($data,1),FILE_APPEND);
foreach($data as $result)
{
	$list.='<option value="'.htmlentities($result['leave_name']).'">'.htmlentities($result['leave_name']).'</option>';
 }
	echo $list;

}

function getFullName($id){
    $con=connectDb();
    $data=getStaffFullName($con, $id);
    return $data['fullName'];
}
function getStaffIdUser($id){
    $con=connectDb();
    $data=getStaffID($con, $id);
    return $data['fullName'];
}

function getLeaveFullDetail($lid){
    $con=connectDb();
    $data=getLeaveSelectedDetails($con, $lid);
    return $data;
}
function getLeaveOrgInfo($lid){
    $con=connectDb();
    $data=getLeaveOrgDetail($con, $lid);
    return $data;
}

function checkLeave1Approval($id){
    $con=connectDb();
    $data=checkleave1LevelModule($con, $id);
    return $data;
}

function checkLeave2Approval($id){
    $con=connectDb();
    $data=checkleave2LevelModule($con, $id);
    return $data;
}

if(isset($_POST['applyLeave']))
{
	$empId=$_SESSION['userid'];
	$leaveType=$_POST['leave_type'];
	$fromDate=date('d-m-Y', strtotime($_POST['date_from']));
	$toDate=date('d-m-Y', strtotime($_POST['date_to']));
	$description=$_POST['description'];
	$status="PENDING";
	$isRead=0;
	$al_days=$_POST['al_entitle'];
	$half=$_POST['half'];
	$mc=$_POST['mc_entitle'];
	$mc_pending= $_POST['mc'];
    $al_days_pending= $_POST['al_days'];
	$postingDate = date("Y-m-d");
	if(strcasecmp(trim($leaveType),"ANNUAL LEAVE")==0){
	    if($al_days_pending==0) {
	        $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
					<strong>Error!</strong> No ANNUAL Leaves are Pending.
					</div>\n";
            header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/leave/applyLeave.php");
            die("\nafer AL");
        }
    }
	else if(strcasecmp(trim($leaveType),"MEDICAL LEAVE")==0){
	    if($mc_pending==0){
        $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
					<strong>Error!</strong> No MEDICAL Leaves are Pending.
					</div>\n";
            header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/leave/applyLeave.php");
            die("\nafer ML");
	    }

    }


	if($fromDate > $toDate)
	{
        $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
					<strong>Error!</strong> End Date should be greater than Start Date
					</div>\n";
        header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/leave/applyLeave.php");
	}
	$DF = date_create($_POST['date_from']);
	$DT = date_create($_POST['date_to']);

	$diff =  date_diff($DF , $DT );
	if(strcasecmp($half,"NO")!=0){
        $num_days=0.5;
    }else {
        $num_days = (1 + $diff->format("%a"));
    }
	$con=connectDb();
	$result=applyLeave($con,$leaveType,$toDate,$fromDate,$description,$status,$isRead,$empId,$num_days,$postingDate);
	if($result)
	{
        $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
					<strong>Success!</strong> Leave Apply successful.
					</div>\n";
		//echo "<script type='text/javascript'> document.location = 'leave_history.php'; </script>";
	}
	else
	{
        $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
					<strong>Failed!</strong> Failed to Apply Leave.
					</div>\n";
		//echo "<script>alert('Something went wrong. Please try again');</script>";
	}
    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/leave/applyLeave.php");
}



function listLeavesTable($type)
{

    $config = parse_ini_file(__DIR__ . "/../jsheetconfig.ini");
    $table = '<table id="table" class="data-table table stripe hover nowrap">
                 <thead>
                 <tr>
                     <th class="table-plus datatable-nosort">STAFF NAME</th>
                     <th>LEAVE TYPE</th>
                     <th>APPLIED DATE</th>
                     <th>IN-CHARGE</th>
                     <th>MANAGER</th>
                     <th class="datatable-nosort">ACTION</th>
                 </tr>
                 </thead>
                 <tbody>';
    $checkNoTable = 0;
    $table .= "";
    $con = connectDb();
    $userid = $_SESSION['userid'];
    $check1 = checkLeave1Approval($userid);
    $check2 = checkLeave2Approval($userid);
    $dataList = listLeaves($con, $type, $check1, $check2, $userid);
    foreach ($dataList as $row) {
        $table .= '<tr><td class="table-plus">
                         <div class="name-avatar d-flex align-items-center">
                             <div class="txt">
                                 <div class="weight-600">' . getFullName($row['empid']) . '</div>
                             </div>
                         </div>
                     </td>
                     <td>' . $row['LeaveType'] . '</td>
                     <td>' . $row['PostingDate'] . '</td>
                     <td>';
        $stats = $row['Status'];
        if (strcasecmp($stats, 'APPROVED') == 0) {

            $table .= '<span style="color: green">Approved</span>';
        }
        if (strcasecmp($stats, 'REJECTED') == 0) {
            $table .= '<span style="color: red">Rejected</span>';
        }
        if (strcasecmp($stats, 'PENDING') == 0) {
            $table .= '<span style="color: blue">Pending</span>';
        }
        $table .= '</td>
                     <td>';
        $stats = $row['admin_status'];
        if (strcasecmp($stats, 'APPROVED') == 0) {
            $table .= '<span style="color: green">Approved</span>';
        }
        if (strcasecmp($stats, 'REJECTED') == 0) {
            $table .= '<span style="color: red">Rejected</span>';
        }
        if (strcasecmp($stats, 'PENDING') == 0) {
            $table .= '<span style="color: blue">Pending</span>';
        }
        if ($type == -1) {
            $table .= '</td>
                     <td>
                         <div class="dropdown">
                             <a class="font-24 p-0 line-height-1 no-arrow dropdown-toggle bt-custom" href="#" role="button" data-toggle="dropdown" style="">
                                 <i class="dw dw-more"><img  src="https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/resources/2/extra/doticon.png" /></i>
                             </a>
                             <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                             <form action="./leaveDetails.php" method="post">
                                 <input type="hidden" name="leaveId" value="' . $row['lid'] . '" />
                                 <!--<button type="submit" name="viewDetails" class="dropdown-item" href="leave_details.php?leaveid="' . $row['lid'] . '"><i class="fa fa-eye"></i> View</button> -->
                                 <button type="submit" name="viewDetails" class="dropdown-item"><i class="fa fa-eye"></i> View</button>
                             </form>
                             <form action="../../phpfunctions/applyLeave.php" method="post">
                                <input type="hidden" name="leaveId" value="' . $row['lid'] . '" />
                                <button type="submit" name="deleteStaff" class="dropdown-item"><i class="fa fa-trash"></i> Delete</button>
                                <!-- <button type="submit" name="deleteStaff" class="dropdown-item" href="admin_dashboard.php?leaveid="' . $row['lid'] . '"><i class="fa fa-trash"></i> Delete</button> -->
                             </form>
                             </div>
                         </div>
                     </td>';
        } elseif ($type == 1 || $type == 2 || $type = 3) {
            $table .= '</td>
                     <td>
                             <form action="./leaveDetails.php" method="post">
                                 <input type="hidden" name="leaveId" value="' . $row['lid'] . '" />
                                 <!--<button type="submit" name="viewDetails" class="dropdown-item" href="leave_details.php?leaveid="' . $row['lid'] . '"><i class="fa fa-eye"></i> View</button> -->
                                 <button type="submit" name="viewDetails" class="bt-custom"><i class="fa fa-eye"></i></button>
                             </form>
                             
                     </td>';
        }

        $table .= '</tr>';
        $checkNoTable++;
    }
    $table .= '</tbody>
             </table>';
    if ($checkNoTable == 0){
        $table="<h3 class='text-center'>No Records Found</h3>";
                         }
return $table;

}
function listPendingLeavesCountTable()
{

    $config = parse_ini_file(__DIR__ . "/../jsheetconfig.ini");
    $table = '<table id="table" class="data-table table stripe hover nowrap">
                 <thead>
                 <tr>
                     <th class="table-plus datatable-nosort">STAFF NAME</th>
                     <th>STAFF ID</th>
                     <th>PENDING AL</th>
                     <th>PENDING ML</th>';
                     if($_SESSION['ManagerRole'] || $_SESSION['role']==1 || $_SESSION['role']==42){ $table.='<th class="datatable-nosort">ACTION</th>'; }

    $table.='</tr>
                 </thead>
                 <tbody>';
    $checkNoTable = 0;
    $table .= "";
    $con = connectDb();
    $userid = $_SESSION['userid'];
    $userbyid=$userid;
    $dataList = getListLeaveOrgDetail($con,$userid);
    foreach ($dataList as $row) {
        if(isset($row['staffId'])){
        if($_SESSION['ManagerRole']||$_SESSION['role']==1 || $_SESSION['role']==42){
            $userbyid=$row['id'];
        }

        $table .= '<tr><td class="table-plus">
                         <div class="name-avatar d-flex align-items-center">
                             <div class="txt">
                                 <div class="weight-600">' . $row['fullName'] . '</div>
                             </div>
                         </div>
                     </td>
                     <td>' . $row['staffId'] . '</td>
                     <td>' . $row['al_entitle'] . '</td>
                     <td>' . $row['mc_entitle'] . '</td>
                     <td>';
        if($_SESSION['ManagerRole'] || $_SESSION['role']==1 || $_SESSION['role']==42){
                         $table.='<div class="dropdown">
                             <a class="font-24 p-0 line-height-1 no-arrow dropdown-toggle bt-custom" href="#" role="button" data-toggle="dropdown" style="">
                                 <i class="dw dw-more"><img  src="https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/resources/2/extra/doticon.png" /></i>
                             </a>
                             <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                             <form action="./staffLeaveUpdate.php" method="post">
                                 <input type="hidden" name="uid" value="' . $userbyid . '" /> 
                                 <button type="submit" name="viewDetails" class="dropdown-item"><i class="fa fa-pencil"></i> Update Leave</button>
                             </form>
                             </div>
                         </div>
                     </td>';}
        $table .= '</tr>';
        $checkNoTable++;
    }
}

    $table .= '</tbody>
             </table>';
    if ($checkNoTable == 0){
        $table="<h3 class='text-center'>No Records Found</h3>";
                         }
return $table;

}

if(isset($_POST['updateLevel1']))
{
    $did=intval($_POST['lid']);
    $description=$_POST['description'];
    $status=$_POST['status'];
    $al_entitle=$_POST['al_entitle'];
    $mc_entitle=$_POST['mc_entitle'];
    $num_days=$_POST['num_days'];
    // $REMLEAVE = $av_leave - $num_days;

    $reg_remarks = 'Leave was Rejected. Level 2 will not see it';
    $reg_status = 'REJECTED';
    $admremarkdate=date('Y-m-d G:i:s ', strtotime("now"));
    $con=connectDb();
    //$result = mysqli_query($conn,"update `tblleaves`, set `AdminRemark`='$description',`Status`='$status',`AdminRemarkDate`='$admremarkdate', `registra_remarks` = '$reg_remarks', `admin_status` = '$reg_status' where `id`='$did'");
    $result = updateLevel1Leave($con,$description,$status,$admremarkdate,$did);

    if ($result) {
        $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
					<strong>Success!</strong> Leave Updated Successfully.
					</div>\n";

    } else{
        $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
					<strong>Failed!</strong> Failed to update leave.
					</div>\n";
    }
    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/leave/allLeaves.php");
}


if(isset($_POST['updateLevel2']))
{
    $did=intval($_POST['lid']);
    $empId=$_POST['empid'];
    $reg_remarks=$_POST['description'];
    $reg_status=$_POST['status'];
    $al_entitle=$_POST['al_entitle'];
    $mc_entitle=$_POST['mc_entitle'];
    $leaveType=$_POST['leaveType'];
    $num_days=$_POST['num_days'];
    $leaveDays=0;
    if($leaveType==='EMERGENCY LEAVE'){
        $al_entitle=$al_entitle -$num_days;
    }else if($leaveType==='ANNUAL LEAVE'){
        $al_entitle=$al_entitle -$num_days;
    }
    elseif($leaveType==='MEDICAL LEAVE'){
        $mc_entitle=$mc_entitle -$num_days;
    }


    $admremarkdate=date('Y-m-d G:i:s ', strtotime("now"));
    $con=connectDb();
    //$result = mysqli_query($conn,"update `tblleaves`, set `AdminRemark`='$description',`Status`='$status',`AdminRemarkDate`='$admremarkdate', `registra_remarks` = '$reg_remarks', `admin_status` = '$reg_status' where `id`='$did'");
    $result=updateLevel2Leave($con,$admremarkdate,$reg_remarks,$reg_status,$did);
    $res="";
    if ($result) {
        file_put_contents("./0leave.log","\n al: ".$al_entitle.", mc: ".$mc_entitle."id: ".$empId,FILE_APPEND);
        $res=updateLeave($con,$al_entitle,$mc_entitle,$empId);

        if($res){
            $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
					<strong>Success!</strong> Leave Updated Successfully.
					</div>\n";
        }
        else{
            $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
					<strong>Success!</strong> Leave table Updated Successfully.
					</div>\n";
        }
    } else{
        $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
					<strong>Failed!</strong> Failed to update leave.
					</div>\n";
    }
    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/leave/allLeaves.php");
}
if(isset($_POST['updateStaffleave']))
{
    $did=intval($_POST['id']);
    $al_entitle=$_POST['al_entitle'];
    $mc_entitle=$_POST['mc_entitle'];
    $con=connectDb();


        $res=updateLeave($con,$al_entitle,$mc_entitle,$did);

        if($res){
            $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
					<strong>Success!</strong> Leave Updated Successfully.
					</div>\n";
        }
        else{
            $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
					<strong>Success!</strong> Leave Updated Successfully.
					</div>\n";
        }
    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/leave/pendingCountLeaves.php");
}
if(isset($_POST['AddLeaves']))
{
    $al_entitle=0;
    $mc_entitle=0;
    if(isset($_POST['al_entitle'])){
        $al_entitle=$_POST['al_entitle'];
    }
    if(isset($_POST['al_entitle'])) {
        $mc_entitle = $_POST['mc_entitle'];
    }
    $con=connectDb();
    $res=addLeave($con,$al_entitle,$mc_entitle);

    if($res){
        $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
					<strong>Success!</strong> Leave Updated Successfully.
					</div>\n";
    }
    else{
        $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
					<strong>Success!</strong> Leave Updated Successfully.
					</div>\n";
    }
    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/leave/pendingCountLeaves.php");
}
if(isset($_POST['removeLeaves']))
{
    $al_entitle=0;
    $mc_entitle=0;
    if(isset($_POST['al_entitle'])){
        $al_entitle=$_POST['al_entitle'];
    }
    if(isset($_POST['al_entitle'])) {
        $mc_entitle = $_POST['mc_entitle'];
    }
    $con=connectDb();
    $res=removeLeave($con,$al_entitle,$mc_entitle);

    if($res){
        $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
					<strong>Success!</strong> Leave Updated Successfully.
					</div>\n";
    }
    else{
        $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
					<strong>Success!</strong> Leave Updated Successfully.
					</div>\n";
    }
    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/leave/pendingCountLeaves.php");
}




if(isset($_POST['deleteStaff'])){
$lid=$_POST['leaveId'];
$con=connectDb();
$res=deleteLeave($con,$lid);
    if($res){
        $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
					<strong>Success!</strong> Leave Deleted Successfully.
					</div>\n";
    }
    else{
        $_SESSION['feedback']="<div class='alert alert-danger' role='alert'>\n
					<strong>Failed!</strong> Leave Failed to Delete.
					</div>\n";
    }
    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/leave/allLeaves.php");
}

?>