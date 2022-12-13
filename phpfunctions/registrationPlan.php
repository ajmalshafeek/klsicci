<?php
$config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
if(!isset($_SESSION))
{
    session_name($config['sessionName']);
    session_start();
}



require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/connect.php");
require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/query/subscriber.php");
function planListRegistration(){
    $con=connectDb();
    $datalist=fetchMembershipPlan($con);
$plan="";$count=1;
    foreach($datalist as $data) {
        $plan.='<div class="row py-3 my-2" style="border: 1px solid #8f8f8f; ">
                            <div class="col-2">
                                <input type="radio" name="planPrice" value="'.$data['price'].'" ';
        if($count==1){
            $plan.=' checked ';
        }
        $plan.='/>
                                
                            </div>
                            <div class="col-10">
                                <lable class="title">'.$data['title'].'</lable><br/>
                                <lable class="discription" style="font-weight: 400">'.$data['description'].'</lable><br />';
        if(!empty($data['duration'])) {
            $plan .= '<lable class="duration" style="font-weight: 300">' . $data['duration'] . ' Months</lable><br />';
        }
        $plan.='<lable class="price">RM '.$data['price'].'</lable>
                            </div>
                </div>';
        $count++;
    }
    echo $plan;
}
function planListRegistrationTitle(){
    $con=connectDb();
    $datalist=fetchMembershipPlan($con);
    $plan="";$count=1;
    foreach($datalist as $data) {
        $plan.='<div class="row py-3 my-2" style="border: 1px solid #8f8f8f; ">
                            <div class="col-2">
                                <input type="checkbox" class="planId" name="planId" value="'.$data['id'].'" ';
        $plan.='/> 
                            </div>
                            <div class="col-10">
                                <lable class="title">'.$data['title'].'</lable></div>
                 </div>';
        $count++;
    }
    echo $plan;
}
function planListRenualLoginRegistration(){
    $con=connectDb();
    $datalist=fetchMembershipPlan($con);
$plan="";$count=1;
    foreach($datalist as $data) {
        $plan.='<div class="col-sm-3 py-3 my-2 mx-1" style="border: 1px solid #8f8f8f; ">
                            <div class="col-2">

                                <input type="radio" name="planPrice" value="'.$data['price'].'" ';
        if($count==1){
            $plan.=' checked ';
        }
        $plan.='/>
                                
                            </div>
                            <div class="col-10">
                                <lable class="title">'.$data['title'].'</lable><br/>
                                <lable class="discription" style="font-weight: 400">'.$data['description'].'</lable><br />';
        if(!empty($data['duration'])) {
            $plan .= '<lable class="duration" style="font-weight: 300">' . $data['duration'] . ' Months</lable><br />';
        }
        $plan.='<lable class="price">RM '.$data['price'].'</lable>
                            </div>
                 </div>';
        $count++;
    }
    echo $plan;
}
?>