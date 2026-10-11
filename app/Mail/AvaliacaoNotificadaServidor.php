<?php
namespace App\Mail;

use App\Models\Avaliacao;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AvaliacaoNotificadaServidor extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Avaliacao $avaliacao) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Sua avaliação está disponível — ' . $this->avaliacao->ciclo?->nome,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.avaliacao-notificada',
            with: [
                'avaliacao' => $this->avaliacao,
                'link'      => url('/avaliacao/' . $this->avaliacao->token_acesso),
            ],
        );
    }
}
