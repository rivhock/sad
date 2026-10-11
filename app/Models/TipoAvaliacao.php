<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoAvaliacao extends Model
{
    protected $table = 'tipo_avaliacoes';
    protected $fillable = ['codigo','nome','anexo','categoria','ativo'];
    protected $casts = ['ativo' => 'boolean'];
}
