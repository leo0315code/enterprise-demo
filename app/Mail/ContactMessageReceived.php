<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * 新留言通知邮件（发送给站点配置的联系邮箱）
 */
class ContactMessageReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ContactMessage $contactMessage)
    {
    }

    public function envelope(): Envelope
    {
        $subject = '【官网留言】来自 '.$this->contactMessage->name
            .($this->contactMessage->subject ? '：'.$this->contactMessage->subject : '');

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-message',
            with: ['msg' => $this->contactMessage],
        );
    }
}
