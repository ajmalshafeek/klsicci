
<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
 session_name($config['sessionName']);
   session_start();
}
require($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientCompany.php");
require($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/orgJobList.php");
require($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/job.php");

//(START)FORM DEFAULT VALUE
$jobId = $_POST['jobId'];
$meterArray = meterDetails($jobId);
$meter1 = $meterArray[0];
$meter2 = $meterArray[1];
$meter3 = $meterArray[2];
$meter4 = $meterArray[3];

$action = actionDetail($jobId);
//(END)FORM DEFAULT VALUE

?>
<!DOCTYPE >

<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link href="../../css/component-custom-switch.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />

  <!--
    <script src="../js/jquery-3.3.1.slim.min.js" ></script>
        <script src="../../js/bootstrap.min.js" ></script>



    <script type="text/javascript" src="../../js/jquery-3.3.1.min.js"></script>
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

            true

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

            .bg-red{
                background-color: #E32526;
            }
    </style>

</head>
<body class="fixed-nav ">

<?php
  include $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/navMenu.php";
?>
<script src="../../js/jsignature/jSignature.min.js"></script>

<h1 class="display-4">UPDATE JOB</h1>
<hr/>
<div class="container">
      <form method="POST" action="../../phpfunctions/job.php" class="needs-validation" novalidate >

        <div id="jobUpdate">
        <?php
        if (isset($_SESSION['feedback'])) {
            echo $_SESSION['feedback'];
            unset($_SESSION['feedback']);
        }

      ?>

      <div class="form-group row">
        <label for="clientCompanyId" class="col-sm-2 col-form-label col-form-label-lg">CLIENT</label>
        <div class="col-sm-10"   >
          <?php
          if (isset($_POST['updateAssignedTask'])) {
           ?>
           <input type="text" readonly class="form-control" id="problem" value="<?php echo $_POST["clientName"]; ?>">
           <?php
          }
          else{
            dropDownListOrganizationClientCompanyActive3();
        //   dropDownVendorClientCompany();
          }
          ?>

        </div>
      </div>

      <div class="form-group row">
            <label for="jobName" class="col-sm-2 col-form-label col-form-label-lg">
            <?php
              if (isset($_POST['updateAssignedTask'])) {
                echo "PROBLEM";
              }else{
                echo "JOBNAME";
              }
                ?>
            </label>
            <div class="col-sm-10"   >
              <?php
               if (isset($_POST['updateAssignedTask'])) {
                ?>
                  <input type="text" readonly class="form-control" id="jobName" value="<?php echo $_POST["jobName"]; ?>">
                <?php
                }
                else{
                  dropDownOrgJobList() ;
                }
              ?>

              <input type="text" class="form-control" placeholder="Enter job name" id="jobName" name="jobName" style="display:none;"></input>
              <div class="invalid-feedback">
                Please enter job name
              </div>
            </div>
        </div>

          <?php
               if (isset($_POST['updateAssignedTask'])) {
          ?>
        <div class="form-group row">
            <label for="jobName" class="col-sm-2 col-form-label col-form-label-lg">PROBLEM DETAILS</label>
            <div class="col-sm-10"   >
               <textarea class="form-control" readonly id="problemDetails" name="problemDetails"  ><?php echo $_POST['problemDetails']; ?></textarea>

            </div>
          </div>
          <?php
               }
          //     dropDownVendorJobList();
          ?>
        <?php
        		if($config['joblistitem']==true){
              require($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/job.php");
              jobItemListForm(null,$_SESSION['orgId']);
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
<!--(START)METER READING-->
          <!--1-->
          <div class="form-group row">
           <label for="meter1" class="col-sm-2 col-form-label col-form-label-lg">METER READING 1</label>
           <div class="col-sm-8"   >
             <input type="text" value="<?php echo $meter1 ?>" name="meter1" id="meter1" class="form-control" >
             <div class="invalid-feedback">
               Please enter meter reading 1
             </div>
           </div>
         </div>
         <!--2-->
         <div class="form-group row">
          <label for="meter2" class="col-sm-2 col-form-label col-form-label-lg">METER READING 2</label>
          <div class="col-sm-8"   >
            <input type="text" value="<?php echo $meter2 ?>" name="meter2" id="meter2" class="form-control" >
            <div class="invalid-feedback">
              Please enter meter reading 2
            </div>
          </div>
        </div>
        <!--3-->
        <div class="form-group row">
         <label for="meter3" class="col-sm-2 col-form-label col-form-label-lg">METER READING 3</label>
         <div class="col-sm-8"   >
           <input type="text" value="<?php echo $meter3 ?>" name="meter3" id="meter3" class="form-control" >
           <div class="invalid-feedback">
             Please enter meter reading 3
           </div>
         </div>
       </div>
       <!--4-->
       <div class="form-group row">
        <label for="meter4" class="col-sm-2 col-form-label col-form-label-lg">METER READING 4</label>
        <div class="col-sm-8"   >
          <input type="text" value="<?php echo $meter4 ?>" name="meter4" id="meter4" class="form-control" >
          <div class="invalid-feedback">
            Please enter meter reading 4
          </div>
        </div>
      </div>
<!--(END)METER READING-->
<!--(START)ACTION TAKEN-->
      <div class="form-group row">
       <label for="action" class="col-sm-2 col-form-label col-form-label-lg">ACTION TAKEN</label>
       <div class="col-sm-10"   >
         <textarea name="action" id="action" class="form-control"><?php echo $action ?></textarea>
         <!--<input type="text" value="" name="action" id="action" class="form-control" > -->
         <div class="invalid-feedback">
           Please enter action taken
         </div>
       </div>
      </div>
<!--(END)ACTION TAKEN-->

          <div class="form-group row">
            <label for="remarks" class="col-sm-2 col-form-label col-form-label-lg">REMARKS</label>
            <div class="col-sm-10"   >
              <textarea class="form-control" id="remarks" name="remarks" ></textarea>
              <div class="invalid-feedback">
                Please enter job remarks
              </div>
            </div>
          </div>

<!--(START)SPAREPART-->
          <div class="form-group row">
            <label for="sparePart" class="col-sm-2 col-form-label col-form-label-lg">SPARE PART</label>
            <div class="col-sm-10"   >
              <table class="table order-list table-responsive  table-hover table-bordered" id="spTable">
              <tr>
                <th style="width:75%;background: gray;">DESCRIPTION</th>
                <th style="width:25%;background: gray;">QUANTITY</th>
              </tr>
              <!-- (START)FETCH FROM JOBSPAREPARTS TALBE -->
                <script> var i = 0; </script>
                <?php
                //$dataList = NULL;
                $dataList = tableSparePart();
                if ($dataList == NULL) {

                }
                else {
                  foreach($dataList as $data) {
                  echo "<tr><td>".$data['description']."</td><td>".$data['qty']."</td></tr>";
                  ?><script> i++; </script><?php
                  }
                }
                ?>
              <!-- (END)FETCH FROM JOBSPAREPARTS TABLE -->
              <tr>
                <td><input type="text" name='spDes0' class="form-control"/></td>
                <td><input type="number" name='spQty0' class="form-control"/></td>
              </tr>
              <tr>
                <td><button onclick="addTableRow()" type="button" class="btn btn-lg btn-block btn-success fa fa-plus "></td>
                  <td><button type='button' onclick='removeTableRow()' class='ibtnDel btn btn-md btn-danger fa fa-minus' ></button></td>
              </tr>
              <input type="text" hidden name="jobId" id="jobId" value="<?php echo $_POST['jobId'] ?>"  />
            </table>
            <script>
            //var i=0;
            var n=0
            //COOKIE
            function setCookie(cname,cvalue,exdays) {
              var d = new Date();
              d.setTime(d.getTime() + (exdays*24*60*60*1000));
              var expires = "expires=" + d.toGMTString();
              document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
            }

            function numRow(){
              i++;
              return i;
            }

            function increment(){
              n++;
              return n;
            }

            function addTableRow() {
              var i=numRow();
              var n=increment();
              var table = document.getElementById("spTable");
              var row = table.insertRow(i+1);
              var cell1 = row.insertCell(0);
              var cell2 = row.insertCell(1);
              cell1.innerHTML = "<input type='text' name='spDes"+i+"' class='form-control'/>";
              cell2.innerHTML = "<input type='number' name='spQty"+i+"' class='form-control'/>";
              cell3.innerHTML = n;
            }

            function removeTableRow() {
              if (i!=0) {
                document.getElementById("spTable").deleteRow(i+1);
                i--;
              }
            }


            </script>
            <br>
            </div>
          </div>
<!--(END)SPAREPART-->

          <div class='form-group row' id='signatureParent' >
            <label for='signature' class='col-sm-2 col-form-label col-form-label-lg'  >CUSTOMER SIGNATURE</label>
              <div class='col-sm-10' >
                <div id="signature" name="signature" ></div>
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
                <button name="updateAssignedTaskOrgStaff"  class="btn btn-primary btn-lg btn-block" type='submit' >SUBMIT</button>
               <?php }
               else{
                 ?>
                  <button name='updateImmediateJobOrgStaff'  class="btn btn-primary btn-lg btn-block" type='submit' >SUBMIT</button>

                    <?php
                }
                    ?>
              </div>
          </div>



</div>

</body>
</html>
