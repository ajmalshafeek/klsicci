
<?php
session_start();
require("../phpfunctions/isLogin.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/bootstrap4.min.css">
    <script src="../js/jquery-3.3.1.slim.min.js" ></script>
    <script src="../js/bootstrap.min.js" ></script>
    
  
    <script>
       
       
 // handle custom file inputs
 $('body').on('change', 'input[type="file"][data-toggle="custom-file"]', function (ev) {

const $input = $(this);
const target = $input.data('target');
const $target = $(target);

if (!$target.length)
  return console.error('Invalid target for custom file', $input);

if (!$target.attr('data-content'))
  return console.error('Invalid `data-content` for custom file target', $input);

// set original content so we can revert if user deselects file
if (!$target.attr('data-original-content'))
  $target.attr('data-original-content', $target.attr('data-content'));

const input = $input.get(0);

let name = _.isObject(input)
  && _.isObject(input.files)
  && _.isObject(input.files[0])
  && _.isString(input.files[0].name) ? input.files[0].name : $input.val();

if (_.isNull(name) || name === '')
  name = $target.attr('data-original-content');

$target.attr('data-content', name);

});
    
       
    </script>
    <style>
    tr,td{
      font-family: "Lucida Console";
      text-align:center;
      //border:1px solid black;
    }
    .profileCard{
      width:100%;
      background: linear-gradient(to bottom right, #33ccff 0%, #e32526 100%);
     // background: linear-gradient(to bottom, #33ccff 0%, #ff99cc 100%);
      // background-color: #E32526;
      border-collapse: collapse;
    }
    
    .profileCard td:nth-child(1),td:nth-child(3) {
      width:40%;
    }
    .profileCard tr:nth-child(2) td:nth-child(2) {
      width:20px;
    }
    
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
        hr {
            margin-top: 1rem;
            margin-bottom: 1rem;
            border: 0;
            border-top: 2px solid rgba(0, 0, 0, 0.1);
        }
/* Valid for any language */
.custom-file-control:before {
	content: "Search";
}
.custom-file-control:empty::after {
	content: "Choose a file...";
}

    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark  bg-red">
<a class="navbar-brand" href="#">B.R.A.G.(<span style='color:black;text-transform: uppercase;'>
  <?php
  echo $_SESSION['fullName'];
  ?>
  </span>)</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="../home.php">HOME<span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item dropdown ">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        BEST PERFORMER - VOTE
        </a>
        <div class="dropdown-menu " aria-labelledby="navbarDropdown">
          <a class="dropdown-item " href="../vote/vote.php">START VOTE</a>
          <div class="dropdown-divider"></div>
          <?php if(isAdmin()===true) {?>
          <a class="dropdown-item" href='../vote/result.php'>RESULT</a>
          <?php }?>
        </div>
      </li>
      <li class="nav-item dropdown ">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        MARKS
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        <?php if(isGroupLeader()===true) {?>
          <a class="dropdown-item" href="../criteria/criteria.php">UPDATE MARKS</a>
          <?php } ?>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="../criteria/myGroupCriteria.php">MY GROUP MARKS</a>
          <?php if(isAdmin()===true) {?>
          <a class="dropdown-item" href="../criteria/allGroupCriteria.php">ALL GROUP MARKS</a>
          <?php }?>

        </div>
      </li>
      <li class="nav-item dropdown active">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
         MY PROFILE
        </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item active" href="../myprofile/changePassword.php">CHANGE PASSWORD</a>
              <div class="dropdown-divider"></div>
            <form action="../phpfunctions/logout.php" "POST" method="POST">
              <button class="dropdown-item buttonAsLink" name="logout" href="#">LOG OUT</button>
            </form>
          </div>
      </li>
    </ul>
  </div>
</nav>
<h1 class="display-4"></h1>
<hr/>

<div class="container">

<div class="tab-content">

<div role="tabpanel" class="tab-pane active" id="information">
    <div class="form-group row">
      <label for="refNo" class="col-sm-2 col-form-label col-form-label-lg">REF NO</label>
      <div class="col-sm-10"   >
      
      </div>
    </div>
    <div class="form-group row">
      <label for="clientName" class="col-sm-2 col-form-label col-form-label-lg">CLIENT</label>
      <div class="col-sm-10"   >
   
      </div>
    </div>

    <div class="form-group row">
      <label for="jobName" class="col-sm-2 col-form-label col-form-label-lg">JOB NAME</label>
      <div class="col-sm-10"   >
      
      </div>
    </div>

    <div class="form-group row">
      <label for="clientName" class="col-sm-2 col-form-label col-form-label-lg">REQUIRED DATE</label>
      <div class="col-sm-10"   >
      
      </div>
    </div>

    <div class="form-group row">
      <label for="remarks" class="col-sm-2 col-form-label col-form-label-lg">REMARKS</label>
      <div class="col-sm-10"   >
      
      </div>
    </div>

    <div class="form-group row">
      <label for="contactPerson" class="col-sm-2 col-form-label col-form-label-lg">CONTACT PERSON</label>
      <div class="col-sm-10"   >
      
      </div>
    </div>

    <div class="form-group row">
      <label for="contactNo" class="col-sm-2 col-form-label col-form-label-lg">CONTACT NO</label>
      <div class="col-sm-10"   >
      
      </div>
    </div>

    <div class="form-group row">
      <label for="clientName" class="col-sm-2 col-form-label col-form-label-lg">LOCATION</label>
      <div class="col-sm-10"   >
    
      </div>
    </div>

</div>

<div role="tabpanel" class="tab-pane fade in"  id="update">
    <form method="POST" action="../phpfunctions/job.php" class="needs-validation" novalidate >
          
      <div id="jobUpdate">        
        <div class="form-group row">
          <label for="remarks" class="col-sm-2 col-form-label col-form-label-lg">Remarks</label>
          <div class="col-sm-10"   >
            <textarea class="form-control" id="remarks" name="remarks" required></textarea>
            <div class="invalid-feedback">
              Please enter job remarks
            </div>
          </div>
        </div>

        <div class='form-group row' id='signatureParent' >
          <label for='signature' class='col-sm-2 col-form-label col-form-label-lg'  >SIGNATURE</label>
            <div class='col-sm-10' >
              <div id="signature" name="signature"></div>
            </div>
        </div>
        

        <div class='form-group row' >
          <label for='status' class='col-sm-2 col-form-label col-form-label-lg' >Status</label>
          <div class='col-sm-10'  >
            <select name='status' class='form-control form-control-lg' id='status' required>
              <option value="0" >complete</option>
              <option value="2" >pending</option>
              <option value="3" >in progress</option>
            </select>
          </div>
        </div>
        <input type="hidden" value="" id="imageBase64" name="imageBase64" />
        <!--
        <input type="image" src='' id='sign_prev' name="sign_prev" style='display: none;' /> 
        <input
          type="submit" name="submit">
        -->
        <div class="form-group row">
            <label class="col-sm-2 col-form-label col-form-label-lg"></label>
            <div class="col-sm-10">
                <button name='updateJobUser' value='' class="btn btn-primary btn-lg btn-block" type='submit' >SUBMIT</button>
            </div>
        </div>
      
      
      </div>
    </form>

</div>
</div>

<form method="POST" action="../phpfunctions/criteria.php" class="needs-validation" novalidate >

    <div class="form-group row">
      <label for="profilePicture" class="col-sm-2 col-form-label col-form-label-lg">PROFILE PICTURE</label>
      <div class="col-sm-10"   >
      <label class="custom-file d-block">
            <input data-toggle="custom-file" data-target="#company-logo" type="file" name="company_logo" accept="image/png" class="custom-file-input">
            <span id="company-logo" class="custom-file-control custom-file-name" data-content="Upload company logo..."></span>
          </label>
      </div>
    </div>

    <div class="form-group row">
      <label for="name" class="col-sm-2 col-form-label col-form-label-lg">NAME</label>
      <div class="col-sm-10"   >
        <input type="text" class="form-control"  id="name" name="name" required></input>
      </div>
    </div>
    
    <div class="form-group row">
      <label for="position" class="col-sm-2 col-form-label col-form-label-lg">POSITION</label>
      <div class="col-sm-10"   >
        <input type="text" class="form-control"  id="position" name="napositionme" required></input>
      </div>
    </div>

     <div class="form-group row">
      <label for="country" class="col-sm-2 col-form-label col-form-label-lg">COUNTRY</label>
      <div class="col-sm-10"   >
        <input type="text" class="form-control"  id="country" name="country" required></input>
      </div>
    </div>

     <div class="form-group row">
      <label for="contactNo" class="col-sm-2 col-form-label col-form-label-lg">CONTACT NO</label>
      <div class="col-sm-10"   >
        <input type="text" class="form-control"  id="contactNo" name="contactNo" required></input>
      </div>
    </div>

     <div class="form-group row">
      <label for="email" class="col-sm-2 col-form-label col-form-label-lg">EMAIL</label>
      <div class="col-sm-10"   >
        <input type="text" class="form-control"  id="email" name="email" required></input>
      </div>
    </div>

  </div>
</form>

</div>

</body>
</html>

