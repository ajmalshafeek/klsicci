<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
if(!isset($_SESSION))
{
 	session_name($config['sessionName']);
	session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/project.php");

if (isset($_POST['addProject'])) {
  //$description = $_POST['description']; kiv
  $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
  <strong>FAILED!</strong> FAILED TO ADD PROJECT \n
  </div>\n";
  $con = connectDb();
  $projectName = $_POST['projectName'];
  $startDate = $_POST['startDate'];
  $endDate = $_POST['endDate'];
  $feedback = insertProject($con,$projectName,$startDate,$endDate);

  if ($feedback) {
    $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
    <strong>SUCCESS!</strong>PROJECT IS SUCCESSFULLY ADDED \n
    </div>\n";
    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/project/viewProject.php");
  }
  else {
    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/project/createProject.php");
  }
}

function projectList(){
  $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
  $con = connectDb();
  $dataList = fetchProjectListAll($con);
  $i = 1;
  $table="
  <table id='projectTable'>
    <thead>
      <tr>
        <th style='width:10%'>No</th>
        <th style='width:85%'>Project Name</th>
        <th style='width:5%'></th>
      </tr>
    </thead>
    <tbody>
  ";
  foreach ($dataList as $data) {
    $table .= "
      <tr>
        <td data-toggle='modal' data-target='#viewProjectModal' onclick='showProject(".$data['id'].")'>".$i."</td>
        <td data-toggle='modal' data-target='#viewProjectModal' onclick='showProject(".$data['id'].")'>".$data['projectName']."</td>
        <td><i class='fa fa-times' style='color:red' onclick='removeProject(".$data['id'].")'></i></td>
      </tr>
    ";
    $i++;
  }
  $table .= "
    </tbody>
  </table>
  ";
 echo $table;
}

if(isset($_GET['showProject'])){
    $con = connectDb();
    $projectId = $_GET['showProject'];
    $data = fetchProjectByProjectId($con,$projectId);
    echo json_encode($data);
}

if (isset($_GET['projectContent'])) {
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organizationUser.php");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/project.php");
  $con = connectDb();
  $projectId = $_GET['projectContent'];
  $dataList = fetchProjectTaskListByProjectId($con,$projectId);
  $i=1;
  $content="";
  foreach ($dataList as $data) {
    $content.="<div class='form-group row'>";
    $content.="<label for='brand' class='col-sm-9 col-form-label col-form-label-lg'>Task ".$i.": ".$data['projectTaskName']."</label>";
    $content.="<div class='col-sm-3 col-form-label col-form-label-lg' style='text-align:right;color:red;cursor:pointer;'><span onclick='removeProjectTask(".$data['id'].")'>Delete Task</span></div>";
    $content.="</div>";

    $content.="
    <div class='form-group row'>
      <div class='col-sm-12'>
        <input id='progress".$data['id']."' onchange='updateProgress(".$data['id'].")' type='range' min='0' max='100' name='progress' value='".$data['progress']."' style='width:100%'>
        <center><span>Progress</span><span class='progress".$data['id']."'> ".$data['progress']."%</span></center><hr>
      </div>
    </div>
    <div class='form-group row'>
      <label for='mandays' class='col-sm-2 col-form-label col-form-label-lg'>Mandays</label>
      <div class='col-sm-10'   >
        <input class='form-control' type='number' name='mandays' value='".$data['mandays']."' readonly>
      </div>
    </div>
    ";

    $content.="
    <div class='form-group row'>";
    $dataListProjectTaskMember = fetchProjectTaskMemberListByProjectTaskId($con,$data['id']);
    foreach ($dataListProjectTaskMember as $dataProjectTaskMember) {
      $rowProjectGroup = fetchProjectGroupById($con,$dataProjectTaskMember['projectGroupId']);
      if ($rowProjectGroup['isStaff'] == 0) {
        $content.="
        <label class='col-sm-2 col-form-label col-form-label-lg'></label>
        <div class='col-sm-9'><input class='form-control' type='text' name='mandays' value='".$rowProjectGroup['otherName']."' readonly></div>
        <div class='col-sm-1 pt-1 pl-0'><i class='fa fa-times' style='color:red' onclick='removeTaskMember(".$dataProjectTaskMember['id'].")'></i></div>
        ";
      }elseif ($rowProjectGroup['isStaff'] == 1) {
        $rowStaff = getOrganizationUserDetails($con,$rowProjectGroup['staffId']);
        $content.="
        <label class='col-sm-2 col-form-label col-form-label-lg'></label>
        <div class='col-sm-9'><input class='form-control' type='text' name='mandays' value='".$rowStaff['fullName']."' readonly></div>
        <div class='col-sm-1 pt-1 pl-0'><i class='fa fa-times' style='color:red' onclick='removeTaskMember(".$dataProjectTaskMember['id'].")'></i></div>
        ";
      }
    }
    $content.="
      <label for='mandays' class='col-sm-2 col-form-label col-form-label-lg'>Members</label>
      <div class='col-sm-10'>".dropDownListProjectMemberByProjectId($projectId,$data['id'])."</div>
      <label class='col-sm-2 col-form-label col-form-label-lg'></label>
      <div class='col-sm-10'><button id='addProjectTaskMemberButton".$data['id']."' onclick='addProjectTaskMember(".$data['id'].")' type='button' class='form-control btn btn-lg btn-block btn-success fa fa-plus' style='height:37.29px;border-radius:2px;' disabled></button></div>
    </div><hr>
    ";
    $i++;
  }
  echo $content;
}

