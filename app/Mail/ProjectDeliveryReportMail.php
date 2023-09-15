<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProjectDeliveryReportMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $delivered_projects;
    public $start_date;
    public function __construct($delivered_projects,$start_date)
    {
        $this->delivered_projects = $delivered_projects;
        $this->start_date         = $start_date;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $today_date = date('d-m-Y');
        return new Envelope(
            subject: ' Delivery Report - '. date('F') . ' ' . date('Y'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'automation.project_delivery_report',
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
