<?php
namespace App\Http\Controllers;

use App\Enums\StatusAvaliacao;
use App\Models\Avaliacao;
use App\Models\LoteAvaliador;
use App\Models\RespostaItem;
use App\Services\AssinaturaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AvaliadorExternoController extends Controller
{
    // ============================================================
    // AVALIAÇÃO INDIVIDUAL (compatibilidade com o que já existe)
    // ============================================================
    public function show(string $token)
    {
        $avaliacao = Avaliacao::where('token_avaliador', $token)
            ->with(['servidor.user','servidor.cargo','servidor.lotacao','ciclo','formulario.itens','respostas.item'])
            ->first();

        if (! $avaliacao) return view('avaliador-externo.invalido');
        if (! $avaliacao->tokenAvaliadorValido()) return view('avaliador-externo.expirado');

        $jaAssinada = $avaliacao->assinaturas()->where('tipo_assinante', 'chefia')->exists();
        $assinaturaChefia = $avaliacao->assinaturas()->where('tipo_assinante', 'chefia')->first();

        return view('avaliador-externo.show', [
            'avaliacao'        => $avaliacao,
            'token'            => $token,
            'jaAssinada'       => $jaAssinada,
            'assinaturaChefia' => $assinaturaChefia,
        ]);
    }

    public function salvar(Request $request, string $token)
    {
        $avaliacao = Avaliacao::where('token_avaliador', $token)->with('formulario.itens')->first();

        if (! $avaliacao || ! $avaliacao->tokenAvaliadorValido()) {
            return redirect()->route('avaliador.externo.show', $token);
        }

        if ($avaliacao->assinadaPelaChefia()) {
            return back()->with('erro', 'Esta avaliação já foi assinada.');
        }

        $this->persistirNotas($request, $avaliacao);

        if ($request->boolean('assinar')) {
            AssinaturaService::assinarExterno(
                $avaliacao->refresh(),
                $request->input('nome_avaliador'),
                $request->input('cpf_avaliador'),
                'chefia'
            );
            return back()->with('sucesso', 'Avaliação preenchida e assinada com sucesso.');
        }

        return back()->with('sucesso', 'Avaliação salva. Você ainda pode revisar antes de assinar.');
    }

    public function assinar(Request $request, string $token)
    {
        $avaliacao = Avaliacao::where('token_avaliador', $token)->first();
        if (! $avaliacao || ! $avaliacao->tokenAvaliadorValido()) return back();

        if ($avaliacao->assinadaPelaChefia()) return back()->with('erro', 'Já assinada.');

        $data = $request->validate([
            'nome_avaliador' => 'required|string|max:255',
            'cpf_avaliador'  => 'required|string|min:11|max:14',
        ]);

        $itens = $avaliacao->formulario->itens;
        $respostas = RespostaItem::where('avaliacao_id', $avaliacao->id)->pluck('nota', 'item_id');
        foreach ($itens as $item) {
            if (! isset($respostas[$item->id])) {
                return back()->with('erro', 'Preencha todas as notas antes de assinar.');
            }
        }

        AssinaturaService::assinarExterno(
            $avaliacao,
            $data['nome_avaliador'],
            $data['cpf_avaliador'],
            'chefia'
        );

        return back()->with('sucesso', 'Avaliação assinada com sucesso.');
    }

    // ============================================================
    // LOTE
    // ============================================================
    public function showLote(Request $request, string $token)
    {
        $lote = LoteAvaliador::where('token', $token)->first();
        if (! $lote) return view('avaliador-externo.invalido');
        if (! $lote->valido()) return view('avaliador-externo.expirado');

        // Sessão identifica por token
        $chave = 'lote_identificado_' . $token;
        $identificado = session()->has($chave);

        if (! $identificado) {
            return view('avaliador-externo.lote-identificacao', [
                'lote'  => $lote,
                'token' => $token,
            ]);
        }

        $avaliacoes = $lote->avaliacoes()
            ->with(['servidor.user','servidor.cargo','servidor.lotacao','ciclo','formulario.itens','respostas.item','assinaturas'])
            ->get();

        return view('avaliador-externo.lote-lista', [
            'lote'        => $lote,
            'token'       => $token,
            'avaliacoes'  => $avaliacoes,
            'nomeChefia'  => session($chave . '_nome'),
            'cpfChefia'   => session($chave . '_cpf'),
        ]);
    }

    public function identificarLote(Request $request, string $token)
    {
        $lote = LoteAvaliador::where('token', $token)->first();
        if (! $lote || ! $lote->valido()) return redirect()->route('avaliador.externo.lote.show', $token);

        $data = $request->validate([
            'nome_avaliador' => 'required|string|max:255',
            'cpf_avaliador'  => 'required|string|min:11|max:14',
        ], [
            'nome_avaliador.required' => 'Informe o nome completo.',
            'cpf_avaliador.required'  => 'Informe o CPF.',
        ]);

        $cpf = preg_replace('/\D/', '', $data['cpf_avaliador']);
        if (strlen($cpf) !== 11) {
            return back()->withInput()->with('erro', 'CPF inválido.');
        }

        // Grava no lote (só na primeira vez)
        if (! $lote->identificado()) {
            $lote->update([
                'nome_avaliador'   => $data['nome_avaliador'],
                'cpf_avaliador'    => $cpf,
                'identificado_em'  => now(),
            ]);
        }

        // Sessão
        session()->put('lote_identificado_' . $token, true);
        session()->put('lote_identificado_' . $token . '_nome', $data['nome_avaliador']);
        session()->put('lote_identificado_' . $token . '_cpf', $cpf);

        return redirect()->route('avaliador.externo.lote.show', $token);
    }

    public function showAvaliacaoDoLote(string $token, Avaliacao $avaliacao)
    {
        $lote = LoteAvaliador::where('token', $token)->first();
        if (! $lote || ! $lote->valido()) return view('avaliador-externo.expirado');

        if (! session()->has('lote_identificado_' . $token)) {
            return redirect()->route('avaliador.externo.lote.show', $token);
        }

        // Confirma que a avaliação pertence ao lote
        if (! $lote->avaliacoes()->where('avaliacoes.id', $avaliacao->id)->exists()) {
            abort(404);
        }

        $avaliacao->load(['servidor.user','servidor.cargo','servidor.lotacao','ciclo','formulario.itens','respostas.item','assinaturas']);

        return view('avaliador-externo.lote-avaliacao', [
            'lote'       => $lote,
            'token'      => $token,
            'avaliacao'  => $avaliacao,
            'nomeChefia' => session('lote_identificado_' . $token . '_nome'),
            'cpfChefia'  => session('lote_identificado_' . $token . '_cpf'),
            'jaAssinada' => $avaliacao->assinadaPelaChefia(),
        ]);
    }

    public function salvarAvaliacaoDoLote(Request $request, string $token, Avaliacao $avaliacao)
    {
        $lote = LoteAvaliador::where('token', $token)->first();
        if (! $lote || ! $lote->valido()) return redirect()->route('avaliador.externo.lote.show', $token);

        if (! session()->has('lote_identificado_' . $token)) {
            return redirect()->route('avaliador.externo.lote.show', $token);
        }

        if (! $lote->avaliacoes()->where('avaliacoes.id', $avaliacao->id)->exists()) {
            abort(404);
        }

        if ($avaliacao->assinadaPelaChefia()) {
            return back()->with('erro', 'Esta avaliação já foi assinada.');
        }

        $this->persistirNotas($request, $avaliacao);

        $assinou = false;
        if ($request->boolean('assinar')) {
            AssinaturaService::assinarExterno(
                $avaliacao->refresh(),
                session('lote_identificado_' . $token . '_nome'),
                session('lote_identificado_' . $token . '_cpf'),
                'chefia'
            );
            $assinou = true;
        }

        // Marca no pivot
        $lote->avaliacoes()->updateExistingPivot($avaliacao->id, [
            'preenchida_em' => now(),
            'assinada_em'   => $assinou ? now() : null,
        ]);

        if ($assinou) {
            return redirect()->route('avaliador.externo.lote.show', $token)
                ->with('sucesso', 'Avaliação de ' . $avaliacao->servidor->user->nome . ' assinada com sucesso.');
        }

        return back()->with('sucesso', 'Avaliação salva. Você pode continuar ou assinar.');
    }

    // ============================================================
    // AUXILIAR
    // ============================================================
    protected function persistirNotas(Request $request, Avaliacao $avaliacao): void
    {
        $data = $request->validate([
            'observacoes'   => 'nullable|string|max:5000',
            'notas'         => 'required|array',
            'notas.*'       => 'required|numeric|min:0|max:10',
            'comentarios'   => 'nullable|array',
            'comentarios.*' => 'nullable|string|max:1000',
        ], [
            'notas.*.required' => 'Todas as notas devem ser preenchidas.',
            'notas.*.min'      => 'A nota mínima é 0.',
            'notas.*.max'      => 'A nota máxima é 10.',
        ]);

        DB::transaction(function () use ($avaliacao, $data) {
            foreach ($avaliacao->formulario->itens as $item) {
                $nota = $data['notas'][$item->id] ?? null;
                if ($nota === null || $nota === '') continue;

                RespostaItem::updateOrCreate(
                    ['avaliacao_id' => $avaliacao->id, 'item_id' => $item->id],
                    [
                        'nota'           => $nota,
                        'comentario'     => $data['comentarios'][$item->id] ?? null,
                        'descricao_item' => $item->descricao,
                        'ordem_item'     => $item->ordem,
                    ]
                );
            }

            $respostas = RespostaItem::where('avaliacao_id', $avaliacao->id)->get();
            $soma = $respostas->sum('nota');

            $notaTotal = match ($avaliacao->formulario->calculo) {
                'media'           => $respostas->count() ? round($soma / $respostas->count(), 2) : 0,
                'media_ponderada' => $respostas->count() ? round($soma / $respostas->count(), 2) : 0,
                default           => round($soma, 2),
            };

            $avaliacao->update([
                'nota_total'     => $notaTotal,
                'observacoes'    => $data['observacoes'] ?? null,
                'data_avaliacao' => now(),
                'status'         => StatusAvaliacao::PREENCHIDA,
            ]);
        });
    }
}
