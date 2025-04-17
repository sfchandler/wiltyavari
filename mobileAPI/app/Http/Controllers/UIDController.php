<?php

namespace App\Http\Controllers;

use garethp\ews\API\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UIDController extends Controller
{
    public function update(Request $request){
        try {
            $uidCount = DB::table('uid_container')->where('candidateId','=',$request->candidateId)->count();
            if($uidCount > 0){
                DB::table('uid_container')
                    ->where('candidateId',$request->candidateId)
                    ->update(['uid'=>$request->uid,'device_os'=>$request->device_os,'os_version'=>$request->os_version]);
                return response()->json(['data' => 'uid data updated.'], 201);
            }else{
                DB::table('uid_container')->insert(['candidateId'=>$request->candidateId,'uid'=>$request->uid,'device_os'=>$request->device_os,'os_version'=>$request->os_version]);
                return response()->json(['data' => 'uid data inserted.'], 201);
            }
        }catch (\Exception $e){
            return response()->json(['data' => 'uid update error'], 500);
        }
    }
}
