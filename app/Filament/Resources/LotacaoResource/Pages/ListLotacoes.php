<?php
namespace App\Filament\Resources\LotacaoResource\Pages;
use App\Filament\Resources\LotacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListLotacoes extends ListRecords
{
    protected static string $resource = LotacaoResource::class;
    protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; }
}
