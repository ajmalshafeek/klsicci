<!--  <div class="bg-loader"><span class="loader"></span></div> -->
<span id="page-top"></span>
<?php

$config=parse_ini_file(__DIR__."/jsheetconfig.ini");

require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/role.php");

require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/isLogin.php");

require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/pagePrivilege.php");

function br2nl( $input ) {

  return preg_replace('/<br\s?\/?>/ius', "\n", str_replace("\n","",str_replace("\r","", htmlspecialchars_decode($input))));

}
date_default_timezone_set("asia/kuala_lumpur");


//(START)PRIVILEGE----------------------------------------

$roleModulesList = loadModulesByRoleId($_SESSION['role']);

$module[]=false;

$pageCheck=0;

$_SESSION['userEditable']=false;

if (!isset($pageIdentity)) {

  $pageIdentity = NULL;

}



foreach ($roleModulesList as $roleModuleData) {

  $module[$roleModuleData['moduleId']]=true;

  if($roleModuleData['moduleId']==$pageIdentity){

    $pageCheck++;

  }

  if ($roleModuleData['moduleId']==16) {

    $_SESSION['userEditable']=true;

  }

    if ($roleModuleData['moduleId']==31) {

        $_SESSION['downloadclients']=true;

    }

    else{$_SESSION['downloadclients']=false;}

    if ($roleModuleData['moduleId']==33) {

        $_SESSION['editcomplaint']=true;

    }

    else{$_SESSION['editcomplaint']=false;}

}





if ($pageCheck==0) {

  //header("Location:  https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/role/fail.php");

}

//(END)PRIVILEGE----------------------------------------

?>





<script>

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

                  url  : '<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/phpfunctions/clientComplaint.php'; ?>',

                  data : {notf:str},

                  success: function (data) {



                      $(".complaint-notification").text(data);



                  }

            });





        var type='<?php echo $_SESSION['type']; ?>';

        var id=''



        if(type=='vendor'){



          type='vendors';

          id='<?php

          if(isset($_SESSION['vendorId'])){

            echo $_SESSION['vendorId'];

          }

          ?>';

        }else{

          type='myStaff';

          id='<?php echo $_SESSION['userid']; ?>';

        }



          $.ajax({

              type  : 'GET',

                url  : '<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/phpfunctions/message.php'; ?>',

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





		

		$(".scroll-to-top").click(function() {

    $('html, body').animate({

        scrollTop: $("#page-top").offset().top

    }, 2000);

});

		

});



