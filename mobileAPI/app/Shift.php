<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $table = 'shift';
    protected $fillable = ['shiftId','tandaShiftId','tandaTimesheetId','shiftDate','shiftDay','clientId','stateId','departmentId','candidateId','shiftStart','shiftEnd','workBreak','shiftNote','shiftStatus','shiftSMSStatus','consultantId','positionId','timeSheetStatus','addressId'];
}
