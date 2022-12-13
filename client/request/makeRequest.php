
<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION)) 
{ 
  session_name($config['sessionName']);
  session_start(); 
} 
require($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientComplaint.php");

?>
<!DOCTYPE >

<html>
<head>

    <meta charset="utf-8">
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
         
    </style>
   
</head>
<body class="fixed-nav ">

<?php
  include $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/navMenu.php";
?>
   

<h1 class="display-4">MAKE A REQUEST</h1>
<hr/>
<div class="container">


    <form method="POST" action="../../phpfunctions/clientComplaint.php" class="needs-validation" novalidate >
    <div id="jobListForm">
        <?php
            if (isset($_SESSION['feedback'])) {
                echo $_SESSION['feedback'];
                unset($_SESSION['feedback']);
            }
        ?>
    
<!--
      <div class="form-group row">
        <label for="jobName" class="col-sm-2 col-form-label col-form-label-lg">PROBLEM</label>
        <div class="col-sm-10"   >
          <?php  
          require($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/orgJobList.php");

          dropDownOrgJobList() 
          
          ?>

          <input type="text" class="form-control" placeholder="Enter a problem" id="jobName" name="jobName" style="display:none;"></input>
          <div class="invalid-feedback">
           
          </div>
        </div>
      </div>
      -->

      <div class="form-group row">
        <label for="problem" class="col-sm-2 col-form-label col-form-label-lg">SUBJECT</label>
        <div class="col-sm-10"   >
          <input type="text" class="form-control" id="problem" placeholder="Request for quotation / Request for assistance" name="problem" required></input>
          <div class="invalid-feedback">
          Please enter subject.
        </div>
        </div>
      </div>

      <div class="form-group row">
        <label for="problemDetails" class="col-sm-2 col-form-label col-form-label-lg">DETAILS OF REQUEST</label>
        <div class="col-sm-10"   >
          <textarea class="form-control" rows="5" id="problemDetails" placeholder="Details of request" name="problemDetails" required></textarea>
          <div class="invalid-feedback">
           Please enter details. 

          </div>
        </div>
      </div>

      <div class="form-group row">
        <label for="problem" class="col-sm-2 col-form-label col-form-label-lg">PERSON IN CHARGE</label>
        <div class="col-sm-10"   >
          <input type="text" class="form-control" id="problem" placeholder="Person in charge name" name="problem" required></input>
          <div class="invalid-feedback">
          Please enter subject.
        </div>
        </div>
      </div>

      <div class="form-group row">
        <label for="problem" class="col-sm-2 col-form-label col-form-label-lg">CONTACT NO.</label>
        <div class="col-sm-10"   >
          <input type="text" class="form-control" id="problem" placeholder="Person in charge contact no" name="problem" required></input>
          <div class="invalid-feedback">
          Please enter subject.
        </div>
        </div>
      </div>

      <div class="form-group row">
        <label for="problem" class="col-sm-2 col-form-label col-form-label-lg">EMAIL ADDRESS</label>
        <div class="col-sm-10"   >
          <input type="text" class="form-control" id="problem" placeholder="Person in charge email address" name="problem" required></input>
          <div class="invalid-feedback">
          Please enter subject.
        </div>
        </div>
      </div>

      <div class="form-group row">
            <label for="startTime" class="col-sm-2 col-form-label col-form-label-lg">REQUIRED DATE</label>
            <div class="col-sm-3"   >
              <input type="date" name="startTime" id="startTime" class="form-control" required>
              <div class="invalid-feedback">
                Please enter start time
              </div>
            </div>
          </div>

     
      <div class="form-group row">
          <label class="col-sm-2 col-form-label col-form-label-lg"></label>
          <div class="col-sm-10">
              <button name='makeComplaint'  class="btn btn-primary btn-lg btn-block" type='submit' >SEND REQUEST</button>
          </div>
      </div>
    </form>
        
</div>

</body>
</html>
