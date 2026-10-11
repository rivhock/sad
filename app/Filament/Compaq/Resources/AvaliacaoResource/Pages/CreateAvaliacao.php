<?php
namespace App\Filament\Compaq\Resources\AvaliacaoResource\Pages;
use App\Filament\Compaq\Resources\AvaliacaoResource;
use Filament\Resources\Pages\CreateRecord;
class CreateAvaliacao extends CreateRecord
{
    protected static string $resource = AvaliacaoResource::class;
    protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
}
