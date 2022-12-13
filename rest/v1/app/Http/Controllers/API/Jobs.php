<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Job;
use App\Models\Jobtransaction;
use App\Models\Clientcomplaint;
use App\Models\Clientcompany;
use App\Models\Clientupdateimage;
use App\Models\Rolemodules;
use App\Models\Autonum;

use App\Models\Organization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use mysql_xdevapi\Table;
use Validator;
use Illuminate\Contracts\Validation\Rule;
use phpDocumentor\Reflection\Types\Null_;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

date_default_timezone_set("Asia/Kuala_Lumpur");
class Jobs extends Controller
{
    //
    public function mytask(){

        $user=Auth::user()->id;
        return $users = DB::select('SELECT *,j.id as idJ, jt.id as idJt , jt.status as statusJt , jt.createdDate as createdDateJt , (SELECT name FROM clientcompany WHERE id=j.clientCompanyId) AS `clientName`, (SELECT CONCAT_WS(", ", NULLIF(address1, ""), NULLIF(address2, ""), NULLIF(city, ""), NULLIF(postalCode, ""), NULLIF(state, "")) FROM clientcompany WHERE id=j.clientCompanyId) AS `clientAddress`, (SELECT issueName FROM clientcomplaint WHERE id=j.complaintId) AS `task`, (SELECT issueDetail FROM clientcomplaint WHERE id=j.complaintId) AS `taskDetails` FROM job j INNER JOIN jobtransaction jt WHERE jt.jobId=j.id AND j.complaintId>0 AND jt.status>0 AND j.orgId=2 AND jt.workerId='.$user.' ORDER BY j.createdDate DESC');
    }
    public function task(Request $request){
        $user=Auth::user()->id;
        return $users = DB::select('SELECT *,j.id as idJ, jt.id as idJt , jt.status as statusJt , jt.createdDate as createdDateJt , (SELECT name FROM clientcompany WHERE id=j.clientCompanyId) AS `clientName`, (SELECT CONCAT_WS(", ", NULLIF(address1, ""), NULLIF(address2, ""), NULLIF(city, ""), NULLIF(postalCode, ""), NULLIF(state, "")) FROM clientcompany WHERE id=j.clientCompanyId) AS `clientAddress`, (SELECT issueName FROM clientcomplaint WHERE id=j.complaintId) AS `task`, (SELECT issueDetail FROM clientcomplaint WHERE id=j.complaintId) AS `taskDetails` FROM job j INNER JOIN jobtransaction jt WHERE jt.jobId=j.id AND j.complaintId>0 AND jt.status>0 AND j.orgId=2 AND jt.workerId='.$user.' AND j.id='.$request->id);
    }

    public function update(Request $req)
    {
       $job= Job::find($req->id);
        $jobid=$req->id;
        $taken=$req->taken;
        $status=$req->status;
        $complaintId=$job->complaintId;
        $logitude=$req->logitude;
        $latitude=$req->latitude;
        $latlong=$latitude.",".$logitude;
        $customer=$req->csname;
        $action=$req->action;
        $uploadFolder = 'orgJob'.'/'.$job->refNo;
        $signaturePath="";
       if($req->hasFile('signature')){
       $image = $req->file('signature');
        $image_uploaded_path = $image->storeAs($uploadFolder,'sign.png');
        $uploadedImageResponse=array(
            "image_name" => basename($image_uploaded_path),
            "image_url" => $image_uploaded_path,
            "mime" => $image->getClientMimeType());
        $sign=explode(".",$uploadedImageResponse['image_name']);
            $signaturePath ="2/".$uploadFolder."/".$sign[0];}
        // save database value job table
        $job= Job::find($jobid);
        $job->status=$status;
        $job->cName=$customer;
        $job->signaturePath=$signaturePath;
        $job->action=$action;
        $job->latlon=$latlong;
        if($status==0){
            $job->endTime=date("Y-m-d h:i:s");
        }
        $return="";
        if($job->save()){
            $return="{ 'job':'success'";
        }else{
            $return="{ 'job':'failed'";
        }


        foreach ($req->file('images') as $image){
            $image_uploaded_path = $image->store($uploadFolder);
            $uploadedImageResponse = array(
                "image_name" => basename($image_uploaded_path),
                "image_url" => $image_uploaded_path,
                "mime" => $image->getClientMimeType()
            );
            //Client Complaint Image Update
            $comImg=new Clientupdateimage;
            $comImg->complaintid=$complaintId;
            $comImg->path=$uploadedImageResponse['image_name'];
            $comImg->taken=$taken;
            if($comImg->save()){
                $return.=",'img':'success'";
            }else{
                $return.=",'img':'failed'";
            }
        }
        $jt=DB::table('jobtransaction')->where('jobId',$jobid)->update([
         'remarks'=>''.$action,
        'status'=>$status,
        'signaturePath'=>''.$signaturePath
         ]);

        $complaint=Clientcomplaint::find($complaintId);
        $complaint->status=$status;
        if( $complaint->save()){
            $return.=",'complaint':'success'}";
        }else{
            $return.=",'complaint':'failed'}";
        }
        return $return;
    }

