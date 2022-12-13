<?php

$config = parse_ini_file(__DIR__ . "/jsheetconfig.ini");

session_name($config['sessionName']);

require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/connect.php");

session_start();

$two = 2;

if (isset($_SESSION['type'])&&$_SESSION['type'] == 'vendor') {

    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/vendor/assignedTask/viewTask.php");

} else if (isset($_SESSION['type'])&&$_SESSION['type'] == 'client') {

    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/schedule/clientview/booking/createbooking.php");

} else if (isset($_SESSION['type'])&&$_SESSION['type'] == 'clientStore') {

if(isset($_SESSION['memberRegOver'])&&$_SESSION['memberRegOver']==true){
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/store/renewal.php");
}
    if(isset($_SESSION['memberRegPending'])&&$_SESSION['memberRegPending']==true){
        header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/store/upgrade.php");
    }
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/store/store.php");

} elseif (isset($_SESSION['serviceEngineer'])&&$_SESSION['serviceEngineer']) {
    echo "<br /><br /><br /><br />Service Engineer";
    //header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/staff/assignedTask/viewTask.php");

    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/staff/assignedTask/viewTask.php");

    ?>
    <script>

        //window.location.replace("https://www.servicing-recarts.com/organization/staff/assignedTask/viewTask.php");

    </script><?php
} else if (isset($_SESSION['orgType']) && $_SESSION['orgType'] == 8) {
    header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/product/store.php");
}
?>

<!DOCTYPE html >
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta https-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon"
          href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/resources/app/favIcon.ico'; ?>'/>
    <!-- <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script> -->
    <!-- <script src="js/highcharts.js" ></script>
      <script src="js/exporting.js" ></script>
      <script src="js/export-data.js" ></script> -->
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/json2/20160511/json2.min.js"></script>
    <!--
     <link rel="stylesheet" href="css/bootstrap.min.css">
     <script src="js/bootstrap.min.js" ></script>
 -->
    <?php

    require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/importScripts.php");

    require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/invoice.php");

    require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/organizationUser.php");

    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/nortification.php");

    ?>
    <style>
        .buttonAsLink {
            background: none !important;
            color: inherit;
            border: none;
            font: inherit;
            cursor: pointer;
        }
.highcharts-credits{display:none !important;}
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
        .bg-red {
            background-color: #E32526;
        }

        table {
            border-collapse: separate;
            border-spacing: 0;
            min-width: 150px;
            font-size: 10px;
        }

        table tr th,
        table tr td {
            border-right: 1px solid #bbb;
            border-bottom: 1px solid #bbb;
            padding: 5px;
        }

        table tr th:first-child,
        table tr td:first-child {
            border-left: 1px solid #bbb;
        }

        table tr th {
            background: #eee;
            border-top: 1px solid #bbb;
            text-align: left;
        }

        /* top-left border-radius */
        table tr:first-child th:first-child {
            border-top-left-radius: 6px;
        }
        /* top-right border-radius */
        table tr:first-child th:last-child {
            border-top-right-radius: 6px;
        }

        /* bottom-left border-radius */
        table tr:last-child td:first-child {
            border-bottom-left-radius: 6px;
        }


        /* bottom-right border-radius */
        table tr:last-child td:last-child {
            border-bottom-right-radius: 6px;
        }
        .shadow {
            box-shadow: 0px 1px 15px -1px #0000002e;
            margin-bottom: 30px;
        }
        div#mtBody table {
            border: 1px solid #000000;
            border-radius: 8px;
            font-size: 16px;
        }
        .card-box {
            position: relative;
            color: #fff;
            padding: 20px 10px 40px;
            margin: 20px 0px;
        }

        .card-box:hover {
            text-decoration: none;
            color: #f1f1f1;
        }

        .card-box:hover .icon i {
            font-size: 100px;
            transition: 1s;
            -webkit-transition: 1s;
        }

        .card-box .inner {
            padding: 5px 10px 0 10px;
        }

        .card-box h3 {
            font-size: 27px;
            font-weight: bold;
            margin: 0 0 8px 0;
            white-space: nowrap;
            padding: 0;
            text-align: left;
        }

        .card-box p {
            font-size: 15px;
        }

        .card-box .icon {
            position: absolute;
            top: auto;
            bottom: 5px;
            right: 5px;
            z-index: 0;
            font-size: 72px;
            color: rgba(0, 0, 0, 0.15);
        }

        .card-box .card-box-footer {
            position: absolute;
            left: 0px;
            bottom: 0px;
            text-align: center;
            padding: 3px 0;
            color: rgba(255, 255, 255, 0.8);
            background: rgba(0, 0, 0, 0.1);
            width: 100%;
            text-decoration: none;
        }

        .card-box:hover .card-box-footer {
            background: rgba(0, 0, 0, 0.3);
        }

        .bg-blue {
            background-color: #00c0ef !important;
        }

        .bg-green {
            background-color: #00a65a !important;
        }

        .bg-orange {
            background-color: #f39c12 !important;
        }

        .bg-red {
            background-color: #d9534f !important;
        }
        .box-content{height: 30px;}
    </style>

    <script>
        function showInputGraph() {
            document.getElementById("graphInputOverall").hidden = true;
            document.getElementById("graphInputData").hidden = false;
        }

        function showInputGraph2() {
            document.getElementById("graph2InputOverall").hidden = true;
            document.getElementById("graph2InputData").hidden = false;
        }

        function showInputGraph3() {
            document.getElementById("graph3InputOverall").hidden = true;
            document.getElementById("graph3InputData").hidden = false;
        }
    </script>
    <title>Portal - Dashboard</title>
