<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Defesa extends Model
{
    protected $table = 'defesas';
    protected $fillable = [
        'avaliacao_id','texto','data_protocolo','anexo','protocolado_por',
        'parecer_decisao','parecer_texto','parecer_nota_anterior','parecer_nota_nova',
        'parecer_em','parecer_por',
    ];
    protected $casts = [
        'data_protocolo'        => 'datetime',
        'parecer_em'            => 'datetime',
        'parecer_nota_anterior' => 'decimal:2',
        'parecer_nota_nova'     => 'decimal:2',
    ];

    public function avaliacao()    { return $this->belongsTo(Avaliacao::class); }
    public function protocolante() { return $this->belongsTo(User::class, 'protocolado_por'); }
    public function parecerista()  { return $this->belongsTo(User::class, 'parecer_por'); }

    public function getAnexoUrlAttribute(): ?string
    {
        return $this->anexo ? Storage::disk('public')->url($this->anexo) : null;
    }

    public function temParecer(): bool
    {
        return ! is_null($this->parecer_decisao);
    }

    public function getParecerLabelAttribute(): string
    {
        return match ($this->parecer_decisao) {
            'deferida'              => 'Deferida',
            'parcialmente_deferida' => 'Parcialmente deferida',
            'indeferida'            => 'Indeferida',
            'pad'                   => 'Encaminhamento para PAD',
            default                 => 'Sem parecer',
        };
    }

    public function getParecerCorAttribute(): string
    {
        return match ($this->parecer_decisao) {
            'deferida'              => 'success',
            'parcialmente_deferida' => 'info',
            'indeferida'            => 'warning',
            'pad'                   => 'danger',
            default                 => 'gray',
        };
    }

    public static function decisoes(): array
    {
        return [
            'deferida'              => 'Deferida (aceita a defesa)',
            'parcialmente_deferida' => 'Parcialmente deferida',
            'indeferida'            => 'Indeferida (mantém nota)',
            'pad'                   => 'Encaminhamento para PAD',
        ];
    }
}
