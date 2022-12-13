<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
  session_name($config['sessionName']);
  session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organizationUser.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/payroll.php");

?>
<!DOCTYPE html >

<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">


    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />

    <?php
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>
    <script>
    function showStaffInfo(){
      var check = document.getElementById("paymentVoucherCheck").checked

      if (check) {
        var datePaymentVoucher = document.getElementById("datePaymentVoucher").value;
        var amountPaymentVoucher = document.getElementById("amountPaymentVoucher").value;
        var methodOfPayment = document.getElementById("methodOfPayment").value;
        var theSumOf = document.getElementById("theSumOf").value;
        var being = document.getElementById("being").value;
        var payee = document.getElementById("payee").value;

        if (methodOfPayment == "" || theSumOf == "" || being == "" || payee == "" || datePaymentVoucher == "" || amountPaymentVoucher == "") {
          document.getElementById("proceedPayroll").disabled = true;
        }else {
          document.getElementById("proceedPayroll").disabled = false;
        }
      }else {
        var x = document.getElementById("orgStaffId").value;
        var y = document.getElementById("datePayment").value;
        if(x == "null" || y == ""){
			
           document.getElementById("proceedPayroll").disabled = true;
           console.log("button disabled");
        }else{
			$('#orgStaffId').css('border-bottom','2px solid gray');
           document.getElementById("proceedPayroll").disabled = false;
           console.log("button enabled");
        }
      }
    }

    function proceedTab(){
        var check = document.getElementById("paymentVoucherCheck").checked;
        var staffId = document.getElementById("orgStaffId").value;
        var month = document.getElementById("monthSlip").value;
        var yearSlip = document.getElementById("yearSlip").value;
        var datePayment = document.getElementById("datePayment").value;
        var allowance = document.getElementById("allowance").value;
        var claims = document.getElementById("claims").value;
        var commissions = document.getElementById("commissions").value;
        var ot = document.getElementById("ot").value;
        var bonus = document.getElementById("bonus").value;
        var deduction = document.getElementById("deduction").value;

        if (check) {
          document.getElementById("form-pVoucher").style.display = "block";
          document.getElementById("payslip").style.display = "none";
          var datePaymentVoucher = document.getElementById("datePaymentVoucher").value;
          var amountPaymentVoucher = document.getElementById("amountPaymentVoucher").value;
          var methodOfPayment = document.getElementById("methodOfPayment").value;
          var theSumOf = document.getElementById("theSumOf").value;
          var being = document.getElementById("being").value;
          var payee = document.getElementById("payee").value;
          $.ajax({
              type  : 'GET',
              url  : '../../phpfunctions/payroll.php?',
              data : {paymentVoucherPreview:true,methodOfPayment:methodOfPayment,theSumOf:theSumOf,being:being,payee:payee,datePayment:datePayment,staffId:staffId,datePaymentVoucher:datePaymentVoucher,amountPaymentVoucher:amountPaymentVoucher},
              success: function (data) {
              document.getElementById('pVoucher').innerHTML = data;
              }
          });
        }else {		
			
          document.getElementById("form-pVoucher").style.display = "none";
          document.getElementById("payslip").style.display = "block";
          $.ajax({

              type  : 'GET',
              url  : '../../phpfunctions/payroll.php?',
              data : {payslip:true,staffId:staffId,month:month,yearSlip:yearSlip,datePayment:datePayment,allowance:allowance,claims:claims,commissions:commissions,ot:ot,bonus:bonus,deduction:deduction},
              success: function (data) {
              document.getElementById('payslip').innerHTML = data;
              }
          });
        }

        document.getElementById("form-tab").style.display = "none";
        document.getElementById("form-preview").style.display = "block";
    }

    function editTab(){
        document.getElementById("form-tab").style.display = "block";
        document.getElementById("form-preview").style.display = "none";
        document.getElementById("form-pVoucher").style.display = "none";
    }

    function extraForm(){
      var check = document.getElementById("paymentVoucherCheck").checked;

      if (check) {
        document.getElementById("paymentVoucherSection").style.display = "block";
        document.getElementById("paymentSalarySection").style.display = "none";
        document.getElementById("href").href = "../../phpfunctions/payroll.php?generatepaymentVoucher=1";
      }else {
        document.getElementById("paymentVoucherSection").style.display = "none";
        document.getElementById("paymentSalarySection").style.display = "block";
        document.getElementById("href").href = "../../phpfunctions/payroll.php?generatePayslipPDF=1";
      }
    }
	$(function () {
        $('.advanceshow').hide();
		
		    $('#orgStaffId').css('border-bottom','2px solid #ff1010');


        //show it when the checkbox is clicked
        $('input[name="advance"]').on('click', function () {
            if ($(this).prop('checked')) {
                $('.advanceshow').fadeIn();
            } else {
                $('.advanceshow').hide();
				$('.advanceshow input').val('');
            }
        });
    });
	
	
    </script>
    <style>

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
        <li class="breadcrumb-item">Payroll</li>
        <li class="breadcrumb-item active">Payroll</li>
      </ol>
    </div>
    <div class="container" id="form-tab">
      <?php
        if (isset($_SESSION['feedback'])) {
            echo $_SESSION['feedback'];
            unset($_SESSION['feedback']);
        }
      ?>
        <form action="#" method="post">
            <div class="form-check">
              <input onchange="extraForm();showStaffInfo()" type="checkbox" class="form-check-input" id="paymentVoucherCheck">
              <label class="form-check-label" for="paymentVoucher">Generate Payment Voucher</label>
            </div>
            <div id="paymentSalarySection">
              <hr>
              <div class="form-group row">
                  <!-- STAFF -->
                  <label for="brand" class="col-sm-1 col-form-label col-form-label-lg">*Staff</label>
                  <div class="col-sm-11"   >
                  <?php dropDownListOrgStaffListIfIsStaff() ?>
                  <div class="invalid-feedback">
                      Please choose staff
                  </div>
                  </div>
              </div>
              <div class="form-group row">
                  <!-- MONTH -->
                  <label for="month" class="col-sm-1 col-form-label col-form-label-lg">Month</label>
                  <div class="col-sm-5"   >
                      <select class="form-control" id="monthSlip">
                          <option value="1">January</option>
                          <option value="2">February</option>
                          <option value="3">March</option>
                          <option value="4">April</option>
                          <option value="5">May</option>
                          <option value="6">Jun</option>
                          <option value="7">July</option>
                          <option value="8">August</option>
                          <option value="9">September</option>
                          <option value="10">October</option>
                          <option value="11">November</option>
                          <option value="12">December</option>
                      <select>
                      <div class="invalid-feedback">
                          Please choose month
                      </div>
                  </div>

                  <!-- YEAR -->
                  <label for="year" id="yearSlipLabel" class="col-sm-1 col-form-label col-form-label-lg">Year</label>
                  <div class="col-sm-5">
                      <select class="form-control" id="yearSlip" name="yearSlip">
                              <option value="2021">2021</option>
                              <option value="2022" selected>2022</option>
                              <option value="2023">2023</option>
                              <option value="2024">2024</option>
                              <option value="2025">2025</option>
                              <option value="2026">2026</option>
                              <option value="2027">2027</option>
                              <option value="2028">2028</option>
                              <option value="2029">2029</option>
                      </select>
                  <div class="invalid-feedback">
                      Please choose year
                  </div>
                  </div>

              </div>
			  <div class="form-check">
					<input  type="checkbox" class="form-check-input" id="advance" name="advance">
					<label class="form-check-label" for="paymentVoucher">Advance</label>
				</div><br/>
			 
			  <div class="form-group row advanceshow">
					<label for="allowance" class="col-sm-1 col-form-label col-form-label-lg">Allowance</label>
					<div class="col-sm-5">
						<input type="text" id="allowance" name="allowance" min="0" class="form-control" >
					</div>
					<label for="claims" class="col-sm-1 col-form-label col-form-label-lg">Claims</label>
					<div class="col-sm-5">
						<input type="text" id="claims" name="claims" min="0" class="form-control" >
					</div>
              </div>
			  <div class="form-group row advanceshow">
					<label for="commissions" class="col-sm-1 col-form-label col-form-label-lg">Commissions</label>
					<div class="col-sm-5">
						<input type="text" id="commissions" name="commissions" min="0" class="form-control" >
					</div>
					<label for="ot" class="col-sm-1 col-form-label col-form-label-lg">OT</label>
					<div class="col-sm-5">
						<input type="text" id="ot" name="ot" min="0" class="form-control" >
					</div>
              </div>
                <div class="form-group row advanceshow">
                    <label for="bonus" class="col-sm-1 col-form-label col-form-label-lg">Bonus</label>
                    <div class="col-sm-5">
                        <input type="text" id="bonus" name="bonus" min="0" class="form-control" >
                    </div>
                    <label for="deduction" class="col-sm-1 col-form-label col-form-label-lg">Deduction</label>
                    <div class="col-sm-5">
                        <input type="text" id="deduction" name="deduction" min="0" class="form-control" >
                    </div>

                </div>
			  
			  
			  
            </div>
            <div id="paymentVoucherSection" style="display:none">
              <hr>
              <div class="form-group row">
                  <!-- PAYEE -->
                  <label for="being" class="col-sm-2 col-form-label col-form-label-lg">Payee</label>
                  <div class="col-sm-10"   >
                      <input oninput="showStaffInfo()" onclick="document.getElementById('payeeMessage').style.display = 'block'" id="payee" class="form-control" type="text" name="payee">
                      <small id="payeeMessage" style="display:none;">(person to whom money is to be paid)</small>
                      <div class="invalid-feedback">
                          Please enter payee name
                      </div>
                  </div>
              </div>

              <div class="form-group row">
                  <!-- DATE PAYMENT VOUCHER -->
                  <label for="methodOfPayment" class="col-sm-2 col-form-label col-form-label-lg">Payment Date</label>
                  <div class="col-sm-10">
                      <input oninput="showStaffInfo()" id="datePaymentVoucher" class="form-control" type="date">
                      <div class="invalid-feedback">
                          Please choose payment date
                      </div>
                  </div>
              </div>

              <div class="form-group row">
                  <!-- AMOUNT PAYMENT VOUCHER -->
                  <label for="methodOfPayment" class="col-sm-2 col-form-label col-form-label-lg">Amount(RM)</label>
                  <div class="col-sm-10">
                      <input oninput="showStaffInfo()" onchange="checkValue(this)" id="amountPaymentVoucher" class="form-control" type="number" min="0.01" step="0.01" >
                      <div class="invalid-feedback">
                          Please choose payment amount
                      </div>
                  </div>
              </div>

              <div class="form-group row">
                  <!-- METHOD OF PAYMENT -->
                  <label for="methodOfPayment" class="col-sm-2 col-form-label col-form-label-lg">Method of Payment</label>
                  <div class="col-sm-10"   >
                      <select onchange="showStaffInfo()" id="methodOfPayment" class="form-control">
                          <option value="0">Cash</option>
                          <option value="1">Cheque</option>
                      <select>
                      <div class="invalid-feedback">
                          Please choose payment method
                      </div>
                  </div>
              </div>

              <div class="form-group row">
                  <!-- THE SUM OF -->
                  <label for="theSumOf" class="col-sm-2 col-form-label col-form-label-lg">Payment for</label>
                  <div class="col-sm-10"   >
                      <input oninput="showStaffInfo()" id="theSumOf" class="form-control" type="text" name="theSumOf">
                      <div class="invalid-feedback">
                          Please enter The Sum of
                      </div>
                  </div>
              </div>

              <div class="form-group row">
                  <!-- BEING -->
                  <label for="being" class="col-sm-2 col-form-label col-form-label-lg">Remarks</label>
                  <div class="col-sm-10"   >
                      <input oninput="showStaffInfo()" id="being" class="form-control" type="text" name="being">
                      <div class="invalid-feedback">
                          Please enter remarks
                      </div>
                  </div>

              </div>
            </div>
            <div class="form-group row" style="display:none">
                <!-- DATE PAYMENT -->
                <label for="datePayment" class="col-sm-1 col-form-label col-form-label-lg">Date Payment</label>
                <div class="col-sm-5"   >
                <input oninput="showStaffInfo()" class="form-control" type="date" id="datePayment" name="datePayment" value="<?php echo date("Y-m-d") ?>" required>
                <div class="invalid-feedback">
                    Please select date
                </div>
                </div>
            </div>

            <!-- PROCEED -->
            <div class="form-group row">
                <div class="col-sm-12">
                    <button id="proceedPayroll" onclick="proceedTab()" name='proceedPayroll' class="btn btn-primary btn-lg btn-block" type='button' disabled>Proceed</button>
                </div>
            </div>
        </form>
    </div>

    <div class="container" id="form-preview" style="display:none;" >
     <!-- EDIT BUTTON -->
     <div class="form-group row">
       <div class="col-sm-12">
         <button id="editPayroll" onclick="editTab()" name='editPayroll' class="btn btn-primary btn-lg btn-block" type='button'>Back to Edit</button>
       </div>
     </div>

    <!-- PURCHASE VOUCHER -->
    <div id="form-pVoucher" style="overflow:scroll;">
        <div id="pVoucher" style="width:970px" style="display:none"></div>
    </div>

    <!-- PAYSLIP -->
    <div style="overflow:scroll;">
        <div id="payslip" style="width:970px"></div>
    </div>


     <!-- PRINT -->
      <div class="form-group row mt-2">
     <?php /*  <div class="col-sm-6"  style="display:none"><a href="../../phpfunctions/payroll.php?printPayslip=1">
         <button id="editPayroll" name='editPayroll' class="btn btn-primary btn-lg btn-block" type='button'>Print</button></a>
       </div> */ ?>
       <div class="col-sm-12">
	       <a id="href" href="../../phpfunctions/payroll.php?generatePayslipPDF=1" >
		       <button id="generatePayroll" name='generatePayroll' class="btn btn-primary btn-lg btn-block" type='button'>Generate</button></a>

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

