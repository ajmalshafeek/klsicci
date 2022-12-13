<?php

    function fetchEmailSuggestion($con,$key,$orgId){
        $dataList=array();
        $key=$key."%";
        $query="SELECT mailAddress FROM invoicemaillist UNION  
        SELECT mailAddress FROM quotationmaillist 
        WHERE mailAddress like ? AND orgId=? ";
        $stmt=mysqli_prepare($con,$query);
        mysqli_stmt_bind_param($stmt,'si',$key,$orgId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while($row=$result->fetch_assoc()){
            $dataList[]=$row;
        }
        mysqli_stmt_close($stmt);

        return $dataList;
    }


?>