<?php

$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");

if(!isset($_SESSION))

{

  session_name($config['sessionName']);

  session_start();

}

require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientComplaint.php");

require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");

require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/configuration.php");

require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/organization/complaint/moreForm/form.php");

require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientCompany.php");

?>

<!DOCTYPE>



<html>



<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">



    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" type="image/x-icon"

        href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />

        

    <link rel="stylesheet" href="../../css/bootstrap.min.css">

    <!--

    <script src="../js/jquery-3.3.1.slim.min.js" ></script>



    <script src="../../js/bootstrap.min.js" ></script>



    <script type="text/javascript" src="../../js/jquery-3.3.1.min.js"></script>

-->

    <?php

      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");

    ?>

  

    <style>

    .buttonAsLink {

        background: none !important;

        color: inherit;

        border: none;

        font: inherit;

        cursor: pointer;



    }



    table {

        border-collapse: collapse;

        width: 100%;

    }



    th {

        background: grey;

        color: black;

    }



    table,

    td,

    th {

        border: 1px solid black;

        text-align: center;

    }



    .sla-option {

        list-style-type: none;

        padding: 0;

        display: flex;

        flex-wrap: wrap;

    }



    .sla-option li {

        flex: inline-block;

        margin: 0 5px 10px 0px;

        width: 80px;

        height: 40px;

        position: relative;

    }



    .sla-option label,

    .sla-option input {

        display: block;

        position: absolute;

        top: 0;

        left: 0;

        right: 0;

        bottom: 0;

    }



    .sla-option input[type="radio"] {

        opacity: 0.0;

        z-index: 100;

    }



    .sla-option input[type="radio"]:checked+label {

        background: #ccc;

    }



    .sla-option label {

        padding: 10px;

        border: 1px solid #7b7b7b;

        border-radius: 5px;

        cursor: pointer;

        z-index: 90;

        margin-bottom: 0px;

        text-align: center;

        font-weight: bold;

    }



    .sla-option label:hover {

        background: #DDD;

    }


   /* .container form input, .container form textarea, .container form select {
        background-color: #f2f2f2 !important;
        border-radius:10px !important;
        border: none;
        border: 1px solid green !important;

!*        border-top: 1px solid green !important;
        border-left: 1px solid green !important;
        border-right: 1px solid green !important;
 *!
    }
    .container form input:placeholder-shown, .container form textarea:placeholder-shown {
        background-color: #f2f2f2;
        border: 1px solid gray !important;
    }
    .container form input:invalid, .container form textarea:invalid {
        border: 1px solid red;}*/
    </style>

    <link rel="stylesheet" href="./../../css/jquery-ui.min.css">

    <!--<script src="./../../js/jquery-ui.js"></script> -->

  <script src="./../../js/jquery-ui.min.js"></script>

  <script>

    function clientId() {

    <?php    /*

          var id = document.getElementById("clientCompanyId").value;

          console.log("companyId: " + id);

          $.ajax({



              type  : 'GET',

              url  : '../../phpfunctions/clientCompany.php?',

              data : {clientCheckboxTable:id},

              success: function (data) {

                document.getElementById("tableProduct").innerHTML = data;

              }

          });

          */

    ?>}



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





    function toggleJobField(str) {



        var id = str;

        if (id == 0) {

            document.getElementById("jobName").style.display = "block";

        } else {

            document.getElementById("jobName").style.display = "none";

        }



    }

