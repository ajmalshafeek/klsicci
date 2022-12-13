<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
date_default_timezone_set("Asia/Kuala_Lumpur");
if(!isset($_SESSION))
{
 	session_name($config['sessionName']);
	session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/claim.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organization.php");
$con = connectDb();

function orgTypeDropDownList(){
  $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organizationType.php");
  $con = connectDb();
  $dataList = fetchOrgTypeList($con);
  $orgId = 2;
  $row = getOrganizationDetails($con,$orgId);
  $radio = '<div class="form-group">';
  //(START)STANDARD
  if ($row['type']==0) {
    $tag = "checked";
  }else{
    $tag = "";
  }
  $radio.='
  <div class="radio">
    <label><input type="radio" value="0" name="orgType" '.$tag.'>Standard</label>
  </div>
  ';
  //(END)STANDARD
  //(START)OTHER ORG TYPE
  foreach ($dataList as $data) {
    if ($row['type']==$data['id']) {
      $tag = "checked";
    }else{
      $tag = "";
    }
    $radio.='
    <div class="radio">
      <label><input type="radio" value="'.$data['id'].'" name="orgType" '.$tag.'>'.$data["orgType"].'</label>
    </div>
    ';
  }
  //(END)OTHER ORG TYPE
  $radio.='</div>';

  echo $radio;
}

	function appUseInOrganization()
	{
		$config = parse_ini_file(__DIR__ . "/../jsheetconfig.ini");
		require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/connect.php");
		require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/organizationType.php");
		$con = connectDb();
		$dataList = fetchOrgUse($con);
		$orgId = 2;
		$radio = '<div class="form-group">';
		//(START)OTHER ORG TYPE
		foreach ($dataList as $data) {
			if (1 == $data['internal']) {
				$tag = "checked";
			} else {
				$tag = "";
			}
			$radio .= '
    <div class="radio">
      <label><input type="checkbox" value="1" name="internal" ' . $tag . '>Internal Organization</label>
    </div>
    ';

				if (1 == $data['external']) {
					$tag = "checked";
				} else {
					$tag = "";
				}
				$radio .= '
    <div class="radio">
      <label><input type="checkbox" value="1" name="external" ' . $tag . '>External Organization</label>
    </div>
    ';
			if (1 == $data['staffcam']) {
				$tag = "checked";
			} else {
				$tag = "";
			}
			$radio .= '
    <div class="radio">
      <label><input type="checkbox" value="1" name="staffcam" ' . $tag . '>Staff Camera Feature</label>
    </div>
    ';
			if (1 == $data['supportcam']) {
				$tag = "checked";
			} else {
				$tag = "";
			}
			$radio .= '
    <div class="radio">
      <label><input type="checkbox" value="1" name="supportcam" ' . $tag . '>Support Camera Feature</label>
    </div>
    ';
			if (1 == $data['membership']) {
				$tag = "checked";
			} else {
				$tag = "";
			}
			$radio .= '
    <div class="radio">
      <label><input type="checkbox" value="1" name="membership" ' . $tag . '>Membership Feature</label>
    </div>
    ';
			if (1 == $data['staffloan']) {
				$tag = "checked";
			} else {
				$tag = "";
			}
			$radio .= '
    <div class="radio">
      <label><input type="checkbox" value="1" name="staffloan" ' . $tag . '>Staff Loan Feature</label>
    </div>
    ';
            if (1 == $data['location']) {
                $tag = "checked";
            } else {
                $tag = "";
            }
            $radio .= '
    <div class="radio">
      <label><input type="checkbox" value="1" name="location" ' . $tag . '>Attendance Location Feature</label>
    </div>
    ';
			$radio .= '
    <div class="radio">
    <fieldset><legend class="legendclientpass">Client Password Column</legend>
      <label><input type="radio" value="1" name="clientpasscol" ' .($data['clientpasscol'] == 1 ? "checked" :"" ). '>Show</label>&nbsp;<label><input type="radio" value="0" name="clientpasscol" ' .($data['clientpasscol'] == 0 ? "checked" :"" ). '>Hide</label>
    </div></fieldset>
    ';
			$clientas=null;
			if(isset($data['clientas'])){
			$clientas =$data['clientas'];}

			$radio .='<fieldset><legend>Client Label As</legend>';
			$radio .='<label><input type="radio" value="1" name="clientas" ' .($clientas == 1 ? "checked" :"" ). ' />Client</label>';
			$radio .='<label><input type="radio" value="2" name="clientas" '.($clientas == 2 ? "checked" :"" ). ' />Client/Site</label>';
			$radio .='<label><input type="radio" value="3" name="clientas" ' .($clientas == 3 ? "checked" :"" ). ' />Site</label>';
			$radio .='<label><input type="radio" value="4" name="clientas" ' .($clientas == 4 ? "checked" :"" ). ' />Member</label>';
			$radio .='</fieldset>';

			}
			//(END)OTHER ORG TYPE
			$radio .= '</div>';

			echo $radio;
	}
?>