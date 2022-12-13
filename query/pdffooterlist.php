<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/genFunc.php");


    function fetchPdfFooterList($con,$footerId,$orgId){
        $dataList=array();

        $query="SELECT * FROM pdffooterlist WHERE 1=1 ";
        $paramType="";
        $paramList = array();

        if($footerId!=null){
            $query.=" AND id=? ";
            $paramList[] = $footerId;
        }

        if($orgId!=null){
            $query.="AND orgId=? ";
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

    function updatePdfFooterList($con,$footerId,$content){
		$success=false;
		$query="UPDATE pdffooterlist SET content=? WHERE id=? ";
		$stmt=mysqli_prepare($con,$query);
		mysqli_stmt_bind_param($stmt,'si',$content,$footerId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		return $success;
	}

    function createPdfFooter($con,$name,$content,$orgId){
        $success=false;

		$query="INSERT INTO pdffooterlist (name,content,orgId) VALUES (?,?,?) ";
        $stmt=mysqli_prepare($con,$query);

		mysqli_stmt_bind_param($stmt,'ssi',$name,$content,$orgId);
		if(mysqli_stmt_execute($stmt)){
			$success=true;
		}
		mysqli_stmt_close($stmt);

		return $success;
    }

    function removeFooter($con, $id){
      $success=false;
      $query="DELETE FROM pdffooterlist WHERE id=? ";
      $stmt=mysqli_prepare($con,$query);
      mysqli_stmt_bind_param($stmt,'i',$id);
  		if(mysqli_stmt_execute($stmt)){
        $success=true;
      }else{
        die('execute() failed: ' . htmlspecialchars($stmt->error));
      }
      return $success;
    }

?>
