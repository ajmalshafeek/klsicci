<?php
$config = parse_ini_file(__DIR__ . "/../jsheetconfig.ini");
if (!isset($_SESSION)) {

    session_name($config['sessionName']);

    session_start();
} ?>
<?php


require_once "dompdf/autoload.inc.php";
use Dompdf\Dompdf;
use Dompdf\Options;

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'admin_devgk');
define('DB_PASSWORD', '802%0svsE');
define('DB_NAME', 'admin_devgk');
define('FIRSTKEY', 'Lk5Uz3slx3BrAghS1aaW5AYgWZRV0tIX5eI0yPchFz4=');
define('SECONDKEY', 'EZ44mFi3TlAey1b2w4Y7lVDuqO+SRxGXsa7nctnr/JmMrA2vN6EJhrvdVZbxaQs5jpSe34X3ejFK/o9+Y5c83w==');
define( 'FS_METHOD', 'direct' );
$url="". 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/'; ;
define('BASE_URL', $url.'document_sign_sow/');

//trigger exception in a "try" block
try {

    $urlParams = explode('/', $_SERVER['REQUEST_URI']);
    $arv = count($urlParams) - 1;
    $functionName = $urlParams[2];
    $functionName = explode('?', $functionName)[0];
    if ($functionName == '') {
        index();
    } else {

        unset($urlParams[0], $urlParams[1], $urlParams[2]);
        $functionName(implode(',', $urlParams));
    }
} catch (Exception $e) {
    index();
}

function database_connection()
{

    /* Attempt to connect to MySQL database */
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Check connection
    if ($mysqli === false) {
        die("ERROR: Could not connect. " . $mysqli->connect_error);
    }

    return $mysqli;
}

function index()
{

    header('Location: ' . BASE_URL . 'views/index.php');
    die();
}

function view_files($id)
{
    header('Location: ' . BASE_URL . 'views/view_pdf.php?id=' . $id);
    die();
}

function member_view_pdf($id)
{
    header('Location: ' . BASE_URL . 'views/member_view_pdf.php/' . $id);
    die();
}
function member_view_client_pdf($id)
{
    header('Location: ' . BASE_URL . 'views/member_view_client_pdf.php/' . $id);
    die();
}


function get_pdf_data($id)
{

    pdf_html($id);
    $dir = 'htmlfolder/'.$id.'pdf/';
    $file_list = scandir($dir);
    $pages_html = array();
    $search = '.png';
    $search1 = $id.'png';
    $pageno = 0;
    foreach($file_list as $key => $value){
        if (  preg_match("/{$search}/i", $value) && preg_match("/{$search1}/i", $value)  ) {
            $pageno = $pageno+1;
            $filehtml = BASE_URL.'htmlfolder/'.$id.'pdf/'.$value;
            $class="";
            if($pageno > 1){

                $class=' class="page_break" ';
            }
            $html_final = '<div '.$class.' style="position:relative;width:892px;height:1230px;">
                <img width="892" height="1230" src="'.$filehtml.'" alt="background image">
            </div>';
            echo $html_final.'****||****';
        }
    }
    exit;
}


