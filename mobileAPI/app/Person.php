<?php

namespace App;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'person';
    protected $fillable = ['firstName','lastName','gender','phoneNumber','email','address'];
}
