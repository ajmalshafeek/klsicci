<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
  session_name($config['sessionName']);
  session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientCompany.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/vendor.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/product.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/organization/client/moreForm/form.php");
  $clientId = $_SESSION['idEdit'];
  $name = $_SESSION['clientNameEdit'];
  $cpa="";
  $lat="";
  $long="";
 if(isset($_SESSION['orgType']) && $_SESSION['orgType']==6) {

       $cpa = $_SESSION['cpa'];
       $lat = $_SESSION['latitude'];
       $long =$_SESSION['longitude'];
  }


  $address1 = $_SESSION['clientAddress1Edit'];
  $address2 = $_SESSION['clientAddress2Edit'];
  $city = $_SESSION['clientCityEdit'];
  $postalCode = $_SESSION['clientPostalCodeEdit'];
  $state = $_SESSION['clientStateEdit'];
  $contactNo = $_SESSION['clientPhoneNumberEdit'];
  $faxNo = $_SESSION['clientFaxNoEdit'];
  $emailAddress = $_SESSION['clientEmailAddressEdit'];
  $instalAddress1 = $_SESSION['instalAddress1Edit'];
  $instalAddress2 = $_SESSION['instalAddress2Edit'];
  $instalCity = $_SESSION['instalCityEdit'];
  $instalPCode = $_SESSION['instalPCodeEdit'];
  $instalState = $_SESSION['instalStateEdit'];
$customer=$_SESSION['nameEdit'];
$business=$_SESSION['businessEdit'];
$incorp=$_SESSION['incorpEdit'];
$finYear=$_SESSION['finYearEdit'];
$regno=$_SESSION['registerEdit'];
$app=$_SESSION['app'];

	$country="";
	$instalCountry="";
	if(isset($_SESSION['country'])) {
		$country = $_SESSION['country'];
}
	if(isset($_SESSION['instalCountry'])) {
		$instalCountry = $_SESSION['instalCountry'];
	}



  if ($instalState==NULL) {
    $instalState=NULL;
  }
  $cStatusInt = $_SESSION['cStatusEdit'];
  switch ($cStatusInt) {
    case '0':
      $cStatus = "TG";
      break;
    case '1':
      $cStatus = "WTY";
      break;
    case '2':
      $cStatus = "PERCALL";
      break;
    case '3':
      $cStatus = "RENTAL";
      break;
    case '4':
      $cStatus = "AD HOC";
      break;
  }

?>
<!DOCTYPE html >

