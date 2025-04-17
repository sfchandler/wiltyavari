<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShiftAddress extends Model
{
    protected $table = 'shift_address';
    protected $fillable = ['id','clientId','address','street','city','state','sub','country','postalCode','latitude','longitude'];
}
