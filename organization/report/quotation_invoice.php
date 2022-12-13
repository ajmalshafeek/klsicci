
<?php
 $config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
 if(!isset($_SESSION)) 
 { 
  session_name($config['sessionName']);
	session_start(); 
} 



?>
<!DOCTYPE html >

<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />

 
<?php 
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>

    <!-- datatable -->
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/jquery.dataTables.js'></script>
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/dataTables.bootstrap4.js'></script>
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/js/sb-admin-datatables.min.js'></script>
    <!-- datatable -->



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
       
    </style>
  <script>


  $(document).ready(function() { 
    $("#invoiceQuotationSummary").click(function(){
        var from=$("#dateFrom").val();
        var to=$("#dateTo").val();
        $.ajax({
          type: 'GET',
          url: '<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/phpfunctions/report.php'; ?>',
          data: {quotationSummary:'true',dateFrom:from,dateTo:to},
          dataType: 'json',
          success: function(data){

            var totalAmountQuot=0;
            var convertedAmountQuot=0;
            var unonvertAmountedQuot=0;

            var unconverted=0;
            var converted=0;
            var total=0;
            console.log(data);

              $.each(data, function(index, obj){
                  if(obj.status==1){
                    unconverted++;
                    unonvertAmountedQuot=parseFloat(unonvertAmountedQuot)+parseFloat(obj.total);

                  }else if(obj.status==0){
                    converted++;
                    convertedAmountQuot=parseFloat(convertedAmountQuot)+parseFloat(obj.total);

                  }
                  total++;
                  totalAmountQuot=parseFloat(totalAmountQuot)+parseFloat(obj.total);

              });

            $("#totalQuotationTD").text("RM "+numberWithSpaces(totalAmountQuot.toFixed(2))+" ( "+total+" )");
            $("#convertedQuotationTD").text("RM "+numberWithSpaces(convertedAmountQuot.toFixed(2))+" ( "+converted+" )");
            $("#unconvertedQuotationTD").text("RM "+numberWithSpaces(unonvertAmountedQuot.toFixed(2))+" ( "+unconverted+" )");
            
            
            //$("#unpaidInvoiceTD").text("RM "+numberWithSpaces(unpaindIncome.toFixed(2))+" ( "+unpaid+" )");


          }

        });

        $.ajax({
          type: 'GET',
          url: '<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/phpfunctions/report.php'; ?>',
          data: {invoiceSummary:'true',dateFrom:from,dateTo:to},
          dataType: 'json',
          success: function(data){
            var totalIncome=0;
            var paidIncome=0;
            var unpaindIncome=0;

            var unpaid=0;
            var paid=0;
            var total=0;
              $.each(data, function(index, obj){
                  if(obj.status==1){
                    unpaid++;
                    unpaindIncome=parseFloat(unpaindIncome)+parseFloat(obj.total);

                  }else if(obj.status==0){
                    paid++;
                    paidIncome=parseFloat(paidIncome)+parseFloat(obj.total);

                  }
                  total++;
                  totalIncome=parseFloat(totalIncome)+parseFloat(obj.total);
                  
                
              });
       
            $("#totalIncomeInvoiceTD").text("RM "+unpaindIncome.toFixed(2));
            $("#paidIncomeInvoiceTD").text("RM "+paidIncome.toFixed(2));
            $("#unpaidIncomeInvoiceTD").text("RM "+totalIncome.toFixed(2));

            $("#totalInvoiceTD").text("RM "+numberWithSpaces(totalIncome.toFixed(2))+ " ( "+total+" )");
            $("#paidInvoiceTD").text("RM "+numberWithSpaces(paidIncome.toFixed(2)) + " ( "+paid+" )");

            $("#unpaidInvoiceTD").text("RM "+numberWithSpaces(unpaindIncome.toFixed(2))+" ( "+unpaid+" )");
       
          }

        });


    });

    $("#invoiceQuotationSummary").trigger('click');


  });

  </script>
  
</head>
<body class="fixed-nav " >


<?php
  include $_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/navMenu.php";
?>

  <div class="content-wrapper">
    <div class="container-fluid">
        <?php echo shortcut() ?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb col-md-12">
        <li class="breadcrumb-item">
          <a href="#">Quotation & Invoice </a>

        </li>
          <li class="breadcrumb-item ">Summary</li>

      </ol>
    </div>
        <div class="container" >
                <form>
                <div class="form-row">

                  <div class="form-group col-md-6">
                      <label for="dateFrom">From</label>
                      <input type="date" class='form-control' value="<?php echo date('Y-m-d'); ?>" id="dateFrom" name="dateFrom" />        
                    </div>

                    <div class="form-group col-md-6">
                      <label for="dateTo">To</label>
                      <input type="date" class='form-control' value="<?php echo date('Y-m-d'); ?>" id="dateTo" name="dateTo" />        
                    </div> 

                    <div class="form-group col-md-6">
                      <button type="button" class="btn btn-primary btn-sm " id="invoiceQuotationSummary" name="invoiceQuotationSummary" >Search</button>
                    </div>
                </div>  

            <div class="table-responsive">
    <!--
            <table class="table table-hover table-bordered " id="invoicePaymentSummary" 
              cellspacing="0" role="grid" >
                <thead>
                  
                    <tr>

                      <th style="width:40%">TOTAL INCOME
                      </th>

                      <th style="width:20%">PAID INCOME
                      </th>
                    
                      <th style="width:20%">UNPAID INCOME
                      </th>
                  
                    </tr>
                <thead>
                <tbody>
                    <tr>

                      <td id="totalIncomeInvoiceTD">
                      </td>

                      <td id="paidIncomeInvoiceTD">
                      </td>
                  
                      <td id="unpaidIncomeInvoiceTD">
                      </td>
                    
                    </tr>

                    
                    
                </tbody>
              </table>
    -->
              <table class="table table-hover table-bordered " id="quotationSummaryTable" 
              cellspacing="0" role="grid" >
                <thead>
                  
                    <tr>

                      <th style="width:40%">Total Quotation
                      </th>

                      <th style="width:20%">Unconverted Quotation
                      </th>
                    
                      <th style="width:20%">Converted Quotation
                      </th>
                  
                    </tr>
                <thead>
                <tbody>
                    <tr>

                      <td id="totalQuotationTD">
                      </td>

                      <td style="background: #EAAEA4 !important" id="unconvertedQuotationTD">
                      </td>
                  
                      <td style="background: #A5CEA5 !important" id="convertedQuotationTD">
                      </td>
                    
                    </tr>

                    
                    
                </tbody>
              </table>
              
                <table class="table table-hover table-bordered" id="invoiceSummaryTable" 
                  cellspacing="0" role="grid" >
                  <thead>
                    
                      <tr>
                      
                        <th style="width:40%">Total Invoice
                        </th>

                        <th style="width:20%">Unpaid Invoice
                        </th>
                      
                        <th style="width:20%">Paid Invoice
                        </th>
                    
                      </tr>
                  <thead>

                  <tbody>
                      <tr>
                      
                        <td id="totalInvoiceTD">
                        </td>

                        <td style="background: #EAAEA4 !important" id="unpaidInvoiceTD">
                        </td>
                    
                        <td style="background: #A5CEA5 !important" id="paidInvoiceTD">
                        </td>
                      
                      </tr>

                  </tbody>
                </table>
            </div>

            </form>
      </div>

    </div>
    
              
  

</body>
</html>

