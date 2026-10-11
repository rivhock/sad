<?php
namespace App\Models;

use App\Enums\CategoriaServidor;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Servidor extends Model
{
    use LogsActivity;

    use SoftDeletes;

    protected $table = 'servidores';
    protected $fillable = [
        'user_id','cargo_id','lotacao_id','secretaria_id',
        'categoria','classe','data_admissao','data_fim_estagio','ativo',
    ];
    protected $casts = [
        'data_admissao'    => 'date',
        'data_fim_estagio' => 'date',
        'ativo'            => 'boolean',
        'categoria'        => CategoriaServidor::class,
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function cargo() { return $this->belongsTo(Cargo::class); }
    public function lotacao() { return $this->belongsTo(Lotacao::class); }
    public function secretaria() { return $this->belongsTo(Secretaria::class); }
    public function chefias() { return $this->hasMany(Chefia::class, 'servidor_id'); }
    public function chefes()  { return $this->hasMany(Chefia::class, 'chefe_id'); }
    public function avaliacoes() { return $this->hasMany(Avaliacao::class); }
    public function qualificacoes() { return $this->hasMany(Qualificacao::class); }
    public function grupos() { return $this->belongsToMany(GrupoAvaliacao::class, 'grupo_servidor', 'servidor_id', 'grupo_id'); }

    public function getNomeAttribute(): ?string { return $this->user?->nome; }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

}
