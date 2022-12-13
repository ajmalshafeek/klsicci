<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
  session_name($config['sessionName']);
  session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organizationUser.php");
$userid = $_SESSION['userid'];
?>

<!DOCTYPE html>
<!-- saved from url=(0062)https://jcloud.my/testing/organization/invoice/viewInvoice.php -->
<html><head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />
    <?php
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>
 <link rel="stylesheet" type="text/css" href="./schuduling_files/myQuotationStyle.css">
    <script src="./schuduling_files/jquery.min.js.download"></script>
    <script src="./schuduling_files/bootstrap.bundle.min.js.download"></script>
    <script src="./schuduling_files/jquery.easing.min.js.download"></script>
    <script src="./schuduling_files/jquery.min.js(1).download"></script>
</head>
 <body class="fixed-nav ">
   <?php
     include $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/navMenu.php";
   ?>
   <div class="content-wrapper">
     <div class="container-fluid">
       <?php echo shortcut() ?>
       <!-- Breadcrumbs-->
       <ol class="breadcrumb col-md-12">
         <li class="breadcrumb-item">
           <a href="../../home.php">Dashboard</a>
         </li>
         <li class="breadcrumb-item ">Recurring Invoice</li>
         <li class="breadcrumb-item active">Recurring Invoice List</li>
       </ol>
     </div>

        <div class="card mb-3 ">
       <div class="card-header ">
         <i class="fa fa-table"></i>
               Invoice Schedule List
       </div>
             <div class="card-body  ">


                  <div class="table-responsive">



           <div id="invoiceListTable_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
           <div class="row">
           <div class="col-sm-12 col-md-6">
           <div class="dataTables_length" id="invoiceListTable_length">
           <label>Show
           <select name="invoiceListTable_length" aria-controls="invoiceListTable" class="form-control form-control-sm">
           <option value="10">10</option>
           <option value="25">25</option>
           <option value="50">50</option>
           <option value="100">100</option>
           </select> entries</label></div></div>
           <div class="col-sm-12 col-md-6">
           <div id="invoiceListTable_filter" class="dataTables_filter">
           <label>Search:<input type="search" class="form-control form-control-sm" placeholder="" aria-controls="invoiceListTable"></label>
           </div></div></div>
           <div class="row">
           <div class="col-sm-12">
           <table class="table table-hover table-bordered table- dataTable no-footer" id="invoiceListTable" width="100%" cellspacing="0" role="grid" style="width: 100%;" aria-describedby="invoiceListTable_info">
           <thead class="thead-dark"><tr role="row">

           <th scope="col" class="sorting" tabindex="0" aria-controls="invoiceListTable" rowspan="1" colspan="1" aria-label="Customer Name: activate to sort column ascending" style="width: 100px;">S.No</th>
           <th style="width: 200px;" scope="col" class="sorting_desc" tabindex="0" aria-controls="invoiceListTable" rowspan="1" colspan="1" aria-sort="descending" aria-label="Date: activate to sort column ascending">Client Name</th>
           <th scope="col" class="sorting" tabindex="0" aria-controls="invoiceListTable" rowspan="1" colspan="1" aria-label="Invoice: activate to sort column ascending" style="width: 99px;">Schedule Date</th>
           <th scope="col" class="sorting" tabindex="0" aria-controls="invoiceListTable" rowspan="1" colspan="1" aria-label="Customer Name: activate to sort column ascending" style="width: 157px;">Action</th>

           </tr></thead>
           <tbody>
             <?php
           include_once ("db.php");
           $str= "SELECT *  FROM `invoice_scheduler` WHERE 1=1 order by  id DESC";
           $schedule_list = mysqli_query($con,$str) or die(mysqli_error($con));
                 $i=1;
                 while ($row = $schedule_list->fetch_assoc()) {
                 ?>
           <tr  role="row" class="odd">
           <td style="text-align: center; vertical-align: middle;"><?php echo $i++;?>
             </td>
             <td class="sorting_1"><?php
             $str12= "SELECT *  FROM `invoice_scheduler_sub` WHERE invoice_scheduler_id='".$row['id']."'";
             $schedule_sub_list = mysqli_query($con,$str12) or die(mysqli_error($con));
             while ($row12 = $schedule_sub_list->fetch_assoc()) {
             $str1= "SELECT *  FROM `clientcompany` WHERE 1=1 order by  name ASC";
             $client_name = mysqli_query($con,$str1) or die(mysqli_error($con));
             while ($row1 = $client_name->fetch_assoc()) {
               if($row1['id']==$row12['client_id']){
                 echo $row1['name'].', ';
               }
             }
             }
             ?>
             </td>
             <td><?php echo date('d-m-Y',strtotime($row['schedule_date'])); ?></td>
             <td>
             <?php if($row['status']==0){
              ?>
             <form class="" action="scheduler-edit.php" method="post">
              <button type="submit" name="invoice_scheduler_id" value="<?php echo $row['id'] ?>" class="btn"  style="text-align:center">
                 Edit
              </button>
             </form>
             <?php }else{ echo 'Completed';}?>
             </td>
             </tr>
                 <?php  }?>
             </tbody>
             </table>
             </div></div>


                </div>
            </div>
       </div>

     </div>
   </div>

   <a class="scroll-to-top rounded" href="https://jcloud.my/testing/organization/invoice/viewInvoice.php#page-top" style="display: none;">
	 <i class="fa fa-angle-up"></i>
   </a>






</body></html>
