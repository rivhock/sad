<?php
namespace App\Models;

use App\Enums\CategoriaServidor;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Cargo extends Model
{
    use LogsActivity;

    protected $fillable = ['nome','categoria','ativo'];
    protected $casts = ['ativo' => 'boolean', 'categoria' => CategoriaServidor::class];

    public function servidores() { return $this->hasMany(Servidor::class); }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

}
