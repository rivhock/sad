<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrupoAvaliacao extends Model
{
    protected $table = 'grupos_avaliacao';
    protected $fillable = ['nome','ciclo_id','descricao','ativo'];
    protected $casts = ['ativo'=>'boolean'];

    public function ciclo() { return $this->belongsTo(CicloAvaliacao::class, 'ciclo_id'); }
    public function servidores() { return $this->belongsToMany(Servidor::class, 'grupo_servidor', 'grupo_id', 'servidor_id'); }
}
