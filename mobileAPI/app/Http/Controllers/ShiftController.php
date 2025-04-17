<?php

namespace App\Http\Controllers;
use App\Mail\EmailGenerator;
use App\ReleaseShift;
use App\ReleaseShiftLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Shift;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

date_default_timezone_set('Australia/Melbourne');

class ShiftController extends Controller
{
    public function index(){
        return Shift::all();
    }

    function rangeWeek(string $start,string $end): array{
        $dtStart = date_create($start);
        $dtEnd = date_create($end);
        $weeks = [];
        while($dtStart <= $dtEnd){
            $weeks[] = [
                'start' => $dtStart->format('Y-m-d'),
                'end' => min($dtEnd,$dtStart->modify('Sunday this week'))->format('Y-m-d'),
                'week' => $dtStart->format('W')
            ];
            $dtStart->modify('next Monday');
        }
        return $weeks;
    }
    public function shiftByDate($candidateId,$shiftDate){
        return DB::table('shift')
            ->leftJoin('shift_address', 'shift_address.id', '=', 'shift.addressId')
            ->leftJoin('client', 'client.clientId', '=', 'shift.clientId')
            ->leftJoin('department', 'department.deptId', '=', 'shift.departmentId')
            ->select('client.client','department.department','shift.*','shift_address.*')
            ->where('candidateId','=',$candidateId)
            ->where('shiftDate','=',$shiftDate)->orderBy('shiftDate','asc')->get();
    }
    public function shiftTestDate($candidateId,$shiftDate){
        $dbResult = DB::table('shift')
            ->leftJoin('shift_address', 'shift_address.id', '=', 'shift.addressId')
            ->leftJoin('client', 'client.clientId', '=', 'shift.clientId')
            ->leftJoin('department', 'department.deptId', '=', 'shift.departmentId')
            ->select('client.client','department.department','shift.*','shift_address.*')
            ->where('candidateId','=',$candidateId)
            ->where('shiftDate','=',$shiftDate)->orderBy('shiftDate','asc')->get();
        //return response()->json(['data'=>$dbResult],201);
        return response()->header(array('headerData'=>$candidateId));
    }
    public function shiftStatusUpdate(Request $request){
        try {
            DB::table('shift')
                ->where('shiftId', $request->shiftId)
                ->update(['shiftStatus' => $request->shiftStatus]);
        }catch (\Exception $e){
            return response()->json(['data' => $e->getMessage()], 201);
        }
        if($request->shiftStatus == 'REJECTED') {
            $shiftData = json_decode($this->getShiftInformation($request->shiftId), true);
            foreach ($shiftData as $key => $value) {
                $shiftDate = $value['shiftDate'];
                $client = $value['client'];
                $department = $value['department'];
                $firstName = $value['firstName'];
                $lastName = $value['lastName'];
                $candidateId = $value['candidateId'];
            }
            $subject = $firstName . ' ' . $lastName . ' shift for ' . $shiftDate . ' is ' . $request->shiftStatus;
            $mailText = 'Shift at ' . $client . ' ' . $department . ' for ' . $shiftDate . ' of ' . $candidateId . ' ' . $firstName . ' ' . $lastName . ' has been ' . $request->shiftStatus;
            $this->updateUserActivityLog($firstName.' '.$lastName,'MOBILE_APP_SHIFT_REJECTION',$request->shiftId,$mailText );
        }elseif ($request->shiftStatus == 'CONFIRMED'){
            $shiftData = json_decode($this->getShiftInformation($request->shiftId), true);
            foreach ($shiftData as $key => $value) {
                $shiftDate = $value['shiftDate'];
                $client = $value['client'];
                $department = $value['department'];
                $firstName = $value['firstName'];
                $lastName = $value['lastName'];
                $candidateId = $value['candidateId'];
            }
            $activityDetail = 'Shift at ' . $client . ' ' . $department . ' for ' . $shiftDate . ' of ' . $candidateId . ' ' . $firstName . ' ' . $lastName . ' has been ' . $request->shiftStatus;
            $this->updateUserActivityLog($firstName.' '.$lastName,'MOBILE_APP_SHIFT_CONFIRMATION',$request->shiftId,$activityDetail);
        }
        return response()->json(['data' => 'shift status updated'], 201);
    }
    public function shiftByDateRange($candidateId,$startDate,$endDate){
        $shiftRange = DB::table('shift','shift_address')
            ->leftJoin('shift_address','shift_address.id','=', 'shift.addressId')
            ->leftJoin('client', 'client.clientId', '=', 'shift.clientId')
            ->leftJoin('department', 'department.deptId', '=', 'shift.departmentId')
            ->select('client.client','department.department','shift.*','shift_address.*')
            ->where('candidateId','=',$candidateId)
            ->whereBetween('shiftDate',array($startDate,$endDate))
            ->orderBy('shiftDate','asc')
            ->get();
        $shiftSet = DB::table('shift','shift_address')
            ->leftJoin('shift_address','shift_address.id','=', 'shift.addressId')
            ->leftJoin('client', 'client.clientId', '=', 'shift.clientId')
            ->leftJoin('department', 'department.deptId', '=', 'shift.departmentId')
            ->select('client.client','department.department','shift.*','shift_address.*')
            ->where('candidateId','=',$candidateId)
            ->whereBetween('shiftDate',array($startDate,$endDate))
            ->orderBy('shiftDate','asc')
            ->pluck('shiftId')->toArray();

        $startYesterday = date('Y-m-d', strtotime('-1 days', strtotime($startDate)));
        $endYesterday = date('Y-m-d', strtotime('-1 days', strtotime($endDate)));

        $timeclockData = DB::table('timeclock')
            ->leftJoin('shift', 'shift.shiftId', '=', 'timeclock.shiftId')
            ->leftJoin('shift_address', 'shift_address.id', '=', 'shift.addressId')
            ->leftJoin('client', 'client.clientId', '=', 'timeclock.clientId')
            ->leftJoin('department', 'department.deptId', '=', 'timeclock.deptId')
            ->select('timeclock.shiftId')
            ->where('timeclock.checkIn', '!=', '00:00')
            ->where('timeclock.checkOut', '=', '00:00')
            ->whereBetween('timeclock.shiftDate',array($startYesterday,$endYesterday))
            ->where('timeclock.candidateId', '=', $candidateId)
            ->pluck('shiftId')->toArray();

        $arrayData = array_merge(array_diff($shiftSet, $timeclockData),array_intersect($shiftSet, $timeclockData), array_diff($timeclockData, $shiftSet));

        $shiftTimeClock = DB::table('shift')
            ->leftJoin('shift_address','shift_address.id','=', 'shift.addressId')
            ->leftJoin('client', 'client.clientId', '=', 'shift.clientId')
            ->leftJoin('department', 'department.deptId', '=', 'shift.departmentId')
            ->select('client.client','department.department','shift.*','shift_address.*')
            ->whereIn('shift.shiftId',$arrayData)
            ->orderBy('shiftDate','asc')
            ->get();

        return $shiftTimeClock;
    }
    public function timeclockByDateRange($candidateId,$startDate,$endDate){
        $shiftRange = DB::table('shift')
            ->leftJoin('shift_address','shift_address.id','=', 'shift.addressId')
            ->leftJoin('client', 'client.clientId', '=', 'shift.clientId')
            ->leftJoin('department', 'department.deptId', '=', 'shift.departmentId')
            ->select('client.client','department.department','shift.*','shift_address.*')
            ->where('candidateId','=',$candidateId)
            ->whereBetween('shiftDate',array($startDate,$endDate))
            ->orderBy('shiftDate','asc')
            ->get();

        $startYesterday = date('Y-m-d', strtotime('-1 days', strtotime($startDate)));
        $endYesterday = date('Y-m-d', strtotime('-1 days', strtotime($endDate)));

        $timeclockData = DB::table('timeclock')
            ->leftJoin('shift', 'shift.shiftId', '=', 'timeclock.shiftId')
            ->leftJoin('shift_address', 'shift_address.id', '=', 'shift.addressId')
            ->leftJoin('client', 'client.clientId', '=', 'timeclock.clientId')
            ->leftJoin('department', 'department.deptId', '=', 'timeclock.deptId')
            ->select('client.client', 'department.department', 'shift.*', 'shift_address.*', 'timeclock.*')
            ->where('timeclock.checkIn', '!=', '00:00')
            ->where('timeclock.checkOut', '=', '00:00')
            ->whereBetween('timeclock.shiftDate',array($startYesterday,$endYesterday))
            ->where('timeclock.candidateId', '=', $candidateId)
            ->get();
        $mergedData = $shiftRange->merge($timeclockData);
        return $mergedData;
    }
    public function getShiftInformation($shiftId){
        return DB::table('shift')
            ->leftJoin('candidate','shift.candidateId','=','candidate.candidateId')
            ->leftJoin('client', 'client.clientId', '=', 'shift.clientId')
            ->leftJoin('department', 'department.deptId', '=', 'shift.departmentId')
            ->select('shift.shiftDate','client.client','department.department','candidate.firstName','candidate.lastName','candidate.candidateId')
            ->where('shiftId','=',$shiftId)->get();
    }
    public function getShiftInformationForExtra($shiftId){
        $shiftData = DB::table('shift')
            ->leftJoin('candidate','shift.candidateId','=','candidate.candidateId')
            ->leftJoin('client', 'client.clientId', '=', 'shift.clientId')
            ->leftJoin('department', 'department.deptId', '=', 'shift.departmentId')
            ->leftJoin('shift_address', 'shift_address.id', '=', 'shift.addressId')
            ->select('candidate.candidateId','candidate.firstName','candidate.lastName','shift.shiftId','shift.shiftDate','shift.shiftDay','shift.clientId','shift.stateId','shift.departmentId','shift.shiftStart','shift.shiftEnd','shift.workBreak','shift.wrkhrs','shift.shiftStatus','shift.positionId','client.client','shift_address.latitude','shift_address.longitude','shift_address.location_check','department.deptId','department.department')
            ->where('shiftId','=',$shiftId)->get();
        $jsonOutput = array(
            "shiftData" => $shiftData
        );
        return json_encode($jsonOutput);

    }

