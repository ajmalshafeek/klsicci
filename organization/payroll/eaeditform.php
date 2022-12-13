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


	<!--<link rel='stylesheet' type='text/css' href='css/myQuotationStyle.css' />-->



	<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/importScripts.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/organization.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/eaForm.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/organizationUser.php");


	?>

</head>


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
	.nav-pills .nav-link.active, .nav-pills .show>.nav-link {
		color:#ffffff;
		background-color: #3c8dbc !important;
	}
	.nav-pills .nav-link{color: #3c8dbc;
		display: flex;
		margin: 5px;
		font-size: 15px;
		align-items: center;
		justify-content: center;
		height: 100%;
		background-color: #f2f2f2;
	}

	fieldset{
		margin-inline-start: 2px;
		margin-inline-end: 2px;
		padding-block-start: 0.35em;
		padding-inline-start: 0.75em;
		padding-inline-end: 0.75em;
		padding-block-end: 0.625em;
		min-inline-size: min-content;
		border-width: 2px;
		border-style: groove;
		border-color: threedface;
		border-image: initial;
	}
	legend{
		font-size: 20px;
		padding: 10px;
		width: auto;
	}
</style>

<?php
include $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/navMenu.php";
?>
<?php
	if(isset($_POST['eaIdToEdit'])){
		$data=fetchEaFormDetails($_POST['eaIdToEdit']);
	}
