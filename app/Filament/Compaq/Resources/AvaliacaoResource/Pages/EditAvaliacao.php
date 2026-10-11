<?php
namespace App\Filament\Compaq\Resources\AvaliacaoResource\Pages;
use App\Filament\Compaq\Resources\AvaliacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditAvaliacao extends EditRecord
{
    protected static string $resource = AvaliacaoResource::class;
    protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; }
    protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
}
