<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
  session_name($config['sessionName']);
  session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/project.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organizationUser.php");

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
    <script type="text/javascript">
    $(document).ready( function () {
      $('#projectTable').DataTable();
    } );

    function showProject(id){
        document.getElementById("projectTaskName").value = "";
        document.getElementById("projectTaskMandays").value = "";
        document.getElementById("projectTaskStartDate").value = "";
        checkAddProjectButton();
        $.ajax({

            type  : 'GET',
            url  : '../../phpfunctions/project.php?',
            data : {showProject:id},
            success: function (data) {
            details= JSON.parse(data);
            document.getElementById('projectId').value = details.id;
            document.getElementById('projectName').innerHTML = details.projectName;
            document.getElementById("projectTaskStartDate").min = details.startDate;
            }
        });

        $.ajax({

            type  : 'GET',
            url  : '../../phpfunctions/project.php?',
            data : {projectContent:id},
            success: function (data) {
            document.getElementById('projectContent').innerHTML = data;
            }
        });

        $.ajax({

            type  : 'GET',
            url  : '../../phpfunctions/project.php?',
            data : {groupMemberList:id},
            success: function (data) {
              document.getElementById('membersList').innerHTML = data;
            }
        });
    }

    function addProjectTask(){
      var projectId = document.getElementById("projectId").value;
      var projectTaskName = document.getElementById("projectTaskName").value;
      var projectTaskMandays = document.getElementById("projectTaskMandays").value;
      var projectTaskStartDate = document.getElementById("projectTaskStartDate").value;
      $.ajax({
          type  : 'GET',
          url  : '../../phpfunctions/project.php?',
          data : {addProjectTask:projectId,projectTaskName:projectTaskName,projectTaskMandays:projectTaskMandays,projectTaskStartDate:projectTaskStartDate},
          success: function (data) {
            console.log(data);
            showProject(projectId);
          }
      });
    }

    function removeProjectTask(projectTaskId){
      var projectId = document.getElementById("projectId").value;
      $.ajax({
          type  : 'GET',
          url  : '../../phpfunctions/project.php?',
          data : {removeProjectTask:projectTaskId},
          success: function (data) {
            console.log(data);
            showProject(projectId);
          }
      });
    }

    function addProjectTaskMember(projectTaskId){
      var projectId = document.getElementById("projectId").value;
      var projectGroupId = document.getElementById("projectGroupId" + projectTaskId).value;

      $.ajax({
          type  : 'GET',
          url  : '../../phpfunctions/project.php?',
          data : {addTaskMember:true,projectTaskId:projectTaskId,projectGroupId:projectGroupId},
          success: function (data) {
            console.log(data);
            showProject(projectId);
          }
      });
    }

    function checkAddProjectButton(){
      var projectTaskName = document.getElementById("projectTaskName").value;
      var projectTaskMandays = document.getElementById("projectTaskMandays").value;
      var projectTaskStartDate = document.getElementById("projectTaskStartDate").value;
      if (projectTaskName == "" || projectTaskMandays == "" || projectTaskStartDate == "") {
        document.getElementById("addProjectButton").disabled = true;
      }else {
        document.getElementById("addProjectButton").disabled = false;
      }
    }

    function showStaffInfo(){
      checkAddGroupMemberButton();
    }

    function changePanel(i){
      if (i == 0) {
        document.getElementById("groupPanel").style.display = "block";
        document.getElementById("projectPanel").style.visibility = "hidden";

        document.getElementById("projectButton").disabled = false;
        document.getElementById("groupButton").disabled = true;
      }else if(i == 1){
        document.getElementById("groupPanel").style.display = "none";
        document.getElementById("projectPanel").style.visibility = "visible";

        document.getElementById("projectButton").disabled = true;
        document.getElementById("groupButton").disabled = false;
      }
    }

    function changeMemberForm(){
      var check = document.getElementById("checkMemberForm").checked;
      checkAddGroupMemberButton();
      if (check) {
        document.getElementById("addStaffForm").style.display = "block";
        document.getElementById("addOtherForm").style.display = "none";
      }else {
        document.getElementById("addStaffForm").style.display = "none";
        document.getElementById("addOtherForm").style.display = "block";
      }
    }

    function addGroupMember(){
      var projectId = document.getElementById("projectId").value;
      var orgStaffId = document.getElementById("orgStaffId").value;
      var otherName = document.getElementById("otherName").value;
      var check = document.getElementById("checkMemberForm").checked;

      if (check) {
        var isStaff = 1;
        var staffId = orgStaffId;
        var otherNameUpdated = null;
      }else {
        var isStaff = 0;
        var staffId = null;
        var otherNameUpdated = otherName;
      }

      $.ajax({
          type  : 'GET',
          url  : '../../phpfunctions/project.php?',
          data : {addProjectGroup:true,projectId:projectId,isStaff:isStaff,staffId:staffId,otherNameUpdated:otherNameUpdated},
          success: function (data) {
            console.log("Status `adProjectMember`: " + data);
            showProject(projectId);
          }
      });
    }

    function removeGroupMember(projectGroupId){
      var projectId = document.getElementById("projectId").value;
      $.ajax({
          type  : 'GET',
          url  : '../../phpfunctions/project.php?',
          data : {removeProjectGroup:projectGroupId},
          success: function (data) {
            console.log(data);
            showProject(projectId);
          }
      });

      $.ajax({
          type  : 'GET',
          url  : '../../phpfunctions/project.php?',
          data : {removeProjectTaskMemberByProjectGroupId:projectGroupId},
          success: function (data) {
            console.log(data);
            showProject(projectId);
          }
      });
    }

    function removeTaskMember(projectTaskMemberId){
      var projectId = document.getElementById("projectId").value;
      $.ajax({
          type  : 'GET',
          url  : '../../phpfunctions/project.php?',
          data : {removeProjectTaskMemberById:projectTaskMemberId},
          success: function (data) {
            console.log(data);
            showProject(projectId);
          }
      });
    }

    function checkAddProjectTaskMemberButton(projectTaskId){
      var checkAddButton = document.getElementById("projectGroupId" + projectTaskId).value;
      console.log("checkAddButton: " + projectTaskId);
      if (checkAddButton != null) {
        document.getElementById("addProjectTaskMemberButton" + projectTaskId).disabled = false;
      }else {
        document.getElementById("addProjectTaskMemberButton" + projectTaskId).disabled = true;
      }
    }

    function checkAddGroupMemberButton(){
      var check = document.getElementById("checkMemberForm").checked;

      if (check) {
        var checkInput = document.getElementById("orgStaffId").value;
        if (checkInput == "null") {
          document.getElementById("addGroupMemberButton").disabled = true;
        }else {
          document.getElementById("addGroupMemberButton").disabled = false;
        }
      }else {
        var checkInput = document.getElementById("otherName").value;

        if (checkInput == "") {
          document.getElementById("addGroupMemberButton").disabled = true;
        }else {
          document.getElementById("addGroupMemberButton").disabled = false;
        }
      }

    }

    function updateProgress(projectTaskId){
      var id = "progress" + projectTaskId;
      var progress = document.getElementById(id).value;

          $.ajax({
              type  : 'GET',
              url  : '../../phpfunctions/project.php?',
              data : {updateProgress:projectTaskId,progress:progress},
              success: function (data) {
                alert(data);
                $("."+id).html(" "+progress+'%');
                showProject(projectId);
              }
          });

    }

    function removeProject(projectId){
      var confirmCheck = confirm("Are you sure you want to remove this project?");
      if (confirmCheck) {
        $.ajax({
            type  : 'GET',
            url  : '../../phpfunctions/project.php?',
            data : {removeProject:projectId},
            success: function (data) {
              location.reload();
            }
        });
      }
    }
    </script>
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
      cursor:pointer;
    }
	.tab-button button{
	width: 100%;
    border-radius: 20px 20px 0px 0px !important;
    padding: 10px;
    border: 1px solid #ccc;
		}
