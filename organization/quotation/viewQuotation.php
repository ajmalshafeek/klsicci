<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");

if(!isset($_SESSION))
{
 	session_name($config['sessionName']);
	session_start();
}

?>
<!DOCTYPE html >
<html>

<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />


	  <?php
	  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/quotation.php");
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/organization/quotation/moreForm/form.php");
	  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot'] . "/phpfunctions/organization.php");
	  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot'] . "/phpfunctions/configuration.php");

    ?>
    <!-- datatable -->
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/jquery.dataTables.js'></script>
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/dataTables.bootstrap4.js'></script>
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/js/sb-admin-datatables.min.js'></script>
    <!-- datatable -->

<style>
    .modal-dialog.modal-dialog-centered.modal-full {
        margin-left: auto;
        margin-right: auto;
    }
    input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }
    #quotationListTable td:first-child {
        cursor: default;
    }
    #quotationListTable td {
        vertical-align: middle;
    }
</style>
  </head>
  <script>
    $(document).ready(function() {


      $('#quotationListTable').DataTable({

          "order": [
            [ 2, "desc" ]
          ] ,
          "columnDefs": [ {
          "targets": 0,
          "orderable": false
          } ],
          "aoColumns": [
              null,
              { "sType": "date" },
              null,
              null,
              null,
              null,
            ]
        /*"columnDefs": [{
          "orderable": false, "targets": 4
            }
            ]*/

        });

    /*
      $('#link-table tr').last().click(function () {
            return false;
      });
    */
    $('#invoiceEditButton').click(function() {
      $('#invoiceEditModal').modal('toggle');
    });


    $('#convertToInvoice').click(function() {

      $('#quotation2InvoiceModal').modal('toggle');
    });


    $('#quotationEditButton').click(function() {

      $('#quotationEditModal').modal('toggle');

    });

        $('#quotationDeleteButton').click(function() {
            $('#quotationDeleteModal').modal('toggle');
        });

    $("#downloadSelectedFile").click(function() {

    });

    $('#quotationListTable tbody').on('click', 'td', function () {
        if($(this).index() == 0) {
        /*  var checkBox=$(this).closest('td').find('[type=checkbox]');
          if(checkBox.prop('checked')){
            checkBox.prop('checked', false);
          }else{
            checkBox.prop('checked', true);
          }
          */
          return;
        }

        var quotNo=$(this).closest('tr').find('td:eq(2)').text();
        var fileName=$(this).closest('tr').data('value');
        //var path=<?php echo "'https://".$_SERVER['HTTP_HOST'].$config['appRoot']."'"; ?>+'/resources/'+<?php echo $_SESSION['orgId']; ?>+'/quotation/';
        var path=<?php echo "'https://".$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/'.$_SESSION['orgId'].'/quotation/'."'"; ?>;
        var quotPDF=path+fileName+".pdf";
        $('#quotationPDFModalTitle').text('QUOTATION # '+quotNo);
        $('#quotationPDFObject').prop('data', quotPDF);
        $('#quotationPDFAnchor').prop('href', quotPDF);

        $('.quotationNo').prop('value',quotNo);
        $('#quotationPDFModal').modal('toggle');

        //(START)PDF NOT LOAD FIXES
        var quotationPDFHTML = "<object id='payslipPDFObject' data='" + quotPDF + "' frameborder='0' width='100%' height='400px' style='height: 85vh;'><p>Your web browser doesn't have a PDF plugin.Instead you can<a id='payslipPDFAnchor' target='_blank' href='" + quotPDF + "'>click here to download the PDF file.</a></p></object>";
        document.getElementById("quotationPDFModalContent").innerHTML = quotationPDFHTML;
        //(END)PDF NOT LOAD FIXES

        var whatstext='';
        $.ajax({
            type  : 'GET',
            url  : '<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/phpfunctions/quotation.php'; ?>',
            data : {clientId:quotNo},
            success: function (data) {
                email = data;
                whatstext='https://wa.me/'+data+'?text='+quotPDF+'';

                    $('.whats').attr('href',whatstext);

            }
        });

        });

    });

    function showDateFilter(){
      var checked = document.getElementById("dateFilterCheck").checked;
      if (checked) {
        document.getElementById("dateFilter").style.display = "block";
      }else {
        document.getElementById("dateFilter").style.display = "none";
      }
    }
  </script>
<style>

#quotationListTable{
	cursor:pointer;
}

.modal-full {
    min-width: 90%;
    margin: 0;
}

.modal-full .modal-content {
    min-height: 100vh;
}

.deleteModal{
    margin-left: auto;
    min-width: 300px;
    /* max-width: 500px; */
    margin-right: auto;
}

</style>
<?php
  include $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/navMenu.php";
?>


