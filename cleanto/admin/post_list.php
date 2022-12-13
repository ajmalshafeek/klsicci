<?php 

include(dirname(dirname(__FILE__)).'/objects/class_connection.php');
include(dirname(dirname(__FILE__)).'/objects/class_users.php');
$database=new cleanto_db();
$con=$database->connect();
$user=new cleanto_users();
$user->conn=$con;

	$params = $columns = $totalRecords = $data = array();

	$params = $_REQUEST;

	$columns = array(
		0 => 'id',
		1 => 'user_email', 
		2 => 'first_name',
		3 => 'last_name'
	);

	$where_condition = $sqlTot = $sqlRec = "";

	if( !empty($params['search']['value']) ) {
		$where_condition .=	" WHERE ";
		$where_condition .= " ( user_email LIKE '%".$params['search']['value']."%' ";    
		$where_condition .= " OR first_name LIKE '%".$params['search']['value']."%' ";
		$where_condition .= " OR last_name LIKE '%".$params['search']['value']."%' ";
		$where_condition .= " OR phone LIKE '%".$params['search']['value']."%' ";
		$where_condition .= " OR zip LIKE '%".$params['search']['value']."%' ";
		$where_condition .= " OR city LIKE '%".$params['search']['value']."%' ";
		$where_condition .= " OR state LIKE '%".$params['search']['value']."%' ";
		$where_condition .= " OR cus_dt LIKE '%".$params['search']['value']."%' )";
	}

	$sql_query = " SELECT * FROM ct_users ";
	$sqlTot .= $sql_query;
	$sqlRec .= $sql_query;
	
	if(isset($where_condition) && $where_condition != '') {

		$sqlTot .= $where_condition;
		$sqlRec .= $where_condition;
	}

/*  	$sqlRec .=  " ORDER BY ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." "; */
 	$sqlRec .=  " ORDER BY ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";

	$queryTot = mysqli_query($con, $sqlTot) or die("Database Error:". mysqli_error($con));

	$totalRecords = mysqli_num_rows($queryTot);

	$queryRecords = mysqli_query($con, $sqlRec) or die("Error to Get the Post details.");

	while( $row = mysqli_fetch_row($queryRecords) ) { 
	$bk="myregistercust_bookings";
	$booking = $user->get_users_totalbookings($row[0]);
	/* print_r($row); */
	array_push($row,$booking);
	if($booking == 0){
		$bk="disabled";
	}/* else{
		$bk=$booking;
	} */
	array_push($row,$bk);
		$data[] = $row;
		/* array_push($data,$booking); */
	}	
	/* print_r($data); */

	$json_data = array(
		"draw"            => intval( $params['draw'] ),   
		"recordsTotal"    => intval( $totalRecords ),  
		"recordsFiltered" => intval($totalRecords),
		"data"            => $data
	);

	echo json_encode($json_data);
?>
	