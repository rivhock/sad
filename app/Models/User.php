<?php
namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasName
{
    use Notifiable, HasRoles, LogsActivity;

    protected $fillable = ['nome','email','cpf','matricula','password','ativo'];
    protected $hidden = ['password','remember_token'];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'ativo' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->logExcept(['password']);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if (! $this->ativo) {
            return false;
        }

        return match ($panel->getId()) {
            'admin'     => $this->hasAnyRole(['Administrador', 'RH']),
            'compaq'    => $this->hasAnyRole(['COMPAQ', 'Administrador']),
            'avaliador' => $this->hasAnyRole(['Avaliador', 'Administrador']),
            default     => false,
        };
    }

    public function getFilamentName(): string
    {
        return $this->nome ?? $this->email;
    }

    public function servidor() { return $this->hasOne(Servidor::class); }
    public function avaliacoesComoAvaliador() { return $this->hasMany(Avaliacao::class, 'avaliador_id'); }
}