    // /**
    //  *
    //  * @param \Illuminate\Http\Client\Request @request
    //  * @return \Illumniate\Http\Response
    //  */
    // public function create(Request $request) {
    //     return Job::create($request->all());
    // }

    /**
     * To create new task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        return Job::store($request->all());
    }

    public function index() {
        $data = DB::table('organization')
            ->where('id',2)->first();
//$data=$data[0];
        if($data->type==6){
           $list = DB::select('SELECT clientcomplaint.* FROM clientcomplaint JOIN telecom_service ON  1=1 AND telecom_service.cid=clientcomplaint.id AND clientcomplaint.status NOT IN ( 0 , 5 , 6 ,7) ORDER BY clientcomplaint.createdDate DESC, clientcomplaint.messageStatus ASC');
            return response()->json(['complaints' => $list], 200);
        }else{
            $list = DB::select('SELECT * FROM clientcomplaint WHERE status>0 ORDER BY createdDate DESC, messageStatus ASC');
            return response()->json(['complaints' => $list], 200);
        }

    //    return Clientcomplaint::all();
    }

    /**
     * Seacrh by name
     *
     * @param  str $name
     * @return \Illuminate\Http\Response
     */
    public function search($name)
    {
        $list= Clientcomplaint::where('issueName','like', '%'.$name.'%')->get();
        return response()->json(['complaints' => $list], 200);
       // return Job::where('jobName','like', '%'.$name.'%')->get();
    }

    /**
     * Seacrh
     *
     * @param  int $status
     * @return \Illuminate\Http\Response
     */
    public function statusFilter($status)
    {
        $list = Clientcomplaint::where('status','like', '%'.$status.'%')->get();
        return response()->json(['complaints' => $list], 200);
       // return Job::where('status','like', '%'.$status.'%')->get();
    }

    /**
     * Search by name
     *
     * @param  str $keyword
     * @return \Illuminate\Http\Response
     */
    public function searchTask($keyword) {
        $list= Clientcomplaint::where('issueName', 'like', '%' . $keyword . '%')
            ->orWhere('issueDetail', 'like', '%' . $keyword . '%')
            ->orWhere('remarks', 'like', '%' . $keyword . '%')
            ->get();
        return response()->json(['complaints' => $list], 200);
       /* return Job::where('jobName', 'like', '%' . $keyword . '%')
                  ->orWhere('remarks', 'like', '%' . $keyword . '%')
                  ->get();*/
    }

