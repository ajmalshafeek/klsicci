
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
	  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/tripInvoice.php");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/organization/invoice/moreForm/form.php");


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
        #invoiceListTable td:first-child {
            cursor: default;
        }
        #invoiceListTable td {
            vertical-align: middle;
        }
    </style>
</head>
<script>
$(document).ready(function() {
  // Setup - add a text input to each footer cell
  $('#invoiceListTable tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    } );
  // DataTable
  $('#invoiceListTable').DataTable({
          "order": [
            [ 3, "desc" ]
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
              null,
            ]


		/*"columnDefs": [{
			 "orderable": false, "targets": 4
			  }
  			]*/

    });

    /* Apply the search
    table.columns().every( function () {
        var that = this;

        $( 'input', this.footer() ).on( 'keyup change clear', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    } ); */

/*
	$('#link-table tr').last().click(function () {
  			return false;
	});
    */
    $('#invoicePayButton').click(function() {


      var invoiceNo=$('#invoicePDFModalTitle').text();
      invoiceNo = invoiceNo.substr(invoiceNo.length - 10);
      $('#invoiceNo').prop('value',invoiceNo);
      $('#invoiceSetPaidModal').modal('toggle');


    });


    $('#invoiceEditButton').click(function() {

      $('#invoiceEditModal').modal('toggle');


    });

    $('#invoiceDeleteButton').click(function() {
      $('#invoiceDeleteModal').modal('toggle');
    });

    $('#invoiceInvalidateButton').click(function() {

      $('#invoiceInvalidateModal').modal('toggle');


    });

    $('#invoiceRevalidateButton').click(function() {

      $('#invoiceRevalidateModal').modal('toggle');


    });

    $('#remarkButton').click(function() {

      $('#remarkModal').modal('toggle');


    });

    $('#invoiceListTable tbody').on('click', 'td', function () {
      if($(this).index() == 0) {
         /* var checkBox=$(this).closest('td').find('[type=checkbox]');
          if(checkBox.prop('checked')){
            checkBox.prop('checked', false);
          }else{
            checkBox.prop('checked', true);
          } */
          return;
        }
      var invNo=$(this).closest('tr').find('td:eq(2)').text();
      var fileName=$(this).closest('tr').data('value');

      //var path=<?php echo "'https://".$_SERVER['HTTP_HOST'].$config['appRoot']."'"; ?>+'/resources/'+<?php echo $_SESSION['orgId']; ?>+'/invoice/';
        var path=<?php echo "'https://".$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/'.$_SESSION['orgId'].'/tripinvoice/'."'"; ?>;
      var invPDF=path+fileName+".pdf";
      $('#invoicePDFModalTitle').text('INVOICE # '+invNo);
      //$('#invoicePDFEmbed').prop('src', invPDF);
      $('#invoicePDFObject').prop('data', invPDF);
      $('#invoicePDFAnchor').prop('href', invPDF);

      $('#invoiceNo').prop('value',invNo);
      //(START)PDF NOT LOAD FIXES
        var invoicePDFHTML = "<object id='payslipPDFObject' data='" + invPDF + "' frameborder='0' width='100%' height='400px' style='height: 85vh;'><p>Your web browser doesn't have a PDF plugin.Instead you can<a id='payslipPDFAnchor' target='_blank' href='" + invPDF + "'>click here to download the PDF file.</a></p></object>";
        document.getElementById("invoicePDFModalContent").innerHTML = invoicePDFHTML;
      //(END)PDF NOT LOAD FIXES

      $.ajax({
            type  : 'GET',
            url  : '<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/phpfunctions/tripInvoice.php'; ?>',
            data : {getInvoiceByInvNo:invNo},
            success: function (data) {
              invoiceDetails = JSON.parse(data);
              if(invoiceDetails.status==0){
                $("#invoicePayButton").attr("hidden",true);
                $("#invoiceEditButton").attr("hidden",true);
              }else{
                $("#invoicePayButton").attr("hidden",false);
                $("#invoiceEditButton").attr("hidden",false);
              }

              if (invoiceDetails.invalidate==0) {
                $("#invoiceInvalidateButton").attr("hidden",false);
              }
              else {
                $("#invoiceInvalidateButton").attr("hidden",true);
              }

              if (invoiceDetails.invalidate==1) {
                $("#invoiceRevalidateButton").attr("hidden",false);
                $("#remarkButton").attr("hidden",false);
                document.getElementById("remark").innerHTML = invoiceDetails.remark;
              }
              else {
                $("#invoiceRevalidateButton").attr("hidden",true);
                $("#remarkButton").attr("hidden",true);
              }
            }
        });


        var whatstext='';
        $.ajax({
            type  : 'GET',
            url  : '<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/phpfunctions/tripInvoice.php'; ?>',
            data : {clientId:invNo},
            success: function (data) {
                email = data;
                whatstext='https://wa.me/'+data+'?text='+invPDF+'';
                if(email.length>0){
                    $('.whats').attr('href',whatstext);
                }
            }
        });

        $('#invoicePDFModal').modal('toggle');

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

#invoiceListTable{
	cursor:pointer;
}
.modal-paymentSummary {
    min-width: 60%;
    margin: 0;
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
        <li class="breadcrumb-item ">Trip Invoice</li>
        <li class="breadcrumb-item ">Invoice</li>
		<li class="breadcrumb-item active">View Invoice</li>
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
            	Invoice List
			</div>
          	<div class='card-body  '>
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
              <div class='table-responsive'>
                <form action="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot']."/phpfunctions/tripInvoice.php"; ?>" method="POST" >
                    <?php  if($role!=1){ ?>
                  <button type="submit" id="downloadSelectedFile" name="downloadSelectedInvoiceFile" class="btn"><i class="fa fa-download"></i> Download</button>
                    <?php } ?>

                    <?php
                    if($role==0){
                        $table="<h4 style='padding: 20px; text-align: center'>You are not authorize for view details</h4>";
                    } elseif($role==42 || $_SESSION['ManagerRole'] || $_SESSION['hideQuotation']) {
                      $table=tripInvoiceListTable(null,null,null,null,null,null,$dateFrom,$dateTo,null,$_SESSION['orgId']);
                    }else{
                        $table=tripInvoiceListTable(null,null,null,null,null,$_SESSION['userid'],$dateFrom,$dateTo,null,$_SESSION['orgId']);
                    }
                      echo $table;
                    ?>

                </form>

              </div>
          </div>
			</div>

		</div>


	</div>
  <form  action="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot']."/phpfunctions/tripInvoice.php"; ?>" method="POST">

 <!-- INVOICE PDF Modal START-->
 <div class="modal fade "  id="invoicePDFModal" tabindex="-1" role="dialog" aria-labelledby="invoicePDFModal" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-full" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="invoicePDFModalTitle">QUOTATION  </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">

            <div id='invoicePDFModalContent' >
            <object id="invoicePDFObject" data="<?php echo "https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/resources/2/quotation/0000000003.pdf"; ?>" frameborder="0" width="100%" height="400px" style="height: 85vh;">
                  <p>Your web browser doesn't have a PDF plugin.
                    Instead you can
                    <a id="invoicePDFAnchor" target="_blank" href="<?php echo "https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/resources/2/quotation/0000000003.pdf"; ?>">click here to
                    download the PDF file.</a>
                  </p>
          </object>
      <!--
			<embed id="invoicePDFEmbed" src="" frameborder="0" width="100%" height="400px" style="height: 85vh;">
-->
            </div>

          </div>
          <div class="modal-footer">

            <button type="button" id='remarkButton' class="btn btn-success btn-lg" >
    		  Remark
    			</button>

          <button type="button" id='invoiceRevalidateButton' class="btn btn-success btn-lg" >
    		  Revalidate
    			</button>

          <button type="button" id='invoiceInvalidateButton' class="btn btn-success btn-lg" >
  		  	Invalidate
  			  </button>

          <?php
          if ($_SESSION['paidStatus']) {
            ?>
            <button type="button" id='invoicePayButton' class="btn btn-success btn-lg" title="SET AS PAID" >
            <i style='' class='fa fa-check' aria-hidden='true'></i>
            Paid
            </button>
            <?php
          }
          ?>

          <button type="button" id='invoiceEditButton' name="invoiceEditButton" class="btn btn-success btn-lg" title="EDIT INVOICE" >
          <i style='' class='fa fa-pencil' aria-hidden='true'></i>
		  		Edit
			    </button>


          <button type="submit" id='invoiceMailButton' name="invoiceMailButton" class="btn btn-success btn-lg" title="MAIL INVOICE" >
          <i style='' class='fa fa-envelope' aria-hidden='true'></i>
		  		Mail
			    </button>


              <a  href="#" class="btn btn-success btn-lg whats" target="_blank">
                  <i style='' class='fa fa-whatsapp' aria-hidden='true'></i>
                  What'sapp
              </a>



              <button type="button" id='invoiceDeleteButton' name="invoiceDeleteButton" class="btn btn-success btn-lg" title="INVOICE DELETE" >
                  <i style='' class='fa fa-trash' aria-hidden='true'></i>
                  Delete
              </button>

            <button type="button" class="btn btn-secondary btn-lg" title="CLOSE DIALOG" data-dismiss="modal">
				      <i class="fa fa-times" aria-hidden="true"></i>
				      Close
			      </button>
          </div>
        </div>
      </div>
    </div>

  <!-- INVOICE PDF Model END -->
    <!--Remark START -->
    <div class="modal fade " data-backdrop="static" id="remarkModal" tabindex="-1" role="dialog" aria-labelledby="remarkModal" aria-hidden="true">
      <div class="modal-dialog modal-paymentSummary" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="remarkModalTitle">REMARK</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
			<div>
			    <p id="remark"></p>
			</div>
		  </div>
          <div class="modal-footer">
        	<button type="button" class="btn btn-secondary" data-dismiss="modal">CLOSE</button>
          </div>
        </div>
      </div>
    </div>

    <!--Remark Model END -->
  <!-- Invoice Invalidate Remark START -->
    <div class="modal fade " data-backdrop="static" id="invoiceInvalidateModal" tabindex="-1" role="dialog" aria-labelledby="invoiceInvalidateModal" aria-hidden="true">
      <div class="modal-dialog modal-paymentSummary" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="invoiceInvalidateModalTitle">REMARK</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
			<div>
				<textarea rows="4" cols="50" name="remark" style="width:100%"></textarea>
			</div>
         <!--   <div id='invoiceInvalidateModalContent' >
              <textarea class="form-control" rows="4" cols="50" name="remark" required></textarea>
			 ARE YOU SURE YOU WANT TO INVALIDATE THE INVOICE?
            </div>
			<div>
				<textarea class="form-control" rows="4" cols="50" name="remark" required></textarea>
			</div>

			<div>
				<input class="form-control" name="remark" required></input>
			</div>
			-->
		  </div>
          <div class="modal-footer">
          <button type="submit" class="btn btn-secondary" name="invalidateButton">SUBMIT</button>
        	<button type="button" class="btn btn-secondary" data-dismiss="modal">CANCEL</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Invoice Invalidate Remark Model END -->

    <!-- Invoice Revalidate START -->
      <div class="modal fade " data-backdrop="static" id="invoiceRevalidateModal" tabindex="-1" role="dialog" aria-labelledby="invoiceRevalidateModal" aria-hidden="true">
        <div class="modal-dialog modal-paymentSummary" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="invoiceRevalidateModalTitle">REVALIDATE</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div id='invoiceRevalidateModalContent' >
              </div>
              REVALIDATE THE INVOICE?
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-secondary" name="revalidateButton">SUBMIT</button>
              	<button type="button" class="btn btn-secondary" data-dismiss="modal">CANCEL</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Invoice Revalidate Model END -->

  <!-- Invoice edit Modal START-->
    <div class="modal fade " data-backdrop="static" id="invoiceEditModal" tabindex="-1" role="dialog" aria-labelledby="invoiceEditModal" aria-hidden="true">
      <div class="modal-dialog modal-paymentSummary" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="invoiceEditModalTitle">EDIT OPTION</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

            <div id='invoiceEditModalContent' >
            <button type="submit" name="invoiceEditButton" style="width:100%" class="btn btn-primary">OVERRIDE EXISTING INVOICE ( same invoice no )</button>
            <br/>
            <br/>
            <button type="submit" name="invoiceEditCreateButton" style="width:100%"  class="btn btn-primary">CREATE NEW INVOICE ( new invoice no ) </button>

            </div>

          </div>
          <div class="modal-footer">
        	<button type="button" class="btn btn-secondary" data-dismiss="modal">CANCEL</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Invoice edit Model END -->

  <!-- Invoice delete Modal START-->
    <div class="modal fade " data-backdrop="static" id="invoiceDeleteModal" tabindex="-1" role="dialog" aria-labelledby="invoiceEditModal" aria-hidden="true">
      <div class="modal-dialog modal-paymentSummary deleteModal" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="invoiceDeleteModalTitle">DELETE INVOICE</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

            <div id='invoiceDeleteModalContent' >
            Are you sure want to delete invoice?
            </div>
          </div>
          <div class="modal-footer">
              <button type="submit" name="removeInvoice" class="btn btn-primary edit">DELETE</button>
        	<button type="button" class="btn btn-secondary remove" data-dismiss="modal">CANCEL</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Invoice delete Model END -->
<!-- Invoice payment Modal START-->
<div class="modal fade " data-backdrop="static" id="invoiceSetPaidModal" tabindex="-1" role="dialog" aria-labelledby="invoiceSetPaidModal" aria-hidden="true">
      <div class="modal-dialog modal-paymentSummary" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="invoiceSetPaidModalTitle">PAYMENT SUMMARY</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

            <div id='invoiceSetPaidModallContent' >

                <div class="form-group row" >
                  <label for="paymentDate" class="col-sm-5 col-form-label col-form-label-lg">PAYMENT DATE</label>
                  <div class="col-sm-5"   >
                    <input class="form-control" required type="date" value="<?php echo date('Y-m-d'); ?>" id="paymentDate" name="paymentDate">
                    <div class="invalid-feedback">
                    </div>
                  </div>
                </div>

                <input hidden type="text" value="0" id="invoiceNo" name="invoiceNo" />


            </div>

          </div>
          <div class="modal-footer">

		    <button type="submit" name="makeInvoicePaid" class="btn btn-primary">SAVE</button>
        	<button type="button" class="btn btn-secondary" data-dismiss="modal">CANCEL</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Invoice payment Model END -->

  </form>
   <a class="scroll-to-top rounded" href="#page-top">
	 <i class="fa fa-angle-up"></i>
   </a>


 </div>
</body>

</html>