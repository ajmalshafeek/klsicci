<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
//`project` TABLE
function insertProject($con,$projectName,$startDate,$endDate){
  $feedback = false;

  $query="INSERT INTO project (projectName,startDate,endDate) VALUES (?,?,?)";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'sss',$projectName,$startDate,$endDate);
  if(mysqli_stmt_execute($stmt)){
    $feedback = true;
  }
  mysqli_stmt_close($stmt);

  return $feedback;

}

function fetchProjectListAll($con){
  $query="SELECT * FROM project WHERE 1";
  $stmt=mysqli_prepare($con,$query);
  //mysqli_stmt_bind_param($stmt);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  while($row=$result->fetch_assoc()){
    $dataList[]=$row;

  }
  mysqli_stmt_close($stmt);

  return $dataList;
}

function fetchProjectByProjectId($con,$projectId){
  $query="SELECT * FROM project WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$projectId);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  $row=$result->fetch_assoc();
  mysqli_stmt_close($stmt);

  return $row;
}


function deleteProjectById($con,$projectId){
  $success=false;

  $query="DELETE FROM project WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$projectId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  mysqli_stmt_close($stmt);

  return $success;
}
//`projecttask` TABLE
function insertProjectTask($con,$projectId,$projectTaskName,$projectTaskMandays,$projectTaskStartDate){
  $feedback = false;

  $query="INSERT INTO projecttask (projectId,projectTaskName,mandays,projectTaskStartDate) VALUES (?,?,?,?)";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'isis',$projectId,$projectTaskName,$projectTaskMandays,$projectTaskStartDate);
  if(mysqli_stmt_execute($stmt)){
    $feedback = true;
  }
  mysqli_stmt_close($stmt);

  return $feedback;

}

function fetchProjectTaskByProjectId($con,$projectId){
  $query="SELECT * FROM projecttask WHERE projectId=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$projectId);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  $row=$result->fetch_assoc();
  mysqli_stmt_close($stmt);

  return $row;
}

function fetchProjectTaskListByProjectId($con,$projectId){
  $dataList = array();
  $query="SELECT * FROM projecttask WHERE projectId=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$projectId);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  while($row=$result->fetch_assoc()){
    $dataList[]=$row;
  }

  return $dataList;
}


function deleteProjectTaskById($con,$projectTaskId){
  $success=false;

  $query="DELETE FROM projecttask WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$projectTaskId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  mysqli_stmt_close($stmt);

  return $success;
}
//`projectmembers` TABLE
/*
function fetchProjectTaskMemberListByProjectTaskId($con,$projectTaskId){
  $dataList = array();
  $query="SELECT * FROM projecttaskmembers WHERE projectTaskId=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$projectTaskId);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  while($row=$result->fetch_assoc()){
    $dataList[]=$row;
  }

  return $dataList;
}*/

//`projectgroup` TABLE
function insertProjectGroup($con,$projectId,$isStaff,$staffId,$otherName){
  $feedback = false;

  $query="INSERT INTO projectgroup (projectId,isStaff,staffId,otherName) VALUES (?,?,?,?)";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'iiis',$projectId,$isStaff,$staffId,$otherName);
  if(mysqli_stmt_execute($stmt)){
    $feedback = true;
  }
  mysqli_stmt_close($stmt);

  return $feedback;

}
function checkStaffid($con,$projectId,$staffId){
    $feedback = true;

    $dataList = array();
    $query="SELECT * FROM projectgroup WHERE projectId=? AND staffId=?";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'ii',$projectId,$staffId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $count=0;
    while($row=$result->fetch_assoc()){
        $dataList[]=$row;
        $count++;
    }

if($count!=0){$feedback = false;}
    return $feedback;
}

function fetchProjectGroupListByProjectId($con,$projectId){
  $dataList = array();
  $query="SELECT * FROM projectgroup WHERE projectId=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$projectId);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  while($row=$result->fetch_assoc()){
    $dataList[]=$row;
  }

  return $dataList;
}

function deleteProjectGroupById($con,$projectGroupId){
  $success=false;

  $query="DELETE FROM projectgroup WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$projectGroupId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  mysqli_stmt_close($stmt);

  return $success;
}

function fetchProjectGroupById($con,$projectGroupId){
  $query="SELECT * FROM projectgroup WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$projectGroupId);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  $row=$result->fetch_assoc();
  mysqli_stmt_close($stmt);

  return $row;
}

//`projecttaskmembers` TABLE
function insertProjectTaskMember($con,$projectTaskId,$projectGroupId){
  $feedback = false;

  $query="INSERT INTO projecttaskmembers (projectTaskId,projectGroupId) VALUES (?,?)";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'ii',$projectTaskId,$projectGroupId);
  if(mysqli_stmt_execute($stmt)){
    $feedback = true;
  }
  mysqli_stmt_close($stmt);

  return $feedback;

}

function deleteProjectTaskMemberByProjectGroupId($con,$projectGroupId){
  $success=false;

  $query="DELETE FROM projecttaskmembers WHERE projectGroupId=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$projectGroupId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  mysqli_stmt_close($stmt);

  return $success;
}

function deleteProjectTaskMemberById($con,$projectTaskMemberId){
  $success=false;

  $query="DELETE FROM projecttaskmembers WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$projectTaskMemberId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  mysqli_stmt_close($stmt);

  return $success;
}

function fetchProjectTaskMemberListByProjectTaskId($con,$projectTaskId){
  $dataList = array();
  $query="SELECT * FROM projecttaskmembers WHERE projectTaskId=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$projectTaskId);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  while($row=$result->fetch_assoc()){
    $dataList[]=$row;
  }

  return $dataList;
}

function updateProgress($con,$progress,$projectTaskId){
  $success=false;
  $query="UPDATE projecttask SET progress=? WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'ii',$progress,$projectTaskId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  return $success;
}

function getProgress($con,$projectTaskId){
    $dataList = array();
    $progress=0;
    $query="SELECT * FROM projecttask WHERE id=?";
    $stmt=mysqli_prepare($con,$query);
    mysqli_stmt_bind_param($stmt,'i',$projectTaskId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while($row=$result->fetch_assoc()){
        //$dataList[]=$row;
        $progress=$row['progress'];
    }

    return $progress;
}

function fetchProjectTaskMemberListByProjectGroupId($con,$projectGroupId){
  $dataList = array();
  $query="SELECT * FROM projecttaskmembers WHERE projectGroupId=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'i',$projectGroupId);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  while($row=$result->fetch_assoc()){
    $dataList[]=$row;
  }

  return $dataList;
}
?>
