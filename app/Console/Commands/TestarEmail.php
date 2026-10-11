<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestarEmail extends Command
{
    protected $signature = 'sad:testar-email {email}';
    protected $description = 'Envia email de teste';

    public function handle(): int
    {
        $email = $this->argument('email');
        $this->info("Enviando email de teste para: {$email}");

        try {
            Mail::raw(
                "Teste do SAD Campo Bom\n\n" .
                "Se voce recebeu este email, o SMTP esta funcionando.\n\n" .
                "Data: " . now()->format('d/m/Y H:i:s'),
                function ($message) use ($email) {
                    $message->to($email)
                            ->subject('Teste de envio — SAD Campo Bom');
                }
            );

            $this->info('OK — Email enviado com sucesso!');
            $this->info('Verifique a caixa de entrada (e o spam).');
            return 0;

        } catch (\Throwable $e) {
            $this->error('ERRO: ' . $e->getMessage());
            return 1;
        }
    }
}
