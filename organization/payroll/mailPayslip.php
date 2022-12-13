<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
  session_name($config['sessionName']);
  session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organizationUser.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/payroll.php");

?>
<!DOCTYPE html >

<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />

    <?php
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>
    <style>
    .mailForm{
        width:100%;
        border-bottom:0px !important;
        background:#FAFFBD !important;
        padding:0px !important;
        outline:none;
    }
    
    .adjust{
        padding:0px !important;
    }
    
    .cardHeaderAdj{
        background: white !important;
        border:0px !important;
        padding-top: 3px !important;
        padding-left: 5px !important;
        padding-right: 5px !important;
    }
    
    .cardBodyAdj{
        background: white !important;
        border:0px !important;
        padding-top: 15px !important;
    }
    .add{
        background:#FAFFBD !important;
        padding:8px !important;
        display: flex !important;
        flex-direction: row !important;
    }
    #cancel{
        color:blue;
    }
    #cancel:hover{
        color:red;
    }
    </style>
    <script>
    </script>
    <style>
    </style>

</head>
<body class="fixed-nav ">

<?php
  include $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/navMenu.php";
?>

  <div class="content-wrapper">
    <div class="container-fluid">
        <?php echo shortcut() ?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb col-md-12">
        <li class="breadcrumb-item">
          <a href="../../home.php">Dashboard</a>
        </li>
        <li class="breadcrumb-item ">Payroll</li>
        <li class="breadcrumb-item active">Mail Payslip</li>
      </ol>
    </div>
<div class="container"  >

<?php
  if (isset($_SESSION['feedback'])) {
    echo $_SESSION['feedback'];
    unset($_SESSION['feedback']);
  }
    ?>
     <div class="col-12">
     <h1  style="text-align:center; font-family:brush script mt;">Mail This Payslip</h1>        
    </div>

