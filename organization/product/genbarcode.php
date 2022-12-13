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

?>
<!DOCTYPE html >

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
        <?php  if($_SESSION['orgType']==7){ ?>
        $(document).ready(function() {
            $("#capacitysection").css("display","none");
 $("#producttype").on('change',function(){
  if($("#producttype").val().toLowerCase()=="ram"||$("#producttype").val().toLowerCase()=="hdd"||$("#producttype").val().toLowerCase()=="hard drive"||$("#producttype").val().toLowerCase()=="hardrive"||$("#producttype").val().toLowerCase()=="ssd"||$("#producttype").val().toLowerCase()=="pendrive"||$("#producttype").val().toLowerCase()=="pen drive"||$("#producttype").val().toLowerCase()=="solid state drive"){
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
                i.fa.fa-minus-circle {
    font-size: 20px;
    margin-top: 10px;
    color: red;
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
        <li class="breadcrumb-item active">Add Product</li>
      </ol>
    </div>
      <div class="container">
            <form method="POST" action="./generate_barcode.php" class="needs-validation" novalidate >
            <?php
                  if (isset($_SESSION['feedback'])) {
                      echo $_SESSION['feedback'];
                      unset($_SESSION['feedback']);
                  }
              ?>
              <div id="barcodeForm">

                  
                  
                  
                   <div class="form-group row">
                    <label for="serialNum" class="col-sm-2 col-form-label col-form-label-lg">Serial Number</label>
                    <div class="col-sm-10"   >
                      <input class="form-control" type="text" name="srn" required>
                      <div class="invalid-feedback">
                      Please enter serial number
                    </div>
                    </div>
                  </div>
                   
                  <div class="form-group row">
                      <label class="col-sm-2 col-form-label col-form-label-lg"></label>
                      <div class="col-sm-10">
                          <button name='genbarcode' class="btn btn-primary btn-lg btn-block"  type='submit'>Generate Barcode</button>
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

  </div>
  <script type="text/javascript">
    $(document).ready(function () {

      // allowed maximum input fields
      var max_input = 20;

      // initialize the counter for textbox
      var x = 1;

      // handle click event on Add More button
      $('.add-btn').click(function (e) {
         
        e.preventDefault();
        if (x < max_input) { // validate the condition
          x++; // increment the counter
          $('.wrapper').append(`
            <div class="row">
            <div class="input-box col-sm-11">
              <input type="text" name="serialNum[]" class="form-control " style=\"width= \"/>
            </div>
            <a href="#" class="remove-lnk"><i class="fa fa-minus-circle" aria-hidden="true"></i></a></div>
          `);
            // add input field
            $('#count').html("Quantity: "+x);
        }
      });

      // handle click event of the remove link
      $('.wrapper').on("click", ".remove-lnk", function (e) {
        e.preventDefault();
        $(this).parent('.row').remove();  // remove input field
        x--; // decrement the counter
          $('#count').html("Quantity: "+x);
      })

    });
  </script>
</body>
</html>