if (isset($_GET['addProjectTask'])) {
  $con = connectDb();
  $projectId = $_GET['addProjectTask'];
  $projectTaskName = $_GET['projectTaskName'];
  $projectTaskMandays = $_GET['projectTaskMandays'];
  $projectTaskStartDate = $_GET['projectTaskStartDate'];
  $feedback = insertProjectTask($con,$projectId,$projectTaskName,$projectTaskMandays,$projectTaskStartDate);
  if ($feedback) {
    echo "Insert `projectTask` succesfull";
  }else {
    echo "Insert `projectTask` fail";
  }
}

if (isset($_GET['addTaskMember'])) {
  $con = connectDb();
  $projectTaskId = $_GET['projectTaskId'];
  $projectGroupId = $_GET['projectGroupId'];
  $feedback = insertProjectTaskMember($con,$projectTaskId,$projectGroupId);
  if ($feedback) {
    echo "Insert `projectMember` succesfull";
  }else {
    echo "Insert `projectMember` fail";
  }
}

if (isset($_GET['addProjectGroup'])) {
  $con = connectDb();
  $projectId = $_GET['projectId'];
  $isStaff = $_GET['isStaff'];
  $staffId = $_GET['staffId'];
  $otherName = $_GET['otherNameUpdated'];

  //(START)Javascript error fix[null == 0 || 0 == null]
  if ($staffId == 0) {
    $staffId = null;
  }
  if ($otherName == "0") {
    $otherName = null;
  }
  //(END)Javascript error fix[null == 0 || 0 == null]
if(checkStaffid($con,$projectId,$staffId)){
  $feedback = insertProjectGroup($con,$projectId,$isStaff,$staffId,$otherName);

  if ($feedback) {
    echo "Add Project Group Success";
  }else {
    echo "Add Project Group Failed";
  }}
    else{
        echo "Add Project Group Already Exist";
    }
}

function dropDownListProjectMemberByProjectId($projectId,$projectTaskId){
  $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
  $con=connectDb();
  $dataList=fetchProjectGroupListByProjectId($con,$projectId);

  $list =  "<select onchange='checkAddProjectTaskMemberButton(".$projectTaskId.")' class='form-control' id='projectGroupId".$projectTaskId."' >";
  $list .=  "<option value='null' selected disabled >--Select--</option>\n";

  foreach ($dataList as $data){
    $dataList = fetchProjectTaskMemberListByProjectGroupId($con,$data['id']);
    if (count($dataList) > 0) {
      continue;
    }
    if ($data['isStaff'] == 0) {
      $name = $data['otherName'];
    }elseif($data['isStaff'] == 1) {
      $dataStaff = getOrganizationUserDetails($con,$data['staffId']);
      $name = $dataStaff['fullName'];
    }
    $list .=  "<option value=".$data['id']." >".$name."</option>";
  }

  $list .=  "</select>";

  return $list;
}

