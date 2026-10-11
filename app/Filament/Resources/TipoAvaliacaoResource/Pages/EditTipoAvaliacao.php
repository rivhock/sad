<?php
namespace App\Filament\Resources\TipoAvaliacaoResource\Pages;
use App\Filament\Resources\TipoAvaliacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditTipoAvaliacao extends EditRecord
{
    protected static string $resource = TipoAvaliacaoResource::class;
    protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; }
}
