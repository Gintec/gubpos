<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendPDFEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $receiverName;
    public $subject;
    public $pdfPath;

    public function __construct($receiverName, $subject, $pdfPath)
    {
        $this->receiverName = $receiverName;
        $this->subject = $subject;
        $this->pdfPath = $pdfPath;
    }



    public function build()
    {

        return $this->subject($this->subject)
                    ->view('emails.email_template')
                    ->attach(public_path('pdf/'.basename($this->pdfPath)), [
                        'as' => 'attachment.pdf',
                        'mime' => 'application/pdf',
                    ]);
    }
}
