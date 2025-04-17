<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function username()
    {
        return 'username';
    }
    public function login(Request $request)
    {
        try {
            $this->validateLogin($request);
            if ($this->attemptLogin($request)) {
                $user = $this->guard()->user();
                $user->generateToken();
                Log::debug('Generated token :'.$user.'Request'.$request);
                $empStatusCheck = DB::table('candidate')->where('username',$user['username'])->where('empStatus','ACTIVE')->count();
                if($empStatusCheck>0){
                    return response()->json([
                        'data' => $user,
                    ]);
                }else{
                    return response()->json([
                        'data' => 'Invalid username or password',
                    ]);
                }
            }else{
                return response()->json([
                    'data' => 'Invalid username or password',
                ]);
            }

            return $this->sendFailedLoginResponse($request);
        }catch (\Exception $e){
            Log::debug('login error :'.$e->getMessage());
        }
    }
    public function getCandidateProfileImage($id){
        return DB::table('candidate_document')->where('candidateId', '=', $id)->where('docTypeId','=',17)->first();
    }
    public function appLogin(Request $request)
    {
        $this->validateLogin($request);

        if ($this->attemptLogin($request)) {
            $user = $this->guard()->user();
            $user->generateToken();
            Log::debug('Generated token :'.$user.'Request'.$request);
            $profileImage = $this->getCandidateProfileImage($user['candidateId']);
            $fPath = '';
            if(!empty($profileImage->filePath)){
                $fPath = $profileImage->filePath;
            }
            return response()->json([
                'success'=>"1",
                'message'=>"",
                'user' => array(
                    'profileImage'=>$fPath,
                    'fullName'=>$user['firstName'].' '.$user['lastName'],
                    'candidateNo'=>$user['candidate_no'],
                    'email'=>$user['email'],
                    'userName'=>$user['username'],
                    'message'=>''),
                'apiKey'=>$user['api_token']
            ]);
        }

        return $this->sendFailedLoginResponse($request);
    }
    public function logout(){
        $user = Auth::guard('api')->user();
        if ($user) {
            $user->api_token = null;
            $user->save();
        }
        return response()->json(['data' => 'User logged out.'], 200);
    }
    public function app_logout(){
        $user = Auth::guard('api')->user();
        if ($user) {
            $user->api_token = null;
            $user->save();
        }
        return response()->json(['message' => 'User logged out.'], 200);
    }
}