    public function updateUserActivityLog($userName,$activityType,$shiftId,$activityDetail) {
        try {
            $timeClock = DB::table('user_activity_log')
                ->insert(['username' => $userName,
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'page' => 'MOBILE_APP',
                    'shiftId' => $shiftId,
                    'activity_type' => $activityType,
                    'activity_detail' => $activityDetail,
                    'log_time' => date('Y-m-d H:i:s')]);
        }catch (\Exception $e){
            return response()->json(['data' => $e->getMessage()], 201);
        }
    }
    public function validateCheckIn($shiftId)
    {
        $result = DB::table('timeclock')
            ->select('timeclock.checkIn')
            ->where('shiftId','=', $shiftId)
            ->where('checkIn','!=','00:00')
            ->get();
        if(count($result)>0){
            return true;
        }else{
            return false;
        }
    }
    public function validateCheckOut($shiftId)
    {
        $result = DB::table('timeclock')
            ->select('timeclock.checkIn')
            ->where('shiftId','=', $shiftId)
            ->where('checkIn','!=','00:00')
            ->where('checkOut','!=','00:00')
            ->get();
        if(count($result)>0){
            return true;
        }else{
            return false;
        }
    }
    public function shiftByDateExtra($candidateId,$shiftDate){
        $yesterday = date('Y-m-d', strtotime('-1 days', strtotime($shiftDate)));

        $confirmedShifts = DB::table('shift')
            ->leftJoin('shift_address', 'shift_address.id', '=', 'shift.addressId')
            ->leftJoin('client', 'client.clientId', '=', 'shift.clientId')
            ->leftJoin('department', 'department.deptId', '=', 'shift.departmentId')
            ->select('client.client','department.department','shift.*','shift_address.*')
            ->where('candidateId','=',$candidateId)
            ->where('shiftDate','=',$shiftDate)
            ->where('shiftStatus','=','CONFIRMED')
            ->orderBy('shiftDate','asc')->get();
        $pendingShifts = DB::table('shift')
            ->leftJoin('shift_address', 'shift_address.id', '=', 'shift.addressId')
            ->leftJoin('client', 'client.clientId', '=', 'shift.clientId')
            ->leftJoin('department', 'department.deptId', '=', 'shift.departmentId')
            ->select('client.client','department.department','shift.*','shift_address.*')
            ->where('candidateId','=',$candidateId)
            ->where('shiftDate','=',$shiftDate)
            ->where('shiftStatus','=','OPEN')
            ->orderBy('shiftDate','asc')->get();

        $pendingCheckOut = DB::table('timeclock')
            ->join('shift', 'shift.shiftId', '=', 'timeclock.shiftId')
            ->leftJoin('shift_address', 'shift_address.id', '=', 'shift.addressId')
            ->leftJoin('client', 'client.clientId', '=', 'timeclock.clientId')
            ->leftJoin('department', 'department.deptId', '=', 'timeclock.deptId')
            ->select('client.client', 'department.department', 'shift.*', 'shift_address.*', 'timeclock.*')
            ->where('timeclock.checkIn', '!=', '00:00')
            ->where('timeclock.checkOut', '=', '00:00')
            ->where('timeclock.shiftDate', '=', $yesterday)
            ->where('timeclock.candidateId', '=', $candidateId)
            ->get();

        $availabilities = DB::table('shift_availability')
            ->select('shift_availability.shift_date','shift_availability.am','shift_availability.pm','shift_availability.night')
            ->where('candidateId', '=', $candidateId)
            ->where('shift_date','=',$shiftDate)
            ->get();
        $jsonOutput = array(
            "success"=>"1",
            "errors"=>array("status"=>200,"message"=>""),
            "pendingShifts" => $pendingShifts,
            "confirmedShifts" => $confirmedShifts,
            "pendingCheckOut"=> $pendingCheckOut,
            "shiftAvailability" => $availabilities
        );
        return json_encode($jsonOutput);
    }
    public function test($candidateId,$shiftDate){
        $yesterday = date('Y-m-d', strtotime('-1 days', strtotime($shiftDate)));

        $confirmedShifts = DB::table('shift')
            ->leftJoin('shift_address', 'shift_address.id', '=', 'shift.addressId')
            ->leftJoin('client', 'client.clientId', '=', 'shift.clientId')
            ->leftJoin('department', 'department.deptId', '=', 'shift.departmentId')
            ->select('client.client','department.department','shift.*','shift_address.*')
            ->where('candidateId','=',$candidateId)
            ->where('shiftDate','=',$shiftDate)
            ->where('shiftStatus','=','CONFIRMED')
            ->orderBy('shiftDate','asc')->get();
        $pendingShifts = DB::table('shift','shift_address','client','department')
            ->leftJoin('shift_address', 'shift_address.id', '=', 'shift.addressId')
            ->leftJoin('client', 'client.clientId', '=', 'shift.clientId')
            ->leftJoin('department', 'department.deptId', '=', 'shift.departmentId')
            ->select('client.client','department.department','shift.*','shift_address.*')
            ->where('candidateId','=',$candidateId)
            ->where('shiftDate','=',$shiftDate)
            ->where('shiftStatus','=','OPEN')
            ->orderBy('shiftDate','asc')->get();

        $pendingCheckOut = DB::table('timeclock')
            ->leftJoin('shift', 'shift.shiftId', '=', 'timeclock.shiftId')
            ->leftJoin('shift_address', 'shift_address.id', '=', 'shift.addressId')
            ->leftJoin('client', 'client.clientId', '=', 'timeclock.clientId')
            ->leftJoin('department', 'department.deptId', '=', 'timeclock.deptId')
            ->select('client.client', 'department.department', 'shift.*', 'shift_address.*', 'timeclock.*')
            ->where('timeclock.checkIn', '!=', '00:00')
            ->where('timeclock.checkOut', '=', '00:00')
            ->where('timeclock.shiftDate', '=', $yesterday)
            ->where('timeclock.candidateId', '=', $candidateId)
            ->get();

        $availabilities = DB::table('shift_availability')
            ->select('shift_availability.shift_date','shift_availability.am','shift_availability.pm','shift_availability.night')
            ->where('candidateId', '=', $candidateId)
            ->where('shift_date','=',$shiftDate)
            ->get();
        $jsonOutput = array(
            "success"=>"1",
            "errors"=>array("status"=>200,"message"=>""),
            "pendingShifts" => $pendingShifts,
            "confirmedShifts" => $confirmedShifts,
            "pendingCheckOut" => $pendingCheckOut,
            "shiftAvailability" => $availabilities
        );
        return json_encode($jsonOutput);
    }
    public function shiftByDateRangeExtra($candidateId,$startDate,$endDate){
        $confirmedShifts = DB::table('shift')
            ->leftJoin('shift_address', 'shift_address.id', '=', 'shift.addressId')
            ->leftJoin('client', 'client.clientId', '=', 'shift.clientId')
            ->leftJoin('department', 'department.deptId', '=', 'shift.departmentId')
            ->select('client.client','department.department','shift.*','shift_address.*')
            ->where('candidateId','=',$candidateId)
            ->whereBetween('shiftDate',array($startDate,$endDate))
            ->where('shift.shiftStatus','=','CONFIRMED')
            ->orderBy('shiftDate','asc')->get();
        $weeks = $this->rangeWeek($startDate,$endDate);
        $shAv = array();
        foreach($weeks as $week){
            $shiftsArray = array();
            foreach ($confirmedShifts as $op){
                if( ($op->shiftDate >= date('Y-m-d',strtotime($week['start'])))  && ($op->shiftDate <= date('Y-m-d',strtotime($week['end']))))
                {
                    $shiftsArray[] = $op;
                }
            }
            if(!empty($shiftsArray)) {
                $shAv[] = array("week" => $week['start'] . ' - ' . $week['end'], "shifts"=>$shiftsArray);
            }
        }
        $jsonOutput = array(
            "success"=>"1",
            "errors"=> array("status"=>200,"message"=>""),
            "confirmedAvailability" => $shAv
        );
        return json_encode($jsonOutput);
    }
    public function pendingShiftByDateExtra($candidateId,$startDate,$endDate){
        $openShifts = DB::table('shift')
            ->leftJoin('shift_address', 'shift_address.id', '=', 'shift.addressId')
            ->leftJoin('client', 'client.clientId', '=', 'shift.clientId')
            ->leftJoin('department', 'department.deptId', '=', 'shift.departmentId')
            ->select('client.client','department.department','shift.*','shift_address.*')
            ->where('candidateId','=',$candidateId)
            ->whereBetween('shiftDate',array($startDate,$endDate))
            ->where('shift.shiftStatus','=','OPEN')
            ->orderBy('shiftDate','asc')->get();
        $weeks = $this->rangeWeek($startDate,$endDate);
        $shAv = array();
        foreach($weeks as $week){
            $shiftsArray = array();
            foreach ($openShifts as $op){
                if( ($op->shiftDate >= date('Y-m-d',strtotime($week['start'])))  && ($op->shiftDate <= date('Y-m-d',strtotime($week['end']))))
                {
                    $shiftsArray[] = $op;
                }
            }
            if(!empty($shiftsArray)) {
                $shAv[] = array("week" => $week['start'] . ' - ' . $week['end'], "shifts"=>$shiftsArray);
            }
        }
        $jsonOutput = array(
            "success"=>"1",
            "errors"=> array("status"=>200,"message"=>""),
            "openShifts" => $shAv
        );
        return json_encode($jsonOutput);
    }

