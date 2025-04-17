<?php

namespace App\Http\Controllers;
date_default_timezone_set('Australia/Melbourne');
use Illuminate\Http\Request;

class ServerTimeController extends Controller
{
    public function index(){
        $date_info = getdate();
        $date = $date_info['mday'];
        $month = $date_info['mon'];
        $year = $date_info['year'];
        $hour = $date_info['hours'];
        $min = $date_info['minutes'];
        $sec = $date_info['seconds'];
        return response()->json(['ServerTime' => $hour.':'.$min], 201);
    }
}
