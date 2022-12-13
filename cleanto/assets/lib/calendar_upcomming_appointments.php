<?php  

$config=parse_ini_file(__DIR__."/../../../jsheetconfig.ini");
if(!isset($_SESSION)) 
{ 
    session_name($config['sessionName']);
    session_start(); 
} 
	include(dirname(dirname(dirname(__FILE__))).'/objects/class_connection.php');	
	include(dirname(dirname(dirname(__FILE__))).'/objects/class_services.php');	
	include_once(dirname(dirname(dirname(__FILE__))).'/header.php');		
	include(dirname(dirname(dirname(__FILE__))).'/objects/class_booking.php');
	include(dirname(dirname(dirname(__FILE__))).'/objects/class_users.php');
    include(dirname(dirname(dirname(__FILE__)))."/objects/class_dashboard.php");
	include(dirname(dirname(dirname(__FILE__)))."/objects/class_setting.php");
    include(dirname(dirname(dirname(__FILE__))).'/objects/class_general.php');
		
		$database=new cleanto_db();
		$conn=$database->connect();
		$database->conn=$conn;
		
		$setting = new cleanto_setting();
		$setting->conn = $conn;
        $general=new cleanto_general();
        $general->conn=$conn;
        $symbol_position=$setting->get_option('ct_currency_symbol_position');
        $decimal=$setting->get_option('ct_price_format_decimal_places');
        $getdateformate = $setting->get_option('ct_date_picker_date_format');
		$time_format = $setting->get_option('ct_time_format');
		$service=new cleanto_services();	
		$booking=new cleanto_booking();
		$user=new cleanto_users();
		$service->conn=$conn;		
		$booking->conn=$conn;
		$user->conn=$conn;
		if(isset($_SESSION['cal_service_id'])){
		$booking->service_id=$_SESSION['cal_service_id'];
			
		}
		if(isset($_SESSION['cal_provider_id'])){
		$booking->provider_id=$_SESSION['cal_provider_id'];	
		}
		if(isset($_SESSION['cal_startdate'])){
			$booking->booking_start_datetime=$_SESSION['cal_startdate'];
		}
		
		if(isset($_SESSION['cal_enddate'])){
			$booking->booking_end_datetime=$_SESSION['cal_enddate'];
		}
		$appointment_array_for_cal = array();
			
		$searchcalendar=$booking->readall();
        $myarrbook = $booking->getallbookings();
        while($tt = mysqli_fetch_array($myarrbook)){
            $order_id = $tt['order_id'];
            $color=$tt['color'];
            $title=$tt['title'];
			
				if($time_format == 12){
			
				$format= 'H:ia';
			
				}else{
			
				$format= 'H:i';
			
				}
            $start=$tt['booking_date_time'];
            $end=$tt['booking_date_time'];
            $price=$general->ct_price_format($tt['net_amount'],$symbol_position,$decimal);
            $status = $tt['booking_status'];
            if($tt['client_id'] == 0){
                $gcn = $user->readoneguest($tt['order_id']);
                $clientname = $gcn[2];
                $clientphone = $gcn[4];
                $clientemail = $gcn[3];
            }
            else
            {
                $user->user_id = $tt['client_id'];
                $cn = $user->readone();
                $clientname = $cn[3]."".$cn[4];
				$fetch_phone =  strlen($cn[5]);
				if($fetch_phone >= 6){
					$clientphone = $cn[5];
				}else{
					$clientphone = '';
				}
                $clientemail = $cn[1];
            }
            $appointment_array_for_cal[]= array(
                "id"=>"$order_id",
                "color_tag"=>"$color",
                "title"=>"$title",
                "start"=>"$start",
                "end"=>"$start",
                "event_status"=>"$status",
                "client_name"=>"$clientname",
                "client_phone"=>"$clientphone",
                "client_email"=>"$clientemail",
                "total_price"=>"$price",
				"date_format"=>"$getdateformate",
				"time_format"=>"$format"
				
            );
		}
			if(isset($appointment_array_for_cal)){
			$json_encoded_string_for_cal  =  json_encode($appointment_array_for_cal);
			echo $json_encoded_string_for_cal;die();
    }
?>