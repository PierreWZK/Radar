<?php

namespace App\Mail;

use App\Models\Alerte;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AlertePerformance extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Alerte $alerte) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: "Radar : {$this->alerte->page->url} a perdu des points");
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.alerte', with: ['alerte' => $this->alerte]);
    }
}
