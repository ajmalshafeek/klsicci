<!--  <div class="bg-loader"><span class="loader"></span></div> -->
<span id="page-top"></span>
<?php

$config = parse_ini_file(__DIR__ . "/jsheetconfig.ini");

require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/role.php");
require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/isLogin.php");
require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/pagePrivilege.php");

function br2nl($input)
{
    return preg_replace('/<br\s?\/?>/ius', "\n", str_replace("\n", "", str_replace("\r", "", htmlspecialchars_decode($input))));
}

date_default_timezone_set("asia/kuala_lumpur");
//(START)PRIVILEGE----------------------------------------

$roleModulesList = loadModulesByRoleId($_SESSION['role']);
$module[] = false;
$pageCheck = 0;
$_SESSION['userEditable'] = false;
if (!isset($pageIdentity)) {
    $pageIdentity = NULL;
}

$_SESSION['editcomplaint'] = false;
foreach ($roleModulesList as $roleModuleData) {

    $module[$roleModuleData['moduleId']] = true;

    if ($roleModuleData['moduleId'] == $pageIdentity) {
        $pageCheck++;
    }

    if ($roleModuleData['moduleId'] == 16) {
        $_SESSION['userEditable'] = true;
    }

    if ($roleModuleData['moduleId'] == 31) {
        $_SESSION['downloadclients'] = true;
    } else {
        $_SESSION['downloadclients'] = false;
    }

    if ($roleModuleData['moduleId'] == 33) {
        $_SESSION['editcomplaint'] = true;
    }
    if ($roleModuleData['moduleId'] == 33) {
        $_SESSION['complaintextra'] = true;
    }
    if ($roleModuleData['moduleId'] == 40) {
        if (isset($_SESSION['complaintExtra'])) {
            $_SESSION['complaintExtra'] = true;
        } else {
            $_SESSION['complaintExtra'] = false;
        }
    } else {
        $_SESSION['complaintExtra'] = false;
    }
}
$_SESSION['hideQuotation'] = true;
if (isset($module[39])) {
    $_SESSION['hideQuotation'] = false;
}