<?php if(isset($_SESSION['orgType'])&& $_SESSION['orgType']==6){ /* ?>



        //development

        $(".cpa").focusout(function(){});

        $(".cpa").focusout(function(){

            var count=0;

            var i;

            var textvalue=$("#cpa").val();

            for(i = 0; i < cpalistid.length ; i++){

                // foreach ($cpaList as $value) {

                if(cpalistid[count][1]==){ echo ","; }

                echo "'".cpalistid[count][2]."'";

                count++;

            }

            $(this).css("background-color", "white");

        });

        //development end;

    <?php */

        $count=0;

        $cpaList=0;

         $cpaList = getCpaList();

         $_SESSION['comid']=$cpaList;

         ?>

    $(document).ready(function() {

        var cpalistid=[<?php 

            for($i = 0; $i < count($cpaList); $i++){

                // foreach ($cpaList as $value) {

                     if($count>0){ echo ","; }

                  echo "'".$cpaList[$count][2]."'";

                  $count++;

                 }

                 $count=0;

            ?>];

            console.log(cpalistid);

    var cpalist=[<?php

    for($i = 0; $i < count($cpaList); $i++){

   // foreach ($cpaList as $value) {

        if($count>0){ echo ","; }

     echo "'".$cpaList[$count][1]."'";

     $count++;

    }

     ?>];

console.log(cpalist);

    

        $( "#cpa" ).autocomplete({

        source: function( request, response ) {

                var matcher = new RegExp( "" + $.ui.autocomplete.escapeRegex( request.term ), "i" );

                    response( $.grep( cpalist, function( item ){

                    return matcher.test( item );

                }) );

            }

        });

        $( ".cpa" ).change(function(){

            checkCompany($( ".cpa" ).val());

            sitename();

            setTimeout(sitename, 2000);

        });

        function checkCompany(textcpa){

            var temp=0;

            var i = cpalist.indexOf(textcpa);

            temp=cpalistid[i];

            $( '#clientCompanyId' ).val(""+temp+"");

        }

        $('#clientCompanyId').change(function() {

            checkCpa($(this).val());

            sitename();});



        function checkCpa(textcpa){

            var temp=0;

            var i = cpalistid.indexOf(textcpa);

            temp=cpalist[i];

            $( '#cpa' ).val(""+temp+"").change();

        }

        function sitename(){

            $('.sitename').val($( '#clientCompanyId option:selected' ).text());

        }

    });



<?php } ?>

    </script>

</head>



<body class="fixed-nav">



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

                <li class="breadcrumb-item">Incident</li>

                <li class="breadcrumb-item active">Create Incident</li>

            </ol>

        </div>



        <div class="container">

            <form method="POST" action="../../phpfunctions/clientComplaint.php" class="needs-validation" novalidate enctype="multipart/form-data" >

                <div id="jobListForm">

                    <?php

                  if (isset($_SESSION['feedback'])) {

                      echo $_SESSION['feedback'];

                      unset($_SESSION['feedback']);

                  }

              ?>





                    <script type="text/javascript">

                    function postCheckTrue() {

                        form = document.createElement('form');

                        form.setAttribute('method', 'POST');

                        form.setAttribute('action', 'makeComplaint.php');

                        myvar = document.createElement('input');

                        myvar.setAttribute('name', 'clientForm');

                        myvar.setAttribute('type', 'hidden');

                        myvar.setAttribute('value', '1');

                        form.appendChild(myvar);

                        document.body.appendChild(form);

                        form.submit();

                    }



                    function checkClient() {

                        var check = document.getElementById("checkClientForm").checked;

                        if (check) {

                            postCheckTrue();

                        } else {

                            window.location = 'makeComplaint.php';

                        }

                    }



                    function generateInvoiceForm() {

                        var check = document.getElementById("checkServiceCharge").checked;

                        if (check) {

                            document.getElementById("serviceCharge").style.display = "block";

                            document.getElementById("invoiceDueDate").style.display = "block";

                            document.getElementById("attention").style.display = "block";

                            document.getElementById("footer").style.display = "block";



                            document.getElementById("serviceChargeForm").required = true;

                            document.getElementById("invoiceDueDateForm").required = true;

                            document.getElementById("attentionForm").required = true;

                            document.getElementById("footerForm").required = true;

                        } else {

                            document.getElementById("serviceCharge").style.display = "none";

                            document.getElementById("invoiceDueDate").style.display = "none";

                            document.getElementById("attention").style.display = "none";

                            document.getElementById("footer").style.display = "none";



                            document.getElementById("serviceChargeForm").required = false;

                            document.getElementById("invoiceDueDateForm").required = false;

                            document.getElementById("attentionForm").required = false;

                            document.getElementById("footerForm").required = false;

                        }

                    }

                    </script>
