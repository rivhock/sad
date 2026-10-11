<?php
namespace App\Filament\Resources\CicloAvaliacaoResource\Pages;
use App\Filament\Resources\CicloAvaliacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditCiclo extends EditRecord
{
    protected static string $resource = CicloAvaliacaoResource::class;
    protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; }
}