<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />

    <link rel='stylesheet' type='text/css' href='css/myQuotationStyle.css' />
    <?php
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>
    <script>
    function productDelete(str){
      document.getElementById("productToDelete").value = str;
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
        <li class="breadcrumb-item active">Edit Client</li>
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
                    <input type="text" value="<?php echo $name ?>" class="form-control" placeholder="Enter company name" id="clientName" name="clientName" required />
                    <div class="invalid-feedback">
                    Please enter client name
                  </div>
                  </div>
                </div>

                  <?php if($_SESSION['orgType']==8||$_SESSION['orgType']==3){ ?>
                  <div class="form-group row">
                      <label for="clientName" class="col-sm-2 col-form-label col-form-label-lg">Client Name</label>
                      <div class="col-sm-10"   >
                          <input type="text" class="form-control" placeholder="Enter client name" id="name" name="name" value="<?php echo $customer; ?>" />
                          <div class="invalid-feedback">
                              Please enter customer name
                          </div>
                      </div>
                  </div>
                  <?php } ?>
                  <?php if(isset($_SESSION['orgType'])&&$_SESSION['orgType']==3) { ?>
                      <div class="form-group row">
                          <label for="business" class="col-sm-2 col-form-label col-form-label-lg">Business Type</label>
                          <div class="col-sm-10"   >
                              <input type="text" class="form-control" placeholder="Enter business type" id="business" name="business" value="<?php echo $business; ?>"  <?php if($_SESSION['orgType']==3){echo 'required'; } ?> />
                              <div class="invalid-feedback">
                                  Please enter business type
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label for="incorp" class="col-sm-2 col-form-label col-form-label-lg">Date of Incorporation</label>
                          <div class="col-sm-4"   >
                              <input type="date" class="form-control" placeholder="Enter Date of Incorporation" id="incorp" name="incorp" value="<?php echo $incorp; ?>"  <?php if($_SESSION['orgType']==3){echo 'required'; } ?> />
                              <div class="invalid-feedback">
                                  Please enter date of incorporation
                              </div>
                          </div>
                          <label for="financialYear" class="col-sm-2 col-form-label col-form-label-lg">Financial Year</label>
                          <div class="col-sm-4"   >
                              <input type="month" class="form-control" placeholder="Enter Financial Year" id="financialYear" name="financialYear" value="<?php echo $finYear; ?>"  <?php if($_SESSION['orgType']==3){echo 'required'; } ?> />
                              <div class="invalid-feedback">
                                  Please enter financial year
                              </div>
                          </div>
                      </div>
                  <?php } ?>
                  <div class="form-group row">
                      <label for="clientName" class="col-sm-2 col-form-label col-form-label-lg">Registration Number</label>
                      <div class="col-sm-10"   >
                          <input type="text" class="form-control" placeholder="Enter Registration No" id="register" name="register" value="<?php echo $regno ?>" required />
                          <div class="invalid-feedback">
                              Please enter Registration No
                          </div>
                      </div>
                  </div>
                  <div class="form-group row">
                      <label for="clientName" class="col-sm-2 col-form-label col-form-label-lg">Application Role</label>
                      <div class="col-sm-10" >
                          <select name="status" class="form-control">
                              <option value="2" <?php if($app==2){echo 'selected';}?> >Frontend Client</option>
                              <option value="1" <?php if($app==1){echo 'selected';}?> >Client User</option>
                          </select>
                          <div class="invalid-feedback">
                              Please enter client user
                          </div>
                      </div>
                  </div>

                      <?php if(isset($_SESSION['orgType'])&&$_SESSION['orgType']==6) { ?>
                 <div class="form-group row">
                  <label for="clientCpa" class="col-sm-2 col-form-label col-form-label-lg">CPA</label>
                  <div class="col-sm-10" >
                    <input type="text" value="<?php echo $cpa; ?>" class="form-control" placeholder="Enter cpa" id="clientCpa" name="clientCpa" required />
                    <div class="invalid-feedback">
                    Please enter client CPA
                  </div>
                  </div>
                </div>
               <div class="form-group row">
                  <label for="clientlat" class="col-sm-2 col-form-label col-form-label-lg">LATITUDE</label>
                  <div class="col-sm-10" >
                    <input type="text" value="<?php echo $lat; ?>" class="form-control" placeholder="Enter latitude" id="clientlat" name="clientlat" required />
                    <div class="invalid-feedback">
                    Please enter client latitude
                  </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="clientlong" class="col-sm-2 col-form-label col-form-label-lg">LONGITUDE</label>
                  <div class="col-sm-10" >
                    <input type="text" value="<?php echo $long; ?>" class="form-control" placeholder="Enter longitude" id="clientlong" name="clientlong" required />
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
                      <input type="text" class="form-control" value="<?php echo $address1 ?>" placeholder="Street address, P.O box, C/O"  id="address1" name="address1" required />
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
                      <input type="text" class="form-control" value="<?php echo $address2 ?>" placeholder="Building, Suite, Unit, Floor"  id="address2" name="address2" required />
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
                      <textarea name="city" class="form-control" id="city" name="city" required><?php echo $city ?></textarea>
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
                      <input type="text" class="form-control" value="<?php echo $postalCode ?>" placeholder="Zip/Postal Code"  id="postalCode" name="postalCode" required />
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
                        <option  value="<?php echo $state ?>" selected><?php echo $state ?></option>
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
			                <input type="text" name="state"  id="state" class="form-control" value="<?php echo $state ?>" />
			                <div class="invalid-feedback">
				                Please select state
			                </div>
		                </div>
	                </div>
	                <div class="form-group row">
		                <label for="country" class="col-sm-2 col-form-label col-form-label-lg">&bull;Country</label>
		                <div class="col-sm-10">
			                <input type="text" name="country"  id="country" class="form-control" value="<?php echo $country ?>" />
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
                <label for="address1" class="col-sm-2 col-form-label col-form-label-lg">Installation Address:</label>
                <div class="col-sm-10">
                  <!--(START)INSTALLATION ADDRESS1-->
                  <div class="form-group row">
                    <label for="instalAddress1" class="col-sm-2 col-form-label col-form-label-lg">&bull;Address 1</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" value="<?php echo $instalAddress1 ?>" placeholder="Street address, P.O box, C/O"  id="instalAddress1" name="instalAddress1" />
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
                      <input type="text" class="form-control" value="<?php echo $instalAddress2 ?>" placeholder="Building, Suite, Unit, Floor"  id="instalAddress2" name="instalAddress2" />
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
                      <textarea name="instalCity" class="form-control" id="instalCity" name="instalCity"><?php echo $instalCity ?></textarea>
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
                      <input type="text" class="form-control" value="<?php echo $instalPCode ?>" placeholder="Zip/Postal Code"  id="instalPCode" name="instalPCode" />
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
                      <select name="instalState"  id="instalState" class="form-control">
                        <option  value="<?php echo $instalState ?>" selected><?php echo $instalState ?></option>
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
			                <input type="text" name="instalState"  id="instalState" class="form-control" value="<?php echo $instalState ?>" />
			                <div class="invalid-feedback">
				                Please select state
			                </div>
		                </div>
	                </div>
	                <div class="form-group row">
		                <label for="instalCountry" class="col-sm-2 col-form-label col-form-label-lg">&bull;Country</label>
		                <div class="col-sm-10">
			                <input type="text" name="instalCountry"  id="instalCountry" class="form-control" value="<?php echo $instalCountry ?>" />
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

                    <input type="text" class="form-control" value="<?php echo $contactNo ?>" placeholder="xx-xxx xxxx"  id="clientContactNo" name="clientContactNo" />
                    <div class="invalid-feedback">
                      Please enter client phone no.
                    </div>
                  </div>
              </div>



              <div class="form-group row">
                <label for="clientFaxNo" class="col-sm-2  col-form-label col-form-label-lg">Fax No.</label>
                  <div class="col-sm-10"   >

                    <input type="text" class="form-control" value="<?php echo $faxNo ?>" placeholder="xx-xxx xxx"  id="clientFaxNo" name="clientFaxNo" />
                    <div class="invalid-feedback">
                      Please enter client fax no.
                    </div>
                  </div>
              </div>



                <div class="form-group row">
                  <label for="clientEmail" class="col-sm-2 col-form-label col-form-label-lg">Client Email</label>
                  <div class="col-sm-10"   >
                    <input type="email"  placeholder="Email Address" class="form-control" value="<?php echo $emailAddress ?>" id="clientEmail" name="clientEmail" />
                    <div class="invalid-feedback">
                      Please enter client email address
                    </div>
                  </div>
                </div>

                <!--(START)CONTRACT STATUS
                <div class="form-group row">
                  <label for="instalStatus" class="col-sm-2 col-form-label col-form-label-lg">CONTRACT STATUS</label>
                  <div class="col-sm-10">
                    <select name="cStatus"  id="instalStatus" class="form-control" required >
                      <option  value="<?php echo $cStatusInt ?>" selected ><?php echo $cStatus ?></option>
                      <option value="0">TG</option>
                      <option value="1">WTY</option>
                      <option value="2">PERCALL</option>
                    </select>
                    <div class="invalid-feedback">
                      Please select status
                    </div>
                  </div>
                </div>
                  (END)CONTRACT STATUS-->
                <?php if ($_SESSION['orgType'] == 1) { ?>
                <!--(START)PRODUCT/CONTRACT STATUS-->
                          <div class="form-group row">
                            <label for="sparePart" class="col-sm-2 col-form-label col-form-label-lg">Product</label>
                            <div class="col-sm-10"   >
                              <table class="table order-list table-responsive  table-hover table-bordered" id="spTable">
                              <tr>
                                <th style="width:75%;background: gray;">Product</th>
                                <th style="width:25%;background: gray;">Contract Status</th>
                              </tr>
                              <!-- (START)INITIALIZE COUNT -->
                                <script> var i = 0;var j = 0; </script>
                                <?php
                                //$dataList = NULL;
                                $dataList = clientproductListById($clientId);
                                if ($dataList == NULL) {

                                }
                                else {
                                  foreach($dataList as $data) {
                                  $productId = $data['productId'];
                                  $dataListProduct = productListById($productId);
                                  switch ($data['cStatus']) {
                                    case '0':
                                      $cStatusTable = "TG";
                                      break;
                                    case '1':
                                      $cStatusTable = "WTY";
                                      break;
                                    case '2':
                                      $cStatusTable = "PERCALL";
                                      break;
                                    case '3':
                                      $cStatusTable = "RENTAL";
                                      break;
                                    case '4':
                                      $cStatusTable = "AD HOC";
                                      break;
                                  }
                                  echo "<tr><td>".$dataListProduct['model']."[".strtoupper($dataListProduct['brand'])."][S/N: ".$dataListProduct['serialNum']."]</td><td>".$cStatusTable."</td><td><i data-toggle='modal' data-target='#productDeleteModal' onclick='productDelete(".$data['id'].")' class='fa fa-trash' style='color:red; font-size: 1.5em;cursor: pointer;'></i></td></tr>";
                                  //echo "<tr><td>".$data['description']."</td><td>".$data['qty']."</td><td><i data-toggle='modal' data-target='#sparepartEditModal' onclick='sparepartEdit(this)' class='fa fa-pencil' style='color:green; font-size: 1.5em; cursor: pointer;'></i></td><td><i data-toggle='modal' data-target='#sparepartDeleteModal' onclick='sparepartDelete(this)' class='fa fa-trash' style='color:red; font-size: 1.5em;cursor: pointer;'></i></td></tr>";
                                  ?><script> i++; j++;</script><?php
                                  }
                                }
                                ?>
                              <!-- (END)INITIALIZE COUNT -->
                              <tr>
                                <!-- <td><input type="number" name='spQty0' class="form-control"/></td> -->
                                <!-- <td><input type="text" name='product0' class="form-control"/></td> -->
                                <td>
                                  <select name="product0"  id="instalStatus" class="form-control">
                                    <?php
                                    $dataList = productList();
                                    echo "<option value='' selected disabled >--Select Product--</option>";
                                    foreach($dataList as $data){
                                      echo "<option value='".$data['id']."'>".$data['model']."[".strtoupper($data['brand'])."][S/N: ".$data['serialNum']."]</option>";
                                    }
                                    ?>
                                  </select>
                                </td>
                                <td>
                                  <select name="cStatus0"  id="instalStatus" class="form-control">
                                    <option  value="" selected disabled >--Select Status--</option>
                                    <option value="0">TG</option>
                                    <option value="1">WTY</option>
                                    <option value="2">PERCALL</option>
                                    <option value="3">RENTAL</option>
                                    <option value="4">AD HOC</option>
                                  </select>
                                </td>
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

                            function decrement(){
                              n--;
                              return n;
                            }

                            function addTableRow() {
                              var i=numRow();
                              var n=increment();
                              var table = document.getElementById("spTable");
                              var row = table.insertRow(i+1);
                              var cell1 = row.insertCell(0);
                              var cell2 = row.insertCell(1);
                              //cell1.innerHTML = "<input type='text' name='product"+i+"' class='form-control'/>";
                              //cell2.innerHTML = "<input type='number' name='spQty"+i+"' class='form-control'/>";
                              cell1.innerHTML = "<select name='product"+n+"' id='instalStatus' class='form-control' ><?php
                                                  $dataList = productList();
                                                  echo "<option value='' selected disabled >--Select Product--</option>";
                                                  foreach($dataList as $data){
                                                    echo "<option value='".$data['id']."'>".$data['model']."[".strtoupper($data['brand'])."][S/N: ".$data['serialNum']."]</option>";
                                                    }
                                                  echo "</select>";
                                                ?>";
                              cell2.innerHTML = "<select name='cStatus"+n+"' id='instalStatus' class='form-control' ><option  value='' selected disabled >--Select Status--</option><option value='0'>TG</option><option value='1'>WTY</option><option value='2'>PERCALL</option><option value='3'>RENTAL</option><option value='4'>AD HOC</option></select>";
                              cell3.innerHTML = n;
                            }

                            function removeTableRow() {
                              if (i!=0 && i>j) {
                                document.getElementById("spTable").deleteRow(i+1);
                                decrement();
                                i--;
                              }
                            }


                            </script>
                            <br>
                            </div>
                          </div>
                <!--(END)PRODUCT/CONTRACT STATUS -->
                <?php } ?>
                <!--
                <div class="form-group row">
                  <label for="clientName" class="col-sm-2 col-form-label col-form-label-lg">COMPANY NAME</label>
                  <div class="col-sm-10"   >
                    <input type="text" value="<?php echo $name ?>" class="form-control" placeholder="Enter company name" id="clientName" name="clientName" required></input>
                    <div class="invalid-feedback">
                    Please enter client name
                  </div>
                  </div>
                </div>


              <div class="form-group row">
                <label for="address1" class="col-sm-2 col-form-label col-form-label-lg">ADDRESS 1</label>
                <div class="col-sm-10"   >
                  <input type="text" value="<?php echo $address1 ?>" class="form-control" placeholder="Street address, P.O box, C/O"  id="address1" name="address1" required ></input>
                  <div class="invalid-feedback">
                    Please enter address 1.
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label for="address2" class="col-sm-2 col-form-label col-form-label-lg">ADDRESS 2</label>
                  <div class="col-sm-10"   >

                    <input type="text" value="<?php echo $address2 ?>" class="form-control" placeholder="Building, Suite, Unit, Floor"  id="address2" name="address2" ></input>
                    <div class="invalid-feedback">
                      Please enter address 2.
                    </div>
                  </div>

              </div>

              <div class="form-group row">
                <label for="city" class="col-sm-2  col-form-label col-form-label-lg">CITY / TOWN</label>
                  <div class="col-sm-10"   >

                    <input type="text" value="<?php echo $city ?>" class="form-control" placeholder="City / Town"  id="city" name="city" required></input>
                    <div class="invalid-feedback">
                      Please enter city / town name
                    </div>
                  </div>
              </div>

            <div class="form-group row">
              <label for="postalCode" class="col-sm-2  col-form-label col-form-label-lg">ZIP  / POSTAL CODE</label>
                <div class="col-sm-10"   >

                  <input type="text" value="<?php echo $postalCode ?>"  class="form-control" placeholder="Zip / Postal Code"  id="postalCode" name="postalCode" required></input>
                  <div class="invalid-feedback">
                    Please enter zip /postal code
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label for="state" class="col-sm-2  col-form-label col-form-label-lg">STATE</label>
                  <div class="col-sm-10"   >

                    <select name="state"  id="state" class="form-control" required >
                      <option  value="<?php echo $state; ?>" selected><?php echo $state; ?></option>
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
                  </div>
              </div>

              <div class="form-group row">
              <label for="clientContactNo" class="col-sm-2  col-form-label col-form-label-lg">PHONE NO.</label>
                <div class="col-sm-10"   >

                    <input type="text" value="<?php echo $contactNo ?>" class="form-control" placeholder="xx-xxx xxxx"  id="clientContactNo" name="orgContactNo" ></input>
                    <div class="invalid-feedback">
                      Please enter client phone no.
                    </div>
                  </div>
              </div>



              <div class="form-group row">
                <label for="clientFaxNo" class="col-sm-2  col-form-label col-form-label-lg">FAX NO.</label>
                  <div class="col-sm-10"   >

                    <input type="text" value="<?php echo $faxNo ?>" class="form-control" placeholder="xx-xxx xxx"  id="clientFaxNo" name="orgFaxNo" ></input>
                    <div class="invalid-feedback">
                      Please enter client fax no.
                    </div>
                  </div>
              </div>



                <div class="form-group row">
                  <label for="clientEmail" class="col-sm-2 col-form-label col-form-label-lg">CLIENT EMAIL</label>
                  <div class="col-sm-10"   >
                    <input type="email" value="<?php echo $emailAddress ?>"  placeholder="Email Address" required class="form-control" id="clientEmail" name="clientEmail"  ></input>
                    <div class="invalid-feedback">
                      Please enter client email address
                    </div>
                  </div>
                </div>


<!--
                <div class="form-group row">
                    <label for="userName" class="col-sm-2 col-sm-2 col-form-label col-form-label-lg">USERNAME</label>
                      <div class="col-sm-10"   >
                        <input type="text" class="form-control" placeholder="Username" id="userName" name="userName" required></input>
                        <div class="invalid-feedback">
                        Please Enter Username
                      </div>
                    </div>
                  </div>
                  -->
                  <div class="form-group row">
                      <label class="col-sm-2 col-form-label col-form-label-lg"></label>
                      <div class="col-sm-10">
                          <button name='editClientCompanyProcess'
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
                          type='submit' >Submit</button>
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


  <!-- (START)DELETE FORM -->
  <form method="POST" action="<?php echo "https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/phpfunctions/product.php" ?>" >

  <div class="modal fade" id="productDeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="productDeleteModalTitle">Remove</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <div id='productDeleteContent' >
            Remove the selected row?
          </div>

        </div>
        <div class="modal-footer">
          <input type="text" hidden name="productToDelete" id="productToDelete" value=""  />
          <input type="text" hidden name="jobId" value="<?php //echo $jobId ?>"  />
          <button type="submit" name='deleteProduct' class="btn btn-primary" >Remove</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>

    </div>
  </div>
  </form>
  <!-- (END)DELETE FORM -->

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
	});

</script>
</body>
</html>