function pdf_html($id){

    if(is_dir('htmlfolder/'.$id.'pdf')=="")
        mkdir('htmlfolder/'.$id.'pdf', 0777); //windows automatically creates folder, on linux folder is created by command
    shell_exec('pdftopng uploads/'.$id.'.pdf htmlfolder/'.$id.'pdf/'.$id.'png');
    return true;
}
function upload_files()
{
    if (isset($_FILES)) {
        if(isset($_POST['clientCompanyId'])&&$_POST['clientCompanyId']!='--Select--'){
        if (isset($_FILES['file'])) {
            $file = $_FILES['file']['name'];
            $file_loc = $_FILES['file']['tmp_name'];
            $file_size = $_FILES['file']['size'];
            $file_type = $_FILES['file']['type'];

            $folder = "uploads/";

            // new file size in KB
            $new_size = $file_size / 1024;
            // new file size in KB

            // make file name in lower case
            $new_file_name = strtolower($file);
            // make file name in lower case

            $final_file = str_replace(' ', '-', $new_file_name);

            // fetch original file extension
            $extension = pathinfo($final_file, PATHINFO_EXTENSION);

            $allowedExtensions = ["jpg", "jpeg", "png", "gif", "pdf", "doc", "docx"];

            // check if the file extension is allowed
            if (!in_array($extension, $allowedExtensions)) {
                // report error and abort
            }

            $mysqli = database_connection();
            // insert file into database

            $sql = $mysqli->query("INSERT INTO tbl_files(`file`,`type`,`size`, `uploadby`,`user`) VALUES('" . $final_file . "','" . $file_type . "','" . $new_size . "',".$_SESSION['userid'].",".$_POST['clientCompanyId'].")");

            // fetch generated id
            $id = $mysqli->insert_id;
            $mysqli->error;
            // move file to $folder and rename it to "$id.$extension"
            $fileMoved = move_uploaded_file($file_loc, $folder . $id . "." . $extension);
            if ($fileMoved) {
                $mysqli->close();
                view_files($id);
            } else {
                // insert file into database
                $sql = $mysqli->query("DELETE FROM tbl_files where id=$id");
                $mysqli->close();

                $_SESSION['message']='<div class="bg-danger py-2 px-2  text-danger" style="padding: 15px">Failed to upload file</div>';
                index();
            }
        }
        }
        else{
            $_SESSION['message']='<div class="bg-danger py-2 px-2 text-danger" style="padding: 15px">Kindly assign to a client</div>';
            index();
        }
    }
}

function get_pdf_listings()
{

    $mysqli = database_connection();
    $sql = $mysqli->query("SELECT *,(SELECT name FROM organizationuser WHERE `id`=`uploadby`) as uploader, (SELECT name FROM clientcompany WHERE `id`=`user`) as assigned, ( CASE WHEN `signby`> 0 THEN (SELECT name FROM client_signatory WHERE `cid`=`user`) ELSE (SELECT name FROM clientcompany WHERE `id`=`user`) END) as sing FROM `tbl_files` ORDER BY `id` DESC");

    $data_arr = array();
    while ($row =  $sql->fetch_array(MYSQLI_NUM)) {
        $signed = '';
        $copy = '';
        if ($row[7] != '') {  $signed = '<a href="' . BASE_URL . 'view_sign_pdf/' . secured_encrypt($row[0]) . '"  target="_blank">View</a>';}
        $edit = '';
        if (!$row[8]) {
            $edit = '<a href="' . BASE_URL . 'view_files/' . $row[0] . '" >Edit</a> ';
            $copy = ' <input type="hidden" value="' . BASE_URL . 'member_view_pdf/' . secured_encrypt($row[0]) . '" id="copy_link_doc' . $row[0] . '">
            <a href="#" onclick="window.top.pdfShared(\'' . $row[0] . '\',\''.$row[10].'\')">Assign</a>';
        }
        $temp="";
        if($row[12]==0){ $temp="No"; }else{ $temp="Yes"; }
        $data_arr[] = array(
            $row[1],
            $row[3],
            $row[5],
            $row[14],
            $row[15],
            $row[16],
            $temp,
            $edit . $signed . $copy

        ); // The customer
    }

    echo json_encode(array('data' => $data_arr));
    $mysqli->close();
}

function get_pdf_client_listings()
{
    $user=$_SESSION['companyId'];
    $mysqli = database_connection();
    $sql = $mysqli->query("SELECT *,(SELECT name FROM organizationuser WHERE `id`=`uploadby`) as uploader, (SELECT name FROM clientcompany WHERE `id`=`user`) as assigned, ( CASE WHEN `signby`> 0 THEN (SELECT name FROM client_signatory WHERE `cid`=`user`) ELSE (SELECT name FROM clientcompany WHERE `id`=`user`) END) as sing FROM `tbl_files` WHERE `user`=".$user." AND approved IS NULL ORDER BY `id` DESC");

    $data_arr = array();
    while ($row =  $sql->fetch_array(MYSQLI_NUM)) {
        $signed = '';
        $copy = '';
        if ($row[7] != '') {
            $signed = '<a href="' . BASE_URL . 'view_sign_pdf/' . secured_encrypt($row[0]) . '"  target="_blank">View</a>';
        }
        $edit = '';
        if (!$row[8]) {
            $edit = '<a href="' . BASE_URL . 'view_files/' . $row[0] . '" >Edit</a> ';
            //$copy = ' <input type="hidden" value="' . BASE_URL . 'member_view_pdf/' . secured_encrypt($row[0]) . '" id="copy_link_doc' . $row[0] . '">
            //<a href="javascript:void(0)" onclick="copylink(' . $row[0] . ')">Copy Link</a>';
            $copy = ' <input type="hidden" value="' . BASE_URL . 'member_view_client_pdf/' . secured_encrypt($row[0]) . '" id="copy_link_doc' . $row[0] . '">
            <a href="' . BASE_URL . 'member_view_client_pdf/' . secured_encrypt($row[0]) . '">Open Link</a>';
        }
        $temp="";
        $data_arr[] = array(
            $row[1],
            $row[3],
            $row[5],
            $row[14],
            $row[15],
            $row[16], $copy

        ); // The customer
    }

    echo json_encode(array('data' => $data_arr));
    $mysqli->close();
}

