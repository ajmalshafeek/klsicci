<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
date_default_timezone_set("Asia/Kuala_Lumpur");
if(!isset($_SESSION))
{
 	session_name($config['sessionName']);
	session_start();
}
// debug
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/attendance.php");
$con = connectDb();

if(isset($_POST['checkin'])){
  $con = connectDb();
  $staffId = $_POST['orgStaffId'];
  $latitude = $_POST['latitude'];
  $longitude = $_POST['longitude'];
  $latlong = $_POST['latitude'].",".$_POST['longitude'];
  $cityName = getCityNameByLatitudeLongitude($latlong);

  //(START)FILE UPLOAD
  $target_dir = $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/".$_SESSION['orgId']."/attendance/";
  $target_file = $target_dir . basename($_FILES['fileName']["name"]);
  $temp = explode(".", $_FILES['fileName']["name"]);
  $newfilename = rand(1000000,10000000) . '.' . end($temp);
  if (move_uploaded_file($_FILES['fileName']["tmp_name"], "../resources/".$_SESSION['orgId']."/attendance/" . $newfilename)) {
     $feedbackFile = true;
  }
  //(END)FILE UPLOAD

  $feedbackAtt = insertAttendance($con,$staffId,$newfilename,$latitude,$longitude,$cityName);

  if($feedbackAtt){
    $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
    <strong>SUCCESS!</strong>ATTENDANCE RECORDED \n
    </div>\n";
  }else{
    $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
  <strong>FAILED!</strong> FAILED TO CHECK-IN ATTENDANCE \n
  </div>\n"; 
  }
  
  /*
  if($feedbackFile){
    $_SESSION['feedback'].="<div class='alert alert-success' role='alert'>\n
    <strong>SUCCESS!</strong>IMAGE UPLOADED \n
    </div>\n";
  }else{
    $_SESSION['feedback'].="<div class='alert alert-warning' role='alert'>\n
  <strong>FAILED!</strong> FAILED TO UPLOAD IMAGE \n
  </div>\n"; 
  }
  */

  header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/attendance/attendance.php");
}



//@param latlong (String) is Latitude and Longitude with , as separator for example "21.3724002,39.8016229"
function getCityNameByLatitudeLongitude($latlong)
{
    $APIKEY = "AIzaSyDgH5mAegqWsg3LTQ8LSJW2RlMofmRsOfg"; // Replace this with your google maps api key 
    $googleMapsUrl = "https://maps.googleapis.com/maps/api/geocode/json?latlng=" . $latlong . "&language=ar&key=" . $APIKEY;
    $response = file_get_contents($googleMapsUrl);
    $response = json_decode($response, true);
    $results = $response["results"];
    $addressComponents = $results[0]["address_components"];
    $cityName = "";
    foreach ($addressComponents as $component) {
        // echo $component;
        $types = $component["types"];
        if (in_array("locality", $types) && in_array("political", $types)) {
            $cityName = $component["long_name"];
        }
    }
    if ($cityName == "") {
        echo "Failed to get CityName";
        return "Error";
    } else {
        return $cityName;
    }
}


function attendanceList(){
    $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organizationUser.php");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/attendance.php");
    $con = connectDb();
    
    $table="
    <table id='attendanceTable'>
      <thead>
        <tr>
          <th style='width:5%'>No</th>
          <th style='width:5%'>Staff Id</th>
          <th style='width:20%'>Staff Name</th>
          <th style='width:20%'>Time in</th>
          <th style='width:5%'>C/I Image</th>
          <th style='width:10%'>C/I Location</th>
          <th style='width:20%'>Time out</th>
          <th style='width:5%'>C/O Image</th>
          <th style='width:10%'>C/O Location</th>
        </tr>
      </thead>
      <tbody>
    ";
    $dataList = fetchAttendanceListAll($con);
    $i=1;
    foreach($dataList as $data){
    $rowStaff = fetchOrganizationUserbyId($con,$data['staffId']);
    if($data['fileName']!=null){
        $fileImage = "<img src='../../resources/".$_SESSION['orgId']."/attendance/".$data['fileName']."' alt='Image not found' width='100%;'>";
    }else{
        $fileImage = "";
    }
    
    if($data['fileNameOut']!=null){
        $fileImageOut = "<img src='../../resources/".$_SESSION['orgId']."/attendance/".$data['fileNameOut']."' alt='Image not found' width='100%;'>";
    }else{
        $fileImageOut = "";
    }
    
    if($data['latitudeOut']!=null && $data['longitudeOut']!=null){
        $showLocationOut = "setTimeout(showLocationOut,500, ".$data['id'].", ".$data['latitudeOut'].", ".$data['longitudeOut'].")";
    }else{
        $showLocationOut = "";
    }
    
    $table .= "
        <tr data-toggle='modal' data-target='#attendanceModal' onclick='showAttendance(".$data['id'].");".$showLocationOut.";setTimeout(showLocation,500, ".$data['id'].", ".$data['latitude'].", ".$data['longitude'].")' style='cursor:pointer'>
          <td>".$i."</td>
          <td>".$rowStaff['id']."</td>
          <td>".$rowStaff['fullName']."</td>
          <td>".$data['checkinTime']."</td>
          <td>".$fileImage."</td>
          <td>".$data['cityName']."</td>
          <td>".$data['checkoutTime']."</td>
          <td>".$fileImageOut."</td>
          <td>".$data['cityNameOut']."</td>
        </tr>
    ";
    $i++;    
    }
    $table .= "
      </tbody>
    </table>
    ";
    
    echo $table;
}

