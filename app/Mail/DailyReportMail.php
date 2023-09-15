<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailyReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $daily_reports;
    /**
     * Create a new message instance.
     */
    public function __construct($daily_reports)
    {
        $this->daily_reports = $daily_reports;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $today_date = date('d-m-Y');
        return new Envelope(
            subject: $today_date.' - Daily Report Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'automation.daily_report',
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
