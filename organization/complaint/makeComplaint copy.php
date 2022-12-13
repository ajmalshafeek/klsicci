
<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
  session_name($config['sessionName']);
  session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientComplaint.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/configuration.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/organization/complaint/moreForm/form.php");

?>
<!DOCTYPE >

<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">


    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />


    <link rel="stylesheet" href="../../css/bootstrap.min.css">
  <!--
    <script src="../js/jquery-3.3.1.slim.min.js" ></script>

    <script src="../../js/bootstrap.min.js" ></script>

    <script type="text/javascript" src="../../js/jquery-3.3.1.min.js"></script>
-->
<?php
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>
	  <script>
    function clientId() {
      /*
        var id = document.getElementById("clientCompanyId").value;
        console.log("companyId: " + id);
        $.ajax({

            type  : 'GET',
            url  : '../../phpfunctions/clientCompany.php?',
            data : {clientCheckboxTable:id},
            success: function (data) {
              document.getElementById("tableProduct").innerHTML = data;
            }
        });
        */
    }

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


        function toggleJobField(str){

          var id=str;
          if(id==0)
          {
            document.getElementById("jobName").style.display = "block";
          }else{
            document.getElementById("jobName").style.display = "none";
          }

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

    table {
      border-collapse: collapse;
      width: 100%;
    }

    th{
      background: grey;
      color: black;
    }

    table, td, th {
      border: 1px solid black;
      text-align: center;
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
        <li class="breadcrumb-item">Incident</li>
        <li class="breadcrumb-item active">Make Incident</li>
      </ol>
    </div>

        <div class="container" >
          <form method="POST" action="../../phpfunctions/clientComplaint.php" class="needs-validation" novalidate >
            <div id="jobListForm">
              <?php
                  if (isset($_SESSION['feedback'])) {
                      echo $_SESSION['feedback'];
                      unset($_SESSION['feedback']);
                  }
              ?>


            <script type="text/javascript">
            function postCheckTrue()   {
               form = document.createElement('form');
               form.setAttribute('method', 'POST');
               form.setAttribute('action', 'makeComplaint.php');
               myvar = document.createElement('input');
               myvar.setAttribute('name', 'clientForm');
               myvar.setAttribute('type', 'hidden');
               myvar.setAttribute('value', '1');
               form.appendChild(myvar);
               document.body.appendChild(form);
               form.submit();
            }

            function checkClient(){
              var check = document.getElementById("checkClientForm").checked;
              if (check) {
                postCheckTrue();
              }else{
                window.location = 'makeComplaint.php';
              }
            }

            function generateInvoiceForm(){
              var check = document.getElementById("checkServiceCharge").checked;
              if (check) {
                document.getElementById("serviceCharge").style.display = "block";
                document.getElementById("invoiceDueDate").style.display = "block";
                document.getElementById("attention").style.display = "block";
                document.getElementById("footer").style.display = "block";

                document.getElementById("serviceChargeForm").required = true;
                document.getElementById("invoiceDueDateForm").required = true;
                document.getElementById("attentionForm").required = true;
                document.getElementById("footerForm").required = true;
              }else {
                document.getElementById("serviceCharge").style.display = "none";
                document.getElementById("invoiceDueDate").style.display = "none";
                document.getElementById("attention").style.display = "none";
                document.getElementById("footer").style.display = "none";

                document.getElementById("serviceChargeForm").required = false;
                document.getElementById("invoiceDueDateForm").required = false;
                document.getElementById("attentionForm").required = false;
                document.getElementById("footerForm").required = false;
              }
            }
            </script>
            <div class="form-check">
              <input type="checkbox" onclick="generateInvoiceForm()" class="form-check-input" name="checkServiceCharge" id="checkServiceCharge">
              <label class="form-check-label">Generate Invoice</label>
            </div>
            <div class="form-check">
              <input type="checkbox" onclick="checkClient()" class="form-check-input" id="checkClientForm" <?php if(isset($_POST['clientForm'])){ echo "checked"; } ?>>
              <label class="form-check-label">Client</label>
            </div>
            <?php
            $data = fetchOrganizationDetails($_SESSION['orgId']);
            if (!isset($_POST['clientForm'])): ?>
              <div class="form-group row">
                <label for="problem" class="col-sm-2 col-form-label col-form-label-lg"></label>
                <div class="col-sm-10"   >
                  <textarea class="form-control" rows="1" readonly><?php echo $data['name']  ?></textarea>
                  <input type="text" name="clientCompanyId" value="0" hidden>
                  <div class="invalid-feedback">
                  Please enter a problem
                </div>
                </div>
              </div>
            <?php endif; ?>
            <?php if (isset($_POST['clientForm'])): ?>
              <div class="form-group row">
                <label for="problem" class="col-sm-2 col-form-label col-form-label-lg">Client</label>
                <div class="col-sm-10"   >
                <?php
                require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientCompany.php");

                dropDownListOrganizationClientCompanyActive3();
                ?>
                  <div class="invalid-feedback">
                  Please enter a problem
                </div>
                </div>
              </div>
            <?php endif; ?>
            <div class="form-group row">
              <label for="problem" class="col-sm-2 col-form-label col-form-label-lg">Task</label>
              <div class="col-sm-10"   >
                <input type="text" class="form-control" placeholder="Enter task name" id="problem" name="problem" required></input>
                <div class="invalid-feedback">
                Please enter a problem
              </div>
              </div>
            </div>

            <div class="form-group row">
              <label for="problemDetails" class="col-sm-2 col-form-label col-form-label-lg">Task Description</label>
              <div class="col-sm-10"   >
                <textarea class="form-control" placeholder="Enter task description" id="problemDetails" name="problemDetails"></textarea>
                <div class="invalid-feedback">

                </div>
              </div>
            </div>

            <div id="attention" style="display:none">
              <div class="form-group row">
                <label for="attention" class="col-sm-2 col-form-label col-form-label-lg">Invoice Attention</label>
                <div class="col-sm-10"   >
                  <input id="attentionForm" class="form-control" type="text" name="attention">
                  <div class="invalid-feedback">

                  </div>
                </div>
              </div>
            </div>

            <div id="serviceCharge" style="display:none">
              <div class="form-group row">
                <label for="serviceCharge" class="col-sm-2 col-form-label col-form-label-lg">Service Charge(RM)</label>
                <div class="col-sm-10"   >
                  <input id="serviceChargeForm" class="form-control" type="number" name="serviceCharge" placeholder="RM" min="0.00" max="1000000000.00" step="0.01">
                  <div class="invalid-feedback">
                    Please put in XX.XX format(Exp: 10.50 @ 1000.00)
                  </div>
                </div>
              </div>
            </div>

            <!-- HYDROKLEEN GLOBAL -->
            <?php
              if($config['customerComplaintFormBookingDate']==true)
              {
            ?>
              <div class="form-group row" >
                <label for="bookingDate" class="col-sm-2 col-form-label col-form-label-lg">Assigned Date</label>
                <div class="col-sm-10"   >
                  <input class="form-control" type="date" value="<?php echo date('Y-m-d'); ?>" id="bookingDate" name="bookingDate">
                  <div class="invalid-feedback">

                  </div>
                </div>
              </div>
              <div class="form-group row" >
                <label for="bookingTime" class="col-sm-2 col-form-label col-form-label-lg">Assigned Time</label>
                <div class="col-sm-10"   >
                  <input class="form-control" type="time" value="<?php echo date('H:i'); ?>" id="bookingTime" name="bookingTime">
                  <div class="invalid-feedback">

                  </div>
                </div>
              </div>
            <?php
              }
            ?>

            <div id="invoiceDueDate" style="display:none;">
              <div class="form-group row">
                <label for="serviceCharge" class="col-sm-2 col-form-label col-form-label-lg">Invoice Due Date</label>
                <div class="col-sm-10"   >
                  <input id="invoiceDueDateForm" class="form-control" type="date" name="invoiceDueDate">
                  <div class="invalid-feedback">

                  </div>
                </div>
              </div>
            </div>

            <div id="footer" style="display:none;">
              <div class="form-group row">
                <label for="footer" class="col-sm-2 col-form-label col-form-label-lg">PDF Footer</label>
                <div class="col-sm-10"   >
                  <select id="footerForm" class="form-control" name="footer">
                    <?php
                      $footerId = null;
                      $footerList=getPdfFooterList($footerId,$_SESSION['orgId']);

                      //$footerNote="";
                      foreach ($footerList as $footer) {
                        $selected="";
                        if($footer['id']==$footerId){
                          $selected="selected";
                        //	$footerNote=$footer['content'];
                        }
                        echo "<option $selected value='".$footer['id']."' >".$footer['name']."</option>";
                      }
                    ?>
                  </select>
                </div>
              </div>
            </div>

            <?php additionalForm($_SESSION['orgType'],isset($module[20])); ?>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label col-form-label-lg"></label>
                <div class="col-sm-10">
                    <button name='makeComplaint'  class="btn btn-primary btn-lg btn-block" type='submit' >Submit</button>
                </div>
            </div>
          </form>
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
</body>
</html>
