
<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");

if(!isset($_SESSION))
{
 	session_name($config['sessionName']);
	session_start();
}
if(!isset($_SESSION['invoiceNumber'])){
    header("Location:https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/index.php");
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/configuration.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/tripInvoice.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientCompany.php");



$quotConfig=getQuotationConfig($_SESSION['orgId']);
$invoiceDetails=getTripInvoiceDetailsByInvNo($_SESSION['invoiceNumber']);
$invoiceMailList=getTripInvoiceMailList($invoiceDetails['id']);

$orgDetails=fetchOrganizationDetails($_SESSION['orgId']);

$clientCompanyDetails=fetchClientCompanyDetail($invoiceDetails['customerId']);
?>
<!DOCTYPE html >
<html>

<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />


    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/external/Multiple-Emails/multiple-emails.css'; ?>">
    <link href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/external/Multple-File-Uploader/dist/styles.imageuploader.css'; ?>" type="text/css" rel="stylesheet" type="text/css" />



	<?php
	  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");

    ?>


	<script src="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/external/Multiple-Emails/multiple-emails.js'; ?>"></script>

    <script src="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/external/Multple-File-Uploader/dist/jquery.imageuploader.js'; ?>"></script>
</head>
<script>
    (function() {

        $(document).on('click', '.btn-add', function(e)
        {
            e.preventDefault();

            var controlForm = $('.controls:first'),
                currentEntry = $(this).parents('.entry:first'),
                newEntry = $(currentEntry.clone()).appendTo(controlForm);
                var fileSizeSpan=$(newEntry).find('.fileSize');
                fileSizeSpan.text("0 MB");

                newEntry.find('input').val('');
                controlForm.find('.entry:not(:last) .btn-add')
                .removeClass('btn-add').addClass('btn-remove')
                .removeClass('btn-success').addClass('btn-danger')
                .html('<span class="fa fa-minus"></span>');
        }).on('click', '.btn-remove', function(e)
        {

            var fileSizeSpan=$(this).parents('.entry:first').find('.fileSize');
            var fileSize = fileSizeSpan.text();
            fileSize = fileSize.substring(0, fileSize.length-3);
            totalFileSize-=fileSize;
            $(this).parents('.entry:first').remove();

            e.preventDefault();
            return false;
        });

            'use strict';
            window.addEventListener('load', function() {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('needs-validation');
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
                });
            }, false);
    })();


		//Plug-in function for the bootstrap version of the multiple email
		$(function() {

            <?php
                $toAddress='"'.$clientCompanyDetails['emailAddress'].'"';
                $ccAddress="";
                foreach($invoiceMailList as $address){
                    if($address['type']=="TO"){
                        if(strlen($toAddress)!=0){
                            $toAddress.=",";
                        }
                      $toAddress.='"'.$address['mailAddress'].'"';
                    }else if($address['type']=="CC"){
                        if(strlen($ccAddress)!=0){
                            $ccAddress.=",";
                        }
                      $ccAddress.='"'.$address['mailAddress'].'"';
                    }
                }


            ?>

            $("#invoiceMailAddress").val('[<?php echo $toAddress; ?>]');
            $("#invoiceMailAddressCC").val('[<?php echo $ccAddress; ?>]');
            $('#mailInvoice').click(function () {
                var messageBody = $('#messageBody_editable').html();
                $('#messageBody').val(messageBody);
            });

           //To render the input device to multiple email input using a simple hyperlink text
           $('#invoiceMailAddress').multiple_emails({theme: "Basic"});


           $('#invoiceMailAddressCC').multiple_emails({theme: "Basic"});


       });

       var totalFileSize=0;
        var fileLimitSize=25; //mb
        function ValidateSize(file) {
            var fileSize = file.files[0].size / 1024 / 1024; // in MB
            totalFileSize+=fileSize;
            var fileSizeSpan=$(file).parents('.entry:first').find('.fileSize');

            if (totalFileSize > fileLimitSize) {

                totalFileSize-=fileSize;
                alert('Failed to attach, total file size limit is '+fileLimitSize+' MB\n'+

                'File Size : '+fileSize.toFixed(2)+' MB\n'+
                'Current size is '+totalFileSize.toFixed(2)+' MB'
                );
                $(file).val(''); //for clearing with Jquery
            } else {
                fileSizeSpan.text(fileSize.toFixed(2)+" MB");
            }
        }
</script>

<style>
.entry:not(:first-of-type)
{
    margin-top: 5px;
}
</style>
<?php
  include $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/navMenu.php";
