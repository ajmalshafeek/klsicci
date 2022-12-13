<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/genFunc.php");	

	

	function fetchJobListItem($con,$jobListId,$orgId){
		$dataList=array();
		$query="SELECT * FROM joblistitem jli WHERE 1=1 ";

		$paramType="";
		$paramList = array();

		if($jobListId!=null){
			$query.="AND jli.joblistId=? ";
			$paramList[] = $jobListId;
		}
	
		if($orgId!=null){
			$query.="AND jli.orgId=? ";
			$paramList[] = $orgId;

		}

		$stmt=mysqli_prepare($con,$query);
		DynamicBindVariables($stmt, $paramList);


		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row=$result->fetch_assoc()){
			$dataList[]=$row;
		}
		mysqli_stmt_close($stmt);

		return $dataList;
	}


?>