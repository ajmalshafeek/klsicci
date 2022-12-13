<?php
 $config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
 if(!isset($_SESSION))
 {
  session_name($config['sessionName']);
	session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/product.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientCompany.php");

?>
<!DOCTYPE html >

<html">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />


    <?php
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>
    <!-- datatable -->
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/jquery.dataTables.js'></script>
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/dataTables.bootstrap4.js'></script>
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/js/sb-admin-datatables.min.js'></script>
    <!-- datatable -->
    <?php  if($_SESSION['orgType']==7){ ?>
      <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.12.4.js"></script>
  <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <?php } ?>
    <script src='https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js'></script>
    <script src='https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js'></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">

    <script>

      function editProduct(id){
$('#sentout').css("display","none");
        $.ajax({

            type  : 'GET',
            url  : '../../phpfunctions/product.php?',
            data : {productData:id},
            success: function (data) {
            details= JSON.parse(data);
                console.log(data);
            $('#nbrand').html(details.brand==""?"&nbsp;":details.brand);
            $('#model').html(details.model==""?"&nbsp;":details.model);
            $('#productIdToEdit').val(id);
            $('.productIdToEdit').val(id);
            $('#producttype'). html(details.producttype==""?"&nbsp;":details.producttype);
            <?php if($_SESSION['orgType']!=8){ ?>
            $('#serialNum').html(details.serialNum==""?"&nbsp;":details.serialNum);
            $('#remarks').html(details.remarks==""?"&nbsp;":details.remarks);

            <?php } if($_SESSION['orgType']==8||$_SESSION['orgType']==3 ){ ?>
            $('#title'). html(details.title==""?"&nbsp;":details.title);
            $('#des'). html(details.description==""?"&nbsp;":details.description);
            $('#price'). html(details.price==""?"&nbsp;":"RM " + details.price);
            $('#qty'). html(details.quantity==""?"&nbsp;":details.quantity);

            <?php } if($_SESSION['orgType']==7){ ?>
                $('#sentout').css("display","revert");
            $('#statusvalue').html(details.status==""?"&nbsp;":details.status);
            $('#date').html(details.status==""?"&nbsp;":details.date.substr(0,10));
            $('#location').html(details.location==""?"&nbsp;":details.location);
            $('#ponumber').html(details.ponumber==""?"&nbsp;":details.ponumber);
            $('#capacity').html(details.capacity==""?"&nbsp;":details.capacity);
            if(details.producttype.toLowerCase()=="ram"||
               details.producttype.toLowerCase()=="hdd"||
               details.producttype.toLowerCase()=="hard drive"||
               details.producttype.toLowerCase()=="hardrive"||
               details.producttype.toLowerCase()=="ssd"||
               details.producttype.toLowerCase()=="pendrive"||
               details.producttype.toLowerCase()=="pen drive"||
               details.producttype.toLowerCase()=="solid state drive"){
            $("#capacitysection").css("display","revert");
    }else{  $("#capacitysection").css("display","none"); }
                
                    if(details.status=="Sent Out"){
            $(".instock").css("display","revert"); 

                     var texta= "<tr class=\"contract\"><td style=\"width:120px;\"><label class=\"text-left\">Contract</label></td><td><label id=\"contract\" class=\"text-center\">"+details.contract+"&nbsp;</label></td></tr>";

                       texta+= "<tr class=\"donumber\"><td style=\"width:120px;\"><label class=\"text-left\">D/O Number</label></td><td><label id=\"donumber\" class=\"text-center\">"+details.donumber+"&nbsp;</label></td></tr>";
                        texta+= "<tr class=\"dodate\"><td><label class=\"text-left\">Date</label></td><td><label id=\"dodate\" class=\"text-center\">"+details.dodate.substr(0,10)+"&nbsp;</label></td></tr>";
                        texta+= "<tr class=\"doto\"><td><label class=\"text-left\">To</label></td><td><label id=\"doto\" class=\"text-center\">"+details.doto+"&nbsp;</label></td></tr>";
                        texta+= "<tr class=\"doincharge\"><td><label class=\"text-left\">In Charge</label></td><td><label id=\"doincharge\" class=\"text-center\">"+details.doincharge+"&nbsp;</label></td></tr>";
                        $(".instock .details").html(texta);
                }
                else{
                    $(".instock").css("display","none");
                }
                
                if(details.status==""||details.status==null){
                   $('.stockstatus').css("display","none");
                    
                   }
                else{
                    $('.stockstatus').css("display","revert");
                    
                }
                
                <?php } ?>
            if(details.remarks==""||details.status!=null){$(".rremarks").css("display","none");}
            }
        });
      }

      function clientDelete(str){

        $("#clientIdToDelete").val(str.value);

      }

      function clientEdit(str){

        $("#clientIdToEdit").val(str.value);

      }

      function showPassword(pwdId) {

        var x = document.getElementById(pwdId);
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }

    }
        <?php if($_SESSION['orgType']==7){ ?>
        $(document).ready(function() {
 $("#producttype").on('change',function(){
  if($("#producttype").val().toLowerCase()=="ram"||
     $("#producttype").val().toLowerCase()=="hdd"||
     $("#producttype").val().toLowerCase()=="hard drive"||
      $("#producttype").val().toLowerCase()=="hardrive"||
     $("#producttype").val().toLowerCase()=="ssd"||
     $("#producttype").val().toLowerCase()=="pendrive"||
     $("#producttype").val().toLowerCase()=="ped drive"||
     $("#producttype").val().toLowerCase()=="solid state drive"){
            $("#capacitysection").css("display","revert");
    }else{  $("#capacitysection").css("display","none"); }                      
 }); 
             $("#sproducttype").on('change',function(){
  if($("#sproducttype").val().toLowerCase()=="ram"||
     $("#sproducttype").val().toLowerCase()=="hdd"||
     $("#sproducttype").val().toLowerCase()=="hard drive"||
     $("#sproducttype").val().toLowerCase()=="hardrive"||
     $("#sproducttype").val().toLowerCase()=="ssd"||
      $("#sproducttype").val().toLowerCase()=="pendrive"||
     $("#sproducttype").val().toLowerCase()=="ped drive"||
     $("#sproducttype").val().toLowerCase()=="solid state drive"){
            $("#scapacity").attr('disabled', false);
    }else{  $("#scapacity").attr('disabled', true); }                      
 });
 $("#scapacity").attr('disabled', true);
        
        });
        <?php } ?>
    </script>
    <style>
    .buttonAsLink{
      background:none!important;
      color:inherit;
      border:none;
      font: inherit;
      cursor: pointer;
    }

      .bg-red{
          background-color: #E32526;
      }
