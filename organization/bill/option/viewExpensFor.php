<?php
$config=parse_ini_file(__DIR__."/../../../jsheetconfig.ini");
if(!isset($_SESSION))
{
 session_name($config['sessionName']);
   session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/bill.php");

?>
<!DOCTYPE html >

<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />
<!--  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css"/> -->
   <!--  <link rel="stylesheet" href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/css/buttons.dataTables.min.css"/> -->
   <!-- <link rel="stylesheet" href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/css/dataTables.min.css"/>-->
    <link rel="stylesheet" href="../css/bootstrap.min.css">

  <!--
      <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <script src="../../js/bootstrap.min.js" ></script>

    <script type="text/javascript" src="../../js/jquery-3.3.1.min.js"></script>
-->
<?php
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>
    <!-- datatable -->
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/jquery.dataTables.js'></script>
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/dataTables.bootstrap4.js'></script>
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/js/sb-admin-datatables.min.js'></script>
    <!--<script src='https://code.jquery.com/jquery-3.3.1.js'></script>
     <script src='https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js'></script> -->
    <script src='https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js'></script>
    <script src='https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js'></script>
    <script src='https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js'></script>
    <script src='https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js'></script>

        <script>
<?php
/*
            $(document).ready(function() {
                $('#dataTable').DataTable( {
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'excelHtml5',text: '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Download Excel',
                            exportOptions: {
                                columns: ':visible'
                            }
                        }
                    ]
                } );
            } );
            */
?>
     function staffDelete(str){

       $("#staffIdToDelete").val(str.value);

     }

     function expenseForCategoryEdit(str){

       $("#billExpenseForIdToEdit").val(str.value);

     }

    </script>
    <style>
    .buttonAsLink{
      background:none!important;
      color:inherit;
      border:none;
      font: inherit;
      cursor: pointer;
    }

            .bg-red{
                background-color: #E32526;
            }
    .dt-button{
        margin: 5px 0px 0px 10px;
        color:white;
        background: #8A0808;
        border:0px;
        padding: 10px;
        border-radius: 5px;
    }

    #dataTable_paginate{
        color:black !important;
    }

    #dataTable2_filter{
        display:none;
    }

    #dataTable2{
        display:none;
    }

    #dataTable2_info{
        display:none;
    }

    #dataTable2_paginate{
        display:none;
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
        <li class="breadcrumb-item active">Expenses</li>
        <li class="breadcrumb-item active">View Expense For</li>

      </ol>
    </div>

      <?php
            if (isset($_SESSION['feedback'])) {
                echo $_SESSION['feedback'];
                unset($_SESSION['feedback']);
            }
        ?>
        <div class='card mb-3'>
		      <div class='card-header'>
					  <i class='fa fa-table'></i>
            Expense For List
				  </div>
          <div class='card-body'>
          <?php
          billExpenseForListTableEditable();
          ?>

          </div>
        </div>


     </div>
    <form method="POST" action="../../../phpfunctions/bill.php" >

    <div class="modal fade" id="billExpenseForEditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="productTypeEditModalTitle">ACTIONS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">

                <div id='staffEditContent' >
                  What action do you wish to do?
                </div>
            </div>
              <div class="modal-footer">
                <input type="text" hidden name="billExpenseForIdToEdit" id="billExpenseForIdToEdit" value=""  />
                <button type="submit" name='editBillExpenseFor' class="btn btn-primary" >Edit</button>
                <button type="submit" name='removeBillExpenseFor' class="btn btn-primary" >Remove</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
              </div>
          </div>
        </div>
      </div>
      </form>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fa fa-angle-up"></i>
    </a>

       <div class="footer">
            <p>Powered by JSoft Solution Sdn. Bhd</p>
            </div>
</body>
</html>
