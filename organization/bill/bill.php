<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
  session_name($config['sessionName']);
  session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/bill.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/vehicle.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/trip.php");

?>
<!DOCTYPE html>

<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">


    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />

    <link rel='stylesheet' type='text/css' href='css/myQuotationStyle.css' />
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

        function showSubcategoryOptions(){
          var billCategoryId = document.getElementById("category").value;
          $.ajax({

              type  : 'GET',
              url  : '../../phpfunctions/bill.php?',
              data : {showSubcategoryOptions:billCategoryId},
              success: function (data) {
                document.getElementById("subcategory").innerHTML = data;
              }
          });
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
        <li class="breadcrumb-item ">Expenses</li>
      </ol>
    </div>
      <div class="container">
            <form method="POST" action="../../phpfunctions/bill.php" class="needs-validation" novalidate >
            <?php
                  if (isset($_SESSION['feedback'])) {
                      echo $_SESSION['feedback'];
                      unset($_SESSION['feedback']);
                  }
              ?>
              <div id="productForm">
                <!--(START)BILL PAYMENT FORM-->
                  <!--(START)CATEGORY-->

                  <div class="form-group row">
                    <label for="category" class="col-sm-2 col-form-label col-form-label-lg">Category</label>
                    <div class="col-sm-10"   >
                      <select onchange="showSubcategoryOptions()" name="category"  id="category" class="form-control" required >
                        <?php categoryOptionListAll() ?>
                      </select>
                      <div class="invalid-feedback">
                      Please choose Category
                    </div>
                    </div>
                  </div>
                  <!--(END)CATEGORY-->

                  <!--(START)SUBCATEGORY-->
                  <div class="form-group row">
                    <label for="category" class="col-sm-2 col-form-label col-form-label-lg">Expense For</label>
                    <div class="col-sm-10"   >
                      <select name="subcategory"  id="subcategory" class="form-control" required >
                        <option value='' selected disabled>--Select Category First--</option>
                      </select>
                      <div class="invalid-feedback">
                      Please choose SubCategory
                    </div>
                    </div>
                  </div>
                  <!--(END)SUBCATEGORY-->

                  <!--(START)INVOICE/ACCOUNT NUMBER-->

                  <div class="form-group row">
                    <!-- INVOICE NUMBER -->
                    <label for="invoiceNum" class="col-sm-2 col-form-label col-form-label-lg">Invoice Number <sub>Optional<sub></label>
                    <div class="col-sm-4"   >
                      <input class="form-control" type="text" name="invoiceNum">
                      <div class="invalid-feedback">
                      Please enter invoice number
                    </div>
                    </div>

                    <!-- ACCOUNT NUMBER -->
                    <label for="accountNum" class="col-sm-2 col-form-label col-form-label-lg">Account Number <sub>Optional<sub></label>
                    <div class="col-sm-4"   >
                      <input class="form-control" type="text" name="accountNum" onkeypress="return onlyNumberKey(event)">
                      <div class="invalid-feedback">
                      Please enter account number
                    </div>
                    </div>

                  </div>
                  <!--(END)INVOICE/ACCOUNT NUMBER-->
                      <div class="form-group row">
                          <label for="client" class="col-sm-2 col-form-label col-form-label-lg">Trip <sub>Optional<sub></label>
                          <div class="col-sm-10">
                              <?php require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientCompany.php");
                              dropDownListTrip();  ?>
                              <div class="invalid-feedback">
                                  Please select client
                              </div>
                          </div>
                      </div>
                  <!--(START)DATE-->
                  <div class="form-group row">
                      <?php if($_SESSION['orgType']==2){ ?>
                      <label for="model" class="col-sm-2 col-form-label col-form-label-lg"><?php if($_SESSION['orgType']==2){ echo "Vehicle "; } ?>Number <sub>Optional<sub></label>
                      <div class="col-sm-4"   >
                          <select name="vehicleNumber" id="vehicleNumber" class="form-control">
                              <option value="" selected>--Select Vehicle Number--</option>
                              <?php getVehicleNumber(); ?>
                          </select>
                          <div class="invalid-feedback">
                              Please enter <?php if($_SESSION['orgType']==2){ echo "Vehicle "; } ?>number
                          </div>
                      </div> <?php } else { ?>
                          <input class="form-control" type="hidden" name="vehicleNo" value="">
                      <?php } ?>
                    <label for="model" class="col-sm-2 col-form-label col-form-label-lg">Date</label>
                    <div class="col-sm-4"   >
                      <input class="form-control" type="date" name="dateBill" required>
                      <div class="invalid-feedback">
                      Please enter date
                    </div>
                    </div>
                  </div>
                  <!--(END)DATE-->
                  <div class="form-group row">
                      <label for="requestBy" class="col-sm-2 col-form-label col-form-label-lg">Request By</label>
                      <div class="col-sm-4"   >
                          <input class="form-control" type="text" name="requestBy">
                          <div class="invalid-feedback">
                              Please enter request by
                          </div>
                      </div>
                      <!-- Request By -->
                      <label for="jobNo" class="col-sm-2 col-form-label col-form-label-lg">Job No</label>
                      <div class="col-sm-4"   >
                          <input class="form-control" type="text" name="jobNo">
                          <div class="invalid-feedback">
                              Please enter job no
                          </div>
                      </div>

                  </div>
                  <div class="form-group row">
                      <label for="location" class="col-sm-2 col-form-label col-form-label-lg">Shop/Location</label>
                      <div class="col-sm-10"   >
                          <input class="form-control" type="text" name="location">
                          <div class="invalid-feedback">
                              Please enter location
                          </div>
                      </div>
                  </div>

                  <div class="form-group row">
                      <label for="description" class="col-sm-2 col-form-label col-form-label-lg">Description</label>
                      <div class="col-sm-10"   >
                          <input class="form-control" type="text" name="description">
                          <div class="invalid-feedback">
                              Please enter description
                          </div>
                      </div>
                  </div>
                  <div class="form-group row">
                  <label for="remarks" class="col-sm-2 col-form-label col-form-label-lg">Remarks</label>
                      <div class="col-sm-10"   >
                          <input class="form-control" type="text" name="remarks">
                          <div class="invalid-feedback">
                              Please enter remarks
                          </div>
                      </div>
                  </div>
                  <!--(START)AMOUNT-->
                  <div class="form-group row">
                      <label for="amount" class="col-sm-2 col-form-label col-form-label-lg">Amount(RM)</label>
                      <div class="col-sm-4">
                          <input class="form-control" type="text" name="amount" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" required>
                          <div class="invalid-feedback">
                              Please enter amount
                          </div>
                      </div>

                      <label for="status" class="col-sm-2 col-form-label col-form-label-lg">Status</label>
                      <div class="col-sm-4"   >
                          <select name="status" id="status" class="form-control" required>
                              <option value="" selected>--Select Status--</option>
                              <option value="0">Pending</option>
                              <option value="1">Paid</option>
                          </select>
                          <div class="invalid-feedback">
                              Please enter status
                          </div>
                      </div>
                  </div>
                  <!--(END)REMARKS-->

                  <div class="form-group row">
                      <label class="col-sm-2 col-form-label col-form-label-lg"></label>
                      <div class="col-sm-10">
                          <button class="btn btn-primary btn-lg btn-block" name='addBill' type='submit' >Submit</button>
                      </div>
                  </div>


                  </div>
              </form>
            </div>

        </div>
<script>
    function onlyNumberKey(evt) {

        // Only ASCII character in that range allowed
        var ASCIICode = (evt.which) ? evt.which : evt.keyCode
        if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
            return false;
        return true;
    }
</script>
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
