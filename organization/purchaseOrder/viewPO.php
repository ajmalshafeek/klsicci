<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
  session_name($config['sessionName']);
  session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/purchaseOrder.php");
$userid = $_SESSION['userid'];
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
    <script type="text/javascript">
      $(document).ready( function () {
        $('#poTable').DataTable();
      } );

      function showPODetails(id){
        $.ajax({
            type  : 'GET',
            url  : '../../phpfunctions/purchaseOrder.php?',
            data : {showPO:id},
            success: function (data) {
            details= JSON.parse(data);
            document.getElementById("poId").value = details.id;
            document.getElementById("poModalTitle").innerHTML = details.fileName.replace(/\..+$/, '');
            document.getElementById("purchaseOrderPDF").innerHTML = "<object width='100%' height='500px' data='../../resources/<?php echo $_SESSION['orgId'] ?>/purchaseOrder/" + details.fileName + "'></object>";
            }
        });
      }

      function checkRemovePO(){
        var poId = document.getElementById("poId").value;
        if (confirm("Are you sure you want to remove the Purchase Order?")) {
          window.location.href = "../../phpfunctions/purchaseOrder.php?poIdRemove=" + poId;
        }
      }
    </script>
    <style>
        .modal-dialog{max-width: 90% !important;}
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
        <li class="breadcrumb-item ">Purchase Order</li>
        <li class="breadcrumb-item active">View Purchase Order</li>
      </ol>
    </div>
    <div class="container">
      <?php
            if (isset($_SESSION['feedback'])) {
                echo $_SESSION['feedback'];
                unset($_SESSION['feedback']);
            }
      ?>
      <?php purchaseOrderTable() ?>
    </div>
  </div>

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fa fa-angle-up"></i>
  </a>
  <div class="footer">
      <p>Powered by JSoft Solution Sdn. Bhd</p>
  </div>

  <!-- PO Modal START-->
  <div class="modal fade" id="poModal" tabindex="-1" role="dialog" aria-labelledby="poModal" aria-hidden="true">
       <div class="modal-dialog modal-dialog-centered modal-full" role="document">
         <div class="modal-content">
           <div class="modal-header">
             <h5 class="modal-title" id="poModalTitle">Purchase Order</h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
             </button>
           </div>
           <div class="modal-body">
             <div class="form-group row">
               <div class="col-sm-12">
                 <div id="purchaseOrderPDF"></div>
               </div>
             </div>
           </div>
           <script type="text/javascript">
           function emailPO(){
             var poId = document.getElementById("poId").value;
             var emailTo  = document.getElementById("emailTo").value;
             var ccTo  = document.getElementById("ccTo").value;

             window.location.href = "../../phpfunctions/purchaseOrder.php?emailPO=1" + "&poId=" + poId + "&emailTo=" + emailTo + "&ccTo=" + ccTo;
           }

           function checkEmailFormat(check){
             if (check == "email") {
               var email = document.getElementById("emailTo").value;
               var fixEmail = email.replace(/[ ,]+/g, ",");
               console.log(fixEmail);
               document.getElementById("emailTo").value = fixEmail;
             }else if(check == "cc") {
               var email = document.getElementById("ccTo").value;
               var fixEmail = email.replace(/[ ,]+/g, ",");
               document.getElementById("ccTo").value = fixEmail;
             }

           }
           </script>
           <div class="modal-footer">
              <input type="number" id="poId" hidden>

              <!-- EMAIL -->
              <button data-toggle="modal" data-target="#emailPOModal" type="button" class="btn btn-primary">
              <i class="fa fa-mail" aria-hidden="true"></i>
                Email
              </button>

              <!-- REMOVE -->
              <button onclick="checkRemovePO()" type="button" class="btn btn-primary">
              <i class="fa fa-trash" aria-hidden="true"></i>
                Remove
              </button>

              <!-- CLOSE -->
              <button type="button" class="btn btn-primary" title="CLOSE DIALOG" data-dismiss="modal">
              <i class="fa fa-times" aria-hidden="true"></i>
                Close
              </button>
           </div>
         </div>
       </div>
  </div>

  <!-- PO Modal START-->
  <div class="modal fade" id="emailPOModal" tabindex="-1" role="dialog" aria-labelledby="emailPOModal" aria-hidden="true">
       <div class="modal-dialog modal-dialog-centered modal-full" role="document">
         <div class="modal-content">
           <div class="modal-header">
             <h5 class="modal-title">Email Purchase Order</h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
             </button>
           </div>
           <div class="modal-body">
             <div class="form-group row">
               <div class="col-sm-12">
                 <small><i>Sepereate with comma "," if more that one emails</i></small>
                 <form class="" action="index.html" method="post">
                   <!-- Email To -->
                   <div class="col-sm-12">
                     <label for="emailTo" class="col-form-label col-form-label-lg">Email To</label>
                     <input oninput="checkEmailFormat('email')" class="form-control" id="emailTo" type="email" name="emailTo" multiple>
                   </div>

                   <!-- CC To -->
                   <div class="col-sm-12">
                     <label for="ccTo" class="col-form-label col-form-label-lg">CC To</label>
                     <input oninput="checkEmailFormat('cc')" class="form-control" id="ccTo" type="email" name="ccTo" multiple>
                   </div>
                 </form>
               </div>
             </div>
           </div>
           <div class="modal-footer">
             <button class="btn btn-primary btn-lg btn-block" onclick="emailPO()" type="button" name="button">Send</button>
           </div>
         </div>
       </div>
  </div>
</body>
</html>
