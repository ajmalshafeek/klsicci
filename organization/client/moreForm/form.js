//COOKIE
function setCookie(cname,cvalue,exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays*24*60*60*1000));
  var expires = "expires=" + d.toGMTString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function numRow(){
  i++;
  return i;
}

function increment(){
  n++;
  return n;
}

function addTableRow() {
  var i=numRow();
  var n=increment();
  var table = document.getElementById("spTable");
  var row = table.insertRow(i+1);
  var cell1 = row.insertCell(0);
  var cell2 = row.insertCell(1);
  //cell1.innerHTML = "<input type='text' name='product"+i+"' class='form-control'/>";
  //cell2.innerHTML = "<input type='number' name='spQty"+i+"' class='form-control'/>";
  cell1.innerHTML = "<select name='product"+i+"' id='instalStatus' class='form-control' required><?php
                      $dataList = productList();
                      echo "<option value='' selected disabled >--Select Product--</option>";
                      foreach($dataList as $data){
                        echo "<option value='".$data['id']."'>".$data['model']."[".strtoupper($data['brand'])."][S/N: ".$data['serialNum']."]</option>";
                        }
                      echo "</select>";
                    ?>";
  cell2.innerHTML = "<select name='cStatus"+i+"' id='instalStatus' class='form-control' required><option  value='' selected disabled >--Select Status--</option><option value='0'>TG</option><option value='1'>WTY</option><option value='2'>PERCALL</option><option value='3'>RENTAL</option><option value='4'>AD HOC</option></select>";
  cell3.innerHTML = n;
}

function removeTableRow() {
  if (i!=0 && i>j) {
    document.getElementById("spTable").deleteRow(i+1);
    i--;
  }
}