if ($pageCheck == 0) {
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


        $('.sidenav-not-toggled').on('shown.bs.collapse', function() {



            var dif = $('.sidenav-not-toggled')[0].scrollHeight - currentScrollHeight;

            $(".sidenav-not-toggled").animate({
                scrollTop: dif + 35
            });

        });



        var currentScrollHeight = $('.sidenav-not-toggled')[0].scrollHeight;

        var activeLink = "";

        $('.nav-link').click(function(e) {



            //  alert($(this).prop('href'));

            //$(".sidenav-not-toggled").animate({scrollTop: $(".sidenav-not-toggled li").offset().top+30});

            //$('.sidenav-not-toggled').animate(

            //{scrollTop: $(".sidenav-not-toggled li").last().offset().top+30},'slow');



            $('.navbar-sidenav .show').removeClass('show');

            $("a[aria-expanded='true']").addClass("collapsed");

            $("a[aria-expanded='true']").prop('aria-expanded', 'false');



        });


        setInterval(function() {

            var str = "notification";


            $.ajax({

                type: 'GET',

                url: '<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/phpfunctions/clientComplaint.php'; ?>',

                data: {
                    notf: str
                },

                success: function(data) {
                    $(".complaint-notification").text(data);
                }
            });

            var type = '<?php echo $_SESSION['type']; ?>';
            var id = ''

            if (type == 'vendor') {

                type = 'vendors';
                id = '<?php
                        if (isset($_SESSION['vendorId'])) {
                            echo $_SESSION['vendorId'];
                        }
                        ?>';
            } else {
                type = 'myStaff';
                id = '<?php echo $_SESSION['userid']; ?>';
            }

            $.ajax({
                type: 'GET',
                url: '<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/phpfunctions/message.php'; ?>',
                data: {
                    messages: '1',
                    userType: type,
                    userId: id
                },
                dataType: 'json',
                success: function(data) {
                    if (data == null) {
                        $('.message-notification-desktop').text("");
                        $('.message-notification-mobile').text("");
                        $('#message-notification-sub-details').text("");
                    } else {
                        var noOfMsg = data.length;
                        if (noOfMsg > 0) {
                            var badge = "<span class='badge badge-pill badge-primary'>" + noOfMsg + " Unread Task</span>"
                            $('.message-notification-mobile').html(badge);
                            var messageSubDetails = "";
                            $.each(data, function(index, obj) {

                                messageSubDetails += "<div class='dropdown-divider'></div>";

                                messageSubDetails += "<a class='dropdown-item' href='#'>";

                                messageSubDetails += "<strong>" + obj.Sender + "</strong>";

                                messageSubDetails += "<div class='dropdown-message small'>" + obj.Subject + "</div>";

                                messageSubDetails += "<div class='small text-muted' style='text-align:right'>" + obj.Datetime + "</div>";

                                messageSubDetails += "</a>";

                            });

                            $('#message-notification-sub-details').html(messageSubDetails);


                            $('.message-notification-desktop').html("<i class='fa fa-fw fa-circle'></i>");

                        }

                    }


                }

            });
            $.ajax({
                type: 'GET',
                url:  '<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/phpfunctions/nortification.php?'; ?>',
                data: {
                    newrequest: true
                },
                success: function(data) {
                    $('.newrequest').html(data);
                }
            });

            $.ajax({
                url:  '<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/phpfunctions/nortification.php?'; ?>',
                data: {
                    potential: true
                },
                type: 'GET',
                success: function(data) {
                    $('.potential').html(data);
                    //document.getElementById("tableProduct").innerHTML = data;
                }
            });

            $.ajax({
                url:  '<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/phpfunctions/nortification.php?'; ?>',
                data: {
                    order: true
                },
                type: 'GET',
                success: function(data) {
                    $('.orderbox').html(data);
                    //document.getElementById("tableProduct").innerHTML = data;
                }
            });

            $.ajax({
                url:  '<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/phpfunctions/nortification.php?'; ?>',
                data: {
                    member: true
                },
                type: 'GET',
                success: function(data) {
                    $('.memberbox').html(data);
                    //document.getElementById("tableProduct").innerHTML = data;
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

    <a class="navbar-left" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/home.php'; ?>'>


        <!--<img  style="height: 50px;max-width:400px; " src='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/resources/' . $_SESSION['orgLogo'] . '.png'; ?>' >-->


    </a>


    <button class="navbar-toggler navbar-toggler-left" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">

        <span class="navbar-toggler-icon" style="background-color:rgb(182, 44, 14) "><i class="fa fa-bars" aria-hidden="true" style="margin:5px 5px 5px 5px;"></i></span>

    </button>

    <div class="collapse navbar-collapse" id="navbarResponsive">

        <?php
        require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/clientComplaint.php");
        $newComplaint = noOfNewComplaint();
        if (isOrganization() === true) {
            if (isAdmin() === true) {
        ?>

                <ul class="navbar-nav navbar-sidenav sidenav-not-toggled " id="exampleAccordion">


                    <div class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">

                        <a class="nav-link" style="padding:0px;" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/home.php'; ?>'>

                            <!-- <img class="center" style="width:100%;"
                             src='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/resources/' . $_SESSION['orgLogo'] . '.png'; ?>'>-->


                            <div class="slider-icon-section">
                                <div class="logo-icon"" style=" background-image:url(<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/resources/' . $_SESSION['orgLogo'] . '.png'; ?>);width: 50px;height: 50px;"></div>
                                <div class="icon-text"><?php echo $_SESSION['orgName']; ?><br /><span><?php echo $_SESSION['name']; ?></span></div>
                            </div>
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

                                <a class="nav-link li-nav-style" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/home.php'; ?>'>

                                    <i class="fa fa-dashboard"></i>

                                    <span class="nav-link-text">Dashboard</span>

                                </a>

                            </li>
                            <?php  /*<li class="nav-item" data-toggle="tooltip" data-placement="right" >

                        <a  class="nav-link active li-nav-style" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . ''; ?>/v2/index.html'>
                            <i class="fa fa-dashboard"></i>
                            <span class="nav-link-text">Membership (Dashboard)</span>
                        </a>

                    </li> */ ?>
                        <?php } ?>
                        <?php if (isset($module[59])) { ?>
                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Membership">

                                <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#collapseMembership" data-parent="#exampleAccordion">
                                    <i class="fa fa-list-alt"></i>
                                    <span class="nav-link-text">Membership</span>
                                </a>
                                <ul class="sidenav-second-level collapse" id="collapseMembership">
                                    <?php if (isset($module[1])) { ?>
                                        <li>
                                            <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/v2/index.html'; ?>'>
                                                Membership (Dashboard)</a>
                                        </li>
                                    <?php } ?>
                                    <li>
                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/auditors/memershipApporval.php'; ?>'>
                                            Membership Approval</a>
                                    </li>
                                    <li>
                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/auditors/memershipList.php'; ?>'>
                                            Approved Members</a>
                                    </li>
                                    <li>
                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/auditors/memershipOverList.php'; ?>'>
                                            Expired Member</a>
                                    </li>
                                </ul>
                            </li>
                            <?php /* <li class="nav-item" data-toggle="tooltip" data-placement="right" >
                       <a  class="nav-link li-nav-style" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . ''; ?>/organization/auditors/memershipApporval.php'>
                           <i class="fa fa-list-alt"></i>
                           <span class="nav-link-text">Membership Approval</span>
                       </a>
                    </li>
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" >
                        <a  class="nav-link li-nav-style" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . ''; ?>/organization/auditors/memershipList.php'>
                            <i class="fa fa-list-alt" aria-hidden="true"></i>
                            <span class="nav-link-text">Approved Members</span>
                        </a>
                    </li>
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" >
                        <a  class="nav-link li-nav-style" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . ''; ?>/organization/auditors/memershipOverList.php'>
                            <i class="fa fa-list-alt" aria-hidden="true"></i>
                            <span class="nav-link-text">Expired Member</span>
                        </a>
                    </li>
                    <?php */ ?>
                        <?php  } ?>
                        <?php if (isset($module[62])) { ?>
                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Professional Development">

                                <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#collapseProfessionalDevelopment" data-parent="#exampleAccordion">
                                    <i class="fa fa-user-o" aria-hidden="true"></i>
                                    <span class="nav-link-text">Professional Development</span>
                                </a>
                                <ul class="sidenav-second-level collapse" id="collapseProfessionalDevelopment">
                                    <li>
                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/auditors/professionalDevelopment.php'; ?>'>
                                            Enquiry</a>
                                    </li>
                                    <li>
                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/auditors/pdRegisterProgram.php'; ?>'>
                                            Register Program</a>
                                    </li>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php if (isset($module[58])) { ?>
                            <?php if (isset($module[68])) { ?>
                                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Certification">

                                    <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#collapseCertification" data-parent="#exampleAccordion">
                                        <i class="fa fa-certificate" aria-hidden="true"></i>
                                        <span class="nav-link-text">Certification</span>
                                    </a>
                                    <ul class="sidenav-second-level collapse" id="collapseCertification">
                                        <li>
                                            <a href="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/auditors/ccmsEligiblity.php'; ?>">
                                                Statement of Eligiblity</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/auditors/candidatesReporting.php'; ?>">
                                                Candidates Reporting</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/auditors/certifiedMembers.php'; ?>">
                                                Certified Members</a>
                                        </li>
                                        <?php if ($module[69]) { ?>
                                            <li>
                                                <a href="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/auditors/e-sign.php'; ?>">
                                                    eCertificate Approval</a>
                                            </li>
                                        <?php } ?>
                                        <li>
                                            <a href="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/auditors/eCertificateHistory.php'; ?>">
                                                eCertificate List</a>
                                        </li>
                                        <?php /* <li>
                                <a href="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/auditors/eCertificateStatus.php';?>" >
                                    eCertificate Status</a>
                            </li> */  ?>
                                        <?php if ($_SESSION['role'] != 72) { ?>
                                            <li>
                                                <a href="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/auditors/GenerateECertificate.php'; ?>">
                                                    Generate eCertificate</a>
                                            </li>
                                        <?php } ?>

                                    </ul>
                                </li>
                            <?php }
                            if (isset($module[64])) { ?>
                                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="EPF Letter Approval">

                                    <a class="nav-link li-nav-style" href="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot']; ?>/organization/auditors/epfLetterApproval.php">
                                        <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                        <span class="nav-link-text">EPF Letter Approval</span>
                                    </a>
                                </li>
                            <?php }
                            if (isset($module[65])) { ?>
                                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Examination">

                                    <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#collapseExamination" data-parent="#exampleAccordion">

                                        <i class="fa fa-graduation-cap" aria-hidden="true"></i>

                                        <span class="nav-link-text">Examination</span>

                                    </a>

                                    <ul class="sidenav-second-level collapse" id="collapseExamination">

                                        <li>

                                            <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/auditors/examinationProgress.php'; ?>'>
                                                Examination Progress</a>

                                        </li>

                                        <li>

                                            <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/auditors/examinationList.php'; ?>'>
                                                Examination List</a>

                                        </li>
                                    </ul>
                                </li>
                                <?php /*

                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Examination Progress">

                        <a class="nav-link li-nav-style" href="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot']; ?>/organization/auditors/examinationList.php">
                            <i class="fa fa-user"></i>
                            <span class="nav-link-text">Examination Progress</span>
                        </a>
                    </li> */ ?>
                        <?php }
                        } ?>
                        <?php if (isset($module[8])) { ?>

                            <!-- STAFF -->

                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Staff">

                                <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#collapseStaff" data-parent="#exampleAccordion">

                                    <i class="fa fa-user"></i>

                                    <span class="nav-link-text">Staff</span>

                                </a>

                                <ul class="sidenav-second-level collapse" id="collapseStaff">

                                    <li>

                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/staff/addStaff.php'; ?>'>Add
                                            Staff</a>

                                    </li>

                                    <li>

                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/staff/viewStaff.php'; ?>'>View
                                            Staff</a>

                                    </li>

                                    <?php if (isset($module[32])) {  /* ?>

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
<?php */ ?>

                                        <li>

                                            <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#designation">Designation</a>

                                            <ul style="background-color:white" class="sidenav-third-level collapse" id="designation">

                                                <li>

                                                    <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/department/addDesignation.php'; ?>'>Add
                                                        Designation</a>

                                                </li>

                                                <li>

                                                    <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/department/viewDesignation.php'; ?>'>View
                                                        Designation</a>

                                                </li>

                                            </ul>

                                        </li>

                                    <?php } ?>

                                </ul>

                            </li>

                        <?php } ?>
                        <?php if (isset($module[75])) { ?>
                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Requisition">
                                <a class="nav-link li-nav-style" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/auditors/requisition.php'; ?>'>
                                    <i class="fa fa-sliders" aria-hidden="true"></i>
                                    <span class="nav-link-text">Requisition</span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if (isset($module[71])) { ?>
                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="ReportManager">

                                <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#collapseReportManager" data-parent="#exampleAccordion">

                                    <i class="fa fa-briefcase" aria-hidden="true"></i>

                                    <span class="nav-link-text">Report Manager</span>

                                </a>

                                <ul class="sidenav-second-level collapse" id="collapseReportManager">
                                    <li>
                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/auditors/applicantsReport.php'; ?>'>
                                            Applicants report</a>
                                    </li>
                                    <li>
                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/auditors/memberDemographicReport.php'; ?>'>
                                            Member Demographic Report</a>
                                    </li>
                                    <li>
                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/auditors/memberEligibleReport.php'; ?>'>
                                            Member Eligible Report</a>
                                    </li>
                                    <li>
                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/auditors/memberHistoryReport.php'; ?>'>
                                            Member History Report</a>
                                    </li>
                                    <li>
                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/auditors/memberListReport.php'; ?>'>
                                            Member List Report</a>
                                    </li>
                                    <li>
                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/auditors/membershipSummaryReport.php'; ?>'>
                                            Membership Summary Report</a>
                                    </li>
                                    <li>
                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/auditors/paymentHistoryReport.php'; ?>'>
                                            Payment History Report</a>
                                    </li>
                                    <li>
                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/auditors/paymentOutstandingReport.php'; ?>'>
                                            Payment Outstanding Report</a>
                                    </li>
                                    <li>
                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/auditors/rejoinOrUpgradingReport.php'; ?>'>
                                            Re-Join / Upgrading Report</a>
                                    </li>
                                    <li>
                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/auditors/submissionReport.php'; ?>'>
                                            Submission Report</a>
                                    </li>
                                </ul>
                            </li>
                        <?php }
                        if (isset($module[72])) { ?>
                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="QAR">

                                <a class="nav-link li-nav-style" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . ''; ?>/organization/auditors/qar.php'>
                                    <i class="fa fa-tags" aria-hidden="true"></i>
                                    <span class="nav-link-text">QAR</span>
                                </a>

                            </li>
                        <?php }
                        if (isset($module[70])) { ?>
                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Event">

                                <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#collapseEvent" data-parent="#exampleAccordion">

                                    <i class="fa fa-th-list" aria-hidden="true"></i>

                                    <span class="nav-link-text">Event Manager</span>

                                </a>

                                <ul class="sidenav-second-level collapse" id="collapseEvent">

                                    <li>

                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/auditors/eventGenerator.php'; ?>'>
                                            Event Generator</a>

                                    </li>
                                    <li>

                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/auditors/eventGeneratedView.php'; ?>'>
                                            View Events</a>

                                    </li>
                                    <li>

                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/auditors/eventRegister.php'; ?>'>
                                            Event Register</a>

                                    </li>

                                    <li>

                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/auditors/eventMarketing.php'; ?>'>
                                            Event Marketing</a>

                                    </li>
                                </ul>
                            </li>
                        <?php }
                        if (isset($module[67])) { ?>
                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Research Grant">

                                <a class="nav-link li-nav-style" href="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot']; ?>/organization/auditors/researchGrant.php">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                    <span class="nav-link-text">Research Grant</span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if (isset($module[63])) { ?>
                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Technical Inquiry">

                                <a class="nav-link li-nav-style" href="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot']; ?>/organization/auditors/technicalInquiry.php">
                                    <i class="fa fa-book" aria-hidden="true"></i>
                                    <span class="nav-link-text">Technical Inquiry</span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if (isset($module[76])) { ?>
                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Requisition">
                                <a class="nav-link li-nav-style" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/auditors/recruitment.php'; ?>'>
                                    <i class="fa fa-sliders" aria-hidden="true"></i>
                                    <span class="nav-link-text">Recruitment</span>
                                </a>
                            </li>
                        <?php } ?>

                        <?php if (isset($module[77])) { ?>
                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Requisition">
                                <a class="nav-link li-nav-style" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/auditors/resignation.php'; ?>'>
                                    <i class="fa fa-sliders" aria-hidden="true"></i>
                                    <span class="nav-link-text">Resignation</span>
                                </a>
                            </li>
                        <?php } ?>

                        <?php if (isset($module[78])) { ?>
                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Requisition">
                                <a class="nav-link li-nav-style" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/auditors/employeeDrive.php'; ?>'>
                                    <i class="fa fa-sliders" aria-hidden="true"></i>
                                    <span class="nav-link-text">Employee Drive</span>
                                </a>
                            </li>
                        <?php } ?>

                        <?php if (isset($module[79])) { ?>
                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Requisition">
                                <a class="nav-link li-nav-style" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/auditors/trainingDevelopment.php'; ?>'>
                                    <i class="fa fa-sliders" aria-hidden="true"></i>
                                    <span class="nav-link-text">Training and Development</span>
                                </a>
                            </li>
                        <?php } ?>

                        <?php if (isset($module[80])) { ?>
                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Requisition">
                                <a class="nav-link li-nav-style" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/auditors/performanceManagement.php'; ?>'>
                                    <i class="fa fa-sliders" aria-hidden="true"></i>
                                    <span class="nav-link-text">Performance Management</span>
                                </a>
                            </li>
                        <?php } ?>


                        <?php if (isset($module[73])) { ?>
                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Terminate Process">

                                <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#terminationProcess" data-parent="#exampleAccordion">

                                    <i class="fa fa-trash-o" aria-hidden="true"></i>

                                    <span class="nav-link-text">Termination Process</span>

                                </a>

                                <ul class="sidenav-second-level collapse" id="terminationProcess">
                                    <li>
                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/auditors/terminationProcess.php'; ?>'>
                                            Termination Unpaid</a>
                                    </li>
                                    <li>
                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/auditors/terminationMemberProcess.php'; ?>'>
                                            Termination Member</a>
                                    </li>
                                </ul>

                            </li>

                        <?php }  ?>

                        <?php if (isset($module[66])) { ?>
                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="IIAM Articles">

                                <a class="nav-link li-nav-style" href="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot']; ?>/organization/auditors/articles.php">
                                    <i class="fa fa-newspaper-o" aria-hidden="true"></i>
                                    <span class="nav-link-text">IIAM Articles</span>
                                </a>
                            </li>



                        <?php } ?> <?php if (isset($module[74])) { ?>
                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Post Member">

                                <a class="nav-link li-nav-style" href="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot']; ?>/organization/auditors/postMember.php">
                                    <i class="fa fa-address-card-o" aria-hidden="true"></i>
                                    <span class="nav-link-text">Post Member</span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if (isset($module[81])) { ?>
                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Requisition">
                                <a class="nav-link li-nav-style" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/auditors/api.php'; ?>'>
                                    <i class="fa fa-sliders" aria-hidden="true"></i>
                                    <span class="nav-link-text">API</span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if (isset($module[23])) { ?>

                            <!-- PROJECT -->

                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Project">

                                <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#collapseProject" data-parent="#exampleAccordion">

                                    <i class="fa fa-user"></i>

                                    <span class="nav-link-text">Project</span>

                                </a>

                                <ul class="sidenav-second-level collapse" id="collapseProject">

                                    <li>

                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/project/createProject.php'; ?>'>Create
                                            Project</a>

                                    </li>

                                    <li>

                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/project/viewProject.php'; ?>'>View
                                            Project</a>

                                    </li>

                                    <li>

                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/project/reportProject.php'; ?>'>Gantt
                                            Chart</a>

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

                                            <ul style="background-color:white" class="sidenav-third-level collapse" id="quotation">

                                                <li>

                                                    <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/quotation/createQuotation.php'; ?>'>Create
                                                        Quotation</a>

                                                </li>

                                                <li>

                                                    <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/quotation/viewQuotation.php'; ?>'>View
                                                        Quotation</a>

                                                </li>

                                            </ul>

                                        </li>

                                    <?php } ?>

                                    <?php if (isset($module[17])) { ?>
                                        <!-- My Invoice Start -->
                                        <li>

                                            <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#myinvoice">My Invoice</a>

                                            <ul class="sidenav-third-level collapse" id="myinvoice">

                                                <li>

                                                    <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/InvoiceC1/createInvoiceC1.php'; ?>'>Create My Invoice</a>

                                                </li>

                                                <li>

                                                    <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/InvoiceC1/viewInvoiceC1.php'; ?>'>View My
                                                        Invoice</a>

                                                </li>
                                                <?php /*
                                        <li>

                                            <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/invoice/report/report.php'; ?>'>Invoice
                                                Report</a>

                                        </li>
 */ ?>

                                            </ul>

                                        </li>
                                        <!-- statement invoice -->
                                        <li>
                                            <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#myinvoicecustom">Invoice Custom Statement</a>
                                            <ul class="sidenav-third-level collapse" id="myinvoicecustom">
                                                <li>
                                                    <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/invoiceST/createInvoiceSt.php'; ?>'>Create Statement Invoice</a>
                                                </li>
                                                <li>
                                                    <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/invoiceST/viewInvoiceSt.php'; ?>'>View Statement
                                                        Invoice</a>
                                                </li>
                                                <?php /*
                                        <li>
                                            <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/invoice/report/report.php'; ?>'>Invoice
                                                Report</a>
                                        </li>
                                        */ ?>
                                            </ul>
                                        </li>
                                        <!-- statement invoice -->
                                        <!-- My Invoice End -->
                                        <li>

                                            <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#invoice">Invoice</a>

                                            <ul class="sidenav-third-level collapse" id="invoice">

                                                <li>

                                                    <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/invoice/createInvoice.php'; ?>'>Create
                                                        Invoice</a>

                                                </li>

                                                <li>

                                                    <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/invoice/viewInvoice.php'; ?>'>View
                                                        Invoice</a>

                                                </li>
                                                <li>

                                                    <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/invoice/report/report.php'; ?>'>Invoice
                                                        Report</a>

                                                </li>

                                            </ul>

                                        </li>

                                        <?php if (isset($module[14])) { ?>

                                            <!-- SETTING -->

                                            <li>

                                                <a class="nav-link li-nav-style" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/setting/qiSetting.php'; ?>'>Setting</a>

                                            </li>

                                        <?php } ?>

                                    <?php } ?>

                                    <?php if (isset($module[35])) { ?>

                                        <li>

                                            <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#services">Services</a>

                                            <ul class="sidenav-third-level collapse" id="services">

                                                <li>

                                                    <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/services/addServices.php'; ?>'>Add
                                                        Services</a>

                                                </li>

                                                <li>

                                                    <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/services/servicesDescription.php'; ?>'>Services
                                                        Description</a>

                                                </li>

                                            </ul>

                                        </li>

                                    <?php } ?>

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
                                    <!--
            <li>

              <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/recurringInvoice/index.php'; ?>'>Recuring Inv. Create</a>

            </li>-->
                                    <li>

                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/recurringInvoice/createRecurringInvoice.php'; ?>'>Recuring
                                            Inv. Create</a>

                                    </li>

                                    <li>

                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/recurringInvoice/scheduler-list.php'; ?>'>Recuring
                                            Inv. List</a>

                                    </li>

                                </ul>

                            </li>

                        <?php } ?>

                        <?php if (isset($module[13])) { ?>

                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Summary Report">

                                <a class="nav-link li-nav-style" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/report/quotation_invoice.php'; ?>'>

                                    <i class="fa fa-table"></i>

                                    <span class="nav-link-text">Summary</span>

                                </a>

                            </li>

                        <?php } ?>

                        <?php if (isset($module[24])) { ?>

                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Profit & Loss Report">

                                <a class="nav-link li-nav-style" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/profitLoss/profitLoss.php'; ?>'>

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

                                    <?php if ($_SESSION["role"] == 1 || $_SESSION["role"] == 42 || $_SESSION['ManagerRole']) { ?>

                                        <li>

                                            <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/payroll/payroll.php'; ?>'>Payroll</a>

                                        </li>


                                        <li>

                                            <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/payroll/viewPayslip.php'; ?>'>View
                                                Payslips</a>

                                        </li>

                                        <li>

                                            <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/payroll/payrollreport.php'; ?>'>Payroll
                                                Report</a>

                                        </li>

                                        <li>

                                            <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/payroll/payrollSetting.php'; ?>'>Setting</a>

                                        </li>

                                    <?php } else { ?>

                                        <li>

                                            <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/payroll/viewPayslip.php'; ?>'>View
                                                Payslips</a>

                                        </li>

                                    <?php } ?>
                                    <?php if (isset($module[45])) { ?>
                                        <li>

                                            <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#eaform">EA</a>

                                            <ul style="background-color:white" class="sidenav-third-level collapse" id="eaform">

                                                <li>

                                                    <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/payroll/eaform.php'; ?>'>EA
                                                        Form</a>

                                                </li>

                                                <li>

                                                    <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/payroll/vieweaform.php'; ?>'>View
                                                        EA Form</a>

                                                </li>
                                                <li>

                                                    <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/payroll/eareport.php'; ?>'>EA
                                                        Report</a>

                                                </li>

                                            </ul>

                                        </li>

                                        <li>

                                            <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#eaOfficer">EA
                                                Officer Setting</a>

                                            <ul style="background-color:white" class="sidenav-third-level collapse" id="eaOfficer">

                                                <li>

                                                    <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/payroll/addOfficerDetails.php'; ?>'>Add
                                                        Officer Details </a>

                                                </li>

                                                <li>

                                                    <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/payroll/viewOfficerDetails.php'; ?>'>View
                                                        Officer Details</a>

                                                </li>

                                            </ul>

                                        </li> <?php } ?>

                                </ul>

                            </li>

                        <?php } ?>

                        <!-- Purchase Order -->

                        <?php if (isset($module[27])) { ?>

                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Purchase Order">

                                <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#collapsePurchaseOrder" data-parent="#exampleAccordion">

                                    <i class="fa fa-clipboard"></i>

                                    <span class="nav-link-text">Purchase Order</span>

                                </a>

                                <ul class="sidenav-second-level collapse" id="collapsePurchaseOrder">

                                    <li>

                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/purchaseOrder/manageSuppliers.php'; ?>'>Manage
                                            Suppliers</a>

                                    </li>

                                    <li>

                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/purchaseOrder/createPO.php'; ?>'>Create
                                            PO</a>

                                    </li>

                                    <li>

                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/purchaseOrder/viewPO.php'; ?>'>View
                                            PO</a>

                                    </li>

                                </ul>

                            </li>

                        <?php

                        } ?>

                        <?php
                        // vehicle reports
                        if (isset($module[57])) { ?>

                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Bill Payment">
                                <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#collapsereports" data-parent="#exampleAccordion">
                                    <!-- <a class="nav-link nav-link-collapse collapsed li-nav-style"
                           href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/bill/bill.php'; ?>'> -->

                                    <i class="fa fa-list-alt"></i>

                                    <span class="nav-link-text">Reports</span>

                                </a>
                                <ul class="sidenav-second-level collapse" id="collapsereports">

                                    <li>

                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/reports/maintenanceReport.php'; ?>'>Maintenance Expense</a>

                                    </li>
                                    <li>
                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/reports/tripReport.php'; ?>'>Trip Expense</a>
                                    </li>
                                    <li>
                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/reports/vehicleReport.php'; ?>'>Vehicle Expense
                                        </a>
                                    </li>
                                </ul>
                            </li>

                        <?php } ?>
                        <!-- Bill Payment -->

                        <?php if (isset($module[28])) { ?>

                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Bill Payment">
                                <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#collapsebillextra" data-parent="#exampleAccordion">
                                    <!-- <a class="nav-link nav-link-collapse collapsed li-nav-style"
                           href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/bill/bill.php'; ?>'> -->

                                    <i class="fa fa-list-alt"></i>

                                    <span class="nav-link-text">Expenses</span>

                                </a>
                                <ul class="sidenav-second-level collapse" id="collapsebillextra">

                                    <li>

                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/bill/bill.php'; ?>'>Add
                                            Expense</a>

                                    </li>
                                    <li>

                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/bill/viewBill.php'; ?>'>View
                                            Expense</a>

                                    </li>
                                    <li>

                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/bill/purchase.php'; ?>'>View
                                            Purchase</a>

                                    </li>
                                    <li>
                                        <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#mtype">Category</a>

                                        <ul style="background-color:white" class="sidenav-third-level collapse" id="mtype">

                                            <li>

                                                <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/bill/option/addCategory.php'; ?>'>Add Category</a>

                                            </li>

                                            <li>

                                                <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/bill/option/viewCategory.php'; ?>'>View Category</a>

                                            </li>


                                        </ul>

                                    </li>
                                    <li>
                                        <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#expensefor">Expense For</a>

                                        <ul style="background-color:white" class="sidenav-third-level collapse" id="expensefor">

                                            <li>

                                                <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/bill/option/addExpensFor.php'; ?>'>Add Expense For</a>

                                            </li>

                                            <li>

                                                <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/bill/option/viewExpensFor.php'; ?>'>View Expense For</a>

                                            </li>


                                        </ul>

                                    </li>

                                </ul>
                            </li>

                        <?php } ?>

                        <!-- Claims -->

                        <?php if (isset($module[19])) { ?>


                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Claim">

                                <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#collapseClaim" data-parent="#exampleAccordion">

                                    <i class="fa fa-clipboard"></i>

                                    <span class="nav-link-text">Claims</span>

                                </a>

                                <ul class="sidenav-second-level collapse" id="collapseClaim">

                                    <li>

                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/claim/claimForm.php'; ?>'>Claim
                                            Form</a>

                                    </li>

                                    <li>

                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/claim/viewClaim.php'; ?>'>View
                                            Claim</a>

                                    </li>

                                    <li>

                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/claim/claimReport.php'; ?>'>Report</a>

                                    </li>
                                    <li>
                                        <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#claimType">Claim
                                            Type</a>

                                        <ul style="background-color:white" class="sidenav-third-level collapse" id="claimType">

                                            <li>

                                                <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/claim/addType.php'; ?>'>Add
                                                    Claim Type</a>

                                            </li>

                                            <li>

                                                <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/claim/viewType.php'; ?>'>View
                                                    Claim Type</a>

                                            </li>


                                        </ul>

                                    </li>

                                </ul>

                            </li>

                        <?php } ?>

                        <?php if (isset($module[22])) { ?>

                            <!-- ATTENDANCE -->

                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Attendance">

                                <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#collapseAttendance" data-parent="#exampleAccordion">

                                    <i class="fa fa-user"></i>

                                    <span class="nav-link-text">Attendance</span>

                                </a>

                                <ul class="sidenav-second-level collapse" id="collapseAttendance">

                                    <li>

                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/attendance/att.php'; ?>'>Clock
                                            In/Out</a>

                                    </li>

                                    <li>

                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/attendance/viewAttendance.php'; ?>'>View
                                            Attendance</a>

                                    </li>
                                    <?php if (isset($module[41])) { ?>
                                        <li>

                                            <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/attendance/attendancereport.php'; ?>'>Attendance
                                                Report</a>

                                        </li>

                                    <?php } ?>
                                    <?php if (isset($module[46])) { ?>
                                        <li>

                                            <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/attendance/setting.php'; ?>'>Late
                                                Attendace Setting</a>

                                        </li>
                                    <?php } ?>
                                </ul>

                            </li>

                        <?php } ?>

                        <?php if (isset($module[29])) { ?>

                            <!-- RECEIPT -->

                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Receipt">

                                <a class="nav-link li-nav-style" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/receipt/receipt.php'; ?>'>

                                    <i class="fa fa-users"></i>

                                    <span class="nav-link-text">Receipt</span>

                                </a>

                            </li>

                        <?php } ?>

                        <?php if (isset($module[34])) { ?>

                            <!-- Sharing -->

                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Sharing">

                                <a class="nav-link li-nav-style" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/share/share.php'; ?>'>

                                    <i class="fa fa-list-alt"></i>

                                    <span class="nav-link-text">Sharing</span>

                                </a>

                            </li>

                        <?php } ?>

                        <?php if (isset($module[4])) { ?>

                            <!-- CLIENT APPOINTMENT -->

                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="calendar">

                                <a class="nav-link li-nav-style" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/calendar/calendar.php'; ?>'>

                                    <i class="fa fa-table"></i>

                                    <span class="nav-link-text">Client Appointment</span>

                                </a>

                            </li>

                        <?php } ?>

                        <?php if (isset($module[2])) { ?>

                            <!-- MY ORGANIZATION -->

                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="My Organization">

                                <a class="nav-link li-nav-style" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/myOrganization/updateMyOrg.php'; ?>'>

                                    <i class="fa fa-briefcase"></i>

                                    <span class="nav-link-text">My Organization</span>

                                </a>

                            </li>

                        <?php } ?>

                        <?php if (isset($module[3])) { ?>

                            <!-- APPOINTMENT -->

                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Schedule">

                                <a class="nav-link li-nav-style" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/schedule/schedule.php'; ?>'>

                                    <i class="fa fa-calendar"></i>

                                    <span class="nav-link-text">Appointment</span>

                                </a>

                            </li>

                        <?php } ?>
                        <?php if (isset($module[56])) { ?>

                            <!-- PRODUCT -->

                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Trip">

                                <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#collapseTrip" data-parent="#exampleAccordion">

                                    <i class="fa fa-archive"></i>

                                    <span class="nav-link-text">Trip</span>

                                </a>

                                <ul class="sidenav-second-level collapse" id="collapseTrip">

                                    <li>

                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/trip/trip.php'; ?>'>Add
                                            Trip</a>

                                    </li>

                                    <li><a class="nav-link-collapse collapsed" data-toggle="collapse" href="#collapseTripd">Trip
                                            Details</a>

                                        <ul style="background-color:white" class="sidenav-third-level collapse" id="collapseTripd">

                                            <li>

                                                <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/trip/viewTrip.php'; ?>'>View
                                                    Trip</a>

                                            </li>
                                            <li>

                                                <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/invoiceTrip/createTripInvoice.php'; ?>'>Create
                                                    Trip Invoice</a>

                                            </li>
                                            <li>

                                                <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/invoiceTrip/viewTripInvoice.php'; ?>'>View
                                                    Trip Invoice</a>

                                            </li>

                                        </ul>

                                    </li>

                                </ul>

                            </li>
                        <?php } ?>

                        <?php if (isset($module[5])) { ?>

                            <!-- PRODUCT -->

                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Product">

                                <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#collapseProduct" data-parent="#exampleAccordion">

                                    <i class="fa fa-archive"></i>

                                    <span class="nav-link-text">Product</span>

                                </a>

                                <ul class="sidenav-second-level collapse" id="collapseProduct">

                                    <li>

                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/product/product.php'; ?>'>Add
                                            Product</a>

                                    </li>

                                    <li><a class="nav-link-collapse collapsed" data-toggle="collapse" href="#productd">Product
                                            Details</a>

                                        <ul style="background-color:white" class="sidenav-third-level collapse" id="productd">

                                            <li>

                                                <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/product/viewProduct.php'; ?>'>View
                                                    Product</a>

                                            </li>
                                            <?php if (isset($_SESSION['orgType']) && $_SESSION['orgType'] == 7) { ?>
                                                <li>

                                                    <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/product/report/report.php'; ?>'>Report</a>

                                                </li>
                                                <li>

                                                    <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/product/sentoutlist.php'; ?>'>Sent
                                                        Out List</a>

                                                </li>
                                            <?php } ?>
                                        </ul>

                                    </li>


                                    <?php if (isset($_SESSION['orgType']) && $_SESSION['orgType'] == 7) { ?>
                                        <li>

                                            <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/product/sentProduct.php'; ?>'>Delivery
                                                Order</a>

                                        </li>

                                        <li>

                                            <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/product/genbarcode.php'; ?>'>Barcode
                                                Generator</a>

                                        </li>
                                    <?php } ?>
                                    <?php if ($_SESSION['orgType'] != 3) { ?>
                                        <li>
                                            <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#brand">Brand</a>

                                            <ul style="background-color:white" class="sidenav-third-level collapse" id="brand">

                                                <li>

                                                    <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/product/option/addBrand.php'; ?>'>Add
                                                        Brand</a>

                                                </li>

                                                <li>

                                                    <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/product/option/viewBrand.php'; ?>'>View
                                                        Brand</a>

                                                </li>


                                            </ul>

                                        </li> <?php } ?>
                                    <li>
                                        <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#type"><?php if ($_SESSION['orgType'] == 8 || $_SESSION['orgType'] == 3) {
                                                                                                                        echo "Category";
                                                                                                                    } else { ?> Product Type<?php } ?></a>

                                        <ul style="background-color:white" class="sidenav-third-level collapse" id="type">

                                            <li>

                                                <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/product/option/addType.php'; ?>'>Add <?php if ($_SESSION['orgType'] == 8 || $_SESSION['orgType'] == 3) {
                                                                                                                                                                                        echo "Category";
                                                                                                                                                                                    } else { ?>Type<?php } ?></a>

                                            </li>

                                            <li>

                                                <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/product/option/viewType.php'; ?>'>View <?php if ($_SESSION['orgType'] == 8 || $_SESSION['orgType'] == 3) {
                                                                                                                                                                                        echo "Category";
                                                                                                                                                                                    } else { ?>Type<?php } ?></a>

                                            </li>


                                        </ul>

                                    </li>

                                </ul>

                            </li>
                        <?php } ?>
                        <?php if (isset($module[54])) { ?>

                            <!-- PRODUCT -->

                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Vehicle">

                                <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#collapseVehicle" data-parent="#exampleAccordion">

                                    <i class="fa fa-archive"></i>

                                    <span class="nav-link-text">Vehicles</span>

                                </a>

                                <ul class="sidenav-second-level collapse" id="collapseVehicle">

                                    <li>

                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/vehicle/vehicle.php'; ?>'>Add
                                            Vehicle</a>

                                    </li>

                                    <li><a class="nav-link-collapse collapsed" data-toggle="collapse" href="#collapseVehicled">Vehicle
                                            Details</a>

                                        <ul style="background-color:white" class="sidenav-third-level collapse" id="collapseVehicled">

                                            <li>

                                                <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/vehicle/viewVehicle.php'; ?>'>View
                                                    Vehicles</a>

                                            </li>

                                        </ul>

                                    </li>
                                    <li>
                                        <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#vtype">Vehicle Type</a>

                                        <ul style="background-color:white" class="sidenav-third-level collapse" id="vtype">

                                            <li>

                                                <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/vehicle/option/addType.php'; ?>'>Add
                                                    Type</a>

                                            </li>

                                            <li>

                                                <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/vehicle/option/viewType.php'; ?>'>View
                                                    Type</a>

                                            </li>


                                        </ul>

                                    </li>
                                    <li>
                                        <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#vbrand">Vehicle Brand</a>

                                        <ul style="background-color:white" class="sidenav-third-level collapse" id="vbrand">

                                            <li>

                                                <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/vehicle/option/addBrand.php'; ?>'>Add
                                                    Brand</a>

                                            </li>

                                            <li>

                                                <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/vehicle/option/viewBrand.php'; ?>'>View
                                                    Brand</a>

                                            </li>


                                        </ul>

                                    </li>
                                    <li>
                                        <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#vcategory">Vehicle Category</a>

                                        <ul style="background-color:white" class="sidenav-third-level collapse" id="vcategory">

                                            <li>

                                                <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/vehicle/option/addCategory.php'; ?>'>Add
                                                    Category</a>

                                            </li>

                                            <li>

                                                <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/vehicle/option/viewCategory.php'; ?>'>View
                                                    Category</a>

                                            </li>


                                        </ul>

                                    </li>

                                </ul>

                            </li>
                            <?php } ?><?php if (isset($module[55])) { ?>

                            <!-- PRODUCT -->

                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Maintenance">

                                <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#collapseMaintenance" data-parent="#exampleAccordion">

                                    <i class="fa fa-archive"></i>

                                    <span class="nav-link-text">Maintenance</span>

                                </a>

                                <ul class="sidenav-second-level collapse" id="collapseMaintenance">

                                    <li>

                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/vehicle/maintenance.php'; ?>'>Add
                                            Maintenance</a>

                                    </li>

                                    <li><a class="nav-link-collapse collapsed" data-toggle="collapse" href="#collapseMaintenanced">Maintenance
                                            Details</a>

                                        <ul style="background-color:white" class="sidenav-third-level collapse" id="collapseMaintenanced">

                                            <li>

                                                <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/vehicle/viewMaintenance.php'; ?>'>View
                                                    Maintenance</a>

                                            </li>
                                            <li>

                                                <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/vehicle/report.php'; ?>'>Maintenance Report</a>

                                            </li>

                                        </ul>

                                    </li>
                                    <li>
                                        <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#mfor">Maintenance For</a>

                                        <ul style="background-color:white" class="sidenav-third-level collapse" id="mfor">

                                            <li>

                                                <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/vehicle/option/addMaintenanceFor.php'; ?>'>Add Maintenance For</a>

                                            </li>

                                            <li>

                                                <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/vehicle/option/viewMaintenanceFor.php'; ?>'>View Maintenance For</a>

                                            </li>


                                        </ul>

                                    </li>

                                </ul>

                            </li>
                        <?php } ?>

                        <?php

                        if (isset($module[6])) {
                            $newLable = "";
                            if (isset($_SESSION['clientas'])) {
                                $newLable = $_SESSION['clientas'];
                            }
                            if ($_SESSION['orgType'] == 3) {
                        ?>
                                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="My Organization">

                                    <a class="nav-link li-nav-style" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/client/fileManager.php'; ?>'>

                                        <i class="fa fa-folder-open"></i>

                                        <span class="nav-link-text">File Manager</span>

                                    </a>

                                </li>
                            <?php } ?>
                            <!-- CLIENT -->

                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Client">

                                <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#collapseClient" data-parent="#exampleAccordion">
                                    <i class="fa fa-user"></i>
                                    <span class="nav-link-text"><?php echo $newLable; ?> </span><?php if ($_SESSION['memberReg']) { ?> <span style="background-color:#ff5722;color:black !important;" class="badge badge-primary newrequest"></span> <?php } ?>
                                </a>

                                <ul class="sidenav-second-level collapse" id="collapseClient">

                                    <li>

                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/client/addClient.php'; ?>'>Add <?php echo $newLable; ?></a>

                                    </li>

                                    <li>

                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/client/viewClient.php'; ?>'>View <?php echo $newLable; ?><span style="background-color:#e91e63;color:black !important;" class="badge badge-primary memberbox"></span></a>

                                    </li>
                                    <?php if ($_SESSION['memberReg']) { ?>
                                        <li>

                                            <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/client/newClientRequest.php'; ?>'>New Request <?php echo $newLable; ?>
                                                <span style="background-color:#8bc34a;color:black !important;" class="badge badge-primary newrequest"><?php echo $_SESSION['newrequestNor'] ?></span></a>

                                        </li>
                                        <li>

                                            <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/client/potentialClients.php'; ?>'>Potential <?php echo $newLable; ?>
                                                <span style="background-color:#ffc107;color:black !important;" class="badge badge-primary potential"><?php echo $_SESSION['potentialNor'] ?></span></a>

                                        </li>
                                    <?php } ?>
                                    <?php if ($_SESSION['memberReg']) { ?>
                                        <li>

                                            <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/membership-subscription/subscriber.php'; ?>'>New Subscription</a>

                                        </li>
                                        <li>

                                            <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/membership-subscription/activatedSubscriber.php'; ?>'>Approved Subscription</a>

                                        </li>
                                        <li>

                                            <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/membership-subscription/membershipPlan.php'; ?>'>Membership Plan</a>

                                        </li>
                                        <li>

                                            <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/membership-subscription/addMembership.php'; ?>'>Add Membership Plan</a>

                                        </li>
                                    <?php } ?>
                                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="PDF Sign">

                                        <a class="nav-link-collapse" data-toggle="collapse" href="#signatory" aria-expanded="true">

                                            <span>Signatory</span>

                                        </a>

                                        <ul class="sidenav-third-level collapse" id="signatory">

                                            <li>

                                                <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/client/addSignatory.php'; ?>'>Add Signatory</a>

                                            </li>
                                            <li>

                                                <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/client/viewSignatory.php'; ?>'>View Signatory</a>

                                            </li>


                                        </ul>

                                    </li>

                                </ul>

                            </li>
                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Client">

                                <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#collapseTerminate" data-parent="#exampleAccordion">

                                    <i class="fa fa-user"></i>

                                    <span class="nav-link-text">Terminate Request</span>

                                </a>

                                <ul class="sidenav-second-level collapse" id="collapseTerminate">

                                    <li>

                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/membership-subscription/terminateRequest.php'; ?>'>View Request</a>

                                    </li>

                                    <li>

                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/membership-subscription/terminateAccount.php'; ?>'>Terminate Account</a>

                                    </li>

                                </ul>

                            </li>

                        <?php } ?>
                        <?php if ($_SESSION['orgType'] == 3) {  ?>
                            <?php if (isset($module[60])) { ?>
                                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="PDF Sign">

                                    <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#signPDF" data-parent="#exampleAccordion">

                                        <i class="fa fa fa-file-pdf-o"></i>

                                        <span class="nav-link-text">PDF Manager</span>

                                    </a>

                                    <ul class="sidenav-second-level collapse" id="signPDF">
                                        <?php /*
                                        <li>

                                            <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/pdfSign/uploadPdf.php'; ?>'>Upload PDF</a>

                                        </li>*/ ?>
                                        <li>

                                            <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/pdfSign/list.php'; ?>'>List PDF</a>

                                        </li>
                                        <?php /*
                                        <li>

                                            <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/pdfSign/pdfList.php'; ?>'>List PDF</a>

                                        </li> */ ?>


                                    </ul>

                                </li> <?php  } ?>
                        <?php } ?>
                        <?php if ($_SESSION['orgType'] == 8 || $_SESSION['orgType'] == 3) { ?>
                            <?php if (isset($module[61])) { ?>
                                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Store">

                                    <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#collapseStore" data-parent="#exampleAccordion">

                                        <i class="fa fa-archive"></i>

                                        <span class="nav-link-text">Online Orders</span><span style="background-color:#4caf50;color:black !important;" class="badge badge-primary orderbox"></span>

                                    </a>

                                    <ul class="sidenav-second-level collapse" id="collapseStore">
                                        <?php if (isset($module[51])) { ?>
                                            <li>

                                                <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/product/store.php'; ?>'>Order
                                                    List</a>

                                            </li>
                                        <?php }
                                        if (isset($module[52])) { ?>
                                            <li>
                                                <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/product/report.php'; ?>'>Order
                                                    Report</a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </li>
                                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Store">

                                    <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#collapseStoreBanner" data-parent="#exampleAccordion">
                                        <i class="fa fa-archive"></i>
                                        <span class="nav-link-text">Store Banner</span>
                                    </a>

                                    <ul class="sidenav-second-level collapse" id="collapseStoreBanner">
                                        <li>

                                            <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/store/addBanner.php'; ?>'>Add Banner</a>

                                        </li>
                                        <li>
                                            <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/store/viewBanner.php'; ?>'>View Banner</a>
                                        </li>
                                    </ul>
                                </li>
                            <?php } ?>
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

                                            <a class="nav-link-collapse" data-toggle="collapse" href="#collapseComplaint" aria-expanded="true">

                                                <i class="fa fa-archive"></i>

                                                <span class="nav-link-text ">Incidents
                                                    <?php if ($newComplaint > 0) { ?>
                                                        <span style="background-color:white;color:black;" class="badge badge-primary complaint-notification"><?php echo $newComplaint ?></span>
                                                    <?php } ?>
                                                </span>
                                            </a>

                                            <ul class="sidenav-third-level collapse" id="collapseComplaint">
                                                <?php if (isset($module[30])) { ?>
                                                    <li>

                                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/complaint/makeComplaint.php'; ?>'>Create
                                                            Task</a>

                                                    </li>
                                                <?php } ?>

                                                <?php if (isset($module[37])) { ?>
                                                    <li>

                                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/complaint/uncompleted.php'; ?>'>Incomplete

                                                            <?php if ($newComplaint > 0) { ?>

                                                                <span class="badge badge-primary complaint-notification" style="background-color:white;color:black;"><?php echo $newComplaint ?></span>

                                                            <?php } ?>

                                                        </a>

                                                    </li>
                                                <?php } ?>
                                                <?php if (isset($module[36])) { ?>
                                                    <?php if ($_SESSION['role'] != 1 && $_SESSION['internalUse'] == 1 && $_SESSION['role'] != 42 && $_SESSION['internalUse'] == 1) { ?>
                                                        <!-- assign task start-->


                                                        <li>
                                                            <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/staff/myTask/viewTask.php'; ?>'>My
                                                                Task

                                                                <?php /*if ($newComplaint>0) { ?>

				          <span class="badge badge-primary complaint-notification" style="background-color:white;color:black;"><?php echo $newComplaint ?></span>

			          <?php } */ ?> </a>

                                                        </li>
                                                        <!-- assign task end -->
                                                <?php }
                                                } ?>
                                                <?php if (isset($module[38])) { ?>
                                                    <li>

                                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/complaint/completed.php'; ?>'>Completed</a>

                                                    </li>
                                                <?php } ?>
                                                <?php if (isset($module[20])) : ?>

                                                    <li>

                                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/complaint/viewSLA.php'; ?>'>Time
                                                            Frame</a>

                                                    </li>

                                                <?php endif; ?>

                                            </ul>

                                        </li>

                                    <?php } ?>

                                    <?php if (isset($module[11])) { ?>

                                        <!-- REPORT -->

                                        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Report">

                                            <a class="nav-link li-nav-style" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/report/report.php'; ?>'>

                                                <i class="fa fa-table"></i>

                                                <span class="nav-link-text">View Job Report</span>

                                            </a>

                                        </li>

                                    <?php } ?>

                                </ul>


                            </li>

                        <?php } ?>

                        <?php if (isset($module[7])) { ?>

                            <!-- VENDOR -->

                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Vendor">

                                <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#collapseVendor" data-parent="#exampleAccordion">

                                    <i class="fa fa-user"></i>

                                    <span class="nav-link-text">Vendor</span>

                                </a>

                                <ul class="sidenav-second-level collapse" id="collapseVendor">

                                    <li>

                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/vendor/addVendor.php'; ?>'>Add
                                            Vendor</a>

                                    </li>
                                    <?php /* ?>
            <li>

              <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/vendor/addVendorClient.php'; ?>'>Assign Client</a>

            </li>

            <li>

              <a href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/organization/vendor/addJobList.php'; ?>'>Add Job</a>

            </li>
<?php */ ?>
                                    <li>

                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/vendor/viewVendor.php'; ?>'>View
                                            Vendor</a>

                                    </li>

                                </ul>

                            </li>

                        <?php } ?>
                        <?php if (isset($module[47])) { ?>
                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Leave">

                                <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#collapseLeave" data-parent="#exampleAccordion">

                                    <i class="fa fa-user"></i>

                                    <span class="nav-link-text">Leave</span>

                                </a>

                                <ul class="sidenav-second-level collapse" id="collapseLeave">

                                    <li>

                                        <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/leave/applyLeave.php'; ?>'>Apply
                                            Leave</a>

                                    </li>

                                    <li>

                                        <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#leaveStatus">Leave
                                            Status</a>

                                        <ul style="background-color:white" class="sidenav-third-level collapse" id="leaveStatus">

                                            <li>

                                                <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/leave/allLeaves.php'; ?>'>View
                                                    All Leaves</a>

                                            </li>
                                            <li>

                                                <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/leave/pendingLeaves.php'; ?>'>Pending
                                                    Leaves</a>

                                            </li>
                                            <li>

                                                <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/leave/RejectedLeaves.php'; ?>'>Rejected
                                                    Leaves</a>

                                            </li>
                                            <li>

                                                <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/leave/approvedLeaves.php'; ?>'>Approved
                                                    Leaves</a>

                                            </li>
                                        </ul>
                                    </li>
                                    <li>

                                        <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#leaveCount">Leave
                                            Count</a>

                                        <ul style="background-color:white" class="sidenav-third-level collapse" id="leaveCount">

                                            <li>

                                                <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/leave/pendingCountLeaves.php'; ?>'>View
                                                    Leaves Count</a>

                                            </li>
                                            <?php if (isset($module[44])) { ?>
                                                <li>

                                                    <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/leave/leaveUpdateAll.php'; ?>'>Annual
                                                        Leave Update</a>

                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                </ul>

                            </li>
                        <?php } ?>

                        <?php if (isset($module[9])) { ?>

                            <!-- PRIVILEGE -->

                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Privilege">

                                <a class="nav-link li-nav-style" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/role/role.php'; ?>'>

                                    <i class="fa fa-users"></i>

                                    <span class="nav-link-text">Privilege</span>

                                </a>

                            </li>

                        <?php } ?>
                        <?php if ($_SESSION['role'] == 42) { ?>
                            <!-- App Configuration -->

                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="App Configuration">

                                <a class="nav-link li-nav-style" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/configuration/appConfiguration.php'; ?>'>

                                    <i class="fa fa-users"></i>

                                    <span class="nav-link-text">App Configuration</span>

                                </a>

                            </li>


                        <?php }
                        if (isset($module[15])) { ?>

                            <!-- HELP -->

                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Help">

                                <a class="nav-link li-nav-style" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/help.php'; ?>'>

                                    <i class="fa fa-question-circle"></i>

                                    <span class="nav-link-text">Help</span>

                                </a>

                            </li>

                        <?php } ?>

                        <?php if (isset($module[16])) { ?>

                            <!-- TERMS & PRIVACY -->

                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Terms & Privacy">

                                <a class="nav-link li-nav-style" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/terms.php'; ?>'>

                                    <!--<i class="fa fa-lock" style="color:#e6e6e6; font-size: 1.4em;"></i>--><i class="fa fa-question-circle"></i>

                                    <span class="nav-link-text">Terms & Privacy</span>

                                </a>

                            </li>

                        <?php } ?>
                        <?php if ($_SESSION['role'] == 1 && $_SESSION['staffId'] == 42) { ?>
                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Terms & Privacy">

                                <a class="nav-link li-nav-style" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/mailConfiguration.php'; ?>'>

                                    <!--<i class="fa fa-lock" style="color:#e6e6e6; font-size: 1.4em;"></i>--><i class="fa fa-envelope"></i>

                                    <span class="nav-link-text">Email Setting</span>

                                </a>

                            </li>

                        <?php } ?>

                        <!-- Backup -->
                        <?php if ($_SESSION['orgType'] != 3) { ?>
                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Backup">

                                <a class="nav-link li-nav-style" href='#'>

                                    <i class="fa fa-lock"></i>

                                    <span class="nav-link-text">Backup</span>

                                </a>

                            </li>
                        <?php } ?>

                </ul>


                <ul class="navbar-nav sidenav-toggler">

                    <li class="nav-item">

                        <a class="nav-link text-center" id="sidenavToggler">

                            <i class="fa fa-fw fa-angle-left"></i>

                        </a>

                    </li>

                    </li>

                </ul>


        <?php }
        } ?>

        <?php if (isClient() === true) { ?>

            <ul class="navbar-nav navbar-sidenav sidenav-not-toggled " id="exampleAccordion">


                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Request">

                    <a class="nav-link nav-link-collapse collapsed li-nav-style" data-toggle="collapse" href="#collapseRequest" data-parent="#exampleAccordion">

                        <i class="fa fa-clipboard"></i>

                        <span class="nav-link-text">Request</span>

                    </a>

                    <ul class="sidenav-second-level collapse" id="collapseRequest">

                        <li>

                            <a id="COMPLAINT-MAKECOMPLAINT" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/client/complaint/makeComplaint.php'; ?>'>Make
                                Request / Incident</a>

                        </li>

                        <li>

                            <a id="COMPLAINT-VIEWCOMPLAINT" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/client/complaint/viewComplaint.php'; ?>'>My
                                Requests / Incidents</a>

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

                            <a id="SCHEDULE-MAKEBOOKING" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/schedule/clientview/booking/createbooking.php'; ?>'>Make
                                Booking</a>

                        </li>

                        <li>

                            <a id="SCHEDULE-VIEWBOOKING" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/schedule/clientview/booking/viewbooking.php'; ?>'>My
                                Booking</a>

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

          <a class="dropdown-item " id="COMPLAINT-MAKECOMPLAINT" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/client/complaint/makeComplaint.php'; ?>'>Make Request / Incident</a>

          <div class="dropdown-divider"></div>

              <a class="dropdown-item " id="COMPLAINT-VIEWCOMPLAINT" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/client/complaint/viewComplaint.php'; ?>' >My Requests / Incidents </a>

          </div>



        </li>



        <li class="nav-item dropdown">

          <a class="nav-link dropdown-toggle " href="#" id="SCHEDULE" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

            SCHEDULE

          </a>

          <div class="dropdown-menu " aria-labelledby="navbarDropdown">

          <a class="dropdown-item " id="SCHEDULE-MAKEBOOKING" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/schedule/clientview/booking/createbooking.php'; ?>'>Make Booking</a>

          <div class="dropdown-divider"></div>

              <a class="dropdown-item " id="SCHEDULE-VIEWBOOKING" href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/schedule/clientview/booking/viewbooking.php'; ?>' >My Booking</a>

          </div>



        </li>

	    </ul>-->


        <?php }


        ?>



        <?php if ((isOrganization() === true && isAdmin() === false) || (isVendor() === true) || ($_SESSION['role'] != 1 && $_SESSION['internalUse'] == 1 && $_SESSION['role'] != 42 && $_SESSION['internalUse'] == 1 && isset($module[36]))) {

            $assignedTaskUrl = "";

            $onSiteTaskUrl = "";

            if (isVendor() === TRUE) {

                $assignedTaskUrl = 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/vendor/assignedTask/viewTask.php';

                $onSiteTaskUrl = 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/vendor/updateJob.php';
            } else {

                $assignedTaskUrl = 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/staff/assignedTask/viewTask.php';

                $onSiteTaskUrl = 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/staff/updateJob.php';
                if ($_SESSION['staffRole'] && $_SESSION['internalUse'] == 1) {
                    $assignedTaskUrl = 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/staff/myTask/viewTask.php';
                    $onSiteTaskUrl = 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/staff/myTask/updateJob.php';
                }
            }

        ?>

            <ul class="navbar-nav mr-auto">

                <li class="nav-item dropdown">

                    <a class="nav-link dropdown-toggle " href="#" id="COMPLAINT" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                        TASK

                    </a>

                    <div class="dropdown-menu " aria-labelledby="navbarDropdown">

                        <a class="dropdown-item " id="COMPLAINT-MAKECOMPLAINT" href='<?php echo $assignedTaskUrl; ?>'>MY
                            TASK</a>

                        <!-- <a class="dropdown-item " id="COMPLAINT-MAKECOMPLAINT" href='<?php echo $onSiteTaskUrl; ?>' >ON-SITE TASK</a> -->

                    </div>

                </li>

            </ul>

            </ul>


        <?php } ?>


        <ul class="navbar-nav ml-auto">

            <?php if ((isOrganization() === true && isAdmin() === false) || (isVendor() === true) || ($_SESSION['role'] != 1 && $_SESSION['internalUse'] == 1 && $_SESSION['role'] != 42 && $_SESSION['internalUse'] == 1 && isset($module[36]))) {

                require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/message.php");

                $newTaskNotification = null;

                $pendingTaskUrl = "";

                if (isVendor() === true) {

                    $newTaskNotification = newTaskNotification("vendors", $_SESSION['vendorId']);

                    $pendingTaskUrl = 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/vendor/assignedTask/viewTask.php';
                } else {

                    $newTaskNotification = newTaskNotification("myStaff", $_SESSION['userid']);

                    $pendingTaskUrl = 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/staff/assignedTask/viewTask.php';
                }

                $desktopNotification = "";

                if ($newTaskNotification != null) {

                    $desktopNotification = "<i class='fa fa-fw fa-circle'></i>";

                    $mobileNotification = "<span class='badge badge-pill badge-primary'>" . sizeof($newTaskNotification) . " Unread Task</span>";
                } else {
                }


            ?>

                <li class="nav-item dropdown">

                    <a class="nav-link dropdown-toggle mr-lg-2" id="messagesDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                        <i class="fa fa-fw fa-envelope"></i>

                        <span class="d-lg-none message-notification-mobile">Messages

                            <?php

                            if ($newTaskNotification != null) {

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

                            if ($newTaskNotification != null) {

                                $notification = "";

                                $i = 0;

                                foreach ($newTaskNotification as $message) {

                                    $i++;

                                    if ($i > 4) {

                                        break;
                                    }

                                    $notification .= "<div class='dropdown-divider'></div>"; //separator

                                    $notification .= "<a class='dropdown-item' href='#'>"; //link to view message content

                                    if (isset($message['Sender'])) {
                                        $notification .= " <strong>" . $message['Sender'] . "</strong>"; //sender of message
                                    }

                                    $notification .= "<div class='dropdown-message small'>" . $message['Subject'] . "</div>"; //header of the message

                                    $notification .= "<div class='small text-muted' style='text-align:right'>" . $message['Datetime'] . "</div>"; //datetime of message

                                    $notification .= "</a>";
                                }

                                echo $notification;
                            }
                            if (isset($module[36])) {
                                if ($_SESSION['staffRole'] && $_SESSION['internalUse'] == 1) {
                                    $pendingTaskUrl = 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/organization/staff/myTask/viewTask.php';
                                }
                            }
                            ?>

                        </span>

                        <div class="dropdown-divider"></div>

                        <a class="dropdown-item small" href='<?php echo $pendingTaskUrl ?>'>View all Incomplete task</a>

                    </div>

                </li>


            <?php } ?>



            <?php

            require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/clientComplaint.php");

            $newComplaint = noOfNewComplaint();

            if (isOrganization() === true && isAdmin() === true) {


            ?>

                <li class="nav-item">

                    <form class="form-inline my-2 my-lg-0 mr-lg-2">

                        <div class="input-group">

                            <!-- <input class="form-control" type="text" placeholder="Search for..." style="background-color:white;height:30px;

              padding: .375rem .75rem;font-size: 1rem;line-height: 1.0;border: 1px solid #ced4da;border-radius: .25rem;">

              <span class="input-group-append">

                 <a href='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/home.php'; ?>'>

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
                function themePanel(id) {

                    if (id == 0) {

                        document.getElementById("themeClose").style.display = "none";

                        document.getElementById("themeOpen").style.display = "block";

                        document.getElementById("themeColors").style.display = "block";

                    } else if (id == 1) {

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

                <a href="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/phpfunctions/theme.php?changeTheme=default.css'; ?>"><i class="fa fa-square themes" style="color:#EF0B06;"></i></a>

                <a href="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/phpfunctions/theme.php?changeTheme=orange.css'; ?>"><i class="fa fa-square themes" style="color:#EB6A22;"></i></a>

                <a href="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/phpfunctions/theme.php?changeTheme=turquoise.css'; ?>"><i class="fa fa-square themes" style="color:#B0D5D0;"></i></a>

            </li>

            <li>

                <div id="themeClose" onclick="themePanel(0)" class="tooltipTheme"><i class="fa fa-chevron-left themeArrows"></i><span class="tooltiptextTheme">Change Theme</span></div>

                <div id="themeOpen" onclick="themePanel(1)" class="tooltipTheme" style="display:none"><i class="fa fa-chevron-right themeArrows"></i><span class="tooltiptextTheme">Close</span>
                </div>

            </li>


            <li class="nav-item">

                <img src="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/resources/app/avatar-user.png'; ?>" style="width:50px" ; "height:54px" ;><br />

            </li>


            <li class="nav-item">

                <a class="nav-link" href="#" style="color:rgb(182, 44, 14)" , "font-weight=bold">

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

                <a style="color:white" class="nav-link" data-toggle="modal" data-target="#logoutModal">

                    Logout <i class="fa fa-sign-out"></i></a>

            </li>

        </ul>


    </div>

</nav>


<!--

  <script src="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/'; ?>adminTheme/jquery/jquery.min.js"></script>

  <script src="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/'; ?>adminTheme/bootstrap/js/bootstrap.bundle.min.js"></script>

          -->

<!-- Core plugin JavaScript

  <script src='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/'; ?>adminTheme/jquery-easing/jquery.easing.min.js'></script>

  -->

<!-- Custom scripts for all pages

  <script src="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/'; ?>adminTheme/js/sb-admin.min.js"></script>

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

                <form action='<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/phpfunctions/logout.php'; ?>' "POST" method="POST">

                    <button class="btn btn-secondary remove" type="button" data-dismiss="modal">Cancel</button>

                    <button class="btn btn-primary edit" name="logout" href="#">Log Out</button>

                </form>

            </div>

        </div>

    </div>

</div>

<?php

$_SESSION['module'] = $module;

function shortcut()
{

    $modulecheck = $_SESSION['module'];

    $config = parse_ini_file(__DIR__ . "/jsheetconfig.ini");

    require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/clientComplaint.php");

    $newComplaint = noOfNewComplaint();

    //<button type='button' class='navbar-toggle' data-toggle='collapse' data-target='#myNavbar' style='cursor: context-menu; background:#3c8dbc; border: 0px; border-radius: 15px; height: 25px; width: 25px;'><div style='background:white; margin: auto; border-radius: 10px; height: 10px; width: 10px;'>

    $shortcut = "

  <nav class='navbar navbar-inverse'>

<div class='navbar-header'  data-toggle='collapse' data-target='#myNavbar' style='font-size:25px;cursor: pointer;'>



  <i class='fa fa-angle-right' style='margin-right: 10px;margin-left:10px' data-toggle='collapse' data-target='#myNavbar' aria-hidden='true'></i>Quick Menu

</div>

<div class='collapse navbar-collapse' id='myNavbar'>

  <ul class='nav navbar-nav'>

  <div class='col-md-12'><center>";

    if (isset($modulecheck[6])) {


        $shortcut = $shortcut . "<a href = 'https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/client/addClient.php'><div id='div2' class='nav-item col-md-2' style='background:#04B0B6;border:0px;margin-bottom:10px;'>
      <i class='fa fa fa-user' style='color:white; font-size:15px;margin-right:5px '></i><span style='font-size:12px;color:white;'>Add Client <i class='fa fa-plus-square' style='color:white; font-size: 1.0em;'></i></span></div></a>";

        $shortcut = $shortcut . "<a href = 'https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/client/viewClient.php'><div id='div2' class='nav-item col-md-2' style='background:#04B0B6;border:0px;margin-bottom:10px;'>
      <i class='fa fa fa-user' style='color:white; font-size:15px;margin-right:5px '></i><span style='font-size:12px;color:white;'>View Client </span></div></a>";
    }

    if (isset($modulecheck[7])) {

        $shortcut = $shortcut . "<a href = 'https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/vendor/addVendor.php'><div id='div2' class='nav-item col-md-2' style='background:#04B0B6;border:0px;margin-bottom:10px;'>

      <i class='fa fa fa-user' style='color:white; font-size:15px;margin-right:5px '></i><span style='font-size:12px;color:white;'>Add Vendor <i class='fa fa-plus-square' style='color:white; font-size: 1.0em;''></i></span></div></a>";
    }

    if (isset($modulecheck[8])) {

        $shortcut = $shortcut . "<a href = 'https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/staff/addStaff.php'><div id='div3' class='nav-item col-md-2' style='background:#04B0B6;border:0px;margin-bottom:10px;'>
      <i class='fa fa fa-user' style='color:white; font-size:15px;margin-right:5px '></i><span style='font-size:12px;color:white;'>Add Staff <i class='fa fa-plus-square' style='color:white; font-size: 1.0em;'></i></span></div></a>";

        $shortcut = $shortcut . "<a href = 'https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/staff/viewStaff.php'><div id='div3' class='nav-item col-md-2' style='background:#04B0B6;border:0px;margin-bottom:10px;'>
      <i class='fa fa fa-user' style='color:white; font-size:15px;margin-right:5px '></i><span style='font-size:12px;color:white;'>View Staff</span></div></a>";

        $shortcut = $shortcut . "<a href = 'https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/department/addDesignation.php'><div id='div3' class='nav-item col-md-2' style='background:#04B0B6;border:0px;margin-bottom:10px;'>
      <i class='fa fa fa-user' style='color:white; font-size:15px;margin-right:5px '></i><span style='font-size:12px;color:white;'>Add Designation</span></div></a>";

        $shortcut = $shortcut . "<a href = 'https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/department/viewDesignation.php'><div id='div3' class='nav-item col-md-2' style='background:#04B0B6;border:0px;margin-bottom:10px;'>
      <i class='fa fa fa-user' style='color:white; font-size:15px;margin-right:5px '></i><span style='font-size:12px;color:white;'>View Designation</span></div></a>";
    }

    if (isset($modulecheck[10])) {

        $shortcut = $shortcut . "<a href = 'https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/complaint/makeComplaint.php'><div id='div3' class='nav-item col-md-2' style='background:#04B0B6;border:0px;margin-bottom:10px;'>
      <i class='fa fa-comment' style='color:white; font-size:15px;margin-right:5px '></i><span style='font-size:12px;color:white;'>Create Incidents <i class='badge badge-primary' style='color:white;'></i></span></div></a>";

        $shortcut = $shortcut . "<a href = 'https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/complaint/uncompleted.php'><div id='div3' class='nav-item col-md-2' style='background:#04B0B6;border:0px;margin-bottom:10px;'>
      <i class='fa fa-comment' style='color:white; font-size:15px;margin-right:5px '></i><span style='font-size:12px;color:white;'>Uncomplete Incidents <i class='badge badge-primary' style='color:white;'></i>" . $newComplaint . "</span></div></a>";

        $shortcut = $shortcut . "<a href = 'https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/complaint/completed.php'><div id='div3' class='nav-item col-md-2' style='background:#04B0B6;border:0px;margin-bottom:10px;'>
      <i class='fa fa-comment' style='color:white; font-size:15px;margin-right:5px '></i><span style='font-size:12px;color:white;'>Completed Incidents</span></div></a>";
    }
    //Expense
    if ($_SESSION['orgType'] == 8) {
        $shortcut = $shortcut . "<a href = 'https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/bill/bill.php'><div id='div3' class='nav-item col-md-2' style='background:#04B0B6;border:0px;margin-bottom:10px;'>
      <span style='font-size:12px;color:white;'>Add Expense</span></div></a>";

        $shortcut = $shortcut . "<a href = 'https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/bill/viewBill.php'><div id='div3' class='nav-item col-md-2' style='background:#04B0B6;border:0px;margin-bottom:10px;'>
      <span style='font-size:12px;color:white;'>View Expenses</span></div></a>";

        $shortcut = $shortcut . "<a href = 'https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/bill/purchase.php'><div id='div3' class='nav-item col-md-2' style='background:#04B0B6;border:0px;margin-bottom:10px;'>
      <span style='font-size:12px;color:white;'>View Purchases</span></div></a>";

        $shortcut = $shortcut . "<a href = 'https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/bill/option/addCategory.php'><div id='div3' class='nav-item col-md-2' style='background:#04B0B6;border:0px;margin-bottom:10px;'>
      <span style='font-size:12px;color:white;'>Add Expense Type</span></div></a>";

        $shortcut = $shortcut . "<a href = 'https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/bill/option/editCategory.php'><div id='div3' class='nav-item col-md-2' style='background:#04B0B6;border:0px;margin-bottom:10px;'>
      <span style='font-size:12px;color:white;'>Edit Expense Type</span></div></a>";

        $shortcut = $shortcut . "<a href = 'https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/bill/option/addExpensFor.php'><div id='div3' class='nav-item col-md-2' style='background:#04B0B6;border:0px;margin-bottom:10px;'>
      <span style='font-size:12px;color:white;'>Add Expense For</span></div></a>";

        $shortcut = $shortcut . "<a href = 'https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/vehicle/maintenance.php'><div id='div3' class='nav-item col-md-2' style='background:#04B0B6;border:0px;margin-bottom:10px;'>
      <span style='font-size:12px;color:white;'>Add Maintenance</span></div></a>";

        $shortcut = $shortcut . "<a href = 'https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/vehicle/viewMaintenance.php'><div id='div3' class='nav-item col-md-2' style='background:#04B0B6;border:0px;margin-bottom:10px;'>
      <span style='font-size:12px;color:white;'>View Maintenance</span></div></a>";

        $shortcut = $shortcut . "<a href = 'https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/vehicle/option/addMaintenanceFor.php'><div id='div3' class='nav-item col-md-2' style='background:#04B0B6;border:0px;margin-bottom:10px;'>
      <span style='font-size:12px;color:white;'>Add Maintenance For</span></div></a>";

        $shortcut = $shortcut . "<a href = 'https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/vehicle/option/viewMaintenanceFor.php'><div id='div3' class='nav-item col-md-2' style='background:#04B0B6;border:0px;margin-bottom:10px;'>
      <span style='font-size:12px;color:white;'>View Maintenance For</span></div></a>";

        $shortcut = $shortcut . "<a href = 'https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/trip/trip.php'><div id='div3' class='nav-item col-md-2' style='background:#04B0B6;border:0px;margin-bottom:10px;'>
      <span style='font-size:12px;color:white;'>Add Trip</span></div></a>";

        $shortcut = $shortcut . "<a href = 'https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/trip/viewTrip.php'><div id='div3' class='nav-item col-md-2' style='background:#04B0B6;border:0px;margin-bottom:10px;'>
      <span style='font-size:12px;color:white;'>View Trip</span></div></a>";

        $shortcut = $shortcut . "<a href = 'https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/vehicle/vehicle.php'><div id='div3' class='nav-item col-md-2' style='background:#04B0B6;border:0px;margin-bottom:10px;'>
      <span style='font-size:12px;color:white;'>Add Vehicle</span></div></a>";

        $shortcut = $shortcut . "<a href = 'https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/vehicle/viewVehicle.php'><div id='div3' class='nav-item col-md-2' style='background:#04B0B6;border:0px;margin-bottom:10px;'>
      <span style='font-size:12px;color:white;'>View Vehicles</span></div></a>";

        $shortcut = $shortcut . "<a href = 'https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/vehicle/option/addType.php'><div id='div3' class='nav-item col-md-2' style='background:#04B0B6;border:0px;margin-bottom:10px;'>
      <span style='font-size:12px;color:white;'>Add Vehicles Type</span></div></a>";

        $shortcut = $shortcut . "<a href = 'https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/vehicle/option/viewType.php'><div id='div3' class='nav-item col-md-2' style='background:#04B0B6;border:0px;margin-bottom:10px;'>
      <span style='font-size:12px;color:white;'>View Vehicles Type</span></div></a>";

        $shortcut = $shortcut . "<a href = 'https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/vehicle/option/addBrand.php'><div id='div3' class='nav-item col-md-2' style='background:#04B0B6;border:0px;margin-bottom:10px;'>
      <span style='font-size:12px;color:white;'>Add Vehicles Brand</span></div></a>";

        $shortcut = $shortcut . "<a href = 'https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/vehicle/option/viewBrand.php'><div id='div3' class='nav-item col-md-2' style='background:#04B0B6;border:0px;margin-bottom:10px;'>
      <span style='font-size:12px;color:white;'>View Vehicles Brand</span></div></a>";

        $shortcut = $shortcut . "<a href = 'https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/vehicle/option/addCategory.php'><div id='div3' class='nav-item col-md-2' style='background:#04B0B6;border:0px;margin-bottom:10px;'>
      <span style='font-size:12px;color:white;'>Add Vehicles Category</span></div></a>";

        $shortcut = $shortcut . "<a href = 'https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/organization/vehicle/option/viewCategory.php'><div id='div3' class='nav-item col-md-2' style='background:#04B0B6;border:0px;margin-bottom:10px;'>
      <span style='font-size:12px;color:white;'>View Vehicles Category</span></div></a>";
    }
    $shortcut = $shortcut . "</center>

  </div>

  <ul>

</div>";

    unset($_SESSION['module']);
    return $shortcut;
}
?>
<script>
    /* var links = document.getElementById("exampleAccordion").getElementsByTagName("a");

    for(var i = 0; i < links.length; i++){
        links[i].onclick = function(event){
            for(var j = 0; j < links.length; j++){
                links[j].classList.remove('active');
            }
            event.target.parentNode.classList.add('active');
            return false;
        };
    }
*/

    //  Use the below snippet and also add / at the end of the url's in the navbar.

    $(document).ready(function() {

        var url = [location.protocol, '//', location.host, location.pathname].join('');

        $('.nav-item.active').removeClass('active');
        $('.nav-item a[href="' + url + '"]').parent().addClass('active');
        $(this).parent().addClass('active').siblings().removeClass('active');

        var collapseItem = localStorage.getItem('collapseItem');
        if (collapseItem) {
            $(collapseItem).collapse('show')
        }

        // Clear local storage on menu close action
        $('#sidebar .list-group > div').on('hide.bs.collapse', function() {
            localStorage.setItem('collapseItem', '');
        });

    });
</script>