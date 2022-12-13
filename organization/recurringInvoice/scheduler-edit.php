<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
  session_name($config['sessionName']);
  session_start();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />
	<?php
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>
	  <link rel="stylesheet" type="text/css" href="./schuduling_files/myQuotationStyle.css">
    <script src="./schuduling_files/jquery.min.js.download"></script>
    <script src="./schuduling_files/bootstrap.bundle.min.js.download"></script>
    <script src="./schuduling_files/jquery.easing.min.js.download"></script>
    <script src="./schuduling_files/jquery.min.js(1).download"></script>
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
        <li class="breadcrumb-item ">Recurring Invoice</li>
        <li class="breadcrumb-item active">Edit Recurring Invoice</li>
      </ol>
    </div>
		<div class="container">
		<?php


		include_once ("db.php");
    $id = $_POST['invoice_scheduler_id'];
		$schr= "SELECT *  FROM `invoice_scheduler` WHERE id=$id";
		$scheduler = mysqli_query($con,$schr) or die(mysqli_error($con));
		$schrl=$scheduler->fetch_assoc();
		$schr1= "SELECT *  FROM `invoice_scheduler_sub` WHERE invoice_scheduler_id=$id";
		$scheduler_sub_list = mysqli_query($con,$schr1) or die(mysqli_error($con));
		?>
			<form name="form1" method="post" action="scheduler-insert.php"><br>
				<input type="hidden" name="id" value="<?php echo $schrl['id']?>">
				<div id="sections">
				<?php $i=0;
				while ($rows = $scheduler_sub_list->fetch_assoc()) {?>

					<div class="section">
						<div class="form-group row" >
							<div class="col-md-5" style="float:left">
								<select name="clientCompanyId[]"  class="form-control" id="clientCompanyId<?php if($i!=0){echo $i;} ?>" data-value="<?php echo $i; ?>" onchange="getInvoice(this)">
									<option selected="" disabled="" value="">--Select Client--</option>
									<?php
									$str= "SELECT *  FROM `clientcompany` WHERE 1=1 order by  name ASC";
									$client_list = mysqli_query($con,$str) or die(mysqli_error($con));
									while ($row = $client_list->fetch_assoc()) {
									?>
										<option value="<?php echo $row['id']; ?>" <?php if($row['id']==$rows['client_id']){echo 'selected';}?>><?php echo $row['name']; ?></option>
									<?php } ?>
								</select>

							</div>
							<div class="col-md-5" style="float:left">
								<select name="clientInvoiceId[]"  class="form-control" id="clientInvoiceId<?php if($i!=0){echo $i;} ?>">
									<option selected="" disabled="" value="">--Select Invoice--</option>
									<?php
									$str1= "SELECT *  FROM `invoice` WHERE 1=1 order by  invoiceNo ASC";
									$client_invoice = mysqli_query($con,$str1) or die(mysqli_error($con));
									while ($row = $client_invoice->fetch_assoc()) {
										if($row['customerId']==$rows['client_id']){
									?>
									<option value="<?php echo $row['id']; ?>" <?php if($row['id']==$rows['invoice_id']){echo 'selected';}?> ><?php echo $row['invoiceNo']; ?></option>
										<?php
										$i++;
										}
										} ?>
								</select>
							</div>
							<div class="col-md-2" style="float:left">
									<button  class="btn btn-danger btn-icon remove" name="" id="">-<i class="entypo-cancel"></i></button>
							</div>

						</div>
				</div>
				<?php } ?>
			</div>
      <div class="section">
        <div class="form-group row">
          <div class="col-md-10"></div>
          <div class="col-md-2">
            <button class="btn btn-green btn-icon addsection" name="" id="">+<i class="entypo-check"></i></button>&nbsp;&nbsp;
          </div>
        </div>
      </div>
      <div class="section">
        <div class="form-group row">
            <label for="schedule_date" class="col-sm-2 col-form-label col-form-label-lg">Set The Date: </label>
            <div class="col-sm-8">
              <input type="date" class="form-control required" id="schedule_date" name="schedule_date" required="" value="<?php echo $schrl['schedule_date']?>">
            </div>
        </div>
      </div>

			<div class="section">
        <div class="form-gorup row">
          <div class="col-md-10"></div>
          <div class="col-md-2">
            <button type="submit" class="btn btn-success" id="activate-step-4" name="createInvoice" value="SAVE">Save</button>
          </div>
        </div>
      </div>
	</form>
</div>

<script>
	var template = $('#sections .section:first').clone();
	var sectionsCount = 1;
	$('body').on('click', '.addsection', function() {
		sectionsCount++;
		var section = template.clone().find(':input').each(function(){

			//set id to store the updated section number
			var newId = this.id + sectionsCount;
			//update for label
			$(this).prev().attr('for', newId);
			//update id
			this.id = newId;
			this.setAttribute('data-value',sectionsCount);
			this.value ="";

		}).end()
		//inject new section
		.appendTo('#sections');
		return false;
	});
	//remove section
		$('#sections').on('click', '.remove', function() {
			//fade out section
			$(this).parent().fadeOut(300, function(){
				//remove parent element (main section)
				$(this).parent().parent().empty();
				return false;
			});
			return false;
		});
</script>
<script>
function getInvoice(t)

{

	var id = t.id;
	var value = t.value;
	var index =t.getAttribute('data-value');


$.ajax({
      url:'get-invoice.php',
      type: 'post',
      data: {'client_id': value},
      success: function(data, status) {
		var option='';
		document.getElementById("clientInvoiceId"+index).innerHTML = data;
      },
    });
}
</script>
				<div class="footer">
				<p>Powered by JSoft Solution Sdn. Bhd</p>
				</div>


		<a class="scroll-to-top rounded" href="https://jcloud.my/testing/organization/invoice/createInvoice.php#page-top">
			<i class="fa fa-angle-up"></i>
		</a>

</body>
s</html>