    /**
     * To assign task to staff
     *
     * @param  str $assign
     * @return \Illuminate\Http\Response
     */
    public function assignTask(Request $req) {
        $userid= Auth::user()->id;
        $workerType = $req->workerType;
        $workerId =$req->workerId;
        $complaintId=$req->complaintId;
        $createdBy=$userid;

        $comp = DB::select('SELECT * FROM clientcomplaint WHERE id='.$complaintId.'');
        $comp=$comp[0];
      //  $comp= Clientcomplaint::where('id',$req->complaintId)->first();
        $clientCompanyId=$comp->companyId;
        $jobName=$comp->issueName;
        $address=null;
        $vendorId=0;
        $vendorUserId=0;
/*        if($workerType==="vendors"){
            $vendorId=$workerId;
        } */
        $picName=null;
        $picContactNo=null;
        $dateRequire=null;
        $status=2;
        $remarks=null;
        $createdDate=date('Y-m-d H:i:s');
        $startTime=null;
        $endTime=null;
        $orgId=2;
        $refId=null;
        $workerDetails=null;

/*
        if($workerType==="vendors"){
            $workerDetails=fetchVendorAdminDetails($con,$vendorId,$orgId);
 $refId=fetchAutoNum($con,$vendorId);
        }else{ */
        $workerDetails = DB::select('SELECT * FROM organizationuser WHERE id='.$workerId.'');

    //        $workerDetails=getOrganizationUserDetails($con,$workerId);
    //$refId=fetchOrgAutoNum($con,$orgId);
//        }

        $refId = DB::select('SELECT * FROM autonum WHERE orgId='.$orgId.' AND vendorId='.$vendorId.'');
     //    orgId=? AND vendorId='0'
        $refId=$refId[0];
        $jobNo = $refId->jobNo;
        $jobRefNo=$refId->jobPrefix.sprintf('%08d',$jobNo);
        $autoNumId=$refId->id;
        $jobNo=$jobNo+1;

        $res= Autonum::where('id',$autoNumId)
            ->update(['jobNo' =>$jobNo]);
        if($res) {
            $jobId = DB::table('job')->insertGetId(
                array(
                    'refNo' => $jobRefNo,
                    'jobName' => $jobName,
                    'clientCompanyId' => $clientCompanyId,
                    'vendorId' => $vendorId,
                    'vendorUserId' => $vendorUserId,
                    'status' => $status,
                    'remarks' => $remarks,
                    'createdDate' => $createdDate,
                    'startTime' => $startTime,
                    'endTime' => $endTime,
                    'createdBy' => $createdBy,
                    'orgId' => $orgId,
                    'complaintId' => $complaintId,
                )
            );

            if($jobId>0){
                $status=2;
                $assignType=$workerType;
                $transactionId = DB::table('jobtransaction')->insertGetId(
                    array(
                        'jobId' => $jobId,
                        'createdDate' => $createdDate,
                        'createdBy' => $createdBy,
                        'assignType'=> $workerType,
                        'workerId'=> $workerId,
                        'startTime' => $startTime,
                        'endTime' => $endTime,
                        'remarks' => $remarks,
                        'status' => $status,
                        'orgId' => $orgId,
                    )
                );

                if($transactionId>0){
                    $messageStatus="O";
                    /*$success=  DB::update('update clientcomplaint set issueName =?, issueDetail=?, occuredDate=?, picName=?, picContactNo=?, createdDate=?, createdBy=?, status=?, companyId=?, orgId=? where id = ?',
                        [$comp['issueName'],$comp['issueDetail'],$comp['occuredDate'],$comp['picName'],$comp['picContactNo'],$comp['createdDate'],
                            $comp['createdBy'], $comp['status'],$comp['companyId'],$comp['orgId']]); */
                    //return $comp;
                    $success= Clientcomplaint::where('id',$comp->id)
                        ->update(['issueName' =>$comp->issueName,'issueDetail'=>$comp->issueDetail,'occuredDate'=>$comp->occuredDate,
                            'picName'=>$comp->picName,'picContactNo'=>$comp->picContactNo,'createdDate'=>$comp->createdDate,
                            'createdBy'=>$comp->createdBy,'status'=>$comp->status,'companyId'=>$comp->companyId,'orgId'=>$comp->orgId
                            ]);
                  if($success){
                      $org = Organization::where('id',$orgId)->get();
                      $org=$org[0];
                     // mail function requirement
                      require base_path("vendor/autoload.php");

                      $orgDetails=getOrganizationDetails($con,$orgId);

                      $from=$orgDetails['supportEmail'];
                      $fromName=$orgDetails['name'];
                      $to=$workerDetails['email'];
                      $subject='NEW TASK';
                      $body='Job task has been assigned to you by '.$_SESSION['name'].'@'.$orgDetails['name'].
                          '. Thus, ticket no: '.ticketno($complaintDetails['id']).
                          ' click on this <a href="https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].
                          '" target="_blank" >Login</a> to the task further action.'
                          ."<br/><b>Task</b>: ".$complaintDetails['issueName']."<br/><b>Details</b>: ".$complaintDetails['issueDetail'].
                          '<br /><br />Thank You';
                      //.$footer;


                      $orgLogo=$_SESSION['orgLogo'];



                      $mailMessage=mailTask($from,$fromName,$to,$subject,$body,$orgLogo);
                      // end mail function
                      if ($org->type==6) {
                          $docrecivedate = "" . date('Y-m-d') . " " . date("h:i") . "";
                          $docremarks = "[" . $docrecivedate . "] Mail acknowledged by " . $_SESSION['name'];;
                          $cid = $comp->id;
                          $docup=DB::update('UPDATE telecom_service SET docrecivedate=?,remarks=? WHERE cid=?',[$docrecivedate,$docremarks,$cid]);
                          if($docup){
                              return response()->json(['assign' => 'successful'], 200);
                          }
                          else{
                              return response()->json(['assign' => 'failed'], 400);
                          }
                      }else{
                          return response()->json(['assign' => 'successful'], 200);
                      }
                  }
                  else{
                      return response()->json(['assign' => 'failed'], 401);
                  }
                }
                else{
                    return response()->json(['assign' => 'failed'], 402);
                }
            }
            else{
                return response()->json(['assign' => 'failed'], 403);
            }
        }
        else{
            return response()->json(['assign' => 'failed'], 404);
        }
    }