<form action="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot']."/phpfunctions/mail.php"; ?>" 
    method="POST" class="needs-validation" novalidate>

        <div class="form-group row">
            <label for="payslipMailAddress" class="col-sm-1 col-form-label col-form-label-lg">TO</label>
            <div class="card adjust col-md-10">
                <div class="card-header cardHeaderAdj adjust">
                   <div class="card add" id="emailAddress"><a href="#"><i id="cancel" class="fa fa-times" aria-hidden="true"></i></a><span>syahid@jsoftsolution.com.my</span></div>
                </div>
                <div class="card-body cardBodyAdj adjust" >
                    <input type='text' id='payslipMailAddress' name='payslipMailAddress'  value='' class='mailForm' style="padding:10px;" required>    
                        <div class="invalid-feedback">
                        Please enter email address to send
                        </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="payslipMailAddressCC" class="col-sm-1 col-form-label col-form-label-lg">CC</label>
            <div class="card adjust col-md-10">
                <div class="card-header cardHeaderAdj adjust">
                   <div class="card add" id="ccAddress"><a href="#"><i id="cancel" class="fa fa-times" aria-hidden="true"></i></a><span>syahid@jsoftsolution.com.my</span></div>
                </div>
                <div class="card-body cardBodyAdj adjust" >
                    <input type='text' id='payslipMailAddress' name='payslipMailAddress'  value='' class='mailForm' style="padding:10px;" required>    
                        <div class="invalid-feedback">
                        Please enter email address to send
                        </div>
                </div>
            </div>
        </div>
        
        <div class="form-group row">
            <label for="messageBody" class="col-sm-1 col-form-label col-form-label-lg">
            SUBJECT
            </label>
            <div class="col-sm-10"   >
            <input type='text'  class='form-control' name="subject" value='payslip-#<?php echo $_SESSION['payslipNumber']; ?>' id="subject">


            </div>  
        </div>
        <div class="form-group row">
            <label for="messageBody" class="col-sm-1 col-form-label col-form-label-lg">
                FILES 
            </label>
            <div class="col-sm-10"   >

                <div class="controls">
    
                    <div class="entry">
                        
                    
                        <input class="btn btn-primary" name="files[]" id="attachmentFile" onchange="ValidateSize(this)" type="file" style="width:400px;background-color:#42859E">
                        <span class="fileSize">0 MB</span>
                        <span class="input-group-btn">
                            <button class="btn btn-success btn-add"  type="button">
                                        <span class="fa fa-plus" value="2"></span>
                        </button>
                        </span>
                    </div>

                </div>         

            </div>
        </div>
        <div class="form-group row">
            <label for="messageBody" class="col-sm-1 col-form-label col-form-label-lg">
            MESSAGE BODY
            </label>
            <div class="col-sm-10"   >
            <div class="div_asTextArea" contentEditable="true" id="messageBody_editable" style="padding:10px;" >
            <?php 
                                $payslipMailBody=$quotConfig['payslipMailBody'];
                                $orgAddress=$orgDetails['address1'].",";
                                if($orgDetails['address2']!=null){
                                    $orgAddress.="<br/>".$orgDetails['address2'].",";
                                }
                                $orgAddress.= "<br/>".$orgDetails['postalCode']." ".$orgDetails['city'].","; 	
                                $orgAddress.= "<br/>".$orgDetails['state'];
            
                                
                                $payslipDate=new DateTime($payslipDetails['payslipDate']);
                                $payslipDate=date_format($payslipDate,"Y-M-d");

                                $dueDate=new DateTime($payslipDetails['dueDate']);
                                $dueDate=date_format($dueDate,"Y-M-d");

                                $payslipNo=$payslipDetails['payslipNo'];
                                $payslipNo=str_pad($payslipNo,10,"0",STR_PAD_LEFT);
                                $payslipMailBody=str_replace("[Customer_Attention]",$payslipDetails['attention'],$payslipMailBody);
                                $payslipMailBody=str_replace("[payslip_Number]",'#'.$payslipNo,$payslipMailBody);
                                $payslipMailBody=str_replace("[payslip_Date]",$payslipDate,$payslipMailBody);
                                $payslipMailBody=str_replace("[Amount_Due]",'RM '.$payslipDetails['total'],$payslipMailBody);
                                $payslipMailBody=str_replace("[Due_Date]",$dueDate,$payslipMailBody);

                                $payslipMailBody=str_replace("[Organization_Name]",$orgDetails['name'],$payslipMailBody);
                                $payslipMailBody=str_replace("[Organization_Logo]",'<img style="height:100px;max-width:200pt" id="myOrgLogo" src="'."https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/resources/".$orgDetails['logoPath'].".png".'" />',$payslipMailBody);
                                $payslipMailBody=str_replace("[Organization_Address]",strtoupper($orgAddress),$payslipMailBody);
                                echo $payslipMailBody;
            
            ?>
                
            </div>
            <input type="text" id="messageBody" name="messageBody" hidden/>
            </div>  
        </div>

          <!--  <section role="main" class="l-main" style="">
               
               <div class="uploader__box js-uploader__box l-center-box">
                       <div class="uploader__contents">
                           <label class="button button--secondary" for="fileinput">Select Files</label>
                           <input id="fileinput" class="uploader__file-input" type="file" multiple value="Select Files">
                       </div>
                   
               </div>
           </section>
-->
       <div class="form-group row">
            <label class="col-sm-1 col-form-label col-form-label-lg"  ></label>
            <div class="col-sm-8"  >
                <a href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/payslip/viewpayslip.php"; ?>" class="btn btn-primary btn-lg " style="background-color:#2BADCA">CLOSE</a>
            </div>
            <div class="col-sm-2" >
                <button type="submit" id="mailpayslip" name="mailpayslip" class="btn btn-primary btn-lg btn-block" >SEND</button>
            </div>
        </div>

<!-- File Attachement Modal START-->
<div class="modal fade " data-backdrop="static" id="fileAttachementModal" tabindex="-1" role="dialog" aria-labelledby="fileAttachementModal" aria-hidden="true">
  <div class="modal-dialog " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="fileAttachementModalTitle">ATTACHEMENT</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <div id='fileAttachementModalContent' >     
           
        </div>

      </div>
        <div class="modal-footer">
            
          <button type="button" class="btn btn-secondary" data-dismiss="modal">CLOSE</button>
        </div>
    </div>
  </div>
</div>

<!-- File Attachement Modal END -->
        
        
</form>
    </div>
  </div>



    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fa fa-angle-up"></i>
    </a>
        <div class="footer">
            <p>Powered by JSoft Solution Sdn. Bhd</p>
          </div>

  </div>
</body>
</html>
