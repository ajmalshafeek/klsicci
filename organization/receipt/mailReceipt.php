<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");

if(!isset($_SESSION))
{
 	session_name($config['sessionName']);
	session_start();
}
if(!isset($_SESSION['receiptNumber'])){
    header("Location:https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/index.php");
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/configuration.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/quotation.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientCompany.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/receipt.php");


$quotConfig=getQuotationConfig($_SESSION['orgId']);
$invoiceDetails = getInvoiceDetailsByReceiptFileName($_SESSION['receiptNumber']);
//$quotationDetails=getQuotationDetailsByQuotNo($_SESSION['quotationNumber']);
$orgDetails=fetchOrganizationDetails($_SESSION['orgId']);
//$quotationMailList=getQuotationMailList($quotationDetails['id']);

//$clientCompanyDetails=fetchClientCompanyDetail($quotationDetails['customerId']);
?>
<!DOCTYPE html >
<html>

<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />


    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/external/Multiple-Emails/multiple-emails.css'; ?>">



	<?php
	  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");

    ?>


	<script src="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/external/Multiple-Emails/multiple-emails.js'; ?>"></script>

</head>
<script>

    function getEmailSuggest(){

    }

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


            $('body').keypress(function (event) {
                if (event.keyCode === 10 || event.keyCode === 13) {
                    event.preventDefault();
                }
            });
            $("#mailAddress").val('[]');
            $("#mailAddressCC").val('[]');
            $('#mailReceipt').click(function () {
                var messageBody = $('#messageBody_editable').html();
                $('#messageBody').val(messageBody);
            });

			//To render the input device to multiple email input using a simple hyperlink text
			$('#mailAddress').multiple_emails({theme: "Basic"});

            $('#mailAddressCC').multiple_emails({theme: "Basic"});



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

<div class="content-wrapper" >

    <div class="container-fluid" >
    <?php echo shortcut() ?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb col-md-12">
        <li class="breadcrumb-item"><a href="../../home.php">Dashboard</a></li>
		    <li class="breadcrumb-item active">Receipt</li>
      </ol>
    </div>





      <!--
style="background-color:white;border-radius:25px;padding-left:20px;padding-top:10px;padding-bottom:10px;padding-right:20px;"
      -->
        <div class="container"  >


	  <?php
			if (isset($_SESSION['feedback'])) {
				echo $_SESSION['feedback'];
				unset($_SESSION['feedback']);
			}
    ?>
            <div class="col-12">

                <h1  style="text-align:center; font-family:brush script mt;">Mail This Receipt</h1>

            </div>
            <form  action="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot']."/phpfunctions/mail.php"; ?>"
                method="POST" class="needs-validation" enctype="multipart/form-data">

                <div class="form-group row">
                    <label for="mailAddress" class="col-sm-1 col-form-label col-form-label-lg">TO</label>

                    <div class="col-sm-10" id="toAddressDiv">
                    <input type='text' id='mailAddress' name='mailAddress' class='form-control' style="padding:10px;" required >
                    <div id="livesearch"></div>
                        <div class="invalid-feedback">
                        Please enter email address to send
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="mailAddressCC" class="col-sm-1 col-form-label col-form-label-lg">CC</label>
                    <div class="col-sm-10"   >
                    <input type='text' id='mailAddressCC' name='mailAddressCC' value="[]" class='form-control' style="padding:10px;">
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
                    <input type='text'  class='form-control' name="subject" value='RECEIPT-#<?php echo $_SESSION['receiptNumber']; ?>' id="subject">


                    </div>
                </div>
                <!--
                <input type="file" name="fileToUpload[]" id="fileToUpload" multiple>
-->
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
                    <!--
style="height: 200px;border: 1px solid #ccc;overflow: scroll;background-color:#EEFAFB    "
                    -->

                    <div class="div_asTextArea" contentEditable="true" id="messageBody_editable" style="padding:10px;" >
                    <?php
                            $quoMailBody=$quotConfig['quotationMailBody'];
                            $orgAddress=$orgDetails['address1'].",";
                            if($orgDetails['address2']!=null){
                                $orgAddress.="<br/>".$orgDetails['address2'].",";
                            }
                            $orgAddress.= "<br/>".$orgDetails['postalCode']." ".$orgDetails['city'].",";
                            $orgAddress.= "<br/>".$orgDetails['state'];

                            $quoMailBody=str_replace("[Customer_Attention]",$invoiceDetails['attention'],$quoMailBody);
                            $quoMailBody=str_replace("[Organization_Name]",$orgDetails['name'],$quoMailBody);
                            $quoMailBody=str_replace("[Organization_Logo]",'<img style="height:100px;max-width:200pt" id="myOrgLogo" src="'."https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/resources/".$orgDetails['logoPath'].".png".'" />',$quoMailBody);
                            $quoMailBody=str_replace("[Organization_Address]",strtoupper($orgAddress),$quoMailBody);
                            echo $quoMailBody;

                    ?>

                    </div>
                    <input type="text" id="messageBody" name="messageBody" hidden/>
                    </div>
                </div>

                    <div class="form-group row">
                        <label class="col-sm-1 col-form-label col-form-label-lg"  ></label>
                        <div class="col-sm-8"  >
                            <a href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/receipt/receipt.php"; ?>" class="btn btn-primary btn-lg " style="background-color:#2BADCA">CLOSE</a>
                        </div>
                        <div class="col-sm-2" style="text-align:right">
                            <button type="submit" id="mailReceipt" name="mailReceipt" class="btn btn-primary btn-lg btn-block" >SEND</button>


                        </div>
                    </div>


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
