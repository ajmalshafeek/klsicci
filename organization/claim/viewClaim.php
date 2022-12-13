<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
  session_name($config['sessionName']);
  session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/claim.php");

?>
<!DOCTYPE html >

<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">


    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />

    <?php
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>
    <!-- Data Table Import -->
    <link rel="stylesheet" type="text/css" href="../../adminTheme/dataTable15012020/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="../../adminTheme/dataTable15012020/jquery.dataTables.js"></script>
    <script>
    $(document).ready( function () {
      $('#claimTable').DataTable();
    } );

    function showAttachment(fileName){
      console.log(fileName);
      var imageSrc = "<a href='../../resources/2/claim/" + fileName + "' download><object width='100%' data='../../resources/2/claim/" + fileName + "'></object></a>";
      document.getElementById("attachmentModalContent").innerHTML = imageSrc;
      console.log(imageSrc);
    }

        function addCtr(){
        var x = document.getElementById("counter").value;
        x++;
        addAtt(x)
        document.getElementById("counter").value = x;
    }

    function subCtr(){
        var x = document.getElementById("counter").value;
        if(x >= 1){
            subAtt();
            x--;
            document.getElementById("counter").value = x;
        }
    }

    function addAtt(x){
        var row="<input class='form-control' type='file' name='file"+x+"' id='fileToUpload'>";
        document.getElementById("rowStore").value += row;
        var xRow = document.getElementById("rowStore").value;
        document.getElementById("displayRow").innerHTML = xRow;
    }

    function subAtt(){
        var x = document.getElementById("counter").value;
        var rowStore = document.getElementById("rowStore").value;
        var subRow = "<input class='form-control' type='file' name='file"+x+"' id='fileToUpload'>";
        var nxRow = rowStore.replace(subRow, "");
        document.getElementById("rowStore").value = nxRow;
        document.getElementById("displayRow").innerHTML = nxRow;
    }

    function showStaffInfo(){

    }

    function showClaim(id){

        $.ajax({

            type  : 'GET',
            url  : '../../phpfunctions/claim.php?',
            data : {showClaim:id},
            success: function (data) {
            details= JSON.parse(data);
            document.getElementById('claimId').value = details.id;
            document.getElementById('project').value = details.project;
            document.getElementById('claim').value = details.claim;

            document.getElementById('projectApprove').value = details.project;
            document.getElementById('claimApprove').value = details.claim;
            document.getElementById('claimTypeApprove').value = details.claimtype;
              var t=details.number;
                document.getElementById('vehicleNumberApprove').value = details.number;
            if(t!==null && t.number!==''){
                document.getElementsByClassName('vehicle')[0].style.display="flex";
                }else{
                document.getElementsByClassName('vehicle')[0].style.display="none";
                }
                document.getElementById('claimSubmitApprove').value = details.id;
            }
        });
    }

    function showClaimAtt(id){

        $.ajax({

            type  : 'GET',
            url  : '../../phpfunctions/claim.php?',
            data : {showClaimAtt:id},
            success: function (data) {
            document.getElementById("attTable").innerHTML = data;
            document.getElementById("attApprove").innerHTML = data;
            }
        });
    }

    function removeClaimAtt(claimId,id){
        $.ajax({

            type  : 'GET',
            url  : '../../phpfunctions/claim.php?',
            data : {removeClaimAtt:id},
            success: function (data) {
                if(data){
                    alert("Succesfully removed attachment");
                    showClaimAtt(claimId);
                }else{
                    alert("Failed to remove attachment");
                }
            }
        });
    }
    </script>
    <!-- Data Table Import -->

    <style>
    table, td, th {
        text-align: center;
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
        <li class="breadcrumb-item ">Claim</li>
        <li class="breadcrumb-item active">View Claim</li>
      </ol>
    </div>
    <?php
        if (isset($_SESSION['feedback'])) {
            echo $_SESSION['feedback'];
            unset($_SESSION['feedback']);
        }
    ?>
      <div class="container" style="overflow: scroll;">
        <?php claimTable() ?>
      </div>
  </div>
  <!-- Show Attachment Modal START-->
  <div class="modal fade" id="attachmentModal" tabindex="-1" role="dialog" aria-labelledby="attachmentModal" aria-hidden="true">
       <div class="modal-dialog modal-dialog-centered modal-full" role="document">
         <div class="modal-content">
           <div class="modal-header">
             <h5 class="modal-title" id="attachmentModalTitle">Attachment</h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
             </button>
           </div>
           <div class="modal-body">
             <div id='attachmentModalContent'>
             </div>
           </div>
           <div class="modal-footer">
             <button type="button" class="btn btn-secondary btn-lg" title="CLOSE DIALOG" data-dismiss="modal">
 				      <i class="fa fa-times" aria-hidden="true"></i>
 				      Close
 			 </button>
           </div>
         </div>
       </div>
     </div>

  <!-- Edit Claim Modal START-->
  <div class="modal fade" id="editClaimModal" tabindex="-1" role="dialog" aria-labelledby="editClaimModal" aria-hidden="true">
       <div class="modal-dialog modal-dialog-centered modal-full" role="document">
         <div class="modal-content">
           <div class="modal-header">
             <h5 class="modal-title" id="editClaimModalTitle">Edit</h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
             </button>
           </div>
           <div class="modal-body">
           <form action="../../phpfunctions/claim.php" method="POST" enctype="multipart/form-data">
            <!--PROJECT-->
            <div class="form-group row">
                <label for="project" class="col-sm-4 col-form-label col-form-label-lg">Project Name</label>
                <div class="col-sm-8"   >
                    <input type="text" id="project" class="form-control" name="project" required>
                <div class="invalid-feedback">
                      Please enter project name
                </div>
                </div>
            </div>

            <!--CLAIM AMOUNT-->
            <div class="form-group row">
                <label for="claim" class="col-sm-4 col-form-label col-form-label-lg">Claim Amount(RM)</label>
                <div class="col-sm-8"   >
                    <input type="text" id="claim" class="form-control" name="claim" required>
                <div class="invalid-feedback">
                      Please enter claim amount(RM)
                </div>
                </div>
            </div>

            <!--ATTACHMENT-->
            <hr>
            <div class="form-group row">
                <div class="col-sm-4"></div>
                <div class="col-sm-8">
                    <span id="attTable"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="attachment" class="col-sm-4 col-form-label col-form-label-lg">Add Attachment</label>
                <div class="col-sm-8">
                        <input class="form-control" type="file" name="file0" id="file0">
                        <span id="displayRow"></span>
                </div>
            </div>
            <!--ADD ATTACHMENT-->

            <div class="form-group row">
                <input id="counter" name="counter" type=text value="0" hidden>
                <input id="rowStore" type=text value="" hidden>
                <!-- <span id="rowStore" style="display:none"><input class='form-control' type='file' name='file0' id='fileToUpload'></span> -->

                <label for="addButton" class="col-sm-4 col-form-label col-form-label-lg"></label>
                <div class="col-sm-4">
                    <button type="button" id="addAttButton" onclick="addCtr()" class="form-control btn btn-lg btn-block btn-success fa fa-plus"></button>
                </div>
                <div class="col-sm-4">
                    <button type="button" id="subAttButton" onclick="subCtr()" class="form-control btn btn-lg btn-block btn-success fa fa-minus"></button>
                </div>
            </div>
            <hr>
            <!-- SUBMIT BUTTON -->
            <input id="claimId" name="claimId" type=text hidden>
            <div class="form-group row">
                <div class="col-sm-12">
                  <button class="btn btn-primary btn-lg btn-block" name="claimUpdate" value="0">Submit</button>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-6">
                  <button class="btn btn-primary btn-lg btn-block" name="claimUpdate" value="2">Remove</button>
                </div>
                <div class="col-sm-6">
                  <button class="btn btn-primary btn-lg btn-block" name="claimUpdate" value="1">Save Draft</button>
                </div>
            </div>
           </form>
           </div>
           <div class="modal-footer">
             <button type="button" class="btn btn-secondary btn-lg" title="CLOSE DIALOG" data-dismiss="modal">
 				      <i class="fa fa-times" aria-hidden="true"></i>
 				      Close
 			 </button>
           </div>
         </div>
       </div>
     </div>

  <!-- Approve Modal START-->
  <div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModal" aria-hidden="true">
       <div class="modal-dialog modal-dialog-centered modal-full" role="document">
         <div class="modal-content">
           <div class="modal-header">
             <h5 class="modal-title" id="approveModalTitle">Approve the Claim?</h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
             </button>
           </div>
           <div class="modal-body">
                <div class="form-group row">
                <label for="projectApprove" class="col-sm-4 col-form-label col-form-label-lg">Description</label>
                <div class="col-sm-8">
                    <input id="projectApprove" class="form-control" type="text" readonly></input>
                </div>
            </div>
                <div class="form-group row">
                <label for="claimTypeApprove" class="col-sm-4 col-form-label col-form-label-lg">Request For</label>
                <div class="col-sm-8">
                    <input id="claimTypeApprove" class="form-control" type="text" readonly></input>
                </div>
            </div>
               <div class="form-group row vehicle">
                <label for="vehicleNumberApprove" class="col-sm-4 col-form-label col-form-label-lg">vehicle Number</label>
                <div class="col-sm-8">
                    <input id="vehicleNumberApprove" class="form-control" type="text" readonly></input>
                </div>
            </div>
                <div class="form-group row">
                <label for="claimApprove" class="col-sm-4 col-form-label col-form-label-lg">Claim(RM)</label>
                <div class="col-sm-8">
                    <input id="claimApprove" class="form-control" type="text" readonly></input>
                </div>
            </div>
                <div class="form-group row">
                <label for="attApprove" class="col-sm-4 col-form-label col-form-label-lg">Attachment</label>
                <div class="col-sm-8">
                    <span id="attApprove"></span>
                </div>
            </div>
           </div>
           <div class="modal-footer">
             <div class="form-group row">
                 <div class="col-sm-6">
                     <form action="../../phpfunctions/claim.php" method="POST">
                      <button id="claimSubmitApprove" class="btn btn-primary btn-lg btn-block" name="claimSubmitApprove">Approve</button>
                     </form>
                 </div>
                 <div class="col-sm-6">
                     <button type="button" class="btn btn-secondary btn-lg" title="CLOSE DIALOG" data-dismiss="modal">
         				      <i class="fa fa-times" aria-hidden="true"></i>
         				      Close
         			 </button>
     			 </div>
 			 </div>
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
</body>
</html>
