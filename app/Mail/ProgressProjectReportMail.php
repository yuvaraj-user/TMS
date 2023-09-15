<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProgressProjectReportMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $progress_projects;
    public function __construct($progress_projects)
    {
        $this->progress_projects = $progress_projects; 
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $today_date = date('d-m-Y');
        return new Envelope(
            subject: $today_date.' - Progress Project Report Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'automation.progress_project_weekly_report',
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
