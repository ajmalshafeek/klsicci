<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
  session_name($config['sessionName']);
  session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/project.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organizationUser.php");

?>
<!DOCTYPE html >

<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">


    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo 'https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].'/resources/app/favIcon.ico'; ?>' />
    <?php
      require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/importScripts.php");
    ?>
    <!-- Data Table Import -->
    <link rel="stylesheet" type="text/css" href="../../adminTheme/dataTable15012020/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="../../adminTheme/dataTable15012020/jquery.dataTables.js"></script>
    <script type="text/javascript">
    $(document).ready( function () {
      $('#projectTable').DataTable();
    } );
    </script>
    <style media="screen">
    #container {
      max-width: 800px;
      margin: 1em auto;
    }

    #loader {
      border: 16px solid #f3f3f3;
      border-radius: 50%;
      border-top: 16px solid #3498db;
      width: 120px;
      height: 120px;
      -webkit-animation: spin 2s linear infinite; /* Safari */
      animation: spin 2s linear infinite;
      z-index:100;
      display:none;
    }

    /* Safari */
    @-webkit-keyframes spin {
      0% { -webkit-transform: rotate(0deg); }
      100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .chartContainer{
      width: 800px;
      margin-left:10%;
      margin-right:10%;
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
        <li class="breadcrumb-item ">Project</li>
        <li class="breadcrumb-item active">Gantt Chart</li>
      </ol>
    </div>
    <?php
    if (isset($_SESSION['feedback'])) {
        echo $_SESSION['feedback'];
        unset($_SESSION['feedback']);
    }
    ?>
    <div class="container">
      <?php dropDownListProjectAll() ?>
    </div>
    <div class="container" style="overflow:scroll">

      <div class="chartContainer">
        <div id="container"></div>
        <div style="padding-top:10%;padding-left:45%;padding-right:45%">
          <div id="loader"></div>
        </div>
      </div>
      <input type="date" id="startDate" hidden>
      <input type="text" id="projectName" hidden>
    </div>
  </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fa fa-angle-up"></i>
    </a>
    <div class="footer">
      <p>Powered by JSoft Solution Sdn. Bhd</p>
    </div>

  </div>
  <script src="https://code.highcharts.com/gantt/highcharts-gantt.js"></script>
  <script src="https://code.highcharts.com/gantt/modules/exporting.js"></script>
  <script type="text/javascript">
  //BLANK CHART DATA
  blankChart()


  function processProject(){
    document.getElementById("loader").style.display = "block";
    document.getElementById("container").style.display = "none";
    var id = document.getElementById("projectId").value;

    var sel = document.getElementById("projectId");
    var text= sel.options[sel.selectedIndex].text;
    document.getElementById("projectName").value = text;
    //(START)GET PROJECT START DATE
    $.ajax({

        type  : 'GET',
        url  : '../../phpfunctions/project.php?',
        data : {projectStartDate:id},
        success: function (data) {
          document.getElementById("startDate").value = data;
          processProjectCont(id,sel,text);
        }
    });
    //(END)GET PROJECT START DATE
  }

 function processProjectCont(id,sel,text){
        $.ajax({

        type  : 'GET',
        url  : '../../phpfunctions/project.php?',
        data : {projectChartDataList:id},
        success: function (data) {
          dataList= JSON.parse(data);
          startDate = document.getElementById("startDate").value;
          console.log("0 " + startDate);
          var i;
          var today = new Date(startDate),
          day = 1000 * 60 * 60 * 24,
          // Utility functions
          dateFormat = Highcharts.dateFormat,
          defined = Highcharts.defined,
          isObject = Highcharts.isObject,
          reduce = Highcharts.reduce;
          console.log("1 " + today);
          // Set to 00:00:00:000 today
          today.setUTCHours(0);
          today.setUTCMinutes(0);
          today.setUTCSeconds(0);
          today.setUTCMilliseconds(0);
          today = today.getTime();
          console.log("2 " + today);
          var data = [];
          var dateStart = [];
          var dateEnd = [];

          var len = dataList.length;
          for (i = 0; i < len; ++i) {

            //dateStart[i] = today + 0 * day;
            //dateEnd[i] = today + dataList[i]['mandays'] * day;
            //dateStart[i] = today + dataList[i]['addStartDate'] * day;
            //dateEnd[i] = dateStart[i] + dataList[i]['mandays'] * day;
            dateStart[i] = new Date(dataList[i]['startDate']);
            dateStart[i] = dateStart[i].getTime();//REFORMAT
            dateEnd[i] = new Date(dataList[i]['endDate']);
            dateEnd[i] = dateEnd[i].getTime();//REFORMAT
            
            var percentage = dataList[i]['completed'];
            var completedAmount = percentage/100;
            data[i] = {"name":dataList[i]['name'], "id":dataList[i]['id'],"dependency":dataList[i]['dependency'],"parent":dataList[i]['parent'],"start":dateStart[i], "end":dateEnd[i], "completed":{amount: completedAmount}, "owner": dataList[i]['owner']}
          }

          var seriesContent = {
            name: 'Project',
            data: data
          }

          setTimeout(function(seriesContent,today,day){
            document.getElementById("loader").style.display = "none";
            document.getElementById("container").style.display = "block";
            console.log(seriesContent);
            console.log("3 " + today);
            console.log(day);
            Highcharts.ganttChart('container', {
            scrollbar: {
                enabled: true
            },
            credits: {
                enabled: false
            },
            series: [seriesContent],
            tooltip: {
              pointFormatter: function () {
                var point = this,
                  format = '%e. %b',
                  options = point.options,
                  completed = options.completed,
                  amount = isObject(completed) ? completed.amount : completed,
                  status = ((amount || 0) * 100) + '%',
                  lines;

                lines = [{
                  value: point.name,
                  style: 'font-weight: bold;'
                }, {
                  title: 'Start',
                  value: dateFormat(format, point.start)
                }, {
                  visible: !options.milestone,
                  title: 'End',
                  value: dateFormat(format, point.end)
                }, {
                  title: 'Completed',
                  value: status
                }, {
                  title: 'Owner',
                  value: options.owner || 'unassigned'
                }];

                return reduce(lines, function (str, line) {
                  var s = '',
                    style = (
                      defined(line.style) ? line.style : 'font-size: 0.8em;'
                    );
                  if (line.visible !== false) {
                    s = (
                      '<span style="' + style + '">' +
                      (defined(line.title) ? line.title + ': ' : '') +
                      (defined(line.value) ? line.value : '') +
                      '</span><br/>'
                    );
                  }
                  return str + s;
                }, '');
              }
            },
            title: {
              text: document.getElementById("projectName").value
            },
            xAxis: {
              currentDateIndicator: true,
              min: today - 3 * day,
              max: today + 28 * day
            }
            });
          }, 100,seriesContent,today,day);
        }
    });
 }

  function blankChart(){
      var today = new Date(),
      day = 1000 * 60 * 60 * 24,
      // Utility functions
      dateFormat = Highcharts.dateFormat,
      defined = Highcharts.defined,
      isObject = Highcharts.isObject,
      reduce = Highcharts.reduce;

  // Set to 00:00:00:000 today
  today.setUTCHours(0);
  today.setUTCMinutes(0);
  today.setUTCSeconds(0);
  today.setUTCMilliseconds(0);
  today = today.getTime();

  Highcharts.ganttChart('container', {
      credits: {
          enabled: false
      },
      series: [{
          name: 'Offices',
          data: [{
              name: '',
              id: 'new_offices',
              owner: 'Peter'
          }]
      }],
      tooltip: {
          pointFormatter: function () {
              var point = this,
                  format = '%e. %b',
                  options = point.options,
                  completed = options.completed,
                  amount = isObject(completed) ? completed.amount : completed,
                  status = ((amount || 0) * 100) + '%',
                  lines;

              lines = [{
                  value: point.name,
                  style: 'font-weight: bold;'
              }, {
                  title: 'Start',
                  value: dateFormat(format, point.start)
              }, {
                  visible: !options.milestone,
                  title: 'End',
                  value: dateFormat(format, point.end)
              }, {
                  title: 'Completed',
                  value: status
              }, {
                  title: 'Owner',
                  value: options.owner || 'unassigned'
              }];

              return reduce(lines, function (str, line) {
                  var s = '',
                      style = (
                          defined(line.style) ? line.style : 'font-size: 0.8em;'
                      );
                  if (line.visible !== false) {
                      s = (
                          '<span style="' + style + '">' +
                          (defined(line.title) ? line.title + ': ' : '') +
                          (defined(line.value) ? line.value : '') +
                          '</span><br/>'
                      );
                  }
                  return str + s;
              }, '');
          }
      },
      title: {
          text: 'Gantt Chart'
      },
      xAxis: {
          currentDateIndicator: true,
          min: today - 0 * day,
          max: today + 18 * day
      }
  });
  }
  </script>
</body>
</html>
