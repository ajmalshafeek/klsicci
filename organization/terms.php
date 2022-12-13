<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");

if(!isset($_SESSION))
{
 	session_name($config['sessionName']);
	session_start();
}


?>




<!DOCTYPE html >

<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />

   <!--
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <script src="js/bootstrap.min.js" ></script>
-->

    <?php
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>

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

<body class="fixed-nav ">
<?php
  include $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/navMenu.php";
?>

<div class="content-wrapper">
    <div class="container-fluid">
    <?php echo shortcut() ?>
      <!-- Breadcrumbs-->
      <ol style="color: white" class="breadcrumb col-md-12">
        <li style = "color:white" class="breadcrumb-item">
          <a href="../home.php">Dashboard</a>
        </li>
        <li class="breadcrumb-item active"></li>Terms & Privacy
      </ol>

    </div>

    <div class="container" style="text-align:justify">


      <h1 style="font-family:brush script mt;">Terms</h1><hr>
      <p>If you use this jobsheet, you are agreeing to be bound by these Terms of Use without any modification
        or qualification. IF YOU ARE DISSATISFIED WITH THE TERMS, CONDITIONS, RULES, POLICIES, GUIDELINES
        OR PRACTICES OF OPERATING OUR SERVICE, UNLESS EXPRESSLY SET OUT IN THESE TERMS OF USE, YOUR SOLE AND
        EXCLUSIVE REMEDY IS TO DISCONTINUE USING THE SERVICE. If for any reason you are unable to meet
        all the conditions set forth in these Terms of Use, or if you breach any of the Terms of Use
        contained herein, your permission to use Jobsheet or access any of Jobsheet Financial’s Services (defined below)
        immediately lapses and you must destroy any materials downloaded or printed from the Jobsheet.</p>

      <h1 style="font-family:brush script mt;">Privacy</h1><hr>
      <p> This privacy policy applies to system and, its subdomains (the “Site”), and our mobile apps, all of which are owned and operated by JSoft It does not apply to any third-party websites, which have their own policies.
      Throughout this policy, when we say Jobsheet, we mean our company, including the Site and any Jobsheet mobile apps, and when we say Services we mean the various financial products and services, and apps we make available to you through our Site and mobile apps,
      including our payments, payroll, accounting, invoicing services, and other small business–related services and support.
      By “Personal Information”, we mean information about an identifiable individual. That’s what this policy is about – our collection, protection, use, retention, disclosure and other processing of
      Personal Information and your rights relating to these activities. We also compile certain aggregate data about our users.


    </div>


    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fa fa-angle-up"></i>
    </a>

            <div class="footer">
              <p>Powered by JSoft Solution Sdn. Bhd</p>
            </div>

          </div>

  </div>


</body>

</html>
