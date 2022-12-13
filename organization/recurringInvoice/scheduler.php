<!DOCTYPE html>
<!-- saved from url=(0064)https://jcloud.my/testing/organization/invoice/createInvoice.php -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="https://jcloud.my/testing/resources/app/favIcon.ico">


	<link rel="stylesheet" type="text/css" href="./schuduling_files/myQuotationStyle.css">



	
    <script src="./schuduling_files/jquery.min.js.download"></script>
    <script src="./schuduling_files/bootstrap.bundle.min.js.download"></script>
  
    <script src="./schuduling_files/jquery.easing.min.js.download"></script>
  
   <!-- 
    <script src='https://jcloud.my/testing/adminTheme/datatables/jquery.dataTables.js'></script>
    <script src='https://jcloud.my/testing/adminTheme/datatables/dataTables.bootstrap4.js'></script>
    <script src='https://jcloud.my/testing/adminTheme/js/sb-admin-datatables.min.js'></script>
-->

  <script src="./schuduling_files/jquery.min.js(1).download"></script>

<script type="text/javascript">

var jQuery_2_2_4 = $.noConflict(true);
jQuery_2_2_4(window).load(function() {

jQuery_2_2_4(".bg-loader").fadeOut("slow");

});

function numberWithSpaces(x) {
    var parts = x.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    return parts.join(".");
}

</script>

   

<script src="./schuduling_files/sb-admin.min.js.download"></script>
     	
