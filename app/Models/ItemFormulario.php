<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ItemFormulario extends Model
{
    use LogsActivity;

    protected $table = 'itens_formulario';
    protected $fillable = ['formulario_id','ordem','descricao','peso','nota_min','nota_max'];
    protected $casts = ['peso'=>'decimal:2','nota_min'=>'decimal:2','nota_max'=>'decimal:2'];

    public function formulario() { return $this->belongsTo(Formulario::class); }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

}
