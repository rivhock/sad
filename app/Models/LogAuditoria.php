<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAuditoria extends Model
{
    protected $table = 'logs_auditoria';
    public $timestamps = false;
    protected $fillable = ['user_id','acao','entidade','entidade_id','payload','ip','user_agent','data_hora'];
    protected $casts = ['payload'=>'array','data_hora'=>'datetime'];

    public function user() { return $this->belongsTo(User::class); }
}