<script>
function dropDownClientChange(str){
	var companyId=str.value;
		
		
		if($.trim($("#attention").val()).length==0){
			// do nothing
		}else{
			
	
			$('#activate-step-2').prop('disabled',false);
			$('#pills-items-tab').removeClass('disabled');
		}
	
}
var counter=0;
$(document).ready(function() {
	    var navListItems = $('ul.setup-panel li a'),
        allWells = $('.setup-content');

    allWells.hide();

    navListItems.click(function(e){
        e.preventDefault();
        var $target = $($(this).attr('href')),
            $item = $(this).closest('li');
      
        if (!$item.hasClass('disabled')) {
			
            navListItems.closest('li').removeClass('active');
            $item.addClass('active');
            allWells.hide();
            $target.show();
			
        }
    });
    
    $('ul.setup-panel li.active a').trigger('click');
    
    $('#activate-step-2').on('click', function(e) {
		
        $('#pills-items-tab').removeClass('disabled');
        $('#pills-items-tab').trigger('click');
      //  $(this).remove();
    })


$('#activate-step-3').on('click', function(e) {
        $('#pills-confirm-tab').removeClass('disabled');
        $('#pills-confirm-tab').trigger('click');
		$('#pills-confirm-tab').addClass('disabled');
		fillValueToQuotForm();
		setClientCDetails();

        //$(this).remove();
	})

function setClientCDetails(){
		
		var companyId=$("#clientCompanyId").val();
		
		$.ajax({
      	
		  type  : 'GET',
		  url  : '../../phpfunctions/clientCompany.php?',
		  data : {quotClientId:companyId},
		  dataType: 'json',
		  success: function (data) {
			  
				  var address=data['address1']+",";
				  if(data['address2']!=null){
					  address+="\n"+data['address2']+",";
				  }
				  address+="\n"+data['city']+" "+data['postalCode']+",";
				  address+="\n"+data['state']+"";
				  
				  
				  $("#quot_customerAddress").val(address);
				  $("#attSpan").text($("#attention").val());
				  $("#quot_attention").val($("#attention").val());
				  
			  }
		  });
	}





	function fillValueToQuotForm(){
		// currency format start
		var locale = 'us';
		var options = {minimumFractionDigits: 2, maximumFractionDigits: 2};
		var formatter = new Intl.NumberFormat(locale, options);
		// currency format end
		var counter=0;
		var customerId=$('#clientCompanyId').val();
		var customerName=$('#clientCompanyId option:selected').text();
		$('#quot_customerName').val(customerName);
		$('#quot_customerId').val(customerId);
		
		//$()$('#quot_customerName').val($("#clientName").val());

		
		var currentRow=$("table.order-list >tbody >tr").length;
		var totalAmount=0;
		
		$('#quot_quotationDate').prop('value','2020-May-11');
		$('#quot_quotationNo').prop('value','0000000016');
				$("table.quotation-Content tbody").empty();
		$('table.order-list > tbody  > tr').each(function() {	
			
			var fields = $(this).find(":input");
			var itemName=fields.eq(0).val();
			var itemDesc=fields.eq(1).val();
			var itemCost=fields.eq(2).val();
			itemCost=itemCost.replace (/,/g, "");
			var itemQty=fields.eq(3).val();
			var price=itemCost*itemQty;
			var outputDesc = itemDesc.replace(/\n/g, "<br />");  

			totalAmount+=price;
			$('#quot_quotationNo').prop('value',);
			var newRow = $("<tr>");
        	var cols = "";
			


			cols += '<td valign="top"><input type="text" hidden readonly name="itemName' + counter + '" value="'+itemName+'" />'+itemName+'</td>';
			cols += '<td valign="top"><textarea  hidden readonly name="itemDesc' + counter + '" value="'+itemDesc+'" >'+itemDesc+'</textarea>'+outputDesc+'</td>';
			cols += '<td valign="top"><input type="text" hidden readonly name="itemCost' + counter + '" value="RM '+formatter.format(itemCost)+'" />RM '+formatter.format(itemCost)+'</td>';
			cols += '<td valign="top"><input type="text" hidden readonly name="itemQty' + counter + '" value="'+itemQty+'" />'+itemQty+'</td>';

			cols += '<td valign="top"><input type="text" hidden readonly name="price' + counter + '" value="RM '+formatter.format(price)+'" />RM '+formatter.format(price)+'</td>';
        	newRow.append(cols);
			$("table.quotation-Content").append(newRow);
			
			counter++;
			
		});
		$('.totalAmount').prop('value','RM '+formatter.format(totalAmount));
		$('#maxItemIndex').prop('value',counter);
	}
	

 	$('#showPaymentModalDialog').click(function() {
		$('#invoicePaymentModal').modal('toggle');
		var totalAmount=getTotalAmount();
		$('#totalAmount').val('RM ' + totalAmount.toFixed(2));
		
	});

	function getTotalAmount(){
		var totalAmount=0;
		$('table.order-list > tbody  > tr').each(function() {
			
			var fields = $(this).find("input");

			var itemCost=fields.eq(2).val();
			var itemQty=fields.eq(3).val();
			var price=itemCost*itemQty;

			totalAmount+=price;
			
			
		});

		return totalAmount;

	}

	$('#paidAmmount').on("input", function(){
		
		var isValid = true;
		
		if ( ($.trim($(this).val()).length == 0 ) || $(this).val()<=0 ){
			isValid = false;
			//return isValid;
		}
        
    
		if(isValid==true){
			$('#activate-step-3').prop('disabled',false);
			$('#pills-items-tab').removeClass('disabled');
			
		}else{
			$('#activate-step-3').prop('disabled',true);
			$('#pills-items-tab').addClass('disabled');
		}
		
	});

 	$('#activate-step-3').on('click', function(e) {
		 $('#invoicePaymentModal').modal('hide');
		 step3Activation();
		
	});

	$('[required]').on("paste keyup", function(){

		var isValid = true;
		$("[required]").each(function(){
		
			if ($.trim($(this).val()).length == 0){
				isValid = false;
				return isValid;
			}else{
				if( $(this).prop('type')=='email' ){	
					var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  					if(!emailReg.test( $(this).prop('value') ) ){
						  isValid=false;
						  return isValid;
					}
				}
					
			}
			
    	});
		if(isValid==true){			
			//alert($("#clientCompanyId").val());
			//$("#clientCompanyId").val()
			if($("#clientCompanyId option:selected").index() > 0){
				
				$('#activate-step-2').prop('disabled',false);
				$('#pills-items-tab').removeClass('disabled');
			}
			
		}else{
			$('#activate-step-2').prop('disabled',true);
			$('#pills-items-tab').addClass('disabled');
		}
		
	});



});

function convertToCurrency(str){
	var id=str.getAttribute('id');
	var value=str.value;
	value = value.replace(/,/g,'');
	document.getElementById(id).value = numberWithCommas(value);

}
function numberWithCommas(x) {
    var parts = x.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");

    return parts.join(".");
}

