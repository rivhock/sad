<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Lotacao extends Model
{
    use LogsActivity;

    protected $table = 'lotacoes';

    protected $fillable = ['nome','secretaria_id','ativo'];
    protected $casts = ['ativo' => 'boolean'];

    public function secretaria() { return $this->belongsTo(Secretaria::class); }
    public function servidores() { return $this->hasMany(Servidor::class); }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

}
