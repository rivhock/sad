<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RespostaItem extends Model
{
    protected $table = 'respostas_itens';
    protected $fillable = ['avaliacao_id','item_id','descricao_item','ordem_item','nota','comentario'];
    protected $casts = ['nota'=>'decimal:2'];

    public function avaliacao() { return $this->belongsTo(Avaliacao::class); }
    public function item()      { return $this->belongsTo(ItemFormulario::class, 'item_id'); }

    /**
     * Retorna a descrição do item preservada no momento da avaliação.
     * Se não houver snapshot (avaliações antigas), cai para o texto atual do item.
     */
    public function getDescricaoAttribute(): string
    {
        return $this->descricao_item
            ?? $this->item?->descricao
            ?? '(item removido)';
    }

    public function getOrdemAttribute(): int
    {
        return $this->ordem_item ?? $this->item?->ordem ?? 0;
    }
}
