<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReleaseShiftLog extends Model
{
    protected $table = 'release_shift_log';
    protected $fillable = ['rel_shift_id',
        'rel_shift_status',
        'candidate_id',
        'created_at',
        'updated_at'];
}
