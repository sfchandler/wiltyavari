<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserActivityLog extends Model
{
    protected $table = 'user_activity_log';
    protected $primaryKey = 'id';
    protected $fillable = [
        'username', 'ip', 'page','activity_type','activity_detail','log_time'
    ];

}