function inputForm(str){
	var rowId=$(str).attr("id");
	$('#inputRowId2').prop('value',rowId);

	var itemDescription=$(str).find('td').eq(1).find('textarea').val();

	$('#inputRow_itemDescription2').prop('value',itemDescription);


	if($(window).width() < 991) {
		$('#inputRowId').prop('value',rowId);		
		var itemName=$(str).find('td').eq(0).find('input').val();
	//	var itemDescription=$(str).find('td').eq(1).find('textarea').val();
		var unitPrice=$(str).find('td').eq(2).find('input').val();
		var quantity=$(str).find('td').eq(3).find('input').val();

		$('#inputRow_itemName').prop('value',itemName);
		$('#inputRow_itemDescription').prop('value',itemDescription);
		$('#inputRow_unitPrice').prop('value',unitPrice);
		$('#inputRow_qty').prop('value',quantity);

		$('#inputFormModal').modal('toggle');
	}
}
// Add , Delete row dynamically
$(document).ready(function () {
	$('input.number-currency').keyup(function(event) {

// skip for arrow keys
	if(event.which >= 37 && event.which <= 40){
		event.preventDefault();
	}
	
	$(this).val(function(index, value) {
		value = value.replace(/,/g,'');
		return numberWithCommas(value);
	});
});
	
	if($(window).width() < 991) {
		$('#tab_logic input').attr('readonly','true');
		$('#tab_logic textarea').attr('readonly','true');
	}
	

	$('#inputItemDescriptionToTable').on("click",function(){

		var rowId=$("#inputRowId2").val();
		var itemDescription=$("#inputRow_itemDescription2").val();
		$("#"+rowId+"").find('td').eq(1).find('textarea').prop('value',itemDescription);


		});

	$('#inputValueToTable').on("click",function(){

		var rowId=$("#inputRowId").val();
		var itemName=$("#inputRow_itemName").val();
		var itemDescription=$("#inputRow_itemDescription").val();
		var unitPrice=$("#inputRow_unitPrice").val();
		var quantity=$("#inputRow_qty").val();

		$("#"+rowId+"").find('td').eq(0).find('input').prop('value',itemName);
		$("#"+rowId+"").find('td').eq(1).find('textarea').prop('value',itemDescription);
		$("#"+rowId+"").find('td').eq(2).find('input').prop('value',unitPrice);
		$("#"+rowId+"").find('td').eq(3).find('input').prop('value',quantity);

		
		
		
	});


    $("#addrow").on("click", function () {
        var newRow = $('<tr id="inputRow_'+counter+'"  onclick="inputForm(this)"> ');
        var cols = "";

	//	cols += '<td>'+counter+'</td>';
		cols += '<td><input type="text" class="form-control" name="itemName' + counter + '"/></td>';
		cols += '<td><textarea data-toggle="modal" readonly data-target="#itemDescriptionModal" class="form-control" rows="3" name="itemDesc' + counter + '"/></textarea></td>';
		cols += '<td><input type="text" class="form-control" id="inputUnitPrice'+counter+'" onkeyup="convertToCurrency(this)" name="unitCost' + counter + '"/></td>';
		cols += '<td><input type="text" class="form-control" name="qty' + counter + '"/></td>';
	//	cols += '<td><input type="text" class="form-control" name="price' + counter + '"/></td>';

        cols += '<td><button type="button"  class="ibtnDel btn btn-md btn-danger fa fa-minus" ></button></td>';
        newRow.append(cols);
        $("table.order-list").append(newRow);
		counter++;
		var currentRow=$("table.order-list >tbody >tr").length;
		if(currentRow>1){
			
			$(".ibtnDel").removeAttr('disabled');
			
		}
    });
	


    $("table.order-list").on("click", ".ibtnDel", function (event) {
		
		var currentRow=$("table.order-list >tbody >tr").length;
		if(currentRow>1){
			$(this).closest("tr").remove();
			if(currentRow==2){
				$("table.order-list >tbody >tr:first ").find('button').attr('disabled', true);
			}
		}
		
        //counter -= 1
    });


});

</script><style>
.modal-full {
    min-width: 90%;
    margin: 0;
}