<body class="fixed-nav " >
<div class="content-wrapper">
    <div class="container-fluid" >
        <?php echo shortcut() ?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb col-md-12">
        <li class="breadcrumb-item">
          <a href="../../home.php">Dashboard</a>
        </li>
		<li class="breadcrumb-item ">Quotation & Invoice</li>
        <li class="breadcrumb-item ">Quotation</li>
		<li class="breadcrumb-item active">View Quotation</li>
      </ol>
    </div>
	  <?php
			if (isset($_SESSION['feedback'])) {
				echo $_SESSION['feedback'];
				unset($_SESSION['feedback']);
			}

      $role=$_SESSION['role'];
        ?>



  		 <div class='card mb-3 '  >
			<div class='card-header '>
				<i class='fa fa-table'></i>
            	Quotation List
			</div>
          	<div class='card-body '>
                <?php  if($role!=1){ ?>
              <input id="dateFilterCheck" type="checkbox" onchange="showDateFilter()" <?php if(isset($_POST['checked'])){ if($_POST['checked']){ echo "checked";} } ?>>  Filter by date range
              <?php
                }
              $form='';
                if (isset($_POST['dateFrom'])) {
                  $dateFrom = $_POST['dateFrom'];
                  $reset = true;
                }else {
                  $dateFrom = null;
                  $reset = false;
                }

                if (isset($_POST['dateTo'])) {
                  $dateTo = $_POST['dateTo'];
                }else {
                  $dateTo = null;
                }

                additionalForm($dateFrom,$dateTo,$reset);

              ?>
                <div class='table-responsive '>
               <form action="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot']."/phpfunctions/quotation.php"; ?>" method="POST" >
<?php  if($role!=1){ ?>
                  <button type="submit" id="downloadSelectedFile" name="downloadSelectedQuotationFile" class="btn"><i class="fa fa-download"></i> Download</button>
<?php } ?>
                  <?php

	                  $table="";
	                  if($role==0){
                          $table="<h4 style='padding: 20px; text-align: center'>You are not authorize for view details</h4>";
                      } elseif($role==42 || $_SESSION['ManagerRole'] || $_SESSION['hideQuotation']) {
		                  $table = quotationListTable(null, null, null, null, null, null, $dateFrom, $dateTo, null, $_SESSION['orgId']);
	                  }else{
		                  $table = quotationListTable(null, null, null, null, null, $_SESSION['userid'], $dateFrom, $dateTo, null, $_SESSION['orgId']);
	                  }
                      echo $table;
                  ?>
                </form>
                </div>

            </div>

		</div>


	</div>
  <form  action="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot']."/phpfunctions/quotation.php"; ?>" method="POST">
 <!-- QUOTATION PDF Modal START-->
 <div class="modal fade "  id="quotationPDFModal" tabindex="-1" role="dialog" aria-labelledby="quotationPDFModal" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-full" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="quotationPDFModalTitle">DETAILS</h5>
			<div class="text-right">
			<button type="button" id='quotationEditButton' name="quotationEditButton" class="btn btn-success btn-lg" title="EDIT QUOTATION" >
        <i style='' class='fa fa-pencil' aria-hidden='true'></i>
		  	Edit
			</button>

      <button type="submit" id='quotationMailButton' name="quotationMailButton" class="btn btn-success btn-lg" title="MAIL QUOTATION" >
        <i style='' class='fa fa-envelope' aria-hidden='true'></i>
		  	Mail
			</button>

                <a  href="#" class="btn btn-success btn-lg whats" id="whats" target="_blank">
                    <i style='' class='fa fa-whatsapp' aria-hidden='true'></i>
                    What'sapp
                </a>

                <button type="button" id='convertToInvoice' class="btn btn-success btn-lg" title="CONVERT TO QUOTATION" >
		  		<i style='' class='fa fa-refresh' aria-hidden='true'></i>
		  		Convert
			</button>
                <button type="button" id='quotationDeleteButton' name="quotationDeleteButton" class="btn btn-success btn-lg" title="QUOTATION DELETE" >
                    <i style='' class='fa fa-trash' aria-hidden='true'></i>
                    Delete
                </button>

                <button type="button" class="btn btn-secondary btn-lg" title="CLOSE DIALOG" data-dismiss="modal">
				<i class="fa fa-times" aria-hidden="true"></i>
				Close
			</button>
			</div>
			
            <!--button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button-->
          </div>
          <div class="modal-body">

            <div id='quotationPDFModalContent' >
            <object id="quotationPDFObject" data="<?php echo "https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/resources/2/quotation/0000000003.pdf"; ?>" frameborder="0" width="100%" height="400px" style="height: 85vh;">
                  <p>Your web browser doesn't have a PDF plugin.
                    Instead you can
                    <a id="quotationPDFAnchor" target="_blank" href="<?php echo "https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/resources/2/quotation/0000000003.pdf"; ?>">click here to
                    download the PDF file.</a>
                  </p>
          </object>



<!--
  <embed id="quotationPDFEmbed" src="" frameborder="0" width="100%" height="400px" style="height: 85vh;">
			<object type="application/pdf" data="<?php echo "https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/resources/2/quotation/0000000003.pdf"; ?>" width="100%" height="400" style="height: 85vh;">No Support</object>