?>

<body class="fixed-nav ">
<div class="content-wrapper">
    <div class="container-fluid">
    <?php echo shortcut() ?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb col-md-12">
        <li class="breadcrumb-item">
		<a href="#">TRIP INVOICE </a>
        </li>

        <li class="breadcrumb-item ">INVOICE</li>
		<li class="breadcrumb-item active">MAIL INVOICE</li>
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
         <h1  style="text-align:center; font-family:brush script mt;">Mail This Invoice</h1>
        </div>

		<form action="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot']."/phpfunctions/mail.php"; ?>"
        method="POST" class="needs-validation" novalidate>




      <div class="form-group row">
                <label for="invoiceMailAddress" class="col-sm-1 col-form-label col-form-label-lg">TO</label>

                <div class="col-sm-10"   >
                <input type='text' id='invoiceMailAddress' name='invoiceMailAddress'  value='[]' class='form-control' style="padding:10px;" required>
                    <div class="invalid-feedback">
                    Please enter email address to send
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="invoiceMailAddressCC" class="col-sm-1 col-form-label col-form-label-lg">CC</label>
                <div class="col-sm-10"   >
                <input type='text' id='invoiceMailAddressCC' name='invoiceMailAddressCC' value='[]' class='form-control' style="padding:10px;">

                    <div class="invalid-feedback">
                    Please enter email address to send
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label for="messageBody" class="col-sm-1 col-form-label col-form-label-lg">
                SUBJECT
                </label>
                <div class="col-sm-10"   >
                <input type='text'  class='form-control' name="subject" value='INVOICE-#<?php echo $_SESSION['invoiceNumber']; ?>' id="subject">


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
                                    $invoiceMailBody=$quotConfig['invoiceMailBody'];
                                    $orgAddress=$orgDetails['address1'].",";
                                    if($orgDetails['address2']!=null){
                                        $orgAddress.="<br/>".$orgDetails['address2'].",";
                                    }
                                    $orgAddress.= "<br/>".$orgDetails['postalCode']." ".$orgDetails['city'].",";
                                    $orgAddress.= "<br/>".$orgDetails['state'];


                                    $invoiceDate=new DateTime($invoiceDetails['invoiceDate'], new DateTimeZone('Asia/Kuala_Lumpur'));
                                    $invoiceDate=date_format($invoiceDate,"Y-M-d");

                                    $dueDate=new DateTime($invoiceDetails['dueDate'], new DateTimeZone('Asia/Kuala_Lumpur'));
                                    $dueDate=date_format($dueDate,"Y-M-d");

                                    $invoiceNo=$invoiceDetails['invoiceNo'];
                                    $invoiceNo=str_pad($invoiceNo,10,"0",STR_PAD_LEFT);
                                    $invoiceMailBody=str_replace("[Customer_Attention]",$invoiceDetails['attention'],$invoiceMailBody);
                                    $invoiceMailBody=str_replace("[Invoice_Number]",'#'.$invoiceNo,$invoiceMailBody);
                                    $invoiceMailBody=str_replace("[Invoice_Date]",$invoiceDate,$invoiceMailBody);
                                    $invoiceMailBody=str_replace("[Amount_Due]",'RM '.$invoiceDetails['total'],$invoiceMailBody);
                                    $invoiceMailBody=str_replace("[Due_Date]",$dueDate,$invoiceMailBody);

                                    $invoiceMailBody=str_replace("[Organization_Name]",$orgDetails['name'],$invoiceMailBody);
                                    $invoiceMailBody=str_replace("[Organization_Logo]",'<img style="height:100px;max-width:200pt" id="myOrgLogo" src="'."https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/resources/".$orgDetails['logoPath'].".png".'" />',$invoiceMailBody);
                                    $invoiceMailBody=str_replace("[Organization_Address]",strtoupper($orgAddress),$invoiceMailBody);
                                    echo $invoiceMailBody;

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
                    <a href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/invoice/viewInvoice.php"; ?>" class="btn btn-primary btn-lg " style="background-color:#2BADCA">CLOSE</a>
                </div>
                <div class="col-sm-2" >
                    <button type="submit" id="mailInvoice" name="mailInvoice" class="btn btn-primary btn-lg btn-block" >SEND</button>
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


    <div class="footer">
        <p>Powered by JSoft Solution Sdn. Bhd</p>
        </div>
        </div>

   <a class="scroll-to-top rounded" href="#page-top">
	 <i class="fa fa-angle-up"></i>
   </a>


 </div>

</body>

</html>