if (isset($_GET['groupMemberList'])) {
  $con = connectDb();
  $projectId = $_GET['groupMemberList'];
  $list = "
  <div class='form-group row'>
    <table>
      <tr>
        <th style='width:10%'>No.</th>
        <th style='width:85%'>Group Member</th>
        <th style='width:5%'></th>
      </tr>
  ";

  $dataList = fetchProjectGroupListByProjectId($con,$projectId);
  $i = 1;
  foreach ($dataList as $data) {
    if ($data['isStaff']==0) {
      $name = $data['otherName'];
    }elseif ($data['isStaff']==1) {
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organizationUser.php");
      $dataStaff = getOrganizationUserDetails($con,$data['staffId']);
      $name = $dataStaff['fullName'];
    }
    $list .= "
    <tr>
      <td>".$i."</td>
      <td>".$name."</td>
      <td><i class='fa fa-times' style='color:red' onclick='removeGroupMember(".$data['id'].")'></i></td>
    </tr>
    ";
    $i++;
  }

  $list .= "
    </table>
  </div>
  ";

  echo $list;
}

if (isset($_GET['removeProjectGroup'])) {
  $con = connectDb();
  $projectGroupId = $_GET['removeProjectGroup'];
  $feedback = deleteProjectGroupById($con,$projectGroupId);
  if ($feedback) {
    echo "Remove Project Group Success";
  }else {
    echo "Remove Project Group Failed";
  }
}

if (isset($_GET['removeProjectTaskMemberByProjectGroupId'])) {
  $con = connectDb();
  $projectGroupId = $_GET['removeProjectTaskMemberByProjectGroupId'];
  $feedback = deleteProjectTaskMemberByProjectGroupId($con,$projectGroupId);
  if ($feedback) {
    echo "Remove Project Task Member Success";
  }else {
    echo "Remove Project Task Member Failed";
  }
}

if (isset($_GET['removeProjectTaskMemberById'])) {
  $con = connectDb();
  $projectTaskMemberId = $_GET['removeProjectTaskMemberById'];
  $feedback = deleteProjectTaskMemberById($con,$projectTaskMemberId);
  if ($feedback) {
    echo "Remove Project Task Member Success";
  }else {
    echo "Remove Project Task Member Failed";
  }
}

if (isset($_GET['removeProjectTask'])) {
  $con = connectDb();
  $projectTaskId = $_GET['removeProjectTask'];
  $feedback = deleteProjectTaskById($con,$projectTaskId);
  if ($feedback) {
    echo "Remove Project Task Success";
  }else {
    echo "Remove Project Task Failed";
  }
}

function dropDownListProjectAll(){
  $con=connectDb();
  $dataList=fetchProjectListAll($con);

  echo "<select onchange='processProject()' name='projectId' class='form-control' id='projectId' >";
  echo "<option value='null' selected disabled >--Select--</option>\n";

  foreach ($dataList as $data){
    echo "<option value=".$data['id']." >".$data['projectName']."</option>";
  }

  echo	"</select>";
}

