
<?php
$config = parse_ini_file(__DIR__ . "/../../jsheetconfig.ini");
if (!isset($_SESSION)) {
    session_name($config['sessionName']);
    session_start();
}

require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/clientComplaint.php";
require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/product.php";

require $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/job.php";
require $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/clientCompany.php";
require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/vendor.php";
require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/organization.php";

?>
<!DOCTYPE html >

<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">


    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/resources/app/favIcon.ico'; ?>' />


    <?php
require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/importScripts.php";
?>

    <!-- datatable -->
    <script src='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/'; ?>adminTheme/datatables/jquery.dataTables.js'></script>
    <script src='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/'; ?>adminTheme/datatables/dataTables.bootstrap4.js'></script>
    <script src='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/'; ?>adminTheme/js/sb-admin-datatables.min.js'></script>
    <!-- datatable -->

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
    tr td {
        border-left: 1px solid #f2f2f2 !important;
    }
    tr.odd td {
        background-color: rgba(200,255,255,0.39);
        border-bottom: 1px solid #000000 !important;border-top: 1px solid #000000 !important;
    }
    </style>
   <script>
     $(document).ready(function() {
       //debug data table
          $('#clientComplaintTable').DataTable({

              "order": [
                [ 0, "desc" ]
              ] ,
              "aoColumns": [
                  null,
                  { "sType": "date" },
                  null,
                  null,
                  null,
                  null,
                  null,

                ]

            });
           $('.downloadExcel').on('click',function(){
        id=1;
        $.ajax({
              type  : 'GET',
              url  : '../../phpfunctions/clientComplaint.php?',
              data : {completeexcel:id},
              success: function (data) {
                console.log("\n\n ajax response json data:\n"+data);
              //  details= JSON.parse(data);
                    var downloadLink = document.createElement("a");
                    var fileData = ['\ufeff'+data];

                    var blobObject = new Blob(fileData,{
                      type: "text/csv;charset=utf-8;"
                    });
                    var d = new Date();
                    var n = d.toLocaleDateString()+" "+d.toLocaleTimeString();
                    var url = URL.createObjectURL(blobObject);
                    downloadLink.href = url;
                    downloadLink.download = "Closed Incident"+n+".csv";

                    /*
                    * Actually download CSV
                    */
                    document.body.appendChild(downloadLink);
                    downloadLink.click();
                    document.body.removeChild(downloadLink);
              }
            });
      });





      });

    /*
    $(document).ready(function() {
      $('#clientComplaintTable tbody').on('click','td',function() {

          if ($(this).index() == 4 ) {
              return;
          }
          $("#forceUpdateComplaintStatus").attr("hidden", true);
          var id = $(this).parents().data('value');
            $.ajax({
              type  : 'GET',
              url  : '../../phpfunctions/clientComplaint.php?',
              data : {complaintId:id},
              success: function (data) {
                details= JSON.parse(data);
                var issueName=details.issueName;
                var issueDetails=details.issueDetail;
                var requiredDate=details.requireDate;

                $('#problem').prop('value',issueName);
                $('#problemDetails').prop('value',issueDetails);
                $('#bookingDate').prop('value',requiredDate);
                $("#complaintIdValue2").prop('value',id);

              }
            });

            $.ajax({
              type  : 'GET',
              url  : '../../phpfunctions/clientCompany.php?',
              data : {clientCheckboxTable:id},
              success: function (data) {
                document.getElementById("tableProduct").innerHTML = data;
              }
            });

             $.ajax({
              type  : 'GET',
              url  : '../../phpfunctions/clientComplaint.php?',
              data : {checkIfJob:id},
              success: function (data) {

                details= JSON.parse(data);
                if(details!=null){
                  $("#forceUpdateComplaintStatus").attr("hidden", false);
                }



              }
            });

          $('#messageContent').modal('toggle');

      });
    });

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

    function resetWorkerModal(str){

      var worker = $('#selectWorker');
      worker.empty();
      document.getElementById("workerType").selectedIndex = "0";
      document.getElementById("assignWorker").disabled = true;
      document.getElementById("assignWorker").classList.remove('btn-primary');
      document.getElementById("assignWorker").classList.add('btn-light');
      document.getElementById("complaintIdValue").value = str;

    } */

      function clientId(clientId,complaintId,jobId) {
        /*  $.ajax({

              type  : 'GET',
              url  : '../../phpfunctions/clientCompany.php?',
              data : {clientProductTableComplaint:complaintId},
              success: function (data) {
                document.getElementById("tableProduct").innerHTML = data;
                console.log(data);
                document.getElementById("complaintIdValue3").value = complaintId;
              }
          }); */
          console.log(jobId);
        $.ajax({
            type  : 'GET',
            url  : '../../phpfunctions/report.php?',
            data : {staffJobDetail:jobId},
            success: function (data) {
              $('#lookjobid').prop('value',jobId);
                 var taskDiv=$("#printModelContent");
                 taskDiv.empty().append(data);

                 //console.log(data);
            }
        });


      }

   </script>
</head>
<body class="fixed-nav ">

<?php
include $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/navMenu.php";
?>

  <div class="content-wrapper">
