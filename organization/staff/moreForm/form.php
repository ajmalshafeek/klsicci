<?php
function additionalForm($jobId,$meter1,$meter2,$meter3,$meter4,$meterTotal,$zone,$service,$action){
  $form='';
  if ($_SESSION['orgType']==1) {
    $form.='
    <div class="form-group row">
     <label for="product" class="col-sm-2 col-form-label col-form-label-lg">PRODUCT</label>
     <div class="col-sm-10"   >
       '.productTableList($jobId).'
     </div>
   </div>
  <!--(START)METER READING-->
    <!--1-->
    <div class="form-group row">
     <label for="meter1" class="col-sm-2 col-form-label col-form-label-lg">METER READING 1</label>
     <div class="col-sm-8"   >
       <input type="text" value="'.$meter1.'" name="meter1" id="meter1" class="form-control" >
       <div class="invalid-feedback">
         Please enter meter reading 1
       </div>
     </div>
   </div>
   <!--2-->
   <div class="form-group row">
    <label for="meter2" class="col-sm-2 col-form-label col-form-label-lg">METER READING 2</label>
    <div class="col-sm-8"   >
      <input type="text" value="'.$meter2.'" name="meter2" id="meter2" class="form-control" >
      <div class="invalid-feedback">
        Please enter meter reading 2
      </div>
    </div>
  </div>
  <!--3-->
  <div class="form-group row">
   <label for="meter3" class="col-sm-2 col-form-label col-form-label-lg">METER READING 3</label>
   <div class="col-sm-8"   >
     <input type="text" value="'.$meter3.'" name="meter3" id="meter3" class="form-control" >
     <div class="invalid-feedback">
       Please enter meter reading 3
     </div>
   </div>
  </div>
  <!--4-->
  <div class="form-group row">
  <label for="meter4" class="col-sm-2 col-form-label col-form-label-lg">METER READING 4</label>
  <div class="col-sm-8"   >
    <input type="text" value="'.$meter4.'" name="meter4" id="meter4" class="form-control" >
    <div class="invalid-feedback">
      Please enter meter reading 4
    </div>
  </div>
  </div>
  <!--Total-->
  <div class="form-group row">
  <label for="meterTotal" class="col-sm-2 col-form-label col-form-label-lg">METER READING TOTAL</label>
  <div class="col-sm-8"   >
    <input type="text" value="'.$meterTotal.'" name="meterTotal" id="meter4" class="form-control" >
    <div class="invalid-feedback">
      Please enter meter reading total
    </div>
  </div>
  </div>
  <!--(END)METER READING-->
  <!--(START)SERVICE/ZONE CHARGE-->
  <hr>
  <div class="form-group row">
  <label for="collapseExample" class="col-sm-2 col-form-label col-form-label-lg">ZONE/SERVICE CHARGE</label>
  <div class="col-sm-10"   >
  <div class="collapse" id="collapseExample">
      <div class="card card-body">
          <label for="zone" class="col-sm-2 col-form-label col-form-label-lg">ZONE CHARGE(RM)</label>
          <input type="text" value="'.$zone.'" name="zone" id="zone" class="form-control" >
          <label for="service" class="col-sm-2 col-form-label col-form-label-lg">SERVICE CHARGE(RM)</label>
          <input type="text" value="'.$service.'" name="service" id="service" class="form-control" >
      </div>
  </div>
  <a class="btn btn-primary col-sm-12" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample" onclick="requiredButtonChange()">
      <p id="requiredButton">Click here if required</p>
  </a>
   <!--<textarea name="action" id="action" class="form-control">'.$action.'</textarea>
   <input type="text" value="" name="action" id="action" class="form-control" > -->
   <div class="invalid-feedback">
     Please enter action taken
   </div>
  </div>
  </div>
  <hr>
  <!--(END)SERVICE/ZONE CHARGE-->';
  }

  if ($_SESSION['orgType']==5) {
      $landscape = landscapeInfo($jobId);
      $machinery = $landscape[0];
      $totalTree = $landscape[1];
      $otherWork = $landscape[2];
      $manDays = $landscape[3];
      $timeIn = $landscape[4];
      $timeOut = $landscape[5];

      $checked0 = "unchecked";
      $checked1 = "unchecked";
      $checked2 = "unchecked";
      $checked3 = "unchecked";
      $checked4 = "unchecked";
      $checked5 = "unchecked";
      $checked6 = "unchecked";
      switch ($machinery) {
          case "0":
              $checked0 = "checked";
              break;
          case "1":
              $checked1 = "checked";
              break;
          case "2":
              $checked2 = "checked";
              break;
          case "3":
              $checked3 = "checked";
              break;
          case "4":
              $checked4 = "checked";
              break;
          case "5":
              $checked5 = "checked";
              break;
          case "6":
              $checked6 = "checked";
              break;
      }

      $form.='
      <hr>
      <div class="form-group row">
      <label for="totalTree" class="col-sm-2  col-form-label col-form-label-lg">Machinery</label>
        <div class="col-sm-10"   >

            <input type="radio" name="machinery" value="0" '.$checked0.'>
            <label for="male">Vemme BC 1000XL</label><br>

            <input type="radio" name="machinery" value="1" '.$checked1.'>
            <label for="female">Lorry Tipper</label><br>

            <input type="radio" name="machinery" value="2" '.$checked2.'>
            <label for="female">Chainsaw</label><br>

            <input type="radio" name="machinery" value="3" '.$checked3.'>
            <label for="female">Lorry Bin</label><br>

            <input type="radio" name="machinery" value="4" '.$checked4.'>
            <label for="female">Skylift</label><br>

            <input type="radio" name="machinery" value="5" '.$checked5.'>
            <label for="female">Bachoer</label><br>

            <input type="radio" name="machinery" value="6" '.$checked6.'>
            <label for="female">Stump Cutter</label><br>

            <div class="invalid-feedback">
              Please enter total tree
            </div>
          </div>
      </div>

      <div class="form-group row">
      <label for="totalTree" class="col-sm-2  col-form-label col-form-label-lg">Total Tree</label>
        <div class="col-sm-10"   >
            <input type="number" class="form-control"  id="totalTree" name="totalTree"></input>
            <div class="invalid-feedback">
              Please enter total tree
            </div>
          </div>
      </div>

      <div class="form-group row">
      <label for="otherWork" class="col-sm-2  col-form-label col-form-label-lg">Other Works</label>
        <div class="col-sm-10">
            <textarea class="form-control" name="otherWork"></textarea>
            <div class="invalid-feedback">
              Please enter total tree
            </div>
          </div>
      </div>

      <div class="form-group row">
      <label for="manDays" class="col-sm-2  col-form-label col-form-label-lg">Man Days</label>
        <div class="col-sm-10">
            <input type="number" class="form-control"  id="manDays" name="manDays"></input>
            <div class="invalid-feedback">
              Please enter total man days
            </div>
          </div>
      </div>

      <div class="form-group row">
      <label for="timeIn" class="col-sm-2  col-form-label col-form-label-lg">Time In</label>
        <div class="col-sm-4">
            <input type="time" class="form-control"  id="timeIn" name="timeIn"></input>
            <div class="invalid-feedback">
              Please enter Time In
            </div>
        </div>

      <label for="timeOut" class="col-sm-2  col-form-label col-form-label-lg">Time Out</label>
        <div class="col-sm-4">
            <input type="time" class="form-control"  id="timeOut" name="timeOut"></input>
            <div class="invalid-feedback">
              Please enter Time Out
            </div>
        </div>
      </div>

      <hr>
      ';
  }
  echo $form;
}

