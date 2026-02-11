<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $name;
    public string $email;
    public string $body;

    /**
     * Create a new message instance.
     */
    public function __construct(string $name, string $email, string $body)
    {
        $this->name = $name;
        $this->email = $email;
        $this->body = $body;
    }

    /**
     * 件名
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'お問い合わせが届きました',
        );
    }

    /**
     * 本文
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contact',
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