    public function releasedShiftsByCandidate($candidateId){
        try {
            $releasedShiftLog = ReleaseShiftLog::select('rel_shift_id')->where('rel_shift_status','=','PENDING APPROVAL')->pluck('rel_shift_id');
            $releasedShifts = DB::table('release_shift')
                ->leftJoin('client', 'client.clientId', '=', 'release_shift.rel_client_id')
                ->leftJoin('department', 'department.deptId', '=', 'release_shift.rel_dept_id')
                ->leftJoin('states', 'states.stateId', '=', 'release_shift.rel_state_id')
                ->leftJoin('candidate_position','candidate_position.positionId','=','release_shift.rel_position_id')
                ->leftJoin('shift_address', 'shift_address.id', '=', 'release_shift.rel_address_id')
                ->select('client.client',
                    'department.department',
                    'states.state',
                    'candidate_position.positionName',
                    'release_shift.rel_shift_id',
                    'release_shift.rel_shift_date',
                    'release_shift.rel_shift_day',
                    'release_shift.rel_client_id',
                    'release_shift.rel_state_id',
                    'release_shift.rel_dept_id',
                    'release_shift.rel_position_id',
                    'release_shift.rel_shift_start',
                    'release_shift.rel_shift_end',
                    'release_shift.rel_shift_break',
                    'release_shift.rel_shift_status',
                    'release_shift.rel_address_id',
                    'shift_address.address',
                    'shift_address.street',
                    'shift_address.city',
                    'shift_address.sub',
                    'shift_address.country',
                    'shift_address.postalCode',
                    'shift_address.latitude',
                    'shift_address.longitude',
                    'shift_address.location_check')
                ->where('release_shift.rel_shift_status','=','RELEASED')
                ->whereRaw("find_in_set('".$candidateId."',release_shift.candidates)")
                ->whereNotIn('release_shift.rel_shift_id',$releasedShiftLog)
                ->orderBy('release_shift.rel_shift_id','desc')->get();
            $jsonOutput = array(
                "success"=>"1",
                "errors"=> array("status"=>200,"message"=>""),
                "releasedShifts" => $releasedShifts
            );
            return json_encode($jsonOutput);
        }catch (\Exception $e){
            $error = $e->getMessage();
            Log::error('release shift error '.$error);
        }
    }
    public function releasedShiftsByIDAndCandidate($rel_shift_id){
        try {
            $releasedShifts = DB::table('release_shift')
                ->leftJoin('client', 'client.clientId', '=', 'release_shift.rel_client_id')
                ->leftJoin('department', 'department.deptId', '=', 'release_shift.rel_dept_id')
                ->leftJoin('states', 'states.stateId', '=', 'release_shift.rel_state_id')
                ->leftJoin('candidate_position','candidate_position.positionId','=','release_shift.rel_position_id')
                ->leftJoin('shift_address', 'shift_address.id', '=', 'release_shift.rel_address_id')
                ->select('client.client',
                    'department.department',
                    'states.state',
                    'candidate_position.positionName',
                    'release_shift.rel_shift_id',
                    'release_shift.rel_shift_date',
                    'release_shift.rel_shift_day',
                    'release_shift.rel_client_id',
                    'release_shift.rel_state_id',
                    'release_shift.rel_dept_id',
                    'release_shift.rel_position_id',
                    'release_shift.rel_shift_start',
                    'release_shift.rel_shift_end',
                    'release_shift.rel_shift_break',
                    'release_shift.rel_shift_status',
                    'release_shift.rel_address_id',
                    'shift_address.address',
                    'shift_address.street',
                    'shift_address.city',
                    'shift_address.sub',
                    'shift_address.country',
                    'shift_address.postalCode',
                    'shift_address.latitude',
                    'shift_address.longitude',
                    'shift_address.location_check')
                ->where('release_shift.rel_shift_status','=','RELEASED')
                ->where('release_shift.rel_shift_id','=',$rel_shift_id)
                ->orderBy('rel_shift_id','desc')->get();

            $jsonOutput = array(
                "success"=>"1",
                "errors"=> array("status"=>200,"message"=>""),
                "shiftReleased" => $releasedShifts
            );
            return json_encode($jsonOutput);
        }catch (\Exception $e){
            $error = $e->getMessage();
            Log::error('release shift error '.$error);
        }
    }
    public function actionedReleasedShifts($candidateId){
        try {
            $releasedShifts = DB::table('release_shift')
                ->join('release_shift_log','release_shift_log.rel_shift_id', '=','release_shift.rel_shift_id')
                ->leftJoin('client', 'client.clientId', '=', 'release_shift.rel_client_id')
                ->leftJoin('department', 'department.deptId', '=', 'release_shift.rel_dept_id')
                ->leftJoin('states', 'states.stateId', '=', 'release_shift.rel_state_id')
                ->leftJoin('candidate_position','candidate_position.positionId','=','release_shift.rel_position_id')
                ->leftJoin('shift_address', 'shift_address.id', '=', 'release_shift.rel_address_id')
                ->select('client.client',
                    'department.department',
                    'states.state',
                    'candidate_position.positionName',
                    'release_shift.rel_shift_id',
                    'release_shift.rel_shift_date',
                    'release_shift.rel_shift_day',
                    'release_shift.rel_client_id',
                    'release_shift.rel_state_id',
                    'release_shift.rel_dept_id',
                    'release_shift.rel_position_id',
                    'release_shift.rel_shift_start',
                    'release_shift.rel_shift_end',
                    'release_shift.rel_shift_break',
                    'release_shift.rel_address_id',
                    'release_shift_log.rel_shift_status',
                    'shift_address.address',
                    'shift_address.street',
                    'shift_address.city',
                    'shift_address.sub',
                    'shift_address.country',
                    'shift_address.postalCode',
                    'shift_address.latitude',
                    'shift_address.longitude',
                    'shift_address.location_check')
                ->where('release_shift_log.candidate_id',$candidateId)
                ->where('release_shift.rel_shift_status','!=','CONFIRMED')
                ->orderBy('rel_shift_id','desc')->get();
            $jsonOutput = array(
                "success"=>"1",
                "errors"=> array("status"=>200,"message"=>""),
                "actionedReleasedShifts" => $releasedShifts
            );
            return json_encode($jsonOutput);
        }catch (\Exception $e){
            $error = $e->getMessage();
            Log::error('release shift error '.$error);
        }
    }
    public function releaseShiftStatusUpdate(Request $request)
    {
        try {
            $message = '';
            if($request->rel_shift_status == 'REJECTED') {
                $message = 'shift rejected';
                $releasedShiftLog = new ReleaseShiftLog();
                $releasedShiftLog->rel_shift_status = 'REJECTED';
                $releasedShiftLog->rel_shift_id = $request->rel_shift_id;
                $releasedShiftLog->candidate_id = $request->candidate_id;
                $releasedShiftLog->created_at = date('Y-m-d H:i:s');
                $releasedShiftLog->save();
            }elseif ($request->rel_shift_status == 'PENDING APPROVAL'){
                $releasedShiftInfo = ReleaseShift::findOrFail($request->rel_shift_id);
                if(!$this->validateCandidateReleaseShiftEntryByDate($releasedShiftInfo->rel_shift_date,$request->candidate_id)) {
                    if (!$this->validateShift($releasedShiftInfo->rel_shift_date, $releasedShiftInfo->rel_client_id, $releasedShiftInfo->rel_dept_id, $releasedShiftInfo->rel_state_id, $releasedShiftInfo->rel_position_id, $releasedShiftInfo->rel_shift_start, $releasedShiftInfo->rel_shift_end)) {
                        if (!$this->validateReleaseShiftLogAcceptance($request->rel_shift_id, $request->rel_shift_status)) {
                            try {
                                DB::beginTransaction();
                                $releasedShiftLog = new ReleaseShiftLog();
                                $releasedShiftLog->rel_shift_status = $request->rel_shift_status;
                                $releasedShiftLog->rel_shift_id = $request->rel_shift_id;
                                $releasedShiftLog->candidate_id = $request->candidate_id;
                                $releasedShiftLog->created_at = date('Y-m-d H:i:s');
                                $releasedShiftLog->save();
                                DB::commit();
                                $message = 'shift pending approval';
                            } catch (\Exception $e) {
                                Log::error('release shift insert error ' . $e->getMessage());
                                DB::rollBack();
                            }
                        } else {
                            $message = 'shift already accepted/expired';
                        }
                    } else {
                        $message = 'shift already accepted/expired';
                    }
                }else{
                    $message = 'Accepted/Rejected released shifts exists for same date';
                }
            }
            $jsonOutput = array(
                "success"=>"1",
                "errors"=> array("status"=>200,"message"=>""),
                "message" => $message
            );
            return json_encode($jsonOutput);
        }catch (\Exception $e){
            return response()->json(['data' => $e->getMessage()], 201);
        }
    }
    public function validateShift($shiftDate,$clientId,$deptId,$stateId,$positionId,$shiftStart,$shiftEnd)
    {
        $result = DB::table('shift')
            ->select('shift.shiftId')
            ->where('shiftDate','=', $shiftDate)
            ->where('clientId','=',$clientId)
            ->where('stateId','=',$stateId)
            ->where('departmentId','=',$deptId)
            ->where('positionId','=',$positionId)
            ->where('shiftStart','=',$shiftStart)
            ->where('shiftEnd','=',$shiftEnd)
            ->get();
        if(count($result)>0){
            return true;
        }else{
            return false;
        }
    }
    public function validateReleaseShiftLogAcceptance($rel_shift_id,$shiftStatus)
    {
        $result = DB::table('release_shift_log')
            ->select('rel_shift_status')
            ->where('rel_shift_id','=', $rel_shift_id)
            ->where('rel_shift_status','=',$shiftStatus)
            ->get();
        if(count($result)>0){
            return true;
        }else{
            return false;
        }
    }
    public function validateCandidateReleaseShiftEntryByDate($rel_shift_date,$candidate_id)
    {
        $rel_status = 'CONFIRMED';
        $rel_removed_status = 'REMOVED';
        $result = DB::table('release_shift')
            ->join('release_shift_log','release_shift_log.rel_shift_id', '=','release_shift.rel_shift_id')
            ->select('release_shift.rel_shift_date')
            ->where('release_shift.rel_shift_date','=', $rel_shift_date)
            ->where('release_shift_log.candidate_id','=',$candidate_id)
            ->where('release_shift.rel_shift_status','!=',$rel_status)
            ->where('release_shift.rel_shift_status','!=',$rel_removed_status)
            ->get();
        if(count($result)>0){
            return true;
        }else{
            return false;
        }
    }
}
