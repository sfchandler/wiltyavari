<?php

namespace App\Http\Controllers\Mail;

use App\Http\Controllers\Controller;
use App\Services\EmailService;
use Illuminate\Http\Request;

class MailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private EmailService $emailService;

    public function __construct(EmailService $emailService){
        $this->emailService = $emailService;
    }
    public function index(EmailService $emailService)
    {
        $emails = $emailService->getInboxMessages();
        return view('emails.inbox', compact('emails'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
