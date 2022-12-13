<?php
$config = parse_ini_file(__DIR__ . "/../../jsheetconfig.ini");
if (!isset($_SESSION)) {

    session_name($config['sessionName']);

    session_start();
} ?><!DOCTYPE html>
<html lang="en">
<head>
  <title>Document Sign</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
  <style>
    /* Remove the navbar's default rounded borders and increase the bottom margin */
    .navbar {
      margin-bottom: 50px;
      border-radius: 0;
    }

    /* Remove the jumbotron's default bottom margin */
     .jumbotron {
      margin-bottom: 0;
    }

    /* Add a gray background color and some padding to the footer */
    footer {
      background-color: #f2f2f2;
      padding: 25px;
    }
  </style>
</head>
<body>

  <div class="container text-center">
    <h1>Digital Sign</h1>
</div>
    <?php if(isset($_SESSION['message'])){echo $_SESSION['message']; unset($_SESSION['message']); }?>
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <form action="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/'; ?>document_sign_sow/upload_files" method="post" enctype="multipart/form-data">
        <ul class="nav navbar-nav">
          <li class="active"><a href="#"><?php require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientCompany.php");
                  dropDownListOrganizationClientCompanyActive3();   ?></a></li>
          <li class="active"><a href="#"><input type="file" name="file" class="form-control" id="file" required ></a></li>
          <li class="active"><a href="#"><input type="submit" value="upload" style="color:black" class="form-control" name="Upload"></a></li>

        </ul>
      </form>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar2">

    </div>

  </div>
</nav>

<div class="container">
  <div class="row">
    <div class="col-sm-12">
      <table id="example" class="display" style="width:100%">
          <thead>
              <tr>
                  <th>File Name</th>
                  <th>Size</th>
                  <th>Date Uploaded</th>
                  <th>Upload By</th>
                  <th>User</th>
                  <th>Sign</th>
                  <th>Shared</th>
                  <th>Actions</th>
              </tr>
          </thead>
          <tfoot>
              <tr>
                  <th>File Name</th>
                  <th>Size</th>
                  <th>Date Uploaded</th>
                  <th>Upload By</th>
                  <th>User</th>
                  <th>Sign</th>
                  <th>Shared</th>
                  <th>Actions</th>
              </tr>
          </tfoot>
      </table>
    </div>
  </div>
</div><br>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function () {
    $('#example').DataTable({
        ajax: '<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/'; ?>document_sign_sow/get_pdf_listings',
    });
  });
  function copylink(id) {
    // Get the text field
    var copyText = $("#copy_link_doc"+id).val();
    // Copy the text inside the text field
    navigator.clipboard.writeText(copyText);
    // Alert the copied text
    alert("Copied the text: " + copyText);
  }
</script>
</body>
</html>