</script>







 <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">

    <a class="navbar-left" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/home.php'; ?>'>



      <!--<img  style="height: 50px;max-width:400px; " src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/'.$_SESSION['orgLogo'].'.png'; ?>' >-->



  </a>



    <button class="navbar-toggler navbar-toggler-left" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">

      <span class="navbar-toggler-icon" style="background-color:rgb(182, 44, 14) "><i class="fa fa-bars" aria-hidden="true" style="margin:5px 5px 5px 5px;"></i></span>

    </button>

    <div class="collapse navbar-collapse" id="navbarResponsive">

    <?php

        require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientComplaint.php");

        $newComplaint=noOfNewComplaint();

    if (isOrganization()===true && isAdmin()===true) {

        ?>

      <ul class="navbar-nav navbar-sidenav sidenav-not-toggled " id="exampleAccordion">



        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">

          <a class="nav-link active" style="padding:0px;" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/home.php'; ?>'>

            <img class="center" style="width:100%;" src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/'.$_SESSION['orgLogo'].'.png'; ?>' >

          </a>

        </li>



    <?php

    //(START)PAID STATUS MODULE

      if (isset($module[26])) {

          $_SESSION['paidStatus'] = true;

      } else {

          $_SESSION['paidStatus'] = false;

      }

        //(END)PAID STATUS MODULE

    ?>

    <?php if (isset($module[1])) { ?>

      <!-- DASHBOARD -->

        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">

          <a class="nav-link active li-nav-style" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/home.php'; ?>'>

            <i class="fa fa-dashboard"></i>

            <span class="nav-link-text">Dashboard</span>

          </a>

        </li>

    <?php } ?>

<?php if (isset($module[10]) || isset($module[11])) { ?>

     <!-- JOBSHEET -->



    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Jobsheet">

      <a class="nav-link nav-link-collapse collapsed li-nav-style" style="color:white" data-toggle="collapse" href="#jobsheet" data-parent="#exampleAccordion">

      <i class="fa fa-file-text"></i>

        <span class="nav-link-text">Jobsheet</span>

      </a>



      <ul class="sidenav-second-level collapse" id="jobsheet">

        <?php if (isset($module[10])) { ?>

        <!-- COMPLAINT -->

        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Complaint">

          <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#collapseComplaint" data-parent="#exampleAccordion">

            <i class="fa fa-archive"></i>

            <span class="nav-link-text " >Incidents

                <?php if ($newComplaint>0) { ?>

                  <span style="background-color:white;color:black;"  class="badge badge-primary complaint-notification" ><?php echo $newComplaint ?></span>

                <?php }?>

            </span>



          </a>

          <ul class="sidenav-second-level collapse" id="collapseComplaint">

            <li>

              <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/complaint/makeComplaint.php'; ?>'>Create Task</a>

            </li>

            <li>

              <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/complaint/uncompleted.php'; ?>'>Incomplete

                  <?php if ($newComplaint>0) { ?>

                    <span class="badge badge-primary complaint-notification" style="background-color:white;color:black;"><?php echo $newComplaint ?></span>

                  <?php }?>

              </a>

            </li>

            <li>

              <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/complaint/completed.php'; ?>'>Completed</a>

            </li>

            <?php if (isset($module[20])): ?>

              <li>

                <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/complaint/viewSLA.php'; ?>'>Time Frame</a>

              </li>

            <?php endif; ?>

          </ul>

        </li>

        <?php } ?>

        <?php if (isset($module[11])) { ?>

        <!-- REPORT -->

        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Report">

          <a class="nav-link li-nav-style" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/report/report.php'; ?>'>

            <i class="fa fa-table"></i>

            <span class="nav-link-text">View Job Report</span>

          </a>

        </li>

        <?php } ?>

      </ul>



    </li>

<?php } ?>

<?php if (isset($module[23])) { ?>

    <!-- PROJECT -->

    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Project">

      <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#collapseProject" data-parent="#exampleAccordion">

        <i class="fa fa-user"></i>

        <span class="nav-link-text" >Project</span>

      </a>

      <ul class="sidenav-second-level collapse" id="collapseProject">

        <li>

          <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/project/createProject.php'; ?>'>Create Project</a>

        </li>

        <li>

          <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/project/viewProject.php'; ?>'>View Project</a>

        </li>

        <li>

          <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/project/reportProject.php'; ?>'>Gantt Chart</a>

        </li>

      </ul>

    </li>

<?php } ?>

    <?php if (isset($module[12]) || isset($module[17])) { ?>

         <!-- BUSINESS -->



        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Quotation & Invoice">

          <a class="nav-link nav-link-collapse collapsed li-nav-style" style="color:white" data-toggle="collapse" href="#quoteInvoice" data-parent="#exampleAccordion">

          <i class="fa fa-file-text"></i>

            <span class="nav-link-text">Business</span>

          </a>



          <ul class="sidenav-second-level collapse" id="quoteInvoice">

            <?php if (isset($module[12])) { ?>

            <li>

              <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#quotation">Quotation</a>

              <ul style="background-color:white"  class="sidenav-third-level collapse" id="quotation">

                <li>

                  <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/quotation/createQuotation.php'; ?>'>Create Quotation</a>

                </li>

                <li>

                  <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/quotation/viewQuotation.php'; ?>'>View Quotation</a>

                </li>

              </ul>

            </li>

            <?php } ?>

            <?php if (isset($module[17])) { ?>

            <li>

              <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#invoice">Invoice</a>

              <ul class="sidenav-third-level collapse" id="invoice">

                <li>

                  <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/invoice/createInvoice.php'; ?>'>Create Invoice</a>

                </li>

                <li>

                  <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/invoice/viewInvoice.php'; ?>'>View Invoice</a>

                </li>
              <li>

                  <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/invoice/report/report.php'; ?>'>Invoice Report</a>

              </li>

              </ul>

            </li>

            <?php if (isset($module[14])) { ?>

              <!-- SETTING -->

              <li>

                <a class="nav-link li-nav-style" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/setting/qiSetting.php'; ?>'>Setting</a>

              </li>

            <?php } ?>

            <?php } ?>

            <?php if(isset($module[35])){?>

            <li>

              <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#services">Services</a>

              <ul class="sidenav-third-level collapse" id="services">

                <li>

                  <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/services/addServices.php'; ?>'>Add Services</a>

                </li>

                <li>

                  <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/services/servicesDescription.php'; ?>'>Services Description</a>

                </li>

              </ul>

            </li>

          <?php }?>

          </ul>



        </li>

    <?php } ?>

    <?php if (isset($module[25])) { ?>

    <!-- Recurring Invoice -->

        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Recurring Ivoice">

          <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#collapseRecurringInvoice" data-parent="#exampleAccordion">

            <i class="fa fa-clipboard"></i>

            <span class="nav-link-text">Recurring Invoice</span>

          </a>

          <ul class="sidenav-second-level collapse" id="collapseRecurringInvoice">

            <li>

              <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/recurringInvoice/index.php'; ?>'>Recuring Inv. Create</a>

            </li>

            <li>

              <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/recurringInvoice/scheduler-list.php'; ?>'>Recuring Inv. List</a>

            </li>

          </ul>

        </li>

    <?php } ?>

    <?php if (isset($module[13])) { ?>

        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Summary Report">

        <a class="nav-link li-nav-style"  href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/report/quotation_invoice.php'; ?>'>

          <i class="fa fa-table"></i>

           <span class="nav-link-text">Summary</span>

         </a>

       </li>

    <?php } ?>

    <?php if (isset($module[24])) { ?>

        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Profit & Loss Report">

        <a class="nav-link li-nav-style"  href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/profitLoss/profitLoss.php'; ?>'>

          <i class="fa fa-table"></i>

           <span class="nav-link-text">Profit & Loss Report</span>

         </a>

       </li>

    <?php } ?>

    <?php if (isset($module[18])) { ?>

    <!-- Payroll -->

        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Payroll">

          <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#collapsePayroll" data-parent="#exampleAccordion">

            <i class="fa fa-clipboard"></i>

            <span class="nav-link-text">Payroll</span>

          </a>

          <ul class="sidenav-second-level collapse" id="collapsePayroll">

              <?php if($_SESSION["role"]==1 || $_SESSION["role"] == 42 || $_SESSION["role"] ==3 ){ ?>

            <li>

              <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/payroll/payroll.php'; ?>'>Payroll</a>

            </li>



            <li>

              <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/payroll/viewPayslip.php'; ?>'>View Payslips</a>

            </li>

                  <li>

                      <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/payroll/payrollreport.php'; ?>'>Payroll Report</a>

                  </li>

            <li>

              <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/payroll/payrollSetting.php'; ?>'>Setting</a>

            </li>

              <?php } else { ?>

                  <li>

                      <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/payroll/viewPayslip.php'; ?>'>View Payslips</a>

                  </li>

              <?php } ?>

          </ul>

        </li>

    <?php } ?>

    <!-- Purchase Order -->

    <?php  if (isset($module[27])) {?>

    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Purchase Order">

      <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#collapsePurchaseOrder" data-parent="#exampleAccordion">

        <i class="fa fa-clipboard"></i>

        <span class="nav-link-text">Purchase Order</span>

      </a>

      <ul class="sidenav-second-level collapse" id="collapsePurchaseOrder">

        <li>

          <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/purchaseOrder/manageSuppliers.php'; ?>'>Manage Suppliers</a>

        </li>

        <li>

          <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/purchaseOrder/createPO.php'; ?>'>Create PO</a>

        </li>

        <li>

          <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/purchaseOrder/viewPO.php'; ?>'>View PO</a>

        </li>

      </ul>

    </li>

    <?php

    } ?>

    <!-- Bill Payment -->

    <?php if(isset($module[28])){ ?>

        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Bill Payment">

          <a class="nav-link nav-link-collapse collapsed li-nav-style" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/bill/bill.php'; ?>'>

            <i class="fa fa-list-alt"></i>

            <span class="nav-link-text">Bill Payment</span>

          </a>

        </li>

    <?php } ?>

    <!-- Claims -->

     <?php if(isset($module[19])){ ?>



        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Claim">

          <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#collapseClaim" data-parent="#exampleAccordion">

            <i class="fa fa-clipboard"></i>

            <span class="nav-link-text">Claims</span>

          </a>

          <ul class="sidenav-second-level collapse" id="collapseClaim">

            <li>

              <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/claim/claimForm.php'; ?>'>Claim Form</a>

            </li>

            <li>

              <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/claim/viewClaim.php'; ?>'>View Claim</a>

            </li>

            <li>

              <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/claim/claimReport.php'; ?>'>Report</a>

            </li>

          </ul>

        </li>

         <?php } ?>

    <?php if(isset($module[22])){ ?>

        <!-- ATTENDANCE -->

        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Attendance">

          <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#collapseAttendance" data-parent="#exampleAccordion">

            <i class="fa fa-user"></i>

            <span class="nav-link-text" >Attendance</span>

          </a>

          <ul class="sidenav-second-level collapse" id="collapseAttendance">

            <li>

              <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/attendance/att.php'; ?>'>Clock In/Out</a>

            </li>

            <li>

              <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/attendance/viewAttendance.php'; ?>'>View Attendance</a>

            </li>

          </ul>

        </li>

    <?php } ?>

    <?php if(isset($module[29])){ ?>

        <!-- RECEIPT -->

        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Receipt">

          <a class="nav-link li-nav-style" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/receipt/receipt.php'; ?>'>

            <i class="fa fa-users"></i>

            <span class="nav-link-text">Receipt</span>

          </a>

        </li>

    <?php } ?>

    <?php if(isset($module[34])){ ?>

    <!-- Sharing -->

    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Sharing">

      <a class="nav-link li-nav-style" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/share/share.php'; ?>'>

        <i class="fa fa-list-alt"></i>

        <span class="nav-link-text">Sharing</span>

      </a>

    </li>

    <?php } ?>

    <?php if(isset($module[4])){ ?>

        <!-- CLIENT APPOINTMENT -->

        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="calendar">

          <a class="nav-link li-nav-style" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/calendar/calendar.php'; ?>'>

          <i class="fa fa-table"></i>

            <span class="nav-link-text">Client Appointment</span>

          </a>

        </li>

    <?php } ?>

    <?php if(isset($module[2])){ ?>

        <!-- MY ORGANIZATION -->

        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="My Organization">

          <a class="nav-link li-nav-style" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/myOrganization/updateMyOrg.php'; ?>'>

          <i class="fa fa-briefcase" ></i>

            <span class="nav-link-text">My Organization</span>

          </a>

        </li>

    <?php } ?>

    <?php if(isset($module[3])){ ?>

        <!-- APPOINTMENT -->

        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Schedule">

          <a class="nav-link li-nav-style" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/schedule/schedule.php'; ?>'>

          <i class="fa fa-calendar"></i>

            <span class="nav-link-text">Appointment</span>

          </a>

        </li>

    <?php } ?>

    <?php if(isset($module[5])){ ?>

        <!-- PRODUCT -->

        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Product">

          <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#collapseProduct" data-parent="#exampleAccordion">

            <i class="fa fa-archive"></i>

            <span class="nav-link-text">Product</span>

          </a>

          <ul class="sidenav-second-level collapse" id="collapseProduct">

            <li>

              <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/product/product.php'; ?>'>Add Product</a>

            </li>

            <li>

              <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/product/viewProduct.php'; ?>'>View Product</a>

            </li>

          </ul>

        </li>

    <?php } ?>

    <?php if(isset($module[6])){ ?>

        <!-- CLIENT -->

        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Client">

          <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#collapseClient" data-parent="#exampleAccordion">

            <i class="fa fa-user"></i>

            <span class="nav-link-text">Client/Site</span>

          </a>

          <ul class="sidenav-second-level collapse" id="collapseClient">

            <li>

              <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/client/addClient.php'; ?>'>Add Client/Site</a>

            </li>

            <li>

              <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/client/viewClient.php'; ?>'>View Client/Site</a>

            </li>

          </ul>

        </li>

    <?php } ?>

    <?php if(isset($module[7])){ ?>

        <!-- VENDOR -->

        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Vendor">

          <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#collapseVendor" data-parent="#exampleAccordion">

            <i class="fa fa-user"></i>

            <span class="nav-link-text">Vendor</span>

          </a>

          <ul class="sidenav-second-level collapse" id="collapseVendor">

            <li>

              <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/vendor/addVendor.php'; ?>'>Add Vendor</a>

            </li>

            <li>

              <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/vendor/addVendorClient.php'; ?>'>Assign Client</a>

            </li>

            <li>

              <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/vendor/addJobList.php'; ?>'>Add Job</a>

            </li>

            <li>

              <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/vendor/viewVendor.php'; ?>'>View Vendor</a>

            </li>

          </ul>

        </li>

    <?php } ?>

    <?php if(isset($module[8])){ ?>

        <!-- STAFF -->

        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Staff">

          <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#collapseStaff" data-parent="#exampleAccordion">

            <i class="fa fa-user"></i>

            <span class="nav-link-text" >Staff</span>

          </a>

          <ul class="sidenav-second-level collapse" id="collapseStaff">

            <li>

              <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/staff/addStaff.php'; ?>'>Add Staff</a>

            </li>

            <li>

              <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/staff/viewStaff.php'; ?>'>View Staff</a>

            </li>

                  <?php if (isset($module[32])) { ?>

              <li>

                  <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#department">Department</a>

                  <ul style="background-color:white"  class="sidenav-third-level collapse" id="department">

                      <li>

                          <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/department/addDepartment.php'; ?>'>Add Department</a>

                      </li>

                      <li>

                          <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/department/viewDepartment.php'; ?>'>View Department</a>

                      </li>



                  </ul>





              </li>



              <li>

                  <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#designation">Designation</a>

                  <ul style="background-color:white"  class="sidenav-third-level collapse" id="designation">

                      <li>

                          <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/department/addDesignation.php'; ?>'>Add Designation</a>

                      </li>

                      <li>

                          <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/department/viewDesignation.php'; ?>'>View Designation</a>

                      </li>

                      </ul>

              </li>

              <?php } ?>

          </ul>

        </li>

    <?php } ?>

    <?php if(isset($module[9])){ ?>

        <!-- PRIVILEGE -->

        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Privilege">

          <a class="nav-link li-nav-style" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/role/role.php'; ?>'>

            <i class="fa fa-users"></i>

            <span class="nav-link-text">Privilege</span>

          </a>

        </li>

    <?php } ?>

    <?php if(isset($module[15])){ ?>

       <!-- HELP -->

       <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Help">

        <a class="nav-link li-nav-style" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/help.php'; ?>'>

            <i class="fa fa-question-circle"></i>

            <span class="nav-link-text">Help</span>

          </a>

        </li>

    <?php } ?>

    <?php if(isset($module[16])){ ?>

    <!-- TERMS & PRIVACY -->

        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Terms & Privacy">

          <a class="nav-link li-nav-style" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/terms.php'; ?>'>

            <!--<i class="fa fa-lock" style="color:#e6e6e6; font-size: 1.4em;"></i>--><i class="fa fa-question-circle"></i>

            <span class="nav-link-text">Terms & Privacy</span>

          </a>

        </li>

    <?php } ?>



    <!-- Backup -->

        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Backup">

          <a class="nav-link li-nav-style" href='#'>

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

        </li>

      </ul>



      <?php }?>

     <?php if(isClient()===true){ ?>

           <ul class="navbar-nav navbar-sidenav sidenav-not-toggled " id="exampleAccordion">



        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Request">

          <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#collapseRequest" data-parent="#exampleAccordion">

            <i class="fa fa-clipboard"></i>

            <span class="nav-link-text">Request</span>

          </a>

          <ul class="sidenav-second-level collapse" id="collapseRequest">

            <li>

              <a id="COMPLAINT-MAKECOMPLAINT" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/client/complaint/makeComplaint.php'; ?>'>Make Request / Incident</a>

            </li>

            <li>

              <a  id="COMPLAINT-VIEWCOMPLAINT" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/client/complaint/viewComplaint.php'; ?>'>My Requests / Incidents</a>

            </li>

          </ul>

        </li>



        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Schedule">

          <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#collapseSchedule" data-parent="#exampleAccordion">

            <i class="fa fa-clipboard"></i>

            <span class="nav-link-text">Schedule</span>

          </a>

          <ul class="sidenav-second-level collapse" id="collapseSchedule">

            <li>

              <a id="SCHEDULE-MAKEBOOKING" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/schedule/clientview/booking/createbooking.php'; ?>'>Make Booking</a>

            </li>

            <li>

              <a  id="SCHEDULE-VIEWBOOKING" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/schedule/clientview/booking/viewbooking.php'; ?>'>My Booking</a>

            </li>

          </ul>

        </li>



      </ul>

      <ul class="navbar-nav sidenav-toggler">

        <li class="nav-item">

          <a class="nav-link text-center" id="sidenavToggler">

            <i class="fa fa-fw fa-angle-left"></i>

          </a>

        </li>

      </ul>

      <!--<ul class="navbar-nav mr-auto" >

        <li class="nav-item dropdown">

          <a class="nav-link dropdown-toggle " href="#" id="COMPLAINT" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

            REQUESTS

          </a>

          <div class="dropdown-menu " aria-labelledby="navbarDropdown">

          <a class="dropdown-item " id="COMPLAINT-MAKECOMPLAINT" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/client/complaint/makeComplaint.php'; ?>'>Make Request / Incident</a>

          <div class="dropdown-divider"></div>

              <a class="dropdown-item " id="COMPLAINT-VIEWCOMPLAINT" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/client/complaint/viewComplaint.php'; ?>' >My Requests / Incidents </a>

          </div>



        </li>



        <li class="nav-item dropdown">

          <a class="nav-link dropdown-toggle " href="#" id="SCHEDULE" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

            SCHEDULE

          </a>

          <div class="dropdown-menu " aria-labelledby="navbarDropdown">

          <a class="dropdown-item " id="SCHEDULE-MAKEBOOKING" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/schedule/clientview/booking/createbooking.php'; ?>'>Make Booking</a>

          <div class="dropdown-divider"></div>

              <a class="dropdown-item " id="SCHEDULE-VIEWBOOKING" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/schedule/clientview/booking/viewbooking.php'; ?>' >My Booking</a>

          </div>



        </li>

	    </ul>-->



      <?php } ?>



      <?php if((isOrganization()===true && isAdmin()===false) || (isVendor()===true)){

        $assignedTaskUrl="";

        $onSiteTaskUrl="";

        if(isVendor()===true){

          $assignedTaskUrl='https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/vendor/assignedTask/viewTask.php';

          $onSiteTaskUrl='https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/vendor/updateJob.php';



        }else{

          $assignedTaskUrl='https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/staff/assignedTask/viewTask.php';

          $onSiteTaskUrl='https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/staff/updateJob.php';

        }

        ?>

      <ul class="navbar-nav mr-auto">

        <li class="nav-item dropdown">

          <a class="nav-link dropdown-toggle " href="#" id="COMPLAINT" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

            TASK

          </a>

          <div class="dropdown-menu " aria-labelledby="navbarDropdown">

          <a class="dropdown-item " id="COMPLAINT-MAKECOMPLAINT" href='<?php echo $assignedTaskUrl; ?>' >ASSIGNED TASK</a>

          <!-- <a class="dropdown-item " id="COMPLAINT-MAKECOMPLAINT" href='<?php echo $onSiteTaskUrl; ?>' >ON-SITE TASK</a> -->

          </div>

        </li>

      </ul>

	  </ul>





      <?php } ?>





      <ul class="navbar-nav ml-auto" >

      <?php if((isOrganization()===true && isAdmin()===false) || (isVendor()===true) ){



          require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/message.php");

          $newTaskNotification=null;

          $pendingTaskUrl="";

          if(isVendor()===true){

            $newTaskNotification=newTaskNotification("vendors",$_SESSION['vendorId']);

            $pendingTaskUrl='https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/vendor/assignedTask/viewTask.php';



          }else{

            $newTaskNotification=newTaskNotification("myStaff",$_SESSION['userid']);

            $pendingTaskUrl='https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/staff/assignedTask/viewTask.php';

          }

          $desktopNotification="";

          if($newTaskNotification!=null){

            $desktopNotification="<i class='fa fa-fw fa-circle'></i>";

            $mobileNotification="<span class='badge badge-pill badge-primary'>".sizeof($newTaskNotification)." Unread Task</span>";

          }else{



          }



        ?>

        <li class="nav-item dropdown">

          <a class="nav-link dropdown-toggle mr-lg-2" id="messagesDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

            <i class="fa fa-fw fa-envelope"></i>

            <span class="d-lg-none message-notification-mobile">Messages

            <?php

              if($newTaskNotification!=null)

              {

                echo $mobileNotification;

              }

            ?>

            </span>

            <span class="indicator text-primary d-none d-lg-block message-notification-desktop">

            <?php

              echo $desktopNotification;

            ?>

            </span>

          </a>

          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="messagesDropdown">

            <h6 class="dropdown-header">Unread Task:</h6>

            <span id="message-notification-sub-details">



            <?php

            if($newTaskNotification!=null){

              $notification="";

              $i=0;

              foreach($newTaskNotification as $message){

                $i++;

                if($i>4){

                  break;

                }

                $notification.="<div class='dropdown-divider'></div>"; //separator

                $notification.="<a class='dropdown-item' href='#'>"; //link to view message content

                $notification.=" <strong>".$message['Sender']."</strong>"; //sender of message

                $notification.="<div class='dropdown-message small'>".$message['Subject']."</div>"; //header of the message

                $notification.="<div class='small text-muted' style='text-align:right'>".$message['Datetime']."</div>"; //datetime of message

                $notification.="</a>";





              }

            echo $notification;

            }

            ?>

            </span>

            <div class="dropdown-divider"></div>

            <a class="dropdown-item small" href='<?php echo $pendingTaskUrl  ?>'>View all Incomplete task</a>

          </div>

        </li>



      <?php } ?>



       <?php

        require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientComplaint.php");

        $newComplaint=noOfNewComplaint();

    if(isOrganization()===true && isAdmin()===true) {



      ?>

      <li class="nav-item">

          <form class="form-inline my-2 my-lg-0 mr-lg-2">

            <div class="input-group">

              <!-- <input class="form-control" type="text" placeholder="Search for..." style="background-color:white;height:30px;

              padding: .375rem .75rem;font-size: 1rem;line-height: 1.0;border: 1px solid #ced4da;border-radius: .25rem;">

              <span class="input-group-append">

                 <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/home.php'; ?>'>

                 <button class="btn btn-primary" type="button" style="height:30px;width:35px;padding-bottom:10px; margin-bottom:25px;

                background-color:rgb(182, 44, 14) border-color:rgb(182, 44, 14)">

                  <i class="fa fa-search"></i>

                </button><a/>

              </span> -->

            </div>

          </form>

        </li>



        <?php



    }



        ?>



     

      <script type="text/javascript">

        function themePanel(id){

          if (id == 0) {

            document.getElementById("themeClose").style.display = "none";

            document.getElementById("themeOpen").style.display = "block";

            document.getElementById("themeColors").style.display = "block";

          }else if (id == 1) {

            document.getElementById("themeOpen").style.display = "none";

            document.getElementById("themeClose").style.display = "block";

            document.getElementById("themeColors").style.display = "none";

            document.getElementsByClassName("tooltipTheme").style.display = "none";

          }

        }



        document.addEventListener("DOMContentLoaded", function(event) {

            var scrollpos = localStorage.getItem('scrollpos');

            if (scrollpos) window.scrollTo(0, scrollpos);

        });



        window.onbeforeunload = function(e) {

            localStorage.setItem('scrollpos', window.scrollY);

        };

        $(document).ready(function() {

        $("#dataTable,.dataTable ").wrap("<div class='custom-div'></div>");

        });

      </script>

      <li id="themeColors" style="display:none;">

        <a href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/phpfunctions/theme.php?changeTheme=default.css'; ?>"><i class="fa fa-square themes" style="color:#EF0B06;"></i></a>

        <a href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/phpfunctions/theme.php?changeTheme=orange.css'; ?>"><i class="fa fa-square themes" style="color:#EB6A22;"></i></a>

        <a href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/phpfunctions/theme.php?changeTheme=turquoise.css'; ?>"><i class="fa fa-square themes" style="color:#B0D5D0;"></i></a>

      </li>

      <li>

        <div id="themeClose" onclick="themePanel(0)" class="tooltipTheme"><i class="fa fa-chevron-left themeArrows"></i><span class="tooltiptextTheme">Change Theme</span></div>

        <div id="themeOpen" onclick="themePanel(1)" class="tooltipTheme" style="display:none"><i class="fa fa-chevron-right themeArrows"></i><span class="tooltiptextTheme">Close</span></div>

      </li>



      <li class="nav-item">

      <img  src="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/avatar-user.png'; ?>" style="width:50px"; "height:54px";><br />

      </li>



      <li class="nav-item">

      <a class="nav-link" href="#" style="color:rgb(182, 44, 14)", "font-weight=bold"  >

      <?php

        echo $_SESSION['name'];

      ?>

      </a>

    <!--

        <div class="inset">

            <img src="https://rs775.pbsrc.com/albums/yy35/PhoenyxStar/link-1.jpg~c200">

          </div>

          -->

      </li>

      <li class="nav-item" style="color:white">

          <a  style="color:white" class="nav-link" data-toggle="modal" data-target="#logoutModal">

            Logout <i class="fa fa-sign-out" ></i></a>

        </li>

      </ul>



    </div>

  </nav>

  <!--

  <script src="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/jquery/jquery.min.js"></script>

  <script src="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/bootstrap/js/bootstrap.bundle.min.js"></script>

          -->

  <!-- Core plugin JavaScript

  <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/jquery-easing/jquery.easing.min.js'></script>

  -->

    <!-- Custom scripts for all pages

  <script src="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/js/sb-admin.min.js"></script>

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

          <form action='<?php echo  'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/phpfunctions/logout.php'; ?>' "POST" method="POST">

            <button class="btn btn-secondary cancel" type="button" data-dismiss="modal">Cancel</button>

            <button class="btn btn-primary edit" name="logout" href="#">Log Out</button>

            </form>

          </div>

        </div>

      </div>

    </div>



    <?php

