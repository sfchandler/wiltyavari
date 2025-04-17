<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReleaseShift extends Model
{
    protected $table = 'release_shift';
    protected $primaryKey = 'rel_shift_id';
    protected $fillable = ['rel_shift_id',
        'rel_shift_date',
        'rel_shift_day',
        'rel_client_id',
        'rel_state_id',
        'rel_dept_id',
        'rel_position_id',
        'rel_shift_start',
        'rel_shift_end',
        'rel_shift_break',
        'rel_shift_status',
        'rel_address_id'];
}
