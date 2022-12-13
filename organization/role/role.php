
<?php
$pageIdentity=3;
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
 session_name($config['sessionName']);
   session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/role.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organizationType.php");
?>
<!DOCTYPE html >

<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">


    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />

    <!--
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <script src="../../js/bootstrap.min.js" ></script>
-->
<?php
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>

    <script>
      function loadPrivilegeCheckboxTable(){
        //var id = document.getElementById("roles").selectedIndex;
        var id = document.getElementById("roles").value;
        $.ajax({
            type  : 'GET',
            url  : '../../phpfunctions/role.php?',
            data : {loadPrivilegeCheckbox:id},
            success: function (data) {
              console.log(data);
              document.getElementById("privilegeCheckboxTable").innerHTML = data;
            }
        });
      }

      function checkAllBox(){
        var  x = document.getElementById("checkAll").checked;
        console.log(x);
        if (x) {
          var clist=document.getElementsByClassName("checkAllBox");
          for (var i = 0; i < clist.length; ++i) {
             clist[i].checked = true;
          }
        }else{
          var clist=document.getElementsByClassName("checkAllBox");
          for (var i = 0; i < clist.length; ++i) {
             clist[i].checked = false;
          }
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

            table, td, th {
              border: 1px solid black;
            }

            table {
              border-collapse: collapse;
              width: 100%;
            }

            th {
              height: 50px;
            }
            input {
	            margin: 5px;
            }
        legend {
	    font-size: 16px;
	    width: 131px;
	    padding: 5px;
    }
    fieldset {
	    min-width: 100%;
	    padding: 5px 20px;
	    margin: 5px;
	    border-width: 2px;
	    border-style: groove;
	    border-color: threedface;
	    border-image: initial;
    }
    .legendclientpass {
	    width: 186px;
    }
    </style>

</head>
<body class="fixed-nav ">

<?php
  include $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/navMenu.php";
?>

 <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="../../home.php">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Role</li>
      </ol>
      <div class="container" >
        <form class="" action="../../phpfunctions/role.php" method="post">
          <?php
                if (isset($_SESSION['feedback'])) {
                    echo $_SESSION['feedback'];
                    unset($_SESSION['feedback']);
                }
          ?>
          <div class="container">
            <select id="roles" class="form-control" onchange="loadPrivilegeCheckboxTable()">
              <option value="">-Select Role-</option>
              <?php echo loadAllRoles() ?>
            </select>
          </div>
          <div id="privilegeCheckboxTable" class="container">

          </div>
          <div class="container">
            <div class="card-header" style="border:1px solid #CFD4DA;border-bottom:0px;">
              Organization Type
            </div>
            <div class="card-body" style="border:1px solid #CFD4DA;">
              <?php orgTypeDropDownList() ?>
            </div>
          </div>
	        <div class="container">
		        <div class="card-header" style="border:1px solid #CFD4DA;border-bottom:0px;">
			        Extra Features
		        </div>
		        <div class="card-body" style="border:1px solid #CFD4DA;">
			        <?php  appUseInOrganization() ?>
		        </div>
	        </div>
          <button class="btn btn-primary btn-lg btn-block" type="submit" name="updatePrivilege">SAVE</button>
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
