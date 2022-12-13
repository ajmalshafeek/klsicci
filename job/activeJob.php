
<?php
session_name("JOBSHEET");
session_start();
require($_SERVER['DOCUMENT_ROOT']."/jobsheet/phpfunctions/job.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script src="../js/jquery-3.3.1.slim.min.js" ></script>
    <script src="../js/bootstrap.min.js" ></script>
    
    <style>
     .buttonAsLink{
      display: block;
    width: 115px;
    height: 25px;
    background: #4E9CAF;
    padding: 10px;
    text-align: center;
    border-radius: 5px;
    color: white;
    font-weight: bold;

    }
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
            .bg-red{
                background-color: #E32526;
            }
    </style>
   
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark  bg-red">
  <a class="navbar-brand" href="#">B.R.A.G.(<span style='color:black;text-transform: uppercase;'>
  <?php
  echo $_SESSION['name'];
  ?>
  </span>)</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].'/jobsheet/home.php'; ?>'>HOME<span class="sr-only">(current)</span></a>
      </li>
   
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
         CLIENT
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].'/jobsheet/organization/client/addClient.php'; ?>'>ADD CLIENT</a>
          <div class="dropdown-divider"></div>
          <!--
          <a class="dropdown-item" href="job/activeJob.php">VIEW CLIENT</a>
          -->
        </div>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
         VENDOR
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].'/jobsheet/organization/vendor/addVendorClient.php'; ?>' >LINK CLIENT</a>
        <a class="dropdown-item" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].'/jobsheet/organization/vendor/addJobList.php'; ?>' >ADD JOB LIST</a>
          <div class="dropdown-divider"></div>
        <!-- 
           <a class="dropdown-item" href="job/activeJob.php">VIEW VENDOR</a>
        -->
        </div>
      </li>
    
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
         REPORT
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].'/jobsheet/organization/reportByVendor.php'; ?>'>BY VENDOR</a>
        </div>
      </li>
    </ul>
  </div>
</nav>
<h1 class="display-4">ACTIVE JOB</h1>
<hr/>
<div class="container">
  <form method="POST" action="../job/updateJob.php" class="needs-validation" novalidate >
    <?php 
      activeJobTable();
    ?>
  </form>
</div>

</body>
</html>

