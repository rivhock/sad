<?php
namespace App\Mail;

use App\Models\Avaliacao;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AvaliacaoAtribuidaAvaliador extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Avaliacao $avaliacao) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nova avaliação atribuída a você — ' . $this->avaliacao->servidor?->user?->nome,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.avaliacao-atribuida',
            with: ['avaliacao' => $this->avaliacao],
        );
    }
}
