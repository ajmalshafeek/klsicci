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
    <style>
    table, td, th {
    }

    table {
      border-collapse: collapse;
      width: 100%;
    }

    th {
      height: 50px;
      color:white;
      background: #212529;
    }
    table tbody tr:hover td{
      background-color: #DEE2E6;
    }

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        margin: 0;
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
        <li class="breadcrumb-item ">Payroll</li>
        <li class="breadcrumb-item active">Payroll Setting</li>
      </ol>
    </div>
    <div class="container" id="formContainer">
      <div class="row col-md-12" style="display: flex; justify-content: flex-end;padding-right:0px;"><p onclick="changeContainer(0)" style="margin-bottom:0px;cursor:pointer;"><i>list>></i></p></div>
    <?php
                  if (isset($_SESSION['feedback'])) {
                      echo $_SESSION['feedback'];
                      unset($_SESSION['feedback']);
                  }
      ?>
      <form class="" action="../../phpfunctions/payroll.php" method="post">
        <!-- STAFF -->
        <div class="form-group row">
          <label for="staff" class="col-sm-2 col-form-label col-form-label-lg">Staff</label>
          <div class="col-sm-10"   >
            <?php dropDownListOrgStaffListIfIsStaff() ?>
            <div class="invalid-feedback">
              Please choose staff to proceed
            </div>
          </div>
        </div>

        <!-- BASIC -->
        <div class="form-group row">
          <label for="basic" class="col-sm-2 col-form-label col-form-label-lg">Basic Salary</label>
          <div class="col-sm-10"   >
            <input oninput="calculateEpf(), calculateTotalSocsoEmpPer()" id="basic" class="form-control" type="number" name="basic">
            <div class="invalid-feedback">
              Please enter staff's basic salary
            </div>
          </div>
        </div>

        <!-- EPF -->
        <div class="form-group row">
          <label for="epf" class="col-sm-2 col-form-label col-form-label-lg">EPF</label>
          <div class="col-sm-10"   >
            <select onchange="calculateEpf()" name="epf"  id="epf" class="form-control">
              <option  value="" selected disabled >--Select %--</option>
              <option value="9">9%</option>
                <option value="11">11%</option>
              <option value="13">13%</option>
            </select>
            <div class="invalid-feedback">
              Please choose staff's EPF %
            </div>
          </div>
        </div>
        <!-- EPF -->
        <div class="form-group row">
          <label for="epfEmployee" class="col-sm-2 col-form-label col-form-label-lg">EPF Employee</label>
          <div class="col-sm-10"   >
            <select onchange="calculateEpf()" name="epfEmployee"  id="epfEmployee" class="form-control">
              <option  value="" selected disabled >--Select %--</option>
              <option value="9">9%</option>
              <option value="11">11%</option>
              <option value="13">13%</option>
            </select>
            <div class="invalid-feedback">
              Please choose employee's EPF %
            </div>
          </div>
        </div>

	      <div class="form-group row">
		      <label for="epfEmp" class="col-sm-2 col-form-label col-form-label-lg">EPF Employee</label>
		      <div class="col-sm-10"   >
			      <input id="epfEmp" class="form-control epfEmp" type="text" name="epfEmp" autocomplete="off">
			      <div class="invalid-feedback">
				      Please enter EPF Employee
			      </div>
		      </div>
	      </div>

	      <div class="form-group row">
		      <label for="epfEmpyr" class="col-sm-2 col-form-label col-form-label-lg">EPF Employer</label>
		      <div class="col-sm-10"   >
			      <input id="epfEmpyr" class="form-control epfEmpyr" type="text" name="epfEmpyr" autocomplete="off">
			      <div class="invalid-feedback">
				      Please enter EPF Employer
			      </div>
		      </div>
	      </div>

          <!-- SOSCO EMPL % -->
          <div class="form-group row">
              <label for="socsoEmployerPer" class="col-sm-2 col-form-label col-form-label-lg">SOSCO Employer %</label>
              <div class="col-sm-10"   >
                  <input type="number" onchange="calculateTotalSocsoEmpPer()" name="socsoEmployerPer"  id="socsoEmployerPer" class="form-control" step="0.0001" />
                  <div class="invalid-feedback">
                      Please choose employer's SOCSO %
                  </div>
              </div>
          </div>

          <!-- SOCSO % -->
          <div class="form-group row">
              <label for="socsoEmpPer" class="col-sm-2 col-form-label col-form-label-lg">SOCSO Employee %</label>
              <div class="col-sm-10"   >
                  <input type="number" onchange="calculateTotalSocsoEmpPer()" name="socsoEmpPer"  id="socsoEmpPer" class="form-control" step="0.0001" />
                  <div class="invalid-feedback">
                      Please choose staff's SOCSO %
                  </div>
              </div>
          </div>

        <!-- SOCSO -->
        <div class="form-group row">
          <label for="socso" class="col-sm-2 col-form-label col-form-label-lg">SOCSO Employer</label>
          <div class="col-sm-10"   >
            <input id="socso" class="form-control socso" type="text" name="socso" autocomplete="off" required="required">
            <div class="invalid-feedback">
              Please enter staff's SOCSO EMPLOYER
            </div>
          </div>
        </div>
        <!-- SOCSO EMP -->
        <div class="form-group row">
          <label for="socsoEmp" class="col-sm-2 col-form-label col-form-label-lg">SOCSO Employee</label>
          <div class="col-sm-10"   >
            <input id="socsoEmp" class="form-control socsoEmp" type="text" name="socsoEmp" autocomplete="off" required="required">
            <div class="invalid-feedback">
              Please enter staff's SOCSO EMPLOYEE
            </div>
          </div>
        </div>

        <!-- PCB -->
        <div class="form-group row">
		      <label for="pcb" class="col-sm-2 col-form-label col-form-label-lg">PCB</label>
		      <div class="col-sm-10"   >
			      <input id="pcb" class="form-control pcb" type="text" name="pcb" autocomplete="off" required="required">
			      <div class="invalid-feedback">
				      Please enter staff's PCB
			      </div>
		      </div>
	      </div>

	      <div class="form-group row">
		      <label for="eis" class="col-sm-2 col-form-label col-form-label-lg">EIS</label>
		      <div class="col-sm-10"   >
			      <input id="eis" class="form-control eis" type="text" name="eis" autocomplete="off" >
			      <div class="invalid-feedback">
				      Please enter staff's EIS
			      </div>
		      </div>
	      </div>

	<!--  Epf(Employer) :
	      Epf(Employee) :
	      Socso:
	      EIS:
        <!-- BANK -->
        <div class="form-group row">
          <!-- BANK NAME-->
          <label for="bankName" class="col-sm-2 col-form-label col-form-label-lg">Bank Name</label>
          <div class="col-sm-4"   >
            <input id="bankName" class="form-control" type="text" name="bankName">
            <div class="invalid-feedback">
              Example: CIMB, Maybank...
            </div>
          </div>

          <!-- BANK ACCOUNT-->
          <label for="bankAcc" class="col-sm-2 col-form-label col-form-label-lg">Bank Account No.</label>
          <div class="col-sm-4"   >
            <input id="bankAcc" class="form-control" type="text" name="bankAcc">
            <div class="invalid-feedback">
              Please enter staff's bank account number
            </div>
          </div>
        </div>

        <div class="form-group row">
          <!-- NASIONALITY-->
          <label for="nasionality" class="col-sm-2 col-form-label col-form-label-lg">Nationality</label>
          <div class="col-sm-4">
            <select id="nasionality" class="form-control" name="nasionality" required>
              <option value="0">Malaysian</option>
              <option value="1">Foreigner</option>
            </select>
            <div class="invalid-feedback">
              Select enter staff's nasionality
            </div>
          </div>

          <!-- TAX NUMBER-->
          <label for="taxId" class="col-sm-2 col-form-label col-form-label-lg">Tax Number</label>
          <div class="col-sm-4">
            <input id="taxId" class="form-control" type="text" name="taxId">
            <div class="invalid-feedback">
              Select enter staff's tax number
            </div>
          </div>
        </div>

        <!-- SUBMIT -->
        <div class="form-group row">
            <label class="col-sm-2 col-form-label col-form-label-lg"></label>
            <div class="col-sm-10">
                <button id="submitPayroll" name='editPayroll' class="btn btn-primary btn-lg btn-block" type='submit' disabled>Save</button>
            </div>
        </div>
      </form>
    </div>
    <div class="container" id="listContainer" style="display:none">
        <div class="row col-md-12" style="display: flex; justify-content: flex-end;padding-right:0px;"><p onclick="changeContainer(1)" style="margin-bottom:0px;cursor:pointer;"><i>form>></i></p></div>
        <?php echo payrollTable() ?>
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
  <!-- Data Table Import -->
  <link rel="stylesheet" type="text/css" href="../../adminTheme/dataTable15012020/jquery.dataTables.css">
  <script type="text/javascript" charset="utf8" src="../../adminTheme/dataTable15012020/jquery.dataTables.js"></script>
  <script>
  $(document).ready( function () {
    $('#tablePayroll').DataTable();
  } );
  function showStaffInfo(){
    var x = document.getElementById("orgStaffId").value;
    if(x == ""){
       document.getElementById("submitPayroll").disabled = true;
    }else{
      document.getElementById("submitPayroll").disabled = false;
    }

  $.ajax({
          type  : 'GET',
          url  : '../../phpfunctions/payroll.php?',
          data : {getStaffSlipSetting:x},
          success: function (data) {
          details= JSON.parse(data);

          //BASIC
          try {
            document.getElementById('basic').value = details.basic;
          }
          catch(e) {
            document.getElementById('basic').value = "";
          }

          //EPF
          try {
            document.getElementById('epf').value = details.epf;
          }
          catch(e) {
            document.getElementById('epf').value = "";
          }
          //EPF Epm %
          try {
            document.getElementById('epfEmployee').value = details.epfEmpPer;
          }
          catch(e) {
            document.getElementById('epfEmployee').value = "";
          }

	      //EPF EMPLOYEE
	          try {
		          document.getElementById('epfEmp').value = details.epfEmp;
	          }
	          catch(e) {
		          document.getElementById('epfEmp').value = "";
	          }

	          //EPF EMPLOYER
	          try {
		          document.getElementById('epfEmpyr').value = details.epfEmpyr;
	          }
	          catch(e) {
		          document.getElementById('epfEmpyr').value = "";
	          }


	          //SOCSO
          try {
            document.getElementById('socso').value = details.socso;
          }
          catch(e) {
            document.getElementById('socso').value = "";
          }
	          //SOCSO EMP
          try {
            document.getElementById('socsoEmp').value = details.socsoEmp;
          }
          catch(e) {
            document.getElementById('socsoEmp').value = "";
          }
	          //SOCSO EMP %
          try {
            document.getElementById('socsoEmpPer').value = details.socsoEmpPer;
          }
          catch(e) {
            document.getElementById('socsoEmpPer').value = "";
          }
	          //SOCSO EMPLR
          try {
            document.getElementById('socsoEmployerPer').value = details.socsoPer;
          }
          catch(e) {
            document.getElementById('socsoEmployerPer').value = "";
          }

          //PCB
          try {
            document.getElementById('pcb').value = details.pcb;
          }
          catch(e) {
            document.getElementById('pcb').value = "";
          }

	          //EIS
	          try {
		          document.getElementById('eis').value = details.eis;
	          }
	          catch(e) {
		          document.getElementById('eis').value = "";
	          }

          //BANK NAME
          try {
            document.getElementById('bankName').value = details.bankName;
          }
          catch(e) {
            document.getElementById('bankName').value = "";
          }

          //BANK ACC
          try {
            document.getElementById('bankAcc').value = details.bankAcc;
          }
          catch(e) {
            document.getElementById('bankAcc').value = "";
          }

          //NASIONALITY
          try {
            document.getElementById('nasionality').value = details.nasionality;
          }
          catch(e) {
            document.getElementById('bankAcc').value = "";
          }

          //TAX ID
          try {
            document.getElementById('taxId').value = details.taxId;
          }
          catch(e) {
            document.getElementById('taxId').value = "";
          }
          }
      });
      setTimeout(calculateEpf, 1000);
      setTimeout(calculateTotalSocsoEmpPer, 1000);
  }

  function calculateEpf(){
      var basicVal = document.getElementById("basic").value;
      var epfVal = document.getElementById("epf").value;
      var epfEmpVal = document.getElementById("epfEmployee").value;
      if(basicVal != "" && epfVal != ""){
          var totalEpf = (basicVal*epfVal)/100;
          var totalEpfEmp = (basicVal*epfEmpVal)/100;
	      document.getElementById("epfEmpyr").value = totalEpf.toFixed(2);
	      document.getElementById("epfEmp").value = totalEpfEmp.toFixed(2);
      }
  }


  function calculateTotalSocsoEmpPer(){
      var basicVal = document.getElementById("basic").value;
      var socsoVal = document.getElementById("socsoEmployerPer").value;
      var socsoEmpVal = document.getElementById("socsoEmpPer").value;

      if(basicVal != "" && socsoVal != ""){
          var totalsocso = (basicVal*socsoVal)/100;
          var totalsocsoEmp = (basicVal*socsoEmpVal)/100;
          document.getElementById("socso").value = totalsocso.toFixed(2);
          document.getElementById("socsoEmp").value = totalsocsoEmp.toFixed(2);
      }
  }

  function changeContainer(i){
      if(i == 0){
          document.getElementById("formContainer").style.display = "none";
          document.getElementById("listContainer").style.display = "block";
      }else if(i==1){
          document.getElementById("formContainer").style.display = "block";
          document.getElementById("listContainer").style.display = "none";
      }
  }
  </script>


<script type="text/javascript">
	$(document).ready(function() {
		jQuery('.socso').keypress(function(event) {

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
		jQuery('.socsoEmp').keypress(function(event) {

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
		jQuery('.pcb').keypress(function(event) {

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
		jQuery('.eis').keypress(function(event) {

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
		jQuery('.epfEmp').keypress(function(event) {

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
		jQuery('.epfEmpyr').keypress(function(event) {

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
</script>
</body>
</html>