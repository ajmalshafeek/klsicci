<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Attendance;
use App\Models\Organizationuser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Validator;
use Illuminate\Contracts\Validation\Rule;

use Laravel\Passport\HasApiTokens;
class Attendances extends Controller
{
    //Route::post('attendance', 'API\Attendance@view');
//Route::post('attendancecheckin', 'API\Attendance@checkin');
//Route::post('attendancecheckout', 'API\Attendance@checkout');
public function view(Request $request){
    // return $users = DB::select('SELECT *  FROM attendance  WHERE staffId NOT IN (19, 37, 38) AND staffId='.$user);
    $data = Auth::user();
//    $data = DB::table('organizationuser')
//        ->where('id',$request->id)->get();
//    $data=$data[0];
    if($data->role==1||$data->role==3){
    $admin = DB::table('attendance')
    ->whereNotIn('staffId', [19, 38])
      ->get();
      return $admin;}
    else{
        $user = DB::table('attendance')
            ->where('staffId', $data->id)
            ->whereNotIn('staffId', [19, 38])
            ->get();
        return $user;
    }
}
/*public function checkin(){
   $user=\Auth::user();
return "checkin ".$user;
}
*/
    public function checkout(Request $request)
    {
        $user=Auth::user()->id;
        $date=date("Y-m-d");
        $attand=DB::select('SELECT * FROM attendance WHERE checkinTime LIKE \''.$date.'%\' AND staffId='.$user.' ORDER BY id DESC LIMIT 1');

        $column= sizeof($attand);
        $attand=json_encode($attand);
        $attand=json_decode($attand);
        if($column!=0) {
            if ($attand[0]->coCheck == 0) {
                $uploadFolder = 'attendance';
                $image = $request->file('image');
                $image_uploaded_path ="";
                $mime="";
                if($request->hasFile('image')){
                    $image_uploaded_path = $image->store($uploadFolder);
                    $mime=$image->getClientMimeType();
                }
                $uploadedImageResponse = array(
                    "image_name" => basename($image_uploaded_path),
                    "image_url" => $image_uploaded_path,
                    "mime" => $mime
                );
                if (empty($uploadedImageResponse['image_name'])) {
                    //return response()->json(['failed' => 'Take photo again'], 200);
                    $uploadedImageResponse['image_name']="";
                }
                $attendance = Attendance::find($attand[0]->id);
                $attendance->staffId = $user;
                $attendance->fileNameOut = $uploadedImageResponse['image_name'];
                $attendance->latitudeOut = $request->lat;
                $attendance->longitudeOut = $request->long;
                $attendance->cityNameOut = $request->city;
                $attendance->checkoutTime = date("Y-m-d h:i:s");
                $attendance->coCheck = 1;

                if ($attendance->save()) {
                    return response()->json(['success' => 'Check-Out'], 200);
                } else {
                    return response()->json(['failed' => 'Check-Out'], 200);
                }
            } else {
                return response()->json(['success' => "Already Check-Out"], 200);
            }
        }else{
            return response()->json(['success' => "You not Check-In"], 200);
        }
    }

    public function checkin(Request $request)
    {
        $user=Auth::user()->id;
        $date=date("Y-m-d");
        $attand=DB::select('SELECT COUNT(*) AS num FROM attendance WHERE checkinTime LIKE \''.$date.'%\' AND staffId='.$user);

        $attand=json_encode($attand);
        $attand=json_decode($attand);
        if($attand[0]->num==0) {
            $uploadFolder = 'attendance';
            $image ="";

            $image = $request->file('image');
            $image_uploaded_path="";
            $mime="";
            if($request->hasFile('image')){
            $image_uploaded_path = $image->store($uploadFolder);
                $mime=$image->getClientMimeType();
            }
            $uploadedImageResponse = array(
                "image_name" => basename($image_uploaded_path),
                "image_url" => $image_uploaded_path,
                "mime" => $mime
            );
            if (empty($uploadedImageResponse['image_name'])) {
               // return response()->json(['failed' => 'Take photo again'], 200);
                $uploadedImageResponse['image_name']="";
            }
            $attendance=new Attendance();
            $attendance->staffId=$user;
            $attendance->fileName=$uploadedImageResponse['image_name'];
            $attendance->latitude=$request->lat;
            $attendance->longitude=$request->long;
            $attendance->cityName=$request->city;
            $attendance->checkinTime=date("Y-m-d h:i:s");
            $attendance->coCheck=0;
            $attendance->save();

            return response()->json(['success' => 'Check-In'], 200);
        }else{
            return response()->json(['success' =>"Already Check-In" ], 200);
      }
}


}
