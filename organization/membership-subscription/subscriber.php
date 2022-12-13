<?php
 $config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
 if(!isset($_SESSION))
 {
  session_name($config['sessionName']);
	session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/subscriber.php");

?>
<!DOCTYPE html >

<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta https-equiv="Content-Type" content="text/html; charset=utf-8">


    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />


    <?php
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
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
    <!-- datatable -->
    <script>


      function clientDelete(str){

        $("#clientIdToDelete").val(str.value);

      }

      function clientEdit(str){

        $("#clientIdToEdit").val(str.value);
        $(".clientIdToEdit").val(str.value);
      }
      function memberEdit(str,state){
        $("#clientIdToEdit").val(str.value);
        $(".clientIdToEdit").val(str.value);
        $("#statusRg").val(state);
        if(state>0){
            $("#activate").css('display','none');
            $("#deactivate").css('display','flex');
        }else{
            $("#activate").css('display','flex');
            $("#deactivate").css('display','none');
        }
      }
      function payEdit(str,state){
        $("#clientIdToEdit").val(str.value);
        $(".clientIdToEdit").val(str.value);
        $("#statusPy").val(state);
        if(state>0){
            $("#payed").css('display','none');
            $("#pending").css('display','flex');
        }else{
            $("#payed").css('display','flex');
            $("#pending").css('display','none');
        }
      }


    <?php  if(isset($_SESSION['downloadclients']) && $_SESSION['downloadclients']){ ?>
    $(document).ready(function() {
    $('#dataTable2').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            { extend: 'excel', text: 'Download Excel' ,
                title:'Client list',
                exportOptions: {
                    columns: ':not(:last-child)',
                }

            }
        ]
    } );
    } );
        <?php } ?>

      function showPassword(pwdId) {

          var x = document.getElementById(pwdId);
          if (x.type === "password") {
              x.type = "text";
          } else {
              x.type = "password";
          }

      }
      function cdetails(phoneNo,email){
        //  alert(phoneNo+","+email);
            $('#emailid').html(email);
            $('#phno').html(phoneNo);
            $("#contactDetails").modal();
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
        <li class="breadcrumb-item ">Membership Subscriber</li>
        <li class="breadcrumb-item active">New Request For Members</li>
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
            New Request Members List
				  </div>
          <?php
          newMemberRequestListTable();
       //   if (isset($_SESSION['downloadclients'])&&$_SESSION['downloadclients']) { clientListDataTable(); /*for excel export*/ }
          ?>
          </div>
        </div>
    </div>


<form method="POST" action="<?php echo "https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/client/addClient.php" ?>" >

    <div class="modal fade" id="clientEditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="clientEditModalTitle">ACTIONS</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div id='staffEditContent' >
                        What action do you wish to do for the client?
                    </div>

                    <div class="modal-footer">
                        <input type="text" hidden name="clientIdToEdit" id="clientIdToEdit" class="clientIdToEdit" value=""  />
                        <!-- <button type="submit" name='addNewClient' class="btn btn-primary edit" value="0" >Add potential Client </button> -->
                        <button type="submit" name='addNewClient' class="btn btn-primary remove" value="1" >Add Member</button>
                        <button type="button" data-toggle='modal' data-target='#removeNewClientModel' class="btn btn-primary edit" >Remove</button>
                        <button type="button" class="btn btn-secondary remove" data-dismiss="modal">CANCEL</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<form method="POST" action="<?php echo "https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/phpfunctions/subscriber.php" ?>" >

    <div class="modal fade" id="regEditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="clientEditModalTitle">ACTIONS</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div id='staffEditContent' >
                        Are you sure want to for the member?
                    </div>

                    <div class="modal-footer">
                        <input type="text" hidden name="clientIdToEdit" id="clientIdToEdit" class="clientIdToEdit" value=""  />
                        <input type="text" hidden name="statusRg" id="statusRg" value=""  />
                        <!-- <button type="submit" name='addNewClient' class="btn btn-primary edit" value="0" >Add potential Client </button> -->
                        <button type="submit" name='updateMembershipStatus' id="activate" class="btn btn-primary edit" value="1" >Activate</button>
                        <button type="submit" name='updateMembershipStatus' id="deactivate" class="btn btn-primary edit" value="0" >De-Activate</button>
                        <button type="button" class="btn btn-secondary remove" data-dismiss="modal">CANCEL</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<form method="POST" action="<?php echo "https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/phpfunctions/subscriber.php" ?>" >

    <div class="modal fade" id="payEditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="clientEditModalTitle">ACTIONS</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div id='staffEditContent' >
                        Are you sure want to for the member?
                    </div>

                    <div class="modal-footer">
                        <input type="text" hidden name="clientIdToEdit" id="clientIdToEdit" class="clientIdToEdit" value=""  />
                        <input type="text" hidden name="statusPy" id="statusPy" value=""  />
                        <!-- <button type="submit" name='addNewClient' class="btn btn-primary edit" value="0" >Add potential Client </button> -->
                        <button type="submit" name='updatePaymentStatus' id="payed" class="btn btn-primary edit" value="1" >Paid</button>
                        <button type="submit" name='updatePaymentStatus' id="pending" class="btn btn-primary edit" value="0" >Unpaid</button>
                        <button type="button" class="btn btn-secondary remove" data-dismiss="modal">CANCEL</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- (END)Show Clinet Product -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fa fa-angle-up"></i>
    </a>

          <div class="footer">
            <p>Powered by JSoft Solution Sdn. Bhd</p>
          </div>
  </div>

<form method="POST" action="<?php echo "https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/phpfunctions/registration.php" ?>" >

    <div class="modal fade" id="removeNewClientModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productTypeEditModalTitle">DELETE ACTION</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div id='staffEditContent' >
                        Are you sure want to delete request?
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="text" hidden name="clientIdToEdit" class="clientIdToEdit" value=""  />
                    <button type="submit" name='removeNewClientRequest' class="btn btn-primary edit" >Yes</button>
                    <button type="button" class="btn btn-secondary remove" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="modal fade" id="contactDetails" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productTypeEditModalTitle">CONTACT DETAILS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body ">
                <div class="row">
                    <div class="col-12">
                        <small>Phone Number</small>
                        <label class="col-12 form-control" id="phno"></label>
                    </div>
                    <div class="col-12">
                        <small>Email ID</small>
                        <label class="col-12 form-control" id="emailid"></label>
                    </div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary remove" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>