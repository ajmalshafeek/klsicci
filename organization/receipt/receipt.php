<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
  session_name($config['sessionName']);
  session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientCompany.php");

?>
<!DOCTYPE html >

<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">


    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />

    <?php
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>
    <!-- Data Table Import -->
    <link rel="stylesheet" type="text/css" href="../../adminTheme/dataTable15012020/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="../../adminTheme/dataTable15012020/jquery.dataTables.js"></script>
    <script>
    $(document).ready( function () {
      $('#receiptTable').DataTable();
    } );

    function showReceipt(id){
      $.ajax({
          type  : 'GET',
          url  : '../../phpfunctions/receipt.php?',
          data : {showReceipt:id},
          success: function (data) {
          details= JSON.parse(data);
          getReceiptBalanceByInvoiceId(details.invoiceId);

          var balanceAmount = details.totalAmount - details.paidAmount;

          document.getElementById("totalAmount").value = Number(details.totalAmount).toFixed(2);
          document.getElementById("paidAmount").value = Number(details.paidAmount).toFixed(2);
          document.getElementById("invoiceId").value= details.invoiceId;
          }
      });
    }

    function showInvoice(invoiceId){
      $.ajax({
          type  : 'GET',
          url  : '../../phpfunctions/invoice.php?',
          data : {showInvoiceReceipt:invoiceId},
          success: function (data) {
          details= JSON.parse(data);

          document.getElementById("invoiceId").value= details.id;
          document.getElementById("totalAmount").value = Number(details.total).toFixed(2);
          document.getElementById("paidAmount").value = Number(0).toFixed(2);
          document.getElementById("balanceAmount").value = Number(details.total).toFixed(2);
          document.getElementById("amountToPay").max = details.total;
          }
      });
    }

    function getReceiptBalanceByInvoiceId(invoiceId){
      $.ajax({
          type  : 'GET',
          url  : '../../phpfunctions/receipt.php?',
          data : {getReceiptBalance:true,invoiceId:invoiceId},
          success: function (data) {
            console.log(data);
            if (data == "0") {
              document.getElementById("amountToPay").readOnly = true;
              document.getElementById("submitReceipt").disabled = true;
              document.getElementById("balanceAmount").value = Number(data).toFixed(2);
            }else {
              document.getElementById("amountToPay").readOnly = false;
              document.getElementById("balanceAmount").value = Number(data).toFixed(2);
              document.getElementById("amountToPay").max = data;
              document.getElementById("submitReceipt").disabled = false;
            }
          }
      });
    }

    function showReceiptPDF(fileName){
      document.getElementById("receiptPDF").innerHTML = "<object class='form-control' type='' data='../../resources/" + <?php echo $_SESSION['orgId'] ?> + "/receipt/" + fileName + "'></object>";

      //GETTING THE FILE NAME WITH EXTENSION(.pdf)
      nameArr = fileName.split('.');
      fileNameNoExtension = nameArr[0];
      document.getElementById("mailReceipt").href = "../../phpfunctions/receipt.php?mailReceipt=" + fileNameNoExtension;
    }
    </script>
    <!-- Data Table Import -->

    <style>
    table, td, th {
        text-align: center;
    }

    table {
      border-collapse: collapse;
      width: 100%;
    }

    th {
      height: 50px;
      color:white;
      background: #212529;
    }
    table tbody tr:hover td{
      background-color: #DEE2E6;
    }
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
        <li class="breadcrumb-item ">Receipt</li>
        <li class="breadcrumb-item active">View Receipt</li>
      </ol>
    </div>
    <?php
        if (isset($_SESSION['feedback'])) {
            echo $_SESSION['feedback'];
            unset($_SESSION['feedback']);
        }
    ?>
    <div class="container">
      <form action="../../phpfunctions/receipt.php" method="post">
        <div class="form-group row">
          <!--CLIENT-->
          <div class="form-group col-md-6">
              <label for="client">Client</label>
              <select name="clientCompanyId" class="form-control" id="clientCompanyId">
              <option  selected disabled value="" >--Select--</option>
              <?php

                $clientCompanyList=dropDownOptionListOrganizationClientCompanyActive();
                echo $clientCompanyList;
              ?>
              </select>
          </div>

          <!--INVOICE NO-->
          <div class="form-group col-md-6">
            <label for="invoiceNo">Invoice</label>
            <input class="form-control" type="text" name="invoiceNo">
          </div>
        </div>
        <div class="form-group row">
          <!--DATE FROM-->
          <div class="form-group col-md-6">
              <label for="client">Date From</label>
              <input class="form-control" type="date" name="dateFrom">
          </div>

          <!--DATE TO-->
          <div class="form-group col-md-6">
              <label for="client">Date To</label>
              <input class="form-control" type="date" name="dateTo">
          </div>
        </div>
        <div class="form-group row">
          <button class="btn btn-secondary btn-lg btn-block" type="submit" name="getReceiptList">Search</button>
        </div>
      </form>
    </div>
    <div class="container">
      <?php
      if(isset($_SESSION['receiptTable'])){
        echo $_SESSION['receiptTable'];
        $_SESSION['receiptTable']="";
      }
      ?>
    </div>
  </div>

  <!-- Create Receipt Modal START-->
  <div class="modal fade" id="receiptModal" tabindex="-1" role="dialog" aria-labelledby="receiptModal" aria-hidden="true">
       <div class="modal-dialog modal-dialog-centered modal-full" role="document">
         <div class="modal-content">
           <div class="modal-header">
             <h5 class="modal-title">Create Receipt</h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
             </button>
           </div>
           <div class="modal-body">
             <div class="form-gorun row">
               <!--TOTAL AMOUNT-->
               <div class="form-group col-md-6">
                   <label for="totalAmount">Total Amount(RM)</label>
                   <input class="form-control" id="totalAmount" type="text" readonly>
               </div>

               <!--PAID AMOUNT-->
               <div class="form-group col-md-6">
                   <label for="paidAmount">Paid Amount(RM)</label>
                   <input class="form-control" id="paidAmount" type="text" readonly>
               </div>
             </div>
             <div class="form-group row">
               <!--BLALANCE AMOUNT-->
               <div class="form-group col-md-6">
                   <label for="balanceAmount">Balance Amount(RM)</label>
                   <input class="form-control" id="balanceAmount" type="text" readonly>
               </div>
             </div>

             <hr>

             <form action="../../phpfunctions/receipt.php" method="post">
               <div class="form-group row">
                 <!--AMOUNT TO PAY-->
                 <div class="form-group col-md-12">
                     <label for="balanceAmount">Amount to Pay(RM)</label>
                     <input id="amountToPay" name="amountToPay" class="form-control" type="number" min="1" step="0.01">
                 </div>
               </div>
               <div class="form-group row">
                 <!--SUBMIT-->
                 <div class="form-group col-md-12">
                     <input id="invoiceId" type="number" name="invoiceId" hidden>
                     <button id="submitReceipt" class="btn btn-secondary btn-lg btn-block" type="submit" name="submitReceipt">Create Receipt</button>
                 </div>
               </div>
             </form>
           </div>
           <div class="modal-footer">
             <button type="button" class="btn btn-secondary btn-lg" title="CLOSE DIALOG" data-dismiss="modal">
 				      <i class="fa fa-times" aria-hidden="true"></i>
 				      Close
 			       </button>
           </div>
         </div>
       </div>
  </div>

  <!-- Show Receipt Modal START-->
  <div class="modal fade" id="receiptPDFModal" tabindex="-1" role="dialog" aria-labelledby="receiptPDFModal" aria-hidden="true">
       <div class="modal-dialog modal-dialog-centered modal-full" role="document">
         <div class="modal-content">
           <div class="modal-header">
             <h5 class="modal-title">Receipt</h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
             </button>
           </div>
           <div id="receiptPDF" class="modal-body">

           </div>
           <div class="modal-footer">
             <a id="mailReceipt" href="">
               <button type="button" class="btn btn-secondary btn-lg">
                <i class="fa fa-envelope-o" aria-hidden="true"></i>
                Mail
               </button>
             </a>
             <button type="button" class="btn btn-secondary btn-lg" title="CLOSE DIALOG" data-dismiss="modal">
              <i class="fa fa-times" aria-hidden="true"></i>
              Close
             </button>
           </div>
         </div>
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
