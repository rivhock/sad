<?php
namespace App\Filament\Compaq\Resources\AvaliacaoResource\Pages;

use App\Enums\StatusAvaliacao;
use App\Filament\Compaq\Resources\AvaliacaoResource;
use App\Models\Avaliacao;
use App\Services\AssinaturaService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

class VisualizarAvaliacao extends Page
{
    protected static string $resource = AvaliacaoResource::class;
    protected static string $view = 'filament.compaq.pages.visualizar-avaliacao';

    public Avaliacao $record;

    public function mount(Avaliacao $record): void
    {
        $this->record = $record->load([
            'servidor.user','servidor.cargo','servidor.secretaria','servidor.lotacao',
            'avaliador','ciclo','formulario.itens',
            'respostas.item','assinaturas.user','defesas','qualificacoes',
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('voltar')
                ->label('Voltar')
                ->color('gray')
                ->url(AvaliacaoResource::getUrl('index')),

            Action::make('assinar_compaq')
                ->label('Assinar COMPAQ')
                ->icon('heroicon-o-shield-check')
                ->color('info')
                ->visible(fn () =>
                    ! $this->record->assinaturas->where('tipo_assinante', 'compaq')->count()
                    && $this->record->assinaturas->where('tipo_assinante', 'chefia')->count() > 0
                )
                ->requiresConfirmation()
                ->modalHeading('Assinar como membro da COMPAQ')
                ->modalDescription('Ao assinar, esta avaliação será homologada definitivamente. A ação será registrada com data, hora, IP e hash.')
                ->action('assinarComoCompaq'),

            Action::make('pdf')
                ->label('Baixar PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->url(fn () => route('avaliacao.pdf.download', $this->record))
                ->openUrlInNewTab(),

            Action::make('editar')
                ->label('Editar')
                ->icon('heroicon-o-pencil-square')
                ->color('warning')
                ->url(fn () => AvaliacaoResource::getUrl('edit', ['record' => $this->record])),
        ];
    }

    public function assinarComoCompaq(): void
    {
        if ($this->record->assinaturas()->where('tipo_assinante', 'compaq')->exists()) {
            Notification::make()
                ->title('Já homologada')
                ->body('Esta avaliação já foi assinada pela COMPAQ.')
                ->warning()
                ->send();
            return;
        }

        if (! $this->record->assinaturas()->where('tipo_assinante', 'chefia')->exists()) {
            Notification::make()
                ->title('Não é possível homologar')
                ->body('A chefia ainda não assinou esta avaliação.')
                ->danger()
                ->send();
            return;
        }

        AssinaturaService::assinar($this->record, auth()->user(), 'compaq');

        if ($this->record->status !== StatusAvaliacao::CONCLUIDA) {
            $this->record->update(['status' => StatusAvaliacao::CONCLUIDA]);
        }

        Notification::make()
            ->title('Avaliação homologada')
            ->body('Assinatura da COMPAQ registrada com sucesso.')
            ->success()
            ->send();

        // Recarrega a página com os dados atualizados
        $this->record->refresh()->load('assinaturas.user');
    }

    public function getTitle(): string
    {
        return 'Avaliação — ' . ($this->record->servidor?->user?->nome ?? '');
    }

    public function getBreadcrumbs(): array
    {
        return [
            AvaliacaoResource::getUrl('index') => 'Avaliações',
            'Visualizar',
        ];
    }
}
