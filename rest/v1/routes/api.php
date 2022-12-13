<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//use App\Models\Job;
use App\Http\Controllers\API\Users;
use App\Http\Controllers\API\Jobs;
use App\Http\Controllers\API\Attendances;
use App\Http\Controllers\API\ClientList;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['cors', 'json.response']], function () {

    Route::post('login', [Users::class, 'login']);
    Route::middleware('auth:organizationuser-api')->group(function () {
        Route::post('logout', [Users::class, 'logout']);
        Route::get('users', [Users::class, 'userInfo']);
        Route::post('detail', [Users::class, 'userlist']);
// attendance
        Route::post('attendance', [Attendances::class, 'view']);
        Route::post('attendance/checkin', [Attendances::class, 'checkin']);
        Route::post('attendance/checkout', [Attendances::class, 'checkout']);

//view client
        Route::get('client/list', [ClientList::class, 'clientlist']);
//view all task
        Route::get('jobsheet/list', [Jobs::class, 'index']);
//add new task
        Route::post('jobsheet/create', [Jobs::class, 'makeIncident']);

        Route::post('jobsheet/assign', [Jobs::class, 'assignTask']);

//search by task name & description
        Route::get('jobsheet/searchTask/{keyword}', [Jobs::class, 'searchTask']);

//filter by task status (complete/incomplete)
        Route::get('jobsheet/statusFilter/{status}', [Jobs::class, 'statusFilter']);

// JobSheet Module
        Route::post('jobsheet/mytask', [Jobs::class, 'mytask']);
        Route::post('jobsheet/mytask/{id}', [Jobs::class, 'task']);
        Route::post('jobsheet/mytask/update/{id}', [Jobs::class, 'update']);

    });
});
