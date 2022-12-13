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

    <link rel="stylesheet" href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'] ?>/css/bootstrap.min.css">

    <?php
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>
    <script>
      $(document).ready( function () {
        $('#payslipListTable').DataTable({
          "order": [
            [ 0, "asc" ]
          ]
        });
      } );

    function loadPayslipPDF(payrollId){
        $.ajax({
                type  : 'GET',
                url  : '../../phpfunctions/payroll.php?',
                data : {loadPayslip:payrollId},
                success: function (data) {
                  console.log(data);
                  if (data != "") {
                    document.getElementById("paymentVoucherPDFModalContent").innerHTML = "";
                    document.getElementById("payslipPDFModalContent").innerHTML = data;
                    document.getElementById("removePayslip").style.display = "block";
                    document.getElementById("editPayslip").style.display = "block";
                    document.getElementById("removePayslip").setAttribute( "onClick", "removePayslip(" + payrollId + ")" );
                    document.getElementById("editPayslip").setAttribute( "onClick", "showPayslipDetails(" + payrollId + ")" );
                    document.getElementById("payslipId").value = payrollId;
                  }
                }
        });

        $.ajax({
                type  : 'GET',
                url  : '../../phpfunctions/payroll.php?',
                data : {loadPaymentVoucher:payrollId},
                success: function (data) {
                  if (data != "") {
                    document.getElementById("payslipPDFModalContent").innerHTML = "";
                    document.getElementById("paymentVoucherPDFModalContent").innerHTML = data;
                    document.getElementById("removePayslip").style.display = "none";
                    document.getElementById("editPayslip").style.display = "none";
                    document.getElementById("payslipId").value = payrollId;
                  }
                }
        });
    }

    function checkCheckbox(){
      var check = document.getElementsByName("emailCheck[]");
      var comma = false;
      var arr="[";
      for(var i=0; i< check.length; i++){
        if (check[i].checked) {
          if (comma) {
            arr += ",";
          }
          arr +=check[i].value;
          //console.log(check[i].value);
          comma = true;
        }
      }
      arr += "]";
      document.getElementById("checkBoxArr").value = arr;
    }
    </script>
    <style>

	    .modal-dialog-custom {
		    max-width: 800px !important;
		    max-height: 80% !important;
		    margin: 1.75rem auto;
	    }
	    .modal-body-custom{height: auto;}
	    object{height: 65vh !important;overflow: hidden !important;}
        tr td {
            border-left: 1px solid #f2f2f2 !important;
        }
        tr.odd td {
            background-color: rgba(200,255,255,0.39);
            border-bottom: 1px solid #000000 !important;border-top: 1px solid #000000 !important;
        }
        input.form-check-input {
            margin: auto;
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
        <li class="breadcrumb-item active">View Payslip</li>
      </ol>
    </div>
    <?php
        if (isset($_SESSION['feedback'])) {
            echo $_SESSION['feedback'];
            unset($_SESSION['feedback']);
        }
    ?>
    <div class="container">
        <button class="btn btn-success btn-lg" data-toggle='modal' data-target='#emailModal' type="button">Email</button>
        <?php echo payslipTable()?>
    </div>


  </div>



    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fa fa-angle-up"></i>
    </a>
        <div class="footer">
            <p>Powered by JSoft Solution Sdn. Bhd</p>
          </div>

 <!-- PAYSLIP PDF Modal START-->
 <div class="modal fade" id="payslipPDFModal" tabindex="-1" role="dialog" aria-labelledby="payslipPDFModal" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-full modal-dialog-custom" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="payslipPDFModalTitle">View PDF</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body modal-body-custom">

            <div id='payslipPDFModalContent' >
            </div>

            <div id='paymentVoucherPDFModalContent' >
            </div>

          </div>
          <div class="modal-footer">

              <?php if($_SESSION["role"]==1 || $_SESSION["role"] == 42 || $_SESSION['ManagerRole'] ){ ?>
            <button type="button" id='editPayslip' data-toggle='modal' data-target='#editPayslipPDFModal' name="editPayslip" class="btn btn-success btn-lg">
              <i style='' class='fa fa-pencil' aria-hidden='true'></i>
              Edit
            </button>
            <button type="button" id='removePayslip' onclick="" name="removePayslip" class="btn btn-success btn-lg">
              <i style='' class='fa fa-trash-o' aria-hidden='true'></i>
              Remove
            </button>
           <?php   } ?>
            <button type="button" class="btn btn-secondary btn-lg" title="CLOSE DIALOG" data-dismiss="modal">
				      <i class="fa fa-times" aria-hidden="true"></i>
				      Close
			      </button>
          </div>
        </div>
      </div>
    </div>

  <!-- PAYSLIP PDF Model END -->

  <!-- EDIT PAYSLIP PDF Modal START-->
  <div class="modal fade " id="editPayslipPDFModal" tabindex="-1" role="dialog" aria-labelledby="editPayslipPDFModal" aria-hidden="true">
       <div class="modal-dialog modal-dialog-centered modal-full" role="document">
         <div class="modal-content">
           <div class="modal-header">
             <h5 class="modal-title">Edit Payslip</h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
             </button>
           </div>
           <form action="../../phpfunctions/payroll.php" method="post">
             <div class="modal-body">
               <div class="form-group row">
                   <!--DESIGNATION-->
                   <div class="col-md-6">
                     <label>Designation</label>
                     <input type="text" id="designationPost" class="form-control"  name="designation" required>
                   </div>

                   <!--DEPARTMENT-->
                   <div class="col-md-6">
                     <label>Department</label>
                     <input type="text" id="departmentPost" class="form-control"  name="department" required>
                   </div>
               </div>

               <div class="form-group row">
                 <!--SALARY MONTH-->
                 <div class="col-md-12">
                   <label>Salary(Month)</label>
                   <input type="number" id="salaryMonth" onchange="calculateEpf()" class="form-control" name="salaryMonth" min="0.00" step="0.01" required>
                 </div>
               </div>

               <div class="form-group row">
                 <!--EPF-->
                 <div class="col-md-12">
                   <label>EPF(%)</label>
                   <select id="epfPerc" onchange="calculateEpf()" class="form-control" name="epfPerc"  required>
                     <option  value="" selected disabled >--Select %--</option>
                     <option value="11">11%</option>
                     <option value="13">13%</option>
                   </select>
                 </div>
               </div>

	           <div class="form-group row">
		             <!--EPF EMPLOYEE-->
		             <div class="col-md-12">
			             <label>EPF EMPLOYEE</label>
			             <input type="number"  id="epfEmp" class="form-control epfEmp" onchange="checkValue(this)" name="epfEmp" min="0.00" step="0.01" required>
		             </div>
	           </div>
	             <div class="form-group row">
		             <!--EPF EMPLOYER-->
		             <div class="col-md-12">
			             <label>EPF EMPLOYER</label>
			             <input type="number" id="epfEmpyr" class="form-control epfEmpyr" onchange="checkValue(this)" name="epfEmpyr" min="0.00" step="0.01" required>
		             </div>
	           </div>

               <div class="form-group row">
                 <!--SOCSO-->
                 <div class="col-md-6">
                   <label>SOCSO(RM)</label>
                   <input type="number" id="socso" class="form-control socso" onchange="checkValue(this)" name="socso"  min="0.00" step="0.01" required>
                 </div>

                 <!--PCB-->
                 <div class="col-md-6">
                   <label>PCB(RM)</label>
                   <input type="number" id="pcb" class="form-control pcb" onchange="checkValue(this)" name="pcb"  min="0.00" step="0.01" required>
                 </div>
               </div>
	             <div class="form-group row">
		             <!--SOCSO-->
		             <div class="col-md-6">
			             <label>EIS(RM)</label>
			             <input type="number" id="eis" class="form-control eis" onchange="checkValue(this)"  name="eis"  min="0.00" step="0.01" required>
		             </div>

		             <!--PCB-->
		             <div class="col-md-6">

		             </div>
	             </div>
			   <div class="form-group row">
                 <!--Allowance-->
                 <div class="col-md-6">
                   <label>Allowance(RM)</label>
                   <input type="number" id="allowance" class="form-control" onchange="checkValue(this)"  name="allowance"  min="0.00" step="0.01"  required>
                 </div>

                 <!--Claims-->
                 <div class="col-md-6">
                   <label>Claims(RM)</label>
                   <input type="number" id="claims" class="form-control"  onchange="checkValue(this)" name="claims"  min="0.00" step="0.01"  required>
                 </div>
               </div>
			   <div class="form-group row">
                 <!--Commissions-->
                 <div class="col-md-6">
                   <label>Commissions(RM)</label>
                   <input type="number" id="commissions" class="form-control" onchange="checkValue(this)" name="commissions"  min="0.00"  step="0.01" required>
                 </div>

                 <!--OT-->
                 <div class="col-md-6">
                   <label>OT(RM)</label>
                   <input type="number" id="ot" class="form-control" onchange="checkValue(this)" name="ot"  min="0.00" step="0.01"  >
                 </div>
               </div>
                 <div class="form-group row">
                 <!--BONUS-->
                     <div class="col-md-12">
                         <label>BONUS(RM)</label>
                         <input type="number" id="bonus" class="form-control" onchange="checkValue(this)" name="bonus"  min="0.00" step="0.01" >
                     </div>
                </div>

               <div class="form-group row">
                 <!--DATE PAYMENT-->
                 <div class="col-md-12">
                   <label>Date Payment</label>
                   <input id="datePayment" class="form-control"  type="date" name="datePayment" required>
                 </div>
               </div>

               <div class="form-group row">
                 <!--BANK NAME-->
                 <div class="col-md-6">
                   <label>Bank Name</label>
                   <input id="bankName" class="form-control" type="text" name="bankName" required>
                 </div>

                 <!--BANK ACCOUNT-->
                 <div class="col-md-6">
                   <label>Bank Account No</label>
                   <input id="bankAcc" class="form-control" type="text" name="bankAcc" required>
                 </div>
               </div>

             </div>
             <div class="modal-footer">
              <input id="payslipId" name="payslipId" type="text" hidden>
	          <button type="submit" class="btn btn-secondary btn-lg" name="updatePayroll">
                Submit
              </button>
              <button type="button" class="btn btn-secondary btn-lg" title="CLOSE DIALOG" data-dismiss="modal">
   				     <i class="fa fa-times" aria-hidden="true"></i>
   				      Close
   			      </button>
             </div>
           </form>
         </div>
       </div>
     </div>

   <!-- EDIT PAYSLIP PDF Model END -->

   <!-- (START)EMAIL FORM -->
  <form method="POST" action="<?php echo "https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/phpfunctions/payroll.php" ?>" >
   <div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered" role="document">
       <div class="modal-content">
         <div class="modal-header">
           <h5 class="modal-title">Email as Attachment</h5>
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
           </button>
         </div>
         <div class="modal-body">
           <small><i>NOTE: Add "," in between Emails/CCs if more than 1 receiver</i></small>
           <div class="form-group row">
             <div class="col-md-12">
               <label>Email</label>
               <input id="emails" class="form-control" type="email" name="emails" multiple>
             </div>
           </div>

           <div class="form-group row">
             <div class="col-md-12">
               <label>CC</label>
               <input id="emailsCc" class="form-control" type="email" name="emailsCc" value="" multiple>
             </div>
           </div>

           <div class="form-group row">
             <div class="col-md-12">
               <input id="checkBoxArr" type="text" name="checkBoxArr" hidden>
               <button class="btn btn-primary btn-lg btn-block" type="submit" name="emailPayrollSelected">Send</button>
             </div>
           </div>
         </div>
       </div>
     </div>
   </div>
  </form>
   <!-- (END)EMAIL FORM -->

<script type="text/javascript">

	function removePayslip(payrollId){
		if (confirm("Are you sure you want to remove this payslip?")) {
			window.location.href = "../../phpfunctions/payroll.php?removePayslip=" + payrollId;
		}
	}

	function showPayslipDetails(payslipId){
		$.ajax({
			type  : 'GET',
			url  : '../../phpfunctions/payroll.php?',
			data : {loadPayslipDetails:payslipId},
			success: function (data) {
				details= JSON.parse(data);
				console.log(data);

				document.getElementById("salaryMonth").value = parseFloat(details.salaryMonth.replace(/,/g, '')).toFixed(2);
				document.getElementById("epfPerc").value = details.epfPerc;
				document.getElementById("socso").value = details.socso;
				document.getElementById("pcb").value = details.pcb;
				document.getElementById("eis").value = details.eis;
				document.getElementById("epfEmp").value = details.epfEmp;
				document.getElementById("epfPerc").value = details.epfRate;
				document.getElementById("epfEmpyr").value = details.epfEmpyr;
				document.getElementById("allowance").value = details.allowance;
				document.getElementById("claims").value = details.claims;
				document.getElementById("commissions").value = details.commissions;
				document.getElementById("ot").value = details.ot;
				document.getElementById("bonus").value = details.bonus;
				document.getElementById("datePayment").value = details.datePayment;
				document.getElementById("bankName").value = details.bankName;
				document.getElementById("bankAcc").value = details.bankAcc;
				document.getElementById("designationPost").value = details.designation;
				document.getElementById("departmentPost").value = details.department;
			}
		});
	}

	function calculateEpf(){
		var basicVal = document.getElementById("salaryMonth").value;
		var epfVal = document.getElementById("epfPerc").value;
		console.log("basicVal: " + basicVal);
		console.log("epfVal: " + epfVal);
		if(basicVal != "" && epfVal != ""){
			var totalEpf = (basicVal*epfVal)/100;
			console.log("totalEpf: " + totalEpf);
			document.getElementById("epfEmpyr").value = totalEpf;

		}
	}

	function checkValue(id){
		var x = parseFloat(id.value);
		id.value=x.toFixed(2);
	}

</script>
</body>

    <!-- datatable -->
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/jquery.dataTables.js'></script>
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/dataTables.bootstrap4.js'></script>
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/js/sb-admin-datatables.min.js'></script>
    <!-- datatable -->


</html>
