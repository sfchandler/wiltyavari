<?php

namespace App\Http\Controllers;

use App\BankAccount;
use App\CandidateDocument;
use App\Mail\EmailGenerator;
use App\Questionnaire;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Candidate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\RegistrationPDF;
use setasign\Fpdi\Fpdi;
use TCPDF;
use TCPDF_FONTS;
use Illuminate\Support\Facades\Log;
date_default_timezone_set('Australia/Melbourne');

/*error_reporting(E_ALL);
ini_set('display_errors', true);*/

class CandidateController extends Controller
{
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
                    $weeks[]   = ['start' => $weekStart, 'end' => $date->format('Y-m-d')];//, 'week' => $week
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
    public function index()
    {
        return Candidate::all();
    }

    public function show($id)
    {
        return DB::table('candidate')->where('candidateId', '=', $id)->get();
    }

    public function showAvailabilityOnDateRange($candidateId, $startDate, $endDate)
    {
        return DB::table('shift_availability')
            ->where('candidateId', '=', $candidateId)
            ->whereBetween('shift_date', array($startDate, $endDate))
            ->get();
    }
    // change to return shav on shiftavailability
    public function showAvailabilityOnDateRangeExtra($candidateId, $startDate, $endDate)
    {
        $availabilities = DB::table('shift_availability')
            ->where('candidateId', '=', $candidateId)
            ->whereBetween('shift_date', array($startDate, $endDate))
            ->get();
        $weeks = $this->rangeWeek($startDate,$endDate);
        $shAv = array();
        foreach($weeks as $week){
            $shiftsArray = array();
            foreach ($availabilities as $av){
                if( ($av->shift_date >= date('Y-m-d',strtotime($week['start'])))  && ($av->shift_date <= date('Y-m-d',strtotime($week['end']))))
                {
                    $shiftsArray[] = $av;
                }
            }
            if(!empty($shiftsArray)) {
                $shAv[] = array("week" => $week['start'] . ' - ' . $week['end'], "shifts"=>$shiftsArray);
            }
        }
        $jsonOutput = array(
            "success"=>"1",
            "errors"=>array("status"=>200,"message"=>""),
            "shiftAvailability" => $shAv
        );
        return json_encode($jsonOutput);
    }

    public function availabilityUpdate(Request $request)
    {
        $candidateId = $request->candidateId;
        $shiftDate = $request->shiftDate;
        $availability = $request->availability;

        $am = $request['availability'][0]['status'];
        $pm = $request['availability'][1]['status'];
        $night = $request['availability'][2]['status'];
        if ($this->validateAvailability($candidateId, $shiftDate)) {
            try {
                DB::table('shift_availability')
                    ->where('candidateId', '=', $candidateId)
                    ->where('shift_date', '=', $shiftDate)
                    ->update(['am' => $am, 'pm' => $pm, 'night' => $night]);
            } catch (\Exception $e) {
                $error = $e->getMessage();
                return response()->json(['response' => $error], 501);
            }
            return response()->json(['response' => 'shift availability updated'], 201);
        } else {
            try {
                DB::table('shift_availability')
                    ->insert(['candidateId' => $candidateId, 'shift_date' => $shiftDate, 'am' => $am, 'pm' => $pm, 'night' => $night]);
            } catch (\Exception $e) {
                $error = $e->getMessage();
                return response()->json(['response' => $error], 501);
            }
            return response()->json(['response' => 'shift availability added'], 201);
        }
    }