table label {
    font-size: 1.2em;
    width: 100%;
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
      <ol class="breadcrumb col-md-12">
        <li class="breadcrumb-item">
          <a href="../../home.php">Dashboard</a>
        </li>
        <li class="breadcrumb-item ">Product</li>
        <li class="breadcrumb-item active">View Product</li>
      </ol>
     </div>
    
      <?php
            if (isset($_SESSION['feedback'])) {
                echo $_SESSION['feedback'];
                unset($_SESSION['feedback']);
            }
        ?>
    <?php  if($_SESSION['orgType']==7){ ?>
    <form action="../../phpfunctions/product.php" method="post">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-2">Date :</div><div class="col-sm-4"><input type="date" name="sdate" class="form-control"></div>
            <div class="col-sm-2">PO number :</div><div class="col-sm-4"><input type="text" name="sponumber" class="form-control"></div>
        </div>
        </div>
        <div class="container-fluid">
        <div class="row">
            <div class="col-sm-2">Type :</div><div class="col-sm-4">
            <input id="sproducttype" name="sproducttype" class="form-control" /></div>
            <div class="col-sm-2">Brand :</div><div class="col-sm-4">
            <input id="sbrand" name="sbrand" class="form-control" /></div>
        </div>
        </div>
            <div class="container-fluid">
        <div class="row">
            <div class="col-sm-2">Capacity :</div><div class="col-sm-4"><select name="scapacity"  id="scapacity" class="form-control" >
                        <option value="" selected >--Select Capacity--</option>
                          <option  value="512MB">512MB</option>
                          <option  value="1GB">1GB</option>
                          <option  value="2GB">2GB</option>
                          <option  value="4GB">4GB</option>
                          <option  value="8GB">8GB</option>
                          <option  value="16GB">16GB</option>
                          <option  value="32GB">32GB</option>
                          <option  value="64GB">64GB</option>
                          <option  value="128GB">128GB</option>
                          <option  value="256GB">256GB</option>
                          <option  value="512GB">512GB</option>
                          <option  value="1TB">1TB</option>
                          <option  value="2TB">2TB</option>
                          <option  value="3TB">3TB</option>
                          <option  value="4TB">4TB</option>
                          <option  value="6TB">6TB</option>
                          <option  value="8TB">8TB</option>
                      </select></div>
            <div class="col-sm-2">Serial Number :</div><div class="col-sm-4"><input type="text" name="sserial" id="sserial" class="form-control"></div>
        </div>
        </div>
        <div class="container-fluid">
            <div class="row">
            <div class="col-sm-12">
                <button type="submit" class="btn" name="search" style="float: right;">Search</button>
                </div>
            </div>
        </div>
    </form>
    <?php } ?>
        <div class='card mb-3'>
		      <div class='card-header'>
					  <i class='fa fa-table'></i>
            Product List
				  </div>
            <div><?php
             /*   $pdf = "<a href='pdf.php'   target='_blank'><button class='btn'><i class=\"fa fa-file-pdf-o\" ></i> PDF</button></a> ";
                $excel = "<a href='excel.php' target='_blank'><button class='btn'><i class=\"fa fa-file-excel-o\" ></i> Excel</button></a>";
          echo $pdf.$excel */ ?></div>
          <?php
            productListTable();
          ?>

          </div>
        </div>
   <form method="POST" action="<?php echo "https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/phpfunctions/product.php" ?>" >

<div class="modal fade mcustom" id="productEditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="productEditModalTitle">Product Details</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <center><table style="width:100%">
             <?php if($_SESSION['orgType']==7){ ?>
            <tr class="stockstatus">
                <td width="120px;"><label>Status</label></td><td><label id="statusvalue" class="text-center">&nbsp;</label></td>
              </tr>
              <tr class="datesec">
                <td><label>Date</label></td><td><label id="date" class="text-center">&nbsp;</label></td>
              </tr>
          <?php }
             if($_SESSION['orgType']==8||$_SESSION['orgType']==3){
                 echo '<tr class="titlesec">
                <td><label class="text-left">Title</label></td><td><label id="title" class="text-center">&nbsp;</label></td>
              </tr>
              <tr class="dessec">
                <td><label class="text-left">Description</label></td><td><label id="des" class="text-center">&nbsp;</label></td>
              </tr>
              <tr class="qtysec">
                <td><label class="text-left">Quantity</label></td><td><label id="qty" class="text-center">&nbsp;</label></td>
              </tr>
              <tr class="pricesec">
                <td><label class="text-left">Price</label></td><td><label id="price" class="text-center">&nbsp;</label></td>
              </tr>
              ';
             }
             ?>
<?php if($_SESSION['orgType']!=3){ ?>
              <tr class="brandsec">
                <td><label class="text-left">Brand</label></td><td><label id="nbrand" class="text-center">&nbsp;</label></td>
              </tr> <?php } ?>
              <tr class="typesec">
                <td><label class="text-left"><?php if($_SESSION['orgType']==8||$_SESSION['orgType']==3){echo 'Category';}else{echo 'Type';} ?></label></td><td><label id="producttype" class="text-center">&nbsp;</label></td>
              </tr>
                  <?php if($_SESSION['orgType']!=3) { ?>
              <tr class="modelsec">
                <td><label class="text-left">Model</label></td><td><label id="model" class="text-center">&nbsp;</label></td>
              </tr> <?php } ?>
              <?php  if($_SESSION['orgType']==7){ ?>
              <tr class="capacitysec">
                <td><label class="text-left">Capacity</label></td><td><label id="capacity" class="text-center">&nbsp;</label></td>
              </tr>
              <tr class="locationsec">
                <td><label class="text-left">Location</label></td><td><label id="location" class="text-center">&nbsp;</label></td>
              </tr>
              <tr class="ponumbersec">
                <td><label class="text-left">PO NO</label></td><td><label id="ponumber" class="text-center">&nbsp;</label></td>
              </tr>
              <?php } ?>
                  <?php if($_SESSION['orgType']!=8&&$_SESSION['orgType']!=3){ ?>
              <tr class="serialNumsec">
                <td><label class="text-left">Serial Number</label></td><td><label id="serialNum" class="text-center">&nbsp;</label></td>
              </tr>
              <tr class="rremarks">
                <td><label class="text-left">Remarks</label></td><td><label id="remarks" class="text-center">&nbsp;</label></td>
              </tr>
                  <?php } ?>
            </table>
            </center>
           
            <?php if($_SESSION['orgType']==7){ ?>
            <div class="instock" style="margin-top: 50px;">
                   <h5>Sent Out Details</h5>
                <hr />
                <center><table style="width:100%" class="details">
                    </table>
            </center>
               
              </div>
          <?php } ?>
              
          </div>


     
      <div class="modal-footer">
          <input type="text" hidden name="productIdToEdit" id="productIdToEdit" value=""  />
          <button type="submit" name='editProductId' class="btn btn-primary edit" >Edit</button>
          <button type="submit" name='setOut' id="sentout" class="btn btn-primary edit" >Sent Out</button>
          <button type="button" data-toggle='modal' data-target='#productDeleteModal' class="btn btn-primary remove" >Remove</button>
          <button type="button" class="btn btn-secondary cancel" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</form>


</div></div>
<form method="POST" action="<?php echo "https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/phpfunctions/product.php" ?>" >

    <div class="modal fade" id="productDeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productTypeEditModalTitle">DELETE ACTION</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div id='staffEditContent' >
                        Are you sure want to delete product?
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="text" hidden name="productIdToEdit" class="productIdToEdit" value=""  />
                    <button type="submit" name='removeProduct' class="btn btn-primary edit" >Yes</button>
                    <button type="button" class="btn btn-secondary remove" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
</form>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fa fa-angle-up"></i>
    </a>

          <div class="footer">
            <p>Powered by JSoft Solution Sdn. Bhd</p>
          </div>
  </div>
<script>
<?php  if($_SESSION['orgType']==7){ ?>


$( "#sbrand" ).autocomplete({
  source: <?php brandListJson(); ?>
});
    
    $( "#sproducttype" ).autocomplete({
  source: <?php productTypeListJson(); ?>
});
    <?php } ?>

$(document).ready(function () {
    $(document).ready(function() {
        $('#dataTableProduct').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                /*'copy',*/ {
                    extend: 'csv',
                    //Name the Excel
                    filename: 'product-list',
                    text: 'CSV',


                }, {
                    extend: 'excel',
                    //Name the Excel
                    filename: 'product-list',
                    text: 'Excel',


                }, {
                    extend: 'pdf',
                    //Name the Excel
                    filename: 'product-list',
                    text: 'PDF',


                }, 'print',  'colvis',


            ],
            'info': true,
            'responsive': true,
            "paging":   true,

        } );
    } );
    $('#dataTable').dataTable({

        dom: 'Bfrtip',
        "sPaginationType": "full_numbers",
        "bRetrieve": true,
        "bPaginate": true,
        "bStateSave": true,

        "sDom":'<"dt_head"fp>t<"F"il>tip',
        "aLengthMenu": [ [50,100,150,200, -1], [50,100,150,200, "All"] ],
        'dom': 'lBfrtip',
        "aLengthMenu": [[50,100,150,200, -1], [50,100,150,200, "All"]],
        "lengthMenu": [[50,100,150,200, -1], [50,100,150,200, "All"]],
        "lengthChange": true,
        buttons: [
            /*'copy',*/ {
                extend: 'csv',
                //Name the Excel
                filename: 'product-list',
                text: 'CSV',


            }, {
                extend: 'excel',
                //Name the Excel
                filename: 'product-list',
                text: 'Excel',


            }, {
                extend: 'pdf',
                //Name the Excel
                filename: 'product-list',
                text: 'PDF',


            }, 'print',  'colvis',


        ],
        'info': true,
        'responsive': true,
        "paging":   true,

    });
});
</script>
</body>
</html>