function additionalRegisterForm(){


  $form='';
  if ($_SESSION['orgType']==5) {
    $form.='
    <hr>
    <!--(START)ADDRESS-->
    <div class="form-group row">
      <label for="address1" class="col-sm-2 col-form-label col-form-label-lg">Address:</label>
      <div class="col-sm-10">
        <!--(START)ADDRESS1-->
        <div class="form-group row">
          <label for="address1" class="col-sm-2 col-form-label col-form-label-lg">&bull;Address 1</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" placeholder="Street address, P.O box, C/O"  id="address1" name="address1" required ></input>
            <div class="invalid-feedback">
              Please enter address 1.
            </div>
          </div>
        </div>
        <!--(END)ADDRESS1-->
        <!--(START)ADDRESS2-->
        <div class="form-group row">
          <label for="address2" class="col-sm-2 col-form-label col-form-label-lg">&bull;Address 2</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" placeholder="Building, Suite, Unit, Floor"  id="address2" name="address2" required ></input>
            <div class="invalid-feedback">
              Please enter address 2.
            </div>
          </div>
        </div>
        <!--(END)ADDRESS2-->
        <!--(START)CITY/TOWN-->
        <div class="form-group row">
          <label for="city" class="col-sm-2 col-form-label col-form-label-lg">&bull;City/Town</label>
          <div class="col-sm-10">
            <textarea name="city" class="form-control" id="city" required></textarea>
            <div class="invalid-feedback">
              Please enter city/town
            </div>
          </div>
        </div>
        <!--(END)CITY/TOWN-->
        <!--(START)ZIP/POSTAL CODE-->
        <div class="form-group row">
          <label for="postalCode" class="col-sm-2 col-form-label col-form-label-lg">&bull;Zip/Postal Code</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" placeholder="Zip/Postal Code"  id="postalCode" name="postalCode" oninput="this.value = this.value.replace(/[^0-9.]/g, ""); this.value = this.value.replace(/(\..*)\./g, "$1");" required></input>
            <div class="invalid-feedback">
              Please enter postal code
            </div>
          </div>
        </div>
        <!--(END)ZIP/POSTAL CODE-->
        <!--(START)STATE-->
        <div class="form-group row">
          <label for="state" class="col-sm-2 col-form-label col-form-label-lg">&bull;State</label>
          <div class="col-sm-10">
            <select name="state"  id="state" class="form-control" required >
              <option  value="" selected disabled >--Select A State--</option>
              <option value="Johor">Johor</option>
              <option value="Kedah">Kedah</option>
              <option value="Kelantan">Kelantan</option>
              <option value="Kuala Lumpur">Kuala Lumpur</option>
              <option value="Labuan">Labuan</option>
              <option value="Malacca">Malacca</option>
              <option value="Negeri Sembilan">Negeri Sembilan</option>
              <option value="Pahang">Pahang</option>
              <option value="Perak">Perak</option>
              <option value="Perlis">Perlis</option>
              <option value="Penang">Penang</option>
              <option value="Sabah">Sabah</option>
              <option value="Sarawak">Sarawak</option>
              <option value="Selangor">Selangor</option>
              <option value="Terengganu">Terengganu</option>
            </select>
            <div class="invalid-feedback">
              Please select state
            </div>
          </div>
        </div>
        <!--(END)STATE-->
      </div>
    </div>
    <!--(END)BILLING ADDRESS-->
    ';

    $form.='
    <div class="form-group row">
    <label for="clientContactNo" class="col-sm-2  col-form-label col-form-label-lg">Phone No.</label>
      <div class="col-sm-10"   >
          <input type="text" class="form-control" placeholder="xx-xxx xxxx"  id="contact" name="contact" oninput="this.value = this.value.replace(/[^0-9.]/g, ""); this.value = this.value.replace(/(\..*)\./g, "$1");" required></input>
          <div class="invalid-feedback">
            Please enter phone no.
          </div>
        </div>
    </div>
    ';

    $form.='
    <div class="form-group row">
    <!--(START)MARRIED STATUS-->
      <label for="state" class="col-sm-2 col-form-label col-form-label-lg">Married</label>
      <div class="col-sm-4">
        <select name="married"  id="married" class="form-control" required >
          <option  value="" selected disabled >--Select Married Status--</option>
          <option value="0">Yes</option>
          <option value="1">No</option>
        </select>
        <div class="invalid-feedback">
          Please select married state
        </div>
      </div>
    <!--(END)MARRIED STATUS-->

    <label for="state" class="col-sm-2 col-form-label col-form-label-lg">Education Level</label>
      <div class="col-sm-4">
        <select name="education"  id="education" class="form-control" required >
          <option  value="" selected disabled >--Select Education level--</option>
          <option value="0">SPM</option>
          <option value="1">Diploma</option>
          <option value="2">Degree</option>
          <option value="3">Master</option>
        </select>
        <div class="invalid-feedback">
          Please select education level
        </div>
      </div>
    </div>
    ';

    $form.='
    <div class="form-group row">
    <!--(START)DRIVING LICENSE-->
      <label for="license" class="col-sm-2 col-form-label col-form-label-lg">Driving License</label>
      <div class="col-sm-4">
        <select name="license"  id="license" class="form-control" required >
          <option  value="" selected disabled >--Does this staff have driving license?--</option>
          <option value="0">Yes</option>
          <option value="1">No</option>
        </select>
        <div class="invalid-feedback">
          Please select drving license status
        </div>
      </div>
    <!--(END)DRIVING LICENSE-->
    </div>
    ';
  }
  echo $form;
}
?>
