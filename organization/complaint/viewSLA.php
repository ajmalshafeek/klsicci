<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");

if(!isset($_SESSION))
{
  session_name($config['sessionName']);
  session_start();
}

?>
<!DOCTYPE html>

<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">


    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="300; URL=<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/complaint/viewSLA.php'; ?>">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />


    <?php
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientComplaint.php");
    ?>
    <!-- datatable -->
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/jquery.dataTables.js'></script>
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/dataTables.bootstrap4.js'></script>
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/js/sb-admin-datatables.min.js'></script>
    <!-- datatable -->
    </head>
    <script>

    $(document).ready(function() {

     $('#clientComplaintTable').DataTable({
       /* drawCallback: function(){
          // Alter pagination control
          $('.form-control-sm', this.api().table().container()).addClass('form-control-lg');
       },
       */
          "order": [
            [ 0, "desc" ]
          ] ,
          "aoColumns": [
              { "sType": "date" },
              null,
              null,
              null,
              null,
              null
            ]
        });

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

          /*  $.ajax({
              type  : 'GET',
              url  : '../../phpfunctions/clientCompany.php?',
              data : {clientCheckboxTable:id},
              success: function (data) {
                document.getElementById("tableProduct").innerHTML = data;
              }
            }); */

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

      function changeWorkTypeReassign(str){
 //      document.getElementById("assignWorker").disabled = true;
       //document.getElementById("assignWorker").classList.remove('btn-primary');
       //document.getElementById("assignWorker").classList.add('btn-light');
         $.ajax({
             type  : 'GET',
               url  : '../../phpfunctions/clientComplaint.php?',
               data : {workerType:str},
               success: function (data) {

                 var worker = $('#selectWorkerReassign');
                 worker.empty().append(data);
                 var noOfList=document.getElementById("workerId").length;
                 if(noOfList>0){
                   document.getElementById("reassignWorker").disabled = false;
                   document.getElementById("reassignWorker").classList.remove('btn-light');
                   document.getElementById("reassignWorker").classList.add('btn-primary');
                 }

               }
         });
       }

      function clientId(clientId,complaintId) {
          $.ajax({

              type  : 'GET',
              url  : '../../phpfunctions/clientCompany.php?',
              data : {clientCheckboxTableEdit:clientId,clientCheckboxTableEditComplaint:complaintId},
              success: function (data) {
                document.getElementById("tableProduct").innerHTML = data;
                document.getElementById("complaintIdValue3").value = complaintId;
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

    }

    </script>
  <style>

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

.modal-messageDetails {
    min-width: 60%;
}

    #clientComplaintTable{
      cursor:pointer;
    }
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

   <?php
  include $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/navMenu.php";
?>
<body class="fixed-nav ">
  <div class="content-wrapper">
    <div class="container-fluid">
    <?php echo shortcut() ?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb col-md-12">
        <li class="breadcrumb-item">
          <a href="../../home.php">Dashboard</a>
        </li>

        <li class="breadcrumb-item">Incident</li>
        <li class="breadcrumb-item active">SLA Time Frame</li>
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
					  SLA Time Frame
				  </div>

          <div class='card-body' >
              <div class='table-responsive'>

                <?php
                $table=orgComplaintListTableSLA(null,null,null,null,"UNRESOLVED",null,$_SESSION['orgId']);
                echo $table
                ?>
                </div>
          </div>
	      </div>

    </div>


    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fa fa-angle-up"></i>
    </a>
    <form method="POST" action="../../phpfunctions/job.php" class="needs-validation" novalidate >
    <input type='text' hidden value='' name='complaintIdValue' id='complaintIdValue' />
     <input type='text' hidden value='' name='workerIdValue' id='workerIdValue' />
     <div class="modal fade" id="assignWorkerModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">


      <div class="modal-dialog modal-dialog-centered " role="document">



          <div class="modal-content">

            <div class="modal-header">

              <h5 class="modal-title" id="assignWorkerModelTitle">ASSIGN WORKER</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>


            </div>

          <div class="modal-body">

            <div class="form-group">
              <label for="email" >Assign To</label>
              <select name='workerType' class='form-control' id='workerType' onchange="changeWorkType(this.value)">
                <option selected disabled value="default" >--Select--</option>
                <option value="myStaff">My Staff</option>
                <option value="vendors">Vendors</option>
              </select>
            </div>

            <div id='selectWorker' >
            </select>


          </div>
          </div>
          <div class="modal-footer">

            <button type="submit" id="assignWorker" name="assignWorker" disabled class="btn btn-light" >Confirm</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>

          </div>

        </div>



      </div>

    </div>



    </form>

   <!-- Messsage details Modal START-->
   <form method="POST" action="../../phpfunctions/job.php" class="needs-validation" novalidate >

      <div class="modal fade" id="messageContent" tabindex="-1" role="dialog" aria-labelledby="messageContentCenterTitle" aria-hidden="true">


          <div class="modal-dialog modal-dialog-centered modal-messageDetails" role="document">



              <div class="modal-content">
          <!-- Start modal header -->
                <div class="modal-header">

                  <h5 class="modal-title" id="messageContentTitle">Update Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>


                </div>
          <!-- End modal header -->

          <!-- Start modal body -->
              <div class="modal-body">
                  <div class="form-group row">
                    <label for="problem" class="col-sm-2 col-form-label col-form-label-lg">PROBLEM</label>
                    <div class="col-sm-10"   >
                      <input type="text" class="form-control" id="problem" name="problem"></input>
                      <div class="invalid-feedback">

                     </div>
                    </div>
                </div>
                <input type='text' hidden value='' name='complaintIdValue' id='complaintIdValue2' />
                <div class="form-group row">
                  <label for="problemDetails" class="col-sm-2 col-form-label col-form-label-lg">PROBLEM DESCRIPTION</label>
                  <div class="col-sm-10"  >
                    <textarea class="form-control" id="problemDetails" rows="5" name="problemDetails"
                    ></textarea>
                    <div class="invalid-feedback">

                    </div>
                  </div>
                </div>

              <?php
                if($config['customerComplaintFormBookingDate']==true)
                {
              ?>
                <div class="form-group row" >
                  <label for="bookingDate" class="col-sm-2 col-form-label col-form-label-lg">BOOKING DATE</label>
                  <div class="col-sm-10"   >
                    <input class="form-control" type="text" value="<?php echo date('Y-M-d'); ?>" id="bookingDate" name="bookingDate">
                    <div class="invalid-feedback">

                    </div>
                  </div>
                </div>
              <?php
                }
              ?>
              <?php
              if ($_SESSION['orgType']==1) {
                additionalDetail($_SESSION['orgType']);
              }
              ?>
              </div>
          <!-- End modal body -->


            <!-- Start modal footer -->
              <div class="modal-footer">
                <!--<button type="button" data-toggle="modal" data-target="#reassign" class="btn btn-primary" style="" >REASSIGN</button> -->
                <button type="submit" id="forceUpdateComplaintStatus" name="forceUpdateComplaintStatus" class="btn btn-primary" style="width:200px;" >MARK AS COMPLETE</button>
                <button type="submit" id="forceUpdateComplaint" name="forceUpdateComplaint" class="btn btn-primary" style="" >EDIT</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">CLOSE</button>

              </div>
            <!-- End modal footer -->

            </div>



          </div>

        </div>
      </form>
          <!-- MEssage details Modal END -->

<!-- (START)REASSIGN -->
<!-- Messsage details Modal START-->
<form method="POST" action="../../phpfunctions/job.php" class="needs-validation" novalidate >

   <div class="modal fade" id="reassign" tabindex="-1" role="dialog" aria-labelledby="reassignTitle" aria-hidden="true">


       <div class="modal-dialog modal-dialog-centered modal-messageDetails" role="document">



           <div class="modal-content">
       <!-- Start modal header -->
             <div class="modal-header">

               <h5 class="modal-title" id="reassignTitle">REASSIGN STAFF</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
                 </button>


             </div>
       <!-- End modal header -->

       <!-- Start modal body -->
           <div class="modal-body">
             <div class="form-group">
               <label for="email" >REASSIGN TO</label>
              <input type='text' hidden value='' name='complaintIdValue' id='complaintIdValue3' />
               <select name='workerType' class='form-control' id='workerType' onchange="changeWorkTypeReassign(this.value)">
                 <option selected disabled value="default" >--SELECT--</option>
                 <option value="myStaff">MY STAFF</option>
                 <option value="vendors">VENDORS</option>
               </select>
             </div>

             <div id='selectWorkerReassign' >
             </select>


           </div>
           </div>
       <!-- End modal body -->


         <!-- Start modal footer -->
           <div class="modal-footer">
             <button type="submit" id="reassignWorker" name="reassignWorker" disabled class="btn btn-light" >CONFIRM</button>
             <button type="button" class="btn btn-secondary" data-dismiss="modal">CLOSE</button>
           </div>
         <!-- End modal footer -->

         </div>



       </div>

     </div>
   </form>
       <!-- MEssage details Modal END -->
<!-- (END)REASSIGN -->

          <div class="footer">
            <p>Powered by JSoft Solution Sdn. Bhd</p>
          </div>
  </div>
</body>
</html>