    /**
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     * make jobsheet task
     */
    public function makeIncident(Request $req) {
        $userid=Auth::user()->id;
        $req->assignDate;
        $req->assignTime;

        $date_time_Obj = date_create($req->assignDate);
        //formatting the date/time object
        $format = date_format($date_time_Obj, "d-m-y");
        $fileNameToStore="";
      //  $fileName="";
            if ($req->hasFile('file')) {
                $filenameWithExt = $req->file('file')->getClientOriginalName ();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $req->file('file')->getClientOriginalExtension();
                $fileNameToStore =  time() . '_' . uniqid() . '.' . $extension;
             //   $fileName=$fileNameToStore;
                $path = $req->file('file')->storeAs('/2/complaint', $fileNameToStore, 'resources');
            }
            $res= DB::table('clientcomplaint')->insert(
            array(
                'issueName'     =>   $req->name,
                'issueDetail'   =>   $req->description,
                'companyId'=>$req->companyId,
                'status'=>2,
                'messageStatus'=>'N',
                'requireDate'=>$format. " " . $req->assignTime.":00",
                'createdBy'=>$userid,
                'fileAttach'=>$fileNameToStore,
                'orgId'=>2,
                'timeFrame'=>$req->sla,
                'createdDate'=>date("Y-m-d h:i:s")

            )
        );
            if($res){
            return response()->json(['complaint' => $res], 200);}
            else{return response()->json(['complaint' => 'failed'], 401);}
            }

function nortificationEmail($assignBy,$sEmail,$rEmail,$orgName,$complaintId,$issueName,$issueDetails){
    $config=parse_ini_file(__DIR__."/../../../jsheetconfig.ini");
        $from=$sEmail;
    $fromName=$orgName;
    $to=$rEmail;
    $subject='NEW TASK';
    $body='Job task has been assigned to you by '.$assignBy.'@'.$orgName.
        '. Thus, ticket no: '.ticketno($complaintId).
        ' click on this <a href="https://'.$_SERVER['HTTP_HOST'].$config['appRoot'].
        '" target="_blank" >Login</a> to the task further action.'
        ."<br/><b>Task</b>: ".$issueName."<br/><b>Details</b>: ".$issueDetails.
        '<br /><br />Thank You';
    //.$footer;

    $orgLogo=$_SESSION['orgLogo'];
    $config=parse_ini_file(__DIR__."/../jsheetconfig.ini");
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/organization.php");
    $orgDetails=fetchOrganizationDetails($_SESSION['orgId']);
    $address=$orgDetails['supportEmail'];
    require_once($_SERVER['DOCUMENT_ROOT'].$config['appRoot']."/phpfunctions/configurationApp.php");
    $ccemail= getCCEmailId();
    $footerCode=getFooterContent();
    $body.=$footerCode;

    $email = new PHPMailer();
    $email->From      = $from;
    $email->FromName  = strtoupper($fromName);
    $email->Subject   = $subject;
    $email->Body      = $body;
    $email->AddAddress($to);
    //	$email->AddCC( substr($address,1,strlen($address)-2));
    $email->AddCC($ccemail,$ccemail);



    $email->IsHTML(true);
    $email->AddEmbeddedImage(dirname(__DIR__)."/resources/".$orgLogo.".png", 'logo_2u');
$send=false;
    if(!$email->send()) {
        $send=false;
    } else {
        $send=true;
    }
    return $send;
}
    function ticketno($ticket){
        return sprintf("%07d",$ticket);

    }
function mailTask($from,$fromName,$to,$subject,$body,$orgLogo){

}
}
