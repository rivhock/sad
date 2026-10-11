<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Secretaria extends Model
{
    use LogsActivity;

    protected $fillable = ['nome','sigla','ativo'];
    protected $casts = ['ativo' => 'boolean'];

    public function lotacoes() { return $this->hasMany(Lotacao::class); }
    public function servidores() { return $this->hasMany(Servidor::class); }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

}