if (isset($_GET['projectChartDataList'])) {
  $con = connectDb();
  $projectId = $_GET['projectChartDataList'];
  $row = fetchProjectByProjectId($con,$projectId);
  $dataList = fetchProjectTaskListByProjectId($con,$projectId);

  $totalMandays = totalMandaysByProjectId($projectId);
  $totalProgress = totalProgressByProjectId($projectId);

  $projectName = $row['projectName'];
  $parent = "parent".$row['id'];

  $object = '[';
  $object .= '{"name":"'.$projectName.'", "id":"'.$parent.'","dependency":null,"parent":null,"startDate":"'.$row['startDate'].'","endDate":"'.projectEndDate($projectId).'","addStartDate":0,"mandays":'.$totalMandays.',"completed":'.round($totalProgress*100,2).', "owner": null}';
  $mandaysSum = 0;
  $dependency = "null";
  foreach ($dataList as $data) {
    $dataListMembers = fetchProjectTaskMemberListByProjectTaskId($con,$data['id']);
    $endDate = date('Y-m-d', strtotime($data['projectTaskStartDate']. ' + '.$data['mandays'].' days'));
    //(START)GET OWNERS/MEMBERS
    $members = "";
    foreach ($dataListMembers as $dataMembers) {
      $rowGroup = fetchProjectGroupById($con,$dataMembers['projectGroupId']);
      if ($rowGroup['isStaff']==0) {
        $members .= $rowGroup['otherName'];
      }elseif($rowGroup['isStaff']==1) {
        require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organizationUser.php");
        $dataStaff = getOrganizationUserDetails($con,$rowGroup['staffId']);
        $members .= $dataStaff['fullName'];
      }
      $members .= " | ";
    }
    //(END)GET OWNERS/MEMBERS
    $object .= ',';
    $object .= '{"name":"'.$data['projectTaskName'].'", "id":"task'.$data['id'].'","dependency":"'.$dependency.'","parent":"'.$parent.'","startDate":"'.$data['projectTaskStartDate'].'","endDate":"'.$endDate.'","addStartDate":'.$mandaysSum.',"mandays":'.$data['mandays'].', "completed":'.$data['progress'].', "owner": "'.$members.'"}';
    $mandaysSum = $data['mandays'] + $mandaysSum;
    $dependency = "task".$data['id'];
  }

  $object .= ']';

  echo $object;
}

if (isset($_GET['projectStartDate'])) {
  $con = connectDb();
  $projectId = $_GET['projectStartDate'];
  $row = fetchProjectByProjectId($con,$projectId);
  echo $row['startDate'];
}

function totalMandaysByProjectId($projectId){
  $con = connectDb();
  $dataList = fetchProjectTaskListByProjectId($con,$projectId);
  $totalMandays = 0;
  foreach ($dataList as $data) {
    $totalMandays = $totalMandays + $data['mandays'];
  }
  return $totalMandays;
}

function projectEndDate($projectId){
  $con = connectDb();
  $dataList = fetchProjectTaskListByProjectId($con,$projectId);
  $dateEnd = null;
  foreach ($dataList as $data) {
    $dateCheck = date('Y-m-d', strtotime($data['projectTaskStartDate']. ' + '.$data['mandays'].' days'));
    if ($dateEnd == null) {
      $dateEnd = $dateCheck;
    }else {
      if ($dateCheck > $dateEnd) {
        $dateEnd = $dateCheck;
      }
    }
  }
  return $dateEnd;
}

function totalProgressByProjectId($projectId){
  $con = connectDb();
  $dataList = fetchProjectTaskListByProjectId($con,$projectId);
  $totalProgress = 0;
  $dividedBy = 0;
  foreach ($dataList as $data) {
    $totalProgress = $totalProgress + $data['progress'];
    $dividedBy = $dividedBy + 100;
  }
  return $totalProgress/$dividedBy;
}

if (isset($_GET['updateProgress'])) {
  $con = connectDb();
  $projectTaskId = $_GET['updateProgress'];
  $progress = $_GET['progress'];
    $current=getProgress($con,$projectTaskId);
    if($current<100) {
        $feedback = updateProgress($con, $progress, $projectTaskId);
        if ($feedback) {
            echo "Progress Updated Successfully";
        } else {
            echo "Fail to Update Progress";
        }
    }
    else{
        echo "Progress Already 100% Completed";
    }
}

if (isset($_GET['removeProject'])) {
  $con = connectDb();
  $projectId = $_GET['removeProject'];
  $feedback = deleteProjectById($con,$projectId);
  if ($feedback) {
    $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
    <strong>SUCCESS!</strong>PROJECT IS REMOVED SUCCESSFULLY \n
    </div>\n";
  }else {
    $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
    <strong>FAILED!</strong> FAILED TO REMOVE PROJECT \n
    </div>\n";
  }
}
?>