?>
<body class="fixed-nav ">
	<div class="content-wrapper">
		<div class="container-fluid">

			<!-- Breadcrumbs-->
			<ol class="breadcrumb col-md-12">
				<li class="breadcrumb-item ">
				Dashboard
				</li>
				<li class="breadcrumb-item ">Payroll</li>
				<li class="breadcrumb-item active">EA Form</li>

			</ol>
		</div>
		<?php if(isset($_SESSION['feedback'])){echo '<div class="container">'.$_SESSION['feedback'].'</div>';}
			if(isset($_SESSION['feedback'])){ unset($_SESSION['feedback']);}?>
		<div class="container">
			<ul class="nav nav-pills nav-justified" id="pills-tabContent" role="tab">
				<li class="nav-item " style="font-size:18px; ">
					<a class="nav-link active" id="pills-staff-tab" data-toggle="pill" href="#pills-staff" role="tab" aria-controls="pills-staff" aria-selected="true">
						Staff Details</a>
				</li>
				<li class="nav-item" style="font-size:18px;">
					<a class="nav-link " id="pills-income-tab" data-toggle="pill" href="#pills-income" role="tab" aria-controls="pills-income" aria-selected="false">
						Employee Income</a>
				</li>
				<li class="nav-item" style="font-size:18px;">
					<a class="nav-link" id="pills-pension-tab" data-toggle="pill" href="#pills-pension" role="tab" aria-controls="pills-pension" aria-selected="false">
						Pension & Others</a>
				</li>
				<li class="nav-item" style="font-size:18px;">
					<a class="nav-link" id="pills-deduction-tab" data-toggle="pill" href="#pills-deduction" role="tab" aria-controls="pills-deduction" aria-selected="false">
						Amount Deduction</a>
				</li>
				<li class="nav-item" style="font-size:18px;">
					<a class="nav-link" id="pills-epf-tab" data-toggle="pill" href="#pills-epf" role="tab" aria-controls="pills-epf" aria-selected="false">
						Contribution paid by employee to EPF</a>
				</li>
				<li class="nav-item" style="font-size:18px;">
					<a class="nav-link" id="pills-allowances-tab" data-toggle="pill" href="#pills-allowances" role="tab"  aria-controls="pills-allowances" aria-selected="false">
						Total Allowances</a>
				</li>
			</ul>

			<form id="eaCreationForm" action="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/phpfunctions/eaForm.php"; ?>" method="POST">
			<div class="tab-content" id="pills-tabContent">
				<!-- TAB 1 -->
				<div class="tab-pane fade show active" id="pills-staff" role="tabpanel" aria-labelledby="pills-staff-tab">
					<!-- TAB 1 CONTENT -->
					<br />

					<div class="form-group row">
						<label for="staffName" class="col-sm-2 col-form-label col-form-label-lg">Name:</label>
						<div class="col-sm-10">
							<select name="staffName" class="form-control" id="staffName" required="required">
								<?php if(isset($data['staffName'])){?>
								<option selected value="<?php echo $data['staffName'] ?>"><?php echo $data['staffName'] ?></option>
								<?php } else {?>
								<option selected disabled value="">--Select--</option>
								<?php } dropDownListOrgStaffListActiveNames(); ?>

							</select>



						</div>
					</div>

					<div class="form-group row">
						<label for="position" class="col-sm-2 col-form-label col-form-label-lg">Position:</label>
						<div class="col-sm-10">

							<input type="text" class="form-control required" placeholder="position name" id="position" name="position"  value="<?php if(isset($data['position'])){ echo $data['position']; } ?>" required />

							<div class="invalid-feedback">
								Please Enter Position Name
							</div>

						</div>
					</div>

					<div class="form-group row">
						<label for="staffNum" class="col-sm-2 col-form-label col-form-label-lg">Staff Number/Salary Number:</label>
						<div class="col-sm-10">

							<input type="text" class="form-control required" placeholder="staff number" id="staffNum" name="staffNum" value="<?php if(isset($data['staffNum'])){ echo $data['staffNum']; } ?>" required />

							<div class="invalid-feedback">
								Please Enter Staff Number
							</div>

						</div>
					</div>
						<div class="form-group row">
						<label for="icNum" class="col-sm-2 col-form-label col-form-label-lg">IC Number:</label>
						<div class="col-sm-10">

							<input type="text" class="form-control required" placeholder="IC number name" id="icNum" name="icNum" value="<?php if(isset($data['icNum'])){ echo $data['icNum']; } ?>" required />

							<div class="invalid-feedback">
								Please Enter IC Number Name
							</div>

						</div>
					</div>
                    <div class="form-group row">
                        <label for="passNum" class="col-sm-2 col-form-label col-form-label-lg">Passport Number:</label>
                        <div class="col-sm-10">

                            <input type="text" class="form-control required" placeholder="passport number" id="passNum" name="passNum" required value="<?php if(isset($data['passNum'])){ echo $data['passNum']; } ?>"/>

                            <div class="invalid-feedback">
                                Please Enter Passport Number
                            </div>

                        </div>
                    </div>
					<div class="form-group row">
						<label for="perkesoNum" class="col-sm-2 col-form-label col-form-label-lg">Perkeso Number:</label>
						<div class="col-sm-10">

							<input type="text" class="form-control required" placeholder="perkeso number" id="perkesoNum" name="perkesoNum" value="<?php if(isset($data['perkesoNum'])){ echo $data['perkesoNum']; } ?>" />

							<div class="invalid-feedback">
								Please Enter Perkeso Number
							</div>

						</div>
					</div>

					<div class="form-group row">

						<label for="kwspNum" class="col-sm-2 col-form-label col-form-label-lg">KWSP Number:</label>
						<div class="col-sm-10">
							<input type="text" class="form-control required" placeholder="KWSP number" id="kwspNum" name="kwspNum" value="<?php if(isset($data['kwspNum'])){ echo $data['kwspNum']; } ?>" />
						<div class="invalid-feedback">
								Please Enter KWSP Number
							</div>

						</div>
					</div>
					<div class="form-group row">

						<label for="childrenNum" class="col-sm-2 col-form-label col-form-label-lg">Number of children eligible for Tax:</label>
						<div class="col-sm-10">
							<select class="form-control required" placeholder="children eligible for tax" id="childrenNum" name="childrenNum" >
								<?php if(isset($data['childrenNum'])){?>
									<option selected value="<?php echo $data['childrenNum'] ?>"><?php echo $data['childrenNum'] ?></option>
								<?php } else {?>
									<option value="0" selected >0</option>
								<?php } ?>

								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
							</select>

							<div class="invalid-feedback">
								Please Enter Number of Children eligible for Tax
							</div>

						</div>
					</div>
					<fieldset><legend>If working less then a year</legend><div>
					<div class="form-group row">
						<label for="startDate" class="col-sm-2 col-form-label col-form-label-lg">Start Date</label>
						<div class="col-sm-10">
							<input type="date" class="form-control required" placeholder="Start Date" id="startDate" name="startDate" value="<?php if(isset($data['startDate'])){ echo $data['startDate']; } ?>"  />
							<div class="invalid-feedback">
								Please Enter Start Date
							</div>

						</div>
					</div>
					<div class="form-group row">

						<label for="endDate" class="col-sm-2 col-form-label col-form-label-lg">End Date</label>
						<div class="col-sm-10">
							<input type="date" class="form-control required" placeholder="End Date" id="endDate" name="endDate" value="<?php if(isset($data['endDate'])){ echo $data['endDate']; } ?>"  />
							<div class="invalid-feedback">
								Please Enter End Date
							</div>

						</div>
					</div>
					</div>
					</fieldset>
					<div class="form-group row mt-3">
						<label class="col-sm-2 col-form-label col-form-label-lg"></label>
						<div class="col-sm-10" style="text-align:right">
							<button id="" class="btn btn-primary btn-lg mt-3 activate-step-2" type='button'>Next</button>
						</div>
					</div>

					<!-- END OF TAB 1 CONTENT -->

				</div>
				<!-- END OF TAB 1 -->

				<!-- TAB 2 -->
				<div class="tab-pane fade " id="pills-income" role="tabpanel" aria-labelledby="pills-income-tab">
					<!-- TAB 2 CONTENT -->
					<br />
					<div class="form-group row">
						<label for="grossSalary" class="col-sm-4 col-form-label col-form-label-lg">1.(a) Gross Salary (including Overtime):</label>
						<div class="col-sm-8">

							<input type="number" class="form-control required" placeholder="gross salary (RM)" id="grossSalary" name="grossSalary" value="<?php if(isset($data['grossSalary'])){ echo $data['grossSalary']; } ?>"   />

							<div class="invalid-feedback">
								Please Enter Gross Salary
							</div>

						</div>
					</div>

					<div class="form-group row">
						<label for="fiBonus" class="col-sm-4 col-form-label col-form-label-lg">(B) Fi, Commisen or Bonus:</label>
						<div class="col-sm-8">

							<input type="number" class="form-control required" placeholder="Fi, Commisen or Bonus (RM)" id="fiBonus" name="fiBonus"  value="<?php if(isset($data['fiBonus'])){ echo $data['fiBonus']; } ?>"  />

							<div class="invalid-feedback">
								Please Enter Fi, Commisen or Bonus
							</div>

						</div>
					</div>
					<div class="form-group row">
						<label for="payFor" class="col-sm-4 col-form-label col-form-label-lg">(c) Allowance (Peyment for:</label>
						<div class="col-sm-4">
							<input type="text" class="form-control required" placeholder="Allowance for" id="payFor" name="payFor"   value="<?php if(isset($data['payFor'])){ echo $data['payFor']; } ?>" />
							<div class="invalid-feedback">
								Please Enter Allowance Payement for
							</div>

						</div>
						<div class="col-sm-4">
							<input type="number" class="form-control required" placeholder="Allowence (RM)" id="payAmount" name="payAmount"  value="<?php if(isset($data['payAmount'])){ echo $data['payAmount']; } ?>" />
							<div class="invalid-feedback">
								Please Enter Allowance
							</div>

						</div>
					</div>

					<div class="form-group row">
						<label for="taxByEmployer" class="col-sm-4 col-form-label col-form-label-lg">(d) Income Tax paid by employer:</label>
						<div class="col-sm-8">

							<input type="number" class="form-control required" placeholder="Tax Paid by Employer (RM)" id="taxByEmployer" name="taxByEmployer"  value="<?php if(isset($data['taxByEmployer'])){ echo $data['taxByEmployer']; } ?>" />

							<div class="invalid-feedback">
								Please Enter Income Tax paid by employer
							</div>

						</div>
					</div>

					<div class="form-group row">
						<label for="esos" class="col-sm-4 col-form-label col-form-label-lg">(e) ESOS.</label>
						<div class="col-sm-8">

							<input type="number" class="form-control required" placeholder="ESOS (RM)" id="esos" name="esos" value="<?php if(isset($data['esos'])){ echo $data['esos']; } ?>" />

							<div class="invalid-feedback">
								Please Enter ESOS
							</div>

						</div>
					</div>

					<fieldset><legend>(f) Reward</legend><div>
							<div class="form-group row">
								<label for="rewardFrom" class="col-sm-2 col-form-label col-form-label-lg">From</label>
								<div class="col-sm-10">
									<input type="text" class="form-control required" placeholder="Reward From" id="rewardFrom" name="rewardFrom"  value="<?php if(isset($data['rewardFrom'])){ echo $data['rewardFrom']; } ?>"  />
									<div class="invalid-feedback">
										Please Enter Reward From
									</div>

								</div>
							</div>
							<div class="form-group row">

								<label for="rewardTo" class="col-sm-2 col-form-label col-form-label-lg">to</label>
								<div class="col-sm-10">
									<input type="text" class="form-control required" placeholder="Reward To" id="rewardTo" name="rewardTo"  value="<?php if(isset($data['rewardTo'])){ echo $data['rewardTo']; } ?>"  />
									<div class="invalid-feedback">
										Please Enter Reward To
									</div>

								</div>
							</div>
						</div>
					</fieldset>


					<fieldset><legend>2. Details of Pending Payments</legend><div>
							<div class="form-group row">
								<label for="rewardFrom" class="col-sm-12 col-form-label col-form-label-lg">Type of income for the previous and current year</label>

							</div>
							<div class="form-group row">
								<label for="previousYear" class="col-sm-2 col-form-label col-form-label-lg">(a)</label>
								<div class="col-sm-10">
									<input type="number" class="form-control required" placeholder="Previous Year (RM)" id="previousYear" name="previousYear" value="<?php if(isset($data['previousYear'])){ echo $data['previousYear']; } ?>" />
									<div class="invalid-feedback">
										Please Enter Previous Year
									</div>

								</div>
							</div>
							<div class="form-group row">

								<label for="currentYear" class="col-sm-2 col-form-label col-form-label-lg">(b)</label>
								<div class="col-sm-10">
									<input type="number" class="form-control required" placeholder="Current Year (RM)" id="currentYear" name="currentYear"  value="<?php if(isset($data['currentYear'])){ echo $data['currentYear']; } ?>"  />
									<div class="invalid-feedback">
										Please Enter Current Year
									</div>

								</div>
							</div>
						</div>
					</fieldset>

					<div class="form-group row mt-3">
						<label class="col-sm-4 col-form-label col-form-label-lg">3. Benefits:</label>
						<div class="col-sm-4">
							<input type="text" class="form-control required" placeholder="State" id="benefitsState" name="benefitsState"  value="<?php if(isset($data['benefitsState'])){ echo $data['benefitsState']; } ?>"  />
							<div class="invalid-feedback">
								Please Enter State
							</div>

						</div>
						<div class="col-sm-4">
							<input type="number" class="form-control required" placeholder="Amount (RM)" id="benefitsAmount" name="benefitsAmount"  value="<?php if(isset($data['benefitsAmount'])){ echo $data['benefitsAmount']; } ?>"  />
							<div class="invalid-feedback">
								Please Enter Amount
							</div>

						</div>
					</div>

					<div class="form-group row">
						<label class="col-sm-4 col-form-label col-form-label-lg">4. Value of residence:</label>
						<div class="col-sm-4">
							<input type="text" class="form-control required" placeholder="Address" id="resiAdd" name="resiAdd"  value="<?php if(isset($data['resiAdd'])){ echo $data['resiAdd']; } ?>"   />
							<div class="invalid-feedback">
								Please Enter Address
							</div>

						</div>
						<div class="col-sm-4">
							<input type="number" class="form-control required" placeholder="Value (RM)" id="resiValue" name="resiValue" value="<?php if(isset($data['resiValue'])){ echo $data['resiValue']; } ?>" />
							<div class="invalid-feedback">
								Please Enter Value
							</div>

						</div>
					</div>

					<div class="form-group row">
						<label for="refundKWSP" class="col-sm-4 col-form-label col-form-label-lg">5. Refunds from KWSP/ Pension that is not approved</label>
						<div class="col-sm-8">

							<input type="number" class="form-control required" placeholder="Refund from KWSP(RM)" id="refundKWSP" name="refundKWSP" value="<?php if(isset($data['refundKWSP'])){ echo $data['refundKWSP']; } ?>"  />

							<div class="invalid-feedback">
								Please Enter Refund KWSP
							</div>

						</div>
					</div>

					<div class="form-group row">
						<label for="reparationJob" class="col-sm-4 col-form-label col-form-label-lg">6. Reparation for job loss</label>
						<div class="col-sm-8">

							<input type="number" class="form-control required" placeholder="Reparation Job(RM)" id="reparationJob" name="reparationJob" value="<?php if(isset($data['reparationJob'])){ echo $data['reparationJob']; } ?>"  />

							<div class="invalid-feedback">
								Please Enter Reparation for job loss
							</div>

						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label col-form-label-lg mt-2"></label>
						<div class="col-sm-10" style="text-align:right">
							<button id="" class="btn btn-primary  btn-lg activate-step-1" type='button'>PREVIOUS</button>
							<button id="" class="btn btn-primary  btn-lg activate-step-3" type='button'>NEXT</button>
						</div>
					</div>

					<!-- END OF TAB 2 CONTENT -->

				</div>
				<!-- END OF TAB 2 -->

				<!-- TAB 3 -->
				<div class="tab-pane fade " id="pills-pension" role="tabpanel" aria-labelledby="pills-pension-tab">
<br />
					<div class="form-group row">
						<label for="pensionAmount" class="col-sm-4 col-form-label col-form-label-lg">1. Pension:</label>
						<div class="col-sm-8">

							<input type="number" class="form-control required" placeholder="Pension (RM)" id="pensionAmount" name="pensionAmount" value="<?php if(isset($data['pensionAmount'])){ echo $data['pensionAmount']; } ?>"  />

							<div class="invalid-feedback">
								Please Enter Pension
							</div>

						</div>
					</div>

					<div class="form-group row">
						<label for="annuitAmount" class="col-sm-4 col-form-label col-form-label-lg">2. Annuities or Other Payments:</label>
						<div class="col-sm-8">

							<input type="number" class="form-control required" placeholder="Amount (RM)" id="annuitAmount" name="annuitAmount" value="<?php if(isset($data['annuitAmount'])){ echo $data['annuitAmount']; } ?>"  />

							<div class="invalid-feedback">
								Please Enter Annuities or Other Payments
							</div>

						</div>
					</div>

					<div class="form-group row">
						<label for="totalPension" class="col-sm-4 col-form-label col-form-label-lg">Total:</label>
						<div class="col-sm-8">

							<input type="number" class="form-control required" placeholder="Amount (RM)" id="totalPension" name="totalPension" value="<?php if(isset($data['totalPension'])){ echo $data['totalPension']; } ?>"  />

							<div class="invalid-feedback">
								Please Enter Total
							</div>

						</div>
					</div>

					<div class="form-group row">
						<label class="col-sm-2 col-form-label col-form-label-lg mt-2"></label>
						<div class="col-sm-10" style="text-align:right">
							<button id="" class="btn btn-primary  btn-lg mt-3 activate-step-2" type='button'>PREVIOUS</button>
							<button id="" class="btn btn-primary btn-lg mt-3 activate-step-4" type='button'>NEXT</button>
						</div>
					</div>
				</div>
				<!-- END OF TAB 3 CONTENT -->

				<!-- TAB 4 -->
				<div class="tab-pane fade " id="pills-deduction" role="tabpanel" aria-labelledby="pills-deduction-tab">
					<br />
					<div class="form-group row">
						<label for="pcbAmount" class="col-sm-4 col-form-label col-form-label-lg">1. PCB(Paid To LHDNM):</label>
						<div class="col-sm-8">

							<input type="number" class="form-control required" placeholder="Amount (RM)" id="pcbAmount" name="pcbAmount" value="<?php if(isset($data['pcbAmount'])){ echo $data['pcbAmount']; } ?>" />

							<div class="invalid-feedback">
								Please Enter Amount
							</div>

						</div>
					</div>

					<div class="form-group row">
						<label for="deductionCP" class="col-sm-4 col-form-label col-form-label-lg">2. Deduction Instruction CP 38:</label>
						<div class="col-sm-8">

							<input type="number" class="form-control required" placeholder="Amount (RM)" id="deductionCP" name="deductionCP" value="<?php if(isset($data['deductionCP'])){ echo $data['deductionCP']; } ?>"  />

							<div class="invalid-feedback">
								Please Enter Amount
							</div>

						</div>
					</div>

					<div class="form-group row">
						<label for="zakatAmount" class="col-sm-4 col-form-label col-form-label-lg">3. Zakat paid via salary deduction:</label>
						<div class="col-sm-8">

							<input type="number" class="form-control required" placeholder="Amount (RM)" id="zakatAmount" name="zakatAmount" value="<?php if(isset($data['zakatAmount'])){ echo $data['zakatAmount']; } ?>" />

							<div class="invalid-feedback">
								Please Enter Amount
							</div>

						</div>
					</div>

					<fieldset><legend>4. Total Deduction claim by employee through TP1 Form:</legend><div>
							<div class="form-group row">
								<label for="deductionTP1a" class="col-sm-4 col-form-label col-form-label-lg">a) Pelepasan</label>
								<div class="col-sm-8">

									<input type="number" class="form-control required" placeholder="Amount (RM)" id="deductionTP1a" name="deductionTP1a" value="<?php if(isset($data['deductionTP1a'])){ echo $data['deductionTP1a']; } ?>"  />

									<div class="invalid-feedback">
										Please Enter Amount
									</div>

								</div>
							</div>
							<div class="form-group row">
								<label for="deductionTP1b" class="col-sm-4 col-form-label col-form-label-lg">b) Zakat yang dibayar melalui potongan gaji bulanan</label>
								<div class="col-sm-8">

									<input type="number" class="form-control required" placeholder="Amount (RM)" id="deductionTP1b" name="deductionTP1b" value="<?php if(isset($data['deductionTP1b'])){ echo $data['deductionTP1b']; } ?>" />

									<div class="invalid-feedback">
										Please Enter Amount
									</div>

								</div>
							</div>
						</div>
					</fieldset>

					<div class="form-group row mt-3">
						<label for="jumlahPele" class="col-sm-4 col-form-label col-form-label-lg">6. Jumlah Pelepasan Bagi anak yang layak:</label>
						<div class="col-sm-8">

							<input type="number" class="form-control required" placeholder="Amount (RM)" id="jumlahPele" name="jumlahPele" value="<?php if(isset($data['jumlahPele'])){ echo $data['jumlahPele']; } ?>" />

							<div class="invalid-feedback">
								Please Enter Amount
							</div>

						</div>
					</div>

					<div class="form-group row">
						<label class="col-sm-2 col-form-label col-form-label-lg mt-2"></label>
						<div class="col-sm-10" style="text-align:right">
							<button id="" class="btn btn-primary btn-lg mt-3 activate-step-3" type='button'>PREVIOUS</button>
							<button id="" class="btn btn-primary btn-lg mt-3 activate-step-5" type='button'>NEXT</button>
						</div>
					</div>
				</div>
				<!-- END OF TAB 4 CONTENT -->

				<!-- TAB 5 -->
				<div class="tab-pane fade " id="pills-epf" role="tabpanel" aria-labelledby="pills-epf-tab">

					<br />
					<div class="form-group row">
						<label for="fundName" class="col-sm-2 col-form-label col-form-label-lg">1. Fund Name:</label>
						<div class="col-sm-10">

							<input type="text" class="form-control required" placeholder="fund name" id="fundName" name="fundName" value="<?php if(isset($data['fundName'])){ echo $data['fundName']; } ?>" />

							<div class="invalid-feedback">
								Please Enter Fund Name
							</div>

						</div>
					</div>


					<div class="form-group row">
						<label for="contribution" class="col-sm-4 col-form-label col-form-label-lg">Mandatory contribution to be paid (State employee share only):</label>
						<div class="col-sm-8">

							<input type="number" class="form-control required" placeholder="Amount (RM)" id="contribution" name="contribution" value="<?php if(isset($data['contribution'])){ echo $data['contribution']; } ?>" />

							<div class="invalid-feedback">
								Please Enter Amount
							</div>

						</div>
					</div>
					<div class="form-group row">
						<label for="perkesoPaidAmount" class="col-sm-4 col-form-label col-form-label-lg">PERKESO: Amount need to be paid (State employee share only):</label>
						<div class="col-sm-8">

							<input type="number" class="form-control required" placeholder="Amount (RM)" id="perkesoPaidAmount" name="perkesoPaidAmount" value="<?php if(isset($data['perkesoPaidAmount'])){ echo $data['perkesoPaidAmount']; } ?>" />

							<div class="invalid-feedback">
								Please Enter Amount
							</div>

						</div>
					</div>

					<div class="form-group row">
						<label class="col-sm-2 col-form-label col-form-label-lg mt-2"></label>
						<div class="col-sm-10" style="text-align:right">
							<button id="" class="btn btn-primary btn-lg mt-3 activate-step-4" type='button'>PREVIOUS</button>
							<button id="" class="btn btn-primary btn-lg mt-3 activate-step-6" type='button'>NEXT</button>
						</div>
					</div>
				</div>
				<!-- END OF TAB 5 CONTENT -->

				<!-- TAB 6 -->
				<div class="tab-pane fade " id="pills-allowances" role="tabpanel" aria-labelledby="pills-allowances-tab">
					<br />
					<div class="form-group row">
						<label for="totalAllowances" class="col-sm-4 col-form-label col-form-label-lg">Total Allowences/ Perquisites/ Tax-exempt benefits:</label>
						<div class="col-sm-8">

							<input type="number" class="form-control required" placeholder="Amount (RM)" id="totalAllowances" name="totalAllowances" value="<?php if(isset($data['totalAllowances'])){ echo $data['totalAllowances']; } ?>" onchange="checkValue(this)" step="0.01" />

							<div class="invalid-feedback">
								Please Enter Amount
							</div>

						</div>
					</div>

					<div class="form-group row">
						<label for="officerName" class="col-sm-4 col-form-label col-form-label-lg">Officer Name:</label>
						<div class="col-sm-8">
							<select name="officerName" class="form-control" id="officerName" required="required">
								<?php if(isset($data['officerName'])){ echo '<option selected value="'.$data['officerName'].'" >'.$data['officerName'].'</option>'; } else {?>
								<option selected disabled value="">--Select--</option> <?php } ?>

								<?php dropDownListEaOfficerList(); ?>

							</select>
							<?php //	<input type="text" class="form-control required" placeholder="Officer Name" id="officerName" name="officerName" /> ?>

							<div class="invalid-feedback">
								Please Enter Officer Name
							</div>

						</div>
					</div>

					<div class="form-group row">
						<label for="officerPosition" class="col-sm-4 col-form-label col-form-label-lg">Position:</label>
						<div class="col-sm-8">

							<input type="text" class="form-control required" placeholder="Officer Position" id="officerPosition" name="officerPosition" value="<?php if(isset($data['officerPosition'])){ echo $data['officerPosition']; } ?>"  />

							<div class="invalid-feedback">
								Please Enter Officer Position
							</div>

						</div>
					</div>

					<div class="form-group row">
						<label for="employerNameAdd" class="col-sm-4 col-form-label col-form-label-lg">Employer Name and Address:</label>
						<div class="col-sm-8">

							<input type="text" class="form-control required" placeholder="Employer Name and Address" id="employerNameAdd" name="employerNameAdd" value="<?php if(isset($data['employerNameAdd'])){ echo $data['employerNameAdd']; } ?>" />

							<div class="invalid-feedback">
								Please Enter Employer Name and Address
							</div>

						</div>
					</div>

					<div class="form-group row">
						<label for="employerNum" class="col-sm-4 col-form-label col-form-label-lg">Employer Telephone Num:</label>
						<div class="col-sm-8">

							<input type="text" class="form-control required" placeholder="Employer Telephone Number" id="employerNum" name="employerNum" value="<?php if(isset($data['employerNum'])){ echo $data['employerNum']; } ?>" />

							<div class="invalid-feedback">
								Please Enter Employer Telephone Num
							</div>

						</div>
					</div>

					<div class="form-group row">
						<label for="date" class="col-sm-4 col-form-label col-form-label-lg">Date:</label>
						<div class="col-sm-8">

							<input type="Date" class="form-control required" placeholder="Date" id="date" name="date" value="<?php if(isset($data['date'])){ echo $data['date']; } ?>" />

							<div class="invalid-feedback">
								Please Enter date
							</div>

						</div>
					</div>

					<div class="form-group row">
						<label for="date" class="col-sm-4 col-form-label col-form-label-lg">Year:</label>
						<div class="col-sm-8">

							<select class="form-control required" placeholder="year" id="year" name="year" value="<?php if(isset($data['year'])){ echo $data['year']; } ?>" />
							<?php
							$date=(int)Date("Y");
								for($i=9;$i>-1;$i--){
									echo '<option value="'.$date.'">'.$date.'</option>>';
									$date--;
								}
							?>
							</select>
							<div class="invalid-feedback">
								Please Enter year
							</div>

						</div>
					</div>

					<div class="form-group row">
						<label class="col-sm-2 col-form-label col-form-label-lg mt-2"></label>
						<div class="col-sm-10" style="text-align:right">
							<input type="hidden" class="form-control" id="eaFormIdEdit" name="eaFormIdEdit" value="<?php if(isset($data['id'])){ echo $data['id']; } ?>" />
							<button id="" class="btn btn-primary  btn-lg mt-3 activate-step-5" type='button'>PREVIOUS</button>
							<button type="submit" id="" class="btn btn-primary btn-lg mt-3" name="updateEAForm" type='button' value="1">DRAFT</button>
							<button type="submit" id="" class="btn btn-primary btn-lg mt-3" name="updateEAForm" type='button' value="0">SUBMIT</button>
						</div>
					</div>
				</div>
				<!-- END OF TAB 6 CONTENT -->
	</div>
				</form>

	</div>

	<div>
		<div class="footer">
			<p>Powered by JSoft Solution Sdn. Bhd</p>
		</div>
	</div>


	<a class="scroll-to-top rounded" href="#page-top">
		<i class="fa fa-angle-up"></i>
	</a>


	</div>

	<script>
		/*
		var currentTab = 0; // Current tab is set to be the first tab (0)
		showTab(currentTab); // Display the current tab
alert(currentTab);
		function showTab(n) {
			// This function will display the specified tab of the form...
			var x = document.getElementsByClassName("tab-pane");
			x[n].style.display = "block";
			//... and fix the Previous/Next buttons:
			if (n == 0) {
			//	document.getElementById("prevBtn").style.display = "none";
			} else {
			//	document.getElementById("prevBtn").style.display = "inline";
			}
			if (n == (x.length - 1)) {
				//document.getElementById("nextBtn").innerHTML = "Submit";
			} else {
			//	document.getElementById("nextBtn").innerHTML = "Next";
			}//
			//... and run a function that will display the correct step indicator:
			fixStepIndicator(n)
		}

		function nextPrev(n) {
			// This function will figure out which tab to display
			var x = document.getElementsByClassName("tab-pane");
			// Exit the function if any field in the current tab is invalid:
			if (n == 1 && !validateForm()) return false;
			// Hide the current tab:
			x[currentTab].style.display = "none";
			// Increase or decrease the current tab by 1:
			currentTab = currentTab + n;
			// if you have reached the end of the form...
			if (currentTab >= x.length) {
				// ... the form gets submitted:
				document.getElementById("regForm").submit();
				return false;
			}
			// Otherwise, display the correct tab:
			showTab(currentTab);
		}

		function validateForm() {
			// This function deals with validation of the form fields
			var x, y, i, valid = true;
			x = document.getElementsByClassName("tab-pane");
			y = x[currentTab].getElementsByTagName("input");
			// A loop that checks every input field in the current tab:
			for (i = 0; i < y.length; i++) {
				// If a field is empty...
				if (y[i].value == "") {
					// add an "invalid" class to the field:
					y[i].className += " invalid";
					// and set the current valid status to false
					valid = false;
				}
			}
			// If the valid status is true, mark the step as finished and valid:
			if (valid) {
				document.getElementsByClassName("step")[currentTab].className += " finish";
			}
			return valid; // return the valid status
		}

		function fixStepIndicator(n) {
			// This function removes the "active" class of all steps...
			var i, x = document.getElementsByClassName("nav-item");
			for (i = 0; i < x.length; i++) {
				x[i].className = x[i].className.replace(" active", "");
			}
			//... and adds the "active" class on the current step:
			x[n].className += " active";
			a.onclick = clickFn;
		}
		function clickFn(){
			alert("test");
		}

		*/
		$(document).ready(function() {

			$('.activate-step-1').on('click', function (e) {
				$('#pills-staff-tab').trigger('click');
			});

			$('.activate-step-2').on('click', function (e) {
				$('#pills-income-tab').trigger('click');
			});

			$('.activate-step-3').on('click', function (e) {
				$('#pills-pension-tab').trigger('click');
			});

			$('.activate-step-4').on('click', function (e) {
				$('#pills-deduction-tab').trigger('click');
			});

			$('.activate-step-5').on('click', function (e) {
				$('#pills-epf-tab').trigger('click');
			});

			$('.activate-step-6').on('click', function (e) {
				$('#pills-allowances-tab').trigger('click');
			});
		$(function() {
			$('#staffName').change(function () {
				var selected = $(this).find('option:selected');
				var staffId = selected.data('id');
				$.ajax({
					type  : 'GET',
					url  : '../../phpfunctions/organizationUser.php?',
					data : {staffDetail:staffId},
					success: function (res) {
						staffDetails = JSON.parse(res);
						console.log("Status : " + staffDetails.staffId );
						$('#staffNum').val(staffDetails.staffId);
                        $('#position').val(staffDetails.staffDesignation);
                        $('#icNum').val(staffDetails.ic);
                        $('#passNum').val(staffDetails.passport);
                        $('#perkesoNum').val(staffDetails.perkeso);
                        $('#kwspNum').val(staffDetails.kwsp);


					}
				});
			});
			$('#officerName').change(function () {
				var selected = $(this).find('option:selected');
				var officerId = selected.data('id');
				$.ajax({
					type  : 'GET',
					url  : '../../phpfunctions/eaForm.php?',
					data : {getOfficerDetailWithId:officerId},
					success: function (res) {
						officerDetail = JSON.parse(res);
						console.log("Status : " + res );
						$('#officerPosition').val(officerDetail.position);
						$('#employerNameAdd').val(officerDetail.employerAdd);
						$('#employerNum').val(officerDetail.emloyerTel);


					}
				});
			});
		});
            $('#totalAllowances').keypress(function(event) {
                if ((event.which != 46 || $(this).val().indexOf('.') != -1) &&
                    ((event.which < 48 || event.which > 57) &&
                        (event.which != 0 && event.which != 8))) {
                    event.preventDefault();
                }
                var text = $(this).val();
                if ((text.indexOf('.') != -1) &&
                    (text.substring(text.indexOf('.')).length > 2) &&
                    (event.which != 0 && event.which != 8) &&
                    ($(this)[0].selectionStart >= text.length - 2)) {
                    event.preventDefault();
                }
            });



		});
        function checkValue(id){
            var x = parseFloat(id.value);
            id.value=x.toFixed(2);
        }
	</script>
</body>

</html>