</head>
<body class="fixed-nav " id="page-top">
<?php
include $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/navMenu.php";
?>
<div class="content-wrapper">
    <div class="container-fluid">
        <?php echo shortcut() ?>
        <!-- Breadcrumbs-->
        <ol style="color: white" class="breadcrumb col-md-12">
            <li style="color:white" class="breadcrumb-item">
                <a href="#">Dashboard</a>
            </li>
            <li class="breadcrumb-item active"></li>
            My Dashboard
        </ol>
    </div>
    <div class="container-fluid">
        <div class="row">
            <?php if ($_SESSION['orgType'] == 3) { ?>
                <div class="col-lg-3 col-sm-6">
                    <div class="card-box bg-blue">
                        <div class="inner">
                            <h3 class="newrequest"> loading.. </h3>
                            <p class="box-content"> New Request </p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-user-o" aria-hidden="true"></i>
                        </div>
                        <a href="https://<?php echo $_SERVER['HTTP_HOST'] . $config['appRoot']; ?>/organization/client/newClientRequest.php"
                           class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card-box bg-green">
                        <div class="inner">
                            <h3 class="potential"> loading.. </h3>
                            <p class="box-content"> Registered Potential client by month </p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-address-card-o" aria-hidden="true"></i>
                        </div>
                        <a href="https://<?php echo $_SERVER['HTTP_HOST'] . $config['appRoot']; ?>/organization/client/potentialClients.php"
                           class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>

            <div class="col-lg-3 col-sm-6">
                <!-- small box -->
                <div class="card-box bg-red">
                    <div class="inner">
                        <h3><?php echo orderSalesCount(); ?></h3>
                        <p class="box-content">New Orders</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-shopping-bag"></i>
                    </div>
                    <a href="https://<?php echo $_SERVER['HTTP_HOST'] . $config['appRoot']; ?>/organization/product/store.php" class="card-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-sm-6">
                <!-- small box -->
                <div class="card-box bg-orange">
                    <div class="inner">
                        <h3><?php echo RegisterMembers(); ?></h3>
                        <p class="box-content">Registered Client</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-pie-chart"></i>
                    </div>
                    <a href="https://<?php echo $_SERVER['HTTP_HOST'] . $config['appRoot']; ?>/organization/client/viewClient.php" class="card-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div> <?php } ?>
        </div>
        <!-- <h2 style="text-align: center;" class="py-5">Under Construction</h2>-->
        <!-- Add Client/Vendor/Staff
      <div class="col-md-12">
        <center>
          <div id="div1" class="nav-item col-md-2" style=" height: 35px; margin-bottom:10px;">
          <a href = '<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/client/addClient.php'; ?>' class="fa fa-user" style="color:white; font-size:30px; "></a><a href = '<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/client/addClient.php'; ?>'>
          Add Client <i class="fa fa-plus-square" style="color:white; font-size: 1.0em;"></i> </a></div>
          <div id="div2" class="nav-item col-md-2" style=" height: 35px; margin-bottom:10px;">
            <a href = '<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/vendor/addVendor.php'; ?>' class="fa fa fa-user" style="color:white; font-size:30px; "></a><a href = '<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/vendor/addVendor.php'; ?>'>
            Add Vendor <i class="fa fa-plus-square" style="color:white; font-size: 1.0em;"></i></a></div>
          <div id="div3" class="nav-item col-md-2" style=" height: 35px; margin-bottom:10px;">
            <a href = '<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/staff/addStaff.php'; ?>' class="fa fa fa-user" style="color:white; font-size:30px;"></a><a href = '<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/staff/addStaff.php'; ?>'>
            Add Staff <i class="fa fa-plus-square" style="color:white; font-size: 1.0em;"></i></a></div>
          <div id="div3" class="nav-item col-md-2" style=" height: 35px; margin-bottom:10px;">
          <a href = '<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/complaint/uncompleted.php'; ?>' class="fa fa-comment" style="color:white; font-size:30px; "></a><a href = '<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/complaint/uncompleted.php'; ?>'>
          Incidents <i class="badge badge-primary" style="color:white;"></i><?php //echo $newComplaint ?></span></a></div>
        </center>
      </div> -->

        <div class="row">
            <?php if (isset($_SESSION['orgType']) && $_SESSION['orgType'] == 2) { ?>
                <div class="col-md-6">
                    <div class="shadow">
                        <div id="dstable"></div>
                        <?php require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/maintenance.php");
                        echo dsInvoiceUnpaid();
                        ?>
                    </div>
                </div>
            <?php } ?>  <?php /* if (isset($_SESSION['orgType']) && $_SESSION['orgType'] == 2) { ?>
                <div class="col-md-6">
                    <div class="shadow">
                        <div id="dstable"></div>
                        <?php require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/maintenance.php");
                        echo dsInvoiceC1Unpaid();
                        ?>
                    </div>
                </div>
            <?php } ?>  <?php if (isset($_SESSION['orgType']) && $_SESSION['orgType'] == 2) { ?>
                <div class="col-md-6">
                    <div class="shadow">
                        <div id="dstable"></div>
                        <?php require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/maintenance.php");
                        echo dsTripInvoiceUnpaid();
                        ?>
                    </div>
                </div>
            <?php } */ ?>
            <?php if (isset($_SESSION['orgType']) && $_SESSION['orgType'] == 8) { ?>
                <div class="col-md-6">
                    <div class="shadow">
                        <div id="dstable"></div>
                        <?php require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/product.php");
                        echo dsTable();
                        ?>
                    </div>
                </div>
            <?php } else if ($_SESSION['orgType'] == 2) { ?>
                <div class="col-md-6">
                    <div class="shadow">
                        <div id="dstable"></div>
                        <?php require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/maintenance.php");
                        echo dsMaintenanceTable();
                        ?>
                    </div>
                    <script>
                        function showMaintenance(mt) {
                            document.getElementById('mtBody').innerHTML = "";
                            $.ajax({

                                type: 'GET',

                                url: 'phpfunctions/maintenance.php?',

                                data: {getMaintenanceDetail: mt},

                                success: function (data) {
                                    var obj = JSON.parse(data);
                                    var table = "";
                                    table = '<table width="100%">';
                                    table += '<tr><td>Truck No</td><td>' + obj.vehicle_no + '</td></tr>';
                                    table += '<tr><td>Truck</td><td>' + obj.vehicle_type + '</td></tr>';
                                    table += '<tr><td>Maintenance For</td><td>' + obj.maintenance + '</td></tr>';
                                    table += '<tr><td>Custpmer Job No</td><td>' + obj.jobNo + '</td></tr>';
                                    table += '<tr><td>Custpmer Invoice No</td><td>' + obj.invoiceNo + '</td></tr>';
                                    table += '<tr><td>Workshop/Location</td><td>' + obj.workshop + '</td></tr>';
                                    table += '<tr><td>Description</td><td>' + obj.description + '</td></tr>';
                                    table += '<tr><td>Remarks</td><td>' + obj.remarks + '</td></tr>';
                                    table += '<tr><td>Maintenance Date</td><td>' + obj.next_date + '</td></tr>';
                                    table += '<tr><td>Amount</td><td>' + obj.amount + '</td></tr>';
                                    table += '<tr><td>Status</td><td>';
                                    if (obj.mstatus == 1) {
                                        table += '<div style="height: 15px;width: 15px;border-radius:15px;background-color: green;"><span style="display: none">Completed</span></div>';
                                    } else if (obj.mstatus == 0) {
                                        table += '<div style="height: 15px;width: 15px;border-radius:15px;background-color: red;"><span style="display: none">Pending</span></div>';
                                    }
                                    table += '</td></tr>';
                                    table += '</table>';
                                    document.getElementById('mtBody').innerHTML = table;
                                }
                            });
                            $('#myModal').modal('show');
                        }
                    </script>
                </div>
            <?php } /* else if($_SESSION['orgType']==3){ ?>
              <div class="col-md-6">

                  <div class="shadow">
                      <div id="dstable"></div>
                      <?php require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/registration.php");
                      echo dsNewRequestTable();
                      ?>
                  </div>

              </div>

          <?php } */ ?>
            <div class="col-md-6">
                <figure class="highcharts-figure">
                    <div id="storeincome"></div></figure>
                <script>
                    <?php
                    $monthlyStoreSale= monthlyStoreSales();
                    $total="[";
                    $customer=array();
                    $c=1;
                    foreach ($monthlyStoreSale AS $sale){
                        if($c==1){$total.=$sale['total'];
                        }
                        else{
                            $total.=", ".$sale['total'];
                        }
                        $customer[]=$sale['month'];
                        $c++;
                    }
                    $total.="]";

                    echo "var customer= ".json_encode($customer).";\n";
                    ?>
                    Highcharts.chart('storeincome', {
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: 'E-commerce monthly sales for quarterly'
                        },
                        subtitle: {
                            text: 'Report Chart'
                        },
                        xAxis: {
                            categories: customer,
                            crosshair: true
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: 'Monthly Sales (RM)'
                            }
                        },
                        tooltip: {
                            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                '<td style="padding:0"><b>RM {point.y:.0f} </b></td></tr>',
                            footerFormat: '</table>',
                            shared: true,
                            useHTML: true
                        },
                        plotOptions: {
                            column: {
                                pointPadding: 0,
                                borderWidth: 0
                            }
                        },
                        series: [{
                            name: 'Total Income',
                            data: <?php echo $total; ?>
                        }]
                    });
                </script>
            </div>

            <div class="col-md-6">
                <figure class="highcharts-figure">
                    <div id="storeTotalIncome"></div></figure>
                <script>
                    <?php
                    $monthlyStoreSale= monthlyStoreSales();
                    $total="[";
                    $customer=array();
                    $c=1;
                    foreach ($monthlyStoreSale AS $sale){
                        if($c==1){$total.=$sale['count'];
                        }
                        else{
                            $total.=", ".$sale['count'];
                        }
                        $customer[]=$sale['month'];
                        $c++;
                    }
                    $total.="]";

                    echo "var customer= ".json_encode($customer).";\n";
                    ?>
                    Highcharts.chart('storeTotalIncome', {
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: 'E-commerce monthly Total number of sales for quarterly'
                        },
                        subtitle: {
                            text: 'Report Chart'
                        },
                        xAxis: {
                            categories: customer,
                            crosshair: true
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: 'Monthly Total Number of Sales'
                            }
                        },
                        tooltip: {
                            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                '<td style="padding:0"><b>{point.y:.0f} </b></td></tr>',
                            footerFormat: '</table>',
                            shared: true,
                            useHTML: true
                        },
                        plotOptions: {
                            column: {
                                pointPadding: 0,
                                borderWidth: 0
                            }
                        },
                        series: [{
                            name: 'Total Income',
                            data: <?php echo $total; ?>
                        }]
                    });
                </script>
            </div>
            <div class="col-md-6">
                <figure class="highcharts-figure">
                    <div id="newclientincome"></div></figure>
                <script>
                    <?php
                    $monthlySale= monthlySales();
                    $total="[";
                    $customer=array();
                    $c=1;
                    foreach ($monthlySale AS $sale){
                        if($c==1){$total.=$sale['total'];
                        }
                        else{
                            $total.=", ".$sale['total'];
                        }
                        $customer[]=$sale['month'];
                        $c++;
                    }
                    $total.="]";

                    echo "var customer= ".json_encode($customer).";\n";
                    ?>
                    Highcharts.chart('newclientincome', {
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: 'Monthly subscription for current year'
                        },
                        subtitle: {
                            text: 'Report Chart'
                        },
                        xAxis: {
                            categories: customer,
                            crosshair: true
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: 'Monthly Sales (RM)'
                            }
                        },
                        tooltip: {
                            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                '<td style="padding:0"><b>RM {point.y:.0f} </b></td></tr>',
                            footerFormat: '</table>',
                            shared: true,
                            useHTML: true
                        },
                        plotOptions: {
                            column: {
                                pointPadding: 0,
                                borderWidth: 0
                            }
                        },
                        series: [{
                            name: 'Total Income',
                            data: <?php echo $total; ?>
                        }]
                    });
                </script>
            </div>

            <div class="col-md-6">
                <figure class="highcharts-figure">
                    <div id="newclientannualincome"></div></figure>
                <script>
                    <?php
                    $yearSale= yearSalesReport();
                    $total="[";
                    $customer=array();
                    $c=1;
                    foreach ($yearSale AS $sale){
                        if($c==1){$total.=$sale['total'];
                          }
                        else{
                        $total.=", ".$sale['total'];
                            }
                        $customer[]=$sale['year'];
                        $c++;
                    }
                    $total.="]";
                    echo "var customer= ".json_encode($customer).";\n";
                  ?>
                    Highcharts.chart('newclientannualincome', {
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: 'Yearly subscription income'
                        },
                        subtitle: {
                            text: 'Report Chart'
                        },
                        xAxis: {
                            categories: customer,
                            crosshair: true
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: 'Annual Sales (RM)'
                            }
                        },
                        tooltip: {
                            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                '<td style="padding:0"><b>RM {point.y:.0f} </b></td></tr>',
                            footerFormat: '</table>',
                            shared: true,
                            useHTML: true
                        },
                        plotOptions: {
                            column: {
                                pointPadding: 0,
                                borderWidth: 0
                            }
                        },
                        series: [{
                            name: 'Total Income',
                            data: <?php echo $total; ?>,
                            color: '#3cb821'
                        }]
                    });
                </script>
            </div>
            <div class="col-md-6">
                <figure class="highcharts-figure">
                    <div id="subscriptionEndGet"></div></figure>
                <script>
                    <?php

                    $subEnd= subscriptionEndGet();
                    $total="[";
                    $customer=array();
                    $c=1;
                    foreach ($subEnd AS $ss){
                        if($c==1){$total.=$ss['count'];
                          }
                        else{
                        $total.=", ".$ss['count'];
                            }
                        $customer[]=$ss['month'];
                        $c++;
                    }
                    $total.="]";

                    echo "var customer= ".json_encode($customer).";\n";

                  ?>
                    Highcharts.chart('subscriptionEndGet', {
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: 'Upcoming renewal'
                        },
                        subtitle: {
                            text: 'Report Chart'
                        },
                        xAxis: {
                            categories: customer,
                            crosshair: true
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: 'Total client'
                            }
                        },
                        tooltip: {
                            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                '<td style="padding:0"><b>{point.y:.0f} </b></td></tr>',
                            footerFormat: '</table>',
                            shared: true,
                            useHTML: true
                        },
                        plotOptions: {
                            column: {
                                pointPadding: 0,
                                borderWidth: 0
                            }
                        },
                        series: [{
                            name: 'Month',
                            data: <?php echo $total; ?>,
                            color: '#ff7600'

                        }]
                    });
                </script>
            </div>
            <div class="col-md-6">
                <figure class="highcharts-figure">
                    <div id="nosubscription"></div></figure>
                <script>
                    <?php

                    $subEnd= noSubscriotionGet();
                    $total="[";
                    $customer=array();
                    $c=1;
                    foreach ($subEnd AS $ss){
                        if($c==1){$total.=$ss['count'];
                          }
                        else{
                        $total.=", ".$ss['count'];
                            }
                        $customer[]=$ss['month'];
                        $c++;
                    }
                    $total.="]";

                    echo "var customer= ".json_encode($customer).";\n";

                  ?>
                    Highcharts.chart('nosubscription', {
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: 'Potential client by online'
                        },
                        subtitle: {
                            text: 'Report Chart'
                        },
                        xAxis: {
                            categories: customer,
                            crosshair: true
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: 'Total subscription'
                            }
                        },
                        tooltip: {
                            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                '<td style="padding:0"><b>{point.y:.0f} </b></td></tr>',
                            footerFormat: '</table>',
                            shared: true,
                            useHTML: true
                        },
                        plotOptions: {
                            column: {
                                pointPadding: 0,
                                borderWidth: 0
                            }
                        },
                        series: [{
                            name: 'Month',
                            data: <?php echo $total; ?>,
                            color: '#6628f8'

                        }]
                    });
                </script>
            </div> <!--<div class="col-md-6">
                <figure class="highcharts-figure">
                    <div id="newclientpe"></div></figure>
                <script>

                    <?php
