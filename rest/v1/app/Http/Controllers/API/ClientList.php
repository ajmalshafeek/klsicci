<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; 

class ClientList extends Controller
{
    public $successStatus = 200;

    /**
     * client list
     * 
     * @return \Illuminate\Http\Response
     */
    public function client() {
        $user = Auth::user();
        return response()->json(['success' => $user], $this-> successStatus); 
    } 
    public function clientlist() {
        try{
            return $client = DB::select('SELECT * FROM clientcompany');
            $data = DB::table('clientcompany')->get();
            
            return $data;
        }
        catch (Exception $e) {
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }
}
