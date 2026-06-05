<?php

namespace App\Mail;

use App\Models\Candidate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CandidateAcceptedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Candidate $candidate
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pendaftaran Duta Kampus Diterima'
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.candidates.accepted'
        );
    }
}
