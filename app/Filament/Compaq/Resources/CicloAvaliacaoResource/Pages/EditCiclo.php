<?php
namespace App\Filament\Compaq\Resources\CicloAvaliacaoResource\Pages;
use App\Filament\Compaq\Resources\CicloAvaliacaoResource;
use Filament\Resources\Pages\EditRecord;
class EditCiclo extends EditRecord
{
    protected static string $resource = CicloAvaliacaoResource::class;
    protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
}
