<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminRemoveUser extends Mailable
{
    use Queueable, SerializesModels;
    public $mailDataForRemoveUserFromSharepoint;
    /**
     * Create a new message instance.
     */
    public function __construct($mailDataForRemoveUserFromSharepoint)
    {
        $this->mailDataForRemoveUserFromSharepoint = $mailDataForRemoveUserFromSharepoint;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Admin Remove User',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.AdminRemoveUser',
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