<?php if(isset($_SESSION['feedback'])){ ?>
    <div class="container">
      <div class="row">
        <div class="col-12">
      <?php echo "".$_SESSION['feedback']; ?>
        </div>
      </div>
    </div>
    <?php } ?>
    <div class="container-fluid">
    <?php echo shortcut() ?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb col-md-12">
        <li class="breadcrumb-item">
          <a href="../../home.php">Dashboard</a>
        </li>
        <li class="breadcrumb-item">Incident </li>
        <li class="breadcrumb-item active">Completed</li>
      </ol>
    </div>
        <div class='card mb-3'>
		      <div class='card-header'>
					  <i class='fa fa-table'></i>
            Completed Job
				  </div>
          <div class='card-body'>
               <div class='table-responsive'>

                  <?php
$table = orgComplaintListTable(null, null, null, null, 0, null, $_SESSION['orgId']);
echo $table;
?>
                </div>
          </div>
          <div class="btn btn-primary downloadExcel" style="width:150px; background-color: green">Download&nbsp;&nbsp;<img src="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/'; ?>resources/app/excel.png" width="16px"/></div>
	      </div>

    </div>

       <!-- Messsage details Modal START
   <form method="POST" action="../../phpfunctions/job.php" class="needs-validation" novalidate >

      <div class="modal fade" id="messageContent" tabindex="-1" role="dialog" aria-labelledby="messageContentCenterTitle" aria-hidden="true">


          <div class="modal-dialog modal-dialog-centered modal-messageDetails" role="document">



              <div class="modal-content">
          <!-- Start modal header
                <div class="modal-header">

                  <h5 class="modal-title" id="messageContentTitle">UPDATE DETAILS</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>


                </div>
          <!-- End modal header

          <!-- Start modal body
              <div class="modal-body">
                  <div class="form-group row">
                    <label for="problem" class="col-sm-2 col-form-label col-form-label-lg">PROBLEM</label>
                    <div class="col-sm-10"   >
                      <input readonly type="text" class="form-control" id="problem" name="problem"></input>
                      <div class="invalid-feedback">

                     </div>
                    </div>
                </div>
                <input type='text' hidden value='' name='complaintIdValue' id='complaintIdValue2' />
                <div class="form-group row">
                  <label for="problemDetails" class="col-sm-2 col-form-label col-form-label-lg">PROBLEM DESCRIPTION</label>
                  <div class="col-sm-10"  >
                    <textarea readonly class="form-control" id="problemDetails" rows="5" name="problemDetails"
                    ></textarea>
                    <div class="invalid-feedback">

                    </div>
                  </div>
                </div>

              <?php
if ($config['customerComplaintFormBookingDate'] == true) {
    ?>
                <div class="form-group row" >
                  <label for="bookingDate" class="col-sm-2 col-form-label col-form-label-lg">BOOKING DATE</label>
                  <div class="col-sm-10"   >
                    <input readonly class="form-control" type="text" value="<?php echo date('Y-M-d'); ?>" id="bookingDate" name="bookingDate">
                    <div class="invalid-feedback">

                    </div>
                  </div>
                </div>
              <?php
}
?>

              <div class="form-group row">
                <label for="product" class="col-sm-2 col-form-label col-form-label-lg">PRODUCT</label>
                <div class="col-sm-10">
                  <div id="tableProduct" class="">

                  </div>
                </div>
              </div>
              </div>
          <!-- End modal body


            <!-- Start modal footer
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">CLOSE</button>

              </div>
            <!-- End modal footer

            </div>



          </div>

        </div>
      </form>
          <!-- MEssage details Modal END -->
          <form method="POST" action="../../phpfunctions/job.php" class="needs-validation" novalidate >
          <!-- JOB Print Modal START-->
          <div class="modal fade" id="printModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="printModalTitle">Print</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <!-- (START)BANNER -->
                  	<img src='../../resources/2/myOrg/banner/<?php
$orgId = $_SESSION['orgId'];
$bannerName = bannerName($orgId);
echo $bannerName;
?>' width='100%' style="max-width: 700px">
                  <!-- (END)BANNER -->

                  <div id='printModelContent' >
                  </div>
                  <?php if (isset($_SESSION['orgType']) && $_SESSION['orgType'] == 6) {?>
                    <div class="form-group row mt-3">
                      <div class="col-12">
                          <select name='status' class='form-control' id='status'>
                            <option value="0" selected>COMPLETE</option>
                            <option value="5">VERIFICATION</option>
                            <option value="6">CLOSED</option>
                         <?php //   <option value="7">REOPEN</option> ?>
                          </select>
                      </div>
                    </div> 
                    <input type="hidden" name="lookjobid" id="lookjobid" readonly />
                    <?php }?>
                </div>
                <div class="modal-footer">
                <?php if (isset($_SESSION['orgType']) && $_SESSION['orgType'] == 6 ) {?>
                  <button type="submit" class="btn btn-secondary" name="updatecompletestatus" id="updatecompletestatus">UPDATE STATUS</button>
                <?php } else {?>
               <a href="../report/print.php" target="_blank"><button type="button" class="btn btn-secondary" >Print</button></a>
                <?php }?>

                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                </div>
              </div>
            </div>
          </div>
          <!-- JOB Print Model END -->
          </form>
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

