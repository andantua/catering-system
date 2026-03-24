<?php

namespace App\Mail;

use App\Models\Ward;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $ward;
    public $orders;
    public $submittedAt;

    /**
     * Create a new message instance.
     */
    public function __construct(Ward $ward, Collection $orders, $submittedAt)
    {
        $this->ward = $ward;
        $this->orders = $orders;
        $this->submittedAt = $submittedAt;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Potwierdzenie zamówienia posiłków - ' . now()->format('d.m.Y'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order-confirmation',
        );
    }

    /**
     * Get the attachments for the array.
     */
    public function attachments(): array
    {
        return [];
    }
}