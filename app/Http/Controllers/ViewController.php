<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ViewController extends Controller
{
    function token(){
        try {
            return response()->json(['token'=>csrf_token()]);
            
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
