<?php
namespace App\Filament\Avaliador\Resources\AvaliacaoResource\Pages;

use App\Filament\Avaliador\Resources\AvaliacaoResource;
use Filament\Resources\Pages\ListRecords;

class ListAvaliacoes extends ListRecords
{
    protected static string $resource = AvaliacaoResource::class;

    public function getTitle(): string
    {
        return 'Avaliações atribuídas a mim';
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
