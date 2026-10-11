<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LoteAvaliador extends Model
{
    protected $table = 'lotes_avaliador';
    protected $fillable = [
        'token', 'nome_avaliador', 'cpf_avaliador',
        'identificado_em', 'expira_em', 'observacoes',
    ];
    protected $casts = [
        'identificado_em' => 'datetime',
        'expira_em'       => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $lote) {
            if (empty($lote->token)) {
                $lote->token = Str::random(64);
            }
            if (empty($lote->expira_em)) {
                $lote->expira_em = now()->addDays(30);
            }
        });
    }

    public function avaliacoes()
    {
        return $this->belongsToMany(
            Avaliacao::class,
            'avaliacao_lote',
            'lote_id',
            'avaliacao_id'
        )->withPivot(['preenchida_em','assinada_em'])->withTimestamps();
    }

    public function getLinkAttribute(): string
    {
        return url('/avaliador-externo/lote/' . $this->token);
    }

    public function getTotalAttribute(): int
    {
        return $this->avaliacoes()->count();
    }

    public function getPendentesAttribute(): int
    {
        return $this->avaliacoes()
            ->whereDoesntHave('assinaturas', fn ($q) => $q->where('tipo_assinante', 'chefia'))
            ->count();
    }

    public function getAssinadasAttribute(): int
    {
        return $this->avaliacoes()
            ->whereHas('assinaturas', fn ($q) => $q->where('tipo_assinante', 'chefia'))
            ->count();
    }

    public function identificado(): bool
    {
        return $this->identificado_em !== null
            && $this->nome_avaliador
            && $this->cpf_avaliador;
    }

    public function valido(): bool
    {
        return is_null($this->expira_em) || $this->expira_em->isFuture();
    }
}