</style><link href="./schuduling_files/bootstrap.css" rel="stylesheet"><link href="./schuduling_files/font-awesome.min.css" rel="stylesheet" type="text/css"><link href="./schuduling_files/dataTables.bootstrap4.css" rel="stylesheet"><link href="./schuduling_files/sb-admin.css" rel="stylesheet"><link href="./schuduling_files/custom-css.css" rel="stylesheet"><script>
    $(document).ready(function() {

    $('#toggleNavPosition').click(function() {
        $('body').toggleClass('fixed-nav');
        $('nav').toggleClass('fixed-top static-top');
    });

     $('#toggleNavColor').click(function() {
        $('nav').toggleClass('navbar-dark navbar-light');
        $('nav').toggleClass('bg-dark bg-light');
        $('body').toggleClass('bg-dark bg-light');
    });

    $("#sidenavToggler").click(function(e) {
        e.preventDefault();
        $("body").toggleClass("sidenav-toggled");
        $(".navbar-sidenav").toggleClass("sidenav-not-toggled");
        $(".navbar-sidenav .nav-link-collapse").addClass("collapsed");
        $(".navbar-sidenav .nav-link-collapseLink").addClass("collapsed");
        $(".navbar-sidenav .sidenav-second-level, .navbar-sidenav .sidenav-third-level").removeClass("show");
    });


/*    $('.sidenav-not-toggled').on('shown.bs.collapse', function() {

      var dif=$('.sidenav-not-toggled')[0].scrollHeight-currentScrollHeight;
      $(".sidenav-not-toggled").animate({scrollTop: dif+35});
    });

   var currentScrollHeight=$('.sidenav-not-toggled')[0].scrollHeight;
    var activeLink="";
    $('.nav-link').click(function(e) {

    //  alert($(this).prop('href'));
      //$(".sidenav-not-toggled").animate({scrollTop: $(".sidenav-not-toggled li").offset().top+30});
      //$('.sidenav-not-toggled').animate(
        //{scrollTop: $(".sidenav-not-toggled li").last().offset().top+30},'slow');

          $('.navbar-sidenav .show').removeClass('show');
          $("a[aria-expanded='true']").addClass("collapsed");
          $("a[aria-expanded='true']").prop('aria-expanded','false');

    }); */


    setInterval(function(){
        var str="notification";

          $.ajax({
                type  : 'GET',
                  url  : 'https://jcloud.my/testing/phpfunctions/clientComplaint.php',
                  data : {notf:str},
                  success: function (data) {

                      $(".complaint-notification").text(data);

                  }
            });


        var type='org';
        var id=''

        if(type=='vendor'){

          type='vendors';
          id='';
        }else{
          type='myStaff';
          id='37';
        }

          $.ajax({
              type  : 'GET',
                url  : 'https://jcloud.my/testing/phpfunctions/message.php',
                data : {messages:'1',userType:type,userId:id},
                dataType: 'json',
                success: function (data) {
                      if(data==null){
                        $('.message-notification-desktop').text("");
                        $('.message-notification-mobile').text("");
                        $('#message-notification-sub-details').text("");


                      }else{
                        var noOfMsg=data.length;
                          if(noOfMsg>0){
                            var badge="<span class='badge badge-pill badge-primary'>"+noOfMsg+" Unread Task</span>"
                            $('.message-notification-mobile').html(badge);
                        var messageSubDetails="";
                        $.each(data, function(index, obj){

                          messageSubDetails+="<div class='dropdown-divider'></div>";
                          messageSubDetails+="<a class='dropdown-item' href='#'>";
                          messageSubDetails+="<strong>"+obj.Sender+"</strong>";
                          messageSubDetails+="<div class='dropdown-message small'>"+obj.Subject+"</div>";
                          messageSubDetails+="<div class='small text-muted' style='text-align:right'>"+obj.Datetime+"</div>";
                          messageSubDetails+="</a>";
                        });
                        $('#message-notification-sub-details').html(messageSubDetails);


                            $('.message-notification-desktop').html("<i class='fa fa-fw fa-circle'></i>");
                          }
                      }

                }
          });

        }, 4000);


});

</script><style>



.bg-green{
    background-color:#43BFC7;

  }
  .inset {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  box-shadow:
    inset 0 0 0 2px rgba(255,255,255,0.6),
    0 1px 1px rgba(0,0,0,0.1);
  background-color: transparent !important;
  z-index: 999;
}

