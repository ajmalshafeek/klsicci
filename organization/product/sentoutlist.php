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
<!DOCTYPE html >

<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">


    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />


    <?php
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>
    <!-- datatable -->
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/jquery.dataTables.js'></script>
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/dataTables.bootstrap4.js'></script>
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/js/sb-admin-datatables.min.js'></script>
    <script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js" ></script>
    <!-- datatable -->
    <?php  if($_SESSION['orgType']==7){ ?>
      <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.12.4.js"></script>
  <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
    <?php } ?>
    <script>

      function editProduct(id){
$('#sentout').css("display","none");
        $.ajax({

            type  : 'GET',
            url  : '../../phpfunctions/product.php?',
            data : {productDataSent:id},
            success: function (data) {
            //details= JSON.parse(data);
             //  console.log(data);
                $('.tableDetails').html(data);
          }
        });
      }


      function clientDelete(str){

        $("#clientIdToDelete").val(str.value);

      }

      function clientEdit(str){

        $("#clientIdToEdit").val(str.value);

      }

      function showPassword(pwdId) {

        var x = document.getElementById(pwdId);
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }

    }
        <?php if($_SESSION['orgType']==7){ ?>
        $(document).ready(function() {
 $("#producttype").on('change',function(){
  if($("#producttype").val().toLowerCase()=="ram"||
     $("#producttype").val().toLowerCase()=="hdd"||
     $("#producttype").val().toLowerCase()=="hard drive"||$("#producttype").val().toLowerCase()=="hardrive"||
     $("#producttype").val().toLowerCase()=="ssd"||
     $("#producttype").val().toLowerCase()=="pendrive"||
     $("#producttype").val().toLowerCase()=="ped drive"||
     $("#producttype").val().toLowerCase()=="solid state drive"){
            $("#capacitysection").css("display","revert");
    }else{  $("#capacitysection").css("display","none"); }                      
 }); 
             $("#sproducttype").on('change',function(){
  if($("#sproducttype").val().toLowerCase()=="ram"||
     $("#sproducttype").val().toLowerCase()=="hdd"||
     $("#sproducttype").val().toLowerCase()=="hard drive"||
     $("#sproducttype").val().toLowerCase()=="hardrive"||
     $("#sproducttype").val().toLowerCase()=="ssd"||     $("#sproducttype").val().toLowerCase()=="pendrive"||
     $("#sproducttype").val().toLowerCase()=="ped drive"||
     $("#sproducttype").val().toLowerCase()=="solid state drive"){
            $("#scapacity").attr('disabled', false);
    }else{  $("#scapacity").attr('disabled', true); }                      
 });
 $("#scapacity").attr('disabled', true);
        
        });
        <?php } ?>

        $(document).ready( function () {
    $('#dataTable').DataTable();
} );
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
   table, th, td {
  border: 1px solid black;padding: 5px;
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
        <li class="breadcrumb-item ">Product</li>
        <li class="breadcrumb-item active">Sent Out List</li>
      </ol>
     </div>
    
      <?php
            if (isset($_SESSION['feedback'])) {
                echo $_SESSION['feedback'];
                unset($_SESSION['feedback']);
            }
        ?>
    <?php  if($_SESSION['orgType']==7){ /*?>
    <form action="../../phpfunctions/product.php" method="post">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-2">Date :</div><div class="col-sm-4"><input type="date" name="sdate" class="form-control"></div>
            <div class="col-sm-2">PO number :</div><div class="col-sm-4"><input type="text" name="sponumber" class="form-control"></div>
        </div>
        </div>
        <div class="container-fluid">
        <div class="row">
            <div class="col-sm-2">Type :</div><div class="col-sm-4">
            <input id="sproducttype" name="sproducttype" class="form-control" /></div>
            <div class="col-sm-2">Brand :</div><div class="col-sm-4">
            <input id="sbrand" name="sbrand" class="form-control" /></div>
        </div>
        </div>
            <div class="container-fluid">
        <div class="row">
            <div class="col-sm-2">Capacity :</div><div class="col-sm-4"><select name="scapacity"  id="scapacity" class="form-control" >
                        <option value="" selected >--Select Capacity--</option>
                          <option  value="512MB">512MB</option>
                          <option  value="1GB">1GB</option>
                          <option  value="2GB">2GB</option>
                          <option  value="4GB">4GB</option>
                          <option  value="8GB">8GB</option>
                          <option  value="16GB">16GB</option>
                          <option  value="32GB">32GB</option>
                          <option  value="64GB">64GB</option>
                          <option  value="128GB">128GB</option>
                          <option  value="256GB">256GB</option>
                          <option  value="512GB">512GB</option>
                          <option  value="1TB">1TB</option>
                          <option  value="2TB">2TB</option>
                          <option  value="3TB">3TB</option>
                          <option  value="4TB">4TB</option>
                          <option  value="6TB">6TB</option>
                          <option  value="8TB">8TB</option>
                      </select></div>
            <div class="col-sm-2">Serial Number :</div><div class="col-sm-4"><input type="text" name="sserial" id="sserial" class="form-control"></div>
        </div>
        </div>
        <div class="container-fluid">
            <div class="row">
            <div class="col-sm-12">
                <button type="submit" class="btn" name="search" style="float: right;">Search</button>
                </div>
            </div>
        </div>
    </form> 
    <?php */} ?>
        <div class='card mb-3'>
		      <div class='card-header'>
					  <i class='fa fa-table'></i>
            Product List
				  </div>
            <div><?php
                $pdf = "<a href='pdf.php'   target='_blank'><button class='btn'><i class=\"fa fa-file-pdf-o\" ></i> PDF</button></a> ";
                $excel = "<a href='excel.php' target='_blank'><button class='btn'><i class=\"fa fa-file-excel-o\" ></i> Excel</button></a>";
          echo $pdf.$excel?></div> 
          <?php
            productSentListTable();
          ?>

          </div>
        </div>
   <form method="POST">

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

          <center><table style="width:100%" class="tableDetails">
           
            </table>
            </center>               
              
              
          </div>


     
      <div class="modal-footer">
        <?php /*  <input type="text" hidden name="productIdToEdit" id="productIdToEdit" value=""  />
          <button type="submit" name='editProductId' class="btn btn-primary" >Edit</button>
          <button type="submit" name='setOut' id="sentout" class="btn btn-primary" >Sent Out</button> 
          <button type="submit" name='removeProduct' class="btn btn-primary" >Remove</button> */ ?>
          <a name='printsent' id="printsent" class="btn btn-primary" href="./printpdf.php" target="_blank" >Print</a>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
<?php  if($_SESSION['orgType']==7){ ?>
<script>
$( "#sbrand" ).autocomplete({
  source: <?php brandListJson(); ?>
});
    
    $( "#sproducttype" ).autocomplete({
  source: <?php productTypeListJson(); ?>
});
    <?php } ?>
</script>
</body>
</html>
