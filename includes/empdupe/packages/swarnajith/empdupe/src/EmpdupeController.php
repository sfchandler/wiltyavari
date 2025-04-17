<?php

namespace Swarnajith\Empdupe;


use App\Payer;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class EmpdupeController extends Controller
{

    public function generate(){
        $generator = new EmpdupeGenerator();
        $data = $generator->generate();
        $fileName = 'EMPDUPE'.'.A01';
        $filePath = './empdupefile/'.$fileName;
        if (!file_exists(public_path().$filePath)) {
            //mkdir($filePath, 0777);
            //mkdir(public_path().'/empdupefile',0777,true);
            $empdupeFile = $filePath;
        }else{
            $empdupeFile = $filePath;
        }
        file_put_contents($empdupeFile, $data);
        $len = strlen($data);
        $remainder = fmod(strlen($data),628);
        $result = $data;
        if($remainder == 0) {
            return view('Empdupe::generate', compact('result'));
        }else{
            return view('Empdupe::generate', compact('result'));
        }

    }

}
