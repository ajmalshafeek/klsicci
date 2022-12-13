<?php
 $config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
 if(!isset($_SESSION))
 {
  session_name($config['sessionName']);
	session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/trip.php");
?>
<!DOCTYPE html >

<html">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />


    <?php
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/maintenance.php");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/vehicle.php");
    ?>
    <!-- datatable -->
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/jquery.dataTables.js'></script>
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/dataTables.bootstrap4.js'></script>
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/js/sb-admin-datatables.min.js'></script>
    <!--<script src='https://code.jquery.com/jquery-3.3.1.js'></script>
     <script src='https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js'></script> -->
    <script src='https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js'></script>
    <script src='https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js'></script>
    <script src='https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js'></script>
    <script src='https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js'></script>

    <script>
       $(document).ready(function() {
            let current = new Date();
            let cDate = current.getFullYear() + '-' + (current.getMonth() + 1) + '-' + current.getDate();
            let cTime = current.getHours() + ":" + current.getMinutes() + ":" + current.getSeconds();
            let dateTime = cDate + ' ' + cTime;
            var temp="Trip List "+dateTime;
            $('.dtable').DataTable( {
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',title: temp,text: '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Download Excel',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    }
                ]
            } );
           document.getElementById("inputDate").style.display = "none";
        } );

       function changeInputType(){
           document.getElementsByName("dateMonth")[0].value = null;
           document.getElementsByName("dateYear")[0].value = null;
           document.getElementsByName("sdate")[0].value = null;

           var val = document.getElementById("selectDateType").value;
           if (val == 0) {
               document.getElementById("inputMonth").style.display = "block";
               document.getElementById("inputYear").style.display = "none";
               document.getElementById("inputDate").style.display = "none";

               document.getElementById("inputMonthForm").required = true;
               document.getElementById("inputYearForm").required = false;
               document.getElementById("inputDateForm").required = false;

           }else if(val==1){
               document.getElementById("inputMonth").style.display = "none";
               document.getElementById("inputDate").style.display = "none";
               document.getElementById("inputYear").style.display = "block";

               document.getElementById("inputMonthForm").required = false;
               document.getElementById("inputDateForm").required = false;
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
           else if(val==2){
               document.getElementById("inputMonth").style.display = "none";
               document.getElementById("inputYear").style.display = "none";
               document.getElementById("inputDate").style.display = "block";

               document.getElementById("inputMonthForm").required = false;
               document.getElementById("inputYearForm").required = false;
               document.getElementById("inputDateForm").required = true;
           }
       }

      function clientDelete(str){

        $("#clientIdToDelete").val(str.value);

      }

      function tripEdit(str){
          var id=str.value;
              $("#tripToEdit").val(id);
          $.ajax({
              type  : 'GET',
              url  : '../../phpfunctions/trip.php?',
              data : {tripDetails:id},
              success: function (res) {
                if(res.length>1){
                  var t = JSON.parse(res);
                  console.log("\n"+t.date);
                  $('.date').html(t.date==""?"&nbsp;":t.date);
                  $('.client').html(t.name==""?"&nbsp;":t.name);
                  $('.truck').html(t.truck_no==""?"&nbsp;":t.truck_no);
                  $('.place'). html(t.place==""?"&nbsp;":t.place);
                  $('.shipment'). html(t.shipment_no==""?"&nbsp;":t.shipment_no);
                  $('.driver'). html(t.fullName==""?"&nbsp;":t.fullName);
                  $('.amount'). html(t.amount==""?"&nbsp;":t.amount);
                }
              }
          });
      }

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
        .dt-button{
            margin: 5px 0px 0px 10px;
            color:white;
            background: #8A0808;
            border:0px;
            padding: 10px;
            border-radius: 5px;
        }

        #dataTable_paginate{
            color:black !important;
        }

        #dataTable2_filter{
            display:none;
        }

        #dataTable2{
            display:none;
        }

        #dataTable2_info{
            display:none;
        }

        #dataTable2_paginate{
            display:none;
        }
        .table .thead-dark th {
            color: #fff !important;
            background-color: #3c8dbc !important;
            border-color: #abb6c2 !important;
            border-top-color: rgb(171, 182, 194);
            border-bottom-color: rgb(171, 182, 194);
            border-bottom-color: #3c8dbc !important;
            border-top-color: #3c8dbc !important;
        }
        .thead-dark {
            background: #3c8dbc !important;
            color: #fff !important;
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
        <li class="breadcrumb-item ">Trip</li>
        <li class="breadcrumb-item active">View Trip</li>
      </ol>
     </div>
    
      <?php
            if (isset($_SESSION['feedback'])) {
                echo $_SESSION['feedback'];
                unset($_SESSION['feedback']);
            }
        ?>
        <div class='card mb-3'>
		      <div class='card-header'>
					  <i class='fa fa-table'></i>
            Trip List
				  </div>

            <div class="container-fluid">
                <form method="POST" action="../../phpfunctions/trip.php" class="needs-validation">
                    <?php
                    if (isset($_SESSION['feedback'])) {
                        echo $_SESSION['feedback'];
                        unset($_SESSION['feedback']);
                    }
                    ?>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label>Driver</label>
                            <select name="driver" id="driver" class="form-control">
                                <option value="" selected disabled>--Select Driver--</option>
                                <?php getDropDownListOrgStaffListNamesForVehicles(); ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Vehicle Number</label>
                            <select id="selectVehicleNo" class="form-control" name="vehicleNo">
                                <option value="" selected disabled>-- Select Vehicle No --</option>
                                <?php getVehicleNumberListForReport(); ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-8">
                            <label>Report Format</label>
                            <select id="selectDateType" class="form-control" onchange="changeInputType()" name="timeCategory">
                                <option value="">-- Select Month --</option>
                                <option value="2">Date</option>
                                <option value="0">Monthly</option>
                                <option value="1">Yearly</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <div id="inputMonth">
                                <label id="label">By Month</label>
                                <input id="inputMonthForm" class="form-control" type="month" name="dateMonth" required>
                            </div>
                            <div id="inputDate">
                                <label id="label">By Date</label>
                                <input id="inputDateForm" class="form-control" type="date" name="sdate" required>
                            </div>
                            <div id="inputYear" style="display:none">
                                <div class="form-group row">
                                    <label id="label">By Year</label>
                                    <select id="inputYearForm" class="form-control" name="dateYear" required>
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
                            <button class="btn btn-primary btn-lg btn-block" type="submit" name="tripViewSearch">Search</button>
                        </div>
                    </div>
                </form>
            </div>
            <form method="POST" action="<?php echo "https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/invoiceTrip/createTripInvoice.php" ?>" >
          <?php
          tripListTableEditable();
          ?>
                <button type="submit" class="btn btn-default" name="createTripInvoiceByCheckBox" value="1">Create Invoice</button>
            </form>
          </div>
        </div>
   <form method="POST" action="<?php echo "https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/phpfunctions/trip.php" ?>" >

<div class="modal fade" id="tripEditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="productEditModalTitle">Vehicle Details</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">

          <center><table style="width:100%">

            <tr class="">
                <td width="120px;"><label>Date</label></td><td><label id="vehicletype" class="text-center date">&nbsp;</label></td>
              </tr>
              <tr class="">
                <td><label>Client</label></td><td><label id="brand" class="text-center client">&nbsp;</label></td>
              </tr>
                 <tr class="">
                <td><label class="text-left">Truck NO</label></td><td><label id="category " class="text-center truck">&nbsp;</label></td>
              </tr>
              <tr class="">
                <td><label class="text-left">Place Description</label></td><td><label id="number" class="text-center place">&nbsp;</label></td>
              </tr>
              <tr class="">
                <td><label class="text-left">Shipment NO</label></td><td><label id="driver" class="text-center shipment">&nbsp;</label></td>
              </tr>
              <tr class="">
                <td><label class="text-left">Driver</label></td><td><label id="driver" class="text-center driver">&nbsp;</label></td>
              </tr>
              <tr class="">
                <td><label class="text-left">Amount</label></td><td><label id="driver" class="text-center amount">&nbsp;</label></td>
              </tr>
            </table>
            </center>
              
          </div>


     
      <div class="modal-footer">
          <input type="text" hidden name="tripToEdit" id="tripToEdit" value=""  />
          <button type="submit" name='editTrip' class="btn btn-primary" >Edit</button>
          <button type="submit" name='removeTrip' class="btn btn-primary" >Remove</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</form>
</div></div>
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