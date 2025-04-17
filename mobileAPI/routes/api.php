<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ServerTimeController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\TimeClockController;
use App\Http\Controllers\UIDController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/
/*Route::get('/example', function () {
    return response()->json(['message' => 'API route is working']);
});*/
Route::post('register',[PersonController::class,'create']);

Route::post('login', [LoginController::class,'login'])->name('login');
Route::post('app_login', [LoginController::class,'appLogin'])->name('app_login');
Route::post('app_logout', [LoginController::class,'app_logout']);
Route::post('logout', [LoginController::class,'logout']);
Route::group(['middleware' => 'auth:api'], function() {
    Route::get('candidates', [CandidateController::class,'index']);
    Route::get('candidates/{id}',[CandidateController::class,'show']);
    Route::get('candidates/questionnaire/{id}',[CandidateController::class,'displayQuestionnaire']);
    Route::post('candidate/delete',[CandidateController::class,'deactivate']);

    Route::post('app_dashboard', [CandidateController::class,'appDashboard']);
    Route::post('app_personal_info', [CandidateController::class,'personalInformation']);
    Route::post('app_update_profile_info', [CandidateController::class,'updateProfileInformation']);
    Route::post('app_update_job_active', [CandidateController::class,'updateJobActiveProvider']);
    Route::post('app_update_citizen', [CandidateController::class,'updateCitizen']);
    Route::post('app_update_pr', [CandidateController::class,'updatePR']);
    Route::post('app_update_working_visa', [CandidateController::class,'updateWorkingVisa']);
    Route::post('app_update_temporary_resident_visa', [CandidateController::class,'updateTemporaryResident']);
    Route::post('app_update_student_visa', [CandidateController::class,'updateStudentVisa']);
    Route::post('app_update_qualifications', [CandidateController::class,'updateQualifications']);
    Route::post('app_update_document', [CandidateController::class,'updateDocument']);
    Route::post('app_update_emergency_contact', [CandidateController::class,'updateEmergencyContact']);
    Route::post('app_update_referee1', [CandidateController::class,'updateReferee1']);
    Route::post('app_update_referee2', [CandidateController::class,'updateReferee2']);
    Route::post('app_update_bank_account', [CandidateController::class,'updateBankAccount']);
    Route::post('app_update_tax_file_no', [CandidateController::class,'updateTFN']);
    Route::post('app_update_tax_residency', [CandidateController::class,'updateTaxResidency']);
    Route::post('app_update_tax_threshold_claim', [CandidateController::class,'updateTaxThresholdClaim']);
    Route::post('app_update_tax_loan_help', [CandidateController::class,'updateTaxLoanHelp']);
    Route::post('app_update_super_fund_check', [CandidateController::class,'updateSuperFundCheck']);
    Route::post('app_update_super_fund_info', [CandidateController::class,'updateSuperFundInformation']);
    Route::post('app_update_police_check_info', [CandidateController::class,'updatePoliceCheckInformation']);
    Route::post('app_update_criminal_history', [CandidateController::class,'updateCriminalHistory']);
    Route::post('app_update_stat_dec', [CandidateController::class,'updateStatDec']);
    Route::post('app_update_police_check_authority', [CandidateController::class,'updatePoliceCheckAuthority']);
    Route::post('app_update_police_check_cost_agreement', [CandidateController::class,'updatePoliceCheckCostAgreement']);
    Route::post('app_update_fit_to_work_1', [CandidateController::class,'updateFit2Work1']);
    Route::post('app_update_fit_to_work_2', [CandidateController::class,'updateFit2Work2']);
    Route::post('app_update_health_history', [CandidateController::class,'updateHealthHistory']);
    Route::post('app_update_health_medical_info', [CandidateController::class,'updateHealthMedicalInformation']);
    Route::post('app_update_health_physical_abilities', [CandidateController::class,'updateHealthPhysicalAbilities']);
    Route::post('app_update_privacy_policy',[CandidateController::class,'updatePrivacyPolicySignature']);

    Route::post('candidates/availability',[CandidateController::class,'availabilityUpdate']);
    Route::post('candidates/availability/{candidateId}/{startDate}/{endDate}',[CandidateController::class,'showAvailabilityOnDateRange']);

    Route::get('shifts/{candidateId}/{shiftDate}',[ShiftController::class,'shiftByDate']);
    //Route::get('shifts/{candidateId}/{shiftDate}/{header}',[ShiftController::class,'shiftTestDate');
    Route::get('shifts/{candidateId}/{startDate}/{endDate}',[ShiftController::class,'shiftByDateRange']);
    Route::get('timeclock/{candidateId}/{startDate}/{endDate}',[ShiftController::class,'timeclockByDateRange']);

    //Route::get('shifts/{candidateId}/{shiftDate}/{shiftId}',[ShiftController::class,'shiftByDate');
    Route::post('shifts/update',[ShiftController::class,'shiftStatusUpdate']);
    Route::post('timeclock/create',[TimeClockController::class,'store']);
    Route::post('timeclock/update',[TimeClockController::class,'update']);
    Route::post('timeclock/status',[TimeClockController::class,'checkStatus']);
    Route::get('timeclock/{candidateId}/{shiftDate}',[TimeClockController::class,'shiftsNotClockedOut']);
    Route::get('servertime',[ServerTimeController::class,'index']);
    Route::post('deviceinformation',[UIDController::class,'update']);

    /* Extra API Endpoints for new mobile App */
    Route::get('extra/shifts/{candidateId}/{shiftDate}',[ShiftController::class,'shiftByDateExtra']);
    Route::get('extra/candidates/availability/{candidateId}/{startDate}/{endDate}',[CandidateController::class,'showAvailabilityOnDateRangeExtra']);
    Route::get('extra/shifts/pending/{candidateId}/{startDate}/{endDate}',[ShiftController::class,'pendingShiftByDateExtra']);
    Route::get('extra/shifts/confirmed/{candidateId}/{startDate}/{endDate}',[ShiftController::class,'shiftByDateRangeExtra']);
    Route::get('extra/timeclock/{candidateId}/{startDate}/{endDate}',[TimeClockController::class,'getClockInSummary']);
    Route::get('extra/candidate/{candidateId}',[CandidateController::class,'getPaySlipSummary']);
    Route::get('extra/shifts/{shiftId}',[ShiftController::class,'getShiftInformationForExtra']);
    Route::get('release/shifts/{candidateId}',[ShiftController::class,'releasedShiftsByCandidate']);
    Route::get('release/shift/{shiftId}',[ShiftController::class,'releasedShiftsByIDAndCandidate']);
    Route::get('release/shifts/actioned/{candidateId}',[ShiftController::class,'actionedReleasedShifts']);
    Route::post('release/shifts/update',[ShiftController::class,'releaseShiftStatusUpdate']);
});
