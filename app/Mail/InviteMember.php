<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InviteMember extends Mailable
{
    use Queueable, SerializesModels;

    public string $form_name;
    public string $owner_name;

    /**
     * Create a new message instance.
     */
    public function __construct(string $form_name, string $owner_name)
    {
        $this->form_name = $form_name;
        $this->owner_name = $owner_name;
    }

    /**
     * 件名
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: config('app.name') . 'へ' . $this->owner_name . 'さんから招待がありました。',
        );
    }

    /**
     * 本文
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.invite_member',
            with: [
                'password' => '123456789',
            ],
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
