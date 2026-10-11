<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Formulario extends Model
{
    use LogsActivity;

    protected $fillable = ['titulo','versao','schema_json','calculo','ativo'];
    protected $casts = ['schema_json'=>'array','ativo'=>'boolean'];

    public function itens() { return $this->hasMany(ItemFormulario::class)->orderBy('ordem'); }
    public function avaliacoes() { return $this->hasMany(Avaliacao::class); }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

}
