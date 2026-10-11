<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Qualificacao extends Model
{
    use SoftDeletes;

    protected $table = 'qualificacoes';
    protected $fillable = [
        'servidor_id','titulo','instituicao','tipo','carga_horaria',
        'data_inicio','data_fim','arquivo_pdf','status',
        'avaliado_por','data_avaliacao','observacoes',
    ];
    protected $casts = [
        'data_inicio'    => 'date',
        'data_fim'       => 'date',
        'data_avaliacao' => 'datetime',
    ];

    public function servidor()  { return $this->belongsTo(Servidor::class); }
    public function avaliador() { return $this->belongsTo(User::class, 'avaliado_por'); }

    public function getArquivoUrlAttribute(): ?string
    {
        return $this->arquivo_pdf ? Storage::disk('public')->url($this->arquivo_pdf) : null;
    }

    public function getLabelStatusAttribute(): string
    {
        return match($this->status) {
            'aprovado'  => 'Aprovado',
            'rejeitado' => 'Rejeitado',
            default     => 'Em análise',
        };
    }

    public static function tipos(): array
    {
        return [
            'curso'    => 'Curso',
            'palestra' => 'Palestra',
        ];
    }
}
