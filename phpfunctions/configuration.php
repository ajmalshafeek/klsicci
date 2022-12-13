<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
if(!isset($_SESSION))
{
 	session_name($config['sessionName']);
	session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");


	if(isset($_POST['updateQuotSetting'])){
		$_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
		<strong>FAILED!</strong> FAILED TO UPDATE SETTING \n
		</div>\n";
		$con=connectDb();
		$isStamp=0;
		if(isset($_POST['stampEnabled'])){
			$isStamp=1;
		}
		$isLogo=0;
		if(isset($_POST['logoEnabled'])){
			$isLogo=1;
		}
		if(isset($_POST['signEnabled'])){
			$isSign=1;
		}
		$base64Logo=$_FILES['logoImage']['tmp_name'];
		$base64Image=$_FILES['stampImage']['tmp_name'];
		$base64Sign=$_POST['signImage'];



		$extraNote=$_POST['extraNote'];
		$headerNote=$_POST['headerNote'];
		$replaceLogo=0;
		if(isset($_POST['replaceLogo'])){
		$replaceLogo=$_POST['replaceLogo'];}
		$updateSign=false;
		if(isset($_POST['updateSign'])){
			$updateSign=true;
		}

		$orgId=$_SESSION['orgId'];
		$stampName="";

		if(!file_exists($_FILES['stampImage']['tmp_name']) || !is_uploaded_file($_FILES['stampImage']['tmp_name'])) {
			$stampName=substr($_POST['uploadFileLabel'],0,strlen($_POST['uploadFileLabel'])-4);
		}else{
			$stampName=time();
		}
		$signName="";
		//	$signName=substr($_POST['uploadSignLabel'],0,strlen($_POST['uploadSignLabel'])-4);
			if(empty($_POST['signName'])){
				$signName=time();
			}elseif($updateSign && !empty($base64Sign)){
				$signName=time();
			}else{
			$signName=$_POST['signName'];}


		$logoName="";
		if(!file_exists($_FILES['logoImage']['tmp_name']) || !is_uploaded_file($_FILES['logoImage']['tmp_name'])) {
			$logoName=substr($_POST['uploadLogoLabel'],0,strlen($_POST['uploadLogoLabel'])-4);

		}else{
			$logoName=time();
		}

		$extraNote=nl2br($extraNote);
		$headerNote=nl2br($headerNote);
		$quotationMailBody=$_POST['quotationBody'];
		$invoiceMailBody=$_POST['invoiceBody'];

		$quotationMailBody=nl2br($quotationMailBody);
		$invoiceMailBody=nl2br($invoiceMailBody);
		$stampDirectory=$_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/".$orgId."/myOrg/";
		if (!file_exists($stampDirectory)) {
			mkdir($stampDirectory, 0777, true);
			copy($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/index.php", $stampDirectory.'/index.php');
		}

 		if($isSign&&isset($base64Sign) || $isSign&&!empty($base64Sign)) {
 			if($updateSign && !empty($base64Sign)){
			list(, $base64Sign) = explode(',', $base64Sign);
			$base64Sign = base64_decode($base64Sign);
			file_put_contents($stampDirectory . '/' . $signName . '.png', $base64Sign);
			copy($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/index.php", $stampDirectory . '/index.php');
 			}
		}
		move_uploaded_file($base64Image,$stampDirectory."/$stampName.png");
		//move_uploaded_file($base64Sign,$stampDirectory."/$signName.png");
		move_uploaded_file($base64Logo,$stampDirectory."/$logoName.png");


		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/quotationConfig.php");

		$updateSuccess=updateQuotationConfig($con,$isStamp,$extraNote,$stampName,$quotationMailBody,$invoiceMailBody,$replaceLogo,$headerNote,$isLogo,$logoName,$isSign,$signName,$orgId);

		$footerId=$_POST['pdfFooter'];
		$content=$extraNote;
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/pdffooterlist.php");
		$updateSuccess=updatePdfFooterList($con,$footerId,$content);

		if($updateSuccess){
			$_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
			<strong>SUCCESS!</strong> SETTING UPDATED SUCCESSFULLY  \n
			</div>\n";
		}

		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/setting/qiSetting.php");


	}

	if(isset($_GET['footerContent'])){

		$con=connectDb();
		$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/pdffooterlist.php");

		$footerId=$_GET["footerId"];
		$orgId=$_SESSION["orgId"];
		$pdfFooterList=fetchPdfFooterList($con,$footerId,$orgId);
		$content=$pdfFooterList[0]['content'];
		$content=preg_replace('/<br\s?\/?>/ius', "\n", str_replace("\n","",str_replace("\r","", htmlspecialchars_decode($content))));
		echo $content;

	}

	if(isset($_POST['createPdfFooter'])){
		$con=connectDb();
		$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/pdffooterlist.php");

		$_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
		<strong>FAILED!</strong> FAILED TO CREATE FOOTER for PDF \n
		</div>\n";

		$name=$_POST["footerName"];
		$content=$_POST["footerContent"];
		$content=nl2br($content);
		$orgId=$_SESSION["orgId"];

		$success=createPdfFooter($con,$name,$content,$orgId);
		if($success){
			$_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
			<strong>SUCCESS!</strong> SUCCESSFULLY CREATED FOOTER for PDF  \n
			</div>\n";
		}

		header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/setting/qiSetting.php");

	}

	function getPdfConfiguration($orgId){
		$con=connectDb();
		$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");


		$getPdfConfiguration=fetchQuotationConfig($con,$orgId);
		return $getPdfConfiguration;
		//echo sizeof($pdfFooterList);
	}

	function getPdfFooterList($footerId,$orgId){

		$con=connectDb();
		$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/pdffooterlist.php");


		$pdfFooterList=fetchPdfFooterList($con,$footerId,$orgId);
		return $pdfFooterList;
		//echo sizeof($pdfFooterList);
	}

	function getQuotationConfig($orgId){
		$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
		require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/quotationConfig.php");

		$con=connectDb();

		$quotConfig=fetchQuotationConfig($con,$orgId);


		return $quotConfig;
	}

  function footerButtonRemove(){
    $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/pdffooterlist.php");
    $con=connectDb();
    $footerId = NULL;
    $orgId = $_SESSION['orgId'];
    $dataList = fetchPdfFooterList($con,$footerId,$orgId);
    $button="<center>";
    foreach($dataList as $data){
      $button .="<a href='../../phpfunctions/configuration.php?removeFooter=".$data['id']."'><button type='button' class='btn col-sm-12' style='margin: 3px;'>".$data['name']."</button></a><br>";
    }
    $button .="</center>";
    echo $button;
  }

  if(isset($_GET['removeFooter'])) {
    $_SESSION['feedback'] = "<div class='alert alert-warning' role='alert'>\n
    <strong>FAILED!</strong> AN ERROR OCCUR \n
    </div>\n";
    $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/pdffooterlist.php");
    $con=connectDb();
    $id = $_GET['removeFooter'];
    $success = removeFooter($con, $id);
    if ($success) {
      $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
			<strong>SUCCESS!</strong> FOOTER SUCCESSFULLY REMOVE \n
			</div>\n";
    }
    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/setting/qiSetting.php");
  }

  if (isset($_GET['format'])) {
    $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
    <strong>FAILED!</strong> FAILED TO UPDATE FORMAT \n
    </div>\n";
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/quotationConfig.php");
    $con=connectDb();
    $orgId = $_SESSION['orgId'];
    $format = $_GET['format'];
    $feedback = updateFormat($con,$format,$orgId);

    //OVERWRITE "quotationPDF.php"
    copy($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/".$_SESSION['orgId']."/qi/qiPDF/".$format."/quotationPDF.php", $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/pdf/quotationPDF.php");

    //OVERWRITE "invoicePDF.php"
    copy($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/".$_SESSION['orgId']."/qi/qiPDF/".$format."/invoicePDF.php", $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/pdf/invoicePDF.php");

    if ($feedback) {
      $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
      <strong>SUCCESS!</strong> FORMAT IS SUCCESSFULLY UPDATED \n
      </div>\n";
    }
    header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/setting/qiSetting.php");
  }
?>
