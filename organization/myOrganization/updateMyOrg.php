
<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
 session_name($config['sessionName']);
   session_start();
}
require($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");
//loadOrganizationDetail();
$orgDetails=fetchOrganizationDetails($_SESSION['orgId']);

?>
<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">


    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />

    <!--
    <script src="../../js/jquery-3.3.1.slim.min.js" ></script>
    <script src="../../js/bootstrap.min.js" ></script>
    -->
    <?php
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>
    <Script>
    $(function() {
      var state='<?php echo $orgDetails['state']; ?>';

      if(state.length>0){
        state=state.charAt(0)+state.substr(1).toLowerCase();
        $('#state').val(state);
      }


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
            .bg-red{
                background-color: #E32526;
            }

                                .hvrbox * {
	box-sizing: border-box;
}
.hvrbox {
	position: relative;
	display: inline-block;
	overflow: hidden;
	max-width: 100%;
	height: auto;
}
.hvrbox img {
	max-width: 100%;
}
.hvrbox .hvrbox-layer_bottom {
	display: block;
}
.hvrbox .hvrbox-layer_top {
	opacity: 0;
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	width: 100%;
	height: 100%;
	background: rgba(0, 0, 0, 0.6);
	color: #fff;
	padding: 15px;
	-moz-transition: all 0.4s ease-in-out 0s;
	-webkit-transition: all 0.4s ease-in-out 0s;
	-ms-transition: all 0.4s ease-in-out 0s;
	transition: all 0.4s ease-in-out 0s;
}
.hvrbox:hover .hvrbox-layer_top,
.hvrbox.active .hvrbox-layer_top {
	opacity: 1;
}
.hvrbox .hvrbox-text {
	text-align: center;
	font-size: 18px;
	display: inline-block;
	position: absolute;
	top: 50%;
	left: 50%;
	-moz-transform: translate(-50%, -50%);
	-webkit-transform: translate(-50%, -50%);
	-ms-transform: translate(-50%, -50%);
	transform: translate(-50%, -50%);
}
.hvrbox .hvrbox-text_mobile {
	font-size: 15px;
	border-top: 1px solid rgb(179, 179, 179); /* for old browsers */
	border-top: 1px solid rgba(179, 179, 179, 0.7);
	margin-top: 5px;
	padding-top: 2px;
	display: none;
}
.hvrbox.active .hvrbox-text_mobile {
	display: block;
}
    </style>

</head>
<body class="fixed-nav " >

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
        <li class="breadcrumb-item active">Update My Organization</li>

      </ol>
    </div>
      <div class="container">
        <?php
            if (isset($_SESSION['feedback'])) {
                echo $_SESSION['feedback'];
                unset($_SESSION['feedback']);
            }
        ?>

    <!-- (START)BANNER -->
    <div class="hvrbox" style="cursor: pointer;" data-toggle='modal' data-target='#changeBannerModal'>
    	<img src='../../resources/2/myOrg/banner/<?php
    	$orgId = $_SESSION['orgId'];
    	$bannerName = bannerName($orgId);
    	echo $bannerName;
    	?>' width='582px'>
    	<div class="hvrbox-layer_top">
    		<div class="hvrbox-text">Click the image to upload company report banner<br>Resolution: 582x145 px<br>Format: .PNG Only</div>
    	</div>
    </div>
    <div><small class="text-muted pl-0">[Click the image to upload company report banner]</small></div>
    <!-- (END)BANNER -->

      <form method="POST" action="../../phpfunctions/organization.php" class="needs-validation " novalidate
      enctype="multipart/form-data" >

        <div id="jobListForm">

        <div class="form-group">
          <label for="orgName" class="col-form-label col-form-label-lg">Organization Name</label>
            <input type="text" class="form-control" placeholder="MY COMPANY SDN BHD"  value="<?php echo $orgDetails['name']; ?>" id="orgName" name="orgName" required />
            <div class="invalid-feedback">
              Please enter organization name
            </div>
        </div>


        <div class="form-group ">
          <label for="address1" class="col-form-label col-form-label-lg">Address 1</label>

            <input type="text" class="form-control" placeholder="Street address, P.O box, c/o" value="<?php echo $orgDetails['address1']; ?>" id="address1" name="address1" required />
            <div class="invalid-feedback">
              Please enter address 1.
            </div>
            <!--
              <p class="help-block">Street address, P.O box, c/o</p>
            -->

        </div>

        <div class="form-group">
          <label for="address2" class="col-form-label col-form-label-lg">Address 2</label>
            <input type="text" class="form-control" placeholder="Building, Suite, unit, floor" value="<?php echo $orgDetails['address2']; ?>" id="address2" name="address2" />
            <div class="invalid-feedback">
              Please enter address 2.
            </div>

        </div>

        <div class="form-group ">
          <label for="city" class=" col-form-label col-form-label-lg">City / Town</label>
            <input type="text" class="form-control" placeholder="City" value="<?php echo $orgDetails['city']; ?>" id="city" name="city" required />
            <div class="invalid-feedback">
              Please enter city / town name
            </div>
        </div>

      <div class="form-group ">
        <label for="postalCode" class=" col-form-label col-form-label-lg">Zip  / Postal Code</label>
            <input type="text" class="form-control" placeholder="Zip or postal code" value="<?php echo $orgDetails['postalCode']; ?>" id="postalCode" name="postalCode" required />
            <div class="invalid-feedback">
              Please enter zip /postal code
            </div>
        </div>

        <div class="form-group ">
          <label for="state" class=" col-form-label col-form-label-lg">State</label>
            <select name="state"  id="state" class="form-control" required >
              <option  value="" selected disabled >--Select A State--</option>
              <option value="Johor">Johor</option>
              <option value="Kedah">Kedah</option>
              <option value="Kelantan">Kelantan</option>
              <option value="Kuala lumpur">Kuala Lumpur</option>
              <option value="Labuan">Labuan</option>
              <option value="Malacca">Malacca</option>
              <option value="Negeri sembilan">Negeri Sembilan</option>
              <option value="Pahang">Pahang</option>
              <option value="Perak">Perak</option>
              <option value="Perlis">Perlis</option>
              <option value="Penang">Penang</option>
              <option value="Sabah">Sabah</option>
              <option value="Sarawak">Sarawak</option>
              <option value="Selangor">Selangor</option>
              <option value="Terengganu">Terengganu</option>
            </select>
				</div>

        <div class="form-group ">
        <label for="orgContactNo" class=" col-form-label col-form-label-lg">Phone No.</label>
            <input type="text" class="form-control" placeholder="xx-xxx xxxx" value="<?php echo $orgDetails['contact']; ?>" id="orgContactNo" name="orgContactNo" />
            <div class="invalid-feedback">
              Please enter organization phone no.
            </div>
        </div>



        <div class="form-group ">
          <label for="orgFaxNo" class=" col-form-label col-form-label-lg">Fax No.</label>
            <input type="text" class="form-control" placeholder="xx-xxx xxx" value="<?php echo $orgDetails['faxNo']; ?>" id="orgFaxNo" name="orgFaxNo" />
            <div class="invalid-feedback">
              Please enter organization fax no.
            </div>
        </div>

        <div class="form-group">
          <label for="orgName" class="col-form-label col-form-label-lg">Support Email</label>
            <input type="email" class="form-control" placeholder="email"  value="<?php echo $orgDetails['supportEmail']; ?>" id="supportEmail" name="supportEmail" required />
            <div class="invalid-feedback">
              Please enter support email
            </div>
        </div>
	        <div class="form-group">
		        <label for="orgName" class="col-form-label col-form-label-lg">Sales Email</label>
		        <input type="email" class="form-control" placeholder="email"  value="<?php echo $orgDetails['salesEmail']; ?>" id="salesEmail" name="salesEmail" required />
		        <div class="invalid-feedback">
			        Please enter sales email
		        </div>
	        </div>


        <div class="form-group ">
          <label for="orgLogo" class="col-form-label col-form-label-lg">Logo</label>
            <div class="col-sm-10 pl-0"  >
                <input type="file" onchange="$(this).next().after().text($(this).val().split('\\').slice(-1)[0])"
                class="custom-file-input" accept="image/png" id="orgLogo" name="orgLogo">
                <label class="custom-file-label" for="orgLogo">Upload Company logo...</label>
                <small class="text-muted pl-0">[Browse image to upload company logo]</small>
                <div class="invalid-feedback">Example invalid custom file feedback
                </div>
              </div>
        </div>
        <div class="form-group ">
            <label class="col-sm-2 col-form-label col-form-label-sm"></label>
                <button name='updateOrganization' class="btn btn-primary btn-lg btn-block" type='submit' >SAVE</button>
        </div>


        </div>
      </form>

            <!-- Change Image Modal START-->
            <form action="../../phpfunctions/organization.php" method="post" enctype="multipart/form-data">
              <div class="modal fade" id="changeBannerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="changeBannerModalTitle">Change Report Banner</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                        <i>*Resolution: 582x145 px*<br>*Format: .PNG Only*</i><br>
                          <input type="file" name="fileToUpload" id="fileToUpload">
                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary btn-sm " name="changeBanner" >Upload</button>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
            <!-- Change Image Modal END -->

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
