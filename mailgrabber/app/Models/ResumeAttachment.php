<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResumeAttachment extends Model
{
    use HasFactory;
    protected $connection = 'outapay-demo';
    protected $table = 'krattachmentpath';
    protected $primaryKey = 'krId';
    protected $fillable = ['messageid','filepath','filename','updated_at'];
}