-->
            </div>

          </div>
          <div class="modal-footer">

      <!--button type="button" id='quotationEditButton' name="quotationEditButton" class="btn btn-success btn-lg" title="EDIT QUOTATION" >
        <i style='' class='fa fa-pencil' aria-hidden='true'></i>
		  	Edit
			</button>

      <button type="submit" id='quotationMailButton' name="quotationMailButton" class="btn btn-success btn-lg" title="MAIL QUOTATION" >
        <i style='' class='fa fa-envelope' aria-hidden='true'></i>
		  	Mail
			</button>

      <button type="button" id='convertToInvoice' class="btn btn-success btn-lg" title="CONVERT TO QUOTATION" >
		  		<i style='' class='fa fa-refresh' aria-hidden='true'></i>
		  		Convert
			</button>
            <button type="button" class="btn btn-secondary btn-lg" title="CLOSE DIALOG" data-dismiss="modal">
				<i class="fa fa-times" aria-hidden="true"></i>
				Close
			</button-->
          </div>
        </div>
      </div>
    </div>
    <!-- QUOTATION PDF Model END -->

      <!-- Quotation delete Modal START-->

      <div class="modal fade " data-backdrop="static" id="quotationDeleteModal" tabindex="-1" role="dialog" aria-labelledby="invoiceEditModal" aria-hidden="true">
          <div class="modal-dialog modal-paymentSummary deleteModal" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="invoiceDeleteModalTitle">DELETE QUOTATION</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">

                      <div id='invoiceDeleteModalContent' >
                          Are you sure want to delete quotation?
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="submit" name="removeQuotation" class="btn btn-primary edit">DELETE</button>
                      <button type="button" class="btn btn-secondary remove" data-dismiss="modal">CANCEL</button>
                  </div>
              </div>
          </div>
      </div>

      <!-- Quotation delete Model END -->
    <!-- Invoice edit Modal START-->
    <div class="modal fade " data-backdrop="static" id="quotationEditModal" tabindex="-1" role="dialog" aria-labelledby="quotationEditModal" aria-hidden="true">
      <div class="modal-dialog modal-paymentSummary" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="quotationEditModalTitle">EDIT OPTION</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

            <div id='quotationEditContent' >
            <button type="submit" name="quotationEditButton" style="width:100%" class="btn btn-primary">Override Existing Quotation ( same quotation no )</button>
            <br/>
            <br/>
            <button type="submit" name="quotationEditCreateButton" style="width:100%"  class="btn btn-primary">Create New Quotation( new quotation no ) </button>

            </div>
            <input hidden type="text" value="0" id="quotationNo" class="quotationNo" name="quotationNo" />

          </div>
          <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>
  <!-- Invoice edit Model END -->
    </form>



<!-- CONVERT TO INVOICE YES/NO DIALOG Modal START-->
<form  action="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot']."/phpfunctions/quotation.php"; ?>" method="POST">
<div class="modal fade " data-backdrop="static" id="quotation2InvoiceModal" tabindex="-1" role="dialog" aria-labelledby="quotation2InvoiceModal" aria-hidden="true">
      <div class="modal-dialog " role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="quotation2InvoiceModalTitle">CONVERT QUOTATION TO INVOICE</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

            <div id='quotation2InvoiceModalContent' >
            Are you sure to convert this quotation to invoice ?
	            <div class="form-group">

		            <label for="pdffooter" class="col-sm-12 col-form-label ">SELECT FOOTER FOR PDF
		            </label>
		            <div class="col-sm-12">
			            <select class="custom-select" name="pdfFooter" id="pdfFooter"
			                  <?php //  style="background-color:#A3C2CE;border:none;border-top:1px solid black;border-radius:0px;border-bottom:1px solid black;" ?> >
				            <?php
if(isset($_SESSION['orgId'])) {
	$footerList = getPdfFooterList(null, $_SESSION['orgId']);
	//$footerNote="";
	foreach ($footerList as $footer) {
		/*       $selected = "";
		if ($footer['id'] == $footerId) {
			$selected = "selected";
			//	$footerNote=$footer['content'];
		} */
		echo "<option value='" . $footer['id'] . "' >" . $footer['name'] . "</option>";
	}
}
				            ?>
			            </select>

		            </div>
	            </div>
	            <div class="form-group">
            <input hidden type="text" value="0" id="quotationNo" class="quotationNo" name="quotationNo" /></div>
            </div>

          </div>
          <div class="modal-footer">

		      <button type="submit" name="convert2Invoice" class="btn btn-primary">YES</button>
        	<button type="button" class="btn btn-secondary" data-dismiss="modal">NO</button>
          </div>
        </div>
      </div>
    </div>
    </form>
    <!-- CONVERT TO INVOICE YES/NO DIALOG Model END -->


   <a class="scroll-to-top rounded" href="#page-top">
	 <i class="fa fa-angle-up"></i>
   </a>


 </div>
</body>

</html>