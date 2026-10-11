<?php
namespace App\Http\Controllers;

use App\Enums\StatusAvaliacao;
use App\Models\Avaliacao;
use App\Models\Defesa;
use App\Models\Qualificacao;
use App\Services\AssinaturaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AvaliacaoPublicaController extends Controller
{
    public function show(string $token)
    {
        $avaliacao = Avaliacao::where('token_acesso', $token)
            ->with(['servidor.user'])
            ->firstOrFail();

        if (! $avaliacao->tokenValido()) {
            return view('avaliacao-publica.expirado');
        }

        if (! session()->has("avaliacao_liberada_{$token}")) {
            return view('avaliacao-publica.verificacao', [
                'avaliacao' => $avaliacao,
                'token'     => $token,
            ]);
        }

        $avaliacao->load([
            'servidor.user','servidor.cargo','servidor.secretaria','servidor.lotacao',
            'avaliador','ciclo','formulario.itens',
            'respostas.item','assinaturas.user','defesas',
        ]);

        $jaAssinou = $avaliacao->assinaturas->where('tipo_assinante', 'avaliado')->count() > 0;
        $assinaturaChefia = $avaliacao->assinaturas->where('tipo_assinante', 'chefia')->first();

        // Histórico: outras avaliações concluídas do mesmo servidor
        $avaliacoesAnteriores = Avaliacao::where('servidor_id', $avaliacao->servidor_id)
            ->where('id', '!=', $avaliacao->id)
            ->whereIn('status', ['concluida', StatusAvaliacao::CONCLUIDA->value])
            ->with(['ciclo','formulario.itens','respostas.item','avaliador'])
            ->orderByDesc('periodo_fim')
            ->get();

        $qualificacoes = Qualificacao::where('servidor_id', $avaliacao->servidor_id)
            ->orderByDesc('data_fim')
            ->get();

        $qualificacoesPorAno = $qualificacoes->groupBy(fn ($q) => $q->data_fim?->format('Y'));

        $totalHoras     = $qualificacoes->sum('carga_horaria');
        $totalAprovadas = $qualificacoes->where('status', 'aprovado')->count();
        $totalPendentes = $qualificacoes->where('status', 'pendente')->count();
        $horasAprovadas = $qualificacoes->where('status', 'aprovado')->sum('carga_horaria');

        return view('avaliacao-publica.show', [
            'avaliacao'            => $avaliacao,
            'jaAssinou'            => $jaAssinou,
            'assinaturaChefia'     => $assinaturaChefia,
            'token'                => $token,
            'avaliacoesAnteriores' => $avaliacoesAnteriores,
            'qualificacoes'        => $qualificacoes,
            'qualificacoesPorAno'  => $qualificacoesPorAno,
            'totalHoras'           => $totalHoras,
            'horasAprovadas'       => $horasAprovadas,
            'totalAprovadas'       => $totalAprovadas,
            'totalPendentes'       => $totalPendentes,
        ]);
    }

    public function verificar(Request $request, string $token)
    {
        $avaliacao = Avaliacao::where('token_acesso', $token)->with('servidor.user')->firstOrFail();

        if (! $avaliacao->tokenValido()) {
            return view('avaliacao-publica.expirado');
        }

        $data = $request->validate(['cpf' => 'required|string']);
        $cpf         = preg_replace('/\D/', '', $data['cpf']);
        $cpfEsperado = preg_replace('/\D/', '', (string) $avaliacao->servidor?->user?->cpf);

        if (strlen($cpf) !== 11 || ! $cpfEsperado || $cpf !== $cpfEsperado) {
            return back()->withInput()->with('erro', 'CPF não confere.');
        }

        session()->put("avaliacao_liberada_{$token}", true);
        return redirect()->route('avaliacao.publica.show', $token);
    }

    public function assinar(Request $request, string $token)
    {
        $avaliacao = Avaliacao::where('token_acesso', $token)->firstOrFail();

        if (! session()->has("avaliacao_liberada_{$token}")) return redirect()->route('avaliacao.publica.show', $token);
        if (! $avaliacao->tokenValido()) abort(410, 'Este link expirou.');

        if ($avaliacao->assinaturas()->where('tipo_assinante', 'avaliado')->exists()) {
            return redirect()->route('avaliacao.publica.show', $token)->with('erro', 'Já assinada.');
        }
        if (! $avaliacao->assinaturas()->where('tipo_assinante', 'chefia')->exists()) {
            return redirect()->route('avaliacao.publica.show', $token)->with('erro', 'A chefia ainda não assinou.');
        }

        AssinaturaService::assinar($avaliacao, $avaliacao->servidor->user, 'avaliado');

        if ($avaliacao->status === StatusAvaliacao::NOTIFICADA) {
            $avaliacao->update(['status' => StatusAvaliacao::CONCLUIDA]);
        }

        return redirect()->route('avaliacao.publica.show', $token)->with('sucesso', 'Assinatura registrada.');
    }

    public function storeDefesa(Request $request, string $token)
    {
        $avaliacao = Avaliacao::where('token_acesso', $token)->firstOrFail();

        if (! session()->has("avaliacao_liberada_{$token}")) {
            return redirect()->route('avaliacao.publica.show', $token);
        }

        if (! $avaliacao->tokenValido()) {
            abort(410, 'Este link expirou.');
        }

        // Só pode apresentar defesa se está notificada e dentro do prazo
        if ($avaliacao->status !== StatusAvaliacao::NOTIFICADA
            && $avaliacao->status !== StatusAvaliacao::EM_DEFESA) {
            return redirect()->route('avaliacao.publica.show', $token)
                ->with('erro', 'Esta avaliação não está em período de defesa.');
        }

        if ($avaliacao->prazo_defesa && $avaliacao->prazo_defesa->isPast()) {
            return redirect()->route('avaliacao.publica.show', $token)
                ->with('erro', 'O prazo para apresentar defesa expirou.');
        }

        // Já existe defesa?
        if ($avaliacao->defesas()->exists()) {
            return redirect()->route('avaliacao.publica.show', $token)
                ->with('erro', 'Você já apresentou defesa para esta avaliação.');
        }

        $data = $request->validate([
            'texto' => 'required|string|min:20|max:5000',
            'anexo' => 'nullable|file|mimes:pdf|max:10240',
        ], [
            'texto.required' => 'O texto da defesa é obrigatório.',
            'texto.min'      => 'A defesa deve ter pelo menos 20 caracteres.',
            'anexo.mimes'    => 'O anexo deve estar em PDF.',
            'anexo.max'      => 'O anexo não pode exceder 10 MB.',
        ]);

        $path = null;
        if ($request->hasFile('anexo')) {
            $path = $request->file('anexo')->storeAs(
                'defesas/' . $avaliacao->id,
                \Illuminate\Support\Str::uuid() . '.pdf',
                'public'
            );
        }

        Defesa::create([
            'avaliacao_id'     => $avaliacao->id,
            'texto'            => $data['texto'],
            'anexo'            => $path,
            'data_protocolo'   => now(),
            'protocolado_por'  => $avaliacao->servidor->user_id,
        ]);

        $avaliacao->update(['status' => StatusAvaliacao::EM_DEFESA]);

        return redirect()->route('avaliacao.publica.show', $token)
            ->with('sucesso', 'Defesa protocolada com sucesso. A COMPAQ vai analisar.');
    }

    public function storeQualificacao(Request $request, string $token)
    {
        $avaliacao = Avaliacao::where('token_acesso', $token)->firstOrFail();

        if (! session()->has("avaliacao_liberada_{$token}")) return redirect()->route('avaliacao.publica.show', $token);
        if (! $avaliacao->tokenValido()) abort(410, 'Este link expirou.');

        $data = $request->validate([
            'titulo'        => 'required|string|max:255',
            'instituicao'   => 'nullable|string|max:255',
            'tipo'          => 'required|in:curso,palestra',
            'carga_horaria' => 'required|integer|min:4|max:1000',
            'data_inicio'   => 'required|date',
            'data_fim'      => 'required|date|after_or_equal:data_inicio',
            'arquivo'       => 'required|file|mimes:pdf|max:10240',
        ], [
            'carga_horaria.min' => 'A carga horária mínima é de 4 horas.',
            'arquivo.mimes'     => 'O arquivo deve estar em PDF.',
        ]);

        $path = $request->file('arquivo')->storeAs(
            'qualificacoes/' . $avaliacao->servidor_id,
            Str::uuid() . '.pdf',
            'public'
        );

        Qualificacao::create([
            'servidor_id'   => $avaliacao->servidor_id,
            'titulo'        => $data['titulo'],
            'instituicao'   => $data['instituicao'] ?? null,
            'tipo'          => $data['tipo'],
            'carga_horaria' => $data['carga_horaria'],
            'data_inicio'   => $data['data_inicio'],
            'data_fim'      => $data['data_fim'],
            'arquivo_pdf'   => $path,
            'status'        => 'pendente',
        ]);

        return redirect()->route('avaliacao.publica.show', $token)->with('sucesso', 'Certificado enviado.');
    }

    public function destroyQualificacao(Request $request, string $token, Qualificacao $qualificacao)
    {
        if (! session()->has("avaliacao_liberada_{$token}")) return redirect()->route('avaliacao.publica.show', $token);

        if ($qualificacao->status !== 'pendente') {
            return redirect()->route('avaliacao.publica.show', $token)->with('erro', 'Não é possível excluir um certificado já analisado.');
        }

        Storage::disk('public')->delete($qualificacao->arquivo_pdf);
        $qualificacao->delete();

        return redirect()->route('avaliacao.publica.show', $token)->with('sucesso', 'Certificado removido.');
    }
}
