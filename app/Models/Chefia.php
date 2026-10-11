<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Chefia extends Model
{
    use LogsActivity;

    protected $table = 'chefias';
    protected $fillable = ['servidor_id','chefe_id','inicio','fim','ativo'];
    protected $casts = ['inicio'=>'date','fim'=>'date','ativo'=>'boolean'];

    public function servidor() { return $this->belongsTo(Servidor::class, 'servidor_id'); }
    public function chefe()    { return $this->belongsTo(Servidor::class, 'chefe_id'); }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

}
