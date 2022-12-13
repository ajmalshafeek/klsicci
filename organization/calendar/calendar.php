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
    <link href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/css/sb-admin.css" rel="stylesheet">

    <link href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/custom-css/custom-css.css" rel="stylesheet">
    <?php if(isset($_SESSION['theme'])){ ?>
        <link href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/custom-css/theme/<?php echo $_SESSION['theme'] ?>" rel="stylesheet">
    <?php } ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
<!--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.css" /> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
        <script src="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/'; ?>adminTheme/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>

    $(document).ready(function() {
     var calendar = $('#calendar').fullCalendar({
      editable:true,
      eventLimit: true,
      header:{
       left:'prev,next today',
       center:'title',
       right:'month,agendaWeek,agendaDay',
      },

      buttonText: {
          today:    'Today',
          month:    'Month',
          week:     'Week',
          day:      'Day'
      },

//This component display excess event in a popup window. The giveaway is its bad for mobile UI
      views: {
        agenda: {
          eventLimit: 6 // adjust to 6 only for agendaWeek/agendaDay
        }
      },

      events: 'load.php',
      selectable:true,
      selectHelper:true,
      displayEventTime: false,
      longPressDelay: 300,
      select: function(start, end, allDay)
      {
       //var title = prompt("Set Client Appointment");

       //(START)RESET FOLLOWUP FORM
       $("#followupCheck").val("1");
       $("#followupCheck").prop("checked", false);
       followupCheck();
       //(END)RESET FOLLOWUP FORM

       var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
       var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");
       $('#start').val(start);
       $('#end').val(end);

       //RESET THE INPUTS
       $('#event').val("");
       $('#rescheduleRemarks').val("");
       $('#remarks').val("");
       $("#addEventModal").modal();
       $("#timeStart").val("00:00:00");

       /*if(title)
       {
        var title = document.getElementById("event").value;
        var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
        var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");
        $.ajax({
         url:"insert.php",
         type:"POST",
         data:{title:title, start:start, end:end},
         success:function()
         {
          calendar.fullCalendar('refetchEvents');
          alert("Added Successfully");
         }
        })
      } */
      },
      editable:true,
      eventResize:function(event)
      {
       var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
       var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
       var title = event.title;
       var id = event.id;
       $.ajax({
        url:"update.php",
        type:"POST",
        data:{eventAdjust:true,title:title, start:start, end:end, id:id},
        success:function(){
         calendar.fullCalendar('refetchEvents');
         alert('Event Update');
        }
       })
      },

      eventDrop:function(event)
      {
       var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
       var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
       var title = event.title;
       var id = event.id;
       $.ajax({
        url:"update.php",
        type:"POST",
        data:{eventAdjust:true,title:title, start:start, end:end, id:id},
        success:function()
        {
         calendar.fullCalendar('refetchEvents');
         alert("Event Updated");
        }
       });
      },

      eventClick:function(event)
      {
       $("#viewEventModal").modal();
       //var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
       var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD");
       var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
       var title = event.title;
       var id = event.id;
       var eventRemarks = event.remarks;
       var time = $.fullCalendar.formatDate(event.start, "HH:mm:ss");

       var todayFull = new Date();
       var today = todayFull.getFullYear() + "-" + (todayFull.getMonth()+1) + "-" + todayFull.getDate();
       console.log(today + "|" + start)

       var dateComStart = new Date(start);
       var dateComToday = new Date(today);
       if (dateComToday > dateComStart) {
         console.log("Date Pass");
         $('#eventTitle').prop('readonly', true);
         $('#eventRemarks').prop('readonly', true);
         $('#rescheduleButton').hide();
         $('#eventDrop').hide();
         $('#datePass').hide();
       }else{
         console.log("!Date Pass");
         $('#eventTitle').prop('readonly', false);
         $('#eventRemarks').prop('readonly', false);
         $('#rescheduleButton').show();
         $('#eventDrop').show();
         $('#datePass').show();
       }

       $('#eventTime').val(time);
       $('#eventStart').val(start);
       $('#rescheduleTime').val(time);
       $('#rescheduleStart').val(start);
       $('#eventEnd').html(end);
       $('#eventTitle').val(title);
       $('#eventRemarks').html(eventRemarks);
       $('#eventId').val(id);

       $("#followupUpdateCheck").val("1");
       $("#followupUpdateCheck").prop("checked", false);
       followupUpdateCheck();

       /*
       if(confirm("Are you sure you want to remove it?"))
       {
        var id = event.id;
        $.ajax({
         url:"delete.php",
         type:"POST",
         data:{id:id},
         success:function()
         {
          calendar.fullCalendar('refetchEvents');
          alert("Event Removed");
         }
        })
      } */
      },

     });

    //ADD EVENT
    $("#eventSubmit").click(function(){
      var title = $('#event').val();
      var start = $('#start').val();
      var end = $('#end').val();
      var remarks = $('#remarks').val();
      start = start.split(' ')[0] + " " + $('#timeStart').val();
      //end = end.split(' ')[0] + " " + $('#timeEnd').val();

      var followup = $('#followUp').val();
      followup = followup.split(' ')[0] + " " + $('#timeStart').val();
      console.log(followup);

      if ($('#followupCheck').val()=="1") {
        $.ajax({
         url:"insert.php",
         type:"POST",
         data:{title:title, start:start, end:end, followup:followup, remarks:remarks},
         success:function()
         {
          calendar.fullCalendar('refetchEvents');
          //alert("Added Successfully");
         }
       })
     }else if($('#followupCheck').val()=="0"){
        $.ajax({
         url:"insert.php",
         type:"POST",
         data:{title:title, start:start, end:end, remarks:remarks},
         success:function()
         {
          calendar.fullCalendar('refetchEvents');
          //alert("Added Successfully");
         }
       })
      }
    });

    $("#eventDrop").click(function(){
      var id = $('#eventId').val();
      $.ajax({
         url:"delete.php",
         type:"POST",
         data:{id:id},
         success:function()
         {
          calendar.fullCalendar('refetchEvents');
          //alert("Event Removed");
         }
      })
    });

    $("#update").click(function(){
      var id = $('#eventId').val();
      var eventTitle = $('#eventTitle').val();
      var eventTime = $('#eventTime').val();
      var eventRemarks = $('#eventRemarks').val();
      var followupUpdateCheck = $('#followupUpdateCheck').val();
      var followUpUpdate = $('#followUpUpdate').val();
      if (followupUpdateCheck=="1") {
        $.ajax({
         url:"update.php",
         type:"POST",
         data:{eventUpdate:true, eventTitle:eventTitle, eventRemarks:eventRemarks, id:id, followUpUpdate:followUpUpdate},
         success:function()
         {
          calendar.fullCalendar('refetchEvents');
          alert("Event Updated");
         }
        });
      }else if(followupUpdateCheck=="0"){
        $.ajax({
         url:"update.php",
         type:"POST",
         data:{eventUpdate:true, eventTitle:eventTitle, eventRemarks:eventRemarks, id:id},
         success:function()
         {
          calendar.fullCalendar('refetchEvents');
          alert("Event Updated");
         }
        });
      }
    });

    $("#reschedule").click(function(){
      var id = $('#eventId').val();
      var rescheduleDate = $('#rescheduleStart').val();
      var rescheduleTime = $('#rescheduleTime').val();
      rescheduleStart = rescheduleDate.split(' ')[0] + " " + rescheduleTime;
      console.log(rescheduleStart);
      var rescheduleRemarks = $('#rescheduleRemarks').val();
      $.ajax({
       url:"update.php",
       type:"POST",
       data:{eventReschedule:true, rescheduleStart, id:id, rescheduleRemarks:rescheduleRemarks},
       success:function()
       {
        calendar.fullCalendar('refetchEvents');
        alert("Event Updated");
       }
      });
    });

    $("#rescheduleButton").click(function(){
      $('#rescheduleModal').css("display","block");
      $('#updateModal').css("display","none");

      $('#rescheduleButton').css("display","none");
      $('#updateButton').css("display","block");

      $('#eventTitle').attr('readonly', true);
    });

    $("#updateButton").click(function(){
      $('#rescheduleModal').css("display","none");
      $('#updateModal').css("display","block");

      $('#rescheduleButton').css("display","block");
      $('#updateButton').css("display","none");

      $('#eventTitle').attr('readonly', false);
    });

    });

    function followupCheck(){
      var x = document.getElementById("followupCheck").value;
      if (x === "0") {
        document.getElementById("followupForm").style.display = "block";
        document.getElementById("followupCheck").value = "1";
      }else if(x === "1"){
        document.getElementById("followupForm").style.display = "none";
        document.getElementById("followupCheck").value = "0";
      }
    }

    function followupUpdateCheck(){
      var x = document.getElementById("followupUpdateCheck").value;
      if (x === "0") {
        document.getElementById("followupUpdateForm").style.display = "block";
        document.getElementById("followupUpdateCheck").value = "1";
      }else if(x === "1"){
        document.getElementById("followupUpdateForm").style.display = "none";
        document.getElementById("followupUpdateCheck").value = "0";
      }
    }
    </script>
    <style>
    @media screen and (max-width: 600px) {
      .fc-button{
        font-size: 10px !important;
      }

      .fc-center h2{
        font-size: 20px !important;
      }

      .fc-view-container{
        font-size: 10px !important;
      }
    }
    </style>
  </head>
    <?php
    //  require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>

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
            <li class="breadcrumb-item active">Calendar</li>
          </ol>
        </div>
          <!--<iframe src="https://calendar.google.com/calendar/embed?src=etsipu%40gmail.com&ctz=Asia%2FShanghai" style="border: 0" width="100%" height="600" frameborder="0" scrolling="no"></iframe>-->
    <div class="container-fluid">
       <div id="calendar"></div>
    </div>

    <!-- Add Event Modal START-->
    <div class="modal fade" id="addEventModal" tabindex="-1" role="dialog" aria-labelledby="addEvent" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered modal-full" role="document">
           <div class="modal-content">
             <div class="modal-header">
               <h5 class="modal-title" id="addEventTitle">Add Appointment</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
               </button>
             </div>
             <div class="modal-body">
               <!--EVENT-->
               <div class="form-group row">
                   <label for="project" class="col-sm-4 col-form-label col-form-label-lg">Appointment Title:</label>
                   <div class="col-sm-8"   >
                       <input type="text" id="event" class="form-control" name="event" required>
                   <div class="invalid-feedback">
                         Please enter Appointment Title
                   </div>
                   </div>
               </div>
               <div class="form-group row">
                   <label for="timeStart" class="col-sm-4 col-form-label col-form-label-lg">Time:</label>
                   <div class="col-sm-8"   >
                       <input type="time"  id="timeStart" class="form-control" name="timeStart" value="00:00:00" required>
                   <div class="invalid-feedback">
                         Please enter Time
                   </div>
                   </div>
               </div>
               <!--REMARKS-->
               <div class="form-group row">
                   <label for="remarks" class="col-sm-4 col-form-label col-form-label-lg">Remarks:</label>
                   <div class="col-sm-8"   >
                       <textarea id="remarks" class="form-control" name="remarks" rows="8" cols="80"></textarea>
                   <div class="invalid-feedback">
                         Please enter Remarks
                   </div>
                   </div>
               </div>
               <!--FOLLOWUP-->
               <hr>
               <div class="form-group row">
                 <label class="col-sm-4 col-form-label col-form-label-lg">Follow Up<input onclick="followupCheck()" id="followupCheck" type="checkbox" value="0" style="width:50px;"></label>
               </div>
               <!--FOLLOWUP-->
               <div class="form-group row" id="followupForm" style="display:none;">
                   <label for="followUp" class="col-sm-4 col-form-label col-form-label-lg">Follow Up Date:</label>
                   <div class="col-sm-8"   >
                       <input type="date" id="followUp" class="form-control" name="followUp" required>
                   <div class="invalid-feedback">
                         Please enter Follow Up date
                   </div>
                   </div>
               </div>
               <!-- SUBMIT BUTTON -->
               <div class="form-group row">
                   <div class="col-sm-12">
                     <button id="eventSubmit" type="button" class="btn btn-primary btn-lg btn-block" name="eventSubmit" data-dismiss="modal">Submit</button>
                   </div>
               </div>
             </div>
             <div class="modal-footer">
               <input type="text" id="start" class="form-control" hidden>
               <input type="text" id="end" class="form-control" hidden>
               <button type="button" class="btn btn-secondary btn-lg" title="CLOSE DIALOG" data-dismiss="modal">
                <i class="fa fa-times" aria-hidden="true"></i>
                Close
         </button>
             </div>
           </div>
         </div>
       </div>
      <!-- Scroll to Top Button-->

      <!-- View Event Modal START-->
      <div class="modal fade" id="viewEventModal" tabindex="-1" role="dialog" aria-labelledby="viewEvent" aria-hidden="true">
           <div class="modal-dialog modal-dialog-centered modal-full" role="document">
             <div class="modal-content">
               <div class="modal-header">
                 <input id="eventTitle" class="form-control" type="text" name="eventTitle" style="font-size:20px;font-weight:bold  !important;">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
                 </button>
               </div>
               <div id="updateModal" class="modal-body">
                 <!--EVENT START-->
                 <div class="form-group row">
                     <label for="project" class="col-sm-3 col-form-label col-form-label-lg">Start:</label>
                     <div class="col-sm-9"   >
                       <!--<div id="eventStart"></div>-->
                       <input id="eventStart" class="form-control" type="date" name="eventStart" readonly>
                     </div>
                 </div>

                 <!--EVENT TIME-->
                 <div class="form-group row">
                     <label for="eventTime" class="col-sm-3 col-form-label col-form-label-lg">Time:</label>
                     <div class="col-sm-9"   >
                       <!--<div id="eventRemarks"></div>-->
                       <input id="eventTime" class="form-control" type="time" name="eventTime" readonly>
                     </div>
                 </div>

                 <!--EVENT REMARKS-->
                 <div class="form-group row">
                     <label for="project" class="col-sm-3 col-form-label col-form-label-lg">Remarks:</label>
                     <div class="col-sm-9"   >
                       <!--<div id="eventRemarks"></div>-->
                       <textarea class="form-control" id="eventRemarks" name="eventRemarks" rows="8" cols="80"></textarea>
                     </div>
                 </div>
                 <div id="datePass">
                   <!--FOLLOWUP-->
                   <hr>
                   <div class="form-group row">
                     <label class="col-sm-4 col-form-label col-form-label-lg">Follow Up<input onclick="followupUpdateCheck()" id="followupUpdateCheck" type="checkbox" value="0" style="width:50px;"></label>
                   </div>
                   <!--FOLLOWUP-->
                   <div class="form-group row" id="followupUpdateForm" style="display:none;">
                       <label for="followUp" class="col-sm-4 col-form-label col-form-label-lg">Follow Up Date:</label>
                       <div class="col-sm-8"   >
                           <input type="date" id="followUpUpdate" class="form-control" name="followUp" required>
                       <div class="invalid-feedback">
                             Please enter Follow Up date
                       </div>
                       </div>
                   </div>

                   <!--EVENT UPDATE-->
                   <div class="form-group row">
                     <div class="col-sm-12">
                       <button id="update" class="btn btn-secondary btn-lg col-sm-12" type="button" name="button" data-dismiss="modal">Update</button>
                     </div>
                   </div>
                 </div>
               </div>
               <div id="rescheduleModal" class="modal-body" style="display:none">
                 <!--EVENT START-->
                 <div class="form-group row">
                     <label for="project" class="col-sm-3 col-form-label col-form-label-lg">Start:</label>
                     <div class="col-sm-9"   >
                       <!--<div id="eventStart"></div>-->
                       <input id="rescheduleStart" class="form-control" type="date" name="rescheduleStart">
                     </div>
                 </div>

                 <!--EVENT TIME-->
                 <div class="form-group row">
                     <label for="rescheduleTime" class="col-sm-3 col-form-label col-form-label-lg">Time:</label>
                     <div class="col-sm-9"   >
                       <!--<div id="eventRemarks"></div>-->
                       <input id="rescheduleTime" class="form-control" type="time" name="rescheduleTime">
                     </div>
                 </div>

                 <!--EVENT REMARKS-->
                 <div class="form-group row">
                     <label for="project" class="col-sm-3 col-form-label col-form-label-lg">Reasons:</label>
                     <div class="col-sm-9"   >
                       <!--<div id="eventRemarks"></div>-->
                       <textarea class="form-control" id="rescheduleRemarks" name="rescheduleRemarks" rows="8" cols="80"></textarea>
                     </div>
                 </div>

                 <!--EVENT UPDATE-->
                 <div class="form-group row">
                   <div class="col-sm-12">
                     <button id="reschedule" class="btn btn-secondary btn-lg col-sm-12" type="button" name="button" data-dismiss="modal">Reschedule</button>
                   </div>
                 </div>

               </div>
               <div class="modal-footer">
                 <input type="text" id="eventId" class="form-control" hidden>
                 <button id="rescheduleButton" type="button" class="btn btn-secondary btn-lg">
                  Reschedule
                 </button>
                 <button id="updateButton" type="button" class="btn btn-secondary btn-lg" style="display:none">
                  Update
                 </button>
                 <button id="eventDrop" type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">
                  Remove
                 </button>
                 <button type="button" class="btn btn-secondary btn-lg" title="CLOSE DIALOG" data-dismiss="modal">
                  <i class="fa fa-times" aria-hidden="true"></i>
                  Close
                </button>
               </div>
             </div>
           </div>
         </div>
        <!-- Scroll to Top Button-->

      </body>
</html>
