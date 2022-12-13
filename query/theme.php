<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

function changeTheme($con,$theme,$orgId){
  $success=false;
  $query="UPDATE organization SET theme=? WHERE id=?";
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,'si',$theme,$orgId);
  if(mysqli_stmt_execute($stmt)){
    $success=true;
  }
  return $success;
}
?>
