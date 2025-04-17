<?php

use App\Http\Controllers\Mail\MailController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/emails',[MailController::class,'index'])->name('emails');
