<?php
namespace App\Http\Controllers\API;


/*
use Validator;
use Exception;
//use Illuminate\Http\Request;
*/
//use Validator;
//use App\Organizationuser;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Http\Controllers\Controller;

use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

//use Illuminate\Support\Facades\Route;
use App\Models\Organizationuser;
use App\Models\Rolemodules;
use App\Models\User;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;

class Users extends Controller
{

    public $successStatus = 200;

    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    /*
    public function details()
    {

        $user = Auth::user();
        return response()->json(['success' => $user], $this-> successStatus);
    }
    //
    public function userlist(){
        try{
  // return $users = DB::select('SELECT *  FROM organizationuser  WHERE id NOT IN (19, 37, 38)');
    $data = DB::table('organizationuser')
    ->whereNotIn('id', [19, 37, 38])
      ->get();
            return response()->json(['success' => $data], 200);
      return $data; }
     catch (Exception $e) {        // catch (Throwable $e) {
        return response()->json(['error'=>'Unauthorised'], 401);
    }
    }
*/
     /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
   /* public function login(Request $request){
        /*
         * $user = Organizationuser::where('username', $request->username)
        ->where('password', $request->password)
        ->first(); */
     /*   $data = [
            'username' => $request->username,
            'password' => $request->password
        ];
        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
         //     $users = DB::select('SELECT *  FROM organizationuser  WHERE username="'.request('username').'" AND password="'.request('password').'"');
        if(!isset($user)){
            return response()->json(['error'=>'Unauthorised'], 401);
        }
        Auth::login($user);
        $d=Auth::user();

            $success['token'] =  $user->createToken('MyApp')-> accessToken;
            return response()->json(['success' => $success, 'user'=>$d], $this-> successStatus);
      */

       /* if(Auth::attempt(['username' => request('username'), 'password' => request('password')])){
            $user = Auth::user();

            return request('username').''.request('password');

           /* $obj = json_decode($user);
            session(["uid" => $obj->id]);
            return  $request->session()->get('uid');
             */
        /*
            if (password_verify( request('password'), $user->password)) {
                $success['token'] =  $user->createToken('MyApp')-> accessToken;
                return response()->json(['success' => $success], $this-> successStatus);
            }


        }
        else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
        */


    /**
     * Login Req
     */
    public function login(Request $request)
    {
/*       $data = [
            'username' => $request->username,
            'password' => $request->password
        ];

        if (Auth::attempt($data)) {
            $success['token'] = Auth::user()->createToken('Laravel8PassportAuth')->accessToken;
            return response()->json(['success' => $success, 'user'=>auth()->user()], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
*/
        $user = Organizationuser::where('username',$request->username)->where('password',$request->password)->first();
        if(!isset($user)){
            return response()->json(['error'=>'Unauthorised'], 401);
       }
        if ($user) {
           Auth::login($user);
            $modules=Rolemodules::where('roleId',$user->role)->get();

           $success['token'] = Auth::user()->createToken('Laravel8PassportAuth');

            $token=$success['token']->accessToken;
            $tokenId=$success['token']->token->id;

            return response()->json(['success' => $token,'tokenid'=>$tokenId, 'user'=>$user,'modules'=>$modules], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    public function userInfo()
    {
        $user = Organizationuser::get();
        if(sizeof($user)>0){
        return response()->json(['user' => $user], 200);}
        else{
            return response()->json(['error'=>'No User Found'], 401);
        }

    }
    public function logout(){
        if (Auth::check()) {
            Auth::user()->token()->revoke();
            return response()->json(['success' =>'logout_success'],200);
        }else{
            return response()->json(['error' =>'api.something_went_wrong'], 500);
        }
        return response()->json(['success' =>'logout success'],200);
    }
}
