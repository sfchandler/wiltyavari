<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CandidateDocument extends Model
{
    protected $table = 'candidate_document';
    protected $fillable = ['candidateId','fileName','filePath','docTypeId'];
}
