<?php
namespace App\Filament\Resources\ServidorResource\Pages;

use App\Filament\Resources\ServidorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;

class EditServidor extends EditRecord
{
    protected static string $resource = ServidorResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['user'] = $this->record->user?->only(['nome','email','cpf','matricula']);
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $userData = $data['user'] ?? [];

        if (filled($userData['password'] ?? null)) {
            $userData['password'] = Hash::make($userData['password']);
        } else {
            unset($userData['password']);
        }

        $this->record->user?->update($userData);
        unset($data['user']);
        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