.inset img {
  border-radius: inherit;
  width: inherit;
  height: inherit;
  display: block;
  position: relative;
  z-index: 998;
}


</style></head>




<!--  <div class="bg-loader"><span class="loader"></span></div> -->

    <!--link href="https://jcloud.my/testing/adminTheme/bootstrap/css/bootstrap.min.css" rel="stylesheet"-->
    
    

    
    

    






 <body class="fixed-nav "><nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <a class="navbar-left" href="https://jcloud.my/testing/home.php">

      <!--<img  style="height: 50px;max-width:400px; " src='https://jcloud.my/testing/resources/2/myOrg/1585287549.png' >-->

  </a>

    <button class="navbar-toggler navbar-toggler-left" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon" style="background-color:rgb(182, 44, 14) "><i class="fa fa-bars" aria-hidden="true" style="margin:5px 5px 5px 5px;"></i></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
    '	      <ul class="navbar-nav navbar-sidenav sidenav-not-toggled " id="exampleAccordion">

        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
          <a class="nav-link active" style="padding:0px;" href="https://jcloud.my/testing/home.php">
            <img class="center" style="width:100%;" src="./schuduling_files/1585287549.png">
			</a>
        </li>


          <!-- DASHBOARD -->
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Add Invoice Schedule">
          <a class="nav-link active li-nav-style" href="scheduler.php">
            <i class="fa fa-dashboard"></i>
            <span class="nav-link-text">Add Invoice Schedule</span>
          </a>
        </li>
		<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Invoice Schedule List">
          <a class="nav-link active li-nav-style" href="scheduler-list.php">
            <i class="fa fa-dashboard"></i>
            <span class="nav-link-text">Invoice Schedule List</span>
          </a>
        </li>
            
             <!-- Share -->
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Share" style="background:#343A40">
          <a class="nav-link li-nav-style" href="https://jcloud.my/testing/organization/share/share.php">
            <i class="fa fa-list-alt"></i>
            <span class="nav-link-text">Share</span>
          </a>
        </li>
           <!-- HELP -->
       <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Help">
        <a class="nav-link li-nav-style" href="https://jcloud.my/testing/organization/help.php">
            <i class="fa fa-question-circle"></i>
            <span class="nav-link-text">Help</span>
          </a>
        </li>
            <!-- TERMS & PRIVACY -->
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Terms &amp; Privacy">
          <a class="nav-link li-nav-style" href="https://jcloud.my/testing/organization/terms.php">
            <!--<i class="fa fa-lock" style="color:#e6e6e6; font-size: 1.4em;"></i>--><i class="fa fa-question-circle"></i>
            <span class="nav-link-text">Terms &amp; Privacy</span>
          </a>
        </li>
    
    <!-- Backup -->
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Backup" style="background:#343A40">
          <a class="nav-link li-nav-style" href="https://jcloud.my/testing/organization/invoice/createInvoice.php#">
            <i class="fa fa-lock"></i>
            <span class="nav-link-text">Backup</span>
          </a>
        </li>

      </ul>

      <ul class="navbar-nav sidenav-toggler">
        <li class="nav-item">
          <a class="nav-link text-center" id="sidenavToggler">
            <i class="fa fa-fw fa-angle-left"></i>
          </a>
        </li>
        
      </ul>

           
      

      <ul class="navbar-nav ml-auto">
      
             <li class="nav-item">
          <form class="form-inline my-2 my-lg-0 mr-lg-2">
            <div class="input-group">
              <!-- <input class="form-control" type="text" placeholder="Search for..." style="background-color:white;height:30px;
              padding: .375rem .75rem;font-size: 1rem;line-height: 1.0;border: 1px solid #ced4da;border-radius: .25rem;">
              <span class="input-group-append">
                 <a href='https://jcloud.my/testing/home.php'>
                 <button class="btn btn-primary" type="button" style="height:30px;width:35px;padding-bottom:10px; margin-bottom:25px;
                background-color:rgb(182, 44, 14) border-color:rgb(182, 44, 14)">
                  <i class="fa fa-search"></i>
                </button><a/>
              </span> -->
            </div>
          </form>
        </li>

        
      <li class="nav-item">
      <img src="./schuduling_files/avatar-user.png" style="width:50px" ;="" "height:54px";=""><br>
      </li>

      <li class="nav-item">
      <a class="nav-link" href="https://jcloud.my/testing/organization/invoice/createInvoice.php#" style="color:rgb(182, 44, 14)" ,="" "font-weight="bold&quot;">
      Admin      </a>
    <!--
        <div class="inset">
            <img src="https://rs775.pbsrc.com/albums/yy35/PhoenyxStar/link-1.jpg~c200">
          </div>
          -->
      </li>
      <li class="nav-item" style="color:white">
          <a style="color:white" class="nav-link" data-toggle="modal" data-target="#logoutModal">
            Logout <i class="fa fa-sign-out"></i></a>
        </li>
      </ul>

    </div>
  </nav>
  <!--
  <script src="https://jcloud.my/testing/adminTheme/jquery/jquery.min.js"></script>
  <script src="https://jcloud.my/testing/adminTheme/bootstrap/js/bootstrap.bundle.min.js"></script>
          -->
  <!-- Core plugin JavaScript
  <script src='https://jcloud.my/testing/adminTheme/jquery-easing/jquery.easing.min.js'></script>
  -->
    <!-- Custom scripts for all pages
  <script src="https://jcloud.my/testing/adminTheme/js/sb-admin.min.js"></script>
