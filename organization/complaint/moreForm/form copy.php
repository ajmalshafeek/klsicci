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
    <script>
    function sliderVal(){
      var val = document.getElementById("sliderRange").value;
      if(val >= 24 && val < 48){
        var str = " [ 1 day and " + (val - 24) + " hour ]";
        document.getElementById("sliderVal").innerHTML = val + str;
      }else if(val >= 48 && val < 72){
        var str = " [ 2 days and " + (val - 48) + " hour ]";
        document.getElementById("sliderVal").innerHTML = val + str;
      }else if(val >= 72){
        var str = " [ 3 days and " + (val - 72) + " hour ]";
        document.getElementById("sliderVal").innerHTML = val + str;
      }else{
        document.getElementById("sliderVal").innerHTML = val;
      }
    }
    </script>
    <div class="form-group row">
      <label for="timeFrame" class="col-sm-2 col-form-label col-form-label-lg">SLA Time Frame</label>
      <div class="col-sm-10">
        <input oninput="sliderVal()" class="slider" id="sliderRange" type="range" min="1" max="72" value="0" name="timeFrame" style="width:100%;margin-top:9px;">
        <p>Hour: <span id="sliderVal">0</span></p>
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
