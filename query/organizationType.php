<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");


function fetchOrgTypeList($con){
  $dataList = array();
  $query="SELECT * FROM organizationtype WHERE 1=1";
  $stmt=mysqli_prepare($con,$query);
  //mysqli_stmt_bind_param($stmt,'ss',$dateYear,$dateMonth);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  while($row=$result->fetch_assoc()){
    $dataList[]=$row;

  }

  return $dataList;
}

	function fetchOrgUse($con){
		$dataList = array();
		$query="SELECT * FROM organizationuse WHERE 1=1";
		$stmt=mysqli_prepare($con,$query);
		//mysqli_stmt_bind_param($stmt,'ss',$dateYear,$dateMonth);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);

		while($row=$result->fetch_assoc()){
			$dataList[]=$row;

		}

		return $dataList;
	}
?>
