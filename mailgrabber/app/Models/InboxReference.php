<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InboxReference extends Model
{
    use HasFactory;
    protected $connection = 'outapay-demo';
    protected $table = 'inbox_reference';
    protected $primaryKey = 'id';
    protected $fillable = ['reference','status'];
}
