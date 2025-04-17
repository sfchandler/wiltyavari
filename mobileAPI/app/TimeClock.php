<?php

namespace App;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class TimeClock extends Model
{
    protected $table = 'timeclock';
    protected $fillable = ['candidateId','shiftDate','shiftId','shiftDay','clientId','positionId','deptId','checkIn','checkOut','workBreak','wrkhrs','supervicerId','supervisor','created_at','updated_at'];
}
