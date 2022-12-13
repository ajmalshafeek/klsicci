
<?php
 $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
 if(!isset($_SESSION)) 
 { 
  session_name($config['sessionName']);
	session_start(); 
} 
require($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/job.php");
require($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientCompany.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/vendor.php");


?>
<!DOCTYPE html >

<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />

   <!--
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script src="../js/bootstrap.min.js" ></script>
    <script type="text/javascript" src="../js/jquery-3.3.1.min.js"></script>
-->
<?php 
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>

    <!-- datatable -->
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/jquery.dataTables.js'></script>
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/dataTables.bootstrap4.js'></script>
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/js/sb-admin-datatables.min.js'></script>
    <!-- datatable -->


<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
    

    <style>
     .buttonAsLink{
      display: block;
    width: 115px;
    height: 25px;
    background: #4E9CAF;
    padding: 10px;
    text-align: center;
    border-radius: 5px;
    color: white;
    font-weight: bold;

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
            .selectedSpan {
                color: green;
                font-weight:bold;
                text-decoration:underline;
            }
            .printButton{
              width:50px; 
              height:50px; 
              border: 0;
              background-color: transparent;
              border-radius:25px;
              cursor:pointer;
              background-size: 100%; 
              background-size: 50px auto; 
              background-image: url(../resources/app/printIcon.ico);
              background-repeat: no-repeat; 
            }
            .excelButton{
              width:50px; 
              height:50px; 
              border: 0;
              background-color: transparent;
              border-radius:25px;
              cursor:pointer;
              background-size: 100%; 
              background-size: 50px auto;
              background-image: url(../resources/app/excelIcon.ico);
              background-repeat: no-repeat; 
            }
            .pdfButton{
              width:50px; 
              height:50px; 
              border: 0;
              background-color: transparent;
              border-radius:25px;
              cursor:pointer;
              background-size: 100%; 
              background-size: 50px auto; 
              background-image: url(../resources/app/pdfIcon.ico);
              background-repeat: no-repeat; 
            }
    </style>
   <script>
$(document).ready(function() {
    $('#reportTable').DataTable( {
        dom: 'Bfrtip',
        buttons: [
          
            { extend: 'excel', className: 'excelButton',text: '' },
            { extend: 'pdf', className: 'pdfButton',text: '',orientation: 'landscape' },
            { extend: 'print', className: 'printButton',text: '' }
          
        ]
    } );
} );
  function toggleSpan(str){
    
    var id=str.id;
    
    $.ajax({
      
        type  : 'GET',
        url  : '../phpfunctions/report.php?',
        data : {reportTypeDropdown:id},
        success: function (data) {
        //  $( "#reportTypeDropdown" ).load( "report.php #reportTypeDropdown" );     
          var divSelect = $('#selectContent');
          divSelect.empty().append(data);
         
         
        }
    });
   
    var selected = document.getElementsByClassName("selectedSpan");
    while (selected.length)
    selected[0].classList.remove("selectedSpan");
    if(id=='staffSpan'){
      str.setAttribute("class", "selectedSpan"); 
      document.getElementById("hiddenSpanValue").value = "staff";
    }else{
      str.setAttribute("class", "selectedSpan"); 
      document.getElementById("hiddenSpanValue").value = "vendor";
    }
    

  }

  function viewSignature(str){
  
    $imageFile=str+".png";
      $("#signaturePath").attr("src",$imageFile);
  }

  function viewJobDetails(str){
   
    $.ajax({
        type  : 'GET',
        url  : '../phpfunctions/report.php?',
        data : {viewJobListItemReport:str},
        success: function (data) {
      
          var taskDiv=$("#jobDetailsModelContent");
          taskDiv.empty().append(data);
          
          //$( "#vendorDetailsTable" ).load( "viewVendor.php #vendorDetailsTable" );                
        }
    });
  }
</script>
</head>
<body class="fixed-nav " >


<?php
  include $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/navMenu.php";
?>

  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">VIEW JOB REPORT</li>

      </ol>

 <div class="container">
            <form method="POST" action="../phpfunctions/report.php" class="needs-validation" novalidate >
           
            <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="clientCompanyId">CLIENT</label>
                  <?php
                        dropDownListOrganizationClientCompanyActive3();
                  ?>
                </div>

                <div class="form-group col-md-6" >
                  <label ><span id="vendorSpan" onclick='toggleSpan(this);'  >VENDOR</span> / 
                  <span id="staffSpan" onclick='toggleSpan(this);' class='selectedSpan' >ORGA. STAFF</span></label>
                    <input type="text" id="hiddenSpanValue" name="hiddenSpanValue" hidden value="staff" />
                  <div id="reportTypeDropdown">
                    <div id="selectContent">
                  <?php
                    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organizationUser.php");
                    dropDownListOrgStaffListActive();
                    //  dropDownListVendorActive3();
                    ?>
                      </div>
                  </div>
                </div>

                <div class="form-group col-md-6">
                  <label for="dateFrom">DATE FROM</label>
                  <input type="date" class='form-control' value="<?php echo date('Y-m-d'); ?>" id="dateFrom" name="dateFrom" />        
                </div>

                <div class="form-group col-md-6">
                  <label for="dateTo">DATE TO</label>
                  <input type="date" class='form-control' value="<?php echo date('Y-m-d'); ?>" id="dateTo" name="dateTo" />        
                </div>

                <div class="form-group col-md-6">
                  <label for="STATUS">STATUS</label>
                  <select name='status' class='form-control form-control-lg' id='status' required>
                    <option selected disabled >--SELECT--</option>
                      <option value="0" >COMPLETED</option>
                      <option value="2" >PENDING</option>
                      <option value="3" >IN PROGRESS</option>
                    </select>
                </div>
              </div>

              <div class="form-group">
                <button type="submit" class="btn btn-primary btn-sm " name="jobReportTable" >SEARCH</button>
              </div>
         
          <!-- Signature Modal START-->
          <div class="modal fade" id="signatureModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="signatureModalTitle">SIGNATURE</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <img id="signaturePath" src='' class="img-fluid" alt="Responsive image">
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
          <!-- Signature Model END -->

      
          <!-- JOB DETAILS Modal START-->
          <div class="modal fade" id="jobDetailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="jobDetailsModalTitle">DETAILS</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">

                  <div id='jobDetailsModelContent' >            
                  </div>

                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
          <!-- JOB DETAILS Model END -->

            
            </form>
      <?php 
          if(isset($_SESSION['tableReport'])){
            echo $_SESSION['tableReport'];
            $_SESSION['tableReport']="";
          }

        ?>
    </div>
    </div>
            <div class="footer">
             <p>Powered by JSoft Solution Sdn. Bhd</p>
            </div>
  

</body>
</html>

