<?php
function additionalForm($orgType,$module){
  $form="";
  if ($orgType == 1) {
  $form.='
  <div class="form-group row">
    <label for="product" class="col-sm-2 col-form-label col-form-label-lg">Product</label>
    <div class="col-sm-10">
      <div id="tableProduct" class="">

      </div>
    </div>
  </div>
  ';
  }

  if ($module) {
    //(START)SLA TIMEFRAME
    $form.='
    <div class="form-group row">
      <label for="timeFrame" class="col-sm-2 col-form-label col-form-label-lg">SLA Time Frame</label>
      <div class="col-sm-10">
      <ul class="sla-option">
      <li>
          <input type="radio" id="a2" name="timeFrame" value="2"/>
          <label for="a2">2hrs</label>
      </li>
      <li>
          <input type="radio" id="a4" name="timeFrame" value="4"/>
          <label for="a4">4hrs</label>
      </li>
      <li>
          <input type="radio" id="a6" name="timeFrame" value="6"/>
          <label for="a6">6hrs</label>
      </li>
      <li>
          <input type="radio" id="a8" name="timeFrame" value="8"/>
          <label for="a8">8hrs</label>
      </li>
      <li>
          <input type="radio" id="a12" name="timeFrame" value="12"/>
          <label for="a12">12hrs</label>
      </li>
      <li>
          <input type="radio" id="a24" name="timeFrame" value="24"/>
          <label for="a24">24hrs</label>
      </li>
      <li>
          <input type="radio" id="a72" name="timeFrame" value="72"/>
          <label for="a72">72hrs</label>
      </li>
      <li>
          <input type="radio" id="a1d" name="timeFrame" value="24"/>
          <label for="a1d">1 Day</label>
      </li>
      <li>
          <input type="radio" id="a2d" name="timeFrame" value="48"/>
          <label for="a2d">2 Days</label>
      </li>
      <li>
          <input type="radio" id="a3d" name="timeFrame" value="72"/>
          <label for="a3d">3 Days</label>
      </li>
      <li>
          <input type="radio" id="a1w" name="timeFrame" value="168"/>
          <label for="a1w">1 Week</label>
      </li>
     <li>
          <input type="radio" id="a2w" name="timeFrame" value="336"/>
          <label for="a2w">2 Weeks</label>
      </li>
         <li>
          <input type="radio" id="a3w" name="timeFrame" value="504"/>
          <label for="a3w">3 Weeks</label>
      </li>
            </li>
    <li>
          <input type="radio" id="a4w" name="timeFrame" value="672"/>
          <label for="a4w">4 Weeks</label>
      </li>

      </ul>
      </div>
    </div>
    ';
    //(END)SLA TIMEFRAME
  }
  echo $form;
}

function additionalDetail($orgType){
  $form="";
  if ($orgType == 1) {
    $form.='
    <div class="form-group row">
      <label for="product" class="col-sm-2 col-form-label col-form-label-lg">PRODUCT</label>
      <div class="col-sm-10">
        <div id="tableProduct" class="">

        </div>
      </div>
    </div>
    ';
  }
  echo $form;
}
?>
