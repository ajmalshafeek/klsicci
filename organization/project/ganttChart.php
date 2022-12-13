<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <style media="screen">
    #container {
      max-width: 800px;
      margin: 1em auto;
    }
    </style>
    <title></title>
  </head>
  <body>
    <div id="container"></div>
  </body>
  <script src="https://code.highcharts.com/gantt/highcharts-gantt.js"></script>
  <script src="https://code.highcharts.com/gantt/modules/exporting.js"></script>
  <script type="text/javascript">
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
  series: [{
    name: 'Offices',
    data: [{
      name: 'Project 1',
      id: 'project_1',
      owner: 'Peter'
    }, {
      name: 'Task 1',
      id: 'task_1',
      parent: 'project_1',
      start: today - (2 * day),
      end: today + (6 * day),
      completed: {
        amount: 0.2
      },
      owner: 'Linda'
    }, {
      name: 'Task 2',
      id: 'task_2',
      dependency: 'task_1',
      parent: 'project_1',
      start: today + 6 * day,
      end: today + 8 * day,
      owner: 'Ivy'
    }, {
      name: 'Task 3',
      id: 'task_3',
      dependency: 'task_2',
      parent: 'project_1',
      start: today + 9.5 * day,
      milestone: true,
      owner: 'Peter'
    }, {
      name: 'Project 2',
      id: 'relocate',
      owner: 'Josh'
    }, {
      name: 'Task 1',
      id: 'relocate_staff',
      parent: 'relocate',
      start: today + 10 * day,
      end: today + 11 * day,
      owner: 'Mark'
    }, {
      name: 'Task 2',
      dependency: 'relocate_staff',
      parent: 'relocate',
      start: today + 11 * day,
      end: today + 13 * day,
      owner: 'Anne'
    }, {
      name: 'Task 3',
      dependency: 'relocate_staff',
      parent: 'relocate',
      start: today + 11 * day,
      end: today + 14 * day
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
    text: 'Gantt Chart Test'
  },
  xAxis: {
    currentDateIndicator: true,
    min: today - 3 * day,
    max: today + 18 * day
  }
  });
  </script>
</html>
