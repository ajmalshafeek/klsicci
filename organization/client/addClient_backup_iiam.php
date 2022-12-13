<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
  session_name($config['sessionName']);
  session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientCompany.php");
$id="";
if(isset($_POST['addNewClient'])){
    $id=$_POST['clientIdToEdit'];
    if($_POST['addNewClient']==0){
        updatePotantialClient($id,$_POST['addNewClient']);
    }
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
            #iiiam{width: 15px; margin-top: calc(0.5rem + 5px);}
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
        <li class="breadcrumb-item"><?php if(isset($_SESSION['clientas'])&&!empty($_SESSION['clientas'])){ if(isset($_SESSION['membership'])&&$_SESSION['membership']){echo 'Non '.$_SESSION['clientas']; } else{echo $_SESSION['clientas'];} } else{ echo 'Client' ; }?></li>
        <li class="breadcrumb-item active">Add <?php if(isset($_SESSION['clientas'])&&!empty($_SESSION['clientas'])){ if(isset($_SESSION['membership'])&&$_SESSION['membership']){echo 'Non '.$_SESSION['clientas']; } else{echo $_SESSION['clientas'];} } else{ echo 'Client' ; }?></li>
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
                  <?php if(isset($_SESSION['membership'])&&$_SESSION['membership']){ ?>
                  <div class="form-group row">
                      <label for="indRefNo" class="col-sm-2 col-form-label col-form-label-lg">Individual Reference No.</label>
                      <div class="col-sm-4"   >
                          <input type="text" class="form-control" placeholder="Enter individual reference no" id="indRefNo" name="indRefNo" value="<?php if(isset($data['individualRefNo'])){echo $data['individualRefNo'];} ?>" required />
                          <div class="invalid-feedback">
                              Please enter individual reference no
                          </div>
                      </div>
                      <label for="iiiam" class="col-sm-3 col-form-label col-form-label-lg">International IIA Member</label>
                      <div class="col-sm-1">
                          <input type="checkbox" class="form-control" placeholder="international IIA member" id="iiiam" name="iiiam" <?php if(isset($data['internationalIIAMember'])){echo 'checked';} ?> required />
                          <div class="invalid-feedback">
                              Please enter international IIA member
                          </div>
                      </div>
                  </div>
                      <div class="form-group row">
                      <label for="title" class="col-sm-2 col-form-label col-form-label-lg">Title</label>
                      <div class="col-sm-2" >
                          <select name="title" class="form-control" required>
                              <option value="" selected disabled>--Select--</option>
                                <option value="Mr">Mr</option>
                                <option value="Mrs">Mrs</option>
                                <option value="Miss">Miss</option>
                                <option value="Ms">Ms</option>
                                <option value="Mx">Mx</option>
                                <option value="Sir">Sir</option>
                                <option value="Dr">Dr</option>
                          </select>
                          <div class="invalid-feedback">
                              Please enter individual reference no
                          </div>
                      </div>
                      </div>
                      <div class="form-group row">
                          <label for="name" class="col-sm-2 col-form-label col-form-label-lg">First Name</label>
                          <div class="col-sm-4"   >
                              <input type="text" class="form-control" id="name" name="name" placeholder="Enter First Name" value="<?php if(isset($data['firstName'])){echo $data['firstName'];} ?>" />
                              <div class="invalid-feedback">
                                  Please enter first name
                              </div>
                          </div>
                          <label for="lname" class="col-sm-2 col-form-label col-form-label-lg">Last Name</label>
                          <div class="col-sm-4"   >
                              <input type="text" class="form-control" placeholder="Enter Last Name" id="lname" name="lname" value="<?php if(isset($data['lastName'])){echo $data['lastName'];} ?>" />
                              <div class="invalid-feedback">
                                  Please enter last name
                              </div>
                          </div>
                  </div>
                      <div class="form-group row">
                          <label for="icno" class="col-sm-2 col-form-label col-form-label-lg">IC No</label>
                          <div class="col-sm-4"   >
                              <input type="text" class="form-control" placeholder="Enter IC No" id="icno" name="icno" value="<?php if(isset($data['icNo'])){echo $data['icNo'];} ?>" required />
                              <div class="invalid-feedback">
                                  Please enter ic no
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="phone" class="col-sm-2 col-form-label col-form-label-lg">Telephone No</label>
                          <div class="col-sm-4"   >
                              <input type="text" class="form-control" placeholder="Enter Phone No" id="phone" name="phone" value="<?php if(isset($data['phone'])){echo $data['phone'];} ?>" required />
                              <div class="invalid-feedback">
                                  Please enter ic no
                              </div>
                          </div>
                          <label for="fax" class="col-sm-2 col-form-label col-form-label-lg">Fax No</label>
                          <div class="col-sm-4"   >
                              <input type="text" class="form-control" placeholder="Enter Fax No" id="fax" name="fax" value="<?php if(isset($data['fax'])){echo $data['fax'];} ?>" required />
                              <div class="invalid-feedback">
                                  Please fax
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="designation" class="col-sm-2 col-form-label col-form-label-lg">Designation</label>
                          <div class="col-sm-10"   >
                              <input type="text" class="form-control" placeholder="Enter designation" id="designation" name="designation" value="<?php if(isset($data['designation'])){echo $data['designation'];} ?>" required />
                              <div class="invalid-feedback">
                                  Please enter designation
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="website" class="col-sm-2 col-form-label col-form-label-lg">Website</label>
                          <div class="col-sm-10"   >
                              <input type="url" class="form-control" placeholder="Enter website" id="website" name="website" value="<?php if(isset($data['designation'])){echo $data['designation'];} ?>" required />
                              <div class="invalid-feedback">
                                  Please enter website
                              </div>
                          </div>
                      </div>
                  <?php } ?>

                <div class="form-group row">
                  <label for="clientName" class="col-sm-2 col-form-label col-form-label-lg"><?php if(isset($_SESSION['membership'])){ echo 'Corporate'; } else{ echo 'Company';} ?> Name</label>
                  <div class="col-sm-10"   >
                    <input type="text" class="form-control" placeholder="Enter <?php if(isset($_SESSION['membership'])){ echo 'corporate'; } else{ echo 'company';} ?> name" id="clientName" name="clientName" value="<?php if(isset($data['company'])){echo $data['company'];} ?>" required />
                    <div class="invalid-feedback">
                    Please enter <?php if(isset($_SESSION['membership'])){ echo 'corporate'; } else{ echo 'company';} ?> name
                  </div>
                  </div>
                </div>
                  <?php if($_SESSION['orgType']==8||$_SESSION['orgType']==3){
                      if(isset($_SESSION['membership'])&&!$_SESSION['membership']){ ?>

                  <div class="form-group row">
                      <label for="clientName" class="col-sm-2 col-form-label col-form-label-lg"><?php if(isset($_SESSION['clientas'])&&!empty($_SESSION['clientas'])){ echo $_SESSION['clientas']; } else { echo 'Client' ; }?> Name</label>
                      <div class="col-sm-10"   >
                          <input type="text" class="form-control" placeholder="Enter <?php if(isset($_SESSION['clientas'])&&!empty($_SESSION['clientas'])){ echo $_SESSION['clientas']; } else { echo 'Client' ; }?> name" id="name" name="name" value="<?php if(isset($data['name'])){echo $data['name'];} ?>" <?php if($_SESSION['orgType']==3){echo 'required'; } ?> />
                          <div class="invalid-feedback">
                              Please enter <?php if(isset($_SESSION['clientas'])&&!empty($_SESSION['clientas'])){ echo $_SESSION['clientas']; } else { echo 'Client' ; }?> name
                          </div>
                      </div>
                  </div>
                      <?php }
                          if(isset($_SESSION['orgType'])&&$_SESSION['orgType']==3) { ?>
                             <?php if(isset($_SESSION['membership'])&&!$_SESSION['membership']){ ?>
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
                          <div class="col-sm-4"   >
                              <input type="date" class="form-control" placeholder="Enter Date of Incorporation" id="incorp" name="incorp" value="<?php if(isset($data['incorp'])){echo $data['incorp'];} ?>"  <?php if($_SESSION['orgType']==3){echo 'required'; } ?> />
                              <div class="invalid-feedback">
                                  Please enter date of incorporation
                              </div>
                          </div>
                          <label for="financialYear" class="col-sm-2 col-form-label col-form-label-lg">Financial Year</label>
                          <div class="col-sm-4"   >
                              <input type="month" class="form-control" placeholder="Enter Financial Year" id="financialYear" name="financialYear" value="<?php if(isset($data['financialYear'])){echo $data['financialYear'];} ?>"  <?php if($_SESSION['orgType']==3){echo 'required'; } ?> />
                              <div class="invalid-feedback">
                                  Please enter financial year
                              </div>
                          </div>
                      </div>
                          <?php } } ?>
                  <?php if(isset($_SESSION['membership'])&&!$_SESSION['membership']){ ?>
                      <div class="form-group row">
                          <label for="clientName" class="col-sm-2 col-form-label col-form-label-lg">Registration Number</label>
                          <div class="col-sm-10"   >
                              <input type="text" class="form-control" placeholder="Enter Registration No" id="register" name="register" value="<?php if(isset($data['register'])){echo $data['register'];} ?>" required />
                              <div class="invalid-feedback">
                                  Please enter Registration No
                              </div>
                          </div>
                      </div>
                      <?php } ?>
                  <?php if(isset($_SESSION['membership'])&&!$_SESSION['membership']){ ?>
                      <div class="form-group row">
                          <label for="clientName" class="col-sm-2 col-form-label col-form-label-lg">Application Role</label>
                          <div class="col-sm-10" >
                              <select name="status" class="form-control">
                                  <option value="1" selected >Frontend Client</option>
                                  <?php /*    <option value="2" <?php if(isset($data['status'])&&$data['status']==2){echo 'selected';} ?>>Frontend Client</option>
                                <option value="1" <?php if(isset($data['status'])&&$data['status']==1){echo 'selected';} ?>>Client User</option> */ ?>
                              </select>
                              <div class="invalid-feedback">
                                  Please enter client user<?php if(isset($data['status'])){echo 'selected';} ?>
                              </div>
                          </div>
                      </div>
                      <?php }else{ ?>
                          <input type="hidden" name="status" value="1">
                          <?php } ?>
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
                        <input type="text" class="form-control" placeholder="Enter City"  id="instalCity" name="instalCity" value="<?php if(isset($data['city'])){echo $data['city'];} ?>" />
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
			              <?php /*  <input type="text" name="country"  id="country" class="form-control" value="<?php if(isset($data['country'])){echo $data['country'];}else{echo 'Malaysia';} ?>" /> */ ?>

                            <select name="country" class="form-control" >
                                <option value="" <?php if(!isset($data['country'])){ ?> selected disabled <?php } ?>>Select Country</option>
                                <?php if(isset($data['country'])){?>  <option value="<?php if(isset($data['country'])){echo $data['country'];} ?>"  <?php if(isset($data['country'])){ ?> selected <?php } ?>><?php if(isset($data['country'])){echo $data['country'];} ?></option> <?php } ?>
                                <?php echo country(); ?>
                            </select>
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
                <label for="address1" class="col-sm-2 col-form-label col-form-label-lg"><?php if(isset($_SESSION['membership'])){ echo 'Corporate';}else{echo 'Service'; }?> Address:</label>
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
                        <input type="text" class="form-control" placeholder="Enter City"  id="instalCity" name="instalCity" />
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
			               <?php // <input type="text" name="instalCountry"  id="instalCountry" class="form-control" value="Malaysia" /> ?>
			               <select name="instalCountry" class="form-control" >
                               <option value="" selected disabled>Select Country</option>
                               <?php echo country(); ?>
                           </select>
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
              <label for="clientContactNo" class="col-sm-2  col-form-label col-form-label-lg"><?php if(isset($_SESSION['membership'])){ echo 'Corporate';} ?> Phone No.</label>
                <div class="col-sm-10"   >

                    <input type="text" class="form-control" placeholder="xx-xxx xxxx"  id="clientContactNo" name="clientContactNo" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" value="<?php if(isset($data['contactNo'])){echo $data['contactNo'];} ?>" required/>
                    <div class="invalid-feedback">
                      Please enter client phone no.
                    </div>
                  </div>
              </div>



              <div class="form-group row">
                <label for="clientFaxNo" class="col-sm-2  col-form-label col-form-label-lg"><?php if(isset($_SESSION['membership'])){ echo 'Corporate';} ?> Fax No.</label>
                  <div class="col-sm-10"   >

                    <input type="text" class="form-control" placeholder="xx-xxx xxx"  id="clientFaxNo" name="clientFaxNo" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" value="<?php if(isset($data['faxNo'])){echo $data['faxNo'];} ?>" />
                    <div class="invalid-feedback">
                      Please enter client fax no.
                    </div>
                  </div>
              </div>
                <div class="form-group row">
                  <label for="clientEmail" class="col-sm-2 col-form-label col-form-label-lg">Email</label>
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

<?php function country(){
    $data= '<option value="Afghanistan">Afghanistan</option>
    <option value="Aland Islands">Aland Islands</option>
    <option value="Albania">Albania</option>
    <option value="Algeria">Algeria</option>
    <option value="American Samoa">American Samoa</option>
    <option value="Andorra">Andorra</option>
    <option value="Angola">Angola</option>
    <option value="Anguilla">Anguilla</option>
    <option value="Antarctica">Antarctica</option>
    <option value="Antigua and Barbuda">Antigua and Barbuda</option>
    <option value="Argentina">Argentina</option>
    <option value="Armenia">Armenia</option>
    <option value="Aruba">Aruba</option>
    <option value="Australia">Australia</option>
    <option value="Austria">Austria</option>
    <option value="Azerbaijan">Azerbaijan</option>
    <option value="Bahamas">Bahamas</option>
    <option value="Bahrain">Bahrain</option>
    <option value="Bangladesh">Bangladesh</option>
    <option value="Barbados">Barbados</option>
    <option value="Belarus">Belarus</option>
    <option value="Belgium">Belgium</option>
    <option value="Belize">Belize</option>
    <option value="Benin">Benin</option>
    <option value="Bermuda">Bermuda</option>
    <option value="Bhutan">Bhutan</option>
    <option value="Bolivia">Bolivia</option>
    <option value="Bonaire, Sint Eustatius and Saba">Bonaire, Sint Eustatius and Saba</option>
    <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
    <option value="Botswana">Botswana</option>
    <option value="Bouvet Island">Bouvet Island</option>
    <option value="Brazil">Brazil</option>
    <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
    <option value="Brunei Darussalam">Brunei Darussalam</option>
    <option value="Bulgaria">Bulgaria</option>
    <option value="Burkina Faso">Burkina Faso</option>
    <option value="Burundi">Burundi</option>
    <option value="Cambodia">Cambodia</option>
    <option value="Cameroon">Cameroon</option>
    <option value="Canada">Canada</option>
    <option value="Cape Verde">Cape Verde</option>
    <option value="Cayman Islands">Cayman Islands</option>
    <option value="Central African Republic">Central African Republic</option>
    <option value="Chad">Chad</option>
    <option value="Chile">Chile</option>
    <option value="China">China</option>
    <option value="Christmas Island">Christmas Island</option>
    <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
    <option value="Colombia">Colombia</option>
    <option value="Comoros">Comoros</option>
    <option value="Congo">Congo</option>
    <option value="Congo, Democratic Republic of the Congo">Congo, Democratic Republic of the Congo</option>
    <option value="Cook Islands">Cook Islands</option>
    <option value="Costa Rica">Costa Rica</option>
    <option value="Cote D\'Ivoire">Cote D\'Ivoire</option>
    <option value="Croatia">Croatia</option>
    <option value="Cuba">Cuba</option>
    <option value="Curacao">Curacao</option>
    <option value="Cyprus">Cyprus</option>
    <option value="Czech Republic">Czech Republic</option>
    <option value="Denmark">Denmark</option>
    <option value="Djibouti">Djibouti</option>
    <option value="Dominica">Dominica</option>
    <option value="Dominican Republic">Dominican Republic</option>
    <option value="Ecuador">Ecuador</option>
    <option value="Egypt">Egypt</option>
    <option value="El Salvador">El Salvador</option>
    <option value="Equatorial Guinea">Equatorial Guinea</option>
    <option value="Eritrea">Eritrea</option>
    <option value="Estonia">Estonia</option>
    <option value="Ethiopia">Ethiopia</option>
    <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
    <option value="Faroe Islands">Faroe Islands</option>
    <option value="Fiji">Fiji</option>
    <option value="Finland">Finland</option>
    <option value="France">France</option>
    <option value="French Guiana">French Guiana</option>
    <option value="French Polynesia">French Polynesia</option>
    <option value="French Southern Territories">French Southern Territories</option>
    <option value="Gabon">Gabon</option>
    <option value="Gambia">Gambia</option>
    <option value="Georgia">Georgia</option>
    <option value="Germany">Germany</option>
    <option value="Ghana">Ghana</option>
    <option value="Gibraltar">Gibraltar</option>
    <option value="Greece">Greece</option>
    <option value="Greenland">Greenland</option>
    <option value="Grenada">Grenada</option>
    <option value="Guadeloupe">Guadeloupe</option>
    <option value="Guam">Guam</option>
    <option value="Guatemala">Guatemala</option>
    <option value="Guernsey">Guernsey</option>
    <option value="Guinea">Guinea</option>
    <option value="Guinea-Bissau">Guinea-Bissau</option>
    <option value="Guyana">Guyana</option>
    <option value="Haiti">Haiti</option>
    <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
    <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
    <option value="Honduras">Honduras</option>
    <option value="Hong Kong">Hong Kong</option>
    <option value="Hungary">Hungary</option>
    <option value="Iceland">Iceland</option>
    <option value="India">India</option>
    <option value="Indonesia">Indonesia</option>
    <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
    <option value="Iraq">Iraq</option>
    <option value="Ireland">Ireland</option>
    <option value="Isle of Man">Isle of Man</option>
    <option value="Israel">Israel</option>
    <option value="Italy">Italy</option>
    <option value="Jamaica">Jamaica</option>
    <option value="Japan">Japan</option>
    <option value="Jersey">Jersey</option>
    <option value="Jordan">Jordan</option>
    <option value="Kazakhstan">Kazakhstan</option>
    <option value="Kenya">Kenya</option>
    <option value="Kiribati">Kiribati</option>
    <option value="Korea, Democratic People\'s Republic of">Korea, Democratic People\'s Republic of</option>
    <option value="Korea, Republic of">Korea, Republic of</option>
    <option value="Kosovo">Kosovo</option>
    <option value="Kuwait">Kuwait</option>
    <option value="Kyrgyzstan">Kyrgyzstan</option>
    <option value="Lao People\'s Democratic Republic">Lao People\'s Democratic Republic</option>'.'
    <option value="Latvia">Latvia</option>
    <option value="Lebanon">Lebanon</option>
    <option value="Lesotho">Lesotho</option>
    <option value="Liberia">Liberia</option>
    <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
    <option value="Liechtenstein">Liechtenstein</option>
    <option value="Lithuania">Lithuania</option>
    <option value="Luxembourg">Luxembourg</option>
    <option value="Macao">Macao</option>
    <option value="Macedonia, the Former Yugoslav Republic of">Macedonia, the Former Yugoslav Republic of</option>
    <option value="Madagascar">Madagascar</option>
    <option value="Malawi">Malawi</option>
    <option value="Malaysia">Malaysia</option>
    <option value="Maldives">Maldives</option>
    <option value="Mali">Mali</option>
    <option value="Malta">Malta</option>
    <option value="Marshall Islands">Marshall Islands</option>
    <option value="Martinique">Martinique</option>
    <option value="Mauritania">Mauritania</option>
    <option value="Mauritius">Mauritius</option>
    <option value="Mayotte">Mayotte</option>
    <option value="Mexico">Mexico</option>
    <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
    <option value="Moldova, Republic of">Moldova, Republic of</option>
    <option value="Monaco">Monaco</option>
    <option value="Mongolia">Mongolia</option>
    <option value="Montenegro">Montenegro</option>
    <option value="Montserrat">Montserrat</option>
    <option value="Morocco">Morocco</option>
    <option value="Mozambique">Mozambique</option>
    <option value="Myanmar">Myanmar</option>
    <option value="Namibia">Namibia</option>
    <option value="Nauru">Nauru</option>
    <option value="Nepal">Nepal</option>
    <option value="Netherlands">Netherlands</option>
    <option value="Netherlands Antilles">Netherlands Antilles</option>
    <option value="New Caledonia">New Caledonia</option>
    <option value="New Zealand">New Zealand</option>
    <option value="Nicaragua">Nicaragua</option>
    <option value="Niger">Niger</option>
    <option value="Nigeria">Nigeria</option>
    <option value="Niue">Niue</option>
    <option value="Norfolk Island">Norfolk Island</option>
    <option value="Northern Mariana Islands">Northern Mariana Islands</option>
    <option value="Norway">Norway</option>
    <option value="Oman">Oman</option>
    <option value="Pakistan">Pakistan</option>
    <option value="Palau">Palau</option>
    <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
    <option value="Panama">Panama</option>
    <option value="Papua New Guinea">Papua New Guinea</option>
    <option value="Paraguay">Paraguay</option>
    <option value="Peru">Peru</option>
    <option value="Philippines">Philippines</option>
    <option value="Pitcairn">Pitcairn</option>
    <option value="Poland">Poland</option>
    <option value="Portugal">Portugal</option>
    <option value="Puerto Rico">Puerto Rico</option>
    <option value="Qatar">Qatar</option>
    <option value="Reunion">Reunion</option>
    <option value="Romania">Romania</option>
    <option value="Russian Federation">Russian Federation</option>
    <option value="Rwanda">Rwanda</option>
    <option value="Saint Barthelemy">Saint Barthelemy</option>
    <option value="Saint Helena">Saint Helena</option>
    <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
    <option value="Saint Lucia">Saint Lucia</option>
    <option value="Saint Martin">Saint Martin</option>
    <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
    <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
    <option value="Samoa">Samoa</option>
    <option value="San Marino">San Marino</option>
    <option value="Sao Tome and Principe">Sao Tome and Principe</option>
    <option value="Saudi Arabia">Saudi Arabia</option>
    <option value="Senegal">Senegal</option>
    <option value="Serbia">Serbia</option>
    <option value="Serbia and Montenegro">Serbia and Montenegro</option>
    <option value="Seychelles">Seychelles</option>
    <option value="Sierra Leone">Sierra Leone</option>
    <option value="Singapore">Singapore</option>
    <option value="Sint Maarten">Sint Maarten</option>
    <option value="Slovakia">Slovakia</option>
    <option value="Slovenia">Slovenia</option>
    <option value="Solomon Islands">Solomon Islands</option>
    <option value="Somalia">Somalia</option>
    <option value="South Africa">South Africa</option>
    <option value="South Georgia and the South Sandwich Islands">South Georgia and the South Sandwich Islands</option>
    <option value="South Sudan">South Sudan</option>
    <option value="Spain">Spain</option>
    <option value="Sri Lanka">Sri Lanka</option>
    <option value="Sudan">Sudan</option>
    <option value="Suriname">Suriname</option>
    <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
    <option value="Swaziland">Swaziland</option>
    <option value="Sweden">Sweden</option>
    <option value="Switzerland">Switzerland</option>
    <option value="Syrian Arab Republic">Syrian Arab Republic</option>
    <option value="Taiwan, Province of China">Taiwan, Province of China</option>
    <option value="Tajikistan">Tajikistan</option>
    <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
    <option value="Thailand">Thailand</option>
    <option value="Timor-Leste">Timor-Leste</option>
    <option value="Togo">Togo</option>
    <option value="Tokelau">Tokelau</option>
    <option value="Tonga">Tonga</option>
    <option value="Trinidad and Tobago">Trinidad and Tobago</option>
    <option value="Tunisia">Tunisia</option>
    <option value="Turkey">Turkey</option>
    <option value="Turkmenistan">Turkmenistan</option>
    <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
    <option value="Tuvalu">Tuvalu</option>
    <option value="Uganda">Uganda</option>
    <option value="Ukraine">Ukraine</option>
    <option value="United Arab Emirates">United Arab Emirates</option>
    <option value="United Kingdom">United Kingdom</option>
    <option value="United States">United States</option>
    <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
    <option value="Uruguay">Uruguay</option>
    <option value="Uzbekistan">Uzbekistan</option>
    <option value="Vanuatu">Vanuatu</option>
    <option value="Venezuela">Venezuela</option>
    <option value="Viet Nam">Viet Nam</option>
    <option value="Virgin Islands, British">Virgin Islands, British</option>
    <option value="Virgin Islands, U.s.">Virgin Islands, U.s.</option>
    <option value="Wallis and Futuna">Wallis and Futuna</option>
    <option value="Western Sahara">Western Sahara</option>
    <option value="Yemen">Yemen</option>
    <option value="Zambia">Zambia</option>
    <option value="Zimbabwe">Zimbabwe</option>';
    return $data;
}?>
</body>
</html>