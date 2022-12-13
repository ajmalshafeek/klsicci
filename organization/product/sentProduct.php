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

   <?php // <link rel='stylesheet' type='text/css' href='../css/myQuotationStyle.css' /> ?>
    <?php
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>-->
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
        function changeWorkType(str){

//      document.getElementById("assignWorker").disabled = true;

      //document.getElementById("assignWorker").classList.remove('btn-primary');

      //document.getElementById("assignWorker").classList.add('btn-light');

 

        $.ajax({

            type  : 'GET',

              url  : '../../phpfunctions/clientComplaint.php?',

              data : {workerType:str},

              success: function (data) {



 

                var worker = $('#selectWorker');

                worker.empty().append(data);

                var noOfList=document.getElementById("workerId").length;

                if(noOfList>0){

                  document.getElementById("assignWorker").disabled = false;

                  document.getElementById("assignWorker").classList.remove('btn-light');

                  document.getElementById("assignWorker").classList.add('btn-primary');

                  

                }



              }

        });

      }
  $(document).ready(function() {

      $(".contract1").change(function() {
          $(".leasing").css("display","none");
      });


      $(".contract0").change(function() {
          $(".leasing").css("display","flex");
      });

  });
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
input[name="contract"] {
    width: 20px;
    height: 20px;
    position: absolute;
    left: -7px;
    top: 10px;
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
        <li class="breadcrumb-item active">Delivery Order</li>
      </ol>
    </div>
      <div class="container">
            <form method="POST" action="../../phpfunctions/sentout.php" class="needs-validation" novalidate >
            <?php
                  if (isset($_SESSION['feedback'])) {
                      echo $_SESSION['feedback'];
                      unset($_SESSION['feedback']);
                  }
              ?>
              <div id="productForm">
                <!--(START)Sent Out FORM-->
                  <div class="form-group row">

                        <label for="problem" class="col-sm-2 col-form-label col-form-label-lg">Sent Out For</label>

                        <div class="col-sm-10">
                            <div class="fb-radio">
                              <label class="col-sm-3 col-form-label col-form-label-lg">&nbsp;<input name="contract" class="contract0" id="item1_0_radio" type="radio" checked="" data-hint="" value="Lease"><span class="fb-fieldlabel" id="item1_0_span">Lease</span></label>
                              <label class="col-sm-3 col-form-label col-form-label-lg">&nbsp;<input name="contract" class="contract1" id="item1_1_radio" type="radio" value="Delivery"><span class="fb-fieldlabel" id="item1_1_span">Delivery</span></label>
                            </div>
                            <div class="invalid-feedback">
                                Please select a Contract
                            </div>
                        </div>
                      
                    </div>
                  <div class="form-group row">

                        <label for="problem" class="col-sm-2 col-form-label col-form-label-lg">Client</label>

                        <div class="col-sm-10">
                            <?php
                            require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientCompany.php");
                            dropDownListOrganizationClientCompanyActive3(); ?>
                        <div class="invalid-feedback">

                                Please enter a client

                            </div>

                        </div>
                      <div class="col-sm-2"></div>
                        <div class="col-sm-10">
                            <a href="../client/addClient.php">Add Client</a>
                      </div>
                    </div>
                  
                  <div class="form-group row ">
                    <label for="serialnumber" class="col-sm-2 col-form-label col-form-label-lg">Serial Number </label>
                    <div class="col-sm-10 wrapper">
                        <div class="input-box">
                      <input type="text" name="serialnumber[]" class="form-control" required/>
                        </div>
                        
                      <div class="invalid-feedback">
                      Please choose serial number
                    </div>
                      
                    </div>
                      
                  </div>
                  <div class="form-group row">
                    
                    <div class="col-sm-2">
                        <a href="#" class="btn add-btn">Add More</a>
                        
                    </div>
                      <div class="col-sm-10">
                          <label class="count" id="count">Quantity 1</label>
                      </div>
                      
                  </div>
                  <!--(START)BRAND-->
                                    <div class="form-group row">
                    <label for="incharge" class="col-sm-2 col-form-label col-form-label-lg">In Charge</label>
                    <div class="col-sm-10">
                      <input type="text" name="incharge" class="form-control" required/>

                    </div>
                    </div>
                  <div class="form-group row">
                    <label for="workerType" class="col-sm-2 col-form-label col-form-label-lg">Assign To</label>
                    <div class="col-sm-10"   >
                      <select name='workerType' class='form-control' id='workerType' onchange="changeWorkType(this.value)">
                        <option selected disabled value="default" >--Select--</option>
                        <option value="myStaff">My Staff</option>
                        <option value="vendors">Vendors</option>
                      </select>

                    </div>
                    </div>
                  <div class="form-group row">
                    <label for="incharge" class="col-sm-2 col-form-label col-form-label-lg">Driver</label>
                    <div class="col-sm-10"   >
                      <div id='selectWorker' >
                        </select>

                      </div>

                    </div>
                  </div>
                  <div class="form-group row ">
                        <label for="vehicleno" class="col-sm-2 col-form-label col-form-label-lg">Vehicle Number</label>
                          <div class="col-sm-10">
                          

                              <input type="text" name="vehicleno" class="form-control" required/>
                           

                        
                            <div class="invalid-feedback">
                              Please choose vehicle number
                            </div>
                      
                          </div>
                  </div>
                  <div class="form-group row leasing">
                        <label for="vehicleno" class="col-sm-2 col-form-label col-form-label-lg">Start Date</label>
                          <div class="col-sm-4">
                            

                              <input type="date" name="startdate" class="form-control" />
                            

                        
                            <div class="invalid-feedback">
                              Please choose start date
                            </div>
                      
                          </div>
                          <label for="vehicleno" class="col-sm-2 col-form-label col-form-label-lg">End Date</label>
                          <div class="col-sm-4">
                            
                              <input type="date" name="enddate" class="form-control" />
                            
                        
                            <div class="invalid-feedback">
                              Please choose end date
                            </div>
                      
                          </div>
                  </div>

                  
                  <div class="form-group row">
                      <label class="col-sm-2 col-form-label col-form-label-lg"></label>
                      <div class="col-sm-10">
                          <input type="text" hidden name="productIdToEdit" id="productIdToEdit" value=""  />
                          <button name='sentProduct'
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
              <input type="text" name="serialnumber[]" class="form-control " style=\"width= \"/>
            </div>
            <a href="#" class="remove-lnk"><i class="fa fa-minus-circle"></i></a></div>
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
  </div>
</body>
</html>
