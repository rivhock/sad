<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assinatura extends Model
{
    protected $table = 'assinaturas';
    protected $fillable = [
        'avaliacao_id','user_id','nome_avaliador_externo','cpf_avaliador_externo',
        'tipo_assinante','hash','ip','assinado_em',
    ];
    protected $casts = ['assinado_em'=>'datetime'];

    public function avaliacao() { return $this->belongsTo(Avaliacao::class); }
    public function user()      { return $this->belongsTo(User::class); }

    public function getNomeAssinanteAttribute(): string
    {
        return $this->user?->nome ?? $this->nome_avaliador_externo ?? 'Desconhecido';
    }

    public function isExterno(): bool
    {
        return $this->user_id === null && $this->nome_avaliador_externo !== null;
    }
}