-->

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="logoutModalLabel">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Select "Logout" to end your current session.</div>
          <div class="modal-footer">
          <form action="https://jcloud.my/testing/phpfunctions/logout.php" "post"="" method="POST">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <button class="btn btn-primary" name="logout" href="#">Log Out</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    	

<div class="content-wrapper">
    <div class="container-fluid">
    
  <nav class="navbar navbar-inverse">
<div class="navbar-header">

  <i class="fa fa-caret-square-o-down" style="font-size:25px;cursor: pointer;" data-toggle="collapse" data-target="#myNavbar" aria-hidden="true"></i><div style="background:white; margin: auto; border-radius: 10px; height: 10px; width: 10px;">
  </div>
</div>
<div class="collapse navbar-collapse" id="myNavbar">
  <ul class="nav navbar-nav">
  <div class="col-md-12"><center>
      <div id="div1" class="nav-item col-md-2" style="background:#343A40;border:0px; height: 35px; margin-bottom:10px;">
      <a href="https://jcloud.my/testing/organization/client/addClient.php" class="fa fa-user" style="color:white; font-size:15px; "></a><a style="font-size:12px" href="https://jcloud.my/testing/organization/client/addClient.php">
      Add Client <i class="fa fa-plus-square" style="color:white; font-size: 1.0em;"></i> </a></div>

      <div id="div2" class="nav-item col-md-2" style="background:#343A40;border:0px; height: 35px; margin-bottom:10px;">
        <a href="https://jcloud.my/testing/organization/vendor/addVendor.php" class="fa fa fa-user" style="color:white; font-size:15px; "></a><a style="font-size:12px" href="https://jcloud.my/testing/organization/vendor/addVendor.php">
        Add Vendor <i class="fa fa-plus-square" style="color:white; font-size: 1.0em;" ></i></a></div>

      <div id="div3" class="nav-item col-md-2" style="background:#343A40;border:0px; height: 35px; margin-bottom:10px;">
        <a href="https://jcloud.my/testing/organization/staff/addStaff.php" class="fa fa fa-user" style="color:white; font-size:15px;"></a><a style="font-size:12px" href="https://jcloud.my/testing/organization/staff/addStaff.php">
        Add Staff <i class="fa fa-plus-square" style="color:white; font-size: 1.0em;"></i></a></div>

      <div id="div3" class="nav-item col-md-2" style="background:#343A40;border:0px; height: 35px; margin-bottom:10px;">
      <a href="https://jcloud.my/testing/organization/complaint/uncompleted.php" class="fa fa-comment" style="color:white; font-size:15px; "></a><a style="font-size:12px" href="https://jcloud.my/testing/organization/complaint/uncompleted.php">
      Incidents <i class="badge badge-primary" style="color:white;"></i>0</a></div></center>
  </div>
  <ul>
