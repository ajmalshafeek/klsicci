<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
  session_name($config['sessionName']);
  session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientCompany.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/mail.php");
$id="";
if(isset($_POST['addNewClient'])){
    $id=$_POST['clientIdToEdit'];
    if($_POST['addNewClient']==0){
        updatePotantialClient($id,$_POST['addNewClient']);
    }
}
if(isset($_POST['sendForm'])){
    $id=$_POST['clientIdToEdit'];
    sendFormEmail($id);
    header("Location: https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/client/potentialClients.php");
}
if(!empty($id)){
$data=getNewClientDetails($id);}

require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/vendor.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/organization/client/moreForm/form.php");
require($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/product.php");

?>
<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />
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
    </script>
    <style>
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

  <div class="content-wrapper">
    <div class="container-fluid">
        <?php echo shortcut() ?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb col-md-12">
        <li class="breadcrumb-item">
          <a href="../../home.php">Dashboard</a>
        </li>
        <li class="breadcrumb-item">Client</li>
        <li class="breadcrumb-item active">Add Client</li>
      </ol>
    </div>
      <div class="container">
            <form method="POST" action="../../phpfunctions/clientCompany.php" class="needs-validation" novalidate >
            <?php
                  if (isset($_SESSION['feedback'])) {
                      echo $_SESSION['feedback'];
                      unset($_SESSION['feedback']);
                  }
              ?>
              <div id="clientForm">
                <div class="form-group row">
                  <label for="clientName" class="col-sm-2 col-form-label col-form-label-lg">Company Name</label>
                  <div class="col-sm-10"   >
                    <input type="text" class="form-control" placeholder="Enter company name" id="clientName" name="clientName" value="<?php if(isset($data['company'])){echo $data['company'];} ?>" required />
                    <div class="invalid-feedback">
                    Please enter company name
                  </div>
                  </div>
                </div>
                  <?php if($_SESSION['orgType']==8||$_SESSION['orgType']==3){ ?>
                  <div class="form-group row">
                      <label for="clientName" class="col-sm-2 col-form-label col-form-label-lg">Client Name</label>
                      <div class="col-sm-10"   >
                          <input type="text" class="form-control" placeholder="Enter client name" id="name" name="name" value="<?php if(isset($data['name'])){echo $data['name'];} ?>" <?php if($_SESSION['orgType']==3){echo 'required'; } ?> />
                          <div class="invalid-feedback">
                              Please enter client name
                          </div>
                      </div>
                  </div>
                      <?php if(isset($_SESSION['orgType'])&&$_SESSION['orgType']==3) { ?>
                      <div class="form-group row">
                          <label for="business" class="col-sm-2 col-form-label col-form-label-lg">Business Type</label>
                          <div class="col-sm-10"   >
                              <input type="text" class="form-control" placeholder="Enter business type" id="business" name="business" value="<?php if(isset($data['business'])){echo $data['business'];} ?>"  <?php if($_SESSION['orgType']==3){echo 'required'; } ?> />
                              <div class="invalid-feedback">
                                  Please enter business type
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="incorp" class="col-sm-2 col-form-label col-form-label-lg">Date of Incorporation</label>
                          <div class="col-sm-2"   >
                              <input type="date" class="form-control" placeholder="Enter Date of Incorporation" id="incorp" name="incorp" value="<?php if(isset($data['incorp'])){echo $data['incorp'];} ?>"  <?php if($_SESSION['orgType']==3){echo 'required'; } ?> />
                              <div class="invalid-feedback">
                                  Please enter date of incorporation
                              </div>
                          </div>
                          <label for="financialMonth" class="col-sm-2 col-form-label col-form-label-lg">Financial Month</label>
                          <div class="col-sm-2">
                          <select type="text" class="form-control" placeholder="Enter Financial Month" id="financialMonth" name="financialMonth" value=""  <?php if($_SESSION['orgType']==3){echo 'required'; } ?> >
                            <option value="" >--Select--</option>
                                 <?php
                                 $tempYear = date("Y");
                                 for($i=1;$i<=12;$i++){ ?>
                                    <option value="<?php echo $i; ?>">
                                        <?php echo $i; ?>
                                    </option>
                                 <?php  } ?>
                                </select>
                              <div class="invalid-feedback">
                                  Please enter financial month
                              </div>
                          </div>
                          <label for="financialYear" class="col-sm-2 col-form-label col-form-label-lg">Financial Year</label>
                          <div class="col-sm-2">
                              <select type="text" class="form-control" placeholder="Enter Financial Year " id="financialYear" name="financialYear" value=""  <?php if($_SESSION['orgType']==3){echo 'required'; } ?> >
                                  <option value="" >--Select--</option>
                                  <?php
                                  $tempYear = date("Y");
                                  for($i=1;$i<=5;$i++){
                                      ?>
                                      <option value="<?php echo $tempYear +1 - $i; ?>">
                                          <?php echo $tempYear +1 - $i; ?>
                                      </option>
                                  <?php  } ?>
                              </select>
                              <div class="invalid-feedback">
                                  Please enter financial year
                              </div>
                          </div>
                      </div>
                          <?php } ?>
                      <div class="form-group row">
                          <label for="clientName" class="col-sm-2 col-form-label col-form-label-lg">Registration Number</label>
                          <div class="col-sm-10"   >
                              <input type="text" class="form-control" placeholder="Enter Registration No" id="register" name="register" value="<?php if(isset($data['register'])){echo $data['register'];} ?>" required />
                              <div class="invalid-feedback">
                                  Please enter Registration No
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="clientName" class="col-sm-2 col-form-label col-form-label-lg">Application Role</label>
                          <div class="col-sm-10" >
                              <select name="status" class="form-control">
                                  <option value="2" selected >Frontend Client</option>
                                  <?php /*    <option value="2" <?php if(isset($data['status'])&&$data['status']==2){echo 'selected';} ?>>Frontend Client</option>
                                <option value="1" <?php if(isset($data['status'])&&$data['status']==1){echo 'selected';} ?>>Client User</option> */ ?>
                              </select>
                              <div class="invalid-feedback">
                                  Please enter client user<?php if(isset($data['status'])){echo 'selected';} ?>
                              </div>
                          </div>
                      </div>
                      <input type="hidden" name="id" value="<?php if(isset($data['id'])){echo $data['id'];} ?>">
                           <?php } ?>
                <?php if(isset($_SESSION['orgType'])&&$_SESSION['orgType']==6) { ?>
                 <div class="form-group row">
                  <label for="clientCpa" class="col-sm-2 col-form-label col-form-label-lg">CPA</label>
                  <div class="col-sm-10" >
                    <input type="text" value="" class="form-control" placeholder="Enter cpa" id="clientCpa" name="clientCpa" required />
                    <div class="invalid-feedback">
                    Please enter client CPA
                  </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="clientlat" class="col-sm-2 col-form-label col-form-label-lg">LATITUDE</label>
                  <div class="col-sm-10" >
                    <input type="text" value="" class="form-control" placeholder="Enter latitude" id="clientlat" name="clientlat" required />
                    <div class="invalid-feedback">
                    Please enter client latitude
                  </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="clientlong" class="col-sm-2 col-form-label col-form-label-lg">LONGITUDE</label>
                  <div class="col-sm-10" >
                    <input type="text" value="" class="form-control" placeholder="Enter longitude" id="clientlong" name="clientlong" required />
                    <div class="invalid-feedback">
                    Please enter client longitude
                  </div>
                  </div>
                </div>
              <?php } ?>

              <!--(START)BILLING ADDRESS-->
              <div class="form-group row" style="background: #ADD8E6;padding-top:15px;border-radius:5px;">
                <label for="address1" class="col-sm-2 col-form-label col-form-label-lg">Billing Address:</label>
                <div class="col-sm-10">
                  <!--(START)BILLING ADDRESS1-->
                  <div class="form-group row">
                    <label for="address1" class="col-sm-2 col-form-label col-form-label-lg">&bull;Address 1</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" placeholder="Street address, P.O box, C/O"  id="address1" name="address1" value="<?php if(isset($data['address1'])){echo $data['address1'];} ?>" required />
                      <div class="invalid-feedback">
                        Please enter billing address 1.
                      </div>
                    </div>
                  </div>
                  <!--(END)BILLING ADDRESS1-->
                  <!--(START)BILLING ADDRESS2-->
                  <div class="form-group row">
                    <label for="address2" class="col-sm-2 col-form-label col-form-label-lg">&bull;Address 2</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" placeholder="Building, Suite, Unit, Floor"  id="address2" name="address2" value="<?php if(isset($data['address2'])){echo $data['address2'];} ?>" required />
                      <div class="invalid-feedback">
                        Please enter billing address 2.
                      </div>
                    </div>
                  </div>
                  <!--(END)BILLING ADDRESS2-->
                  <!--(START)CITY/TOWN-->
                  <div class="form-group row">
                    <label for="city" class="col-sm-2 col-form-label col-form-label-lg">&bull;City/Town</label>
                    <div class="col-sm-10">
                      <textarea name="city" class="form-control" id="city" name="city" required><?php if(isset($data['city'])){echo $data['city'];} ?></textarea>
                      <div class="invalid-feedback">
                        Please enter city/town
                      </div>
                    </div>
                  </div>
                  <!--(END)CITY/TOWN-->
                  <!--(START)ZIP/POSTAL CODE-->
                  <div class="form-group row">
                    <label for="postalCode" class="col-sm-2 col-form-label col-form-label-lg">&bull;Zip/Postal Code</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" placeholder="Zip/Postal Code"  id="postalCode" name="postalCode" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" value="<?php if(isset($data['postalCode'])){ echo $data['postalCode']; } ?>" required />
                      <div class="invalid-feedback">
                        Please enter postal code
                      </div>
                    </div>
                  </div>
                  <!--(END)ZIP/POSTAL CODE-->
                  <!--(START)STATE-->
                  <div class="form-group row statemop">
                    <label for="state" class="col-sm-2 col-form-label col-form-label-lg">&bull;State</label>
                    <div class="col-sm-10">
                      <select name="state"  id="state" class="form-control" >
                        <?php if(!isset($data['state'])) { ?><option  value="" <?php if(!isset($data['state'])){echo " ".'selected disabled';} ?> >--Select A State--</option><?php } ?>
                          <?php if(isset($data['state'])) { ?><option  value="<?php if(isset($data['state'])){echo $data['state']." ".'selected';} ?>" ><?php if(isset($data['state'])){echo $data['state'];} ?></option><?php } ?>
                        <option value="Johor">Johor</option>
                        <option value="Kedah">Kedah</option>
                        <option value="Kelantan">Kelantan</option>
                        <option value="Kuala Lumpur">Kuala Lumpur</option>
                        <option value="Labuan">Labuan</option>
                        <option value="Malacca">Malacca</option>
                        <option value="Negeri Sembilan">Negeri Sembilan</option>
                        <option value="Pahang">Pahang</option>
                        <option value="Perak">Perak</option>
                        <option value="Perlis">Perlis</option>
                        <option value="Penang">Penang</option>
                        <option value="Sabah">Sabah</option>
                        <option value="Sarawak">Sarawak</option>
                        <option value="Selangor">Selangor</option>
                        <option value="Terengganu">Terengganu</option>
                      </select>
                      <div class="invalid-feedback">
                        Please select state
                      </div>
                    </div>
                  </div>
	                <div class="form-group row">
		                <label for="statemnot" class="col-sm-2 col-form-label col-form-label-lg">&bull;Enter State Manually</label>
		                <div class="col-sm-10">
			                <label><input type="radio" name="statemnot" id="statemnot1" class="statemnot1" value="1" />&nbsp;Yes</label>&nbsp;&nbsp;
			                <label><input type="radio" name="statemnot" id="statemnot2" class="statemnot2" value="0" checked="checked" />&nbsp;No</label>
			                <div class="invalid-feedback">
				                Please select manual or not
			                </div>
		                </div>
	                </div>
	                <div class="form-group row statemop1">
		                <label for="state" class="col-sm-2 col-form-label col-form-label-lg">&bull;State</label>
		                <div class="col-sm-10">
			                <input type="text" name="state"  id="state" class="form-control" value="<?php if(isset($data['state'])){echo $data['state']; } ?> " />
			                <div class="invalid-feedback">
				                Please select state
			                </div>
		                </div>
	                </div>
	                <div class="form-group row">
		                <label for="country" class="col-sm-2 col-form-label col-form-label-lg">&bull;Country</label>
		                <div class="col-sm-10">
			                <input type="text" name="country"  id="country" class="form-control" value="<?php if(isset($data['country'])){echo $data['country'];}else{echo 'Malaysia';} ?>" />
			                <div class="invalid-feedback">
				                Please select country
			                </div>
		                </div>
	                </div>
                  <!--(END)STATE-->
                </div>
              </div>
              <!--(END)BILLING ADDRESS-->

              <!--(START)INSTALLATION ADDRESS-->
              <div class="form-group row" style="background: #ADD8E6;padding-top:15px;border-radius:5px;">
                <label for="address1" class="col-sm-2 col-form-label col-form-label-lg">Service Address:</label>
                <div class="col-sm-10">
                    <div class="form-group row">
                        <label for="sameAddress" class="col-sm-2 col-form-label col-form-label-lg"></label>
                        <div class="col-sm-10">
                            <button type="button" class="btn btn-lg" placeholder=""  id="sameAddress" name="sameAddress">Click For Fill Same Address</button>
                        </div>
                    </div>
                  <!--(START)INSTALLATION ADDRESS1-->
                  <div class="form-group row">
                    <label for="instalAddress1" class="col-sm-2 col-form-label col-form-label-lg">&bull;Address 1</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" placeholder="Street address, P.O box, C/O"  id="instalAddress1" name="instalAddress1" />
                      <div class="invalid-feedback">
                        Please enter billing address 1.
                      </div>
                    </div>
                  </div>
                  <!--(END)INSTALLATION ADDRESS1-->
                  <!--(START)INSTALLATION ADDRESS2-->
                  <div class="form-group row">
                    <label for="instalAddress2" class="col-sm-2 col-form-label col-form-label-lg">&bull;Address 2</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" placeholder="Building, Suite, Unit, Floor"  id="instalAddress2" name="instalAddress2" />
                      <div class="invalid-feedback">
                        Please enter billing address 2.
                      </div>
                    </div>
                  </div>
                  <!--(END)INSTALLATION ADDRESS2-->
                  <!--(START)CITY/TOWN-->
                  <div class="form-group row">
                    <label for="instalCity" class="col-sm-2 col-form-label col-form-label-lg">&bull;City/Town</label>
                    <div class="col-sm-10">
                      <textarea name="instalCity" class="form-control" id="instalCity" name="instalCity" ></textarea>
                      <div class="invalid-feedback">
                        Please enter city/town
                      </div>
                    </div>
                  </div>
                  <!--(END)CITY/TOWN-->
                  <!--(START)ZIP/POSTAL CODE-->
                  <div class="form-group row">
                    <label for="instalPCode" class="col-sm-2 col-form-label col-form-label-lg">&bull;Zip/Postal Code</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" placeholder="Zip/Postal Code"  id="instalPCode" name="instalPCode" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" />
                      <div class="invalid-feedback">
                        Please enter postal code
                      </div>
                    </div>
                  </div>
                  <!--(END)ZIP/POSTAL CODE-->
                  <!--(START)STATE-->
                  <div class="form-group row stateop">
                    <label for="instalState" class="col-sm-2 col-form-label col-form-label-lg">&bull;State</label>
                    <div class="col-sm-10">
                      <select name="instalState"  id="instalState" class="form-control" >
                        <option  value="" selected >--Select A State--</option>
                        <option value="Johor">Johor</option>
                        <option value="Kedah">Kedah</option>
                        <option value="Kelantan">Kelantan</option>
                        <option value="Kuala Lumpur">Kuala Lumpur</option>
                        <option value="Labuan">Labuan</option>
                        <option value="Malacca">Malacca</option>
                        <option value="Negeri Sembilan">Negeri Sembilan</option>
                        <option value="Pahang">Pahang</option>
                        <option value="Perak">Perak</option>
                        <option value="Perlis">Perlis</option>
                        <option value="Penang">Penang</option>
                        <option value="Sabah">Sabah</option>
                        <option value="Sarawak">Sarawak</option>
                        <option value="Selangor">Selangor</option>
                        <option value="Terengganu">Terengganu</option>
                      </select>
                      <div class="invalid-feedback">
                        Please select state
                      </div>
                    </div>
                  </div>

	                <div class="form-group row">
		                <label for="statenot" class="col-sm-2 col-form-label col-form-label-lg">&bull;Enter State Manually</label>
		                <div class="col-sm-10">
			                <label><input type="radio" name="statenot" id="statenot1" class="statenot1" value="1" />&nbsp;Yes</label>&nbsp;&nbsp;
			                <label><input type="radio" name="statenot" id="statenot2" class="statenot2" value="0" checked="checked" />&nbsp;No</label>
			                <div class="invalid-feedback">
				                Please select manual or not
			                </div>
		                </div>
	                </div>
	                <div class="form-group row stateop1">
		                <label for="instalState" class="col-sm-2 col-form-label col-form-label-lg">&bull;State</label>
		                <div class="col-sm-10">
			                <input type="text" name="instalState"  id="instalState" class="form-control" />
			                <div class="invalid-feedback">
				                Please select state
			                </div>
		                </div>
	                </div>
	                <div class="form-group row">
		                <label for="instalCountry" class="col-sm-2 col-form-label col-form-label-lg">&bull;Country</label>
		                <div class="col-sm-10">
			                <input type="text" name="instalCountry"  id="instalCountry" class="form-control" value="Malaysia" />
			                <div class="invalid-feedback">
				                Please select country
			                </div>
		                </div>
	                </div>
                  <!--(END)STATE-->
                </div>
              </div>
              <!--(END)INSTALLATION ADDRESS-->

              <div class="form-group row">
              <label for="clientContactNo" class="col-sm-2  col-form-label col-form-label-lg">Phone No.</label>
                <div class="col-sm-10"   >

                    <input type="text" class="form-control" placeholder="xx-xxx xxxx"  id="clientContactNo" name="clientContactNo" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" value="<?php if(isset($data['contactNo'])){echo $data['contactNo'];} ?>" required/>
                    <div class="invalid-feedback">
                      Please enter client phone no.
                    </div>
                  </div>
              </div>



              <div class="form-group row">
                <label for="clientFaxNo" class="col-sm-2  col-form-label col-form-label-lg">Fax No.</label>
                  <div class="col-sm-10"   >

                    <input type="text" class="form-control" placeholder="xx-xxx xxx"  id="clientFaxNo" name="clientFaxNo" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" value="<?php if(isset($data['faxNo'])){echo $data['faxNo'];} ?>" />
                    <div class="invalid-feedback">
                      Please enter client fax no.
                    </div>
                  </div>
              </div>



                <div class="form-group row">
                  <label for="clientEmail" class="col-sm-2 col-form-label col-form-label-lg">Client Email</label>
                  <div class="col-sm-10"   >
                    <input type="email"  placeholder="Email Address" class="form-control" id="clientEmail" name="clientEmail" value="<?php if(isset($data['email'])){echo $data['email'];} ?>" required />
                    <div class="invalid-feedback">
                      Please enter client email address
                    </div>
                  </div>
                </div>

                <?php additionalForm($_SESSION['orgType']); ?>


                  <div class="form-group row">
                      <label class="col-sm-2 col-form-label col-form-label-lg"></label>
                      <div class="col-sm-10">
                          <input type="hidden" name="tid" value="<?php if(isset($data['id'])){echo $data['id'];} ?>">
                          <button name='addClientCompanyOrg'
                          <?php
                              require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");

                              $isInLimit=isInLimit($_SESSION['orgId'],2,"client");
                              if($isInLimit){
                                ?>
                                disabled
                                class="btn btn-secondary btn-lg btn-block"
                                <?php
                              }else{
                                ?>
                                  class="btn btn-primary btn-lg btn-block"
                                <?php
                              }
                            ?>
                          type='submit' >SUBMIT</button>
                      </div>
                  </div>


                  </div>
              </form>
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
<script>
	$(document).ready(function() {
		$('.statemop1').css('display','none');
		$('.stateop1').css('display','none');
		$('input[type=radio][name="statenot"]').change(function() {
				if($(this).val()==1){
				$('.stateop').css('display','none');
				$('.stateop1').css('display','flex');
			}
			else{
				$('.stateop').css('display','flex');
				$('.stateop1').css('display','none');
			}
		});

		$('input[type=radio][name="statemnot"]').change(function() {
					if($(this).val()==1){
				$('.statemop').css('display','none');
				$('.statemop1').css('display','flex');
			}
			else{
				$('.statemop').css('display','flex');
				$('.statemop1').css('display','none');
			}
		});
		$('#sameAddress').on('click',function (){
		    $('#instalAddress1').val($('#address1').val());
		    $('#instalAddress2').val($('#address2').val());
		    $('#instalCity').val($('#city').val());
		    $('#instalState').val($('#state').val());
		    $('#instalPCode').val($('#postalCode').val());
		    $('#instalCountry').val($('#country').val());
        });

	});

</script>
</body>
</html>