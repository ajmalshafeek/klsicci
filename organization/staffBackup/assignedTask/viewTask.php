
<?php
$config=parse_ini_file(__DIR__."/../../../jsheetconfig.ini");
if(!isset($_SESSION)) 
{ 
  session_name($config['sessionName']);
  session_start(); 
} 
require($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/job.php");


?>
<!DOCTYPE >

<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../../../css/bootstrap.min.css">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />

  <!--  
    <script src="../js/jquery-3.3.1.slim.min.js" ></script>

    <script src="../../../js/bootstrap.min.js" ></script>
    
    <script type="text/javascript" src="../../../js/jquery-3.3.1.min.js"></script>
-->
<?php 
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>
    <!-- datatable -->
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/jquery.dataTables.js'></script>
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/dataTables.bootstrap4.js'></script>
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/js/sb-admin-datatables.min.js'></script>
    <!-- datatable -->

    
	  <script>
    function updateMessageStatus(jobTransId){

          $.ajax({
               type  : 'GET',
                url  : '../../../phpfunctions/job.php?',
                data : {updateJobTransMessageStatus:jobTransId},
                success: function (data) {
                }

            });
    }

    function viewTaskDetails(jobId,jobTrans){
      
          $.ajax({
               type  : 'GET',
                url  : '../../../phpfunctions/job.php?',
                data : {viewTaskDetailsModeContent:jobId},
                success: function (data) {
                  document.getElementById("jobId").value = jobId;
                  document.getElementById("jobTransId").value = jobTrans;
                  var taskDiv=$("#taskModelContent");
                  taskDiv.empty().append(data);
                  
                  //$( "#vendorDetailsTable" ).load( "viewVendor.php #vendorDetailsTable" );                
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

<h1 class="display-4">MY TASK</h1>
<hr/>
<div class="container">
<?php
        if (isset($_SESSION['feedback'])) {
            echo $_SESSION['feedback'];
            unset($_SESSION['feedback']);
        }
     ?>

    <div class='card mb-3'>
      <div class='card-header'>
        <i class='fa fa-table'></i> 
        TASK LIST
      </div>
      <?php 
        viewAssignedTask(null,null,"UNRESOLVED",null,null,null,
        "myStaff",$_SESSION['userid'],null,null,null,null);
      // myComplaintsTable();
      ?>
      </div>
    </div>
  

  <form action="../updateJob.php" method="post" >

 <div class="modal fade" id="messageContent" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="messageContentModalTitle">ASSIGNED TASK</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

            <div id='taskModelContent' >            
            </div>

          <div class="modal-footer">
            <input type="text" hidden name="jobId" id="jobId" value=""  />
            <input type="text" hidden name="jobTransId" id="jobTransId" value=""  />

            <button type="submit" name='updateAssignedTask' class="btn btn-primary" >UPDATE</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">CLOSE</button>
          </div>
        </div>
      </div>
    </div>
    </form>


</div>

</body>
</html>