</ul></ul></div>

        <!-- Breadcrumbs-->
      <ol class="breadcrumb col-md-12">
        <li class="breadcrumb-item">
          <a href="https://jcloud.my/testing/organization/invoice/createInvoice.php#">Quotation &amp; Invoice </a>
        </li>
		
        <li class="breadcrumb-item active ">Invoice</li>
		<li class="breadcrumb-item active">Add Scheduler</li>
      </ol>
    </nav></div>
		<div class="container">
			<?php
include_once ("db.php"); 
$str= "SELECT *  FROM `clientcompany` WHERE 1=1 order by  name ASC";
$client_list = mysqli_query($con,$str) or die(mysqli_error());
$str1= "SELECT *  FROM `invoice` WHERE 1=1 order by  invoiceNo ASC";
$client_invoice = mysqli_query($con,$str1) or die(mysqli_error());
			?>
				<form name="form1" method="post" action="scheduler-insert.php">
				<br>
				<div id="sections">
					<div class="section">
						<div class="form-group row" >
							<div class="col-md-5" style="float:left">
								<select name="clientCompanyId[]"  class="form-control" id="clientCompanyId" data-value="" onchange="getInvoice(this)">
								<option selected="" disabled="" value="">--Select Client--</option>
								<?php 
								while ($row = $client_list->fetch_assoc()) {
								?>							
								<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
								<?php } ?>	
							</select>								
							</div>						
							<div class="col-md-5" style="float:left">
								<select name="clientInvoiceId[]"  class="form-control" id="clientInvoiceId">	
								</select>	
							</div>
						<div class="col-md-2" style="float:left">
							<button  class="btn btn-danger btn-icon remove" name="" id="">-<i class="entypo-cancel"></i></button>
						</div>
						</div>
				</div>
			</div>
					<div class="form-group row text-right">
						<div class="col-md-12 invoice-add-button">
							<button class="btn btn-green btn-icon addsection" name="" id="">+<i class="entypo-check"></i></button>&nbsp;&nbsp;
						</div>
					</div>
						<div class="form-group row">
							<label for="schedule_date" class="col-sm-2 col-form-label col-form-label-lg">Set The Date: </label>
							<div class="col-sm-10">								
								<input type="date" class="form-control required" id="schedule_date" name="schedule_date" required="">								
							</div>
						</div>
						<div style="text-align:right">
				<button type="submit" class="btn btn-success" id="activate-step-4" name="scheduleInvoice" value="SAVE">Save</button>

					</div><br>
					<!--div class="form-group row text-right">
						<div class="col-md-12 ">
						<input type="submit" class="btn btn-success" name="scheduleInvoice" value="Save">
						</div>
					</div-->
					</form>
				</div>	
					

<script>
	var template = $('#sections .section:first').clone();
	var sectionsCount = 1;
	$('body').on('click', '.addsection', function() {
		sectionsCount++;
		var section = template.clone().find(':input').each(function(){

			//set id to store the updated section number
			var newId = this.id + sectionsCount;
			//update for label
			$(this).prev().attr('for', newId);
			//update id
			this.id = newId;
			this.setAttribute('data-value',sectionsCount);
			//this.name = newId;
		}).end()
		//inject new section
		.appendTo('#sections');
		return false;
	});	
	//remove section
		$('#sections').on('click', '.remove', function() {
			//fade out section
			$(this).parent().fadeOut(300, function(){
				//remove parent element (main section)				
				$(this).parent().parent().empty();
				return false;
			});
			return false;
		});
</script>
<script>
function getInvoice(t)

{
	
	var id = t.id;
	var value = t.value;
	var index =t.getAttribute('data-value');
	

$.ajax({
      url:'get-invoice.php',
      type: 'post',
      data: {'client_id': value},
      success: function(data, status) {
		var option='';
		document.getElementById("clientInvoiceId"+index).innerHTML = data;	
      },
    });
}
</script>	














					

			<div>
				<div class="footer">
				<p>Powered by JSoft Solution Sdn. Bhd</p>
				</div>
			</div>


		<a class="scroll-to-top rounded" href="https://jcloud.my/testing/organization/invoice/createInvoice.php#page-top">
			<i class="fa fa-angle-up"></i>
		</a>
		
  
 
 


</body></html>