<?php
$config=parse_ini_file(__DIR__."/../../../jsheetconfig.ini");
if(!isset($_SESSION))
{
  session_name($config['sessionName']);
  session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/bill.php");

?>
<!DOCTYPE HTML>

<html>
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
      $('#profitLossTable').DataTable({
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
      document.getElementsByName("yearReportFormat")[0].checked = false;
      document.getElementsByName("yearReportFormat")[1].checked = false;

      var val = document.getElementById("selectDateType").value;
      if (val == 0) {
        document.getElementById("inputMonth").style.display = "block";
        document.getElementById("inputYear").style.display = "none";

        document.getElementById("inputMonthForm").required = true;
        document.getElementById("inputYearForm").required = false;
        document.getElementsByName("yearReportFormat")[0].required = false;
      }else {
        document.getElementById("inputMonth").style.display = "none";
        document.getElementById("inputYear").style.display = "block";

        document.getElementById("inputMonthForm").required = false;
        document.getElementById("inputYearForm").required = true;
        document.getElementsByName("yearReportFormat")[0].required = true;

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
          <a href="../../home.php">Dashboard</a>
        </li>
        <li class="breadcrumb-item ">Profit & Loss Report</li>
      </ol>
    </div>
    <div class="container">
      <form method="POST" action="../../../phpfunctions/productReport.php" class="needs-validation">
      <?php
        if (isset($_SESSION['feedback'])) {
            echo $_SESSION['feedback'];
            unset($_SESSION['feedback']);
        }
      ?>
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
              <div class="form-check">
                <input id="separated" type="radio" name="yearReportFormat" value="separated"><label for="separated">Separated</label>
              </div>
              <div class="form-check">
                <input id="combined" type="radio" name="yearReportFormat" value="combined"><label for="combined">Combined</label>
              </div>
            </div>
          </div>
      </div>
      <div class="form-group row">
        <div class="col-md-12">
            <button class="btn btn-primary btn-lg btn-block" type="submit" name="processReport">Search</button>
        </div>
      </div>
      </form>
    </div>
    <div class="container">
      <?php
      if (isset($_SESSION['profitLossTable'])) {
          $pdf = "<a href='pdf.php'   target='_blank'><button class='btn'><i class=\"fa fa-file-pdf-o\" ></i> PDF</button></a> ";
          $excel = "<a href='excel.php' target='_blank'><button class='btn'><i class=\"fa fa-file-excel-o\" ></i> Excel</button></a>";
          echo $pdf.$excel.$_SESSION['profitLossTable'];
          unset($_SESSION['profitLossTable']);
      }
      ?>
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
