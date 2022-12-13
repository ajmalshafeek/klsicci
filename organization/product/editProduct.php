<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
  session_name($config['sessionName']);
  session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/product.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientCompany.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/vendor.php");

if(isset($_SESSION['productIdToEdit'])){
    $productid=$_SESSION['productIdToEdit'];
}
?>
<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">


    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />

   <?// <link rel='stylesheet' type='text/css' href='../css/myQuotationStyle.css' /> ?>
    <?php
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>
    <script>
      (function() {
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
        $(document).ready(function() {
            $("#capacitysection").css("display","none");
 $("#producttype").on('change',function(){
  if($("#producttype").val().toLowerCase()=="ram"||$("#producttype").val().toLowerCase()=="hdd"||$("#producttype").val().toLowerCase()=="hard drive"||$("#producttype").val().toLowerCase()=="hardrive"||$("#producttype").val().toLowerCase()=="ssd"||$("#producttype").val().toLowerCase()=="pendrive"||$("#producttype").val().toLowerCase()=="pen drive"||$("#producttype").val().toLowerCase()=="solid state drive"){
            $("#capacitysection").css("display","flex");
    }else{  $("#capacitysection").css("display","none"); }                      
 }); 
 });
       
        var id=<?php echo $_SESSION['productIdToEdit']; ?>;
        var delay=10;
        $.ajax({

            type  : 'GET',
            url  : '../../phpfunctions/product.php?',
            data : {productData:id},
            success: function (data) {
                
            details= JSON.parse(data);
                console.log(data);
                <?php if($_SESSION['orgType']!=3){ ?>
            $('#nbrand').val(details.brand);
            $('#model').val(details.model);
            <?php } ?>

            <?php if($_SESSION['orgType']!=8&&$_SESSION['orgType']!=3){ ?>
            $('#serialNum').val(details.serialNum);
            $('#remarks').val(details.remarks);
            <?php } ?>
                <?php if($_SESSION['orgType']==8 || $_SESSION['orgType']==3){ ?>
                $('#title').val(details.title==""?"&nbsp;":details.title);
                $('#des').val(details.description==""?"&nbsp;":details.description);
                $('#price').val(details.price==""?"&nbsp;":details.price);
                $('#qty').val(details.quantity==""?"&nbsp;":details.quantity);
                $('#sku').val(details.sku==""?"&nbsp;":details.sku);
                <?php if($_SESSION['orgType']==3){ ?>
                $('#clientCompanyId').val(details.cid==""?"&nbsp;":details.cid);
                <?php } ?>
                <?php } ?>
            $('#productIdToEdit').val(id);
            $('#producttype').val(details.producttype);    
            <?php if($_SESSION['orgType']==7){ ?>
            $('#location').val(details.location);
            $('#ponumber').val(details.ponumber);
            $('#capacity').val(details.capacity);
                setTimeout(function() {
            if(details.producttype.toLowerCase()=="ram"||
               details.producttype.toLowerCase()=="hdd"||
               details.producttype.toLowerCase()=="hard drive"||               
               details.producttype.toLowerCase()=="hardrive"||               
               details.producttype.toLowerCase()=="ssd"||
               details.producttype.toLowerCase()=="pendrive"||
               details.producttype.toLowerCase()=="pen drive"||
               details.producttype.toLowerCase()=="solid state drive"){
            $("#capacitysection").css("display","flex");
    }else{  $("#capacitysection").css("display","none"); }
                },delay);
<?php } ?>
            }
        });
      <?php if($_SESSION['orgType']==7){ ?>
        $(document).ready(function() {
 $("#producttype").on('change',function(){
  if($("#producttype").val().toLowerCase()=="ram"||$("#producttype").val().toLowerCase()=="hdd"||$("#producttype").val().toLowerCase()=="hard drive"||$("#producttype").val().toLowerCase()=="hardrive"||$("#producttype").val().toLowerCase()=="ssd"||$("#producttype").val().toLowerCase()=="pendrive"||$("#producttype").val().toLowerCase()=="ped drive"||$("#producttype").val().toLowerCase()=="solid state drive"){
            $("#capacitysection").css("display","flex");
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
           /*
           a.buttonNav {
                -webkit-appearance: button;
                -moz-appearance: button;
                appearance: button;
                text-decoration: none;
                color: white;
                background-color:red;
            }
            */
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
        <li class="breadcrumb-item active">Edit Product</li>
      </ol>
    </div>
      <div class="container">
            <form method="POST" action="../../phpfunctions/product.php" class="needs-validation" enctype="multipart/form-data" novalidate >
            <?php
                  if (isset($_SESSION['feedback'])) {
                      echo $_SESSION['feedback'];
                      unset($_SESSION['feedback']);
                  }
              ?>
              <div id="productForm">
                <!--(START)PRODUCT FORM-->
                  <?php $temp="Product Type";  if($_SESSION['orgType']==8||$_SESSION['orgType']==3){ $temp="Category"; ?>
                       <div class="form-group row">
                          <label for="title" class="col-sm-2 col-form-label col-form-label-lg">Title</label>
                          <div class="col-sm-10"   >
                              <input class="form-control" type="text" id="title" name="title" required>
                              <div class="invalid-feedback">
                                  Please enter title
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="des" class="col-sm-2 col-form-label col-form-label-lg">Description</label>
                          <div class="col-sm-10"   >
                              <input class="form-control" type="text" id="des" name="des" required>
                              <div class="invalid-feedback">
                                  Please enter description
                              </div>
                          </div>
                      </div>
                      <?php  if($_SESSION['orgType']!=3){ ?>
                      <div class="form-group row">
                          <label for="sku" class="col-sm-2 col-form-label col-form-label-lg">SKU</label>
                          <div class="col-sm-10"   >
                              <input class="form-control" type="text" id="sku" name="sku" required>
                              <div class="invalid-feedback">
                                  Please enter sku
                              </div>
                          </div>
                      </div>
                          <?php } ?>
                      <div class="form-group row">
                          <label for="qty" class="col-sm-2 col-form-label col-form-label-lg">Quantity</label>
                          <div class="col-sm-4"   >
                              <input class="form-control" type="text" id="qty" name="qty" required>
                              <div class="invalid-feedback">
                                  Please enter Quantity
                              </div>
                          </div>
                          <label for="price" class="col-sm-2 col-form-label col-form-label-lg">Price</label>
                          <div class="col-sm-4"   >
                              <input class="form-control" type="number" id="price" name="price" step="0.01" required>
                              <div class="invalid-feedback">
                                  Please enter price
                              </div>
                          </div>
                      </div>

                  <?php } ?>
                  <?php  if($_SESSION['orgType']!=3){ ?>
                  <!--(START)BRAND-->
                  <div class="form-group row">
                    <label for="brand" class="col-sm-2 col-form-label col-form-label-lg">Brand</label>
                    <div class="col-sm-10"   >
                      <select name="brand"  id="nbrand" class="form-control" required >
                        <option  value="" selected disabled >--Select Brand--</option>
                        <?php brandList(); ?>
                      </select>
                      <div class="invalid-feedback">
                      Please choose brand
                    </div>
                    </div>
                  </div>
                  <?php } ?>
                  <div class="form-group row">
                      <label for="producttype" class="col-sm-2 col-form-label col-form-label-lg"><?php echo $temp ?></label>
                      <div class="col-sm-10" >
                          <select name="producttype"  id="producttype" class="form-control" required >
                              <option  value="" selected disabled >--Select <?php echo $temp ?>--</option>
                              <?php productTypeList(); ?>
                          </select>
                          <div class="invalid-feedback">
                              Please select a <?php echo strtolower($temp); ?>
                          </div>
                      </div>
                  </div>
                  <?php if($_SESSION['orgType']==3){ ?>
                      <div class="form-group row">
                          <label for="clientCompanyId" class="col-sm-2 col-form-label col-form-label-lg">Client</label>
                          <div class="col-sm-10">
                              <?php require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientCompany.php");
                              dropDownListOrganizationClientCompanyActive3();   ?>
                              <div class="invalid-feedback">
                                  Please select client
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="doc" class="col-sm-2 col-form-label col-form-label-lg">Document</label>
                          <div class="col-sm-10">
                              <div style="font-size: 1em" class="py-1">upload document if want to replace </div>
                              <input class="form-control" type="file" name="doc" onchange="fileDocValidation(this)" accept="application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/msexcel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                              <small>upload doc, docx, xls, xlsx and pdf format only</small>
                              <div class="invalid-feedback">
                                  Please enter file
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="img1" class="col-sm-2 col-form-label col-form-label-lg">Image</label>
                          <div class="col-sm-10">
                              <div style="font-size: 1em" class="py-1">upload cover image if want to replace </div>
                              <input class="form-control" type="file" name="img1" onchange="fileJPGValidation(this)" accept="image/jpeg">
                              <div class="invalid-feedback">
                                  Please enter image
                              </div>
                          </div>
                      </div>
                  <?php } ?>


                  <?php  if($_SESSION['orgType']!=3){ ?>
                  <!--(END)MODEL-->
                   <div class="form-group row">
                    <label for="model" class="col-sm-2 col-form-label col-form-label-lg">Model</label>
                    <div class="col-sm-10"   >
                      <input class="form-control" id="model" type="text" name="model" required>
                      <div class="invalid-feedback">
                      Please enter model
                    </div>
                    </div>
                  </div>
                  <?php } ?>
                   <?php  if($_SESSION['orgType']==7){ ?>
                  
                  <div class="form-group row" id="capacitysection">
                    <label for="capacity" class="col-sm-2 col-form-label col-form-label-lg">Capacity</label>
                    <div class="col-sm-10" >
                      <select name="capacity"  id="capacity" class="form-control" >
                        <option  value="" selected disabled >--Select Capacity--</option>
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
                      </select>
                      <div class="invalid-feedback">
                      Please enter capacity
                    </div>
                    </div>
                  </div>
                  
                    <div class="form-group row">
                    <label for="location" class="col-sm-2 col-form-label col-form-label-lg">Location</label>
                    <div class="col-sm-10"   >
                      <input class="form-control" type="text" id="location" name="location" required>
                      <div class="invalid-feedback">
                      Please enter location
                    </div>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="ponumber" class="col-sm-2 col-form-label col-form-label-lg">PO Number</label>
                    <div class="col-sm-10"   >
                      <input class="form-control" type="text" id="ponumber" name="ponumber" required>
                      <div class="invalid-feedback">
                      Please enter po number
                    </div>
                    </div>
                  </div>
                  
                   <?php } ?>

                  <?php  if($_SESSION['orgType']!=8 && $_SESSION['orgType']!=3){ ?>
                  <!--(START)SERIAL NUMBER-->
                  <div class="form-group row">
                    <label for="serialNum" class="col-sm-2 col-form-label col-form-label-lg">Serial Number</label>
                    <div class="col-sm-10"   >
                        <textarea class="form-control" id="serialNum"  name="serialNum" required></textarea>
                      <div class="invalid-feedback">
                      Please enter serial number
                    </div>
                    </div>
                  </div>
                  <!--(END)SERIAL NUMBER-->
                  <!--(START)REMARKS-->
                  <div class="form-group row">
                    <label for="remark" class="col-sm-2 col-form-label col-form-label-lg">Remarks</label>
                    <div class="col-sm-10">
                      <textarea class="form-control" id="remarks" name="remarks"></textarea>
                      <div class="invalid-feedback">
                      Please enter remarks
                    </div>
                    </div>
                  </div>
                  <!--(END)REMARKS-->
                  <?php } ?>
                <!--(END)PRODUCT FORM-->

                  <div class="form-group row">
                      <label class="col-sm-2 col-form-label col-form-label-lg"></label>
                      <div class="col-sm-10">
                          <input type="text" hidden name="productIdToEdit" id="productIdToEdit" value=""  />
                          <button name='editProduct'
                          <?php
                              require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");

                              $isInLimit=isInLimit($_SESSION['orgId'],2,"client");
                              if($isInLimit){
                                ?>
                                disabled
                                class="btn btn-secondary btn-lg btn-block"
                                <?php
                              }else{
                                ?>
                                  class="btn btn-primary btn-lg btn-block"
                                <?php
                              }
                            ?>
                          type='submit' >Submit</button>
                      </div>
                  </div>


                  </div>
              </form>
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
<?php if(isset($_SESSION['productIdToEdit'])){
    unset($_SESSION['productIdToEdit']);
} ?>
  </div>
<script>
    function fileJPGValidation(a){
        var fileInput = a;

        var filePath = fileInput.value;
        var allowedExtensions = /(\.jpg|\.jpeg)$/i;
        if(!allowedExtensions.exec(filePath)){
            alert('Please upload file having extensions .jpeg /.jpg only.');
            fileInput.value = '';
            return false;
        }
    }

    function fileDocValidation(a){
        var fileInput = a;

        var filePath = fileInput.value;
        var allowedExtensions = /(\.doc|\.docx|\.xls|\.xlsx|\.pdf)$/i;
        if(!allowedExtensions.exec(filePath)){
            alert('Please upload file having extensions .doc/ .docx/ .xls/ .xlsx /.pdf only.');
            fileInput.value = '';
            return false;
        }
    }
</script>
</body>
</html>