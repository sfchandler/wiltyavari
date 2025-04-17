<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $table = 'employee_bank_account';
    protected $fillable = ['candidateId','bankName','accountName','accountNumber','bsb'];
}
