<?php
namespace App\Models;

use App\Enums\StatusAvaliacao;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Avaliacao extends Model
{
    protected $table = 'avaliacoes';
    protected $fillable = [
        'token_acesso','token_expira_em',
        'token_avaliador','token_avaliador_expira_em',
        'ciclo_id','formulario_id','servidor_id','avaliador_id','tipo','status',
        'periodo_inicio','periodo_fim','data_avaliacao','nota_total','observacoes',
        'data_notificacao','prazo_defesa',
    ];
    protected $casts = [
        'periodo_inicio'             => 'date',
        'periodo_fim'                => 'date',
        'data_avaliacao'             => 'datetime',
        'data_notificacao'           => 'datetime',
        'prazo_defesa'               => 'datetime',
        'token_expira_em'            => 'datetime',
        'token_avaliador_expira_em'  => 'datetime',
        'nota_total'                 => 'decimal:2',
        'status'                     => StatusAvaliacao::class,
    ];

    protected static function booted(): void
    {
        static::creating(function (self $avaliacao) {
            if (empty($avaliacao->token_acesso)) {
                $avaliacao->token_acesso = Str::random(64);
                $avaliacao->token_expira_em = now()->addDays(30);
            }
        });
    }

    public function ciclo()      { return $this->belongsTo(CicloAvaliacao::class, 'ciclo_id'); }
    public function formulario() { return $this->belongsTo(Formulario::class); }
    public function servidor()   { return $this->belongsTo(Servidor::class); }
    public function avaliador()  { return $this->belongsTo(User::class, 'avaliador_id'); }
    public function respostas()  { return $this->hasMany(RespostaItem::class); }
    public function defesas()    { return $this->hasMany(Defesa::class); }
    public function assinaturas(){ return $this->hasMany(Assinatura::class); }
    public function qualificacoes() { return $this->hasMany(Qualificacao::class, 'servidor_id', 'servidor_id'); }

    public function lotes()
    {
        return $this->belongsToMany(
            LoteAvaliador::class,
            'avaliacao_lote',
            'avaliacao_id',
            'lote_id'
        )->withPivot(['preenchida_em','assinada_em'])->withTimestamps();
    }

    public function assinadaPelaChefia(): bool
    {
        return $this->assinaturas()->where('tipo_assinante', 'chefia')->exists();
    }

    public function getLinkAvaliadoAttribute(): string
    {
        return url('/avaliacao/' . $this->token_acesso);
    }

    public function getLinkAvaliadorExternoAttribute(): ?string
    {
        return $this->token_avaliador
            ? url('/avaliador-externo/' . $this->token_avaliador)
            : null;
    }

    public function tokenValido(): bool
    {
        return $this->token_acesso
            && (is_null($this->token_expira_em) || $this->token_expira_em->isFuture());
    }

    public function tokenAvaliadorValido(): bool
    {
        return $this->token_avaliador
            && (is_null($this->token_avaliador_expira_em) || $this->token_avaliador_expira_em->isFuture());
    }

    /**
     * Gera (ou regenera) o token do avaliador externo por mais 30 dias.
     */
    public function gerarTokenAvaliador(int $dias = 30): void
    {
        $this->update([
            'token_avaliador' => Str::random(64),
            'token_avaliador_expira_em' => now()->addDays($dias),
        ]);
    }
}