$_SESSION['module']=$module;

function shortcut(){

	$modulecheck=$_SESSION['module'];

  $config=parse_ini_file(__DIR__."/jsheetconfig.ini");

  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/clientComplaint.php");

  $newComplaint=noOfNewComplaint();

  //<button type='button' class='navbar-toggle' data-toggle='collapse' data-target='#myNavbar' style='cursor: context-menu; background:#3c8dbc; border: 0px; border-radius: 15px; height: 25px; width: 25px;'><div style='background:white; margin: auto; border-radius: 10px; height: 10px; width: 10px;'>

  $shortcut ="

  <nav class='navbar navbar-inverse'>

<div class='navbar-header'  data-toggle='collapse' data-target='#myNavbar' style='font-size:25px;cursor: pointer;'>



  <i class='fa fa-angle-right' style='margin-right: 10px;margin-left:10px' data-toggle='collapse' data-target='#myNavbar' aria-hidden='true'></i>Quick Menu

</div>

<div class='collapse navbar-collapse' id='myNavbar'>

  <ul class='nav navbar-nav'>

  <div class='col-md-12'><center>";

if(isset($modulecheck[6])){



    $shortcut=$shortcut."<a href = 'https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/client/addClient.php'><div id='div2' class='nav-item col-md-2' style='background:#3c8dbc;border:0px;margin-bottom:10px;'>

      <i class='fa fa fa-user' style='color:white; font-size:15px;margin-right:5px '></i><span style='font-size:12px;color:white;'>Add Client <i class='fa fa-plus-square' style='color:white; font-size: 1.0em;'></i></span></div></a>";}

if(isset($modulecheck[7])){

    $shortcut=$shortcut."<a href = 'https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/vendor/addVendor.php'><div id='div2' class='nav-item col-md-2' style='background:#3c8dbc;border:0px;margin-bottom:10px;'>

      <i class='fa fa fa-user' style='color:white; font-size:15px;margin-right:5px '></i><span style='font-size:12px;color:white;'>Add Vendor <i class='fa fa-plus-square' style='color:white; font-size: 1.0em;''></i></span></div></a>";}

if(isset($modulecheck[8])){

    $shortcut=$shortcut."<a href = 'https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/staff/addStaff.php'><div id='div3' class='nav-item col-md-2' style='background:#3c8dbc;border:0px;margin-bottom:10px;'>

      <i class='fa fa fa-user' style='color:white; font-size:15px;margin-right:5px '></i><span style='font-size:12px;color:white;'>Add Staff <i class='fa fa-plus-square' style='color:white; font-size: 1.0em;'></i></span></div></a>";}

if(isset($modulecheck[10])){

      $shortcut=$shortcut."<a href = 'https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/organization/complaint/uncompleted.php'><div id='div3' class='nav-item col-md-2' style='background:#3c8dbc;border:0px;margin-bottom:10px;'>

      <i class='fa fa-comment' style='color:white; font-size:15px;margin-right:5px '></i><span style='font-size:12px;color:white;'>Incidents <i class='badge badge-primary' style='color:white;'></i>".$newComplaint."</span></div></a>";}

	$shortcut=$shortcut."</center>

  </div>

  <ul>

</div>";

	unset($_SESSION['module']);

    echo $_SESSION['downloadclients'];

  return $shortcut;

}



?>