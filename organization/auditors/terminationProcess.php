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

<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />


    <?php
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>
    <!-- datatable -->
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/jquery.dataTables.js'></script>
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/dataTables.bootstrap4.js'></script>
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/js/sb-admin-datatables.min.js'></script>
    <!-- datatable -->

    <script src='https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js'></script>
    <script src='https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js'></script>
    <script src='https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js'></script>
    <script src='https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js'></script>

    <script>
/*

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
      */


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
        <li class="breadcrumb-item ">Auditors</li>
        <li class="breadcrumb-item active">Termination Process</li>
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
                  Termination Process List
				  </div>
            <div class="row">
                <div class="col-12 text-center">
                    <img src="img/adm-termination-process-1.png" class="tech-1" style="max-width: 100%" />
                    <img src="img/adm-termination-process-2.png" class="tech-2" style="max-width: 100%" />
                    <img src="img/adm-termination-process-3.png" class="tech-3" style="max-width: 100%" />
                    <script>
                        $('.tech-2').css('display','none');
                        $('.tech-3').css('display','none');
                        $('.tech-1').on('click',function () {
                            $('.tech-2').css('display','initial');
                            $('.tech-1').css('display','none');
                        });
                        $('.tech-2').on('click',function () {
                            $('.tech-3').css('display','initial');
                            $('.tech-2').css('display','none');
                        });
                        $('.tech-3').on('click',function () {
                            $('.tech-1').css('display','initial');
                            $('.tech-3').css('display','none');
                        });
                    </script>
                    <?php /* if(isset($_POST['demo'])){
                        echo "<div class='alert-success py-2 px-2 text-center'>Successfully Submit</div>";
                    } else{?>
                        <form method="post">
                            <center><button type="submit" name="demo" class="btn btn-default mt-30" value="1">Submit</button></center>
                        </form> <?php } */ ?>
                </div>
            </div>
          <?php
         // tripListTableEditable();
          ?>
          </div>
        </div>
<?php /*
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
*/?>
</div></div>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fa fa-angle-up"></i>
    </a>

          <div class="footer">
            <p>Powered by JSoft Solution Sdn. Bhd</p>
          </div>
  </div><script>
    $(document).ready(function()
        /*
    let current = new Date();
    let cDate = current.getFullYear() + '-' + (current.getMonth() + 1) + '-' + current.getDate();
    let cTime = current.getHours() + ":" + current.getMinutes() + ":" + current.getSeconds();
    let dateTime = cDate + ' ' + cTime;
    var temp="Trip List "+dateTime;
        $('#dtable').DataTable( {
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'print',title: temp,
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7]
                    }
                },
                {
                    extend: 'copyHtml5',title: temp,
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7]
                    }
                },
                {
                    extend: 'excelHtml5',title: temp,
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7]
                    }
                },
                {
                    extend: 'pdfHtml5',title: temp,
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7]
                    }
                }
            ]
        } );
    } */ );
</script>
</body>
</html>