<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailGenerator extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $temp;

    public function __construct($temp)
    {
        $this->temp = $temp;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //return $this->view('view.name');
        return $this->from('donotreply@labourbank.com.au')
            ->subject($this->temp->subject)
            ->view('mails.temp')
            ->text('mails.temp_plain')
            ;
    }
    //->with(['testVarOne'=>1,
    //                'testVarTwo'=>2,])
    //            ->attach(public_path('images').'untitled.png',['as'=>'untitled.png','mime'=>'image/png',])
}
