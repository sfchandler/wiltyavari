<?php

namespace App\Http\Controllers;
use App\Shift;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\TimeClock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use DateInterval;
use DatePeriod;
use DateTime;
date_default_timezone_set('Australia/Melbourne');

class TimeClockController extends Controller
{
    function getCurrentTime(){
        $date_info = getdate();
        $date = $date_info['mday'];
        $month = $date_info['mon'];
        $year = $date_info['year'];
        $hour = $date_info['hours'];
        $min = $date_info['minutes'];
        $sec = $date_info['seconds'];
        if(strlen($hour) == 1){
            $hour = '0'.$hour;
        }
        if(strlen($min) == 1){
            $min = '0'.$min;
        }
        return $hour.':'.$min;
    }
    /*function rangeWeek(string $start, string $end): array
    {
        $start     = new DateTime($start);
        $end       = new DateTime($end);
        $interval  = new DateInterval('P1D');
        $period    = new DatePeriod($start, $interval, $end);
        $weeks     = [];
        $oldWeek   = null;
        $weekStart = null;
        foreach ($period as $date) {
            $week = $date->format('W');
            if ($week !== $oldWeek) {
                if (null === $weekStart) {
                    $oldWeek   = $week;
                    $weekStart = $date->format('Y-m-d');
                } else {
                    $weeks[]   = ['start' => $weekStart, 'end' => $date->format('Y-m-d')]; //, 'week' => $week
                    $weekStart = null;
                }
                continue;
            }
        }

        $weeks[] = ['start' => $weekStart, 'end' => $end->format('Y-m-d')];//, 'week' => $week

        return $weeks;
    }*/
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
    function calculateHoursWorked($shiftDate, $shiftStart, $shiftEnd, $workBreak)
    {
        if (strtotime($shiftEnd) < strtotime($shiftStart)) {
            $shiftEndDate = date('Y-m-d', strtotime($shiftDate . ' + 1 day'));
        } else {
            $shiftEndDate = $shiftDate;
        }

        $starttime = strtotime($shiftDate . ' ' . $shiftStart . ':00');
        $endtime = strtotime($shiftEndDate . ' ' . $shiftEnd . ':00');
        $diff = $endtime - $starttime;
        $breaks = $workBreak * 60;
        $hours = ($diff - $breaks) / 60 / 60;

        /*$info = $info . 'clocked in: ' . $shiftDate . ' ' . $shiftStart . ':00' . '>>';
        $info = $info . 'clocked out: ' . $shiftEndDate . ' ' . $shiftEnd . ':00' . '>>';
        $info = $info . 'breaks: ' . $breaks . ' minutes >>';
        $info = $info . 'hours worked: ' . number_format($hours, 2) . ' ';*/
        if($hours >0) {
            return number_format($hours, 2);
        }else{
            return 0;
        }
    }
    function getStateByShiftId($shiftId){
        return $result = DB::table('shift')
            ->select('stateId')
            ->where('shiftId',$shiftId)
            ->value('stateId');
    }
    public function validateClientShiftLocation($clientId)
    {
        $location_check = DB::table('shift_address')
            ->select('shift_address.location_check')
            ->where('shift_address.clientId',$clientId)
            ->value('location_check');
        if($location_check == 'YES'){
            return true;
        }else{
            return false;
        }
    }
    public function store(Request $request)
    {
        $stateId = $this->getStateByShiftId($request->shiftId);
        if($stateId == 4){
            date_default_timezone_set('Australia/Perth');
        }elseif($stateId == 3){
            date_default_timezone_set('Australia/Brisbane');
        }elseif($stateId == 2){
            date_default_timezone_set('Australia/Melbourne');
        }elseif($stateId == 1){
            date_default_timezone_set('Australia/Sydney');
        }elseif($stateId == 5){
            date_default_timezone_set('Australia/Adelaide');
        }
        try {
            if($this->validateClientShiftLocation($request->clientId)){
                if(!empty($request->checkin_latitude) && !empty($request->checkin_longitude)) {
                    $supervicerId = DB::table('candidate')
                        ->select('candidate.supervicerId')
                        ->where('candidateId', '=', $request->candidateId)
                        ->value('supervicerId');
                    $supervisor = DB::table('candidate')
                        ->select('candidate.candidateId')
                        ->where('candidate_no', '=', $supervicerId)
                        ->value('candidateId');
                    $jobCode = DB::table('jobcode')
                        ->select('jobcode.jobCode')
                        ->where('clientId', '=', $request->clientId)
                        ->where('positionid', '=', $request->positionId)
                        ->value('jobCode');

                    $shiftStart = DB::table('shift')
                        ->select('shiftStart')
                        ->where('shiftId', $request->shiftId)
                        ->first();


                    if (($request->shiftDate == date('Y-m-d'))) {
                        $currentTime = $this->getCurrentTime();
                        if ($currentTime == '00:00') {
                            $currentTime = '00:01';
                        }
                        $timeClock = DB::table('timeclock')
                            ->insert(['candidateId' => $request->candidateId,
                                'shiftDate' => $request->shiftDate,
                                'shiftId' => $request->shiftId,
                                'shiftDay' => $request->shiftDay,
                                'clientId' => $request->clientId,
                                'positionId' => $request->positionId,
                                'deptId' => $request->deptId,
                                'jobCode' => $jobCode,
                                'checkIn' => $currentTime,
                                'checkOut' => $request->checkOut,
                                'workBreak' => $request->workBreak,
                                'wrkhrs' => $request->wrkhrs,
                                'supervicerId' => $supervicerId,
                                'supervisor' => $supervisor,
                                'checkin_latitude' => $request->checkin_latitude,
                                'checkin_longitude' => $request->checkin_longitude]);
                    } else {
                        return response()->json(['data' => 'Invalid Check In Date'], 201);
                    }
                }else{
                    $supervicerId = DB::table('candidate')
                        ->select('candidate.supervicerId')
                        ->where('candidateId', '=', $request->candidateId)
                        ->value('supervicerId');
                    $supervisor = DB::table('candidate')
                        ->select('candidate.candidateId')
                        ->where('candidate_no', '=', $supervicerId)
                        ->value('candidateId');
                    $jobCode = DB::table('jobcode')
                        ->select('jobcode.jobCode')
                        ->where('clientId', '=', $request->clientId)
                        ->where('positionid', '=', $request->positionId)
                        ->value('jobCode');

                    $shiftStart = DB::table('shift')
                        ->select('shiftStart')
                        ->where('shiftId', $request->shiftId)
                        ->first();


                    if (($request->shiftDate == date('Y-m-d'))) {
                        $currentTime = $this->getCurrentTime();
                        if ($currentTime == '00:00') {
                            $currentTime = '00:01';
                        }
                        $timeClock = DB::table('timeclock')
                            ->insert(['candidateId' => $request->candidateId,
                                'shiftDate' => $request->shiftDate,
                                'shiftId' => $request->shiftId,
                                'shiftDay' => $request->shiftDay,
                                'clientId' => $request->clientId,
                                'positionId' => $request->positionId,
                                'deptId' => $request->deptId,
                                'jobCode' => $jobCode,
                                'checkIn' => $currentTime,
                                'checkOut' => $request->checkOut,
                                'workBreak' => $request->workBreak,
                                'wrkhrs' => $request->wrkhrs,
                                'supervicerId' => $supervicerId,
                                'supervisor' => $supervisor,
                                'checkin_latitude' => $request->checkin_latitude,
                                'checkin_longitude' => $request->checkin_longitude]);
                    } else {
                        return response()->json(['data' => 'Invalid Check In Date'], 201);
                    }
                }
            }else{
                $supervicerId = DB::table('candidate')
                    ->select('candidate.supervicerId')
                    ->where('candidateId', '=', $request->candidateId)
                    ->value('supervicerId');
                $supervisor = DB::table('candidate')
                    ->select('candidate.candidateId')
                    ->where('candidate_no', '=', $supervicerId)
                    ->value('candidateId');
                $jobCode = DB::table('jobcode')
                    ->select('jobcode.jobCode')
                    ->where('clientId', '=', $request->clientId)
                    ->where('positionid', '=', $request->positionId)
                    ->value('jobCode');

                $shiftStart = DB::table('shift')
                    ->select('shiftStart')
                    ->where('shiftId', $request->shiftId)
                    ->first();


                if (($request->shiftDate == date('Y-m-d'))) {
                    $currentTime = $this->getCurrentTime();
                    if ($currentTime == '00:00') {
                        $currentTime = '00:01';
                    }
                    $timeClock = DB::table('timeclock')
                        ->insert(['candidateId' => $request->candidateId,
                            'shiftDate' => $request->shiftDate,
                            'shiftId' => $request->shiftId,
                            'shiftDay' => $request->shiftDay,
                            'clientId' => $request->clientId,
                            'positionId' => $request->positionId,
                            'deptId' => $request->deptId,
                            'jobCode' => $jobCode,
                            'checkIn' => $currentTime,
                            'checkOut' => $request->checkOut,
                            'workBreak' => $request->workBreak,
                            'wrkhrs' => $request->wrkhrs,
                            'supervicerId' => $supervicerId,
                            'supervisor' => $supervisor,
                            'checkin_latitude' => $request->checkin_latitude,
                            'checkin_longitude' => $request->checkin_longitude]);
                } else {
                    return response()->json(['data' => 'Invalid Check In Date'], 201);
                }
            }
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return response()->json(['data'=> 'Already checked In Error'], 500);
        }
        return response()->json(['data'=> 'Checked In'], 201);
    }
    public function validateCheckIn($shiftId)
    {
        $result = DB::table('timeclock')
            ->select('timeclock.checkIn')
            ->where('shiftId','=', $shiftId)
            ->where('checkIn','!=','00:00')
            /*->whereNotNull('checkIn')*/
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
            /*->whereNotNull('checkIn')
            ->whereNotNull('checkOut')*/
            ->where('checkIn','!=','00:00')
            ->where('checkOut','!=','00:00')
            ->get();
        if(count($result)>0){
            return true;
        }else{
            return false;
        }
    }
    public function update(Request $request)
    {


        $stateId = $this->getStateByShiftId($request->shiftId);
        if($stateId == 4){
            date_default_timezone_set('Australia/Perth');
        }elseif($stateId == 3){
            date_default_timezone_set('Australia/Brisbane');
        }elseif($stateId == 2){
            date_default_timezone_set('Australia/Melbourne');
        }elseif($stateId == 1){
            date_default_timezone_set('Australia/Sydney');
        }elseif($stateId == 5){
            date_default_timezone_set('Australia/Adelaide');
        }
        if($this->validateCheckIn($request->shiftId)) {
            if($this->validateCheckOut($request->shiftId)) {
                return response()->json(['data' => 'Already checked out Error'], 500);
            }else{
                try {
                    $clientId = DB::table('timeclock')->select('clientId')->where('shiftId',$request->shiftId)->value('clientId');
                    if($this->validateClientShiftLocation($clientId)) {
                        if (!empty($request->checkout_latitude) && !empty($request->checkout_longitude)) {
                            $shiftDate = DB::table('timeclock')
                                ->select('timeclock.shiftDate')
                                ->where('shiftId', '=', $request->shiftId)
                                ->value('shiftDate');
                            $checkInTime = DB::table('timeclock')
                                ->select('timeclock.checkIn')
                                ->where('shiftId', '=', $request->shiftId)
                                ->value('checkIn');
                            $checkout_gap = date("Y-m-d H:i:s", strtotime('+14 hours', strtotime($shiftDate . '' . $checkInTime . ':00')));
                            $currentTime = date('Y-m-d H:i:s');
                            $checkInDateTime = date("Y-m-d H:i:s", strtotime($shiftDate . '' . $checkInTime . ':00'));
                            if (($checkInDateTime <= $currentTime) && ($currentTime <= $checkout_gap)) {
                                $wrkhrs = $this->calculateHoursWorked($shiftDate, $checkInTime, $request->checkOut, $request->workBreak);
                                DB::table('timeclock')
                                    ->where('shiftId', '=', $request->shiftId)
                                    ->update([
                                        'checkOut' => $this->getCurrentTime(),
                                        'workBreak' => $request->workBreak,
                                        'wrkhrs' => $wrkhrs,
                                        'checkout_latitude' => $request->checkout_latitude,
                                        'checkout_longitude' => $request->checkout_longitude
                                    ]);
                            } else {
                                return response()->json(['data' => 'CheckOut Time Expired'], 201);
                            }
                        } else {
                            $shiftDate = DB::table('timeclock')
                                ->select('timeclock.shiftDate')
                                ->where('shiftId', '=', $request->shiftId)
                                ->value('shiftDate');
                            $checkInTime = DB::table('timeclock')
                                ->select('timeclock.checkIn')
                                ->where('shiftId', '=', $request->shiftId)
                                ->value('checkIn');
                            $checkout_gap = date("Y-m-d H:i:s", strtotime('+14 hours', strtotime($shiftDate . '' . $checkInTime . ':00')));
                            $currentTime = date('Y-m-d H:i:s');
                            $checkInDateTime = date("Y-m-d H:i:s", strtotime($shiftDate . '' . $checkInTime . ':00'));
                            if (($checkInDateTime <= $currentTime) && ($currentTime <= $checkout_gap)) {
                                $wrkhrs = $this->calculateHoursWorked($shiftDate, $checkInTime, $request->checkOut, $request->workBreak);
                                DB::table('timeclock')
                                    ->where('shiftId', '=', $request->shiftId)
                                    ->update([
                                        'checkOut' => $this->getCurrentTime(),
                                        'workBreak' => $request->workBreak,
                                        'wrkhrs' => $wrkhrs,
                                        'checkout_latitude' => $request->checkout_latitude,
                                        'checkout_longitude' => $request->checkout_longitude
                                    ]);
                            } else {
                                return response()->json(['data' => 'CheckOut Time Expired'], 201);
                            }
                            //return response()->json(['data' => 'Check Out latitude and longitude not received'], 201);
                        }
                    }else{
                        $shiftDate = DB::table('timeclock')
                            ->select('timeclock.shiftDate')
                            ->where('shiftId', '=', $request->shiftId)
                            ->value('shiftDate');
                        $checkInTime = DB::table('timeclock')
                            ->select('timeclock.checkIn')
                            ->where('shiftId', '=', $request->shiftId)
                            ->value('checkIn');
                        $checkout_gap = date("Y-m-d H:i:s", strtotime('+14 hours', strtotime($shiftDate . '' . $checkInTime . ':00')));
                        $currentTime = date('Y-m-d H:i:s');
                        $checkInDateTime = date("Y-m-d H:i:s", strtotime($shiftDate . '' . $checkInTime . ':00'));
                        if (($checkInDateTime <= $currentTime) && ($currentTime <= $checkout_gap)) {
                            $wrkhrs = $this->calculateHoursWorked($shiftDate, $checkInTime, $request->checkOut, $request->workBreak);
                            DB::table('timeclock')
                                ->where('shiftId', '=', $request->shiftId)
                                ->update([
                                    'checkOut' => $this->getCurrentTime(),
                                    'workBreak' => $request->workBreak,
                                    'wrkhrs' => $wrkhrs,
                                    'checkout_latitude' => $request->checkout_latitude,
                                    'checkout_longitude' => $request->checkout_longitude
                                ]);
                        } else {
                            return response()->json(['data' => 'CheckOut Time Expired'], 201);
                        }
                    }
                } catch (\Exception $e) {
                    Log::error($e->getMessage());
                    return response()->json(['data' => 'CheckOut Error'], 500);
                }
                return response()->json(['data' => 'Checked Out'], 201);
            }
        }else{
            return response()->json(['data'=> 'Please check in before checkout'], 500);
        }
    }
    public function shiftsNotClockedOut($candidateId,$shiftDate){
        $yesterday = date('Y-m-d', strtotime('-1 days', strtotime($shiftDate)));
        try {
            $shiftData = DB::table('timeclock')
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
            if(!empty($shiftData)) {
                $jsonOutput = array(
                    "incompleteShifts" => $shiftData
                );
                return json_encode($jsonOutput);
            }else{
                return response()->json(['data' => 'No Pending Check Outs'], 201);
            }
        }catch (\Exception $e){
            return response()->json(['data' => 'No Pending Check Outs'], 201);
        }
        /*if(is_array($shiftData) && empty($shiftData)){
            return response()->json(['data' => 'No Pending Check Outs'], 201);
        }else{
            return $shiftData;
        }*/
    }
    public function checkStatus(Request $request){
        if($this->validateCheckIn($request->shiftId)) {
            if($this->validateCheckOut($request->shiftId)){
                return response()->json(['status' => 'Checked In and Out'], 201);
            }else{
                return response()->json(['status' => 'Checked In, Not Checked Out'], 201);
            }
        }else{
            return response()->json(['status' => 'Not Checked In'], 201);
        }
    }
    /*public function checkInStatus(Request $request){
        if($this->validateCheckIn($request->shiftId)) {
            return response()->json(['status' => 'Checked In'], 201);
        }else{
            return response()->json(['status' => 'Not Checked In'], 201);
        }
        $jsonOutput = array(
            "success"=>"1",
            "errors"=>array("status"=>200,"message"=>""),
            "month"=>$month, "period" => $weekStartDate . ' - ' . $weekEndDate,
            "clockInSummary" => $clockInArray
        );
        return json_encode($jsonOutput);
    }
    public function checkOutStatus(Request $request){
        if($this->validateCheckOut($request->shiftId)){
            return response()->json(['status' => 'Checked Out'], 201);
        }else{
            return response()->json(['status' => 'Not Checked Out'], 201);
        }
        $jsonOutput = array(
            "success"=>"1",
            "errors"=>array("status"=>200,"message"=>""),
            "month"=>$month, "period" => $weekStartDate . ' - ' . $weekEndDate,
            "clockInSummary" => $clockInArray
        );
        return json_encode($jsonOutput);
    }*/
    public function getClockInSummary($candidateId,$weekStartDate,$weekEndDate){
        try {
            $checkInTime = DB::table('timeclock')
                ->select('timeclock.shiftDate','timeclock.shiftDay','timeclock.checkIn','timeclock.checkOut','timeclock.workBreak')
                ->whereBetween('timeclock.shiftDate', array($weekStartDate,$weekEndDate))
                ->where('timeclock.candidateId','=',$candidateId)
                ->orderBy('timeclock.shiftDate','asc')->get();
            $weeks = $this->rangeWeek($weekStartDate,$weekEndDate);
            $clockInArray = array();
            foreach($weeks as $week){
                foreach ($checkInTime as $chk){
                    if( ($chk->shiftDate >= date('Y-m-d',strtotime($week['start'])))  && ($chk->shiftDate <= date('Y-m-d',strtotime($week['end']))))
                    {
                        $clockInArray[] = $chk;
                    }
                }
            }
            $time=strtotime($weekStartDate);
            $month=date("F",$time);
            $jsonOutput = array(
                "success"=>"1",
                "errors"=>array("status"=>200,"message"=>""),
                "month"=>$month, "period" => $weekStartDate . ' - ' . $weekEndDate,
                "clockInSummary" => $clockInArray
            );
            return json_encode($jsonOutput);
        }catch (\Exception $e){
            $e->getMessage();
        }
    }


}