button:focus {
    outline: 0px dotted !important;
    outline: 0px auto -webkit-focus-ring-color !important;
	background-color:#DDDDDD;
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
        <li class="breadcrumb-item ">Project</li>
        <li class="breadcrumb-item active">View Project</li>
      </ol>
    </div>
    <?php
    if (isset($_SESSION['feedback'])) {
        echo $_SESSION['feedback'];
        unset($_SESSION['feedback']);
    }
    ?>
    <div class="container">
      <?php projectList(); ?>
    </div>
  </div>
  <!-- View Projust Modal START-->
  <div class="modal fade" id="viewProjectModal" tabindex="-1" role="dialog" aria-labelledby="viewProjectModal" aria-hidden="true">
       <div class="modal-dialog modal-dialog-centered modal-full" role="document">
         <div class="modal-content">
           <div class="modal-header">
             <h5 class="modal-title" id="projectName"></h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
             </button>
           </div>
           <div class="modal-body">
             <div class="form-group row tab-button">

               <div class="col-sm-6 p-0">
                 <button id="projectButton" onclick="changePanel(1)" type="button" name="button" style="width:100%;" disabled>Task</button>
               </div>
			   <div class="col-sm-6 p-0">
                 <button id="groupButton" onclick="changePanel(0)" type="button" name="button" style="width:100%;">Group</button>
               </div>
             </div>
             <!--GROUP-->
             <div id="groupPanel" style="display:none;">
               <div id="membersList"></div>
               <hr>
               <div class="form-check">
                <input onchange="changeMemberForm()" type="checkbox" class="form-check-input" id="checkMemberForm" checked>
                <label class="form-check-label" for="checkForm">From organization staff</label>
               </div>
               <div class="form-group row" id="addStaffForm">
                 <div class="col-md-12">
                   <?php dropDownListOrgStaffListActive() ?>
                 </div>
               </div>
               <div class="form-group row" id="addOtherForm" style="display:none">
                 <div class="col-md-12">
                   <input oninput="checkAddGroupMemberButton()" id="otherName" class="form-control" type="text" value="" placeholder="Enter individual name">
                 </div>
               </div>
               <div class="form-group row">
                 <div class="col-md-12">
                   <button id="addGroupMemberButton" onclick="addGroupMember()" class="btn btn-primary btn-lg btn-block" type='button' disabled>Add</button>
                 </div>
               </div>
             </div>
             <!--PROJECT-->
             <div id="projectPanel">
               <div id="projectContent"></div>
               <div class="form-group row">
                 <div class="col-sm-8 pr-0">
                     <input id="projectTaskName" class="form-control" oninput="checkAddProjectButton()" type="text" name="" value="" placeholder="New Task">
                 </div>
                 <div class="col-sm-4">
                     <input id="projectTaskMandays" class="form-control" oninput="checkAddProjectButton()" type="number" name="" value="" placeholder="Mandays" min="0">
                 </div>
               </div>
               <div class="form-group row">
                 <div class="col-sm-12">
                     <small>Start Date:</small>
                     <input id="projectTaskStartDate" class="form-control" oninput="" onchange="checkAddProjectButton()" type="date" name="" value="" placeholder="Start Date">
                 </div>
               </div>
               <div class="form-group row">
                 <div class="col-sm-12">
                     <button id="addProjectButton" onclick="addProjectTask()" type="button" class="form-control btn btn-lg btn-block btn-success fa fa-plus" style="height:37.29px;border-radius:2px;" disabled></button>
                 </div>
               </div>
             </div>
             <input id="projectId" type="number" hidden>
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
