<?php
function additionalForm($orgType){
  $form="";
  if ($orgType == 1) {
    $form = '
    <div class="form-group row">
      <label for="sparePart" class="col-sm-2 col-form-label col-form-label-lg">Product</label>
    <div class="col-sm-10">
      <table class="table order-list table-responsive  table-hover table-bordered" id="spTable">
      <tr>
        <th style="width:75%;background: gray;">Product</th>
        <th style="width:25%;background: gray;">Contact Status</th>
      </tr>
      <!-- (START)INITIALIZE COUNT -->
        <script> var i = 0;var j = 0; </script>
      <!-- (END)INITIALIZE COUNT -->
      <tr>
        <td>
          <select name="product0"  id="instalStatus" class="form-control" required>';
    $dataList = productList();
    $form .= '<option value="" selected disabled >--Select Product--</option>';
    foreach($dataList as $data){
      $form .= '<option value="'.$data['id'].'">'.$data['model'].'['.strtoupper($data['brand']).'][S/N: '.$data['serialNum'].']</option>';
    }
    $form .='</select>
        </td>
        <td>
          <select name="cStatus0"  id="instalStatus" class="form-control" required>
            <option  value="" selected disabled >--Select Status--</option>
            <option value="0">TG</option>
            <option value="1">WTY</option>
            <option value="2">PERCALL</option>
            <option value="3">RENTAL</option>
            <option value="4">AD HOC</option>
          </select>
        </td>
      </tr>
      <tr>
        <td><button onclick="addTableRow()" type="button" class="btn btn-lg btn-block btn-success fa fa-plus "></td>
          <td><button type="button" onclick="removeTableRow()" class="ibtnDel btn btn-md btn-danger fa fa-minus" ></button></td>
      </tr>
      <input type="text" hidden name="jobId" id="jobId" value="';
    $form .= '"/>';
    $form .='</table>';
    $form .= '<script>var n=0;';

    $form .= 'function setCookie(cname,cvalue,exdays) { var d = new Date();d.setTime(d.getTime() + (exdays*24*60*60*1000));var expires = "expires=" + d.toGMTString();document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";}';

    $form .= 'function numRow(){ i++;return i;}';

    $form .= 'function increment(){ n++;return n;}';

    $form .= 'function addTableRow(){ var i=numRow();var n=increment();var table = document.getElementById("spTable");var row = table.insertRow(i+1);var cell1 = row.insertCell(0);var cell2 = row.insertCell(1);cell1.innerHTML = "<select name=\'product"+i+"\' id=\'instalStatus\' class=\'form-control\' required>';

      $dataList = productList();
      $form .= "<option value='' selected disabled >--Select Product--</option>";
      foreach($dataList as $data){
        $form .= "<option value='".$data['id']."'>".$data['model']."[".strtoupper($data['brand'])."][S/N: ".$data['serialNum']."]</option>";
      }
      $form .= '</select>";';
      $form .= 'cell2.innerHTML = "<select name=\'cStatus"+i+"\' id=\'instalStatus\' class=\'form-control\' required><option  value=\'\' selected disabled >--Select Status--</option><option value=\'0\'>TG</option><option value=\'1\'>WTY</option><option value=\'2\'>PERCALL</option><option value=\'3\'>RENTAL</option><option value=\'4\'>AD HOC</option></select>";';
      $form .= 'cell3.innerHTML = n;}';

    $form .= 'function removeTableRow(){ if(i!=0 && i>j) { document.getElementById("spTable").deleteRow(i+1); i--;}}';

    $form .='</script><br></div></div>';
  }

  echo $form;
}

?>