if(isset($_POST['att1'])){
  $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
  $con = connectDb();
  $clockio = $_POST['clockio'];
  $staffId = $_SESSION['userid'];
  $latitude = $_POST['latitude'];
  $longitude = $_POST['longitude'];
  $latlong = $_POST['latitude'].",".$_POST['longitude'];
  $date = date('Y-m-d');
  $feedbackFile = false;
  
  if($clockio == 0){
      $dataList = fetchAttendanceByStaffIdAndDate($con,$staffId,$date);
      if(count($dataList) == 0){
          //(START)FILE UPLOAD
          $base64_image_string =$_POST['base64img'];
         // echo "<br>".$base64_image_string;
          $output_file_without_extension = date("Ymdhisa").rand(1000000,10000000);
          $path_with_end_slash = $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/".$_SESSION['orgId']."/attendance/";
          $newfilename = save_base64_image($base64_image_string, $output_file_without_extension, $path_with_end_slash );
          $feedbackFile = true;
          //(END)FILE UPLOAD
          
          $cityName = getCityNameByLatitudeLongitude($latlong);
          $feedback = insertAttendance($con,$staffId,$newfilename,$latitude,$longitude,$cityName);
          
          if($feedback){
               $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
               <strong>SUCCESS!</strong>CLOCK IN SUCCESSFULL \n
               </div>\n";
          }else{
               $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
               <strong>ERROR!</strong>SOMETHING WENT WRONG \n
               </div>\n";
          }
          
      }else{
          $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
          <strong>FAILED!</strong>CANNOT CLOCK IN TWICE A DAY \n
          </div>\n";
      }
  }elseif($clockio == 1){
      $row = fetchAttendanceRowByStaffIdAndDate($con,$staffId,$date);

      if($row['coCheck']=="0"){
          //(START)FILE UPLOAD debug
          $base64_image_string = $_POST['base64img'];
         // echo "<br>".$base64_image_string;
          $output_file_without_extension = date("Ymdhisa").rand(1000000,10000000);
          $path_with_end_slash = $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/".$_SESSION['orgId']."/attendance/";
          $newfilename = save_base64_image($base64_image_string, $output_file_without_extension, $path_with_end_slash );
          $feedbackFile = true;
          //(END)FILE UPLOAD
          
          $cityName = getCityNameByLatitudeLongitude($latlong);
          $feedback = updateAttendanceByStaffIdAndDate($con,$newfilename,$latitude,$longitude,$cityName,$staffId,$date);
          if($feedback){
               $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
               <strong>SUCCESS!</strong>CLOCK OUT SUCCESSFULL \n
               </div>\n";
          }else{
               $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
               <strong>ERROR!</strong>SOMETHING WENT WRONG \n
               </div>\n";
          }          
      }elseif($row['coCheck']=="1"){
              $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
              <strong>FAIL!</strong>YOU HAVE ALREADY CLOCKED OUT \n
              </div>\n";
      }else{
              $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
              <strong>FAIL!</strong>YOU HAVE NOT CLOCKED IN YET \n
              </div>\n";
      }
  }
  
  /*
  if($feedbackFile){
       $_SESSION['feedback'].="<div class='alert alert-success' role='alert'>\n
       <strong>SUCCESS!</strong>IMAGE IS UPLOADED \n
       </div>\n";
  }else{
       $_SESSION['feedback'].="<div class='alert alert-warning' role='alert'>\n
       <strong>ERROR!</strong>FAILED TO UPLOAD IMAGE \n
       </div>\n";
  }
  */
          
  header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/attendance/att.php");
}

