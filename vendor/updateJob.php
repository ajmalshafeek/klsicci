
<?php
$config=parse_ini_file("../jsheetconfig.ini");
session_name($config['sessionName']);
session_start();
require($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/vendorClient.php");
require($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/jobList.php");

?>
<!DOCTYPE >

<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />

    <link rel="stylesheet" href="../css/bootstrap.min.css">
  <!--  
    <script src="../js/jquery-3.3.1.slim.min.js" ></script>

    <script src="../js/bootstrap.min.js" ></script>
    
    <script type="text/javascript" src="../js/jquery-3.3.1.min.js"></script>
--> 
<?php 
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>
	  <script>

    function updateTabClass(id){
      if(id=='update'){
        $('#signatureParent').resize();
      }
      
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

        $(document).ready(function() {
            // Initialize jSignature
            var $sigdiv = $("#signature").jSignature({
              'UndoButton':true
            });
      

            $('#signature').change(function() {    
                var data = $sigdiv.jSignature('getData', 'image');
                // Storing in textarea
                //$('#output').val(data);

                // Alter image source 
              //  alert(data);
                $('#imageBase64').attr('value', "data:" + data);
             //   $('#sign_prev').show();
            });
        });
      </script>
    <style>

    
    #signature{
    
      border:solid 1px #E32526;
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
   
</head>
<body class="fixed-nav ">

<?php
  include $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/navMenu.php";
?>
    <script src="../js/jsignature/jSignature.min.js"></script>

<h1 class="display-4">UPDATE JOB</h1>
<hr/>
<div class="container">


      <form method="POST" action="../phpfunctions/job.php" class="needs-validation" novalidate >
            
        <div id="jobUpdate"> 
        <?php
          if (isset($_SESSION['feedback'])) {
            echo $_SESSION['feedback'];
            unset($_SESSION['feedback']);
          }
        ?>      
       
         
                </div>
                <?php 
          if (isset($_POST['updateAssignedTask'])){
            ?>
            <div class="form-group row" >
              <label for="clientCompanyId" class="col-sm-2 col-form-label col-form-label-lg">CLIENT</label>
                <div class="col-sm-10"   >    
                  <input type="text" readonly class="form-control" id="problem" value="<?php echo $_POST["clientName"]; ?>">
                  <div class="invalid-feedback">
                    Please enter client name
                  </div>
                </div>
              </div>
                    <?php
           
          }else{
            dropDownVendorClientCompany();
          }
          ?>
       
       
          <?php
            if (isset($_POST['updateAssignedTask'])){
            ?>
              <div class="form-group row">
                <label for="jobName" class="col-sm-2 col-form-label col-form-label-lg"><?php if($_SESSION['orgType']==7){?>TASK<?php } else{ ?>PROBLEM<?php } ?></label>
                  <div class="col-sm-10"   >
                    <input type="text" readonly class="form-control" id="jobName" value="<?php echo $_POST["jobName"]; ?>">
                  <div class="invalid-feedback">
                    Please enter job name
                  </div>
                </div>
              </div>
              <?php

              }else{
                dropDownVendorJobList();
              }
              ?>
                


       
            <?php  
               if (isset($_POST['updateAssignedTask'])) {
          ?>
        <div class="form-group row">
            <label for="jobName" class="col-sm-2 col-form-label col-form-label-lg"><?php if($_SESSION['orgType']==7){?>TASK<?php } else{ ?>PROBLEM<?php } ?> DETAILS</label>
            <div class="col-sm-10"   >
               <textarea class="form-control" readonly id="problemDetails" name="problemDetails"  ><?php echo $_POST['problemDetails']; ?></textarea>
             
            </div>
          </div>
          <?php
               }
         
              ?>


          <div class="form-group row">
            <label for="startTime" class="col-sm-2 col-form-label col-form-label-lg">START TIME</label>
            <div class="col-sm-10"   >
              <input type="datetime-local" value="<?php echo date('Y-m-d'); ?>T00:00" name="startTime" id="startTime" class="form-control" >
              <div class="invalid-feedback">
                Please enter start time
              </div>
            </div>
          </div>




           <div class="form-group row">
            <label for="endTime" class="col-sm-2 col-form-label col-form-label-lg">END TIME</label>
            <div class="col-sm-10"   >
              <input type="datetime-local" value="<?php echo date('Y-m-d'); ?>T00:00" name="endTime" id="endTime" class="form-control" >
              <div class="invalid-feedback">
                Please enter end time
              </div>
            </div>
          </div>


          <div class="form-group row">
            <label for="remarks" class="col-sm-2 col-form-label col-form-label-lg">REMARKS</label>
            <div class="col-sm-10"   >
              <textarea class="form-control" id="remarks" name="remarks" required></textarea>
              <div class="invalid-feedback">
                Please enter job remarks
              </div>
            </div>
          </div>

          <div class='form-group row' id='signatureParent' >
            <label for='signature' class='col-sm-2 col-form-label col-form-label-lg'  >CUSTOMER SIGNATURE</label>
              <div class='col-sm-10' >
                <div id="signature" name="signature"></div>
              </div>
          </div>
          

          <div class='form-group row' >
            <label for='status' class='col-sm-2 col-form-label col-form-label-lg' >STATUS</label>
            <div class='col-sm-10'  >
              <select name='status' class='form-control form-control-lg' id='status' required>
                <option value="0" >COMPLETE</option>
                <option value="2" >PENDING</option>
                <option value="3" >IN PROGRESS</option>
              </select>
            </div>
          </div>
          <input type="hidden" value="" id="imageBase64" name="imageBase64" />
       
          <div class="form-group row">
              <label class="col-sm-2 col-form-label col-form-label-lg"></label>
              <div class="col-sm-10">
              <?php  
               if (isset($_POST['updateAssignedTask'])) {
                ?>
                 <input type="text" hidden name="jobId" value="<?php echo $_POST['jobId']; ?>"  />
                <input type="text" hidden name="jobTransId" value="<?php echo $_POST['jobTransId']; ?>"  />
                <button name="updateAssignedTaskVendor"  class="btn btn-primary btn-lg btn-block" type='submit' >SUBMIT</button>
               <?php 
               }
               else{
                 ?>
                  <button name='updateImmediateJobVendor'  class="btn btn-primary btn-lg btn-block" type='submit' >SUBMIT</button>

                       <?php 
               }
                  ?>
              </div>
          </div>
        </form>
           

</div>

</body>
</html>