<script type="text/javascript">
	$(document).ready(function() {
		$('#allowance').keypress(function(event) {
			if ((event.which != 46 || $(this).val().indexOf('.') != -1) &&
				((event.which < 48 || event.which > 57) &&
				(event.which != 0 && event.which != 8))) {
				event.preventDefault();
			}

			var text = $(this).val();

			if ((text.indexOf('.') != -1) &&
				(text.substring(text.indexOf('.')).length > 2) &&
				(event.which != 0 && event.which != 8) &&
				($(this)[0].selectionStart >= text.length - 2)) {
				event.preventDefault();
			}
		});
		$('#claims').keypress(function(event) {
			if ((event.which != 46 || $(this).val().indexOf('.') != -1) &&
				((event.which < 48 || event.which > 57) &&
				(event.which != 0 && event.which != 8))) {
				event.preventDefault();
			}

			var text = $(this).val();

			if ((text.indexOf('.') != -1) &&
				(text.substring(text.indexOf('.')).length > 2) &&
				(event.which != 0 && event.which != 8) &&
				($(this)[0].selectionStart >= text.length - 2)) {
				event.preventDefault();
			}
		});
		$('#commissions').keypress(function(event) {
			if ((event.which != 46 || $(this).val().indexOf('.') != -1) &&
				((event.which < 48 || event.which > 57) &&
				(event.which != 0 && event.which != 8))) {
				event.preventDefault();
			}

			var text = $(this).val();

			if ((text.indexOf('.') != -1) &&
				(text.substring(text.indexOf('.')).length > 2) &&
				(event.which != 0 && event.which != 8) &&
				($(this)[0].selectionStart >= text.length - 2)) {
				event.preventDefault();
			}
		});
		$('#ot').keypress(function(event) {
			if ((event.which != 46 || $(this).val().indexOf('.') != -1) &&
				((event.which < 48 || event.which > 57) &&
				(event.which != 0 && event.which != 8))) {
				event.preventDefault();
			}

			var text = $(this).val();

			if ((text.indexOf('.') != -1) &&
				(text.substring(text.indexOf('.')).length > 2) &&
				(event.which != 0 && event.which != 8) &&
				($(this)[0].selectionStart >= text.length - 2)) {
				event.preventDefault();
			}
		});
		$('#bonus').keypress(function(event) {
			if ((event.which != 46 || $(this).val().indexOf('.') != -1) &&
				((event.which < 48 || event.which > 57) &&
				(event.which != 0 && event.which != 8))) {
				event.preventDefault();
			}

			var text = $(this).val();

			if ((text.indexOf('.') != -1) &&
				(text.substring(text.indexOf('.')).length > 2) &&
				(event.which != 0 && event.which != 8) &&
				($(this)[0].selectionStart >= text.length - 2)) {
				event.preventDefault();
			}
		});

	});

    function checkValue(id){
        var x = parseFloat(id.value);
        id.value=x.toFixed(2);
    }
</script>
</body>
</html>