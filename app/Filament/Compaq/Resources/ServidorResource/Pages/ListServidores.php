<?php
namespace App\Filament\Compaq\Resources\ServidorResource\Pages;

use App\Filament\Compaq\Resources\ServidorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListServidores extends ListRecords
{
    protected static string $resource = ServidorResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
