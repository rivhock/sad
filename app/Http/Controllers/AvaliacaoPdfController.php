<?php
namespace App\Http\Controllers;

use App\Models\Avaliacao;
use Barryvdh\DomPDF\Facade\Pdf;

class AvaliacaoPdfController extends Controller
{
    public function download(Avaliacao $avaliacao)
    {
        $avaliacao->load([
            'servidor.user','servidor.cargo','servidor.secretaria','servidor.lotacao',
            'avaliador','ciclo','formulario.itens',
            'respostas.item','assinaturas.user','defesas','qualificacoes',
        ]);

        $pdf = Pdf::loadView('pdf.avaliacao', [
            'avaliacao' => $avaliacao,
        ]);

        $pdf->setPaper('A4', 'portrait');

        $nome = 'avaliacao-' . \Illuminate\Support\Str::slug($avaliacao->servidor?->user?->nome ?? 'servidor')
              . '-' . $avaliacao->ciclo?->nome
              . '.pdf';

        return $pdf->download($nome);
    }

    public function stream(Avaliacao $avaliacao)
    {
        $avaliacao->load([
            'servidor.user','servidor.cargo','servidor.secretaria','servidor.lotacao',
            'avaliador','ciclo','formulario.itens',
            'respostas.item','assinaturas.user','defesas','qualificacoes',
        ]);

        $pdf = Pdf::loadView('pdf.avaliacao', ['avaliacao' => $avaliacao]);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('avaliacao.pdf');
    }
}
