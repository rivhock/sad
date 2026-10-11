<?php
namespace App\Filament\Resources\TipoAvaliacaoResource\Pages;
use App\Filament\Resources\TipoAvaliacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListTipoAvaliacoes extends ListRecords
{
    protected static string $resource = TipoAvaliacaoResource::class;
    protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; }
}
