<?php
namespace App\Filament\Resources\ServidorResource\Pages;

use App\Filament\Resources\ServidorResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateServidor extends CreateRecord
{
    protected static string $resource = ServidorResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $userData = $data['user'] ?? [];
        $userData['password'] = Hash::make($userData['password'] ?? 'mudar123');
        $user = User::create($userData);
        $data['user_id'] = $user->id;
        unset($data['user']);
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
