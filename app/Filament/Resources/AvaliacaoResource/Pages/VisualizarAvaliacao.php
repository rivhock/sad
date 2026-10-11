<?php
namespace App\Filament\Resources\AvaliacaoResource\Pages;

use App\Enums\StatusAvaliacao;
use App\Filament\Resources\AvaliacaoResource;
use App\Models\Avaliacao;
use App\Services\AssinaturaService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

class VisualizarAvaliacao extends Page
{
    protected static string $resource = AvaliacaoResource::class;
    protected static string $view = 'filament.resources.avaliacao-resource.pages.visualizar-avaliacao';

    public Avaliacao $record;

    public function mount(Avaliacao $record): void
    {
        $this->record = $record->load([
            'servidor.user','avaliador','ciclo','formulario.itens',
            'respostas.item','assinaturas.user','defesas',
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('voltar')
                ->label('Voltar')->color('gray')
                ->url(AvaliacaoResource::getUrl('index')),

            Action::make('assinar_avaliado')
                ->label('Assinar como Avaliado')
                ->color('success')
                ->icon('heroicon-o-pencil')
                ->visible(fn () =>
                    in_array($this->record->status, [StatusAvaliacao::NOTIFICADA, StatusAvaliacao::EM_DEFESA, StatusAvaliacao::CONCLUIDA])
                    && ! $this->record->assinaturas->where('tipo_assinante','avaliado')->count()
                    && auth()->id() === $this->record->servidor?->user_id
                )
                ->requiresConfirmation()
                ->modalDescription('Ao assinar, você declara ciência da avaliação. Esta ação será registrada com hash e IP.')
                ->action('assinarComoAvaliado'),

            Action::make('assinar_compaq')
                ->label('Assinar como COMPAQ')
                ->color('info')
                ->icon('heroicon-o-shield-check')
                ->visible(fn () => (auth()->user()?->hasRole('COMPAQ') || auth()->user()?->hasRole('Administrador'))
                    && ! $this->record->assinaturas->where('tipo_assinante','compaq')->count())
                ->requiresConfirmation()
                ->action('assinarComoCompaq'),
        ];
    }

    public function assinarComoAvaliado(): void
    {
        AssinaturaService::assinar($this->record, auth()->user(), 'avaliado');
        if ($this->record->status === StatusAvaliacao::NOTIFICADA) {
            $this->record->update(['status' => StatusAvaliacao::CONCLUIDA]);
        }
        Notification::make()->title('Assinatura registrada')->success()->send();
        $this->record->refresh()->load('assinaturas.user');
    }

    public function assinarComoCompaq(): void
    {
        AssinaturaService::assinar($this->record, auth()->user(), 'compaq');
        if ($this->record->status !== StatusAvaliacao::CONCLUIDA) {
            $this->record->update(['status' => StatusAvaliacao::CONCLUIDA]);
        }
        Notification::make()->title('Assinatura da COMPAQ registrada')->success()->send();
        $this->record->refresh()->load('assinaturas.user');
    }

    public function getTitle(): string
    {
        return 'Avaliação — ' . ($this->record->servidor?->user?->nome ?? '');
    }

    public function getBreadcrumbs(): array
    {
        return [AvaliacaoResource::getUrl('index') => 'Avaliações', 'Visualizar'];
    }
}
