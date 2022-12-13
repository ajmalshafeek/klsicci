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
    <link rel="stylesheet" href="../css/bootstrap.min.css">
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

      function editProduct(id){

        $.ajax({

            type  : 'GET',
            url  : '../../phpfunctions/product.php?',
            data : {productData:id},
            success: function (data) {
            details= JSON.parse(data);
                console.log(data);
            $('#nbrand').html(details.brand==""?"&nbsp;":details.brand);
            $('#model').html(details.model==""?"&nbsp;":details.model);
            $('#serialNum').html(details.serialNum==""?"&nbsp;":details.serialNum);
            $('#remarks').html(details.remarks==""?"&nbsp;":details.remarks);
            $('#productIdToEdit').val(id);
            $('#producttype'). html(details.producttype==""?"&nbsp;":details.producttype);    
            <?php if($_SESSION['orgType']==7){ ?>
                
            $('#statusvalue').html(details.status==""?"&nbsp;":details.status);
            $('#location').html(details.location==""?"&nbsp;":details.location);
            $('#ponumber').html(details.ponumber==""?"&nbsp;":details.ponumber);
            $('#capacity').html(details.capacity==""?"&nbsp;":details.capacity);
            if(details.producttype.toLowerCase()=="ram"||
               details.producttype.toLowerCase()=="hdd"||
               details.producttype.toLowerCase()=="hard drive"||               details.producttype.toLowerCase()=="hardrive"||               details.producttype.toLowerCase()=="ssd"||
               details.producttype.toLowerCase()=="pendrive"||
               details.producttype.toLowerCase()=="pen drive"||
               details.producttype.toLowerCase()=="solid state drive"){
            $("#capacitysection").css("display","revert");
    }else{  $("#capacitysection").css("display","none"); }
                
                    if(details.status=="Send Out"){
            $(".instock").css("display","revert"); 
                       
                        var texta="<div class=\"form-group row\"><label class=\"col-sm-4 col-form-label col-form-label-lg\">D/O Number</label><div class=\"col-sm-8\"><label id=\"nbrand\" class=\"form-control bor\" required >"+details.donumber+"&nbsp;</label></div>";
                        texta+="<label class=\"col-sm-4 col-form-label col-form-label-lg\">Date</label><div class=\"col-sm-8\"><label id=\"nbrand\" class=\"form-control bor\" required >"+details.dodate+"&nbsp;</label></div>";
                        texta+="<label class=\"col-sm-4 col-form-label col-form-label-lg\">To</label><div class=\"col-sm-8\"><label id=\"nbrand\" class=\"form-control bor\" required >"+details.doto+"&nbsp;</label></div>";
                        texta+="<label class=\"col-sm-4 col-form-label col-form-label-lg\">In Charge</label><div class=\"col-sm-8\"><label id=\"nbrand\" class=\"form-control bor\" required >"+details.doincharge+"&nbsp;</label></div>";
                        $(".instock .details").html(texta);
                }
                else{
                    $(".instock").css("display","none");
                }
                
                if(details.status==""||details.status==null){
                   $('.stockstatus').css("display","none");
                   }
                else{
                    $('.stockstatus').css("display","revert");
                }
                
                <?php } ?>
            if(details.remarks==""||details.status!=null){$(".rremarks").css("display","none");}
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
  if($("#producttype").val().toLowerCase()=="ram"||$("#producttype").val().toLowerCase()=="hdd"||$("#producttype").val().toLowerCase()=="hard drive"||$("#producttype").val().toLowerCase()=="hardrive"||$("#producttype").val().toLowerCase()=="ssd"||$("#producttype").val().toLowerCase()=="pendrive"||$("#producttype").val().toLowerCase()=="ped drive"||$("#producttype").val().toLowerCase()=="solid state drive"){
            $("#capacitysection").css("display","revert");
    }else{  $("#capacitysection").css("display","none"); }                      
 }); 
 });
        <?php } ?>
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
        <li class="breadcrumb-item active">View Product</li>
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
            Product List
				  </div>
          <?php
            productListTable();
          ?>

          </div>
        </div>
    </div>
<!--
    <form method="POST" action="<?php echo "https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/phpfunctions/clientCompany.php" ?>" >

