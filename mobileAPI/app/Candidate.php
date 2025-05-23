<?php

namespace App;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $primaryKey = 'candidate_no';
    protected $table = 'candidate';
    protected $fillable = [
        'candidate_no',
        'clockPin',
        'candidateId',
        'tandaUserId',
        'lamattinaId',
        'axiomno',
        'messageid',
        'firstName',
        'middle_name',
        'nickname',
        'lastName',
        'address',
        'street_number',
        'street_name',
        'state',
        'postcode',
        'homePhoneNo',
        'mobileNo',
        'email',
        'sex',
        'screenDate',
        'suburb',
        'currentWrk',
        'howfar',
        'genLabourPay',
        'criminalConviction',
        'convictionDescription',
        'hasCar',
        'licenceType',
        'residentStatus',
        'medicalCondition',
        'medicalConditionDesc',
        'workType','overtime',
        'bookInterview',
        'intvwTime',
        'consultantId',
        'status',
        'dob',
        'candidateStatus',
        'tfn',
        'password',
        'type',
        'apiKey',
        'supervicerId',
        'employeeImage',
        'token',
        'superMemberNo',
        'empStatus',
        'empCondition',
        'reg_pack_status',
        'foundhow',
        'promotion',
        'reg_pack_sent_time',
        'ohsCheckStatus',
        'ohsCheckedBy',
        'ohsCheckedTime',
        'reg_app_completion',
        'reg_app_active',
        'reg_app_progress',
        'reg_app_contracts_active',
        'reg_app_contracts_progress',
        'reg_app_contracts_progress_color',
        'reg_app_update_active'];
}
