<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CicloAvaliacao extends Model
{
    use LogsActivity;

    protected $table = 'ciclos_avaliacao';
    protected $fillable = ['nome','data_inicio','data_fim','ativo'];
    protected $casts = ['data_inicio'=>'date','data_fim'=>'date','ativo'=>'boolean'];

    public function avaliacoes() { return $this->hasMany(Avaliacao::class, 'ciclo_id'); }
    public function grupos() { return $this->hasMany(GrupoAvaliacao::class, 'ciclo_id'); }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

}