if(isset($_POST['att2'])){
  $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
  $con = connectDb();
  $clockio = $_POST['clockio'];
  $staffId = $_SESSION['userid'];
  $latitude = $_POST['latitude'];
  $longitude = $_POST['longitude'];
  $latlong = $_POST['latitude'].",".$_POST['longitude'];
  $date = date('Y-m-d');
  $feedbackFile = false;
  
  if($clockio == 0){
      $dataList = fetchAttendanceByStaffIdAndDate($con,$staffId,$date);
      if(count($dataList) == 0){
          //(START)FILE UPLOAD
          $target_dir = $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/".$_SESSION['orgId']."/attendance/";
          $target_file = $target_dir . basename($_FILES['fileName']["name"]);
          $temp = explode(".", $_FILES['fileName']["name"]);
          $newfilename = date("Ymdhisa").rand(1000000,10000000) . '.' . end($temp);
          if (move_uploaded_file($_FILES['fileName']["tmp_name"], "../resources/".$_SESSION['orgId']."/attendance/" . $newfilename)) {
             $feedbackFile = true;
          }
          //(END)FILE UPLOAD
          
          $cityName = getCityNameByLatitudeLongitude($latlong);
          $feedback = insertAttendance($con,$staffId,$newfilename,$latitude,$longitude,$cityName);
          
          if($feedback){
               $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
               <strong>SUCCESS!</strong>CLOCK IN SUCCESSFULL \n
               </div>\n";
          }else{
               $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
               <strong>ERROR!</strong>SOMETHING WENT WRONG \n
               </div>\n";
          }
          
      }else{
          $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
          <strong>FAILED!</strong>CANNOT CLOCK IN TWICE A DAY \n
          </div>\n";
      }
  }elseif($clockio == 1){
      $row = fetchAttendanceRowByStaffIdAndDate($con,$staffId,$date);

      if($row['coCheck']=="0"){
          //(START)FILE UPLOAD
          $target_dir = $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/resources/".$_SESSION['orgId']."/attendance/";
          $target_file = $target_dir . basename($_FILES['fileName']["name"]);
          $temp = explode(".", $_FILES['fileName']["name"]);
          $newfilename = date("Ymdhisa").rand(1000000,10000000) . '.' . end($temp);
          if (move_uploaded_file($_FILES['fileName']["tmp_name"], "../resources/".$_SESSION['orgId']."/attendance/" . $newfilename)) {
             $feedbackFile = true;
          }
          //(END)FILE UPLOAD
          $cityName = getCityNameByLatitudeLongitude($latlong);
          $feedback = updateAttendanceByStaffIdAndDate($con,$newfilename,$latitude,$longitude,$cityName,$staffId,$date);
          if($feedback){
               $_SESSION['feedback']="<div class='alert alert-success' role='alert'>\n
               <strong>SUCCESS!</strong>CLOCK OUT SUCCESSFULL \n
               </div>\n";
          }else{
               $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
               <strong>ERROR!</strong>SOMETHING WENT WRONG \n
               </div>\n";
          }          
      }elseif($row['coCheck']=="1"){
              $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
              <strong>FAIL!</strong>YOU HAVE ALREADY CLOCKED OUT \n
              </div>\n";
      }else{
              $_SESSION['feedback']="<div class='alert alert-warning' role='alert'>\n
              <strong>FAIL!</strong>YOU HAVE NOT CLOCKED IN YET \n
              </div>\n";
      }
  }
  
  /*
  if($feedbackFile){
       $_SESSION['feedback'].="<div class='alert alert-success' role='alert'>\n
       <strong>SUCCESS!</strong>IMAGE IS UPLOADED \n
       </div>\n";
  }else{
       $_SESSION['feedback'].="<div class='alert alert-warning' role='alert'>\n
       <strong>ERROR!</strong>FAILED TO UPLOAD IMAGE \n
       </div>\n";
  }
  */
          
  header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/attendance/att.php");
}

