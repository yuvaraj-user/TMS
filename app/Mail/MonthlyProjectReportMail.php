<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MonthlyProjectReportMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $projects;
    public $employees;
    public $project_total_hours;
    public $billable;
    public $non_billable;
    public $start_date;
    public $end_date;

    
    public function __construct($projects,$employees,$project_total_hours,$billable,$non_billable,$start_date,$end_date)
    {
        $this->projects = $projects;
        $this->employees = $employees;
        $this->project_total_hours = $project_total_hours;
        $this->billable = $billable;
        $this->non_billable = $non_billable;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $today_date = date('d-m-Y');
        return new Envelope(
            subject: 'Software Console Hourly Report Of '. date('F') . ' ' . date('Y'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'automation.monthly_project_report',
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
