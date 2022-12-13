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


	<link rel='stylesheet' type='text/css' href='css/myQuotationStyle.css' />



	<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/importScripts.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/organization.php");
	loadOrganizationDetail();
	require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/clientCompany.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/tripInvoice.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/trip.php");
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

<?php
    if(isset($_POST['createTripInvoiceByCheckBox'])){


        $triplist=$_POST['checkedRow'];
        `var list ='`.print_r($triplist,1).`';`;


        echo "$(document).ready(function() {\n";
        echo "$(\"table.order-list >tbody \").html(\"\");\n";
        echo "var counter = 0;";
        $trip=array();
        $con=connectDb();
        foreach($triplist as $list){
                $trip=fetchTripListById($con,$list);

                echo "var newRow = $('<tr id=\"inputRow_'+counter+'\"  onclick=\"inputForm(this)\" class=\"".$trip['id']."\"> ');";
                echo "var cols = \"\"\n;";
                echo "cols += `<td><input type=\"date\" class=\"form-control\" value=\"" . $trip['date'] . "\" name=\"itemDate' + counter + '\"/></td>`; \n";
                echo "cols += `<td><input type=\"text\" class=\"form-control\" value=\"" . "SMU-".$trip['id'] . "\" name=\"itemJob' + counter + '\"/></td>`; \n";
                echo "cols += `<td><input type=\"text\" class=\"form-control\" value=\"" . $trip['truck_no'] . "\" name=\"itemVehicle' + counter + '\"/></td>`; \n";
                echo "cols += `<td><input type=\"text\" class=\"form-control\" value=\"" . $trip['shipment_no'] . "\" name=\"itemDocNo' + counter + '\"/></td>`; \n";
                echo "cols += `<td><textarea  data-toggle=\"modal\" data-target=\"#itemDescriptionModal\" class=\"form-control\" rows=\"3\" name=\"itemDesc' + counter + '\">`;";
                echo "cols += `";
                if(!empty($trip["collectionPoint"]))
                {echo "From ".$trip['collectionPoint'];}
                if(!empty($trip["deliveryPoint"]))
                {echo "To ".$trip['deliveryPoint'];}
                if(!empty($trip["remarks"]))
                {echo $trip["remarks"];}
                echo "`;";
                echo "cols += `</textarea></td>`; \n";
                echo "cols += `<td><input type=\"text\" min=\"0\" max=\"20\" class=\"form-control\" value=\"" . $trip['truck'] . "\" name=\"unitType' + counter + '\"/></td>`; \n";
                echo "cols += `<td><input type=\"text\" class=\"form-control number-currency\"  value=\"" . number_format($trip['amount'], 2) . "\"  name=\"amount' + counter + '\"  step=\"0.01\"/></td>`; \n";

                echo "cols += `<td><button type=\"button\"  disabled class=\"ibtnDel btn btn-md btn-danger fa fa-minus\" ></button></td>`;\n";
                echo "newRow.append(cols);\n";
                echo "$(\"table.order-list\").append(newRow);\n";
                echo "counter++;\n";
        }
        echo "});";
    }
