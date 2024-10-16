<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailables\Address;

class SendTestMail extends Mailable
{
    use Queueable, SerializesModels;
    public $sendTestMailData;
    /**
     * Create a new message instance.
     */
    public function __construct($sendTestMailData)
    {
        $this->sendTestMailData = $sendTestMailData;
       
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    { 
        $month_year = date('F Y',strtotime("-30 days"));
        return new Envelope(
            subject: "Month End Portfolios - $month_year",
            replyTo: [
                new Address($this->sendTestMailData['replytoemail'], ($this->sendTestMailData['replytoname']))
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.SendTestMail',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
