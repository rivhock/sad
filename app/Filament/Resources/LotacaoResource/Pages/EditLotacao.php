<?php
namespace App\Filament\Resources\LotacaoResource\Pages;
use App\Filament\Resources\LotacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditLotacao extends EditRecord
{
    protected static string $resource = LotacaoResource::class;
    protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; }
}
