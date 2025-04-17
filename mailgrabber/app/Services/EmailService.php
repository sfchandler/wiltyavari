<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Webklex\PHPIMAP\Client;

class EmailService
{
    private $client;

    public function __construct() {
        /*$this->client = new Client([
            'host'          => config('imap.accounts.default.host'),
            'port'          => config('imap.accounts.default.port'),
            'encryption'    => config('imap.accounts.default.encryption'),
            'validate_cert' => config('imap.accounts.default.validate_cert'),
            'username'      => config('imap.accounts.default.username'),
            'password'      => config('imap.accounts.default.password'),
            'protocol'      => config('imap.accounts.default.protocol'),
        ]);
        $this->client->connect();*/
        $this->client = \Webklex\IMAP\Facades\Client::make([
            'username'      => config('imap.accounts.default.username'),
            'password'      => config('imap.accounts.default.password'),
            'host'          => config('imap.accounts.default.host'),
            'port'          => config('imap.accounts.default.port'),
            'encryption'    => config('imap.accounts.default.encryption'),
            'protocol'      => config('imap.accounts.default.protocol'),
            'validate_cert' => config('imap.accounts.default.validate_cert'),
        ]);
        $this->client->connect();
    }

    public function getInboxMessages() {
        try {
            $folder = $this->client->getFolder('INBOX');
            $allMessages = $folder->messages()->all()->get();
            foreach ($allMessages as $message) {
                echo $message->getSubject().'<br>';
                echo $message->getTextBody().'<br>';
                echo $message->getTo().'<br>';
                // Save message attachments:
                if($message->getAttachments()->count()>0) {
                    $message->getAttachments()->each(function ($attachment) use ($message) {
                        /*file_put_contents(storage_path('attachments/' . $message->getMessageId() . '/' . $attachment->name), $attachment->content);*/
                        Storage::disk('attachments')->put($attachment->name, $attachment->content);
                    });
                }
            }
        }catch (\Exception $e){
            echo $e->getMessage();
        }
    }


}