<?php $dataListUse=fetchOrgUseApplication();
	$internal=0;
	$external=0;
	foreach ($dataListUse as $data) {
	if (1 == $data['internal']) {
		$internal = true;
	}else{
		$internal = false;
	}

	if (1 == $data['external']) {
		$external = true;
		}else{
		$external = false;
	}
	}
?>
                    <?php if($_SESSION['orgType']!=6&&!isset($_SESSION['orgType'])){?>

                    <div class="form-check">

                        <input type="checkbox" onclick="generateInvoiceForm()" class="form-check-input"

                            name="checkServiceCharge" id="checkServiceCharge">

                        <label class="form-check-label">Generate Invoice</label>

                    </div>
	                <?php }

	                    if($external & $internal) {
						?>

						<div class="form-check">

						<input type="checkbox" onclick="checkClient()" class="form-check-input" id="checkClientForm"
							<?php if (isset($_POST['clientForm'])) {

				echo "checked";

			} ?>>

		<label class="form-check-label">Required <?php echo $_SESSION['clientas']; ?></label>

	</div>

<?php
}
                    

            $data = fetchOrganizationDetails($_SESSION['orgId']);

            if (isset($_POST['clientForm'])){

            if(isset($_SESSION['orgType']) && $external){ ?>

                    <?php }

                    /*?>  <input type="text" name="clientCompanyId" id="clientCompanyId" class="clientCompanyId" value="0" hidden>

                    <?php */

	                    }

	                   elseif($internal){ ?>
	            <div class="form-group row">

		            <label for="problem" class="col-sm-2 col-form-label col-form-label-lg">
			            Internal Org
		            </label>

		            <div class="col-sm-10">

			            <?php if($internal){ ?> <textarea class="form-control" rows="1" readonly><?php echo $data['name']  ?></textarea>
				            <input type="text" name="clientCompanyId" value="0" hidden> <?php }
				            /*
				   else { ?>
					<?php   require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientCompany.php");
		dropDownListOrganizationClientCompanyActive3();  } */ ?>

			            <div class="invalid-feedback">

				            Please enter a problem

			            </div>

		            </div>

	            </div>

           <?php } elseif($external){ ?>
	            <div class="form-group row">

		            <label for="problem" class="col-sm-2 col-form-label col-form-label-lg">
			            <?php echo $_SESSION['clientas']; ?>
		            </label>

		            <div class="col-sm-10">

			            <?php require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientCompany.php");
		dropDownListOrganizationClientCompanyActive3();   ?>

			            <div class="invalid-feedback">

				            Please enter a problem

			            </div>

		            </div>

	            </div>

           <?php }


                    ?>

                    <?php if (isset($_POST['clientForm'])&&$_SESSION['orgType']!=6): ?>

                    <div class="form-group row">

                        <label for="problem" class="col-sm-2 col-form-label col-form-label-lg">Client</label>

                        <div class="col-sm-10">

                            <?php

                require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientCompany.php");

                dropDownListOrganizationClientCompanyActive3();

                ?>

                            <div class="invalid-feedback">

                                Please enter a problem

                            </div>

                        </div>

                    </div>

                    <?php endif; ?>

                    <?php

         if (isset($_SESSION['orgType'])&& $_SESSION['orgType']==6) {

             if ($config['customerComplaintFormBookingDate']==true) {

                 ?>

                    <div class="form-group row">

                        <label for="dcreatedate" class="col-sm-2 col-form-label col-form-label-lg">Docket Creation Date</label>

                        <div class="col-sm-10">

                            <input class="form-control" type="date" value="<?php echo date('Y-m-d'); ?>"

                                id="dcreatedate" name="dcreatedate">

                            <div class="invalid-feedback">



                            </div>

                        </div>

                    </div>

                    <?php

             }

         }?>

                    <div class="form-group row">

                        <label for="problem" class="col-sm-2 col-form-label col-form-label-lg"><?php if($_SESSION['orgType']==7){?>Task <?php }else { ?>Task<?php } ?> </label>

                        <div class="col-sm-10">

                            <input type="text" class="form-control" placeholder="Enter <?php if($_SESSION['orgType']==7){?>Task <?php }else { ?>Task<?php } ?> name" id="problem"

                                name="problem" required maxlength="100" />

                            <div class="invalid-feedback">

                                Please enter a problem

                            </div>

                        </div>

                    </div>



                    <div class="form-group row">

                        <label for="problemDetails" class="col-sm-2 col-form-label col-form-label-lg">
                            Description</label>

                        <div class="col-sm-10">

                            <textarea class="form-control" placeholder="Enter description" id="problemDetails"

                                name="problemDetails"></textarea>

                            <div class="invalid-feedback">



                            </div>

                        </div>

                    </div>
	                <?php if($_SESSION['complaintExtra'] == true && isset($_SESSION['complaintExtra'])){ ?>

		                <!-- Nature of Complaint start-->
		                <div class='form-group row'>

			                <label for='requestType' class='col-sm-2 col-form-label col-form-label-lg' >Nature of Complaint/ Request</label>

			                <div class='col-sm-10' >
				                <input type="text" value="" name="requestType" id="requestType" class="form-control" placeholder="Enter Nature of Complaint or Request" >
			                </div>
		                </div>
		                <!-- Nature of Complaint end -->
		                <!-- Last nature of repair start-->
		                <div class='form-group row'>

			                <label for='lastRequest' class='col-sm-2 col-form-label col-form-label-lg' >Last Nature of Repair</label>

			                <div class='col-sm-10' >
				                <input type="text" value="" name="lastRequest" id="lastRequest" class="form-control" placeholder="Enter Last Nature of Repair" >
			                </div>
		                </div>
		                <!-- Last nature of repair end -->
		                <!-- Last repair date start-->
		                <div class='form-group row'>

			                <label for='lastRepair' class='col-sm-2 col-form-label col-form-label-lg' >Last Repair Date</label>

			                <div class='col-sm-10' >
				                <input type="text" value="" name="lastRepair" id="lastRepair" class="form-control" placeholder="Last Repair Date">
			                </div>
		                </div>
		                <!-- Last repair date end -->
	                <?php } ?>
                    <?php  if (isset($_SESSION['orgType'])&& $_SESSION['orgType']==6) { ?>

                    <div id="cpa-row">

                        <div class="form-group row">

                            <label for="cpa" class="col-sm-2 col-form-label col-form-label-lg">CPA</label>

                            <div class="col-sm-10">



                            <input type="text" class="form-control cpa" placeholder="CPA" id="cpa"

                                name="cpa" required />

                                <div class="invalid-feedback">

                                </div>

                            </div>

                        </div>

                    </div>



                    <div id="category">

                        <div class="form-group row">

                            <label for="category" class="col-sm-2 col-form-label col-form-label-lg">Category</label>

                            <div class="col-sm-10">

                                <select id="footerForm" class="form-control" name="category">

                                    <option value=0 selected>--Select Category--</option>

                                    <option value="CORP">CORP</option>

                                    <option value="ELITE PLUS">ELITE PLUS</option>

                                </select>

                                <div class="invalid-feedback">

                                </div>

                            </div>

                        </div>

                    </div>



                    <div id="state">

                        <div class="form-group row">

                            <label for="state" class="col-sm-2 col-form-label col-form-label-lg">State</label>

                            <div class="col-sm-10">

                                <select id="footerForm" class="form-control stateop" name="state">

                                    <option value=0 selected>--Select State--</option>



                                </select>

                                <div class="invalid-feedback">

                                </div>

                            </div>

                        </div>

                    </div>



                 <?php  /*  <div id="subregion">

                        <div class="form-group row">

                            <label for="subregion" class="col-sm-2 col-form-label col-form-label-lg">SUB REGION</label>

                            <div class="col-sm-10">

                                <select id="footerForm" class="form-control subregionop" name="subregion">

                                    <option value=0 selected>Select Sub Region</option>

                                </select>





                                <div class="invalid-feedback">

                                </div>

                            </div>

                        </div>

                    </div> */ ?>



                    <div id="vsattech">

                        <div class="form-group row">

                            <label for="vsattech" class="col-sm-2 col-form-label col-form-label-lg">Vsat

                                Technology</label>

                            <div class="col-sm-10">

                                <select id="footerForm" class="form-control" name="vsattech">

                                    <option value=0 selected>--Select Vsat Technology--</option>

                                    <option value="C-BAND">C-BAND</option>

                                    <option value="KU BAND">KU BAND</option>

                                </select>

                                <div class="invalid-feedback">

                                </div>

                            </div>

                        </div>

                    </div>



                    <div class="form-group row">

                        <label for="" class="col-sm-2 col-form-label col-form-label-lg">Site Name</label>

                        <div class="col-sm-10">

                            <input type="text" name="sitename" id="sitename" class="sitename" value="" hidden />

                           <?php /* <select id="footerForm" class="form-control" name="sitename">

                                <option value=0 selected>Select Site Name</option>

                                <option value="ANGLO EASTERN PLANTATION">ANGLO EASTERN PLANTATION</option>

                                <option value="BHP JLN SKUDAI">BHP JLN SKUDAI</option>

                                <option value="BHP KG RAJA">BHP KG RAJA</option>

                                <option value="BHP ULU TIRAM">BHP ULU TIRAM</option>

                                <option value="BOUSTEAD BEKOH ESTATE">BOUSTEAD BEKOH ESTATE</option>

                                <option value="BOUSTEAD SG. JERNIH">BOUSTEAD SG. JERNIH</option>

                                <option value="EXCEL TRAINING">EXCEL TRAINING</option>

                                <option value="SHALIMAR ESTATE">SHALIMAR ESTATE</option>

                                <option value="SK LENJANG">SK LENJANG</option>

                            </select>

                            */

                            require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientCompany.php");



                         dropDownListOrganizationClientCompanyActive3(); ?>

                            <div class="invalid-feedback">

                                Please enter a problem

                            </div>

                        </div>

                    </div>

                    <div class="form-group row">

                        <label for="note" class="col-sm-2 col-form-label col-form-label-lg">Note</label>

                        <div class="col-sm-10">

                            <textarea class="form-control" placeholder="Enter Note" id="note"

                                name="note" rows="4" ></textarea>

                            <div class="invalid-feedback">



                            </div>

                        </div>

                    </div>

                    <?php } ?>



                    <div id="attention" style="display:none">

                        <div class="form-group row">

                            <label for="attention" class="col-sm-2 col-form-label col-form-label-lg">Invoice

                                Attention</label>

                            <div class="col-sm-10">

                                <input id="attentionForm" class="form-control" type="text" name="attention">

                                <div class="invalid-feedback">



                                </div>

                            </div>

                        </div>

                    </div>



                    <div id="serviceCharge" style="display:none">

                        <div class="form-group row">

                            <label for="serviceCharge" class="col-sm-2 col-form-label col-form-label-lg">Service

                                Charge(RM)</label>

                            <div class="col-sm-10">

                                <input id="serviceChargeForm" class="form-control" type="number" name="serviceCharge"

                                    placeholder="RM" min="0.00" max="1000000000.00" step="0.01">

                                <div class="invalid-feedback">

                                    Please put in XX.XX format(Exp: 10.50 @ 1000.00)

                                </div>

                            </div>

                        </div>

                    </div>



                    <!-- HYDROKLEEN GLOBAL -->

                    <?php

              if($config['customerComplaintFormBookingDate']==true)

              {

            ?>

                    <div class="form-group row">

                        <label for="bookingDate" class="col-sm-2 col-form-label col-form-label-lg">Assigned Date</label>

                        <div class="col-sm-10">

                            <input class="form-control" type="date" value="<?php echo date('Y-m-d'); ?>"

                                id="bookingDate" name="bookingDate">

                            <div class="invalid-feedback">



                            </div>

                        </div>

                    </div>

                    <div class="form-group row">

                        <label for="bookingTime" class="col-sm-2 col-form-label col-form-label-lg">Assigned Time</label>

                        <div class="col-sm-10">

                            <input class="form-control" type="time" value="<?php echo date('H:i'); ?>" id="bookingTime"

                                name="bookingTime">

                            <div class="invalid-feedback">



                            </div>

                        </div>

                    </div>

                    <?php

              }

            ?>

                    <div id="invoiceDueDate" style="display:none;">

                        <div class="form-group row">

                            <label for="serviceCharge" class="col-sm-2 col-form-label col-form-label-lg">Invoice Due

                                Date</label>

                            <div class="col-sm-10">

                                <input id="invoiceDueDateForm" class="form-control" type="date" name="invoiceDueDate">

                                <div class="invalid-feedback">



                                </div>

                            </div>

                        </div>

                    </div>



                    <div id="footer" style="display:none;">

                        <div class="form-group row">

                            <label for="footer" class="col-sm-2 col-form-label col-form-label-lg">PDF Footer</label>

                            <div class="col-sm-10">

                                <select id="footerForm" class="form-control" name="footer">

                                    <?php

                      $footerId = null;

                      $footerList=getPdfFooterList($footerId,$_SESSION['orgId']);



                      //$footerNote="";

                      foreach ($footerList as $footer) {

                        $selected="";

                        if($footer['id']==$footerId){

                          $selected="selected";

                        //	$footerNote=$footer['content'];

                        }

                        echo "<option $selected value='".$footer['id']."' >".$footer['name']."</option>";

                      }

                    ?>

                                </select>

                            </div>

                        </div>

                    </div>

	                <div class="form-group row">

		                <label for="myfile" class="col-sm-2 col-form-label col-form-label-lg">Document Upload</label>

		                <div class="col-sm-10">

			                <input id="myfile" class="form-control" type="file" name="myfile" accept="application/pdf,application/msword,
  application/vnd.openxmlformats-officedocument.wordprocessingml.document" />

			                <div class="invalid-feedback">

			                </div>

		                </div>

	                </div>

                    <?php additionalForm($_SESSION['orgType'],isset($module[20])); ?>



                    <div class="form-group row">

                        <label class="col-sm-2 col-form-label col-form-label-lg"></label>

                        <div class="col-sm-10">

                            <button id="makeComplaint" name='makeComplaint' class="btn btn-primary btn-lg btn-block"

                                type='button'>Submit</button>
	                        <button id="hidden-submit" name='makeComplaint' class="btn btn-primary btn-lg btn-block"

	                                type='submit' hidden="true">Submit</button>

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

    <script>

    $(document).ready(function() {



        var state_arr = new Array("JOHOR", "KEDAH", "N SEMBILAN", "PAHANG", "SELANGOR", "TERENGGANU");

        $.each(state_arr, function(i, item) {

            $('.stateop').append($('<option>', {

                value: item,

                text: item

            }, '</option>'));

        });



        // Cities

        var c_a = new Array();

        c_a['JOHOR'] = "SKUDAI|TANGKAK|ULU TIRAM";

        c_a['KEDAH'] = "SG PETANI";

        c_a['N SEMBILAN'] = "BROGA";

        c_a['PAHANG'] = "PALOH HINAI|SG KOYAN";

        c_a['SELANGOR'] = "BUKIT ROTAN";

        c_a['TERENGGANU'] = "KEMAMAN";



        $('.stateop').change(function() {

            var s = $(".stateop option:selected").html();



            if (s == 'Select State') {

                $('.subregion').empty();

                $('.subregion').append($('<option>', {

                    value: '0',

                    text: 'Select subregion'

                }, '</option>'));

            }

            $('.subregionop').empty();

            $('.subregionop').append($('<option>', {

                value: '0',

                text: 'Select Sub Region'

            }, '</option>'));

            var subregion_arr = c_a[s].split("|");





            $.each(subregion_arr, function(j, item_subregion) {

                $('.subregionop').append($('<option>', {

                    value: item_subregion,

                    text: item_subregion

                }, '</option>'));

            });





        });

    });

    </script>

    <script>
	    $(document).ready(function(){
		    $('#makeComplaint').click(function() {
			    var btn = $(this);
			    btn.prop('disabled', true);
			    $( "#hidden-submit" ).click();
			    setTimeout(function() {
				    btn.prop('disabled', false);
			    },30000);   // enable after 5 seconds
		    });
	    });

    </script>
</body>



</html>