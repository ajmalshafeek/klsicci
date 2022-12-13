<?php
$config = parse_ini_file(__DIR__ . "/../../jsheetconfig.ini");
if (!isset($_SESSION)) {
	session_name($config['sessionName']);
	session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/configuration.php");
require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/services.php");

?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/resources/app/favIcon.ico'; ?>' />


	<link rel='stylesheet' type='text/css' href='./css/myQuotationStyle.css' />



	<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/importScripts.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/organization.php");
	loadOrganizationDetail();
	require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/clientCompany.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/invoice.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/organization.php");

	$footerId = 0;
	?>

</head>
<script>
	function dropDownClientChange(str) {
		var companyId = str.value;


		if ($.trim($("#attention").val()).length == 0) {
			// do nothing
		} else {


			$('#activate-step-2').prop('disabled', false);
			$('#pills-items-tab').removeClass('disabled');
		}

	}
	var counter = 0;
	$(document).ready(function() {
		<?php if (isset($_SESSION['editType'])) {
			$invoiceDetails = getinvoiceDetailsByInvNo($_SESSION['invoiceNumber']);
			$footerId = $invoiceDetails['footerId'];

			$invoiceBreakdown = getInvoiceBreakdownByInvId($invoiceDetails['id']);

			echo "$('#attention').val('" . $invoiceDetails['attention'] . "');\n";
			echo "$('select#clientCompanyId').prop('value', " . $invoiceDetails['customerId'] . ").change();\n";
			echo "$(\"table.order-list >tbody \").html(\"\");\n";
			foreach ($invoiceBreakdown as $item) {

				echo "var newRow = $('<tr id=\"inputRow_'+counter+'\"  onclick=\"inputForm(this)\"> ');";
				echo "var cols = \"\"\n;";

				echo "cols += '<td><input type=\"text\" class=\"form-control\" value=\"" . $item['itemName'] . "\" name=\"itemName' + counter + '\"/></td>'; \n";
				echo "cols += '<td><textarea  data-toggle=\"modal\" data-target=\"#itemDescriptionModal\" class=\"form-control\" rows=\"3\" name=\"itemDesc' + counter + '\">" . $item['itemDescription'] . "</textarea></td>'; \n";
				echo "cols += '<td><input type=\"text\" class=\"form-control number-currency\"  value=\"" . number_format($item['itemPrice'], 2) . "\"  name=\"unitCost' + counter + '\"/></td>'; \n";
				echo "cols += '<td><input type=\"number\" min=\"0\" max=\"20\" class=\"form-control\" value=\"" . $item['quantity'] . "\" name=\"qty' + counter + '\"/></td>'; \n";

				echo "cols += '<td><button type=\"button\"  disabled class=\"ibtnDel btn btn-md btn-danger fa fa-minus\" ></button></td>';\n";
				echo "newRow.append(cols);\n";
				echo "$(\"table.order-list\").append(newRow);\n";

				echo "counter++;\n";
			}

			echo "if(counter>1){\n";
			echo "$(\".ibtnDel\").removeAttr('disabled');\n";
			echo "}\n";


			echo "var dueDate='" . date('Y-m-d', strtotime($invoiceDetails['dueDate'])) . "';";

			echo "$('#quot_dueDate').val(dueDate);\n";

			$invoiceNo = $_SESSION['invoiceNumber'];
		}

		?>
		var navListItems = $('ul.setup-panel li a'),
			allWells = $('.setup-content');

		allWells.hide();

		navListItems.click(function(e) {
			e.preventDefault();
			var $target = $($(this).attr('href')),
				$item = $(this).closest('li');

			if (!$item.hasClass('disabled')) {

				navListItems.closest('li').removeClass('active');
				$item.addClass('active');
				allWells.hide();
				$target.show();

			}
		});

		$('ul.setup-panel li.active a').trigger('click');

		$('#activate-step-2').on('click', function(e) {
            $('#quot_recurringDate').val($('#recurringDate').val());
            $('#quot_recurringEnd').val($('#recurringEnd').val());
            $('#quot_recurringBy').val($('#recurringBy').val());
			$('#quot_ponumber').val($('#ponumber').val());
			$('#pills-items-tab').removeClass('disabled');
			$('#pills-items-tab').trigger('click');
			//  $(this).remove();
		})


		$('#activate-step-3').on('click', function(e) {
			$('#pills-confirm-tab').removeClass('disabled');
			$('#pills-confirm-tab').trigger('click');
			$('#pills-confirm-tab').addClass('disabled');
			fillValueToQuotForm();
			setClientCDetails();

			//$(this).remove();
		});
		$('#ponum').on('click', function(e) {
			if ($('#ponum').is(":checked")) {
				$('#quot_ponum').val("1");
				$('.postatus').css("display", "flex");
				$('.poview').css("display", "table-row");
			} else {
				$('#quot_ponum').val("");
				$('.postatus').css("display", "none");
				$('.poview').css("display", "none");
			}
			console.log($('#quot_ponum').val());
		});

		function setClientCDetails() {

			var companyId = $("#clientCompanyId").val();

			$.ajax({

				type: 'GET',
				url: '../../phpfunctions/clientCompany.php?',
				data: {
					quotClientId: companyId
				},
				dataType: 'json',
				success: function(data) {

					var address = data['address1'] + ",";
					if (data['address2'] != null) {
						address += "\n" + data['address2'] + ",";
					}
					address += "\n" + data['city'] + " " + data['postalCode'] + ",";
					address += "\n" + data['state'] + "";


					$("#quot_customerAddress").val(address);
					$("#attSpan").text($("#attention").val());
					$("#quot_attention").val($("#attention").val());

				}
			});
		}





		function fillValueToQuotForm() {
			// currency format start
			var locale = 'us';
			var options = {
				minimumFractionDigits: 2,
				maximumFractionDigits: 2
			};
			var formatter = new Intl.NumberFormat(locale, options);
			// currency format end
			var counter = 0;
			var customerId = $('#clientCompanyId').val();
			var customerName = $('#clientCompanyId option:selected').text();
			$('#quot_customerName').val(customerName);
			$('#quot_customerId').val(customerId);

			//$()$('#quot_customerName').val($("#clientName").val());


			var currentRow = $("table.order-list >tbody >tr").length;
			var totalAmount = 0;

			$('#quot_quotationDate').prop('value', '<?php echo date('d-m-Y'); ?>');
			$('#quot_quotationNo').prop('value', '<?php echo latestInvoiceNo(); ?>');
			<?php
			if (isset($_SESSION['editType'])) {
				$editType = $_SESSION['editType'];

				if ($editType == "overrideInvoice") {
					echo "$('#quot_quotationNo').prop('value','" . $invoiceNo . "');\n";
				}
			}

			?>
			$("table.quotation-Content tbody").empty();
			$('table.order-list > tbody  > tr').each(function() {

				var fields = $(this).find(":input");
				var itemName = fields.eq(0).val();
				var itemDesc = fields.eq(1).val();
				var itemCost = fields.eq(2).val();
				itemCost = itemCost.replace(/,/g, "");
				var itemQty = fields.eq(3).val();
				var price = itemCost * itemQty;
				var outputDesc = itemDesc.replace(/\n/g, "<br />");

				totalAmount += price;
				$('#quot_quotationNo').prop('value', <?php time(); ?>);
				var newRow = $("<tr>");
				var cols = "";



				cols += '<td valign="top"><input type="text" hidden readonly name="itemName' + counter + '" value="' + itemName + '" />' + itemName + '</td>';
				cols += '<td valign="top"><textarea  hidden readonly name="itemDesc' + counter + '" value="' + itemDesc + '" >' + itemDesc + '</textarea>' + outputDesc + '</td>';
				cols += '<td valign="top"><input type="text" hidden readonly name="itemCost' + counter + '" value="RM ' + formatter.format(itemCost) + '" />RM ' + formatter.format(itemCost) + '</td>';
				cols += '<td valign="top"><input type="number" hidden readonly name="itemQty' + counter + '" value="' + itemQty + '" />' + itemQty + '</td>';

				cols += '<td valign="top"><input type="text" hidden readonly name="price' + counter + '" value="RM ' + formatter.format(price) + '" />RM ' + formatter.format(price) + '</td>';
				newRow.append(cols);
				$("table.quotation-Content").append(newRow);

				counter++;

			});
			$('.totalAmount').prop('value', 'RM ' + formatter.format(totalAmount));
			$('#maxItemIndex').prop('value', counter);
		}


		$('#showPaymentModalDialog').click(function() {
			$('#invoicePaymentModal').modal('toggle');
			var totalAmount = getTotalAmount();
			$('#totalAmount').val('RM ' + totalAmount.toFixed(2));

		});

		function getTotalAmount() {
			var totalAmount = 0;
			$('table.order-list > tbody  > tr').each(function() {

				var fields = $(this).find("input");

				var itemCost = fields.eq(2).val();
				var itemQty = fields.eq(3).val();
				var price = itemCost * itemQty;

				totalAmount += price;


			});

			return totalAmount;

		}

		$('#paidAmmount').on("input", function() {

			var isValid = true;

			if (($.trim($(this).val()).length == 0) || $(this).val() <= 0) {
				isValid = false;
				//return isValid;
			}


			if (isValid == true) {
				$('#activate-step-3').prop('disabled', false);
				$('#pills-items-tab').removeClass('disabled');

			} else {
				$('#activate-step-3').prop('disabled', true);
				$('#pills-items-tab').addClass('disabled');
			}

		});

		$('#activate-step-3').on('click', function(e) {
			$('#invoicePaymentModal').modal('hide');
		//	step3Activation();

		});

		$('[required]').on("paste keyup", function() {

			var isValid = true;
			$("[required]").each(function() {

				if ($.trim($(this).val()).length == 0) {
					isValid = false;
					return isValid;
				} else {
					if ($(this).prop('type') == 'email') {
						var emailReg = /^(([\w-]+\.)+@([\w-]+\.)+[\w-]{2,4})?$/;
						if (!emailReg.test($(this).prop('value'))) {
							isValid = false;
							return isValid;
						}
					}

				}

			});
			if (isValid == true) {
				//alert($("#clientCompanyId").val());
				//$("#clientCompanyId").val()
				if ($("#clientCompanyId option:selected").index() > 0) {

					$('#activate-step-2').prop('disabled', false);
					$('#pills-items-tab').removeClass('disabled');
				}

			} else {
				$('#activate-step-2').prop('disabled', true);
				$('#pills-items-tab').addClass('disabled');
			}

		});
        $('#recurringDate').change(function(){
            $('#quot_dueDate').val($('#recurringDate').val());
            if($('#recurringBy').val()!=''){
                $('#activate-step-2').prop("disabled",false);
            }else{$('#activate-step-2').prop("disabled",true);}

        });
        $('#recurringBy').change(function(){

            if($('#recurringDate').val()!=''){
                $('#activate-step-2').prop("disabled",false);
            }else{$('#activate-step-2').prop("disabled",true);}

        });


	});

	function convertToCurrency(str) {
		var id = str.getAttribute('id');
		var value = str.value;
		value = value.replace(/,/g, '');
		document.getElementById(id).value = numberWithCommas(value);

	}

	function numberWithCommas(x) {
		var parts = x.toString().split(".");
		parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");

		return parts.join(".");
	}

	function inputForm(str) {
		var rowId = $(str).attr("id");
		$('#inputRowId2').prop('value', rowId);

		var itemDescription = $(str).find('td').eq(1).find('textarea').val();

		$('#inputRow_itemDescription2').prop('value', itemDescription);


		if ($(window).width() < 991) {
			$('#inputRowId').prop('value', rowId);
			var itemName = $(str).find('td').eq(0).find('input').val();
			//	var itemDescription=$(str).find('td').eq(1).find('textarea').val();
			var unitPrice = $(str).find('td').eq(2).find('input').val();
			var quantity = $(str).find('td').eq(3).find('input').val();

			$('#inputRow_itemName').prop('value', itemName);
			$('#inputRow_itemDescription').prop('value', itemDescription);
			$('#inputRow_unitPrice').prop('value', unitPrice);
			$('#inputRow_qty').prop('value', quantity);

			$('#inputFormModal').modal('toggle');
		}
	}
	// Add , Delete row dynamically
	$(document).ready(function() {
		$('input.number-currency').keyup(function(event) {

			// skip for arrow keys
			if (event.which >= 37 && event.which <= 40) {
				event.preventDefault();
			}

			$(this).val(function(index, value) {
				value = value.replace(/[^0-9]/g, '');
				return numberWithCommas(value);
			});
		});

		if ($(window).width() < 991) {
			$('#tab_logic input').attr('readonly', 'true');
			$('#tab_logic textarea').attr('readonly', 'true');
		}


		$('#inputItemDescriptionToTable').on("click", function() {

			var rowId = $("#inputRowId2").val();
			var itemDescription = $("#inputRow_itemDescription2").val();
			$("#" + rowId + "").find('td').eq(1).find('textarea').prop('value', itemDescription);


		});

		$('#inputValueToTable').on("click", function() {

			var rowId = $("#inputRowId").val();
			var itemName = $("#inputRow_itemName").val();
			var itemDescription = $("#inputRow_itemDescription").val();
			var unitPrice = $("#inputRow_unitPrice").val();
			var quantity = $("#inputRow_qty").val();

			$("#" + rowId + "").find('td').eq(0).find('input').prop('value', itemName);
			$("#" + rowId + "").find('td').eq(1).find('textarea').prop('value', itemDescription);
			$("#" + rowId + "").find('td').eq(2).find('input').prop('value', unitPrice);
			$("#" + rowId + "").find('td').eq(3).find('input').prop('value', quantity);




		});


		$("#addrow").on("click", function() {
			var newRow = $('<tr id="inputRow_' + counter + '"  onclick="inputForm(this)"> ');
			var cols = "";

			//	cols += '<td>'+counter+'</td>';
			cols += '<td><input type="text" class="form-control" name="itemName' + counter + '"/></td>';
			cols += '<td><textarea data-toggle="modal" readonly data-target="#itemDescriptionModal" class="form-control" rows="3" name="itemDesc' + counter + '"/></textarea></td>';
			cols += '<td><input type="text" class="form-control" id="inputUnitPrice' + counter + '" onkeyup="convertToCurrency(this)" name="unitCost' + counter + '"/></td>';
			cols += '<td><input type="number" min="0" max="20" class="form-control" name="qty' + counter + '"/></td>';
			//	cols += '<td><input type="text" class="form-control" name="price' + counter + '"/></td>';

			cols += '<td><button type="button"  class="ibtnDel btn btn-md btn-danger fa fa-minus" ></button></td>';
			newRow.append(cols);
			$("table.order-list").append(newRow);
			counter++;
			var currentRow = $("table.order-list >tbody >tr").length;
			if (currentRow > 1) {

				$(".ibtnDel").removeAttr('disabled');

			}
		});



		$("table.order-list").on("click", ".ibtnDel", function(event) {

			var currentRow = $("table.order-list >tbody >tr").length;
			if (currentRow > 1) {
				$(this).closest("tr").remove();
				if (currentRow == 2) {
					$("table.order-list >tbody >tr:first ").find('button').attr('disabled', true);
				}
			}

			//counter -= 1
		});


	});

	function loadServicesTable() {
		var servicesArrStr = document.getElementById("servicesArrStr").value;
		var servicesArrObj = JSON.parse("[" + servicesArrStr + "]");

		for (var counter = 0; counter < servicesArrObj.length; counter++) {
			serviceId = servicesArrObj[counter];
			$.ajax({
				type: 'GET',
				url: '../../phpfunctions/services.php?',
				data: {
					getServiceDescDetails: serviceId
				},
				success: function(data) {
					//(START)CHECK IF ELEMENT EXIST
					var elementCheck = document.getElementById("inputRow_");
					if (elementCheck) {
						elementCheck.parentNode.removeChild(elementCheck);
					}
					//(END)CHECK IF ELEMENT EXIST
					dataList = JSON.parse(data);
					//details= JSON.parse(data);
					//document.getElementById("item").value = details.item;
					Object.keys(dataList).forEach(function(key) {
						console.log(dataList[key].item);
						var newRow = $('<tr id="inputRow_' + counter + '"  onclick="inputForm(this)"> ');
						var cols = "";
						cols += '<td><input type="text" class="form-control" value="' + dataList[key].item + '" name="itemName' + counter + '"/></td>';
						cols += '<td><textarea data-toggle="modal" readonly data-target="#itemDescriptionModal" class="form-control" rows="3" name="itemDesc' + counter + '">' + dataList[key].description + '</textarea></td>';
						cols += '<td><input type="text" class="form-control" id="inputUnitPrice' + counter + '" onkeyup="convertToCurrency(this)" value="' + dataList[key].unitPrice + '" name="unitCost' + counter + '"/></td>';
						cols += '<td><input type="text" class="form-control" value="' + dataList[key].quantity + '" name="qty' + counter + '"/></td>';
						cols += '<td><button type="button"  class="ibtnDel btn btn-md btn-danger fa fa-minus" ></button></td>';
						newRow.append(cols);
						$("table.order-list").append(newRow);
						counter++;
						var currentRow = $("table.order-list >tbody >tr").length;
						if (currentRow > 1) {
							$(".ibtnDel").removeAttr('disabled');
						}
					});
				}
			});
		}
	}
</script>
<script>
	$(document).ready(function() {
		var t = new Date();
		var year = t.getFullYear();
		var day, month, day1;
		var daya = t.getDate() + 7;
		if (daya < 10) {
			day1 = "0" + daya
		} else {
			day1 = daya;
		}
		if (t.getDate() < 10) {
			day = "0" + t.getDate();
		} else {
			day = t.getDate();
		}
		if ((t.getMonth() + 1) < 10) {
			month = "0" + (t.getMonth() + 1);
		} else {
			month = t.getMonth() + 1;
		}
		var datestr = year + "-" + month + "-" + day1;
		var todayDate = year + "-" + month + "-" + day;
		//console.log(datestr);
		jQuery('#quot_dueDate').val(datestr);
		jQuery('#quot_dueDate').attr("min", todayDate);

	});
</script>

<style>
	.modal-full {
		min-width: 90%;
		margin: 0;
	}

	.custom-checkbox-lg label::before,
	.custom-checkbox-lg label::after {
		top: 0.1rem !important;
		left: -2rem !important;
		width: 1.25rem !important;
		height: 1.25rem !important;
	}

	.custom-checkbox-lg label {
		margin-left: 0.5rem !important;
		font-size: 1rem !important;
		margin-top: -12px;
		/*float: revert;*/
	}

	.custom-checkbox-lg input[type="checkbox"] {
		width: 1.5em !important;
		height: 1.5em !important;
		float: left;
	}
</style>
<?php
include $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/navMenu.php";
?>

<body class="fixed-nav ">
	<div class="content-wrapper">
		<div class="container-fluid">
			<?php echo shortcut() ?>
			<!-- Breadcrumbs-->
			<ol class="breadcrumb col-md-12">
				<li class="breadcrumb-item">
					<a href="#">Recurring Invoice </a>
				</li>
				<li class="breadcrumb-item active">Create Recurring Invoice</li>
			</ol>
		</div>
		<div class="container">


			<ul class="nav nav-pills nav-justified" id="pills-tab" role="tablist">
				<li class="nav-item " style="font-size:18px; ">
					<!--
							<a class="nav-link active"  id="pills-clientDetails-tab" data-toggle="pill" href="#pills-clientDetails" role="tab" aria-controls="pills-clientDetails" aria-selected="true">#1 CLIENT DETAILS</a>
							-->
					<a class="nav-link" id="pills-clientDetails-tab" data-toggle="pill" href="#pills-clientDetails" role="tab" aria-controls="pills-clientDetails" aria-selected="true">
						<img style="width:50px;" src="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/resources/app/jobsheet_icon_client.png'; ?>" />
					</a>Client
				</li>
				<li class="nav-item" style="font-size:18px;">
					<!--
							<a class="nav-link  disabled " id="pills-items-tab" data-toggle="pill" href="#pills-items" role="tab" aria-controls="pills-items" aria-selected="false">#2 INVOICED ITEM</a>
							-->
					<a class="nav-link  disabled " id="pills-items-tab" data-toggle="pill" href="#pills-items" role="tab" aria-controls="pills-items" aria-selected="false">
						<img style="width:50px;" src="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/resources/app/jobsheet_icon_invoice.png'; ?>" />
					</a>Invoice

				</li>
				<li class="nav-item" style="font-size:18px;">
					<!--
						<a class="nav-link  disabled" id="pills-confirm-tab" data-toggle="pill" href="#pills-confirm" role="tab" aria-controls="pills-confirm" aria-selected="false">#3 CONFIRM</a>
						-->
					<a class="nav-link  disabled" id="pills-confirm-tab" data-toggle="pill" href="#pills-confirm" role="tab" aria-controls="pills-confirm" aria-selected="false">
						<img style="width:50px;" src="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/resources/app/thumbnail.png'; ?>" />
					</a>Confirmation

				</li>
			</ul>


			<div class="tab-content" id="pills-tabContent">
				<!-- TAB 1 -->
				<div class="tab-pane fade show active " id="pills-clientDetails" role="tabpanel" aria-labelledby="pills-clientDetails-tab">
					<!-- TAB 1 CONTENT -->
					<br />


					<div class="form-group row">
						<label for="clientName" class="col-sm-2 col-form-label col-form-label-lg">Name:</label>
						<div class="col-sm-10">
							<select name="clientCompanyId" onChange="dropDownClientChange(this);" class="form-control" id="clientCompanyId">
								<option selected disabled value="">--Select--</option>
								<?php

								$clientCompanyList = dropDownOptionListOrganizationClientCompanyActive();
								echo $clientCompanyList;
								?>
							</select>

						</div>
					</div>

					<div class="form-group row">
						<label for="attention" class="col-sm-2 col-form-label col-form-label-lg">Attention:</label>
						<div class="col-sm-10">

							<input type="text" class="form-control required" placeholder="Recipient name " id="attention" name="attention" />

							<div class="invalid-feedback">
								Please Enter Recipient Name
							</div>

						</div>
					</div>
                    <div class="form-group row">
                        <label for="recurringDate" class="col-sm-2 col-form-label col-form-label-lg">Start Date:</label>
                        <div class="col-sm-4">

                            <input type="date" class="form-control required" placeholder="Recurring Date" id="recurringDate" name="recurringDate" />

                            <div class="invalid-feedback">
                                Please Enter Recurring Date
                            </div>

                        </div>
                        <label for="recurringEnd" class="col-sm-2 col-form-label col-form-label-lg">End Date:</label>
                        <div class="col-sm-4">

                            <input type="date" class="form-control" placeholder="Recurring Date" id="recurringEnd" name="recurringEnd"  />

                            <div class="invalid-feedback">
                                Please Enter End Date
                            </div>

                        </div>
                        </div>
                        <div class="form-group row">
                        <label for="recurringBy" class="col-sm-2 col-form-label col-form-label-lg">Recurring By:</label>
                        <div class="col-sm-4">

                            <select name="recurringBy"  class="form-control required" id="recurringBy">
                                <option selected disabled value="">--Select--</option>
                                <option value="Month">Month</option>
                                <option value="Year">Year</option>
                                </select>

                            <div class="invalid-feedback">
                                Please Enter Recurring Format
                            </div>

                        </div>
                    </div>

					<div class="form-group row">
						<label class="col-sm-2 col-form-label col-form-label-lg"></label>
						<div class="col-sm-10 custom-checkbox-lg">
							<label for="ponum" class="col-form-label col-form-label-lg">Required PO Number</label>
							<input type="checkbox" id="ponum" name="ponum" value="1" class="form-control" />
						</div>
					</div>
					<div class="form-group row postatus" style="display:none">
						<label for="ponumber" class="col-sm-2 col-form-label col-form-label-lg">PO NO:</label>
						<div class="col-sm-10">

							<input type="text" class="form-control" placeholder="PO Number" id="ponumber" name="ponumber" />

							<div class="invalid-feedback">
								Please Enter PO Number
							</div>

						</div>
					</div>


					<div class="form-group row">
						<label class="col-sm-2 col-form-label col-form-label-lg"></label>
						<div class="col-sm-10" style="text-align:right">
							<button id="activate-step-2" disabled class="btn btn-primary  btn-lg" type='button'>Next</button>
						</div>
					</div>

					<!-- END OF TAB 1 CONTENT -->

				</div>
				<!-- END OF TAB 1 -->

				<!-- TAB 2 -->
				<div class="tab-pane fade " id="pills-items" role="tabpanel" aria-labelledby="pills-items-tab">
					<!-- START Button Service -->
					<button class="btn" data-toggle='modal' data-target='#serviceModal'>Add Service</button>
					<!-- END Button Service -->
					<!-- TAB 2 CONTENT -->

					<br />
					<center>
						<table class="table order-list table-responsive  table-hover table-bordered" style="width:80%" id="tab_logic">
							<thead>
								<tr>

									<th class="text-center" class="th_item" style="color:#19334d;width:18%">
										Item:
									</th>
									<th class="text-center" class="th_desc" style="color:#19334d;width:40%">
										Description:
									</th>
									<th class="text-center" class="th_cost" style="color:#19334d;width:20%">
										Unit Cost:
									</th>

									<th class="text-center" class="th_qty" style="color:#19334d;width:12%">
										Quantity:
									</th>

									<th class="text-center" class="th_qty" style="color:#19334d;width:5%">
										Remove:
									</th>

								</tr>
							</thead>
							<tbody>
								<tr id='inputRow_' onclick="inputForm(this)">

									<td>
										<input type="text" name='itemName0' class="form-control" />
									</td>
									<td>
										<textarea data-toggle="modal" readonly data-target="#itemDescriptionModal" name='itemDesc0' rows="3" class="form-control"></textarea>
									</td>
									<td>
										<input type="text" name='unitCost0' class="form-control number-currency" />
									</td>
									<td>
										<input type="number" name='qty0' min="0" max="20" class="form-control" />
									</td>
									<!--
									<td>
										<input type="text" name='price0' class="form-control"/>
									</td>
									-->
									<td>
										<button type="button" disabled class="ibtnDel btn btn-md btn-danger fa fa-minus">
										</button>
									</td>

								</tr>
							</tbody>
							<tfoot>
								<tr>
									<td style="text-align: left;">
										<button type="button" class="btn btn-lg btn-block btn-success fa fa-plus " id="addrow">

										</button>
									</td>
								</tr>
							</tfoot>
						</table>
					</center>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label col-form-label-lg"></label>
						<div class="col-sm-10" style="text-align:right">

							<button id="activate-step-3" class="btn btn-primary  btn-lg" type='button'>NEXT</button>
						</div>
					</div>

					<!-- END OF TAB 2 CONTENT -->

				</div>
				<!-- END OF TAB 2 -->

				<!-- TAB 3 -->
				<?php
				$orgDetails = fetchOrganizationDetails($_SESSION['orgId']);

				?>
				<div class="tab-pane fade " id="pills-confirm" role="tabpanel" aria-labelledby="pills-confirm-tab">
					<br />

					<form id="invoiceCreationForm" action="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/phpfunctions/invoice.php"; ?>" method="POST">
						<div class="form-group row">

							<label for="extraNote" class="col-sm-2 col-form-label ">PDF FOOTER
							</label>
							<div class="col-sm-10">
								<select class="custom-select" name="pdfFooter" id="pdfFooter" style="background-color:#A3C2CE;border:none;border-top:1px solid black;border-radius:0px;border-bottom:1px solid black;">
									<?php
									$footerList = getPdfFooterList(null, $_SESSION['orgId']);

									//$footerNote="";
									foreach ($footerList as $footer) {
										$selected = "";
										if ($footer['id'] == $footerId) {
											$selected = "selected";
											//	$footerNote=$footer['content'];
										}
										echo "<option $selected value='" . $footer['id'] . "' >" . $footer['name'] . "</option>";
									}
									?>
								</select>
							</div>
						</div>

						<div style="text-align:right">


							<?php
							if (isset($_SESSION['editType'])) {
								$editType = $_SESSION['editType'];

								if ($editType == "overrideInvoice") {
									echo "<button type=\"submit\" class=\"btn btn-success\" id=\"activate-step-4\"   name=\"updateInvoice\" value=\"UPDATE\" >Update</button>\n";
								} else {
									echo "<button type=\"submit\" class=\"btn btn-success\" id=\"activate-step-4\"   name=\"createInvoice\" value=\"SAVE\" >Save</button>\n";
								}
							} else {
								echo "<button type=\"submit\" class=\"btn btn-success\" id=\"activate-step-4\"   name=\"createInvoice\" value=\"SAVE\" >Save</button>\n";
							}
							?>

						</div></br>
						<input type="hidden" name="maxItemIndex" id="maxItemIndex" value="0" />

						<div class="table-responsive">

							<table class="quotation-Header">
								<thead style="background-color:black;">
									<tr>
										<th style="background-color:black;" colspan="5">
											<i class="fa fa fa-file-word-o" style="color:white;"></i>
											<span style="color:white; background-color: black;" class="a">Invoice</span>
										</th>
									</tr>
								</thead>

								<tbody>
									<tr>
										<td colspan="2" style="min-width:250px;width:40%; padding-top:15px;">
											<b>
												<?php echo $orgDetails['name']; ?>
											</b>
											<input hidden type="text" readonly size="50" id="quot_myOrgName" name="quot_myOrgName" value="<?php echo $orgDetails['name']; ?>" />

										</td>


										<td colspan="3" rowspan="4" class="myOrgLogo" style="width:60%">
											<img id="image" style="height:100px;max-width:350px" src=<?php echo "https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/resources/" . $_SESSION['orgLogo'] . ".png"; ?> alt="logo" />
										</td>
									</tr>

									<tr>
										<td class="myOrgAddress" colspan="2">
											<?php echo $orgDetails['address1'] . ",";
											if ($orgDetails['address2'] != null) {
												echo "<br/>" . $orgDetails['address2'] . ",";
											}
											echo "<br/>" . $orgDetails['postalCode'] . " " . $orgDetails['city'] . ",";
											echo "<br/>" . $orgDetails['state'];
											?>
											<textarea hidden rows="4" cols="100" id="quot_myOrgAddress" name="quot_myOrgAddress"><?php echo $orgDetails['address1'] . ",";
																																	if ($orgDetails['address2'] != null) {
																																		echo "\n" . $orgDetails['address2'] . ",";
																																	}
																																	echo "\n" . $orgDetails['postalCode'] . " " . $orgDetails['city'] . ",";
																																	echo "\n" . $orgDetails['state'];
																																	?></textarea>
										</td>


									</tr>

									<tr>
										<td colspan="2">
											<?php
											if ($orgDetails['contact']) {
												echo "<b>Phone Number: </b>" . $orgDetails['contact'];
											}
											?>
											<input type="text" hidden value="<?php echo $orgDetails['contact']; ?>" id="quot_orgPhone" name="quot_orgPhone" />
										</td>


									</tr>

									<tr>

										<td colspan="2" style="padding-bottom:15px;">
											<?php
											if ($orgDetails['faxNo']) {
												echo "<b>Fax Number: </b>" . $orgDetails['faxNo'];
											}

											?>
											<input type="text" hidden value="<?php echo $orgDetails['faxNo']; ?>" id="quot_orgFaxNo" name="quot_orgFaxNo" />
										</td>

									</tr>

									<tr>
										<td class="customerName" style="border-top: 1px solid black;" valign="top" colspan="2">
											<input type="text" readonly value="Customer Name" id="quot_customerName" name="quot_customerName" />
											<input type="text" readonly value="0" hidden id="quot_customerId" name="quot_customerId" />
											<input type="text" hidden id="quot_ponum" name="quot_ponum" />
                                            <input type="text" hidden id="quot_recurringDate" name="quot_recurringDate" />
                                            <input type="text" hidden id="quot_recurringEnd" name="quot_recurringEnd" />
                                            <input type="text" hidden id="quot_recurringBy" name="quot_recurringBy" />
                                        </td>

										<td rowspan="4" style="border-top: 1px solid black;width:150px">

										</td>
										<td rowspan="4" style="border-top: 1px solid black;width:150px">
											<table width="100%" height="100%" class="invoc-Header">
												<tr>
													<td class="meta-head">
														Invoice:
													</td>
													<td class="meta-body">
														<input type="number" readonly value="0000123" id="quot_quotationNo" name="quot_quotationNo" />
													</td>

												</tr>
												<tr class="poview" style="display:none;">
													<td class="meta-head">
														PO NO:
													</td>

													<td class="meta-body">
														<input type="text" readonly id="quot_ponumber" name="quot_ponumber" />

													</td>
												</tr>
												<tr>
													<td class="meta-head">
														Due Date:
													</td>
						</div>
						<td class="meta-body">

							<input type="date" id="quot_dueDate" class="dueDate" name="quot_dueDate" />
						</td>
						</tr>
						<tr>
							<td class="meta-head">
								Date:
							</td>
							<td class="meta-body">
								<input type="text" readonly value="21st Sept 2018" id="quot_quotationDate" name="quot_quotationDate" />

							</td>
						</tr>
						</table>
						</td>

						<tr>
							<td class="customerAddress" valign="top" colspan="2" rowspan="2">
								<textarea readonly rows="4" cols="100" id="quot_customerAddress" name="quot_customerAddress">
										</textarea>
							</td>
						</tr>
						<tr>

						</tr>
						<tr>
							<td colspan="5"><b>To:&nbsp;</b>
								<input type="text" hidden id="quot_attention" name="quot_attention" />
								<span id="attSpan"></span>
							</td>
						</tr>

						</tbody>
						</table>

						<table class="quotation-Content">
							<thead>
								<tr>
									<th style="width:15%;min-width:100px">
										Item:
									</th>
									<th style="width:50%;min-width:200px">
										Description:
									</th>
									<th style="width:15%">
										Unit Cost:
									</th>
									<th style="width:20%">
										Quantity:
									</th>
									<th style="width:15%">
										Price:
									</th>

								</tr>
								<thead>
								<tbody>

								</tbody>
							<tfoot>

								<tr>
									<td colspan="2" class="blank">

									</td>
									<td colspan="2" class="meta-head">
										Total:
									</td>
									<td>


										<input type="text" readonly class="totalAmount" id="quot_totalAmount" name="quot_totalAmount" />
									</td>
								</tr>
							</tfoot>
						</table>


		        </form>
			<br />


		</div>
		<!-- END OF TAB 3 CONTENT -->
	</div>
	<!-- END OF TAB 3 -->
	</div>




	<!-- START MODAL -->
	<!-- INVOICE PAYMENT Modal START-->
	<form action="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/phpfunctions/quotation.php"; ?>" method="POST">
		<div class="modal fade " data-backdrop="static" id="invoicePaymentModal" tabindex="-1" role="dialog" aria-labelledby="invoicePaymentModal" aria-hidden="true">
			<div class="modal-dialog " role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="invoicePaymentModal">PAYMENT</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">

						<div id='invoicePaymentModalContent'>
							<div class="form-group row">
								<label for="totalAmount" class="col-sm-2 col-form-label col-form-label-lg">Total:</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" readonly id="totalAmount" name="totalAmount" />
									<div class="invalid-feedback">

									</div>
								</div>
							</div>

							<div class="form-group row">
								<label for="paidAmmount" class="col-sm-2 col-form-label col-form-label-lg">Paid Amount:</label>
								<div class="col-sm-10">
									<input type="number" class="form-control" id="paidAmmount" name="paidAmmount" />
									<div class="invalid-feedback">

									</div>
								</div>
							</div>

						</div>

					</div>
					<div class="modal-footer">

						<button type="button" id="activate-step-3" disabled class="btn btn-primary">SUBMIT</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">CLOSE</button>
					</div>
				</div>
			</div>
		</div>
	</form>
	<!-- INVOICE PAYMENT  Model END -->

	<!-- input form MODAL START -->
	<div class="modal fade " data-backdrop="static" id="inputFormModal" tabindex="-1" role="dialog" aria-labelledby="inputFormModal" aria-hidden="true">
		<div class="modal-dialog modal-full" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="inputFormModalTitle">DETAILS</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">

					<div id='inputFormModalContent'>
						<input type="text" hidden id="inputRowId" />
						<div class="form-group row">
							<label for="orgName" class="col-sm-2 col-form-label col-form-label-md">ITEM NAME</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" placeholder="Item name" id="inputRow_itemName" />
							</div>
						</div>

						<div class="form-group row">
							<label for="orgName" class="col-sm-2 col-form-label col-form-label-md">ITEM DESCRIPTION</label>
							<div class="col-sm-10">
								<textarea class="form-control" placeholder="Item Description" rows="3" id="inputRow_itemDescription" onkeyup="if(this.value = this.value.replace(/\'/g, '')){document.getElementById('restricApos').style.display = 'none';}else{document.getElementById('restricApos').style.display = 'block';}"></textarea>
							</div>
						</div>

						<div class="form-group row">
							<label for="orgName" class="col-sm-2 col-form-label col-form-label-md">UNIT PRICE</label>
							<div class="col-sm-10">
								<input type="number" class="form-control" placeholder="Unit price" id="inputRow_unitPrice" />
							</div>
						</div>

						<div class="form-group row">
							<label for="orgName" class="col-sm-2 col-form-label col-form-label-md">QUANTITY</label>
							<div class="col-sm-10">
								<input type="number" class="form-control" placeholder="Quantity" id="inputRow_qty" />
							</div>
						</div>
					</div>

				</div>
				<div class="modal-footer">

					<button type="button" data-dismiss="modal" name="inputValueToTable" id="inputValueToTable" class="btn btn-primary">DONE</button>
				</div>
			</div>
		</div>
	</div>
	<!-- input form MODAL END -->


	<!-- item description  MODAL START -->
	<div class="modal fade " data-backdrop="static" id="itemDescriptionModal" tabindex="-1" role="dialog" aria-labelledby="itemDescriptionModal" aria-hidden="true">
		<div class="modal-dialog modal-half" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="itemDescriptionModalTitle">ITEM DESCRIPTION</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">

					<div id='itemDescriptionModalContent'>
						<input type="text" hidden id="inputRowId2" />


						<textarea class="form-control" placeholder="Item Description" rows="10" id="inputRow_itemDescription2" onkeyup="if(this.value = this.value.replace(/\'/g, '')){document.getElementById('restricApos').style.display = 'none';}else{document.getElementById('restricApos').style.display = 'block';}"></textarea>
						<span id="restricApos" style="display:none">Cannot use Apostrophe(')</span>


					</div>

				</div>
				<div class="modal-footer">

					<button type="button" data-dismiss="modal" name="inputItemDescriptionToTable" id="inputItemDescriptionToTable" class="btn btn-primary">DONE</button>
				</div>
			</div>
		</div>
	</div>
	<!-- item description MODAL END -->

	<!-- Service Modal START-->
	<div class="modal fade" id="serviceModal" tabindex="-1" role="dialog" aria-labelledby="serviceModal" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Services</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<script type="text/javascript">
					function addServiceList() {
						var serviceId = document.getElementById("service").value;
						if (serviceId != "empty") {
							var servicesArrStr = document.getElementById("servicesArrStr").value;
							if (servicesArrStr == "") {
								document.getElementById("servicesArrStr").value = serviceId;
							} else {
								document.getElementById("servicesArrStr").value = servicesArrStr + "," + serviceId;
							}
							loadServicesRows();
						}
					}

					function loadServicesRows() {
						document.getElementById("servicesRows").innerHTML = "";
						var servicesArrStr = document.getElementById("servicesArrStr").value;
						console.log("Add: " + servicesArrStr);
						var servicesArrObj = JSON.parse("[" + servicesArrStr + "]");
						for (var i = 0; i < servicesArrObj.length; i++) {
							serviceId = servicesArrObj[i];
							$.ajax({
								type: 'GET',
								url: '../../phpfunctions/services.php?',
								data: {
									getServiceDetails: serviceId
								},
								success: function(data) {
									details = JSON.parse(data);
									var serviceRows = document.getElementById("servicesRows").innerHTML;
									document.getElementById("servicesRows").innerHTML = serviceRows + "<div class='form-group row'><div class='col-sm-10'>" + details.service + "</div><div class='col-sm-2'><button onclick='removeServiceArrStr(" + details.id + ")' class='btn-md btn-danger fa fa-minus'></button></div></div><hr/>";
								}
							});
						}
					}

					function removeServiceArrStr(id) {
						var servicesArrStr = document.getElementById("servicesArrStr").value;
						if (servicesArrStr.includes(id + ",")) {
							var updatedServicesArrStr = servicesArrStr.replace(id + ",", "");
						} else if (servicesArrStr.includes(id)) {
							var updatedServicesArrStr = servicesArrStr.replace(id, "");
						}

						if (updatedServicesArrStr[updatedServicesArrStr.length - 1] == ",") {
							var updatedServicesArrStr = updatedServicesArrStr.substring(0, updatedServicesArrStr.length - 1);
						}

						document.getElementById("servicesArrStr").value = updatedServicesArrStr;
						loadServicesRows();
					}

					function loadServiceList() {
						loadServicesTable();
					}
				</script>
				<div class="modal-body">
					<div id="servicesRows"></div>
					<div class="form-group row">
						<div class="col-sm-12">
							<select id="service" class="form-control" name="service">
								<?php echo servicesOptionList(); ?>
							</select>
						</div>
						<div class="col-sm-12">
							<button onclick="addServiceList()" class="btn btn-primary btn-block" type="button" name="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="form-group row">
						<div class="col-sm-6">
							<input id="servicesArrStr" type="text" hidden>
							<button onclick="loadServiceList()" type="button" class="btn btn-secondary btn-lg" title="CLOSE DIALOG" data-dismiss="modal">
								<i class="fa fa-plus" aria-hidden="true"></i>
								Add
							</button>
						</div>
						<div class="col-sm-6">
							<button type="button" class="btn btn-secondary btn-lg" title="CLOSE DIALOG" data-dismiss="modal">
								<i class="fa fa-times" aria-hidden="true"></i>
								Close
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Service Modal END-->

	<!-- END MODAL -->


	<div>
		<div class="footer">
			<p>Powered by JSoft Solution Sdn. Bhd</p>
		</div>
	</div>


	<a class="scroll-to-top rounded" href="#page-top">
		<i class="fa fa-angle-up"></i>
	</a>


	</div>
	<?php
	unset($_SESSION['invoiceNumber']);
	unset($_SESSION['editType']);

	?>

</body>

</html>