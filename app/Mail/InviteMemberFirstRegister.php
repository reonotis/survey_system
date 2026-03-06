<?php

namespace App\Mail;

use App\Traits\PasswordTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InviteMemberFirstRegister extends Mailable
{
    use PasswordTrait, Queueable, SerializesModels;

    public string $form_name;
    public string $to_mail_address;
    public string $owner_name;
    public string $password;

    /**
     * Create a new message instance.
     */
    public function __construct(string $to_mail_address, string $form_name, string $owner_name, string $password)
    {
        $this->to_mail_address = $to_mail_address;
        $this->form_name = $form_name;
        $this->owner_name = $owner_name;
        $this->password = $password;
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
            view: 'emails.invite_member_first_register',
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
