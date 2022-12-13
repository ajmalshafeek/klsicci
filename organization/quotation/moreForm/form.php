<?php
function additionalForm($dateFrom,$dateTo,$reset){
  $config=parse_ini_file(__DIR__."/../../../jsheetconfig.ini");
  //(START)SECTION

  if ($reset) {
    $resetButton = '
    <div class="form-group row">
    <div class="col-md-12">
        <a href="viewQuotation.php" style="float:right;color:blue;"><i>Reset</i></a>
    </div>
    </div>
    ';
  }else {
    $resetButton = '';
  }
  $form='
  <form action="https://'.$_SERVER["HTTP_HOST"].$config["appRoot"].'/organization/quotation/viewQuotation.php" method="post">
  <div id="dateFilter" class="card" style="display:none">
    <div class="card-body">
      <div class="form-group row">
        <div class="col-md-6">
          <label for="dateFrom">Date From:</label>
          <input id="dateFrom" class="form-control" type="date" name="dateFrom" value="'.$dateFrom.'" required>
        </div>
        <div class="col-md-6">
          <label for="dateTo">Date To:</label>
          <input id="dateTo" class="form-control" type="date" name="dateTo" value="'.$dateTo.'" required>
        </div>
      </div>
      <div class="form-group row">
      <div class="col-md-12">
          <button class="btn btn-secondary btn-lg btn-block" type="submit" name="dateFilter">Search</button>
      </div>
      </div>
      '.$resetButton.'
    </div>
  </div>
  <input type="checkbox" name="checked" checked hidden>
  <script type="text/javascript">showDateFilter()</script>
  </form>
  ';
  //(END)SECTION

  echo $form;
}
?>
