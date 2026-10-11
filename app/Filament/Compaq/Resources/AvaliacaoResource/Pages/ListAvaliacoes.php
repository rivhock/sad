<?php
namespace App\Filament\Compaq\Resources\AvaliacaoResource\Pages;
use App\Filament\Compaq\Resources\AvaliacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListAvaliacoes extends ListRecords
{
    protected static string $resource = AvaliacaoResource::class;
    protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; }
}
