<?php
 $config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
 if(!isset($_SESSION))
 {
  session_name($config['sessionName']);
	session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/product.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientCompany.php");

?>
<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />


    <?php
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>
    <!-- datatable -->
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/jquery.dataTables.js'></script>
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/dataTables.bootstrap4.js'></script>
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/js/sb-admin-datatables.min.js'></script>
    <!-- datatable -->
    <script>
      function updateStoreOrder(id){
$('#sentout').css("display","none");
          $('.notes').css("display","none");
          $('.mgrnotes').css("display","none");
        $.ajax({
            type  : 'GET',
            url  : '../../phpfunctions/product.php?',
            data : {productOrderData:id},
            success: function (data) {
          //  details= JSON.parse(data);
                console.log(data);
                $('#loadBtn').attr("disabled","disabled");
                $('.orderIdToUpdate').val(id);
                $('.orderItemList').html(data);
                $.ajax({
                    type  : 'GET',
                    url  : '../../phpfunctions/product.php?',
                    data : {storeOrderNotes:id},
                    success: function (data) {
                        details= JSON.parse(data);
                        console.log(data);
                        var notes=details.notes;
                        $('.orderStatus').val(details.status);
                        if(notes!=null){
                        $('.notes').html("Client's Notes:<br/>"+notes);
                            document.getElementsByClassName("notes")[0].style.display="block";
                        }
                        var mgr=details.mgr;
                        if(mgr!=null){
                        $('.mgrnotes').html("Manager Notes:<br/>"+mgr);
                           document.getElementsByClassName("mgrnotes")[0].style.display="block";
                        }
                    }
                    });
            }
        });
      }
      function clientDelete(str){
        $("#clientIdToDelete").val(str.value);
      }
      function clientEdit(str){
        $("#clientIdToEdit").val(str.value);
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
table label {
    font-size: 1.2em;
    width: 100%;
}

     .loader {
         border: 16px solid #f3f3f3;
         border-radius: 100%;
         border-top: 16px solid #10ADADFF;
         width: 20px;
         height: 20px;
         -webkit-animation: spin 2s linear infinite; /* Safari */
         animation: spin 2s linear infinite;
         top: calc(50% - 15px) !important;
         left: calc(50% - 15px) !important;
         padding: 30px !important;
     }
    table thead,table tr ,table tr th,table tr td{
             border-bottom: 1px solid #000000 !important;
         }
    /* Safari */
    @-webkit-keyframes spin {
        0% { -webkit-transform: rotate(0deg); }
        100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
.notes, .mgrnotes{
    padding: 10px;
    border: 1px solid #dbdbdb;
    background-color: antiquewhite;
    margin-bottom: 20px;
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
        <li class="breadcrumb-item ">Store</li>
        <li class="breadcrumb-item active">Order Details</li>
      </ol>
     </div>
    
      <?php
            if (isset($_SESSION['feedback'])) {
                echo $_SESSION['feedback'];
                unset($_SESSION['feedback']);
            }
        ?>
    <form action="../../phpfunctions/product.php" method="post">
    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-4"><label>Order Id :</label><input type="number" name="oid" class="form-control"></div>
            <div class="col-sm-4"><label>Date :</label><input type="date" name="sdate" class="form-control"></div>
            <div class="col-sm-4"><label >Status :</label>
                <select name="status" class="form-control">
                    <option disabled selected>--Select--</option>
                    <option value="0">All Order</option>
                    <option value="1">Received Order</option>
                    <option value="2">Processed Order</option>
                    <option value="3">Shipped Order</option>
                    <option value="4">Delivered Order</option>
                    <option value="-1">Canceled Ordered</option>
                </select></div>
        </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <button type="submit" class="btn" name="searchStore" style="float: right;">Search</button>
                </div>
            </div>
        </div>
    </form>
        <div class='card mb-3'>
		      <div class='card-header'>
					  <i class='fa fa-table'></i>
            Order List
				  </div>
            <div><?php
                $pdf = "<a href='pdf.php'   target='_blank'><button class='btn'><i class=\"fa fa-file-pdf-o\" ></i> PDF</button></a> ";
                $excel = "<a href='excel.php' target='_blank'><button class='btn'><i class=\"fa fa-file-excel-o\" ></i> Excel</button></a>";
          echo $pdf.$excel?></div> 
          <?php
          productStoreListTable();
          ?>
          </div>
        </div>
   <form method="POST" action="<?php echo "https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/phpfunctions/product.php" ?>" >
<div class="modal fade" id="productEditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="productEditModalTitle">Product Details</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="orderItemList"></div>
            <div class="notes"></div>
            <div class="mgrnotes"></div>
            <div class="form-group">
                <select name="orderStatus" class="form-control orderStatus">
                    <option value="1">Received Order</option>
                    <option value="2">Processed Order</option>
                    <option value="3">Shipped Order</option>
                    <option value="4">Delivered Order</option>
                    <option value="-1">Canceled Order</option>
                </select>
            </div>
            <div class="form-group" id="assignto">
                <label for="email" >Assign To</label>
                <select name='workerType' class='form-control' id='workerType' onchange="changeWorkType(this.value)" >
                    <option value="0" disabled selected>--Select--</option>
                    <option value="myStaff">My Staff</option>
                    <option value="vendors">Vendors</option>
                </select>
            </div>
            <div class="form-group" class="noteblock" id="noteblock" style="display:none;">
                <label for="email" >Cancel Email Note</label>
                <input type="text" name="note" class='form-control' id="note">
            </div>

            <div id='selectWorker'></div>
            <div class="form-group" class="noteblock" id="noteblock">
                <label for="email" >Manager Notes:</label>
                <input type="text" name="managernote" class='form-control' id="note">
            </div>
          <div class="modal-footer">
          <input type="text"  name="orderIdToUpdate" class="orderIdToUpdate" value=""  hidden />
              <button type="submit" name='updateStoreNote' class="btn btn-primary edit"  class="updateStatus"
              >Add Notes</button>
          <button type="submit" name='updateStoreOrder' class="btn btn-primary remove" id="loadBtn" class="updateStatus"
          >Update Status</button>
          <button type="button" class="btn btn-secondary cancel" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</form>
</div></div>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fa fa-angle-up"></i>
    </a>

          <div class="footer">
            <p>Powered by JSoft Solution Sdn. Bhd</p>
          </div>
  </div>
<div class="center text-center " id="bloader" style="padding: 40VH; width: 100%; height: 100VH;z-index: -9999999;background-color:#ffffffa3;position: fixed;top: 0;left: 0"><div class="loader"></div></div>
<script>
    function changeWorkType(str){
        $.ajax({

            type  : 'GET',
            url  : '../../phpfunctions/clientComplaint.php?',
            data : {workerType:str},
            success: function (data) {
                var worker = $('#selectWorker');
                worker.empty().append(data);
                var noOfList=0;
                if(document.getElementById("workerId")){
                noOfList=document.getElementById("workerId").length;}
                else{
                    noOfList=0;
                }
                console.log(noOfList);
                if(noOfList>0 && noOfList!=null){
                    document.getElementById("assignWorker").disabled = false;
                    document.getElementById("assignWorker").classList.remove('btn-light');
                    document.getElementById("assignWorker").classList.add('btn-primary');
                }
                $('#workerType').val(0);
            }

        });

    }
    $(document).ready(function() {
        document.getElementById("bloader").style.display="none";
    $('#bloader').css("display","none;");
$('#loadBtn').on('click',function (){
    $('#bloader').css("z-index","9999999");
    $('#bloader').css("display","block");
    document.getElementById("bloader").style.display="block";
});
        document.getElementById("noteblock").style.display="none";
$('.orderStatus').on("change",function () {
    var check=$('.orderStatus').val();
    $("#workerType").val("--Select--");
    $("#selectWorker").empty();
    var value=-1;
    if(check==value){
      document.getElementById("noteblock").style.display="block";
      if(document.getElementById("assignto")){
      document.getElementById("assignto").style.display="none";}
      if(document.getElementById("workerId")){
      document.getElementById("workerId").style.display="none";}
        document.getElementById("loadBtn").setAttribute("disabled","disabled");

    } else if(check==4) {
        document.getElementById("noteblock").style.display = "none";
        if (document.getElementById("assignto")) {
            document.getElementById("assignto").style.display = "none";
        }
        if (document.getElementById("workerId")) {
            document.getElementById("workerId").style.display = "none";
        }
        document.getElementById("loadBtn").removeAttribute("disabled");
    } else{
        document.getElementById("noteblock").style.display="none";
        document.getElementById("assignto").style.display="block";
        document.getElementById("loadBtn").setAttribute("disabled","disabled");
        $('#workerType').val(0);
    }
});
$("#note").on("keyup",function () {
    var note=$("#note").val();
    if(note.length>5){
        document.getElementById("loadBtn").removeAttribute("disabled");
    }
});
$("#workerType").on("change",function () {
    document.getElementById("loadBtn").removeAttribute("disabled");
});

        $('#OrderTable').DataTable( {
            "order": [[ 0, "desc" ]]
        } );

    });
</script>
</body>
</html>