    private function validateAvailability($candidateId, $shiftDate)
    {
        $result = DB::table('shift_availability')
            ->where('candidateId', '=', $candidateId)
            ->where('shift_date', '=', $shiftDate)
            ->get();
        if (count($result) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getCandidateByNo($candidateNo)
    {
        return DB::table('candidate')->where('candidate_no', '=', $candidateNo)->first();
    }

    public function validateCandidate($candidateId)
    {
        $result = DB::table('candidate')->where('candidateId', '=', $candidateId)->get();
        if (count($result) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function validateCandidateByNo($candidateNo)
    {
        $result = DB::table('candidate')->where('candidate_no', '=', $candidateNo)->get();
        if (count($result) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function update(Request $request)
    {
        if ($this->validateCandidate($request->candidateId)) {
            try {
                $residentStatus = $request->residentStatus;
                $candidateId = $request->candidateId;
                $visaExpiry = $request->visaExpiry;
                if (!empty($request->employeeImage)) {
                    $employeeImage = addslashes(base64_encode(file_get_contents($request->employeeImage)));
                } else {
                    $employeeImage = '';
                }

                DB::table('candidate')
                    ->where('candidateId', '=', $request->candidateId)
                    ->update([
                        'title' => $request->title,
                        'firstName' => $request->firstName,
                        'middle_name' => $request->middle_name,
                        'lastName' => $request->lastName,
                        'fullName' => $request->firstName . ' ' . $request->middle_name . ' ' . $request->lastName,
                        'address' => $request->address,
                        'unit_no' => $request->unit_no,
                        'street_number' => $request->street_number,
                        'street_name' => $request->street_name,
                        'suburb' => $request->suburb,
                        'state' => $request->state,
                        'postcode' => $request->postcode,
                        'homePhoneNo' => $request->homePhoneNo,
                        'mobileNo' => $request->mobileNo,
                        'sex' => $request->sex,
                        'residentStatus' => $request->residentStatus,
                        'dob' => $request->dob,
                        'tfn' => $request->tfn,
                        'username' => $request->candidateId,
                        'employeeImage' => $employeeImage,
                        'superMemberNo' => $request->superMemberNo,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'empStatus' => 'INACTIVE',
                        'empCondition' => $request->empCondition,
                        'reg_pack_status' => $request->reg_pack_status
                    ]);
                if ($residentStatus == 'Working Visa') {
                    $this->updateVisaTypeAndExpiry($candidateId, 4, $visaExpiry);
                } elseif ($residentStatus == 'Temporary Resident') {
                    $this->updateVisaTypeAndExpiry($candidateId, 3, $visaExpiry);
                } elseif ($residentStatus == 'Student Visa') {
                    $this->updateVisaTypeAndExpiry($candidateId, 2, $visaExpiry);
                } elseif ($residentStatus == 'Australian Citizen') {
                    $this->updateVisaTypeAndExpiry($candidateId, 0, '');
                } elseif ($residentStatus == 'Australian Permanent Resident') {
                    $this->updateVisaTypeAndExpiry($candidateId, 1, '');
                }
                if (($request->reg_pack_status == '1') && !empty($request->candidateId)) {
                    $this->generatePdfDocuments($request->candidateId);
                } else {
                    return response()->json(['data' => 'Candidate Updated'], 201);
                }
            } catch (\Exception $e) {
                return response()->json(['data' => 'Update Error' . $e->getMessage()], 500);
            }
        } else {
            return response()->json(['data' => 'Candidate not found or Invalid'], 500);
        }
    }

    public function deactivate(Request $request)
    {
        if ($this->validateCandidate($request->candidateId)) {
            try {
                DB::table('candidate')
                    ->where('candidateId', '=', $request->candidateId)
                    ->update(['empStatus' => $request->empStatus,
                              'updated_at' => date('Y-m-d H:i:s')]);
                return response()->json(['data' => 'Candidate Deleted'], 201);
            } catch (\Exception $e) {
                return response()->json(['data' => 'Candidate Delete Error' . $e->getMessage()], 500);
            }
        }else {
            return response()->json(['data' => 'Candidate not found or Invalid'], 500);
        }
    }
    public function updateCitizen(Request $request)
    {
        try {
            $candidateInfo = $this->getCandidateByNo($request->candidateNo);
            $candidateId = $candidateInfo->candidateId;
            if ($this->validateCandidateByNo($request->candidateNo)) {
                if ($request->residentialStatus == 'australian-citizen') {
                    $citizenshipCertificate = $request->file('citizenshipCertificate');
                    $citizenshipCertificateFileName = $citizenshipCertificate->getClientOriginalName();
                    $citizenshipCertificatePath = $citizenshipCertificate->move('../documents/' . $candidateId . '/', $citizenshipCertificateFileName);
                    DB::table('candidate_document')
                        ->insert(['candidateId' => $candidateId,
                            'docTypeId' => 63,
                            'fileName' => $citizenshipCertificateFileName,
                            'filePath' => './documents/' . $candidateId . '/' . $citizenshipCertificateFileName]);

                    $passport = $request->file('passport');
                    $passportFileName = $passport->getClientOriginalName();
                    $passportPath = $passport->move('../documents/' . $candidateId . '/', $passportFileName);
                    DB::table('candidate_document')
                        ->insert(['candidateId' => $candidateId,
                            'docTypeId' => 64,
                            'fileName' => $passportFileName,
                            'filePath' => './documents/' . $candidateId . '/' . $passportFileName]);

                    $medicare = $request->file('medicareCertificate');
                    $medicareFileName = $medicare->getClientOriginalName();
                    $medicarePath = $medicare->move('../documents/' . $candidateId . '/', $medicareFileName);
                    DB::table('candidate_document')
                        ->insert(['candidateId' => $candidateId,
                            'docTypeId' => 65,
                            'fileName' => $medicareFileName,
                            'filePath' => './documents/' . $candidateId . '/' . $medicareFileName]);

                    $birth = $request->file('birthCertificate');
                    $birthFileName = $birth->getClientOriginalName();
                    $birthCertPath = $birth->move('../documents/' . $candidateId . '/', $birthFileName);
                    DB::table('candidate_document')
                        ->insert(['candidateId' => $candidateId,
                            'docTypeId' => 66,
                            'fileName' => $birthFileName,
                            'filePath' => './documents/' . $candidateId . '/' . $birthFileName]);

                    return response()->json([
                        'success' => "1",
                        'message' => "Files and information uploaded successfully"
                    ]);
                } else {
                    return response()->json([
                        'success' => "0",
                        'message' => "Residential Status Invalid"
                    ]);
                }
            } else {
                return response()->json([
                    'success' => "0",
                    'message' => "Candidate not found or Invalid"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => "0",
                'message' => 'Citizen update error '.$e->getMessage()
            ]);
        }
    }

    public function updatePR(Request $request)
    {
        try {
            $candidateInfo = $this->getCandidateByNo($request->candidateNo);
            $candidateId = $candidateInfo->candidateId;
            if ($this->validateCandidateByNo($request->candidateNo)) {
                if ($request->residentialStatus == 'australian-permanent-resident') {
                    $passport = $request->file('passport');
                    $passportFileName = $passport->getClientOriginalName();
                    $passportPath = $passport->move('../documents/' . $candidateId . '/', $passportFileName);
                    DB::table('candidate_document')
                        ->insert(['candidateId' => $candidateId,
                            'docTypeId' => 64,
                            'fileName' => $passportFileName,
                            'filePath' => './documents/' . $candidateId . '/' . $passportFileName]);

                    $medicare = $request->file('medicareCertificate');
                    $medicareFileName = $medicare->getClientOriginalName();
                    $medicarePath = $medicare->move('../documents/' . $candidateId . '/', $medicareFileName);
                    DB::table('candidate_document')
                        ->insert(['candidateId' => $candidateId,
                            'docTypeId' => 65,
                            'fileName' => $medicareFileName,
                            'filePath' => './documents/' . $candidateId . '/' . $medicareFileName]);

                    $birth = $request->file('birthCertificate');
                    $birthFileName = $birth->getClientOriginalName();
                    $birthCertPath = $birth->move('../documents/' . $candidateId . '/', $birthFileName);
                    DB::table('candidate_document')
                        ->insert(['candidateId' => $candidateId,
                            'docTypeId' => 66,
                            'fileName' => $birthFileName,
                            'filePath' => './documents/' . $candidateId . '/' . $birthFileName]);

                    return response()->json([
                        'success' => "1",
                        'message' => "Files uploaded successfully"
                    ]);
                } else {
                    return response()->json([
                        'success' => "0",
                        'message' => "Residential Status Invalid"
                    ]);
                }
            } else {
                return response()->json([
                    'success' => "0",
                    'message' => "Candidate not found or Invalid"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => "0",
                'message' => 'PR update error '.$e->getMessage()
            ]);
        }
    }

    public function updateWorkingVisa(Request $request)
    {
        try {
            $candidateInfo = $this->getCandidateByNo($request->candidateNo);
            $candidateId = $candidateInfo->candidateId;
            if ($this->validateCandidateByNo($request->candidateNo)) {
                if ($request->residentialStatus == 'working-visa') {
                    $passport = $request->file('passport');
                    $passportFileName = $passport->getClientOriginalName();
                    $passportPath = $passport->move('../documents/' . $candidateId . '/', $passportFileName);
                    DB::table('candidate_document')
                        ->insert(['candidateId' => $candidateId,
                            'docTypeId' => 64,
                            'fileName' => $passportFileName,
                            'filePath' => './documents/' . $candidateId . '/' . $passportFileName]);

                    $visaGrant = $request->file('visaGrantLetter');
                    $visaGrantFileName = $visaGrant->getClientOriginalName();
                    $birthCertPath = $visaGrant->move('../documents/' . $candidateId . '/', $visaGrantFileName);
                    DB::table('candidate_document')
                        ->insert(['candidateId' => $candidateId,
                            'docTypeId' => 67,
                            'fileName' => $visaGrantFileName,
                            'filePath' => './documents/' . $candidateId . '/' . $visaGrantFileName]);

                    $expDate = $request->visaExpiryDate;
                    $result = DB::table('employee_visatype')->where('candidateId', '=', $candidateId)->get();
                    if (count($result) > 0) {
                        $update = DB::table('employee_visatype')
                            ->where('visaTypeId', ' = ', 4)
                            ->where('candidateId', ' = ', $candidateId)
                            ->update(['expiryDate' => $expDate]);
                    } else {
                        $insert = DB::table('employee_visatype')
                            ->insert(['candidateId' => $candidateId,
                                'visaTypeId' => 4,
                                'expiryDate' => $expDate]);
                    }

                    return response()->json([
                        'success' => "1",
                        'message' => "Files uploaded successfully"
                    ]);
                } else {
                    return response()->json([
                        'success' => "0",
                        'message' => "Residential Status Invalid"
                    ]);
                }
            } else {
                return response()->json([
                    'success' => "0",
                    'message' => "Candidate not found or Invalid"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => "0",
                'message' => 'Error updating working visa '.$e->getMessage()
            ]);
        }
    }

    public function updateTemporaryResident(Request $request)
    {
        try {
            $candidateInfo = $this->getCandidateByNo($request->candidateNo);
            $candidateId = $candidateInfo->candidateId;
            if ($this->validateCandidateByNo($request->candidateNo)) {
                if ($request->residentialStatus == 'temporary-resident') {
                    $passport = $request->file('passport');
                    $passportFileName = $passport->getClientOriginalName();
                    $passportPath = $passport->move('../documents/' . $candidateId . '/', $passportFileName);
                    DB::table('candidate_document')
                        ->insert(['candidateId' => $candidateId,
                            'docTypeId' => 64,
                            'fileName' => $passportFileName,
                            'filePath' => './documents/' . $candidateId . '/' . $passportFileName]);

                    $visaGrant = $request->file('visaGrantLetter');
                    $visaGrantFileName = $visaGrant->getClientOriginalName();
                    $birthCertPath = $visaGrant->move('../documents/' . $candidateId . '/', $visaGrantFileName);
                    DB::table('candidate_document')
                        ->insert(['candidateId' => $candidateId,
                            'docTypeId' => 67,
                            'fileName' => $visaGrantFileName,
                            'filePath' => './documents/' . $candidateId . '/' . $visaGrantFileName]);

                    $expDate = $request->visaExpiryDate;
                    $result = DB::table('employee_visatype')->where('candidateId', '=', $candidateId)->get();
                    if (count($result) > 0) {
                        $update = DB::table('employee_visatype')
                            ->where('visaTypeId', '=', 3)
                            ->where('candidateId', '=', $candidateId)
                            ->update(['expiryDate' => $expDate]);
                    } else {
                        $insert = DB::table('employee_visatype')
                            ->insert(['candidateId' => $candidateId,
                                'visaTypeId' => 3,
                                'expiryDate' => $expDate]);
                    }

                    return response()->json([
                        'success' => "1",
                        'message' => "Files uploaded successfully"
                    ]);
                } else {
                    return response()->json([
                        'success' => "0",
                        'message' => "Residential Status Invalid"
                    ]);
                }
            } else {
                return response()->json([
                    'success' => "0",
                    'message' => "Candidate not found or Invalid"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => "0",
                'message' => 'Error updating Temporary resident visa'.$e->getMessage()
            ]);
        }
    }

    public function updateStudentVisa(Request $request)
    {
        try {
            $candidateInfo = $this->getCandidateByNo($request->candidateNo);
            $candidateId = $candidateInfo->candidateId;
            if ($this->validateCandidateByNo($request->candidateNo)) {
                if ($request->residentialStatus == 'student-visa') {
                    $passport = $request->file('passport');
                    $passportFileName = $passport->getClientOriginalName();
                    $passportPath = $passport->move('../documents/' . $candidateId . '/', $passportFileName);
                    DB::table('candidate_document')
                        ->insert(['candidateId' => $candidateId,
                            'docTypeId' => 64,
                            'fileName' => $passportFileName,
                            'filePath' => './documents/' . $candidateId . '/' . $passportFileName]);

                    $visaGrant = $request->file('visaGrantLetter');
                    $visaGrantFileName = $visaGrant->getClientOriginalName();
                    $birthCertPath = $visaGrant->move('../documents/' . $candidateId . '/', $visaGrantFileName);
                    DB::table('candidate_document')
                        ->insert(['candidateId' => $candidateId,
                            'docTypeId' => 67,
                            'fileName' => $visaGrantFileName,
                            'filePath' => './documents/' . $candidateId . '/' . $visaGrantFileName]);

                    $studentId = $request->file('studentId');
                    $studentIdFileName = $studentId->getClientOriginalName();
                    $studentIdPath = $studentId->move('../documents/' . $candidateId . '/', $studentIdFileName);
                    DB::table('candidate_document')
                        ->insert(['candidateId' => $candidateId,
                            'docTypeId' => 68,
                            'fileName' => $studentIdFileName,
                            'filePath' => './documents/' . $candidateId . '/' . $studentIdFileName]);

                    $expDate = $request->visaExpiryDate;
                    $workHourRestriction = $request->hourRestrictions;
                    $result = DB::table('employee_visatype')->where('candidateId', '=', $candidateId)->get();
                    if (count($result) > 0) {
                        $update = DB::table('employee_visatype')
                            ->where('visaTypeId', '=', 3)
                            ->where('candidateId', '=', $candidateId)
                            ->update(['expiryDate' => $expDate, 'workHourRestriction' => $workHourRestriction]);
                    } else {
                        $insert = DB::table('employee_visatype')
                            ->insert(['candidateId' => $candidateId,
                                'visaTypeId' => 3,
                                'expiryDate' => $expDate]);
                    }

                    return response()->json([
                        'success' => "1",
                        'message' => "Files uploaded successfully"
                    ]);
                } else {
                    return response()->json([
                        'success' => "0",
                        'message' => "Residential Status Invalid"
                    ]);
                }
            } else {
                return response()->json([
                    'success' => "0",
                    'message' => "Candidate not found or Invalid"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => "0",
                'message' => 'Error updating student visa information'.$e->getMessage()
            ]);
        }
    }

    public function updateQualifications(Request $request)
    {
        try {
            $candidateInfo = $this->getCandidateByNo($request->candidateNo);
            $candidateId = $candidateInfo->candidateId;
            if ($this->validateCandidateByNo($request->candidateNo)) {
                $drvLicence = $request->file('drivingLicence');
                $drvLicenceFileName = $drvLicence->getClientOriginalName();
                $drvLicencePath = $drvLicence->move('../documents/' . $candidateId . '/', $drvLicenceFileName);
                DB::table('candidate_document')
                    ->insert(['candidateId' => $candidateId,
                        'docTypeId' => 7,
                        'fileName' => $drvLicenceFileName,
                        'filePath' => './documents/' . $candidateId . '/' . $drvLicenceFileName]);

                $whiteCard = $request->file('whiteCard');
                $whiteCardFileName = $whiteCard->getClientOriginalName();
                $whiteCardPath = $whiteCard->move('../documents/' . $candidateId . '/', $whiteCardFileName);
                DB::table('candidate_document')
                    ->insert(['candidateId' => $candidateId,
                        'docTypeId' => 54,
                        'fileName' => $whiteCardFileName,
                        'filePath' => './documents/' . $candidateId . '/' . $whiteCardFileName]);

                $forkliftLicence = $request->file('forkLiftLicence');
                $forkliftLicenceFileName = $forkliftLicence->getClientOriginalName();
                $forkliftLicencePath = $forkliftLicence->move('../documents/' . $candidateId . '/', $forkliftLicenceFileName);
                DB::table('candidate_document')
                    ->insert(['candidateId' => $candidateId,
                        'docTypeId' => 54,
                        'fileName' => $forkliftLicenceFileName,
                        'filePath' => './documents/' . $candidateId . '/' . $forkliftLicenceFileName]);

                $other1 = $request->file('otherLicence1');
                $other1FileName = $other1->getClientOriginalName();
                $other1Path = $other1->move('../documents/' . $candidateId . '/', $other1FileName);
                DB::table('candidate_document')
                    ->insert(['candidateId' => $candidateId,
                        'docTypeId' => 69,
                        'fileName' => $other1FileName,
                        'filePath' => './documents/' . $candidateId . '/' . $other1FileName]);

                $other2 = $request->file('otherLicence2');
                $other2FileName = $other2->getClientOriginalName();
                $other2Path = $other2->move('../documents/' . $candidateId . '/', $other2FileName);
                DB::table('candidate_document')
                    ->insert(['candidateId' => $candidateId,
                        'docTypeId' => 69,
                        'fileName' => $other2FileName,
                        'filePath' => './documents/' . $candidateId . '/' . $other2FileName]);

                return response()->json([
                    'success' => "1",
                    'message' => "Files uploaded successfully"
                ]);
            } else {
                return response()->json([
                    'success' => "0",
                    'message' => "Candidate not found or Invalid"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => "0",
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateDocument(Request $request)
    {
        try {
            $candidateInfo = $this->getCandidateByNo($request->candidateNo);
            $candidateId = $candidateInfo->candidateId;
            if ($this->validateCandidateByNo($request->candidateNo)) {
                $file = $request->file('documentFile');
                $fileName = $file->getClientOriginalName();
                $path = $file->move('../documents/' . $candidateId . '/', $fileName);
                DB::table('candidate_document')
                    ->insert(['candidateId' => $candidateId,
                        'docTypeId' => $request->documentTypeId,
                        'fileName' => $fileName,
                        'validTo' => $request->expiryDate,
                        'filePath' => './documents/' . $candidateId . '/' . $fileName]);
                return response()->json([
                    'success' => "1",
                    'message' => "File uploaded successfully"
                ], 201);
            } else {
                return response()->json([
                    'success' => "0",
                    'message' => "Candidate not found or Invalid"
                ]);
            }
        } catch (\Exception $e) {
            $e->getMessage();
            return response()->json([
                'success' => "0",
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateProfileInformation(Request $request)
    {
        try {
            $candidateInfo = $this->getCandidateByNo($request->candidateNo);
            $candidateId = $candidateInfo->candidateId;
            if ($this->validateCandidateByNo($request->candidateNo)) {
                $file = $request->file('fileName');
                $fileName = $file->getClientOriginalName();
                $path = $file->move('../documents/' . $candidateId . '/', $fileName);

                DB::table('candidate_document')
                    ->insert(['candidateId' => $candidateId,
                        'docTypeId' => 17,
                        'fileName' => $fileName,
                        'filePath' => './documents/' . $candidateId . '/' . $fileName]);

                DB::table('candidate')
                    ->where('candidateId', '=', $candidateId)
                    ->update([
                        'title' => $request->title,
                        'firstName' => $request->firstName,
                        'middle_name' => $request->middleName,
                        'lastName' => $request->lastName,
                        'fullName' => $request->firstName . ' ' . $request->middleName . ' ' . $request->lastName,
                        'address' => $request->unitNumber . ' ' . $request->streetNumber . ' ' . $request->streetName . ' ' . $request->suburb . ' ' . $request->state . ' ' . $request->postcode,
                        'unit_no' => $request->unitNumber,
                        'street_number' => $request->streetNumber,
                        'street_name' => $request->streetName,
                        'suburb' => $request->suburb,
                        'state' => $request->state,
                        'postcode' => $request->postcode,
                        'mobileNo' => $request->mobileNumber,
                        'sex' => $request->gender,
                        'dob' => date('d/m/Y', strtotime($request->dateOfBirth))
                    ]);
                return response()->json([
                    'success' => "1",
                    'message' => "Profile details updated successfully"
                ]);
            } else {
                return response()->json([
                    'success' => "0",
                    'message' => "Candidate not found or Invalid"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => "0",
                'message' => $e->getMessage()
            ]);
        }
    }

    public function appDashboard(Request $request)
    {
        try {
            $candidateInfo = $this->getCandidateByNo($request->candidateNo);
            $candidateId = $candidateInfo->candidateId;
            if ($this->validateCandidateByNo($request->candidateNo)) {
                $profileImage = DB::table('candidate_document')->where('candidateId', '=', $candidateId)->where('docTypeId', '=', 17)->first();
                $fPath = '';
                if (!empty($profileImage->filePath)) {
                    $fPath = $profileImage->filePath;
                }
                if ($candidateInfo->reg_pack_status == '0') {
                    $applicationStatus = 'Pending';
                } else {
                    $applicationStatus = 'Submitted';
                }
                return response()->json([
                    'success' => "1",
                    'message' => "",
                    'profile' => array(
                        'fullName' => $candidateInfo->firstName . ' ' . $candidateInfo->lastName,
                        'profileImage' => $fPath,
                        'applicationCompletion' => $candidateInfo->reg_app_completion,
                        'applicationStatus' => $applicationStatus
                    ),
                    'toDo' => array(
                        'registration' => array(
                            'active' => $candidateInfo->reg_app_active,
                            'progress' => $candidateInfo->reg_app_progress
                        ),
                        'contracts' => array(
                            'active' => $candidateInfo->reg_app_contracts_active,
                            'progress' => $candidateInfo->reg_app_contracts_progress,
                            'progressColor' => $candidateInfo->reg_app_contracts_progress_color
                        ),
                    ),
                    'contact' => array(
                        'fullName' => $candidateInfo->firstName . ' ' . $candidateInfo->lastName,
                        'profileImage' => $fPath,
                        'phoneNumber' => $candidateInfo->mobileNo,
                        'email' => $candidateInfo->email
                    ),
                    'update' => array(
                        'active' => $candidateInfo->reg_app_update_active
                    ),
                ]);
            } else {
                return response()->json([
                    'success' => "0",
                    'message' => "Candidate not found or Invalid"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => "0",
                'message' => $e->getMessage()
            ]);
        }
    }

    public function personalInformation(Request $request)
    {
        try {
            if ($this->validateCandidateByNo($request->candidateNo)) {
                $candidateInfo = $this->getCandidateByNo($request->candidateNo);
                $candidateId = $candidateInfo->candidateId;
                $profileImage = DB::table('candidate_document')->where('candidateId', '=', $candidateId)->where('docTypeId', '=', 17)->first();
                $profileImagePath = '';
                if (!empty($profileImage->filePath)) {
                    $profileImagePath = $profileImage->filePath;
                }
                $citizenshipDocument = DB::table('candidate_document')->where('candidateId', '=', $candidateId)->where('docTypeId', '=', 63)->first();
                $citizenshipDocumentPath = '';
                if (!empty($citizenshipDocument->filePath)) {
                    $citizenshipDocumentPath = $citizenshipDocument->filePath;
                }
                $passport = DB::table('candidate_document')->where('candidateId', '=', $candidateId)->where('docTypeId', '=', 64)->first();
                $passportPath = '';
                if (!empty($passport->filePath)) {
                    $passportPath = $passport->filePath;
                }
                $medicare = DB::table('candidate_document')->where('candidateId', '=', $candidateId)->where('docTypeId', '=', 65)->first();
                $medicarePath = '';
                if (!empty($medicare->filePath)) {
                    $medicarePath = $medicare->filePath;
                }
                $birthCertificate = DB::table('candidate_document')->where('candidateId', '=', $candidateId)->where('docTypeId', '=', 66)->first();
                $birthCertificatePath = '';
                if (!empty($birthCertificate->filePath)) {
                    $birthCertificatePath = $birthCertificate->filePath;
                }
                $visaGrant = DB::table('candidate_document')->where('candidateId', '=', $candidateId)->where('docTypeId', '=', 67)->first();
                $visaGrantLetterPath = '';
                if (!empty($visaGrant->filePath)) {
                    $visaGrantLetterPath = $visaGrant->filePath;
                }
                $driversLicence = DB::table('candidate_document')->where('candidateId', '=', $candidateId)->where('docTypeId', '=', 7)->first();
                $driversLicencePath = '';
                if (!empty($driversLicence->filePath)) {
                    $driversLicencePath = $driversLicence->filePath;
                }
                $whiteCard = DB::table('candidate_document')->where('candidateId', '=', $candidateId)->where('docTypeId', '=', 54)->first();
                $whiteCardPath = '';
                if (!empty($whiteCard->filePath)) {
                    $whiteCardPath = $whiteCard->filePath;
                }
                $forkliftLicence = DB::table('candidate_document')->where('candidateId', '=', $candidateId)->where('docTypeId', '=', 55)->first();
                $forkliftLicencePath = '';
                if (!empty($forkliftLicence->filePath)) {
                    $forkliftLicencePath = $forkliftLicence->filePath;
                }

                return response()->json([
                    'success' => "1",
                    'message' => "",
                    'personalInformation' => array(
                        'title' => $candidateInfo->title,
                        'firstName' => $candidateInfo->firstName,
                        'middleName' => $candidateInfo->middle_name,
                        'lastName' => $candidateInfo->lastName,
                        'gender' => $candidateInfo->sex,
                        'dateOfBirth' => $candidateInfo->dob,
                        'mobileNumber' => $candidateInfo->mobileNo,
                        'email' => $candidateInfo->email
                    ),
                    'address' => array(
                        'unitNumber' => $candidateInfo->unit_no,
                        'streetNumber' => $candidateInfo->street_number,
                        'streetName' => $candidateInfo->street_name,
                        'suburb' => $candidateInfo->suburb,
                        'state' => $candidateInfo->state,
                        'postCode' => $candidateInfo->postcode,
                    ),
                    'profile' => array(
                        'profileImage' => $profileImagePath
                    ),
                    'residentialStatus' => array(
                        'australianCitizen' => array(
                            'selected' => "1",
                            'citizenshipDocument' => $citizenshipDocumentPath,
                            'passport' => $passportPath,
                            'medicareCertificate' => $medicarePath,
                            'birthCertificate' => $birthCertificatePath
                        ),
                        'permenantResident' => array(
                            'selected' => "1",
                            'passport' => $passportPath,
                            'medicareCertificate' => $medicarePath,
                            'birthCertificate' => $birthCertificatePath
                        ),
                        'workVisa' => array(
                            'expiryDate' => "1",
                            'visaGrantLetter' => $visaGrantLetterPath,
                            'passport' => $passportPath
                        ),
                        'temporaryResident' => array(
                            'expiryDate' => "",
                            'visaGrantLetter' => $visaGrantLetterPath,
                            'passport' => $passportPath
                        ),
                        'studentVisa' => array(
                            'expiryDate' => "",
                            'workingHoursRestrictions' => "",
                            'visaGrantLetter' => $visaGrantLetterPath,
                            'passport' => $passportPath,
                            'studentID' => ""
                        )
                    ),
                    'selectedType' => "residentialStatus",
                    'qualifications' => array(
                        'drivingLicence' => $driversLicencePath,
                        'whiteCard' => $whiteCardPath,
                        'forkliftLicence' => $forkliftLicencePath,
                        'other1' => "",
                        'other2' => ""
                    ),
                    'emergencyContact' => array(
                        'fullName' => "",
                        'relationship' => "",
                        'state' => "",
                        'mobileNumber' => "",
                        'homePhoneNumber' => ""
                    ),
                    'referee1' => array(
                        'name' => "",
                        'companyName' => "",
                        'position' => "",
                        'relationship' => "",
                        'mobileNumber' => ""
                    ),
                    'referee2' => array(
                        'name' => "",
                        'companyName' => "",
                        'position' => "",
                        'relationship' => "",
                        'mobileNumber' => ""
                    ),
                    'bankAccount' => array(
                        'accountName' => "",
                        'bankName' => "",
                        'bsb' => "",
                        'accountNumber' => ""
                    ),
                    'taxFileNumber' => array(
                        'taxFileNumber' => ""
                    ),
                    'superFundInformation' => array(
                        'superAccountName' => "",
                        'fundName' => "",
                        'membershipName' => "",
                        'fundAddress' => "",
                        'phoneNo' => "",
                        'website' => "",
                        'fundABN' => "",
                        'fundUSI' => ""
                    ),
                ]);
            } else {
                return response()->json([
                    'success' => "0",
                    'message' => "Candidate not found or Invalid"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => "0",
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateEmergencyContact(Request $request)
    {
        try {
            if ($this->validateCandidateByNo($request->candidateNo)) {
                $candidateInfo = $this->getCandidateByNo($request->candidateNo);
                $candidateId = $candidateInfo->candidateId;
                Questionnaire::where('candidateId', '=', $candidateId)->update([
                    'emcName' => $request->fullName,
                    'emcRelationship' => $request->relationship,
                    'emcState' => $request->state,
                    'emcMobile' => $request->mobileNumber,
                    'emcHomePhone' => $request->homePhoneNumber
                ]);
                return response()->json([
                    'success' => "1",
                    'message' => "Emergency Contact details updated"
                ]);
            } else {
                return response()->json([
                    'success' => "0",
                    'message' => "Candidate not found or Invalid"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => "0",
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateReferee1(Request $request)
    {
        try {
            if ($this->validateCandidateByNo($request->candidateNo)) {
                $candidateInfo = $this->getCandidateByNo($request->candidateNo);
                $candidateId = $candidateInfo->candidateId;
                Questionnaire::where('candidateId', '=', $candidateId)->update([
                    'referee1Name' => $request->referee1Name,
                    'referee1CompanyName' => $request->referee1CompanyName,
                    'referee1Position' => $request->referee1Position,
                    'referee1Relationship' => $request->referee1Relationship,
                    'referee1Mobile' => $request->referee1Mobile
                ]);
                return response()->json([
                    'success' => "1",
                    'message' => "Referee 1 details updated"
                ]);
            } else {
                return response()->json([
                    'success' => "0",
                    'message' => "Candidate not found or Invalid"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => "0",
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateReferee2(Request $request)
    {
        try {
            if ($this->validateCandidateByNo($request->candidateNo)) {
                $candidateInfo = $this->getCandidateByNo($request->candidateNo);
                $candidateId = $candidateInfo->candidateId;
                Questionnaire::where('candidateId', '=', $candidateId)->update([
                    'referee2Name' => $request->referee2Name,
                    'referee2CompanyName' => $request->referee2CompanyName,
                    'referee2Position' => $request->referee2Position,
                    'referee2Relationship' => $request->referee2Relationship,
                    'referee2Mobile' => $request->referee2Mobile
                ]);
                return response()->json([
                    'success' => "1",
                    'message' => "Referee 2 details updated"
                ]);
            } else {
                return response()->json([
                    'success' => "0",
                    'message' => "Candidate not found or Invalid"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => "0",
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateBankAccount(Request $request)
    {
        try {
            if ($this->validateCandidateByNo($request->candidateNo)) {
                $candidateInfo = $this->getCandidateByNo($request->candidateNo);
                $candidateId = $candidateInfo->candidateId;

                $bankName = $request->bankName;
                $bankAccountName = $request->bankAccountName;
                $bankAccountNumber = $request->bankAccountNumber;
                $bsb = $request->bsb;
                $result = DB::table('employee_bank_account')->where('candidateId', '=', $candidateId)->get();
                if (count($result) > 0) {
                    $update = DB::table('employee_bank_account')
                        ->where('candidateId', '=', $candidateId)
                        ->update(['bankName' => $bankName,
                            'accountName' => $bankAccountName,
                            'accountNumber' => $bankAccountNumber,
                            'bsb' => $bsb]);
                    if ($update) {
                        return response()->json([
                            'success' => "1",
                            'message' => "Bank Account information updated"
                        ]);
                    } else {
                        return response()->json([
                            'success' => "0",
                            'message' => "Error updating Bank Account information"
                        ]);
                    }
                } else {
                    $insert = DB::table('employee_bank_account')
                        ->insert(['candidateId' => $candidateId,
                            'bankName' => $bankName,
                            'accountName' => $bankAccountName,
                            'accountNumber' => $bankAccountNumber,
                            'bsb' => $bsb]);
                    if ($insert) {
                        return response()->json([
                            'success' => "1",
                            'message' => "Bank Account information saved"
                        ]);
                    } else {
                        return response()->json([
                            'success' => "0",
                            'message' => "Error saving Bank Account information"
                        ]);
                    }
                }
            } else {
                return response()->json([
                    'success' => "0",
                    'message' => "Candidate not found or Invalid"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => "0",
                'message' => $e->getMessage()
            ]);
        }
        /*$candidateId = $request->candidateId;
        $bankName = $request->bankName;
        $bankAccountName = $request->bankAccountName;
        $bankAccountNumber = $request->bankAccountNumber;
        $bsb = $request->bsb;
        try {
            $result = DB::table('employee_bank_account')->where('candidateId', '=', $candidateId)->get();
            if (count($result) > 0) {
                $update = DB::table('employee_bank_account')
                    ->where('candidateId', '=', $candidateId)
                    ->update(['bankName' => $bankName,
                        'accountName' => $bankAccountName,
                        'accountNumber' => $bankAccountNumber,
                        'bsb' => $bsb]);
                return response()->json(['data' => 'Bank Account Information Updated'], 201);
            } else {
                $insert = DB::table('employee_bank_account')
                    ->insert(['candidateId' => $candidateId,
                        'bankName' => $bankName,
                        'accountName' => $bankAccountName,
                        'accountNumber' => $bankAccountNumber,
                        'bsb' => $bsb]);
                return response()->json(['data' => 'Bank Account Information Saved'], 201);
            }
        } catch (\Exception $e) {
            $e->getMessage();
            return response()->json(['data' => 'Bank Account Information update Error' . $e->getMessage()], 500);
        }*/
    }

    public function updateTFN(Request $request)
    {
        try {
            if ($this->validateCandidateByNo($request->candidateNo)) {
                $candidateInfo = $this->getCandidateByNo($request->candidateNo);
                $candidateId = $candidateInfo->candidateId;

                $candidate = Candidate::find($request->candidateNo);
                if ($candidate) {
                    $candidate->tfn = $request->taxFileNo;
                    $candidate->save();
                    Questionnaire::where('candidateId', '=', $candidateId)->update([
                        'paidBasis' => $request->paidBasis
                    ]);
                    return response()->json([
                        'success' => "1",
                        'message' => "Tax File Number updated"
                    ]);
                } else {
                    return response()->json([
                        'success' => "0",
                        'message' => "Existing Candidate record not found"
                    ]);
                }
            } else {
                return response()->json([
                    'success' => "0",
                    'message' => "Candidate not found or Invalid"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => "0",
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateTaxResidency(Request $request)
    {
        try {
            if ($this->validateCandidateByNo($request->candidateNo)) {
                $candidateInfo = $this->getCandidateByNo($request->candidateNo);
                $candidateId = $candidateInfo->candidateId;
                Questionnaire::where('candidateId', '=', $candidateId)->update(['taxResident' => $request->taxResident]);
                return response()->json([
                    'success' => "1",
                    'message' => "Tax Residency updated"
                ]);
            } else {
                return response()->json([
                    'success' => "0",
                    'message' => "Candidate not found or Invalid"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => "0",
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateTaxThresholdClaim(Request $request)
    {
        try {
            if ($this->validateCandidateByNo($request->candidateNo)) {
                $candidateInfo = $this->getCandidateByNo($request->candidateNo);
                $candidateId = $candidateInfo->candidateId;
                Questionnaire::where('candidateId', '=', $candidateId)->update(['taxClaim' => $request->taxClaim]);
                return response()->json([
                    'success' => "1",
                    'message' => "Tax Threshold Claim updated"
                ]);
            } else {
                return response()->json([
                    'success' => "0",
                    'message' => "Candidate not found or Invalid"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => "0",
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateTaxLoanHelp(Request $request)
    {
        try {
            if ($this->validateCandidateByNo($request->candidateNo)) {
                $candidateInfo = $this->getCandidateByNo($request->candidateNo);
                $candidateId = $candidateInfo->candidateId;
                Questionnaire::where('candidateId', '=', $candidateId)->update(['taxHelp' => $request->taxHelp]);
                return response()->json([
                    'success' => "1",
                    'message' => "Tax loan help updated"
                ]);
            } else {
                return response()->json([
                    'success' => "0",
                    'message' => "Candidate not found or Invalid"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => "0",
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateSuperFundCheck(Request $request)
    {
        try {
            if ($this->validateCandidateByNo($request->candidateNo)) {
                $candidateInfo = $this->getCandidateByNo($request->candidateNo);
                $candidateId = $candidateInfo->candidateId;
                Questionnaire::where('candidateId', '=', $candidateId)->update(['superFundCheck' => $request->superAccountCheck]);
                return response()->json([
                    'success' => "1",
                    'message' => "Super Fund check updated"
                ]);
            } else {
                return response()->json([
                    'success' => "0",
                    'message' => "Candidate not found or Invalid"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => "0",
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateSuperFundInformation(Request $request)
    {
        try {
            if ($this->validateCandidateByNo($request->candidateNo)) {
                $candidateInfo = $this->getCandidateByNo($request->candidateNo);
                $candidateId = $candidateInfo->candidateId;
                $candidate = Candidate::find($request->candidateNo);
                $candidate->superMemberNo = $request->superMemberNumber;
                $candidate->save();
                Questionnaire::where('candidateId', '=', $candidateId)->update([
                    'superAccountName' => $request->superAccountName,
                    'superFundName' => $request->superFundName,
                    'superFundAddress' => $request->superFundAddress,
                    'superPhoneNo' => $request->superPhoneNumber,
                    'superWebsite' => $request->superFundWebsite,
                    'superFundABN' => $request->superFundABN,
                    'superFundUSI' => $request->superFundUSI
                ]);
                return response()->json([
                    'success' => "1",
                    'message' => "Super Fund Information updated"
                ]);
            } else {
                return response()->json([
                    'success' => "0",
                    'message' => "Candidate not found or Invalid"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => "0",
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updatePoliceCheckInformation(Request $request)
    {
        try {
            if ($this->validateCandidateByNo($request->candidateNo)) {
                $candidateInfo = $this->getCandidateByNo($request->candidateNo);
                $candidateId = $candidateInfo->candidateId;
                Questionnaire::where('candidateId', '=', $candidateId)->update([
                    'policeCheck' => $request->policeCheck
                ]);
                $file = $request->file('policeCheckFile');
                $fileName = $file->getClientOriginalName();
                $path = $file->move('../documents/' . $candidateId . '/', $fileName);
                DB::table('candidate_document')
                    ->insert(['candidateId' => $candidateId,
                        'docTypeId' => 18,
                        'fileName' => $fileName,
                        'filePath' => './documents/' . $candidateId . '/' . $fileName]);
                return response()->json([
                    'success' => "1",
                    'message' => "Police Check Information updated"
                ]);
            } else {
                return response()->json([
                    'success' => "0",
                    'message' => "Candidate not found or Invalid"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => "0",
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateCriminalHistory(Request $request)
    {
        try {
            if ($this->validateCandidateByNo($request->candidateNo)) {
                $candidateInfo = $this->getCandidateByNo($request->candidateNo);
                $candidateId = $candidateInfo->candidateId;
                Questionnaire::where('candidateId', '=', $candidateId)->update([
                    'crimeCheck' => $request->crimeCheck
                ]);
                return response()->json([
                    'success' => "1",
                    'message' => "Criminal Check Information updated"
                ]);
            } else {
                return response()->json([
                    'success' => "0",
                    'message' => "Candidate not found or Invalid"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => "0",
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateStatDec(Request $request)
    {
        try {
            if ($this->validateCandidateByNo($request->candidateNo)) {
                $candidateInfo = $this->getCandidateByNo($request->candidateNo);
                $candidateId = $candidateInfo->candidateId;
                Questionnaire::where('candidateId', '=', $candidateId)->update([
                    'statOccupation' => $request->occupation,
                    'neverConvicted' => $request->neverConvicted,
                    'neverImprisonment' => $request->neverImprisonment
                ]);
                return response()->json([
                    'success' => "1",
                    'message' => "Statutory Declaration Information updated"
                ]);
            } else {
                return response()->json([
                    'success' => "0",
                    'message' => "Candidate not found or Invalid"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => "0",
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updatePoliceCheckAuthority(Request $request)
    {
        try {
            if ($this->validateCandidateByNo($request->candidateNo)) {
                $candidateInfo = $this->getCandidateByNo($request->candidateNo);
                $candidateId = $candidateInfo->candidateId;
                Questionnaire::where('candidateId', '=', $candidateId)->update([
                    'crimeDate1' => $request->crimeDate1,
                    'crime1' => $request->crime1,
                    'crimeDate2' => $request->crimeDate2,
                    'crime2' => $request->crime2,
                    'optionChk' => $request->optionCheck
                ]);
                return response()->json([
                    'success' => "1",
                    'message' => "Police Check Authority Information updated"
                ]);
            } else {
                return response()->json([
                    'success' => "0",
                    'message' => "Candidate not found or Invalid"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => "0",
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updatePoliceCheckCostAgreement(Request $request)
    {
        try {
            if ($this->validateCandidateByNo($request->candidateNo)) {
                $candidateInfo = $this->getCandidateByNo($request->candidateNo);
                $candidateId = $candidateInfo->candidateId;
                Questionnaire::where('candidateId', '=', $candidateId)->update([
                    'agreePoliceCheckCost' => $request->agree
                ]);
                return response()->json([
                    'success' => "1",
                    'message' => "Police Check Cost Agreement updated"
                ]);
            } else {
                return response()->json([
                    'success' => "0",
                    'message' => "Candidate not found or Invalid"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => "0",
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateJobActiveProvider(Request $request)
    {
        try {
            if ($this->validateCandidateByNo($request->candidateNo)) {
                $candidateInfo = $this->getCandidateByNo($request->candidateNo);
                $candidateId = $candidateInfo->candidateId;
                Questionnaire::where('candidateId', '=', $candidateId)->update([
                    'jobActiveStatus' => $request->jobActiveStatus,
                    'jobActiveProvider' => $request->jobActiveProvider,
                ]);
                return response()->json([
                    'success' => "1",
                    'message' => "Job Active Provider Information updated successfully"
                ]);
            } else {
                return response()->json([
                    'success' => "0",
                    'message' => "Candidate not found or Invalid"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => "0",
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateFit2Work1(Request $request)
    {
        try {
            if ($this->validateCandidateByNo($request->candidateNo)) {
                $candidateInfo = $this->getCandidateByNo($request->candidateNo);
                $candidateId = $candidateInfo->candidateId;
                Questionnaire::where('candidateId', '=', $candidateId)->update([
                    'pb_suburb' => $request->fit2wrk_birth_suburb,
                    'pb_state' => $request->fit2wrk_birth_state,
                    'pb_country' => $request->fit2wrk_birth_country,
                    'fw_first_name' => $request->fit2wrk_first_name,
                    'fw_middle_name' => $request->fit2wrk_middle_name,
                    'fw_last_name' => $request->fit2wrk_last_name,
                    'fw_type' => $request->fit2wrk_type,
                    'fw_unit_no1' => $request->fit2wrk_unit_number1,
                    'fw_street_number1' => $request->fit2wrk_street_number1,
                    'fw_street_name1' => $request->fit2wrk_street_name1,
                    'fw_suburb1' => $request->fit2wrk_suburb1,
                    'fw_state1' => $request->fit2wrk_state1,
                    'fw_postcode1' => $request->fit2wrk_postcode1,
                    'fw_country1' => $request->fit2wrk_country1,
                ]);
                return response()->json([
                    'success' => "1",
                    'message' => "Fit2Work 1 Information updated successfully"
                ]);
            } else {
                return response()->json([
                    'success' => "0",
                    'message' => "Candidate not found or Invalid"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => "0",
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateFit2Work2(Request $request)
    {
        try {
            if ($this->validateCandidateByNo($request->candidateNo)) {
                $candidateInfo = $this->getCandidateByNo($request->candidateNo);
                $candidateId = $candidateInfo->candidateId;
                Questionnaire::where('candidateId', '=', $candidateId)->update([
                    'fw_unit_no2' => $request->fit2wrk_unit_number2,
                    'fw_street_number2' => $request->fit2wrk_street_number2,
                    'fw_street_name2' => $request->fit2wrk_street_name2,
                    'fw_suburb2' => $request->fit2wrk_suburb2,
                    'fw_state2' => $request->fit2wrk_state2,
                    'fw_postcode2' => $request->fit2wrk_postcode2,
                    'fw_country2' => $request->fit2wrk_country2,
                    'fw_licence' => $request->fit2wrk_aus_drv_licence_no,
                    'fw_licence_state' => $request->fit2wrk_drv_licence_state,
                    'fw_passport_no' => $request->fit2wrk_passport_number,
                    'fw_passport_country' => $request->fit2wrk_passport_country,
                    'fw_passport_type' => $request->fit2wrk_passport_type
                ]);
                return response()->json([
                    'success' => "1",
                    'message' => "Fit2Work 2 Information updated successfully"
                ]);
            } else {
                return response()->json([
                    'success' => "0",
                    'message' => "Candidate not found or Invalid"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => "0",
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateHealthHistory(Request $request)
    {
        try {
            if ($this->validateCandidateByNo($request->candidateNo)) {
                $candidateInfo = $this->getCandidateByNo($request->candidateNo);
                $candidateId = $candidateInfo->candidateId;
                Questionnaire::where('candidateId', '=', $candidateId)->update([
                    'medicalCondition' => $request->medical_condition,
                    'medConditionDesc' => $request->medical_condition_description,
                    'psycoCondition' => $request->psyco_condition,
                    'psycoConditionDesc' => $request->psyco_condition_description,
                    'alergyCondition' => $request->alergy_condition,
                    'alergyConditionDesc' => $request->alergy_condition_description,
                    'pregnantCondition' => $request->pregnant_condition,
                    'shoulderCondition' => $request->shoulder_condition,
                    'armCondition' => $request->arm_condition,
                    'strainCondition' => $request->strain_condition,
                    'epilepsyCondition' => $request->epilepsy_condition,
                    'hearingCondition' => $request->hearing_condition,
                    'stressCondition' => $request->stress_condition,
                    'fatiqueCondition' => $request->fatique_condition,
                    'asthmaCondition' => $request->asthma_condition,
                    'arthritisCondition' => $request->arthritis_condition,
                    'dizzinessCondition' => $request->dizziness_condition,
                    'headCondition' => $request->head_condition,
                    'speechCondition' => $request->speech_condition,
                    'backCondition' => $request->back_condition,
                    'kneeCondition' => $request->knee_condition,
                    'persistentCondition' => $request->persistent_condition,
                    'skinCondition' => $request->skin_condition,
                    'stomachStrains' => $request->stomach_strains,
                    'visionCondition' => $request->vision_condition,
                    'boneCondition' => $request->bone_condition,
                    'bloodCondition' => $request->blood_condition,
                    'lungCondition' => $request->lung_condition,
                    'surgeryInformation' => $request->surgery_information,
                    'stomachCondition' => $request->stomach_condition,
                    'heartCondition' => $request->heart_condition,
                    'infectiousCondition' => $request->infectious_condition
                ]);
                return response()->json([
                    'success' => "1",
                    'message' => "Health History Information updated successfully"
                ]);
            } else {
                return response()->json([
                    'success' => "0",
                    'message' => "Candidate not found or Invalid"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => "0",
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateHealthMedicalInformation(Request $request)
    {
        try {
            if ($this->validateCandidateByNo($request->candidateNo)) {
                $candidateInfo = $this->getCandidateByNo($request->candidateNo);
                $candidateId = $candidateInfo->candidateId;
                Questionnaire::where('candidateId', '=', $candidateId)->update([
                    'medicalTreatment' => $request->medical_treatment,
                    'medicalTreatmentDesc' => $request->medical_treatment_description,
                    'drowsinessCondition' => $request->drowsiness_condition,
                    'drowsinessConditionDesc' => $request->drowsiness_condition_description,
                    'chronicCondition' => $request->chronic_condition,
                    'chronicConditionDesc' => $request->chronic_condition_description,
                    'workInjury' => $request->work_injury,
                    'workInjuryDesc' => $request->work_injury_description,
                    'workCoverClaim' => $request->work_cover_claim
                ]);
                return response()->json([
                    'success' => "1",
                    'message' => "Health Medical Information updated successfully"
                ]);
            } else {
                return response()->json([
                    'success' => "0",
                    'message' => "Candidate not found or Invalid"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => "0",
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateHealthPhysicalAbilities(Request $request)
    {
        try {
            if ($this->validateCandidateByNo($request->candidateNo)) {
                $candidateInfo = $this->getCandidateByNo($request->candidateNo);
                $candidateId = $candidateInfo->candidateId;
                Questionnaire::where('candidateId', '=', $candidateId)->update([
                    'crouchingCondition' => $request->crouching_condition,
                    'sittingCondition' => $request->sitting_condition,
                    'workShoulderHeight' => $request->work_shoulder_height,
                    'hearingConversation' => $request->hearing_conversation,
                    'workAtHeights' => $request->work_at_heights,
                    'groundCondition' => $request->ground_condition,
                    'handlingFood' => $request->handling_food,
                    'shiftWork' => $request->shift_work,
                    'standingMinutes' => $request->standing_minutes,
                    'liftingCondition' => $request->lifting_condition,
                    'grippingObjects' => $request->gripping_objects,
                    'repetitiveMovement' => $request->repetitive_movement,
                    'walkingStairs' => $request->walking_stairs,
                    'handTools' => $request->hand_tools,
                    'protectiveEquipment' => $request->protective_equipment,
                    'workHeights' => $request->work_heights,
                    'workConfinedSpaces' => $request->work_confined_spaces,
                    'workHotColdEnvironment' => $request->work_hot_cold_environment
                ]);
                return response()->json([
                    'success' => "1",
                    'message' => "Health Physical Abilities updated successfully"
                ]);
            } else {
                return response()->json([
                    'success' => "0",
                    'message' => "Candidate not found or Invalid"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => "0",
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updatePrivacyPolicySignature(Request $request)
    {
        try {
            if ($this->validateCandidateByNo($request->candidateNo)) {
                $candidateInfo = $this->getCandidateByNo($request->candidateNo);
                $candidateId = $candidateInfo->candidateId;
                $file = $request->file('signature');
                $fileName = $file->getClientOriginalName();
                $path = $file->move('../documents/' . $candidateId . '/', $fileName);
                /*if (!empty($request->signature)) {
                    $signatureImage = addslashes(base64_encode(file_get_contents($request->signature)));
                } else {
                    $signatureImage = '';
                }*/
                DB::table('employee_signature')
                    ->where('candidateId', '=', $candidateId)
                    ->update(['signature' => './documents/' . $candidateId . '/' . $fileName,
                        'updated_at' => date('Y-m-d H:i:s')]);
                $this->generatePDF($candidateId);
                return response()->json([
                    'success' => "1",
                    'message' => "Privacy policy signed successfully"
                ]);
            } else {
                return response()->json([
                    'success' => "0",
                    'message' => "Candidate not found or Invalid"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => "0",
                'message' => $e->getMessage()
            ]);
        }
    }

    public function generatePDF($canId)
    {
        $candidateInfo = DB::table('candidate')->select('candidateId',
            'title',
            'firstName',
            'middle_name',
            'lastName',
            'fullName',
            'address',
            'unit_no',
            'street_number',
            'street_name',
            'state',
            'postcode',
            'homePhoneNo',
            'mobileNo',
            'email',
            'sex',
            'suburb',
            'residentStatus',
            'status',
            'dob',
            'candidateStatus',
            'tfn',
            'superMemberNo',
            'empStatus',
            'empCondition',
            'reg_pack_status')->where('candidateId', '=', $canId)->get();
        $profileImage = DB::table('candidate_document')->where('candidateId', '=', $canId)->where('docTypeId', '=', 17)->first();
        $fPath = '';
        if (!empty($profileImage->filePath)) {
            $fPath = $profileImage->filePath;
            $parts = explode('./',$fPath);
            $realPath = '/var/www/html/'.$parts[1];
        }
        $questionnaireInfo = DB::table('questionnaire')->select('paidBasis',
            'taxClaim',
            'taxHelp',
            'taxResident',
            'jobActiveStatus',
            'jobActiveProvider',
            'workprocin',
            'emcName',
            'emcRelationship',
            'emcState',
            'emcMobile',
            'emcHomePhone',
            'referee1Name',
            'referee1CompanyName',
            'referee1Position',
            'referee1Relationship',
            'referee1Mobile',
            'referee2Name',
            'referee2CompanyName',
            'referee2Position',
            'referee2Relationship',
            'referee2Mobile',
            'superAccountName',
            'superFundName',
            'superFundAddress',
            'superPhoneNo',
            'superWebsite',
            'superFundABN',
            'superFundUSI',
            'medicalCondition',
            'medConditionDesc',
            'psycoCondition',
            'psycoConditionDesc',
            'alergyCondition',
            'alergyConditionDesc',
            'pregnantCondition',
            'shoulderCondition',
            'armCondition',
            'strainCondition',
            'epilepsyCondition',
            'hearingCondition',
            'stressCondition',
            'fatiqueCondition',
            'asthmaCondition',
            'arthritisCondition',
            'dizzinessCondition',
            'headCondition',
            'speechCondition',
            'backCondition',
            'kneeCondition',
            'persistentCondition',
            'skinCondition',
            'stomachStrains',
            'visionCondition',
            'boneCondition',
            'bloodCondition',
            'lungCondition',
            'surgeryInformation',
            'stomachCondition',
            'heartCondition',
            'infectiousCondition',
            'medicalTreatment',
            'medicalTreatmentDesc',
            'drowsinessCondition',
            'drowsinessConditionDesc',
            'chronicCondition',
            'chronicConditionDesc',
            'workInjury',
            'workInjuryDesc',
            'workCoverClaim',
            'crouchingCondition',
            'sittingCondition',
            'workShoulderHeight',
            'hearingConversation',
            'workAtHeights',
            'groundCondition',
            'handlingFood',
            'shiftWork',
            'standingMinutes',
            'liftingCondition',
            'grippingObjects',
            'repetitiveMovement',
            'walkingStairs',
            'handTools',
            'protectiveEquipment',
            'workHeights',
            'workConfinedSpaces',
            'workHotColdEnvironment',
            'superFundCheck',
            'policeCheck',
            'statOccupation',
            'crimeCheck',
            'crimeDate1',
            'crime1',
            'crimeDate2',
            'crime2',
            'optionChk',
            'neverConvicted',
            'neverImprisonment',
            'pb_suburb',
            'pb_state',
            'pb_country',
            'fw_first_name',
            'fw_middle_name',
            'fw_last_name',
            'fw_unit_no1',
            'fw_street_number1',
            'fw_street_name1',
            'fw_suburb1',
            'fw_state1',
            'fw_postcode1',
            'fw_country1',
            'fw_unit_no2',
            'fw_street_number2',
            'fw_street_name2',
            'fw_suburb2',
            'fw_state2',
            'fw_postcode2',
            'fw_country2',
            'fw_licence',
            'fw_licence_state',
            'fw_passport_no',
            'fw_passport_country',
            'fw_type',
            'fw_passport_type',
            'created_at',
            'updated_at')->where('candidateId', '=', $canId)->get();
        $visaInfo = DB::table('employee_visatype')->select('empVisaTypeId', 'expiryDate')->where('candidateId', '=', $canId)->get();
        $bankInfo = DB::table('employee_bank_account')->select('bankName', 'accountName', 'accountNumber', 'bsb')->where('candidateId', '=', $canId)->get();
        try {
            $signatureInfo = DB::table('employee_signature')->where('candidateId', '=', $canId)->first();
            $signaturePath = '';
            if (!empty($signatureInfo->signature)) {
                $signaturePath = $signatureInfo->signature;
                $parts = explode('./',$signaturePath);
                $realSignaturePath = '/var/www/html/'.$parts[1];
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => "0",
                'message' => "Signature retrieval error " . $e->getMessage()], 500);
        }
        if (isset($realSignaturePath)) {
            foreach ($candidateInfo as $casual) {
                $candidateId = $casual->candidateId;
                $title = $casual->title;
                $firstName = $casual->firstName;
                $middle_name = $casual->middle_name;
                $lastName = $casual->lastName;
                $fullName = $casual->fullName;
                $address = $casual->address;
                $unit_no = $casual->unit_no;
                $street_number = $casual->street_number;
                $street_name = $casual->street_name;
                $state = $casual->state;
                $postcode = $casual->postcode;
                $homePhoneNo = $casual->homePhoneNo;
                $mobileNo = $casual->mobileNo;
                $email = $casual->email;
                $sex = $casual->sex;
                $suburb = $casual->suburb;
                $residentStatus = $casual->residentStatus;
                $status = $casual->status;
                $dob = $casual->dob;
                $candidateStatus = $casual->candidateStatus;
                $tfn = $casual->tfn;
                $superMemberNo = $casual->superMemberNo;
                $empStatus = $casual->empStatus;
                $empCondition = $casual->empCondition;
                $reg_pack_status = $casual->reg_pack_status;
            }
            foreach ($questionnaireInfo as $qs) {
                $paidBasis = $qs->paidBasis;
                $taxClaim = $qs->taxClaim;
                $taxHelp = $qs->taxHelp;
                $taxResident = $qs->taxResident;
                $jobActiveStatus = $qs->jobActiveStatus;
                $jobActiveProvider = $qs->jobActiveProvider;
                $workprocin = $qs->workprocin;
                $emcName = $qs->emcName;
                $emcRelationship = $qs->emcRelationship;
                $emcMobile = $qs->emcMobile;
                $emcHomePhone = $qs->emcHomePhone;
                $referee1Name = $qs->referee1Name;
                $referee1CompanyName = $qs->referee1CompanyName;
                $referee1Position = $qs->referee1Position;
                $referee1Relationship = $qs->referee1Relationship;
                $referee1Mobile = $qs->referee1Mobile;
                $referee2Name = $qs->referee2Name;
                $referee2CompanyName = $qs->referee2CompanyName;
                $referee2Position = $qs->referee2Position;
                $referee2Relationship = $qs->referee2Relationship;
                $referee2Mobile = $qs->referee2Mobile;
                $superFundCheck = $qs->superFundCheck;
                $superAccountName = $qs->superAccountName;
                $superFundName = $qs->superFundName;
                $superFundAddress = $qs->superFundAddress;
                $superPhoneNo = $qs->superPhoneNo;
                $superWebsite = $qs->superWebsite;
                $superFundABN = $qs->superFundABN;
                $superFundUSI = $qs->superFundUSI;
                $medicalCondition = $qs->medicalCondition;
                $medConditionDesc = $qs->medConditionDesc;
                $psycoCondition = $qs->psycoCondition;
                $psycoConditionDesc = $qs->psycoConditionDesc;
                $alergyCondition = $qs->alergyCondition;
                $alergyConditionDesc = $qs->alergyConditionDesc;
                $pregnantCondition = $qs->pregnantCondition;
                $shoulderCondition = $qs->shoulderCondition;
                $armCondition = $qs->armCondition;
                $strainCondition = $qs->strainCondition;
                $epilepsyCondition = $qs->epilepsyCondition;
                $hearingCondition = $qs->hearingCondition;
                $stressCondition = $qs->stressCondition;
                $fatiqueCondition = $qs->fatiqueCondition;
                $asthmaCondition = $qs->asthmaCondition;
                $arthritisCondition = $qs->arthritisCondition;
                $dizzinessCondition = $qs->dizzinessCondition;
                $headCondition = $qs->headCondition;
                $speechCondition = $qs->speechCondition;
                $backCondition = $qs->backCondition;
                $kneeCondition = $qs->kneeCondition;
                $persistentCondition = $qs->persistentCondition;
                $skinCondition = $qs->skinCondition;
                $stomachStrains = $qs->stomachStrains;
                $visionCondition = $qs->visionCondition;
                $boneCondition = $qs->boneCondition;
                $bloodCondition = $qs->bloodCondition;
                $lungCondition = $qs->lungCondition;
                $surgeryInformation = $qs->surgeryInformation;
                $stomachCondition = $qs->stomachCondition;
                $heartCondition = $qs->heartCondition;
                $infectiousCondition = $qs->infectiousCondition;
                $medicalTreatment = $qs->medicalTreatment;
                $medicalTreatmentDesc = $qs->medicalTreatmentDesc;
                $drowsinessCondition = $qs->drowsinessCondition;
                $drowsinessConditionDesc = $qs->drowsinessConditionDesc;
                $chronicCondition = $qs->chronicCondition;
                $chronicConditionDesc = $qs->chronicConditionDesc;
                $workInjury = $qs->workInjury;
                $workInjuryDesc = $qs->workInjuryDesc;
                $workCoverClaim = $qs->workCoverClaim;
                $crouchingCondition = $qs->crouchingCondition;
                $sittingCondition = $qs->sittingCondition;
                $workShoulderHeight = $qs->workShoulderHeight;
                $hearingConversation = $qs->hearingConversation;
                $workAtHeights = $qs->workAtHeights;
                $groundCondition = $qs->groundCondition;
                $handlingFood = $qs->handlingFood;
                $shiftWork = $qs->shiftWork;
                $standingMinutes = $qs->standingMinutes;
                $liftingCondition = $qs->liftingCondition;
                $grippingObjects = $qs->grippingObjects;
                $repetitiveMovement = $qs->repetitiveMovement;
                $walkingStairs = $qs->walkingStairs;
                $handTools = $qs->handTools;
                $protectiveEquipment = $qs->protectiveEquipment;
                $workHeights = $qs->workHeights;
                $workConfinedSpaces = $qs->workConfinedSpaces;
                $workHotColdEnvironment = $qs->workHotColdEnvironment;

                $policeCheck = $qs->policeCheck;
                $statOccupation = $qs->statOccupation;
                $crimeCheck = $qs->crimeCheck;
                $crimeDate1 = $qs->crimeDate1;
                $crime1 = $qs->crime1;
                $crimeDate2 = $qs->crimeDate2;
                $crime2 = $qs->crime2;
                $optionChk = $qs->optionChk;
                $neverConvicted = $qs->neverConvicted;
                $neverImprisonment = $qs->neverImprisonment;
                $pb_suburb = $qs->pb_suburb;
                $pb_state = $qs->pb_state;
                $pb_country = $qs->pb_country;
                $fw_first_name = $qs->fw_first_name;
                $fw_middle_name = $qs->fw_middle_name;
                $fw_last_name = $qs->fw_last_name;
                $fw_unit_no1 = $qs->fw_unit_no1;
                $fw_street_number1 = $qs->fw_street_number1;
                $fw_street_name1 = $qs->fw_street_name1;
                $fw_suburb1 = $qs->fw_suburb1;
                $fw_state1 = $qs->fw_state1;
                $fw_postcode1 = $qs->fw_postcode1;
                $fw_country1 = $qs->fw_country1;
                $fw_unit_no2 = $qs->fw_unit_no2;
                $fw_street_number2 = $qs->fw_street_number2;
                $fw_street_name2 = $qs->fw_street_name2;
                $fw_suburb2 = $qs->fw_suburb2;
                $fw_state2 = $qs->fw_state2;
                $fw_postcode2 = $qs->fw_postcode2;
                $fw_country2 = $qs->fw_country2;
                $fw_licence = $qs->fw_licence;
                $fw_licence_state = $qs->fw_licence_state;
                $fw_passport_no = $qs->fw_passport_no;
                $fw_passport_country = $qs->fw_passport_country;
                $fw_type = $qs->fw_type;
                $fw_passport_type = $qs->fw_passport_type;
            }
            foreach ($visaInfo as $vsa) {
                $empVisaTypeId = $vsa->empVisaTypeId;
                $expiryDate = $vsa->expiryDate;
            }
            foreach ($bankInfo as $bnk) {
                $bankName = $bnk->bankName;
                $accountName = $bnk->accountName;
                $accountNumber = $bnk->accountNumber;
                $bsb = $bnk->bsb;
            }

            $pdf = new RegistrationPDF('P', 'mm', 'A4', true, 'UTF-8', false, $canId);
            $pdf->setCandidateId($canId);
            $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', 8));
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH);
            $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
            $pdf->SetMargins(10, 30, 10);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
            if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
                require_once(dirname(__FILE__) . '/lang/eng.php');
                $pdf->setLanguageArray($l);
            }
            $pdf->SetFont('helvetica', '', 8);
            $pdf->startPageGroup();
            $pdf->AddPage();
            $html = '';
            $html = $html . '<br><style></style>';
            $html = $html . '<table style="width: 80%" border="0" cellspacing="2" cellpadding="2" nobr="true">
                                <tbody>
                                <tr><td colspan="2"><b>PERSONAL INFORMATION</b></td></tr>
                                  <tr>
                                    <td width="100%" colspan="2" style="text-align: center"><div><img src="'.$realPath.'" width="80" height="80" class="pro_image"/></div></td>
                                  </tr>
                                  <tr>
                                    <td width="30%">Title:</td>
                                    <td width="70%"><b>'.$title.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="30%">First Name:</td>
                                    <td width="70%"><b>'.$firstName.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="30%">Middle Name:</td>
                                    <td width="70%"><b>'.$middle_name.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="30%">Last Name:</td>
                                    <td width="70%"><b>'.$lastName.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="30%">Gender:</td>
                                    <td width="70%"><b>'.$sex.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="30%">Date of birth:</td>
                                    <td width="70%"><b>'.$dob.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="30%">Address:</td>
                                    <td width="70%"><b>'.$address.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="30%">Unit Number:</td>
                                    <td width="70%"><b>'.$unit_no.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="30%">Street Number:</td>
                                    <td width="70%"><b>'.$street_number.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="30%">Street Name:</td>
                                    <td width="70%"><b>'.$street_name.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="30%">Suburb:</td>
                                    <td width="70%"><b>'.$suburb.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="30%">State:</td>
                                    <td width="70%"><b>'.$state.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="30%">Postcode:</td>
                                    <td width="70%"><b>'.$postcode.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="30%">Mobile:</td>
                                    <td width="70%"><b>'.$mobileNo.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="30%">Email:</td>
                                    <td width="70%"><b>'.$email.'</b></td>
                                  </tr>
                                </tbody>
                             </table>
                             <br>
                             <div>Are you currently registered with any jobactive provider? If yes kindly specify the provider name</div>';
            $html = $html.'<i';
            if ($jobActiveStatus == 'Yes') {
                $html = $html . ' style="color:red"';
            }
            $html = $html.'><b>'.$jobActiveStatus.'</b></i>';
            $html = $html.'<div><b>'.$jobActiveProvider.'</b></div>';
            $html = $html.'<br><label>Residential Status: </label><i><b>' . $residentStatus.'</b></i>';
            $html = $html.'<br><br><label>WorkPro CIN: </label><i><b>'. $workprocin.'</b></i>';
            $html = $html.'<br><br><table style="width: 80%" border="0" cellspacing="2" cellpadding="2" nobr="true">
                                <tbody>
                                <tr><td colspan="2"><b>EMERGENCY CONTACT INFORMATION</b></td></tr>
                                  <tr>
                                    <td width="30%">Name:</td>
                                    <td width="70%"><b>'.$emcName.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="30%">Relationship:</td>
                                    <td width="70%"><b>'.$emcRelationship.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="30%">Mobile Number:</td>
                                    <td width="70%"><b>'.$emcMobile.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="30%">Home Phone Number:</td>
                                    <td width="70%"><b>'.$emcHomePhone.'</b></td>
                                  </tr>
                                </tbody>
                                </table>';
            $html = $html.'<br><table style="width: 80%" border="0" cellspacing="2" cellpadding="2" nobr="true">
                                <tbody>
                                <tr><td colspan="2"><b>REFEREE 1 INFORMATION</b></td></tr>
                                  <tr>
                                    <td width="30%">Referee 1 Name:</td>
                                    <td width="70%"><b>'.$referee1Name.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="30%">Referee 1 Company Name:</td>
                                    <td width="70%"><b>'.$referee1CompanyName.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="30%">Referee 1 Position:</td>
                                    <td width="70%"><b>'.$referee1Position.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="30%">Referee 1 Relationship:</td>
                                    <td width="70%"><b>'.$referee1Relationship.'</b></td>
                                  </tr>
                                </tbody>
                                </table>';
            $html = $html.'<br><table style="width: 80%" border="0" cellspacing="2" cellpadding="2" nobr="true">
                                <tbody>
                                <tr><td colspan="2"><b>REFEREE 2 INFORMATION</b></td></tr>
                                  <tr>
                                    <td width="30%">Referee 2 Name:</td>
                                    <td width="70%"><b>'.$referee2Name.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="30%">Referee 2 Company Name:</td>
                                    <td width="70%"><b>'.$referee2CompanyName.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="30%">Referee 2 Position:</td>
                                    <td width="70%"><b>'.$referee2Position.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="30%">Referee 2 Relationship:</td>
                                    <td width="70%"><b>'.$referee2Relationship.'</b></td>
                                  </tr>
                                </tbody>
                                </table>';
            $html = $html.'<br><table style="width: 80%" border="0" cellspacing="2" cellpadding="2" nobr="true">
                                <tbody>
                                <tr><td colspan="2"><b>BANK ACCOUNT INFORMATION</b></td></tr>
                                  <tr>
                                    <td width="30%">Bank Account Name:</td>
                                    <td width="70%"><b>'.$accountName.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="30%">Bank Name:</td>
                                    <td width="70%"><b>'.$bankName.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="30%">BSB:</td>
                                    <td width="70%"><b>'.$accountNumber.'</b></td>
                                  </tr>
                                </tbody>
                                </table>';
            $html = $html.'<br><table style="width: 80%" border="0" cellspacing="2" cellpadding="2" nobr="true">
                                <tbody>
                                <tr><td colspan="2"><b>TAX DECLARATION INFORMATION</b></td></tr>
                                  <tr>
                                    <td width="30%">Tax File Number:</td>
                                    <td width="70%"><b>'.$tfn.'</b></td>
                                  </tr>
                                  <tr>
                                    <td colspan="2">On what basis are you paid? <b>'.$paidBasis.'</b></td>
                                  </tr>
                                  <tr>
                                    <td colspan="2">Are you: <b>'.$taxResident.'</b></td>
                                  </tr>
                                  <tr>
                                    <td colspan="2"><p>Do you want to claim the tax-free threshold from this payer? </p><p>Only claim the tax‑free threshold from one payer at a time, unless your total income from all sources for the financial year will be less than the tax‑free threshold.</p><b>'.$taxClaim.'</b><p>Answer no here if you are a foreign resident or working holiday maker, except if you are a foreign resident in receipt of an Australian Government pension or allowance</p></td>
                                  </tr>
                                  <tr>
                                    <td colspan="2"><p>Do you have a Higher Education Loan Program (HELP), VET Student Loan (VSL), Financial Supplement (FS), Student Start-up Loan (SSL) or Trade Support Loan (TSL) debt?</p><b>'.$taxHelp.'</b><p>Your payer will withhold additional amounts to cover any compulsory Yes repayment that may be raised on your notice of assessment.</p></td>
                                  </tr>
                                </tbody>
                                </table>';
            $html = $html.'<br><table style="width: 80%" border="0" cellspacing="2" cellpadding="2" nobr="true">
                                <tbody>
                                <tr><td colspan="2"><b>SUPER FUND INFORMATION</b></td></tr>';
            if($superFundCheck == 'DO NOT HAVE OWN SUPER ACCOUNT') {
                $html = $html . '<tr>
                                <td colspan="2"><b>' . $superFundCheck . '</b></td>
                           </tr>';
            }
                $html = $html.'<tr>
                                <td width="30%">Super Account Name:</td>
                                <td width="70%"><b>'.$superAccountName.'</b></td>
                           </tr>
                           <tr>
                                <td width="30%">Super Fund Name:</td>
                                <td width="70%"><b>'.$superFundName.'</b></td>
                           </tr>
                           <tr>
                                <td width="30%">Super Member Name:</td>
                                <td width="70%"><b>'.$superMemberNo.'</b></td>
                           </tr>
                           <tr>
                                <td width="30%">Super Fund Address:</td>
                                <td width="70%"><b>'.$superFundAddress.'</b></td>
                           </tr>
                           <tr>
                                <td width="30%">Super Phone Number:</td>
                                <td width="70%"><b>'.$superFundName.'</b></td>
                           </tr>
                           <tr>
                                <td width="30%">Super Website:</td>
                                <td width="70%"><b>'.$superWebsite.'</b></td>
                           </tr>
                           <tr>
                                <td width="30%">Super Fund ABN:</td>
                                <td width="70%"><b>'.$superFundABN.'</b></td>
                           </tr>
                           <tr>
                                <td width="30%">Super Fund USI:</td>
                                <td width="70%"><b>'.$superFundUSI.'</b></td>
                           </tr>
                           <tr>
                                <td colspan="2"><p>Your employer is not required to accept your choice of fund if you have not provided the appropriate documents</p></td>
                           </tr></tbody></table>';
            $html = $html.'<br><table style="width: 80%" border="0" cellspacing="2" cellpadding="2" nobr="true">
                                <tbody>
                                  <tr><td colspan="2"><b>POLICE CHECK INFORMATION</b></td></tr>
                                  <tr>
                                    <td colspan="2">Do you have a Australian police clearance ? <b>'.$policeCheck.'</b></td>
                                  </tr>
                                  <tr>
                                    <td colspan="2">Do you have any prior or pending criminal history? <b>'.$crimeCheck.'</b></td>
                                  </tr>
                                  <tr>
                                    <td colspan="2">
                                        <table style="width: 100%" border="0" cellspacing="2" cellpadding="2" nobr="true">
                                            <tbody>
                                                <tr><td><b>STATUTORY DECLARATION</b></td></tr>
                                                <tr>
                                                    <td>
                                                    <p>I, '.$firstName.' '.$lastName.' of '.$address.' '.$statOccupation.', do solemnly and sincerely declare that:-</p>
                                                    <p><b>';
            if($neverConvicted == 'Yes'){
            $html = $html.'<span style="font-family:zapfdingbats;font-size: 14pt;">3</span>';
            }else{
            $html = $html.' ';
            }
            $html = $html.' I have never been convicted of a criminal offense in Australia <br>';
            if($neverImprisonment == 'Yes'){
            $html = $html.'<span style="font-family:zapfdingbats;font-size: 14pt;">3</span>';
            }else{
            $html = $html.' ';
            }
            $html = $html.' I have never been convicted of a criminal offence and/or sentenced to imprisonment in any country other than Australia</b></p>
                                                    <p>
                                                    <b>I acknowledge that this declaration is true and correct, and I make it with the understanding and belief that a person who makes a false declaration is liable to the penalties of perjury.</b>
                                                    </p>
                                                    <p>Declared at Level 9, 10 Queen St, Melbourne</p>
                                                    <p><b>this  '.date('d').' day of '.date('M').' '.date('Y').'</b></p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td colspan="2">
                                        <table style="width: 100%" border="0" cellspacing="2" cellpadding="2" nobr="true">
                                            <tbody>
                                                <tr><td colspan="2"><b>POLICE CHECK AUTHORITY FORM DECLARATION</b></td></tr>
                                                <tr>
                                                    <td width="40%">Date:</td>
                                                    <td width="40%">Nature of Offence:</td>
                                                </tr>
                                                <tr>
                                                    <td width="40%"><b>'.$crimeDate1.'</b></td>
                                                    <td width="40%"><b>'.$crime1.'</b></td>
                                                </tr>
                                                <tr>
                                                    <td width="40%"><b>'.$crimeDate2.'</b></td>
                                                    <td width="40%"><b>'.$crime2.'</b></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td colspan="2">
                                        <b>Please tick the most suitable box.</b>
                                    </td>
                                  </tr>';
            if ($optionChk == 'option1') {
                $html = $html.'<p><b><span style="font-family:zapfdingbats;font-size: 14pt;">3</span>';
                $html = $html.' I hold a previously completed National Police Check (within 3 years)</b></p>';
            }else{
                $html = $html.'<p><b><span style="font-family:zapfdingbats;font-size: 14pt;">3</span>';
                $html = $html .' I have completed the enclosed Application Form and provided sufficient ID so that Chandler Personnel can conduct a National Police Check on my behalf</b></p>';
            }
            $html = $html.'<tr>
                            <td colspan="2">
                                <table style="width: 100%" border="0" cellspacing="2" cellpadding="2" nobr="true">
                                    <tbody>
                                        <tr>
                                            <td colspan="2"><b>FIT2WORK</b></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><u>Place of bith:</u></td>
                                        </tr>
                                        <tr>
                                            <td width="40%">Suburb:</td>
                                            <td width="40%"><b>'.$pb_suburb.'</b></td>
                                        </tr>
                                        <tr>
                                            <td width="40%">State:</td>
                                            <td width="40%"><b>'.$pb_state.'</b></td>
                                        </tr>
                                        <tr>
                                            <td width="40%">Country</td>
                                            <td width="40%"><b>'.$pb_country.'</b></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><u>Additional Detials:</u>  Previous names(if applicable)</td>
                                        </tr>
                                        <tr>
                                            <td width="40%">First Name:</td>
                                            <td width="40%"><b>'.$fw_first_name.'</b></td>
                                        </tr>
                                        <tr>
                                            <td width="40%">Middle Name:</td>
                                            <td width="40%"><b>'.$fw_middle_name.'</b></td>
                                        </tr>
                                        <tr>
                                            <td width="40%">Last Name:</td>
                                            <td width="40%"><b>'.$fw_last_name.'</b></td>
                                        </tr>
                                        <tr>
                                            <td width="40%">Type:</td>
                                            <td width="40%"><b>'.$fw_type.'</b></td>
                                        </tr>
                                         <tr>
                                            <td colspan="2"><u>5 Year Previous Address:</u></td>
                                        </tr>
                                        <tr>
                                            <td width="40%">Unit Number:</td>
                                            <td width="40%"><b>'.$fw_unit_no1.'</b></td>
                                        </tr>
                                        <tr>
                                            <td width="40%">Street Number:</td>
                                            <td width="40%"><b>'.$fw_street_number1.'</b></td>
                                        </tr>
                                        <tr>
                                            <td width="40%">Street Name:</td>
                                            <td width="40%"><b>'.$fw_street_name1.'</b></td>
                                        </tr>
                                        <tr>
                                            <td width="40%">Suburb:</td>
                                            <td width="40%"><b>'.$fw_suburb1.'</b></td>
                                        </tr>
                                        <tr>
                                            <td width="40%">State:</td>
                                            <td width="40%"><b>'.$fw_state1.'</b></td>
                                        </tr>
                                        <tr>
                                            <td width="40%">Post Code:</td>
                                            <td width="40%"><b>'.$fw_postcode1.'</b></td>
                                        </tr>
                                        <tr>
                                            <td width="40%">Country:</td>
                                            <td width="40%"><b>'.$fw_country1.'</b></td>
                                        </tr>
                                         <tr>
                                            <td colspan="2"><u>5 Year Previous Address:</u></td>
                                        </tr>
                                        <tr>
                                            <td width="40%">Unit Number:</td>
                                            <td width="40%"><b>'.$fw_unit_no2.'</b></td>
                                        </tr>
                                        <tr>
                                            <td width="40%">Street Number:</td>
                                            <td width="40%"><b>'.$fw_street_number2.'</b></td>
                                        </tr>
                                        <tr>
                                            <td width="40%">Street Name:</td>
                                            <td width="40%"><b>'.$fw_street_name2.'</b></td>
                                        </tr>
                                        <tr>
                                            <td width="40%">Suburb:</td>
                                            <td width="40%"><b>'.$fw_suburb2.'</b></td>
                                        </tr>
                                        <tr>
                                            <td width="40%">State:</td>
                                            <td width="40%"><b>'.$fw_state2.'</b></td>
                                        </tr>
                                        <tr>
                                            <td width="40%">Post Code:</td>
                                            <td width="40%"><b>'.$fw_postcode2.'</b></td>
                                        </tr>
                                        <tr>
                                            <td width="40%">Country:</td>
                                            <td width="40%"><b>'.$fw_country2.'</b></td>
                                        </tr>
                                         <tr>
                                            <td colspan="2"><u>Documents:</u></td>
                                        </tr>
                                        <tr>
                                            <td width="40%">Aust. Drivers Licence No.:</td>
                                            <td width="40%"><b>'.$fw_licence.'</b></td>
                                        </tr>
                                        <tr>
                                            <td width="40%">State/Territory:</td>
                                            <td width="40%"><b>'.$fw_licence_state.'</b></td>
                                        </tr>
                                        <tr>
                                            <td width="40%">Passport Number:</td>
                                            <td width="40%"><b>'.$fw_passport_no.'</b></td>
                                        </tr>
                                        <tr>
                                            <td width="40%">Passport Country:</td>
                                            <td width="40%"><b>'.$fw_passport_country.'</b></td>
                                        </tr>
                                        <tr>
                                            <td width="40%">Passport Type:</td>
                                            <td width="40%"><b>'.$fw_passport_type.'</b></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                          </tr>
                        </tbody>
                        </table>';
            $html = $html.'<br><br><table style="width: 90%" border="0" cellspacing="2" cellpadding="2" nobr="true">
                                <tbody>
                                  <tr><td colspan="2"><b>HEALTH QUESTIONNAIRE</b></td></tr>
                                  <tr>
                                    <td colspan="2">
                                        <p>Health and safety of our employees is of utmost importance to chandler recruitment. This questionnaire is designed to assist us in ensuring that our employees are only placed in the assignments which they are capable of performing efficiently and in a safely manner.</p>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td colspan="2">
                                        <p>Please read this document carefully and discuss any queries that you may have prior to completing the form with your respective Chandler Recruitment Consultants.</p>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td colspan="2">
                                        <p><b>IMPORTANT:</b> The information obtained in this questionnaire will be treated in strict confidence and will only be used in conjunction with the requirements of an assignment.</p>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td colspan="2">
                                        <p><b>INJURY DECLARATION</b></p>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td colspan="2">
                                        <p>You are required to disclose to Chandler Recruitment Consultants any or all existing or pre-existing injuries, illnesses or diseases suffered by you which could be accelerated, aggravated, deteriorate or recur by you performing the responsibilities associated with the employment for which you are applying with Chandler Recruitment Consultants.</p>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td colspan="2">
                                        <p>If you fail to disclose this information or if you provide false and misleading information in relation to any pre-existing injury/condition you and your dependents may not be entitled to any form of workers’ compensation and this may also constitute grounds for disciplinary action or dismissal.</p>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td colspan="2">
                                        <p><b>SECTION A : HEALTH HISTORY</b></p>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Please select the appropriate answer: Have you ever been medically retired on the grounds of ill health?</p></td>
                                    <td width="30%">';
            $html = $html.'<i';
            if ($medicalCondition == 'Yes') {
                $html = $html . ' style="color:red"';
            }
            $html = $html.'><b>'.$medicalCondition.'</b></i>';
            $html = $html.'<br><b>'.$medConditionDesc.'</b>';
            $html = $html.'</td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Do you have a physical or psychological condition that might preclude you from some work duties or certain workplace environments (eg. asthma, Hay fever, vertigo)?</p></td>
                                    <td width="30%">';
            $html = $html.'<i';
            if ($psycoCondition == 'Yes') {
                $html = $html . ' style="color:red"';
            }
            $html = $html.'><b>'.$psycoCondition.'</b></i>';
            $html = $html.'<br><b>'.$psycoConditionDesc.'</b>';
            $html = $html.'</td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Do you suffer from any allergies?</p></td>
                                    <td width="30%">';
            $html = $html.'<i';
            if ($alergyCondition == 'Yes') {
                $html = $html . ' style="color:red"';
            }
            $html = $html.'><b>'.$alergyCondition.'</b></i>';
            $html = $html.'<br><b>'.$alergyConditionDesc.'</b>';
            $html = $html.'</td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Some work duties and workplace environments may not be advisable for pregnant women. If you wish to indicate that you are pregnant you may do so voluntarily here.</p></td>
                                    <td width="30%"><b>'.$pregnantCondition.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Any neck or shoulder injuries/pain</p></td>
                                    <td width="30%"><b>'.$shoulderCondition.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Any arm, hand, elbow or wrist injury/pain </p></td>
                                    <td width="30%"><b>'.$armCondition.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Repetitive strains or overuse injury </p></td>
                                    <td width="30%"><b>'.$strainCondition.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Epilepsy, fits, seizures, blackouts </p></td>
                                    <td width="30%"><b>'.$epilepsyCondition.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Loss of hearing, Impaired Hearing</p></td>
                                    <td width="30%"><b>'.$hearingCondition.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Stress/Anxiety or nervous disorder </p></td>
                                    <td width="30%"><b>'.$stressCondition.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Fatigue / tiredness related issues</p></td>
                                    <td width="30%"><b>'.$fatiqueCondition.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Asthma or other respiratory/breathing problems</p></td>
                                    <td width="30%"><b>'.$asthmaCondition.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Arthritis, rheumatism</p></td>
                                    <td width="30%"><b>'.$arthritisCondition.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Dizziness, fainting, vertigo</p></td>
                                    <td width="30%"><b>'.$dizzinessCondition.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Head Injury</p></td>
                                    <td width="30%"><b>'.$headCondition.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Speech impairment</p></td>
                                    <td width="30%"><b>'.$speechCondition.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Any back injury/pain e.g. Scoliosis</p></td>
                                    <td width="30%"><b>'.$backCondition.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Any knee, leg or ankle pain/injury </p></td>
                                    <td width="30%"><b>'.$kneeCondition.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Persistent or frequent headaches, migraines</p></td>
                                    <td width="30%"><b>'.$persistentCondition.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Skin disorders, dermatitis, eczema</p></td>
                                    <td width="30%"><b>'.$skinCondition.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Any stomach strains/hernias etc</p></td>
                                    <td width="30%"><b>'.$stomachStrains.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Difficulty with vision or sight in either eye, Impaired Vision</p></td>
                                    <td width="30%"><b>'.$visionCondition.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Any problems with bones/joints or muscles</p></td>
                                    <td width="30%"><b>'.$boneCondition.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>High / Low blood pressure</p></td>
                                    <td width="30%"><b>'.$bloodCondition.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Lung disorders/ Nerve disorders</p></td>
                                    <td width="30%"><b>'.$lungCondition.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Any operations or surgery? If Yes Please give details</p></td>
                                    <td width="30%"><b>'.$surgeryInformation.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Stomach problems, ulcers</p></td>
                                    <td width="30%"><b>'.$stomachCondition.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Heart trouble, angina</p></td>
                                    <td width="30%"><b>'.$heartCondition.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Infectious disease</p></td>
                                    <td width="30%"><b>'.$infectiousCondition.'</b></td>
                                  </tr>
                                  <tr>
                                    <td colspan="2">
                                        <p><b>SECTION B : MEDICAL DETAILS</b></p>
                                    </td>
                                  </tr>
                                  <tr><td colspan="2"><b>Please select the appropriate answer:</b></td></tr>
                                  <tr>
                                    <td width="70%"><p>Are you currently receiving any medical treatment for illness, injury or medical condition?</p></td>
                                    <td width="30%">';
            $html = $html.'<i';
            if ($medicalTreatment == 'Yes') {
                $html = $html . ' style="color:red"';
            }
            $html = $html.'><b>'.$medicalTreatment.'</b></i>';
            $html = $html.'<br><b>'.$medicalTreatmentDesc.'</b>';
            $html = $html.'</td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Are you taking any medication that has the potential to cause drowsiness or affect your work performance (including operating machinery?</p></td>
                                    <td width="30%">';
            $html = $html.'<i';
            if ($drowsinessCondition == 'Yes') {
                $html = $html . ' style="color:red"';
            }
            $html = $html.'><b>'.$drowsinessCondition.'</b></i>';
            $html = $html.'<br><b>'.$drowsinessConditionDesc.'</b>';
            $html = $html.'</td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Do you have any pre-existing and/or chronic and/or long term injuries or illness?</p></td>
                                    <td width="30%">';
            $html = $html.'<i';
            if ($chronicCondition == 'Yes') {
                $html = $html . ' style="color:red"';
            }
            $html = $html.'><b>'.$chronicCondition.'</b></i>';
            $html = $html.'<br><b>'.$chronicConditionDesc.'</b>';
            $html = $html.'</td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Have you ever had a work related injury?</p></td>
                                    <td width="30%">';
            $html = $html.'<i';
            if ($workInjury == 'Yes') {
                $html = $html . ' style="color:red"';
            }
            $html = $html.'><b>'.$workInjury.'</b></i>';
            $html = $html.'<br><b>'.$workInjuryDesc.'</b>';
            $html = $html.'</td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Was a Workcover claim lodged? (Question not applicable to QLD. Applicants)</p></td>
                                    <td width="30%"><b>'.$workCoverClaim.'</b></td>
                                  </tr>
                                  <tr>
                                    <td colspan="2">
                                        <p><b>SECTION C : PHYSICAL ABILITIES</b></p>
                                    </td>
                                  </tr>
                                  <tr><td colspan="2"><b>Please indicate whether you have, or could have, difficulties performing any of the following activities.</b></td></tr>
                                  <tr><td colspan="2">If you <b>have, or could have difficulties performing any of the following activities, </b> answer <b style="color: red">YES</b></td></tr>
                                  <tr><td colspan="2">If not answer <b>NO</b></td></tr>
                                  <tr>
                                    <td width="70%"><p>Crouching/bending/ Kneeling (repeatedly) </p></td>
                                    <td width="30%"><b>'.$crouchingCondition.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Sitting for up to 30 minutes</p></td>
                                    <td width="30%"><b>'.$sittingCondition.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Working above shoulder height </p></td>
                                    <td width="30%"><b>'.$workShoulderHeight.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Hearing a normal conversation </p></td>
                                    <td width="30%"><b>'.$hearingConversation.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Climbing a ladder/working at heights </p></td>
                                    <td width="30%"><b>'.$workAtHeights.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Walking/working on uneven ground </p></td>
                                    <td width="30%"><b>'.$walkingStairs.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Handling meat and/or food produce </p></td>
                                    <td width="30%"><b>'.$handlingFood.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Performing Shift Work </p></td>
                                    <td width="30%"><b>'.$shiftWork.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Standing for 30 minutes </p></td>
                                    <td width="30%"><b>'.$standingMinutes.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Lifting objects weighing 15 kilograms or more </p></td>
                                    <td width="30%"><b>'.$liftingCondition.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Gripping objects firmly with both hands </p></td>
                                    <td width="30%"><b>'.$grippingObjects.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Repetitive movement of hands or arms </p></td>
                                    <td width="30%"><b>'.$repetitiveMovement.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Walking up and down stairs </p></td>
                                    <td width="30%"><b>'.$walkingStairs.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Using hand tools/operating machinery </p></td>
                                    <td width="30%"><b>'.$handTools.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Wearing personal protective equipment (PPE)</p></td>
                                    <td width="30%"><b>'.$protectiveEquipment.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Working in confined spaces or underground </p></td>
                                    <td width="30%"><b>'.$workConfinedSpaces.'</b></td>
                                  </tr>
                                  <tr>
                                    <td width="70%"><p>Working in hot/cold environments inc. refrigerated storage </p></td>
                                    <td width="30%"><b>'.$workHotColdEnvironment.'</b></td>
                                  </tr>
                                </tbody>
                                </table>';
            $html = $html.'<br><br><table style="width: 95%" border="0" cellspacing="1" cellpadding="1" nobr="true">
                                <tbody>
                                    <tr><td colspan="2"><b>PRIVACY POLICY</b></td></tr>
                                  <tr>
                                    <td width="50%">
                                        <br>
                                        <p>Your privacy is important to Chandler Services. It is our commitment to protect the privacy of the information of our employees and others. This statement outlines our privacy policy and how we manage and disclose personal information.</p>
                                        <p><b>What is your personal information?</b></p>
                                        <p>Personal information is any information or an opinion (whether true or not) about you. It may range from the very sensitive (eg. criminal history, medical history or condition) to the everyday information (eg. full name, address, and phone number). It would include the opinions of others about your work performance (whether true or not), your work experience and qualifications, aptitude test results and other information obtained by us in connection with your possible work placements.</p>
                                        <p><b>Why is your personal information collected?</b></p>
                                        <p>Your personal information will be collected by the experienced team of consultants at Chandler Services. It is collected and held to assist Chandler Services in determining your suitability for work placements. It is also used for staff management and in order to identify any training requirements.</p>
                                        <p><b>How will your information be collected?</b></p>
                                        <p>Personal information will be collected from you directly when you fill out and submit one of our registration forms or any other information in connection with your application to us for registration. Personal information will also be collected when:</p>
                                        <p> - we receive any reference about you</p>
                                        <p> - we receive the results of any competency or medical test</p>
                                        <p> - we receive performance feedback (whether positive or negative)</p>
                                        <p> - we receive any complaint from or about you in the workplace</p>
                                        <p> - we receive any information about a workplace accident in which you are involved</p>
                                        <p> - we receive any information about any insurance investigation, litigation, registration or professional disciplinary matter, criminal matter, inquest or inquiry in which you are involved</p>
                                        <p> - you provide us with any additional information about you</p>
                                        <p><b>How will your information be used?</b></p>
                                        <p>Your personal information may be used in connection with:</p>
                                        <p> - your actual or possible work placement</p>
                                        <p> - your performance appraisals our assessment of your ongoing performance and prospects</p>
                                        <p> - any test or assessment (including medical tests and assessments) that you might be required to undergo</p>
                                        <p> - our identification of your training needs</p>
                                        <p> - any workplace rehabilitation</p>
                                        <br>
                                        <p>I HAVE READ AND UNDERSTOOD THE ABOVE PRIVACY POLICY.</p>
                                        <br>
                                        <p>Candidate Signature</p>
                                        <p><img src="'.$realSignaturePath.'"/></p>
                                    </td>
                                    <td width="50%">
                                        <p> - our management of any complaint, investigation or inquiry in which you are involved</p>
                                        <p> - any insurance claim or proposal that requires disclosure of your personal information</p>
                                        <p><b>Who might your personal information be disclosed to?</b></p>
                                        <p> - potential and actual employers and clients of Chandler Services</p>
                                        <p> - Referees</p>
                                        <p> - companies within the Chandler Services Group</p>
                                        <p> - our insurers</p>
                                        <p> - a professional association or registration body that has a proper interest in the disclosure of your personal information</p>
                                        <p> - a workers compensation body</p>
                                        <p> - our contractors and suppliers (eg. IT contractors and database designers)</p>
                                        <p> - any person with a lawful entitlement to obtain the information</p>
                                        <p><b>How can you gain access to your personal information that we hold?</b></p>
                                        <p>Under privacy legislation you have a right to see any personal information about you that we may hold. If you are able to establish that any of the information that we hold about you is not accurate, complete and up to date we will take reasonable steps to correct this.</p>
                                        <p><b>How is your personal information stored?</b></p>
                                        <p>Chandler Services takes all reasonable steps to ensure that information held in paper or electronic form is secure, and that it is protected from misuse, loss, unauthorized access, modification or disclosure. All staff at Chandler Services will take reasonable steps to ensure that personal information is only used for recruitment purposes or disclosed to other organisations to the extent necessary for our business purposes. When personal information is no longer required it will be destroyed.</p>
                                        <p><b>Changes to our Privacy Policy?</b></p>
                                        <p>If any changes are made to Chandler Services’ Privacy Policy, they will be posted on our website so that you are always kept up to date about the information we might use and whether it will be disclosed to anyone.</p>
                                        <p><b>Inquiries or Feedback?</b></p>
                                        <p>If you have any questions or concerns about our commitment to your privacy, please don’t hesitate to contact us on 1300 499 449.</p>
                                    </td>
                                  </tr>
                                </tbody>
                                </table>';
            $html = utf8_decode($html);
            @$pdf->writeHTML($html);//,true,false,true,false,''
            $time = time();
            $pdf->Output('/var/www/html/documents/'.$canId.'/'.'reg_mob_'.$canId.'_'.$time.'.pdf', 'F');//'/var/www/html/mobileAPI/app/Http/Controllers/user_register.pdf','F'
            $this->pdfDocumentUpdate($canId, 42, 'reg_mob_'.$canId.'_'.$time.'.pdf');
            $updateStatus = $this->updateCandidateInfo($canId, '', $firstName, $lastName, '', $mobileNo, $email, $sex, '', '', '', '', '', '', '', '', $residentStatus, $medicalCondition, $medConditionDesc, '', '', '', '', $dob, $this->getConsultantEmail($canId), 'INACTIVE', $empCondition, 1, $superMemberNo, $tfn);
            if (($updateStatus == 'Added') || ($updateStatus == 'Updated')) {
                if ($residentStatus == 'Working Visa') {
                    $this->updateVisaTypeAndExpiry($canId, 4, $expiryDate);
                } elseif ($residentStatus == 'Temporary Resident') {
                    $this->updateVisaTypeAndExpiry($canId, 3, $expiryDate);
                } elseif ($residentStatus == 'Student Visa') {
                    $this->updateVisaTypeAndExpiry($canId, 2, $expiryDate);
                } elseif ($residentStatus == 'Australian Citizen') {
                    $this->updateVisaTypeAndExpiry($canId, 0, '');
                } elseif ($residentStatus == 'Australian Permanent Resident') {
                    $this->updateVisaTypeAndExpiry($canId, 1, '');
                }
            }

            /*=======================  TAX FILE NUMBER DECLARATION =================================*/
            $txPdf = new Fpdi();
            $txPdf->AddPage();
            $tx_source_pdf = "../docform/CPS_TFN_declaration_form_N3092.pdf";
            $tx_pdf = "../docform/CPS_TFN_declaration_form_" . time() . ".pdf";
            shell_exec('gs -dBATCH -dNOPAUSE -q -sDEVICE=pdfwrite -sOutputFile="' . $tx_pdf . '" "' . $tx_source_pdf . '"');
            $txPdf->setSourceFile($tx_pdf);
            $page1 = $txPdf->importPage(1);
            $txPdf->useTemplate($page1);
            $txPdf->AddPage();
            $page2 = $txPdf->importPage(2);
            $txPdf->useTemplate($page2);
            $txPdf->AddPage();
            $page3 = $txPdf->importPage(3);
            $txPdf->useTemplate($page3);
            $txPdf->AddPage();
            $page4 = $txPdf->importPage(4);
            $txPdf->useTemplate($page4);
            $txPdf->AddPage();
            $page5 = $txPdf->importPage(5);
            $txPdf->useTemplate($page5);
            $txPdf->SetFont("Arial", "", 12);
            $fontSize = '12';
            $fontColor = '0,0,0';
            $dobSplit = explode('/', $dob);
            $dobdd = str_split($dobSplit[0]);
            $dobmm = str_split($dobSplit[1]);
            $dobyy = str_split($dobSplit[2]);

            $fullDate = date('d/m/Y');
            $year = str_split(date('Y'));
            $month = str_split(date('m'));
            $day = str_split(date('d'));
            $tfnSplit = str_split($tfn);

            $txPdf->Text(49, 37, $tfnSplit[0]);
            $txPdf->Text(54, 37, $tfnSplit[1]);
            $txPdf->Text(59, 37, $tfnSplit[2]);
            $txPdf->Text(69, 37, $tfnSplit[3]);
            $txPdf->Text(74, 37, $tfnSplit[4]);
            $txPdf->Text(79, 37, $tfnSplit[5]);
            $txPdf->Text(89, 37, $tfnSplit[6]);
            $txPdf->Text(94, 37, $tfnSplit[7]);
            $txPdf->Text(99, 37, $tfnSplit[8]);

            if (strlen($email) > 19) {
                $emailPart1 = substr($email, 0, 19);
                $emailPart2 = substr($email, 19);
                $txPdf->Text(112, 38, chunk_split(strtoupper($emailPart1), 1));
                $txPdf->Text(112, 47, chunk_split(strtoupper($emailPart2), 1));
            } else {
                $txPdf->Text(112, 38, chunk_split(strtoupper($email), 1));
            }

            $txPdf->Text(162, 57, $dobdd[0]);
            $txPdf->Text(166, 57, $dobdd[1]);
            $txPdf->Text(175, 57, $dobmm[0]);
            $txPdf->Text(179, 57, $dobmm[1]);
            $txPdf->Text(188, 57, $dobyy[0]);
            $txPdf->Text(193, 57, $dobyy[1]);
            $txPdf->Text(197, 57, $dobyy[2]);
            $txPdf->Text(202, 57, $dobyy[3]);

            if ($paidBasis == 'Full - time') {
                $txPdf->Text(126, 71, 'X');
            } elseif ($paidBasis == 'Part - time') {
                $txPdf->Text(146, 71, 'X');
            } elseif ($paidBasis == 'Labour - hire') {
                $txPdf->Text(159, 71, 'X');
            } elseif ($paidBasis == 'Superannuation') {
                $txPdf->Text(183, 71, 'X');
            } elseif ($paidBasis == 'Casual') {
                $txPdf->Text(202, 71, 'X');
            }

            if ($taxResident == 'Australian resident') {
                $txPdf->Text(136, 85, 'X');
            } elseif ($taxResident == 'Foreign resident') {
                $txPdf->Text(167, 85, 'X');
            } elseif ($taxResident == 'Working holiday resident') {
                $txPdf->Text(202, 85, 'X');
            }

            if ($taxClaim == 'Yes') {
                $txPdf->Text(118, 107, 'X');
            } elseif ($taxClaim == 'No') {
                $txPdf->Text(131, 107, 'X');
            }

            if ($taxHelp == 'Yes') {
                $txPdf->Text(118, 128, 'X');
            } elseif ($taxHelp == 'No') {
                $txPdf->Text(202, 128, 'X');
            }

            if ($title == 'Mr') {
                $txPdf->Text(54, 72, 'X');
            } elseif ($title == 'Mrs') {
                $txPdf->Text(69, 72, 'X');
            } elseif ($title == 'Miss') {
                $txPdf->Text(84, 72, 'X');
            } elseif ($title == 'Ms') {
                $txPdf->Text(98, 72, 'X');
            }

            $txPdf->Text(9, 82, chunk_split(strtoupper($lastName), 1));
            $txPdf->Text(9, 91, chunk_split(strtoupper($firstName), 1));

            if (strlen($address) > 19) {
                $addressPart1 = substr($address, 0, 19);
                $addressPart2 = substr($address, 19);
                $txPdf->Text(9, 114, chunk_split(strtoupper($addressPart1), 1));
                $txPdf->Text(9, 122, chunk_split(strtoupper($addressPart2), 1));
            } else {
                $txPdf->Text(9, 114, chunk_split(strtoupper($address), 1));
            }

            $txPdf->Text(9, 132, chunk_split(strtoupper($suburb), 1));

            $txPdf->Text(9, 140, chunk_split(strtoupper($state), 1));

            $txPdf->Text(34, 140, chunk_split($postcode, 1));

            $txPdf->Image($realSignaturePath, 105, 135, 70, 15, 'png');
            $txPdf->Text(162, 150, $day[0]);
            $txPdf->Text(166, 150, $day[1]);
            $txPdf->Text(175, 150, $month[0]);
            $txPdf->Text(179, 150, $month[1]);
            $txPdf->Text(188, 150, $year[0]);
            $txPdf->Text(193, 150, $year[1]);
            $txPdf->Text(197, 150, $year[2]);
            $txPdf->Text(202, 150, $year[3]);

            $txFileName = 'taxFrm_mobile_' . $canId . '_' . time() . '.pdf';
            $txFilePath = '../tax/' . $txFileName;
            $txPdf->Output('../tax/' . $txFileName, 'F');
            if(copy($txFilePath, '../documents/' . $canId . '/' . $txFileName)){
                unlink($tx_pdf);
                unlink($txFilePath);
            }
            $this->pdfDocumentUpdate($canId, 46, $txFileName);


            /*==================================  POLICE CHECK INFORMATION  ===========================*/
            if (($policeCheck == 'No') && (!empty($statOccupation))) {
                $statPdf = new Fpdi();
                $statPdf->AddPage();
                $stat_source_pdf = "../docform/StatutoryDeclaration_Criminal_Convictions_v3.pdf";
                $stat_pdf = "../docform/StatutoryDeclaration_" . time() . ".pdf";
                shell_exec('gs -dBATCH -dNOPAUSE -q -sDEVICE=pdfwrite -sOutputFile="' . $stat_pdf . '" "' . $stat_source_pdf . '"');
                $statPdf->setSourceFile($stat_pdf);
                $page1 = $statPdf->importPage(1);
                $statPdf->useTemplate($page1);
                $statPdf->SetFont("Times", "", 12);
                $statPdf->Text(37, 61, $firstName . ' ' . $lastName);
                $statPdf->Text(37, 75, $address);
                $statPdf->Text(37, 89, $statOccupation);

                $statPdf->Text(46, 214, date('d'));
                $statPdf->Text(70, 214, date('M'));
                $statPdf->Text(80, 214, date('Y'));
                $statPdf->Image($realSignaturePath, 118, 200, 80, 15, 'png');

                $stFileName = 'statDec_mobile_' . $canId . '_' . time() . '.pdf';
                $stFilePath = '../docform/' . $stFileName;
                $statPdf->Output('../docform/' . $stFileName, 'F');
                if(copy($stFilePath, '../documents/' . $canId . '/' . $stFileName)){
                    unlink($stat_pdf);
                    unlink($stFilePath);
                }
                $this->pdfDocumentUpdate($canId, 27, $stFileName);

            }

            $crimePdf = new Fpdi();
            $crimePdf->AddPage();
            $crime_source_pdf = "../docform/PoliceCheckAuthorityFormv2.pdf";
            $crime_pdf = "../docform/PoliceCheckAuthority_" . time() . ".pdf";
            shell_exec('gs -dBATCH -dNOPAUSE -q -sDEVICE=pdfwrite -sOutputFile="' . $crime_pdf . '" "' . $crime_source_pdf . '"');
            $crimePdf->setSourceFile($crime_pdf);
            $page1 = $crimePdf->importPage(1);
            $crimePdf->useTemplate($page1);
            $crimePdf->SetFont("Arial", "", 10);

            if ($crimeCheck == 'Yes') {
                $crimePdf->Text(170, 70, 'X');
                $crimePdf->Text(22, 86, $crimeDate1);
                $crimePdf->Text(55, 86, $crime1);
                $crimePdf->Text(22, 95, $crimeDate2);
                $crimePdf->Text(55, 95, $crime2);
            } else {
                $crimePdf->Text(185, 70, 'X');
            }

            if ($optionChk == 'option1') {
                $crimePdf->Text(21, 122, 'X');
            } else {
                $crimePdf->Text(21, 129, 'X');
            }
            $crimePdf->Text(23, 162, $firstName . ' ' . $lastName);

            $crimePdf->Image($realSignaturePath, 10, 175, 80, 15, 'png');

            $crimePdf->Text(20, 198, $firstName . ' ' . $lastName);
            $crimePdf->Text(95, 198, date('d / m / Y'));

            $crFileName = 'policeCheck_mobile_' . $canId . '_' . time() . '.pdf';
            $crFilePath = '../docform/' . $crFileName;
            $crimePdf->Output('../docform/' . $crFileName, 'F');
            if(copy($crFilePath, '../documents/' . $canId . '/' . $crFileName)){
                unlink($crime_pdf);
                unlink($crFilePath);
            }
            $this->pdfDocumentUpdate($canId, 18, $crFileName);


            $fitPdf = new Fpdi();
            $fitPdf->AddPage();
            $fit_source_pdf = "../docform/Fit2Work Form.pdf";
            $fit_pdf = "../docform/Fit2Work_" . time() . ".pdf";
            shell_exec('gs -dBATCH -dNOPAUSE -q -sDEVICE=pdfwrite -sOutputFile="' . $fit_pdf . '" "' . $fit_source_pdf . '"');
            $fitPdf->setSourceFile($fit_pdf);
            $page1 = $fitPdf->importPage(1);
            $fitPdf->useTemplate($page1);
            $fitPdf->SetFont("Arial", "", 10);

            $fitPdf->Text(26, 36, strtoupper($firstName));
            $fitPdf->Text(138, 36, strtoupper($middle_name));
            $fitPdf->Text(26, 44, strtoupper($lastName));

            if ($sex == 'Male') {
                $fitPdf->Text(25, 52, 'x');
            } elseif ($sex == 'Female') {
                $fitPdf->Text(38, 52, 'x');
            } elseif ($sex == 'Intersex') {
                $fitPdf->Text(55, 52, 'x');
            } elseif ($sex == 'Unknown') {
                $fitPdf->Text(72, 52, 'x');
            }
            $dobSplit = explode('/', $dob);
            $dobdd = str_split($dobSplit[0]);
            $dobmm = str_split($dobSplit[1]);
            $dobyy = str_split($dobSplit[2]);
            $fitPdf->Text(139, 54, $dobdd[0]);
            $fitPdf->Text(146, 54, $dobdd[1]);
            $fitPdf->Text(156, 54, $dobmm[0]);
            $fitPdf->Text(160, 54, $dobmm[1]);
            $fitPdf->Text(170, 54, $dobyy[0]);
            $fitPdf->Text(174, 54, $dobyy[1]);
            $fitPdf->Text(178, 54, $dobyy[2]);
            $fitPdf->Text(182, 54, $dobyy[3]);

            $fitPdf->Text(29, 73, $pb_suburb);
            $fitPdf->Text(99, 73, $pb_state);
            $fitPdf->Text(155, 73, $pb_country);

            $fitPdf->Text(22, 94, $unit_no);
            $fitPdf->Text(50, 94, $street_number);
            $fitPdf->Text(75, 94, $street_name);
            $fitPdf->Text(187, 94, $postcode);
            $fitPdf->Text(22, 102, $suburb);
            $fitPdf->Text(100, 102, $state);
            $fitPdf->Text(149, 102, 'Australia');

            $fitPdf->Text(25, 131, $fw_first_name);
            $fitPdf->Text(138, 131, $fw_middle_name);
            $fitPdf->Text(25, 139, $fw_last_name);

            if ($fw_type == 'Previous') {
                $fitPdf->Text(172, 139, 'x');
            } elseif ($fw_type == 'Maiden') {
                $fitPdf->Text(189, 139, 'x');
            }

            $fitPdf->Text(22, 156, $fw_unit_no1);
            $fitPdf->Text(49, 156, $fw_street_number1);
            $fitPdf->Text(75, 156, $fw_street_name1);
            $fitPdf->Text(187, 156, $fw_postcode1);
            $fitPdf->Text(22, 166, $fw_suburb1);
            $fitPdf->Text(100, 166, $fw_state1);
            $fitPdf->Text(149, 166, $fw_country1);

            $fitPdf->Text(22, 180, $fw_unit_no2);
            $fitPdf->Text(49, 180, $fw_street_number2);
            $fitPdf->Text(75, 180, $fw_street_name2);
            $fitPdf->Text(187, 180, $fw_postcode2);
            $fitPdf->Text(22, 190, $fw_suburb2);
            $fitPdf->Text(100, 190, $fw_state2);
            $fitPdf->Text(149, 190, $fw_country2);

            $fitPdf->Text(143, 206, $mobileNo);
            $fitPdf->Text(20, 216, $email);

            $fitPdf->Text(45, 230, $fw_licence);
            $fitPdf->Text(144, 230, $fw_licence_state);

            $fitPdf->Text(45, 249, $fw_passport_no);
            $fitPdf->Text(144, 249, $fw_passport_country);

            if ($fw_passport_type == 'private') {
                $fitPdf->Text(45, 254, 'x');
            } elseif ($fw_passport_type == 'Government') {
                $fitPdf->Text(61, 254, 'x');
            } elseif ($fw_passport_type == 'UN Refugee') {
                $fitPdf->Text(83, 254, 'x');
            }

            $fitPdf->AddPage();
            $page2 = $fitPdf->importPage(2);
            $fitPdf->useTemplate($page2);
            $fitPdf->AddPage();
            $page3 = $fitPdf->importPage(3);
            $fitPdf->useTemplate($page3);
            $fitPdf->AddPage();
            $page4 = $fitPdf->importPage(4);
            $fitPdf->useTemplate($page4);

            $fitPdf->Text(12, 34, $firstName . ' ' . $middle_name);
            $fitPdf->Text(104, 34, $lastName);

            $fitPdf->Image($realSignaturePath, 40, 140, 80, 15, 'png');

            $fitPdf->Text(130, 151, date('d'));
            $fitPdf->Text(138, 151, date('m'));
            $fitPdf->Text(146, 151, date('Y'));

            $fitPdf->AddPage();
            $page5 = $fitPdf->importPage(5);
            $fitPdf->useTemplate($page5);
            $fitPdf->AddPage();
            $page6 = $fitPdf->importPage(6);
            $fitPdf->useTemplate($page6);

            $fit2wkFileName = 'fit2wrk_mobile_' . $canId . '_' . time() . '.pdf';
            $fit2wkFilePath = '../docform/' . $fit2wkFileName;
            $fitPdf->Output('../docform/' . $fit2wkFileName, 'F');
            if(copy($fit2wkFilePath, '../documents/' . $canId . '/' . $fit2wkFileName)){
                unlink($fit_pdf);
                unlink($fit2wkFilePath);
            }
            $this->pdfDocumentUpdate($canId, 56, $fit2wkFileName);


            $this->sendNotification('Candidate ' . $firstName . ' ' . $lastName . ', has filled Registration Form with Labourbank - mobile', 'donotreply@labourbank.com.au', 'swarnajithf@chandlerservices.com.au', 'Labourbank Online Registration Pack - mobile');
        }
    }
    public function updateCandidateInfo($candidateId, $messageid, $firstName, $lastName, $candidatePhone, $candidateMobile, $candidateEmail, $candidateSex, $screenDate, $suburb, $currentWrk, $howfar, $genLabourPay, $criminalConviction, $convictionDescription, $hasCar, $residentStatus, $medicalCondition, $medicalConditionDesc, $workType, $overtime, $bookInterview, $intvwTime, $dob, $consultantId, $empStatus, $empCondition, $regPackStatus, $superMemberNo, $tfn)
    {
        try {
            $fullName = $firstName . ' ' . $lastName;
            if (empty($empStatus)) {
                $empStatus = 'INACTIVE';
            }
            $result = DB::table('candidate')->where('candidateId', '=', $candidateId)->get();
            if (count($result) > 0) {
                $update = DB::table('candidate')
                    ->where('candidateId', '=', $candidateId)
                    ->where('email', '=', $candidateEmail)
                    ->update([
                        'firstName'=>$firstName,
                        'lastName'=>$lastName,
                        'fullName'=>$fullName,
                        'homePhoneNo'=>$candidatePhone,
                        'mobileNo'=>$candidateMobile,
                        'sex'=>$candidateSex,
                        'screenDate'=>$screenDate,
                        'suburb'=>$suburb,
                        'currentWrk'=>$currentWrk,
                        'howfar'=>$howfar,
                        'genLabourPay'=>$genLabourPay,
                        'criminalConviction'=>$criminalConviction,
                        'convictionDescription'=>$convictionDescription,
                        'hasCar'=>$hasCar,
                        'residentStatus'=>$residentStatus,
                        'medicalCondition'=>$medicalCondition,
                        'medicalConditionDesc'=>$medicalConditionDesc,
                        'workType'=>$workType,
                        'overtime'=>$overtime,
                        'bookInterview'=>$bookInterview,
                        'intvwTime'=>$bookInterview,
                        'consultantId'=>$consultantId,
                        'username'=>$candidateId,
                        'empStatus'=>$empStatus,
                        'dob'=>$dob,
                        'empCondition'=>$empCondition,
                        'superMemberNo'=>$superMemberNo,
                        'tfn'=>$tfn,
                        'reg_pack_status'=>$regPackStatus
                    ]);
                return 'Updated';
            }else{
                $pin = $this->generateMobileAppPIN();
                $insert = DB::table('candidate')
                    ->insert([
                        'clockPin'=>$pin,
                        'candidateId'=>$candidateId,
                        'messageid'=>'',
                        'firstName'=>$firstName,
                        'lastName'=>$lastName,
                        'fullName'=>$fullName,
                        'homePhoneNo'=>$candidatePhone,
                        'mobileNo'=>$candidateMobile,
                        'email'=>$candidateEmail,
                        'sex'=>$candidateSex,
                        'screenDate'=>$screenDate,
                        'suburb'=>$suburb,
                        'currentWrk'=>$currentWrk,
                        'howfar'=>$howfar,
                        'genLabourPay'=>$genLabourPay,
                        'criminalConviction'=>$criminalConviction,
                        'convictionDescription'=>$convictionDescription,
                        'hasCar'=>$hasCar,
                        'residentStatus'=>$residentStatus,
                        'medicalCondition'=>$medicalCondition,
                        'medicalConditionDesc'=>$medicalConditionDesc,
                        'workType'=>$workType,
                        'overtime'=>$overtime,
                        'bookInterview'=>$bookInterview,
                        'intvwTime'=>$intvwTime,
                        'consultantId'=>$consultantId,
                        'dob'=>$dob,
                        'username'=>$candidateId,
                        'empStatus'=>$empStatus,
                        'empCondition'=>$empCondition,
                        'superMemberNo'=>$superMemberNo,
                        'tfn'=>$tfn,
                        'reg_pack_status'=>$regPackStatus
                    ]);
                $insert = DB::table('uid_container')
                    ->insert([
                        'candidateId'=>$candidateId
                    ]);
                return 'Added';
            }

        }catch (\Exception $e){
            return 'Error Candidate Information update '.$e->getMessage();
        }
    }
    public function updateQuestionnaire(Request $request)
    {

        /* $residentStatus = $request->residentStatus;
         $visaExpiry = $request->visaExpiry;
         $bankAccountName = $request->bankAccountName;
         $bankName = $request->bankName;
         $bsb = $request->bsb;
         $bankAccountNumber = $request->bankAccountNumber;
         $tfn = $request->tfn;
         $superMembershipNo = $request->superMembershipNo;*/
        try {
            $candidateId = $request->candidateId;
            $paidBasis = $request->paidBasis;
            $taxClaim = $request->taxClaim;
            $taxHelp = $request->taxHelp;
            $taxResident = $request->taxResident;
            $workprocin = $request->workprocin;
            $emcName = $request->emcName;
            $emcRelationship = $request->emcRelationship;
            $emcMobile = $request->emcMobile;
            $emcHomePhone = $request->emcHomePhone;
            $referee1Name = $request->referee1Name;
            $referee1CompanyName = $request->referee1CompanyName;
            $referee1Position = $request->referee1Position;
            $referee1Relationship = $request->referee1Relationship;
            $referee1Mobile = $request->referee1Mobile;
            $referee2Name = $request->referee2Name;
            $referee2CompanyName = $request->referee2CompanyName;
            $referee2Position = $request->referee2Position;
            $referee2Relationship = $request->referee2Relationship;
            $referee2Mobile = $request->referee2Mobile;
            $superAccountName = $request->superAccountName;
            $superFundName = $request->superFundName;
            $superFundAddress = $request->superFundAddress;
            $superPhoneNo = $request->superPhoneNo;
            $superWebsite = $request->superWebsite;
            $superFundABN = $request->superFundABN;
            $superFundUSI = $request->superFundUSI;
            $medicalCondition = $request->medicalCondition;
            $medConditionDesc = $request->medConditionDesc;
            $psycoCondition = $request->psycoCondition;
            $psycoConditionDesc = $request->psycoConditionDesc;
            $alergyCondition = $request->alergyCondition;
            $alergyConditionDesc = $request->alergyConditionDesc;
            $pregnantCondition = $request->pregnantCondition;
            $shoulderCondition = $request->shoulderCondition;
            $armCondition = $request->armCondition;
            $strainCondition = $request->strainCondition;
            $epilepsyCondition = $request->epilepsyCondition;
            $hearingCondition = $request->hearingCondition;
            $stressCondition = $request->stressCondition;
            $fatiqueCondition = $request->fatiqueCondition;
            $asthmaCondition = $request->asthmaCondition;
            $arthritisCondition = $request->arthritisCondition;
            $dizzinessCondition = $request->dizzinessCondition;
            $headCondition = $request->headCondition;
            $speechCondition = $request->speechCondition;
            $backCondition = $request->backCondition;
            $kneeCondition = $request->kneeCondition;
            $persistentCondition = $request->persistentCondition;
            $skinCondition = $request->skinCondition;
            $stomachStrains = $request->stomachStrains;
            $visionCondition = $request->visionCondition;
            $boneCondition = $request->boneCondition;
            $bloodCondition = $request->bloodCondition;
            $lungCondition = $request->lungCondition;
            $surgeryInformation = $request->surgeryInformation;
            $stomachCondition = $request->stomachCondition;
            $heartCondition = $request->heartCondition;
            $infectiousCondition = $request->infectiousCondition;
            $medicalTreatment = $request->medicalTreatment;
            $medicalTreatmentDesc = $request->medicalTreatmentDesc;
            $drowsinessCondition = $request->drowsinessCondition;
            $drowsinessConditionDesc = $request->drowsinessConditionDesc;
            $chronicCondition = $request->chronicCondition;
            $chronicConditionDesc = $request->chronicConditionDesc;
            $workInjury = $request->workInjury;
            $workInjuryDesc = $request->workInjuryDesc;
            $workCoverClaim = $request->workCoverClaim;
            $crouchingCondition = $request->crouchingCondition;
            $sittingCondition = $request->sittingCondition;
            $workShoulderHeight = $request->workShoulderHeight;
            $hearingConversation = $request->hearingConversation;
            $workAtHeights = $request->workAtHeights;
            $groundCondition = $request->groundCondition;
            $handlingFood = $request->handlingFood;
            $shiftWork = $request->shiftWork;
            $standingMinutes = $request->standingMinutes;
            $liftingCondition = $request->liftingCondition;
            $grippingObjects = $request->grippingObjects;
            $repetitiveMovement = $request->repetitiveMovement;
            $walkingStairs = $request->walkingStairs;
            $handTools = $request->handTools;
            $protectiveEquipment = $request->protectiveEquipment;
            $workHeights = $request->workHeights;
            $workConfinedSpaces = $request->workConfinedSpaces;
            $workHotColdEnvironment = $request->workHotColdEnvironment;
            $supercheck = $request->superFundCheck;
            $policeCheck = $request->policeCheck;
            $statOccupation = $request->statOccupation;
            $crimeCheck = $request->crimeCheck;
            $crimeDate1 = $request->crimeDate1;
            $crime1 = $request->crime1;
            $crimeDate2 = $request->crimeDate2;
            $crime2 = $request->crime2;
            $optionChk = $request->optionChk;
            $neverConvicted = $request->neverConvicted;
            $neverImprisonment = $request->neverImprisonment;
            $pb_suburb = $request->pb_suburb;
            $pb_state = $request->pb_state;
            $pb_country = $request->pb_country;
            $fw_first_name = $request->fw_first_name;
            $fw_middle_name = $request->fw_middle_name;
            $fw_last_name = $request->fw_last_name;
            $fw_unit_no1 = $request->fw_unit_no1;
            $fw_street_number1 = $request->fw_street_number1;
            $fw_street_name1 = $request->fw_street_name1;
            $fw_suburb1 = $request->fw_suburb1;
            $fw_state1 = $request->fw_state1;
            $fw_postcode1 = $request->fw_postcode1;
            $fw_country1 = $request->fw_country1;
            $fw_unit_no2 = $request->fw_unit_no2;
            $fw_street_number2 = $request->fw_street_number2;
            $fw_street_name2 = $request->fw_street_name2;
            $fw_suburb2 = $request->fw_suburb2;
            $fw_state2 = $request->fw_state2;
            $fw_postcode2 = $request->fw_postcode2;
            $fw_country2 = $request->fw_country2;
            $fw_licence = $request->fw_licence;
            $fw_licence_state = $request->fw_licence_state;
            $fw_passport_no = $request->fw_passport_no;
            $fw_passport_country = $request->fw_passport_country;
            $fw_type = $request->fw_type;
            $fw_passport_type = $request->fw_passport_type;

            DB::table('questionnaire')
                ->where('candidateId', '=', $candidateId)
                ->update([
                    'paidBasis' => $paidBasis,
                    'taxClaim' => $taxClaim,
                    'taxHelp' => $taxHelp,
                    'taxResident' => $taxResident,
                    'workprocin' => $workprocin,
                    'emcName' => $emcName,
                    'emcRelationship' => $emcRelationship,
                    'emcMobile' => $emcMobile,
                    'emcHomePhone' => $emcHomePhone,
                    'referee1Name' => $referee1Name,
                    'referee1CompanyName' => $referee1CompanyName,
                    'referee1Position' => $referee1Position,
                    'referee1Relationship' => $referee1Relationship,
                    'referee1Mobile' => $referee1Mobile,
                    'referee2Name' => $referee2Name,
                    'referee2CompanyName' => $referee2CompanyName,
                    'referee2Position' => $referee2Position,
                    'referee2Relationship' => $referee2Relationship,
                    'referee2Mobile' => $referee2Mobile,
                    'superAccountName' => $superAccountName,
                    'superFundName' => $superFundName,
                    'superFundAddress' => $superFundAddress,
                    'superPhoneNo' => $superPhoneNo,
                    'superWebsite' => $superWebsite,
                    'superFundABN' => $superFundABN,
                    'superFundUSI' => $superFundUSI,
                    'medicalCondition' => $medicalCondition,
                    'medConditionDesc' => $medConditionDesc,
                    'psycoCondition' => $psycoCondition,
                    'psycoConditionDesc' => $psycoConditionDesc,
                    'alergyCondition' => $alergyCondition,
                    'alergyConditionDesc' => $alergyConditionDesc,
                    'pregnantCondition' => $pregnantCondition,
                    'shoulderCondition' => $shoulderCondition,
                    'armCondition' => $armCondition,
                    'strainCondition' => $strainCondition,
                    'epilepsyCondition' => $epilepsyCondition,
                    'hearingCondition' => $hearingCondition,
                    'stressCondition' => $stressCondition,
                    'fatiqueCondition' => $fatiqueCondition,
                    'asthmaCondition' => $asthmaCondition,
                    'arthritisCondition' => $arthritisCondition,
                    'dizzinessCondition' => $dizzinessCondition,
                    'headCondition' => $headCondition,
                    'speechCondition' => $speechCondition,
                    'backCondition' => $backCondition,
                    'kneeCondition' => $kneeCondition,
                    'persistentCondition' => $persistentCondition,
                    'skinCondition' => $skinCondition,
                    'stomachStrains' => $stomachStrains,
                    'visionCondition' => $visionCondition,
                    'boneCondition' => $boneCondition,
                    'bloodCondition' => $bloodCondition,
                    'lungCondition' => $lungCondition,
                    'surgeryInformation' => $surgeryInformation,
                    'stomachCondition' => $stomachCondition,
                    'heartCondition' => $heartCondition,
                    'infectiousCondition' => $infectiousCondition,
                    'medicalTreatment' => $medicalTreatment,
                    'medicalTreatmentDesc' => $medicalTreatmentDesc,
                    'drowsinessCondition' => $drowsinessCondition,
                    'drowsinessConditionDesc' => $drowsinessConditionDesc,
                    'chronicCondition' => $chronicCondition,
                    'chronicConditionDesc' => $chronicConditionDesc,
                    'workInjury' => $workInjury,
                    'workInjuryDesc' => $workInjuryDesc,
                    'workCoverClaim' => $workCoverClaim,
                    'crouchingCondition' => $crouchingCondition,
                    'sittingCondition' => $sittingCondition,
                    'workShoulderHeight' => $workShoulderHeight,
                    'hearingConversation' => $hearingConversation,
                    'workAtHeights' => $workAtHeights,
                    'groundCondition' => $groundCondition,
                    'handlingFood' => $handlingFood,
                    'shiftWork' => $shiftWork,
                    'standingMinutes' => $standingMinutes,
                    'liftingCondition' => $liftingCondition,
                    'grippingObjects' => $grippingObjects,
                    'repetitiveMovement' => $repetitiveMovement,
                    'walkingStairs' => $walkingStairs,
                    'handTools' => $handTools,
                    'protectiveEquipment' => $protectiveEquipment,
                    'workHeights' => $workHeights,
                    'workConfinedSpaces' => $workConfinedSpaces,
                    'workHotColdEnvironment' => $workHotColdEnvironment,
                    'supercheck' => $supercheck,
                    'policeCheck' => $policeCheck,
                    'statOccupation' => $statOccupation,
                    'crimeCheck' => $crimeCheck,
                    'crimeDate1' => $crimeDate1,
                    'crime1' => $crime1,
                    'crimeDate2' => $crimeDate2,
                    'crime2' => $crime2,
                    'optionChk' => $optionChk,
                    'neverConvicted' => $neverConvicted,
                    'neverImprisonment' => $neverImprisonment,
                    'pb_suburb' => $pb_suburb,
                    'pb_state' => $pb_state,
                    'pb_country' => $pb_country,
                    'fw_first_name' => $fw_first_name,
                    'fw_middle_name' => $fw_middle_name,
                    'fw_last_name' => $fw_last_name,
                    'fw_unit_no1' => $fw_unit_no1,
                    'fw_street_number1' => $fw_street_number1,
                    'fw_street_name1' => $fw_street_name1,
                    'fw_suburb1' => $fw_suburb1,
                    'fw_state1' => $fw_state1,
                    'fw_postcode1' => $fw_postcode1,
                    'fw_country1' => $fw_country1,
                    'fw_unit_no2' => $fw_unit_no2,
                    'fw_street_number2' => $fw_street_number2,
                    'fw_street_name2' => $fw_street_name2,
                    'fw_suburb2' => $fw_suburb2,
                    'fw_state2' => $fw_state2,
                    'fw_postcode2' => $fw_postcode2,
                    'fw_country2' => $fw_country2,
                    'fw_licence' => $fw_licence,
                    'fw_licence_state' => $fw_licence_state,
                    'fw_passport_no' => $fw_passport_no,
                    'fw_passport_country' => $fw_passport_country,
                    'fw_type' => $fw_type,
                    'fw_passport_type' => $fw_passport_type]);
            return response()->json(['data' => 'Questionnaire Updated'], 201);
        } catch (\Exception $e) {
            return response()->json(['data' => 'Questionnaire Update Error' . $e->getMessage()], 500);
        }
    }
    public function displayQuestionnaire($id)
    {
        return DB::table('questionnaire')->where('candidateId', '=', $id)->get();
    }
    public function displayBankAccountInformation($id)
    {
        return DB::table('employee_bank_account')->where('candidateId', '=', $id)->get();
    }
    /*public function updateDocument(Request $request)
    {
        try {
            $file = $request->file('document_file');
            $fileName = $file->getClientOriginalName();
            $path = $file->move('../documents/' . $request->candidateId . '/', $fileName);

            DB::table('candidate_document')
                ->insert(['candidateId' => $request->candidateId,
                    'docTypeId' => $request->docTypeId,
                    'fileName' => $fileName,
                    'filePath' => './documents/' . $request->candidateId . '/' . $fileName]);
            return response()->json(['response' => 'Document Uploaded'], 201);
        } catch (\Exception $e) {
            $e->getMessage();
            return response()->json(['data' => 'Document Upload Error' . $e->getMessage()], 500);
        }
    }*/
    public function pdfDocumentUpdate($candidateId, $docTypeId, $fileName)
    {
        //$path = $file->move(' ../documents / '.$candidateId.' / ',$fileName);
        DB::table('candidate_document')
            ->insert(['candidateId' => $candidateId,
                'docTypeId' => $docTypeId,
                'fileName' => $fileName,
                'filePath' => './documents/' . $candidateId . '/' . $fileName]);
    }

    public function updateVisaTypeAndExpiry($candidateId, $visaTypeId, $expDate)
    {
        try {
            $result = DB::table('employee_visatype')->where('candidateId', '=', $candidateId)->get();
            if (count($result) > 0) {
                $update = DB::table('employee_visatype')
                    ->where('empVisaTypeId', '=', $visaTypeId)
                    ->where('candidateId', '=', $candidateId)
                    ->update(['expiryDate' => $expDate]);
                return response()->json(['data' => 'Visa Information Updated'], 201);
            } else {
                $insert = DB::table('employee_visatype')
                    ->insert(['candidateId' => $candidateId,
                        'empVisaTypeId' => $visaTypeId,
                        'expiryDate' => $expDate]);
                return response()->json(['data' => 'Visa Information Saved'], 201);
            }
        } catch (\Exception $e) {
            $e->getMessage();
            return response()->json(['data' => 'Visa Information update Error' . $e->getMessage()], 500);
        }
    }
    public function getConsultantEmail($candidateId)
    {
        try {
            $consultantId = DB::table('candidate')->select('consultantId')->where('candidateId', '=', $candidateId)->first();
            return $consultantEmail = DB::table('consultant')->select('email')->where('consultantId', '=', $consultantId)->first();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function getConsultantId($consultantEmail)
    {
        try {
           return $consultantId = DB::table('consultant')->select('consultantId')->where('email', '=', $consultantEmail)->first();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function getCandidateNoById($candidateId){
        try {
            return DB::table('candidate')->select('candidate_no')->where('candidateId', '=', $candidateId)->first();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function generatePIN($digits = 6)
    {
        $i = 0; //counter
        $pin = ""; //our default pin is blank.
        while ($i < $digits) {
            //generate a random number between 0 and 9.
            $pin .= mt_rand(0, 9);
            $i++;
        }
        return $pin = mt_rand(100000, 999999);
    }
    public function generateMobileAppPIN(){
        $pin = null;
        try {
            rePin:
            $pin = $this->generatePIN();
            $result = DB::table('candidate')->select('clockPin')->where('clockPin','=', $pin)->first();
            if ($result > 0) {
                goto rePin;
            } else {
                return $pin;
            }
        } catch (\Exception $e) {
            return 'PIN generation error '.$e->getMessage();
        }
    }
    public function sendNotification($mailText, $sender, $recipient, $subject)
    {
        $objTemp = new \stdClass();
        $objTemp->mailText = $mailText;
        $objTemp->sender = $sender;
        $objTemp->receiver = $recipient;
        $objTemp->subject = $subject;
        Mail::to($recipient)->send(new EmailGenerator($objTemp));
    }

    public function getPaySlipGrossPay($weekendingDate,$candidateId,$itemType){
        try {
            $grossPay = DB::table('payrundetails')
                            ->select('payrundetails.gross')
                            ->where('payrundetails.weekendingDate','=',$weekendingDate)
                            ->where('payrundetails.candidateId','=',$candidateId)
                            ->where('payrundetails.itemType','=',$itemType)->first();
            return $grossPay;
        }catch (\Exception $e){
            $e->getMessage();
        }
    }
    public function getPaySlipNetPay($weekendingDate,$candidateId,$itemType){
        try {
            $netPay = DB::table('payrundetails')
                ->select('payrundetails.net')
                ->where('payrundetails.weekendingDate','=',$weekendingDate)
                ->where('payrundetails.candidateId','=',$candidateId)
                ->where('payrundetails.itemType','=',$itemType)->first();
            return $netPay;
        }catch (\Exception $e){
            $e->getMessage();
        }
    }
    public function getPaySlipSummary($candidateId){
        try {
            $paySlipInfo = DB::table('payslip_info')
                ->select('payslip_info.weekendingDate','payslip_info.filePath','payslip_info.payDate','payslip_info.payPeriodStart','payslip_info.payPeriodEnd')
                ->where('payslip_info.candidateId','=',$candidateId)
                ->orderBy('payslip_info.weekendingDate','asc')->get();
            Log::info('DB result'.$paySlipInfo);
            $slipsSummary = array();
            /*$janArray = array();
            $febArray = array();*/
            $summaryArray = array();
            $month = '';
            if(!empty($paySlipInfo)) {
                foreach ($paySlipInfo as $paySlip) {
                    $weekendingDate = $paySlip->weekendingDate;
                    $weekStartDate = date('Y-m-d', strtotime($weekendingDate . ' - 6 day'));
                    $time = strtotime($weekStartDate);
                    $grossPay = $this->getPaySlipGrossPay($weekendingDate, $candidateId, 9);
                    $netPay = $this->getPaySlipNetPay($weekendingDate, $candidateId, 13);

                    if (empty($month)) {
                        $month = date("F", $time);
                    }
                    if ($month != date("F", $time)) {
                        $month = date("F", $time);
                    }
                    $slipsSummary[] = array("month" => $month, "period" => array("startDate" => $weekStartDate, "endDate" => $weekendingDate), "netPay" => $netPay->net, "grossPay" => $grossPay->gross, "paySlipUrl" => $paySlip->filePath);
                    /* if ($month == 'January') {
                         $janArray[] = array("month"=>$month, "slipsSummary"=>array("period" => array("startDate" => $weekStartDate, "endDate" => $weekendingDate), "netPay" => $netPay->net, "grossPay" => $grossPay->gross, "paySlipUrl" => $paySlip->filePath));
                     }elseif ($month == 'February'){
                         $febArray[] = array("month"=>$month, "slipsSummary"=>array("period" => array("startDate" => $weekStartDate, "endDate" => $weekendingDate), "netPay" => $netPay->net, "grossPay" => $grossPay->gross, "paySlipUrl" => $paySlip->filePath));

                     }*/
                }
                $summaryArray["slipsSummary"] = $slipsSummary;
            }else{
                $slipsSummary[] = array();
                $summaryArray["slipsSummary"] = $slipsSummary;
            }
            $jsonOutput = array(
                "success" => "1",
                "errors" => array("status" => 200, "message" => ""),
                "summary" => $summaryArray
            );
            return json_encode($jsonOutput);
        }catch (\Exception $e){
            Log::error('Error '. $e->getMessage());
        }
    }
}