/*
                    $yearSale= yearSalesReport();
                    $totalYearsReport="[";
                    $c=1;
                    foreach ($yearSale AS $sale){
                        if($c==1){
                            $totalYearsReport.="{
                                name: '".$sale['year']."',
                                y: ".$sale['total'].",
                                sliced: true,
                                selected: true
                            }";
                        }
                        else{
                            $totalYearsReport.=",{
                                name: '".$sale['year']."',
                                y: ".$sale['total']."
                            }";
                        }
                        $c++;
                    }
                    $totalYearsReport.="]";

                    echo "var customer= ".json_encode($customer).";\n";


                    */?>


                    Highcharts.chart('newclientpe', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Year Wise Sale Comparison'
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        },
                        accessibility: {
                            point: {
                                valueSuffix: '%'
                            }
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                                }
                            }
                        },
                        series: [{
                            name: 'Sales',
                            colorByPoint: true,
                            data: <?php /*echo $totalYearsReport; */?>
                        }]
                    });
                </script>
            </div>-->
            <?php if (isset($module[10])) { ?>
                <div class="col-md-6">
                    <div class="shadow">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <input id="graphInputOverall" onclick="showInputGraph();processGraph()"
                                       class="form-control" type="text" value="Overview" readonly
                                       style="cursor:pointer;">
                                <input id="graphInputData" onchange="processGraph()" class="form-control" type="month"
                                       value="<?php echo date("Y-m") ?>" hidden>
                                <input id="storeStaffNameListArray" type="text" hidden>
                                <input id="storeStaffJobStatusCount" type="text" hidden>
                                <div id="storeStaffJobStatusCountDiv" hidden></div>
                            </div>
                        </div>
                        <div id="graph"></div>
                    </div>
                </div>
            <?php } ?>
            <!--
        <div class="col-md-6">
              <div id="graph2" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
              <table id="datatable">
                  <thead>
                      <tr>
                          <th></th>
                          <th>Unpaid/Unconverted</th>
                          <th>Invalid</th>
                          <th>Paid/Converted</th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr>
                          <th>Invoice</th>
                          <td><?php echo tripInvoicePaidCount() ?></td>
                          <td><?php echo tripInvoiceInvalidCount() ?></td>
                          <td><?php echo tripInvoiceUnpaidCount() ?></td>
                      </tr>
                      <tr>
                          <th>Quotation</th>
                          <td><?php echo quotationPaidCount() ?></td>
                          <td>0</td>
                          <td><?php echo quotationUnpaidCount() ?></td>
                      </tr>
                  </tbody>
              </table>
        </div> -->
            <?php if (isset($module[17]) && isset($module[12])) { ?>
                <div class="col-md-6">
                    <div class="shadow">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <input id="graph2InputOverall" onclick="showInputGraph2();processGraph2()"
                                       class="form-control" type="text" value="Overview" readonly
                                       style="cursor:pointer;">
                                <input id="graph2InputData" onchange="processGraph2()" class="form-control" type="month"
                                       value="<?php echo date("Y-m") ?>" hidden>
                                <input id="storeInvoicePaidCount" type="number" hidden>
                                <input id="storeInvoiceUnpaidCount" type="number" hidden>
                                <input id="storeQuotationConvCount" type="number" hidden>
                                <input id="storeQuotationUnconvCount" type="number" hidden>
                            </div>
                        </div>
                        <div id="graph2" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                    </div>
                </div>
            <?php }

            if (isset($module[17])) {
                ?>
                <div class="col-md-6">
                    <div class="shadow">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <input id="graph3InputOverall" onclick="showInputGraph3();processGraph3()"
                                       class="form-control" type="text" value="Overview" readonly
                                       style="cursor:pointer;">
                                <input id="graph3InputData" onchange="processGraph3()" class="form-control" type="month"
                                       value="<?php echo date("Y-m") ?>" hidden>
                                <input id="storeInvoiceAmount" type="number" hidden>
                                <input id="storeClaimAmount" type="number" hidden>
                                <input id="storePayrollAmount" type="number" hidden>
                            </div>
                        </div>
                        <div id="graph3"
                             style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="shadow">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <select id="graph4InputData" onchange="processGraph4()" class="form-control"
                                        type="month">
                                    <?php
                                    $yearStart = date("Y", strtotime("-5 year"));
                                    for ($i = 0; $i <= 5; $i++) {
                                        if ($yearStart == date("Y")) {
                                            $selected = "selected";
                                        } else {
                                            $selected = "";
                                        }
                                        echo "<option value='" . $yearStart . "-01' " . $selected . ">" . $yearStart . "</option>";
                                        $set = strtotime("12:00am January 1 " . $yearStart . " +1 year");
                                        $yearStart = date("Y", $set);
                                    }
                                    ?>
                                </select>
                                <!--<input id="graph4InputData" onchange="processGraph4()" class="form-control" type="month" value="<?php echo date("Y-m") ?>">-->
                                <input id="storeYear" type="text" hidden>
                                <input id="storeYearPrev" type="text" hidden>
                                <input id="storeYearDigit" type="text" hidden>
                                <input id="storeYearDigitPrev" type="text" hidden>
                            </div>
                        </div>
                        <div id="graph4" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <!--
    <center><div id="row">
        <div id="div1" class="nav-item">
          <a href = '<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/client/addClient.php'; ?>' class="fa fa-user" style="color:white; font-size:64px; padding-top:80px;"></a> <div id="colorstrip"/></div><a href = '<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/client/addClient.php'; ?>'>
          Add Client <i class="fa fa-plus-square" style="color:white; font-size: 1.0em;"></i> </a></div>
        <div id="div2" class="nav-item">
          <a href = '<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/vendor/addVendor.php'; ?>' class="fa fa fa-user" style="color:white; font-size:64px; padding-top:80px;"></a> <div id="colorstrip"/></div><a href = '<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/vendor/addVendor.php'; ?>'>
          Add Vendor <i class="fa fa-plus-square" style="color:white; font-size: 1.0em;"></i></a></div>
        <div id="div3" class="nav-item">
          <a href = '<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/staff/addStaff.php'; ?>' class="fa fa fa-user" style="color:white; font-size:64px; padding-top:80px;"></a> <div id="colorstrip"/></div><a href = '<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/staff/addStaff.php'; ?>'>
          Add Staff <i class="fa fa-plus-square" style="color:white; font-size: 1.0em;"></i></a></div>
          <div id="div3" class="nav-item">
          <a href = '<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/complaint/uncompleted.php'; ?>' class="fa fa-comment" style="color:white; font-size:64px; padding-top:80px;"></a> <div id="colorstrip"/></div><a href = '<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/complaint/uncompleted.php'; ?>'>
          Incidents <i class="badge badge-primary" style="color:white;"></i><?php // echo $newComplaint ?></span></a></div>
    </div></center> -->

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fa fa-angle-up"></i>
    </a>
    <div class="footer">
        <p>Powered by JSoft Solution Sdn. Bhd</p>
    </div>
</div>
<!-- The Modal -->
<div class="modal" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Maintenance Detail</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body" id="mtBody">
                Modal body..
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript">

    Highcharts.chart('graph', {
        credits: {
            enabled: false
        },
        chart: {
            type: 'pie'
        },
        title: {
            text: 'Job Report Chart'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false
                },
                showInLegend: true
            }

        },
        series:     <?php echo staffJobStatusCount() ?>
    });
    //processGraph();
    //processGraph2();
    <?php  if(isset($module[17])){  ?>
    processGraph3();
    processGraph4();
    <?php } ?>
    function processGraph(dateMonth) {
        var dateMonth = document.getElementById("graphInputData").value;
        $.ajax({
            type: 'GET',
            url: 'phpfunctions/organizationUser.php?',
            data: {nameListArray: true},
            success: function (data) {
                document.getElementById("storeStaffNameListArray").value = data;
            }
        });

        $.ajax({
            type: 'GET',
            url: 'phpfunctions/organizationUser.php?',
            data: {jobStatusCount2: true, dateMonth: dateMonth},
            success: function (data) {
                document.getElementById("storeStaffJobStatusCountDiv").innerHTML = data;
            }
        });
        window.setTimeout(displayGraph, 300);
    }

    function staffNameListArrayFx() {
        var staffStr = document.getElementById("storeStaffNameListArray").value;
        var newstaffStr = staffStr.replace(/([a-zA-Z0-9]+?):/g, '"$1":');
        newstaffStr = staffStr.replace(/'/g, '"');
        var staff = JSON.parse(newstaffStr);
        return staff;
    }

    function staffJobStatusCountFx() {
        var completeArr = "";
        var comma = false;
        var allComplete = document.getElementsByClassName("complete");
        for (var i = 0; i < allComplete.length; i++) {
            if (comma) {
                completeArr += ",";
            }
            completeArr += document.getElementsByClassName("complete")[i].value;
            comma = true;
        }

        completeArrJSON = JSON.parse("[" + completeArr + "]");
        var comma = false;
        var pendingArr = "";
        var allPending = document.getElementsByClassName("pending");
        for (var i = 0; i < allPending.length; i++) {
            if (comma) {
                pendingArr += ",";
            }
            pendingArr += document.getElementsByClassName("pending")[i].value;
            comma = true;
        }
        pendingArrJSON = JSON.parse("[" + pendingArr + "]");
        var comma = false;
        var progressArr = "";
        var allProgress = document.getElementsByClassName("progress");
        for (var i = 0; i < allProgress.length; i++) {
            if (comma) {
                progressArr += ",";
            }
            progressArr += document.getElementsByClassName("progress")[i].value;
            comma = true;
        }
        progressArrJSON = JSON.parse("[" + progressArr + "]");
        var taskCountStr = [{name: 'Completed', data: completeArrJSON}, {
            name: 'Pending',
            data: pendingArrJSON
        }, {name: 'In Progress', data: progressArrJSON}];
        return taskCountStr;
    }

    function displayGraph() {
        var staffNameListArray = "['mookesh','prem','mathavan','f003','testing','syahid','firdaus','Akeem','gopi','tayeb','nosheen','najim','hajian','technicion',]";
        var staffJobStatusCount = "{name:'Completed',data:[31,2,1,2,0,18,0,0,0,0,0,0,0,0,]},{name:'Pending',data:[6,1,1,1,1,5,0,2,0,0,0,0,0,0,]},{name:'In Progress',data:[0,0,0,0,0,1,0,0,0,0,0,0,0,0,]}";
        Highcharts.chart('graph', {
            credits: {
                enabled: false
            },
            chart: {
                type: 'bar'
            },
            title: {
                text: 'Job Report Chart'
            },
            xAxis: {
                categories: staffNameListArrayFx()
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Total task'
                }
            },
            legend: {
                reversed: true
            },
            plotOptions: {
                series: {
                    stacking: 'normal'
                }
            },
            series: staffJobStatusCountFx()
        });
    }

    Highcharts.chart('graph2', {
        credits: {
            enabled: false
        },
        chart: {
            type: 'column'
        },
        title: {
            text: 'Quotation & Invoice'
        },
        xAxis: {
            categories: ['Quotation', 'Invoice']
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Unit'
            }
        },
        tooltip: {
            pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
            shared: true
        },
        plotOptions: {
            column: {
                stacking: 'total'
            }
        },
        series: [{
            name: 'Converted/Paid',
            data: [<?php echo quotationUnpaidCount() ?>,<?php echo invoicePaidCount() ?>]
        }, {
            name: 'Unconverted/Unpaid',
            data: [<?php echo quotationPaidCount() ?>,<?php echo invoiceUnpaidCount() ?>]
        }]
    });
    function processGraph2() {
        var dateMonth = document.getElementById("graph2InputData").value;
        $.ajax({
            type: 'GET',
            url: 'phpfunctions/invoice.php?',
            data: {invoicePaidCount: true, dateMonth: dateMonth},
            success: function (data) {
                document.getElementById("storeInvoicePaidCount").value = data;
            }
        });
        $.ajax({
            type: 'GET',
            url: 'phpfunctions/invoice.php?',
            data: {invoiceUnpaidCount: true, dateMonth: dateMonth},
            success: function (data) {
                document.getElementById("storeInvoiceUnpaidCount").value = data;
            }
        });
        $.ajax({
            type: 'GET',
            url: 'phpfunctions/invoice.php?',
            data: {quotationConvCount: true, dateMonth: dateMonth},
            success: function (data) {
                document.getElementById("storeQuotationConvCount").value = data;
            }
        });
        $.ajax({
            type: 'GET',
            url: 'phpfunctions/invoice.php?',
            data: {quotationUnconvCount: true, dateMonth: dateMonth},
            success: function (data) {
                document.getElementById("storeQuotationUnconvCount").value = data;
            }
        });
        window.setTimeout(displayGraph2, 300);
    }

    function displayGraph2() {
        var invoicePaidCount = document.getElementById("storeInvoicePaidCount").value;
        var invoiceUnpaidCount = document.getElementById("storeInvoiceUnpaidCount").value;
        var quotationConvCount = document.getElementById("storeQuotationConvCount").value;
        var quotationUnconvCount = document.getElementById("storeQuotationUnconvCount").value;
        var paidConv = JSON.parse("[" + quotationUnconvCount + "," + invoicePaidCount + "]");
        var unpaidConv = JSON.parse("[" + quotationConvCount + "," + invoiceUnpaidCount + "]");

        Highcharts.chart('graph2', {
            credits: {
                enabled: false
            },
            chart: {
                type: 'column'
            },
            title: {
                text: 'Quotation & Invoice'
            },
            xAxis: {
                categories: ['Quotation', 'Invoice']
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Unit'
                }
            },
            tooltip: {
                pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
                shared: true
            },
            plotOptions: {
                column: {
                    stacking: 'total'
                }
            },
            series: [{
                name: 'Converted/Paid',
                data: paidConv
            }, {
                name: 'Unconverted/Unpaid',
                data: unpaidConv
            }]
        });
    }
    /*Highcharts.chart('graph3', {

        credits:{

            enabled:false,

        },

        chart: {

            plotBackgroundColor: null,

            plotBorderWidth: null,

            plotShadow: false,

            type: 'pie'

        },

        title: {

            text: 'Sales, Claim, & Payroll'

        },

        tooltip: {

            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'

        },

        plotOptions: {

            pie: {

                allowPointSelect: true,

                cursor: 'pointer',

                dataLabels: {

                    enabled: true,

                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',

                    style: {

                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'

                    }

                }

            }

        },

        series: [{

            name: 'Brands',

            colorByPoint: true,

            data: [{

                name: 'Remains',

                y: 61.41,

                sliced: true,

                selected: true

            }, {

                name: 'Claim',

                y: 11.84

            }, {

                name: 'Payroll',

                y: 10.85

            }]

        }]

    }); */

    <?php  if(isset($module[17])){  ?>

    function processGraph3() {

        var dateMonth = document.getElementById("graph3InputData").value;

        $.ajax({

            type: 'GET',

            url: 'phpfunctions/invoice.php?',

            data: {invoiceAmount: true, dateMonth: dateMonth},

            success: function (data) {

                document.getElementById("storeInvoiceAmount").value = data;

            }

        });


        $.ajax({

            type: 'GET',

            url: 'phpfunctions/claim.php?',

            data: {claimAmount: true, dateMonth: dateMonth},

            success: function (data) {

                console.log("claim:" + data);

                document.getElementById("storeClaimAmount").value = data;

            }

        });


        $.ajax({

            type: 'GET',

            url: 'phpfunctions/payroll.php?',

            data: {payrollAmount: true, dateMonth: dateMonth},

            success: function (data) {

                document.getElementById("storePayrollAmount").value = data;

            }

        });


        window.setTimeout(calculationGraph3, 300);

    }


    function calculationGraph3() {

        var invoiceAmount = document.getElementById("storeInvoiceAmount").value;

        var claimAmount = document.getElementById("storeClaimAmount").value;

        var payrollAmount = document.getElementById("storePayrollAmount").value;


        var invoiceAmountInt = parseInt(invoiceAmount);

        var claimAmountInt = parseInt(claimAmount);

        var payrollAmountInt = parseInt(payrollAmount);


        var claimPercentage = (claimAmountInt / invoiceAmountInt) * 100;

        var payrollPercentage = (payrollAmountInt / invoiceAmountInt) * 100;

        var invoicePercentage = 100 - claimPercentage - payrollAmount;


        window.setTimeout(displayGraph3(invoicePercentage, payrollPercentage, claimPercentage), 300);

    }


    function displayGraph3(invoice, claim, payroll) {

        Highcharts.chart('graph3', {

            credits: {

                enabled: false

            },

            chart: {

                plotBackgroundColor: null,

                plotBorderWidth: null,

                plotShadow: false,

                type: 'pie'

            },

            title: {

                text: 'Sales, Claim, & Payroll'

            },

            tooltip: {

                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'

            },

            plotOptions: {

                pie: {

                    allowPointSelect: true,

                    cursor: 'pointer',

                    dataLabels: {

                        enabled: true,

                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',

                        style: {

                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'

                        }

                    }

                }

            },

            series: [{

                name: 'Brands',

                colorByPoint: true,

                data: [{

                    name: 'Remains',

                    y: invoice,

                    sliced: true,

                    selected: true

                }, {

                    name: 'Claim',

                    y: payroll

                }, {

                    name: 'Payroll',

                    y: claim

                }]

            }]

        });

    }
    <?php  }  ?>
    /*

    Highcharts.chart('graph4', {

        credits:{

            enabled:false,

        },

        chart: {

            type: 'line'

        },

        title: {

            text: 'Sample Graph'

        },

        subtitle: {

            text: 'Source: sample.com'

        },

        xAxis: {

            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']

        },

        yAxis: {

            title: {

                text: 'Sample (unit)'

            }

        },

        plotOptions: {

            line: {

                dataLabels: {

                    enabled: true

                },

                enableMouseTracking: false

            }

        },

        series: [{

            name: 'Subject1',

            data: [7.0, 6.9, 9.5, 14.5, 18.4, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]

        }, {

            name: 'Subject2',

            data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]

        }]

    }); */


    <?php  if(isset($module[17])){  ?>
    function processGraph4() {

        var dateMonth = document.getElementById("graph4InputData").value;

        var dateObj = new Date(dateMonth);

        var year = dateObj.getUTCFullYear();


        var dateObjPrev = new Date(dateMonth);

        var yearPrev = dateObjPrev.getUTCFullYear() - 1;

        document.getElementById("storeYearDigit").value = year;

        document.getElementById("storeYearDigitPrev").value = yearPrev;

        $.ajax({

            type: 'GET',

            url: 'phpfunctions/invoice.php?',

            data: {invoicePaidCompare: true, dateMonth: year},

            success: function (data) {

                document.getElementById("storeYear").value = data;

            }

        });


        $.ajax({

            type: 'GET',

            url: 'phpfunctions/invoice.php?',

            data: {invoicePaidCompare: true, dateMonth: yearPrev},

            success: function (data) {

                document.getElementById("storeYearPrev").value = data;

            }

        });

        window.setTimeout(displayGraph4, 500);

    }


    function displayGraph4() {

        var yearDigit = document.getElementById("storeYearDigit").value;

        var yearDigitPrev = document.getElementById("storeYearDigitPrev").value;


        var yearData = document.getElementById("storeYear").value;

        var yearPrevData = document.getElementById("storeYearPrev").value;


        var year = JSON.parse(yearData);

        var yearPrev = JSON.parse(yearPrevData);


        Highcharts.chart('graph4', {

            credits: {

                enabled: false

            },

            chart: {

                type: 'line'

            },

            title: {

                text: 'Sales Comparison with previous year'

            },

            subtitle: {

                text: yearDigit + ' / ' + yearDigitPrev

            },

            xAxis: {

                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']

            },

            yAxis: {

                title: {

                    text: 'Sample (unit)'

                }

            },

            plotOptions: {

                line: {

                    dataLabels: {

                        enabled: true

                    },

                    enableMouseTracking: false

                }

            },

            series: [{

                name: yearDigit,

                data: year

            }, {

                name: yearDigitPrev,

                data: yearPrev

            }]

        });

    }

    <?php } ?>


</script>

</body>

<script>
    $(document).ready(function () {
        $.ajax({
            type: 'GET',
            url: 'phpfunctions/product.php?',
            data: {dashTable: true},
            success: function (data) {
                console.log(data);
                $('#dstable').html(data);
            }
        });

    });
</script>

</html>