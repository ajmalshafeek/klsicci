<?php
$config=parse_ini_file(__DIR__."/../../../jsheetconfig.ini");
if(!isset($_SESSION))
{
  session_name($config['sessionName']);
  session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientCompany.php");

?>
<!DOCTYPE HTML>

<html  xmlns="https://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">


    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />

    <link rel='stylesheet' type='text/css' href='css/myQuotationStyle.css' />
    <?php
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    
    
    ?>
    <!-- Data Table Import -->
    <link rel="stylesheet" type="text/css" href="../../../adminTheme/dataTable15012020/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="../../../adminTheme/dataTable15012020/jquery.dataTables.js"></script>

    <script type="text/javascript">
    $(document).ready( function () {
      $('#invoicereport').DataTable({
        searching: false,
        "ordering": false,
        "pageLength": 21,
        "bLengthChange": false,
        "bInfo": false,
        "language": {
          "paginate": {
            "previous": "Previous Month",
            "next": "Next Month"
          }
        }
      });
    } );

    function changeInputType(){
      document.getElementsByName("dateMonth")[0].value = null;
      document.getElementsByName("dateYear")[0].value = null;


      var val = document.getElementById("selectDateType").value;
      if (val == 0) {
        document.getElementById("inputMonth").style.display = "block";
        document.getElementById("inputYear").style.display = "none";

        document.getElementById("inputMonthForm").required = true;
        document.getElementById("inputYearForm").required = false;

      }else {
        document.getElementById("inputMonth").style.display = "none";
        document.getElementById("inputYear").style.display = "block";

        document.getElementById("inputMonthForm").required = false;
        document.getElementById("inputYearForm").required = true;


        var i = 10;
        var d = new Date();
        var year = d.getFullYear();

        for(i=1;i<=10;i++){
          document.getElementById("year"+i).value = year;
          document.getElementById("year"+i).innerHTML = year;
          year--;
        }
      }
    }
    </script>
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
          <a href="../../../home.php">Dashboard</a>
        </li>
        <li class="breadcrumb-item ">Invoice Report</li>
      </ol>
    </div>
    <div class="container">
      <form method="POST" action="../../../phpfunctions/invoicereport.php" class="needs-validation">
      <?php
        if (isset($_SESSION['feedback'])) {
            echo $_SESSION['feedback'];
            unset($_SESSION['feedback']);
        }
      ?>

          <div class="form-group row">
						<label for="clientName" class="col-sm-2 col-form-label col-form-label-lg">Name:</label>
						<div class="col-sm-7">
							<select name="clientCompanyId" onChange="dropDownClientChange(this);" class="form-control" id="clientCompanyId">
								<option selected disabled value="">--Select--</option>
                                <option value="0">All</option>
								<?php

								$clientCompanyList = dropDownOptionListOrganizationClientCompanyActive();
								echo $clientCompanyList;
								?>
							</select>

						</div>
                    <div class="col-sm-3">
                    <select name="invoiceStatus" class="form-control" id="invoiceStatus">
                        <option selected disabled value="">--Select--</option>
                        <option value="-1">ALL</option>
                        <option value="1">Unpaid</option>
                        <option value="0">Paid</option>
                    </select>

                    </div>
    </div>
          <div class="form-group row">
          <div class="col-md-8">
            <label>Report Format</label>
            <select id="selectDateType" class="form-control" onchange="changeInputType()" name="timeCategory">
              <option value="0">Monthly</option>
              <option value="1">Yearly</option>
            </select>
          </div>

          <div class="col-md-4">
            <div id="inputMonth">
              <label id="label">By Month</label>
              <input id="inputMonthForm" class="form-control" type="month" name="dateMonth" required>
            </div>
            <div id="inputYear" style="display:none">
              <div class="form-group row">
                <label id="label">By Year</label>
                <select id="inputYearForm" class="form-control" name="dateYear">
                  <option id="year1"></option>
                  <option id="year2"></option>
                  <option id="year3"></option>
                  <option id="year4"></option>
                  <option id="year5"></option>
                  <option id="year6"></option>
                  <option id="year7"></option>
                  <option id="year8"></option>
                  <option id="year9"></option>
                  <option id="year10"></option>
                </select>
              </div>

            </div>
          </div>
      </div>
      <div class="form-group row">
        <div class="col-md-12">
            <button class="btn btn-primary btn-lg btn-block" type="submit" name="processinvoicereport">Search</button>
        </div>
      </div>
      </form>
    </div>
      <div class="container">
         <?php if (isset($_SESSION['datesearch'])) { ?>
             <h4 style="text-align: center;"><?php if (isset($_SESSION['datesearch'])){echo $_SESSION['datesearch'];unset($_SESSION['datesearch']);} ?></h4>
        <?php } ?>
      </div>
    <div class="container">
      <?php
      if (isset($_SESSION['invoicetable'])) {
          $pdf = "<a href='pdf.php'   target='_blank'><button class='btn'><i class=\"fa fa-file-pdf-o\" ></i> PDF</button></a> ";
          $excel = "<a href='excel.php' target='_blank'><button class='btn'><i class=\"fa fa-file-excel-o\" ></i> Excel</button></a>";
          echo $pdf.$excel.$_SESSION['invoicetable'];
          unset($_SESSION['invoicetable']);
      }
      ?>
    </div>
      <div class="container" style="padding-bottom: 50px">
           <?php if (isset($_SESSION['totalunpaid'])&&$_SESSION['totalunpaid']!=0) { ?>
          <div class="row">
          <div class="col-sm-6 text-right">Total Unpaid</div><div class="col-sm-4 text-left"><?php echo $_SESSION['totalunpaid']; ?></div><div class="col-sm-2 text-left"></div></div>
        <?php } ?>
           <?php if (isset($_SESSION['totalpaid'])&&$_SESSION['totalpaid']!=0) { ?>
              <div class="row">
          <div class="col-sm-6 text-right">Total Paid</div><div class="col-sm-4 text-left"><?php echo $_SESSION['totalpaid']; ?></div><div class="col-sm-2 text-left"></div></div>
        <?php } ?>
          <?php if (isset($_SESSION['outstanding']) && ($_SESSION['outstanding'])==true) { ?>
                  <div class="row">
          <div class="col-sm-6 text-right">Total Outstanding</div><div class="col-sm-4 text-left"><?php echo $_SESSION['totalunpaid']-$_SESSION['totalpaid'];unset($_SESSION['totalpaid']);unset($_SESSION['totalunpaid']); ?></div><div class="col-sm-2 text-left"></div></div>
        <?php } unset($_SESSION['totalpaid']);unset($_SESSION['totalunpaid']);unset($_SESSION['outstanding']); ?>
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
