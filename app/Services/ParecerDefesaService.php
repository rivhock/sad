<?php
namespace App\Services;

use App\Enums\StatusAvaliacao;
use App\Models\Avaliacao;
use App\Models\Defesa;
use App\Models\RespostaItem;
use Illuminate\Support\Facades\DB;

class ParecerDefesaService
{
    /**
     * Emite parecer sobre uma defesa.
     *
     * $dados = [
     *   'decisao'        => 'deferida|parcialmente_deferida|indeferida|pad',
     *   'texto'          => 'texto do parecer',
     *   'notas_ajuste'   => [item_id => nova_nota, ...] (opcional, só se deferida/parcial)
     * ]
     */
    public static function emitir(Avaliacao $avaliacao, array $dados): Defesa
    {
        $defesa = $avaliacao->defesas()->latest()->first();

        if (! $defesa) {
            throw new \Exception('Não existe defesa para esta avaliação.');
        }

        return DB::transaction(function () use ($avaliacao, $defesa, $dados) {
            $notaAnterior = $avaliacao->nota_total;
            $novaNota = null;

            // Se deferida ou parcialmente, ajusta as notas
            if (in_array($dados['decisao'], ['deferida', 'parcialmente_deferida'])) {
                $ajustes = $dados['notas_ajuste'] ?? [];

                foreach ($ajustes as $itemId => $novaNotaItem) {
                    RespostaItem::where('avaliacao_id', $avaliacao->id)
                        ->where('item_id', $itemId)
                        ->update(['nota' => $novaNotaItem]);
                }

                // Recalcula a nota total
                $respostas = RespostaItem::where('avaliacao_id', $avaliacao->id)->get();
                $soma = $respostas->sum('nota');

                $novaNota = match ($avaliacao->formulario->calculo) {
                    'media'           => $respostas->count() ? round($soma / $respostas->count(), 2) : 0,
                    'media_ponderada' => $respostas->count() ? round($soma / $respostas->count(), 2) : 0,
                    default           => round($soma, 2),
                };

                $avaliacao->update([
                    'nota_total'     => $novaNota,
                    'data_avaliacao' => now(),
                ]);
            }

            // Grava o parecer na defesa
            $defesa->update([
                'parecer_decisao'       => $dados['decisao'],
                'parecer_texto'         => $dados['texto'],
                'parecer_nota_anterior' => $notaAnterior,
                'parecer_nota_nova'     => $novaNota,
                'parecer_em'            => now(),
                'parecer_por'           => auth()->id(),
            ]);

            // Atualiza o status da avaliação
            if ($dados['decisao'] === 'pad') {
                // Encaminha para PAD — não conclui
                $avaliacao->update(['status' => StatusAvaliacao::EM_DEFESA]);
            } else {
                // Volta para "notificada" para o servidor assinar a versão final
                $avaliacao->update(['status' => StatusAvaliacao::NOTIFICADA]);
            }

            return $defesa->refresh();
        });
    }
}
