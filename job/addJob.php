
<?php
session_name("JOBSHEET");
session_start();
require($_SERVER['DOCUMENT_ROOT']."/jobsheet/phpfunctions/clientCompany.php");
require($_SERVER['DOCUMENT_ROOT']."/jobsheet/phpfunctions/vendoruser.php");
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
      background:none!important;
      color:inherit;
      border:none; 
      font: inherit;
      cursor: pointer;

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
        <a class="nav-link" href="#">HOME<span class="sr-only">(current)</span></a>
      </li>
    <!--  
      <li class="nav-item">
        <a class="nav-link" href="#">CRITERIA</a>
      </li>
      -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
         JOB
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href='vote/result.php'>ADD JOB</a>
          
        </div>
      </li>
     
    </ul>
  </div>
</nav>
<h1 class="display-4">ADD NEW JOB</h1>
<hr/>
<div class="container">
  <form method="POST" action="../phpfunctions/job.php" class="needs-validation" novalidate >
    <?php
      dropDownListClientCompanyActive();
    ?>
    <div id="criteriaForm">
    
    <div class="form-group row">
      <label for="jobName" class="col-sm-2 col-form-label col-form-label-lg">JOB NAME</label>
      <div class="col-sm-10"   >
        <input type="text" class="form-control" id="jobName" name="jobName" required></input>
        <div class="invalid-feedback">
          Please enter job name
        </div>
      </div>
    </div>
    
    <div class="form-group row">
      <label for="address" class="col-sm-2 col-form-label col-form-label-lg">Job location</label>
      <div class="col-sm-10"   >
        <textarea class="form-control" id="address" name="address" required></textarea>
        <div class="invalid-feedback">
          Please enter job remarks
        </div>
      </div>
    </div>

    <div class="form-group row">
      <label for="picName" class="col-sm-2 col-form-label col-form-label-lg">Person in charge name</label>
      <div class="col-sm-10"   >
        <input type="text" class="form-control" id="picName" name="picName" required></input>
        <div class="invalid-feedback">
          Please enter name of person in charge
        </div>
      </div>
    </div>

    <div class="form-group row">
      <label for="picContactNo" class="col-sm-2 col-form-label col-form-label-lg">Person in charge contact no.</label>
      <div class="col-sm-10"   >
        <input type="text" class="form-control" id="picContactNo" name="picContactNo" required></input>
        <div class="invalid-feedback">
          Please enter contact no. of person in charge
        </div>
      </div>
    </div>

    <div class="form-group row">
      <label for="dateRequire" class="col-sm-2 col-form-label col-form-label-lg">Date required for job</label>
      <div class="col-sm-10"   >
        <input type="date" class='form-control' value="<?php echo date('Y-m-d'); ?>" id="dateRequire" name="dateRequire" />
        
        <div class="invalid-feedback">
          Please enter date required for job person in charge
        </div>
      </div>
    </div>

    <div class="form-group row">
      <label for="remarks" class="col-sm-2 col-form-label col-form-label-lg">Job remarks</label>
      <div class="col-sm-10"   >
        <textarea class="form-control" id="remarks" name="remarks" required></textarea>
        <div class="invalid-feedback">
          Please enter job remarks
        </div>
      </div>
    </div>

    <?php
      dropDownListVendorUserActive();
    ?>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label col-form-label-lg"></label>
        <div class="col-sm-10">
            <button name='addJob' class="btn btn-primary btn-lg btn-block" type='submit' >SUBMIT</button>
        </div>
    </div>

    
    </div>
  </form>
</div>

</body>
</html>

