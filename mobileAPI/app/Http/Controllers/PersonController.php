<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Person;

class PersonController extends Controller
{
    public function create(Request $request){
        try{
            $firstName = $request->firstName;
            $lastName = $request->lastName;
            $gender = $request->gender;
            $phoneNumber = $request->phoneNumber;
            $email = $request->email;
            $address = $request->address;

            if(!$this->validatePerson($email,$phoneNumber)) {
                try{
                    DB::table('person')
                        ->insert(['firstName'=>$firstName,'lastName'=>$lastName,'gender'=>$gender,'phoneNumber'=>$phoneNumber,'email'=>$email,'address'=>$address]);
                    return response()->json(['response' => 'request submitted'], 201);
                }catch (\Exception $e1){
                    return response()->json(['response' => 'error saving to database'], 501);
                }
            }else{
                $error = 'record exists';
                return response()->json(['response' => $error], 501);
            }
        }catch (\Exception $e){
            $error = $e->getMessage();
            return response()->json(['response' => $error], 501);
        }
    }
    private function validatePerson($email,$phoneNumber){
        $result =  DB::table('person')
            ->where('email','=',$email)
            ->where('phoneNumber','=',$phoneNumber)
            ->get();
        if(count($result)>0){
            return true;
        }else{
            return false;
        }
    }
}
