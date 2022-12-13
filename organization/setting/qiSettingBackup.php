
<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION)) 
{ 
 session_name($config['sessionName']);
   session_start(); 
} 
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/configuration.php");
		$quotConfig=getQuotationConfig($_SESSION['orgId']);


?>
<!DOCTYPE html>

<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link href="../../css/component-custom-switch.css" rel="stylesheet">


    <?php 
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>
 
<script>
    function toggleStampUploadFile(str){
      var isEnabled=str.checked;
      if(isEnabled){
        $("#stampUploadDiv").css("display","block");

      }else{
        $("#stampUploadDiv").css("display","none");

      }
    }
    
    $(document).ready(function(){
        $('[data-toggle="tooltipInvoice"]').tooltip(); 
        $('[data-toggle="tooltipQuotation"]').tooltip(); 

        $("#pdfFooter").change(function(){
          
          var id=$(this).val();
          //$("#extraNote").empty();
            $.ajax({
              type  : 'GET',
              url  : '<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/phpfunctions/configuration.php'; ?>',
              data : {footerContent:true,footerId:id},
              success: function (data) {
                
                $("#extraNote").text(data);

              }
            });


        });

        $("#createPdfFooter").click(function() {
          $("#footerName").val('');
          $("#footerContent").val('');
          $('#pdfFooterCreationModal').modal('toggle');
        });

        
    });


    </script>
    <style>
    .buttonAsLink{
      background:none!important;
      color:inherit;
      border:none; 
      font: inherit;
      cursor: pointer;
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
    </style>
   
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
		<li class="breadcrumb-item">BUSINESS / SETTING</li>  
        <li class="breadcrumb-item active">QUOTATION / INVOICE SETTING TEMPLATE</li>

      </ol>
      <div class="container">
      <form method="POST" action="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot']."/phpfunctions/configuration.php"; ?>"  
      enctype="multipart/form-data" >
        <div style="text-align:right">
        <button name='updateQuotSetting' class="btn  " type='submit' >
          <i class="fa fa-floppy-o fa-2x" aria-hidden="true"></i>
        </button>
         </div>
        <?php
            if (isset($_SESSION['feedback'])) {
                echo $_SESSION['feedback'];
                unset($_SESSION['feedback']);
            }
        ?>
        <div id="accordion">
          <div class="card">
            <div class="card-header" id="headingOne">
              <h5 class="mb-0">
                <button type="button" class="btn btn-block" style="background:#045B9B;box-shadow: 5px 5px black;" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                  PDF SETTING
                </button>
              </h5>
            </div>

            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
              <div class="card-body">
                <div class="form-group row">
                  <label for="stampEnabled" class="col-sm-2 col-form-label col-form-label-lg">STAMP</label>
                      <div class="col-1"   >

                        <div class='custom-switch '>
                          <input class='form-control custom-switch-input' 
                          <?php
                            if($quotConfig['isStamp']==true){
                              echo "checked";
                            }

                          ?>
                            onchange='toggleStampUploadFile(this)' id="stampEnabled"  name="stampEnabled" type='checkbox'>
                          <label class='custom-switch-btn' for="stampEnabled"></label>
                        </div>  

                      </div>
                      <div class="col-5" id="stampUploadDiv" <?php
                            if($quotConfig['isStamp']!=true){
                              echo 'style="display:none"';
                            }

                          ?>>
                      <input type="file" onchange="$(this).next().after().text($(this).val().split('\\').slice(-1)[0])" 
                        class="custom-file-input" accept="image/png" id="stampImage" name="stampImage">
                        <label class="custom-file-label" for="stampImage">
                          <?php 
                          if($quotConfig['stampName']!="stamp"){
                            echo "".$quotConfig['stampName'].".png";
                          }
                          else{
                            echo "Upload Stamp Image...";

                          }
                          ?>
                          <input type="hidden" name="uploadFileLabel" value="<?php 
                          if($quotConfig['stampName']!="stamp"){
                            echo "".$quotConfig['stampName'].".png";
                          }
                          else{
                            echo "Upload Stamp Image...";

                          }
                          ?>" >

                        </label>
                        <div class="invalid-feedback">Example invalid custom file feedback
                        </div>
                      </div>
                </div>

                <div class="form-group row">
             
                  <label for="extraNote" class="col-sm-2 col-form-label col-form-label-lg">FOOTER
                    <button type="button" id="createPdfFooter" class="btn " style="padding:5px 5px;border" ><i class="fa fa-plus" aria-hidden="true"></button></i>
                  </label>
                      <div class="col-sm-10"   >
                      <select class="custom-select" name="pdfFooter" id="pdfFooter" style="background-color:#A3C2CE;border:none;border-top:1px solid black;border-radius:0px;border-bottom:1px solid black;">
                          <?php 
                            $footerList=getPdfFooterList(null,$_SESSION['orgId']);
                              $i=0;
                              $footerNote="";
                              foreach ($footerList as $footer) {
                                $selected="";
                                if($i==0){
                                  $selected="selected";
                                  $footerNote=$footer['content'];
                                  $i++;
                                }
                                echo "<option $selected value='".$footer['id']."' >".$footer['name']."</option>";
                              }

                          ?>
               
                      </select>
                      
                      <textarea id="extraNote" name="extraNote" class="form-control" style="box-shadow: 0 8px 6px -2px black;background:#F5F2F2" rows="6"><?php 
                        $note=$footerNote;
                        $note=br2nl($note);
                        echo $note;
                        ?></textarea>

                      </div>  
                  </div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header" id="headingTwo">
              <h5 class="mb-0">
                <button type="button" class="btn btn-block collapsed" style="background:#045B9B;box-shadow: 5px 5px black;" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                  QUOTATION MAIL SETTING
                </button>
              </h5>
            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
              <div class="card-body">
                
                <div class="form-group row">
                  <label for="quotationBody" class="col-sm-2 col-form-label col-form-label-lg">QUOTATION BODY
                      <i class="fa fa-info-circle text-info" aria-hidden="true" data-html="true" data-toggle="tooltipQuotation" data-placement="top" 
                      title="[Customer_Attention]<br/>
                      [Organization_Name]<br/>
                      [Organization_Logo]<br/>
                      [Organization_Address]"></i>
                      </label>
                      <div class="col-sm-10"   >
                        <textarea id="quotationBody" name="quotationBody" class="form-control" rows="6"><?php 
                        
                        $quoMailBody=$quotConfig['quotationMailBody'];
                        $quoMailBody=br2nl($quoMailBody);
                        echo $quoMailBody;
                        ?></textarea>

                      </div>  
                  </div>

              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header" id="headingThree">
              <h5 class="mb-0">
                <button type="button" class="btn btn-block collapsed" style="background:#045B9B;box-shadow: 5px 5px black;" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                  INVOICE MAIL SETTING
                </button>
              </h5>
            </div>
            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
              <div class="card-body">
                
                <div class="form-group row">
                
                  <label for="invoiceBody" class="col-sm-2 col-form-label col-form-label-lg">INVOICE BODY
                  <i class="fa fa-info-circle text-info" aria-hidden="true" data-html="true" data-toggle="tooltipInvoice" data-placement="top" 
                  title="[Customer_Attention]<br/>
                    [Invoice_Number]<br/>
                    [Invoice_Date]<br/>
                    [Amount_Due]<br/>
                    [Due_Date]<br/>
                    [Organization_Name]<br/>
                    [Organization_Logo]<br/>
                    [Organization_Address]"
                  
                  ></i>
                  </label>
                    <div class="col-sm-10"   >
                      <textarea id="invoiceBody" name="invoiceBody" class="form-control" rows="6"><?php 
                      
                      $invoiceMailBody=$quotConfig['invoiceMailBody'];
                      $invoiceMailBody=br2nl($invoiceMailBody);
                      echo $invoiceMailBody;
                      ?></textarea>

                    </div>  
                  </div>
              </div>
            </div>
          </div>

        </div>

      </form>
      <!-- CREATE PDF FOOTER Modal START-->
      <form  action="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot']."/phpfunctions/configuration.php"; ?>" method="POST">
      <div class="modal fade  " data-backdrop="static" id="pdfFooterCreationModal" tabindex="-1" role="dialog" aria-labelledby="pdfFooterCreationModal" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="pdfFooterCreationModalTitle">CREATE FOOTER for PDF</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">

                  <div class="form-group">
                    <label for="footerName">Footer Name</label>
                    <input type="text" class="form-control" id="footerName" name="footerName" placeholder="e.g. license,service,maintenance">
                  </div>

                  <div class="form-group">
                    <label for="footerContent">Footer Content</label>
                    <textarea class="form-control" id="footerContent" name="footerContent" rows="5" ></textarea>
                  </div>

                </div>
                <div class="modal-footer">
          
                <button type="submit" name="createPdfFooter" class="btn ">SAVE</button>
                <button type="button" class="btn " data-dismiss="modal">CLOSE</button>
                </div>
              </div>
            </div>
          </div>
          </form>
          <!-- CREATE PDF FOOTER Model END -->


      </div>

      </div>

      
      <div class="footer">
			<p>Powered by JSoft Solution Sdn. Bhd</p>
			</div>
			</div>

            
   <!-- Scroll to Top Button-->
   <a class="scroll-to-top rounded" href="#page-top">
       <i class="fa fa-angle-up"></i>
   </a>
   
   
   </div>
</body>
</html>