<div class="modal fade" id="clientDeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="clientDeleteModalTitle">REMOVE CLIENT</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

            <div id='staffDeleteContent' >
              Are you sure want to remove client ?
            </div>

          <div class="modal-footer">
            <input type="text" hidden name="clientIdToDelete" id="clientIdToDelete" value=""  />

            <button type="submit" name='removeClient' class="btn btn-primary" >YES</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">NO</button>
          </div>
        </div>
      </div>
    </div>
  </form> -->

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

          <table style="width:100%">
            <tr class="stockstatus">
                <td width="60px;"><label>Status</label></td><td><label id="statusvalue">Status</label></td>
              </tr>
              <tr class="brandsec">
                <td><label>Brand</label></td><td><label id="nbrand">Status</label></td>
              </tr>
              <tr class="typesec">
                <td><label>Type</label></td><td><label id="producttype">Status</label></td>
              </tr>
              <tr class="modelsec">
                <td><label>Model</label></td><td><label id="model">Status</label></td>
              </tr>
              <tr class="capacitysec">
                <td><label>Capacity</label></td><td><label id="capacity">Status</label></td>
              </tr>
            </table>
              <div class="row stockstatus">
              <label>Status</label>
              <div class="col-sm-8">
                <label id="statusvalue" class="form-control bor" required >&nbsp;
                </label>
                <div class="invalid-feedback">
                </div>
              </div>        
            </div>
            <!--BRAND-->
            <div class="row">
              <label class="col-sm-4 col-form-label col-form-label-lg">Brand</label>
              <div class="col-sm-8">
                <label id="nbrand" class="form-control bor" required >&nbsp;
                </label>
                <div class="invalid-feedback">
                </div>
              </div>        
            </div>
            <!--(END)BRAND-->

                        <!--Product Type-->
            <div class="row">
              <label class="col-sm-4 col-form-label col-form-label-lg">Type</label>
              <div class="col-sm-8">
                <label id="producttype" class="form-control bor" required >&nbsp;
                </label>
                <div class="invalid-feedback">
                </div>
              </div>
            </div>
            <!--(END)Product Type--> 
       
            <!--MODEL-->
            <div class="row">
              <label class="col-sm-4 col-form-label col-form-label-lg">Model</label>
              <div class="col-sm-8">
                <label id="model" class="form-control bor" required >&nbsp;
                </label>
                <div class="invalid-feedback">
                </div>
              </div>
            </div>
            <!--(END)MODEL-->
         <?php if($_SESSION['orgType']==7){ ?>
         <!--capacity-->
            <div class="row" id="capacitysection">
              <label class="col-sm-4 col-form-label col-form-label-lg">Capacity</label>
              <div class="col-sm-8">
                <label id="capacity" class="form-control bor" required >&nbsp;
                </label>
                <div class="invalid-feedback">
                </div>
              </div>
            </div>
        <!--Location-->
            <div class=" row">
              <label class="col-sm-4 col-form-label col-form-label-lg">Location</label>
              <div class="col-sm-8">
                <label id="location" class="form-control bor" required >&nbsp;
                </label>
                <div class="invalid-feedback">
                </div>
              </div>
            </div>
            <!--(END)Product Type-->
    <!--Product Type-->
            <div class=" row">
              <label class="col-sm-4 col-form-label col-form-label-lg">PO NO</label>
              <div class="col-sm-8">
                <label id="ponumber" class="form-control bor" required >&nbsp;
                </label>
                <div class="invalid-feedback">
                </div>
              </div>
            </div>
            <!--(END)Product Type-->
        <?php } ?>
            <!--SERIAL NUMBER-->
            <div class="row">
              <label class="col-sm-4 col-form-label col-form-label-lg">Serial Number</label>
              <div class="col-sm-8">
                <label id="serialNum" class="form-control bor" required >&nbsp;
                </label>
                <div class="invalid-feedback">
                </div>
              </div>
            </div>
            <!--(END)SERIAL NUMBER-->
            <!--REMARKS-->
            <div class="row rremarks">
              <label class="col-sm-4 col-form-label col-form-label-lg">Remarks</label>
              <div class="col-sm-8">
                <label id="remarks" class="form-control bor" required >&nbsp;
                </label>
                <div class="invalid-feedback">
                </div>
              </div>
            </div>
            <!--(END)REMARKS-->

            <div class="instock" style="display: block !important;">
                   <h5>Sent Out Details</h5><h5>&nbsp;</h5>
                <div class="container details" style="">
                    <!--REMARKS-->
            <div class="row rremarks">
              <label class="col-sm-4 col-form-label col-form-label-lg">Remarks</label>
              <div class="col-sm-8">
                <label id="remarks" class="form-control bor" required >&nbsp;
                </label>
                <div class="invalid-feedback">
                </div>
              </div>
            </div>
            <!--(END)REMARKS-->
                </div>
              </div>
              
          </div>


      </div>
      <div class="modal-footer">
          <input type="text" hidden name="productIdToEdit" id="productIdToEdit" value=""  />
          <button type="submit" name='editProductId' class="btn btn-primary" >Edit</button>
          <button type="submit" name='removeProduct' class="btn btn-primary" >Remove</button>
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

</body>
</html>
