<?php
 $config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
 if(!isset($_SESSION))
 {
  session_name($config['sessionName']);
	session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/maintenance.php");
?>
<!DOCTYPE html >

<html">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />


    <?php
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>
    <!-- datatable -->
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/jquery.dataTables.js'></script>
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/dataTables.bootstrap4.js'></script>
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/js/sb-admin-datatables.min.js'></script>

    <script src='https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js'></script>
    <script src='https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js'></script>
    <script src='https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js'></script>
    <script src='https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js'></script>
    <!-- datatable -->
    <script>


      function clientDelete(str){

        $("#clientIdToDelete").val(str.value);

      }

      function maintenanceForEdit(str){
          var id=str.value;
              $("#maintenanceIdToEdit").val(id);

          $.ajax({

              type  : 'GET',
              url  : '../../phpfunctions/maintenance.php?',
              data : {maintenanceDetail:id},
              success: function (res) {
                  const data= JSON.parse(res);
                  console.log("\n"+data.vehicle_type);
                   $('.date').html(data.date==""?"&nbsp;":data.date);
                   $('.type').html(data.vehicle_type==""?"&nbsp;":data.vehicle_type);
                   $('.vnum').html(data.vehicle_no==""?"&nbsp;":data.vehicle_no);
                   $('.mfor'). html(data.maintenance==""?"&nbsp;":data.maintenance);
                   $('.ndate'). html(data.next_date==""?"&nbsp;":data.next_date);
                   $('.reminder'). html(data.reminder==""?"&nbsp;":data.reminder);


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
table label {
    font-size: 1.2em;
    width: 100%;
}
    button.dt-button {
        padding: 5px 15px;
        border: 0px;
        border-radius: 3px;
        margin-bottom: 10px;
        color: #fff;
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
        <li class="breadcrumb-item ">Maintenance</li>
        <li class="breadcrumb-item active">View Maintenance</li>
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
            Maintenance List
				  </div>
          <?php
          fetchMaintenanceTableList();
          ?>
          </div>
        </div>
   <form method="POST" action="<?php echo "https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/phpfunctions/maintenance.php" ?>" >

<div class="modal fade" id="maintenanceForEditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="productEditModalTitle">Maintenance Details</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <center><table style="width:100%">

            <tr class="vehicletype">
                <td width="120px;"><label>Date</label></td><td><label id="date" class="text-center date">&nbsp;</label></td>
              </tr>
              <tr class="">
                <td><label>Vehicle Type</label></td><td><label id="type" class="text-center type">&nbsp;</label></td>
              </tr>
                 <tr class="category">
                <td><label class="text-left">Vehicle No</label></td><td><label id="vnum" class="text-center vnum">&nbsp;</label></td>
              </tr>
              <tr class="number">
                <td><label class="text-left">Maintenance For</label></td><td><label id="mfor" class="text-center mfor">&nbsp;</label></td>
              </tr>
              <tr class="driver">
                <td><label class="text-left">Next Date</label></td><td><label id="ndate" class="text-center ndate">&nbsp;</label></td>
              </tr>
                  <tr class="driver">
                <td><label class="text-left">Remider</label></td><td><label id="reminder" class="text-center reminder">&nbsp;</label></td>
              </tr>
            </table>
            </center>
              
          </div>


     
      <div class="modal-footer">
          <input type="text" hidden name="maintenanceIdToEdit" id="maintenanceIdToEdit" value=""  />
          <button type="submit" name='editMaintenance' class="btn btn-primary" >Edit</button>
          <button type="submit" name='removeMaintenance' class="btn btn-primary" >Remove</button>
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
<script>
    $(document).ready(function() {
        let current = new Date();
        let cDate = current.getFullYear() + '-' + (current.getMonth() + 1) + '-' + current.getDate();
        let cTime = current.getHours() + ":" + current.getMinutes() + ":" + current.getSeconds();
        let dateTime = cDate + ' ' + cTime;
        var temp="Maintenance List "+dateTime;
        $('#dtable').DataTable( {
            dom: 'Blfrtip',
            buttons: [
                {
                extend: 'print',title: temp,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6]
                }
            },
                {
                    extend: 'copyHtml5',title: temp,
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6]
                    }
                },
                {
                    extend: 'excelHtml5',title: temp,
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6]
                    }
                },
                {
                    extend: 'pdfHtml5',title: temp,
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6]
                    }
                }
            ]
        } );
    } );
</script>
</body>
</html>