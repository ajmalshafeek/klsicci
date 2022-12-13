<?php

	function updateMailConfig($con,$host,$port,$user,$pass,$smptsecure){
		$success=false;
		$id=1;
		$query="UPDATE mailconfiguration SET `host`=?,`port`=?,`user`=?,`pass`=?,`smtpsecure`=? WHERE `id`=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'sisssi',$host,$port,$user,$pass,$smptsecure,$id);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		return $success;
	}

	function fetchMailDetails($con){
        $id=1;
		$query="SELECT * FROM mailconfiguration WHERE id=?";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'i',$id);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row=$result->fetch_assoc();
		mysqli_stmt_close($stmt);
		return $row;
	}


?>