/*
function pdf_html($id)
{
    $res='pdftohtml uploads/' . $id . '.pdf htmlfolder/abc' . $id . 'pdf';
	file_put_contents('./pdftohtml.log','shell:'.print_r($res,1).'<br />\n',FILE_APPEND);
    if (is_dir('htmlfolder/' . $id . 'pdf') == "")
        try {
			// mkdir("htmlfolder/" . $id . "pdf/", 0770, true);
       $result= shell_exec($res);
			file_put_contents('./pdftohtml.log','res:'.print_r($result,1).'<br />\n',FILE_APPEND);
        }
    catch(Exception $e){
    file_put_contents('./pdftohtml.log','error:'.print_r($res,1).'<br />\n',FILE_APPEND);
}
    return true;
}
*/
function get_pdf_saved_data($id)
{
    $mysqli = database_connection();
    $sql = $mysqli->query("SELECT * FROM tbl_files where id='" . $id . "' order by id desc");
    $data_arr = array();
    $html = '';
    $is_approved = 0;
    while ($row =  $sql->fetch_array(MYSQLI_NUM)) {
        $html =  $row[4];
        $is_approved = $row[8];
    }

    if ($is_approved) {
        echo 'approved';
        exit;
    } else {
        echo  file_get_contents($html);
        exit;
    }
}
function get_is_att($id){
    print_r($id);
    die();
}
function save_pdf_data($id)
{
    $newhtml = $_POST['pages'];
    $user_id = rand(10000, 1000000000);
    $file_dir = 'marked_documents/' . $id . '_' . $user_id . '.html';
    file_put_contents($file_dir, $newhtml);
    $mysqli = database_connection();
    // insert file into database
    $sql = $mysqli->query("UPDATE tbl_files SET pdf_html='" . $file_dir . "' where id='" . $id . "' ");
    $mysqli->close();
    exit;
}

function save_signed_pdf_data($id){

    $newhtml = $_POST['pages'];
    $mysqli = database_connection();
    // insert file into database
    
    $pdf_link = html_to_pdf($id,$newhtml);
    $sql = $mysqli->query("UPDATE tbl_files SET signed_doc='".$pdf_link."' where id='".$id."' ");
    $sql = $mysqli->query("UPDATE tbl_files SET signed_html='".$pdf_link."' where id='".$id."' ");
    $mysqli = database_connection();
    $sql = $mysqli->query("SELECT * FROM tbl_files where id='".$id."' order by id desc");
    $data_arr = array();
    $id = '';
    $pdf_html = '';
    while($row =  $sql->fetch_array(MYSQLI_NUM))
    {   
        $id =  $row[0];
        $pdf_html =  $row[4];
    }
    unlink("uploads/".$id.".pdf");
    unlink($pdf_html);
    $dir = 'htmlfolder/'.$id.'pdf/';
    $file_list = scandir($dir);
    foreach($file_list as $key => $value){
       unlink($dir.''.$value);
    }
    rmdir($dir);
    $sql = $mysqli->query("UPDATE tbl_files SET approved='1' where id='".$id."' ");
    echo  file_get_contents($html);
    $mysqli -> close();
    exit;
}


