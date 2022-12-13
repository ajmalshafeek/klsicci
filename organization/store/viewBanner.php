<?php
$config = parse_ini_file(__DIR__ . "/../../jsheetconfig.ini");
if (!isset($_SESSION)) {
  session_name($config['sessionName']);
  session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/store_banner.php"); ?>
<!DOCTYPE html>

<html">

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/resources/app/favIcon.ico'; ?>' />


    <?php
    require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/importScripts.php");
    ?>
    <!-- datatable -->
    <script src='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/'; ?>adminTheme/datatables/jquery.dataTables.js'></script>
    <script src='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/'; ?>adminTheme/datatables/dataTables.bootstrap4.js'></script>
    <script src='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/'; ?>adminTheme/js/sb-admin-datatables.min.js'></script>
    <!-- datatable -->
    <?php if ($_SESSION['orgType'] == 7) { ?>
      <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
      <script src="//code.jquery.com/jquery-1.12.4.js"></script>
      <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <?php } ?>
    <script src='https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js'></script>
    <script src='https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js'></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">

    <script>
      function editBanner(id) {
        $.ajax({
          type: 'GET',
          url: '../../phpfunctions/store_banner.php?',
          data: {
            bannerData: id
          },
          success: function(data) {
            details = JSON.parse(data);
            console.log(data);
            $('#startDate').html(details.start == "" ? "&nbsp;" : details.start);
            $('#endDate').html(details.end == "" ? "&nbsp;" : details.end);
            $('#bannerImage').html(details.path == "" ? "&nbsp;" : '<img src="https://<?php echo $_SERVER['HTTP_HOST'] . $config['appRoot']; ?>/' + details.path + '" width="100%">');
            $('#bannerIdToEdit').val(id);
            $('.bannerIdToEdit').val(id);
          }
        });
      }

      function bannerDelete(str) {
        $("#clientIdToDelete").val(str.value);

      }

      function bannerEdit(str) {

        $("#clientIdToEdit").val(str.value);

      }
    </script>
    <style>
      .buttonAsLink {
        background: none !important;
        color: inherit;
        border: none;
        font: inherit;
        cursor: pointer;
      }

      .bg-red {
        background-color: #E32526;
      }

      table label {
        font-size: 1.2em;
        width: 100%;
      }

      td {
        vertical-align: middle !important;
      }
    </style>

  </head>

  <body class="fixed-nav ">

    <?php
    include $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/navMenu.php";
    ?>

    <div class="content-wrapper">
      <div class="container-fluid">
        <?php echo shortcut() ?>
        <!-- Breadcrumbs-->
        <ol class="breadcrumb col-md-12">
          <li class="breadcrumb-item">
            <a href="../../home.php">Dashboard</a>
          </li>
          <li class="breadcrumb-item ">Store</li>
          <li class="breadcrumb-item ">Banner</li>
          <li class="breadcrumb-item active">View Banner</li>
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
          Banner List
        </div>
        <?php
        bannerListTable();
        ?>

      </div>
    </div>
    <form method="POST" action="<?php echo "https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/phpfunctions/store_banner.php"; ?>">

      <div class="modal fade mcustom" id="bannerEditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="productEditModalTitle">Banner Details</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <center>
                <table style="width:100%">
                  <tr class="bannerImage">
                    <td colspan="2" id="bannerImage" class="text-center"></td>
                  </tr>
                  <tr class="startDate">
                    <td><label class="text-left">Start Date</label></td>
                    <td><label id="startDate" class="text-center">&nbsp;</label></td>
                  </tr>
                  <tr class="endDate">
                    <td><label class="text-left">End Date</label></td>
                    <td><label id="endDate" class="text-center">&nbsp;</label></td>
                  </tr>
                </table>
              </center>
            </div>

            <div class="modal-footer">
              <input type="text" hidden name="bannerIdToEdit" id="bannerIdToEdit" value="" />
              <button type="submit" name='editBannerId' class="btn btn-primary edit">Edit</button>
              <button type="button" data-toggle='modal' data-target='#bannerDeleteModal' class="btn btn-primary remove">Remove</button>
              <button type="button" class="btn btn-secondary cancel" data-dismiss="modal">Cancel</button>
            </div>
          </div>
        </div>
    </form>


    </div>
    </div>
    <form method="POST" action="<?php echo "https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/phpfunctions\store_banner.php" ?>">
      <div class="modal fade" id="bannerDeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="productTypeEditModalTitle">DELETE ACTION</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <div id='bannerEditContent'>
                Are you sure want to delete banner?
              </div>
            </div>
            <div class="modal-footer">
              <input type="text" hidden name="bannerIdToEdit" class="bannerIdToEdit" value="" />
              <button type="submit" name='removeBanner' class="btn btn-primary edit">Yes</button>
              <button type="button" class="btn btn-secondary remove" data-dismiss="modal">No</button>
            </div>
          </div>
        </div>
      </div>
    </form>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fa fa-angle-up"></i>
    </a>

    <div class="footer">
      <p>Powered by JSoft Solution Sdn. Bhd</p>
    </div>
    </div>
    <script>
      <?php if ($_SESSION['orgType'] == 7) { ?>


        $("#sbrand").autocomplete({
          source: <?php brandListJson(); ?>
        });

        $("#sproducttype").autocomplete({
          source: <?php productTypeListJson(); ?>
        });
      <?php } ?>

      $(document).ready(function() {
        $('#dataTableBanner').dataTable({

          dom: 'Bfrtip',
          //"sPaginationType": "full_numbers",
          //  "bRetrieve": true,
          // "bPaginate": true,
          // "bStateSave": true,
          "aLengthMenu": [
            [50, 100, 150, 200, -1],
            [50, 100, 150, 200, "All"]
          ],
          'dom': 'lBfrtip',
          "aLengthMenu": [
            [50, 100, 150, 200, -1],
            [50, 100, 150, 200, "All"]
          ],
          "lengthMenu": [
            [50, 100, 150, 200, -1],
            [50, 100, 150, 200, "All"]
          ],
          // "lengthChange": true,
          //'info': true,
          //'responsive': true,
          //"paging": true,

        });
      });
    </script>
  </body>

  </html>