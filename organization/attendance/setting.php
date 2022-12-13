<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
 session_name($config['sessionName']);
   session_start();
}
	require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/attendanceSetting.php");
	$data=getLateEmailSetting();
?>
<!DOCTYPE html>

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
//      require($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/organization/staff/moreForm/form.php");
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
    .py2 {
	    margin: 10px;
	    display: inline-flex;
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
        <li class="breadcrumb-item active">Staff</li>
        <li class="breadcrumb-item active">Late Attendance Setting</li>
      </ol>
      </div>
      <div class="container" >
          <form method="POST" action="../../phpfunctions/attendanceSetting.php" class="needs-validation" novalidate >
          <?php
                if (isset($_SESSION['feedback'])) {
                    echo $_SESSION['feedback'];
                    unset($_SESSION['feedback']);
                }
            ?>
            <div id="criteriaForm">
              <div class="form-group row">
		            <label for="startTime" class="col-sm-2 col-form-label col-form-label-lg">Start Time</label>
		            <div class="col-sm-10 col-md-4"   >
			            <input type="time" placeholder="Enter Start Time"  class="form-control" id="startTime" name="startTime" value="<?php echo $data['startTime'] ?>" required />
			            <div class="invalid-feedback">
				            Please enter start time
			            </div>
		            </div>
		            <label for="endTime" class="col-sm-2 col-form-label col-form-label-lg">End Time</label>
		            <div class="col-sm-10 col-md-4"   >
			            <input type="time" placeholder="Enter End Time"  class="form-control" id="endTime" name="endTime" value="<?php echo $data['endTime'] ?>" required />
			            <div class="invalid-feedback">
				            Please enter end time
			            </div>
		            </div>
	            </div>
	            <div class="form-group row">
		            <label class="col-sm-12 col-md-2 col-form-label col-form-label-lg">Working Day:</label>
		            <label for="workingDayStart" class="col-sm-2 col-md-1 col-form-label col-form-label-lg">From</label>

		            <div class="col-sm-10 col-md-4"   >
			            <select id="workingDayStart" name="workingDayStart" class="form-control workingDayStart" required="required">
				            <option selected value="0">-- Select Start Day --</option>
				            <option value="Monday" <?php if(strcasecmp($data['workingStart'],"Monday")==0){ echo "selected";}?>>Monday</option>
				            <option value="Tuesday" <?php if(strcasecmp($data['workingStart'],"Tuesday")==0){ echo "selected";}?>>Tuesday</option>
				            <option value="Wednesday" <?php if(strcasecmp($data['workingStart'],"Wednesday")==0){ echo "selected";}?>>Wednesday	</option>
				            <option value="Thursday" <?php if(strcasecmp($data['workingStart'],"Thursday")==0){ echo "selected";}?>>Thursday</option>
				            <option value="Friday" <?php if(strcasecmp($data['workingStart'],"Friday")==0){ echo "selected";}?>>Friday</option>
				            <option value="Saturday" <?php if(strcasecmp($data['workingStart'],"Saturday")==0){ echo "selected";}?>>Saturday</option>
				            <option value="Sunday" <?php if(strcasecmp($data['workingStart'],"Sunday")==0){ echo "selected";}?>>Sunday</option>
				            </select>
			            <div class="invalid-feedback">
				            Please enter start day
			            </div>
		            </div>
		            <label for="workingDayEnd" class="col-sm-2 col-md-1 col-form-label col-form-label-lg">To</label>
		            <div class="col-sm-10 col-md-4"   >
			            <select id="workingDayEnd" name="workingDayEnd" class="form-control workingDayStart" required="required">
				            <option selected value="0">-- Select Last Day --</option>
				            <option value="Monday" <?php if(strcasecmp($data['workingEnd'],"Monday")==0){ echo "selected";}?>>Monday</option>
				            <option value="Tuesday" <?php if(strcasecmp($data['workingEnd'],"Tuesday")==0){ echo "selected";}?>>Tuesday</option>
				            <option value="Wednesday" <?php if(strcasecmp($data['workingEnd'],"Wednesday")==0){ echo "selected";}?>>Wednesday	</option>
				            <option value="Thursday" <?php if(strcasecmp($data['workingEnd'],"Thursday")==0){ echo "selected";}?>>Thursday</option>
				            <option value="Friday" <?php if(strcasecmp($data['workingEnd'],"Friday")==0){ echo "selected";}?>>Friday</option>
				            <option value="Saturday" <?php if(strcasecmp($data['workingEnd'],"Saturday")==0){ echo "selected";}?>>Saturday</option>
				            <option value="Sunday" <?php if(strcasecmp($data['workingEnd'],"Sunday")==0){ echo "selected";}?>>Sunday</option>
			            </select>
			            <div class="invalid-feedback">
				            Please enter start day
			            </div>
		            </div>
	            </div>
            <div class="form-group row">
	            <label for="emailList" class="col-sm-2 col-form-label col-form-label-lg">Officer Email List</label>
	            <div class="col-sm-10"   >
		            <select placeholder="Select Officer Email"  class="form-control emailList" id="emailList" name="emailList[]" multiple="multiple" size="5" required >
			            	<?php echo listOfficerEmail(); ?>
			            </select>
		            <a href="#" class="py2 btn" data-toggle="modal" data-target="#messageContent"> Add Email in List </a>&nbsp;&nbsp;&nbsp;<a href="#" class="py2 btn bg-danger delete" data-toggle="modal" data-target="#deleteContent"> Remove Email from List</a>
		            <div class="invalid-feedback">
			            Please enter officer email
		            </div>
	            </div>
            </div>
	            <div class="form-group row">
		            <label for="lateTimeSet" class="col-sm-12 col-md-4 col-form-label col-form-label-lg">Consider Late After(automated email shoot)</label>
		            <div class="col-sm-12 col-md-4"   >
			            <input type="time" placeholder="Enter Start Time"  class="form-control" id="lateTimeSet" name="lateTimeSet" value="<?php echo $data['emailTime'] ?>" required />
			            <div class="invalid-feedback">
				            Please enter start time
			            </div>
		            </div>
	            </div>

              <div class="form-group row">
                  <label class="col-sm-2 col-form-label col-form-label-lg"></label>
                  <div class="col-sm-10">
                      <button name='lateAttendanceSetup' class="btn btn-primary btn-lg btn-block" type='submit' style="text-align: center;">Save Setting</button>
                  </div>
              </div>


            </div>
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



<div class="modal fade" id="messageContent" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<form action="../../phpfunctions/attendanceSetting.php" method="post" >
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="messageContentModalTitle">OFFICER EMAIL ADD</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group row">
						<label for="emailAdd" class="col-sm-12 col-form-label col-form-label-lg">Officer Email Id</label>
						<div class="col-sm-12"   >
							<input type="email" placeholder="Enter Officer Email"  class="form-control" id="emailAdd" name="emailAdd" required />
							<div class="invalid-feedback">
								Please enter email
							</div>
						</div>
					</div>


				</div>
				<div class="modal-footer">
					<button type="submit" name='addOfficerEmail' class="btn btn-primary" >ADD EMAIL</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">CLOSE</button>
				</div>
			</div>
		</div>
	</form>
</div>

<div class="modal fade" id="deleteContent" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<form action="../../phpfunctions/attendanceSetting.php" method="post" >
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="messageContentModalTitle">OFFICER EMAIL DELETE</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group row">
						<label for="emailDelete" class="col-sm-12 col-form-label col-form-label-lg">Are You Sure Delete Officer Email Id</label>
						<div class="col-sm-12"   >
							<input type="text" placeholder="Enter Officer Email"  class="form-control emailDelete" id="emailDelete" name="emailDelete" required />
							<div class="invalid-feedback">
								Please enter email
							</div>
						</div>
					</div>


				</div>
				<div class="modal-footer">
					<button type="submit" name='deleteOfficerEmail' class="btn btn-primary" >DELETE EMAIL</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">CLOSE</button>
				</div>
			</div>
		</div>
	</form>
</div>

<script>
	$(document).ready(function(){
		$('.delete').click(function(){
			var list= $('.emailList').val();
			var txtList="";
			jQuery.each( list, function( i, val ) {
				if(i==0){
				txtList=txtList+val;}
				else{
					txtList=txtList+","+val;
				}
			});
			jQuery('.emailDelete').val(txtList);
		});

		$('select .workingDayStart').val('<?php echo $data['workingStart'] ?>');
		$('select .workingDayEnd').val('<?php echo $data['workingEnd'] ?>');

	});
</script>
</body>
</html>
