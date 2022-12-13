<?php

$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");



if(!isset($_SESSION))

{

  session_name($config['sessionName']);

  session_start();

}

	ini_set('display_errors', 1);

?>

<!DOCTYPE html>



<html>

<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">





    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />





    <?php

      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");

      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientComplaint.php");

      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/organization/complaint/moreForm/form.php");

    ?>

    <!-- datatable -->

    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/jquery.dataTables.js'></script>

    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/dataTables.bootstrap4.js'></script>

    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/js/sb-admin-datatables.min.js'></script>

    <!-- datatable -->
<style>
    .wheel {
        border: 5px solid #f3f3f3;
        border-radius: 50%;
        border-top: 5px solid #00b7ff;
        border-bottom: 5px solid #00b7ff;
        width: 50px;
        height: 50px;
        margin-left: auto;
        margin-right: auto;
        -webkit-animation: spin 2s linear infinite;
        animation: spin 2s linear infinite;
    }
    @-webkit-keyframes spin {
        0% { -webkit-transform: rotate(0deg); }
        100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    </style>
    </head>

    <script>



    $(document).ready(function() {



     $('#clientComplaintTable').DataTable({
         buttons: [
             'copyHtml5',
             'excelHtml5',
             'csvHtml5',
             'pdfHtml5'
         ],
           "order": [ [ 1, "desc" ]    ] ,

          "aoColumns": [null, { "sType": "date" },
        <?php if($_SESSION['externalUse']){echo  "null,";} ?>
              null,
              null,
              null,
              null]
     });

       /* var table = $('#clientComplaintTable').DataTable();

        new $.fn.dataTable.Buttons( table, {
            buttons: [
                'copy', 'excel', 'pdf'
            ]
        } );  */

      $('.downloadExcel').on('click',function(){

        id=1;

        $.ajax({

              type  : 'GET',

              url  : '../../phpfunctions/clientComplaint.php?',

              data : {uncompleteexcel:id},

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

      $('#clientComplaintTable tbody').on('click','td',function() {



          if ($(this).index() == 5 ) {

              return;

          }

          $("#forceUpdateComplaintStatus").attr("hidden", true);

          var id = $(this).parents().data('value');

            $.ajax({

              type  : 'GET',

              url  : '../../phpfunctions/clientComplaint.php?',

              data : {complaintId:id},

              success: function (data) {

                //data = data.substring(1);

                details= JSON.parse(data);

	              console.log(details);


                var issueName=details.issueName;

                var issueDetails=details.issueDetail;

                var requiredDate=details.requireDate;
				var file=details.fileAttach;



                $('#problem').prop('value',issueName);

                $('#problemDetails').prop('value',issueDetails);
                  var url="";
                if(file!=null) {
                    var url = '../../resources/2/complaint/' + file;
                    $('.fileAttachBlock').css("display","block");
                    $('.attach').css("display","block");
                    $('.attach').attr("href", url);
                    $('.attach').text("Download "+file);
                }else{
                    $('.fileAttachBlock').css("display","none");
                    $('.attach').attr("href", url);
                    $('.attach').text("Download");
                    $('.attach').css("display","none");

                }

                <?php if($_SESSION['orgType']==6 && isset($_SESSION['orgType'])){ ?>

                $('#cpa').prop('value',details.cpa);

                $('#category').prop('value',details.category);

                $('#state').prop('value',details.state);

              <?php // $('#region').prop('value',details.region); ?>

                $('#vsattechnology').prop('value',details.vsattechnology);

                $('#sitename').prop('value',details.sitename);

                $('#note').html(details.note);

                $('#remarklog').html(details.remarkslog);

               <?php  }?>

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

                //document.getElementById("tableProduct").innerHTML = data;

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
  tr td {
      border-left: 1px solid #f2f2f2 !important;
  }
  tr.odd td {
      background-color: rgba(200,255,255,0.39);
      border-bottom: 1px solid #000000 !important;border-top: 1px solid #000000 !important;
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



        <li class="breadcrumb-item">Complaint</li>

        <li class="breadcrumb-item active">Incomplete</li>

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

					  Incomplete Job

				  </div>



          <div class='card-body' >

              <div class='table-responsive'>



                <?php

                $table=orgComplaintListTable(null,null,null,null,"UNRESOLVED",null,$_SESSION['orgId']);

                echo $table

                ?>

                </div>

          </div>

          <div class="btn btn-primary downloadExcel" style="width:150px; background-color: green">Download&nbsp;&nbsp;<img src="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>resources/app/excel.png" width="16px"/></div>

	      </div>



    </div>


  <?php if($_SESSION['staffRole']){ ?></div><?php } ?>
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



              <h5 class="modal-title" id="assignWorkerModelTitle">ASSIGN <?php if($_SESSION['orgType']==7){?>DRIVER <?php } else { ?>STAFF<?php } ?></h5>
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
            <button type="submit" id="assignWorker" name="assignWorker" onclick="loader()" disabled class="btn btn-light" >Confirm</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          </div>



        </div>







      </div>



    </div>







    </form>



   <!-- Messsage details Modal START-->

  <?php if($_SESSION['editcomplaint']&&$_SESSION['orgType']==6){ ?>

  <?php echo '<form method="POST" action="./updateComplaint.php" class="needs-validation" novalidate >'; ?>

  <?php } else { ?>

   <form method="POST" action="../../phpfunctions/job.php" class="needs-validation" novalidate >

       <?php } ?>

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
                    <label for="problem" class="col-sm-2 col-form-label col-form-label-lg"><?php if($_SESSION['orgType']==7){?>DELIVERY <?php }else { ?>PROBLEM<?php } ?></label>
                    <div class="col-sm-10" >
                      <input type="text" class="form-control" id="problem" name="problem" />
                        <div class="invalid-feedback">
                        </div>
                    </div>
                </div>
                <input type='text' hidden value='' name='complaintIdValue' id='complaintIdValue2' />

                <div class="form-group row">

                  <label for="problemDetails" class="col-sm-2 col-form-label col-form-label-lg"><?php if($_SESSION['orgType']==7){?>DELIVERY <?php }else { ?>PROBLEM<?php } ?> DESCRIPTION</label>

                  <div class="col-sm-10"  >

                    <textarea class="form-control" id="problemDetails" rows="5" name="problemDetails"

                    ></textarea>

                    <div class="invalid-feedback">



                    </div>

                  </div>

                </div>



              <?php

              if ($_SESSION['orgType']!=6) {

                  if ($config['customerComplaintFormBookingDate']==true) {

                      ?>

                <div class="form-group row" >

                  <label for="bookingDate" class="col-sm-2 col-form-label col-form-label-lg">CREATED DATE</label>

                  <div class="col-sm-10"   >

                    <input class="form-control" type="text" value="<?php echo date('Y-M-d'); ?>" id="bookingDate" name="bookingDate">

                    <div class="invalid-feedback">



                    </div>

                  </div>

                </div>

              <?php

                  }

              }

                if($_SESSION['orgType']==6){

              ?>

              <div class="form-group row" >

                  <label for="cpa" class="col-sm-2 col-form-label col-form-label-lg">CPA/VSAT ID</label>

                  <div class="col-sm-10"   >

                    <input class="form-control" type="text" value="" id="cpa" name="cpa" readonly>

                    <div class="invalid-feedback">



                    </div>

                  </div>

                </div>

                <div class="form-group row" >

                  <label for="category" class="col-sm-2 col-form-label col-form-label-lg">CATEGORY</label>

                  <div class="col-sm-10"   >

                    <input class="form-control" type="text" value="" id="category" name="category" readonly>

                    <div class="invalid-feedback">



                    </div>

                  </div>

                </div>

                <div class="form-group row" >

                  <label for="state" class="col-sm-2 col-form-label col-form-label-lg">STATE</label>

                  <div class="col-sm-10"   >

                    <input class="form-control" type="text" value="" id="state" name="state" readonly>

                    <div class="invalid-feedback">



                    </div>

                  </div>

                </div>

                    <?php /*

                <div class="form-group row" >

                  <label for="region" class="col-sm-2 col-form-label col-form-label-lg">REGION</label>

                  <div class="col-sm-10"   >

                    <input class="form-control" type="text" value="" id="region" name="region" readonly>

                    <div class="invalid-feedback">



                    </div>

                  </div>

                </div>

                     */ ?>

                <div class="form-group row" >

                  <label for="vsattechnology" class="col-sm-2 col-form-label col-form-label-lg">VSAT TECH</label>

                  <div class="col-sm-10"   >

                    <input class="form-control" type="text" value="" id="vsattechnology" name="vsattechnology" readonly>

                    <div class="invalid-feedback">



                    </div>

                  </div>

                </div>

                <div class="form-group row" >

                  <label for="sitename" class="col-sm-2 col-form-label col-form-label-lg">SITENAME</label>

                  <div class="col-sm-10"   >

                    <input class="form-control" type="text" value="" id="sitename" name="sitename" readonly>

                    <div class="invalid-feedback">



                    </div>

                  </div>

                </div>

                <div class="form-group row">

                    <label for="remarks" class="col-sm-2 col-form-label col-form-label-lg">REMARKS LOG</label>

                    <div class="col-sm-10" id="remarklog"  >



                        </div>

                    </div>

                <div class="form-group row">

                    <label for="remarks" class="col-sm-2 col-form-label col-form-label-lg">NOTES LOG</label>

                    <div class="col-sm-10" id="note"  >



                        </div>

                    </div>

                  <?php if(!$_SESSION['editcomplaint']){ ?>

               <div class="form-group row">

                    <label for="remarks" class="col-sm-2 col-form-label col-form-label-lg">YOUR REMARKS</label>

                    <div class="col-sm-10"   >

                      <input type="text" class="form-control" id="remarks" name="remarks"/>

                        <div class="invalid-feedback">



                        </div>

                    </div>

                </div>

                    <?php } ?>



              <?php

              }

              if ($_SESSION['orgType']==1) {

                additionalDetail($_SESSION['orgType']);

              }

              ?>
                  <div class="form-group row fileAttachBlock">
                      <div class="col-sm-12"   >
                          <a href="" class="attach btn btn-default" target="_blank" style="margin-right: auto;margin-left: auto;width: 300px;display: none;">File Download</a>
                      </div>
                  </div>


              </div>

          <!-- End modal body -->





            <!-- Start modal footer -->

              <div class="modal-footer">

                <!--<button type="button" data-toggle="modal" data-target="#reassign" class="btn btn-primary" style="" >REASSIGN</button> -->

                <button type="submit" id="forceUpdateComplaintStatus" name="forceUpdateComplaintStatus" class="btn btn-primary" style="width:200px;" >MARK AS COMPLETE</button>

                  <?php if($_SESSION['editcomplaint']&&$_SESSION['orgType']==6){ ?>

                  <button type="submit" id="forceEditComplaint" name="forceEditComplaint" class="btn btn-primary" >EDIT</button>

                  <?php }else{ ?>

                <button type="submit" id="forceUpdateComplaint" name="forceUpdateComplaint" class="btn btn-primary" >UPDATE</button>

                  <?php } ?>

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
               <h5 class="modal-title" id="reassignTitle">REASSIGN <?php  if($_SESSION['orgType']==7){?>DRIVER <?php }else { ?>STAFF<?php } ?></h5>
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

  <div class="laoder" id="loader" style="display:none;width: 100vw; height: 100vh; position: fixed; top:0;left: 0; background-color: #004187de; z-index: 999999" ><div style="padding-top: 45vh;text-align:center ;font-size: xx-large;color: #fff;"><div class="wheel"></div><br />Loading</div></div>
  <script>
  $( document ).ready(function() {
    $("#reassignWorker,#assignWorker").on("click",function () {
        document.getElementById("loader").style.display="block";
    });
      document.getElementById("loader").style.display="none";
  });
  </script>
</body>

</html>