function html_to_pdf($id, $html)
{
  
    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);




    $html_arr = explode('****||****',$html);
    $html='<!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml" lang="" xml:lang=""><head><title></title><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/><style>@page {margin-top: 0; margin-left:5;}</style></head><body>';
    $index = 0;

    foreach($html_arr as $value){
        $index = $index +1;
        if($value!=''){

            $value = preg_replace('/>\s+</', "><", trim($value));
            $html .= trim($value);
        }
    }

    $html .=  '</body></html>';

    $html = preg_replace('/>\s+</', "><", $html);
    $orientation = 'portrait';
    $dompdf->set_option("dpi",110);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', $orientation);
    $dompdf->render();

    $user_id = rand(10000,1000000000);
    $file_dir = 'signed_documents/'.$id.'_'.$user_id.'.pdf';
    file_put_contents($file_dir, $dompdf->output());
    return $file_dir;


}

function view_sign_pdf($id)
{

    $id = secured_decrypt($id);

    $mysqli = database_connection();
    $sql = $mysqli->query("SELECT * FROM tbl_files where id='" . $id . "' order by id desc");
    $data_arr = array();
    $link = '';
    while ($row =  $sql->fetch_array(MYSQLI_NUM)) {
        $link =  $row[7];
    }

    $file = $link;
    $linkarr = explode('/', $file);
    $filename = explode('/', $file)[count($linkarr) - 1];

    // Header content type
    header('Content-type: application/pdf');

    header('Content-Disposition: inline; filename="' . $filename . '"');

    header('Content-Transfer-Encoding: binary');

    header('Accept-Ranges: bytes');

    // Read the file
    @readfile($file);
}

function get_dataurl()
{

    if (isset($_FILES)) {
        if (isset($_FILES['file'])) {
            $id = rand(10000, 1000000000);
            $file = $_FILES['file']['name'];
            $file_loc = $_FILES['file']['tmp_name'];
            $file_size = $_FILES['file']['size'];
            $file_type = $_FILES['file']['type'];
            $folder = "uploads/signatures/";

            // new file size in KB
            $new_size = $file_size / 1024;
            // new file size in KB

            // make file name in lower case
            $new_file_name = strtolower($file);
            // make file name in lower case

            $final_file = str_replace(' ', '-', $new_file_name);

            // fetch original file extension
            $extension = pathinfo($final_file, PATHINFO_EXTENSION);

            // $allowedExtensions = ["jpg", "jpeg", "png", "gif", "pdf", "doc", "docx"];

            // // check if the file extension is allowed
            // if (! in_array($extension, $allowedExtensions))
            // {
            //     // report error and abort
            // }


            // // move file to $folder and rename it to "$id.$extension"
            // $fileMoved = move_uploaded_file($file_loc,$folder.$id.".".$extension);

            $path = $file_loc;
            $type = $extension;
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            echo $base64;
        }
    }
}


function secured_encrypt($data)
{
	return $data;
    return base64_encode($data);
    $first_key = base64_decode(FIRSTKEY);
    $second_key = base64_decode(SECONDKEY);

    $method = "aes-256-cbc";
    $iv_length = openssl_cipher_iv_length($method);
    $iv = openssl_random_pseudo_bytes($iv_length);

    $first_encrypted = openssl_encrypt($data, $method, $first_key, OPENSSL_RAW_DATA, $iv);
    $second_encrypted = hash_hmac('sha3-512', $first_encrypted, $second_key, TRUE);

    $output = base64_encode($iv . $second_encrypted . $first_encrypted);
    return $output;
}

function secured_decrypt($input)
{
	return $input;
    return base64_decode($input);
    $first_key = base64_decode(FIRSTKEY);
    $second_key = base64_decode(SECONDKEY);
    $mix = base64_decode($input);

    $method = "aes-256-cbc";
    $iv_length = openssl_cipher_iv_length($method);

    $iv = substr($mix, 0, $iv_length);
    $second_encrypted = substr($mix, $iv_length, 64);
    $first_encrypted = substr($mix, $iv_length + 64);

    $data = openssl_decrypt($first_encrypted, $method, $first_key, OPENSSL_RAW_DATA, $iv);
    $second_encrypted_new = hash_hmac('sha3-512', $first_encrypted, $second_key, TRUE);

    if (hash_equals($second_encrypted, $second_encrypted_new))
        return $data;

    return false;
}
function delete_data(){


    $mysqli = database_connection();
    $sql = $mysqli->query("Delete FROM tbl_files where 1 ");
    exit;

}
