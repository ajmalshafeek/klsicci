<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
    session_name($config['sessionName']);
    session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/subscriber.php");

?>
<!DOCTYPE html >

<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta https-equiv="Content-Type" content="text/html; charset=utf-8">


    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />


    <?php
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>
    <!-- datatable -->
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/jquery.dataTables.js'></script>
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/datatables/dataTables.bootstrap4.js'></script>
    <script src='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/js/sb-admin-datatables.min.js'></script>
    <!--<script src='https://code.jquery.com/jquery-3.3.1.js'></script>
     <script src='https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js'></script> -->
    <script src='https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js'></script>
    <script src='https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js'></script>
    <script src='https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js'></script>
    <script src='https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js'></script>
    <!-- datatable -->
    <script>


        function clientDelete(str){

            $("#clientIdToDelete").val(str.value);

        }

        function clientEdit(str){

            $("#clientIdToEdit").val(str.value);
            $(".clientIdToEdit").val(str.value);
        }
        function memberEdit(str,state){
            $("#clientIdToEdit").val(str.value);
            $(".clientIdToEdit").val(str.value);
            $("#statusRg").val(state);
            if(state>0){
                $("#activate").css('display','none');
                $("#deactivate").css('display','flex');
            }else{
                $("#activate").css('display','flex');
                $("#deactivate").css('display','none');
            }
        }
        function payEdit(str,state){
            $("#clientIdToEdit").val(str.value);
            $(".clientIdToEdit").val(str.value);
            $("#statusPy").val(state);
            if(state>0){
                $("#payed").css('display','none');
                $("#pending").css('display','flex');
            }else{
                $("#payed").css('display','flex');
                $("#pending").css('display','none');
            }
        }


        <?php  if(isset($_SESSION['downloadclients']) && $_SESSION['downloadclients']){ ?>
        $(document).ready(function() {
            $('#dataTable2').DataTable( {
                dom: 'Bfrtip',
                buttons: [
                    { extend: 'excel', text: 'Download Excel' ,
                        title:'Client list',
                        exportOptions: {
                            columns: ':not(:last-child)',
                        }

                    }
                ]
            } );
        } );
        <?php } ?>

        function showPassword(pwdId) {

            var x = document.getElementById(pwdId);
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }

        }

    </script>
    <script>
        $(document).ready(function() {
            $('#dataTable tbody').on('click', 'td', function () {
                if($(this).index() == 4) {
                    return;
                }

                var fileNo=$(this).closest('tr').find('td:eq(2)').text();
                var fileName=$(this).closest('tr').data('value');
                if(fileName.length>0) {
                    var path =<?php echo "'https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/resources/' . $_SESSION['orgId'] . '/plan/' . "'"; ?>;
                    var filePDF = path + fileName;
                    $('#filePDFModalTitle').text('PDF File # ' + fileNo);
                    $('#filePDFObject').prop('data', filePDF);
                    $('#filePDFAnchor').prop('href', filePDF);

                    $('.fileNo').prop('value', fileNo);
                    $('#filePDFModal').modal('toggle');

                    //(START)PDF NOT LOAD FIXES
                    var filePDFHTML = "<object id='filePDFObject' data='" + filePDF + "' frameborder='0'  style='height: 85vh;width: 100%'><p>Your web browser doesn't have a PDF plugin.Instead you can<a id='pdfAnchor' target='_blank' href='" + filePDF + "'>click here to download the PDF file.</a></p></object>";
                    document.getElementById("filePDFModalContent").innerHTML = filePDFHTML;
                    //(END)PDF NOT LOAD FIXES
                }
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

        .bg-red{
            background-color: #E32526;
        }

        .dt-button{
            margin: 5px 0px 0px 10px;
            color:white;
            background: #8A0808;
            border:0px;
            padding: 10px;
            border-radius: 5px;
        }

        #dataTable_paginate{
            color:black !important;
        }

        #dataTable2_filter{
            display:none;
        }

        #dataTable2{
            display:none;
        }

        #dataTable2_info{
            display:none;
        }

        #dataTable2_paginate{
            display:none;
        }
        .modal-dialog.modal-dialog-centered.modal-full {
            max-width: 90vw;
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
            <li class="breadcrumb-item ">Membership Subscriber</li>
            <li class="breadcrumb-item active">View Members</li>
        </ol>
    </div>

    <?php
    if (isset($_SESSION['feedback'])) {
        echo $_SESSION['feedback'];
        unset($_SESSION['feedback']);
    }
    ?>
    <div class='card mb-3'>
        <div class='card-header'>
            <i class='fa fa-table'></i>
            Members List
        </div>
        <?php
        membershipPlanTable();
        //   if (isset($_SESSION['downloadclients'])&&$_SESSION['downloadclients']) { clientListDataTable(); /*for excel export*/ }
        ?>
    </div>
</div>
</div>


<form method="POST" action="<?php echo "https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/phpfunctions/subscriber.php" ?>" >

    <div class="modal fade" id="planEditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="clientEditModalTitle">ACTIONS</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div id='staffEditContent' >
                        What action do you wish to do for the membership plan?
                    </div>

                    <div class="modal-footer">
                        <input type="text" hidden name="clientIdToEdit" id="clientIdToEdit" class="clientIdToEdit" value=""  />
                        <!-- <button type="submit" name='addNewClient' class="btn btn-primary edit" value="0" >Add potential Client </button> -->

                        <button type="button" data-toggle='modal' data-target='#planUpdateModal' class="btn btn-primary remove" >Upload Pdf</button>
                        <button type="button" data-toggle='modal' data-target='#removeNewClientModel' class="btn btn-primary edit" >Remove</button>
                        <button type="button" class="btn btn-secondary remove" data-dismiss="modal">CANCEL</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<form method="POST" action="<?php echo "https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/phpfunctions/subscriber.php" ?>" enctype="multipart/form-data" >

    <div class="modal fade" id="planUpdateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="clientEditModalTitle">ACTIONS</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div id='planUpdateContent' >
                    Upload PDF document
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12"   >
                            <input type="file" placeholder="Enter pdf file"  class="form-control" id="filePdf" name="filePdf" onchange="filePDFValidation(this)" accept="application/pdf" />
                            <div class="invalid-feedback">
                                Please enter file
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <input type="text" hidden name="clientIdToEdit" id="clientIdToEdit" class="clientIdToEdit" value=""  />
                        <!-- <button type="submit" name='addNewClient' class="btn btn-primary edit" value="0" >Add potential Client </button> -->
                        <button type="submit" class="btn btn-primary edit" name="updatePdf" >Update PDF</button>
                        <button type="button" class="btn btn-secondary remove" data-dismiss="modal">CANCEL</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

    <!-- file PDF Modal START-->
    <div class="modal fade "  id="filePDFModal" tabindex="-1" role="dialog" aria-labelledby="filePDFModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-full" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filePDFModalTitle">DETAILS</h5>
                    <div class="text-right">
                        <button type="button" class="btn btn-secondary btn-lg" title="CLOSE DIALOG" data-dismiss="modal">
                            <i class="fa fa-times" aria-hidden="true"></i>
                            Close
                        </button>
                    </div>
                </div>
                <div class="modal-body">

                    <div id='filePDFModalContent' >
                        <object id="filePDFObject" data="<?php echo "https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/resources/2/file/0000000003.pdf"; ?>" frameborder="0" width="100%" height="400px" style="height: 85vh;">
                            <p>Your web browser doesn't have a PDF plugin.
                                Instead you can
                                <a id="filePDFAnchor" target="_blank" href="<?php echo "https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/resources/2/file/0000000003.pdf"; ?>">click here to
                                    download the PDF file.</a>
                            </p>
                        </object>
                    </div>

                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <!-- file PDF Model END -->


<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fa fa-angle-up"></i>
</a>

<div class="footer">
    <p>Powered by JSoft Solution Sdn. Bhd</p>
</div>
</div>

<form method="POST" action="<?php echo "https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/phpfunctions/subscriber.php" ?>" >

    <div class="modal fade" id="removeNewClientModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                        Are you sure want to delete request?
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="text" hidden name="clientIdToEdit" class="clientIdToEdit" value=""  />
                    <button type="submit" name='removePlan' class="btn btn-primary edit" >Yes</button>
                    <button type="button" class="btn btn-secondary remove" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>

</form>
<script>
    function filePDFValidation(a){
        var fileInput = a;

        var filePath = fileInput.value;
        var allowedExtensions = /(\.pdf)$/i;
        if(!allowedExtensions.exec(filePath)){
            alert('Please upload file having extensions .pdf only.');
            fileInput.value = '';
            return false;
        }
    }
</script>
</body>
</html>