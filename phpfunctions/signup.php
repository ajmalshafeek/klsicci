<?php
    $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");

    if(isset($_POST["signUp"])){
        $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

        $addSuccess=false;

        /* start of JOB SHEET ADD USER */
        require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientCompany.php");	
      
        $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
        <strong>FAILED!</strong> YOU ALREADY REACH THE LIMIT TO ADD CLIENT \n
        </div>\n
        ";
	
        $con=connectDb();
        $clientName=$_POST['firstName']." ".$_POST['lastName'];
        $email=$_POST['email'];
        $contactNo=$_POST['phoneNumber'];

        $createdDate=date('Y-m-d H:i:s');
        $createdBy="0";
        $orgId=$config['orgId'];
        $regNo="";
        $address1="";
        $address2="";
        $postalCode="";
        $city="";
        $state="";
        $orgFaxNo="";

        $compId=addClientCompany($con,$clientName,$regNo,$email,$address1,$address2,$postalCode,
        $city,$state,$contactNo,$createdDate,$createdBy,$orgId);
        if($compId>0){
            require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientUser.php");	

            $status=1;
            $fullName="";
            $name="";
            $identification="";
            $userName=$_POST['email'];
            $password=$_POST['password'];
            $email="";
            $status=1;
            $role=9999;
            $companyId=$compId;
            $saveSuccess=addClientUser($con,$fullName,$name,$identification,$userName,$password,$email,$status,$role,$companyId,$orgId);
            if($saveSuccess){
                $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
                <strong>SUCCESS!</strong> SUCCESSFULLY ADDED ".$clientName."\n
                <br/><strong>Username : </strong>".$userName."\n
                <br/><strong>Password : </strong>".$password."
                </div>\n";
                $addSuccess=true;
            }
        }

        /* end of JOB SHEET ADD USER */

        /* start of CLEANTO ADD USER */
        if($addSuccess){
            require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/cleanto/objects/class_users.php");
            require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/cleanto/objects/class_connection.php");

            $database= new cleanto_db();
            
            $user=new cleanto_users();
            $user->conn=$database->connect();
            $user->user_pwd=md5($_POST['password']);
            $user->first_name=ucwords($_POST['firstName']);
            $user->last_name=ucwords($_POST['lastName']);
            $user->user_email=$_POST['email'];
            $user->phone=$_POST['phoneNumber'];
            
            $user->address="";
            $user->zip="";
            $user->city="";
            $user->state="";
            
            $user->notes="";
            $user->vc_status="-";
            $user->p_status="-";
            $user->status='E';
            $user->usertype=serialize(array('client'));
            $user->contact_status="";
            $add_user=$user->add_user();
        }
        /* end of CLEANTO ADD USER */
        
        header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/login.php?regSuccess=true");
    }

?>