<?php

namespace App\Console\Commands;

use App\Models\InboxReference;
use App\Models\Resume;
use App\Models\ResumeAttachment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Webklex\IMAP\Facades\Client;

class MailGrabber extends Command
{
    private $client;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailgrabber:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /*$client = Client::account("default");
        $client->connect();
        $folders = $client->getFolders(false);
        foreach($folders as $folder){
            $this->info("Accessing folder: ".$folder->path);
            $messages = $folder->messages()->all()->limit(3, 0)->get();
            $this->info("Number of messages: ".$messages->count());
            foreach ($messages as $message) {
                $this->info("\tMessage: ".$message->message_id);
            }
        }
        return 0;*/
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
        $this->getInboxMessages();
    }

    public function getInboxMessages() {
        try {
            $folder = $this->client->getFolder('INBOX');
            $allMessages = $folder->messages()->all()->get();
            foreach ($allMessages as $message) {
                $messageId = $message->getMessageId();
                $subject = $message->getSubject();
                $mailBodyText = $message->getTextBody();
                $mailBodyHTML = $message->getHTMLBody();
                $uid = $message->getUid();
                $mailFrom = $message->getFrom();
                $mailTo = $message->getTo();
                $mailDate = $message->getDate();
                $this->updateResumeInformation($messageId,$uid,$mailFrom,$mailTo,$mailDate,$subject,$mailBodyText,$mailBodyHTML);
                if($message->getAttachments()->count()>0) {
                    $message->getAttachments()->each(function ($attachment) use ($message) {
                        $attachmentDate =  date('Y-m-d', strtotime($message->getDate()));
                        $attachmentName = $attachment->name;
                        $alphabeticalDirectory = strtoupper($attachmentName[0]);
                        Storage::disk('attachments')->put('/'.$attachmentDate.'/'.$alphabeticalDirectory.'/'.$attachmentName, $attachment->content);
                        $this->updateAttachmentPath($message->getMessageId(),'./attachments/'.$attachmentDate.'/'.$alphabeticalDirectory.'/'.$attachmentName,$attachmentName);
                    });
                }
            }
        }catch (\Exception $e){
            Log::error('Error updating inbox data - '.$e->getMessage());
        }
    }
    public function updateResumeInformation($messageId,$uid,$mailFrom,$mailTo,$mailDate,$subject,$mailBodyText,$mailBodyHTML)
    {
        try {
            $str = explode('ref:',$subject);
            if(!empty($str[1])) {
                $reference = strtoupper(str_replace('/','',trim(str_replace(' ', '', $str[1]))));
                if(!empty($reference)){
                    if(DB::connection('outapay-demo')->table('inbox_references')->where('reference',$reference)->count() == 0) {
                        DB::connection('outapay-demo')->table('inbox_references')->insert(['reference' => $reference, 'status' => 1]);
                        /*$inboxReference = new InboxReference();
                        $inboxReference->reference = $reference;
                        $inboxReference->status = 1;
                        $inboxReference->save();*/
                    }
                }
                $status = DB::connection('outapay-demo')->table('inbox_references')->select('status')->where('reference',$reference)->value('status');
                if(empty($status)){
                    $status = 1;
                }elseif ($status == '0'){
                    $status = 0;
                }elseif ($status == '1'){
                    $status = 1;
                }
            }else{
                $reference = 'UNKNOWN';
                $status = 0;
            }
            if(empty($mailBodyHTML)){
                $messageBody = $mailBodyText;
            }else{
                $messageBody = $mailBodyHTML;
            }
            if(DB::connection('outapay-demo')->table('resume')->where('messageid', $messageId)->count() > 0){
                DB::connection('outapay-demo')->table('resume')
                    ->update([
                        'subject' => $subject,
                        'msgBody' => $messageBody,
                        'reference' => $reference,
                        'status' => $status,
                    ])
                    ->where('messageid', $messageId);
            }else{
                /*$resumeInbox = new Resume();
                $resumeInbox->messageid = $messageId;
                $resumeInbox->uid = $uid;
                $resumeInbox->msgno = $uid;
                $resumeInbox->mailfrom = $mailFrom;
                $resumeInbox->mailto = $mailTo;
                $resumeInbox->subject = $subject;
                $resumeInbox->msgbody = $messageBody;
                $resumeInbox->date = date('Y-m-d H:i:s', strtotime($mailDate));
                $resumeInbox->reference = $reference;
                $resumeInbox->status = $status;
                $resumeInbox->save();*/
                DB::connection('outapay-demo')->table('resume')->insert(['messageid' => $messageId,
                'uid' => $uid,
                'msgno' => $uid,
                'mailfrom' => $mailFrom,
                'mailto' => $mailTo,
                'subject' => $subject,
                'msgbody' => $messageBody,
                'date' => date('Y-m-d H:i:s', strtotime($mailDate)),
                'reference' => $reference,
                'status' => $status
                ]);
            }
        }catch (\Exception $e){
            echo $e->getMessage();
            Log::error('Error updating resume information - '.$e->getMessage());
        }
    }
    public function updateAttachmentPath($messageId,$attachmentPath,$attachmentName)
    {
        try {
            if(DB::connection('outapay-demo')->table('attachmentpath')->where('messageid', $messageId)->where('filename',$attachmentName)->count() == 0){
                $extension = pathinfo($attachmentName, PATHINFO_EXTENSION);
                if ($extension == 'pdf' || $extension == 'doc' || $extension == 'docx' || $extension == 'rtf') {
                    DB::connection('outapay-demo')->table('attachmentpath')->insert(['messageid'=>$messageId,'filepath'=>$attachmentPath,'filename'=>$attachmentName]);
                   /* $resumeAttachment = new ResumeAttachment();
                    $resumeAttachment->messageid = $messageId;
                    $resumeAttachment->filepath = $attachmentPath;
                    $resumeAttachment->filename = $attachmentName;
                    $resumeAttachment->save();*/
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }catch (\Exception $e){
            Log::error('Error updating resume attachment path '.$e->getMessage());
        }
    }
}
