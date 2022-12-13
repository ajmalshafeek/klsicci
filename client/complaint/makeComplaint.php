
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
   <!-- Breadcrumbs-->
  <div class="content-wrapper">
  <div class="container-fluid">
  <span id="pageTitleBlock" class="d-block p-3 bg-primary ">New Request</span>  
  <br/>
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
        <label for="problem" class="col-sm-2 col-form-label col-form-label-lg" >REQUEST</label>
        <div class="col-sm-10"   >
          <input type="text" class="form-control" id="problem" name="problem" required></input>
          <div class="invalid-feedback">
          Please enter a problem 
        </div>
        </div>
      </div>

      <div class="form-group row">
        <label for="problemDetails" class="col-sm-2 col-form-label col-form-label-lg" >REQUEST DESCRIPTION</label>
        <div class="col-sm-10"   >
          <textarea class="form-control" id="problemDetails" name="problemDetails" required></textarea>
          <div class="invalid-feedback">
            
          </div>
        </div>
      </div>
      

      <!-- HYDROKLEEN GLOBAL -->
      <?php 
        if($config['customerComplaintFormBookingDate']==true)
        {
      ?>
        <div class="form-group row" >
          <label for="bookingDate" class="col-sm-2 col-form-label col-form-label-lg" >BOOKING DATE</label>
          <div class="col-sm-10"   >
            <input class="form-control" type="date" value="<?php echo date('Y-m-d'); ?>" id="bookingDate" name="bookingDate">
            <div class="invalid-feedback">

            </div>
          </div>
        </div>
      <?php
        }
      ?>

    


      <div class="form-group row">
          <label class="col-sm-2 col-form-label col-form-label-lg"></label>
          <div class="col-sm-10">
              <button name='makeComplaint'  class="btn btn-primary btn-lg btn-block" type='submit' >SUBMIT</button>
          </div>
      </div>
    </form>
</div>
</div>
</body>
</html>
