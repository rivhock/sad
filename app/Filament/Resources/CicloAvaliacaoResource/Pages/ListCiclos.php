<?php
namespace App\Filament\Resources\CicloAvaliacaoResource\Pages;
use App\Filament\Resources\CicloAvaliacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListCiclos extends ListRecords
{
    protected static string $resource = CicloAvaliacaoResource::class;
    protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; }
}
