<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InterviewScheduledMail extends Mailable
{
    use Queueable, SerializesModels;

    public object $interview;

    public string $formattedSchedule;

    public function __construct(object $interview)
    {
        $this->interview = $interview;

        $this->formattedSchedule = $interview->scheduled_at
            ? Carbon::parse($interview->scheduled_at)->locale('id')->translatedFormat('l, d F Y H:i')
            : '-';
    }

    public function build(): self
    {
        return $this
            ->subject('Jadwal Wawancara Seleksi Duta PNJ')
            ->view('emails.candidates.interview-scheduled');
    }
}