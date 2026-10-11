<?php
namespace App\Filament\Compaq\Resources\FormularioResource\Pages;
use App\Filament\Compaq\Resources\FormularioResource;
use Filament\Resources\Pages\EditRecord;
class EditFormulario extends EditRecord
{
    protected static string $resource = FormularioResource::class;
    protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
}
