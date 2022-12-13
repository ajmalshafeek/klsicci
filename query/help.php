<?php

	function updateHelp($con,$website,$email,$call,$manual){
		$success=false;
		$id=1;
		$query="UPDATE help SET `website`=?,`email`=?,`call`=?,`manual`=? WHERE `id`=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'ssisi',$website,$email,$call,$manual,$id);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		return $success;
	}

	function fetchHelpDetails($con){
        $id=1;
		$query="SELECT * FROM help WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$id);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row=$result->fetch_assoc();
		mysqli_stmt_close($stmt);
		return $row;
	}


?>