if(isset($_GET['attendanceDetail'])){
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/organizationUser.php");
    $con = connectDb();
    $id = $_GET['attendanceDetail'];
    $row = fetchAttendanceRowById($con,$id);
    $rowStaff = fetchOrganizationUserbyId($con,$row['staffId']);
    
    //IMAGE IN
    if($row['fileName'] != null){
     $imageIn = "<img src='../../resources/".$_SESSION['orgId']."/attendance/".$row['fileName']."' alt='Image not found' width='100%;'>";      
    }else{
     $imageIn = "";
    }
    
    //IMAGE OUT
    if($row['fileNameOut'] != null){
     $imageOut = "<img src='../../resources/".$_SESSION['orgId']."/attendance/".$row['fileNameOut']."' alt='Image not found' width='100%;'>";      
    }else{
     $imageOut = "";
    }
    
    if($row['coCheck'] == 1){
        $detailOut = '
        <div class="form-group row">
          <label class="col-sm-4 col-form-label col-form-label-lg">Time Out</label>
          <div class="col-sm-8"><input class="form-control" type="text" value="'.$row['checkoutTime'].'" readonly></div>
        </div>
        <div class="form-group row">
          <label class="col-sm-4 col-form-label col-form-label-lg">C/O Image</label>
          <div class="col-sm-8">'.$imageOut.'</div>
        </div>
        <div class="form-group row">
          <label class="col-sm-4 col-form-label col-form-label-lg">Location</label>
          <div class="col-sm-8"><div id="staticMapOut'.$row['id'].'"></div></div>
        </div>
        <div class="form-group row">
          <label class="col-sm-4 col-form-label col-form-label-lg">City</label>
          <div class="col-sm-8"><input class="form-control" type="text" value="'.$row['cityNameOut'].'" readonly></div>
        </div>
    ';
    }else{
        $detailOut = '
        <div class="form-group row">
          <label class="col-sm-12 col-form-label col-form-label-lg"><i>Staff still have not clock out</i></label>
        </div>
        ';
    }
    
    $details = '
    <div class="form-group row">
      <label class="col-sm-4 col-form-label col-form-label-lg">Staff Name</label>
      <div class="col-sm-8">
          <input class="form-control" type="text" value="'.$rowStaff['fullName'].'" readonly>
      </div>
    </div>
    <hr>
    <div class="form-group row">
      <label class="col-sm-4 col-form-label col-form-label-lg">Time In</label>
      <div class="col-sm-8">
          <input class="form-control" type="text" value="'.$row['checkinTime'].'" readonly>
      </div>
    </div>
    <div class="form-group row">
      <label class="col-sm-4 col-form-label col-form-label-lg">C/I Image</label>
      <div class="col-sm-8">'.$imageIn.'</div>
    </div>
    <div class="form-group row">
      <label class="col-sm-4 col-form-label col-form-label-lg">Location</label>
      <div class="col-sm-8"><div id="staticMap'.$row['id'].'"></div></div>
    </div>
    <div class="form-group row">
      <label class="col-sm-4 col-form-label col-form-label-lg">City</label>
      <div class="col-sm-8"><input class="form-control" type="text" value="'.$row['cityName'].'" readonly></div>
    </div>
    <hr>
    '.$detailOut.'
    ';
    
    echo $details;
}

function save_base64_image($base64_image_string, $output_file_without_extension, $path_with_end_slash ) {
    //usage:  if( substr( $img_src, 0, 5 ) === "data:" ) {  $filename=save_base64_image($base64_image_string, $output_file_without_extentnion, getcwd() . "/application/assets/pins/$user_id/"); }
    //
    //data is like:    data:image/png;base64,asdfasdfasdf
    
    $splited = explode(',', substr( $base64_image_string , 5 ) , 2);
    $mime=$splited[0];
    $data=$splited[1];

    $mime_split_without_base64=explode(';', $mime,2);
    $mime_split=explode('/', $mime_split_without_base64[0],2);
    if(count($mime_split)==2)
    {
        $extension=$mime_split[1];
        if($extension=='jpeg')$extension='jpg';
        //if($extension=='javascript')$extension='js';
        //if($extension=='text')$extension='txt';
        $output_file_with_extension=$output_file_without_extension.'.'.$extension;
    }
    file_put_contents( $path_with_end_slash . $output_file_with_extension, base64_decode($data) );
    return $output_file_with_extension;
}
?>
