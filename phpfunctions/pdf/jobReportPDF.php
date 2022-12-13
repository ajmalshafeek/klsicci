<?php


//use Spipu\Html2Pdf\Html2Pdf; //exabyte no support
if(!isset($_SESSION))
{
 	session_name($config['sessionName']);
	session_start();
}
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");

//require $_SERVER['DOCUMENT_ROOT'].$config['appRoot'].'/external/html2pdf/vendor/autoload.php'; //exabyte no support
require $_SERVER['DOCUMENT_ROOT'].$config['appRoot'].'/external/mPDF/vendor/autoload.php';
//require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/quotation.php");
//require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/quotation.php");
function generateJobListPDF(){
	$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/clientCompany.php");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/vendor.php");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organizationUser.php");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");
  $con = connectDB();
  $orgId = $_SESSION['orgId'];

	$html2pdf=new \Mpdf\Mpdf();
    $info = "";
    //$info.= "<img src='../../resources/2/myOrg/bannerReport.png' width='70%'><br>";
    $info.= "<img src='".$_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/2/myOrg/banner/".bannerName($orgId)."' width='70%'><br>";
    if (isset($_SESSION['vendorIdReport'])) {
      $vendorIdReport=$_SESSION['vendorIdReport'];
      $vendorDetails=fetchVendorDetails($con,$vendorIdReport);
      $info.= "<p style='font-size: 8px'>VENDOR: ".$vendorDetails['name']."</p><br>";
    }
    if (isset($_SESSION['orgStaffIdReport'])) {
      $orgStaffIdReport=$_SESSION['orgStaffIdReport'];
      $orgStaffDetails=getOrganizationUserDetails($con,$orgStaffIdReport);
      $info.= "<p style='font-size: 8px'>STAFF: ".$orgStaffDetails['fullName']."</p><br>";
    }
    if (isset($_SESSION['clientCompanyIdReport'])) {
      $clientCompanyIdReport=$_SESSION['clientCompanyIdReport'];
      $clientCompanyDetails=fetchClientCompanyDetails($con,$clientCompanyIdReport);
      $info.= "<p style='font-size: 8px'>CLIENT: ".$clientCompanyDetails['name']."</p><br>";
    }
    if (isset($_SESSION['modelFilter'])) {
      $modelFilter=$_SESSION['modelFilter'];
      $info.= "<p style='font-size: 8px'>MODEL: ".$modelFilter."</p>";
      $serialNumFilter=$_SESSION['serialNumFilter'];
      $info.= "<p style='font-size: 8px'>SERIAL. NO: ".$serialNumFilter."</p>";
    }
    $info.= "<p style='font-size: 8px'>DATE: ".$_SESSION['dateReport']."</p><br>";

    if (isset($_SESSION['statusReport'])) {
      $info.= "<p style='font-size: 8px'>STATUS: ".$_SESSION['statusReport']."</p><br>";
    }


    $_SESSION['vendorIdReport'] = null;
    $_SESSION['orgStaffIdReport'] = null;
    $_SESSION['clientCompanyIdReport'] =null;
    $_SESSION['dateReport'] = null;
    $_SESSION['statusReport'] = null;
    $_SESSION['modelFilter'] = false;
    $_SESSION['serialNumFilter'] = false;
    $jobListTable=$_SESSION['tablePDF'];
    $htmlStart =
    "<html lang='en' dir='ltr'><head>";

    $style =
    "<style>
      table {
        border-collapse: collapse;
      }

      td {
        font-size: 8px
      }
      th {
        font-size: 8px
      }

      table, th, td {
        border: 1px solid black;
      }
    </style>
    </head>";
    $htmlEnd ="</html>";

    $overall = $htmlStart.$style.$info.$jobListTable.$htmlEnd;
//		$html2pdf->writeHTML($jobListTable);
    $html2pdf->writeHTML($overall);

		//$html2pdf->output();
		return $html2pdf;

	}
	?>
