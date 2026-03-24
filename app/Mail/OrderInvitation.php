<?php

namespace App\Mail;

use App\Models\Ward;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $ward;
    public $token;
    public $code;

    /**
     * Create a new message instance.
     */
    public function __construct(Ward $ward, string $token, string $code)
    {
        $this->ward = $ward;
        $this->token = $token;
        $this->code = $code;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Zaproszenie do zamówienia posiłków - ' . now()->format('d.m.Y'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order-invitation',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}