<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    use HasFactory;
    protected $connection = 'outapay-demo';
    protected $table = 'resumekr';
    protected $primaryKey = 'autoid';
    protected $fillable = ['messageid','uid','msgno','mailfrom','mailto','subject','msgbody','email','phone_number','date','reference','inbox_status','status'];
}