?>

	var counter = 0;
	$(document).ready(function() {
		<?php if (isset($_SESSION['editType'])) {
			$invoiceDetails = getTripInvoiceDetailsByInvNo($_SESSION['invoiceNumber']);
			$footerId = $invoiceDetails['footerId'];

			$invoiceBreakdown = getTripInvoiceBreakdownByInvId($invoiceDetails['id']);

			echo "$('#attention').val('" . $invoiceDetails['attention'] . "');\n";
			echo "$('select#clientCompanyId').prop('value', " . $invoiceDetails['customerId'] . ").change();\n";
			echo "$(\"table.order-list >tbody \").html(\"\");\n";
			foreach ($invoiceBreakdown as $item) {
                echo "console.log(".$item['truck'].")";
				echo "var newRow = $('<tr id=\"inputRow_'+counter+'\"  onclick=\"inputForm(this)\"> ');";
				echo "var cols = \"\"\n;";

				echo "cols += `<td><input type=\"date\" class=\"form-control\" value=\"" . $item['itemDate'] . "\" name=\"itemDate' + counter + '\"/></td>`; \n";
				echo "cols += `<td><input type=\"text\" class=\"form-control\" value=\"" . $item['itemJob'] . "\" name=\"itemJob' + counter + '\"/></td>`; \n";
				echo "cols += `<td><input type=\"text\" class=\"form-control\" value=\"" . $item['itemVehicle'] . "\" name=\"itemVehicle' + counter + '\"/></td>`; \n";
				echo "cols += `<td><input type=\"text\" class=\"form-control\" value=\"" . $item['itemDocNo'] . "\" name=\"itemDocNo' + counter + '\"/></td>`; \n";
				echo "cols += `<td><textarea  data-toggle=\"modal\" data-target=\"#itemDescriptionModal\" class=\"form-control\" rows=\"3\" name=\"itemDesc' + counter + '\">" . $item['itemDescription'] . "</textarea></td>`; \n";
                echo "cols += `<td><input type=\"text\" min=\"0\" max=\"20\" class=\"form-control\" value=\"" . $item['truck'] . "\" name=\"unitType' + counter + '\"/></td>`; \n";
				echo "cols += `<td><input type=\"text\" class=\"form-control number-currency\"  value=\"" . number_format($item['amount'], 2) . "\"  name=\"amount' + counter + '\"  step=\"0.01\"/></td>`; \n";

				echo "cols += `<td><button type=\"button\"  disabled class=\"ibtnDel btn btn-md btn-danger fa fa-minus\" ></button></td>`;\n";
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
			$('#quot_ponumber').val($('#ponumber').val());
			$('#quot_prefnumber').val($('#prefnumber').val());
			$('#pills-items-tab').removeClass('disabled');
			$('#pills-items-tab').trigger('click');
			//  $(this).remove();
		})


		$('#activate-step-3').on('click', function(e) {

		    var discount=$('#discount').val();
            if(typeof discount == 'undefined' || discount==null || discount=="" || isNaN(discount)){
                discount=0;
            }else{
            discount=Number(discount).toFixed(2);

            }
            $('#quot_discount').val(discount);
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
		});
		$('#pref').on('click', function(e) {
			if ($('#pref').is(":checked")) {
				$('#quot_pref').val("1");
				$('.prefstatus').css("display", "flex");
				$('.prefview').css("display", "table-row");
			} else {
				$('#quot_pref').val("");
				$('.prefstatus').css("display", "none");
				$('.prefview').css("display", "none");
			}
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
					address += "\n" + data['city'].toLowerCase().replace(/^\w/, c => c.toUpperCase()) + " " + data['postalCode'] + ",";
					address += "\n" + data['state'].toLowerCase().replace(/^\w/, c => c.toUpperCase()) + " "+data['country'].toLowerCase().replace(/^\w/, c => c.toUpperCase());


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

			$('#quot_quotationDate').prop('value', '<?php echo date('Y-m-d'); ?>');
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
				var itemDate = fields.eq(0).val();
				var itemJob = fields.eq(1).val();
				var itemVehicle = fields.eq(2).val();
				var itemDocNo = fields.eq(3).val();
				var itemDesc = fields.eq(4).val();
				var unitType = fields.eq(5).val();
				var amount = fields.eq(6).val();
                amount = amount.replace(/,/g, "");
				var price = parseFloat(amount);
				var outputDesc = itemDesc.replace(/\n/g, "<br />");

				totalAmount += parseFloat(price);
				$('#quot_quotationNo').prop('value', <?php time(); ?>);
				var newRow = $("<tr>");
				var cols = "";

				cols += '<td valign="top"><input type="text" hidden readonly name="itemDate' + counter + '" value="' + itemDate + '" />' + itemDate + '</td>';
				cols += '<td valign="top"><input type="text" hidden readonly name="itemJob' + counter + '" value="' + itemJob + '" />' + itemJob + '</td>';
				cols += '<td valign="top"><input type="text" hidden readonly name="itemVehicle' + counter + '" value="' + itemVehicle + '" />' + itemVehicle + '</td>';
				cols += '<td valign="top"><input type="text" hidden readonly name="itemDocNo' + counter + '" value="' + itemDocNo + '" />' + itemDocNo + '</td>';
				cols += '<td valign="top"><textarea  hidden readonly name="itemDesc' + counter + '" value="' + itemDesc + '" >' + itemDesc + '</textarea>' + outputDesc + '</td>';
                cols += '<td valign="top"><input type="text" hidden readonly name="unitType' + counter + '" value="' + unitType + '" />' + unitType + '</td>';
				cols += '<td valign="top"><input type="text" hidden readonly name="amount' + counter + '" value="RM ' + formatter.format(amount) + '" />RM ' + formatter.format(amount) + '</td>';
				//cols += '<td valign="top"><input type="text" hidden readonly name="unitType' + counter + '" value="' + itemQty + '" />' + itemQty + '</td>';


				newRow.append(cols);
				$("table.quotation-Content").append(newRow);
				counter++;
			});
            var discount=$("#discount").val();
            if(typeof discount == 'undefined' || discount==null || discount=="" || isNaN(discount)){
                discount=0;
            }
			$('.totalAmount').prop('value', 'RM ' + formatter.format(parseFloat(totalAmount) - parseFloat(discount)));
			$('#maxItemIndex').prop('value', counter);
		}


		$('#showPaymentModalDialog').click(function() {
            var discount=$("#discount").val();
            if(typeof discount == 'undefined' || discount==null || discount=="" || isNaN(discount)){
                discount=0;
            }
			$('#invoicePaymentModal').modal('toggle');
			var totalAmount = getTotalAmount() - discount;
			$('#totalAmount').val('RM ' + totalAmount.toFixed(2));

		});

		function getTotalAmount() {
			var totalAmount = 0;
			$('table.order-list > tbody  > tr').each(function() {

				var fields = $(this).find("input");
				var amount = fields.eq(6).val();
				var price = amount;
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
			step3Activation();

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
			var itemDate = $(str).find('td').eq(0).find('input').val();
			var itemJob = $(str).find('td').eq(1).find('input').val();
			var itemVehicle = $(str).find('td').eq(2).find('input').val();
			var itemDocNo = $(str).find('td').eq(3).find('input').val();
			//	var itemDescription=$(str).find('td').eq(4).find('textarea').val();
			var unitType = $(str).find('td').eq(5).find('input').val();
			var amount = $(str).find('td').eq(6).find('input').val();

			$('#inputRow_itemDate').prop('value', itemDate);
			$('#inputRow_itemJob').prop('value', itemJob);
			$('#inputRow_itemVehicle').prop('value', itemVehicle);
			$('#inputRow_itemDocNo').prop('value', itemDocNo);
			$('#inputRow_itemDescription').prop('value', itemDescription);
			$('#inputRow_unitType').prop('value', unitType);
			$('#inputRow_amount').prop('value', amount);

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
				//       value = value.replace(/[^0-9]/g, '');
				value = value.replace(/[^\d{1,3}(,\d{3})*(\.\d+)?$]/g, '$1,');
				//   return numberWithCommas(value);
				return value;
			});
		});
		$('input.number-currency').blur(function(event) {
			$(this).val(function(index, value) {
				value =   parseFloat(value).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
				return value;
			});
		});
		$('input.number-currency').focusin(function(event) {
			$(this).val(function(index, value) {
				value =   value.replace(/\,/g,'');
				return value;
			});
		});

		if ($(window).width() < 991) {
			$('#tab_logic input').attr('readonly', 'true');
			$('#tab_logic textarea').attr('readonly', 'true');
		}


		$('#inputItemDescriptionToTable').on("click", function() {

			var rowId = $("#inputRowId2").val();
			var itemDescription = $("#inputRow_itemDescription2").val();
			console.log(itemDescription);
			$("#" + rowId + "").find('td').eq(4).find('textarea').prop('value', itemDescription);


		});

		$('#inputValueToTable').on("click", function() {

			var rowId = $("#inputRowId").val();
			var itemDate = $("#inputRow_itemDate").val();
			var itemJob = $("#inputRow_itemJob").val();
			var itemVehicle = $("#inputRow_itemVehicle").val();
			var itemDocNo = $("#inputRow_itemDocNo").val();
			var itemDescription = $("#inputRow_itemDescription").val();
			var unitType = $("#inputRow_unitType").val();
			var amount = $("#inputRow_amount").val();

			$("#" + rowId + "").find('td').eq(0).find('input').prop('value', itemDate);
			$("#" + rowId + "").find('td').eq(1).find('input').prop('value', itemJob);
			$("#" + rowId + "").find('td').eq(2).find('input').prop('value', itemVehicle);
			$("#" + rowId + "").find('td').eq(3).find('input').prop('value', itemDocNo);
			$("#" + rowId + "").find('td').eq(4).find('textarea').prop('value', itemDescription);
			$("#" + rowId + "").find('td').eq(5).find('input').prop('value', unitType);
			$("#" + rowId + "").find('td').eq(6).find('input').prop('value', amount);
		});


		$("#addrow").on("click", function() {
			var newRow = $('<tr id="inputRow_' + counter + '"  onclick="inputForm(this)"> ');
			var cols = "";
            cols='<td><input type="date" name="itemDate' + counter + '" class="form-control" /></td>';
            cols+='<td><input type="text" name="itemJob' + counter + '" class="form-control" /></td>';
            cols+='<td><input type="text" name="itemVehicle' + counter + '" class="form-control" /></td>';
            cols+='<td><input type="text" name="itemDocNo' + counter + '" class="form-control" /></td>';
            cols+='<td><textarea data-toggle="modal" readonly data-target="#itemDescriptionModal" name="itemDesc' + counter + '" rows="3" class="form-control"></textarea></td>';
            cols+='<td><input type="text" name="unitType' + counter + '" class="form-control" /></td>';
            cols+='<td><input type="number" name="amount' + counter + '" min="0" max="20" class="form-control" /></td>';
            cols+='<td><button type="button" class="ibtnDel btn btn-md btn-danger fa fa-minus"></button></td>';
			//	cols += '<td>'+counter+'</td>';
		/*	cols += '<td><input type="text" class="form-control" name="itemName' + counter + '"/></td>';
			cols += '<td><textarea data-toggle="modal" readonly data-target="#itemDescriptionModal" class="form-control" rows="3" name="itemDesc' + counter + '"/></textarea></td>';
			cols += '<td><input type="text" class="form-control" id="inputUnitPrice' + counter + '" onkeyup="convertToCurrency(this)" name="unitCost' + counter + '"/></td>';
			cols += '<td><input type="number" min="0" max="20" class="form-control" name="qty' + counter + '"/></td>';
			//	cols += '<td><input type="text" class="form-control" name="price' + counter + '"/></td>';

			cols += '<td><button type="button"  class="ibtnDel btn btn-md btn-danger fa fa-minus" ></button></td>';

		 */
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
			if (currentRow > 0) {
				$(this).closest("tr").remove();
				if (currentRow == 1) {
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
						cols += '<td><input type="date" class="form-control" value="' + dataList[key].itemDate + '" name="itemDate' + counter + '"/></td>';
						cols += '<td><input type="text" class="form-control" value="' + dataList[key].itemJob + '" name="itemJob' + counter + '"/></td>';
						cols += '<td><input type="text" class="form-control" value="' + dataList[key].itemVehicle + '" name="itemVehicle' + counter + '"/></td>';
						cols += '<td><input type="text" class="form-control" value="' + dataList[key].itemDocNo + '" name="itemDocNo' + counter + '"/></td>';
						cols += '<td><textarea data-toggle="modal" readonly data-target="#itemDescriptionModal" class="form-control" rows="3" name="itemDesc' + counter + '">' + dataList[key].description + '</textarea></td>';
						cols += '<td><input type="text" class="form-control" value="' + dataList[key].unitType + '" name="unitType' + counter + '"/></td>';
                        cols += '<td><input type="text" class="form-control" id="inputUnitPrice' + counter + '" onkeyup="convertToCurrency(this)" value="' + dataList[key].amount + '" name="amount' + counter + '"/></td>';
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
		var datestr = day1 + "/" + month + "/" +  year;
		var todayDate = day + "/" + month + "/" +  year;
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
    #quot_prefnumber{width: 100%}
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
					<a href="#">Trip Invoice </a>
				</li>

				<li class="breadcrumb-item active ">Invoice</li>
				<li class="breadcrumb-item active">Create Trip Invoice</li>
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

							<input type="text" class="form-control required" placeholder="Recipient name " id="attention" name="attention" required />

							<div class="invalid-feedback">
								Please Enter Recipient Name
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
						<div class="col-sm-10 custom-checkbox-lg">
							<label for="pref" class="col-form-label col-form-label-lg">Required D/O </label>
							<input type="checkbox" id="pref" name="pref" value="1" class="form-control" />
						</div>
					</div>
					<div class="form-group row prefstatus" style="display:none">
						<label for="prefnumber" class="col-sm-2 col-form-label col-form-label-lg">D/O:</label>
						<div class="col-sm-10">

							<input type="text" class="form-control" placeholder="D/O" id="prefnumber" name="prefnumber" />

							<div class="invalid-feedback">
								Please Enter D/O
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
				<!--	<button class="btn" data-toggle='modal' data-target='#serviceModal'>Add Service</button> -->
					<!-- END Button Service -->
					<!-- TAB 2 CONTENT -->

					<br />
					<center>
						<table class="table order-list table-responsive  table-hover table-bordered" style="width:100%" id="tab_logic">
							<thead>
								<tr>
									<th class="text-center" class="th_date" style="color:#19334d;width:10%">
                                        Date:
									</th>
									<th class="text-center" class="th_job" style="color:#19334d;width:15%">
                                        Job No.:
									</th>
									<th class="text-center" class="th_vehicle" style="color:#19334d;width:15%">
                                        Vehicle No.:
									</th>
									<th class="text-center" class="th_vehicle" style="color:#19334d;width:15%">
                                        Document No.:
									</th>
									<th class="text-center" class="th_desc" style="color:#19334d;width:25%">
                                        Description:
									</th>
									<th class="text-center" class="th_type" style="color:#19334d;width:15%">
                                        Type:
									</th>
									<th class="text-center" class="th_amount" style="color:#19334d;width:15%">
                                        Amount:
									</th>
									<th class="text-center" class="th_qty" style="color:#19334d;width:5%">
										Remove:
									</th>
								</tr>
							</thead>
							<tbody>
								<tr id='inputRow_' onclick="inputForm(this)">

									<td>
										<input type="date" name='itemDate0' class="form-control" />
									</td>
                                    <td>
										<input type="text" name='itemJob0' class="form-control" />
									</td>
                                    <td>
										<input type="text" name='itemVehicle0' class="form-control" />
									</td>
                                    <td>
										<input type="text" name='itemDocNo0' class="form-control" />
									</td>
									<td>
										<textarea data-toggle="modal" readonly data-target="#itemDescriptionModal" name='itemDesc0' rows="3" class="form-control"></textarea>
									</td>
									<td>
										<input type="text" name='unitType0' class="form-control" />
									</td>
									<td>
										<input type="number" name='amount0'  class="form-control" step="0.01" />
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
									<td colspan="2" style="text-align: left;">
										<button type="button" class="btn btn-lg btn-block btn-success fa fa-plus " id="addrow">

										</button>
									</td>
                                    <td colspan=5 align="right" valign="middle">
                                        <input type="hidden" step="0.01" name="discount" id="discount" class="form-control" />
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

					<form id="invoiceCreationForm" action="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/phpfunctions/tripInvoice.php"; ?>" method="POST">
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
									echo "<button type=\"button\" class=\"btn btn-success\" id=\"activate-step-4\"   name=\"updateTripInvoice\" value=\"UPDATE\" >Update</button>\n";
									echo "<button type=\"submit\" class=\"btn btn-success\" id=\"hidden-submit\"   name=\"updateTripInvoice\" value=\"SAVE\" hidden='true' >Save</button>\n";
								} else {
									echo "<button type=\"button\" class=\"btn btn-success\" id=\"activate-step-4\"   name=\"createTripInvoice\" value=\"SAVE\" >Save</button>\n";
									echo "<button type=\"submit\" class=\"btn btn-success\" id=\"hidden-submit\"   name=\"createTripInvoice\" value=\"SAVE\" hidden='true' >Save</button>\n";
								}
							} else {
								echo "<button type=\"button\" class=\"btn btn-success\" id=\"activate-step-4\"   name=\"createTripInvoice\" value=\"SAVE\" >Save</button>\n";
								echo "<button type=\"submit\" class=\"btn btn-success\" id=\"hidden-submit\"   name=\"createTripInvoice\" value=\"SAVE\" hidden='true' >Save</button>\n";
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
										<td colspan="2" style="min-width:250px;width:40%; padding-top:15px;" align="left">
											<b>
												<?php echo $orgDetails['name']; ?>
											</b>
											<input hidden type="text" readonly size="50" id="quot_myOrgName" name="quot_myOrgName" value="<?php echo $orgDetails['name']; ?>" />

										</td>
                                        <td colspan="3" rowspan="4" class="myOrgLogo" style="width:60%;  text-align: right !important;">
                                            <img id="image" style="height:100px;max-width:350px" src=<?php echo "https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/resources/" . $_SESSION['orgLogo'] . ".png"; ?> alt="logo" />
                                        </td>



									</tr>

									<tr>
										<td class="myOrgAddress" colspan="2" style="text-align: left !important;">
											<?php echo $orgDetails['address1'] . ",";
											if ($orgDetails['address2'] != null) {
												echo "<br/>" . $orgDetails['address2'] . ",";
											}
											echo "<br/>" . $orgDetails['postalCode'] . " " . ucfirst(strtolower($orgDetails['city'])) . ",";
											echo "<br/>" . ucfirst(strtolower($orgDetails['state'])). " - Malaysia";
											?>
											<textarea hidden rows="4" cols="100" id="quot_myOrgAddress"
                                              name="quot_myOrgAddress"><?php echo $orgDetails['address1'] . ",";
                                                if ($orgDetails['address2'] != null) {
                                                    echo "\n" . $orgDetails['address2'] . ",";
                                                }
                                                echo "\n" . $orgDetails['postalCode'] . " " . ucfirst(strtolower($orgDetails['city'])) . ",";
                                                echo "\n" . ucfirst(strtolower($orgDetails['state'])). " - Malaysia";
                                                ?></textarea>
										</td>


									</tr>

									<tr>
										<td colspan="2" align="left">
											<?php
											if ($orgDetails['contact']) {
												echo "<b>Phone Number: </b>" . $orgDetails['contact'];
											}
											?>
											<input type="text" hidden value="<?php echo $orgDetails['contact']; ?>"
                                                   id="quot_orgPhone" name="quot_orgPhone" />
										</td>

									</tr>

									<tr>

										<td colspan="2" style="padding-bottom:15px;" align="left">
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
												<tr class="prefview" style="display:none;">
													<td class="meta-head">
														D/O TERM:
													</td>

													<td class="meta-body">
														<input type="text" readonly id="quot_prefnumber" name="quot_prefnumber" />

													</td>
												</tr>
												<tr>
													<td class="meta-head">
                                                        Date:
													</td>
						</div>
						<td class="meta-body">
                            <input type="date" value="" id="quot_quotationDate" name="quot_quotationDate" />
						</td>
						</tr>
                        <tr>
							<td class="meta-head">
                                Payment Term Date:
							</td>
							<td class="meta-body">
                                <input type="date" id="quot_dueDate" value="" class="dueDate" name="quot_dueDate" />
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
									<th style="width:10%;min-width:100px">
                                        Date:
									</th>
                                    <th style="width:15%;min-width:100px">
                                        Job No.:
									</th>
                                    <th style="width:15%;min-width:100px">
                                        Vehicle No.:
									</th>
                                    <th style="width:15%;min-width:100px">
                                        Document No.:
									</th>
									<th style="width:25%;min-width:200px">
										Description:
									</th>
									<th style="width:15%">
                                        Type:
									</th>
									<th style="width:10%">
                                        Amount:
									</th>

								</tr>
								<thead>
								<tbody>

								</tbody>
							<tfoot>
                            <tr>
                                <td colspan="3" class="blank">

                                </td>
                                <td colspan="2" class="meta-head">
                                    Discount:
                                </td>
                                <td colspan="2">
                                    RM <input type="text" readonly class="discount" id="quot_discount" name="quot_discount" style="width: calc(100% - 40px)" />
                                </td>

                            </tr>
								<tr>
									<td colspan="3" class="blank">

									</td>
                                    <td colspan="2" class="meta-head">
										Total:
									</td>
									<td colspan="2">


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
	<form action="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/phpfunctions/tripInvoice.php"; ?>" method="POST">
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
							<label for="orgName" class="col-sm-2 col-form-label col-form-label-md">Date</label>
							<div class="col-sm-10">
								<input type="date" class="form-control" placeholder="Item date" id="inputRow_itemDate" />
							</div>
						</div>
                        <div class="form-group row">
							<label for="orgName" class="col-sm-2 col-form-label col-form-label-md">Job No.</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" placeholder="Item job" id="inputRow_itemJob" />
							</div>
						</div>
                        <div class="form-group row">
							<label for="orgName" class="col-sm-2 col-form-label col-form-label-md">Vehicle No.</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" placeholder="Item vehicle no." id="inputRow_itemVehicle" />
							</div>
						</div>
                        <div class="form-group row">
							<label for="orgName" class="col-sm-2 col-form-label col-form-label-md">Document No.</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" placeholder="Item document no." id="inputRow_itemDocNo" />
							</div>
						</div>

						<div class="form-group row">
							<label for="orgName" class="col-sm-2 col-form-label col-form-label-md">DESCRIPTION</label>
							<div class="col-sm-10">
								<textarea class="form-control" placeholder="Item Description" rows="3" id="inputRow_itemDescription" onkeyup="if(this.value = this.value.replace(/\'/g, '')){document.getElementById('restricApos').style.display = 'none';}else{document.getElementById('restricApos').style.display = 'block';}"></textarea>
							</div>
						</div>

						<div class="form-group row">
							<label for="orgName" class="col-sm-2 col-form-label col-form-label-md">Type</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" placeholder="type" id="inputRow_unitType" />
							</div>
						</div>

						<div class="form-group row">
							<label for="orgName" class="col-sm-2 col-form-label col-form-label-md">Amount</label>
							<div class="col-sm-10">
								<input type="number" class="form-control" placeholder="Item amount" id="inputRow_amount" />
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
<?php /*
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
*/ ?>
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
	unset($_SESSION['createTripInvoiceByCheckBox']);

	?>
	<script>
		$(document).ready(function(){
			$('#activate-step-4').click(function() {
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