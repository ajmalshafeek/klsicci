<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Clientcomplaint;
use Laravel\Ui\Presets\React;

class Complaint extends Controller
{

    /**
     * Seacrh by name
     *
     * @param  str $keyword
     * @return \Illuminate\Http\Response
     */ 
    public function search($keyword) {

        // Clientcomplaint::where(function ($query) use ($keyword) {
        //     // $keyword = $request->input('keyword');

        //     // $query->where('issueName', 'like', '%' . $keyword . '%')
        //     //       ->orWhere('issueDetail', 'like', '%' . $keyword . '%')
        //     //       ->orWhere('picName', 'like', '%' . $keyword . '%');
        // });

        return Clientcomplaint::where('issueName', 'like', '%' . $keyword . '%')
                  ->orWhere('issueDetail', 'like', '%' . $keyword . '%')
                  ->orWhere('picName', 'like', '%' . $keyword . '%')
                  ->get();
    }
}
