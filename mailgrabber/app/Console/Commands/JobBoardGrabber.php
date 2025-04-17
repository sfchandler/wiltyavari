<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class JobBoardGrabber extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobboardgrabber:run';

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
        $this->client = \Webklex\IMAP\Facades\Client::make([
            'username'      => config('imap.accounts.default.username'),
            'password'      => config('imap.accounts.default.password'),
            'host'          => config('imap.accounts.default.host'),
            'port'          => config('imap.accounts.default.port'),
            'encryption'    => config('imap.accounts.default.encryption'),
            'protocol'      => config('imap.accounts.default.protocol'),
            'validate_cert' => config('imap.accounts.default.validate_cert'),
        ]);
        try {
            //  Connect to the IMAP Server
            $this->client->connect();
        }catch (Exception $e) {
            echo 'Exception : ',  $e->getMessage(), "\n";
        }

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
                $this->updateJobBoardResumeInformation($messageId,$uid,$mailFrom,$mailTo,$mailDate,$subject,$mailBodyText,$mailBodyHTML);
                if($message->getAttachments()->count()>0) {
                    $message->getAttachments()->each(function ($attachment) use ($message) {
                        $attachmentDate =  date('Y-m-d', strtotime($message->getDate()));
                        $attachmentName = $attachment->name;
                        $alphabeticalDirectory = strtoupper($attachmentName[0]);
                        Storage::disk('jbattachments')->put('/'.$attachmentDate.'/'.$alphabeticalDirectory.'/'.$attachmentName, $attachment->content);
                        $this->updateJobBoardAttachmentPath($message->getMessageId(),'./jbattachments/'.$attachmentDate.'/'.$alphabeticalDirectory.'/'.$attachmentName,$attachmentName);
                    });
                }
            }
        }catch (\Exception $e){
            Log::error('Error updating inbox data - '.$e->getMessage());
        }
    }
    public function updateJobBoardResumeInformation($messageId,$uid,$mailFrom,$mailTo,$mailDate,$subject,$mailBodyText,$mailBodyHTML)
    {
        try {
            //$str = explode('ref:',$subject);//explode(DOMAIN_URL.'/job-post/',$mail->subject);
            $str = explode('https://www.outapay.com.au/job-post/',$subject);
            if(!empty($str[1])) {
                $reference = strtoupper(str_replace('/','',trim(str_replace(' ', '', $str[1]))));
                if(!empty($reference)){
                    if(DB::connection('outapay-demo')->table('job_board_reference')->where('reference',$reference)->count() == 0) {
                        DB::connection('outapay-demo')->table('job_board_reference')->insert(['reference' => $reference, 'status' => 1]);
                        /*$inboxReference = new InboxReference();
                        $inboxReference->reference = $reference;
                        $inboxReference->status = 1;
                        $inboxReference->save();*/
                    }
                }
                $status = DB::connection('outapay-demo')->table('job_board_reference')->select('status')->where('reference',$reference)->value('status');
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
            if(DB::connection('outapay-demo')->table('jobboard_resume')->where('messageid', $messageId)->count() > 0){
                DB::connection('outapay-demo')->table('jobboard_resume')
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
                DB::connection('outapay-demo')->table('jobboard_resume')->insert(['messageid' => $messageId,
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
    public function updateJobBoardAttachmentPath($messageId,$attachmentPath,$attachmentName)
    {
        try {
            if(DB::connection('outapay-demo')->table('jbattachmentpath')->where('messageid', $messageId)->where('filename',$attachmentName)->count() == 0){
                $extension = pathinfo($attachmentName, PATHINFO_EXTENSION);
                if ($extension == 'pdf' || $extension == 'doc' || $extension == 'docx' || $extension == 'rtf') {
                    DB::connection('outapay-demo')->table('jbattachmentpath')->insert(['messageid'=>$messageId,'filepath'=>$attachmentPath,'filename'=>$attachmentName]);
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
